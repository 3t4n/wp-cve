<?php

function mpsp_style(){


 // wp_register_style('mpsp-custom-style',plugins_url('css/custom_style.css',__FILE__));

	wp_register_style('mpsp-style',plugins_url('owl-carousel/owl.carousel.css',__FILE__));
  
	wp_register_style('mpsp_theme',plugins_url('owl-carousel/owl.theme.css',__FILE__));

	wp_register_style('mpsp_transitions',plugins_url('owl-carousel/owl.transitions.css',__FILE__));
}
add_filter('init','mpsp_style');

function mpsp_script(){
  
	
	wp_register_script('mpsp_script1',plugins_url('owl-carousel/owl.carousel.js',__FILE__), array( 'jquery'), false);

}

add_filter('init','mpsp_script');

function mpsp_scripts_add() {
	$screenid = get_current_screen();
	if($screenid->id == 'mpsp_slider'){ 
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'wp-color-picker');
	    wp_enqueue_script( 'mpsp-script-colorpicker-handle', plugins_url('/lpp_color_picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	    wp_enqueue_style('mpsp-custom-style',plugins_url('css/custom_style.css',__FILE__) );
	}
}

add_action('admin_enqueue_scripts', 'mpsp_scripts_add');



 ?>