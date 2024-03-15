<?php

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;



/**
 * Add Team members custom post type
 */

add_action( 'init', 'wpb_otm_post_type', 0 );

if ( ! function_exists('wpb_otm_post_type') ) {
	function wpb_otm_post_type() {

		$labels = array(
			'name'                  => esc_html_x( 'Team Members', 'Post Type General Name', 'our-team-members' ),
			'singular_name'         => esc_html_x( 'Team Member', 'Post Type Singular Name', 'our-team-members' ),
			'menu_name'             => esc_html__( 'Team Members', 'our-team-members' ),
			'name_admin_bar'        => esc_html__( 'Team Members', 'our-team-members' ),
			'archives'              => esc_html__( 'Team Member Archives', 'our-team-members' ),
			'attributes'            => esc_html__( 'Team Member Attributes', 'our-team-members' ),
			'parent_item_colon'     => esc_html__( 'Parent Team Member:', 'our-team-members' ),
			'all_items'             => esc_html__( 'All Team Members', 'our-team-members' ),
			'add_new_item'          => esc_html__( 'Add New Team Member', 'our-team-members' ),
			'add_new'               => esc_html__( 'Add New Team Member', 'our-team-members' ),
			'new_item'              => esc_html__( 'New Team Member', 'our-team-members' ),
			'edit_item'             => esc_html__( 'Edit Team Member', 'our-team-members' ),
			'update_item'           => esc_html__( 'Update Team Member', 'our-team-members' ),
			'view_item'             => esc_html__( 'View Team Member', 'our-team-members' ),
			'view_items'            => esc_html__( 'View Team Members', 'our-team-members' ),
			'search_items'          => esc_html__( 'Search Team Member', 'our-team-members' ),
			'not_found'             => esc_html__( 'Not found', 'our-team-members' ),
			'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'our-team-members' ),
			'featured_image'        => esc_html__( 'Team Member Image', 'our-team-members' ),
			'set_featured_image'    => esc_html__( 'Set team member image', 'our-team-members' ),
			'remove_featured_image' => esc_html__( 'Remove team member image', 'our-team-members' ),
			'use_featured_image'    => esc_html__( 'Use as team member image', 'our-team-members' ),
			'insert_into_item'      => esc_html__( 'Insert into team member', 'our-team-members' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this team member', 'our-team-members' ),
			'items_list'            => esc_html__( 'Team members list', 'our-team-members' ),
			'items_list_navigation' => esc_html__( 'Team members list navigation', 'our-team-members' ),
			'filter_items_list'     => esc_html__( 'Filter team members list', 'our-team-members' ),
		);
		$rewrite = array(
			'slug'                  => apply_filters( 'wpb_team_member_slug', 'team_member' ),
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => esc_html__( 'Team Member', 'our-team-members' ),
			'description'           => esc_html__( 'Custom post type for Our Team Members plugin.', 'our-team-members' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'taxonomies'            => array( 'wpb_team_member_category' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 80,
			'menu_icon'             => 'dashicons-groups',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
		);

		register_post_type( 'wpb_team_members', $args );
	}
}



/**
 * Register Team Custom Taxonomy
 */

add_action( 'init', 'wpb_otm_custom_taxonomy', 0 );

if ( ! function_exists( 'wpb_otm_custom_taxonomy' ) ) {
	function wpb_otm_custom_taxonomy() {

		$labels = array(
			'name'                       => esc_html_x( 'Team Categories', 'Taxonomy General Name', 'our-team-members' ),
			'singular_name'              => esc_html_x( 'Team Category', 'Taxonomy Singular Name', 'our-team-members' ),
			'menu_name'                  => esc_html__( 'Team Category', 'our-team-members' ),
			'all_items'                  => esc_html__( 'All Team Categories', 'our-team-members' ),
			'parent_item'                => esc_html__( 'Parent Team Category', 'our-team-members' ),
			'parent_item_colon'          => esc_html__( 'Parent Team Category:', 'our-team-members' ),
			'new_item_name'              => esc_html__( 'New Team Category Name', 'our-team-members' ),
			'add_new_item'               => esc_html__( 'Add New Team Category', 'our-team-members' ),
			'edit_item'                  => esc_html__( 'Edit Team Category', 'our-team-members' ),
			'update_item'                => esc_html__( 'Update Team Category', 'our-team-members' ),
			'view_item'                  => esc_html__( 'View Team Category', 'our-team-members' ),
			'separate_items_with_commas' => esc_html__( 'Separate team Categories with commas', 'our-team-members' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove team categories', 'our-team-members' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used', 'our-team-members' ),
			'popular_items'              => esc_html__( 'Popular team category', 'our-team-members' ),
			'search_items'               => esc_html__( 'Search team categories', 'our-team-members' ),
			'not_found'                  => esc_html__( 'Not Found', 'our-team-members' ),
			'no_terms'                   => esc_html__( 'No team categories', 'our-team-members' ),
			'items_list'                 => esc_html__( 'Team category list', 'our-team-members' ),
			'items_list_navigation'      => esc_html__( 'Team categories list navigation', 'our-team-members' ),
		);
		$rewrite = array(
			'slug'                       => 'team_category',
			'with_front'                 => true,
			'hierarchical'               => false,
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'wpb_team_member_category', array( 'wpb_team_members' ), $args );

	}
}


if ( ! function_exists('wpb_otm_shortcode_generator') ) {

// Register Team Member Shortcode Generator Post Type
function wpb_otm_shortcode_generator() {

	$labels = array(
		'name'                  => esc_html_x( 'Team Member Shortcode', 'Post Type General Name', 'our-team-members' ),
		'singular_name'         => esc_html_x( 'Team Member Shortcode', 'Post Type Singular Name', 'our-team-members' ),
		'menu_name'             => esc_html__( 'Team Member Shortcode', 'our-team-members' ),
		'name_admin_bar'        => esc_html__( 'Post Type', 'our-team-members' ),
		'archives'              => esc_html__( 'Item Archives', 'our-team-members' ),
		'attributes'            => esc_html__( 'Item Attributes', 'our-team-members' ),
		'parent_item_colon'     => esc_html__( 'Parent Item:', 'our-team-members' ),
		'all_items'             => esc_html__( 'Shortcode Generator', 'our-team-members' ),
		'add_new_item'          => esc_html__( 'Add New Shortcode', 'our-team-members' ),
		'add_new'               => esc_html__( 'Add New', 'our-team-members' ),
		'new_item'              => esc_html__( 'New Shortcode', 'our-team-members' ),
		'edit_item'             => esc_html__( 'Edit Shortcode', 'our-team-members' ),
		'update_item'           => esc_html__( 'Update Shortcode', 'our-team-members' ),
		'view_item'             => esc_html__( 'View Shortcode', 'our-team-members' ),
		'view_items'            => esc_html__( 'View Shortcode', 'our-team-members' ),
		'search_items'          => esc_html__( 'Search Item', 'our-team-members' ),
		'not_found'             => esc_html__( 'Not found', 'our-team-members' ),
		'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'our-team-members' ),
		'featured_image'        => esc_html__( 'Featured Image', 'our-team-members' ),
		'set_featured_image'    => esc_html__( 'Set featured image', 'our-team-members' ),
		'remove_featured_image' => esc_html__( 'Remove featured image', 'our-team-members' ),
		'use_featured_image'    => esc_html__( 'Use as featured image', 'our-team-members' ),
		'insert_into_item'      => esc_html__( 'Insert into item', 'our-team-members' ),
		'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'our-team-members' ),
		'items_list'            => esc_html__( 'Items list', 'our-team-members' ),
		'items_list_navigation' => esc_html__( 'Items list navigation', 'our-team-members' ),
		'filter_items_list'     => esc_html__( 'Filter items list', 'our-team-members' ),
	);
	$args = array(
		'label'               => esc_html__( 'Team Member Shortcode', 'our-team-members' ),
		'description'         => esc_html__( 'Post Type For Team Member Shortcode Generator ', 'our-team-members' ),
		'labels'              => $labels,
		'supports'            => array( 'title', ),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'edit.php?post_type=wpb_team_members',
		'menu_position'       => 5,
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,		
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'wpb_otm_shortcode', $args );

}
add_action( 'init', 'wpb_otm_shortcode_generator', 0 );

}



/**
 * Shortcode post update message
 */

if ( ! function_exists('wpb_otm_shortcode_updated_messages') ) {
	function wpb_otm_shortcode_updated_messages ( $msg ) {

	    $msg[ 'wpb_otm_shortcode' ] = array (
			0 	=> '', // Unused. Messages start at index 1.
			1 	=> esc_html__( 'Shortcode updated.', 'our-team-members' ),
			2 	=> esc_html__( 'Custom field updated.', 'our-team-members' ),
			3 	=> esc_html__( 'Custom field deleted.', 'our-team-members' ),
			4 	=> esc_html__( 'Shortcode updated.', 'our-team-members' ),
			/* translators: %s: date and time of the revision */
			5 	=> isset( $_GET['revision']) ? sprintf( esc_html__( 'Post restored to revision from %s.', 'our-team-members' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 	=> esc_html__( 'Shortcode published.', 'our-team-members' ),
			7 	=> esc_html__( 'Shortcode saved.', 'our-team-members' ),
			8 	=> esc_html__( 'Shortcode submitted.', 'our-team-members' ),
			10 	=> esc_html__( 'Shortcode draft updated.', 'our-team-members' ),
	    );

	    return $msg;

	}
}
add_filter( 'post_updated_messages', 'wpb_otm_shortcode_updated_messages', 10, 1 );




/**
 * Select shortocde
 */

add_action('admin_footer', 'wpb_otm_shortcode_focus_select');

if ( ! function_exists('wpb_otm_shortcode_focus_select') ) {
	function wpb_otm_shortcode_focus_select() {
		$screen = get_current_screen();

		if( $screen->id == 'wpb_otm_shortcode' || $screen->id == 'edit-wpb_otm_shortcode' ){
			?>
				<script>
					jQuery("input.wpb-otm-the-shortcode").focus(function() { $(this).select(); } );
				</script>
			<?php
		}
	}
}


/**
 * Tax Column term ID
 */

add_filter('manage_edit-wpb_team_member_category_columns' , 'wpb_otm_taxonomy_columns');
add_filter( 'manage_wpb_team_member_category_custom_column', 'wpb_otm_taxonomy_columns_content', 10, 3 );

function wpb_otm_taxonomy_columns( $columns ){
	$columns['wpb_otm_term_id'] = esc_html__( 'Term ID', 'our-team-members' );
	return $columns;
}


function wpb_otm_taxonomy_columns_content( $content, $column_name, $term_id ){
    if ( 'wpb_otm_term_id' == $column_name ) {
        $content = $term_id;
    }
	return $content;
}
