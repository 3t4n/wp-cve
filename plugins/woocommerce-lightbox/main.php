<?php
/**
 * Plugin Name:       WPB Product Quick View Popup for WooCommerce
 * Plugin URI:        https://wpbean.com/downloads/woocommerce-lightbox-pro/
 * Description:       Highly customizable product quick view lightbox plugin for WooCommerce Store. 
 * Version:           2.1.8
 * Author:            wpbean
 * Author URI:        https://wpbean.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-lightbox
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Localization
 */

function wpb_wl_textdomain() {
	load_plugin_textdomain( 'woocommerce-lightbox', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'wpb_wl_textdomain' );


/**
 * Include JS Files 
 */

function wpb_wl_adding_scripts() {
	wp_enqueue_style( 'wpb-wl-fancybox', plugins_url( 'assets/css/jquery.fancybox.min.css', __FILE__) );
	wp_enqueue_style('wpb-wl-magnific-popup', plugins_url('assets/css/magnific-popup.css', __FILE__),'','1.0');
	wp_enqueue_style('wpb-wl-main', plugins_url('assets/css/main.css', __FILE__),'','1.0');

	wp_enqueue_script( 'wpb-wl-fancybox', plugins_url( 'assets/js/jquery.fancybox.min.js', __FILE__ ), array( 'jquery' ), '3.5.7', true );
	wp_enqueue_script('wpb-wl-magnific-popup', plugins_url('assets/js/jquery.magnific-popup.min.js', __FILE__), array('jquery'), '1.0', true);
	wp_enqueue_script('wpb-wl-plugin-main', plugins_url('assets/js/main.js', __FILE__), array('jquery', 'wpb-wl-magnific-popup'), '1.0', true);
}
add_action( 'wp_enqueue_scripts', 'wpb_wl_adding_scripts' ); 




/* Requred files */

require_once dirname( __FILE__ ) . '/inc/wpb_wl_hooks.php';