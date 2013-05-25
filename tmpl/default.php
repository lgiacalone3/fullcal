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
	"<DIV ".
	"id=\"mod_civicrm_fullcalendar_dialog\" class=\"modal\" title=\"\">".
	"<a id=\"aref_mod_civicrm_fullcalendar_dialog\" class=\"modal\" ".
	"href=\"\" ".
	"rel=\"{handler: 'iframe', size: {x: 520, y: 400}}\"></a>".
	"</div>\n\n";
}


if ( $displayParams['filterOnLocation'] == 1 ) {
	echo
	  "<div id=\"mod_civicrm_fullcalendar_filter_by\"></div>\n\n";
}






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





// call FullCalendar's javascript
$statement =  "var \$cfcj = jQuery.noConflict();\n".
			  "\$cfcj(document).ready(function() {\n";
					
if ( $displayParams['filterOnLocation'] === "1" ) {
	$statement .= "\n\n//DEBUG alert('will init allLocationsText with '+ \$cfcj('#mcfc_location_filter').val() || []  );";
	$statement .= "\nvar allLocationsText = \$cfcj('#mcfc_location_filter').val()  || [] ; \n\n";
}


$statement .= "\$cfcj('#calendar').fullCalendar({\n\n"; 





// Add some more parameters:
$statement .= "\t\tdefaultView: '".$displayParams['defalt_view']."', \n" ;
$statement .= "\t\tweekMode: '".$displayParams['weekMode']."', \n" ;
$statement .= "\t\tfirstHour: ".intval($displayParams['firstHour']).", \n" ;
$statement .= "\t\tslotMinutes: ".intval($displayParams['slotMinutes']).", \n" ;
$statement .= "\t\taspectRatio: ".$displayParams['aspectRatio'].", \n" ;
$statement .= "\t\taxisFormat: '".$displayParams['axisFormat']."', \n" ;


// Set the Months
$statement .= "\t\tmonthNamesShort: [".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_01" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_02" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_03" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_04" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_05" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_06" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_07" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_08" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_09" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_10" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_11" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_SHORT_12" )."'".
		"], \n" ;


// Set the Months
$statement .= "\t\tmonthNames: [".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_01" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_02" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_03" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_04" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_05" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_06" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_07" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_08" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_09" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_10" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_11" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_MONTH_NAMES_12" )."'".
		"], \n" ; 

// Set the day names (short)
$statement .= "\t\tdayNamesShort: [".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_SHORT_1" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_SHORT_2" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_SHORT_3" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_SHORT_4" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_SHORT_5" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_SHORT_6" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_SHORT_7" )."'".
		"], \n" ;

// Set the day names 
$statement .= "\t\tdayNames: [".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_1" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_2" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_3" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_4" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_5" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_6" )."', ".
		"'".JText::_( "MOD_CIVICRM_FULLCALENDAR_DAY_NAMES_7" )."',".
		"], \n" ;


$statement .= "\t\tbuttonText: {\n".
		"\t\t\tprev:     '&nbsp;&#9668;&nbsp;',  // left triangle\n".
		"\t\t\tnext:     '&nbsp;&#9658;&nbsp;',  // right triangle\n".
		"\t\t\tprevYear: '&nbsp;&lt;&lt;&nbsp;', // <<\n".
		"\t\t\tnextYear: '&nbsp;&gt;&gt;&nbsp;', // >>\n".
		"\t\t\ttoday:    '".JText::_( "MOD_CIVICRM_FULLCALENDAR_BUTTON_TODAY" )."',\n".
		"\t\t\tmonth:    '".JText::_( "MOD_CIVICRM_FULLCALENDAR_BUTTON_MONTH" )."',\n".
		"\t\t\tweek:     '".JText::_( "MOD_CIVICRM_FULLCALENDAR_BUTTON_WEEK" )."',\n".
		"\t\t\tday:      '".JText::_( "MOD_CIVICRM_FULLCALENDAR_BUTTON_DAY" )."'\n".
		"\t\t}, \n" ;


$statement .= "\t\ttitleFormat: {\n".
		"\t\t\tmonth: '".$displayParams[calendar_titleFormat_month]."',\n".
		"\t\t\tweek: '".$displayParams[calendar_titleFormat_week]."',\n".
		"\t\t\tday: '".$displayParams[calendar_titleFormat_day]."',\n".
		"\t\t}, \n" ;
		




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
			"\t\t events: myEventsArray" ;


