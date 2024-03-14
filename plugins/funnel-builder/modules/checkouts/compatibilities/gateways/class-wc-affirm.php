<?php

/**
 * WooCommerce Affirm Gateway BY WooCommerce (v.1.2.2)
 *
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_Affirm
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_Affirm {
	public function __construct() {
		add_action( 'wfacp_template_load', [ $this, 'action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );
	}

	public function enable() {

		if ( false === WFACP_Common::is_theme_builder() && class_exists( 'WooCommerce_Gateway_Affirm' ) ) {
			return true;
		}

		return false;

	}

	public function action() {
		if ( ! $this->enable() ) {
			return;
		}
		$instance = WFACP_Common::remove_actions( 'woocommerce_checkout_after_order_review', 'WooCommerce_Gateway_Affirm', 'inline_checkout' );

		if ( $instance instanceof WooCommerce_Gateway_Affirm && ! is_null( WC()->session ) ) {
			$instance->inline_checkout();
		}
	}

	public function add_internal_css() {
		if ( ! $this->enable() || ! function_exists( 'wfacp_template' ) ) {
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

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . ".affirm-iframe-widget{max-width: 100% !important;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_Affirm(), 'wfacp-wc-affirm' );
