<?php
/**
 * Nelio Content subscription-related functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils/functions
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This function returns the current subscription plan, if any.
 *
 * @return string|boolean name of the current subscription plan, or `false` if it has none.
 *
 * @since 1.0.0
 */
function nc_get_subscription() {

	$subscription = get_option( 'nc_subscription', false );

	// TODO. Compatibility with previous versions of Nelio Content.
	if ( empty( $subscription ) ) {
		return false;
	} elseif ( is_array( $subscription ) ) {
		return 'basic';
	}//end if

	return $subscription;

}//end nc_get_subscription()

/**
 * Returns whether the current user is a paying customer or not.
 *
 * @return boolean whether the current user is a paying customer or not.
 *
 * @since 1.0.0
 */
function nc_is_subscribed() {

	$subscription = nc_get_subscription();
	return ! empty( $subscription );

}//end nc_is_subscribed()

/**
 * This helper function updates the current subscription.
 *
 * @param string $plan   The plan of the subscription.
 * @param array  $limits Max profile limit values.
 *
 * @since 2.0.17
 */
function nc_update_subscription( $plan, $limits ) {

	if ( empty( $plan ) || 'free' === $plan ) {
		delete_option( 'nc_subscription' );
	} else {
		update_option( 'nc_subscription', $plan );
	}//end if

	update_option( 'nc_site_limits', $limits );

}//end nc_update_subscription()

/**
 * Returns the plan related to the given product.
 *
 * @param string $product Product name.
 *
 * @return string plan related to the given product.
 *
 * @since 2.0.17
 */
function nc_get_plan( $product ) {
	return Nelio_Content\Helpers\get_string(
		array(
			'nc-monthly'          => 'basic',
			'nc-monthly-standard' => 'standard',
			'nc-monthly-plus'     => 'plus',
			'nc-yearly'           => 'basic',
			'nc-yearly-standard'  => 'standard',
			'nc-yearly-plus'      => 'plus',
		),
		$product,
		'basic'
	);
}//end nc_get_plan()
