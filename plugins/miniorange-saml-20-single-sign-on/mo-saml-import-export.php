<?php
/**
 * File Name: mo-saml-import-export.php
 * Description: This file will import and export the configuration
 *
 * @package miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once dirname( __FILE__ ) . '/includes/lib/class-mo-saml-options-enum.php';
require_once 'class-mo-saml-logger.php';
add_action( 'admin_init', 'mo_saml_miniorange_import_export' );

$tab_class_names_array = array(
	'SSO_Login'         => 'Mo_Saml_Options_Enum_Sso_Login',
	'Identity_Provider' => 'Mo_Saml_Options_Enum_Identity_Provider',
	'Service_Provider'  => 'Mo_Saml_Options_Enum_Service_Provider',
	'Attribute_Mapping' => 'Mo_Saml_Options_Enum_Attribute_Mapping',
	'Role_Mapping'      => 'Mo_Saml_Options_Enum_Role_Mapping',
);

if ( get_option( Mo_Saml_Sso_Constants::MO_SAML_TEST_STATUS ) !== 1 ) {

	$tab_class_names_array['Test_Configuration'] = 'Mo_Saml_Options_Test_Configuration';
}

define( 'TAB_CLASS_NAMES', maybe_serialize( $tab_class_names_array ) );

/**
 * This Function will Send the Export Configuration to the Query as a string and It will download in a file.
 *
 * @param string $test_config_screen This Parameter will help us to know whether it is coming from Query or Service Provider Tab.
 * @param string $json_in_string This Parameter will help us to know whether Export Configuration should sent in Json_string or not.
 * @return bool
 */
function mo_saml_miniorange_import_export( $test_config_screen = false, $json_in_string = false ) {
	if ( $test_config_screen ) {
		$_POST['option'] = 'mo_saml_export';
	}

	if ( ! empty( $_POST['option'] ) ) {
		if ( 'mo_saml_export' === $_POST['option'] || 'mo_saml_logger' === $_POST['option'] ) {
			if ( $test_config_screen && $json_in_string ) {
				$export_referer = check_admin_referer( 'mo_saml_contact_us_query_option' );
			} elseif ( 'mo_saml_export' === $_POST['option'] ) {
				$export_referer = check_admin_referer( 'mo_saml_export' );
			} else {
				$export_referer = check_admin_referer( 'mo_saml_logger' );
				$json_in_string = true;
			}

			if ( $export_referer ) {
				$tab_class_name      = maybe_unserialize( TAB_CLASS_NAMES );
				$configuration_array = array();
				foreach ( $tab_class_name as $key => $value ) {
					$configuration_array[ $key ] = mo_saml_get_configuration_array( $value );
				}
				$configuration_array['Version_dependencies'] = mo_saml_get_version_informations();
				$version                                     = phpversion();
				if ( substr( $version, 0, 3 ) === '5.3' ) {
					$json_string_escaped = ( wp_json_encode( $configuration_array, JSON_PRETTY_PRINT ) );        // json_encode for escaping and encoding.
				} else {
					$json_string_escaped = ( wp_json_encode( $configuration_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
				}

				if ( $json_in_string ) {
					return $json_string_escaped;
				}
				header( 'Content-Disposition: attachment; filename=miniorange-saml-config.json' );
				//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- It was giving an improper file while escaping.
				echo ( $json_string_escaped );
				exit;
			}
		} elseif ( 'mo_saml_keep_settings_on_deletion' === $_POST['option'] && check_admin_referer( 'mo_saml_keep_settings_on_deletion' ) ) {

			if ( ! empty( $_POST['mo_saml_keep_settings_intact'] ) ) {
				update_option( Mo_Saml_Options_Enum::MO_SAML_KEEP_SETTINGS_DELETION, 'true' );
			} else {
				update_option( Mo_Saml_Options_Enum::MO_SAML_KEEP_SETTINGS_DELETION, '' );
			}
			update_option( 'mo_saml_message', 'Keep Settings Intact Option Updated Successfully.' );
			Mo_SAML_Utilities::mo_saml_show_success_message();
		}

		return;

	}

}

/**
 * Function will export the Configuration
 *
 * @param string $class_name Name of All class Constants.
 * @return array
 */
function mo_saml_get_configuration_array( $class_name ) {
	$class_object = call_user_func( $class_name . '::get_constants' );
	$mo_array     = array();
	foreach ( $class_object as $key => $value ) {
		$mo_option_exists = get_option( $value );

		if ( $mo_option_exists ) {
			$mo_option_exists = maybe_unserialize( $mo_option_exists );
			$mo_array[ $key ] = $mo_option_exists;

		}
	}

	return $mo_array;
}

/**
 * Function will export the versions of WP,PHP,etc
 *
 * @return array
 */
function mo_saml_get_version_informations() {
	$array_version                      = array();
	$array_version['Plugin_version']    = Mo_Saml_Options_Plugin_Constants::VERSION;
	$array_version['PHP_version']       = phpversion();
	$array_version['Wordpress_version'] = get_bloginfo( 'version' );
	$array_version['OPEN_SSL']          = Mo_SAML_Utilities::mo_saml_is_openssl_installed();
	$array_version['CURL']              = Mo_SAML_Utilities::mo_saml_is_curl_installed();
	$array_version['ICONV']             = Mo_SAML_Utilities::mo_saml_is_iconv_installed();
	$array_version['DOM']               = Mo_SAML_Utilities::mo_saml_is_dom_installed();

	return $array_version;

}
