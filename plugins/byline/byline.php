<?php
/*
Plugin Name: Byline
Description: Solves the multi-author problem by creating a custom taxonomy called "Byline" that allows posts to be tagged with multiple authors, then replaces the default WordPress author display. 
Version: 0.25
Author: Matt Dulin
Author URI: http://mattdulin.com
License: GPL2
*/


/**
 * Add custom taxonomy called "Byline" that functions like a tag (non-hierarchical) 
 * See: http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
function add_custom_taxonomies() {
	// Add new "bylines" taxonomy to Posts
	register_taxonomy('byline', 'post', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => false,
		'show_admin_column' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Bylines', 'taxonomy general name' ),
			'singular_name' => _x( 'Byline', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Bylines' ),
			'popular_items' => __('Popular Bylines'),
			'all_items' => __( 'All Bylines' ),
			'edit_item' => __( 'Edit Byline' ),
			'update_item' => __( 'Update Byline' ),
			'separate_items_with_commas' => __( 'Separate bylines with commas' ),
			'add_new_item' => __( 'Add New Byline' ),
			'add_or_remove_items' => __( 'Add or remove bylines' ),
			'choose_from_most_used' => __( 'Choose from most used bylines' ),			
			'new_item_name' => __( 'New Byline Name' ),
			'menu_name' => __( 'Bylines' )
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'byline', // This controls the base slug that will display before each term
			'with_front' => true, // Don't display the category base before "/bylines/"
			'hierarchical' => false // This will allow URL's like "/bylines/boston/cambridge/"
		),
	));
}
add_action( 'init', 'add_custom_taxonomies', 0 );


//Display the byline by replacing instances of the_author throughout most areas of the site

add_filter( 'the_author', 'byline' );
add_filter( 'get_the_author_display_name', 'byline' );

function byline( $name ) {
global $post;

$author = get_the_term_list( $post->ID, 'byline', '', ', ', '' );

//if ( $author && is_singular() || is_home() || is_page() || is_category() || is_tag() )  Use other if statements to control display if desired. 
if ( $author && !is_admin() && !is_feed() )
$name = $author;

return $name;

if ( $author && is_feed() )  //Preserves native Wordpress author for feeds
$name = get_the_author();
return $name;

}

?>
