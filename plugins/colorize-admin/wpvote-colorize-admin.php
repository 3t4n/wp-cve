<?php
/*
Plugin Name: Colorize Admin
Plugin URI: http://wordpress.org/plugins/colorize-admin
Description: This is a simple plugin that will make your wp admin panel theme much more pleasant for work.
Author: cicophoto
Version: 2.0
Author URI: https://wordpress.org/plugins/colorize-admin
License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

/* No direct access */
if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );
/* Call install */
register_activation_hook( __FILE__ ,'install_cssadmin_theme');
register_deactivation_hook(__FILE__,'uninstall_cssadmin_theme');
require plugin_dir_path( __FILE__ ) . 'inc/install.php';
/*Disable admin color default color themes  */
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
 /* Load plugin textdomain for language files.*/
           add_action( 'plugins_loaded', 'colorizeadmin_load_textdomain' );
           function colorizeadmin_load_textdomain() { load_plugin_textdomain( 'colorize-admin', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); }
           /* Add css styles to plugin */
           add_action( 'admin_print_styles', 'colorizeadmin_add_init' );
           add_action( 'wp_enqueue_scripts', 'colorizeadmin_add_init' );
           function colorizeadmin_add_init() { if ( is_admin() ) {wp_enqueue_style("functions", plugins_url( '/colorize/'.get_option('_colorthemeadmin').'_style.css', __FILE__ ), false, "1.0", "all"); }
           wp_enqueue_style("functions-admin", plugins_url( '/colorize/'.get_option('_colorthemeadmin').'_admin.css', __FILE__ ), false, "1.0", "all"); }
           require plugin_dir_path( __FILE__ ) . 'inc/settings.php';
/* Call Admin options */
require plugin_dir_path( __FILE__ ) . 'inc/admin-options.php';


?>