<?php
/**
 * File to handle customer login process.
 *
 * @package miniorange-saml-20-single-sign-on\handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class to handle customer registration and customer validation.
 */
class Mo_SAML_Customer_Login_Handler {
	/**
	 * This function registers the customer while signing up.
	 *
	 * @param array $post_array array of customer details.
	 * @param class $db_handler database handler.
	 * @return void
	 */
	public static function mo_saml_register_customer( $post_array, $db_handler ) {

		$new_registration = self::mo_saml_get_action_type( $post_array );

		if ( ! self::mo_saml_validate_customer_fields( $post_array, $new_registration ) ) {
			return;
		}

		$save_array = array();
		$save_array[ Mo_Saml_Customer_Constants::ADMIN_EMAIL ]    = $new_registration ? sanitize_email( $post_array[ Mo_Saml_Account_Setup_Constants::REGISTER_EMAIL ] ) : sanitize_email( $post_array[ Mo_Saml_Account_Setup_Constants::LOGIN_EMAIL ] );
		$save_array[ Mo_Saml_Customer_Constants::ADMIN_PASSWORD ] = stripslashes( sanitize_text_field( $post_array[ Mo_Saml_Account_Setup_Constants::CUSTOMER_PASSWORD ] ) );
		$db_handler->mo_saml_save_options( $save_array );

		$customer = new Mo_SAML_Customer();
		$response = '';

		if ( $new_registration ) {
			$content = json_decode( $customer->mo_saml_check_customer(), true );
			if ( ! is_null( $content ) ) {
				if ( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND' ) === 0 ) {
					$response = self::mo_saml_create_customer( $customer, $db_handler );
				} else {
					$response = self::mo_saml_get_current_customer( $customer, $db_handler, $new_registration );
				}
			}
		} else {
			$response = self::mo_saml_get_current_customer( $customer, $db_handler, $new_registration );
		}
	}

	/**
	 * This function deletes the account details of the customer.
	 *
	 * @return void
	 */
	public static function mo_saml_change_account() {
		$class_object = call_user_func( 'Mo_Saml_Customer_Constants::get_constants' );
		if ( ! is_multisite() ) {
			// delete all customer related key-value pairs.
			foreach ( $class_object as $key => $value ) {
				delete_option( $value );
			}
			delete_option( Mo_Saml_Options_Enum::SAML_MESSAGE );

		} else {
			$original_blog_id = get_current_blog_id();
			switch_to_blog( $original_blog_id );
			foreach ( $class_object as $key => $value ) {
				delete_option( $value );
			}
			delete_option( Mo_Saml_Options_Enum::SAML_MESSAGE );
		}
	}

