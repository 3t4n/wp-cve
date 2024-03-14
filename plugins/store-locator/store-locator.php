<?php
/*
Plugin Name: Store Locator for WordPress with Google Maps – LotsOfLocales
Plugin URI: http://www.viadat.com/store-locator/
Description: A full-featured map maker & location management interface for creating WordPress store locators and address location maps using Google Maps, featuring several addons & themes.  Manage a few or thousands of locations effortlessly with setup in minutes.
Version: 3.98.9
Author: Viadat Creations
Author URI: http://www.viadat.com
Text Domain: store-locator
Domain Path: /sl-admin/languages/
License: GPLv3

Store Locator for WordPress with Google Maps – LotsOfLocales
Copyright (C) 2008 - 2024 Viadat Creations | info [at] viadat.com

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses>.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$sl_version="3.98.9";
define('SL_VERSION', $sl_version);
$sl_db_version=3.0;
include_once("sl-define.php");

include_once(SL_INCLUDES_PATH."/copyfolder.lib.php");

add_action('admin_menu', 'sl_add_options_page');
add_action('wp_head', 'sl_head_scripts', 1001);


include_once("sl-functions.php");
include_once(SL_INCLUDES_PATH."/via-latest.php");
include_once(SL_INCLUDES_PATH."/update-keys.php");

register_activation_hook( __FILE__, 'sl_install_tables');

add_action('the_content', 'sl_template');
	
if (preg_match("@$sl_dir@", $_SERVER['REQUEST_URI'])) {
	add_action("admin_print_scripts", 'sl_add_admin_javascript');
	add_action("admin_print_styles",'sl_add_admin_stylesheet');
}

//better translation load - v3.98.4 - 2/26/19 - absolute / variable path, instead of relative / hardcoded path
load_textdomain("store-locator", SL_LANGUAGES_PATH . "/store-locator-" . get_locale() . ".mo");

// -- cleaned up unused function code - v3.98.4 - 2/26/19

function sl_update_db_check() {
    global $sl_db_version;
    if (sl_data('sl_db_version') != $sl_db_version) {
        sl_install_tables();
    }
}
add_action('plugins_loaded', 'sl_update_db_check');

/*add_action('activated_plugin','save_error');
function save_error(){
    update_option('plugin_error',  ob_get_contents());
}*/


?>