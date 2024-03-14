<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function rs_eucc_css() {
	wp_register_style( 'rs_eucc_css', RS_EUCC__PLUGIN_URL.RS_EUCC__BASE.'admin-css/style.css', array(), RS_EUCC__PLUGIN_VERSION, 'screen' );
	if( ( is_admin() ) && ( array_key_exists( 'page', $_GET ) ) ) {
		if( $_GET['page'] == RS_EUCC__ADMIN_PAGE ) { wp_enqueue_style( 'rs_eucc_css' ); }
	}
}

function rs_eucc_plugin_links( $links, $file ) {
	$plugin_file = explode( RS_EUCC__PLUGIN_DIR_NAME.'/', RS_EUCC__PLUGIN_FILE );
	$plugin_file = RS_EUCC__PLUGIN_DIR_NAME.'/'.$plugin_file[1];
	if( $file == $plugin_file ) {
		$settings = '<a href="'.RS_EUCC__PLUGIN_ADMIN_URL.'">Settings</a>';
		array_unshift( $links, $settings );
	}
	return $links;
}