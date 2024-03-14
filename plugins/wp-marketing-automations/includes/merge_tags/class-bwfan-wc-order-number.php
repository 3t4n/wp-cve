<?php
if ( ! class_exists( 'BWFAN_WC_Order_number' ) ) {

	class BWFAN_WC_Order_number extends BWFAN_Merge_Tag {

		private static $instance = null;

		public function __construct() {
			$this->tag_name        = 'order_number';
			$this->tag_description = __( 'Order Number', 'wp-marketing-automations' );
			add_shortcode( 'bwfan_order_number', array( $this, 'parse_shortcode' ) );
			$this->support_fallback = false;
			$this->priority         = 4;
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

			$data = BWFAN_Merge_Tag_Loader::get_data();
			if ( ! isset( $data['order_id'] ) ) {
				return '';
			}
			$order = wc_get_order( $data['order_id'] );
			if ( ! $order instanceof WC_Order ) {
				return ''; // Order not found
			}

			return $this->parse_shortcode_output( $order->get_order_number(), $attr );
		}

		/**
		 * Show dummy value of the current merge tag.
		 *
		 * @return integer
		 */
		public function get_dummy_preview() {
			return '12345';
		}
	}

	/**
	 * Register this merge tag to a group.
	 */
	if ( bwfan_is_woocommerce_active() ) {
		BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_number', null, 'Order' );
	}
}
