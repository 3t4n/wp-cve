<?php

/**
 * WooCommerce Italian Add-on Plus By laboratorio d'Avanguardia (Version 0.7.2.32)
 * Plugin Path: http://ldav.it/plugin/woocommerce-italian-add-on/
 */
#[AllowDynamicProperties]
class WFACP_Compatibility_WC_Italian_Add_ON {
	private $instance = null;
	private $add_fields = [
		'billing_invoice_type',
		'billing_customer_type',
		'billing_cf',
		'billing_cf2',
		'billing_PEC',
	];
	private $new_fields = [];

	public function __construct() {
		/* Register Add field */

		if ( WFACP_Common::is_funnel_builder_3() ) {
			add_action( 'wffn_rest_checkout_form_actions', [ $this, 'setup_fields_billing' ] );
		} else {
			add_action( 'init', [ $this, 'setup_fields_billing' ], 20 );
		}

		add_filter( 'wfacp_html_fields_billing_wfacp_wc_italian_add_on', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 50, 2 );
		add_action( 'woocommerce_billing_fields', [ $this, 'checkout_fields' ], 100 );
		add_action( 'wfacp_checkout_page_found', [ $this, 'action' ] );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		add_filter( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );


		/* update order meta of funnelkit checkout fields*/
		add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'woocommerce_checkout_update_order_meta' ], 99, 2 );
	}

	public function checkout_fields( $fields ) {
		if ( ! is_array( $fields ) || count( $fields ) == 0 ) {
			return $fields;
		}

		foreach ( $this->add_fields as $i => $field_key ) {
			if ( isset( $fields[ $field_key ] ) ) {
				$this->new_fields[ $field_key ] = $fields[ $field_key ];
			}
		}


		return $fields;
	}

	public function action() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_after_order_notes', 'WooCommerce_Italian_add_on_plus', 'after_order_notes' );
	}

	public function wfacp_internal_css() {
		if ( is_null( $this->instance ) || ! $this->instance instanceof WooCommerce_Italian_add_on_plus ) {
			return;
		}
		$this->instance->after_order_notes( WC()->checkout() );
	}

	public function setup_fields_billing() {
		new WFACP_Add_Address_Field( 'wfacp_wc_italian_add_on', array(
			'type'         => 'wfacp_html',
			'label'        => __( 'WC Italian Fields', 'woofunnels-aero-checkout' ),
			'palaceholder' => __( 'WC Italian Fields', 'woofunnels-aero-checkout' ),
			'cssready'     => [ 'wfacp-col-left-third' ],
			'class'        => array( 'form-row-third first', 'wfacp-col-full' ),
			'required'     => false,
			'priority'     => 60,
		) );
	}

	public function call_fields_hook( $field, $key ) {

		if ( empty( $key ) || 'billing_wfacp_wc_italian_add_on' !== $key || empty( $this->new_fields ) ) {
			return;
		}


		foreach ( $this->new_fields as $field_key => $field_val ) {
			woocommerce_form_field( $field_key, $field_val );
		}
	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( ! array_key_exists( $key, $this->new_fields ) ) {
			return $args;
		}

		$all_cls          = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
		$args['class']    = $all_cls;
		$args['cssready'] = [ 'wfacp-col-full' ];

		if ( isset( $args['type'] ) && 'checkbox' !== $args['type'] ) {
			$input_class         = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
			$args['input_class'] = $input_class;
			$label_class         = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );
			$args['label_class'] = $label_class;

		}


		return $args;
	}

	public function woocommerce_checkout_update_order_meta( $order_id, $data ) {
		if ( ! isset( $_POST['_wfacp_post_id'] ) ) {
			return;
		}
		$order = wc_get_order( $order_id );
		foreach ( $this->add_fields as $item ) {
			if ( isset( $_POST[ $item ] ) ) {
				$order->{$item} = $_POST[ $item ];
				$order->update_meta_data( '_'.$item, $_POST[ $item ] );
			}
		}
		$order->save();
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_Italian_Add_ON(), 'wfacp-wc-italian-add-on' );
