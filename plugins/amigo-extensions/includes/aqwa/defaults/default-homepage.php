<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Aqwa theme default homepage settings
 *
 * 
 */ 

$post_arg = array(
	'post_title' => 'Home',
	'post_name' => 'Home',
	'post_status' => 'publish' ,
	'post_type' => 'page',
	'comment_status' => 'closed',
	'ping_status' =>  'closed' ,
	'post_author' => 1,
	'post_date' => date('Y-m-d H:i:s')		
	
);  

$page_value = wp_insert_post( $post_arg, false );
if ( $page_value && ! is_wp_error( $page_value ) ){
	update_post_meta( $page_value, '_wp_page_template', 'templates/template-homepage.php' );
	$page = get_page_by_title('Home');
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $page->ID );
}