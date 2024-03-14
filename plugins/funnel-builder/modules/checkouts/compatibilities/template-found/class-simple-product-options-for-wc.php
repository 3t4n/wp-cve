<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Name: Product Options for WooCommerce
 * Author: Pektsekye
 * Author URI: http://www.hottons.com
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Simple_Product_options_For_WC {
	private $instance = null, $pluginUrl = null;

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
	}

	public function actions() {

		$this->instance  = Pektsekye_PO();
		$this->pluginUrl = $this->instance->getPluginUrl();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
	}

	public function enqueue_frontend_scripts() {
		wp_enqueue_script( 'pofw_product_options', $this->pluginUrl . 'view/frontend/web/product/main.js', array( 'jquery', 'jquery-ui-widget' ) );
		wp_enqueue_style( 'pofw_product_options', $this->pluginUrl . 'view/frontend/web/product/main.css' );
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Simple_Product_options_For_WC(), 'wfacp-simple-product-options-for-wc' );
