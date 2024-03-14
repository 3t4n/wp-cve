<?php
/*
Plugin Name:       Exclude Pages From Menu
Plugin URI:        https://wordpress.org/plugins/exclude-pages-from-menu/
Description:       The plugin provides option in the page edit screen to remove page from navigation menu in the front end of site.
Version:           3.0
Author:            Vinod Dalvi
Author URI:        https://profiles.wordpress.org/vinod-dalvi/
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path:       /languages
Text Domain:       exclude-pages-from-menu

Exclude Pages From Menu plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Exclude Pages From Menu plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Exclude Pages From Menu plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

/**
 * The file responsible for starting the Exclude Pages From Menu plugin
 *
 * This particular file is responsible for including the necessary dependencies and starting the plugin.
 *
 * @package EPFM
 */


/**
 * If this file is called directly, then abort execution.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-exclude-pages-from-menu-activator.php
 */
function activate_exclude_pages_from_menu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-exclude-pages-from-menu-activator.php';
	Exclude_Pages_From_Menu_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-exclude-pages-from-menu-deactivator.php
 */
function deactivate_exclude_pages_from_menu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-exclude-pages-from-menu-deactivator.php';
	Exclude_Pages_From_Menu_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_exclude_pages_from_menu' );
register_deactivation_hook( __FILE__, 'deactivate_exclude_pages_from_menu' );


/**
 * Include the core class responsible for loading all necessary components of the plugin.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-exclude-pages-from-menu.php';

/**
 * Instantiates the Exclude Pages From Menu class and then
 * calls its run method officially starting up the plugin.
 */
function run_exclude_pages_from_menu() {
	$ewpd = new Exclude_Pages_From_Menu();
	$ewpd->run();
}

/**
 * Call the above function to begin execution of the plugin.
 */
run_exclude_pages_from_menu();
