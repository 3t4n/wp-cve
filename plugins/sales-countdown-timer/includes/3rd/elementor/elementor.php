<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
	return;
}
if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' )) {
	add_action( 'elementor/widgets/register', function () {
		if ( is_file( SALES_COUNTDOWN_TIMER_INCLUDES . '3rd/elementor/shortcode-widget.php' ) ) {
			require_once( 'shortcode-widget.php' );
			$widget = new SALES_COUNTDOWN_TIMER_Elementor_Widget();

			Elementor\Plugin::instance()->widgets_manager->register( $widget );

		}
	} );
} else {
	add_action( 'elementor/widgets/widgets_registered', function () {
		if ( is_file( SALES_COUNTDOWN_TIMER_INCLUDES . '3rd/elementor/shortcode-widget.php' ) ) {
			require_once( 'shortcode-widget.php' );
			$widget = new SALES_COUNTDOWN_TIMER_Elementor_Widget();

			Elementor\Plugin::instance()->widgets_manager->register_widget_type( $widget );

		}
	} );
}