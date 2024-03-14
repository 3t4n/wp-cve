<?php

/**
 * WooCommerce Fakturownia By WP Desk
 * Author URI: https://www.wpdesk.pl
 * Version: 1.4.3
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_WC_fakturownia {
	private $add_fields = [ 'billing_faktura', 'billing_nip' ];
	private $new_fields = [];


	public function __construct() {

		/* Register Add field */
		add_action( 'init', [ $this, 'setup_fields_billing' ], 20 );
		add_filter( 'wfacp_html_fields_billing_wfacp_nip', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 50, 3 );
		add_action( 'woocommerce_billing_fields', function ( $fields ) {
			if ( is_array( $fields ) && count( $fields ) > 0 ) {
				foreach ( $this->add_fields as $i => $field_key ) {
					if ( isset( $fields[ $field_key ] ) ) {
						$this->new_fields[ $field_key ] = $fields[ $field_key ];
					}
				}
			}

			return $fields;
		}, 100 );


		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

	}


	public function setup_fields_billing() {
		new WFACP_Add_Address_Field( 'wfacp_nip', array(
			'type'         => 'wfacp_html',
			'label'        => __( 'NIP', 'woocommerce-fakturownia' ),
			'palaceholder' => __( 'NIP', 'woocommerce-fakturownia' ),
			'cssready'     => [ 'wfacp-col-left-third' ],
			'class'        => array( 'form-row-third first', 'wfacp-col-full' ),
			'required'     => false,
			'priority'     => 60,
		) );


	}

	public function call_fields_hook( $field, $key, $args ) {

		if ( empty( $key ) || 'billing_wfacp_nip' !== $key ) {
			return;
		}

		if ( ! is_array( $this->new_fields ) || count( $this->new_fields ) == 0 ) {
			return;
		}


		foreach ( $this->new_fields as $field_key => $field_val ) {

			woocommerce_form_field( $field_key, $field_val );
		}


	}

	public function action() {
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 90, 2 );
	}

	public function add_default_wfacp_styling( $args, $key ) {


		if ( ! is_array( $this->new_fields ) || count( $this->new_fields ) == 0 || ! array_key_exists( $key, $this->new_fields ) ) {
			return $args;
		}

		$args['class'] = ( isset ( $args['class'] ) && ! empty( $args['class'] ) ) ? array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] ) : [];
		$args['cssready'] = [ 'wfacp-col-full' ];


		if ( $key === 'billing_nip' ) {
			$args['input_class'] = ( isset ( $args['input_class'] ) && ! empty( $args['input_class'] ) ) ? array_merge( [ 'wfacp-form-control' ], $args['input_class'] ) : [];
			$args['label_class'] = ( isset ( $args['label_class'] ) && ! empty( $args['label_class'] ) ) ? array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] ) : [];

		} elseif ( $key === 'faktura_field' ) {
			$args['label_class'] = [ 'checkbox' ];
		}


		return $args;
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_fakturownia(), 'wc-fakturownia' );

