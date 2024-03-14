<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/*
Plugin Name: Blockskit Import
Plugin URI:  
Description: A easy plugin to import starter sites.
Version:     0.0.6
Author:      BlockskitDev
Author URI:  
License:     GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Domain Path: /languages
Text Domain: blockskit-import
*/

define( 'BLOCKSKIT_TEMPLATE_URL', plugin_dir_url( __FILE__ ) );
define( 'BLOCKSKIT_IMPORT_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Returns the currently active theme's name.
 *
 * @since    0.0.1
 */
function blockskit_import_get_theme_slug(){
    $demo_theme = wp_get_theme();
   	return $demo_theme->get( 'TextDomain' );
}

/**
 * Returns the currently active theme's screenshot.
 *
 * @since    0.0.1
 */
function blockskit_import_get_theme_screenshot(){
	$demo_theme = wp_get_theme();
    return $demo_theme->get_screenshot();
}
/**
 * The core plugin class that is used to define internationalization,admin-specific hooks, 
 * and public-facing site hooks..
 *
 * @since    0.0.1
 */   
require BLOCKSKIT_IMPORT_PATH . 'demo/functions.php';

require BLOCKSKIT_IMPORT_PATH . 'includes/admin-notices.php';

/**
 * Register all of the hooks related to the admin area functionality
 * of the plugin.
 *
 * @since    0.0.1
 */
$plugin_admin = blockskit_import_hooks();
add_filter( 'advanced_import_demo_lists', array( $plugin_admin,'blockskit_import_demo_import_lists'), 10, 1 );
add_filter( 'admin_menu', array( $plugin_admin, 'import_menu' ), 10, 1 );
add_filter( 'wp_ajax_blockskit_import_getting_started', array( $plugin_admin, 'install_advanced_import' ), 10, 1 );
add_filter( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ), 10, 1 );
add_filter( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ), 10, 1 );
add_filter( 'advanced_export_include_options', array( $plugin_admin, 'blockskit_import_include_options' ), 10, 1 );
add_action( 'advanced_import_replace_post_ids', array( $plugin_admin, 'blockskit_import_replace_attachment_ids' ), 30 );