<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/*
Plugin Name: Candid Advanced Toolset
Description: Demo Import for Candid Themes.
Version:     1.0.9
Author:      Candid Themes
Author URI:  http://www.candidthemes.com
License:     GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: candid-advanced-toolset
*/

/**
 * Check the required theme is installed and activated or not.
 */
$candid_themes_name = wp_get_theme(); // gets the current theme

/**
 * Condition for Grip Demo Import.
*/
if ( 'Grip' == $candid_themes_name->name || 'Grip' == $candid_themes_name->parent_theme ) {

    require plugin_dir_path( __FILE__ ) . '/grip-dummy-data/candid-grip-demo-import.php';
}
/**
 * Condition for Grip Pro Demo Import.
*/
if ( 'Grip Pro' == $candid_themes_name->name || 'Grip Pro' == $candid_themes_name->parent_theme ) {

	 require plugin_dir_path( __FILE__ ) . '/grip-pro-dummy-data/candid-grip-pro-demo-import.php';
}

/**
 * Condition for Refined Magazine Demo Import.
*/
if ( 'Refined Magazine' == $candid_themes_name->name || 'Refined Magazine' == $candid_themes_name->parent_theme ) {

	 require plugin_dir_path( __FILE__ ) . '/refined-magazine-dummy-data/candid-refined-magazine-demo-import.php';
}

/**
 * Condition for Engage Mag Demo Import.
*/
if ( 'Engage Mag' == $candid_themes_name->name || 'Engage Mag' == $candid_themes_name->parent_theme ) {

	 require plugin_dir_path( __FILE__ ) . '/engage-mag-dummy-data/candid-engage-mag-demo-import.php';
}