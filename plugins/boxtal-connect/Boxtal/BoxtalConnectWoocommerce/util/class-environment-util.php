<?php
/**
 * Contains code for environment util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

use Boxtal\BoxtalConnectWoocommerce\Plugin;
use Boxtal\BoxtalPhp\RestClient;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Environment util class.
 *
 * Helper to check environment.
 */
class Environment_Util {

	/**
	 * Get warning about PHP version, WC version.
	 *
	 * @param Plugin $plugin plugin object.
	 * @return string $message
	 */
	public static function check_errors( $plugin ) {
		if ( false === RestClient::healthcheck() ) {
			/* translators: 1) Company name 2) Company name */
			return sprintf( __( '%1$s Connect - You need either the curl extension or allow_url_fopen activated on your server for the %2$s Connect plugin to work.', 'boxtal-connect' ), Branding::$company_name, Branding::$company_name );
		}

		if ( version_compare( PHP_VERSION, $plugin['min-php-version'], '<' ) ) {
			/* translators: 1) int version 2) int version */
			$message = __( '%1$s Connect - The minimum PHP version required for this plugin is %2$s. You are running %3$s.', 'boxtal-connect' );
			return sprintf( $message, Branding::$company_name, $plugin['min-php-version'], PHP_VERSION );
		}

		if ( ! defined( 'WC_VERSION' ) ) {
			/* translators: 1) Company name */
			return sprintf( __( '%s Connect requires WooCommerce to be activated to work.', 'boxtal-connect' ), Branding::$company_name );
		}

		if ( version_compare( WC_VERSION, $plugin['min-wc-version'], '<' ) ) {
			/* translators: 1) Company name 2) int version 3) int version */
			$message = __( '%1$s Connect - The minimum WooCommerce version required for this plugin is %2$s. You are running %3$s.', 'boxtal-connect' );

			return sprintf( $message, Branding::$company_name, $plugin['min-wc-version'], WC_VERSION );
		}
		return false;
	}
}
