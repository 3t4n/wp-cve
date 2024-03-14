<?php

class BWFAN_WC_Order_Items_SKU extends BWFAN_Merge_Tag {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'order_items_sku';
		$this->tag_description = __( 'Order Items SKU', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_items_sku', array( $this, 'parse_shortcode' ) );
		$this->support_fallback = false;
		$this->priority = 2;
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
	 * @return int|mixed|void
	 */
	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->get_dummy_preview();
		}

		$order_id = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
		$order = wc_get_order( $order_id );

		if ( ! $order instanceof WC_Order ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$order_items = $order->get_items();
		if ( empty( $order_items ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$order_sku = [];

		foreach ( $order_items as $item ) {

			/** added handling with wc product bundle */
			if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
				continue;
			}

			$product = $item->get_product();
			if ( ! $product instanceof WC_Product ) {
				continue;
			}

			$order_sku[] = $product->get_sku();
		}

		if ( empty( $order_sku ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$order_sku = implode( ',', $order_sku );

		return $this->parse_shortcode_output( $order_sku, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return integer
	 */
	public function get_dummy_preview() {
		return 'tshirt, belt';
	}


}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Items_SKU', null, 'Order' );
}
