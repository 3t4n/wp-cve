<?php
/* wppa-tinymce-photo-front.php
* Pachkage: wp-photo-album-plus
*
* Version 8.1.08.003
*
*/

if ( ! defined( 'ABSPATH' ) )
    die( "Can't load this file directly" );

add_action( 'init', 'wppa_tinymce_photo_action_init_front' );

function wppa_tinymce_photo_action_init_front() {

	if ( wppa_switch( 'photo_shortcode_enabled' ) && wppa_opt( 'photo_shortcode_fe_type' ) != '-none-' ) {

		add_filter( 'mce_buttons', 'wppa_filter_mce_photo_button_front', 11 );
		add_filter( 'mce_external_plugins', 'wppa_filter_mce_photo_plugin_front' );
	}
}

function wppa_filter_mce_photo_button_front( $buttons ) {

	// add a separation before our button.
	array_push( $buttons, ' ', 'wppa_photo_button' );
	return $buttons;
}

function wppa_filter_mce_photo_plugin_front( $plugins ) {

	// this plugin file will work the magic of our button
	$file = 'js/wppa-tinymce-photo-front.js';

	$plugins['wppaphoto'] = plugin_dir_url( __FILE__ ) . $file;
	return $plugins;
}
