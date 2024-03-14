<?php
/**
 * HootKit Modules
 *
 * @package Hootkit
 */

namespace HootKit\Inc;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\HootKit\Inc\Helper_Mods' ) ) :

	class Helper_Mods {

		/**
		 * Class Instance
		 */
		private static $instance;

		/**
		 * Mods
		 */
		public static $mods = null;

		/**
		 * Constructor
		 */
		public function __construct() {
			if ( null === self::$mods ) {
				self::$mods = apply_filters( 'hootkit_default_mods', self::defaults() );
				add_action( 'after_setup_theme', array( $this, 'remove_deprecated' ), 12 );
			}
		}

		/**
		 * Remove Deprecated Modules
		 * Placeholder Function - Not currently used (may be deleted later)
		 */
		public function remove_deprecated() {
			// Remove all widgets
			if ( apply_filters( 'hootkit_deprecate_widgets', false ) )
				foreach ( self::$mods['modules'] as $mod => $atts )
					if ( ( $key = array_search( 'widget', $atts['types'] ) ) !== false ) {
						unset( self::$mods['modules'][ $mod ]['types'][ $key ] );
						if ( empty( self::$mods['modules'][ $mod ]['types'] ) )
							unset( self::$mods['modules'][ $mod ] );
					}
		}

		/**
		 * Default Module Atts
		 */
		public static function defaults() {
			return array(

				'supports'    => array(
					'cta-styles', 'content-blocks-style5', 'content-blocks-style6', 'slider-styles', 'widget-subtitle',
					'content-blocks-iconoptions', 'social-icons-altcolor',
					'slider-style3', 'slider-subtitles',
					'post-grid-firstpost-category',
					'grid-widget', // JNES@deprecated <= HootKit v1.1.3 @9.20 postgrid=>grid-widget
					'list-widget', // JNES@deprecated <= HootKit v1.1.3 @9.20 postslist=>list-widget
				),

				'modules' => array(

					// DISPLAY SET: Sliders
					'slider-image' => array(
						'types'       => array( 'widget' ),                      // Module Types available
						'displaysets' => array( 'sliders' ),                     // Settings Set
						'requires'    => array(),                                // Required plugins/components
						'desc'        => '',                                     // Settings info popover
						'assets'      => array( 'lightslider', 'font-awesome' ), // Assets required
						'adminassets' => array( 'wp-media' ),                    // Admin assets required
					),
					'carousel' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'sliders' ),
						'assets'      => array( 'lightslider' ), // 'font-awesome'
						'adminassets' => array( 'wp-media' ),
					),
					'ticker' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'sliders' ),
						'assets'      => array( 'font-awesome' ),
						'adminassets' => array( 'font-awesome' ),
					),

					// DISPLAY SET: Posts
					'content-posts-blocks' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'post' ),
						'adminassets' => array( 'select2' ),
					),
					'post-grid' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'post' ),
						'assets'      => array( 'lightslider' ), // 'font-awesome'
						'adminassets' => array( 'select2' ),
					),
					'post-list' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'post' ),
						'adminassets' => array( 'select2' ),
					),
					'postcarousel' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'sliders', 'post' ),
						'assets'      => array( 'lightslider' ), // 'font-awesome'
						'adminassets' => array( 'select2' ),
					),
					'postlistcarousel' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'sliders', 'post' ),
						'assets'      => array( 'lightslider' ), // 'font-awesome'
						'adminassets' => array( 'select2' ),
					),
					'ticker-posts' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'sliders', 'post' ),
						'adminassets' => array( 'select2' ),
					),
					'slider-postimage' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'sliders', 'post' ),
						'assets'      => array( 'lightslider' ), // 'font-awesome'
						'adminassets' => array( 'select2' ),
					),

					// DISPLAY SET: Content
					'announce' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
						'assets'      => array( 'font-awesome' ),
						'adminassets' => array( 'font-awesome' ),
					),
					'profile' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
						'adminassets' => array( 'wp-media' ),
					),
					'cta' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
					),
					'content-blocks' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
						'assets'      => array( 'font-awesome' ),
						'adminassets' => array( 'font-awesome', 'wp-media' ),
					),
					'content-grid' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
						'assets'      => array( 'lightslider' ), // 'font-awesome'
						'adminassets' => array( 'wp-media' ),
					),
					'contact-info' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
					),
					'icon-list' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
						'assets'      => array( 'font-awesome' ),
						'adminassets' => array( 'font-awesome' ),
					),
					'notice' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
						'assets'      => array( 'font-awesome' ),
						'adminassets' => array( 'font-awesome' ),
					),
					'number-blocks' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
						'assets'      => array( 'circliful' ),
					),
					'tabs' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
					),
					'toggle' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
					),
					'vcards' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'content' ),
						'adminassets' => array( 'wp-media' ),
					),

					// DISPLAY SET: Display
					'buttons' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'display' ),
					),
					'cover-image' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'display' ),
						'assets'      => array( 'lightslider' ), // 'font-awesome'
						'adminassets' => array( 'wp-media' ),
					),
					'icon' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'display' ),
						'assets'      => array( 'font-awesome' ),
						'adminassets' => array( 'font-awesome' ),
					),
					'social-icons' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'display' ),
					),

					// DISPLAY SET: Misc
					'top-banner' => array(
						'types'       => array( 'misc' ),
						'displaysets' => array( 'content' ),
						'requires'    => array( 'customizer' ),
					),
					'shortcode-timer' => array(
						'types'       => array( 'misc' ),
						'displaysets' => array( 'shortcode' ),
					),
					'fly-cart' => array(
						'types'       => array( 'misc' ),
						'displaysets' => array( 'woocom' ),
						'requires'    => array( 'woocommerce', 'customizer' ),
						'assets'      => array( 'font-awesome' ),
						// 'adminassets' => array( 'font-awesome' ), // @todo: load font-awesome in customizer
					),

					// DISPLAY SET: WooCom
					'products-carticon' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'woocom' ),
						'requires'    => array( 'woocommerce' ),
						'assets'      => array( 'font-awesome' ),
						'adminassets' => array( 'font-awesome' ),
					),
					'content-products-blocks' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'woocom' ),
						'requires'    => array( 'woocommerce' ),
						'adminassets' => array( 'select2' ),
					),
					'product-list' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'woocom' ),
						'requires'    => array( 'woocommerce' ),
						'adminassets' => array( 'select2' ),
					),
					'productcarousel' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'sliders', 'woocom' ),
						'requires'    => array( 'woocommerce' ),
						'assets'      => array( 'lightslider' ), // 'font-awesome'
						'adminassets' => array( 'select2' ),
					),
					'productlistcarousel' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'sliders', 'woocom' ),
						'requires'    => array( 'woocommerce' ),
						'assets'      => array( 'lightslider' ), // 'font-awesome'
						'adminassets' => array( 'select2' ),
					),
					'products-ticker' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'sliders', 'woocom' ),
						'requires'    => array( 'woocommerce' ),
						'adminassets' => array( 'select2' ),
					),
					'products-search' => array(
						'types'       => array( 'widget' ),
						'displaysets' => array( 'woocom' ),
						'requires'    => array( 'woocommerce' ),
					),

				),

			);
		}

		/**
		 * Returns the instance
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

	}

	Helper_Mods::get_instance();

endif;