<?php
/**
 * Settings.
 *
 * @package WcGetnet
 */

declare(strict_types=1);

namespace WcGetnet\Entities;

/**
 * Settings class.
 */
class WcGetnet_Settings {

	public static function post( $key, $sanitize ) {
		return filter_input( INPUT_POST, $key, $sanitize );
	}

	public static function get( $key, $sanitize ) {
		return filter_input( INPUT_GET, $key, $sanitize );
	}

	public static function getDigits( string $value ) {
	return preg_replace( '/\D/', '', $value );
	}

	public static function getBilletSettings() {
		return get_option( 'woocommerce_getnet-billet_settings' );
	}

	public static function getCreditCardSettings() {
		return get_option( 'woocommerce_getnet-creditcard_settings' );
	}

	public static function getPixSettings() {
		return get_option( 'woocommerce_getnet-pix_settings' );
	}

	public static function payment_statuses_to_notification( $status ) {
		switch ( $status ) {
			case 'getnet-creditcard':
				return 'credit';

			case 'getnet-billet':
				return 'boleto';

			case 'getnet-pix':
				return 'pix';

			default:
				return 'undefined';
		}
	}
}
