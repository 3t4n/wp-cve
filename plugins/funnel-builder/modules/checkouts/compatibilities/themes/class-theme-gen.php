<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_ThemeGen {

	public function __construct() {

		/* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'dequeue_actions' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );


	}

	public function is_enabled() {

		return function_exists( 'thegem_enqueue_woocommerce_styles' );
	}


	public function dequeue_actions() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		remove_action( 'woocommerce_before_checkout_form', 'thegem_woocommerce_checkout_scripts', 1 );
		remove_action( 'woocommerce_before_checkout_form', 'thegem_woocommerce_checkout_tabs', 5 );
		remove_action( 'woocommerce_before_checkout_form', 'thegem_cart_checkout_steps', 5 );

		if ( function_exists( 'thegem_enqueue_woocommerce_styles' ) ) {
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', thegem_checkout_get_type() == 'multi-step' ? 9 : 11 );
		}


		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 4 );
		remove_action( 'woocommerce_before_checkout_form', 'thegem_woocommerce_before_checkout_wrapper_start', 6 );
		remove_action( 'woocommerce_before_checkout_form', 'thegem_woocommerce_before_checkout_wrapper_end', 100 );
		remove_action( 'woocommerce_checkout_after_customer_details', 'thegem_woocommerce_checkout_nav_buttons', 100 );
		remove_action( 'woocommerce_checkout_before_customer_details', 'thegem_woocommerce_customer_details_start', 1 );
		remove_action( 'woocommerce_checkout_after_customer_details', 'thegem_woocommerce_customer_details_end', 1000 );
		remove_action( 'woocommerce_checkout_before_order_review_heading', 'thegem_woocommerce_order_review_start', 1 );
		remove_action( 'woocommerce_checkout_after_order_review', 'thegem_woocommerce_order_review_end', 1000 );
		remove_action( 'woocommerce_after_checkout_form', 'thegem_woocommerce_checkout_form_steps_script' );
		remove_action( 'woocommerce_after_checkout_registration_form', 'thegem_woocommerce_checkout_registration_buttons', 100 );
		remove_action( 'woocommerce_checkout_before_order_review', 'thegem_woocommerce_order_review_table_start', 1 );
		remove_action( 'woocommerce_checkout_after_order_review', 'thegem_woocommerce_order_review_table_end', 1000 );

		add_action( 'wp_enqueue_scripts', [ $this, 'theme_style' ] );
	}

	public function theme_style() {
		wp_dequeue_style( 'thegem-woocommerce' );
	}

	public function add_internal_css() {
		if ( ! $this->is_enabled() ) {
			return;
		}


		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$bodyClass = "body ";

		$px = $instance->get_template_type_px() . "px";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$px        = "7px";
			$bodyClass = "body #wfacp-e-form ";
		}

		echo "<style>";
		echo '.woocommerce-checkout .woocommerce{    margin-top: 0;}';

		echo "</style>";

	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_ThemeGen(), 'wfacp-themegen' );
