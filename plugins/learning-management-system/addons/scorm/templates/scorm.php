<?php
/**
 * Scorm learn page main content template.
 *
 * @version 1.8.3
 *
 * @var $course_id
 */

defined( 'ABSPATH' ) || exit;

$scorm_url  = masteriyo_get_iframe_url( $course_id );
$scorm_meta = masteriyo_get_scorm_meta( $course_id );

if ( ! empty( $scorm_url ) ) : ?>
	<div class="masteriyo-course__overlay"></div>

	<div class="masteriyo-wrapper scorm">
		<iframe id="masteriyo-scorm-course-iframe"
				data-src="<?php echo esc_url( $scorm_url ); ?>"
				data-course-id="<?php echo esc_attr( $course_id ); ?>"
				data-scorm-version="<?php echo ( ! empty( $scorm_meta['scorm_version'] ) ) ? esc_attr( $scorm_meta['scorm_version'] ) : '1.2'; ?>"
		></iframe>
	</div>
	<?php
else :
	?>

	<?php
endif;