//			$debug = 0;
//
//			if ($debug) {
//				echo '<ul class="civieventlist">';
//			}

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
			$maxevents = $displayParams['maxevents'] ;

			$x = 1;

			/*
			 *  +---------------------------------------------+
			*  |
			*  |
			*  |  load the categories
			*  |
			*  |
			*  +---------------------------------------------+
			*/

			// which categories are in the ones the user wants?
			foreach ($eventtitles as &$event) {
				for ($i = 0, $n = count($event->street_address); ($i < $n); $i++) {
							$which_category_ids[] = $event->event_type_id;
				} //for
			}  // foreach
			$which_category_ids = array_unique($which_category_ids);
			sort($which_category_ids);
			$inclausevalues = "";
			for ( $i=0, $n = count($which_category_ids); ($i < $n); $i++) {
				$inclausevalues .= $which_category_ids[$i];
				if ($i < ($n-1)) { $inclausevalues.=","; }  // don't add the last comma
			}
			
			
			$query = "SELECT civicrm_option_value.value, civicrm_option_value.label
						FROM civicrm_option_value
						INNER JOIN
						civicrm_option_group
						ON (civicrm_option_value.option_group_id = civicrm_option_group.id)
						WHERE (civicrm_option_group.name = 'event_type')
			              and value in (".$inclausevalues.")";
						// AND (civicrm_option_value.is_active = '1')";
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
			// ---------------------------------------------------
			// ---------------------------------------------------





			
			$titleTemplate = $displayParams['fieldtemplate'] ;
				
			
			
			/*
			 *  +---------------------------------------------+
			*  |
			*  |
			*  |  load the locations
			*  |
			*  |
			*  +---------------------------------------------+
			*/
			
			/*
			if ( ($displayParams['colorby'] == 2 ) &&
					(stristr($titleTemplate, "{event_street_address}") == FALSE)    )  {
				$locationArray[]="color by legend settings do not match fieds in tempate.";
				JError::raiseWarning(2001,  $locationArray[0] );
			} else
				if ( ($displayParams['colorby'] == 3 ) &&
					(stristr($titleTemplate, "{event_supplemental_address1}") == FALSE)    )  {
				$locationArray[]="color by legend settings do not match fieds in tempate.";
				JError::raiseWarning(2001,  $locationArray[0] );
			} else
				if ( ($displayParams['colorby'] == 4 ) &&
					(stristr($titleTemplate, "{event_supplemental_address2}") == FALSE)    )  {
				$locationArray[]="color by legend settings do not match fieds in tempate.";
				JError::raiseWarning(2001,  $locationArray[0] );
			} else {
*/
			
			
			if ( 
					($displayParams['colorby'] == 2 ) ||
					($displayParams['colorby'] == 3 ) ||
					($displayParams['colorby'] == 4 ) ||
					($displayParams['filterOnLocation'] == 1 )
				){		
						
				foreach ($eventtitles as &$event) {
					for ($i = 0, $n = count($event->street_address); ($i < $n); $i++) {
						switch ($displayParams['locationFilterField']) {
							case 1:
								$locationArray[] = $event->street_address;
								break;
							case 2:
								$locationArray[] = $event->supplemental_address1;
								break;
							case 3:
								$locationArray[] = $event->supplemental_address2;
								break;
						} // switch
					} //for
				}  // foreach
				$locationArray = array_unique($locationArray);
				sort($locationArray);
			} // if we need locations for colorby 2,3,4 or filterOnLocation
				
				
	

