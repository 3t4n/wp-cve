<?php

namespace Elementor;

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

/**
 * Elementor category
 */
function wpfilm_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'wpfilm-studio',
        [
            'title'  => 'WP Film Studio',
            'icon' => 'font'
        ],
        1
    );
}
add_action('elementor/init','Elementor\wpfilm_elementor_init');

// Trailer Category
function wpfilm_studio_trailer_categories(){
    $terms = get_terms( array(
        'taxonomy' => 'wpfilm_trailer_category',
        'hide_empty' => true,
    ));
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $options[ $term->slug ] = $term->name;
        }
        return $options;
    }
}
// Movie Category
function wpfilm_studio_movie_categories(){
    $terms = get_terms( array(
        'taxonomy' => 'wpfilm_movie_category',
        'hide_empty' => true,
    ));
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $options[ $term->slug ] = $term->name;
        }
        return $options;
    }
}
// Campaign Category
function wpfilm_categories(){
    $terms = get_terms( array(
        'taxonomy' => 'campaign_category',
        'hide_empty' => true,
    ));
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $options[ $term->slug ] = $term->name;
        }
        return $options;
    }
}