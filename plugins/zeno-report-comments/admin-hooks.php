<?php
/*
 * Admin Hooks for Zeno Report Comments.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Run action for compatibility with v1. All code was split up in v2 with this left over.
 *
 * @since 2.0.0
 */
function zeno_report_comments_backend_init() {

	do_action( 'zeno_report_comments_backend_init' );

}
add_action( 'admin_init', 'zeno_report_comments_backend_init' );


/*
 * Add example text to the privacy policy.
 *
 * @since 1.1.2
 */
function zeno_report_comments_add_privacy_policy_content() {

	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	$content = '<p>' . esc_html__( 'When visitors report a comment, the comment ID will be stored in a cookie in the browser. Also, the IP address will be saved temporarily in the database together with the number of reports.', 'zeno-report-comments' ) . '</p>';

	wp_add_privacy_policy_content(
		'Zeno Report Comments',
		wp_kses_post( wpautop( $content, false ) )
	);

}
add_action( 'admin_init', 'zeno_report_comments_add_privacy_policy_content' );


function zeno_report_comments_settings_fields() {

	// add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() )
	// register_setting( string $option_group, string $option_name, array $args = array() )

	add_settings_field( 'zrcmnt_enabled', esc_html__( 'Allow comment flagging', 'zeno-report-comments' ), 'zeno_report_comments_comment_flag_enable', 'discussion', 'default' );
	register_setting( 'discussion', 'zrcmnt_enabled' );

	$args = array( 'sanitize_callback' => 'intval' );
	add_settings_field( 'zrcmnt_threshold', esc_html__( 'Flagging threshold', 'zeno-report-comments' ), 'zeno_report_comments_comment_flag_threshold', 'discussion', 'default' );
	register_setting( 'discussion', 'zrcmnt_threshold', $args );

	add_settings_field( 'zrcmnt_admin_notification', esc_html__( 'Administrator notifications', 'zeno-report-comments' ), 'zeno_report_comments_comment_admin_notification_setting', 'discussion', 'default' );
	register_setting( 'discussion', 'zrcmnt_admin_notification' );

	add_settings_field( 'zrcmnt_admin_notification_each', esc_html__( 'Administrator notifications', 'zeno-report-comments' ), 'zeno_report_comments_comment_admin_notification_each_setting', 'discussion', 'default' );
	register_setting( 'discussion', 'zrcmnt_admin_notification_each' );

	add_settings_field( 'zrcmnt_spamcheck', esc_html__( 'Check for spambot protection', 'zeno-report-comments' ), 'zeno_report_comments_comment_spamcheck', 'discussion', 'default' );
	register_setting( 'discussion', 'zrcmnt_spamcheck' );

}
add_action( 'admin_init', 'zeno_report_comments_settings_fields' );


/*
 * Callback for settings field
 */
function zeno_report_comments_comment_flag_enable() {

	$enabled = (int) get_option( 'zrcmnt_enabled' );
	?>
	<label for="zrcmnt_enabled">
		<input name="zrcmnt_enabled" id="zrcmnt_enabled" type="checkbox" value="1" <?php checked( true, $enabled ); ?> />
		<?php esc_html_e( 'Allow your visitors to flag a comment as inappropriate.', 'zeno-report-comments' ); ?>
	</label>
	<?php

}


/*
 * Callback for settings field
 */
function zeno_report_comments_comment_flag_threshold() {

	$threshold = (int) get_option( 'zrcmnt_threshold' );
	?>
	<label for="zrcmnt_threshold">
		<input size="2" name="zrcmnt_threshold" id="zrcmnt_threshold" type="text" value="<?php echo esc_attr( $threshold ); ?>" />
		<?php esc_html_e( 'Amount of user reports needed to send a comment to moderation?', 'zeno-report-comments' ); ?>
	</label>
	<?php

}


/*
 * Callback for Discussions setting
 *
 * @since 1.0
*/
function zeno_report_comments_comment_admin_notification_setting() {

	$enabled = (int) get_option( 'zrcmnt_admin_notification', 1 );
	?>
	<label for="zrcmnt_admin_notification">
		<input name="zrcmnt_admin_notification" id="zrcmnt_admin_notification" type="checkbox" value="1" <?php checked( true, $enabled ); ?> />
		<?php esc_html_e( 'Send administrators an email when a user has sent a comment to moderation.', 'zeno-report-comments' ); ?>
	</label>
	<?php

}


