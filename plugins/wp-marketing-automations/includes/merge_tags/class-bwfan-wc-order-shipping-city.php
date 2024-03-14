<?php

/**
 * Class BWFAN_WC_Order_Shipping_City
 *
 * Merge tag outputs order shipping city
 *
 * Since 2.0.6
 */
class BWFAN_WC_Order_Shipping_City extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'order_shipping_city';
		$this->tag_description = __( 'Order Shipping City', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_shipping_city', array( $this, 'parse_shortcode' ) );
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
			return $this->parse_shortcode_output( $this->get_dummy_preview(), $attr );
		}

		$order_id = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
		$order 	= wc_get_order( $order_id );

		if ( ! $order instanceof WC_Order ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$city = BWFAN_Woocommerce_Compatibility::get_order_shipping_city( $order );

		return $this->parse_shortcode_output( $city, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return __( 'New York', 'wp-marketing-automations' );
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Shipping_City', null, 'Order' );
}
