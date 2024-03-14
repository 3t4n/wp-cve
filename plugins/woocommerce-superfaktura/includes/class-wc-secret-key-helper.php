<?php
/**
 * SuperFaktúra WooCommerce.
 *
 * @package   SuperFaktúra WooCommerce
 * @author    2day.sk <superfaktura@2day.sk>
 * @copyright 2022 2day.sk s.r.o., Webikon s.r.o.
 * @license   GPL-2.0+
 * @link      https://www.superfaktura.sk/integracia/
 */

/**
 * WC_Secret_Key_Helper.
 *
 * @package SuperFaktúra WooCommerce
 * @author  2day.sk <superfaktura@2day.sk>
 */
class WC_Secret_Key_Helper {

	/**
	 * Generate secret key.
	 */
	public static function generate_secret_key() {
		return str_shuffle( SHA1( random_int( PHP_INT_MIN, PHP_INT_MAX ) ) );
	}
}
