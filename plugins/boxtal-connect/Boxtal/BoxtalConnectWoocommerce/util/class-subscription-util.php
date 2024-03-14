<?php
/**
 * Contains code for subscription util class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce\Util;

use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Subscription util class.
 *
 * Helper to manage consistency between woocommerce versions subscription getters and setters.
 */
class Subscription_Util {

	/**
	 * Get id of WC subscription.
	 *
	 * @param \WC_Subscription $subscription woocommerce subscription.
	 * @return string $id subscription id
	 */
	public static function get_id( $subscription ) {
		if ( method_exists( $subscription, 'get_id' ) ) {
			return $subscription->get_id();
		}
		return $subscription->id;
	}

	/**
	 * Save WC subscription.
	 *
	 * @param \WC_Subscription $subscription woocommerce subscription.
	 * @void
	 */
	public static function save( $subscription ) {
		if ( method_exists( $subscription, 'save' ) ) {
			$subscription->save();
		}
	}

	/**
	 * Update meta data for WC subscription.
	 *
	 * @param \WC_Subscription $subscription woocommerce subscription.
	 * @param string           $key key of meta data.
	 * @param string           $data data to be added.
	 * @void
	 */
	public static function update_metadata( $subscription, $key, $data ) {
		if ( method_exists( $subscription, 'update_meta_data' ) ) {
			$subscription->update_meta_data( $key, $data );
		} else {
			update_post_meta( $subscription->id, $key, $data );
		}
	}

	/**
	 * Get meta data to WC subscription.
	 *
	 * @param \WC_Subscription $subscription woocommerce subscription.
	 * @param string           $key key of meta data.
	 * @void
	 */
	public static function get_meta( $subscription, $key ) {
		if ( method_exists( $subscription, 'get_meta' ) ) {
			return $subscription->get_meta( $key );
		}
		return get_post_meta( $subscription->id, $key, true );
	}

	/**
	 * Get an subscription parcelpoint meta data
	 *
	 * @param \WC_Subscription $subscription woocommerce subscription.
	 * @return mixed    $parcelpoint in standard format
	 */
	public static function get_parcelpoint( $subscription ) {

		$parcelpoint = self::get_meta( $subscription, Branding::$branding_short . '_parcel_point' );

		if ( ! $parcelpoint ) {
			$parcelpoint = null;
			$code        = self::get_meta( $subscription, Branding::$branding_short . '_parcel_point_code' );
			$network     = self::get_meta( $subscription, Branding::$branding_short . '_parcel_point_network' );

			if ( $code && $network ) {
				$parcelpoint = Parcelpoint_Util::create_parcelpoint(
					$network,
					$code,
					null,
					null,
					null,
					null,
					null,
					null,
					null
				);
			}
		}

		return $parcelpoint;
	}

	/**
	 * Get subscription in admin context.
	 *
	 * @return \WC_Subscription $subscription woocommerce subscription
	 */
	public static function admin_get_subscription() {
		global $the_subscription, $post;
		if ( ! is_object( $the_subscription ) && function_exists( 'wcs_get_subscription' ) ) {
			$subscription = wcs_get_subscription( $post->ID );
		} else {
			$subscription = $the_subscription;
		}
		return $subscription;
	}
}
