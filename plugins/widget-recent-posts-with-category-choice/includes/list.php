<?php
/*
Widget Update Options
Plugin: Recent Posts Widget Advanced
Since: 0.6
Author: KGM Servizi
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function show_list_taxonomy_slug( $taxonomy ) {
    $taxonomy_terms = get_terms( $taxonomy );
    foreach ( $taxonomy_terms as $term ) {
        $terms[] = $term->slug;
    }
    if ( !empty( $terms ) ) {
        echo "<br/>• " . implode( "<br/>• " , $terms );
    }
    else{
        echo "No " . esc_html( $taxonomy ) . " used.";
    }
}

function show_post_type() {
    $post_types = get_post_types();
    foreach ( $post_types as $post_type ) {
        $terms[] = $post_type;
    }
    if ( !empty( $terms ) ) {
        echo "<br/>• " . implode( "<br/>• " , $terms );
    }
}
