<?php
/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
/*
gs gallery
*/

//Registers a new post type
if ( ! function_exists( 'GS_wps' ) ) {
    function GS_wps() {
        $labels = array(
            'name'               => _x( 'Product Shortcode', 'gswps' ),
            'singular_name'      => _x( 'Product Shortcode', 'gswps' ),
            'menu_name'          => _x( 'GS Product Slider', 'admin menu', 'gswps' ),
            'name_admin_bar'     => _x( 'GS Product Shortcode', 'add new on admin bar', 'gswps' ),
            'add_new'            => _x( 'Add New Product Shortcode', 'Product Shortcode', 'gswps' ),
            'add_new_item'       => __( 'Add New Product Shortcode', 'gswps' ),
            'new_item'           => __( 'New Product Shortcode', 'gswps' ),
            'edit_item'          => __( 'Edit Product Shortcode', 'gswps' ),
            'view_item'          => __( 'View Product Shortcode', 'gswps' ),
            'all_items'          => __( 'All Product Shortcode', 'gswps' ),
            'search_items'       => __( 'Search Product Shortcode', 'gswps' ),
            'parent_item_colon'  => __( 'Parent Product Shortcode:', 'gswps' ),
            'not_found'          => __( 'No Product Shortcode found.', 'gswps' ),
            'not_found_in_trash' => __( 'No Product Shortcode found in Trash.', 'gswps' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'gs_wps_cpt' ), //this slug is for post path/permalink
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_icon'          => 'dashicons-format-gallery',
            'supports'           => array( 'title','thumbnail'),
//            'register_meta_box_cb' => 'GSPROPERTIES_video_gal_metaboxes'
        );
        register_post_type( 'gs_wps_cpt', $args );
        flush_rewrite_rules();
    }
}
add_action( 'init', 'GS_wps' );

function my_admin_menu() { 
$gswps_new_cpt ='edit.php?post_type=gs_wps_cpt';
add_submenu_page('gsp-main', 'Generate Shortcode', 'Generate Shortcode', 'manage_options', $gswps_new_cpt);

}


add_action('admin_menu', 'my_admin_menu'); 
