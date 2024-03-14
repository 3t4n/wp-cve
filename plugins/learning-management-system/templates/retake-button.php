<?php
/**
 * "Retake Course" button.
 *
 * @version 1.8.0
*/

use Masteriyo\Enums\CourseProgressStatus;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! $course->is_purchasable() ) {
	return;
}

/**
 * Fires before rendering retake button.
 *
 * @since 1.8.0
 *
 * @param \Masteriyo\Models\Course $course Course object.
 */
do_action( 'masteriyo_before_retake_button', $course );

?>

<?php if ( masteriyo_can_start_course( $course ) ) : ?>
	<?php if ( $progress && CourseProgressStatus::COMPLETED === $progress->get_status() && $course->get_enable_course_retake() ) : ?>
		<a href="<?php echo esc_url( $course->get_retake_url() ); ?>" target="_blank" class="<?php echo esc_attr( $class ); ?>">
		<span title="<?php esc_html_e( 'Retake this course', 'masteriyo' ); ?>">
			<?php echo wp_kses_post( masteriyo_get_svg( 'course-retake', true ) ); ?>
	</span>
		</a>
	<?php endif; ?>
<?php endif; ?>
<?php

/**
 * Fires after rendering retake button.
 *
 * @since 1.8.0
 *
 * @param \Masteriyo\Models\Course $course Course object.
 */
do_action( 'masteriyo_after_retake_button', $course );
