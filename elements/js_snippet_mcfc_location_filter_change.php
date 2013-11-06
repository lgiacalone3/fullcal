<?php
defined( '_JEXEC' ) or die;


$js_snippet_mcfc_location_filter_mouseup = <<<'MUMU'


//$cfcj('#mcfc_location_filter')
$('#mcfc_location_filter')
		.change(
		function() {

		
			// clear out the array,
			myEventsArray = [];

			// Let's see what got selected
			//var selected_elements = $cfcj('#mcfc_location_filter').val() || [];
			var selected_elements = $('#mcfc_location_filter').val() || [];
		
			// DEBUG alert("You have selected:\n" + selected_elements.join(", \n") + "\n for a total of " + selected_elements.length + " elements.");

			// Did the user's choice include "ALL LOCATIONS" or
			// not filtering on the English value the 0th (first)
			// element of the locations list

			// if clone the full data array with a by reference copy and
			// the heavy lifting is done, refresh below
			// DEBUG alert("selected_elements[0] is " + selected_elements[0] + "\nallLocationsText is " + allLocationsText);
		
			if (selected_elements[0] === allLocationsText[0]) {
				myEventsArray = myEventsArrayALL.slice(0, myEventsArrayALL.length);
				// DEBUG alert('YES, selected_elements[0] === allLocationsText[0] ');
			} 
		    else {
				// DEBUG alert('NO, selected_elements[0] === allLocationsText where ' + selected_elements[0] +" and "+ allLocationsText[0]  );
				// need to loop on selected_elements
				for ( var se = 0; se < selected_elements.length; se++) {

					var locationToFind = selected_elements[se];

					// loop through it, and copy by
					// reference the events
					// that match our search term(s)
					for ( var i = 0, j = myEventsArray.length; i < myEventsArrayALL.length; i++) {
						if (myEventsArrayALL[i].eventLoc === locationToFind) {
							myEventsArray[j] = myEventsArrayALL[i];
							j++;
						} // if
					} // for
				} // for loop on selected_elements
			} // if ALL LOCATONS else ...

			//$cfcj('#calendar').fullCalendar('removeEvents');
		    $('#calendar').fullCalendar('removeEvents');

			// DELETE // $cfcj('#calendar').fullCalendar( 'refetchEvents' );

			//$cfcj('#calendar').fullCalendar('addEventSource',
		    $('#calendar').fullCalendar('addEventSource',
					myEventsArray);

		}); // END cfcj('#mcfc_location_filter').change(function()

		

		
MUMU;



?>
