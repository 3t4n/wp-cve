<?php

$infusionsoft_app_name = get_option( 'infusionsoft_app_name', '' );
$infusionsoft_api_key = get_option( 'infusionsoft_api_key', '' );

$connInfo = array( $infusionsoft_app_name . ':' . $infusionsoft_app_name . ':i:' . $infusionsoft_api_key . ':This is the connection for ' . $infusionsoft_app_name . '.infusionsoft.com');

?>
