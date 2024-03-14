<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //Exit if accessed directly
// phpcs:ignoreFile
STM_LMS_Course::course_views( get_the_ID() ); ?>
<?php get_header(); ?>
<?php STM_LMS_Templates::show_lms_template( 'modals/preloader' ); ?>
	<div class="<?php echo apply_filters( 'stm_lms_wrapper_classes', 'stm-lms-wrapper' ); ?>">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php do_action( 'stm-lms-content-' . get_post_type() ); ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>

<?php get_footer(); ?>

