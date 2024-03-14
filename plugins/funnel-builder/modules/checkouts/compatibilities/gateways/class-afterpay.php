<?php

#[AllowDynamicProperties] 

  class WFACP_Afterpay_process_fields {
	public function __construct() {
		add_filter( 'woocommerce_checkout_posted_data', [ $this, 'wfacp_process_fields' ] );
	}

	public function wfacp_process_fields( $data ) {
		if ( isset( $_REQUEST['payment_method'] ) && 'afterpay' !== $_REQUEST['payment_method'] ) {
			return $data;
		}
		if ( ! isset( $_REQUEST['_wfacp_post_id'] ) ) {
			return $data;
		}
		$arr = [
			'billing_first_name'  => 'shipping_first_name',
			'billing_last_name'   => 'shipping_last_name',
			'shipping_first_name' => 'billing_first_name',
			'shipping_last_name'  => 'billing_last_name',
		];

		foreach ( $arr as $a_key => $second_key ) {
			if ( isset( $_REQUEST[ $a_key ] ) && '' !== $_REQUEST[ $a_key ] ) {
				$data[ $a_key ] = $_REQUEST[ $a_key ];
				continue;
			}
			if ( isset( $_REQUEST[ $second_key ] ) && '' !== $_REQUEST[ $second_key ] ) {
				$data[ $a_key ] = $_REQUEST[ $second_key ];
				continue;
			}
		}

		return $data;
	}
}

add_action( 'wfacp_after_template_found', function () {
	if ( ! class_exists( 'WC_Gateway_Afterpay' ) ) {
		return;
	}
	WFACP_Plugin_Compatibilities::register( new WFACP_Afterpay_process_fields(), 'afterpay' );
} );
