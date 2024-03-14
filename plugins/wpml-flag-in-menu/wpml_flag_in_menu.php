<?php
/*
 Plugin Name: WPML flag in menu
 Plugin URI: https://www.MijnPress.nl
 Description: Shows translated flags (for every language except current viewing lang) in the default or wp_nav_menu at last position
 Version: 1.2
 Author: Ramon Fincken
 Author URI: https://www.MijnPress.nl
 */

add_filter( 'wp_nav_menu_items', 'mijnpress_wpml_flag_in_menu', 99, 2 );

function mijnpress_wpml_flag_in_menu( $items, $args = NULL ) {

	// Sanity plugin check
	if( function_exists( 'icl_get_languages' ) ) {
		$languages = icl_get_languages( 'skip_missing=0&orderby=code' );
		$new_items = '';
		if( !empty( $languages ) ){
			foreach( $languages as $l ) {
				// Exclude current viewing language				
				if( $l['language_code'] != ICL_LANGUAGE_CODE ) {
				    $new_items .= '<li class="menu-item menu-item-type-post_type menu-item-object-page mp_custom_flag mp_'.$args->menu->slug.'">';
					if( !$l['active'] ) {
						$new_items .= '<a href="'.$l['url'].'">';
					}
					if( $l['country_flag_url'] ) {
						$new_items .= '<img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['language_code'].'" width="18" />';
					}
					// $items .= icl_disp_language($l['native_name'], $l['translated_name']);
					if( !$l['active'] ) {
						$new_items .= '</a>';
					}
					$new_items .= '</li>';
				}
			}
		}
	} else {
		// Sanity make sure
		return $items;
	}
	// Idea by Simon Weil
	if( is_rtl() ) {
		$items = $new_items.$items;
	}
	else {
		$items .= $new_items;
	}
	
	return $items;
}
