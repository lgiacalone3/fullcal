<?php
defined( '_JEXEC' ) or die;

// Include the helper
require_once( dirname(__FILE__).DS.'helper.php' );

$eventtitles   = modCiviCRMFullCalendarHelper::getEventTitles( $params );
$displayParams = modCiviCRMFullCalendarHelper::sendParam( $params );

require( JModuleHelper::getLayoutPath( 'mod_civicrm_fullcalendar' ) );

//echo "This is your test module!";

?>








