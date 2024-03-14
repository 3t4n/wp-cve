<?php
function solwzd_enqueue_script(){   
	
	wp_enqueue_script( 'jquery-ui-slider');
	wp_enqueue_script( 'jquery-ui-datepicker');
	wp_enqueue_script( 'jquery-touch-punch' );
	wp_enqueue_script( 'svg_script', plugin_dir_url( __FILE__ ) . 'js/svg-inject.min.js', array('jquery'), '1.0.0', false );
	wp_enqueue_script( 'sw_script', plugin_dir_url( __FILE__ ) . 'js/custom.js', array('jquery'), '1.0.0', false );
	wp_localize_script( 'sw_script', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );	
	wp_enqueue_script( 'validation_script', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.js', array('jquery'), '1.0.0', false );
	
	if(get_option('sw_google_autocomplete_address') != ''){
		wp_enqueue_script( 'map_script', 'https://maps.googleapis.com/maps/api/js?key='.get_option('sw_google_autocomplete_address').'&libraries=places', array('jquery'), '1.0.0', false );
	}
    wp_enqueue_style( 'ui_style', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), '1.0.0', false );
    wp_enqueue_style( 'sw_style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), '1.0.0', false );
}
add_action('wp_enqueue_scripts', 'solwzd_enqueue_script');
function solwzd_include_js($hook) {
	
	// I recommend to add additional conditions just to not to load the scipts on each page
	if( is_admin() ) { 
		// Add the color picker css file       
		wp_enqueue_style( 'wp-color-picker' ); 
    	wp_enqueue_script( 'wp-color-picker');
	}
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
	if(isset($_GET["page"]) && sanitize_text_field($_GET["page"]) == "solar_options"){

		wp_enqueue_style('sw_select2', plugin_dir_url( __FILE__ ) . 'admin/css/select2.min.css' );
		wp_enqueue_script('sw_select2', plugin_dir_url( __FILE__ ) . 'admin/js/select2.min.js', array('jquery') );

		wp_enqueue_style('sw_tagify', plugin_dir_url( __FILE__ ) . 'admin/css/tagify.css' );
		wp_enqueue_script('sw_tagify', plugin_dir_url( __FILE__ ) . 'admin/js/tagify.min.js', array('jquery') );
		wp_enqueue_script( 'validation_phone', plugin_dir_url( __FILE__ ) . 'js/intlTelInput.js', array('jquery'), SOLWZD_VERSION, false );

		wp_enqueue_script( 'sw_custom_script', plugin_dir_url( __FILE__ ) . 'admin/js/custom.js', array( 'jquery' ) );
		wp_localize_script( 'sw_custom_script', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( 'sw_custom_script', 'ajax_phone_object', array( 'phone_utils' => plugin_dir_url( __FILE__ ) . 'js/utils.js' ) );
		
		wp_enqueue_style( 'validation_phone_style', plugin_dir_url( __FILE__ ) . 'css/intlTelInput.css', array(), SOLWZD_VERSION, false );
		wp_register_style( 'sw_custom_style', plugin_dir_url( __FILE__ ) . 'admin/css/custom.css', false, '1.0.0' );
        wp_enqueue_style( 'sw_custom_style' );
	}
}
add_action( 'admin_enqueue_scripts', 'solwzd_include_js' );
?>