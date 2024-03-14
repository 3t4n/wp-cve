<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Course Post Type
 *
 * @class       TP_Course_Post_type
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

class TP_Course_Post_type {

    public function __construct() {
        add_action( 'init', array( $this, 'tp_course_post_type' ) );
    }

    public function tp_course_post_type() {

        $course_labels = array(
            'name'               => _x( 'Courses', 'post type general name', 'tp-education' ),
            'singular_name'      => _x( 'Course', 'post type singular name', 'tp-education' ),
            'menu_name'          => _x( 'Courses', 'admin menu', 'tp-education' ),
            'name_admin_bar'     => _x( 'Course', 'add new on admin bar', 'tp-education' ),
            'add_new'            => _x( 'Add New', 'Course', 'tp-education' ),
            'add_new_item'       => __( 'Add New Course', 'tp-education' ),
            'new_item'           => __( 'New Course', 'tp-education' ),
            'edit_item'          => __( 'Edit Course', 'tp-education' ),
            'view_item'          => __( 'View Course', 'tp-education' ),
            'all_items'          => __( 'All Courses', 'tp-education' ),
            'search_items'       => __( 'Search Courses', 'tp-education' ),
            'parent_item_colon'  => __( 'Parent Courses:', 'tp-education' ),
            'not_found'          => __( 'No Courses Found.', 'tp-education' ),
            'not_found_in_trash' => __( 'No Courses Found in Trash.', 'tp-education' )
        );
        $course_args = array(
            'labels'             => $course_labels,
            'description'        => __( 'Description.', 'tp-education' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'tp-course', 'with_front' => false ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-book',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );
        register_post_type( 'tp-course', $course_args );

        // Add new taxonomy for Courses
        $course_cat_labels = array(
            'name'              => _x( 'Course Categories', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Course Category', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Course Categories', 'tp-education' ),
            'all_items'         => __( 'All Course Categories', 'tp-education' ),
            'parent_item'       => __( 'Parent Course Category', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Course Category:', 'tp-education' ),
            'edit_item'         => __( 'Edit Course Category', 'tp-education' ),
            'update_item'       => __( 'Update Course Category', 'tp-education' ),
            'add_new_item'      => __( 'Add New Course Category', 'tp-education' ),
            'new_item_name'     => __( 'New Course Category Name', 'tp-education' ),
            'menu_name'         => __( 'Course Category', 'tp-education' ),
        );

        $course_cat_args = array(
            'hierarchical'      => true,
            'labels'            => $course_cat_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tp-course-category' ),
        );

        register_taxonomy( 'tp-course-category', array( 'tp-course' ), $course_cat_args );

        // Add new taxonomy for Course
        $course_tag_labels = array(
            'name'              => _x( 'Course Tags', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Course Tag', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Course Tags', 'tp-education' ),
            'all_items'         => __( 'All Course Tags', 'tp-education' ),
            'parent_item'       => __( 'Parent Course Tag', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Course Tag:', 'tp-education' ),
            'edit_item'         => __( 'Edit Course Tag', 'tp-education' ),
            'update_item'       => __( 'Update Course Tag', 'tp-education' ),
            'add_new_item'      => __( 'Add New Course Tag', 'tp-education' ),
            'new_item_name'     => __( 'New Course Tag Name', 'tp-education' ),
            'menu_name'         => __( 'Course Tag', 'tp-education' ),
        );

        $course_tag_args = array(
            'labels'            => $course_tag_labels,
        );
        
        register_taxonomy( 'tp-course-tag', array( 'tp-course' ), $course_tag_args );
        
    }

}

new TP_Course_Post_type();