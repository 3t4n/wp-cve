<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Excursion Post Type
 *
 * @class       TP_Excursion_Post_type
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

class TP_Excursion_Post_type {

    public function __construct() {
        add_action( 'init', array( $this, 'tp_excursion_post_type' ) );
    }

    public function tp_excursion_post_type() {

        $excursion_labels = array(
            'name'               => _x( 'Excursions', 'post type general name', 'tp-education' ),
            'singular_name'      => _x( 'Excursion', 'post type singular name', 'tp-education' ),
            'menu_name'          => _x( 'Excursions', 'admin menu', 'tp-education' ),
            'name_admin_bar'     => _x( 'Excursion', 'add new on admin bar', 'tp-education' ),
            'add_new'            => _x( 'Add New', 'Excursion', 'tp-education' ),
            'add_new_item'       => __( 'Add New Excursion', 'tp-education' ),
            'new_item'           => __( 'New Excursion', 'tp-education' ),
            'edit_item'          => __( 'Edit Excursion', 'tp-education' ),
            'view_item'          => __( 'View Excursion', 'tp-education' ),
            'all_items'          => __( 'All Excursions', 'tp-education' ),
            'search_items'       => __( 'Search Excursions', 'tp-education' ),
            'parent_item_colon'  => __( 'Parent Excursions:', 'tp-education' ),
            'not_found'          => __( 'No Excursions Found.', 'tp-education' ),
            'not_found_in_trash' => __( 'No Excursions Found in Trash.', 'tp-education' )
        );
        $excursion_args = array(
            'labels'             => $excursion_labels,
            'description'        => __( 'Description.', 'tp-education' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'tp-excursion', 'with_front' => false ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-palmtree',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );
        register_post_type( 'tp-excursion', $excursion_args );

        // Add new taxonomy for Excursions
        $excursion_cat_labels = array(
            'name'              => _x( 'Excursion Categories', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Excursion Category', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Excursion Categories', 'tp-education' ),
            'all_items'         => __( 'All Excursion Categories', 'tp-education' ),
            'parent_item'       => __( 'Parent Excursion Category', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Excursion Category:', 'tp-education' ),
            'edit_item'         => __( 'Edit Excursion Category', 'tp-education' ),
            'update_item'       => __( 'Update Excursion Category', 'tp-education' ),
            'add_new_item'      => __( 'Add New Excursion Category', 'tp-education' ),
            'new_item_name'     => __( 'New Excursion Category Name', 'tp-education' ),
            'menu_name'         => __( 'Excursion Category', 'tp-education' ),
        );

        $excursion_cat_args = array(
            'hierarchical'      => true,
            'labels'            => $excursion_cat_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tp-excursion-category' ),
        );

        register_taxonomy( 'tp-excursion-category', array( 'tp-excursion' ), $excursion_cat_args );

        // Add new taxonomy for Excursion
        $excursion_tag_labels = array(
            'name'              => _x( 'Excursion Tags', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Excursion Tag', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Excursion Tags', 'tp-education' ),
            'all_items'         => __( 'All Excursion Tags', 'tp-education' ),
            'parent_item'       => __( 'Parent Excursion Tag', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Excursion Tag:', 'tp-education' ),
            'edit_item'         => __( 'Edit Excursion Tag', 'tp-education' ),
            'update_item'       => __( 'Update Excursion Tag', 'tp-education' ),
            'add_new_item'      => __( 'Add New Excursion Tag', 'tp-education' ),
            'new_item_name'     => __( 'New Excursion Tag Name', 'tp-education' ),
            'menu_name'         => __( 'Excursion Tag', 'tp-education' ),
        );

        $excursion_tag_args = array(
            'labels'            => $excursion_tag_labels,
        );
        
        register_taxonomy( 'tp-excursion-tag', array( 'tp-excursion' ), $excursion_tag_args );
     
    }
    
}

new TP_Excursion_Post_type();