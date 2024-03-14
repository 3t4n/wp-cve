<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Testimonial Post Type
 *
 * @class       TP_Testimonial_Post_Type
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

class TP_Testimonial_Post_Type {

    public function __construct(){
        add_action( 'init', array( $this, 'tp_testimonial_post_type' ) );
    }

    public function tp_testimonial_post_type() {

        $testimonial_labels = array(
            'name'               => _x( 'Testimonials', 'post type general name', 'tp-education' ),
            'singular_name'      => _x( 'Testimonial', 'post type singular name', 'tp-education' ),
            'menu_name'          => _x( 'Testimonials', 'admin menu', 'tp-education' ),
            'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'tp-education' ),
            'add_new'            => _x( 'Add New', 'Testimonial', 'tp-education' ),
            'add_new_item'       => __( 'Add New Testimonial', 'tp-education' ),
            'new_item'           => __( 'New Testimonial', 'tp-education' ),
            'edit_item'          => __( 'Edit Testimonial', 'tp-education' ),
            'view_item'          => __( 'View Testimonial', 'tp-education' ),
            'all_items'          => __( 'All Testimonials', 'tp-education' ),
            'search_items'       => __( 'Search Testimonials', 'tp-education' ),
            'parent_item_colon'  => __( 'Parent Testimonials:', 'tp-education' ),
            'not_found'          => __( 'No Testimonial Found.', 'tp-education' ),
            'not_found_in_trash' => __( 'No Testimonial Found in Trash.', 'tp-education' )
        );
        $testimonial_args = array(
            'labels'             => $testimonial_labels,
            'description'        => __( 'Description.', 'tp-education' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'tp-testimonial', 'with_front' => false ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-star-filled',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );
        register_post_type( 'tp-testimonial', $testimonial_args );

    }
    
}

new TP_Testimonial_Post_Type();
