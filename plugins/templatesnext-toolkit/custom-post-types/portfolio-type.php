<?php

/* ************************************************ */	
/*	Portfolio Post Type Functions  */
/* ************************************************ */	
	//$portfolio_permalinks = get_option( 'nx_portfolio_permalinks' );
	
	    
	add_action('init', 'tx_portfolio_register');  
	  
	function tx_portfolio_register() {
		
		$portfolio_permalink = _x( 'portfolio', 'slug', 'tx' );
		
	    $labels = array(
	        'name' => _x('Portfolio', 'post type general name', 'tx'),
	        'singular_name' => _x('Portfolio Item', 'post type singular name', 'tx'),
	        'add_new' => _x('Add New', 'portfolio item', 'tx'),
	        'add_new_item' => __('Add New Portfolio Item', 'tx'),
	        'edit_item' => __('Edit Portfolio Item', 'tx'),
	        'new_item' => __('New Portfolio Item', 'tx'),
	        'view_item' => __('View Portfolio Item', 'tx'),
	        'search_items' => __('Search Portfolio', 'tx'),
	        'not_found' =>  __('No portfolio items have been added yet', 'tx'),
	        'not_found_in_trash' => __('Nothing found in Trash', 'tx'),
	        'parent_item_colon' => ''
	    );
			
	    $args = array(  
	        'labels' => $labels,  
	        'public' => true,  
	        'show_ui' => true,
	        'show_in_menu' => true,
	        'show_in_nav_menus' => false,
	        'hierarchical' => false,
	        'rewrite' => $portfolio_permalink != "portfolio" ? array(
	        				'slug' => untrailingslashit( $portfolio_permalink ),
	        				'with_front' => false,
	        				'feeds' => true )
	        			: false,
	        'supports' => array('title', 'editor', 'thumbnail'),
	        'has_archive' => true,
			'menu_icon' => 'dashicons-art',

	        'taxonomies' => array('portfolio-category')
	       );  

		register_post_type( 'portfolio' , $args ); 
			
	} 
	
	
	function tx_create_portfolio_taxonomy() {
		
		$atts = array(
			"label" 						=> _x('Portfolio Categories', 'category label', 'tx'), 
			"singular_label" 				=> _x('Portfolio Category', 'category singular label', 'tx'), 
			'public'                        => true,
			'hierarchical'                  => true,
			'show_ui'                       => true,
			'show_in_nav_menus'             => false,
			'args'                          => array( 'orderby' => 'term_order' ),
			'rewrite' 						=> array(
												//'slug'         => empty( $portfolio_permalinks['category_base'] ) ? _x( 'portfolio-category', 'slug', 'nx-admin' ) : $portfolio_permalinks['category_base'],
												'slug'         => _x( 'portfolio-category', 'slug', 'tx' ),
												'with_front'   => false,
												'hierarchical' => true,
											),
			'query_var'                     => true
		);
		
		register_taxonomy( 'portfolio-category', 'portfolio', $atts );
	}
	
	add_action( 'init', 'tx_create_portfolio_taxonomy', 0 );
	 
		
	
	add_filter("manage_edit-portfolio_columns", "portfolio_edit_columns");   
	  
	function portfolio_edit_columns($columns){  
	        $columns = array(  
	            "cb" => "<input type=\"checkbox\" />",  
	            "thumbnail" => "",
	            "title" => __("Portfolio Item", 'tx'),
	            "description" => __("Description", 'tx'),
	            "portfolio-category" => __("Categories", 'tx') 
	        );  
	  
	        return $columns;  
	}