<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
	return;
}
add_action( 'elementor/widgets/widgets_registered', function () {
	if ( is_file( VI_WOO_BOPO_BUNDLE_INCLUDES . 'elementor/bopobb-widget.php' ) ) {
		require_once( 'bopobb-widget.php' );
		$bopobb_widget = new BOPO_Elementor_Bundle_Widget();
		if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' )){
			Elementor\Plugin::instance()->widgets_manager->register( $bopobb_widget );
		}else {
			Elementor\Plugin::instance()->widgets_manager->register_widget_type( $bopobb_widget );
		}
	}
} );