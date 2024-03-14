<?php


/**
 * class FlutterWaveGateway
 *
 * @link       https://appcheap.io
 * @since      3.1.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Gateway\FlutterWaveGateway;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Data\CartData;

class FlutterWaveGateway {

	public function confirm_payment( $request ) {

		if ( ! class_exists( '\Flutterwave\Rave' ) || ! class_exists( '\FLW_WC_Payment_Gateway' ) ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "The plugin Flutterwave not install yet.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$txn_ref     = $request->get_param( 'txRef' );
		$gateway     = new \FLW_WC_Payment_Gateway();
		$overrideRef = true;


		$o = explode( '_', $txn_ref );

		$order_id = intval( $o[1] );

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return new \WP_Error(
				"app_builder_confirm_payment",
				__( "Order not found.", "app-builder" ),
				array(
					'status' => 403,
				)
			);
		}

		$payment = new \Flutterwave\Rave( $gateway->public_key, $gateway->secret_key, $txn_ref, $overrideRef, $gateway->logging_option );

		$payment->logger->notice( 'Payment completed. Now requiring payment.' );

		require_once( constant( 'APP_BUILDER_ABSPATH' ) . 'includes/Gateway/FlutterWaveGateway/AppEventHandle.php' );

		$payment->eventHandler( new \AppEventHandle( $order, $request->get_param( 'cart_key' ) ) )->requeryTransaction( urldecode( $txn_ref ) );

		return [
			'redirect' => 'order',
			'orderId'  => $order_id,
		];

	}
}
