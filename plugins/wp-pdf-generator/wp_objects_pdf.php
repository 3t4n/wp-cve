<?php
/**
* Plugin Name: WP PDF Generator
* Plugin URI: https://wpexperts.io/products/wordpress-pdf-generator/
* Description: Easy Web to PDF Download
* Version: 1.2.3
* Author: wpexperts.io
* Author URI: https://wpexperts.io/
* Requires at least: WP 3.0.1
* Tested up to: 6.2.2
* License: GPLv2 or later
* Text Domain: wpexperts
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/
if ( ! defined( 'ABSPATH' ) ) exit;
// Define Plugin Version and compatibility.
define( 'WPEXPERTS_PDF_VERSION', '1.2.3' );
define( 'WPEXPERTS_PDF_MINIMUM_WP_VERSION', '4.9.8' );
// Define Plugin Url.
define( 'WPEXPERTS_PDF_URL', plugin_dir_url(__FILE__));
define( 'WPEXPERTS_PDF_ASSETS', WPEXPERTS_PDF_URL . "assets/");
// Define Plugin Dir Paht.
define( 'WPEXPERTS_PDF_DIR', dirname(__FILE__) . '/' );
define( 'WPEXPERTS_PDF_LANG', WPEXPERTS_PDF_DIR . "languages/");
/**
 * 
 * Here we define plugin action hook
 * it will add link in plugin action bar
 * 
 */
function wpexperts_pdf_plugin_action_bar( $actions, $plugin_file ){
    static $plugin;
    if (!isset($plugin))
        $plugin = plugin_basename(__FILE__);
    if ( $plugin == $plugin_file ) {
        $wpexperts_pdf = array('settings' => '<a href="'.admin_url( 'admin.php?page=pdf-settings' ).'">' . __('Settings', 'wpexperts') . '</a>');
        $actions = array_merge( $wpexperts_pdf, $actions );
    }
    return $actions;
}
add_filter( 'plugin_action_links', 'wpexperts_pdf_plugin_action_bar', 10, 5 );
/**
 * 
 * Here we define plugin action hook
 * it will add link in plugin action bar
 * 
 */ 
function wpexperts_pdf_plugin_row( $links, $file ) {    
    if ( plugin_basename( __FILE__ ) == $file ) {
        $row_settings = array('settings' => '<a href="'.admin_url('admin.php?page=pdf-settings').'" aria-label="'.esc_attr__('Plugin Additional Links Settings', 'wpexperts').'">'.esc_html__('Settings', 'wpexperts').'</a>');
        $row_documentation = array('documentation' => '<a href="'.esc_url('https://wpexperts.io/documentation/wordpress-pdf-generator-plugin/') . '" target="_blank" aria-label="'.esc_attr__('Plugin Additional Links Documentation', 'wpexperts').'" style="color:green;">'.esc_html__('Documentation', 'wpexperts').'</a>');
        $links = array_merge( $links, $row_settings );
        $links = array_merge( $links, $row_documentation );
    }
    return $links;
}
add_filter( 'plugin_row_meta', 'wpexperts_pdf_plugin_row', 10, 2 );
/**
 * 
 * Here wpexperts pdf base class that hold plugin
 * functions and data. this class as treat
 * as auto-run.
 * 
 */
include_once( WPEXPERTS_PDF_DIR . 'wp_objects_pdf_class.php' );
$WPEXPERTS_PDF = new WPEXPERTS_PDF();
/**
 * 
 * Here we define plugin activation hook
 * 
 */
register_activation_hook( __FILE__ , array( $WPEXPERTS_PDF , 'wpexperts_pdf_install' ) );
 /**
 * 
 * Here we define plugin de-activation hook
 * 
 */
 register_deactivation_hook( __FILE__ , array( $WPEXPERTS_PDF ,'wpexperts_pdf_uninstall' ) );
/**
 * 
 * Here wpexperts pdf define function
 * that yo can use in your php template
 * where you need.
 * 
 * @function-for-php-template
 * 
 */
function wp_objects_pdf(){
    echo do_shortcode( '[wp_objects_pdf]' );
}