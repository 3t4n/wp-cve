<?php
/**
 * Ajax class
 *
 * @package ZiinaPayment
 */

namespace ZiinaPayment\Ajax;

defined( 'ABSPATH' ) || exit();

/**
 * Class Ajax
 *
 * @package ZiinaPayment
 * @since   1.0.0
 */
class Main {
	/**
	 * Ajax constructor.
	 */
	public function __construct() {
		new Payment();
	}
}
