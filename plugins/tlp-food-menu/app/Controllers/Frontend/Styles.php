<?php
/**
 * Dynamic Styles Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Frontend;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Dynamic Styles Class.
 */
class Styles {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'dynamicStyles' ], 99 );
	}

	/**
	 * Dynamic Styles
	 *
	 * @return void
	 */
	public static function dynamicStyles() {
		$styles    = '';
		$primary   = '';
		$secondary = '';

		$settings = get_option( TLPFoodMenu()->options['settings'] );

		if ( ! empty( $settings['fmp_single_primary_color'] ) ) {
			$primary = '--rtfm-primary-color: ' . Fns::sanitize_hex_color( $settings['fmp_single_primary_color'] ) . ';';
		}

		if ( ! empty( $settings['fmp_single_secondary_color'] ) ) {
			$secondary = 'background: ' . Fns::sanitize_hex_color( $settings['fmp_single_secondary_color'] ) . ';';
		}

		if ( ! empty( $primary ) || ! empty( $secondary ) ) {
			$styles .= '
				.single-food-menu {
					' . $primary . '
				}
				.fmp-wrapper .fmp-tabs > li {
					' . $secondary . '
				}
			';
		}

		wp_add_inline_style( 'fm-frontend', str_replace( [ "\r", "\n", "\t" ], '', $styles ) );
	}
}
