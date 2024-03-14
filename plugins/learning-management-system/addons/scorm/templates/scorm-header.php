<?php

/**
 * Scorm learn page header template content.
 *
 * @version 1.8.3
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="masteriyo-scorm-course-header">
	<div class="masteriyo-scorm-course-header__course">
		<span class="masteriyo-scorm-course-header__course-name"><?php esc_html_e( 'Course Name:', 'masteriyo' ); ?></span>
		<h5 class="masteriyo-scorm-course-header__course-title"><?php echo esc_html( $course->get_title() ); ?></h5>
	</div>

	<a href="<?php echo esc_url( $course->get_permalink() ); ?>" class="masteriyo-scorm-course-header__button-exit"><?php esc_html_e( 'Exit', 'masteriyo' ); ?></a>
</div>