/*
 * Callback for Discussions setting
 *
 * @since 1.0
 */
function zeno_report_comments_comment_admin_notification_each_setting() {

	$enabled = (int) get_option( 'zrcmnt_admin_notification_each', 1 );
	?>
	<label for="zrcmnt_admin_notification_each">
		<input name="zrcmnt_admin_notification_each" id="zrcmnt_admin_notification_each" type="checkbox" value="1" <?php checked( true, $enabled ); ?> />
		<?php esc_html_e( 'Send administrators an email each time a user has reported on a comment.', 'zeno-report-comments' ); ?>
	</label>
	<?php

}


/*
 * Callback for settings field
 */
function zeno_report_comments_comment_spamcheck() {

	$enabled = (int) get_option( 'zrcmnt_spamcheck', 1 );
	?>
	<label for="zrcmnt_spamcheck">
		<input name="zrcmnt_spamcheck" id="zrcmnt_spamcheck" type="checkbox" value="1" <?php checked( true, $enabled ); ?> />
		<?php esc_html_e( 'Add protection against spambots to the report button.', 'zeno-report-comments' ); ?>
	</label>
	<?php

}


/*
 * Add the report counter to comments screen.
 */
function zeno_report_comments_add_comment_reported_column( $comment_columns ) {

	$comment_columns['comment_reported'] = esc_html_x('Reported', 'column name', 'zeno-report-comments');
	return $comment_columns;

}
add_filter('manage_edit-comments_columns', 'zeno_report_comments_add_comment_reported_column', 90, 1 );
add_filter('manage_edit-comments_sortable_columns', 'zeno_report_comments_add_comment_reported_column', 90, 1 );


/*
 * Content for custom column on comments screen.
 *
 * @return none it is an action, not a filter, use echo.
 */
function zeno_report_comments_manage_comment_reported_column( $column_name, $comment_id ) {

	$comment_id = (int) $comment_id;

	if ( $column_name === 'comment_reported' ) {

		$settings = zeno_report_comments_get_settings();

		$already_moderated = (bool) zeno_report_comments_already_moderated( $comment_id );

		$reports = (int) get_comment_meta( $comment_id, 'zrcmnt_reported', true );

		echo '
			<span class="zeno-comments-report-moderate" id="zeno-comments-result-' . esc_attr( $comment_id ) . '">';

		if ( $reports > 0 ) {

			echo sprintf( _n( '%d report', '%d reports', (int) $reports, 'zeno-report-comments' ), (int) $reports );

			echo '
				<span class="row-actions">
					<a href="#" aria-label="' . esc_attr__( 'Moderate and remove reports.', 'zeno-report-comments' ) . '" title="' . esc_attr__( 'Moderate and remove reports.', 'zeno-report-comments' ) . '" data-zeno-comment-id="' . esc_attr( $comment_id ) . '">(' . esc_html__( 'allow and remove reports', 'zeno-report-comments' ) . ')</a>
				</span>
			';

		} else {
			echo (int) $reports;
		}
		if ( $already_moderated === true ) {
			echo esc_html( $settings['moderated_note'] );
		}

		echo '
			</span>';

	}

}
add_action('manage_comments_custom_column', 'zeno_report_comments_manage_comment_reported_column', 10, 2);


/*
 * Add the responses counter to comments screen.
 * It's not sortable, that was chaotic and made no sesne.
 */
function zeno_report_comments_add_comment_responses_column( $comment_columns ) {

	$comment_columns['comment_responses'] = esc_html_x('Responses', 'column name', 'zeno-report-comments');
	return $comment_columns;

}
add_filter('manage_edit-comments_columns', 'zeno_report_comments_add_comment_responses_column', 91, 1 );


/*
 * Content for custom column on comments screen.
 *
 * @return none it is an action, not a filter, use echo.
 */
