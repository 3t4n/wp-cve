<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_storefront {

	public function __construct() {

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_actions' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function remove_actions() {

		if ( WFACP_Common::is_customizer() ) {
			WFACP_Common::remove_actions( 'customize_preview_init', 'Storefront_NUX_Starter_Content', 'update_homepage_content' );

		}


	}

	public function action() {
		add_action( 'wp_enqueue_scripts', [ $this, 'theme_style' ], 99 );
	}

	public function theme_style() {
		wp_dequeue_style( 'storefront-woocommerce-style' );
	}

	public function internal_css() {

		echo '<style>';
		echo 'body form#wfacp_checkout_form > .blockUI{position: absolute !important;}';
		echo 'table:not( .has-background ) th{    background-color: transparent;}';
		echo 'body .wfacp_main_form .wfacp_shipping_table.wfacp_shipping_recurring tr.shipping > td p:last-child {padding: 11px;border: 1px solid #ddd;-webkit-border-radius: 4px;-moz-border-radius: 4px;-ms-border-radius: 4px;border-radius: 4px;}';
		echo '</style>';

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_storefront(), 'storefront' );
