<?php
/**
 * Exception for invalid phone number.
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
 * Class InvalidPhoneNumber
 *
 * @package NovaPoshta\Api\Exception
 */
class InvalidPhoneNumber extends Exception {

	/**
	 * InvalidPhoneNumber constructor.
	 *
	 * @param string $phone Phone number.
	 */
	public function __construct( string $phone ) {

		parent::__construct(
			wp_kses(
				sprintf( /* translators: %1$s - phone number, %2$s, %3$s, %4$s - examples */
					__( '%1$s is invalid phone number format. The following formats are accepted: %2$s, %3$s, %4$s', 'shipping-nova-poshta-for-woocommerce' ),
					'<strong>' . esc_html( $phone ) . '</strong>',
					'+380660000000',
					'380660000000',
					'0660000000'
				),
				[
					'strong' => [],
				]
			),
			400
		);
	}
}
