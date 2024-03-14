<?php
/**
 * Plugin Name:       WP Front-end login and register
 * Plugin URI:        http://webprepration.com/
 * Description:       This plugin will help you to add ajax enabled custom login/register form on your website in just few minutes.
 * Version:           2.1.0
 * Author:            Mohsin khan
 * Author URI:        https://profiles.wordpress.org/mohsin-khan
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Function that runs during plugin activation.
 */
function activate_wp_mp_register_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mp-register-login-activator.php';
	Wp_Mp_Register_Login_Activator::activate();
}

/**
 * Function that runs during plugin deactivation.
 */
function deactivate_wp_mp_register_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-mp-register-login-deactivator.php';
	Wp_Mp_Register_Login_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_mp_register_login' );
register_deactivation_hook( __FILE__, 'deactivate_wp_mp_register_login' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-mp-register-login.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_wp_mp_register_login() {

	$plugin = new Wp_Mp_Register_Login();
	$plugin->run();

}
run_wp_mp_register_login();
