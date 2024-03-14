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

echo wp_kses_post( str_replace( 'class="', 'class="widget-fullwidth posts-featured ', $exs_args['before_widget'] ) );
echo '<div class="posts-featured ' . esc_attr( $exs_css_class . ' layout-' . $exs_layout . ' layout-gap-' . $exs_gap . ' ' . $exs_center_class ) . '">';
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
	<div class="posts-wrap d-grid grid-3-cols">
		<?php
		$exs_count = count( $exs_r->posts ) - 1;
		foreach ( $exs_r->posts as $exs_index => $exs_post ) :
			$exs_post_title     = get_the_title( $exs_post->ID );
			$exs_post_thumbnail = get_the_post_thumbnail( $exs_post->ID, 'thumbnail' );
			$exs_title          = ( ! empty( $exs_post_title ) ) ? $exs_post_title : esc_html__( '(no title)', 'exs' );

			//first and second elements is featured
			if ( 0 === $exs_index || 1 === $exs_index ) :
				?>
				<div class="posts-featured-item <?php echo esc_attr( ( ! empty( $exs_post_thumbnail ) ) ? 'has-post-thumbnail' : 'no-post-thumbnail' ); ?>">
					<?php if ( ! empty( $exs_post_thumbnail ) ) : ?>
					<div class="content-absolute">
						<a class="posts-list-thumbnail" href="<?php the_permalink( $exs_post->ID ); ?>">
							<?php
							echo get_the_post_thumbnail( $exs_post->ID, 'large' );
							function_exists( 'exs_post_format_icon') ? exs_post_format_icon( get_post_format( $exs_post->ID ) ) : '';
							?>
						</a>
						<?php endif; ?>
						<div class="overlap-content">
							<h3 class="post-title">
								<a href="<?php the_permalink( $exs_post->ID ); ?>"><?php echo wp_kses_post( $exs_title ); ?></a>
							</h3>
							<?php exs_widgets_categories_list( $exs_cats, $exs_post->ID ); ?>
							<?php if ( $exs_show_date ) : ?>
								<footer class="entry-footer">
									<span class="icon-inline post-date">
										<?php function_exists( 'exs_icon' ) ? exs_icon( 'calendar' ) : ''; ?>
										<span><?php echo get_the_date( '', $exs_post->ID ); ?></span>
									</span>
								</footer>
							<?php endif; ?>
						</div><!-- .overlap-content -->
						<?php if ( ! empty( $exs_post_thumbnail ) ) : ?>
					</div><!-- .content-absolute -->
				<?php endif; ?>
				</div><!-- .posts-featured-item -->
				<?php
				// not featured items
			else :
				//open UL for second post - first not featured post
				if ( 2 === $exs_index ) :
					?>
					<div class="widget-posts-secondary">
						<ul class="posts-list">
							<?php endif; //first not featured post ?>
							<li class="<?php echo esc_attr( ! empty( $exs_post_thumbnail ) ? 'list-has-post-thumbnail' : 'no-post-thumbnail' ); ?>">
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
										<span class="icon-inline post-date">
											<?php function_exists( 'exs_icon' ) ? exs_icon( 'calendar' ) : ''; ?>
											<span><?php echo get_the_date( '', $exs_post->ID ); ?></span>
										</span>
									<?php endif; ?>
								</div>
							</li>
						<?php
						endif; //0 === $exs_index || 1 === $exs_index
			if ( $exs_index === $exs_count && $exs_count > 1 ) :
				?>
						</ul><!-- .posts-list -->
					</div><!-- .posts-secondary -->
					<?php
				endif; //count
			endforeach;
		?>
		</div><!-- .posts-wrap -->
	<?php if ( ! empty( $exs_read_all ) ) : ?>
		<span class="read-all-link">
			<a href="<?php echo esc_url( $exs_read_all_url ); ?>">
				<?php echo esc_html( $exs_read_all ); ?>
			</a>
		</span>
	<?php endif; //$exs_read_all ?>
	</div><!-- .posts-featured -->
<?php
echo wp_kses_post( $exs_args['after_widget'] );
