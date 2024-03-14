<?php

/**
 * WooCommerce Order Delivery by Themesquad
 * Plugin URL: https://woocommerce.com/products/woocommerce-order-delivery/
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_WC_delivery_date
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_WC_delivery_date {

	private $wc_delivery_date_obj = null;
	private $locations = array(
		'before_customer_details' => array(
			'hook'     => 'woocommerce_checkout_before_customer_details',
			'priority' => 10,
		),
		'before_billing'          => array(
			'hook'     => 'woocommerce_checkout_billing',
			'priority' => 5,
		),
		'after_billing'           => array(
			'hook'     => 'woocommerce_checkout_billing',
			'priority' => 99,
		),
		'before_order_notes'      => array(
			'hook'     => 'woocommerce_before_order_notes',
			'priority' => 10,
		),
		'after_order_notes'       => array(
			'hook'     => 'woocommerce_after_order_notes',
			'priority' => 10,
		),
		'after_additional_fields' => array(
			'hook'     => 'woocommerce_checkout_shipping',
			'priority' => 99,
		),
		'after_order_review'      => array(
			'hook'     => 'woocommerce_checkout_order_review',
			'priority' => 15,
		),
		'after_customer_details'  => array(
			'hook'     => 'woocommerce_checkout_after_customer_details',
			'priority' => 10,
		),
	);

	public function __construct() {

		/* Register Add field */
		add_action( 'wfacp_template_load', [ $this, 'remove_wc_delivery_date_hook' ] );
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );

		add_filter( 'wfacp_html_fields_wfacp_delivery_date', '__return_false' );

		add_action( 'process_wfacp_html', [ $this, 'call_wc_delivery_date_hook' ], 10, 3 );

		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );

		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 999, 2 );
	}

	public function remove_wc_delivery_date_hook() {
		if ( ! $this->is_enable() ) {
			return;
		}
		$obj = WFACP_Common::remove_actions( 'woocommerce_checkout_shipping', 'WC_OD_Checkout', 'checkout_content' );

		if ( function_exists( 'WC_OD' ) ) {
			$key = WC_OD()->settings()->get_setting( 'checkout_location' );

			if ( ! empty( $key ) && false == strpos( $key, 'woocommerce_' ) ) {
				$location = ( isset( $this->locations[ $key ] ) ? $this->locations[ $key ] : $this->locations['after_additional_fields'] );
				if ( isset( $location['hook'] ) && ! empty( $location['hook'] ) ) {

					if ( 'woocommerce_checkout_shipping' !== $location['hook'] ) {
						$obj = WFACP_Common::remove_actions( $location['hook'], 'WC_OD_Checkout', 'checkout_content' );
					}

				}
			}
		}


		if ( ! is_null( $obj ) ) {
			$this->wc_delivery_date_obj = $obj;

			return;
		}
	}

	public function add_field( $fields ) {
		if ( ! $this->is_enable() ) {
			return $fields;
		}
		$fields['wfacp_delivery_date'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap' ],
			'id'         => 'wfacp_delivery_date',
			'field_type' => 'advanced',
			'label'      => __( 'WC Order Delivery', 'woocommerce-order-delivery' ),
		];

		return $fields;
	}

	public function call_wc_delivery_date_hook( $field, $key, $args ) {
		if ( ! empty( $key ) && $key == 'wfacp_delivery_date' && $this->is_enable() && ! is_null( $this->wc_delivery_date_obj ) ) {
			echo "<div class='wfacp_delivery_date_wrap wfacp_clear'>";
			$this->wc_delivery_date_obj->checkout_content();
			echo "</div>";
		}
	}

	public function is_enable() {
		return class_exists( 'WC_Order_Delivery' );
	}

	public function internal_css() {
		if ( ! $this->is_enable() ) {
			return '';
		}
		if ( ! function_exists( 'wfacp_template' ) ) {
			return;
		}
		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}
		$px = $instance->get_template_type_px();
		if ( false !== strpos( $instance->get_template_type(), 'elementor' ) ) {
			$px = "7";
		}
		echo '<style>';
		if ( $px != '' ) {
			echo "body .wfacp_main_form.woocommerce .wfacp_delivery_date_wrap{clear: both;padding:0 $px" . 'px !important' . "}";
			echo "body .wfacp_main_form.woocommerce .wfacp_delivery_date_wrap p{margin-bottom:8px !important;}";
			echo "body .wfacp_main_form.woocommerce .wfacp_delivery_date_wrap h3{margin-top:0px !important;}";
			echo "body .wfacp_main_form.woocommerce .wfacp_delivery_date_wrap #wc-od #delivery_date{padding: 10px 12px 10px;}";
		}
		echo '</style>';
	}


	public function add_default_wfacp_styling( $args, $key ) {

		if ( $key == 'delivery_date' && $this->is_enable() ) {

			$args['input_class'] = [ 'wfacp-form-control' ];

		}

		return $args;
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_delivery_date(), 'wfacp-wc-delivery-date' );
