<?php
/**
 * CiviCRM Calendar Joomla Module
 *
 * @copyright    Copyright (C) 2005-2011 Open Source Matters. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.html.html');



if ( $displayParams['modal'] == "1" ) {
	jimport( 'joomla.html.html.behavior' );
	JHtmlBehavior::modal();


	// we need a place holder for the link
	// we will put that in a hidden div of 0 pixels in height
	echo 
	  "<div ".  
	  "id=\"mod_civicrm_fullcalendar_dialog\" class=\"modal\" title=\"\">".
	  "<a id=\"aref_mod_civicrm_fullcalendar_dialog\" class=\"modal\" ".
	  "href=\"\" ".
	  "rel=\"{handler: 'iframe', size: {x: 520, y: 400}}\"></a>".
	  "</div>";
}
?>


<?php






$document =& JFactory::getDocument();


$document->addStyleSheet(JURI::base() .
	'modules/mod_civicrm_fullcalendar/fullcalendar/demos/cupertino/theme.css', 
	'text/css', 'screen');
$document->addStyleSheet(JURI::base() .
	'modules/mod_civicrm_fullcalendar/elements/legend.css', 
	'text/css', 'screen');
$document->addStyleSheet(JURI::base() . 
	'modules/mod_civicrm_fullcalendar/fullcalendar/fullcalendar/fullcalendar.css', 
	'text/css', 'screen');
$document->addStyleSheet(JURI::base() .	
	'modules/mod_civicrm_fullcalendar/fullcalendar/fullcalendar/fullcalendar.print.css', 
	'text/css', 'print');
?>

<?php





// call FullCalendar's javascript
// ====================================================
$statement = <<<'JSJS'


var $cfcj = jQuery.noConflict();

	$cfcj(document).ready(function() {

	$cfcj('#calendar').fullCalendar({

JSJS;
// ====================================================




// Add some more parameters:
$statement .= "\t\tdefaultView: '".$displayParams['defalt_view']."', \n" ;
$statement .= "\t\tweekMode: '".$displayParams['weekMode']."', \n" ;
$statement .= "\t\tfirstHour: ".intval($displayParams['firstHour']).", \n" ;
$statement .= "\t\tslotMinutes: ".intval($displayParams['slotMinutes']).", \n" ;
$statement .= "\t\taspectRatio: ".$displayParams['aspectRatio'].", \n" ;
$statement .= "\t\taxisFormat: '".$displayParams['axisFormat']."', \n" ;

if ($displayParams[showCalNav] === "1") {
	$statement .=
	"\t\t	header: { \n".
	"\t\t\t	left: 'prev,next today', \n".
	"\t\t\t	center: 'title', \n".
	"\t\t\t	right: 'month,agendaWeek,agendaDay' \n".
	"\t\t	},	\n" ;
}
else {
	$statement .=
	"\t\t	header: { \n".
	"\t\t\t	left: 'false', \n".
	"\t\t\t	center: 'false', \n".
	"\t\t\t	right: 'false' \n".
	"\t\t	},	\n" ;
}

// more paramaters
// ====================================================
$statement .= <<<'JSJS'
		theme: true,
		timeFormat:
			{
			    // for agendaWeek and agendaDay
			    agenda: 'h:mm{ - h:mm}', // 5:00 - 6:30

			    // for all other views
			    '': 'h:mm TT'            // 7p
			},
		editable: false,
		events: [

JSJS;
// ====================================================


$debug = 0;

if ($debug) {
	echo '<ul class="civieventlist">';
}

//color themes
$theme = $displayParams['colorpicker'];
$legend_picked = $displayParams['legendpicker'];
$customcolorsstr = $displayParams['customcolors'];

if ($theme == 1) {
	$color = array("#FF007F", "#00CCAA", "#14B5CC", "#51B207", "#FFBA14", "#CA5EFF");
} elseif ($theme == 2) {
	$color = array("#2672EC", "#8C0095", "#AC193D", "#008299", "#D24726", "#008A00");
} elseif ($theme == 3) {
	$color = array("blue", "blue", "blue", "blue", "blue", "blue");
} elseif ($theme == 4) {
	$color = explode(" ", $customcolorsstr);
} else {
	$color = array("blue", "green", "red", "purple", "orange", "gray");
}

// Set number of records to be displayed and clear counter
$maxevents = ($displayParams['maxevents']) ? $displayParams['maxevents'] : 10;

$x = 1;

$query = "SELECT civicrm_option_value.value, civicrm_option_value.label
			FROM civicrm_option_value
			INNER JOIN
			civicrm_option_group
			ON (civicrm_option_value.option_group_id = civicrm_option_group.id)
			WHERE (civicrm_option_group.name = 'event_type')
			AND (civicrm_option_value.is_active = '1')";
$db =& JFactory::getDBO();
$db->setQuery($query);
$result = $db->loadObjectList();


if ($result === null) {
	JError::raiseWarning(1002, $db->getErrorMsg());
}



$column = array();


$lines = $db->loadAssocList();
foreach($lines as $row) {
	$categories[] = $row['value'];
	$catname[] = $row['label'];
}

foreach ($eventtitles as &$event) {

	if ($x > $maxevents) {
		return;
	} else {
		$x++;
	}

	$baselink = 'index.php/component/civicrm/?task=civicrm/event/';

	for ($i = 0, $n = count($event->title); ($i < $n); $i++) {
			
		if( $displayParams['modal'] == "1" ) {
			// modallink
			$link =  $baselink.'info&reset=1&id='.$event->eventID.'&tmpl=component' ;

		}
		else {
			$link = JRoute::_($baselink . 'info&reset=1&id=' . $event->eventID);

		}


		$registernow = JRoute::_( $baselink.'register&reset=1&id='.$event->eventID );

		$datetime = strtotime($event->start_date);
		//$mysqldate = date("m/d/y g:i A", $datetime);
		$sd = date('j', $datetime);
		$sm = date('n', $datetime);
		$sm = $sm - 1; // why do I need to decrement the month?
		$sy = date('Y', $datetime);
		$shr = date('G', $datetime);
		$smin = date('i', $datetime);
		$ssec = date('s', $datetime);


		$datetime = strtotime($event->end_date);
		//$mysqldate = date("m/d/y g:i A", $datetime);
		$ed = date('j', $datetime);
		$em = date('n', $datetime);
		$em = $em - 1; //	why do I need to decrement the month?
		$ey = date('Y', $datetime);
		$ehr = date('G', $datetime);
		$emin = date('i', $datetime);
		$esec = date('s', $datetime);

		//duration and single/multi checker
		//also sets color for single/multi
		$duration = "";
		if ($ed == $sd && $em == $sm && $ey == $sy) {
			//single day
			$eventcolor = $color[0];
			if ($ehr - $shr == 1) {
				$duration .= ($ehr - $shr) . " hour";
			} elseif ($ehr - $shr > 1) {
				$duration .= ($ehr - $shr) . " hours";
			}
			if ($emin - $smin == 1) {
				$duration .= ", " . ($emin - $smin) . " minute";
			} elseif ($emin - $smin > 1) {
				$duration .= ", " . ($emin - $smin) . " minutes";
			}
		} else {
			//multi-day
			$eventcolor = $color[1];
			$allday = true;
			if ($ed - $sd == 1) {
				$duration .= ($ed - $sd) . " day";
			} elseif ($ed - $sd > 1) {
				$duration .= ($ed - $sd) . " days";
			}
			if ($ehr - $shr == 1) {
				$duration .= ", " . ($ehr - $shr) . " hour";
			} elseif ($ehr - $shr > 1) {
				$duration .= ", " . ($ehr - $shr) . " hours";
			}
			if ($emin - $smin == 1) {
				$duration .= ", " . ($emin - $smin) . " minute";
			} elseif ($emin - $smin > 1) {
				$duration .= ", " . ($emin - $smin) . " minutes";
			}
		} // else

		$statement .=
		"{ " .
		"id: '$event->id ', ";
		$color1 = $displayParams['color1'];


		// adds title, summary and duration according to settings
		// let's encode the title
		// but using the json encode function adds equals signs
		// so lets remove that
		// and then perfrom a trim on the final result
		$e_title = trim(trim(json_encode(($event->title))), "\"");
		// apostrophes still cause a problem after the
		// json_encode.  So we will do a replace (escape) on that
		// see this for help: http://www.the-art-of-web.com/javascript/escape/
		$e_title = preg_replace("/\\'/", "\\\'", $e_title);
		// enocde this string as civicrm allows new lines in the
		// summary field and these are represented as \n\r
		$e_summary = trim(trim(json_encode(($event->summary))), "\"");
		// escape out the apostrophes
		$e_summary = preg_replace("/\\'/", "\\\'", $e_summary);
		// catch prevent a null string that literally says null
		if ($e_summary == "null") {
			$e_summary = "";
		} else {
			// we'll add the hypen here so the there is not an extra
			// hypen in the case that there is no event summary text
			// and the conditions for the second elsif evaluate to true
			$e_summary = " - " . $e_summary;
		}


		if ($displayParams['showduration'] && $displayParams['summary']) {
			$statement .= "title: '$e_title - $e_summary - $duration', ";
		} elseif ($displayParams['showduration']) {
			$statement .= "title: '$e_title - $duration', ";
		} elseif ($displayParams['summary']) {
			$statement .= "title: '$e_title $e_summary', ";
		} else {
			$statement .= "title: '$e_title', ";
		}

		//sets category colors
		if ($displayParams['color'] == 1) {
			$eventid = $event->event_type_id;
			$key = array_search($eventid, $categories);
			//    $eventcolor = $color[$key];


			// map event type ids to the colors
			$my_color_key_array = array_keys($categories,$event->event_type_id);
			$eventcolor = $color[$my_color_key_array[0]];
		}




		$statement .=
		"start: new Date($sy, $sm, $sd), " .
		"end: new Date($ey, $em, $ed), " .
		"color: '$eventcolor', " ;
		
		if ( $displayParams[useHighContrast] == true) {
		  $statement .= "textColor: '#".modCiviCRMFullCalendarHelper::getHighContrastColor($eventcolor)."', " ;
		}
		else { 
		  $statement .= "textColor: '".$displayParams[eventTextColor]."', " ;
		}
		
		$statement .=
		"allDay: '$allday', " .
		"url: '$link' " .
		"}";

		// need the comma, but not for the last event, of course
		if ($i < $n) {
            $statement .= ",";
        }
	}
}


$statement .= "]";



// ====================================================
// if we need modal dialog boxes for the events
// ====================================================
if ( $displayParams['modal'] == "1" ) {
	$statement .= <<<'JSJS'
,
eventClick: function(event) {
        if (event.url) {
		$cfcj('a[id$="aref_mod_civicrm_fullcalendar_dialog"]').attr("href", event.url   );
		$('aref_mod_civicrm_fullcalendar_dialog' ).click() ;
	return false;
        } // if
} // eventClick: function(event)

JSJS;
	// ====================================================
} // if ( $displayParams['modal'] == "1" ) {





$statement .= <<<'JSJS'
      });
  });
JSJS;
// ====================================================





$document->addScript(JURI::base()
    . 'modules/mod_civicrm_fullcalendar/fullcalendar/jquery/jquery-1.8.1.min.js');

$document->addScript(JURI::base()
    . 'modules/mod_civicrm_fullcalendar/fullcalendar/jquery/jquery-ui-1.8.23.custom.min.js');


$document->addScript(JURI::base()
    . 'modules/mod_civicrm_fullcalendar/fullcalendar/fullcalendar/fullcalendar.min.js');


// This will add the javascript call for FullCalendar to the <HEAD>
//  refer to -> http://arshaw.com/fullcalendar/docs/usage/
$document->addScriptDeclaration($statement);
?>

<!-- this will add the FullCalendar dynamically inside the div below -->
<div id='mod_civicrm_fullcalendar'>
	<div id='calendar'></div>
	<?PHP





	if ($legend_picked != "0") {

	$legendLabel = trim($displayParams['legendLabel']) ;

	
	
    if ($legend_picked == "1") {
        echo "<div id='mod_civicrm_fullcalendar_legend'>";


        echo "<table id='mod_civicrm_fullcalendar_colorlegend'>".
          "<tr><th>".$legendLabel."</th></tr>";
        for ($i = 0, $n = count($catname); ($i < $n); $i++) {
            echo 	"<tr><td>".
            		"<span id='colorsquare' ".
            		"style='";

            
            if ( $displayParams[useHighContrast] == true) {
            	echo "color: #".modCiviCRMFullCalendarHelper::getHighContrastColor($color[$i])."; ";
            }
            else {
            	echo "color: ".$displayParams[eventTextColor]."; ";
            }           
            
            
            echo
              		"text-align:center; background-color:" .
              		$color[$i] . "'>" . $color[$i] . "</span><span class='text'>" .
              		$catname[$i] ."</span></td></tr>";
        }
        echo "</table>";
        } //   if ($legend_picked == "1")


        

        
        





    if  ( ($legend_picked == "2") ||  ($legend_picked == "3") ) {
    	$legendOutput  = "<div id='mod_civicrm_fullcalendar_legend' >";

    	    	 
    	// how many boexs?
    	$num_o_boxes = count($catname) ;
    	
    	// one more box to say 'legend'
    	// if there is legend text that is not null
    	$legend_offset = 0 ;
    	if ( $legendLabel != null) {
    		$num_o_boxes++;
    		$legend_offset = 1 ;
    	}
    	
		$legendOutput .=  "<div id=\"mod_civicrm_fullcalendar_legend_wrapper\"  >";
		for ($i = 0, $n = $num_o_boxes; ($i < $n); $i++) {
			$legendOutput .= "<div class=\"mod_civicrm_fullcalendar_legend_cell\" "; 
			$legendOutput .= "style=\""; 
		
			
			// if this is not the 0th row, put in the color
			// or if this is the 0th row and we don't have legend text, then put in the color
			if (  ( $i > 0) || (  ( $i == 0) && ( $legendLabel == null) )) {
				$legendOutput .= "background-color: ".$color[$i-$legend_offset]."; ";
				
				if ( $displayParams[useHighContrast] == true) {
					$legendOutput .= "color: #".modCiviCRMFullCalendarHelper::getHighContrastColor($color[$i-$legend_offset])."; ";
				}
				else {
					$legendOutput .= "color: ".$displayParams[eventTextColor]."; ";
				}										
			}
			// if this is 0th row we have legend text, then spit out the legend text
			else if ( ( $i == 0) && ( $legendLabel != null) ) {
				$legendOutput .= "color: black;";
			}			
			
			if ( ($legend_picked == "2") ) {
			$legendOutput .= "width:".
					substr_replace( 
						sprintf("%.3f", ((1/$num_o_boxes)*(100 - ($num_o_boxes*.25 )  ) ) )
						,"",-1
					).
					"%;";
			} else {
				$legendOutput .= "width: 280px;" ;
			}
			$legendOutput .= "\">";
					
			
						
			// if this is not the 0th row, put in the color
			// or if this is the 0th row and we don't have legend text, then put in the color
			if (  ( $i > 0) || (  ( $i == 0) && ( $legendLabel == null) )) {
				$legendOutput .= $catname[$i-$legend_offset] ;
				// ."-->".getHighContrastColor($color[$i-$legend_offset]);
				
			} 
			// if this is 0th row we have legend text, then spit out the legend text
			else if ( ( $i == 0) && ( $legendLabel != null) ) {
				$legendOutput .= $legendLabel ;
			}	
			
			$legendOutput .= "</div>";
		} // for

		$legendOutput .= "</div>"; // mod_civicrm_fullcalendar_legend_wrapper 
		
		
		// output the legend
		echo $legendOutput ;
		
		
    	echo "</div>"; // close div for id='mod_civicrm_fullcalendar_legend'
    	}  // if legend_picked == 3 
    	   
    
    


    	echo "</div>"; // close div for id='mod_civicrm_fullcalendar_legend'
    	}  // if color legend

    ?>
</div>
<!-- close div for id equal mod_civicrm_fullcalendar -->

