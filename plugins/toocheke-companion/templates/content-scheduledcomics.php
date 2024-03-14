<?php
/**
 * Template part for displaying top ten comics
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */
$args = array(
    'post_type' => 'comic',
    'post_status' => 'future',
    'posts_per_page' => 10,
    'orderby' => 'post_date',
    'order' => 'ASC',
);
$popular_comics = new WP_Query($args);
$templates = new Toocheke_Companion_Template_Loader;
if ($popular_comics->have_posts()): ?>


<ul id="comic-list">
    <?php

/* Start the Loop */
$rank = 0;
while ( $popular_comics->have_posts() ) : $popular_comics->the_post();
  set_query_var('rank', $rank);
  $templates->get_template_part('content', 'comiclistitem');
endwhile;
// Reset Post Data
wp_reset_postdata();
?>
</ul>    



<?php

endif;
?>