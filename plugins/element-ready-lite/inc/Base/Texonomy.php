<?php

/*-----------------------------------------------------------------------------------*/
/*	Creating Custom Taxonomy 
/*-----------------------------------------------------------------------------------*/

if (class_exists('Give')) {
	// create two taxonomies, genres and writers for the post type "book"
	function element_ready_give_campaign_taxonomies() {
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( 'Categories', 'taxonomy general name', 'element-ready-lite' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'element-ready-lite' ),
			'search_items'      => esc_html__( 'Search Categories', 'element-ready-lite' ),
			'all_items'         => esc_html__( 'Categories', 'element-ready-lite' ),
			'parent_item'       => esc_html__( 'Parent Category', 'element-ready-lite' ),
			'parent_item_colon' => esc_html__( 'Parent Category:', 'element-ready-lite' ),
			'edit_item'         => esc_html__( 'Edit Category', 'element-ready-lite' ),
			'update_item'       => esc_html__( 'Update Category', 'element-ready-lite' ),
			'add_new_item'      => esc_html__( 'Add New Category', 'element-ready-lite' ),
			'new_item_name'     => esc_html__( 'New Category', 'element-ready-lite' ),
			'menu_name'         => esc_html__( 'Categories', 'element-ready-lite' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'campaigncategory' ),
		);

		register_taxonomy( 'campaigncats', array( 'give_forms' ), $args );
	}
	add_action( 'init', 'element_ready_give_campaign_taxonomies', 0 );

	function element_ready_plugin_templates( $template ) {
		if (  is_single() && get_post_type() == 'give_forms' ) {
			$template = ELEMENT_READY_DIR_PATH . '/templates/give/single-give-forms.php';
	    }
	    return $template;
	}
	add_filter('template_include', 'element_ready_plugin_templates');
}

if (  class_exists('Give') ) {
	function elment_ready_give_style(){
		wp_enqueue_style( 'element-ready-grid' );
	}
	add_action( 'wp_enqueue_scripts', 'elment_ready_give_style' );
}