<?php
/**
 * Template part for displaying list of comics
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */
$randomNumber = rand();
$templates = new Toocheke_Companion_Template_Loader;
$companion = new Toocheke_Companion_Comic_Features();
$series_id = null;
$series_id = isset($_GET['sid']) ? (int) $_GET['sid'] : null;
$collection_id = 0;
$display_default = get_option('toocheke-comics-navigation') && 1 == get_option('toocheke-comics-navigation');
$display_random_button = get_option('toocheke-random-navigation') && 1 == get_option('toocheke-random-navigation');
$display_likes = get_option('toocheke-comic-likes') && 1 == get_option('toocheke-comic-likes');
$display_no_views = get_option('toocheke-comic-no-of-views') && 1 == get_option('toocheke-comic-no-of-views');
$display_no_of_comments = get_option('toocheke-comic-no-of-comments') && 1 == get_option('toocheke-comic-no-of-comments');
$allowed_tags = array(
    'a' => array(
        'class' => array(),
        'title' => array(),
        'href' => array(),
    ),
    'i' => array(
        'class' => array(),
    ),
    'img' => array(
        'class' => array(),
        'src' => array(),
    ),
);
//$random_url = home_url() . '/?random&amp;nocache=1&amp;post_type=comic&amp;r=' . $randomNumber;
$random_url = home_url() . '/random?r=' . $randomNumber;
$image_button_url = get_option('toocheke-random-button');
$button = $display_default ? '<i class="fas fa-lg fa-random"></i>' : '<img class="comic-image-nav" src="' . esc_attr($image_button_url) . '" />';
?>

<div class="single-comic-navigation">
    <?php echo wp_kses(toocheke_universal_get_comic_link('ASC', 'backward', $collection_id, $display_default, 'first', $series_id), $allowed_tags); ?>
    <?php echo wp_kses(toocheke_universal_adjacent_comic_link(get_the_ID(), $collection_id, 'previous', $display_default, $series_id), $allowed_tags); ?>
    <a style="<?php echo esc_attr($collection_id == 0 && null == $series_id && $display_random_button ? "" : "display:none") ?>"
        href="<?php echo esc_url($random_url); ?>"
        title="Random Comic"><?php echo wp_kses($button, $allowed_tags) ?></a>
    <?php echo wp_kses(toocheke_universal_adjacent_comic_link(get_the_ID(), $collection_id, 'next', $display_default, $series_id), $allowed_tags); ?>
    <?php echo wp_kses(toocheke_universal_get_comic_link('DESC', 'forward', $collection_id, $display_default, 'latest', $series_id), $allowed_tags); ?>
    <div id="chapter-navigation">
        <?php
$templates->get_template_part('content', 'chaptersdropdown');
$templates->get_template_part('content', 'chaptersnavigation');
?>
    </div>
    <div id="comic-analytics">
                  <?php
if ($display_no_of_comments) {
    ?>
<span class="single-comic-total-comments">
               <i class="far fa-lg fa-comment-dots" aria-hidden="true"></i><?php comments_number('0', '1', '%');?>
</span>
<?php
}
?>
     <?php
if ($display_no_views) {
    ?>
<span class="single-comic-total-views">
               <i class="far fa-lg fa-eye" aria-hidden="true"></i><?php echo wp_kses_data($companion->toocheke_universal_get_post_views(get_the_ID())); ?>
</span>
<?php
}
?>
                  <?php
if ($display_likes) {
    ?>
            <span class="single-comic-total-likes">
               <?php echo do_shortcode("[toocheke-like-button]"); ?>
</span>
<?php
}
?>
           
                  </div>

</div>