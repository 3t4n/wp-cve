<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      1.4.4
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woo_Category_Slider_Gutenberg_Block_Init' ) ) {
	/**
	 * Woo_Category_Slider_Gutenberg_Block_Init class.
	 */
	class Woo_Category_Slider_Gutenberg_Block_Init {
		/**
		 * Script and style suffix
		 *
		 * @since 1.4.4
		 * @access protected
		 * @var string
		 */
		protected $suffix;

		/**
		 * Custom Gutenberg Block Initializer.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'woo_category_slider_gutenberg_shortcode_block' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'woo_category_slider_block_editor_assets' ) );
		}

		/**
		 * Register block editor script for backend.
		 */
		public function woo_category_slider_block_editor_assets() {
			wp_enqueue_script(
				'woo-category-slider-shortcode-block',
				plugins_url( '/GutenbergBlock/build/index.js', dirname( __FILE__ ) ),
				array( 'jquery' ),
				SP_WCS_VERSION,
				true
			);

			/**
			* Register block editor css file enqueue for backend.
			*/
			wp_enqueue_style( 'sp-wcs-swiper' );
			wp_enqueue_style( 'sp-wcs-font-awesome' );
			wp_enqueue_style( 'woo-category-slider-grid' );
		}

		/**
		 * Shortcode list.
		 *
		 * @return array
		 */
		public function woo_category_slider_pro_post_list() {
			$shortcodes = get_posts(
				array(
					'post_type'      => 'sp_wcslider',
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
		public function woo_category_slider_gutenberg_shortcode_block() {

			/**
			 * Register block editor js file enqueue for backend.
			 */
			wp_register_script( 'sp-wcs-gb_swiper-config', SP_WCS_URL . 'public/js/swiper-config' . $this->suffix . '.js', array( 'jquery' ), SP_WCS_VERSION, true );
			wp_localize_script(
				'sp-wcs-gb_swiper-config',
				'sp_woo_category_slider_load_script',
				array(
					'ajax_url'      => admin_url( 'admin-ajax.php' ),
					'path'          => SP_WCS_URL,
					'loadScript'    => SP_WCS_URL . 'public/js/swiper-config.js',
					'url'           => admin_url( 'post-new.php?post_type=sp_wcslider' ),
					'shortCodeList' => $this->woo_category_slider_pro_post_list(),
				)
			);

			/**
			 * Register Gutenberg block on server-side.
			 */
			register_block_type(
				'woo-category-slider/shortcode',
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
						'preview'            => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'is_admin'           => array(
							'type'    => 'boolean',
							'default' => is_admin(),
						),
					),
					'example'         => array(
						'attributes' => array(
							'preview' => true,
						),
					),
					// Enqueue blocks.editor.build.js in the editor only.
					'editor_script'   => array(
						'sp-wcs-swiper-js',
						'sp-wcs-gb_swiper-config',
					),
					// Enqueue blocks.editor.build.css in the editor only.
					'editor_style'    => array(),
					'render_callback' => array( $this, 'woo_category_slider_pro_render_shortcode' ),
				)
			);
		}

		/**
		 * Render callback.
		 *
		 * @param string $attributes Shortcode.
		 * @return string
		 */
		public function woo_category_slider_pro_render_shortcode( $attributes ) {

			if ( $attributes['preview'] ) {
				return '<div class="sp_wcs_shortcode_block_preview_image"><img src="' . SP_WCS_URL . 'src/Admin/GutenbergBlock/src/wp-team-block-preview.svg"/></div>';
			}

			if ( is_null( $attributes['shortcode'] ) || '' === $attributes['shortcode'] ) {
				return __( '<i></i>', 'woo-category-slider-grid' );
			}

			if ( ! $attributes['is_admin'] ) {
				return do_shortcode( '[woocatslider id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' );
			}

			return '<div id="' . uniqid() . '">' . do_shortcode( '[woocatslider id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
		}
	}
}
