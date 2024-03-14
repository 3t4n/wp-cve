<?php
function bookshelves_create_taxonomy() {
	register_taxonomy(
		'location',
		'bookshelves',
		array(
			'labels' 			=> array(
				'name'          => __( 'Location', 'library-bookshelves' ),
				'singular_name' => __( 'Location', 'library-bookshelves' ),
				'search_items'  => __( 'Search Locations', 'library-bookshelves' ),
				'all_items'     => __( 'All Locations', 'library-bookshelves' ),
				'edit_item'     => __( 'Edit Location', 'library-bookshelves' ),
				'update_item'   => __( 'Update Location', 'library-bookshelves' ),
				'add_new_item'  => __( 'Add New Location', 'library-bookshelves' ),
				'new_item_name' => __( 'New Location Name', 'library-bookshelves' ),
				'not_found'     => __( 'No locations found.', 'library-bookshelves' ),
				'menu_name'     => __( 'Locations', 'library-bookshelves' ),
				'view_item'     => __( 'View Locations', 'library-bookshelves' ),
			),
			'description'       => __( 'Used to assign Bookshelf posts to Bookshelf Widgets.', 'library-bookshelves' ),
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'rewrite'           => false,
			'query_var'         => true,
			'show_admin_column' => true,
		)
	);
}
add_action( 'init', 'bookshelves_create_taxonomy' );
