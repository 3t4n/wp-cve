<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * WooCommerce Cart Abandonment Recovery by CartFlows Inc
 */


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Woo_Cart_Abandonment_Recovery {
	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ], 10 );
	}

	public function action() {
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_styling' ], 10, 2 );
	}

	public function is_enable() {
		return function_exists( 'wcf_ca' );
	}


	public function add_default_styling( $args, $key ) {

		if ( ! $this->is_enable() || ! wcf_ca()->utils->is_gdpr_enabled() || $key != 'billing_email' ) {
			return $args;
		}


		$args['class'] = array_merge( [ 'wfacp_woo_cart_abandonment_recovery' ], $args['class'] );


		return $args;
	}

	public function internal_css() {
		if ( ! $this->is_enable() ) {
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
		$cssHtml .= $bodyClass . ".wfacp_woo_cart_abandonment_recovery:not(.wfacp-anim-wrap) label{bottom: auto;top: 18px;margin-top:0;}";
		$cssHtml .= $bodyClass . ".wfacp_woo_cart_abandonment_recovery:not(.wfacp-anim-wrap) input.wfacp-form-control{padding-top: 10px;padding-bottom: 10px;}";

		$cssHtml .= "</style>";

		echo $cssHtml;

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Woo_Cart_Abandonment_Recovery(), 'wcar' );

