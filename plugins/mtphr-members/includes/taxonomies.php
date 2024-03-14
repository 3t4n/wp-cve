<?php
/**
 * Add custom taxonomies
 *
 * @package Metaphor Members
 */
 



add_action( 'init', 'mtphr_members_categories' );
/**
 * Create a category taxonomy
 *
 * @since 1.0.0
 */
function mtphr_members_categories() {

	// Set the slug
	$settings = mtphr_members_settings();
	$slug = $settings['slug'].'-catagory';
	$singular = $settings['singular_label'];
  	
	// Create labels
	$labels = array(
		'name' => sprintf(__('%s Categories', 'mtphr-members'), $singular),
		'singular_name' => __('Category', 'mtphr-members'),
		'search_items' =>  __('Search Categories', 'mtphr-members'),
		'all_items' => __('All Categories', 'mtphr-members'),
		'parent_item' => __('Parent', 'mtphr-members'),
		'parent_item_colon' => __('Parent:', 'mtphr-members'),
		'edit_item' => __('Edit Category', 'mtphr-members'), 
		'update_item' => __('Update Category', 'mtphr-members'),
		'add_new_item' => __('Add New Category', 'mtphr-members'),
		'new_item_name' => __('New Category', 'mtphr-members'),
		'menu_name' => __('Categories', 'mtphr-members'),
	); 	 	
	
	// Create the arguments
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $slug )
	); 
	
	// Register the taxonomy
	register_taxonomy( 'mtphr_member_category', array( 'mtphr_member' ), $args );
}



