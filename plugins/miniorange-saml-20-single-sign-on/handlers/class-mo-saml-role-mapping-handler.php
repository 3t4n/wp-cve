<?php
/**
 * This file contains a handler class to save or update the plugin settings related to role mapping.
 *
 * @package miniorange-saml-20-single-sign-on\handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Handles form submission for settings under the Role Mapping section.
 */
class Mo_SAML_Role_Mapping_Handler {
	/**
	 * This will set the default role for the user under the Role Mapping section.
	 *
	 * @param array           $post_array Contains the values of $_POST.
	 * @param DatabaseHandler $db_handler Object for DatabaseHandler.
	 */
	public static function mo_saml_update_default_role( $post_array, $db_handler ) {

		$save_array = array();
		if ( ! Mo_SAML_Utilities::mo_saml_check_empty_or_null( array( $post_array[ Mo_Saml_Options_Enum_Role_Mapping::ROLE_DEFAULT_ROLE ] ) ) ) {
			$save_array[ Mo_Saml_Options_Enum_Role_Mapping::ROLE_DEFAULT_ROLE ] = sanitize_text_field( $post_array[ Mo_Saml_Options_Enum_Role_Mapping::ROLE_DEFAULT_ROLE ] );
		}

		$db_handler->mo_saml_save_options( $save_array );

		$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, Mo_Saml_Messages::UPDATED_DEFAULT_ROLE, 'DEFAULT_ROLE_ID', $save_array );
		$post_save->mo_saml_post_save_action();
	}
}
