<?php

class BWFAN_WC_Order_Billing_Phone extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'order_billing_phone';
		$this->tag_description = __( 'Order Billing Phone', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_billing_phone', array( $this, 'parse_shortcode' ) );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Parse the merge tag and return its value.
	 *
	 * @param $attr
	 *
	 * @return mixed|string|void
	 */
	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->get_dummy_preview();
		}

		$order_id = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
		$order 	= wc_get_order( $order_id );

		if ( ! $order instanceof WC_Order ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$billing_phone   = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_billing_phone' );
		$billing_country = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_billing_country' );

		if ( ! empty( $billing_country ) ) {
			$billing_phone = BWFAN_Phone_Numbers::add_country_code( $billing_phone, $billing_country );
		}

		return $this->parse_shortcode_output( $billing_phone, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return '8585858585';
	}


}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Billing_Phone', null, 'Order' );
}
