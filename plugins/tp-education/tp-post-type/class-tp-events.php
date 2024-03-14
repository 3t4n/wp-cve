<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Events Post Type
 *
 * @class       TP_Event_Post_type
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

class TP_Event_Post_type {

    public function __construct()
    {
        add_action( 'init', array( $this, 'tp_events_post_type' ) );
    }

    public function tp_events_post_type() 
    {

        $event_labels = array(
            'name'               => _x( 'Events', 'post type general name', 'tp-education' ),
            'singular_name'      => _x( 'Event', 'post type singular name', 'tp-education' ),
            'menu_name'          => _x( 'Events', 'admin menu', 'tp-education' ),
            'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'tp-education' ),
            'add_new'            => _x( 'Add New', 'Event', 'tp-education' ),
            'add_new_item'       => __( 'Add New Event', 'tp-education' ),
            'new_item'           => __( 'New Event', 'tp-education' ),
            'edit_item'          => __( 'Edit Event', 'tp-education' ),
            'view_item'          => __( 'View Event', 'tp-education' ),
            'all_items'          => __( 'All Events', 'tp-education' ),
            'search_items'       => __( 'Search Events', 'tp-education' ),
            'parent_item_colon'  => __( 'Parent Events:', 'tp-education' ),
            'not_found'          => __( 'No Events Found.', 'tp-education' ),
            'not_found_in_trash' => __( 'No Events Found in Trash.', 'tp-education' )
        );
        $event_args = array(
            'labels'             => $event_labels,
            'description'        => __( 'Description.', 'tp-education' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'tp-event', 'with_front' => false ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-calendar',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );
        register_post_type( 'tp-event', $event_args );

        // Add new taxonomy for events
        $event_cat_labels = array(
            'name'              => _x( 'Event Categories', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Event Categories', 'tp-education' ),
            'all_items'         => __( 'All Event Categories', 'tp-education' ),
            'parent_item'       => __( 'Parent Event Category', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Event Category:', 'tp-education' ),
            'edit_item'         => __( 'Edit Event Category', 'tp-education' ),
            'update_item'       => __( 'Update Event Category', 'tp-education' ),
            'add_new_item'      => __( 'Add New Event Category', 'tp-education' ),
            'new_item_name'     => __( 'New Event Category Name', 'tp-education' ),
            'menu_name'         => __( 'Event Category', 'tp-education' ),
        );

        $event_cat_args = array(
            'hierarchical'      => true,
            'labels'            => $event_cat_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tp-event-category' ),
        );

        register_taxonomy( 'tp-event-category', array( 'tp-event' ), $event_cat_args );

        // Add new taxonomy for Event
        $event_tag_labels = array(
            'name'              => _x( 'Event Tags', 'taxonomy general name', 'tp-education' ),
            'singular_name'     => _x( 'Event Tag', 'taxonomy singular name', 'tp-education' ),
            'search_items'      => __( 'Search Event Tags', 'tp-education' ),
            'all_items'         => __( 'All Event Tags', 'tp-education' ),
            'parent_item'       => __( 'Parent Event Tag', 'tp-education' ),
            'parent_item_colon' => __( 'Parent Event Tag:', 'tp-education' ),
            'edit_item'         => __( 'Edit Event Tag', 'tp-education' ),
            'update_item'       => __( 'Update Event Tag', 'tp-education' ),
            'add_new_item'      => __( 'Add New Event Tag', 'tp-education' ),
            'new_item_name'     => __( 'New Event Tag Name', 'tp-education' ),
            'menu_name'         => __( 'Event Tag', 'tp-education' ),
        );

        $event_tag_args = array(
            'labels'            => $event_tag_labels,
        );
        
        register_taxonomy( 'tp-event-tag', array( 'tp-event' ), $event_tag_args );
        
    }
    
}

new TP_Event_Post_type();
