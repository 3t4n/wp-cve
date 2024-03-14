<?php
namespace WC_BPost_Shipping\Assets;

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;

/**
 * Class WC_BPost_Shipping_Assets_Detector try to detect the page loaded to known resources to provide to registration
 * @package WC_BPost_Shipping\Assets
 */
class WC_BPost_Shipping_Assets_Detector {

	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter_woocommerce;

	/**
	 * WC_BPost_Shipping_Assets_Detector constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter_woocommerce
	 */
	public function __construct( WC_BPost_Shipping_Adapter_Woocommerce $adapter_woocommerce ) {
		$this->adapter_woocommerce = $adapter_woocommerce;
	}

	/**
	 * Inform if the user is on the checkout page
	 * @return bool
	 */
	public function is_checkout_page() {
		return $this->adapter_woocommerce->is_checkout() && ! $this->adapter_woocommerce->is_order_received_page();
	}

	/**
	 * Inform if the user is on the order receive page
	 * @return bool
	 */
	public function is_checkout_order_receive_page() {
		return $this->adapter_woocommerce->is_order_received_page();
	}

	/**
	 * Inform if the user is on the bpost admin page
	 * @return bool
	 */
	public function is_admin_bpost_page() {
		return $this->adapter_woocommerce->is_admin(); //TODO complete restrictions
	}

	/**
	 * @return bool
	 */
	public function is_admin_order_edit_page() {
		return $this->adapter_woocommerce->is_admin(); //TODO complete restrictions
	}
}
