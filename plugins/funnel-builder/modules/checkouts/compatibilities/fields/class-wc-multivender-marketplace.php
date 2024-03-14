<?php

/**
 * WCFM - WooCommerce Multivendor Marketplace by WC Lovers
 * Plugin URI: https://wclovers.com/knowledgebase_category/wcfm-marketplace/
 */

#[AllowDynamicProperties]

  class WFACP_Multivender_Market_Place {
	private $instance = null;
	private $new_fields = [];
	private $add_fields = [
		'wcfmmp_user_location',
		'wcfmmp_user_location_lat',
		'wcfmmp_user_location_lng',
	];

	public function __construct() {

		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_after_checkout_page_found', [ $this, 'action' ], 20 );
		add_filter( 'wfacp_html_fields_wcfmmp_user_location', '__return_false' );
		add_filter( 'wfacp_html_fields_wcfmmp_user_location_map', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'process_html' ], 999, 3 );

		add_action( 'woocommerce_checkout_fields', [ $this, 'assign_checkout_fields' ], 99 );
	}

	public function assign_checkout_fields( $fields ) {
		if ( ! $this->is_enable() || ! $this->instance instanceof WCFMmp_Frontend ) {
			return $fields;
		}

		$fields_here = $this->instance->wcfmmp_checkout_user_location_fields( $fields );


		if ( is_array( $fields_here['billing'] ) && count( $fields_here['billing'] ) > 0 ) {


			foreach ( $this->add_fields as $i => $field_key ) {
				if ( isset( $fields_here['billing'] [ $field_key ] ) ) {
					$this->new_fields[ $field_key ] = $fields_here['billing'] [ $field_key ];
				}
			}
		}

		return $fields;
	}

	public function is_enable() {
		return class_exists( 'WCFMmp_Frontend' );
	}

	public function action() {
		if ( ! $this->is_enable() ) {
			return;
		}
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_checkout_fields', 'WCFMmp_Frontend', 'wcfmmp_checkout_user_location_fields' );
	}

	public function add_field( $fields ) {
		if ( ! $this->is_enable() ) {
			return $fields;
		}
		$fields['wcfmmp_user_location']     = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'wcfmmp_user_location' ],
			'id'         => 'wcfmmp_user_location',
			'field_type' => 'wcfmmp_user_location',
			'label'      => __( 'Delivery Location', 'wc-multivendor-marketplace' ),
		];
		$fields['wcfmmp_user_location_map'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'wcfmmp_user_location_map' ],
			'id'         => 'wcfmmp_user_location_map',
			'field_type' => 'wcfmmp_user_location_map',
			'label'      => __( 'Display Map', 'wc-multivendor-marketplace' ),
		];


		return $fields;
	}

	public function process_html( $field, $key, $arg ) {

		if ( ! $this->is_enable() || empty( $key ) || ! $this->instance instanceof WCFMmp_Frontend ) {
			return;
		}
		if ( ! is_array( $this->new_fields ) || count( $this->new_fields ) == 0 ) {
			return;
		}


		if ( 'wcfmmp_user_location' === $key ) {

			foreach ( $this->new_fields as $field_key => $args ) {

				if ( isset( $args['type'] ) && 'checkbox' !== $args['type'] ) {

					$args['input_class'] = array_merge( [ 'wfacp-form-control' ], $args['input_class'] );
					$args['label_class'] = array_merge( [ 'wfacp-form-control-label' ], $args['label_class'] );
					$args['class']       = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full' ], $args['class'] );
					$args['cssready']    = [ 'wfacp-col-full' ];

				} else {
					$args['class']    = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-full ' ], $args['class'] );
					$args['cssready'] = [ 'wfacp-col-full' ];
				}
				woocommerce_form_field( $field_key, $args );
			}

		}

		if ( 'wcfmmp_user_location_map' === $key ) {
			$this->instance->wcfmmp_checkout_user_location_map( WC()->checkout() );
		}


	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Multivender_Market_Place(), 'wfacp-WCFmp' );

