<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           NL_Zalo_Officical_Chat
 *
 * @wordpress-plugin
 * Plugin Name:       Zalo Official Live Chat
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Luu Trong Nghia
 * Author URI:        http://haita.media/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nl-zalo-officical-chat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NL_Zalo_Official_Chat__VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_nl_zalo_official_chat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nl-zalo-official-chat-activator.php';
	NL_Zalo_Official_Chat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_nl_zalo_official_chat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nl-zalo-official-chat-deactivator.php';
	NL_Zalo_Official_Chat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nl_zalo_official_chat' );
register_deactivation_hook( __FILE__, 'deactivate_nl_zalo_official_chat' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nl-zalo-official-chat.php';

add_filter('plugin_action_links', 'nl_zalo_official_add_action_links', 10, 2);

function nl_zalo_official_add_action_links($links, $file) {
	if($file == plugin_basename(dirname(__FILE__) . '/nl-zalo-official-chat.php')) {
		$links[] = '<a href="options-general.php?page=zalo-oa-chat">'.__('Settings', 'zalooachat').'</a>';
	}

	return $links;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nl_zalo_official_chat() {

	$plugin = new NL_Zalo_Official_Chat();
	$plugin->run();

}
run_nl_zalo_official_chat();
