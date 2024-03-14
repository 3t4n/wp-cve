<?php
/*
 * Template Name: Youzify Template
 * Description: Youzify Plugin Pages Template.
 */

get_header();

do_action( 'youzify_before_youzify_template_content' );

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
    the_content();
	endwhile;
endif;

do_action( 'youzify_after_youzify_template_content' );

get_footer();