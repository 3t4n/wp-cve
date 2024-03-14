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
 * Class InvalidFieldDescription
 *
 * @package NovaPoshta\Api\Exception
 */
class InvalidFieldDescription extends Exception {

	/**
	 * InvalidFieldDescription constructor.
	 *
	 * @param string $description Description.
	 */
	public function __construct( string $description ) {

		parent::__construct(
			wp_kses(
				sprintf( /* translators: %s - description */
					__( '%s is invalid description format. Only Russian, Ukrainian letters, numbers, comma, space, and hyphen, are allowed. Also, the maximum length is 36 characters.', 'shipping-nova-poshta-for-woocommerce' ),
					'<strong>' . esc_html( $description ) . '</strong>'
				),
				[
					'strong' => [],
				]
			),
			400
		);
	}
}
