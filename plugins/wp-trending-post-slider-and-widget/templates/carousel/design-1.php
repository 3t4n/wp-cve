<?php
/**
 * 'wtpsw_carousel' Design 1 Shortcodes HTML
 *
 * @package WP Trending Post Slider and Widget
 * @since 1.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="wtpsw-post-carousel-slides">
	<div class="wtpsw-medium-12 wtpswcolumns">

		<div class="wtpsw-post-image-bg">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array(500,500 )); ?></a>
		</div>

		<h4 class="wtpsw-post-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h4>

		<?php if( $showdate == 'true' || $showauthor == 'true' || $show_comment_count == 'true' ) { ?>
			<div class="wtpsw-post-stats">
				<?php if( $showauthor == 'true' ) {
					$wtpsw_post_stats[] = "<span>".esc_html__( 'By', 'wtpsw' )." <a href='".get_author_posts_url( $post->post_author )."'>".get_the_author()."</a></span>";
				}

				if($showdate == "true") {
					$wtpsw_post_stats[] = "<span>".get_the_date()."</span>";
				}

				if( $show_comment_count == "true" && $comment_text ) {
					$wtpsw_post_stats[] = "<span class='wtpsw-post-comment'>".esc_attr($comment_text)."</span>";
				}

				echo join( ' / ', $wtpsw_post_stats ); ?>
			</div>
		<?php }

		if($showcontent == "true") {  ?>
		<div class="wtpsw-post-content">
			<div class="wtpsw-sub-content"><?php echo wtpsw_get_post_excerpt( get_the_content(), '', $words_limit ); ?></div>
		</div>
		<?php } ?>
	</div>
</div>