<?php 

defined( 'ABSPATH' ) or die();

class fabstar_Customizer_scripts {

	// Enqueue scripts/styles.
	public static function wl_customizer_enqueue() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'customizer-dynamic', WL_COMPANION_PLUGIN_URL . 'admin/inc/controllers/fabstar/js/dynamic_fields_fabstar.js',array( 'jquery'), '', true ); 
		wp_enqueue_script( 'bootstrap', WL_COMPANION_PLUGIN_URL . 'admin/js/bootstrap.js' ); 
		wp_enqueue_script( 'customizer-dynamic', WL_COMPANION_PLUGIN_URL . 'admin/js/dynamic_fields.js', array( 'jquery'), '', true ); 
		wp_enqueue_script( 'customizer-iconpicker', WL_COMPANION_PLUGIN_URL . 'admin/js/fontawesome-iconpicker.js', array( 'jquery'), '', true );
		wp_enqueue_media();

		wp_enqueue_style( 'Bootstrap', WL_COMPANION_PLUGIN_URL . 'admin/css/weblizar-companion.css', array());
		wp_enqueue_style( 'font-awesome', WL_COMPANION_PLUGIN_URL . 'admin/css/all.min.css', array() );
		wp_enqueue_style( 'customizer-dynamic', WL_COMPANION_PLUGIN_URL . 'admin/css/dynamic_fields.css', array());
		wp_enqueue_style( 'iconpicker', WL_COMPANION_PLUGIN_URL . 'admin/css/fontawesome-iconpicker.css', array());
	} 
}
