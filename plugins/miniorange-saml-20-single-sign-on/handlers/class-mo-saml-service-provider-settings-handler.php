<?php
/**
 * Handles the form submission for the Service Provider Setup tab.
 *
 * @package miniorange-saml-20-single-sign-on\handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Form handler class for the Service Provider Setup tab.
 */
class Mo_SAML_Service_Provider_Settings_Handler {

	/**
	 * Parses the Service Provider Setup form data and saves the configuration in the database.
	 *
	 * @param array           $post_array Contains the complete form post data.
	 * @param DatabaseHandler $db_handler The database handler for saving the data.
	 * @return void
	 */
	public static function mo_saml_service_provider_save_settings( $post_array, $db_handler ) {

		if ( ! self::mo_saml_validate_service_provider_fields( $post_array ) ) {
			return;
		}

		$save_array = array();
		$save_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME ]    = sanitize_text_field( trim( $post_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME ] ) );
		$save_array[ Mo_Saml_Options_Enum_Service_Provider::LOGIN_URL ]        = sanitize_text_field( trim( $post_array[ Mo_Saml_Options_Enum_Service_Provider::LOGIN_URL ] ) );
		$save_array[ Mo_Saml_Options_Enum_Service_Provider::ISSUER ]           = sanitize_text_field( trim( $post_array[ Mo_Saml_Options_Enum_Service_Provider::ISSUER ] ) );
		$save_array[ Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ] = $post_array[ Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ];
		$save_array[ Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ] = self::mo_saml_parse_saml_certificates( $save_array[ Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ] );

		if ( ! $save_array[ Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ] ) {
			return;
		}

		if ( ! empty( $post_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME ] ) ) {
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME ] = sanitize_text_field( $post_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME ] );
		}

		if ( ! empty( $post_array[ Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ] ) ) {
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ] = 'checked';
		} else {
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ] = 'unchecked';
		}

		if ( ! empty( $post_array[ Mo_Saml_Options_Enum_Service_Provider::ASSERTION_TIME_VALIDITY ] ) ) {
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::ASSERTION_TIME_VALIDITY ] = 'checked';
		} else {
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::ASSERTION_TIME_VALIDITY ] = 'unchecked';
		}
		$save_array[ Mo_Saml_Options_Enum_Sso_Login::SSO_BUTTON ] = 'true';
		$db_handler->mo_saml_save_options( $save_array );
		$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, Mo_Saml_Messages::IDP_DETAILS_SUCCESS, 'SERVICE_PROVIDER_CONF', $save_array );
		$post_save->mo_saml_post_save_action();

		$mo_saml_identity_provider_identifier_name = get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME );

		if ( ! empty( $mo_saml_identity_provider_identifier_name ) ) {

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading GET parameter to check the current tab name doesn't require nonce verification.
			if ( ! empty( Mo_Saml_Options_Plugin_Idp::$idp_list[ $mo_saml_identity_provider_identifier_name ] ) && ( ! isset( $_GET['tab'] ) || 'addons' !== $_GET['tab'] ) ) {
				update_option( Mo_Saml_Sso_Constants::MO_SAML_CLOSE_NOTICE, '0' );
			} else {
				update_option( Mo_Saml_Sso_Constants::MO_SAML_CLOSE_NOTICE, '1' );
			}
		}
	}

	/**
	 * This function takes care of validating and sanitizing the X.509 Certificate.
	 *
	 * @param array $saml_x509_certificate An array of certificates configured in the Service Provider Setup tab.
	 * @return mixed
	 */
	public static function mo_saml_parse_saml_certificates( $saml_x509_certificate ) {
		foreach ( $saml_x509_certificate as $key => $value ) {
			if ( empty( $value ) ) {
				unset( $saml_x509_certificate[ $key ] );
			} else {
				$saml_x509_certificate[ $key ] = Mo_SAML_Utilities::mo_saml_sanitize_certificate( $value );

				// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- Silenced this to avoid any additional warning messages if an invalid certificate is uploaded.
				if ( ! @openssl_x509_read( $saml_x509_certificate[ $key ] ) ) {
					$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::INVALID_CERT, 'INVALID_CERT' );
					$post_save->mo_saml_post_save_action();
					return false;
				}
			}
		}
		$saml_x509_certificate = maybe_serialize( $saml_x509_certificate );

		return $saml_x509_certificate;
	}

	/**
	 * Takes care of validating the input fields from the Service Provider Setup tab.
	 *
	 * @param array $post_array Contains the form post data.
	 * @return boolean
	 */
	public static function mo_saml_validate_service_provider_fields( $post_array ) {
		$validate_fields_array = array(
			$post_array[ Mo_Saml_Options_Enum_Service_Provider::LOGIN_URL ],
			$post_array[ Mo_Saml_Options_Enum_Service_Provider::ISSUER ],
			$post_array[ Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ],
		);

		if ( '0' !== $post_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME ] ) {
			array_push( $validate_fields_array, $post_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME ] );
		}
		if ( Mo_SAML_Utilities::mo_saml_check_empty_or_null( $validate_fields_array ) ) {
			$log_object = array(
				Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME => $post_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME ],
				Mo_Saml_Options_Enum_Service_Provider::LOGIN_URL     => $post_array[ Mo_Saml_Options_Enum_Service_Provider::LOGIN_URL ],
				Mo_Saml_Options_Enum_Service_Provider::ISSUER        => $post_array[ Mo_Saml_Options_Enum_Service_Provider::ISSUER ],
			);
			$post_save  = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::FIELDS_EMPTY, 'INVALID_CONFIGURATION_SETTING', $log_object );
		} elseif ( ! preg_match( '/^\w*$/', $post_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME ] ) ) {
			$log_object = array( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME => $post_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME ] );
			$post_save  = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::INVALID_FORMAT, 'INVALID_IDP_NAME_FORMAT', $log_object );
		}
		if ( isset( $post_save ) ) {
			$post_save->mo_saml_post_save_action();
			return false;
		}
		return true;
	}

}
