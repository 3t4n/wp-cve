<?php

if ( ! class_exists( 'WFACP_NextGen_Social_Login' ) ) {
	#[AllowDynamicProperties]
	class WFACP_NextGen_Social_Login {
		public function __construct() {
			add_action( 'wfacp_outside_header', [ $this, 'actions' ] );
		}

		public function actions() {

			$available = ( class_exists( 'NextendSocialLogin' ) && class_exists( 'NextendSocialLoginSettings' ) && property_exists( 'NextendSocialLogin', 'settings' ) && NextendSocialLogin::$settings instanceof NextendSocialLoginSettings );

			// Return if lite base plugin not exists;
			if ( ! $available ) {
				return;
			}
			add_action( 'woocommerce_before_checkout_form', [ $this, 'add_div_wrapper' ], 12 );
			add_action( 'wfacp_internal_css', [ $this, 'css' ], 12 );

			/** Pro functionality */
			if ( ! class_exists( 'NextendSocialLoginPRO' ) ) {
				/** return if pro class not exists */
				return;
			}

			/** Pro checking */
			if ( ! class_exists( 'NextendSocialLoginPRO' ) ) {
				/** return if pro class not exists */
				return;
			}

			switch ( NextendSocialLogin::$settings->get( 'woocommerce_billing' ) ) {
				case 'before':
					remove_action( 'woocommerce_before_checkout_billing_form', 'NextendSocialLoginPRO::woocommerce_before_checkout_billing_form' );
					add_action( 'woocommerce_before_checkout_form', 'NextendSocialLoginPRO::woocommerce_before_checkout_billing_form', 15 );
					break;
				case 'after':
					remove_action( 'woocommerce_after_checkout_billing_form', 'NextendSocialLoginPRO::woocommerce_after_checkout_billing_form' );
					add_action( 'woocommerce_before_checkout_form', 'NextendSocialLoginPRO::woocommerce_after_checkout_billing_form', 15 );
					break;
				case 'before-checkout-registration':
					remove_action( 'woocommerce_before_checkout_registration_form', 'NextendSocialLoginPRO::woocommerce_before_checkout_billing_form' );
					add_action( 'woocommerce_before_checkout_form', 'NextendSocialLoginPRO::woocommerce_before_checkout_billing_form', 15 );
					break;
				case 'after-checkout-registration':
					remove_action( 'woocommerce_after_checkout_registration_form', 'NextendSocialLoginPRO::woocommerce_after_checkout_billing_form' );
					add_action( 'woocommerce_before_checkout_form', 'NextendSocialLoginPRO::woocommerce_after_checkout_billing_form', 15 );
					break;
			}
		}

		public function add_div_wrapper() {
			echo '<div id="customer_details"></div>';
		}

		public function css() {
			echo "<style>#wfacp-e-form div.nsl-container-block-fullwidth .nsl-container-buttons a, #wfacp-e-form div.nsl-container-block .nsl-container-buttons a{	margin:5px}</style>";
		}
	}

	new WFACP_NextGen_Social_Login();
}