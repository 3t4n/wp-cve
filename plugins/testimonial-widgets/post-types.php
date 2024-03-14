<?php
function wpttst_register_cpt() {
if ( ! post_type_exists( 'wpt-testimonial' ) ) {
$args = wpttst_get_cpt_defaults();
$args['labels'] = apply_filters( 'wpttst_testimonial_labels', $args['labels'] );
$args['supports'] = apply_filters( 'wpttst_testimonial_supports', $args['supports'] );
$args['exclude_from_search'] = apply_filters( 'wpttst_exclude_from_search', $args['exclude_from_search'] );
$args['taxonomies'] = apply_filters( 'wpttst_testimonial_taxonomies', $args['taxonomies'] );
register_post_type( 'wpt-testimonial', apply_filters( 'wpttst_post_type', $args ) );
}
if ( ! taxonomy_exists( 'wpt-testimonial-category' ) && apply_filters( 'wpttst_register_taxonomy', true ) ) {
$args = wpttst_get_tax_defaults();
$args['labels'] = apply_filters( 'wpttst_taxonomy_labels', $args['labels'] );
register_taxonomy( 'wpt-testimonial-category', array( 'wpt-testimonial' ), apply_filters( 'wpttst_taxonomy', $args ) );
}
}
add_action( 'init', 'wpttst_register_cpt', 12 );
function wpttst_get_cpt_defaults() {
$labels = array(
'name' => esc_html_x( 'My Testimonials', 'post type general name', 'testimonial-widgets' ),
'singular_name' => esc_html_x( 'Testimonial', 'post type singular name', 'testimonial-widgets' ),
'add_new' => esc_html__( 'Add New', 'testimonial-widgets' ),
'add_new_item' => esc_html__( 'Add New Testimonial', 'testimonial-widgets' ),
'edit_item' => esc_html__( 'Edit Testimonial', 'testimonial-widgets' ),
'new_item' => esc_html__( 'New Testimonial', 'testimonial-widgets' ),
'view_item' => esc_html__( 'View Testimonial', 'testimonial-widgets' ),
'view_items' => esc_html__( 'View Testimonials', 'testimonial-widgets' ),
'search_items' => esc_html__( 'Search Testimonials', 'testimonial-widgets' ),
'not_found' => esc_html__( 'Nothing Found', 'testimonial-widgets' ),
'not_found_in_trash' => esc_html__( 'Nothing found in Trash', 'testimonial-widgets' ),
'all_items' => esc_html__( 'All Testimonials', 'testimonial-widgets' ),
'archives' => esc_html__( 'Testimonial Archives', 'testimonial-widgets' ),
'attributes' => esc_html__( 'Attributes', 'testimonial-widgets' ),
'insert_into_item' => esc_html__( 'Insert into testimonial', 'testimonial-widgets' ),
'uploaded_to_this_item' => esc_html__( 'Uploaded to this testimonial', 'testimonial-widgets' ),
'featured_image' => esc_html__( 'Reviewer\'s photo', 'testimonial-widgets' ),
'set_featured_image' => esc_html__( 'Set reviewer\'s photo', 'testimonial-widgets' ),
'remove_featured_image' => esc_html__( 'Remove reviewer\'s photo', 'testimonial-widgets' ),
'use_featured_image' => esc_html__( 'Use as reviewer\'s photo', 'testimonial-widgets' ),
'filter_items_list' => esc_html__( 'Filter testimonials list', 'testimonial-widgets' ),
'items_list_navigation' => esc_html__( 'Testimonials list navigation', 'testimonial-widgets' ),
'items_list' => esc_html__( 'Testimonials list', 'testimonial-widgets' ),
'menu_name' => 'Trustindex testimonials',
'name_admin_bar' => esc_html_x( 'Testimonial', 'admin bar menu name', 'testimonial-widgets' ),
);
$supports = array(
'title',
'editor',
'thumbnail',
'page-attributes'
);
$args = array(
'labels' => $labels,
'public' => true,
'hierarchical' => false,
'exclude_from_search' => false,
'show_ui' => true,
'show_in_menu' => false,
'show_in_nav_menus' => true,
'show_in_admin_bar' => true,
'capability_type' => 'post',
'supports' => $supports,
'taxonomies' => array( 'wpt-testimonial-category' ),
'has_archive' => false,
'rewrite' => array(
'slug' => esc_html_x( 'wpt-testimonial', 'slug', 'testimonial-widgets' ),
'with_front' => true,
'feeds' => false,
'pages' => true,
),
'can_export' => true
);
return $args;
}
function wpttst_get_tax_defaults() {
$labels = array(
'name' => esc_html__( 'Testimonial Categories', 'testimonial-widgets' ),
'singular_name' => esc_html__( 'Testimonial Category', 'testimonial-widgets' ),
'menu_name' => esc_html__( 'Categories', 'testimonial-widgets' ),
'all_items' => esc_html__( 'All categories', 'testimonial-widgets' ),
);
$args = array(
'labels' => $labels,
'hierarchical' => true,
'rewrite' => array( 'slug' => esc_html_x( 'wpt-testimonial-category', 'slug', 'testimonial-widgets' ) ),
'show_in_menu' => true,
);
return $args;
}
function wpttst_testimonial_supports( $supports ) {
$options = get_option( 'wpttst_options' );
if ( isset( $options['support_custom_fields'] ) && $options['support_custom_fields'] )
$supports[] = 'custom-fields';
return $supports;
}
add_filter( 'wpttst_testimonial_supports', 'wpttst_testimonial_supports' );
function wpttst_updated_messages( $messages ) {
global $post;
$revision = isset($_GET['revision']) ? sanitize_text_field($_GET['revision']) : null;
$preview_url = get_preview_post_link( $post );
$add_new_testimonial = 'post-new.php?post_type=wpt-testimonial';
$add_new_widget = 'admin.php?page=testimonial-widgets/tabs/create-widget.php';
$preview_post_link_html = sprintf( ' Add <a href="%1$s">%2$s</a> or <a href="%3$s">%4$s</a>.',
esc_url( $add_new_testimonial ),
esc_html__( 'new testimonial', 'testimonial-widgets' ),
esc_url( $add_new_widget ),
esc_html__( 'create testimonial widget', 'testimonial-widgets' )
);
$view_post_link_html = sprintf( ' Add <a href="%1$s">%2$s</a> or <a href="%3$s">%4$s</a>.',
esc_url( $add_new_testimonial ),
esc_html__( 'new testimonial', 'testimonial-widgets' ),
esc_url( $add_new_widget ),
esc_html__( 'create testimonial widget', 'testimonial-widgets' )
);
$scheduled_post_link_html = sprintf( ' Add <a href="%1$s">%2$s</a> or <a href="%3$s">%4$s</a>.',
esc_url( $add_new_testimonial ),
esc_html__( 'new testimonial', 'testimonial-widgets' ),
esc_url( $add_new_widget ),
esc_html__( 'create testimonial widget', 'testimonial-widgets' )
);

$scheduled_date = date_i18n( 'M j, Y @ H:i', strtotime( $post->post_date ) );
$messages['wpt-testimonial'] = array(
0 => '',
1 => esc_html__( 'Testimonial updated.', 'testimonial-widgets' ) . $view_post_link_html,
2 => esc_html__( 'Custom field updated.', 'testimonial-widgets' ),
3 => esc_html__( 'Custom field deleted.', 'testimonial-widgets' ),
4 => esc_html__( 'Testimonial updated.', 'testimonial-widgets' ),

5 => $revision ? sprintf( esc_html__( 'Testimonial restored to revision from %s.', 'testimonial-widgets' ), wp_post_revision_title( absint( $revision ), false ) ) : false,
6 => esc_html__( 'Testimonial saved.', 'testimonial-widgets' ) . $view_post_link_html,
7 => esc_html__( 'Testimonial saved.', 'testimonial-widgets' ),
8 => esc_html__( 'Testimonial submitted.', 'testimonial-widgets' ) . $preview_post_link_html,
9 => sprintf( esc_html__( 'Testimonial scheduled for: %s.', 'testimonial-widgets' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_post_link_html,
10 => esc_html__( 'Testimonial draft updated.', 'testimonial-widgets' ) . $preview_post_link_html,
);
return $messages;
}
add_filter( 'post_updated_messages', 'wpttst_updated_messages' );
function wpttst_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
$bulk_messages['wpt-testimonial'] = array(
'updated' => _n( '%s testimonial updated.', '%s testimonials updated.', $bulk_counts['updated'], 'testimonial-widgets' ),
'locked' => ( 1 == $bulk_counts['locked'] ) ? esc_html__( '1 testimonial not updated, somebody is editing it.', 'testimonial-widgets' ) : _n( '%s testimonial not updated, somebody is editing it.', '%s testimonials not updated, somebody is editing them.', $bulk_counts['locked'], 'testimonial-widgets' ),
'deleted' => _n( '%s testimonial permanently deleted.', '%s testimonials permanently deleted.', $bulk_counts['deleted'], 'testimonial-widgets' ),
'trashed' => _n( '%s testimonial moved to the Trash.', '%s testimonials moved to the Trash.', $bulk_counts['trashed'], 'testimonial-widgets' ),
'untrashed' => _n( '%s testimonial restored from the Trash.', '%s testimonials restored from the Trash.', $bulk_counts['untrashed'], 'testimonial-widgets' ),
);
return $bulk_messages;
}
add_filter( 'bulk_post_updated_messages', 'wpttst_bulk_updated_messages', 10, 2 );
