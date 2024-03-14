<?php
/**
 * Template part for thumbnail archive of comics
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */
$comics_paged = isset($_GET['comics_paged']) ? (int) $_GET['comics_paged'] : 1;
$comic_order = get_option('toocheke-comics-order') ? get_option('toocheke-comics-order') : 'DESC';
$templates = new Toocheke_Companion_Template_Loader;
$series_id = get_query_var('series_id');
if (post_type_exists('comic')):
    $comics_args = array(
        'post_parent' => $series_id,
        'post_type' => 'comic',
        'post_status' => 'publish',
        'posts_per_page' => get_option('posts_per_page'),
        'paged' => $comics_paged,
        'orderby' => 'post_date',
        'order' => $comic_order,
    );

    $comics_query = new WP_Query($comics_args);
    if ($comics_query->have_posts()):
 ?>



<ul id="comic-list">
      <?php

/* Start the Loop */
while ($comics_query->have_posts()): $comics_query->the_post();


    /*
     * Include the Post-Type-specific template for the content.
     * If you want to override this in a child theme, then include a file
     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
     */
    $templates->get_template_part('content', 'comiclistitem');

endwhile;
?>
</ul>


     <!-- Start Pagination -->
<?php
// Set up paginated links.
$comic_links = paginate_links(array(
    'format' => '?comics_paged=%#%#comics-section',
    'current' => $comics_paged,
    'total' => $comics_query->max_num_pages,
    'prev_text' => wp_kses(__('<i class=\'fas fa-chevron-left\'></i>', 'toocheke'), array('i' => array('class' => array()))),
    'next_text' => wp_kses(__('<i class=\'fas fa-chevron-right\'></i>', 'toocheke'), array('i' => array('class' => array()))),

));

if ($comic_links):

?>

<nav class="pagination">

    <?php echo wp_kses($comic_links, array(
    'a' => array(
        'href' => array(),
        'class' => array(),
    ),
    'i' => array(
        'class' => array(),
    ),
    'span' => array(
        'class' => array(),
    ),
)); ?>

</nav>
<!--/ .navigation -->
<?php
endif;
?>
<!-- End Pagination -->



<?php
$comics_query = null;
wp_reset_postdata();
endif;

endif;
?>