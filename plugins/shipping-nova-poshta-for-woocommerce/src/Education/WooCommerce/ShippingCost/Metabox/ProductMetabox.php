<?php
/**
 * Product Metabox
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Education\WooCommerce\ShippingCost\Metabox;

use NovaPoshta\Main;

/**
 * Class ProductMetabox
 *
 * @package NovaPoshta\Admin\Metabox
 */
class ProductMetabox {

	/**
	 * Nonce
	 */
	const NONCE = Main::PLUGIN_SLUG . '-product-formulas';

	/**
	 * Nonce field name
	 */
	const NONCE_FIELD = Main::PLUGIN_SLUG . '_nonce';

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_action( 'woocommerce_product_options_shipping', [ $this, 'add' ] );
	}

	/**
	 * Add metabox html on product page (Shipment tab)
	 */
	public function add() {

		require NOVA_POSHTA_PATH . 'templates/education/admin/metaboxes/product.php';
	}
}
