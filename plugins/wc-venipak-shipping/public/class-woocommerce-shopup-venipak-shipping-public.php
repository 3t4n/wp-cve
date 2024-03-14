<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/public
 * @author     ShopUp <info@shopup.lt>
 */
class Woocommerce_Shopup_Venipak_Shipping_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * 
	 *
	 * @since    1.0.0
	 */
	public function add_venipak_shipping_logo( $label, $method ) {
		$icon = '<img width="60" class="wc-venipak-shipping-logo" src="' . plugin_dir_url( __FILE__ ) . 'images/venipak-logo.png' . '" />';  
		if ( $method->method_id === "shopup_venipak_shipping_pickup_method" ||  $method->method_id === "shopup_venipak_shipping_courier_method") {
			return "{$icon} {$label}";
		}
		return $label;  
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Load styles only on cart and checkout pages
		if ( is_cart() || is_checkout() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-shopup-venipak-shipping-public.css?v=' . $this->version );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Load scripts only on cart and checkout pages
		if ( is_cart() || is_checkout() ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-shopup-venipak-shipping-public.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( 'google_cluster_js', 'https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js', array(), '1', true );
			wp_enqueue_script( 'shopup_select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array(), '1', true );
			wp_enqueue_style( 'shopup_select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '1', true );
			wp_add_inline_script( $this->plugin_name, "window.adminUrl = '" . admin_url(). "';" );
		}
	}


}
