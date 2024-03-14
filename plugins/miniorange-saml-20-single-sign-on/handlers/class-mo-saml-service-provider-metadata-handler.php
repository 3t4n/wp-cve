<?php
	/**
	 * Class to handle actions present in the "Service Provider Metadata" tab of the plugin.
	 *
	 * @package miniorange-saml-20-single-sign-on\handlers
	 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Class to handle downloading of the SAML SP metadata & updating the SP endpoints.
 */
class Mo_SAML_Service_Provider_Metadata_Handler {

	/**
	 * Function to download the plugin's metadata or SP Metadata.
	 *
	 * @param bool $download to handle view and download business logic.
	 * @return void
	 */
	public static function download_plugin_metadata( $download = false ) {

		$sp_base_url = Mo_SAML_Utilities::mo_saml_get_sp_base_url();
		$entity_id   = Mo_SAML_Utilities::mo_saml_get_sp_entity_id( $sp_base_url );
		$acs_url     = $sp_base_url . '/';

		if ( ob_get_contents() ) {
			ob_clean();
		}

		header( 'Content-Type: text/xml' );
		if ( $download ) {
			header( 'Content-Disposition: attachment; filename="Metadata.xml"' );
		}

		echo '<?xml version="1.0"?>
<md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" validUntil="2026-07-22T10:07:10Z" cacheDuration="PT1446808792S" entityID="' . esc_attr( $entity_id ) . '">
    <md:SPSSODescriptor AuthnRequestsSigned="false" WantAssertionsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
        <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified</md:NameIDFormat>
	    <md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="' . esc_url( $acs_url ) . '" index="1"/>
    </md:SPSSODescriptor>
	<md:Organization>
		<md:OrganizationName xml:lang="en-US">miniOrange</md:OrganizationName>
		<md:OrganizationDisplayName xml:lang="en-US">miniOrange</md:OrganizationDisplayName>
		<md:OrganizationURL xml:lang="en-US">https://miniorange.com</md:OrganizationURL>
	</md:Organization>
	<md:ContactPerson contactType="technical">
		<md:GivenName>miniOrange</md:GivenName>
		<md:EmailAddress>info@xecurify.com</md:EmailAddress>
	</md:ContactPerson>
	<md:ContactPerson contactType="support">
		<md:GivenName>miniOrange</md:GivenName> 
		<md:EmailAddress>info@xecurify.com</md:EmailAddress>
	</md:ContactPerson>
</md:EntityDescriptor>';
		exit;
	}

	/**
	 * Function to update the SP endpoints.
	 *
	 * @param array                    $post_array array containing the new SP Entity ID.
	 *
	 * @param Mo_SAML_Database_Handler $db_handler  instance of database handler.
	 *
	 * @return void
	 */
	public static function update_sp_endpoints( $post_array, $db_handler ) {

		if ( isset( $post_array[ Mo_Saml_Options_Enum_Identity_Provider::SP_ENTITY_ID ] ) ) {
			$save_array[ Mo_Saml_Options_Enum_Identity_Provider::SP_ENTITY_ID ] = sanitize_text_field( $post_array[ Mo_Saml_Options_Enum_Identity_Provider::SP_ENTITY_ID ] );
			$db_handler->mo_saml_save_options( $save_array );

			$post_save = new Mo_SAML_Post_Save_Handler( Mo_Saml_Save_Status_Constants::SUCCESS, Mo_Saml_Messages::SETTINGS_UPDATED, 'SP_ENTITY_ID', $save_array );
			$post_save->mo_saml_post_save_action();
		}
	}
}
