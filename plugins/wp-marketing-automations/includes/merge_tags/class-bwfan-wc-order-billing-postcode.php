<?php

/**
 * Class BWFAN_WC_Order_Billing_Postcode
 *
 * Merge tag outputs order billing postcode
 *
 * Since 2.0.6
 */
class BWFAN_WC_Order_Billing_Postcode extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'order_billing_postcode';
		$this->tag_description = __( 'Order Billing Postcode', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_billing_postcode', array( $this, 'parse_shortcode' ) );
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

		/** If order */
		$order_id = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
		$order 	= wc_get_order( $order_id );

		if ( ! $order instanceof WC_Order ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$postcode = BWFAN_Woocommerce_Compatibility::get_order_billing_postcode( $order );

		return $this->parse_shortcode_output( $postcode, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return '10001';
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Billing_Postcode', null, 'Order' );
}
