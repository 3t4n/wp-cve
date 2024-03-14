<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Active_WooChimp {
	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_get_fragments', [ $this, 'actions' ] );
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'actions' ] );

		add_action( 'wfacp_template_load', [ $this, 'hooks_actions' ] );


	}

	public function actions() {
		if ( class_exists( 'WooChimp' ) && isset( $GLOBALS['WooChimp'] ) ) {
			$woochimp = $GLOBALS['WooChimp'];
			remove_action( 'woocommerce_checkout_before_customer_details', [ $woochimp, 'add_permission_question' ] );
			remove_action( 'woocommerce_checkout_after_customer_details', [ $woochimp, 'add_permission_question' ] );
			remove_action( 'woocommerce_review_order_before_submit', [ $woochimp, 'add_permission_question' ] );
			remove_action( 'woocommerce_review_order_after_submit', [ $woochimp, 'add_permission_question' ] );
			remove_action( 'woocommerce_review_order_before_order_total', [ $woochimp, 'add_permission_question' ] );
			remove_action( 'woocommerce_checkout_billing', [ $woochimp, 'add_permission_question' ] );
			remove_action( 'woocommerce_checkout_shipping', [ $woochimp, 'add_permission_question' ] );
			remove_action( 'woocommerce_after_checkout_billing_form', [ $woochimp, 'add_permission_question' ] );
			add_action( 'woocommerce_review_order_before_submit', [ $woochimp, 'add_permission_question' ] );
		}

	}


	public function hooks_actions() {
		$this->ibericode();

		$this->SSWCMC();
	}

	public function ibericode() {
		global $mc4wp;

		if ( apply_filters( 'wfacp_enable_wp4mc_enable', true ) ) {
			if ( ! is_null( $mc4wp ) && isset( $mc4wp['integrations'] ) && ( $mc4wp['integrations'] instanceof MC4WP_Integration_Manager ) ) {

				$integrations = $mc4wp['integrations']->get_enabled_integrations();

				if ( isset( $integrations['woocommerce'] ) ) {

					$wcommerce = $integrations['woocommerce'];

					if ( $wcommerce instanceof MC4WP_Integration_Fixture ) {

						$instance = $wcommerce->instance;


						if ( $instance instanceof MC4WP_WooCommerce_Integration ) {


							$hook_for_mp = "woocommerce_" . $instance->options['position'];

							if ( $hook_for_mp !== 'after_email_field' ) {
								remove_filter( 'woocommerce_form_field_email', array( $instance, 'add_checkbox_after_email_field' ), 10, 4 );
							}

							WFACP_Common::remove_actions( $hook_for_mp, 'MC4WP_WooCommerce_Integration', 'output_checkbox' );
							if ( $hook_for_mp == 'woocommerce_review_order_before_submit' ) {
								add_action( $hook_for_mp, [ $instance, 'output_checkbox' ], 20 );
							} else {
								add_action( 'wfacp_after_billing_email_field', [ $instance, 'output_checkbox' ], 20 );
							}
						}
					}
				}
			}
		}

	}

	public function SSWCMC() {

		if ( function_exists( 'SSWCMC' ) && class_exists( 'SS_WC_MailChimp_Handler' ) ) {


			$instance = SS_WC_MailChimp_Handler::get_instance();


			if ( class_exists( 'SS_WC_MailChimp_Plugin' ) ) {
				$obj = SS_WC_MailChimp_Plugin::get_instance();
			} else {
				$obj = $instance->sswcmc;
			}
			$opt_in_checkbox_display_location = $obj->opt_in_checkbox_display_location();

			// Maybe add an "opt-in" field to the checkout
			$opt_in_checkbox_display_location = ! empty( $opt_in_checkbox_display_location ) ? $opt_in_checkbox_display_location : 'woocommerce_review_order_before_submit';

			// Old opt-in checkbox display locations
			$old_opt_in_checkbox_display_locations = array(
				'billing' => 'woocommerce_after_checkout_billing_form',
				'order'   => 'woocommerce_review_order_before_submit',
			);
			// Map old billing/order checkbox display locations to new format
			if ( array_key_exists( $opt_in_checkbox_display_location, $old_opt_in_checkbox_display_locations ) ) {
				$opt_in_checkbox_display_location = $old_opt_in_checkbox_display_locations[ $opt_in_checkbox_display_location ];
			}


			remove_action( $opt_in_checkbox_display_location, array( $instance, 'maybe_add_checkout_fields' ) );
			add_action( 'wfacp_after_billing_email_field', array( $instance, 'maybe_add_checkout_fields' ) );
		}
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Active_WooChimp(), 'woochimp' );
