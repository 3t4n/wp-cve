<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_Sumo_Payment_Plans {
	private $instance = null;


	public function __construct() {

		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_sumo_payment_plan', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );

		add_filter( 'wfacp_after_checkout_page_found', [ $this, 'add_action' ], 10, 2 );


		add_filter( 'wfacp_internal_css', [ $this, 'add_internal_css' ] );


	}


	public function enabled() {
		return class_exists( 'SUMO_PP_Checkout_Manager' );
	}

	public function add_field( $fields ) {
		if ( ! $this->enabled() ) {
			return;
		}
		$fields['sumo_payment_plan'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_sumo_payment_plan' ],
			'id'         => 'sumo_payment_plan',
			'field_type' => 'sumo_payment_plan',
			'label'      => __( 'Sumo Payment Plan', 'woofunnels-aero-checkout' ),
		];

		return $fields;
	}

	public function add_action() {
		if ( ! $this->enabled() || ! defined( 'SUMO_PP_PLUGIN_PREFIX' ) ) {
			return;
		}


		$option         = get_option( SUMO_PP_PLUGIN_PREFIX . 'order_payment_plan_form_position', 'checkout_order_review' );
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_' . $option, 'SUMO_PP_Order_PaymentPlan', 'render_plan_selector' );


	}


	public function display_field( $field, $key ) {
		if ( ! $this->enabled() || empty( $key ) || 'sumo_payment_plan' !== $key || ! $this->instance instanceof SUMO_PP_Order_PaymentPlan ) {
			return '';
		}

		echo "<div id='wfacp_sumo_pp_checkout_manager'>";
		$this->instance->render_plan_selector();
		echo "</div>";

	}


	public function add_internal_css() {
		if ( ! $this->enabled() || ! $this->instance instanceof SUMO_PP_Order_PaymentPlan || ! function_exists( 'wfacp_template' ) ) {
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
		echo $bodyClass . '#wfacp_sumo_pp_checkout_manager{clear:both;padding:0 ' . $px . '}';
		echo $bodyClass . '._sumo_pp_orderpp_fields input {position: relative !important;left: auto;right: auto;top: auto;margin: 0;}';
		echo $bodyClass . 'table.shop_table._sumo_pp_orderpp_fields td {padding: 10px !important;}';
		echo $bodyClass . 'table.shop_table._sumo_pp_orderpp_fields {    margin-bottom: 20px !important;border: 1px solid #bfbfbf !important;}';
		echo $bodyClass . '#wfacp_sumo_pp_checkout_manager input[type="checkbox"]{position: relative !important;left: auto;right: auto;top: auto;margin: 0;}';
		echo $bodyClass . '#wfacp_sumo_pp_checkout_manager input[type="radio"]{position: relative !important;left: auto;right: auto;top: auto;margin: 0;}';
		echo $bodyClass . '#wfacp_sumo_pp_checkout_manager  tr td:first-child {    width: 50%;}';
		echo $bodyClass . '#wfacp_sumo_pp_checkout_manager  table{ table-layout: fixed;}';

		echo "</style>";

	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Sumo_Payment_Plans(), 'wfacp-sumo-payment-plans' );


