<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Active_WCJ {

	public function __construct() {

		add_filter( 'wfacp_allow_zero_discounting', [ $this, 'allow_zero_discounting' ], 10, 2 );
		add_filter( 'wfacp_product_raw_data', [ $this, 'wfacp_product_raw_data' ], 10, 2 );
		add_filter( 'wfacp_custom_field_order_id', [ $this, 'add_invoice_field' ] );
	}

	public function add_invoice_field( $order_id ) {
		if ( isset( $_REQUEST['create_invoice_for_order_id'] ) && $_REQUEST['create_invoice_for_order_id'] > 0 ) {
			$order_id = absint( $_REQUEST['create_invoice_for_order_id'] );
		}

		return $order_id;
	}

	public function price_by_country_enabled() {
		return function_exists( 'wcj_get_option' ) && ( 'yes' == wcj_get_option( 'wcj_price_by_country_enabled', 'no' ) );
	}

	public function allow_zero_discounting( $status ) {
		if ( $this->price_by_country_enabled() ) {
			$status = false;
		}

		return $status;
	}

	public function wfacp_product_raw_data( $raw_data, $pro ) {
		if ( did_action( 'wfac_qv_images' ) ) {
			return $raw_data;
		}
		if ( $this->price_by_country_enabled() ) {
			$raw_data['regular_price'] = $pro->get_regular_price();
			$raw_data['price']         = $pro->get_price();
		}

		return $raw_data;
	}

	public static function is_enable() {
		return class_exists( 'WC_Jetpack' );
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Active_WCJ(), 'wcj' );

