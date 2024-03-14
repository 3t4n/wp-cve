<?php
defined( 'EOS_CARDS_DIR' ) || exit; //exit if file not inclued by the plugin

//Return plugin options
function eos_cards_get_option( $option_key = false ){
	if( !$option_key ){
		$option_key = 'eos-cards-options';
	}
	if( !is_multisite() ){
		return get_option( $option_key );
	}
	else{
		return get_blog_option( get_current_blog_id(),$option_key );
	}
}
