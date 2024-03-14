<?php

defined( 'ABSPATH' ) || exit();

/**
 * FRONTEND Help Dialog controller
 */
class EPHD_Help_Dialog_Front_Ctrl {

	public function __construct() {
		add_action( 'wp_ajax_ephd_help_dialog_contact', array( $this, 'submit_contact_form' ) );
		add_action( 'wp_ajax_nopriv_ephd_help_dialog_contact', array( $this, 'submit_contact_form' ) );
	}

	/**
	 * Contact Form Submission
	 */
	public function submit_contact_form() {

		// check wpnonce and prevent direct access
		EPHD_Utilities::ajax_verify_nonce_and_prevent_direct_access_or_error_die();

		// Spam checking
		// 1. Fake input field - do not proceed if is filled, return generic response
		if ( ! empty( $_REQUEST['catch_details'] ) ) {
			wp_send_json_success( esc_html__( 'Thank you. We will get back to you soon.', 'help-dialog' ) );
		}
		// 2. Check additional parameter that is set by our JS - do not proceed if is missed
		if ( empty( $_REQUEST['jsnonce'] ) || ! wp_verify_nonce( $_REQUEST['jsnonce'], '_wpnonce_ephd_ajax_action' ) ) {
			wp_send_json_success( esc_html__( 'Thank you. We will get back to you soon.', 'help-dialog' ) );
		}

		// get user submission
		$reply_to_email = EPHD_Utilities::post( 'email', '', 'email', EPHD_Submissions_DB::EMAIL_LENGTH );
		if ( empty( $reply_to_email ) || ! is_email( $reply_to_email ) ) {
			wp_send_json_error( esc_html__( 'Please enter a valid email address.', 'help-dialog' ) );
		}

		$widget_id = (int)EPHD_Utilities::post( 'widget_id', EPHD_Config_Specs::DEFAULT_ID );

		// retrieve contact form settings
		$widgets_config_handler = new EPHD_Widgets_DB();
		$widget_config = $widgets_config_handler->get_widget_config_by_id( $widget_id );
		if ( empty( $widget_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 226 ) );
			wp_die();
		}

