<?php
/**
 * Template part for displaying single comic
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */

$templates = new Toocheke_Companion_Template_Loader;
$display_comic_nav_above_comic = get_option('toocheke-comic-nav-above-comic') && 1 == get_option('toocheke-comic-nav-above-comic');
$comic_order = get_option('toocheke-comics-order') ? get_option('toocheke-comics-order') : 'DESC';
$series_id = get_query_var('series_id');
$companion = new Toocheke_Companion_Comic_Features();
$companion->toocheke_universal_set_post_views(get_the_ID());
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
    if (is_singular('comic')) {
        $templates->get_template_part('content', 'comicnavigation');
    }

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
echo get_the_content();
echo '</span>';
echo '</div>';
echo '<div id="unspliced-comic">';

echo '<span class="default-lang">';
echo wp_kses(get_post_meta($post->ID, 'desktop_comic_editor', true), $allowed_tags);
echo '</span>';

echo '</div>';
echo '</div>';

$templates->get_template_part('content', 'comicnavigation');

?>
</div>
<?php
$templates->get_template_part('content', 'comicblogpost');
?>
