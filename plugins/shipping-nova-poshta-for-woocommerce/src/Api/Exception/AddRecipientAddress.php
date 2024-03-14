<?php
/**
 * Exception when recipient hasn't address.
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
 * Class AllowOnlyOneRecipientAddress
 *
 * @package NovaPoshta\Api\Exception
 */
class AddRecipientAddress extends Exception {

	/**
	 * InvalidFieldName constructor.
	 */
	public function __construct() {

		parent::__construct(
			esc_html__( 'You need to add at least one recipient address', 'shipping-nova-poshta-for-woocommerce' ),
			400
		);
	}
}
