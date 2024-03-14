<?php
/**
 * Register `team` post type
 */
function custom_post_type() {
	
	// Service Labels
	$service_labels = array(
		'name' => __("Service", 'free-education-helper'),
		'singular_name' => __("Service", 'free-education-helper'),
		'menu_name' => 'Services',
		'add_new' => __("Add New", 'free-education-helper'),
		'add_new_item' => __("Add New Service",'free-education-helper'),
		'edit_item' => __("Edit Service",'free-education-helper'),
		'new_item' => __("New Service",'free-education-helper'),
		'view_item' => __("View Service",'free-education-helper'),
		'search_items' => __("Search Services",'free-education-helper'),
		'not_found' =>  __("No Service Found",'free-education-helper'),
		'not_found_in_trash' => __("No Service Found in Trash",'free-education-helper'),
		'parent_item_colon' => '',
	);

	// Service Arguments
	$service_args = array(
		'labels' => $service_labels,
		'public' => true,
		'has_archive' => true,
		'show_in_menu'		=> true,
		'menu_position' => 8,
		'exclude_from_search' => false,
		'menu_icon'	=> 'dashicons-admin-page',
		'rewrite' => false,
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array('service_categories','post_tag')
	);


	// Testimonials Labels
	$testimonial_labels = array(
		'name' => __("Testimonials", 'free-education-helper'),
		'singular_name' => __("Testimonial", 'free-education-helper'),
		'menu_name' => 'Testimonial',
		'add_new' => __("Add New", 'free-education-helper'),
		'add_new_item' => __("Add New testimonial",'free-education-helper'),
		'edit_item' => __("Edit testimonial",'free-education-helper'),
		'new_item' => __("New testimonial",'free-education-helper'),
		'view_item' => __("View testimonial",'free-education-helper'),
		'search_items' => __("Search testimonials",'free-education-helper'),
		'not_found' =>  __("No Testimonial Found",'free-education-helper'),
		'not_found_in_trash' => __("No Testimonial Found in Trash",'free-education-helper'),
		'parent_item_colon' => '',
	);

	// Testimonials Arguments
	$testimonial_args = array(
		'labels' => $testimonial_labels,
		'public' => true,
		'has_archive' => true,
		'show_in_menu'		=> true,
		'menu_position' => 9,
		'exclude_from_search' => false,
		'menu_icon'	=> 'dashicons-format-quote',
		'rewrite' => false,
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array('testimonials_categories','post_tag'),
	);
	$free_education_theme = wp_get_theme();
	if( ($free_education_theme->get( 'TextDomain' ) != 'education-pro') ):

	// Event Labels
		$event_labels = array(
			'name' => __("Event", 'free-education-helper'),
			'singular_name' => __("Event", 'free-education-helper'),
			'menu_name' => 'Events',
			'add_new' => __("Add New", 'free-education-helper'),
			'add_new_item' => __("Add New Event",'free-education-helper'),
			'edit_item' => __("Edit Event",'free-education-helper'),
			'new_item' => __("New Event",'free-education-helper'),
			'view_item' => __("View Event",'free-education-helper'),
			'search_items' => __("Search Events",'free-education-helper'),
			'not_found' =>  __("No Event Found",'free-education-helper'),
			'not_found_in_trash' => __("No Event Found in Trash",'free-education-helper'),
			'parent_item_colon' => '',
		);

	// Event Arguments
		$event_args = array(
			'labels' => $event_labels,
			'public' => true,
			'has_archive' => true,
			'show_in_menu'		=> true,
			'menu_position' => 10,
			'exclude_from_search' => false,
			'menu_icon'	=> 'dashicons-media-text',
			'rewrite' => false,
			'supports' => array('title', 'editor', 'thumbnail'),
			'taxonomies' => array('event_categories','post_tag')
		);
		register_post_type( 'event' , $event_args );
	endif;
	// Register post type
	register_post_type( 'service' , $service_args );

	register_post_type( 'testimonial' , $testimonial_args );
}

add_action( 'init', 'custom_post_type');

function free_education_testimonials_taxonomy() {  
	register_taxonomy(  
		'testimonials_categories', 
        'testimonial',        //post type name
        array(  
        	'hierarchical' => true,  
        	'label' => 'Category',
        	'query_var' => true
        )  
    );  
}  

add_action( 'init', 'free_education_testimonials_taxonomy');


function free_education_service_taxonomy() {  
	register_taxonomy(  
		'service_categories', 
        'service',        //post type name
        array(  
        	'hierarchical' => true,  
        	'label' => 'Category',
        	'query_var' => true
        )  
    );  
}  

add_action( 'init', 'free_education_service_taxonomy');

$free_education_theme = wp_get_theme();
if( ($free_education_theme->get( 'TextDomain' ) != 'education-pro')):
	function free_education_event_taxonomy() {  
		register_taxonomy(  
			'event_categories', 
        'event',        //post type name
        array(  
        	'hierarchical' => true,  
        	'label' => 'Category',
        	'query_var' => true
        )  
    );  
	}  
	add_action( 'init', 'free_education_event_taxonomy');
endif;	
