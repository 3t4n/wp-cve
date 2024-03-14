<?php
/**
 * Gutenberg Controller Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Gutenberg Controller Class.
 */
class GutenbergController {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'enqueue_block_assets', [ $this, 'block_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'block_editor_assets' ] );

		if ( function_exists( 'register_block_type' ) ) {
			register_block_type(
				'rttpg/food-menu-pro',
				[
					'render_callback' => [ $this, 'render_shortcode' ],
				]
			);
		}
	}

	/**
	 * Render
	 *
	 * @param array $atts Attribute.
	 * @return void|string
	 */
	public static function render_shortcode( $atts ) {
		if ( ! empty( $atts['gridId'] ) ) {
			return do_shortcode( '[foodmenu id="' . absint( $atts['gridId'] ) . '"]' );
		}
	}

	/**
	 * Block assets
	 *
	 * @return void
	 */
	public function block_assets() {
		wp_enqueue_style( 'wp-blocks' );
	}

	/**
	 * Editor Assets
	 *
	 * @return void
	 */
	public function block_editor_assets() {
		// Scripts.
		wp_enqueue_script(
			'rt-food-menu-cgb-block-js',
			TLPFoodMenu()->assets_url() . 'js/tlp-food-menu-blocks.min.js',
			[ 'wp-blocks', 'wp-i18n', 'wp-element' ],
			( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : TLP_FOOD_MENU_VERSION,
			true
		);

		wp_localize_script(
			'rt-food-menu-cgb-block-js',
			'rtFoodMenu',
			[
				'short_codes' => Fns::get_shortCode_list(),
				'icon'        => TLPFoodMenu()->assets_url() . 'images/icon-20x20.png',
			]
		);

		wp_enqueue_style( 'fm-admin' );
	}
}
