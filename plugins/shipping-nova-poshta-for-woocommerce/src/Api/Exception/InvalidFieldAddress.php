<?php
/**
 * Exception for invalid field name.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Api\Exception;

use Exception;

/**
 * Class InvalidFieldAddress
 *
 * @package NovaPoshta\Api\Exception
 */
class InvalidFieldAddress extends Exception {

	/**
	 * InvalidFieldName constructor.
	 *
	 * @param string $address Address.
	 */
	public function __construct( string $address ) {

		parent::__construct(
			wp_kses(
				sprintf( /* translators: %s - address */
					__( '%s is invalid address format. Only Russian, Ukrainian letters, numbers, comma, space, and hyphen, are allowed. Also, the maximum length is 36 characters.', 'shipping-nova-poshta-for-woocommerce' ),
					'<strong>' . esc_html( $address ) . '</strong>'
				),
				[
					'strong' => [],
				]
			),
			400
		);
	}
}
