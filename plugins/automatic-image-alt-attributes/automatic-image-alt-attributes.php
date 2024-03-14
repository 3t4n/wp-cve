<?php
/*
Plugin Name: Automatic image ALT attributes
Description: Automatically generates ALT attributes in image HTML. Restoring WordPress < 4.7 functionality. 
Version: 2.0
License: GPL
Author: Birmingham
*/



function auto_alt_fix_1($html, $id) {
	return str_replace('alt=""','alt="'.get_the_title($id).'"',$html);
}

add_filter('image_send_to_editor', 'auto_alt_fix_1', 10, 2);



function auto_alt_fix_2($attributes, $attachment){
	if ( !isset( $attributes['alt'] ) || '' === $attributes['alt'] ) {
		$attributes['alt']=get_the_title($attachment->ID);
	}
	return $attributes;
}

add_filter('wp_get_attachment_image_attributes', 'auto_alt_fix_2', 10, 2);