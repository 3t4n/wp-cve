<?php
/**
 * Shipping
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Education\WooCommerce\Shipping;

use NovaPoshta\Education\WooCommerce\Shipping\Methods\Courier\Courier;

/**
 * Class Shipping
 *
 * @package NovaPoshta\WooCommerce\Shippings
 */
class Shipping {

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_filter( 'woocommerce_shipping_methods', [ $this, 'register_methods' ] );
	}

	/**
	 * Register shipping method
	 *
	 * @param array $methods Shipping methods.
	 *
	 * @return array
	 */
	public function register_methods( array $methods ): array {

		$methods[ Courier::ID ] = '\NovaPoshta\Education\WooCommerce\Shipping\Methods\Courier\Courier';

		return $methods;
	}

}
