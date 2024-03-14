<?php
/**
 * Load the translation files.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Loads the localized .mo file for the 'peachpay-for-woocommerce' domain.
 * Adjusts the .mo file path based on the site's current locale settings.
 *
 * @param string $mofile Current path to the .mo file.
 * @param string $domain Text domain of the plugin.
 * @return string Modified .mo file path for the specific locale, or original path if not applicable.
 */
function peachpay_load_textdomain( $mofile, $domain ) {
	if ( 'peachpay-for-woocommerce' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
		$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
		$mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
	}

	return $mofile;
}

add_filter( 'load_textdomain_mofile', 'peachpay_load_textdomain', 10, 2 );
