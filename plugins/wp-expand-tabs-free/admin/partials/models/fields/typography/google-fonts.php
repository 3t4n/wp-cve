<?php
/**
 * Framework Google fonts file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'wptabspro_get_google_fonts' ) ) {
	/**
	 * Google fonts.
	 */
	function wptabspro_get_google_fonts() {

		return array(
			'Open Sans'           => array( array( '300', '300italic', 'normal', 'italic', '600', '600italic', '700', '700italic', '800', '800italic' ), array( 'cyrillic-ext', 'cyrillic', 'greek-ext', 'latin-ext', 'greek', 'latin', 'vietnamese' ) ),
			'Open Sans Condensed' => array( array( '300', '300italic', '700' ), array( 'cyrillic-ext', 'cyrillic', 'greek-ext', 'latin-ext', 'greek', 'latin', 'vietnamese' ) ),
		);
	}
}

