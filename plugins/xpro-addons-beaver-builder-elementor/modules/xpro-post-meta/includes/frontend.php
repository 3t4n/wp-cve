<?php
global $post;
if ( isset( $post_id ) && '' !== $post_id ) {
	$post_id = $module->get_post_id( $i );
	if ( $post_id ) :
		$post = get_post( $post_id, OBJECT );
		setup_postdata( $post );
		do_action( 'xpro_before_post' );
		?>
		<div id="xpro-post-<?php echo esc_attr( $post_id ); ?>" class="xpro-themer-module-wrapper clearfix">
			<div class="xpro-themer-module-layout-cls">
				<div class="xpro-themer-module-inner-cls">
					<!-- post meta layout -->
					<ul class="xpro-post-meta-cls">
						<?php if ( 'yes' === $settings->display_date ) : ?>
							<li class="xpro-post-meta-date"><?php the_time( 'F j, Y' ); ?></li>
						<?php endif; ?>

						<?php if ( ! empty( get_the_category_list() ) && 'yes' === $settings->display_taxonomy ) : ?>
							<li class="xpro-post-meta-category">
								<span class="xpro-post-meta-cat-links"><?php echo get_the_category_list( esc_html__( ', ', 'xpro' ) ); ?></span>
							</li>
						<?php endif; ?>

						<?php if ( 'yes' === $settings->display_comment ) : ?>
							<li class="xpro-post-meta-comment">
								<a href="<?php comments_link(); ?>">
									<?php comments_number( esc_html__( 'Leave A  Comment', 'xpro' ), esc_html__( '1 Comment', 'xpro' ), esc_html__( '% Comments', 'xpro' ) ); ?>
								</a>
							</li>
						<?php endif; ?>

						<?php if ( 'yes' === $settings->display_author ) : ?>
							<li class="xpro-post-meta-author">
								<?php esc_html_e( 'By', 'xpro' ); ?>
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a>
							</li>
						<?php endif; ?>
					</ul>
					<!-- post meta layout end -->
				</div>
			</div> <!-- layout end -->
		</div> <!-- xprowoo-themer-module-wrapper end -->
		<?php
		do_action( 'xpro_before_post' );
		wp_reset_postdata();
	endif;
}
?>
