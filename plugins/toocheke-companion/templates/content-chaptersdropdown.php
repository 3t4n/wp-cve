<?php
/**
 * Template part for chapters dropdown
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */

$display_chapters_dropdown = get_option('toocheke-chapter-dropdown') && 1 == get_option('toocheke-chapter-dropdown');
$comic_order = get_option('toocheke-chapter-first-comic') ? get_option('toocheke-chapter-first-comic') : 'DESC';
$series_id = null;
$series_id = isset($_GET['sid']) ? (int) $_GET['sid'] : null;
if (get_query_var('series_id')) {
    $series_id = (int) get_query_var('series_id');
}
if ('series' === get_post_type()) {
    $series_id = get_the_ID();
}
?>
<?php
if ($display_chapters_dropdown):

    $chapter_args = array(
        'taxonomy' => 'chapters',
        'style' => 'none',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'chapter-order',
                'type' => 'NUMERIC',
            )),
        'show_count' => 0,

    );

    $chapters_list = get_categories($chapter_args);
    if ($chapters_list) {
        ?>
         <h3>CHOOSE YOUR STARTING POINT</h3>
          <div class="chapter-inline-dropdown">
	<select id="chapters-drodpown" onchange="document.location.href=this.options[this.selectedIndex].value" class="input-sm">
	<option value="">Select Chapter</option>
	<?php

        foreach ($chapters_list as $chapter) {
            /**
             * Get latest post for this chapter
             */
            $link_to_first_comic = '';
            $args = array(
                'posts_per_page' => 1,
                'post_parent' => $series_id,
                'post_type' => 'comic',
                'orderby' => 'post_date',
                'order' => 'ASC',
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
                $link_to_first_comic = add_query_arg('sid', $series_id, get_post_permalink()); // Display the image of the first post in category
                wp_reset_postdata();
                printf(wp_kses_data('%1$s'), '<option value="' . esc_url($link_to_first_comic) . '">');
                echo wp_kses_data($chapter->name);
                echo '</option>';
            endwhile;
        }
        ?>
	</select>
    </div>
	<?php
    }
endif;
?>