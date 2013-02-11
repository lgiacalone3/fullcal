<?php
defined( '_JEXEC' ) or die;


$js_snippet_mcfc_location_filter_mouseup = <<<'MUMU'

	$cfcj('#mcfc_location_filter')
			.bind(
					'mouseup',
					function() {
			
						// clear out the array,
						myEventsArray = [];
			
						// Let's see what got selected
						//var txtDEBUG = "";
						var selected_elements = new Array();
						// lets examine the values
						// for HTMLOptionElement
						// in our select
						$cfcj('#mcfc_location_filter')
						.children()
						.each(
								function() {
									if ($(this).selected) {
										//txtDEBUG += $(this).value + ': ' + $(this).label + '\n';
										selected_elements[selected_elements.length] = $(this).label;
									}
								}); // children().each( function)
								//alert('You have selected:\n' + txtDEBUG + " for a total of " +  selected_elements.length + " elements.");
			
								// Did the user's choice include "ALL LOCATIONS" or
								// not filtering on the English value the 0th element of the locations list
								// if clone the full data array with a by reference copy and
								// the heavy lifting is done, refresh below
								if (selected_elements[0] === allLocationsText) {
								myEventsArray = myEventsArrayALL
									.slice(
											0,
											myEventsArrayALL.length);
								} else {
			
								// need to loop on selected_elements
									for ( var se = 0; se < selected_elements.length; se++) {
			
										var locationToFind = selected_elements[se];
			
										// loop through it, and copy by reference the events
										// that match our search term(s)
										for ( var i = 0, j = myEventsArray.length; i < myEventsArrayALL.length; i++) {
										if (myEventsArrayALL[i].eventLoc === locationToFind) {
										myEventsArray[j] = myEventsArrayALL[i];
										j++;
										} // if
										} //for
										} // for loop on selected_elements
										} // if ALL LOCATONS else ...
			
										$cfcj('#calendar').fullCalendar(
										'removeEvents');
										// $cfcj('#calendar').fullCalendar( 'refetchEvents' );
										$cfcj('#calendar').fullCalendar(
										'addEventSource',
												myEventsArray);
			
					}); // bind on mouseup
			
MUMU;



?>
