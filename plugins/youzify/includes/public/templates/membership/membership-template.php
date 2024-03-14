<?php
/*
 * Template Name: Membership Template
 * Description: Membership Plugin Pages Template.
 */
get_header();
$shortcode = youzify_get_membership_page_shortcode( $post->ID );
echo apply_filters( 'the_content', $shortcode );
get_footer();