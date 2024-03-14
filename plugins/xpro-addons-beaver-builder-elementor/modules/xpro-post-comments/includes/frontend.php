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
					<!-- comments layout -->
					<?php if ( ! comments_open() && FLBuilderModel::is_builder_active() ) : ?>
						<div class="xpro-alert xpro-alert-<?php echo esc_attr( $settings->comments_type ); ?>" role="alert">
							<span class="xpro-alert-title">
								<?php esc_attr_e( 'Comments are closed.', 'Addons' ); ?>
							</span>
							<span class="xpro-alert-description">
								<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'Addons' ); ?>
							</span>
						</div>
						<?php
					else :
						comments_template();
					endif;
					?>
					<!-- comments layout end -->
				</div>
			</div> <!-- layout end -->
		</div> <!-- xprowoo-themer-module-wrapper end -->
		<?php
		do_action( 'xpro_before_post' );
		wp_reset_postdata();
	endif;
}
?>
