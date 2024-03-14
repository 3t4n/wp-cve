<?php
/**
 * Easy Video Reviews - Elementor
 * Elementor
 *
 * @package EasyVideoReviews
 */

namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( '\EasyVideoReviews\ElementorInit' ) ) {
	/**
	 * Class ElementorInit
	 *
	 * @since 1.0.0
	 * @package EasyVideoReviews
	 */
	class ElementorInit {

		/**
		 * ElementorInit constructor.
		 */
		public function __construct() {
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'add_evr_elementor_widgets' ], 99 );
			add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'evr_elementor_before_enqueue_scripts' ] );
		}

		/**
		 * Add Elementor Widgets
		 */
		public function add_evr_elementor_widgets() {
			require_once __DIR__ . '/widgets/class-elementor-button-widget.php';
			require_once __DIR__ . '/widgets/class-elementor-showcase-widget.php';

			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \EasyVideoReviews\Elementor\Widget\Button() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \EasyVideoReviews\Elementor\Widget\Showcase() );
		}

		/**
		 * Elementor before scripts add custom icon class
		 */
		public function evr_elementor_before_enqueue_scripts() {
			echo '<style>';
			echo '
        .evr-logo-icon {
        width: 25px;
        height: 20px;
        display: inline-block;
        margin: auto;
        background-image: url(\'' . esc_url( EASY_VIDEO_REVIEWS_PUBLIC ) . '/images/evr.svg\') !important;
        background-size: 100%;
        }';
			echo '</style>';
		}
	}

	new ElementorInit();
}
