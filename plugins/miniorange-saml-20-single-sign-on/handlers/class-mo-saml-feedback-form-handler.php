<?php
/**
 * Handles the submission of the Feedback form.
 *
 * @package miniorange-saml-20-single-sign-on\handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Handler class for the feedback form. This class takes care of validating the feedback form data, making the feedback API request and performing post-feedback redirection.
 */
class Mo_SAML_Feedback_Form_Handler {

	/**
	 * Redirects to the Installed Plugin page with the correct message after the plugin is deactivated.
	 *
	 * @return void
	 */
	public static function mo_saml_skip_feedback() {
		deactivate_plugins( dirname( ( __DIR__ ) ) . '\login.php' );

		wp_safe_redirect( self_admin_url( 'plugins.php?deactivate=true' ) );
		exit;

	}

	/**
	 * Sends the feedback email based on the user input on the feedback form.
	 *
	 * @param array $post_array Contains the user input from the feedback form.
	 * @return void
	 */
	public static function mo_saml_send_feedback( $post_array ) {

		$email    = self::mo_saml_get_user_email( $post_array );
		$message  = self::mo_saml_get_feedback_message( $post_array );
		$phone    = get_option( Mo_Saml_Customer_Constants::ADMIN_PHONE );
		$customer = new Mo_SAML_Customer();

		$response = json_decode( $customer->mo_saml_send_email_alert( $email, $phone, $message ), true );

		deactivate_plugins( dirname( ( __DIR__ ) ) . '\login.php' );

		if ( ! self::mo_saml_validate_response( $response ) ) {
			return;
		}

		wp_safe_redirect( self_admin_url( 'plugins.php?deactivate=true' ) );
		exit;
	}

	/**
	 * Formats the feedback message for the feedback email.
	 *
	 * @param array $post_array Contains the user input from the feedback form.
	 * @return string
	 */
	public static function mo_saml_get_feedback_message( $post_array ) : string {
		$message                   = 'Plugin Deactivated';
		$rate_value                = isset( $post_array['rate'] ) ? $post_array['rate'] : '';
		$deactivate_reason_message = isset( $post_array['query_feedback'] ) ? sanitize_text_field( $post_array['query_feedback'] ) : false;
		$reply_required            = isset( $post_array['get_reply'] ) ? sanitize_text_field( $post_array['get_reply'] ) : '';
		$multisite_enabled         = is_multisite() ? 'True' : 'False';

		$message .= empty( $reply_required ) ? '<b style="color:red;"> &nbsp; [Reply : don\'t reply]</b>' : '[Reply : yes]';
		$message .= ', [Multisite enabled: ' . $multisite_enabled . ']';
		$message .= ', Feedback : ' . $deactivate_reason_message . '';
		$message .= ', [Rating :' . $rate_value . ']';

		return $message;
	}

	/**
	 * Fetches the user's email address for the feedback email.
	 *
	 * @param array $post_array Contains the user input from the feedback form.
	 * @return string
	 */
	public static function mo_saml_get_user_email( $post_array ) : string {
		if ( isset( $post_array['query_mail'] ) && filter_var( $post_array['query_mail'], FILTER_VALIDATE_EMAIL ) ) {
			$email = $post_array['query_mail'];
		} else {
			$email = get_option( Mo_Saml_Customer_Constants::ADMIN_EMAIL );
			if ( empty( $email ) ) {
				$user  = wp_get_current_user();
				$email = $user->user_email;
			}
		}
		return $email;
	}

	/**
	 * Validates the feedback API call response and displays the relevant message.
	 *
	 * @param array $response Contains the response from the feedback API call.
	 * @return bool
	 */
	public static function mo_saml_validate_response( $response ) : bool {
		if ( json_last_error() === JSON_ERROR_NONE ) {
			if ( ! empty( $response['status'] ) && Mo_Saml_Api_Status_Constants::ERROR === $response['status'] ) {
				$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, $response['message'] );
			} elseif ( false === $response ) {
				$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::QUERY_NOT_SUBMITTED );
			}
		}
		if ( isset( $post_save ) ) {
			$post_save->mo_saml_post_save_action();
			return false;
		}
		return true;
	}
}
