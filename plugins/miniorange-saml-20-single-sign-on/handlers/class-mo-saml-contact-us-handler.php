<?php
/**
 * Responsible to validate the call setup details such as Customer email, call time, date, etc.
 * It also makes a Contact us API request.
 *
 * @package    miniorange-saml-20-single-sign-on\handlers
 * @author     miniOrange
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Responsible to handle i.e. validate as well as submit the call setup details such as email, call time, date, etc.
 */
class Mo_SAML_Contact_Us_Handler {
	/**
	 * Makes a Contact Us API request.
	 *
	 * @param  array $post_array Contains call setup fields.
	 * @return void
	 */
	public static function mo_saml_send_contact_us( $post_array ) {

		$call_setup = false;
		if ( ! empty( $post_array['saml_setup_call'] ) ) {

			if ( ! self::mo_saml_validate_call_setup_fields( $post_array ) ) {
				return;
			}

			$time_zone  = sanitize_text_field( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_TIMEZONE ] );
			$call_date  = sanitize_text_field( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_CALL_DATE ] );
			$call_time  = sanitize_text_field( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_CALL_TIME ] );
			$call_setup = true;
		}

		if ( ! self::mo_saml_validate_contact_us_fields( $post_array, $call_setup ) ) {
			return;
		}

		$email = sanitize_email( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_EMAIL ] );
		$query = sanitize_text_field( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_QUERY ] );
		$phone = sanitize_text_field( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_PHONE ] );

		if ( true === $call_setup ) {
			$local_timezone   = 'Asia/Kolkata';
			$call_datetime    = $call_date . $call_time;
			$convert_datetime = strtotime( $call_datetime );
			$ist_date         = new DateTime( gmdate( 'Y-m-d H:i:s', $convert_datetime ), new DateTimeZone( $time_zone ) );

			$ist_date->setTimezone( new DateTimeZone( $local_timezone ) );
			$query = $query . '<br><br>Meeting Details: (' . $time_zone . ') ' . gmdate( 'd M, Y  H:i', $convert_datetime ) . ' [IST Time -> ' . $ist_date->format( 'd M, Y  H:i' ) . ']';
		} else {
			$query = $query;
		}

		$customer = new Mo_SAML_Customer();
		$response = $customer->mo_saml_submit_contact_us( $email, $phone, $query, $call_setup );

		if ( ! is_null( $response ) && false !== $response && 'Query submitted.' === $response ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, Mo_Saml_Messages::QUERY_SUBMITTED );
		} else {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::QUERY_NOT_SUBMITTED );
		}

		$post_save->mo_saml_post_save_action();
	}
	/**
	 * Validates all the contact us form fields such as customer email, customer query, etc.
	 *
	 * @param  array $post_array Contains call setup fields.
	 * @param  bool  $call_setup Determines whether the call setup field is selected or not.
	 * @return bool
	 */
	public static function mo_saml_validate_contact_us_fields( $post_array, $call_setup ) {

		$validate_fields_array = array( sanitize_email( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_EMAIL ] ), sanitize_text_field( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_QUERY ] ) );
		if ( true === $call_setup ) {
			$validate_fields_array = array( sanitize_email( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_EMAIL ] ) );
		}

		if ( Mo_SAML_Utilities::mo_saml_check_empty_or_null( $validate_fields_array ) ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::CONTACT_EMAIL_EMPTY );
		} elseif ( ! filter_var( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_EMAIL ], FILTER_VALIDATE_EMAIL ) ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::CONTACT_EMAIL_INVALID );
		}
		if ( isset( $post_save ) ) {
			$post_save->mo_saml_post_save_action();
			return false;
		}
		return true;
	}
	/**
	 * Validates the call setup fields.
	 *
	 * @param  array $post_array Contains call setup fields such as call time, call date etc.
	 * @return bool
	 */
	public static function mo_saml_validate_call_setup_fields( $post_array ) {
		$validate_fields_array = array( sanitize_text_field( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_CALL_TIME ] ), sanitize_text_field( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_CALL_DATE ] ), sanitize_text_field( $post_array[ Mo_Saml_Contact_Us_Constants::CUSTOMER_TIMEZONE ] ) );

		if ( Mo_SAML_Utilities::mo_saml_check_empty_or_null( $validate_fields_array ) ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::CALL_SETUP_DETAILS_EMPTY );
		}

		if ( isset( $post_save ) ) {
			$post_save->mo_saml_post_save_action();
			return false;
		}
		return true;
	}

}
