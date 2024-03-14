<?php

/**
 * Plugin Name: Timologia for WooCommerce By John Athanasiou
 * Plugin URI: https://wordpress.org/plugins/timologia-for-woocommerce/
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_With_WC_Timologia {
	private static $instance = null;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_billing' ] );
		} else {
			$this->setup_fields_billing();
		}
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 11, 2 );
	}


	private function is_enabled() {
		return function_exists( 'tfwc_get_keys_labels' );
	}

	public function setup_fields_billing() {

		if ( false == $this->is_enabled() ) {
			return;
		}


		if ( function_exists( 'tfwc_get_keys_labels' ) ) {
			$labels = tfwc_get_keys_labels();
		}


		$invoice_text    = __( 'Invoice', 'woofunnels-aero-checkout' );
		$vat_text        = __( 'VAT', 'woofunnels-aero-checkout' );
		$tax_office_text = __( 'Tax Office', 'woofunnels-aero-checkout' );
		$store_text      = __( 'Profession', 'woofunnels-aero-checkout' );


		if ( isset( $labels['timologio'] ) ) {
			$invoice_text = $labels['timologio'];
		}

		if ( isset( $labels['vat'] ) ) {
			$vat_text = _x( $labels['vat'], 'placeholder' );
		}

		if ( isset( $labels['irs'] ) ) {
			$tax_office_text = _x( $labels['irs'], 'placeholder' );
		}


		if ( isset( $labels['store'] ) ) {
			$store_text = _x( $labels['store'], 'placeholder' );
		}

		new WFACP_Add_Address_Field( 'timologio', array(
			'type'         => 'select',
			'label'        => $invoice_text,
			'palaceholder' => $invoice_text,
			'cssready'     => [ 'wfacp-col-left-third' ],
			'class'        => [ 'form-row-first', 'address-field', 'timologio-select', 'wfacp_drop_list', 'wfacp_dropdown', 'wfacp-timologio' ],
			'required'     => false,
			'options'      => array(
				'N' => __( 'No', 'wc-timologia' ),
				'Y' => __( 'Yes', 'wc-timologia' ),
			),
			'priority'     => 999,
		) );

		new WFACP_Add_Address_Field( 'vat', array(
			'type'         => 'text',
			'label'        => $vat_text,
			'palaceholder' => $vat_text,
			'cssready'     => [ 'wfacp-col-left-third' ],
			'class'        => array( 'form-row-first', 'timologio-hide', 'validate-required' ),
			'required'     => false,
			'priority'     => 1000,
		) );

		new WFACP_Add_Address_Field( 'irs', array(
			'type'         => 'text',
			'label'        => $tax_office_text,
			'palaceholder' => $tax_office_text,
			'cssready'     => [ 'wfacp-col-left-third' ],
			'class'        => array( 'form-row-first', 'timologio-hide', 'validate-required' ),
			'required'     => false,
			'priority'     => 1001,
		) );

		new WFACP_Add_Address_Field( 'store', array(
			'type'         => 'text',
			'label'        => $store_text,
			'palaceholder' => $store_text,
			'cssready'     => [ 'wfacp-col-left-third' ],
			'class'        => array( 'form-row-first', 'timologio-hide', 'validate-required' ),
			'required'     => false,
			'priority'     => 1002,
		) );


	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( false == $this->is_enabled() ) {
			return $args;
		}

		if ( $key == 'billing_company' ) {
			$args['class'] = array_merge( $args['class'], [ 'timologio-hide' ] );
		}

		return $args;
	}


}

WFACP_Compatibility_With_WC_Timologia::get_instance();




