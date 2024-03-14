<?php
/**
 * The Template for displaying all single Give Forms.
 *
 * Override this template by copying it to yourtheme/give/single-give-forms.php
 *
 * @package       Give/Templates
 * @version       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
if ( is_active_sidebar( 'give-forms-sidebar' ) ) {
	$post_layout = 'quomodo-col-md-8';
}else{
	$post_layout = 'quomodo-col-md-10 quomodo-col-md-offset-1';
}
?>
<div class="give-content-area">
    <div class="quomodo-container">
        <div class="quomodo-row">
        	<div class="<?php echo esc_attr( $post_layout ); ?>">
			<?php
				while ( have_posts() ) : the_post();
					do_action( 'give_before_single_form' );
					if ( post_password_required() ) {
						echo get_the_password_form();
						return;
					}
				?>
					<div id="give-form-<?php the_ID(); ?>-content" <?php post_class(); ?>>
						<?php
							global $post;
							do_action( 'give_pre_featured_thumbnail' ); ?>
							<div class="images post-media">
								<?php
								if ( has_post_thumbnail() ) {

									$image_size = give_get_option( 'featured_image_size' );
									$image      = get_the_post_thumbnail( $post->ID, apply_filters( 'single_give_form_large_thumbnail_size', ( ! empty( $image_size ) ? $image_size : 'large' ) ) );
									echo wp_kses_post( apply_filters( 'single_give_form_image_html', $image ) );
								} else {
									echo wp_kses_post( apply_filters( 'single_give_form_image_html', sprintf( '<img src="%s" alt="%s" />', esc_url(give_get_placeholder_img_src()), esc_attr__( 'Placeholder', 'give' ) ), $post->ID ) );
								} ?>
							</div>

							<?php do_action( 'give_post_featured_thumbnail' ); ?>

							<div class="<?php echo apply_filters( 'give_forms_single_summary_classes', 'summary entry-summary' ); ?>">
								<?php do_action( 'give_single_form_summary' ); ?>
							</div>

							<?php

							do_action( 'give_after_single_form_summary' );
						?>
					</div>

				<?php

				do_action( 'give_after_single_form' );
				endwhile; ?>
			</div>
			<?php if ( is_active_sidebar( 'give-forms-sidebar' )) : ?>
				<div class="quomodo-col-md-4">
					<div class="widget-area">
						<?php dynamic_sidebar( 'give-forms-sidebar' ); ?>
			        </div>
	        	</div>
			<?php endif; ?>
        </div>
    </div>
</div>

<?php
get_footer();