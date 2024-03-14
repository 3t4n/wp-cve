<?php

if(!function_exists('get_thumb_image_src')){
	function get_thumb_image_src($att_id = '', $type = ''){
		if($att_id == '' or $type == '' )
		return false;
		
		$full_image = wp_get_attachment_image_src( $att_id, $type );
		if(is_array($full_image)){
			return $full_image[0];
		} else {
			return false;
		}
	}
}

if(!function_exists('get_thumb_image')){
	function get_thumb_image($att_id = '', $type = ''){
		if($att_id == '' or $type == '' )
		return false;
		
		$full_image = wp_get_attachment_image_src( $att_id, $type );
		if(is_array($full_image)){
			return '<img src="'.$full_image[0].'" alt="'.$type.'">';
		} else {
			return false;
		}
	}
}

if(!function_exists('get_thumb_image_sc')){
	function get_thumb_image_sc( $atts ) {
		$a = shortcode_atts( array(
			'att_id' => '',
			'type' => '',
		), $atts );
		return get_thumb_image($a['att_id'], $a['type']);
	}
}

if(!function_exists('get_thumb_image_src_sc')){
	function get_thumb_image_src_sc( $atts ) {
		$a = shortcode_atts( array(
			'att_id' => '',
			'type' => '',
		), $atts );
		return get_thumb_image_src($a['att_id'], $a['type']);
	}
}

if(!function_exists('thumb_editor_afo_text_domain')){
	function thumb_editor_afo_text_domain(){
		load_plugin_textdomain('thumbnail-editor', FALSE, basename( THE_PLUGIN_PATH ) .'/languages');
	}
}