<?php
/**
 * Controls the display of the_content in portfolio posts
 *
 * To override this template copy it to <your-theme>/themify-portfolio-posts/default.php and edit.
 *
 * @var $content the original content output of the post
 */
?>

<div class="tpp-project-info">
	<?php if ( $date = get_post_meta( get_the_ID(), 'project_date', true ) ) : ?>
		<p>
			<?php _e( 'Date:', 'themify-portfolio-post' ); ?> 
			<?php echo esc_html( $date ); ?>
		</p>
	<?php endif; ?>
	<?php if ( $client = get_post_meta( get_the_ID(), 'project_client', true ) ) : ?>
		<p>
			<?php _e( 'Client:', 'themify-portfolio-post' ); ?> 
			<?php echo esc_html( $client ); ?>
		</p>
	<?php endif; ?>
	<?php if ( $services = get_post_meta( get_the_ID(), 'project_services', true ) ) : ?>
		<p>
			<?php _e( 'Services:', 'themify-portfolio-post' ); ?> 
			<?php echo esc_html( $services ); ?>
		</p>
	<?php endif; ?>
	<?php if ( $launch = get_post_meta( get_the_ID(), 'project_launch', true ) ) : ?>
		<p>
			<?php _e( 'Launch:', 'themify-portfolio-post' ); ?> 
			<a href="<?php echo esc_url( $launch ); ?>"><?php echo esc_html( $launch ); ?></a>
		</p>
	<?php endif; ?>
</div>

<?php echo $content; ?>