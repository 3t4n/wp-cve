<?php
function flipbox_builder_flipbox_builder_init() {
    $labels = array(
        'name'                  => _x( 'Flipbox Builder', 'Post type general name', 'flipbox-builder-text-domain' ),
        'singular_name'         => _x( 'Flipbox Builder', 'Post type singular name', 'flipbox-builder-text-domain' ),
        'menu_name'             => _x( 'Flipbox Builder', 'Admin Menu text', 'flipbox-builder-text-domain' ),
        'name_admin_bar'        => _x( 'Flipbox Builder', 'Add New on Toolbar', 'flipbox-builder-text-domain' ),
        'add_new'               => __( 'Add New Flipbox', 'flipbox-builder-text-domain' ),
        'add_new_item'          => __( 'Create New Flipbox', 'flipbox-builder-text-domain' ),
        'new_item'              => __( 'New Flipbox', 'flipbox-builder-text-domain' ),
        'edit_item'             => __( 'Edit Flipbox', 'flipbox-builder-text-domain' ),
        'view_item'             => __( 'View Flipbox', 'flipbox-builder-text-domain' ),
        'all_items'             => __( 'All Flipbox', 'flipbox-builder-text-domain' ),
        'search_items'          => __( 'Search Flipbox Builder', 'flipbox-builder-text-domain' ),
        'parent_item_colon'     => __( 'Parent Flipbox Builder:', 'flipbox-builder-text-domain' ),
        'not_found'             => __( 'No Flipbox Builder found.', 'flipbox-builder-text-domain' ),
        'not_found_in_trash'    => __( 'No Flipbox Builder found in Trash.', 'flipbox-builder-text-domain' ),
        'featured_image'        => _x( 'Flipbox Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'flipbox-builder-text-domain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'flipbox-builder-text-domain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'flipbox-builder-text-domain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'flipbox-builder-text-domain' ),
        'archives'              => _x( 'Flipbox archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'flipbox-builder-text-domain' ),
        'insert_into_item'      => _x( 'Insert into Flipbox', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'flipbox-builder-text-domain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this Flipbox', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'flipbox-builder-text-domain' ),
        'filter_items_list'     => _x( 'Filter Flipbox Builder list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'flipbox-builder-text-domain' ),
        'items_list_navigation' => _x( 'Flipbox Builder list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'flipbox-builder-text-domain' ),
        'items_list'            => _x( 'Flipbox Builder list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'flipbox-builder-text-domain' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'fb' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title'),
		'menu_icon'=>'dashicons-image-flip-vertical',
    );
 
    register_post_type( 'fb', $args );
} 
add_action( 'init', 'flipbox_builder_flipbox_builder_init' );
?>
<?php 
add_filter( 'manage_edit-fb_columns', 'flipbox_builder_columns' ) ;
add_action( 'manage_fb_posts_custom_column', 'flipbox_builder_manage_columns' , 10, 2 );
function flipbox_builder_columns( $columns )
{
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Flipbox' ),
		'shortcode' => __( 'Flipbox Shortcode' ),
		'date' => __( 'Date' )
	);
	return $columns;
}
function flipbox_builder_manage_columns( $column, $post_id ){  
		
	switch( $column ) {
		case 'shortcode' :
		echo '<input style="width:150px;" type="text" value="[Flipbox id='.esc_attr($post_id).']" readonly="readonly" onclick="this.select()" />';
		break;		
		default :
		break;
	}
}
?>