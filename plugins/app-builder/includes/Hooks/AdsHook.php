<?php

/**
 * class AdsHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      2.9.0
 *
 */

namespace AppBuilder\Hooks;

defined( 'ABSPATH' ) || exit;

class AdsHook {
	public function __construct() {
		add_filter( 'app_builder_prepare_hide_ads', array( $this, 'app_builder_prepare_hide_ads' ), 10, 2 );
	}

	/**
	 *
	 * Filter hide Ads
	 *
	 * @param $hide
	 * @param $user
	 *
	 * @return bool
	 */
	public function app_builder_prepare_hide_ads( $hide, $user ): bool {

		/**
		 * Return default hide ads value if some paid memberships pro functions do not exist
		 */
		if ( ! function_exists( 'pmpro_getOption' ) ||
		     ! function_exists( 'pmpro_getOption' ) ||
		     ! function_exists( 'pmpro_hasMembershipLevel' ) ) {
			return $hide;
		}

		/**
		 * Hiding Ads configs
		 */
		$hideads       = pmpro_getOption( "hideads" );
		$hideadslevels = pmpro_getOption( "hideadslevels" );

		if ( ! is_array( $hideadslevels ) ) {
			$hideadslevels = explode( ",", $hideadslevels );
		}

		/**
		 * Check membership level with user ID
		 */
		if ( $hideads == 1 && pmpro_hasMembershipLevel( null, $user->ID ) ||
		     $hideads == 2 && pmpro_hasMembershipLevel( $hideadslevels, $user->ID ) ) {
			return true;
		}

		return $hide;
	}
}
