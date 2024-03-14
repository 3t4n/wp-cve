<?php


/*
 * Register post type and taxonomies.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_post_types() {
	$labels = array(
		'name'                  => esc_html__('Chessgames', 'chessgame-shizzle'),
		'singular_name'         => esc_html__('Chessgame', 'chessgame-shizzle'),
		'add_new'               => esc_html__('New Chessgame', 'chessgame-shizzle'),
		'add_new_item'          => esc_html__('New Chessgame', 'chessgame-shizzle'),
		'edit_item'             => esc_html__('Edit Chessgame', 'chessgame-shizzle'),
		'new_item'              => esc_html__('New Chessgame', 'chessgame-shizzle'),
		'view_item'             => esc_html__('View Chessgame', 'chessgame-shizzle'),
		'view_items'            => esc_html__('View Chessgames', 'chessgame-shizzle'),
		'search_items'          => esc_html__('Search Chessgames', 'chessgame-shizzle'),
		'not_found'             => esc_html__('No Chessgame found', 'chessgame-shizzle'),
		'not_found_in_trash'    => esc_html__('No Chessgame found in the Thrash', 'chessgame-shizzle'),
		'parent_item_colon'     => '',
		'all_items'             => esc_html__('All Chessgames', 'chessgame-shizzle'),
		'archives'              => esc_html__('Chessgame Archives', 'chessgame-shizzle'),
		'insert_into_item'      => esc_html__('Insert into post', 'chessgame-shizzle'),
		'uploaded_to_this_item' => esc_html__('Uploaded to this post', 'chessgame-shizzle'),
		'featured_image'        => esc_html__('Featured Image', 'chessgame-shizzle'),
		'set_featured_image'    => esc_html__('Set Featured Image', 'chessgame-shizzle'),
		'remove_featured_image' => esc_html__('Remove Featured Image', 'chessgame-shizzle'),
		'use_featured_image'    => esc_html__('Use as Featured Image', 'chessgame-shizzle'),
		'menu_name'             => esc_html__('Chessgames', 'chessgame-shizzle'),
	);
	register_post_type( 'cs_chessgame', array(
		'public'              => true,
		'show_in_menu'        => true,
		'show_ui'             => true,
		'labels'              => $labels,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields', 'comments', 'revisions' ),
		'capability_type'     => 'post',
		'taxonomies'          => array( 'cs_category', 'cs_tag' ),
		'exclude_from_search' => false,
		'rewrite'             => array(
			'slug' => 'chessgame',
			'with_front' => true,
			),
		'has_archive'         => true,
		'menu_icon'           => 'dashicons-screenoptions',
		)
	);

	$labels = array(
		'name'                          => esc_html__('Categories', 'chessgame-shizzle'),
		'singular_name'                 => esc_html__('Category', 'chessgame-shizzle'),
		'search_items'                  => esc_html__('Search Categories', 'chessgame-shizzle'),
		'popular_items'                 => esc_html__('Popular Categories', 'chessgame-shizzle'),
		'all_items'                     => esc_html__('All Categories', 'chessgame-shizzle'),
		'parent_item'                   => esc_html__('Parent Category', 'chessgame-shizzle'),
		'edit_item'                     => esc_html__('Edit Category', 'chessgame-shizzle'),
		'update_item'                   => esc_html__('Update Category', 'chessgame-shizzle'),
		'add_new_item'                  => esc_html__('Add New Category', 'chessgame-shizzle'),
		'new_item_name'                 => esc_html__('New Category', 'chessgame-shizzle'),
		'separate_items_with_commas'    => esc_html__('Separate Categories with commas', 'chessgame-shizzle'),
		'add_or_remove_items'           => esc_html__('Add or remove Categories', 'chessgame-shizzle'),
		'choose_from_most_used'         => esc_html__('Choose from most used Categories', 'chessgame-shizzle'),
		'not_found'                     => esc_html__('No Categories found', 'chessgame-shizzle'),
	);
	$args = array(
		'label'                         => esc_html__('Categories', 'chessgame-shizzle'),
		'labels'                        => $labels,
		'public'                        => true,
		'hierarchical'                  => true,
		'show_ui'                       => true,
		'show_in_nav_menus'             => true,
		'args'                          => array( 'orderby' => 'term_order' ),
		'rewrite'                       => array(
			'slug' => 'cs_category',
			'with_front' => true,
			),
		'query_var'                     => true,
	);
	register_taxonomy( 'cs_category', 'cs_chessgame', $args );

	$labels = array(
		'name'                          => esc_html__('Tags', 'chessgame-shizzle'),
		'singular_name'                 => esc_html__('Tag', 'chessgame-shizzle'),
		'search_items'                  => esc_html__('Search Tags', 'chessgame-shizzle'),
		'popular_items'                 => esc_html__('Popular Tags', 'chessgame-shizzle'),
		'all_items'                     => esc_html__('All Tags', 'chessgame-shizzle'),
		'parent_item'                   => esc_html__('Parent Tag', 'chessgame-shizzle'),
		'edit_item'                     => esc_html__('Edit Tag', 'chessgame-shizzle'),
		'update_item'                   => esc_html__('Update Tag', 'chessgame-shizzle'),
		'add_new_item'                  => esc_html__('Add New Tag', 'chessgame-shizzle'),
		'new_item_name'                 => esc_html__('New Tag', 'chessgame-shizzle'),
		'separate_items_with_commas'    => esc_html__('Separate Tags with commas', 'chessgame-shizzle'),
		'add_or_remove_items'           => esc_html__('Add or remove Tags', 'chessgame-shizzle'),
		'choose_from_most_used'         => esc_html__('Choose from most used Tags', 'chessgame-shizzle'),
		'not_found'                     => esc_html__('No Tags found', 'chessgame-shizzle'),
	);
	$args = array(
		'label'                         => esc_html__('Tags', 'chessgame-shizzle'),
		'labels'                        => $labels,
		'public'                        => true,
		'hierarchical'                  => false,
		'show_ui'                       => true,
		'show_in_nav_menus'             => true,
		'args'                          => array( 'orderby' => 'term_order' ),
		'rewrite'                       => array(
			'slug' => 'cs_tag',
			'with_front' => true,
			),
		'query_var'                     => true,
	);
	register_taxonomy( 'cs_tag', 'cs_chessgame', $args );
}
add_action( 'init', 'chessgame_shizzle_post_types', 10 );


/*
 * Set default content for new post for chessgame.
 * This case, when no content was filled it, it still gets saved (instead of losing all PGN data).
 *
 * @since 1.2.8
 */
function chessgame_shizzle_editor_content( $content, $post ) {

	if ( $post->post_type === 'cs_chessgame' && empty( $content ) ) {
		$content = esc_html__( 'Please edit this content (it cannot be empty).', 'chessgame-shizzle' );
	}

	return $content;

}
add_filter( 'default_content', 'chessgame_shizzle_editor_content', 10, 2 );
