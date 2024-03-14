<?php
/**
 * Posts Sidebar
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    PowerKit
 * @subpackage PowerKit/templates
 */

// when layout is not selected, used list.php
// but we don't need to print any html in this situation.
if ( ! isset( $attributes['layout'] ) || ! $attributes['layout'] ) {
	return;
}

$class_name = $attributes['canvasClassName'];

$class_name .= sprintf( ' cnvs-block-posts-%s', $attributes['layout'] );
?>
<div class="cnvs-block-posts-sidebar <?php echo esc_attr( $class_name ); ?>">
	<div class="cnvs-block-posts-sidebar-inner">
		<ul class="cnvs-posts-list">
			<?php
			while ( $posts->have_posts() ) {
				$posts->the_post();
				?>
				<li class="cnvs-post-item">
					<article <?php post_class(); ?>>

						<div class="cnvs-post-outer">

							<?php if ( has_post_thumbnail() ) { ?>
								<div class="cnvs-post-inner cnvs-post-thumbnail">
									<a href="<?php the_permalink(); ?>" class="post-thumbnail">
										<?php the_post_thumbnail( $attributes['imageSize'] ); ?>

										<?php if ( 'sidebar-numbered' === $attributes['layout'] ) : ?>
											<span class="cnvs-post-number">
												<?php echo esc_html( $posts->current_post + 1 ); ?>
											</span>
										<?php endif; ?>
									</a>
								</div>
							<?php } ?>

							<div class="cnvs-post-inner cnvs-post-data">
								<?php
								// Post Meta.
								cnvs_block_post_meta( $attributes, 'category' );
								?>

								<?php
								// Post Title.
								$tag = ( 'sidebar-large' === $attributes['layout'] ) ? 'h5' : 'h6';

								the_title( '<' . $tag . ' class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></' . $tag . '>' );

								// Post Meta.
								cnvs_block_post_meta( $attributes, cnvs_allowed_post_meta( true, 'category' ) );
								?>
							</div>

						</div>

					</article>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
</div>
