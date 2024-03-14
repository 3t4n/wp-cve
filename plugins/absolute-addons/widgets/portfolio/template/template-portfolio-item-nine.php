<?php
/**
 * Template for Portfolio Style Nine
 *
 * @package AbsoluteAddons
 */

/** @define "ABSOLUTE_ADDONS_WIDGETS_PATH" "./../../../widgets/" */

/**
 * @var array $settings
 * @var string $portfolio_terms
 * @var string $style
 * @var string $figure_link
 * @var string $figure_attr
 */

require ABSOLUTE_ADDONS_WIDGETS_PATH . 'portfolio/template/template-figure.php';
?>
<div class="portfolio-content-wrapper">
	<div class="portfolio-content">
		<?php the_terms( get_the_ID(), 'portfolio_category', '<span>', '', '</span>' ); ?>
		<?php if ( $figure_link ) { ?>
			<a href="<?php echo esc_url( $figure_link ); ?>">
				<h3><?php the_title() ?></h3>
			</a>
		<?php } else { ?>
			<h3><?php the_title() ?></h3>
		<?php } ?>
		<div class="post-like">
			<?php
			$liked_user_ids = get_post_meta( get_the_ID(), '_absp_like_user_ids', true );
			$liked_user_ids = empty( $liked_user_ids ) ? [] : $liked_user_ids;
			$liked_class    = in_array( get_current_user_id(), $liked_user_ids ) ? 'fas' : 'far';
			$is_disabled    = is_user_logged_in() ? '' : 'absp-disabled';
			?>
			<a href="#" class="absp-like-action <?php echo esc_attr( $is_disabled ); ?>" data-post-id="<?php the_ID(); ?>" data-nonce="<?php esc_attr( wp_create_nonce( 'absp-like' ) ); ?>">
				<i class="<?php echo esc_attr( $liked_class ); ?> fa-heart"></i>
				<?php esc_html_e( 'Likes', 'absolute-addons' ); ?>
			</a>
		</div>
	</div>
</div>
