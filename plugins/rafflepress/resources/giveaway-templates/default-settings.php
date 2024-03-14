<?php

$wp_timezone_string           = get_option( 'timezone_string' );
$rafflepress_default_timezone = 'UTC';
if ( ! empty( $wp_timezone_string ) ) {
	$rafflepress_default_timezone = $wp_timezone_string;
}
$rafflepress_default_settings = '{  
    "api_key":"",
    "updates":"none",
    "updates_to":"",
    "slug":"rafflepress",
    "default_timezone":"' . $rafflepress_default_timezone . '"
 }';
