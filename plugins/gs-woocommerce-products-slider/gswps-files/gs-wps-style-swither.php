<?php

function gswps_style_swither( $theme, $gs_wps_title, $gs_wps_loop ) {

	if ( $theme == 'gs-effect-1'  || $theme == 'gs-effect-5' ) {
		return gs_wps_theme_one( $theme, $gs_wps_title, $gs_wps_loop );
	}

	if ( $theme == 'gs-effect-2' || $theme == 'gs-effect-3' || $theme == 'gs-effect-4' ) {
		return gs_wps_theme_two( $theme, $gs_wps_title, $gs_wps_loop );
	}
}

function gs_wps_theme_one( $theme, $gs_wps_title, $gs_wps_loop ) {
	$output ='';
	$output .= '<figure>';
		if (has_post_thumbnail( $gs_wps_loop->post->ID )){
			$output .= get_the_post_thumbnail($gs_wps_loop->post->ID, 'gswps_product_thumb', 
							array('class' => "gswps_img") );
		} else {
		    $output .= '<img id="gs-wps-pls-img" src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" />';
		}
		$output .= '<figcaption>';
			$output .='<h3 class="gs_wps_title"><a href="'.get_permalink().'" class="gswps_img_url">' . $gs_wps_title . '</a></h3>';
			$output .='<div class="gs_wps_price">'.do_shortcode('[add_to_cart id="'.get_the_ID().'"]').'</div>';
		$output .= '</figcaption>';
	$output .= '</figure>';
	
	return $output;
}

function gs_wps_theme_two( $theme, $gs_wps_title, $gs_wps_loop ) {
	$output ='';
	$output .= '<figure>';
		$output .= '<a href="'.get_permalink().'" class="gswps_img_url">';
		if (has_post_thumbnail( $gs_wps_loop->post->ID )){
			$output .= get_the_post_thumbnail($gs_wps_loop->post->ID, 'gswps_product_thumb', 
							array('class' => "gswps_img") );
		} else {
		    $output .= '<img id="gs-wps-pls-img" src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" />';
		}
		$output .= '</a>';
		$output .= '<figcaption>';
			$output .='<h3 class="gs_wps_title"><a href="'.get_permalink().'" class="gswps_img_url">' . $gs_wps_title . '</a></h3>';
			$output .='<div class="gs_wps_price">'.do_shortcode('[add_to_cart id="'.get_the_ID().'"]').'</div>';
		$output .= '</figcaption>';

	$output .= '</figure>';
	return $output;
}