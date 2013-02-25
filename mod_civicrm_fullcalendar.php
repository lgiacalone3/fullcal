<?php
defined( '_JEXEC' ) or die;


require_once( dirname(__FILE__).DS.'elements/EventData.php' );

require_once( dirname(__FILE__).DS.'elements/js_snippet_mcfc_location_filter_change.php' );
require_once( dirname(__FILE__).DS.'elements/js_snippet_eventClick_modal_dialogs.php');


// Include the helper
require_once( dirname(__FILE__).DS.'helper.php' );

$eventtitles   = modCiviCRMFullCalendarHelper::getEventTitles( $params );
$displayParams = modCiviCRMFullCalendarHelper::sendParam( $params );

require( JModuleHelper::getLayoutPath( 'mod_civicrm_fullcalendar' ) );





?>








