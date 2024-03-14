<?php
/**
 * Plugin Name: Simple WP Maintenance Mode
 * Plugin URI: http://www.wsiconsultores.com.br/plugins/swpmm
 * Description: This tiny plugin actives the maintenance mode with standard messages from WordPress.
 * Version: 1.0
 * Author: Lucas
 * Author URI: https://profiles.wordpress.org/lucascaires
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

function simple_maintenace_mode() {        
    if ( !current_user_can( 'edit_themes' ) || !is_user_logged_in() ) {
        $html = '<h1>'.__('Maintenance').'</h1>';
        $html .= '<p>'.__('Briefly unavailable for scheduled maintenance. Check back in a minute.').'</p>';
        wp_die($html);
    }
}
add_action('get_header', 'simple_maintenace_mode');
