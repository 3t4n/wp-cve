<?php

/*Admin style and script*/
add_action('admin_enqueue_scripts', 'PQDFW_load_admin');
function PQDFW_load_admin(){
	wp_enqueue_script( 'PQDFW_admin_script', PQDFW_PLUGIN_DIR . '/assets/js/pqdfw_admin_script.js', array( 'jquery', 'select2'), false, '1.0.0', true );
	wp_localize_script( 'ajaxloadpost', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_style( 'PQDFW_admin_style', PQDFW_PLUGIN_DIR . '/assets/css/pqdfw_admin_style.css', false, '1.0.0' );  
	wp_enqueue_style( 'woocommerce_admin_styles-css', WP_PLUGIN_URL. '/woocommerce/assets/css/admin.css',false,'1.0',"all"); 
  $quantity_product_rule = get_option('quantity_product_rule'); 
  wp_localize_script( 'PQDFW_admin_script', 'PQDFW_DATA', array('quantity_product_rule'=> $quantity_product_rule ));     	
}

/*Frontend style and script */
add_action('wp_enqueue_scripts', 'PQDFW_load_script_style');
function PQDFW_load_script_style(){
	wp_enqueue_style( 'PQDFW-frontend-css', PQDFW_PLUGIN_DIR.'/assets/css/pqdfw_front_style.css', false, '1.0' );
  wp_enqueue_script( 'PQDFW_frontend_script', PQDFW_PLUGIN_DIR . '/assets/js/pqdfw_front_script.js', array( 'jquery'), false, '1.0.0', true );
  wp_localize_script( 'PQDFW_frontend_script', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );      	
}
