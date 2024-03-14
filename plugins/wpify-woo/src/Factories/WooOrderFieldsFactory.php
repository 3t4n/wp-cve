<?php

namespace WpifyWoo\Factories;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractCustomFieldsFactory;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractWooOrderModel;

class WooOrderFieldsFactory extends AbstractCustomFieldsFactory {

	/**
	 * Register hooks
	 *
	 * @return bool|void
	 */
	public function setup() {}

	/**
	 * Get a single field value
	 *
	 * @param AbstractWooOrderModel $order
	 * @param                       $field
	 *
	 * @return mixed
	 */
	public function get_field( $order, $field ) {
		$woo_order = $order->get_wc_order();
		return $woo_order ? $woo_order->get_meta( $field, true ) : null;
	}

	/**
	 * Save custom field value
	 *
	 * @param AbstractWooOrderModel $order
	 * @param                       $field
	 * @param                       $value
	 *
	 * @return bool|int
	 */
	public function save_field( $order, $field, $value ) {
		$woo_order = $order->get_wc_order();
		return $woo_order ? $order->get_wc_order()->update( $field, true ) : null;
	}
}
