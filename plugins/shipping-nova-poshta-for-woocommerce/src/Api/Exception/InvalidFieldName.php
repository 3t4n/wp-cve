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
 * Class InvalidFieldName
 *
 * @package NovaPoshta\Api\Exception
 */
class InvalidFieldName extends Exception {

	/**
	 * InvalidFieldName constructor.
	 *
	 * @param string $name Field name.
	 */
	public function __construct( string $name ) {

		parent::__construct(
			wp_kses(
				sprintf( /* translators: %s - field name */
					__( '%s is invalid name format. Only Russian, Ukrainian letters are allowed. Also, the maximum length is 36 characters.', 'shipping-nova-poshta-for-woocommerce' ),
					'<strong>' . esc_html( $name ) . '</strong>'
				),
				[
					'strong' => [],
				]
			),
			400
		);
	}
}
