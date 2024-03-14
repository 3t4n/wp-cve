<?php
/**
 * Activation & Deactivation actions.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Helpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Activation & Deactivation actions.
 */
class Install {
	/**
	 * Activation actions.
	 *
	 * @return void
	 */
	public static function activate() {
		$get_activation_time = strtotime( 'now' );

		add_option( 'rtfm_plugin_activation_time', $get_activation_time );
		add_option( 'rtfm_activation_redirect', true );

		\flush_rewrite_rules();
	}

	/**
	 * Deactivation actions.
	 *
	 * @return void
	 */
	public static function deactivate() {
		\flush_rewrite_rules();
	}
}
