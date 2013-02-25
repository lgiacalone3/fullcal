<?php

defined('JPATH_BASE') or die();



class mcfcEventsArray extends JObject
{

	function buildArray($eventtitles, $params)
	{
		
		$displayParams = modCiviCRMFullCalendarHelper::sendParam( $params );
		
		//$fooRetVal ="";
		//
		//
		//$fooRetVal = "\n\ndocument.write('foobar<BR>";
		//
		//$fooRetVal .= "\displayParams[filterOnStreetAddress] = ".$displayParams['filterOnStreetAddress'] ; 
		
		//$fooRetVal .= "<BR>";
		//$fooRetVal .= "');\n";
	
		// return $fooRetVal ; 
		
		

	

		
	} // buildArray
		
		
	
	
		function buildArrayDEBUG()
	{
	
		$fooRetVal = <<<'EAEA'
				
		var myEventsArrayALL =  [
				{
				id: '2', title: 'Abbreviated Service with Holy Communion (Chapel)', 
				eventLoc : 'Chapel',
				
				textColor: '#FFFFFF', 
				allDay: false, 
				start: new Date(2013, 1, 3, 9, 00), 
				end: new Date(2013, 1, 3, 10, 00), 
				url: 'index.php/component/civicrm/?task=civicrm/event/info&reset=1&id=2&tmpl=component' ,
				color: 'blue' 
			},
				{
				id: '26', title: 'Congregation Council Meeting (Church Office)', 
				eventLoc : 'Church Office',
				
				textColor: '#FFFFFF', 
				allDay: false, 
				start: new Date(2013, 1, 5, 19, 00), 
				end: new Date(2013, 1, 5, 21, 00), 
				url: 'index.php/component/civicrm/?task=civicrm/event/info&reset=1&id=26&tmpl=component' ,
				color: 'green' 
			}
			];		
		
		
EAEA;

		
return $fooRetVal ; 


	} // buildArrayDEBUG
}



?>