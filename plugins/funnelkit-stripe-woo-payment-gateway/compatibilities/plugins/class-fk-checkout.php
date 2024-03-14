<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


use FKWCS\Gateway\Stripe\SmartButtons;

if ( ! class_exists( 'FKWCS_Compat_FK_Checkout' ) ) {

	class FKWCS_Compat_FK_Checkout {

		public function __construct() {
			add_filter( 'wfacp_smart_buttons', [ $this, 'add_buttons' ] );
			add_action( 'wfacp_smart_button_container_fkwcs_gpay_apay', [ $this, 'add_fkwcs_gpay_apay_buttons' ] );
			add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );
			add_action( 'wfacp_template_load', [ $this, 'checkout_hook' ] );
		}

		public function add_buttons( $buttons ) {

			$instance = SmartButtons::get_instance();
			remove_action( 'woocommerce_checkout_before_customer_details', [ $instance, 'payment_request_button' ], 5 );

			$buttons['fkwcs_gpay_apay'] = [
				'iframe' => true,
				'name'   => __( 'Stripe Payment Request', 'funnelkit-stripe-woo-payment-gateway' ),
			];

			return $buttons;

		}

		public function checkout_hook() {
			add_filter( 'wfacp_page_settings', [ $this, 'enable_smart_button_optimizations' ] );
			add_filter( 'wfacp_smart_button_or_text', [ $this, 'change_separator_txt' ] );
			add_filter( 'wfacp_smart_button_legend_title', [ $this, 'change_express_checkout_txt' ] );
		}

		public function enable_smart_button_optimizations( $page_settings ) {

			$instance = SmartButtons::get_instance();
			if ( isset( $instance->local_settings['express_checkout_location'] ) && in_array( 'checkout', $instance->local_settings['express_checkout_location'], true ) ) {
				$page_settings['enable_smart_buttons'] = 'true';

			}

			return $page_settings;
		}


		public function add_fkwcs_gpay_apay_buttons() {
			add_filter( 'fkwcs_express_buttons_is_only_buttons', '__return_true' );
			$instance = SmartButtons::get_instance();
			$instance->payment_request_button();
		}

		public function change_separator_txt( $text ) {
			$instance = SmartButtons::get_instance();
			if ( isset( $instance->local_settings['express_checkout_location'] ) && isset( $instance->local_settings['express_checkout_separator_checkout'] ) ) {
				$text = $instance->local_settings['express_checkout_separator_checkout'];
			}

			return $text;
		}

		public function change_express_checkout_txt( $text ) {
			$instance = SmartButtons::get_instance();
			if ( isset( $instance->local_settings['express_checkout_location'] ) && isset( $instance->local_settings['express_checkout_title'] ) ) {
				$text = $instance->local_settings['express_checkout_title'];
			}

			return $text;
		}

		public function add_internal_css() {
			if ( ! defined( 'WC_STRIPE_VERSION' ) ) {
				return;
			}


			$instance = wfacp_template();
			if ( ! $instance instanceof \WFACP_Template_Common ) {
				return;
			}
			$bodyClass = "body";
			if ( 'pre_built' !== $instance->get_template_type() ) {
				$bodyClass = "body #fkwcs-e-form";
			}
			if ( version_compare( WC_STRIPE_VERSION, '5.6.0', '<' ) ) {
				return;
			}


			echo "<style>";

			echo esc_html( $bodyClass ) . " #payment ul.payment_methods li .card-brand-icons img{position: absolute;}";

			echo "</style>";

		}
	}


}


