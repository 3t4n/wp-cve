<?php
	/**
	 * Handles Form processing of the Attribute Mapping.
	 *
	 * @package  miniorange-saml-20-single-sign-on\handlers
	 */

if ( ! defined( 'ABSPATH' ) ) {
		exit();
}

/**
 * Handles Form Processing of Attribute Mapping section of "Attribute&Role Mapping" tab of the plugin.
 */
class Mo_SAML_Attribute_Mapping_Handler {
	/**
	 * Handles clearing attributes list shown in the attribute mapping of the plugin
	 *
	 * @return void
	 */
	public static function clear_attr_list() {
		delete_option( Mo_Saml_Options_Test_Configuration::TEST_CONFIG_ATTRS );
		$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, Mo_Saml_Messages::ATTRIBUTES_CLEARED, 'CLEAR_ATTR_LIST' );
		$post_save->mo_saml_post_save_action();
	}
}
