<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YITH WooCommerce EU VAT & OSS Premium by YITH (2.0.1)
 */
#[AllowDynamicProperties]

  class WFACP_Yth_Wc_Eu_Vat {
	private $new_fields = [];


	public function __construct() {

		/* Register Add field */

		add_action( 'init', [ $this, 'setup_fields_billing' ], 20 );
		add_filter( 'wfacp_html_fields_billing_wfacp_yweu_vat', '__return_false' );


		/* Assign Object */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

		/* Display Fields */
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 10, 3 );

		/* default classes */
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );

		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

	}


	public function is_enable() {
		return class_exists( 'YITH_WooCommerce_EU_VAT' );
	}

	public function setup_fields_billing() {
		if ( ! $this->is_enable() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'wfacp_yweu_vat', array(
			'type'     => 'wfacp_html',
			'label'    => __( 'YTH EU Vat', 'woofunnels-aero-checkout' ),
			'cssready' => [ 'wfacp-col-left-third' ],
			'class'    => array( 'form-row-third first', 'wfacp-col-full' ),
			'required' => false,
			'priority' => 60,
		) );


	}

	public function action() {
		if ( ! $this->is_enable() ) {
			return;
		}
		add_filter( 'woocommerce_checkout_fields', [ $this, 'checkout_fields' ], 100 );
	}

	public function checkout_fields( $fields ) {
		$billing_fields = WC()->checkout()->get_checkout_fields( 'billing' );
		if ( isset( $billing_fields['billing_yweu_vat'] ) && is_array( $billing_fields['billing_yweu_vat'] ) && count( $billing_fields['billing_yweu_vat'] ) > 0 ) {
			$this->new_fields['billing_yweu_vat'] = $billing_fields['billing_yweu_vat'];

		}

		return $fields;
	}


	public function display_field( $field, $key, $args ) {


		if ( empty( $key ) || 'billing_wfacp_yweu_vat' !== $key || count( $this->new_fields ) === 0 ) {
			return;
		}


		echo "<div id='wfacp_yweu_vat' class='wfacp_clear'>";
		foreach ( $this->new_fields as $field_key => $field_val ) {
			woocommerce_form_field( $field_key, $field_val );
		}
		echo "</div>";


	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( ! $this->is_enable() ) {
			return $args;
		}


		if ( 'billing_yweu_vat' !== $key ) {
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

	public function internal_css() {

		if ( ! $this->is_enable() ) {
			return;

		}
		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body ";
		$px        = $instance->get_template_type_px() . "px";
		if ( 'pre_built' !== $instance->get_template_type() ) {
			$px        = "7px";
			$bodyClass = "body #wfacp-e-form ";
		}


		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . ".wfacp_main_form .ywev-country-confirmation input[type='checkbox'] {position: relative;margin: 0 10px 0 0;}";

		$cssHtml .= "</style>";
		echo $cssHtml;
	}


}


if ( ! function_exists( 'yith_ywev_premium_init' ) ) {
	return;
}

WFACP_Plugin_Compatibilities::register( new WFACP_Yth_Wc_Eu_Vat(), 'wfacp-yth-wc-eu-vat' );





