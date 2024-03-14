<?php
/*
Plugin Name: Unyson WooCommerce Shortcodes
Plugin URI:  https://flytemplates.com/unyson-woocommerce-shortcodes
Description: WooComerce Shortcodes for Unyson Page Builder
Version:     1.0.3
Author:      FlyTemplates
Author URI:  https://flytemplates.com/
Tags: unyson, shortcodes, woocommerce, add to cart shortcode, products shortcode
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: uws
Domain Path: /languages
*/


/**
 * Start load UWS shortcodes from plugin
 *
 * @since 1.0
 */
function _uws_fw_extensions_locations( $locations ) {
	$locations[ dirname( __FILE__ ) . '/extensions' ] = plugin_dir_url( __FILE__ ) . 'extensions';

	return $locations;
}

add_filter( 'fw_extensions_locations', '_uws_fw_extensions_locations' );


require_once dirname( __FILE__ ) . '/includes/helpers.php';

