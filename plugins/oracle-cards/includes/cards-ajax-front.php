<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly
add_action( 'wp_ajax_nopriv_eos_cards_get_data','eos_cards_get_data' );
add_action( 'wp_ajax_eos_cards_get_data','eos_cards_get_data' );
function eos_cards_get_data(){
	if( !isset( $_POST['id'] ) || 0 === absint( $_POST['id'] ) ){
		die();
		exit;
	}
	$card = get_post( absint( $_POST['id'] ) );
	if( class_exists( 'EOSBMap' ) ){
		EOSBMap::addAllMappedShortcodes();
	}
	if( class_exists( 'WPBMap' ) ){
		WPBMap::addAllMappedShortcodes();
	}
	echo do_shortcode( $card->post_content );
	die();
	exit;
}

add_action( 'wp_ajax_nopriv_eos_mix_cards','eos_mix_cards' );
add_action( 'wp_ajax_eos_mix_cards','eos_mix_cards' );
function eos_mix_cards(){
	extract( $_POST );
	if( function_exists( 'eos_check_site_code' ) ){
		eos_check_site_code();
	}
	$array = json_decode( stripslashes( $data ),true );
	unset( $array['clicked'] );
	$params = '';
	if( $array ){
		foreach( $array as $param => $value){
			$params .= ' '.esc_attr( $param ).'="'.esc_attr( $value ).'"';
		}
	}
	$shortcode = isset( $array['type'] ) ? sanitize_key( $array['type'] ) : 'deck';
	if( class_exists( 'EOSBMap' ) ){
		EOSBMap::addAllMappedShortcodes();
	}
	if( class_exists( 'WPBMap' ) ){
		WPBMap::addAllMappedShortcodes();
	}
	echo do_shortcode( '[oracle_cards'.$params.']' );
	die();
	exit;
}
