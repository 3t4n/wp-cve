<?php

/* ************************************************ */
/*	Team Post Type Functions  */
/* ************************************************ */	    
	    
	
	add_action('init', 'tx_team_register');  
	  
	function tx_team_register() {  
	
	    $labels = array(
	        'name' => _x('Team', 'post type general name', 'tx'),
	        'singular_name' => _x('Team Member', 'post type singular name', 'tx'),
	        'add_new' => _x('Add New', 'team member', 'tx'),
	        'add_new_item' => __('Add New Team Member', 'tx'),
	        'edit_item' => __('Edit Team Member', 'tx'),
	        'new_item' => __('New Team Member', 'tx'),
	        'view_item' => __('View Team Member', 'tx'),
	        'search_items' => __('Search Team Members', 'tx'),
	        'not_found' =>  __('No team members have been added yet', 'tx'),
	        'not_found_in_trash' => __('Nothing found in Trash', 'tx'),
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
			'menu_icon' => 'dashicons-groups',				
	        'taxonomies' => array('team-category')
	       );  
	  
	    register_post_type( 'team' , $args );
		
	}  
	
	function tx_create_team_taxonomy() {
		
		$atts = array(
			"label" 						=> _x('Team Categories', 'category label', 'tx'), 
			"singular_label" 				=> _x('Team Category', 'category singular label', 'tx'), 
			'public'                        => true,
			'hierarchical'                  => true,
			'show_ui'                       => true,
			'show_in_nav_menus'             => false,
			'args'                          => array( 'orderby' => 'term_order' ),
			'rewrite'                       => false,
			'query_var'                     => true
		);
		
		register_taxonomy( 'team-category', 'team', $atts );		
		
	}
	add_action( 'init', 'tx_create_team_taxonomy', 0 );		
	
	
	add_filter('manage_edit-team_columns', 'tx_team_edit_columns');   
	  
	function tx_team_edit_columns($columns){  
	        $columns = array(  
	            "cb" => "<input type=\"checkbox\" />",  
	            "thumbnail" => "",
	            "title" => __("Team Member", 'tx'),
	            "description" => __("Description", 'tx'),
	            "team-category" => __("Categories", 'tx')
	        );  
	  
	        return $columns;  
	}
	
	// Replace title placeholder	
	function tx_change_title_text( $title ){
		 $screen = get_current_screen();
	 
		 if  ( 'team' == $screen->post_type ) {
			  $title = 'Enter Team Members Name';
		 }
	 
		 return $title;
	}
	 
	add_filter( 'enter_title_here', 'tx_change_title_text' );	