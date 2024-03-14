<?php
/**
 * Functions file
 *
 * @package YITH\OrderTracking\Includes
 */

if ( ! function_exists( 'yith_ywot_get_view' ) ) {
	/**
	 * Get the view
	 *
	 * @param string $view View name.
	 * @param array  $args Parameters to include in the view.
	 */
	function yith_ywot_get_view( $view, $args = array() ) {
		$view_path = trailingslashit( YITH_YWOT_VIEW_PATH ) . $view;

		extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		if ( file_exists( $view_path ) ) {
			include $view_path;
		}
	}
}
