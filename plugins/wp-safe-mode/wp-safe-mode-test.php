<?php
//Check to make sure we're able to load safe mode without a fatal error.
$wp_content_location = dirname(dirname(dirname(__FILE__)));
$abspath = dirname($wp_content_location); //ABS_PATH assuming we're in the plugins folder default location, otherwise we don't test this

if( file_exists($abspath.'/wp-load.php') ){
	define( 'WPMU_PLUGIN_DIR', $wp_content_location.'/wp-safe-mode' ); //WP Safe Mode
	require_once( $abspath . '/wp-load.php' );
	wp();
	echo "WP Safe Mode OK";
}else{
	echo "Could not find <strong>wp-load.php</strong> file location. Looking in <strong>$abspath</strong>";
}
exit();