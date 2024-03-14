<?php

class BWFAN_WC_Order_Customer_Details extends BWFAN_Merge_Tag {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'order_customer_details';
		$this->tag_description = __( 'Order Customer Details', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_customer_details', array( $this, 'parse_shortcode' ) );

		/**  add styles for customer details */
		add_action( 'bwfan_output_email_style', array( $this, 'order_customer_details_style' ) );
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
	 * @return mixed|string|void
	 */
	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->parse_shortcode_output( $this->get_dummy_preview(), $attr );
		}

		$order_id = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
		$order    = wc_get_order( $order_id );

		if ( ! $order instanceof WC_Order ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		ob_start();
		do_action( 'woocommerce_email_customer_details', $order, false, false, '' );
		$output = ob_get_clean();

		return $this->parse_shortcode_output( $output, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return '-';
	}

	/**
	 * set no border for customer details
	 */
	public function order_customer_details_style() {
		?>
        .address {
        padding: 12px;
        color: '#333333';
        border: none !important;
        }
		<?php
	}


}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Customer_Details', null, 'Order' );
}