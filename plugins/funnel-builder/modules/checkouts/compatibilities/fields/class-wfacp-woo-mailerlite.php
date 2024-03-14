<?php

/**
 * Plugin : MailerLite - WooCommerce integration by MailerLite (v.1.8.5)
 * Plugin URI:  https://wordpress.org/plugins/woo-mailerlite/
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_WC_MailerLite {
	private $position = null;
	private $position_show = [ 'review_order_before_submit' ];

	private $display_dragable_field = true;


	public function __construct() {

		/*
		 *  Register Add Custom Fields for Mailer Lite
		 */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		/*
		 *  Prevent to display custom field
		 */
		add_filter( 'wfacp_html_fields_woo_ml_subscribe_html', '__return_false' );
		/*
		 *  Remove Plugin's Hook on the funnelkit checkout page Except review_order_before_submit Hook
		 */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );

		/*
		 *  Process Custom field According to plugin functionality
		 */
		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 50, 3 );
		/*
		 *  Add the custom css for Mail lite field on the Funnelkit Checkout
		 */
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}

	public function add_field( $fields ) {
		$fields['woo_ml_subscribe_html'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'woo_ml_subscribe' ],
			'id'         => 'woo_ml_subscribe_html',
			'field_type' => 'advanced',
			'label'      => __( 'Woo MailerLite', 'woofunnels-aero-checkout' ),
		];

		return $fields;
	}

	public function actions() {

		if ( ! function_exists( 'woo_ml_get_option' ) ) {
			return;
		}
		$checkout_position = woo_ml_get_option( 'checkout_position', 'checkout_billing' );

		/* default classes */
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );

		if ( in_array( $checkout_position, $this->position_show ) ) {
			$this->display_dragable_field = false;

			return;
		}

		$this->position = $checkout_position;
		remove_action( 'woocommerce_' . $checkout_position, 'woo_ml_checkout_label', 20 );
	}

	public function call_fields_hook( $field, $key, $args ) {

		if ( ( ! empty( $key ) && ( 'woo_ml_subscribe_html' === $key ) ) ) {

			if ( ! in_array( $this->position, $this->position_show ) ) {
				$this->mailer_lite_html();
			}

		}
	}

	public function mailer_lite_html() {

		if ( false == $this->display_dragable_field ) {
			return;
		}

		woo_ml_checkout_label();


	}

	public function internal_css() {

		if ( ! function_exists( 'wfacp_template' ) ) {
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

		echo "<style>";
		echo $bodyClass . ".wfacp_main_form.woocommerce #woo_ml_subscribe_field{text-align:left;}";
		echo "</style>";

	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( 'woo_ml_subscribe' !== $key ) {
			return $args;
		}


		if ( isset( $args['type'] ) && 'checkbox' !== $args['type'] ) {

			$args['input_class'] = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$args['label_class'] = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );
			$args['class']       = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full' ], $args['class'] );
			$args['cssready']    = [ 'wfacp-col-full' ];

		} else {
			$args['class']    = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
			$args['cssready'] = [ 'wfacp-col-full' ];
		}


		return $args;
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_MailerLite(), 'wfacp-woo-mailerlite' );