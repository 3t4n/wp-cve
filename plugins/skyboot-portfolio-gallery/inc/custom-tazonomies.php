<?php
if( !defined('ABSPATH') ) exit;
/*---------------------------
*    Register Custom Taxonomy
* ---------------------------*/

// Register Portfollio Gallery Custom Taxonomy
if(!function_exists('skyboot_portfolio_post_type_taxonomy')){
	function skyboot_portfolio_post_type_taxonomy() {

		// Portfolio Gallery taxonomy z
		$labels = array(
			'name'                       => _x( 'Category', 'Taxonomy General Name', 'skyboot-pg' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'skyboot-pg' ),
			'menu_name'                  => __( 'Category', 'skyboot-pg' ),
			'all_items'                  => __( 'All Category', 'skyboot-pg' ),
			'parent_item'                => __( 'Parent Category', 'skyboot-pg' ),
			'parent_item_colon'          => __( 'Parent Category:', 'skyboot-pg' ),
			'new_item_name'              => __( 'New Category Name', 'skyboot-pg' ),
			'add_new_item'               => __( 'Add New Category', 'skyboot-pg' ),
			'edit_item'                  => __( 'Edit Category', 'skyboot-pg' ),
			'update_item'                => __( 'Update Category', 'skyboot-pg' ),
			'view_item'                  => __( 'View Category', 'skyboot-pg' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'skyboot-pg' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'skyboot-pg' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'skyboot-pg' ),
			'popular_items'              => __( 'Popular Category', 'skyboot-pg' ),
			'search_items'               => __( 'Search Category', 'skyboot-pg' ),
			'not_found'                  => __( 'Not Found', 'skyboot-pg' ),
			'no_terms'                   => __( 'No items', 'skyboot-pg' ),
			'items_list'                 => __( 'Category list', 'skyboot-pg' ),
			'items_list_navigation'      => __( 'Category list navigation', 'skyboot-pg' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_rest'      		 => true,	
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		register_taxonomy( 'skyboot_portfolio_cat', array( 'skyboot_portfolio' ), $args );


	}
	add_action( 'init', 'skyboot_portfolio_post_type_taxonomy', 0 );
}

