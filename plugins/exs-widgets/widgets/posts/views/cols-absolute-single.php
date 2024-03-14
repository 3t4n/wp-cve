<?php
/**
 * Widget Posts view file
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$exs_center_class = ( ! empty( $exs_text_center ) ) ? ' text-center' : '';

echo wp_kses_post( str_replace( 'class="', 'class="widget-fullwidth posts-single ', $exs_args['before_widget'] ) );
echo '<div class="posts-single ' . esc_attr( ' layout-' . $exs_layout . ' ' . $exs_css_class . $exs_center_class ) . '">';
if ( $exs_title ) {
	echo wp_kses_post( $exs_args['before_title'] . $exs_title . $exs_args['after_title'] );
}
if ( $exs_sub_title ) {
	echo '<p class="sub-title">' . wp_kses_post( $exs_sub_title ) . '</p><!-- .sub-title-->';
}
if ( ! empty( $exs_cat_name ) && ! empty( $exs_show_cat ) ) {
	echo '<h4 class="widget-posts-category-name"><span>' . wp_kses_post( $exs_cat_name ) . '</span></h4>';
}
?>
	<div class="posts-single-wrap">
		<?php
		while ( $exs_r->have_posts() ) :
			$exs_r->the_post();
			$exs_id             = get_the_ID();
			$exs_post_title     = get_the_title( $exs_id );
			$exs_title          = ( ! empty( $exs_post_title ) ) ? $exs_post_title : esc_html__( '(no title)', 'exs' );
			$exs_post_thumbnail = get_the_post_thumbnail( $exs_id, 'post-thumbnail' );
			$exs_post_class     = ( ! empty( $exs_post_thumbnail ) ) ? 'posts-single-item has-post-thumbnail content-absolute' : 'posts-single-item no-post-thumbnail';
			?>
			<article <?php post_class(); ?>>
				<div class="<?php echo esc_attr( $exs_post_class ); ?>">

					<?php if ( ! empty( $exs_post_thumbnail ) ) : ?>
						<a class="posts-list-thumbnail" href="<?php the_permalink( $exs_id ); ?>">
							<?php
							echo get_the_post_thumbnail( $exs_id, 'post-thumbnail' );
							function_exists( 'exs_post_format_icon') ? exs_post_format_icon( get_post_format( $exs_id ) ) : '';
							?>
						</a>
						<div class="overlap-content">
							<h3 class="post-title">
								<a href="<?php the_permalink( $exs_id ); ?>"><?php echo wp_kses_post( $exs_title ); ?></a>
							</h3>
							<?php exs_widgets_categories_list( $exs_cats, $exs_id ); ?>
							<?php if ( $exs_show_date ) : ?>
								<footer class="entry-footer">
									<span class="icon-inline">
										<?php function_exists( 'exs_icon' ) ? exs_icon( 'calendar' ) : ''; ?>
										<span><?php echo get_the_date( '', $exs_id ); ?></span>
									</span>
								</footer>
								<?php
								endif; //$exs_show_date
								the_excerpt();
							?>
						</div>
					<?php else : ?>
						<h3 class="post-title">
							<a href="<?php the_permalink( $exs_id ); ?>"><?php echo wp_kses_post( $exs_title ); ?></a>
						</h3>
						<?php if ( $exs_show_date ) : ?>
							<footer class="entry-footer">
							<span class="icon-inline">
								<?php function_exists( 'exs_icon' ) ? exs_icon( 'calendar' ) : ''; ?>
								<span><?php echo get_the_date( '', $exs_id ); ?></span>
							</span>
							</footer>
							<?php
						endif; //$exs_show_date
						the_excerpt();
						?>
					<?php endif; //$exs_post_thumbnail ?>
				</div><!-- <?php echo esc_attr( $exs_post_class ); ?> -->
			</article>
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div><!-- .posts-single-wrap -->
	<?php if ( ! empty( $exs_read_all ) ) : ?>
		<span class="read-all-link">
			<a href="<?php echo esc_url( $exs_read_all_url ); ?>">
				<?php echo esc_html( $exs_read_all ); ?>
			</a>
		</span>
	<?php endif; //$exs_read_all ?>
	</div><!-- .posts-single -->
<?php
echo wp_kses_post( $exs_args['after_widget'] );
