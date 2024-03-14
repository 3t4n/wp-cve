<?php

class BWFAN_WC_Order_Review extends Merge_Tag_Abstract_Product_Display {

	private static $instance = null;

	public $supports_order_table = true;

	public function __construct() {
		$this->tag_name        = 'order_review';
		$this->tag_description = __( 'Order Review', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_review', array( $this, 'parse_shortcode' ) );
		$this->support_fallback = false;
		$this->support_v1       = false;
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
	 * @return mixed|void
	 */
	public function parse_shortcode( $attr ) {
		if ( false === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			$order_id    = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
			$order       = wc_get_order( $order_id );
			$this->order = $order;

			if ( ! $this->order instanceof WC_Order ) {
				return $this->parse_shortcode_output( '', $attr );
			}

			$items             = $order->get_items();
			$products          = [];
			$products_quantity = array();
			foreach ( $items as $item ) {
				/** added handling with wc product bundle */
				if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
					continue;
				}

				$product                                 = $item->get_product();
				$products[]                              = $product;
				$products_quantity[ $product->get_id() ] = $item->get_quantity();
			}
			$this->products          = $products;
			$this->products_quantity = $products_quantity;
		}

		$output = $this->process_shortcode( $attr );

		return $this->parse_shortcode_output( $output, $attr );
	}

	public function process_shortcode( $attr ) {

		$products          = [];
		$products_quantity = [];

		if ( is_array( $this->products ) && count( $this->products ) > 0 ) {
			$products = $this->products;
		}

		if ( is_array( $this->products_quantity ) && count( $this->products_quantity ) > 0 ) {
			$products_quantity = $this->products_quantity;
		}

		/** Filter products in case want to hide free products */
		$hide_free_products = BWFAN_Common::hide_free_products_cart_order_items();
		if ( true === $hide_free_products ) {
			/** $products */
			$products_mod = array_filter( $products, function ( $single_product ) {
				return ( $single_product->get_price() > 0 );
			} );
			$products     = $products_mod;
		}

		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			$products = wc_get_products( array(
				'numberposts' => 3,
				'post_status' => 'published', // Only published products
			) );
		}
		if ( apply_filters( 'bwfan_current_integration_action', false ) ) {
			$product_names = [];
			foreach ( $products as $single_product ) {
				$product_names[] = BWFAN_Common::get_name( $single_product );
			}
			$product_names = wp_json_encode( $product_names );

			return $product_names;
		}
		$this->template = 'review-rows';

		$file_path = BWFAN_PLUGIN_DIR . '/templates/' . $this->template . '.php';

		ob_start();
		include $file_path;
		$response = ob_get_clean();

		return apply_filters( 'bwfan_alter_email_body', $response, $products, $this->template, $products_quantity );
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Review', null, 'Order' );
}