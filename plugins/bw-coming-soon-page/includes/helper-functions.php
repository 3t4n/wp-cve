<?php

// Check whether a page already exists
if ( ! function_exists( 'bw_coming_soon_if_page_exists' ) ) {

	function bw_coming_soon_if_page_exists($slug) {

	    global $wpdb;

	    if( $wpdb->get_row( "SELECT post_name FROM wp_posts WHERE post_name = '" . $slug . "' AND post_type = 'page'", 'ARRAY_A' ) ) {

	        return true;

	    } else {

	        return false;
	        
	    }
	}
}

// Get all existing pages as an array
if ( ! function_exists( 'bwcs_get_all_pages' ) ) {

	function bwcs_get_all_pages(){

		$pages 			= get_pages();
		$pages_in_array	= array();

		foreach ( $pages as $page ) {

			$pages_in_array[ $page->ID ] = $page->post_title;
		}

		return $pages_in_array;

	}
}

// Display all pages as dropdown
if ( ! function_exists( 'bwcs_pages_as_dropdown' ) ) {

	function bwcs_pages_as_dropdown() {

		$seleced_page_ID = get_option( 'bwcs_coming_soon_page' );

		if ( empty( $seleced_page_ID ) ) {

			$seleced_page_ID = get_page_by_path( 'coming-soon' )->ID;

		}

		$pages = bwcs_get_all_pages();
		foreach ( $pages as $key => $value ) {

			$selected = ( $key == $seleced_page_ID ) ? 'selected="selected"': '';
			echo "<option value='$key' $selected> $value </option>";
		}
	}
}

// Display all user roles as checkbox
if ( ! function_exists( 'bwcs_roles_as_checkbox' ) ) {

	function bwcs_roles_as_checkbox(){
		
		global $wp_roles;
	    $roles 			= $wp_roles->get_names();
	    $selected_roles = get_option( 'bwcs_roles' );

	    $selected = '';
	    foreach ( $roles as $key => $value ) {

	    	if ( !empty( $selected_roles ) ) {
	    		$selected = ( in_array( $key, $selected_roles ) ) ? 'checked' : '';
	    	}    	

	    	$admin = ( $key == 'administrator' ) ? 'checked disabled' : '';    	

	    	echo "<input type='checkbox' name='bwcs_roles[]' value='$key' id='role-$key' $admin $selected /> <label for='role-$key'>$value </label>";
	    }

	}
}

// Display all pages as checkbox
if ( ! function_exists( 'bwcs_pages_as_checkbox' ) ) {

	function bwcs_pages_as_checkbox(){

		$seleced_coming_soon_page_ID = get_option( 'bwcs_coming_soon_page' );

		if ( empty( $seleced_coming_soon_page_ID ) ) {

			$seleced_coming_soon_page_ID = get_page_by_path( 'coming-soon' )->ID;

		}

		$selected 		= '';
		$selected_pages = get_option( 'bwcs_other_pages' );
		$pages 			= bwcs_get_all_pages();
		foreach ( $pages as $key => $value ) {

			if ( $seleced_coming_soon_page_ID == $key ) {
				continue;
			}

			if ( ! empty( $selected_pages ) ) {
	    		$selected = ( in_array( $key, $selected_pages ) ) ? 'checked' : '';
	    	}		
			
			echo "<input type='checkbox' name='bwcs_other_pages[]' value='$key' id='page-$key' $selected /> <label for='page-$key'>$value </label>";
		}
	}
}