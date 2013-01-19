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





$statement .=
	"\t\t	theme: true, \n".
	"\t\t	timeFormat: \n\t\t\t{ \n".
	"\t\t\t   // for agendaWeek and agendaDay \n".
	"\t\t\t   agenda: '".trim($displayParams[agenda_timeFormat])."', \n\n". 
	"\t\t\t   // for all other views \n".
	"\t\t\t   '': '".trim($displayParams[allother_timeFormat])."' \n\n". 
	"\t\t	  }, \n" 
	;

	
	$statement .=
		"\t\t editable: false, \n";
	
	
	if (  trim($displayParams[advancedoptions]) != null  ) {
	// we need a comma on the end, is there one there on the end remove it and we will add it
	$statement .= "\t\t ".rtrim(trim($displayParams[advancedoptions]), ",").",\n";
	}
	
	$statement .=	
		"\t\t events: [ \n"
		;

		
		
		
		
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



$titleTemplate = $displayParams['fieldtemplate'] ; 


foreach ($eventtitles as &$event) {


			
	if ($x > $maxevents) {
		return;
	} else {
		$x++;
	}

	$baselink = 'index.php/component/civicrm/?task=civicrm/event/';

	for ($i = 0, $n = count($event->title); ($i < $n); $i++) {
		$allDay = false;
		$e_title = "";
		$e_summary = "";
		$e_street_address = "";
		$e_supplemental_address_1 = "";
		$e_supplemental_address_2 = "";
		$e_duration = "";
		$color1 = $displayParams['color1'];
		
		
		if( $displayParams['modal'] == "1" ) {
			// modallink
			$link =  $baselink.'info&reset=1&id='.$event->eventID.'&tmpl=component' ;

		}
		else {
			$link = JRoute::_($baselink . 'info&reset=1&id=' . $event->eventID);

		}


		$registernow = JRoute::_( $baselink.'register&reset=1&id='.$event->eventID );

		
		

		/*
		 *  +-----------------------------------------------------+
		 *  |
		 *  |                   ID
		 *  |
		 *  +-----------------------------------------------------+
		 *
		 */		$statement .=
		 "\t\t\t".
		 "{ " .
		 "id: '$event->id ', ";
		
		
		
		
		
		/*
		 *  +-----------------------------------------------------+
		 *  |
		 *  |                   DURATION
		 *  |
		 *  +-----------------------------------------------------+
		 *
		 */
		// we need to do lots of date stuff regarless of coloring or
		// if we have {event_duration} in the template

		$datetime = strtotime($event->start_date);
		//$mysqldate = date("m/d/y g:i A", $datetime);
		$sd = date('j', $datetime);
		$sm = date('n', $datetime);
		$sm = $sm - 1; // remember to decrement the month
		$sy = date('Y', $datetime);
		$shr = date('G', $datetime);
		$smin = date('i', $datetime);
		$ssec = date('s', $datetime);



		// Do we event have an end date ???
		// if not and if the start time was MIDNIGHT, we will assume that this event will
		// be a pure ALL DAY event
		// if the start time was not midnight, this will be a reminder of the day event
		$datetime = strtotime($event->end_date);
		if ($datetime == null) {
			$ed = $sd ;
			$em = $sm ;
			$ey = $sy ;
			$ehr = $shr ;
			$emin = $smin ;
			$esec = $ssec ;

			if (( $shr + $smin) == 0) {
				$allDay = true;
			} else {
				$allDay = false;
				$ehr = 23; $emin = 59; $esec=59;
			}

		} // if end_date is null
		else {
			// end_date is not null - that's good....

			//$mysqldate = date("m/d/y g:i A", $datetime);
			$ed = date('j', $datetime);
			$em = date('n', $datetime);
			$em = $em - 1; 	//remember to decrement the month
			// .. because for JavaScript, Jan is month 0
			$ey = date('Y', $datetime);
			$ehr = date('G', $datetime);
			$emin = date('i', $datetime);
			$esec = date('s', $datetime);
		}

		//duration and single/multi checker
		//also sets color for single/multi
		if ($ed == $sd && $em == $sm && $ey == $sy) {
			//single day


			$eventcolor = $color[0];
			if ( ($emin == $smin) &&  ($shr==$ehr)   ) {
				$e_duration = "1 day";
			} else {
				if ($ehr - $shr == 1) {
					$e_duration .= ($ehr - $shr) . " hour";
				} elseif ($ehr - $shr >= 1) {
					$e_duration .= ($ehr - $shr) . " hours";
				}
				if ($emin - $smin == 1) {
					$e_duration .= ", " . ($emin - $smin) . " minute";
				} elseif ($emin - $smin >= 1) {
					$e_duration .= ", " . ($emin - $smin) . " minutes";
				}
			} // shr==ehr smin==emin

		} else {
			//multi-day
			$eventcolor = $color[1];
			$allDay = true;
			if ($ed - $sd == 1) {
				$e_duration .= ($ed - $sd) . " day";
			} elseif ($ed - $sd > 1) {
				$e_duration .= ($ed - $sd) . " days";
			}
			if ($ehr - $shr == 1) {
				$e_duration .= ", " . ($ehr - $shr) . " hour";
			} elseif ($ehr - $shr > 1) {
				$e_duration .= ", " . ($ehr - $shr) . " hours";
			}
			if ($emin - $smin == 1) {
				$e_duration .= ", " . ($emin - $smin) . " minute";
			} elseif ($emin - $smin > 1) {
				$e_duration .= ", " . ($emin - $smin) . " minutes";
			}
		}


		


		/*
		 *  +-----------------------------------------------------+
		 *  |
		 *  |                   TITLE
		 *  |
		 *  +-----------------------------------------------------+
		 * 
		 */
		// do we even need the title?
		if(stristr($titleTemplate, "{event_title}") != FALSE) {
			// let's encode the title
			// but using the json encode function adds equals signs
			// so lets remove that
			// and then perfrom a trim on the final result
			$e_title .= trim(trim(json_encode(($event->title))), "\"");
			// apostrophes still cause a problem after the
			// json_encode.  So we will do a replace (escape) on that
			// see this for help: http://www.the-art-of-web.com/javascript/escape/
			$e_title = preg_replace("/\\'/", "\\\'", $e_title);
			// catch prevent a null string that literally says null
			if ($e_title == "null") {
				$e_title = "";
			}
		} // if(stristr($titleTemplate, "{event_title}") != FALSE)
			

		/*
		 *  +-----------------------------------------------------+
		 *  |
		 *  |                   SUMMARY
		 *  |
		 *  +-----------------------------------------------------+
		 * 
		 */		// do we even need the summary?
		if(stristr($titleTemplate, "{event_summary}") != FALSE) {
			// enocde this string as civicrm allows new lines in the
			// summary field and these are represented as \n\r
			$e_summary = trim(trim(json_encode(($event->summary))), "\"");
			// escape out the apostrophes
			$e_summary = preg_replace("/\\'/", "\\\'", $e_summary);
			// catch prevent a null string that literally says null
			if ($e_summary == "null") {
				$e_summary = "";
			}
		} // if(stristr($titleTemplate, "{event_summary}") != FALSE) {


		/*
		 *  +-----------------------------------------------------+
		*  |
		*  |                   LOCATION
		*  |
		*  +-----------------------------------------------------+
		*
		*/
		// do we even need the location?
		if  (stristr($titleTemplate, "{event_street_address}") != FALSE) {
			// let's encode the string
			// but using the json encode function adds equals signs
			// so lets remove that
			// and then perfrom a trim on the final result
			$e_street_address .= trim(trim(json_encode(($event->street_address))), "\"");
			// apostrophes still cause a problem after the
			// json_encode.  So we will do a replace (escape) on that
			// see this for help: http://www.the-art-of-web.com/javascript/escape/
			$e_street_address = preg_replace("/\\'/", "\\\'", $e_street_address);
			// catch prevent a null string that literally says null
			if ($e_street_address == "null") {
				$e_street_address = "";
			}
		}  // if(stristr($titleTemplate, "{event_street_address}") != FALSE) {
		
		if  (stristr($titleTemplate, "{event_supplemental_address_1}") != FALSE) {
			// let's encode the string
			// but using the json encode function adds equals signs
			// so lets remove that
			// and then perfrom a trim on the final result
			$e_supplemental_address_1 .= trim(trim(json_encode(($event->event_supplemental_address_1))), "\"");
			// apostrophes still cause a problem after the
			// json_encode.  So we will do a replace (escape) on that
			// see this for help: http://www.the-art-of-web.com/javascript/escape/
			$e_supplemental_address_1 = preg_replace("/\\'/", "\\\'", $e_supplemental_address_1);
			// catch prevent a null string that literally says null
			if ($e_supplemental_address_1 == "null") {
				$e_supplemental_address_1 = "";
			}
		}  // if(stristr($titleTemplate, "{event_supplemental_address_1}") != FALSE) {
		
		if  (stristr($titleTemplate, "{event_supplemental_address_2}") != FALSE) {
			// let's encode the string
			// but using the json encode function adds equals signs
			// so lets remove that
			// and then perfrom a trim on the final result
			$e_supplemental_address_2 .= trim(trim(json_encode(($event->event_supplemental_address_2))), "\"");
			// apostrophes still cause a problem after the
			// json_encode.  So we will do a replace (escape) on that
			// see this for help: http://www.the-art-of-web.com/javascript/escape/
			$e_supplemental_address_2 = preg_replace("/\\'/", "\\\'", $e_supplemental_address_2);
			// catch prevent a null string that literally says null
			if ($e_supplemental_address_2 == "null") {
				$e_supplemental_address_2 = "";
			}
		}  // if(stristr($titleTemplate, "{event_supplemental_address_1}") != FALSE) {		
		
		


		/*
		 *  +-----------------------------------------------------+
		*  |
		*  |  assemble the 'title' param for fullcalendar's
		*  |  event invokation
		*  |
		*  +-----------------------------------------------------+
		*
		*/
		$final_title = $titleTemplate ; 
		$final_title = str_replace ( "{event_title}" , $e_title , $final_title );
		$final_title = str_replace ( "{event_street_address}" , $e_street_address , $final_title );
		$final_title = str_replace ( "{event_supplemental_address_1}" , $e_supplemental_address_1 , $final_title );
		$final_title = str_replace ( "{event_supplemental_address_2}" , $e_supplemental_address_2 , $final_title );
		$final_title = str_replace ( "{event_summary}" , $e_summary , $final_title );
		$final_title = str_replace ( "{event_duration}" , $e_duration , $final_title );
		$statement .= "title: '$final_title', ";
		

		
		
		
		
		//sets category colors
		if ($displayParams['color'] == 1) {
			$eventid = $event->event_type_id;
			$key = array_search($eventid, $categories);
			//    $eventcolor = $color[$key];


			// map event type ids to the colors
			$my_color_key_array = array_keys($categories,$event->event_type_id);
			$eventcolor = $color[$my_color_key_array[0]];
		}
		// 
		//else {
			// well, then it was color by duration and that was set up above
			// in the duration block
		//}




		if ( $displayParams[useHighContrast] == true) {
			$statement .= "\n\t\t\t\ttextColor: '#".modCiviCRMFullCalendarHelper::getHighContrastColor($eventcolor)."', " ;
		}
		else {
			$statement .= "textColor: '".$displayParams[eventTextColor]."', " ;
		}



		if ( $allDay == 0 ) {
			$statement .=
			"\n\t\t\t\t".
			"allDay: false, ".
			"\n\t\t\t\t".
			"start: new Date($sy, $sm, $sd, $shr, $smin), " .
			"\n\t\t\t\t".
			"end: new Date($ey, $em, $ed, $ehr, $emin), " ;
		}
		else {
			$statement .=
			"\n\t\t\t\t".
			"allDay: true, " .
			"\n\t\t\t\t".
			"start: new Date($sy, $sm, $sd), " .
			"\n\t\t\t\t".
			"end: new Date($ey, $em, $ed), " ;
		}

		$statement .=
		"\n\t\t\t\t".
		"url: '$link' ,".
		"\n\t\t\t\t".
		"color: '$eventcolor' " ;
		$statement .= 
			"\n\t\t\t".
		    "}";

		// need the comma, but not for the last event, of course
		if ($i < $n) {
            $statement .= ",";
        }
	}
}