		// retrieve global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 225, $global_config ) );
		}

		// get username
		$reply_to_name = $widget_config['contact_name_toggle'] == 'on'
			? EPHD_Utilities::post( 'user_first_name', '', 'text', EPHD_Submissions_DB::NAME_LENGTH )
			: 'N/A';
		if ( empty( $reply_to_name ) ) {
			wp_send_json_error( esc_html__( 'Please enter your name.', 'help-dialog' ) );
		}

		// Check acceptance box if enabled
		$acceptance = EPHD_Utilities::post( 'acceptance', null );
		if ( $widget_config['contact_acceptance_checkbox'] == 'on' && empty( $acceptance ) ) {
			wp_send_json_error( esc_html__( 'Acceptance checkbox is a required field', 'help-dialog' ) );
		}

		// get subject
		$subject = $widget_config['contact_subject_toggle'] == 'on'
			? EPHD_Utilities::post( 'subject', '', 'text', EPHD_Submissions_DB::SUBJECT_LENGTH )
			: 'N/A';

		$message = EPHD_Utilities::post( 'comment', '', 'text-area', EPHD_Submissions_DB::COMMENT_LENGTH );
		$page_id = (int)EPHD_Utilities::post( 'page_id' );
		if ( $page_id < 0 ) {
			EPHD_Logging::add_log( 'Invalid page id', $page_id );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 227 ) );
		}

		$page_name = EPHD_Utilities::post( 'page_name', '', 'text', EPHD_Submissions_DB::PAGE_NAME_LENGTH );
		$notification_status = 'received';
		$notification_details = '';

		// write record to the DataBse table
		$handler = new EPHD_Submissions_DB();
		$inserted_submission_id = $handler->insert_submission(
			$page_id,
			$page_name,
			date( 'Y-m-d H:i:s' ),
			$reply_to_name,
			$reply_to_email,
			$subject,
			$message,
			$handler::STATUS_EMAIL_PENDING,
			$notification_status,
			$notification_details,
			'' //FUTURE TODO EPHD_Core_Utilities::get_ip_address()
		);
		// record failure and try to send submission to admin
		if ( is_wp_error( $inserted_submission_id ) ) {
			EPHD_Logging::add_log( 'Failed to insert contact form submission', $inserted_submission_id );
			$inserted_submission_id = 0;
		}

		$submission_email_test = EPHD_Utilities::post( 'submission_email_test' );

		// allow test unsaved email
		if ( $submission_email_test ) {
			$global_config['contact_submission_email'] = $reply_to_email;
		}

		// send the email if user defined one
		$send_result = '';
		if ( empty( $global_config['contact_submission_email'] ) ) {
			$notification_status = 'no_submission_email';

		} else {

			$notification_status = $handler::STATUS_EMAIL_SENT;
			$email_message = '
			<html>
				<body>
					<table cellspacing="0" cellpadding="0" border="0" style="width: 100%;">
						<tbody>
							<tr style="background-color:#EAF2FA;">
								<td colspan="2" style="font-family: sans-serif; font-size:12px;padding:3px;"><strong>' . esc_html__( 'Name', 'help-dialog' ) . '</strong></td>
		   					</tr>
		   					<tr style="background-color:#FFFFFF;">
								<td width="20" style="padding:3px;">&nbsp;</td>
								<td style="font-family: sans-serif; font-size:12px;padding:3px;">' . sanitize_text_field( $reply_to_name ) . '</td>
		   					</tr>
		   					<tr style="background-color:#EAF2FA;">
								<td colspan="2" style="font-family: sans-serif; font-size:12px;padding:3px;"><strong>' . esc_html__( 'Email', 'help-dialog' ) . '</strong></td>
		   					</tr>
		   					<tr style="background-color:#FFFFFF;">
								<td width="20" style="padding:3px;">&nbsp;</td>
								<td style="font-family: sans-serif; font-size:12px;padding:3px;">' . sanitize_email( $reply_to_email ) . '</a></td>
		   					</tr>
		   					<tr style="background-color:#EAF2FA;">
								<td colspan="2" style="font-family: sans-serif; font-size:12px;padding:3px;"><strong>' . esc_html__( 'Subject', 'help-dialog' ) . '</strong></td>
		   					</tr>
		   					<tr style="background-color:#FFFFFF;">
								<td width="20" style="padding:3px;">&nbsp;</td>
								<td style="font-family: sans-serif; font-size:12px;padding:3px;">' . sanitize_text_field( $subject ) . '</td>
		   					</tr>
		   					<tr style="background-color:#EAF2FA;">
								<td colspan="2" style="font-family: sans-serif; font-size:12px;padding:3px;"><strong>' . esc_html__( 'Message', 'help-dialog' ) . '</strong></td>
		   					</tr>
		   					<tr style="background-color:#FFFFFF;">
								<td width="20" style="padding:3px;">&nbsp;</td>
								<td style="font-family: sans-serif; font-size:12px;padding:3px;">' . str_replace( [ "\r\n", '\r\n', "\n", '\n', "\r", '\r' ], '<br />', $message ) . '<br /></td>
		   					</tr>   
						</tbody>
					</table>
				</body>
			</html>';

			$subject = esc_html__( 'Help Dialog Submission', 'help-dialog' ) . ': ' . $subject;
			$send_result = EPHD_Utilities::send_email( $email_message, $global_config['contact_submission_email'], $reply_to_email, $reply_to_name, $subject, true  );
			if ( ! empty( $send_result ) ) {
				$notification_status = $handler::STATUS_EMAIL_ERROR;
				$notification_details = substr( $send_result, 0, EPHD_Submissions_DB::NOTIFICATION_DETAILS_LENGTH );
			}
		}

		$update_result = $handler->update_submission(
			$inserted_submission_id,
			$notification_status,
			$notification_details
		);

		if ( is_wp_error( $update_result ) ) {
			EPHD_Logging::add_log( 'Failed update submission after sending email', $update_result );
		}

		// let user know if we are not able to submit the email
		if ( ! empty( $send_result ) ) {
			wp_send_json_error( esc_html__( 'Sending the email failed due to your system mis-configuration.', 'help-dialog' ) . '<br>' . esc_html__( 'Error details:', 'help-dialog' ) . ' ' . $notification_details . '<br>' . esc_html__( 'Please talk to your administrator.', 'help-dialog' ) );
		}

		// is this a test of contact form submission
		if ( ! empty( $submission_email_test ) ) {
			if ( $notification_status == $handler::STATUS_EMAIL_ERROR ) {
				wp_send_json_error( esc_html__( 'Email Test: Failed to send Email.', 'help-dialog' ) ) . ' ' . $notification_details;
			} else {
				wp_send_json_success( esc_html__( 'Email Test: Email sent successfully.', 'help-dialog' )
					. ( EPHD_Utilities::post( 'is_email_unsaved' ) ? ' ' . esc_html__( 'Please do not forget to save the email address.', 'help-dialog' ) : '' ) );
			}
		}

		$widget_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Widgets_DB::EPHD_WIDGETS_CONFIG_NAME );
		wp_send_json_success( wp_kses( $widget_config['contact_success_message'], $widget_specs['contact_success_message']['allowed_tags'] ) );
	}
}