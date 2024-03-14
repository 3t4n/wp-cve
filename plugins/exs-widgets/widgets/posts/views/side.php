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

echo wp_kses_post( str_replace( 'class="', 'class="widget-fullwidth posts-side ', $exs_args['before_widget'] ) );
echo '<div class="posts-side ' . esc_attr( 'layout-' . $exs_layout . ' ' . $exs_css_class . $exs_center_class ) . '">';
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
	<div class="posts-side-wrap">
		<?php
		while ( $exs_r->have_posts() ) :
			$exs_r->the_post();
			$exs_id             = get_the_ID();
			$exs_post_title     = get_the_title( $exs_id );
			$exs_post_thumbnail = get_the_post_thumbnail( $exs_id, 'thumbnail' );
			$exs_title          = ( ! empty( $exs_post_title ) ) ? $exs_post_title : esc_html__( '(no title)', 'exs' );
			?>
			<div class="posts-side-item <?php echo ( ! empty( $exs_post_thumbnail ) ) ? 'has-post-thumbnail side-item' : 'no-post-thumbnail'; ?>">
				<?php if ( ! empty( $exs_post_thumbnail ) ) : ?>
					<a class="posts-list-thumbnail" href="<?php the_permalink( $exs_id ); ?>">
						<?php
						echo get_the_post_thumbnail( $exs_id, 'large' );
						function_exists( 'exs_post_format_icon') ? exs_post_format_icon( get_post_format( $exs_id ) ) : '';
						?>
					</a>
				<?php endif; ?>
				<div class="item-content">
					<div class="entry-header">
						<h2 class="entry-title">
							<a href="<?php the_permalink( $exs_id ); ?>"><?php echo wp_kses_post( $exs_title ); ?></a>
						</h2>
					</div>
					<?php exs_widgets_categories_list( $exs_cats, $exs_id ); ?>
					<?php if ( $exs_show_date ) : ?>
						<footer class="entry-footer">
							<span class="icon-inline post-date">
								<?php function_exists( 'exs_icon' ) ? exs_icon( 'calendar' ) : ''; ?>
								<span><?php echo get_the_date( '', $exs_id ); ?></span>
							</span>
						</footer>
						<?php
					endif; //$exs_show_date
					the_excerpt();
					?>
				</div>
			</div><!-- .posts-side-item  -->
			<?php
		endwhile;
		wp_reset_postdata();
		if ( ! empty( $exs_read_all ) ) :
			?>
			<span class="read-all-link">
				<a href="<?php echo esc_url( $exs_read_all_url ); ?>">
					<?php echo esc_html( $exs_read_all ); ?>
				</a>
			</span>
		<?php endif; //$exs_read_all ?>
	</div><!-- .posts-side-wrap -->
	</div><!-- .post-side -->
<?php
echo wp_kses_post( $exs_args['after_widget'] );
