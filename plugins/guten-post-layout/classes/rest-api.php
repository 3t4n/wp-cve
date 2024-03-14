<?php
/**
 *
 *
 * @since   1.0.0
 * @package gpl
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Create API fields for additional info
 */

function guten_post_layout_register_rest_fields() {
    $post_types = get_post_types();

    register_rest_field(
        $post_types,
        'guten_post_layout_featured_media_urls',
        array(
            'get_callback' => 'get_guten_post_layout_featured_media',
            'update_callback' => null,
            'schema' => array(
                'description' => __( 'Different Sized Featured Images', 'guten-post-layout'),
                'type' => 'array'
            )
        )
    );

}
add_action('rest_api_init', 'guten_post_layout_register_rest_fields');


function guten_post_layout_get_image_sizes() {

    global $_wp_additional_image_sizes;

    $sizes       = get_intermediate_image_sizes();
    $image_sizes = array();

    $image_sizes[] = array(
        'value' => 'full',
        'label' => esc_html__( 'Full', 'guten-post-layout' ),
    );

    foreach ( $sizes as $size ) {
        if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
            $image_sizes[] = array(
                'value' => $size,
                'label' => ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
            );
        } else {
            $image_sizes[] = array(
                'value' => $size,
                'label' => sprintf(
                    '%1$s (%2$sx%3$s)',
                    ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
                    $_wp_additional_image_sizes[ $size ]['width'],
                    $_wp_additional_image_sizes[ $size ]['height']
                ),
            );
        }
    }

    return $image_sizes;
}

function get_guten_post_layout_featured_media($object, $field_name, $request){

    $image_sizes = guten_post_layout_get_image_sizes();

    $featured_images = array();

    if ( ! isset( $object['featured_media'] ) ) {
        return $featured_images;
    }

    foreach ( $image_sizes as $key => $value ) {
        $size = $value['value'];

        $featured_images[ $size ] = wp_get_attachment_image_src(
            $object['featured_media'],
            $size,
            false
        );
    }

    return $featured_images;

}

/**
 * Create API Order By Fields
 */
function guten_post_layout_register_rest_orderby_fields(){
    $post_types = get_post_types();

    foreach ( $post_types as $type ) {
        // This enables the orderby=menu_order for any Posts
        add_filter( "rest_{$type}_collection_params", 'guten_post_layout_add_orderby_params', 10, 1 );
    }
}
add_action( 'init', 'guten_post_layout_register_rest_orderby_fields' );


/**
 * Add menu_order to the list of permitted orderby values
 */
function guten_post_layout_add_orderby_params( $params ) {

    $params['orderby']['enum'][] = 'menu_order';
    $params['orderby']['enum'][] = 'rand';
    $params['orderby']['enum'][] = 'author';
    $params['orderby']['enum'][] = 'id';
    $params['orderby']['enum'][] = 'date';
    $params['orderby']['enum'][] = 'title';
    $params['orderby']['enum'][] = 'modified';
    $params['orderby']['enum'][] = 'parent';

    return $params;

}
