<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Affiliation Post Type
 *
 * @class       TP_Affiliation_Post_type
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

class TP_Affiliation_Post_type {

    public function __construct(){
        add_action( 'init', array( $this, 'tp_affiliation_post_type' ) );
    }

    public function tp_affiliation_post_type() {

        $affiliation_labels = array(
            'name'               => _x( 'Affiliation', 'post type general name', 'tp-education' ),
            'singular_name'      => _x( 'Affiliation', 'post type singular name', 'tp-education' ),
            'menu_name'          => _x( 'Affiliation', 'admin menu', 'tp-education' ),
            'name_admin_bar'     => _x( 'Affiliation', 'add new on admin bar', 'tp-education' ),
            'add_new'            => _x( 'Add New', 'Affiliation', 'tp-education' ),
            'add_new_item'       => __( 'Add New Affiliation', 'tp-education' ),
            'new_item'           => __( 'New Affiliation', 'tp-education' ),
            'edit_item'          => __( 'Edit Affiliation', 'tp-education' ),
            'view_item'          => __( 'View Affiliation', 'tp-education' ),
            'all_items'          => __( 'All Affiliation', 'tp-education' ),
            'search_items'       => __( 'Search Affiliation', 'tp-education' ),
            'parent_item_colon'  => __( 'Parent Affiliation:', 'tp-education' ),
            'not_found'          => __( 'No Affiliation Found.', 'tp-education' ),
            'not_found_in_trash' => __( 'No Affiliation Found in Trash.', 'tp-education' )
        );
        $affiliation_args = array(
            'labels'             => $affiliation_labels,
            'description'        => __( 'Description.', 'tp-education' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'tp-affiliation', 'with_front' => false ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-awards',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        );
        register_post_type( 'tp-affiliation', $affiliation_args );

        // Add new taxonomy for Affiliation
        $affiliation_cat_labels = array(
            'name'              => _x( 'Affiliation Categories', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Affiliation Category', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Affiliation Categories', 'tp-education' ),
            'all_items'         => __( 'All Affiliation Categories', 'tp-education' ),
            'parent_item'       => __( 'Parent Affiliation Category', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Affiliation Category:', 'tp-education' ),
            'edit_item'         => __( 'Edit Affiliation Category', 'tp-education' ),
            'update_item'       => __( 'Update Affiliation Category', 'tp-education' ),
            'add_new_item'      => __( 'Add New Affiliation Category', 'tp-education' ),
            'new_item_name'     => __( 'New Affiliation Category Name', 'tp-education' ),
            'menu_name'         => __( 'Affiliation Category', 'tp-education' ),
        );

        $affiliation_cat_args = array(
            'hierarchical'      => true,
            'labels'            => $affiliation_cat_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tp-affiliation-category' ),
        );

        register_taxonomy( 'tp-affiliation-category', array( 'tp-affiliation' ), $affiliation_cat_args );

        // Add new taxonomy for Affiliation
        $affiliation_tag_labels = array(
            'name'              => _x( 'Affiliation Tags', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Affiliation Tag', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Affiliation Tags', 'tp-education' ),
            'all_items'         => __( 'All Affiliation Tags', 'tp-education' ),
            'parent_item'       => __( 'Parent Affiliation Tag', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Affiliation Tag:', 'tp-education' ),
            'edit_item'         => __( 'Edit Affiliation Tag', 'tp-education' ),
            'update_item'       => __( 'Update Affiliation Tag', 'tp-education' ),
            'add_new_item'      => __( 'Add New Affiliation Tag', 'tp-education' ),
            'new_item_name'     => __( 'New Affiliation Tag Name', 'tp-education' ),
            'menu_name'         => __( 'Affiliation Tag', 'tp-education' ),
        );

        $affiliation_tag_args = array(
            'labels'            => $affiliation_tag_labels,
        );
        
        register_taxonomy( 'tp-affiliation-tag', array( 'tp-affiliation' ), $affiliation_tag_args );
        
    }
    
}

new TP_Affiliation_Post_type();
