<?php
/**
 * Exception for invalid id.
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
 * Class InvalidId
 *
 * @package NovaPoshta\Api\Exception
 */
class InvalidId extends Exception {

	/**
	 * InvalidId constructor.
	 *
	 * @param string $id ID.
	 */
	public function __construct( string $id ) {

		parent::__construct(
			wp_kses(
				sprintf( /* translators: %s - ID */
					__( '%s is invalid id format. Check if all fields are filled in.', 'shipping-nova-poshta-for-woocommerce' ),
					'<strong>' . esc_html( $id ) . '</strong>'
				),
				[
					'strong' => [],
				]
			),
			400
		);
	}
}
