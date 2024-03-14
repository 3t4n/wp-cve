<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.lehelmatyus.com
 * @since             1.0.0
 * @package           terms_popup_on_user_login
 *
 * @wordpress-plugin
 * Plugin Name:       Terms Popup On User Login
 * Plugin URI:        https://www.lehelmatyus.com/wp-plugins/terms-popup-on-user-login
 * Description:       Creates a popup with a scrollable window that will show your own custom Terms and Conditions when users logs in to your website
 * Version:           1.0.54
 * Author:            Lehel Matyus
 * Author URI:        https://www.lehelmatyus.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       terms-popup-on-user-login
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('TERMS_POPUP_ON_USER_LOGIN_VERSION', '1.0.54');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-terms-popup-on-user-login-activator.php
 */
function activate_terms_popup_on_user_login() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-terms-popup-on-user-login-activator.php';
	Terms_Popup_On_User_Login_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-terms-popup-on-user-login-deactivator.php
 */
function deactivate_terms_popup_on_user_login() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-terms-popup-on-user-login-deactivator.php';
	Terms_Popup_On_User_Login_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_terms_popup_on_user_login');
register_deactivation_hook(__FILE__, 'deactivate_terms_popup_on_user_login');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-terms-popup-on-user-login.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_terms_popup_on_user_login() {

	$plugin = new terms_popup_on_user_login();
	$plugin->run();
}
run_terms_popup_on_user_login();
