<?php
/**
 * Template part for displaying single comic
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */

$templates = new Toocheke_Companion_Template_Loader;
$display_comic_nav_above_comic = get_option('toocheke-comic-nav-above-comic') && 1 == get_option('toocheke-comic-nav-above-comic');
$latest_collection_id = 0;
$series_id = get_query_var('series_id');
$comic_order = 'DESC';
if (get_query_var('comic_order')) {
    $comic_order = get_query_var('comic_order');
}

$single_comics_args = array(
    'post_parent' => $series_id,
    'post_type' => 'comic',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'orderby' => 'post_date',
    'order' => $comic_order,

);

$single_comic_query = new WP_Query($single_comics_args);
?>
<?php
/* Start the Loop */
while ($single_comic_query->have_posts()): $single_comic_query->the_post();
    ?>
				<div id="comic" class="single-comic-wrapper">

				<?php
    if ($display_comic_nav_above_comic) {
        if (!$series_id) {
            set_query_var('series_id', null);
        } else {
            set_query_var('series_id', $series_id);
        }
        set_query_var('below_comic', 0);
        set_query_var('comic_order', $comic_order);
        $templates->get_template_part('content', 'traditionalcomicnavigation');
    }
    $comic_layout = get_option('toocheke-comic-layout-devices');
    $wrapper_id = $comic_layout === '1' ? 'two-comic-options' : 'one-comic-option';
    $allowed_tags = array(
        'img' => array(
            'src' => array(),
            'alt' => array(),
            'width' => array(),
            'height' => array(),
            'class' => array(),
        ),
    );

    echo '<div id="' . esc_attr($wrapper_id) . '">';
    echo '<div id="spliced-comic">';
    echo '<span class="default-lang">';
    the_content();
    echo '</span>';
    echo '</div>';
    echo '<div id="unspliced-comic">';

    echo '<span class="default-lang">';
    echo wp_kses(get_post_meta($post->ID, 'desktop_comic_editor', true), $allowed_tags);
    echo '</span>';

    echo '</div>';
    echo '</div>';

    if (!$series_id) {
        set_query_var('series_id', null);
    } else {
        set_query_var('series_id', $series_id);
    }
    set_query_var('below_comic', 1);
    set_query_var('comic_order', $comic_order);
    $templates->get_template_part('content', 'traditionalcomicnavigation');

    ?>

			</div>
			<?php
    $templates->get_template_part('content', 'comicblogpost');
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()):
            comments_template();
        endif;
/* End the Loop */
endwhile;
wp_reset_postdata();
