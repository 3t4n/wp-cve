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

echo wp_kses_post( str_replace( 'class="', 'class="widget-fullwidth ', $exs_args['before_widget'] ) );
echo '<div class="widget-posts-gird layout-cols-' . esc_attr( $exs_columns . ' layout-' . $exs_layout . ' layout-gap-' . $exs_gap . ' ' . $exs_css_class . $exs_center_class ) . '">';
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
	<div class="grid-wrapper">
		<?php
		foreach ( $exs_r->posts as $exs_post ) :
			$exs_post_title     = get_the_title( $exs_post->ID );
			$exs_post_thumbnail = get_the_post_thumbnail( $exs_post->ID, 'large' );
			$exs_title          = ( ! empty( $exs_post_title ) ) ? $exs_post_title : esc_html__( '(no title)', 'exs' );
			?>
			<div class="grid-item">
				<article class="<?php echo esc_attr( ( ! empty( $exs_post_thumbnail ) ) ? 'has-post-thumbnail' : 'no-post-thumbnail' ); ?>">
					<?php if ( ! empty( $exs_post_thumbnail ) ) : ?>
						<a class="posts-list-thumbnail" href="<?php the_permalink( $exs_post->ID ); ?>">
							<?php
							echo wp_kses_post( $exs_post_thumbnail );
							function_exists( 'exs_post_format_icon') ? exs_post_format_icon( get_post_format( $exs_post->ID ) ) : '';
							?>
						</a>
					<?php endif; ?>
					<div class="item-content">
						<h3 class="post-title">
							<a href="<?php the_permalink( $exs_post->ID ); ?>"><?php echo wp_kses_post( $exs_title ); ?></a>
						</h3>
						<?php exs_widgets_categories_list( $exs_cats, $exs_post->ID ); ?>
						<?php if ( ! empty( $exs_show_date ) ) : ?>
							<footer class="entry-footer">
								<span class="icon-inline post-date">
									<?php function_exists( 'exs_icon' ) ? exs_icon( 'calendar' ) : ''; ?>
									<span><?php echo get_the_date( '', $exs_post->ID ); ?></span>
								</span>
							</footer>
						<?php endif; ?>
					</div>
				</article>
			</div><!-- .grid-item -->
		<?php endforeach; ?>
	</div><!-- .grid-wrapper -->
	<?php if ( ! empty( $exs_read_all ) ) : ?>
	<span class="read-all-link">
		<a href="<?php echo esc_url( $exs_read_all_url ); ?>">
			<?php echo esc_html( $exs_read_all ); ?>
		</a>
	</span>
	<?php endif; //$exs_read_all ?>
	</div><!-- .widget-posts-grid -->
<?php
echo wp_kses_post( $exs_args['after_widget'] );

