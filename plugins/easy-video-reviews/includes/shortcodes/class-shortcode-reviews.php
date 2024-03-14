<?php
/**
 * Showcases shortcode for Easy Video Reviews
 *
 * @since 1.0.0
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Reviews' ) ) {

	/**
	 * Showcases shortcode for Easy Video Reviews
	 *
	 * @since 1.3.8
	 */
	class Reviews extends \EasyVideoReviews\Base\Controller {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Register the hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			/**
			 * 'reviews' shortcode
			 *
			 * @since 1.4.0
			 */
			add_shortcode( 'reviews', [ $this, 'render_shortcode_template' ] );

			/**
			 * [deprecated] 'evr-reviews' shortcode
			 *
			 * @since 1.0.0
			 */
			add_shortcode( 'evr-videos', [ $this, 'render_shortcode_template' ] );
		}

		/**
		 * Render the reviews shortcode
		 *
		 * @param array $atts shortcode attributes.
		 * @return string
		 */
		public function render_shortcode_template( $atts = [] ) {
			if ( class_exists( 'WooCommerce' ) ) {
				$post_types = 'product';
				if ( is_singular($post_types) ) {
					$woocommerce_gallery = $this->option()->get( 'woocommerce_gallery_settings' );
				}
			} else {
				$woocommerce_gallery = new \stdClass();
			}
			$default_args  = [
				// internal.
				'view'             => class_exists( 'WooCommerce' ) && isset( $woocommerce_gallery['displayStyle'] ) ? sanitize_text_field( wp_unslash( $woocommerce_gallery['displayStyle '] ) ) : 'grid',
				'reviewsids'       => '',
				'gallery_style'    => class_exists('WooCommerce') && isset( $woocommerce_gallery['style'] ) ? sanitize_text_field(wp_unslash( $woocommerce_gallery['style'] )) : '1',
				'date'             => class_exists( 'WooCommerce' ) && isset( $woocommerce_gallery['showDate'] ) ? sanitize_text_field( wp_unslash( $woocommerce_gallery['showDate'] ) ) : false,
				'rounded'          => false,
				'shares'           => false,
				'grid_pagination'  => class_exists( 'WooCommerce' ) && isset( $woocommerce_gallery['grid']['pagination'] ) ? sanitize_text_field( wp_unslash( $woocommerce_gallery['grid']['pagination'] ) ) : '0',
				'additional_class_css' => class_exists( 'WooCommerce' ) && isset( $woocommerce_gallery['additionalCssClass'] ) ? sanitize_text_field( wp_unslash( $woocommerce_gallery['additionalCssClass'] ) ) : '',
				'player_control' => class_exists( 'WooCommerce' ) && isset( $woocommerce_gallery['playerControl'] ) ? sanitize_text_field( wp_unslash( $woocommerce_gallery['playerControl'] ) ) : '1',

				// slider.
				'autoplay'         => false,
				'autoplay_speed'   => 3000,
				'dots'             => false,
				'arrows'           => false,
				'infinite'         => false,
				'slides_to_show'   => 3,
				'slides_to_scroll' => 1,
				'auto_scroll_duration' => '2500',
				'draggable_scrolling' => '0',
				'auto_scrolling' => '0',

				// sorting.
				'limit'            => class_exists( 'WooCommerce' ) && isset( $woocommerce_gallery['grid'] ) ? sanitize_text_field( wp_unslash( $woocommerce_gallery['grid']['videoPerPage'] ) ) : 10,
				'offset'           => 0,
				'order'            => 'DESC',
				'orderby'          => 'id',
				'pagination'       => class_exists( 'WooCommerce' ) && isset( $woocommerce_galler['carousal']['pagination'] ) ? sanitize_text_field( wp_unslash( $woocommerce_galler['carousal']['pagination'] ) ) : '0',
				'navigation'       => class_exists( 'WooCommerce' ) && isset( $woocommerce_galler['carousal']['navigation'] ) ? sanitize_text_field( wp_unslash( $woocommerce_galler['carousal']['navigation'] ) ) : '0',
				'scrollbar'        => class_exists( 'WooCommerce' ) && isset( $woocommerce_galler['carousal']['scrollbar'] ) ? sanitize_text_field( wp_unslash( $woocommerce_galler['carousal']['scrollbar'] ) ) : '0',

				// gird.
				'columns'          => class_exists( 'WooCommerce' ) && isset( $woocommerce_gallery['columns'] ) ? sanitize_text_field( wp_unslash( $woocommerce_gallery['columns'] ) ) : '3',
				'columns_tablet'   => class_exists( 'WooCommerce' ) && isset( $woocommerce_gallery['columns_tablet'] ) ? sanitize_text_field( wp_unslash( $woocommerce_gallery['columns_tablet'] ) ) : '2',
				'columns_mobile'   => class_exists( 'WooCommerce' ) && isset( $woocommerce_gallery['columns_mobile'] ) ? sanitize_text_field( wp_unslash( $woocommerce_gallery['columns_mobile'] ) ) : '1',
				'gap'              => 10,
				'gap_tablet'       => 10,
				'gap_mobile'       => 10,

				// filters.
				'folder'           => '',
				'folder_not'       => '',
				'playlist'         => '',
				'playlist_not'     => '',
				'id'               => '',
				'id_not'           => '',
				'slug'             => '',
				'slug_not'         => '',
				'tags'             => '',
				'tags_not'         => '',
			];

			$args = shortcode_atts( $default_args, $atts );

			$configuration = wp_json_encode( $args );

			$showcase = '';

			$showcase = wp_sprintf('<div class="evr-reviews evr-reviews-showcase relative md:gap-3 lg:gap-4" data-evr-reviews="%s"><div id="evr-showcase-grid" class="evr-showcase-galleries"></div></div>',esc_attr( $configuration ));

			return wp_kses_post( $showcase );
		}
	}

	// Instantiate the class.
	Reviews::init();

}
