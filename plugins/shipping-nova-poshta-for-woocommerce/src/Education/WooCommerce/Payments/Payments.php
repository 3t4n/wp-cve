<?php
/**
 * Payments
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Education\WooCommerce\Payments;

use NovaPoshta\Education\WooCommerce\Payments\Gateways\COD;

/**
 * Class Payments
 *
 * @package NovaPoshta\WooCommerce\Payments
 */
class Payments {

	/**
	 * Add hooks
	 */
	public function hooks() {

		add_filter( 'woocommerce_payment_gateways', [ $this, 'register_methods' ] );
	}

	/**
	 * Register shipping method
	 *
	 * @param array $methods Shipping methods.
	 *
	 * @return array
	 */
	public function register_methods( array $methods ): array {

		$methods[ COD::ID ] = '\NovaPoshta\Education\WooCommerce\Payments\Gateways\COD';

		return $methods;
	}
}

