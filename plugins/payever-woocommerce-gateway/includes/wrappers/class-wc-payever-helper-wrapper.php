<?php

class WC_Payever_Helper_Wrapper {

	/**
	 * @return bool
	 */
	public function is_products_sync_enabled() {
		return WC_Payever_Helper::instance()->is_products_sync_enabled();
	}

	/**
	 * @return bool
	 */
	public function is_products_sync_cron_mode() {
		return 'cron' === get_option( WC_Payever_Helper::PAYEVER_PRODUCTS_SYNC_MODE );
	}

	/**
	 * @param WC_Order $order
	 *
	 * @return bool
	 */
	public function validate_order_payment_method( $order ) {
		return WC_Payever_Helper::instance()->validate_order_payment_method( $order );
	}

	/**
	 * @param string $payment_method
	 *
	 * @return bool
	 */
	public function is_payever_method( $payment_method ) {
		return WC_Payever_Helper::instance()->is_payever_method( $payment_method );
	}

	/**
	 * @return string
	 */
	public function get_product_sync_token() {
		return get_option( WC_Payever_Helper::PAYEVER_PRODUCTS_SYNC_TOKEN );
	}

	/**
	 * @param WC_Order $order
	 * @return string|null
	 */
	public function get_payment_method( $order ) {
		return WC_Payever_Helper::instance()->get_payment_method( $order );
	}

	/**
	 * @param string $variation_sku
	 * @return int
	 */
	public function get_product_variation_id_by_sku( $variation_sku ) {
		return WC_Payever_Helper::instance()->get_product_variation_id_by_sku( $variation_sku );
	}
}
