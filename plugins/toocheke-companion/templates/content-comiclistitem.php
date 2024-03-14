<?php
/**
 * Template part for displaying list of comics
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */
//show comic number only for singlre comic series
$companion = new Toocheke_Companion_Comic_Features();
$show_comic_number = is_front_page() && is_home();
$display_likes = get_option('toocheke-comic-likes') && 1 == get_option('toocheke-comic-likes');
$display_no_views = get_option('toocheke-comic-no-of-views') && 1 == get_option('toocheke-comic-no-of-views');
$rank = get_query_var('rank');
$series_id = get_query_var('series_id');
?>
<li id="post-<?php esc_attr(the_ID());?>" <?php wp_kses_data(post_class());?>>
<?php
$comic_url = get_permalink($post);
if ($series_id) {
    $comic_url = add_query_arg('sid', $series_id, $comic_url);
}
?>
       <?php if ('publish' === get_post_status($id)): ?>
<a href="<?php echo esc_url($comic_url); ?>">
<?php endif?>
                                 <div class="comic-item">
                                    <div class="thmb">
										<?php
if ($rank) {
    ?>
                                            <span class="rank"><?php echo esc_html($rank); ?></span>
                                            <?php
}
if (has_post_thumbnail()) {
    the_post_thumbnail('thumbnail');
} else {
    ?>
   <img src="<?php echo esc_attr(plugins_url('toocheke-companion' . '/img/default-thumbnail-image.png')); ?>" />
   <?php
}
?>

</div>
                                    <div class="comic-info">
                                    <?php
isset($show_comic_number) ? $comic_number = $show_comic_number ? "#" . wp_kses_data(get_post_meta($post->ID, 'incr_number', true)) . ". " : "" : "";

?>
                                   <div class="comic-title-wrapper">
                                   <p class="comic-title"><span class="comic-number"><?php echo wp_kses_data($comic_number) ?></span> <?php echo wp_kses_data(get_the_title()); ?></p>
</div>

                                    <p class="comic-list-item-details">
                                    <?php
if ($display_likes || $display_no_views) {
    ?>
                                        <span class="analytics-wrapper">
                                            <?php
if ($display_likes) {
        $no_of_likes = 0;
        if (get_post_meta($post->ID, "_post_like_count", true)) {
            $no_of_likes = get_post_meta($post->ID, "_post_like_count", true);
        }
        ?>
    <span  class="likes-wrapper"><i class='far fa-heart'></i> <?php echo wp_kses_data($no_of_likes) ?></span>
    <?php
}
    ?>
     <?php
if ($display_no_views) {
        ?>
<span class="views-wrapper">
               <i class="far fa-lg fa-eye" aria-hidden="true"></i> <?php echo wp_kses_data($companion->toocheke_universal_get_post_views($post->ID)); ?>
</span>
<?php
}
    ?>
</span>
<?php
}
?>
                                    <span class="comic-post-date">
                                       <?php echo get_the_date(); ?>
                                       </span>

                                    </p>
</div>
                                 </div>
                                 <?php if ('publish' === get_post_status($id)): ?>
                              </a>
                              <?php endif?>
                           </li>
