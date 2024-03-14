<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @since 1.1.0
 */

class CF7_Views_Elementor_Widget_Init {
	function __construct() {

		add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
	}

	function register_widget( $widgets_manager ) {

		require_once CF7_VIEWS_DIR_URL . '/inc/elementor/class-cf7-views-elementor-widget.php';

		$widgets_manager->register( new \CF7_Views_Elementor_Widget() );

	}

}
new CF7_Views_Elementor_Widget_Init();
