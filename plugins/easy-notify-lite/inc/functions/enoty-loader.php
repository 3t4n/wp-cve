<?php

if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly.');
}

function easynotify_loader( $data, $id, $getval, $type ) {
	
	$val = array();
	$preval = array();
	$result = array();
	$metakey = array();
	
	$val['headertext'] = get_post_meta( $id, 'enoty_cp_header_text', true );
	$val['headerback'] = get_post_meta( $id, 'enoty_cp_header_back_col', true );
	$val['maincontent'] = get_post_meta( $id, 'enoty_cp_maincontent', true );
	$val['maincontentfont'] = get_post_meta( $id, 'enoty_cp_main_text_size_col_fnt', true );
	$val['maincontentcol'] = get_post_meta( $id, 'enoty_cp_main_text_size_col_clr', true );	
	$val['mainimage'] = get_post_meta( $id, 'enoty_cp_img', true );
	$val['bullets'] = get_post_meta( $id, 'enoty_cp_bullet', true );
	$val['bulletssize'] = get_post_meta( $id, 'enoty_cp_bullet_list_text_fnt', true );
	$val['bulletsfont'] = get_post_meta( $id, 'enoty_cp_bullet_list_text_clr', true );
	$val['bulletstyle'] = get_post_meta( $id, 'enoty_cp_bullet_style_color', true );
  	$val['bulletsison'] = get_post_meta( $id, 'enoty_cp_bullet_swc', true );
	$val['headertextfont'] = get_post_meta( $id, 'enoty_cp_header_text_size_col_fnt', true );	
	$val['headertextcol'] = get_post_meta( $id, 'enoty_cp_header_text_size_col_clr', true );
  	$val['pattern'] = get_post_meta( $id, 'enoty_cp_pattern', true );
  	$val['overlaycol'] = get_post_meta( $id, 'enoty_cp_overlay_col', true );
  	$val['overlayopct'] = get_post_meta( $id, 'enoty_cp_overlay_opcty', true );	

	$preval['headertext']  = 'enoty_cp_header_text';
	$preval['headerback'] = 'enoty_cp_header_back_col';
	$preval['maincontent'] = 'enoty_cp_maincontent';
	$preval['maincontentfont'] = 'enoty_cp_main_text_size_col_fnt';
	$preval['maincontentcol'] = 'enoty_cp_main_text_size_col_clr';	
	$preval['mainimage'] = 'enoty_cp_img';
	$preval['bullets']  = 'enoty_cp_bullet';
	$preval['bulletssize']  = 'enoty_cp_bullet_list_text_fnt';
	$preval['bulletsfont']  = 'enoty_cp_bullet_list_text_clr';
	$preval['bulletstyle']  = 'enoty_cp_bullet_style_color';
	$preval['bulletsison']  = 'enoty_cp_bullet_swc';
	$preval['headertextfont']  = 'enoty_cp_header_text_size_col_fnt';	
	$preval['headertextcol']  = 'enoty_cp_header_text_size_col_clr';
  	$preval['pattern'] = 'enoty_cp_pattern';
  	$preval['overlaycol'] = 'enoty_cp_overlay_col';
  	$preval['overlayopct'] = 'enoty_cp_overlay_opcty';

	
		
	if ( trim( $type ) == '1' ) {
		
		foreach( $preval as $key => $value) {
			foreach( $data as $k ){
				if ( $key == $k ) {
					$metakey[$k] = $value;
					}
				}
			}

			foreach( $metakey as $k => $val ){
				foreach( $getval as $key => $value) {
				if ( $key == $val ) {
					$result[$k] = $value ;
					}
				}
			}
		
		
	} else {
		
		foreach( $data as $k ){
			foreach($val as $key => $value) {
				if ( $k == isset($key) ) {
					$result[$key] = $value;
					}
				}
			}
		}
		
	return $result;
	

}


?>