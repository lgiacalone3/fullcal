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
	echo "<div  style=\"visibility: hidden; height: 0px;\" id=\"mod_civicrm_fullcalendar_dialog\" class=\"modal\" title=\"\">";
	echo
	"<a id=\"aref_mod_civicrm_fullcalendar_dialog\" class=\"modal\" ".
	"href=\"\" ".
	"rel=\"{handler: 'iframe', size: {x: 520, y: 400}}\"></a>";
	echo "</div>";
}
?>


<?php



// based on code from:
// http://psoug.org/snippet/CSS-Colornames-to-RGB-values_415.htm
//  GetColor  returns  an  associative  array  with  the  red,  green  and  blue
//  values  of  the  desired  color
FUNCTION  GetColor($Colorname)
{
	$Colors  =  ARRAY(
	
	//  Colors  as  they  are  defined  in  HTML  3.2
			"black"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0x00),
			"maroon"=>array( "red"=>0x80,  "green"=>0x00,  "blue"=>0x00),
			"green"=>array( "red"=>0x00,  "green"=>0x80,  "blue"=>0x00),
			"olive"=>array( "red"=>0x80,  "green"=>0x80,  "blue"=>0x00),
			"navy"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0x80),
			"purple"=>array( "red"=>0x80,  "green"=>0x00,  "blue"=>0x80),
			"teal"=>array( "red"=>0x00,  "green"=>0x80,  "blue"=>0x80),
			"gray"=>array( "red"=>0x80,  "green"=>0x80,  "blue"=>0x80),
			"silver"=>array( "red"=>0xC0,  "green"=>0xC0,  "blue"=>0xC0),
			"red"=>array( "red"=>0xFF,  "green"=>0x00,  "blue"=>0x00),
			"lime"=>array( "red"=>0x00,  "green"=>0xFF,  "blue"=>0x00),
			"yellow"=>array( "red"=>0xFF,  "green"=>0xFF,  "blue"=>0x00),
			"blue"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0xFF),
			"fuchsia"=>array( "red"=>0xFF,  "green"=>0x00,  "blue"=>0xFF),
			"aqua"=>array( "red"=>0x00,  "green"=>0xFF,  "blue"=>0xFF),
			"white"=>array( "red"=>0xFF,  "green"=>0xFF,  "blue"=>0xFF),
	
			//  Additional  colors  as  they  are  used  by  Netscape  and  IE
			"aliceblue"=>array( "red"=>0xF0,  "green"=>0xF8,  "blue"=>0xFF),
			"antiquewhite"=>array( "red"=>0xFA,  "green"=>0xEB,  "blue"=>0xD7),
			"aquamarine"=>array( "red"=>0x7F,  "green"=>0xFF,  "blue"=>0xD4),
			"azure"=>array( "red"=>0xF0,  "green"=>0xFF,  "blue"=>0xFF),
			"beige"=>array( "red"=>0xF5,  "green"=>0xF5,  "blue"=>0xDC),
			"blueviolet"=>array( "red"=>0x8A,  "green"=>0x2B,  "blue"=>0xE2),
			"brown"=>array( "red"=>0xA5,  "green"=>0x2A,  "blue"=>0x2A),
			"burlywood"=>array( "red"=>0xDE,  "green"=>0xB8,  "blue"=>0x87),
			"cadetblue"=>array( "red"=>0x5F,  "green"=>0x9E,  "blue"=>0xA0),
			"chartreuse"=>array( "red"=>0x7F,  "green"=>0xFF,  "blue"=>0x00),
			"chocolate"=>array( "red"=>0xD2,  "green"=>0x69,  "blue"=>0x1E),
			"coral"=>array( "red"=>0xFF,  "green"=>0x7F,  "blue"=>0x50),
			"cornflowerblue"=>array( "red"=>0x64,  "green"=>0x95,  "blue"=>0xED),
			"cornsilk"=>array( "red"=>0xFF,  "green"=>0xF8,  "blue"=>0xDC),
			"crimson"=>array( "red"=>0xDC,  "green"=>0x14,  "blue"=>0x3C),
			"darkblue"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0x8B),
			"darkcyan"=>array( "red"=>0x00,  "green"=>0x8B,  "blue"=>0x8B),
			"darkgoldenrod"=>array( "red"=>0xB8,  "green"=>0x86,  "blue"=>0x0B),
			"darkgray"=>array( "red"=>0xA9,  "green"=>0xA9,  "blue"=>0xA9),
			"darkgreen"=>array( "red"=>0x00,  "green"=>0x64,  "blue"=>0x00),
			"darkkhaki"=>array( "red"=>0xBD,  "green"=>0xB7,  "blue"=>0x6B),
			"darkmagenta"=>array( "red"=>0x8B,  "green"=>0x00,  "blue"=>0x8B),
			"darkolivegreen"=>array( "red"=>0x55,  "green"=>0x6B,  "blue"=>0x2F),
			"darkorange"=>array( "red"=>0xFF,  "green"=>0x8C,  "blue"=>0x00),
			"darkorchid"=>array( "red"=>0x99,  "green"=>0x32,  "blue"=>0xCC),
			"darkred"=>array( "red"=>0x8B,  "green"=>0x00,  "blue"=>0x00),
			"darksalmon"=>array( "red"=>0xE9,  "green"=>0x96,  "blue"=>0x7A),
			"darkseagreen"=>array( "red"=>0x8F,  "green"=>0xBC,  "blue"=>0x8F),
			"darkslateblue"=>array( "red"=>0x48,  "green"=>0x3D,  "blue"=>0x8B),
			"darkslategray"=>array( "red"=>0x2F,  "green"=>0x4F,  "blue"=>0x4F),
			"darkturquoise"=>array( "red"=>0x00,  "green"=>0xCE,  "blue"=>0xD1),
			"darkviolet"=>array( "red"=>0x94,  "green"=>0x00,  "blue"=>0xD3),
			"deeppink"=>array( "red"=>0xFF,  "green"=>0x14,  "blue"=>0x93),
			"deepskyblue"=>array( "red"=>0x00,  "green"=>0xBF,  "blue"=>0xFF),
			"dimgray"=>array( "red"=>0x69,  "green"=>0x69,  "blue"=>0x69),
			"dodgerblue"=>array( "red"=>0x1E,  "green"=>0x90,  "blue"=>0xFF),
			"firebrick"=>array( "red"=>0xB2,  "green"=>0x22,  "blue"=>0x22),
			"floralwhite"=>array( "red"=>0xFF,  "green"=>0xFA,  "blue"=>0xF0),
			"forestgreen"=>array( "red"=>0x22,  "green"=>0x8B,  "blue"=>0x22),
			"gainsboro"=>array( "red"=>0xDC,  "green"=>0xDC,  "blue"=>0xDC),
			"ghostwhite"=>array( "red"=>0xF8,  "green"=>0xF8,  "blue"=>0xFF),
			"gold"=>array( "red"=>0xFF,  "green"=>0xD7,  "blue"=>0x00),
			"goldenrod"=>array( "red"=>0xDA,  "green"=>0xA5,  "blue"=>0x20),
			"greenyellow"=>array( "red"=>0xAD,  "green"=>0xFF,  "blue"=>0x2F),
			"honeydew"=>array( "red"=>0xF0,  "green"=>0xFF,  "blue"=>0xF0),
			"hotpink"=>array( "red"=>0xFF,  "green"=>0x69,  "blue"=>0xB4),
			"indianred"=>array( "red"=>0xCD,  "green"=>0x5C,  "blue"=>0x5C),
			"indigo"=>array( "red"=>0x4B,  "green"=>0x00,  "blue"=>0x82),
			"ivory"=>array( "red"=>0xFF,  "green"=>0xFF,  "blue"=>0xF0),
			"khaki"=>array( "red"=>0xF0,  "green"=>0xE6,  "blue"=>0x8C),
			"lavender"=>array( "red"=>0xE6,  "green"=>0xE6,  "blue"=>0xFA),
			"lavenderblush"=>array( "red"=>0xFF,  "green"=>0xF0,  "blue"=>0xF5),
			"lawngreen"=>array( "red"=>0x7C,  "green"=>0xFC,  "blue"=>0x00),
			"lemonchiffon"=>array( "red"=>0xFF,  "green"=>0xFA,  "blue"=>0xCD),
			"lightblue"=>array( "red"=>0xAD,  "green"=>0xD8,  "blue"=>0xE6),
			"lightcoral"=>array( "red"=>0xF0,  "green"=>0x80,  "blue"=>0x80),
			"lightcyan"=>array( "red"=>0xE0,  "green"=>0xFF,  "blue"=>0xFF),
			"lightgoldenrodyellow"=>array( "red"=>0xFA,  "green"=>0xFA,  "blue"=>0xD2),
			"lightgreen"=>array( "red"=>0x90,  "green"=>0xEE,  "blue"=>0x90),
			"lightgrey"=>array( "red"=>0xD3,  "green"=>0xD3,  "blue"=>0xD3),
			"lightpink"=>array( "red"=>0xFF,  "green"=>0xB6,  "blue"=>0xC1),
			"lightsalmon"=>array( "red"=>0xFF,  "green"=>0xA0,  "blue"=>0x7A),
			"lightseagreen"=>array( "red"=>0x20,  "green"=>0xB2,  "blue"=>0xAA),
			"lightskyblue"=>array( "red"=>0x87,  "green"=>0xCE,  "blue"=>0xFA),
			"lightslategray"=>array( "red"=>0x77,  "green"=>0x88,  "blue"=>0x99),
			"lightsteelblue"=>array( "red"=>0xB0,  "green"=>0xC4,  "blue"=>0xDE),
			"lightyellow"=>array( "red"=>0xFF,  "green"=>0xFF,  "blue"=>0xE0),
			"limegreen"=>array( "red"=>0x32,  "green"=>0xCD,  "blue"=>0x32),
			"linen"=>array( "red"=>0xFA,  "green"=>0xF0,  "blue"=>0xE6),
			"mediumaquamarine"=>array( "red"=>0x66,  "green"=>0xCD,  "blue"=>0xAA),
			"mediumblue"=>array( "red"=>0x00,  "green"=>0x00,  "blue"=>0xCD),
			"mediumorchid"=>array( "red"=>0xBA,  "green"=>0x55,  "blue"=>0xD3),
			"mediumpurple"=>array( "red"=>0x93,  "green"=>0x70,  "blue"=>0xD0),
			"mediumseagreen"=>array( "red"=>0x3C,  "green"=>0xB3,  "blue"=>0x71),
			"mediumslateblue"=>array( "red"=>0x7B,  "green"=>0x68,  "blue"=>0xEE),
			"mediumspringgreen"=>array( "red"=>0x00,  "green"=>0xFA,  "blue"=>0x9A),
			"mediumturquoise"=>array( "red"=>0x48,  "green"=>0xD1,  "blue"=>0xCC),
			"mediumvioletred"=>array( "red"=>0xC7,  "green"=>0x15,  "blue"=>0x85),
			"midnightblue"=>array( "red"=>0x19,  "green"=>0x19,  "blue"=>0x70),
			"mintcream"=>array( "red"=>0xF5,  "green"=>0xFF,  "blue"=>0xFA),
			"mistyrose"=>array( "red"=>0xFF,  "green"=>0xE4,  "blue"=>0xE1),
			"moccasin"=>array( "red"=>0xFF,  "green"=>0xE4,  "blue"=>0xB5),
			"navajowhite"=>array( "red"=>0xFF,  "green"=>0xDE,  "blue"=>0xAD),
			"oldlace"=>array( "red"=>0xFD,  "green"=>0xF5,  "blue"=>0xE6),
			"olivedrab"=>array( "red"=>0x6B,  "green"=>0x8E,  "blue"=>0x23),
			"orange"=>array( "red"=>0xFF,  "green"=>0xA5,  "blue"=>0x00),
			"orangered"=>array( "red"=>0xFF,  "green"=>0x45,  "blue"=>0x00),
			"orchid"=>array( "red"=>0xDA,  "green"=>0x70,  "blue"=>0xD6),
			"palegoldenrod"=>array( "red"=>0xEE,  "green"=>0xE8,  "blue"=>0xAA),
			"palegreen"=>array( "red"=>0x98,  "green"=>0xFB,  "blue"=>0x98),
			"paleturquoise"=>array( "red"=>0xAF,  "green"=>0xEE,  "blue"=>0xEE),
			"palevioletred"=>array( "red"=>0xDB,  "green"=>0x70,  "blue"=>0x93),
			"papayawhip"=>array( "red"=>0xFF,  "green"=>0xEF,  "blue"=>0xD5),
			"peachpuff"=>array( "red"=>0xFF,  "green"=>0xDA,  "blue"=>0xB9),
			"peru"=>array( "red"=>0xCD,  "green"=>0x85,  "blue"=>0x3F),
			"pink"=>array( "red"=>0xFF,  "green"=>0xC0,  "blue"=>0xCB),
			"plum"=>array( "red"=>0xDD,  "green"=>0xA0,  "blue"=>0xDD),
			"powderblue"=>array( "red"=>0xB0,  "green"=>0xE0,  "blue"=>0xE6),
			"rosybrown"=>array( "red"=>0xBC,  "green"=>0x8F,  "blue"=>0x8F),
			"royalblue"=>array( "red"=>0x41,  "green"=>0x69,  "blue"=>0xE1),
			"saddlebrown"=>array( "red"=>0x8B,  "green"=>0x45,  "blue"=>0x13),
			"salmon"=>array( "red"=>0xFA,  "green"=>0x80,  "blue"=>0x72),
			"sandybrown"=>array( "red"=>0xF4,  "green"=>0xA4,  "blue"=>0x60),
			"seagreen"=>array( "red"=>0x2E,  "green"=>0x8B,  "blue"=>0x57),
			"seashell"=>array( "red"=>0xFF,  "green"=>0xF5,  "blue"=>0xEE),
			"sienna"=>array( "red"=>0xA0,  "green"=>0x52,  "blue"=>0x2D),
			"skyblue"=>array( "red"=>0x87,  "green"=>0xCE,  "blue"=>0xEB),
			"slateblue"=>array( "red"=>0x6A,  "green"=>0x5A,  "blue"=>0xCD),
			"slategray"=>array( "red"=>0x70,  "green"=>0x80,  "blue"=>0x90),
			"snow"=>array( "red"=>0xFF,  "green"=>0xFA,  "blue"=>0xFA),
			"springgreen"=>array( "red"=>0x00,  "green"=>0xFF,  "blue"=>0x7F),
			"steelblue"=>array( "red"=>0x46,  "green"=>0x82,  "blue"=>0xB4),
			"tan"=>array( "red"=>0xD2,  "green"=>0xB4,  "blue"=>0x8C),
			"thistle"=>array( "red"=>0xD8,  "green"=>0xBF,  "blue"=>0xD8),
			"tomato"=>array( "red"=>0xFF,  "green"=>0x63,  "blue"=>0x47),
			"turquoise"=>array( "red"=>0x40,  "green"=>0xE0,  "blue"=>0xD0),
			"violet"=>array( "red"=>0xEE,  "green"=>0x82,  "blue"=>0xEE),
			"wheat"=>array( "red"=>0xF5,  "green"=>0xDE,  "blue"=>0xB3),
			"whitesmoke"=>array( "red"=>0xF5,  "green"=>0xF5,  "blue"=>0xF5),
			"yellowgreen"=>array( "red"=>0x9A,  "green"=>0xCD,  "blue"=>0x32));	
		
	$rgb = "";
	foreach($Colors[trim($Colorname)] as $key => $value) {
		$rgb .= sprintf("%02X", $value);	
	}	
	
