<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 *  File required by options.php that includes all the functions needed for admin ajax requests
 */
add_action( 'wp_ajax_eos_create_cards_from_imgs', 'eos_create_cards_from_imgs' );
//It creates new cards from the uploaded images
function eos_create_cards_from_imgs(){
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;
	$error = false;
	if (
		false === $nonce
		|| ! wp_verify_nonce( $nonce, 'eos_cards_creation_nonce' ) //check for intentions
		|| !current_user_can( 'manage_options' ) //check for rights
		|| !isset( $_POST['ids'] ) //check of uploaded images ids
		|| !isset( $_POST['new_deck_title'] )
	) {
	   echo 0;
	   die();
	   exit;
	}
	$title_by_file = isset( $_POST['title_by_file'] ) ? sanitize_text_field( $_POST['title_by_file'] ) : false;
	$deck_title = $_POST['new_deck_title'] !== '' ? sanitize_text_field( $_POST['new_deck_title'] ) : esc_html__( 'Untitled deck','oracle-cards' );
	$n = 1;
	foreach( explode( ',',$_POST['ids'] ) as $attachment_id ){
		$name = 'true' === $title_by_file ? get_the_title( $attachment_id ) : 'card '.$n.' '.$attachment_id;
		$posts = get_posts( array( 'name' => $name,'post_type' => 'card', 'post_status' => 'publish','numberposts' => 1 ) );
		if( empty( $posts ) || !isset( $posts[0] ) || !is_object( $posts[0] ) ){
			$post_id = wp_insert_post( array(
				  'post_title'    => $name,
				  'post_status'   => 'publish',
				  'post_author'   => 1,
				  'post_type' => 'card',
				  'comment_status' => 'closed',
				  'ping_status' => 'closed',
				  'input_tax' => array( 'decks' => array( esc_attr( $deck_title ) ) )
			) );
			if( $post_id ){
				set_post_thumbnail( $post_id, $attachment_id );
				if( !term_exists( $deck_title, 'decks' ) ){
					wp_insert_term( $deck_title, 'decks' );
				}
				wp_set_object_terms( $post_id,$deck_title,'decks',true );
				++$n;
			}
			else{
				$error = true;
			}
		}
	}
	echo false === $error ? $n - 1 : 0;
	die();
}

add_action( 'wp_ajax_eos_cards_save_setting', 'eos_cards_save_setting' );
//It saves the plugin options
function eos_cards_save_setting(){
	$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;
	$error = false;
	if (
		false === $nonce
		|| ! wp_verify_nonce( $nonce, 'eos_cards_settings_nonce' ) //check for intentions
		|| !current_user_can( 'manage_options' ) //check for rights
		|| !isset( $_POST['options_json'] )
	) {
	   echo 0;
	   die();
	   exit;
	}
	if( !is_multisite() ){
		update_option( 'eos-cards-options',sanitize_text_field( $_POST['options_json'] ) );
	}
	else{
		update_blog_option( get_current_blog_id(),'eos-cards-options',sanitize_text_field( $_POST['options_json'] ) );
	}
	echo 1;
	die();
}
