<?php

/*
 * Creating custom cost type to  adding FAQs.
 */
function jltmaf_register_faq_post_type() {

	// Register FAQs Post Type
	$labels = array(
		'name'                => _x( 'FAQs', MAF_TD ),
		'singular_name'       => _x( 'FAQ', MAF_TD ),
		'menu_name'           => __( 'FAQs', MAF_TD ),
		'parent_item_colon'   => __( 'Parent FAQs:', MAF_TD ),
		'all_items'           => __( 'All FAQs', MAF_TD ),
		'view_item'           => __( 'View FAQ', MAF_TD ),
		'add_new_item'        => __( 'Add New FAQ', MAF_TD ),
		'add_new'             => __( 'New FAQ', MAF_TD ),
		'edit_item'           => __( 'Edit FAQ', MAF_TD ),
		'update_item'         => __( 'Update FAQ', MAF_TD ),
		'search_items'        => __( 'Search FAQs', MAF_TD ),
		'not_found'           => __( 'No FAQs found', MAF_TD ),
		'not_found_in_trash'  => __( 'No FAQs found in Trash', MAF_TD ),
		);
	$args = array(
		'label'               => __( 'FAQ', MAF_TD ),
		'description'         => __( 'Jewel Theme FAQ Post Type', MAF_TD ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor' ),
		'hierarchical'        => true,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon' 		  => 'dashicons-welcome-write-blog',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest' 	      => true

	);
	register_post_type( 'faq', $args );

	//Register Category Taxonomy FAQs
	register_taxonomy( 'faq_cat', 'faq', array(
		'labels'                     =>  __( 'Categories', MAF_TD ),
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest' 	      		 => true,
	) );

	// Register Tags Taxonomy FAQs
	register_taxonomy( 'faq_tags', 'faq', array(
		'labels'                     => __( 'Tags', MAF_TD ),
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest' 	      		 => true,
	) );
}

// Hook into the 'init' action
add_action( 'init', 'jltmaf_register_faq_post_type', 0 );
