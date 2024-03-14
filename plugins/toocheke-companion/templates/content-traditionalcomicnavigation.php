<?php
$randomNumber = rand();
$comic_order ='DESC';
if (get_query_var('comic_order')) {
    $comic_order = get_query_var('comic_order');
}
$templates = new Toocheke_Companion_Template_Loader;
$companion = new Toocheke_Companion_Comic_Features();
$latest_collection_id = 0;
$below_comic = null !== get_query_var('below_comic') ? (int) get_query_var('below_comic') : 0;
$display_default = get_option('toocheke-comics-navigation') && 1 == get_option('toocheke-comics-navigation');
$display_random_button = get_option('toocheke-random-navigation') && 1 == get_option('toocheke-random-navigation');
$display_likes = get_option('toocheke-comic-likes') && 1 == get_option('toocheke-comic-likes');
$display_no_views = get_option('toocheke-comic-no-of-views') && 1 == get_option('toocheke-comic-no-of-views');
$display_no_of_comments = get_option('toocheke-comic-no-of-comments') && 1 == get_option('toocheke-comic-no-of-comments');
$series_id = null;
$allow_home_comments = get_option('toocheke-comic-discussion');
$home_layout = get_theme_mod('home_layout_setting', 'default');
if('alt-1' === $home_layout || 'alt-2' === $home_layout  || 'alt-4' === $home_layout ){
    //toocheke_universal_set_post_views(get_the_ID());
}
if (get_query_var('series_id')) {
    $series_id = (int) get_query_var('series_id');
}

if ('series' === get_post_type()) {
    $series_id = get_the_ID();
}
?>
<div class="single-comic-navigation">
    <?php

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
    <?php
if ('DESC' === $comic_order) {
    echo wp_kses(toocheke_universal_get_comic_link('ASC', 'backward', $latest_collection_id, $display_default, 'first', $series_id), $allowed_tags);
    echo wp_kses(toocheke_universal_adjacent_comic_link(get_the_ID(), $latest_collection_id, 'previous', $display_default, $series_id), $allowed_tags);
}
?>

    <a style="<?php echo esc_attr(null == $series_id && $display_random_button ? "" : "display:none") ?>"
        href="<?php echo esc_url($random_url); ?>"
        title="Random Comic"><?php echo wp_kses($button, $allowed_tags) ?></i></a>
    <?php
                
if ('ASC' === $comic_order) {

    echo wp_kses(toocheke_universal_adjacent_comic_link(get_the_ID(), $latest_collection_id, 'next', $display_default, $series_id), $allowed_tags);
    echo wp_kses(toocheke_universal_get_comic_link('DESC', 'forward', $latest_collection_id, $display_default, 'latest', $series_id), $allowed_tags);

}
?>
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
               <i class="far fa-lg fa-eye" aria-hidden="true"></i><?php echo wp_kses_data($companion->toocheke_universal_get_post_views(get_the_ID()));?>
</span>
<?php
}
?>
		                  <?php
if ($display_likes):
?>
		            <span class="single-comic-total-likes">
		               <?php echo do_shortcode("[toocheke-like-button]"); ?>
		</span>
		<?php
endif;
?>
         

	                  </div>
</div>
<?php
if (($allow_home_comments === '1' && is_home() && $below_comic) || ($allow_home_comments === '1' && is_singular('series'))) {
    ?>
<div id="comments-wrapper" class="row">
    <div class="col-lg-12">
        <?php
global $withcomments;
    // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
    $withcomments = "1";
    comments_template();
    ?>
    </div>
</div>
<?php
}
?>