<?php
/**
 * Logs.
 *
 * @package WcGetnet
 */

declare(strict_types=1);

namespace WcGetnet\Entities;

use WC_Logger;

/**
 * Logs class.
 */
class WcGetnet_Logs {
	public static function get_creditcard_order( $title, $var ) {
		$log = new WC_Logger();
		$log->add('getnet-creditcard-order-', "{$title} : ".print_r( $var, true ) );
	}

	public static function get_creditcard_order_refund( $title, $var ) {
		$log = new WC_Logger();
		$log->add('getnet-creditcard-order-refund-', "{$title} : ".print_r( $var, true ) );
	}
	public static function get_creditcard_order_refund_error( $title, $var ) {
		$log = new WC_Logger();
		$log->add('getnet-creditcard-order-refund-error-', "{$title} : ".print_r( $var, true ) );
	}

	public static function get_creditcard_error( $title, $var ) {
		$log = new WC_Logger();
		$log->add('getnet-creditcard-order-error-', "{$title} : ".print_r( $var, true ) );
	}

	public static function get_billet_order( $title, $var ) {
		$log = new WC_Logger();
		$log->add('getnet-billet-order-', "{$title} : ".print_r( $var, true ) );
	}

	public static function get_billet_error( $title, $var ) {
		$log = new WC_Logger();
		$log->add('getnet-billet-order-error-', "{$title} : ".print_r( $var, true ) );
	}

	public static function get_pix_order( $title, $var ) {
		$log = new WC_Logger();
		$log->add('getnet-pix-order-', "{$title} : ".print_r( $var, true ) );
	}

	public static function get_pix_error( $title, $var ) {
		$log = new WC_Logger();
		$log->add('getnet-pix-order-error-', "{$title} : ".print_r( $var, true ) );
	}

	public static function token_generate_error( $title, $var ) {
		$log = new WC_Logger();
		$log->add('getnet-token-generate-error-', "{$title} : ".print_r( $var, true ) );
	}

	public static function webhook_log( $status, $message ) {
		$log = new WC_Logger();
		$log->add( 'getnet-webhook-log-', "{$status} : ".print_r( $message, true ) );
	}
}
