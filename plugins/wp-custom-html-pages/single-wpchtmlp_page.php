<?php
/*
Template Name: WPCHTMLP Page Display
Template Post Type: wpchtmlp_page
*/

while ( have_posts() ) : the_post();
    $meta = get_post_meta(get_the_ID(), 'WPCHTMLP_page_meta_box', true);
    echo stripslashes($meta['html_code']);
endwhile; // End of the loop.