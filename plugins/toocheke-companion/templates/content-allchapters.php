<?php
/**
 * Template part for displaying all chapters
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */

/**
 * Get latest six chapters of comics. If there are no chapters or chapters with no comics, don't display.
 */
$chapter_comic_order = get_option('toocheke-chapter-first-comic') ? get_option('toocheke-chapter-first-comic') : 'DESC';
$comic_order = get_option('toocheke-comics-order') ? get_option('toocheke-comics-order') : 'DESC';
//Get total number of active chapters
$total_args = array(
    'taxonomy' => 'chapters',
);
$all_active_chapters_list = get_categories($total_args);
$total_active_chapters = count($all_active_chapters_list);
//start paging
$chapter_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$chapters_per_page = 60;
$total_number_of_pages = ceil($total_active_chapters / $chapters_per_page);
$paged_offset = ($chapter_paged - 1) * $chapters_per_page;

//setup paginate args
$paginate_args = array(
    'taxonomy' => 'chapters',
    'style' => 'none',
    'orderby' => 'meta_value_num',
    'order' => $chapter_comic_order,
    'meta_query' => array(
        array(
            'key' => 'chapter-order',
            'type' => 'NUMERIC',
        )),
    'show_count' => 0,
    'number' => $chapters_per_page,
    'paged' => $chapter_paged,
    'offset' => $paged_offset,
);

$paged_chapters_list = get_categories($paginate_args);

if ($paged_chapters_list) {
    ?>
                <!-- START COMIC CHAPTER LIST-->
                <div id="all-chapters-wrapper" class="grid-container grid-four-cols">
              

                     
             
                <?php

foreach ($paged_chapters_list as $chapter) {

    /**
     * Get latest post for this chapter
     */
    $link_to_first_comic = '';
    $args = array(
        'posts_per_page' => 1,
        'post_type' => 'comic',
        'orderby' => 'post_date',
        'order' => $comic_order,
        "tax_query" => array(
            array(
                'taxonomy' => "chapters", // use the $tax you define at the top of your script
                'field' => 'term_id',
                'terms' => $chapter->term_id, // use the current term in your foreach loop
            ),
        ),
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );
    $first_comic_query = new WP_Query($args);
    // The Loop
    while ($first_comic_query->have_posts()): $first_comic_query->the_post();
    $link_to_first_comic = get_post_permalink(); // Display the image of the first post in category
        wp_reset_postdata();
        printf(wp_kses_data('%1$s'), '<div class="chapter-thumbnail">');
        printf(wp_kses_data('%1$s'), '<a href="' . esc_url($link_to_first_comic) . '">');
        $term_id = absint($chapter->term_id);
        $thumb_id = get_term_meta($term_id, 'chapter-image-id', true);

        if (!empty($thumb_id)) {
            $term_img = wp_get_attachment_url($thumb_id);
            printf(wp_kses_data('%1$s'), '<img src="' . esc_url($term_img) . '" /><br/>');
        }
        else {
            ?>
                                            <img
                                            src="<?php echo esc_attr(plugins_url('toocheke-companion' . '/img/default-thumbnail-image.png')); ?>" />
                                            <?php
        }
        echo wp_kses_data($chapter->name);
        echo '</a></div>';
    endwhile;

}
// Reset Post Data
wp_reset_postdata();

?>
            

                 
                </div>
                <!--end chapters wrapper-->
                <div class="chapters-navigation">
                    <hr/>

<!-- Start Pagination -->
<?php
// Set up paginated links.
 $big = 999999999; // need an unlikely integer
 $links = paginate_links(array(
     'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
     'format' => '?paged=%#%',
     'current' => max(1, get_query_var('paged')),
     'total' => $total_number_of_pages,
     'prev_text' => wp_kses(__('<i class=\'fas fa-chevron-left\'></i>', 'toocheke'), array('i' => array('class' => array()))),
     'next_text' => wp_kses(__('<i class=\'fas fa-chevron-right\'></i>', 'toocheke'), array('i' => array('class' => array()))),
 ));

 if ($links):

 ?>

<div class="paginate-links">

         <?php echo wp_kses($links, array(
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

     </div><!--/ .navigation -->
 <?php
endif;
 ?>
<!-- End Pagination -->
                    </div>
                    <!--end chapters-navigation-->
                <!-- END COMIC CHAPTER LIST-->
                <?php
}