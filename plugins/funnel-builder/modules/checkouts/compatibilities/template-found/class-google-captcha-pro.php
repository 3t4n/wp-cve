<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Google_captcha_Pro {


	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'actions' ] );
		add_action( 'wfacp_internal_css', [ $this, 'wac_css_func' ] );

	}

	public function actions() {
		$is_user_logged_in = is_user_logged_in();

		if ( ! function_exists( 'gglcptchpr_init' ) ) {

			return;
		}

		if ( ! gglcptch_is_recaptcha_required( 'woocommerce_checkout', $is_user_logged_in ) ) {
			return;

		}

		if ( ! function_exists( 'gglcptch_echo_recaptcha' ) ) {
			return;
		}

		add_action( 'woocommerce_checkout_after_terms_and_conditions', 'gglcptch_echo_recaptcha', 10, 0 );

	}

	public function wac_css_func( $selected_template_slug ) {

		?>
        <style>
            body .grecaptcha-badge {
                z-index: 2;
            }

        </style>
		<?php

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Google_captcha_Pro(), 'gcp' );

