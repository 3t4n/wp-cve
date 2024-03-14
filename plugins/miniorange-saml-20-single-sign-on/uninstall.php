<?php
/**
 * This file runs automatically when the user deletes the plugin in order to clear out any plugin options and/or settings specific to the plugin.
 *
 * @package miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

require_once dirname( __FILE__ ) . '/includes/lib/class-mo-saml-options-enum.php';
if ( ! ( get_option( 'mo_saml_keep_settings_on_deletion' ) === 'true' ) ) {

	if ( is_multisite() ) {
		$original_blog_id = get_current_blog_id();
		switch_to_blog( $original_blog_id );
	}
	mo_saml_delete_configuration_array();
}
/**
 * Deletes the configuration of the plugin.
 *
 * @return void
 */
function mo_saml_delete_configuration_array() {
	$tab_class_names_array = array(
		'Mo_Saml_Options_Test_Configuration',
		'Mo_Saml_Customer_Constants',
		'Mo_Saml_Options_Enum',
		'Mo_Saml_Options_Enum_Identity_Provider',
		'Mo_Saml_Options_Enum_Service_Provider',
		'Mo_Saml_Sso_Constants',
		'Mo_Saml_Options_Enum_Attribute_Mapping',
		'Mo_Saml_Options_Enum_Role_Mapping',
		'Mo_Saml_Options_Enum_Sso_Login',
	);
	foreach ( $tab_class_names_array as $class_name ) {
		$class_object = call_user_func( $class_name . '::get_constants' );
		foreach ( $class_object as $key => $value ) {
			delete_option( $value );
		}
	}
}

