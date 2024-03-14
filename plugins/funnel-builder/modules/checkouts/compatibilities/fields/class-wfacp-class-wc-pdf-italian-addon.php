<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Name: WooCommerce PDF Invoices Italian Add-on
 * Plugin URI: https://it.wordpress.org/plugins/woocommerce-pdf-invoices-italian-add-on/
 * Version: 0.7.0.20
 * Author: laboratorio d'Avanguardia
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_With_WC_PDF_Italian_addon {
	private $new_fields = [];
	private $object = null;

	public function __construct() {
		/* Register Add field */

		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_billing' ] );
		} else {
			add_action( 'init', [ $this, 'setup_fields_billing' ], 20 );
		}


		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_action' ] );
		add_filter( 'wfacp_html_fields_billing_pdf_invoice_italian', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 50, 3 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
	}

	public function is_enabled() {
		return class_exists( 'WooCommerce_Italian_add_on' );
	}

	public function setup_fields_billing() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		new WFACP_Add_Address_Field( 'pdf_invoice_italian', array(
			'type'         => 'wfacp_html',
			'label'        => __( 'WC PDF Invoices Italian', 'woocommerce-fakturownia' ),
			'palaceholder' => __( 'NIP', 'woocommerce-fakturownia' ),
			'cssready'     => [ 'wfacp-col-left-third' ],
			'class'        => array( 'form-row-third first', 'wfacp-col-full' ),
			'required'     => false,
			'priority'     => 60,
		) );


	}

	public function remove_action() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		$this->object = WFACP_Common::remove_actions( 'woocommerce_billing_fields', 'WooCommerce_Italian_add_on', 'billing_fields' );
		if ( $this->object instanceof WooCommerce_Italian_add_on ) {
			$this->new_fields = $this->object->billing_fields( [] );
			add_action( 'wfacp_internal_css', [ $this, 'enqueue_js_css' ] );
		}


	}

	public function enqueue_js_css() {
		$this->object->add_js_and_fields( '' );

	}

	public function call_fields_hook( $field, $key, $args ) {
		if ( $this->is_enabled() && ( ! empty( $key ) && ( 'billing_pdf_invoice_italian' === $key ) ) ) {
			if ( ! is_array( $this->new_fields ) || count( $this->new_fields ) == 0 ) {
				return;
			}

			foreach ( $this->new_fields as $field_key => $field_val ) {
				woocommerce_form_field( $field_key, $field_val );
			}

		}
	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( ! $this->is_enabled() ) {
			return $args;
		}

		$input_class = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
		$label_class = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );

		if ( array_key_exists( $key, $this->new_fields ) ) {
			$all_cls             = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
			$args['class']       = $all_cls;
			$args['cssready']    = [ 'wfacp-col-left-half' ];
			$args['input_class'] = $input_class;
			$args['label_class'] = $label_class;

		}


		return $args;
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_PDF_Italian_addon(), 'wfacp-woocommerce-pdf-italian-add-on' );


