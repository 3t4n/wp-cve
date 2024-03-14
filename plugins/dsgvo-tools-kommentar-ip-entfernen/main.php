<?php
/*
Plugin Name: GDPR tools: comment ip removement
Description: Part of GDPR tools package. Removes all ip adresses from comments.
Version: 1.4
Author: fabian heinz webdesign
Author URI: https://www.fabian-heinz-webdesign.de
Text Domain: dsgvo-tools-kommentar-ip-entfernen
License: GPL3
*/

require_once plugin_basename( '/admin/settings.php' );
require_once plugin_basename( '/admin/looper.php' );

add_action( 'init', function () {
	load_plugin_textdomain( 'dsgvo-tools-kommentar-ip-entfernen' );
} );

function fhw_dsgvo_kommentare_ip_entfernen( $ip ) {
	return '';
}
if( 'on' != get_option( 'fhw_dsgvo_kommentar_time_removement' ) )
    add_filter( 'pre_comment_user_ip', 'fhw_dsgvo_kommentare_ip_entfernen' );

register_activation_hook( __FILE__, 'fhw_dsgvo_kommentare_plugin_activation' );
function fhw_dsgvo_kommentare_plugin_activation() {
    if ( ! wp_next_scheduled( 'fhw_dsgvo_kommentare_rotation' ) ) {
        wp_schedule_event( time(), 'daily', 'fhw_dsgvo_kommentare_rotation' );
    }
}

register_deactivation_hook( __FILE__, 'fhw_dsgvo_kommentare_plugin_deactivation' );
function fhw_dsgvo_kommentare_plugin_deactivation() {
    wp_clear_scheduled_hook( 'fhw_dsgvo_kommentare_rotation' );
}