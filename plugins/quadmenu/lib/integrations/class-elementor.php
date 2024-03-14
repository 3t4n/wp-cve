<?php

namespace QuadLayers\QuadMenu\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use QuadLayers\QuadMenu\Integrations\Elementor\Module;

/**
 * Elementor ex QuadMenu_Elementor Class
 */
class Elementor {

	private static $instance;

	public function __construct() {
		// add_filter('wp_nav_menu_args', array($this, 'elementor'), 10, 1);
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'module' ) );
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'exclude' ) );
		add_action( 'wp_footer', array( $this, 'footer' ) );
	}

	function exclude( $elementor ) {

		if ( ! class_exists( '\\Elementor\\Plugin', false ) ) {
			return;
		}

		$elementor->unregister_widget_type( 'wp-widget-quadmenu_widget' );
	}

	function module( $elementor ) {

		if ( ! class_exists( '\\Elementor\\Plugin', false ) ) {
			return;
		}

		$elementor->register_widget_type( new Module() );
	}

	function footer() {

		if ( ! class_exists( '\\Elementor\\Plugin', false ) ) {
			return;
		}
		// if (!Elementor\Plugin::$instance->editor->is_edit_mode() && !Elementor\Plugin::$instance->preview->is_preview_mode()) {
		// return;
		// }

		if ( ! property_exists( '\\Elementor\\Plugin', 'instance' ) ) {
			return;
		}

		if ( ! @\Elementor\Plugin::$instance->preview ) {
			return;
		}

		if ( ! @\Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return;
		}
		?>
	<script>
		jQuery(function ($) {
		if (window.elementorFrontend) {

			elementorFrontend.hooks.addAction('frontend/element_ready/global', function (response) {

			var $quadmenu = $('nav#quadmenu', $(response));

			if ($quadmenu.length) {

				setTimeout(function () {
				$quadmenu.quadmenu();
				}, 100);

			}

			});
		}
		});
	</script>
		<?php

	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

