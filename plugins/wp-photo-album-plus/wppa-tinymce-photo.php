<?php
/* wppa-tinymce-photo.php
* Pachkage: wp-photo-album-plus
*
* Version 8.1.08.003
*
*/

if ( ! defined( 'ABSPATH' ) )
    die( "Can't load this file directly" );

add_action( 'init', 'wppa_tinymce_photo_action_init' ); // 'admin_init'

function wppa_tinymce_photo_action_init() {

	if ( wppa_switch( 'photo_shortcode_enabled' ) ) {

		add_filter( 'mce_buttons', 'wppa_filter_mce_photo_button', 11 );
		add_filter( 'mce_external_plugins', 'wppa_filter_mce_photo_plugin' );
	}
}

function wppa_filter_mce_photo_button( $buttons ) {

	// add a separation before our button.
	array_push( $buttons, ' ', 'wppa_photo_button' );
	return $buttons;
}

function wppa_filter_mce_photo_plugin( $plugins ) {

	// this plugin file will work the magic of our button
	$file = 'js/wppa-tinymce-photo.js';

	$plugins['wppaphoto'] = plugin_dir_url( __FILE__ ) . $file;
	return $plugins;
}
