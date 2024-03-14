<?php
/**
 * Recorder Button Shortcode for Easy Video Reviews
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Shortcodes;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\Button') ) {
	/**
	 * Recorder Button Shortcode for Easy Video Reviews
	 *
	 * @since 1.3.8
	 */
	class Button extends \EasyVideoReviews\Base\Controller {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Register the hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			/**
			 * 'recorder' shortcode
			 *
			 * @since 1.4.0
			 */
			add_shortcode( 'recorder', [ $this, 'render_shortcode_template' ] );

			/**
			 * [deprecated] 'evr-button' shortcode
			 *
			 * @since 1.0.0
			 */
			add_shortcode( 'evr-button', [ $this, 'render_shortcode_template' ] );
		}

		/**
		 * Render the recorder shortcode
		 *
		 * @param array  $atts shortcode attributes.
		 * @param string $content shortcode content.
		 * @return string
		 */
		public function render_shortcode_template( $atts = [], $content = null ) {
			if ( class_exists( 'WooCommerce' ) ) {
				$post_types = 'product';
				if ( is_singular( $post_types ) ) {
					$woocommerce_button = (object) $this->option()->get( 'woocommerce_button' );
				}
			}

			$default_args = [
				// internal.
				'align'      => class_exists( 'WooCommerce' ) && isset( $woocommerce_button->alignment ) ? sanitize_text_field( wp_unslash( $woocommerce_button->alignment ) ) : 'text-center',
				'display'    => class_exists( 'WooCommerce' ) && isset( $woocommerce_button->display ) ? sanitize_text_field( wp_unslash( $woocommerce_button->display ) ) : 'inline-block',
				'rounded'    => class_exists( 'WooCommerce' ) && isset( $woocommerce_button->rounded ) ? sanitize_text_field( wp_unslash( $woocommerce_button->rounded ) ) : false,
				'background' => class_exists( 'WooCommerce' ) && isset( $woocommerce_button->background ) ? sanitize_text_field( wp_unslash( $woocommerce_button->background ) ) : '#000099',
				'color'      => class_exists( 'WooCommerce' ) && isset( $woocommerce_button->color ) ? sanitize_text_field( wp_unslash( $woocommerce_button->color ) ) : 'white',
				'size'       => class_exists( 'WooCommerce' ) && isset( $woocommerce_button->size ) ? sanitize_text_field( wp_unslash( $woocommerce_button->size ) ) : 'md',
				'border_radius'       => class_exists( 'WooCommerce' ) && isset( $woocommerce_button->border_radius ) ? sanitize_text_field( wp_unslash( $woocommerce_button->border_radius ) ) : 'md',
				'border_color'       => class_exists( 'WooCommerce' ) && isset( $woocommerce_button->border_color ) ? sanitize_text_field( wp_unslash( $woocommerce_button->border_color ) ) : 'md',

				// filters.
				'folder'     => 0,
				'playlist'   => 0,
				'tags'       => '',

				// accessability.
				'dynamic'    => false,
			];

			$args = shortcode_atts( $default_args, $atts );

			// Custom label.
			$custom_label = $this->io()->get_input('label', null);
			if ( ! empty ( $custom_label ) ) {
				$content = $custom_label;
			}

			// Default label.
			if ( empty( $content ) ) {
				$content = class_exists( 'WooCommerce' ) && isset( $woocommerce_button->text ) ? sanitize_text_field( wp_unslash( $woocommerce_button->text ) ) : esc_html__( 'Leave a review', 'easy-video-reviews' );
			}

			$out = wp_sprintf('<div class="flex items-center p-5 bg-[#FDFEFF] min-h-[200px] h-full %s %s"><span class="evr-recorder-button %s" style="color: %s; background: %s; font-size: %spx !important; border-radius: %s; border-color: %s;" data-evr-button="%s">%s</span></div>',
				esc_attr( $args['align'] ),
				esc_attr( $args['display'] ),
				esc_attr( class_exists( 'WooCommerce' ) && isset($woocommerce_button->text) ? 'text-sm sm:text-base font-bold p-5  border' : '' ),
				esc_attr( $args['color'] ),
				esc_attr( $args['background'] ),
				esc_attr( $args['size'] ),
				esc_attr( $args['border_radius'] ),
				esc_attr( $args['border_color'] ),
				esc_attr( $args['folder'] ),
				esc_html( $content )
			);

			return wp_kses_post( $out );
		}
	}

	// Initialize the class.
	Button::init();

}
