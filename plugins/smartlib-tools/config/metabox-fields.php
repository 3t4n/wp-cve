<?php

global $meta_boxes;

/**
 * prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = 'smartlib_';
// 1st meta box


$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id'         => 'standard',
    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title'      => __( 'Additional information to the content', 'bootframe' ),
    // Post types, accept custom post types as well - DEFAULT is 'post'. Can be array (multiple post types) or string (1 post type). Optional.
    'post_types' => array(  'smartlib_portfolio' ),
    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context'    => 'normal',
    // Order of meta box: high (default), low. Optional.
    'priority'   => 'high',
    // Auto save: true, false (default). Optional.
    'autosave'   => true,
    // List of meta fields
    // Show this meta box for posts matched below conditions


    'fields'     => array(
        array(
            // Field name - Will be used as label
            'name'  => __( 'Client Name', 'bootframe' ),
            // Field ID, i.e. the meta key
            'id'    => "{$prefix}client_name",
            // Field description (optional)

            'type'  => 'text',
            'size' => 100
        ),
        array(
            // Field name - Will be used as label
            'name'  => __( 'Portfolio images', 'bootframe' ),
            // Field ID, i.e. the meta key
            'id'    => "{$prefix}item_image",
            // Field description (optional)

            'type'             => 'image_advanced'
        )
    ),

);