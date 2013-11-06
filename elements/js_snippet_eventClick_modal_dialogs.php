<?php
defined( '_JEXEC' ) or die;


$js_snippet_eventClick_modal_dialogs = <<<'ECMD'
,  // don't forget that comma
	eventClick: function(event) {
    	    if (event.url) {
				//$cfcj('a[id$="aref_mod_civicrm_fullcalendar_dialog"]').attr("href", event.url   );
				$('a[id$="aref_mod_civicrm_fullcalendar_dialog"]').attr("href", event.url   );		

		        // this is correct with just $() 
		        // RESTORE $('aref_mod_civicrm_fullcalendar_dialog' ).click() ;
	        	//$('a[id$="aref_mod_civicrm_fullcalendar_dialog.modal"]').click();
		//  alert( $('aref_mod_civicrm_fullcalendar_dialog' ).click()  );
				return false;
	        } // if
	} // eventClick: function(event)
		
		

ECMD;



?>