return $rgb ; 	
}



// based on code from:
// http://www.bennadel.com/blog/902-Selecting-Contrasting-Text-Color-Based-On-Background-Color.htm
function getContrastingColor($hexcolor) {
	
	// do we have a CSS color name
	// if so we convert and use that
	// otherwise use the param
	$hexcolor_local =  GetColor($hexcolor);
	if ( $hexcolor_local == null ) {
		$hexcolor_local = $hexcolor;
	}
	
	
	define("_BLACK", "000000");
	define("_WHITE", "FFFFFF");
	// determine R, G and B values from the HEX color
	$hexcolor_local = strlen($hexcolor_local) == 7 ? substr($hexcolor_local, 1) : $hexcolor_local;
	if(strlen($hexcolor_local) == 6){
		$r = substr($hexcolor_local,0,2);
		$g = substr($hexcolor_local,2,2);
		$b = substr($hexcolor_local,4,2);

		$brightness = (hexdec($r) * 0.299) + (hexdec($g) * 0.587) + (hexdec($b) * 0.114);
		if ( $brightness <= 125) { return _WHITE; }
	}
	// default
	return _BLACK;
}




$document =& JFactory::getDocument();


$document->addStyleSheet(JURI::base() . 'modules/mod_civicrm_fullcalendar/fullcalendar/demos/cupertino/theme.css', 'text/css', 'screen');
$document->addStyleSheet(JURI::base() . 'modules/mod_civicrm_fullcalendar/elements/colors.css', 'text/css', 'screen');
$document->addStyleSheet(JURI::base() . 'modules/mod_civicrm_fullcalendar/fullcalendar/fullcalendar/fullcalendar.css', 'text/css', 'screen');
$document->addStyleSheet(JURI::base() . 'modules/mod_civicrm_fullcalendar/fullcalendar/fullcalendar/fullcalendar.print.css', 'text/css', 'print');
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
		"color: '$eventcolor', " .
		"textColor: '#".getContrastingColor($eventcolor)."', " .
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


    if ($legend_picked == "1") {
        echo "<div id='mod_civicrm_fullcalendar_legend'>";


        echo "<table id='mod_civicrm_fullcalendar_colorlegend'>".
          "<tr><th>Color Legend</th></tr>";
        for ($i = 0, $n = count($catname); ($i < $n); $i++) {
            echo "<tr><td><span id='colorsquare' style='text-align:center; background-color:" .
              $color[$i] . "'>" . $color[$i] . "</span><span class='text'>" .
              $catname[$i] ."</span></td></tr>";
        }
        echo "</table>";
        } //   if ($legend_picked == "1")






    if  ( ($legend_picked == "2") ||  ($legend_picked == "3") ) {
    	$legendOutput  = "<div id='mod_civicrm_fullcalendar_legend' style=\"background-color: white;";
    	$legendOutput .= "margin-top: 5px; margin-bottom: 5px; ";
    	//$legendOutput .= "padding-top: 5px; padding-bottom: 5px;\">";
    	$legendOutput .= "padding: 5px;";
    	$legendOutput .= "\">";
    	 
    	// how many boexs?
    	$num_o_boxes = count($catname) ;
    	
    	// one more box to say 'legend'
    	// if there is legend text that is not null
    	$legendLabel = trim($displayParams['legendLabel']) ;
    	$legend_offset = 0 ;
    	if ( $legendLabel != null) {
    		$num_o_boxes++;
    		$legend_offset = 1 ;
    	}
    	
		$legendOutput .=  "<div id=\"mod_civicrm_fullcalendar_legend_wrapper\"  >";
		for ($i = 0, $n = $num_o_boxes; ($i < $n); $i++) {
			$legendOutput .= "<div "; 
			$legendOutput .= "style=\""; 
			$legendOutput .= "display:inline-block;";
			$legendOutput .= "text-align: center; ";
			
			
			
			// if this is not the 0th row, put in the color
			// or if this is the 0th row and we don't have legend text, then put in the color
			if (  ( $i > 0) || (  ( $i == 0) && ( $legendLabel == null) )) {
				$legendOutput .= "background-color: ".$color[$i-$legend_offset]."; ";
				$legendOutput .= "color: #".getContrastingColor($color[$i-$legend_offset])."; ";
			}
			// if this is 0th row we have legend text, then spit out the legend text
			else if ( ( $i == 0) && ( $legendLabel != null) ) {
				$legendOutput .= "color: black;";
			}			
			
			
			$legendOutput .= "margin-left: 0.25%;" ;
			$legendOutput .= "vertical-align: middle; ";
			$legendOutput .= "height: 100%;";
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
				// ."-->".getContrastingColor($color[$i-$legend_offset]);
				
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

