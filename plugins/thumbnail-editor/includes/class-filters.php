<?php
class Thumbnail_Filters {
	
	public function __construct() {
		if(get_option('thep_disable_srcset') == 'Yes'){
			add_filter( 'wp_calculate_image_srcset', array($this, 'disable_srcset') );
		}
		if(get_option('thep_disable_wh') == 'Yes'){
			add_filter( 'post_thumbnail_html', array($this, 'remove_thumbnail_dimensions'), 10, 3 );
		}
		add_filter( 'post_thumbnail_html', array($this, 'add_thumbnail_attr'), 10, 4 );
	}
	
	function disable_srcset( $sources ) {
		return false;
	}
	
	function remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
		return $html;
	}

	function add_thumbnail_attr( $html, $post_id, $post_image_id, $size ) {
		$html = str_replace( '<img', '<img thumbnail="'.$size.'"', $html );
		return $html;
	}

}