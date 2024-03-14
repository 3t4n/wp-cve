<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
	return;
}
add_action( 'elementor/widgets/widgets_registered', function () {
	if ( is_file( VI_WOO_COUPON_BOX_INCLUDES . '3rd/elementor/shortcode-widget.php' ) ) {
		require_once( 'shortcode-widget.php' );
		$widget = new VIWCB_Elementor_Widget();
		Elementor\Plugin::instance()->widgets_manager->register_widget_type( $widget );
	}
} );

add_action( 'elementor/preview/enqueue_styles', function () {
	wp_enqueue_style( 'wcbwidget-shortcode-style', VI_WOO_COUPON_BOX_CSS . 'shortcode-style.css', array(), VI_WOO_COUPON_BOX_VERSION );
} );
add_action( 'elementor/preview/enqueue_scripts', function () {
	wp_enqueue_script( 'wcbwidget-shortcode-script', VI_WOO_COUPON_BOX_JS . 'shortcode-script.js', array( 'jquery' ), VI_WOO_COUPON_BOX_VERSION );
} );