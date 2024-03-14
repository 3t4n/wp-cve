<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Theme_Flatsome {

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_actions' ] );
		add_action( 'init', [ $this, 'builder_post_type' ] );
		add_action( 'wfacp_template_load', [ $this, 'add_terms_condition' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );
	}

	public function remove_actions() {
		if ( function_exists( 'flatsome_checkout_scripts' ) ) {
			remove_action( 'wp_enqueue_scripts', 'flatsome_checkout_scripts', 100 );
		}
		if ( function_exists( 'flatsome_viewport_meta' ) ) {
			remove_action( 'wp_head', 'flatsome_viewport_meta', 1 );
		}
	}

	public function builder_post_type() {
		if ( function_exists( 'add_ux_builder_post_type' ) ) {
			add_ux_builder_post_type( WFACP_Common::get_post_type_slug() );
		}
	}

	public function add_terms_condition() {
		if ( function_exists( 'flatsome_fix_policy_text' ) ) {
			add_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 21 );
		}

		if ( function_exists( 'flatsome_fix_policy_text' ) ) {
			remove_action( 'woocommerce_checkout_after_order_review', 'wc_checkout_privacy_policy_text', 1 );
		}
	}


	public function add_internal_css() {
		if ( ! function_exists( 'wfacp_template' ) ) {
			return;
		}


		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$bodyClass = "body ";


		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body #wfacp-e-form ";
		}

		echo "<style>";
		echo $bodyClass . '.wfacp_form #payment select {-webkit-appearance: menulist;-moz-appearance: menulist;}';
		echo $bodyClass . ' ul.woocommerce-error li .container {padding: 0;}';
		echo $bodyClass . ' #payment div.payment_box p {position: relative;font-weight: normal;}';
		echo $bodyClass . ' .woocommerce-error .medium-text-center {text-align: left !important;}';
		echo $bodyClass . ' .wfacp-coupon-page .message-container.container.medium-text-center { text-align: left !important;}';
		echo $bodyClass . ' .wfacp_notice_dismise_link.demo_store a:before {display: none;}';
		echo $bodyClass . ' .wfacp_main_form .woocommerce-error {color: #ff0000 !important;}';
		echo $bodyClass . ' .wfacp_main_form.woocommerce .woocommerce-checkout #payment ul.payment_methods li input[type=radio] {  margin: 0 10px 0 0 !important;}';
		echo $bodyClass . ' .wfacp-row.wfacp_coupon_field_box.wfacp_coupon_collapsed{ margin-top: 10px;}';
		echo $bodyClass . ' .wfacp_main_form .wfacp-coupon-section .wfacp-coupon-page .wfacp_coupon_field_box { margin-top: 10px;}';
		echo $bodyClass . ' button.button.button-primary:after{   display: none;}';
		echo $bodyClass . ' button.button.button-primary:before{   display: none;}';
		echo "</style>";

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Theme_Flatsome(), 'flatsome' );
