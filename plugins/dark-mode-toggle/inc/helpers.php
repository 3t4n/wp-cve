<?php
/**
 * Helper functions.
 *
 * @package Dark_Mode_Toggle
 */

defined( 'ABSPATH' ) || die();

if ( ! function_exists( 'darkmodetg' ) ) {
	/**
	 * Get service from the main registry.
	 *
	 * @param string $key Service key.
	 *
	 * @return DarkModeToggle\Service|null
	 */
	function darkmodetg( $key ) {
		return DarkModeToggle\Plugin::get( $key );
	}
}