	/**
	 * This function is used to describe the type of action according to post array.
	 *
	 * @param array $post_array User input array.
	 * @return bool
	 */
	public static function mo_saml_get_action_type( $post_array ) {
		if ( isset( $post_array[ Mo_Saml_Account_Setup_Constants::LOGIN_EMAIL ] ) ) {
			return false;
		}
		return true;
	}
	/**
	 * This function validates the all fields of the customer details.
	 *
	 * @param array $post_array Post array.
	 * @param bool  $new_registration new or old registration.
	 * @return bool
	 */
	public static function mo_saml_validate_customer_fields( $post_array, $new_registration ) {

		if ( $new_registration ) {
			if ( Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( $post_array[ Mo_Saml_Account_Setup_Constants::CONFIRM_PASSWORD ] ) ) ) {
				$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::FIELDS_EMPTY );
			} elseif ( strcmp( $post_array[ Mo_Saml_Account_Setup_Constants::CUSTOMER_PASSWORD ], $post_array[ Mo_Saml_Account_Setup_Constants::CONFIRM_PASSWORD ] ) !== 0 ) {
				$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::PASSWORD_MISMATCH );
			} elseif ( ! filter_var( $post_array[ Mo_Saml_Account_Setup_Constants::REGISTER_EMAIL ], FILTER_VALIDATE_EMAIL ) ) {
				$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::CONTACT_EMAIL_INVALID );
			}
		}

		if ( Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( $post_array[ Mo_Saml_Account_Setup_Constants::CUSTOMER_PASSWORD ] ) ) ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::FIELDS_EMPTY );
		} elseif ( self::mo_saml_check_password_pattern( sanitize_text_field( $post_array[ Mo_Saml_Account_Setup_Constants::CUSTOMER_PASSWORD ] ) ) ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::PASSWORD_PATTERN_INVALID );

		}

		if ( isset( $post_save ) ) {
			$post_save->mo_saml_post_save_action();
			return false;
		}
		return true;
	}
	/**
	 * This function Checks the pattern of user password.
	 *
	 * @param string $password password of user.
	 * @return bool
	 */
	public static function mo_saml_check_password_pattern( $password ) {
		$pattern = '/^[(\w)*(\!\@\#\$\%\^\&\*\.\-\_)*]+$/';
		return ! preg_match( $pattern, $password );
	}
	/**
	 * This function is to create a customer.
	 *
	 * @param object $customer customer details.
	 * @param object $db_handler database handler.
	 * @return mixed
	 */
	public static function mo_saml_create_customer( $customer, $db_handler ) {

		$customer_key = json_decode( $customer->mo_saml_create_customer(), true );
		if ( ! is_null( $customer_key ) ) {
			$response = array();
			if ( strcasecmp( $customer_key['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS' ) === 0 ) {
				$api_response       = self::mo_saml_get_current_customer( $customer, $db_handler, false );
				$response['status'] = $api_response ? 'success' : 'error';
			} elseif ( strcasecmp( $customer_key['status'], 'SUCCESS' ) === 0 ) {
				self::mo_saml_update_customer_details( $customer_key, $db_handler, true );
				$response['status'] = 'success';
			}

			update_option( Mo_Saml_Customer_Constants::ADMIN_PASSWORD, '' );
			return $response;
		}
		return false;
	}
	/**
	 * This function is to get current customer details.
	 *
	 * @param object $customer customer details.
	 * @param object $db_handler database handler.
	 * @param bool   $new_registration new or old registration.
	 * @return mixed
	 */
	public static function mo_saml_get_current_customer( $customer, $db_handler, $new_registration ) {

		$content = $customer->mo_saml_get_customer_key();

		if ( ! is_null( $content ) ) {
			$customer_key = json_decode( $content, true );

			if ( json_last_error() !== JSON_ERROR_NONE ) {
				$error_message = $new_registration ? Mo_Saml_Messages::ACCOUNT_EXISTS : Mo_Saml_Messages::INVALID_CREDENTIALS;
				$post_save     = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, $error_message );
				$post_save->mo_saml_post_save_action();

				update_option( Mo_Saml_Customer_Constants::ADMIN_PASSWORD, '' );
				$response['status'] = 'error';
				return $response;
			}

			self::mo_saml_update_customer_details( $customer_key, $db_handler, $new_registration );
			$response['status'] = 'success';
			return $response;
		}
		return false;
	}
	/**
	 * This function is to update customer details.
	 *
	 * @param array   $customer_key array of customer details.
	 * @param mixed   $db_handler database handler.
	 * @param boolean $new_registration new or old registration.
	 * @return void
	 */
	public static function mo_saml_update_customer_details( $customer_key, $db_handler, $new_registration = true ) {

		$save_array = array();
		$save_array[ Mo_Saml_Customer_Constants::CUSTOMER_KEY ]   = $customer_key['id'];
		$save_array[ Mo_Saml_Customer_Constants::API_KEY ]        = $customer_key['apiKey'];
		$save_array[ Mo_Saml_Customer_Constants::ADMIN_PASSWORD ] = '';

		$certificate = get_option( Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE );

		$db_handler->mo_saml_save_options( $save_array );

		$save_message = $new_registration ? Mo_Saml_Messages::REGISTER_SUCCESS : Mo_Saml_Messages::CUSTOMER_FOUND;
		$post_save    = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, $save_message );
		$post_save->mo_saml_post_save_action();
	}

}
