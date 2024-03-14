<?php
/*
 * WooCommerce PDF Invoices & Packing Slips
 * http://www.wpovernight.com
 */

class  WFACP_Compatibility_Pdf_Invoice_Packing_slip {
	public function __construct() {
		add_filter( 'wpo_wcpdf_address_comparison_fields', [ $this, 'remove_shipping_data' ], 999, 2 );
	}

	/**
	 * Remove Shipping data before printing in pdf if client using billing address.
	 * and not fill shipping address
	 *
	 *
	 * @param $data []
	 * @param $instance WC_order
	 *
	 * @return array
	 */
	public function remove_shipping_data( $data, $instance ) {
		/**
		 * @var $order WC_Order
		 */
		$order = $instance->order;


		$id   = $order->get_id();
		$meta = get_post_meta( $id );

		if ( isset( $meta['_shipping_same_as_billing'] ) && empty( $meta['_shipping_same_as_billing'] [0] ) ) {
			return [];
		}

		return $data;
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Pdf_Invoice_Packing_slip(), 'pdf_invoice' );