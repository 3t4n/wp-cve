<?php

/**
 * class ShortcodeHook
 *
 * @link       https://appcheap.io
 * @author     ngocdt
 * @since      2.5.1
 *
 */

namespace AppBuilder\Hooks;

defined( 'ABSPATH' ) || exit;

class ShortcodeHook {

	public function __construct() {
		add_shortcode( 'ads', array( $this, 'ads_shortcode' ) );
	}

	/**
	 * Return custom tags
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function ads_shortcode( $atts ): string {

		$atts = shortcode_atts(
			array(
				'adSize'   => 'banner',
				'width'  => '320',
				'height' => '50',
			), $atts, 'ads' );

		return '<div class="mobile-ads" data-size="' . $atts['adSize'] . '" data-width="' . $atts['width'] . '" data-height="' . $atts['height'] . '"></div>';
	}
}