//				} //else
				// ---------------------------------------------------
				

				
				
			
			/*
			 *  +------------------------------------------------------------------
			 *  | 
			 *  |     Populate the options selection box with locatons
			 *  |
			 *  +------------------------------------------------------------------
			 * 
			 */
				
	


				
				if ( $displayParams['filterOnLocation'] === "1" ) {
					$select_and_option_values =
					JText::_( "MOD_CIVICRM_FULLCALENDAR_FILTER_BY_LOCATION_LABEL" ).
					":<BR />".
					"<P>".
					"<select name='select' id='mcfc_location_filter' multiple>".
					"<option value='".$displayParams['filterTextForAllLocations']."' selected>".$displayParams['filterTextForAllLocations']."</option>";
						
						
					foreach ($locationArray as &$loc) {
						$select_and_option_values .= "<option value='".$loc."'>".$loc."</option>";
					}
						
					$select_and_option_values .= "</select>";


					echo
					"<script type=\"text/javascript\">\n".
					"document.getElementById('mod_civicrm_fullcalendar_filter_by').innerHTML = \"".$select_and_option_values."\";".
					"</script>\n\n";
				} // if ( $displayParams['filterOnLocation'] === "1" )

			

				$events_array_statement = "\n\n\t\tvar myEventsArrayALL =  [\n";
				$eventNumberWhat = 0;
				$howManyEvents = count($eventtitles) ;
				foreach ($eventtitles as &$event) {
				
				
					$eventNumberWhat++;
				

					
					/*
					// ================================= 
					// DEBUG for IE testing
					//
					echo "eventNumberWhat"."=".$eventNumberWhat." for ".$event->title." for id ".$event->eventID."<BR/>" ;   // DEBUG
					 if ($eventNumberWhat >= 999) {

						$events_array_statement .=
						"{\n".
						"id: '99999999', title: 'TEST EVENT',\n".
						"eventLoc: 'TEST LOCATION',\n".
						"textColor: '#FFFFFF',\n".
						"allDay: false,\n".
						"start: new Date(2013, 1, 3, 9, 00),\n".
						"end: new Date(2013, 1, 3, 10, 00),\n".
						"color: 'blue'\n".
						"}\n";
						break;
					}
					// ================================= 
					*/		
					
					if ($x > $maxevents) {
						break;
					} else {
						$x++;
					}
				
					$baselink = 'index.php/component/civicrm/?task=civicrm/event/';
				
						
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
							$link =  JRoute::_($baselink . 'info&reset=1&id='.$event->eventID.'&tmpl=component') ;
				
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
						*/
						$events_array_statement .=
						"\t\t\t\t{\n\t\t\t\t" .
						"id: '".$event->eventID."', ";
				
				
				
				
				
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
						} // else
				
				
				
				
				
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
						 *
						 *
						 *
						 */
						if (
								($displayParams['colorby'] == 2) ||
								($displayParams['colorby'] == 3) ||
								($displayParams['colorby'] == 4) ||
								($displayParams['filterOnLocation'] == 1)
						) {
								
							$the_field = "";
							switch ($displayParams['locationFilterField']) {
								case 1:
									$the_field = $event->street_address;
									break;
								case 2:
									$the_field = $event->supplemental_address1;
									break;
								case 3:
									$the_field = $event->supplemental_address2;
									break;
							} // switch


							$events_array_statement .= "\n\t\t\t\teventLoc: '".
									$the_field.								"',\n\t\t\t\t";

						}
						
						
				
				
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
						$events_array_statement .= "title: '$final_title', ";
				
				
				
				
						//sets category colors
						if ($displayParams['colorby'] == 1) {
							// event type
							$eventid = $event->event_type_id;
							$key = array_search($eventid, $categories);
				
							// map event type ids to the colors
							$my_color_key_array = array_keys($categories,$event->event_type_id);
							$eventcolor = $color[$my_color_key_array[0]];
						}
						else if (
								// color by location
								// any of the following:
								//   street_address, supplemental_address_1, supplemental_address_2
								($displayParams['colorby'] == 2) ||
								($displayParams['colorby'] == 3) ||
								($displayParams['colorby'] == 4) 
								) {
				
				
							$eventcolor = $color[
							array_search( $event->street_address , $locationArray)];


							
				        } // color by location
						//
						//else {
						// well, then it was color by duration and that was set up above
						// in the duration block
						//}
				
				
				
				
						if ( $displayParams[useHighContrast] == true) {
						$events_array_statement .= "\n\t\t\t\ttextColor: '#".modCiviCRMFullCalendarHelper::getHighContrastColor($eventcolor)."', " ;
						}
						else {
						$events_array_statement .= "textColor: '".$displayParams[eventTextColor]."', " ;
						}
				
				
				
						if ( $allDay == 0 ) {
						$events_array_statement .=
						"\n\t\t\t\t".
							"allDay: false, ".
							"\n\t\t\t\t".
				"start: new Date($sy, $sm, $sd, $shr, $smin), " .
								"\n\t\t\t\t".
								"end: new Date($ey, $em, $ed, $ehr, $emin), " ;
						}
						else {
						$events_array_statement .=
						"\n\t\t\t\t".
							"allDay: true, " .
							"\n\t\t\t\t".
							"start: new Date($sy, $sm, $sd), " .
				"\n\t\t\t\t".
				"end: new Date($ey, $em, $ed), " ;
							}
				
							$events_array_statement .=
							"\n\t\t\t\t".
							"url: '$link' ,".
							"\n\t\t\t\t".
							"color: '$eventcolor' " ;
						$events_array_statement .=
										"\n\t\t\t".
										"}";
				
				
				
				
				
										// need the comma, but not for the last event, of course
												// 		$events_array_statement .= " dollar n is ".$n."\n";
				
				
							if ( $eventNumberWhat < $howManyEvents ) {
							$events_array_statement .= ",";
					}
					$events_array_statement .= "\n";

					}  // foreach
				
				
				
					$events_array_statement .= "\t\t\t];\n";
				
				
				

			// ====================================================
			// if we need modal dialog boxes for the events
			// ====================================================
			if ( $displayParams['modal'] == "1" ) {
				$statement .= $js_snippet_eventClick_modal_dialogs ; 
			} // if ( $displayParams['modal'] == "1" ) {


			$statement .= "\n\t\t\t});\n";

			
			// add in the javascrupt for front-end filtering 
			if ( $displayParams['filterOnLocation'] === "1" ) {
				$statement .= $js_snippet_mcfc_location_filter_mouseup ;
			}

			
			

						
			$statement .= "\n\t\t}); // END of READY FUNCTION";
			
			

			
			$statement .= $events_array_statement ;
			
			
			// FOOBAR - move the event code starting at line 305 to this function 
			$statement .= mcfcEventsArray::buildArray( $eventtitles, $params );

			// debug - only 2 events
			// these two do not seem to break IE 
			// $statement .= mcfcEventsArray::buildArrayDEBUG( $eventtitles, $params );
				
			
			
			// ====================================================
			// ====================================================
			$statement .= <<<'JSJS'

			// Initialize a copy by reference of the array with all event locations as "ALL LOCATIONS" is the default
			var myEventsArray = [];
			myEventsArray = myEventsArrayALL.slice(0, myEventsArrayALL.length);
			
			
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
          "<tr><th>".$legendLabel."</th></tr>\n";

        // are we coloring by duration?
        if  ($displayParams['colorby'] == 0)  {

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

        else if (
				//
				// color by location
				//
				 ($displayParams['colorby'] == 2) ||
				 ($displayParams['colorby'] == 3) ||
				 ($displayParams['colorby'] == 4) ) {

			// $useThisIndex =  $color[ array_search( $event->street_address , $locationArray) ];
			//$useThisIndex = array_search( $event->street_address , $locationArray) ;


			// we are coloring by event category
			for ($i = 0, $n = count($locationArray); ($i < $n); $i++) {
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
				$locationArray[$i] ."</span></td></tr>";
			} // for
		} // else .... color by location
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
		$legendOutput  = "\t<div id=\"mod_civicrm_fullcalendar_legend\" >\n";


		// one more box to say 'legend'
		// if there is legend text that is not null
		$legend_offset = 0 ;
		if ( $legendLabel != null) {
    		$legend_offset = 1 ;
    	}
    	$legendOutput .=  "\t\t<div id=\"mod_civicrm_fullcalendar_legend_wrapper\"  >\n";


    	// are we coloring by duration?
    	if  ($displayParams['colorby'] == 0)  {
		// yes, coloring by DURATION and need only two boxes plus maybe the legend
		$num_o_boxes = 2 ;
		if ($legendLabel != null) {$num_o_boxes+=1; }
		

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

			$legendOutput .= "</div>";
			} // for
			} // if color by druation
			
			
			
			
			
			
			if (
					($displayParams['colorby'] == 2) ||
					($displayParams['colorby'] == 3) ||
					($displayParams['colorby'] == 4)
 				)
			{
				$legendOutput .= "\t\t\t<!-- coloring by event location -->\n";
				$num_o_boxes = count($locationArray) ;
				if ($legendLabel != null) {$num_o_boxes+=1; }
				
				for ($i = 0, $n = $num_o_boxes; ($i < $n); $i++) {
				$legendOutput .= "\t\t\t<div class=\"mod_civicrm_fullcalendar_legend_cell\" \n";
				$legendOutput .= "\t\t\t\tstyle=\"";


				// if this is not the 0th box, put in the color
				// or if this is the 0th box and we don't have legend text, then put in the color
				if (  ( $i > 0) || (  ( $i == 0) && ( $legendLabel == null) )) {
					$legendOutput .= "background-color: ".$color[$i-$legend_offset]."; \n";
					$legendOutput .= "\t\t\t\t";
						
					if ( $displayParams[useHighContrast] == true) {
						$legendOutput .= "color: #".modCiviCRMFullCalendarHelper::getHighContrastColor($color[$i-$legend_offset])."; ";
					}
					else {
						$legendOutput .= "color: ".$displayParams[eventTextColor]."; ";
					}
				}
				// if this is 0th box we have legend text, then spit out the legend text
				else if ( ( $i == 0) && ( $legendLabel != null) ) {
					$legendOutput .= "color: black;";
				}

				if ( ($legend_picked == "2") ) {
					$legendOutput .= "width:".
							substr_replace(
							sprintf("%.3f", ((1/$num_o_boxes)*(100 - ($num_o_boxes*.75 )  ) ) )
							,"",-1
						).
						"%;";
				} else {
					$legendOutput .= "padding: 2px; padding-left: 8px; padding-right: 4px; margin-top: 4px;" ;
				}
				$legendOutput .= "\">";

				// if this is not the 0th row, put in the color
				// or if this is the 0th row and we don't have legend text, then put in the color
				if (  ( $i > 0) || (  ( $i == 0) && ( $legendLabel == null) )) {
					$legendOutput .= $locationArray[$i-$legend_offset] ;

				}
				// if this is 0th box we have legend text, then spit out the legend text
				else if ( ( $i == 0) && ( $legendLabel != null) ) {
					$legendOutput .= $legendLabel ;
				}

				$legendOutput .= "\n\t\t\t</div>\n";
			} // for
		} // coloring by location (2,3,4)

		
		
		
		
		
    	if  ($displayParams['colorby'] == 1)  {
		// coloring by event category
			$num_o_boxes = count($catname) ;
			if ($legendLabel != null) {$num_o_boxes+=1; }
				
			$legendOutput .= "\t\t\t<!-- coloring by event category -->\n";
	    	for ($i = 0, $n = $num_o_boxes; ($i < $n); $i++) {
				$legendOutput .= "\t\t\t<div class=\"mod_civicrm_fullcalendar_legend_cell\" \n";
				$legendOutput .= "\t\t\t\tstyle=\"";


				// if this is not the 0th row, put in the color
				// or if this is the 0th row and we don't have legend text, then put in the color
				if (  ( $i > 0) || (  ( $i == 0) && ( $legendLabel == null) )) {
					$legendOutput .= "background-color: ".$color[$i-$legend_offset]."; \n";
					$legendOutput .= "\t\t\t\t";
					
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
							sprintf("%.3f", ((1/$num_o_boxes)*(100 - ($num_o_boxes*.75 )  ) ) )
							,"",-1
						).
						"%;";
				} else {
					$legendOutput .= "padding: 2px; padding-left: 8px; padding-right: 4px; margin-top: 4px;" ;
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
	
				$legendOutput .= "\n\t\t\t</div>\n";
			} // for

		} // if then else --> color by event category
		
		
		$legendOutput .= "\t\t</div> <!-- mod_civicrm_fullcalendar_legend_wrapper -->\n";


		// output the legend
		echo $legendOutput ;


    	}  // if legend_picked == 3





    	echo "\t</div> <!-- mod_civicrm_fullcalendar_legend -->\n"; 
    	}  // if color legend

    	?>
</div><!-- mod_civicrm_fullcalendar -->


