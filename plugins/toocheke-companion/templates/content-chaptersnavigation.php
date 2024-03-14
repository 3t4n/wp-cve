<?php
/**
 * Template part for chapters navigation
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toocheke
 */

$display_chapters_navigation = get_option('toocheke-chapter-navigation-buttons') && 1 == get_option('toocheke-chapter-navigation-buttons');
$display_default = get_option('toocheke-comics-navigation') && 1 == get_option('toocheke-comics-navigation');
$comic_order = get_option('toocheke-chapter-first-comic') ? get_option('toocheke-chapter-first-comic') : 'DESC';
$series_id = null;
$series_id = isset($_GET['sid']) ? (int) $_GET['sid'] : null;
if (get_query_var('series_id')) {
    $series_id = (int) get_query_var('series_id');
}
if ('series' === get_post_type()) {
    $series_id = get_the_ID();
}
$previous_chapter = toocheke_universal_get_previous_chapter();
$next_chapter = toocheke_universal_get_next_chapter();

?>

<?php
if ($display_chapters_navigation):

        ?>
<div class="chapter-inline-nav">
<a href="<?php echo esc_url($previous_chapter) ?>"
        class="float-left <?php echo empty($previous_chapter) ? 'd-none' : ''; ?>">
        <?php if ($display_default ): ?>
        <i class="fa fa-chevron-circle-left fa-lg"></i><?php else: ?>
        <img class="comic-image-nav" src="<?php echo esc_attr(get_option('toocheke-previous-chapter-button')) ?>" /><?php endif;?></a><span class="chapters-nav-label float-left <?php echo $display_default ? '' : 'd-none'; ?>">CHAPTERS</span>
    <a href="<?php echo esc_url($next_chapter) ?>"
        class="float-left <?php echo empty($next_chapter) ? 'd-none' : ''; ?>">
        <?php if ($display_default): ?>
        <i class="fa fa-chevron-circle-right fa-lg"></i>
        <?php else: ?>
        <img class="comic-image-nav" src="<?php echo esc_attr(get_option('toocheke-next-chapter-button')) ?>" />
        <?php endif;?>
    </a>
</div>
<?php


endif;
?>