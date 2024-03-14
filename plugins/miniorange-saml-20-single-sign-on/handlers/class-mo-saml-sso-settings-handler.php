<?php
/**
 * Responsible for saving and updating the plugin configuration under the SSO Settings.
 *
 * @package    miniorange-saml-20-single-sign-on\handlers
 * @author     miniOrange
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Responsible for saving and updating the plugin configuration under the SSO Settings.
 */
class Mo_SAML_SSO_Settings_Handler {
	/**
	 * Responsible for saving and updating the plugin configuration under the SSO Settings.
	 *
	 * @param  array                    $post_array Stores SSO Button value.
	 * @param  Mo_SAML_Database_Handler $db_handler DB handler object.
	 * @return void
	 */
	public static function mo_saml_add_sso_button( $post_array, $db_handler ) {
		if ( ! Mo_SAML_Utilities::mo_saml_is_sp_configured() ) {
			$add_link = 'Service Provider';
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				$add_link = mo_saml_add_link( 'Service Provider', add_query_arg( array( 'tab' => 'save' ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
			}
			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::ERROR, 'Please complete ' . $add_link . ' Configuration in Service Provider Setup tab first.', 'SERVICE_PROVIDER_NOT_FOUND' );
		} else {
			$save_array[ Mo_Saml_Options_Enum_Sso_Login::SSO_BUTTON ] = ! empty( $post_array[ Mo_Saml_Options_Enum_Sso_Login::SSO_BUTTON ] ) ? sanitize_text_field( $post_array[ Mo_Saml_Options_Enum_Sso_Login::SSO_BUTTON ] ) : 'false';
			$db_handler->mo_saml_save_options( $save_array );

			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, Mo_Saml_Messages::SETTINGS_UPDATED, 'SSO_SETTINGS', $save_array );
		}
		$post_save->mo_saml_post_save_action();
	}
}
