<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EU/UK VAT for WooCommerce by WPWhale
 * Plugin URI: https://wpfactory.com/author/wpwhale/
 */

#[AllowDynamicProperties]

  class WFACP_Compatibility_With_EU_UK_Vat_Manager_For_WC {

	private $instance = null;
	private $keys = [];

	public function __construct() {

		/* checkout page */

		add_filter( 'wfacp_advanced_fields', [ $this, 'add_fields' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_action( 'process_wfacp_html', [ $this, 'call_to_action' ], 10, 3 );
		add_filter( 'wfacp_html_fields_wfacp_eu_vat_manager', '__return_false' );

		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_styling' ], 10, 2 );


	}

	public function add_fields( $field ) {
		$field['wfacp_eu_vat_manager'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'form-row-wide' ],
			'id'         => 'wfacp_eu_vat_manager',
			'field_type' => 'advanced',
			'label'      => __( 'EU/UK VAT Manager', 'woofunnels-aero-checkout' ),

		];

		return $field;
	}

	public function action() {
		if ( ! $this->is_enable() ) {
			return;
		}
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_checkout_fields', 'Alg_WC_EU_VAT_Core', 'add_eu_vat_checkout_field_to_frontend' );

	}

	public function is_enable() {
		return class_exists( 'Alg_WC_EU_VAT_Core' );
	}

	public function call_to_action( $field, $key, $args ) {

		if ( empty( $key ) || $key !== 'wfacp_eu_vat_manager' || ! $this->instance instanceof Alg_WC_EU_VAT_Core ) {
			return '';
		}

		$new_fields = $this->instance->add_eu_vat_checkout_field_to_frontend( [] );


		if ( ! isset( $new_fields['billing'] ) || ! is_array( $new_fields['billing'] ) ) {
			return;
		}


		foreach ( $new_fields['billing'] as $key => $field ) {
			$this->keys[] = $key;
			woocommerce_form_field( $key, $field );
		}

	}


	public function add_default_styling( $args, $key ) {

		if ( ! $this->is_enable() || ! in_array( $key, $this->keys ) ) {
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
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_EU_UK_Vat_Manager_For_WC(), 'wfacp-wc-eu-vat' );


