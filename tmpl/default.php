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


?>

<link rel="stylesheet" href="modules/mod_civievent/civievent.css" type="text/css"/>


<?php

$document =& JFactory::getDocument();


$document->addStyleSheet(JURI::base() . 'modules/mod_civicrm_fullcalendar/fullcalendar/demos/cupertino/theme.css', 'text/css', 'screen');
$document->addStyleSheet(JURI::base() . 'modules/mod_civicrm_fullcalendar/elements/colors.css', 'text/css', 'screen');

$document->addStyleSheet(JURI::base() . 'modules/mod_civicrm_fullcalendar/fullcalendar/fullcalendar/fullcalendar.css', 'text/css', 'screen');
$document->addStyleSheet(JURI::base() . 'modules/mod_civicrm_fullcalendar/fullcalendar/fullcalendar/fullcalendar.print.css', 'text/css', 'print');
?>

<?php
// AQS
// call FullCalendar's javascript
// ====================================================
$statement = <<<'JSJS'


var $cfcj = jQuery.noConflict();

	$cfcj(document).ready(function() {

	$cfcj('#calendar').fullCalendar({
		firstHour: 10,
		slotMinutes: 30,
		axisFormat: 'h:mm TT',
		theme: true,
		aspectRatio: 2,
		weekMode: 'liquid',
		timeFormat: 
			{
			    // for agendaWeek and agendaDay
			    agenda: 'h:mm{ - h:mm}', // 5:00 - 6:30

			    // for all other views
			    '': 'h:mm TT'            // 7p
			},

		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
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
$customcolorsstr = $displayParams['customcolors'];
$colorlegend = $displayParams['colorlegend'];
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
$result = mysql_query($query) or die(mysql_error());

$column = array();

while ($row = mysql_fetch_array($result)) {
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

        $link = JRoute::_($baselink . 'info&reset=1&id=' . $event->eventID);

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
        }

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
            $eventcolor = $color[$key];
        }

        $statement .=
            "start: new Date($sy, $sm, $sd), " .
                "end: new Date($ey, $em, $ed), " .
                "color: '$eventcolor', " .
                "allDay: '$allday', " .
                "url: '$link' " .
                "}";

        // need the comma, but not for the last event, of course
        if ($i < $n) {
            $statement .= ",";
        }
    }
}

// ====================================================
$statement .= <<<'JSJS'

			]

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
<div id='calendar'></div>
<?PHP
    if ($colorlegend == 0){
        echo "<br/><div id='legend'><table id='colorlegend'><tr><th>Color Legend</th></tr>";
        for ($i = 0, $n = count($catname); ($i < $n); $i++) {
            echo "<tr><td><span id='colorsquare' style='background-color:" . $color[$i] . "'>" . $color[$i] . "</span><span class='text'>" .  $catname[$i] ."</span></td></tr>";
        }
        echo "</table></div>";
    }
?>

