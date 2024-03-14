<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      2.5.4
 *
 * @package    woo-product-slider-free
 * @subpackage woo-product-slider-free/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WooProductSlider\Admin\GutenbergBlock;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gutenberg_Init class.
 */
class Gutenberg_Init {
	/**
	 * Script and style suffix
	 *
	 * @since 2.5.4
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Custom Gutenberg Block Initializer.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'woo_product_slider_free_gutenberg_shortcode_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'woo_product_slider_free_block_editor_assets' ) );
	}

	/**
	 * Register block editor script for backend.
	 */
	public function woo_product_slider_free_block_editor_assets() {
		wp_enqueue_script(
			'woo-product-slider-free-shortcode-block',
			plugins_url( '/GutenbergBlock/build/index.js', dirname( __FILE__ ) ),
			array( 'jquery' ),
			SP_WPS_VERSION,
			true
		);

		/**
		* Register block editor css file enqueue for backend.
		*/
		wp_enqueue_style( 'sp-wps-swiper' );
		wp_enqueue_style( 'sp-wps-font-awesome' );
		wp_enqueue_style( 'sp-wps-style' );
	}

	/**
	 * Shortcode list.
	 *
	 * @return array
	 */
	public function woo_product_slider_free_post_list() {
		$shortcodes = get_posts(
			array(
				'post_type'      => 'sp_wps_shortcodes',
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
	public function woo_product_slider_free_gutenberg_shortcode_block() {
		/**
		 * Register block editor js file enqueue for backend.
		 */
		wp_register_script( 'sp-wps-gb-scripts-js', esc_url( SP_WPS_URL . 'Frontend/assets/js/scripts.min.js' ), array( 'jquery' ), SP_WPS_VERSION, false );

		wp_localize_script(
			'sp-wps-gb-scripts-js',
			'sp_wps_load_script',
			array(
				'path'          => SP_WPS_URL,
				'loadScript'    => SP_WPS_URL . 'Frontend/assets/js/scripts.min.js',
				'url'           => admin_url( 'post-new.php?post_type=sp_wps_shortcodes' ),
				'shortCodeList' => $this->woo_product_slider_free_post_list(),
			)
		);

		/**
		 * Register Gutenberg block on server-side.
		 */
		register_block_type(
			'woo-product-slider-pro/shortcode',
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
					'sp-wps-swiper-js',
					'sp-wps-gb-scripts-js',
				),
				// Enqueue blocks.editor.build.css in the editor only.
				'editor_style'    => array(),
				'render_callback' => array( $this, 'woo_product_slider_free_render_shortcode' ),
			)
		);
	}

	/**
	 * Render callback.
	 *
	 * @param string $attributes ShortCode.
	 * @return string
	 */
	public function woo_product_slider_free_render_shortcode( $attributes ) {
		$class_name = '';
		if ( ! empty( $attributes['className'] ) ) {
			$class_name = $attributes['className'];
		}
		if ( empty( $attributes['shortcode'] ) || ! get_post_status( $attributes['shortcode'] ) ) {
			return ' ';
		}
		if ( ! $attributes['is_admin'] ) {
			return '<div class="' . esc_attr( $class_name ) . '">' . do_shortcode( '[woo_product_slider id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
		}

		$edit_page_link = get_edit_post_link( sanitize_text_field( $attributes['shortcode'] ) );

		return ' <div id="' . uniqid() . '"> <a href="' . $edit_page_link . '" target="_blank" class="sp_woo_product_slider_block_edit_button">Edit View</a>' . do_shortcode( '[woo_product_slider id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
	}
}
