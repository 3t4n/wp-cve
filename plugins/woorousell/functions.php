<?php /*
Plugin Name: WoorouSell - Product Carousel For WooCommerce
Version: 1.1.0
Plugin URI: https://www.mojofywp.com/woorousell
Description: Showcase your woocommerce products in a beautiful and responsive carousel format
Author: MojofyWP
Author URI: https://www.mojofywp.com

WordPress - 
Requires at least: 4.9.8
Tested up to: 6.2.2
Stable tag: 1.1.0

Text Domain: woorousell
Domain Path: /langCopyright 2012 - 2019 Smashing Advantage Enterprise.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin slug (for translation)
 **/

if(!defined('WRSL_SLUG')) define( 'WRSL_SLUG', 'woorousell' );

/**
 * Plugin version
 **/
if(!defined('WRSL_VERSION')) define( 'WRSL_VERSION', '1.1.0' );

/**
 * Plugin path
 **/
if(!defined('WRSL_PATH')) define( 'WRSL_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin url
 **/
if(!defined('WRSL_URL')) define( 'WRSL_URL', plugin_dir_url( __FILE__ ) );


if ( !function_exists( 'woorousell_init' ) && ! function_exists( 'woorousell_fs' ) ) :

/**
 * Load plugin core class file
 */
require_once ( 'includes/freemius.php' );
require_once ( 'includes/class-woorousell.php' );
require_once ( 'includes/helpers.php' );

/**
 * Init WoorouSell core class
 *
 */
function woorousell_init() {

	global $woorousell;

	// Instantiate Plugin
	$woorousell = WoorouSell::get_instance();

	// Localization
	load_plugin_textdomain( WRSL_SLUG , false , dirname( plugin_basename( __FILE__ ) ) . '/lang' );

}

add_action( 'plugins_loaded' , 'woorousell_init' );

endif;