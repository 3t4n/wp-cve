<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class Post Type
 *
 * @class       TP_Class_Post_type
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

class TP_Class_Post_type {

    public function __construct(){
        add_action( 'init', array( $this, 'tp_class_post_type' ) );
    }

    public function tp_class_post_type() {

        $class_labels = array(
            'name'               => _x( 'Classes', 'post type general name', 'tp-education' ),
            'singular_name'      => _x( 'Class', 'post type singular name', 'tp-education' ),
            'menu_name'          => _x( 'Classes', 'admin menu', 'tp-education' ),
            'name_admin_bar'     => _x( 'Class', 'add new on admin bar', 'tp-education' ),
            'add_new'            => _x( 'Add New', 'Class', 'tp-education' ),
            'add_new_item'       => __( 'Add New Class', 'tp-education' ),
            'new_item'           => __( 'New Class', 'tp-education' ),
            'edit_item'          => __( 'Edit Class', 'tp-education' ),
            'view_item'          => __( 'View Class', 'tp-education' ),
            'all_items'          => __( 'All Classes', 'tp-education' ),
            'search_items'       => __( 'Search Classes', 'tp-education' ),
            'parent_item_colon'  => __( 'Parent Classes:', 'tp-education' ),
            'not_found'          => __( 'No Classes Found.', 'tp-education' ),
            'not_found_in_trash' => __( 'No Classes Found in Trash.', 'tp-education' )
        );

        $class_args = array(
            'labels'             => $class_labels,
            'description'        => __( 'Description.', 'tp-education' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'tp-class', 'with_front' => false ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-image-filter',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );
        
        register_post_type( 'tp-class', $class_args );

        // Add new taxonomy for Classes
        $class_cat_labels = array(
            'name'              => _x( 'Class Categories', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Class Category', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Class Categories', 'tp-education' ),
            'all_items'         => __( 'All Class Categories', 'tp-education' ),
            'parent_item'       => __( 'Parent Class Category', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Class Category:', 'tp-education' ),
            'edit_item'         => __( 'Edit Class Category', 'tp-education' ),
            'update_item'       => __( 'Update Class Category', 'tp-education' ),
            'add_new_item'      => __( 'Add New Class Category', 'tp-education' ),
            'new_item_name'     => __( 'New Class Category Name', 'tp-education' ),
            'menu_name'         => __( 'Class Category', 'tp-education' ),
        );

        $class_cat_args = array(
            'hierarchical'      => true,
            'labels'            => $class_cat_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tp-class-category' ),
        );

        register_taxonomy( 'tp-class-category', array( 'tp-class' ), $class_cat_args );

        // Add new taxonomy for Class
        $class_tag_labels = array(
            'name'              => _x( 'Class Tags', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Class Tag', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Class Tags', 'tp-education' ),
            'all_items'         => __( 'All Class Tags', 'tp-education' ),
            'parent_item'       => __( 'Parent Class Tag', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Class Tag:', 'tp-education' ),
            'edit_item'         => __( 'Edit Class Tag', 'tp-education' ),
            'update_item'       => __( 'Update Class Tag', 'tp-education' ),
            'add_new_item'      => __( 'Add New Class Tag', 'tp-education' ),
            'new_item_name'     => __( 'New Class Tag Name', 'tp-education' ),
            'menu_name'         => __( 'Class Tag', 'tp-education' ),
        );

        $class_tag_args = array(
            'labels'            => $class_tag_labels,
        );

        register_taxonomy( 'tp-class-tag', array( 'tp-class' ), $class_tag_args );
  
    }
    
}

new TP_Class_Post_type();