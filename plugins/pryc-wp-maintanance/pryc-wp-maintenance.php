<?php
/*
 * Plugin Name: PRyC WP: Maintanance
 * Plugin URI:
 * Description: A simple plugin for the maintenance mode
 * Author: PRyC
 * Author URI:
 * Version: 1.0.2
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;

function pryc_wp_maintenance() {
	if ( !current_user_can( 'manage_options' ) ) {
		echo '<!DOCTYPE html><head><meta charset="utf-8" /><meta name="robots" content="noindex"><title>' . get_bloginfo( 'name' ) . '</title></head><body><a href="' . get_admin_url() . '">Login</a> | Maintanance | Technical break | Przerwa techniczna | Technischer bruch | Pause technique | Pausa tecnica | Descanso técnico | Pausa técnica | Технический перерыв | Teknik mola | 技术突破。</body></html>';
		
		die;
	}
}
add_action( 'template_redirect', 'pryc_wp_maintenance' );

