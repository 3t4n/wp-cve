<?php

/**
 *
 * @link              https://abhi.world/
 * @since             1.0.0
 * @package           Rvtgiwc
 *
 * @wordpress-plugin
 * Plugin Name:       Rename VAT to GST for WooCommerce
 * Plugin URI:        https://abhi.world/rename-vat-to-gst-woocommerce
 * Description:       Simple little plugin that renames VAT to GST in WooCommerce's Emails and Cart/Checkout page.
 * Version:           1.0.1
 * Author:            Abhi C.
 * Author URI:        https://abhi.world/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rvtgiwc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'RVTGIWC_VERSION', '1.0.1' );

// Rename VAT to GST in Woocommerce emails and checkout page
add_filter( 'gettext', function( $translation, $text, $domain ) {
	if ( $domain == 'woocommerce' ) {
		if ( $text == '(ex. VAT)' ) { $translation = '(ex. GST)'; }
	}
	return $translation;
}, 10, 3 );
