<?php
/**
 * 'Trending List Widget' Design 1 Shortcodes HTML
 *
 * @package WP Trending Post Slider and Widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<li class="wtpsw-post-li">
	<?php if( $show_thumb ) { ?>
	<div class="wtpsw-post-thumb-left">
		<a href="<?php the_permalink(); ?>">
		<?php if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
			the_post_thumbnail( array(100, 100) );
		} ?>
		</a>
	</div>
	<?php } ?>

	<div class="wtpsw-post-thumb-right">
		<h6> <a class="wtpsw-post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>

		<?php if( $show_date ) { ?>
			<div class="wtpsw-date-post"><?php echo get_the_date(); ?></div>
		<?php } ?>

		<div class="wtpsw-post-stats">
			<?php if( $show_author ) {
				$wtpsw_stats[] = "<span class='wtpsw-post-author'>".esc_html__( 'By ', 'wtpsw' )."<a href='".get_author_posts_url( $post->post_author )."'>".get_the_author()."</a></span>";
			}

			if( $show_comment_count && $comment_text ) {
				$wtpsw_stats[] = "<span class='wtpsw-post-comment'>".esc_attr($comment_text)."</span>";
			}

			echo join( ' | ', $wtpsw_stats ); ?>
		</div>

		<?php if( $show_content ) { ?>
		<div class="wtpsw-post-cnt">
			<?php echo wtpsw_get_post_excerpt( $post->ID, null, $content_length );  ?>
		</div>
		<?php } ?>
	</div>
</li>