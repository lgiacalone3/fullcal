<?php
class modCiviCRMFullCalendarHelper
{

    // show online member names
    function getEventTitles(&$params)
    {
        jimport('joomla.application.module.helper');

         
		// let's use the JDatabaseQuery class for assembly
		// see more here:
		//   http://www.theartofjoomla.com/9-developer/135-database-upgrades-in-joomla-16.html
		// and
		//   http://docs.joomla.org/API16:JDatabaseQuery/select
        jimport( 'joomla.database.databasequery' );

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("e.title, e.id AS eventID, e.start_date, e.end_date, ".
        		"e.event_type_id, e.summary, e.is_active, ".
        		"e.is_public, e.is_online_registration");
        $query->from('civicrm_event e') ;
        $query->where('e.is_active = 1 AND e.is_template != 1');
        $query->order('e.start_date ASC');

        //disable past events setting
        $showpastevents =  trim($params->get('showpastevents'));
        $currentdate = date("Y-m-d");
        if (! $showpastevents){
        	// OK, so hide past events, or events that are not in the future ....
        	//only view events that end on or after current date, or where no end_date is defined
        	$query->where("( e.end_date >= '" . $currentdate . "' OR e.end_date IS NULL OR e.end_date = '') ");
        	// OR e.end_date = \'\'");
        	//only view events that start on or after current date
        	$query->where("e.start_date >= '" . $currentdate ."'");
        }

        
        
        
        //determine privacy setting
        $privacy = trim($params->get('privacy'));
        switch ($privacy) {
        	case(0):
        		{
        			// both public or private
        			break;
        		}
        	case(1):
        		{
        			// public only
        			$query->where("e.is_public = 1 ");
        			break;
        		}
        	case(2):
        		{
        			// private only
        			$query->where("e.is_public = 0 ");
        			break;
        		}
        }
        
        
        //determine display mode
        $mode = trim($params->get('mode'));
        switch ($mode) {
        	case(0): //default mode
        		{
        			break;
        		}

        	case(1): //date range mode
        		{
        			$startdate = trim($params->get('startdate'));
    		    	$enddate = trim($params->get('enddate'));


    		    	//Rewrite date format
        			if ($startdate) {
        				$startdate_formatted = date('Y-m-d', strtotime($startdate));
        			}
    		    	if ($enddate) {
        				$enddate_formatted = date('Y-m-d', strtotime($enddate));
    		    	}

    		    	/*
    		    	echo "\$startdate: ".$startdate."<BR />" ; // debug
    		    	echo "\$enddate: ".$enddate."<BR />" ; // debug
    		    	echo "\$startdate_formatted: ".$startdate_formatted."<BR />" ; // debug
        			echo "\$enddate_formattted: ".$enddate_formatted."<BR />" ; // debug
					*/
    		    	
        			if ($startdate == "" OR $startdate == "Select") //no start date selected, throw error
        			{
        				JError::raiseWarning(500, "No start date selected for mod_civicrmfullcalendar module.");
        			} elseif ($enddate == "" OR $enddate == "Select") //Open end date parameter
        			{
        				$query->where("e.start_date >= '" . $startdate_formatted . "'");

        			} else //both start and end date parameter, replace default end date range
        			{
        				$query->where("e.start_date >= '" . $startdate_formatted . "' AND " .
        						"e.end_date <= '" . $enddate_formatted . "'");
        			}
        			break;
        		}

        	case(2): //event type mode
        		{
        			$tid = trim($params->get('tid'));
        			$query->where("e.event_type_id =".$tid);
        			break;
        		}
        } //close mode switch

        
        
        // do we even need the location?  ... don't forget, some events MIGHT NOT HAVE A LOCATION!
        if (
        		(stristr(trim($params->get('fieldtemplate')), "{event_street_address}") != FALSE) ||
        		(stristr(trim($params->get('fieldtemplate')), "{event_supplemental_address_1}") != FALSE) ||
        		(stristr(trim($params->get('fieldtemplate')), "{event_supplemental_address_2}") != FALSE) ||
        		$params->get('filterOnLocation') === "1"
        ) {
        	// get the event location data
        	$query->select( "a.street_address, a.supplemental_address_1, a.supplemental_address_2 ");
		$query->leftJoin('civicrm_loc_block lb ON e.loc_block_id = lb.id')->leftJoin('civicrm_address a ON a.id = lb.address_id'); 
        }

        // DEBUG echo $query ;  // DEBUG
        
        //run $query;
        $db->setQuery($query);
        $result = $db->loadObjectList();
        
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, "No events meet the selected criteria.");
        }


        return $result;

    } //end getEventTitles

    function sendParam(&$params)
    {
        $displayParams['link'] = trim($params->get('link'));
        $displayParams['tid'] = trim($params->get('tid'));
        $displayParams['modal'] = trim($params->get('modal'));
        $displayParams['maxevents'] = trim($params->get('maxevents'));
        $displayParams['showdates'] = trim($params->get('showdates'));
        //$displayParams['showduration'] = trim($params->get('showduration'));
        $displayParams['dateformat'] = trim($params->get('dateformat'));
        //$displayParams['summary'] = trim($params->get('summary'));
        $displayParams['colorby'] = trim($params->get('colorby'));
        $displayParams['cssskinname'] = trim($params->get('cssskinname'));
        $displayParams['color1'] = trim($params->get('color1'));
        $displayParams['color2'] = trim($params->get('color2'));
        $displayParams['color3'] = trim($params->get('color3'));
        $displayParams['color4'] = trim($params->get('color4'));
        $displayParams['color5'] = trim($params->get('color5'));
        $displayParams['color6'] = trim($params->get('color6'));
        $displayParams['showpastevents'] = trim($params->get('showpastevents'));
        $displayParams['colorpicker'] = trim($params->get('colorpicker'));
        $displayParams['legendpicker'] = trim($params->get('legendpicker'));
        $displayParams['customcolors'] = trim($params->get('customcolors'));
        $displayParams['defalt_view'] = trim($params->get('default_view'));
        $displayParams['weekMode'] = trim($params->get('weekMode'));
        $displayParams['firstHour'] = trim($params->get('firstHour'));
        $displayParams['slotMinutes'] = trim($params->get('slotMinutes'));
        $displayParams['aspectRatio'] = trim($params->get('aspectRatio'));
        $displayParams['axisFormat'] = trim($params->get('axisFormat'));
        $displayParams['showCalNav'] = trim($params->get('showCalNav'));
        $displayParams['legendLabel'] = trim($params->get('legendLabel'));
        $displayParams['eventTextColor'] = trim($params->get('eventTextColor'));
        $displayParams['useHighContrast'] = trim($params->get('useHighContrast'));
        $displayParams['advancedoptions'] = trim($params->get('advancedoptions'));
        $displayParams['fieldtemplate'] = trim($params->get('fieldtemplate'));
        $displayParams['agenda_timeFormat'] = trim($params->get('agenda_timeFormat'));
        $displayParams['allother_timeFormat'] = trim($params->get('allother_timeFormat'));
        $displayParams['filterOnLocation'] = trim($params->get('filterOnLocation'));
        $displayParams['filterTextForAllLocations'] = trim($params->get('filterTextForAllLocations'));
        $displayParams['calendar_titleFormat_month'] = trim($params->get('calendar_titleFormat_month'));
        $displayParams['calendar_titleFormat_week'] = trim($params->get('calendar_titleFormat_week'));
        $displayParams['calendar_titleFormat_day'] = trim($params->get('calendar_titleFormat_day'));
        $displayParams['locationFilterField']  = trim($params->get('locationFilterField'));
        $displayParams['filterBoxNumberOfRows']  = trim($params->get('filterBoxNumberOfRows'));
        
        
        return $displayParams;
    } //end sendParams




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
    function getHighContrastColor($hexcolor) {
    
    	// do we have a CSS color name
    	// if so we convert and use that
    	// otherwise use the param
    	$hexcolor_local =  modCiviCRMFullCalendarHelper::GetColor($hexcolor);
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
    

}

?>
