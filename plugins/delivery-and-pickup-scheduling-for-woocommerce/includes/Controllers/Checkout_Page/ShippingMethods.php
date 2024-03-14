<?php
/**
 * File responsible for methods that control shipping methods.
 *
 * Author:          Uriahs Victor
 * Created on:      03/04/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.6
 * @package Controllers
 */

namespace Lpac_DPS\Controllers\Checkout_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Helpers\Utilities;
use Lpac_DPS\Models\Plugin_Settings\OrderType;

/**
 * Class responsible for manipulating shipping methods.
 *
 * @package Lpac_DPS\Controllers\Checkout_Page
 * @since 1.0.6
 */
class ShippingMethods {

	/**
	 * Setup Session Data for plugin.
	 *
	 * @param mixed $post_data The POST data from checkout page.
	 * @return void
	 * @since 1.0.6
	 */
	public function setupSessionData( $post_data ): void {
		WC()->session->set( 'lpac_dps_order_type', false );

		$data = Utilities::normalizePostString( $post_data );

		$order_type = $data['lpac_dps_order_type'] ?? '';
		WC()->session->set( 'lpac_dps_order_type', $order_type );
	}

	/**
	 * Change the shipping methods shown based on the order type selected.
	 *
	 * @param mixed $rates Different shipping rates (methods) available.
	 * @param mixed $package The product in the cart.
	 * @return mixed
	 * @since 1.0.6
	 */
	public function alterShippingMethods( $rates, $package ) {

		if ( OrderType::filterShippingMethods() === false ) {
			return $rates;
		}

		$order_type = WC()->session->get( 'lpac_dps_order_type' );

		if ( $order_type === 'delivery' ) {
			$rates = array_filter(
				$rates,
				function ( $item ) {
					// return all rates apart from local pickup.
					if ( strpos( $item, 'local_pickup' ) === false ) {
						return true;
					}
				},
				ARRAY_FILTER_USE_KEY
			);
		}

		if ( $order_type === 'pickup' ) {
			$rates = array_filter(
				$rates,
				function ( $item ) {
					// return only local pickup rates
					if ( strpos( $item, 'local_pickup' ) !== false ) {
						return true;
					}
				},
				ARRAY_FILTER_USE_KEY
			);
		}

		return $rates;
	}

	/**
	 * Clear shipping rate cache.
	 *
	 * Without this, the method that drops shipping methods based on whether delivery or pickup is selected (alterShippingMethods()) doesn't seem to work.
	 *
	 * @param mixed $packages
	 * @return array
	 * @since 1.0.14
	 */
	public function clearShippingRateCache( $packages ): array {
		foreach ( $packages as &$package ) {
			$package['rate_cache'] = wp_rand();
		}
		unset( $package );

		return $packages;
	}
}
