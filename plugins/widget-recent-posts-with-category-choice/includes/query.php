<?php
/*
Widget Query Posts
Plugin: Recent Posts Widget Advanced
Since: 0.4
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$title               = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );
$title               = apply_filters( 'widget_title', $title, $instance, $this->id_base );        
$post_type           = ( ! empty( $instance['post_type'] ) ) ? $instance['post_type'] : 'post'; 
$post_format         = ( ! empty( $instance['post_format'] ) ) ? $instance['post_format'] : false; 
$exclude_post_format = isset( $instance['exclude_post_format'] ) ? $instance['exclude_post_format'] : false;
$category            = ( ! empty( $instance['category'] ) ) ? $instance['category'] : false; 
$exclude             = isset( $instance['exclude'] ) ? $instance['exclude'] : false;
$tag                 = ( ! empty( $instance['tag'] ) ) ? $instance['tag'] : false; 
$exclude_tag         = isset( $instance['exclude_tag'] ) ? $instance['exclude_tag'] : false;
$author              = ( ! empty( $instance['author'] ) ) ? $instance['author'] : false; 
$exclude_author      = isset( $instance['exclude_author'] ) ? $instance['exclude_author'] : false;
$show_sticky_posts   = isset( $instance['show_sticky_posts'] ) ? $instance['show_sticky_posts'] : false;               
$show_thumb          = isset( $instance['show_thumb'] ) ? $instance['show_thumb'] : false;        
$show_date           = isset( $instance['show_date'] ) ? $instance['show_date'] : false;        
$show_author         = isset( $instance['show_author'] ) ? $instance['show_author'] : false;        
$number              = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;		
$offset              = ( ! empty( $instance['offset'] ) ) ? absint( $instance['offset'] ) : 0;		


$query = array(
    'post_type'      => explode( ',', $post_type ),
    'offset'         => $offset,
    'posts_per_page' => $number,
    'no_found_rows'  => true,
    'post_status'    => 'publish'
);

if ( $post_format ) {
    if ( $exclude_post_format ) {
        $query['tax_query'][] = array( 'taxonomy' => 'post_format', 'terms' => explode( ',', $post_format ), 'field' => 'slug', 'operator' => 'NOT IN' );
    } else {
        $query['tax_query'][] = array( 'taxonomy' => 'post_format', 'terms' => explode( ',', $post_format ), 'field' => 'slug', 'operator' => 'IN' );
    }
}

if ( $category ) {
    if ( $exclude ) {
        $query['category__not_in'] = explode( ',', $category );
    } else {
        $query['category__in'] = explode( ',', $category );
    }
}

if ( $tag ) {
    if ( $exclude_tag ) {
        $query['tag__not_in'] = explode( ',', $tag );
    } else {
        $query['tag__in'] = explode( ',', $tag );
    }
}

if ( $author ) {
    if ( $exclude_author ) {
        $query['author__not_in'] = explode( ',', $author );
    } else {
        $query['author__in'] = explode( ',', $author );
    }
}

if ( $show_sticky_posts ) {
    $query['ignore_sticky_posts'] = false;
} else {
    $query['ignore_sticky_posts'] = true;
}

$query = apply_filters( 'widget_posts_args', $query );
$query = new WP_Query( $query );

if ( ! $query->have_posts() ) {
    return;
}
