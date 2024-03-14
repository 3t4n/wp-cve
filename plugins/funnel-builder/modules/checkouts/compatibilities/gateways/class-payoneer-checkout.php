<?php

/**
 * Payoneer Checkout  by Inpsyde GmbH (v.0.2.1)
 * Plugin URI: https://inpsyde.com
 */

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Payoneer_Checkout_Gateway {
	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );

	}

	public function actions() {
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
		add_action( 'wfacp_checkout_before_order_review', function () {
			echo '<div id=order_review>';

		} );
		add_action( 'wfacp_checkout_after_order_review', function () {
			echo '</div>';

		} );

	}

	public function internal_css() {

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$bodyClass = "body ";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form ";
		}

		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#order_review {width:100%;}";
		$cssHtml .= "</style>";
		echo $cssHtml;
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Payoneer_Checkout_Gateway(), 'afterpay' );
