<?php
/**
 * Constants definations.
 */
$api_url = apply_filters( 'demo_importer_plus_api_url', 'https://rishidemos.com/' );
$id      = apply_filters( 'demo_importer_plus_api_id', array() );

if ( ! defined( 'DEMO_IMPORTER_PLUS_MAIN_DEMO_URI' ) ) {
	define( 'DEMO_IMPORTER_PLUS_MAIN_DEMO_URI', $api_url );
}

if ( ! defined( 'DEMO_IMPORTER_PLUS_MAIN_DEMO_ID' ) ) {
	define( 'DEMO_IMPORTER_PLUS_MAIN_DEMO_ID', $id );
}
