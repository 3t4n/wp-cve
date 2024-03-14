<?php 

defined( 'ABSPATH' ) or die();

class travelogged_Customizer_scripts {

	// Enqueue scripts/styles.
	public static function wl_customizer_enqueue() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'customizer-dynamic', WL_COMPANION_PLUGIN_URL . 'admin/inc/controllers/travelogged/js/dynamic_fields_travel.js', array( 'jquery'), '', true ); 
		wp_enqueue_script( 'customizer-iconpicker', WL_COMPANION_PLUGIN_URL . 'admin/js/fontawesome-iconpicker.js', array( 'jquery'), '', true );
		wp_enqueue_media();

		wp_enqueue_style( 'font-awesome', WL_COMPANION_PLUGIN_URL . 'admin/css/all.min.css', array() );
		wp_enqueue_style( 'customizer-dynamic', WL_COMPANION_PLUGIN_URL . 'admin/css/dynamic_fields.css', array());
		wp_enqueue_style( 'iconpicker', WL_COMPANION_PLUGIN_URL . 'admin/css/fontawesome-iconpicker.css', array());
	} 

}
?>