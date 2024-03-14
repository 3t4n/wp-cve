<?php
/**
 * Product Category Metabox
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
 * Class ProductCategoryMetabox
 *
 * @package NovaPoshta\Admin\Metabox
 */
class ProductCategoryMetabox {

	/**
	 * Nonce
	 */
	const NONCE = Main::PLUGIN_SLUG . '-product-cat-formulas';
	/**
	 * Nonce field name
	 */
	const NONCE_FIELD = Main::PLUGIN_SLUG . '_nonce';

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_action( 'product_cat_edit_form_fields', [ $this, 'edit' ] );
	}

	/**
	 * Add metabox html on product_cat edit page
	 *
	 * @param object $term current term.
	 */
	public function edit( $term ) {

		require NOVA_POSHTA_PATH . 'templates/education/admin/metaboxes/product-cat-edit.php';
	}
}
