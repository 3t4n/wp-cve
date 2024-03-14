<?php
/**
 * The Template for displaying google classroom result in single course page
 * @version 1.8.3
 */


defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Fires before rendering google classroom section in single course page.
 *
 * @since 1.8.3
 */
do_action( 'masteriyo_before_single_course_google_classroom' );
?>
<?php
if ( $course_code || $is_view_button ) :
	?>
<div class="masteriyo-course--google-classroom">
	<?php
	/**
	 * Action hook for rendering before course google_classroom content.
	 *
	 * @since 1.8.3
	 *
	 * @param \Masteriyo\Models\Course $course
	 */
	do_action( 'masteriyo_single_course_before_google_classroom_content', $course );
	?>

	<h2 class="masteriyo-course--google-classroom__heading">
		<?php echo esc_attr__( 'Google Classroom', 'masteriyo' ); ?>
	</h2>

	<?php
	if ( $course_code ) :
		?>
		<div class="masteriyo-course--google-classroom__code">
		<span class="masteriyo-course--google-classroom__code-label">
			Code: <span class="masteriyo-copy-this-text"><?php echo esc_attr( $course_code ); ?></span>
		</span>

		<div class="copy-button-code">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/>
				<path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
			</svg>
		</div>
	</div>
	<?php endif; ?>
	<?php if ( $is_view_button ) : ?>
	<button class='masteriyo-course-complete' data-course-id="<?php echo esc_attr( $course->get_id() ); ?>" type='submit'>
		<?php echo esc_attr__( 'Complete Course', 'masteriyo' ); ?>
	</button>
	<?php endif; ?>
</div>
<?php endif; ?>
<?php

/**
 * Fires after rendering google classroom section in single course page.
 *
 * @since 1.8.3
 */
do_action( 'masteriyo_after_single_course_google_classroom' );
