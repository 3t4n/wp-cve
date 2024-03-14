<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'add_meta_boxes', 'rfi_post_types_add_box' );
add_action( 'admin_init', 'rfi_post_types_add_box', 1 ); 
add_action( 'save_post', 'rfi_post_types_save_data' ); 
add_action( 'wp_head', 'rfi_featured_image_header');	
add_action('admin_menu','rfi_option_menu');
function rfi_option_menu(){
	if(current_user_can('manage_options')){
	  	$icon_url = '';
	  	add_menu_page('Remove Featured Image','Remove Featured Image','administrator','rfi_settings','wp_rfi_settings',$icon_url);  
	}
}