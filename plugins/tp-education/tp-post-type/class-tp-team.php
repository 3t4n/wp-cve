<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Team Post Type
 *
 * @class       TP_Team_Post_type
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

class TP_Team_Post_type {

    public function __construct(){
        add_action( 'init', array( $this, 'tp_team_post_type' ) );
    }

    public function tp_team_post_type() {

        $team_labels = array(
            'name'               => _x( 'Team', 'post type general name', 'tp-education' ),
            'singular_name'      => _x( 'Team', 'post type singular name', 'tp-education' ),
            'menu_name'          => _x( 'Team', 'admin menu', 'tp-education' ),
            'name_admin_bar'     => _x( 'Team', 'add new on admin bar', 'tp-education' ),
            'add_new'            => _x( 'Add New', 'Team', 'tp-education' ),
            'add_new_item'       => __( 'Add New Team', 'tp-education' ),
            'new_item'           => __( 'New Team', 'tp-education' ),
            'edit_item'          => __( 'Edit Team', 'tp-education' ),
            'view_item'          => __( 'View Team', 'tp-education' ),
            'all_items'          => __( 'All Team', 'tp-education' ),
            'search_items'       => __( 'Search Team', 'tp-education' ),
            'parent_item_colon'  => __( 'Parent Team:', 'tp-education' ),
            'not_found'          => __( 'No Team Found.', 'tp-education' ),
            'not_found_in_trash' => __( 'No Team Found in Trash.', 'tp-education' )
        );
        $team_args = array(
            'labels'             => $team_labels,
            'description'        => __( 'Description.', 'tp-education' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'tp-team', 'with_front' => false ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );
        register_post_type( 'tp-team', $team_args );

        // Add new taxonomy for Team
        $team_cat_labels = array(
            'name'              => _x( 'Team Categories', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Team Category', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Team Categories', 'tp-education' ),
            'all_items'         => __( 'All Team Categories', 'tp-education' ),
            'parent_item'       => __( 'Parent Team Category', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Team Category:', 'tp-education' ),
            'edit_item'         => __( 'Edit Team Category', 'tp-education' ),
            'update_item'       => __( 'Update Team Category', 'tp-education' ),
            'add_new_item'      => __( 'Add New Team Category', 'tp-education' ),
            'new_item_name'     => __( 'New Team Category Name', 'tp-education' ),
            'menu_name'         => __( 'Team Category', 'tp-education' ),
        );

        $team_cat_args = array(
            'hierarchical'      => true,
            'labels'            => $team_cat_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tp-team-category' ),
        );

        register_taxonomy( 'tp-team-category', array( 'tp-team' ), $team_cat_args );

        // Add new taxonomy for Team
        $team_tag_labels = array(
            'name'              => _x( 'Team Tags', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Team Tag', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Team Tags', 'tp-education' ),
            'all_items'         => __( 'All Team Tags', 'tp-education' ),
            'parent_item'       => __( 'Parent Team Tag', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Team Tag:', 'tp-education' ),
            'edit_item'         => __( 'Edit Team Tag', 'tp-education' ),
            'update_item'       => __( 'Update Team Tag', 'tp-education' ),
            'add_new_item'      => __( 'Add New Team Tag', 'tp-education' ),
            'new_item_name'     => __( 'New Team Tag Name', 'tp-education' ),
            'menu_name'         => __( 'Team Tag', 'tp-education' ),
        );

        $team_tag_args = array(
            'labels'            => $team_tag_labels,
        );
        
        register_taxonomy( 'tp-team-tag', array( 'tp-team' ), $team_tag_args );
        
    }
    
}

new TP_Team_Post_type();
