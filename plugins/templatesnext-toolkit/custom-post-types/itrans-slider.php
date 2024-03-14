<?php

/* ************************************************ */
/*	Team Post Type Functions  */
/* ************************************************ */	    
	    
	
	add_action('init', 'tx_itrans_slider_register');  
	  
	function tx_itrans_slider_register() {  
	
	    $labels = array(
	        'name' => _x('itrans Slider', 'post type general name', 'tx'),
	        'singular_name' => _x('itrans Slide', 'post type singular name', 'tx'),
	        'add_new' => _x('Add New', 'itrans Slide', 'tx'),
	        'add_new_item' => __('Add New itrans Slide', 'tx'),
	        'edit_item' => __('Edit itrans Slide', 'tx'),
	        'new_item' => __('New itrans Slide', 'tx'),
	        'view_item' => __('View itrans Slide', 'tx'),
	        'search_items' => __('Search itrans Slide', 'tx'),
	        'not_found' =>  __('No itrans slide have been added yet', 'tx'),
	        'not_found_in_trash' => __('Nothing found in Trash', 'tx'),
			'featured_image' => __( 'Slide Image' ),			
	        'parent_item_colon' => ''
	    );
	
	    $args = array(  
	        'labels' => $labels,  
	        'public' => true,  
	        'show_ui' => true,
	        'show_in_menu' => true,
	        'show_in_nav_menus' => false,
	        'rewrite' => false,
	        'supports' => array('title', 'editor', 'thumbnail'),
	        'has_archive' => true,
	        'taxonomies' => array('itrans-slider-category')
	       );  
	  
	    register_post_type( 'itrans-slider' , $args );
		
	}  
	
	function create_itrans_slider_taxonomy() {
		
		$atts = array(
			"label" 						=> _x('itrans Slider Categories', 'category label', 'tx'), 
			"singular_label" 				=> _x('itrans Slider Category', 'category singular label', 'tx'), 
			'public'                        => true,
			'hierarchical'                  => true,
			'show_ui'                       => true,
			'show_in_nav_menus'             => false,
			'args'                          => array( 'orderby' => 'term_order' ),
			'rewrite'                       => false,
			'query_var'                     => true
		);
		
		register_taxonomy( 'itrans-slider-category', 'itrans-slider', $atts );		
		
	}
	add_action( 'init', 'create_itrans_slider_taxonomy', 0 );		
	
	
	add_filter("manage_edit-itrans_slider_columns", "itrans_slider_edit_columns");   
	  
	function itrans_slider_edit_columns($columns){  
	        $columns = array(  
	            "cb" => "<input type=\"checkbox\" />",  
	            "thumbnail" => "",
	            "title" => __("Slide Title", 'tx'),
	            "description" => __("Description", 'tx'),
	            "team-category" => __("Categories", 'tx')
	        );  
	  
	        return $columns;  
	}