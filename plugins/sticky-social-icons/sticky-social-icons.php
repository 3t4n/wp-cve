<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.sanil.com.np
 * @since             1.0.0
 * @package           Sticky_Social_Icons
 *
 * @wordpress-plugin
 * Plugin Name:       Sticky Social Icons
 * Plugin URI:        http://www.sanil.com.np
 * Description:       Show your favorite social media icons into the side of your website screen.
 * Version:           1.2.1
 * Requires at least: 5.0
 * Requires PHP:      5.6
 * Author:            Sanil Shakya
 * Author URI:        http://www.sanil.com.np
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sticky-social-icons
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 */
define('STICKY_SOCIAL_ICONS_VERSION', '1.2.1');
define('STICKY_SOCIAL_ICONS_ENVIRONMENT', 'live');
define('STICKY_SOCIAL_ICONS_DB_INITIALS', 'sanil_ssi_db_');
define('STICKY_SOCIAL_ICONS_DEFAULTS', array(160, 16, 54, 54)); // offset, font size, width, height

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sticky-social-icons-activator.php
 */
function activate_sticky_social_icons() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-sticky-social-icons-activator.php';
	Sticky_Social_Icons_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sticky-social-icons-deactivator.php
 */
function deactivate_sticky_social_icons() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-sticky-social-icons-deactivator.php';
	Sticky_Social_Icons_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_sticky_social_icons');
register_deactivation_hook(__FILE__, 'deactivate_sticky_social_icons');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-sticky-social-icons.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sticky_social_icons() {

	$plugin = new Sticky_Social_Icons();
	$plugin->run();
}
run_sticky_social_icons();
