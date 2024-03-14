<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.8
 *
 * @package    WP_Team
 * @subpackage WP_Team/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin\GutenbergBlock;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Team_Gutenberg_Block_Init' ) ) {
	/**
	 * Team_Pro_Gutenberg_Block_Init class.
	 */
	class WP_Team_Gutenberg_Block_Init {
		/**
		 * Custom Gutenberg Block Initializer.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'sptf_gutenberg_shortcode_block' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'sptf_block_editor_assets' ) );
		}

		/**
		 * Register block editor script for backend.
		 */
		public function sptf_block_editor_assets() {
			wp_enqueue_script(
				'team-free-shortcode-block',
				plugins_url( '/GutenbergBlock/build/index.js', dirname( __FILE__ ) ),
				array( 'jquery' ),
				SPT_PLUGIN_VERSION,
				true
			);

			/**
			 * Register block editor css file enqueue for backend.
			 */
			wp_enqueue_style( 'team-free-swiper' );
			wp_enqueue_style( 'team-free-fontawesome' );
			wp_enqueue_style( SPT_PLUGIN_SLUG );

		}

		/**
		 * Shortcode list.
		 *
		 * @return array
		 */
		public function sptf_post_list() {
			$shortcodes = get_posts(
				array(
					'post_type'      => 'sptp_generator',
					'post_status'    => 'publish',
					'posts_per_page' => 9999,
				)
			);

			if ( count( $shortcodes ) < 1 ) {
				return array();
			}

			return array_map(
				function ( $shortcode ) {
						return (object) array(
							'id'    => absint( $shortcode->ID ),
							'title' => esc_html( $shortcode->post_title ),
						);
				},
				$shortcodes
			);
		}

		/**
		 * Register Gutenberg shortcode block.
		 */
		public function sptf_gutenberg_shortcode_block() {
			/**
			 * Register block editor js file enqueue for backend.
			 */
			wp_register_script( SPT_PLUGIN_SLUG, SPT_PLUGIN_ROOT . 'src/Frontend/js/script.js', array( 'jquery' ), SPT_PLUGIN_VERSION, true );

			wp_localize_script(
				SPT_PLUGIN_SLUG,
				'TeamFreeGbScript',
				array(
					'loodScript'    => SPT_PLUGIN_ROOT . 'src/Frontend/js/script.js',
					'path'          => SPT_PLUGIN_ROOT,
					'url'           => admin_url( 'post-new.php?post_type=sptp_generator' ),
					'shortCodeList' => $this->sptf_post_list(),
				)
			);
			/**
			 * Register Gutenberg block on server-side.
			 */
			register_block_type(
				'sp-team-pro/shortcode',
				array(
					'attributes'      => array(
						'shortcode'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'showInputShortcode' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'is_admin'           => array(
							'type'    => 'boolean',
							'default' => is_admin(),
						),
						'preview'            => array(
							'type'    => 'boolean',
							'default' => false,
						),
					),
					'example'         => array(
						'attributes' => array(
							'preview' => true,
						),
					),
					// Enqueue blocks.editor.build.js in the editor only.
					'editor_script'   => array(
						'team-free-swiper',
						SPT_PLUGIN_SLUG,
					),
					// Enqueue blocks.editor.build.css in the editor only.
					'editor_style'    => array(),
					'render_callback' => array( $this, 'sp_team_free_render_shortcode' ),
				)
			);
		}

		/**
		 * Render callback.
		 *
		 * @param string $attributes Shortcode.
		 * @return string
		 */
		public function sp_team_free_render_shortcode( $attributes ) {

			if ( is_null( $attributes['shortcode'] ) || '' === $attributes['shortcode'] ) {
				return __( '<i></i>', 'team-free' );
			}
			$class_name = '';
			if ( ! empty( $attributes['className'] ) ) {
				$class_name = 'class="' . esc_attr( $attributes['className'] ) . '"';
			}
			if ( ! $attributes['is_admin'] ) {
				return '<div ' . $class_name . ' >' . do_shortcode( '[wpteam id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
			}
			$edit_page_link = get_edit_post_link( sanitize_text_field( $attributes['shortcode'] ) );

			return '<div id="' . uniqid() . '"><a href="' . esc_url( $edit_page_link ) . '" target="_blank" class="sp_wp_team_block_edit_button">Edit View</a>' . do_shortcode( '[wpteam id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
		}
	}
}
