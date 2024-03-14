<?php

	// Register Custom Taxonomy "lesson-difficulty"
	add_action( 'init', 'wpc_lesson_difficulty_args', 0 );
	// Customize taxonomy
	function wpc_lesson_difficulty_args() {
	  $labels = array(
	    'name'              => _x( 'Difficulty', 'taxonomy general name' ),
	    'singular_name'     => _x( 'Difficulty', 'taxonomy name' ),
	    'search_items'      => __( 'Search Difficulty' ),
	    'all_items'         => __( 'All Difficulty' ),
	    'parent_item'       => __( 'Parent Term Category' ),
	    'parent_item_colon' => __( 'Parent Term Category:' ),
	    'edit_item'         => __( 'Edit Difficulty' ), 
	    'update_item'       => __( 'Update Difficulty' ),
	    'add_new_item'      => __( 'Add New Difficulty' ),
	    'new_item_name'     => __( 'New Difficulty' ),
	    'menu_name'         => __( 'Difficulty' ),
	  );
	  $args = array(
	    'labels' => $labels,
	    'hierarchical' => false,
	    'show_admin_column' => true,
	    'query_var' => true
	  );
	  register_taxonomy( 'course-difficulty', 'course', $args );
	}
	// Register Custom Taxonomy "category"
	add_action( 'init', 'wpc_course_category_args', 0 );
	// Customize taxonomy
	function wpc_course_category_args() {
	  $labels = array(
	    'name'              => _x( 'Category', 'taxonomy general name' ),
	    'singular_name'     => _x( 'Category', 'taxonomy name' ),
	    'search_items'      => __( 'Search Category' ),
	    'all_items'         => __( 'All Categories' ),
	    'parent_item'       => __( 'Parent Term Category' ),
	    'parent_item_colon' => __( 'Parent Term Category:' ),
	    'edit_item'         => __( 'Edit Category' ), 
	    'update_item'       => __( 'Update Category' ),
	    'add_new_item'      => __( 'Add New Category' ),
	    'new_item_name'     => __( 'New Category' ),
	    'menu_name'         => __( 'Category' ),
	  );
	  $args = array(
	    'labels' => $labels,
	    'hierarchical' => false,
	    'show_admin_column' => true,
	    'query_var' => true
	  );
	  register_taxonomy( 'course-category', 'course', $args );
	}

?>