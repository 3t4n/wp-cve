<?php
/**
 * Plugin Name: WooCommerce Product Price x Quantity Preview
 * Plugin URI: 
 * Description: An extension to WooCommerce that will preview price total when quantity changes on the product page.
 * Author: Reigel Gallarde
 * Author URI: http://reigelgallarde.me
 * Version: 1.2.1
 * WC requires at least: 3.0.0
 * WC tested up to: 3.5.1
 *
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package     
 * @author      Reigel Gallarde
 * @category    Plugin
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
 
 
 /**
 * Exit if accessed directly
 **/
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Check if WooCommerce is active
 **/
 
if ( !function_exists('is_plugin_active') )
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 

if ( ! is_plugin_active('woocommerce/woocommerce.php') ) {
    return;
}

if (!class_exists('WooCommercePPQP')) :

class WooCommercePPQP {
	
	protected static $_instance = null;
	protected static $version = '1.2.1';
	
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function __construct(){
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'woocommerce_loaded', array( $this, 'ppqp_init' ) );
		
	}
	
	public function load_plugin_textdomain(){
		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'woo-ppqp' );
		
		unload_textdomain( 'woo-ppqp' );
		load_textdomain( 'woo-ppqp', WP_LANG_DIR . '/plugins/woo-ppqp-' . $locale . '.mo' );
		load_plugin_textdomain( 'woo-ppqp', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
		
	}
	
	public function ppqp_init() {		
	
		if ( !is_admin() ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'woocommerce_total_product_price_html' ), 31 );
			add_action( 'wp_enqueue_scripts', array( $this, 'woocommerce_register_script' ), 5 );
			add_action( 'wp_enqueue_scripts', array( $this, 'woocommerce_enqueue_script' ) );
		}		
		
	}
	
	public function woocommerce_total_product_price_html() {
		global $product;
		
		if ( $product->is_type( array( 'simple' ) ) ) {
			// let's setup our divs
			echo self::get_ppqp_html_holder();
		}
		
	}
	
	public static function get_ppqp_html_holder( $html = 'div' ) {
		return '<'.$html.' class="product-total-price ppqp-price-holder"></'.$html.'>';
	}
	
	public function woocommerce_register_script(){
		wp_register_script( 'ppqp_script', plugin_dir_url( __FILE__ ) . 'assets/js/price-preview.js', array( 'jquery', 'wp-util', 'wc-add-to-cart-variation' ), self::$version );
	}
	
	public function woocommerce_enqueue_script(){
		
		if ( ! is_single() ) { return; }
		
		global $post;
		$product = wc_get_product( $post->ID );
		if ( !empty( $product ) ) {
			wp_enqueue_script( 'wp-util' ); 
			wp_enqueue_script( 'ppqp_script' );
			
			$ppqp_params = array(
				'precision' 			=> wc_get_price_decimals(),
				'thousand_separator' 	=> wc_get_price_thousand_separator(),
				'decimal_separator'  	=> wc_get_price_decimal_separator(),
				'currency' 				=> get_woocommerce_currency_symbol(),
				'product_type'			=> $product->get_type(),
				'price'					=> $product->get_price()
			);
			
			wp_localize_script( 'ppqp_script', 'ppqp_params', apply_filters( 'wp_localize_ppqp_params', $ppqp_params, $product ) );
			
			$plugin_tempates_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/';
			$ppqp_price_template = apply_filters('ppqp_price_preview_template_file', 'ppqp/price-preview.php', $product );
			wc_get_template( $ppqp_price_template, array(), '', $plugin_tempates_path );
		}
	}

}
endif;


// initialize WooCommercePPQP
WooCommercePPQP::instance();