$statement .= "\n\t\t\t]\n";



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





$statement .= "\t\t\t});\n".
  			  "\t\t});";






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

		$durationColoringTextArray = array(
			"shorter than all day",
			"all day or multi-day"
		);

	$legendLabel = trim($displayParams['legendLabel']) ;


	
	
	
	/* 
	 *  +-----------------------------------------------------+  
 	 *  | 
	 *  |                      LEGEND1  
	 *  |
	 *  +-----------------------------------------------------+
	 *
	 */ 
    // a table based legend
    // 2 columns
    // and the rows are based on the number of categories or
    // if the coloring is by event duration, the 2 colors for 
    // all day and no all day
	if ($legend_picked == "1") {
        echo "<div id='mod_civicrm_fullcalendar_legend'>";


        echo "<table id='mod_civicrm_fullcalendar_colorlegend'>".
          "<tr><th>".$legendLabel."</th></tr>";

        // are we coloring by duration?
        if  ($displayParams['color'] == 0)  {

     		//all day or multi-day
     		echo 	"<tr><td>".
       		"<span id='colorsquare' ".
       		"style='";
     		 
     		$eventcolor = $color[1];
     		if ( $displayParams[useHighContrast] == true) {
     			echo "color: #".modCiviCRMFullCalendarHelper::getHighContrastColor($color[1])."; ";
     		}
     		else {
				echo "color: ".$displayParams[eventTextColor]."; ";
     		}
     		echo
     		"text-align:center; background-color:" .
     		$color[1] . "'>" . $color[1] . "</span><span class='text'>" .
     		$durationColoringTextArray[1]."</span></td></tr>";

     		     		
     		//all-day
     		echo 	"<tr><td>".
     				"<span id='colorsquare' ".
     				"style='";
     		
     		$eventcolor = $color[0];
     		if ( $displayParams[useHighContrast] == true) {
     			echo "color: #".modCiviCRMFullCalendarHelper::getHighContrastColor($color[0])."; ";
     		}
     		else {
     			echo "color: ".$displayParams[eventTextColor]."; ";
     		}
     		echo
     		"text-align:center; background-color:" .
     		$color[0] . "'>" . $color[0] . "</span><span class='text'>" .
     		$durationColoringTextArray[0]."</span></td></tr>";
     		 


        }
        else {
		 // we are coloring by event category
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
        } // color by event category

        echo "</table>";
	} //   if ($legend_picked == "1")









	/*
	 *  +-----------------------------------------------------+
	 *   |
  	 *   |                LEGEND2 or LEGEND 3
	 *   |
	 *   +-----------------------------------------------------+
	 *
	 */
        if  ( ($legend_picked == "2") ||  ($legend_picked == "3") ) {
    	$legendOutput  = "<div id='mod_civicrm_fullcalendar_legend' >";

    	
    	// how many boexs?
    	// ...
    	// are we coloring by duration?
    	if  ($displayParams['color'] == 0)  {	 
    		$num_o_boxes = 2 ;
    	}
    	else {
    	$num_o_boxes = count($catname) ;
		}
    	
		
    	// one more box to say 'legend'
    	// if there is legend text that is not null
    	$legend_offset = 0 ;
    	if ( $legendLabel != null) {
    		$num_o_boxes++;
    		$legend_offset = 1 ;
    	}
    	$legendOutput .=  "<div id=\"mod_civicrm_fullcalendar_legend_wrapper\"  >";

    	
    	// are we coloring by duration?
    	if  ($displayParams['color'] == 0)  {
		// yes, coloring by duration and need only two boxes plus maybe the legend
		
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
							  $legendOutput .= $durationColoringTextArray[$i-$legend_offset] ;
							}
							// if this is 0th row and we have legend text, then spit out the legend text
							else if ( ( $i == 0) && ( $legendLabel != null) ) {
							$legendOutput .= $legendLabel ;
			}
			
			$legendOutput .= "</div>";
			} // for


		} 
		else {
		// nope, coloring be event category
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

		} // if then else --> color be event category
		
		
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

