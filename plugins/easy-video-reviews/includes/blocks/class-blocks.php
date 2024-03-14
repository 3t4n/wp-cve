<?php

/**
 * Easy Video Review Gutenberg Block
 *
 * @since 1.2.0
 * @package EasyVideoReviews
 */

namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\Blocks' ) ) {

	/**
	 * Easy Video Review Gutenberg Block
	 *
	 * @since 1.2.0
	 */
	class Blocks extends \EasyVideoReviews\Base\Controller {


		/**
		 * Register block
		 *
		 * @return void
		 */
		public function register_hooks() {
			add_filter( 'block_categories_all', [ $this, 'register_block_category' ], 10, 2 );
			add_action( 'init', [ $this, 'register_blocks' ] );
		}


		/**
		 * Register EVR Gutenberg Block Category
		 *
		 * @param array $categories Block categories.
		 * @return mixed
		 */
		public function register_block_category( $categories ) {

			return array_merge(
				$categories,
				[
					[
						'slug'  => 'evr_blocks',
						'title' => esc_html__( 'Easy Video Reviews', 'easy-video-reviews' ),
						'icon'  => 'microphone',
					],
				]
			);
		}


		/**
		 * Register EVR Gutenberg Blocks
		 *
		 * @return void
		 */
		public function register_blocks() {
			if ( ! function_exists( 'register_block_type' ) ) {
				return;
			}

			wp_register_script(
				'evr-editor-script',
				plugin_dir_url( __FILE__ ) . '/script/index.js',
				[ 'react', 'wp-block-editor', 'wp-components', 'wp-element', '_evr_inline' ],
				EASY_VIDEO_REVIEWS_VERSION,
				true
			);

			// Registers button block.
			register_block_type( 'evr/button', [ 'editor_script' => 'evr-editor-script' ] );

			// Registers showcase block.
			register_block_type( 'evr/showcase', [ 'editor_script' => 'evr-editor-script' ] );
		}
	}

	// Init the class.
	Blocks::init();
}
