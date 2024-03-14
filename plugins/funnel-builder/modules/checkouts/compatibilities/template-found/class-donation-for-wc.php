<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Name: Donation For Woocommerce
 * Version: 2.0.2
 * Author: wpexpertsio
 *
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Donation_for_WC {
	public function __construct() {
		/* Donation of WC Style */
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );

	}

	public function wfacp_internal_css() {
		?>

        <style>
            /* Donation of WC */
            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc_donation_on_checkout {
                padding: 0;
                margin: 0 0 15px;
                background: transparent;
            }

            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc_donation_on_checkout .price-wrapper {
                margin-bottom: 0;
                position: relative;
            }

            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc-donation-in-action .wc-input-text {
                margin-bottom: 0;
            }

            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc-donation-in-action .price-wrapper.before::before {
                z-index: 2;
            }

            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc-donation-in-action .in-action-elements > * {
                margin-bottom: 15px;
            }

            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc-donation-in-action .in-action-elements > *:last-child {
                margin-bottom: 0;
            }


            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc-donation-in-action button#wc-donation-f-submit-donation {
                border-radius: 2px;
                padding: 10px 30px;
                font-size: 16px;
                line-height: 1.5;
                margin: 0;
                display: block;
                font-family: inherit;
                text-transform: capitalize;
            }

            body .wfacp_main_form.woocommerce .wc-donation-in-action button#wc-donation-f-submit-donation:hover {
                opacity: .9;
            }

            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc-donation-in-action select,
            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc-donation-in-action input[type=text],
            body .wfacp_main_form.woocommerce #wfacp_checkout_form .wc-donation-in-action input[type=number] {
                padding-top: 10px;
                padding-bottom: 10px;
            }

        </style>

		<?php
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Donation_for_WC(), 'wfacp-donation-for-wc' );

