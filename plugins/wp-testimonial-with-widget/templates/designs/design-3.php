<?php
/**
 * Design 3 Shortcodes HTML
 *
 * @package WP Testimonials with rotator widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="<?php echo esc_attr( $css_class ); ?>">
	<div class="wptww-testimonial-inner <?php if ( empty( $author_image ) ) { echo 'wptww-no-author-image'; } ?>">
		<?php if ( isset( $author_image ) && ( $display_avatar ) ) { ?>
			<div class="wptww-testimonial_avatar">
				<div class="wptww-avtar-image"><?php echo $author_image; ?></div>
			</div>
		<?php } ?>
		<div class="wptww-testimonial-author">
			<?php if( $display_client && ! empty( $author ) ) { ?>
				<div class="wptww-testimonial-client"><?php echo esc_html( $author ); ?></div>
			<?php }

			if( $display_job && ! empty( $job_title ) || $display_company && ! empty( $company ) ) { ?>
				<div class="wptww-testimonial-cdec">
				<?php 
					if( $display_job && ! empty( $job_title ) ) {
						echo esc_html( $job_title );
					}

					if( $display_job && ! empty( $job_title ) && $display_company && ! empty( $company ) ) { 
						echo " / ";
					}

					if( $display_company && ! empty( $company ) ) {
						echo '<a href="'.esc_url( $url ).'"> '.esc_html( $company ).' </a>';
					}
				?>
				</div>
			<?php } ?>
		</div>
		<div class="wptww-testimonial-content">
			<h4><?php echo esc_html( $testimonial_title ); ?></h4>
			<div class="wptww-testimonials-text">
				<p>
				<?php if( $display_quotes ) { ?> <em> <?php }
					echo get_the_content();
				if( $display_quotes ) { ?> </em> <?php } ?>
				</p>
			</div>
		</div>
	</div>
</div>