<?php

if ( ! function_exists('bwcs_redirection_process') ) {

	function bwcs_redirection_process()
	{
		// get options	
		$is_enabled 			= ( ! empty( get_option( 'bwcs_enable_plugin' ) ) ) ? get_option( 'bwcs_enable_plugin' ) : 'enabled';
		$roles 					= ( ! empty( get_option( 'bwcs_roles' ) ) ) ? get_option( 'bwcs_roles' ) : array();
		$pages 					= ( ! empty( get_option( 'bwcs_other_pages' ) ) ) ? get_option( 'bwcs_other_pages' ) : array();
		$current_user_role 		= wp_get_current_user()->roles[0];
		$current_page_id 		= get_queried_object_id();
		$coming_soon_page_id	= get_option( 'bwcs_coming_soon_page' );
		$shop_page_id 			= get_option( 'woocommerce_shop_page_id' );
		
	    // If options are not udated yet 
		if ( empty( $coming_soon_page_id ) ) {
		    
			$coming_soon_page_id = get_page_by_path( 'coming-soon' )->ID;
			
		}
		
		// Condition for shop page
		if ( empty( $shop_page_id ) ){
		    
		    $shop_page = false;	    
		    
		}else{

			if ( ! function_exists('is_shop') ) {

				// nothing to do

			}elseif (is_shop() && in_array( $shop_page_id, $pages ) ) {
		        
		        $shop_page = true;
		        
		    }
		    
		}

		if ( $is_enabled != 'disabled' ) {
			
			if ( in_array( $current_page_id, $pages ) ) {

				// no redirection 

			}elseif ( in_array( $current_user_role, $roles ) || current_user_can( 'manage_options' ) ) {

				// no redirection 

			}elseif ( $shop_page ) {

				// no redirection if shop page is selected

			}elseif( $current_page_id != $coming_soon_page_id ) {

				wp_redirect( get_permalink( $coming_soon_page_id ) );
				exit;
			}


		}
	}
	
	add_action( 'template_redirect', 'bwcs_redirection_process', 1 );
}