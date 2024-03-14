<?php
/**
 * WooCommerce PayPal Here Gateway
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce PayPal Here Gateway to newer
 * versions in the future. If you wish to customize WooCommerce PayPal Here Gateway for your
 * needs please refer to https://docs.woocommerce.com/document/woocommerce-gateway-paypal-here/
 *
 * @author    WooCommerce
 * @copyright Copyright (c) 2018-2020, Automattic, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * Gets the main PayPal Here plugin instance.
 *
 * @since 1.0.0
 *
 * @return \Automattic\WooCommerce\PayPal_Here\Plugin
 */
function wc_paypal_here() {

	return \Automattic\WooCommerce\PayPal_Here\Plugin::instance();
}


// TODO: Remove this when WC 3.1.0+ can be required {JB 2018-10-11}
if ( ! function_exists( 'wc_make_phone_clickable' ) ) {

	/**
	 * Converts a plaintext phone number to a clickable phone number.
	 *
	 * Remove formatting and allow "+".
	 * Example and specs: https://developer.mozilla.org/en/docs/Web/HTML/Element/a#Creating_a_phone_link
	 *
	 * @since 1.0.0
	 *
	 * @param string $phone Content to convert phone number.
	 * @return string Content with converted phone number.
	 */
	function wc_make_phone_clickable( $phone ) {

		$number = trim( preg_replace( '/[^\d|\+]/', '', $phone ) );

		return $number ? '<a href="tel:' . esc_attr( $number ) . '">' . esc_html( $phone ) . '</a>' : '';
	}

}