function zeno_report_comments_manage_comment_responses_column( $column_name, $comment_id ) {

	$comment_id = (int) $comment_id;

	if ( $column_name === 'comment_responses' ) {

		$args = array(
			'status'       => 'all', // default.
			'parent'       => $comment_id,
			'hierarchical' => 'flat', // get all nested comments in a flat array, not in a sub-array for children.
			'fields'       => '', // needed for hierarchical to work.
			 );
		$comments = get_comments( $args );

		if ( is_array( $comments ) && ! empty( $comments ) ) {

			$count = count( $comments );
			echo sprintf( _n( '%d response', '%d responses', (int) $count, 'zeno-report-comments' ), (int) $count );

			foreach( $comments as $comment ) {
				echo '
				<span class="row-actions">
					<a href="' . admin_url( 'comment.php?action=editcomment&c=' ) . (int) $comment->comment_ID . '" aria-label="' . esc_attr__( 'View and edit response.', 'zeno-report-comments' ) . '" title="' . esc_attr__( 'View and edit response.', 'zeno-report-comments' ) . '" data-zeno-comment-id="' . (int) $comment->comment_ID . '">' . (int) $comment->comment_ID . '</a>
				</span><br />
			';

			}
		}

	}

}
add_action('manage_comments_custom_column', 'zeno_report_comments_manage_comment_responses_column', 11, 2);


function zeno_report_comments_admin_enqueue_scripts() {

	// Use home_url() if domain mapped to avoid cross-domain issues
	if ( home_url() !== site_url() ) {
		$ajaxurl = home_url( '/wp-admin/admin-ajax.php' );
	} else {
		$ajaxurl = admin_url( 'admin-ajax.php' );
	}
	$ajaxurl = apply_filters( 'zeno_report_comments_ajax_url', $ajaxurl );

	wp_enqueue_script( 'zrcmnt-admin-ajax-request', ZENORC_URL . '/js/admin-ajax.js', array( 'jquery' ), ZENORC_VER, true );

	$nonce = wp_create_nonce( 'zrcmnt_zeno_flag_comment_nonce' );
	$data_to_be_passed = array(
		'ajaxurl' => $ajaxurl,
		'nonce'   => $nonce,
	);
	wp_localize_script( 'zrcmnt-admin-ajax-request', 'zenocommentsajax', $data_to_be_passed );

}
add_action( 'admin_enqueue_scripts', 'zeno_report_comments_admin_enqueue_scripts' );


/*
 * Ajax callback on admin comments screen to moderate a comment.
 * AJAX action: zeno_report_comments_moderate_comment
 * This is on the main screen of comments in a column of this plugin.
 */
function zeno_report_comments_moderate_comment() {

	if ( ! current_user_can('moderate_comments') ) {
		echo 'error';
		die();
	}

	$settings = zeno_report_comments_get_settings();

	if ( ! isset( $_POST['comment_id'] ) || ! is_numeric( $_POST['comment_id'] ) || empty( $_POST['comment_id'] ) ) {
		zeno_report_comments_cond_die( $settings['invalid_values_message'] );
	}
	$comment_id = (int) $_POST['comment_id'];
	$nonce = '';
	if ( isset( $_POST['sc_nonce'] ) ) {
		$nonce = $_POST['sc_nonce'];
	}
	// Check for Nonce.
	if ( ! wp_verify_nonce( $nonce, 'zrcmnt_zeno_flag_comment_nonce' ) ) {
		zeno_report_comments_cond_die( $settings['invalid_nonce_message'] );
	} else {
		update_comment_meta( (int) $comment_id, 'zrcmnt_moderated', true );
		delete_comment_meta( (int) $comment_id, 'zrcmnt_reported' );
		wp_set_comment_status( (int) $comment_id, 'approve' );
		zeno_report_comments_cond_die( $settings['moderated_message'] );
	}

}
add_action( 'wp_ajax_zeno_report_comments_moderate_comment', 'zeno_report_comments_moderate_comment' );


/*
 * Mark a comment as being moderated so it will not be autoflagged again.
 * Remove the reports to clean up the database. Moderator decided already anyway.
 * This is on the main screen of comments in a column of WP Core.
 * This is also in the edit screen of a comment.
 * This hook is called in wp_transition_comment_status()
 *
 * @param $comment_id string The comment ID as a numeric string.
 * @param $comment WP_Comment Comment object.
 *
 * @since 2.0.0
 */
function zeno_report_comments_comment_approved_comment( $comment_id, $comment ) {

	update_comment_meta( (int) $comment_id, 'zrcmnt_moderated', true );
	delete_comment_meta( (int) $comment_id, 'zrcmnt_reported' );

}
add_action( 'comment_approved_comment', 'zeno_report_comments_comment_approved_comment', 10, 2 );
