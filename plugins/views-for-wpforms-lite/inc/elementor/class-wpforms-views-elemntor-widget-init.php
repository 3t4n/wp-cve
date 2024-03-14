<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @since 1.1.0
 */

class WPforms_Views_Elementor_Widget_Init {
	function __construct() {

		add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
	}

	function register_widget( $widgets_manager ) {

		require_once WPFORMS_VIEWS_DIR_URL_LITE . '/inc/elementor/class-wpforms-views-elementor-widget.php';

		$widgets_manager->register( new \WPforms_Views_Elementor_Widget() );

	}

}
new WPforms_Views_Elementor_Widget_Init();
