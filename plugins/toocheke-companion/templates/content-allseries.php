<?php
/**
 * Template part for displaying all series
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */

$series_order = get_option('toocheke-series-order') ? get_option('toocheke-series-order') : 'DESC';
$comic_order = get_option('toocheke-comics-order') ? get_option('toocheke-comics-order') : 'DESC';
if (post_type_exists('series')):
    /**
     * Setup query to show ALL ‘series’ posts
     * Output is thumbnail
     */
    $series_args = array(
        'post_type' => 'series',
        'post_status' => 'publish',
        'nopaging' => true,
        'orderby' => 'title',
        'order' => $series_order,
    );
    $series_query = new WP_Query($series_args);
    if ($series_query->have_posts()):
    ?>
	                <div id="series-grid" class="grid-container grid-three-cols">
	                    <?php
    while ($series_query->have_posts()): $series_query->the_post();
        $link_to_first_comic = get_permalink($post);
        $series_thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'medium') : esc_attr(plugins_url('toocheke-companion' . '/img/default-thumbnail-image.png'));
        $series_title = get_the_title();
        $series_excerpt = get_the_excerpt();
        $series_id = get_the_ID();
        $args = array(
            'post_parent' => $series_id,
            'posts_per_page' => 1,
            'post_type' => 'comic',
            'orderby' => 'post_date',
            'order' => $comic_order,
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );
        $first_comic_query = new WP_Query($args);
        while ($first_comic_query->have_posts()): $first_comic_query->the_post();
            $link_to_first_comic = add_query_arg('sid', $series_id, get_post_permalink()); // Display the image of the first post in category
        endwhile;
// Reset Post Data
        wp_reset_postdata();
        ?>
		                    <span class="series-thumbnail-wrapper">
		                        <a class="series-thumbnail" href="<?php echo esc_url( $link_to_first_comic); ?>">
                                <img
                                    src="<?php echo esc_attr($series_thumbnail_url); ?>" />
		                        </a>
		                        <div class="series-rollover">
		                            <a class="series-link" href="<?php echo esc_url($link_to_first_comic); ?>">
		                                <h3><?php echo  $series_title;?></h3>
		                                <?php echo $series_excerpt;?>
		                            </a>


		                        </div>
		                    </span>
		                    <?php
    endwhile;
    $series_query = null;
    wp_reset_postdata();
    ?>
	                </div>
	                <!--.series-grid-->
	                <?php
endif;
?>
                <?php
endif;
?>