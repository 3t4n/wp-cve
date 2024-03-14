<?php

/**
 * Order delivery date By Woocommerce Delivery
 * Plugin URI: https://welaunch.io/plugins/woocommerce-delivery/
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_WC_Delivery
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_WC_Delivery {

	private $deliveryDatePosition = null;
	private $deliveryTimePosition = null;
	private $deliveryLocationPosition = null;
	private $options = null;
	private $field_keys = [
		'woocommerce_delivery_date_field',
		'woocommerce_delivery_time_field',
		'woocommerce_delivery_location_field'
	];

	public function __construct() {
		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_filter( 'wfacp_html_fields_deliveryDatePosition', '__return_false' );
		add_filter( 'wfacp_html_fields_deliveryTimePosition', '__return_false' );
		add_filter( 'wfacp_html_fields_deliveryLocationPosition', '__return_false' );

		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 999, 3 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 99, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function add_field( $fields ) {
		if ( $this->is_enable() ) {
			$fields['deliveryDatePosition'] = [
				'type'       => 'wfacp_html',
				'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'deliveryDatePosition' ],
				'id'         => 'oddt',
				'field_type' => 'deliveryDatePosition',
				'label'      => __( 'Delivery Date', 'funnel-builder' ),
			];

			$fields['deliveryTimePosition']     = [
				'type'       => 'wfacp_html',
				'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'deliveryTimePosition' ],
				'id'         => 'oddt',
				'field_type' => 'deliveryTimePosition',
				'label'      => __( 'Delivery Time', 'funnel-builder' ),
			];
			$fields['deliveryLocationPosition'] = [
				'type'       => 'wfacp_html',
				'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'deliveryLocationPosition' ],
				'id'         => 'deliveryLocationPosition',
				'field_type' => 'deliveryLocationPosition',
				'label'      => __( 'Delivery Location', 'funnel-builder' ),
			];
		}

		return $fields;
	}

	public function actions() {
		if ( $this->is_enable() ) {
			global $woocommerce_delivery_options;
			$this->options = $woocommerce_delivery_options;
			if ( class_exists( 'WooCommerce_Delivery_Date' ) ) {
				$position                       = $this->get_option( 'deliveryDatePosition' );
				$position_time                  = $this->get_option( 'deliveryTimePosition' );
				$location_position              = $this->get_option( 'deliveryLocationPosition' );
				$this->deliveryDatePosition     = WFACP_Common::remove_actions( $position, 'WooCommerce_Delivery_Date', 'add_field' );
				$this->deliveryTimePosition     = WFACP_Common::remove_actions( $position_time, 'WooCommerce_Delivery_Time', 'add_field' );
				$this->deliveryLocationPosition = WFACP_Common::remove_actions( $location_position, 'WooCommerce_Delivery_Location', 'add_field' );
			}
		}
	}

	private function is_enable() {
		return class_exists( 'WooCommerce_Delivery' );
	}

	protected function get_option( $option ) {
		if ( ! isset( $this->options ) ) {
			return false;
		}

		if ( ! is_array( $this->options ) ) {
			return false;
		}

		if ( ! array_key_exists( $option, $this->options ) ) {
			return false;
		}

		return $this->options[ $option ];
	}


	public function call_fields_hook( $field, $key, $args ) {
		if ( ! empty( $key ) && $this->is_enable() ) {
			if ( 'deliveryDatePosition' === $key && $this->deliveryDatePosition instanceof WooCommerce_Delivery_Date ) {
				$this->deliveryDatePosition->add_field( WC()->checkout() );
			}
			if ( 'deliveryTimePosition' === $key && $this->deliveryTimePosition instanceof WooCommerce_Delivery_Time ) {
				$this->deliveryTimePosition->add_field( WC()->checkout() );
			}
			if ( 'deliveryLocationPosition' === $key && $this->deliveryLocationPosition instanceof WooCommerce_Delivery_Location ) {
				$this->deliveryLocationPosition->add_field( WC()->checkout() );
			}
		}
	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( in_array( $key, $this->field_keys ) ) {
			$args['input_class'] = array_merge( $args['input_class'], [ 'wfacp-form-control' ] );
			$args['label_class'] = array_merge( $args['label_class'], [ 'wfacp-form-control-label' ] );
			$args['class']       = array_merge( $args['class'], [ 'wfacp-col-full', 'wfacp-form-control-wrapper' ] );
		}

		return $args;
	}

	public function internal_css() {
		?>
        <style>
            .woocommerce-delivery-date-container {
                clear: both;
            }
        </style>
		<?php
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_Delivery(), 'wc-delivery' );
