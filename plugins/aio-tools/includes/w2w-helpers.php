<?php
//Check Current Plugin Active
if ( ! function_exists( 'w2w_check_active_plugin' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	function w2w_check_active_plugin($plugin){
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || is_plugin_active_for_network( $plugin );
	}
}

//Check Current Theme Active
if ( ! function_exists( 'w2w_check_active_theme' ) ) {	
	function w2w_check_active_theme($theme){
		return ( $theme == get_option( 'template' ) );
	}
}

