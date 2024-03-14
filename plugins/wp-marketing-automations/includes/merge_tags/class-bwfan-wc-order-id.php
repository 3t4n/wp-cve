<?php

class BWFAN_WC_Order_Id extends BWFAN_Merge_Tag {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'order_id';
		$this->tag_description = __( 'Order ID', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_id', array( $this, 'parse_shortcode' ) );
		$this->support_fallback = false;
		$this->priority         = 3;
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

		return $this->parse_shortcode_output( $data['order_id'], $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return integer
	 */
	public function get_dummy_preview() {
		return 266;
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Id', null, 'Order' );
}
