<?php
/** The file contains the functions to handle metadata uploading via URL or file and handling the errors.
 *
 * @package     miniorange-saml-20-single-sign-on\handlers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require 'class-mo-saml-post-save-handler.php';

/**
 * Mo_SAML_Upload_Metadata_Handler Class to upload metadata file and getting IdP Metadata.
 */
class Mo_SAML_Upload_Metadata_Handler {

	/**
	 * Metadata type.
	 *
	 * @access   private
	 * @var      string $metadata_type Metadata type.
	 */
	private static $metadata_type;

	/**
	 * This function is used for uploading metadata.
	 *
	 * @param array  $post_array Metadata Forms value.
	 * @param array  $file_array Metadata File.
	 * @param object $db_handler DB Handler Object.
	 */
	public static function mo_saml_upload_metadata( $post_array, $file_array, $db_handler ) {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$metadata_file_empty      = empty( $file_array[ Mo_Saml_Options_Enum_Metadata_Upload::METADATA_FILE ] ) ? true : false;
		$metadata_file_name_empty = Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( $file_array[ Mo_Saml_Options_Enum_Metadata_Upload::METADATA_FILE ]['tmp_name'] ) );
		$metadata_url_empty       = Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( $post_array[ Mo_Saml_Options_Enum_Metadata_Upload::METADATA_URL ] ) );

		if ( ! self::mo_saml_validate_metadata_fields( $post_array, $metadata_file_empty, $metadata_url_empty, $metadata_file_name_empty ) ) {
			return;
		}

		self::mo_saml_set_metadata_type( $metadata_url_empty );
		$file = self::mo_saml_get_file_contents( $post_array, $file_array );

		if ( Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( $file ) ) ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::INVALID_METADATA_CONFIG, 'UPLOAD_METADATA_INVALID_CONFIGURATION' );
			$post_save->mo_saml_post_save_action();
			return;
		}

		self::mo_saml_handle_upload_metadata( $file, $post_array, $db_handler );
	}

	/**
	 * This function is used for validating the metadata fields.
	 *
	 * @param array $post_array               Metadata Forms value.
	 * @param bool  $metadata_file_empty      Metadata File Empty or Not.
	 * @param bool  $metadata_url_empty       Metadata URL Empty or Not.
	 * @param bool  $metadata_file_name_empty Metadata File name Empty or Not.
	 */
	public static function mo_saml_validate_metadata_fields( $post_array, $metadata_file_empty, $metadata_url_empty, $metadata_file_name_empty ) {

		if ( '0' !== $post_array[ Mo_Saml_Options_Enum_Metadata_Upload::IDENTITY_PROVIDER_NAME ] && Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( $post_array[ Mo_Saml_Options_Enum_Metadata_Upload::IDENTITY_PROVIDER_NAME ] ) ) ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::IDP_NAME_EMPTY, 'IDP_NAME_EMPTY' );

		} elseif ( ! preg_match( '/^\w*$/', $post_array[ Mo_Saml_Options_Enum_Metadata_Upload::IDENTITY_PROVIDER_NAME ] ) ) {
			$log_object = array( Mo_Saml_Options_Enum_Metadata_Upload::IDENTITY_PROVIDER_NAME => $post_array[ Mo_Saml_Options_Enum_Metadata_Upload::IDENTITY_PROVIDER_NAME ] );
			$post_save  = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::INVALID_IDP_NAME_FORMAT, 'INVAILD_IDP_NAME_FORMAT', $log_object );
		} elseif ( 'false' === $metadata_file_empty && $metadata_file_name_empty ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::METADATA_NAME_EMPTY, 'UPLOAD_METADATA_NAME_EMPTY' );

		} elseif ( $metadata_file_empty && $metadata_url_empty ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::METADATA_EMPTY, 'UPLOAD_METADATA_EMPTY' );
		}

		if ( isset( $post_save ) ) {
			$post_save->mo_saml_post_save_action();
			return false;
		}
		return true;
	}

	/**
	 * This function is used to verify the type of metadata URL or file.
	 *
	 * @param string $metadata_url_empty Metadata type file or url.
	 */
	public static function mo_saml_set_metadata_type( $metadata_url_empty ) {
		self::$metadata_type = 'file';
		if ( ! $metadata_url_empty ) {
			self::$metadata_type = 'url';
		}
	}

	/**
	 * This function is used to get metadata file content.
	 *
	 * @param array $post_array Metadata type file or url.
	 * @param array $file_array Metadata type file or url.
	 */
	public static function mo_saml_get_file_contents( $post_array, $file_array ) {
		if ( 'file' === self::$metadata_type ) {
			if ( isset( $_FILES[ Mo_Saml_Options_Enum_Metadata_Upload::METADATA_FILE ]['tmp_name'] ) ) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Cannot unslash file path.
				$metadata_file = sanitize_text_field( $_FILES[ Mo_Saml_Options_Enum_Metadata_Upload::METADATA_FILE ]['tmp_name'] );
			}
			$file = Mo_SAML_Utilities::mo_safe_file_get_contents( $metadata_file );
			Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'UPLOAD_METADATA_SUCCESS' ), Mo_SAML_Logger::DEBUG );
		} else {
			$url = filter_var( $post_array[ Mo_Saml_Options_Enum_Metadata_Upload::METADATA_URL ], FILTER_SANITIZE_URL );
			Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'UPLOAD_METADATA_URL', array( 'url' => $url ) ), Mo_SAML_Logger::INFO );

			$response = Mo_SAML_Utilities::mo_saml_wp_remote_get( $url, array( 'sslverify' => false ) );
			if ( ! empty( $response ) && isset( $response ) ) {
				$file = $response['body'];
				Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'UPLOAD_METADATA_SUCCESS_FROM_URL' ), Mo_SAML_Logger::INFO );
			} else {
				$file = null;
				Mo_SAML_Logger::mo_saml_add_log( Mo_Saml_Error_Log::mo_saml_write_message( 'UPLOAD_METADATA_ERROR_FROM_URL' ), Mo_SAML_Logger::ERROR );
			}
		}
		return $file;
	}

	/**
	 * This function is for handling and uploading metadata.
	 *
	 * @param string $file Metadata XML value.
	 * @param array  $post_array Metadata Post data.
	 * @param object $db_handler DB Handler Object.
	 */
	public static function mo_saml_handle_upload_metadata( $file, $post_array, $db_handler ) {

		//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler -- We need this function to handle errors.
		$old_error_handler = set_error_handler( array( self::class, 'mo_saml_handle_xml_error' ) );
		$document          = new DOMDocument();
		$document->loadXML( $file );
		restore_error_handler();
		//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMDocumet has by default has the naming convention which is not SnakeCase.
		$first_child = $document->firstChild;
		if ( Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( $first_child ) ) ) {
			self::mo_saml_handle_empty_metadata_child();
			return;
		}

		$metadata           = new Mo_SAML_IDP_Metadata_Reader( $document );
		$identity_providers = $metadata->mo_saml_get_identity_providers();
		if ( Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( $identity_providers ) ) ) {
			self::mo_saml_handle_empty_metadata_idp_value();
			return;
		}

		$save_array = array();
		foreach ( $identity_providers as $key => $idp ) {
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME ] = sanitize_text_field( $post_array[ Mo_Saml_Options_Enum_Metadata_Upload::IDENTITY_PROVIDER_NAME ] );
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::LOGIN_URL ]     = $idp->mo_saml_get_login_url( 'HTTP-Redirect' );
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::ISSUER ]        = $idp->mo_saml_get_entity_id();

			// certs already sanitized in Metadata Reader.
			$saml_x509_certificate = $idp->mo_saml_get_signing_certificate();
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::X509_CERTIFICATE ] = maybe_serialize( $saml_x509_certificate );

			$save_array[ Mo_Saml_Options_Enum_Service_Provider::IS_ENCODING_ENABLED ]     = 'checked';
			$save_array[ Mo_Saml_Options_Enum_Service_Provider::ASSERTION_TIME_VALIDITY ] = 'checked';
			$save_array[ Mo_Saml_Options_Enum_Sso_Login::SSO_BUTTON ]                     = 'true';
			$db_handler->mo_saml_save_options( $save_array );
			break;
		}
		$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, Mo_Saml_Messages::METADATA_UPLOAD_SUCCESS, 'UPLOAD_METADATA_CONFIGURATION_SAVED', $save_array );
		$post_save->mo_saml_post_save_action();
	}

	/**
	 * This function is for checking if metadata contains empty values and handling them accordingly.
	 */
	public static function mo_saml_handle_empty_metadata_child() {
		if ( 'file' === self::$metadata_type ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::INVALID_METADATA_FILE, 'UPLOAD_METADATA_INVALID_FILE' );
		} else {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::INVALID_METADATA_URL, 'UPLOAD_METADATA_INVALID_URL' );
		}

		$post_save->mo_saml_post_save_action();
	}

	/**
	 * This function is for checking if metadata contains empty file or URL.
	 */
	public static function mo_saml_handle_empty_metadata_idp_value() {
		if ( 'file' === self::$metadata_type ) {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::INVALID_METADATA_FILE, 'UPLOAD_METADATA_INVALID_FILE' );
		} else {
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, Mo_Saml_Messages::INVALID_METADATA_URL, 'UPLOAD_METADATA_INVALID_URL' );
		}

		$post_save->mo_saml_post_save_action();
	}

	/**
	 * This function is for handling XML file errors.
	 *
	 * @param string $errno   error no.
	 * @param string $errstr  error str.
	 * @param string $errfile error file.
	 * @param int    $errline error line.
	 */
	public static function mo_saml_handle_xml_error( $errno, $errstr, $errfile, $errline ) {
		if ( E_WARNING === $errno && ( substr_count( $errstr, 'DOMDocument::loadXML()' ) > 0 ) ) {
			return true;
		} else {
			return false;
		}
	}
}
