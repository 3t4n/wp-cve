<?php
/**
 * Dialog modals for single course page.
 *
 * This template can be overridden by copying it to yourtheme/masteriyo/single-course/modals.php.
 *
 * HOWEVER, on occasion Masteriyo will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package Masteriyo\Templates
 * @version 1.8.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="masteriyo-confirm-retake-course-modal-content masteriyo-hidden">
	<div class="masteriyo-overlay">
		<div class="masteriyo--modal masteriyo-modal-confirm-retake-course">
			<h4 class="masteriyo--title"><?php esc_html_e( 'Retake Course', 'masteriyo' ); ?></h4>
			<div class="masteriyo--content"><?php esc_html_e( 'Are you sure you want to retake the course? This action will permanently delete all your progress in this course.', 'masteriyo' ); ?></div>
			<div class="masteriyo-actions">
				<button class="masteriyo-btn masteriyo-btn-outline masteriyo-cancel"><?php esc_html_e( 'Cancel', 'masteriyo' ); ?></button>
				<button class="masteriyo-btn masteriyo-btn-warning masteriyo-confirm"><?php esc_html_e( 'Confirm', 'masteriyo' ); ?></button>
			</div>
		</div>
	</div>
</div>

<?php
