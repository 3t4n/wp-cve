<?php

/**
 * Scorm learn page template content.
 *
 * @version 1.8.3
 */

use Masteriyo\Constants;

defined( 'ABSPATH' ) || exit;

$course_id = isset( $course_id ) ? $course_id : 0;

$course = masteriyo_get_course( $course_id );

if ( ! $course ) {
	return;
}

wp_enqueue_script( 'masteriyo-scorm-pipwerks', Constants::get( 'MASTERIYO_SCORM_URL' ) . 'assets/vendor/scorm/pipwerks.js', '', Constants::get( 'MASTERIYO_VERSION' ), true );
wp_enqueue_script( 'masteriyo-scorm-package', Constants::get( 'MASTERIYO_SCORM_URL' ) . 'assets/js/frontend/scorm.js', '', Constants::get( 'MASTERIYO_VERSION' ), true );
wp_enqueue_style( 'masteriyo-scorm-style', plugins_url( '/assets/css/public.css', Constants::get( 'MASTERIYO_PLUGIN_FILE' ) ), '', Constants::get( 'MASTERIYO_VERSION' ) );
wp_enqueue_style( 'masteriyo-scorm-style', plugins_url( '/assets/css/public.css', Constants::get( 'MASTERIYO_PLUGIN_FILE' ) ), array(), Constants::get( 'MASTERIYO_VERSION' ) );

wp_localize_script(
	'masteriyo-scorm-package',
	'_MASTERIYO_SCORM_COURSE_',
	array(
		'wp_rest_nonce' => wp_create_nonce( 'wp_rest' ),
		'restUrl'       => rest_url( 'masteriyo/v1/scorm' ),
	)
);

?>

<!DOCTYPE html>
<html lang="en" <?php echo is_rtl() ? 'dir="rtl"' : ''; ?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( get_the_title() ); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> translate="no">

	<?php

	/**
	 * Fires before rendering user Scorm learn page.
	 *
	 * @since 1.8.3
	 */
	do_action( 'masteriyo_before_scorm_learn_page_content' );

	?>
	<div id="masteriyo-interactive-scorm-course">

		<?php

		$scorm_header_file   = MASTERIYO_SCORM_DIR . '/templates/scorm-header.php';
		$scorm_template_file = MASTERIYO_SCORM_DIR . '/templates/scorm.php';

		if ( file_exists( $scorm_header_file ) ) {
			require_once $scorm_header_file;
		}

		if ( file_exists( $scorm_template_file ) ) {
			require_once $scorm_template_file;
		}

		/**
		 * Fires after main content of the Scorm learn page.
		 *
		 * @since 1.8.3
		 */
		do_action( 'masteriyo_after_scorm_learn_page_main_content' );
		?>
	</div>
	<?php

	/**
	 * Fires after rendering user Scorm learn page.
	 *
	 * @since 1.8.3
	 */
	do_action( 'masteriyo_after_scorm_learn_page_content' );

	wp_footer();
	?>
</body>

</html>
