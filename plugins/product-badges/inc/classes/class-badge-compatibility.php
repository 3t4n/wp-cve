<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Lion_Badge_Compatibility class
 */
class Lion_Badge_Compatibility {

	public static function theme_compatibility() {
		$themes = array(
			'storefront' => array(
				'css' => 'html .single-product div.product {overflow: visible;}'
			)
		);

		return $themes;
	}

	public static function get_compatibility_css() {
		$compatibility = self::theme_compatibility();

		$css = '';

		if ( isset( $compatibility[ get_option( 'stylesheet' ) ] ) ) {
			$css .= "\r\n" . $compatibility[ get_option( 'stylesheet' ) ]['css'];
		}

		return $css;
	}
}