<?php
/**
 * Template part for displaying latest chapters
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */

/**
 * Get latest six chapters of comics. If there are no chapters or chapters with no comics, don't display.
 */
$chapter_comic_order = get_option('toocheke-chapter-first-comic') ? get_option('toocheke-chapter-first-comic') : 'DESC';
$comic_order = get_option('toocheke-comics-order') ? get_option('toocheke-comics-order') : 'DESC';
$chapter_args = array(
    'taxonomy' => 'chapters',
    'style' => 'none',
    'orderby' => 'meta_value_num',
    'order' => $comic_order,
    'meta_query' => array(
        array(
            'key' => 'chapter-order',
            'type' => 'NUMERIC',
        )),
    'show_count' => 0,
    'number' => 6,
);
$chapters_list = get_categories($chapter_args);

if ($chapters_list) {
    ?>
                <!-- START COMIC CHAPTER LIST-->
                <div id="chapter-wrapper" class="grid-container grid-three-cols">
              

                     
             
                        <?php

    foreach ($chapters_list as $chapter) {
        /**
         * Get latest post for this chapter
         */
        $link_to_first_comic = '';
        $args = array(
            'posts_per_page' => 1,
            'post_type' => 'comic',
            'order' => $chapter_comic_order,
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
                printf(wp_kses_data('%1$s'), '<img src="' . esc_attr($term_img) . '" /><br/>');
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
            
                    <!--end chapters row-->
            
                 
                </div>

                <?php
}