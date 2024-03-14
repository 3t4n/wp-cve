<?php
function directorypress_register_post_type() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$args = array(
			'labels' => array(
				'name' => __('Listings', 'DIRECTORYPRESS'),
				'singular_name' => __('Listing', 'DIRECTORYPRESS'),
				'add_new' => __('Create new listing', 'DIRECTORYPRESS'),
				'add_new_item' => __('Create new listing', 'DIRECTORYPRESS'),
				'edit_item' => __('Edit listing', 'DIRECTORYPRESS'),
				'new_item' => __('New listing', 'DIRECTORYPRESS'),
				'view_item' => __('View listing', 'DIRECTORYPRESS'),
				'search_items' => __('Search listings', 'DIRECTORYPRESS'),
				'not_found' =>  __('No listings found', 'DIRECTORYPRESS'),
				'not_found_in_trash' => __('No listings found in trash', 'DIRECTORYPRESS')
			),
			'has_archive' => true,
			'description' => __('Listings', 'DIRECTORYPRESS'),
			'public' => true,
			'exclude_from_search' => false,
			'supports' => array('title', 'author', 'comments'),
			'menu_icon' => DIRECTORYPRESS_RESOURCES_URL . 'images/menuicon.png',
		);
		if (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_description']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_description']){
			$args['supports'][] = 'editor';
		}
		if (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_summary']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_summary']){
			$args['supports'][] = 'excerpt';
		}
		register_post_type(DIRECTORYPRESS_POST_TYPE, $args);
		
		register_taxonomy(DIRECTORYPRESS_CATEGORIES_TAX, DIRECTORYPRESS_POST_TYPE, array(
				'hierarchical' => true,
				'has_archive' => true,
				'labels' => array(
					'name' =>  __('Listing categories', 'DIRECTORYPRESS'),
					'menu_name' =>  __('Listing categories', 'DIRECTORYPRESS'),
					'singular_name' => __('Category', 'DIRECTORYPRESS'),
					'add_new_item' => __('Create category', 'DIRECTORYPRESS'),
					'new_item_name' => __('New category', 'DIRECTORYPRESS'),
					'edit_item' => __('Edit category', 'DIRECTORYPRESS'),
					'view_item' => __('View category', 'DIRECTORYPRESS'),
					'update_item' => __('Update category', 'DIRECTORYPRESS'),
					'search_items' => __('Search categories', 'DIRECTORYPRESS'),
				),
			)
		);
		register_taxonomy(DIRECTORYPRESS_LOCATIONS_TAX, DIRECTORYPRESS_POST_TYPE, array(
				'hierarchical' => true,
				'has_archive' => true,
				'labels' => array(
					'name' =>  __('Listing locations', 'DIRECTORYPRESS'),
					'menu_name' =>  __('Listing locations', 'DIRECTORYPRESS'),
					'singular_name' => __('Location', 'DIRECTORYPRESS'),
					'add_new_item' => __('Create location', 'DIRECTORYPRESS'),
					'new_item_name' => __('New location', 'DIRECTORYPRESS'),
					'edit_item' => __('Edit location', 'DIRECTORYPRESS'),
					'view_item' => __('View location', 'DIRECTORYPRESS'),
					'update_item' => __('Update location', 'DIRECTORYPRESS'),
					'search_items' => __('Search locations', 'DIRECTORYPRESS'),
					
				),
			)
		);
		register_taxonomy(DIRECTORYPRESS_TAGS_TAX, DIRECTORYPRESS_POST_TYPE, array(
				'hierarchical' => false,
				'labels' => array(
					'name' =>  __('Listing tags', 'DIRECTORYPRESS'),
					'menu_name' =>  __('Listing tags', 'DIRECTORYPRESS'),
					'singular_name' => __('Tag', 'DIRECTORYPRESS'),
					'add_new_item' => __('Create tag', 'DIRECTORYPRESS'),
					'new_item_name' => __('New tag', 'DIRECTORYPRESS'),
					'edit_item' => __('Edit tag', 'DIRECTORYPRESS'),
					'view_item' => __('View tag', 'DIRECTORYPRESS'),
					'update_item' => __('Update tag', 'DIRECTORYPRESS'),
					'search_items' => __('Search tags', 'DIRECTORYPRESS'),
				),
			)
		);
}


