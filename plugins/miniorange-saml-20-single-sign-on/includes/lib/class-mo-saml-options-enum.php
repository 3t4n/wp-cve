<?php
/**
 * Defines the Constant class used throughout the plugin.
 *
 * @package miniorange-saml-20-single-sign-on\includes\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound -- Disabling this to define multiple constant classes in the same file.
require_once 'class-mo-saml-basic-enum.php';

/**
 * Defines constants for options used throughout the plugin.
 */
class Mo_Saml_Options_Enum extends Mo_SAML_Basic_Enum {
	const SAML_MESSAGE                   = 'mo_saml_message';
	const NEW_USER                       = 'mo_is_new_user';
	const MO_SAML_KEEP_SETTINGS_DELETION = 'mo_saml_keep_settings_on_deletion';
	const PLUGIN_DO_ACTIVATION           = 'mo_plugin_do_activation_redirect';
}

/**
 * Defines constants for Redirection SSO Links tab.
 */
class Mo_Saml_Options_Enum_Sso_Login extends Mo_SAML_Basic_Enum {
	const SSO_BUTTON = 'mo_saml_add_sso_button_wp';
}

/**
 * Defines constants for Service Provider Metadata tab.
 */
class Mo_Saml_Options_Enum_Identity_Provider extends Mo_SAML_Basic_Enum {
	const SP_BASE_URL  = 'mo_saml_sp_base_url';
	const SP_ENTITY_ID = 'mo_saml_sp_entity_id';
}

/**
 * Defines constants for Service Provider Setup tab.
 */
class Mo_Saml_Options_Enum_Service_Provider extends Mo_SAML_Basic_Enum {
	const IDENTITY_PROVIDER_NAME  = 'mo_saml_identity_provider_identifier_name';
	const IDENTITY_NAME           = 'saml_identity_name';
	const LOGIN_URL               = 'saml_login_url';
	const ISSUER                  = 'saml_issuer';
	const X509_CERTIFICATE        = 'saml_x509_certificate';
	const IS_ENCODING_ENABLED     = 'mo_saml_encoding_enabled';
	const ASSERTION_TIME_VALIDITY = 'mo_saml_assertion_time_validity';
	const RESPONSE_SIGNED         = 'saml_response_signed';
	const ASSERTION_SIGNED        = 'saml_assertion_signed';
}

/**
 * Defines constants for Redirection SSO Links tab.
 */
class Mo_Saml_Sso_Constants extends Mo_SAML_Basic_Enum {
	const MO_SAML_REDIRECT_ERROR        = 'mo_saml_redirect_error_code';
	const MO_SAML_REDIRECT_ERROR_REASON = 'mo_saml_redirect_error_reason';
	const MO_SAML_REQUIRED_CERTIFICATE  = 'mo_saml_required_certificate';
	const MO_SAML_REQUIRED_ISSUER       = 'mo_saml_required_issuer';
	const MO_SAML_TEST_STATUS           = 'MO_SAML_TEST_STATUS';
	const MO_SAML_EXPIRE_NOTICE         = 'mo_date_expire_notice';
	const MO_SAML_CLOSE_NOTICE          = 'mo_saml_close_notice';
}

/**
 * Defines constants for Service Provider Metadata tab.
 */
class Mo_Saml_Options_Enum_Metadata_Upload extends Mo_SAML_Basic_Enum {
	const IDENTITY_PROVIDER_NAME = 'saml_identity_metadata_provider';
	const METADATA_URL           = 'metadata_url';
	const METADATA_FILE          = 'metadata_file';
}

/**
 * Defines Test Configuration constants.
 */
class Mo_Saml_Options_Test_Configuration extends Mo_SAML_Basic_Enum {
	const SAML_REQUEST          = 'MO_SAML_REQUEST';
	const SAML_RESPONSE         = 'MO_SAML_RESPONSE';
	const TEST_CONFIG_ERROR_LOG = 'MO_SAML_TEST';
	const TEST_CONFIG_ATTRS     = 'mo_saml_test_config_attrs';
}

/**
 * Defines constants for Attribute Mapping section.
 */
class Mo_Saml_Options_Enum_Attribute_Mapping extends Mo_SAML_Basic_Enum {
	const ATTRIBUTE_USERNAME        = 'saml_am_username';
	const ATTRIBUTE_EMAIL           = 'saml_am_email';
	const ATTRIBUTE_FIRST_NAME      = 'saml_am_first_name';
	const ATTRIBUTE_LAST_NAME       = 'saml_am_last_name';
	const ATTRIBUTE_GROUP_NAME      = 'saml_am_group_name';
	const ATTRIBUTE_ACCOUNT_MATCHER = 'saml_am_account_matcher';
}

/**
 * Defines constants for Role Mapping section.
 */
class Mo_Saml_Options_Enum_Role_Mapping extends Mo_SAML_Basic_Enum {
	const ROLE_DEFAULT_ROLE = 'saml_am_default_user_role';
}

/**
 * Defines support form constants.
 */
class Mo_Saml_Contact_Us_Constants extends Mo_SAML_Basic_Enum {
	const CUSTOMER_EMAIL     = 'mo_saml_contact_us_email';
	const CUSTOMER_QUERY     = 'mo_saml_contact_us_query';
	const CUSTOMER_PHONE     = 'mo_saml_contact_us_phone';
	const CUSTOMER_TIMEZONE  = 'mo_saml_setup_call_timezone';
	const CUSTOMER_CALL_DATE = 'mo_saml_setup_call_date';
	const CUSTOMER_CALL_TIME = 'mo_saml_setup_call_time';
}

/**
 * Defines constants for Demo Request tab.
 */
class Mo_Saml_Demo_Constants extends Mo_SAML_Basic_Enum {
	const DEMO_SITE_URL    = 'https://demo.miniorange.com/wordpress-saml-demo/';
	const DEMO_EMAIL       = 'mo_saml_demo_email';
	const DEMO_PLAN        = 'mo_saml_demo_plan';
	const DEMO_DESCRIPTION = 'mo_saml_demo_description';
	const DEMO_ADDONS      = 'mo_saml_demo_addons';
}

/**
 * Defines constants for Account Setup tab.
 */
class Mo_Saml_Account_Setup_Constants extends Mo_SAML_Basic_Enum {
	const REGISTER_EMAIL    = 'registerEmail';
	const LOGIN_EMAIL       = 'loginEmail';
	const CUSTOMER_PASSWORD = 'password';
	const CONFIRM_PASSWORD  = 'confirmPassword';
}

/**
 * Defines constants for the external links.
 */
class Mo_Saml_External_Links extends Mo_SAML_Basic_Enum {
	const FAQ_DOWNLOAD_PAID_PLUGIN = 'https://faq.miniorange.com/knowledgebase/install-premium-plugin-free-plugin/';
	const PRICING_PAGE             = 'https://plugins.miniorange.com/wordpress-single-sign-on-sso#pricing';
}

/**
 * Defines customer account constants.
 */
class Mo_Saml_Customer_Constants extends Mo_SAML_Basic_Enum {
	const ADMIN_EMAIL    = 'mo_saml_admin_email';
	const ADMIN_PASSWORD = 'mo_saml_admin_password';
	const ADMIN_PHONE    = 'mo_saml_admin_phone';
	const CUSTOMER_KEY   = 'mo_saml_admin_customer_key';
	const API_KEY        = 'mo_saml_admin_api_key';
}

/**
 * Defines Error constants.
 */
class Mo_Saml_Options_Error_Constants extends Mo_SAML_Basic_Enum {
	const ERROR_NO_CERTIFICATE      = 'Unable to find a certificate .';
	const CAUSE_NO_CERTIFICATE      = 'No signature found in SAML Response or Assertion. Please sign at least one of them.';
	const ERROR_WRONG_CERTIFICATE   = 'Unable to find a certificate matching the configured fingerprint.';
	const CAUSE_WRONG_CERTIFICATE   = 'X.509 Certificate field in plugin does not match the certificate found in SAML Response.';
	const ERROR_INVALID_AUDIENCE    = 'Invalid Audience URI.';
	const CAUSE_INVALID_AUDIENCE    = "The value of 'Audience URI' field on Identity Provider's side is incorrect";
	const ERROR_ISSUER_NOT_VERIFIED = 'Issuer cannot be verified.';
	const CAUSE_ISSUER_NOT_VERIFIED = 'IdP Entity ID configured and the one found in SAML Response do not match';
}

/**
 * Defines Plugin Constants.
 */
class Mo_Saml_Options_Plugin_Constants extends  Mo_SAML_Basic_Enum {
	const CMS_NAME         = 'WP';
	const APPLICATION_NAME = 'WP miniOrange SAML 2.0 SSO Plugin';
	const APPLICATION_TYPE = 'SAML';
	const VERSION          = '5.1.3';
	const HOSTNAME         = 'https://login.xecurify.com';
	const WP_VERSION       = '6.4';
	const PLUGIN_FILE      = 'miniorange-saml-20-single-sign-on/login.php';
}

/**
 * Defines constants for IDP specific ads.
 */
class Mo_Saml_Options_Plugin_Idp_Specific_Ads extends Mo_SAML_Basic_Enum {
	/**
	 * An array of arrays defining ads links and texts based on the IDP name.
	 *
	 * @var array
	 */
	public static $idp_specific_ads = array(
		'ADFS'        => array(
			'Text'       => 'miniOrange SAML Single Sign On Plugin allows users in a corporate Active Directory setup to log into WordPress using their Windows Credentials. Once the user is logged in to a domain joined machine, they will not have to re-enter credentials in order to log into WordPress.',
			'Link'       => 'https://plugins.miniorange.com/saml-single-sign-on-sso-wordpress-using-adfs#step8',
			'Heading'    => 'Enable Windows SSO',
			'Link_Title' => 'See Configuration',
		),

		'Azure AD'    => array(
			'Text'       => 'User Sync for Azure AD / Azure B2C plugin Offers WordPress integrations with Microsoft Azure AD Graph APIs and provides Bi-directional User Synchronization, Creation of <a target = "blank" href = "https://wordpress.org/plugins/employee-staff-directory/ "> Employee Directory</a>, <a target = "blank" href = "https://wordpress.org/plugins/embed-power-bi-reports/ ">PowerBI integration </a>, <a target = "blank" href = "https://wordpress.org/plugins/embed-sharepoint-onedrive-documents/ ">Sharepoint integration </a>, etc.',
			'Link'       => 'https://wordpress.org/plugins/user-sync-for-azure-office365/',
			'Heading'    => 'User Sync for Azure AD / Azure B2C',
			'Link_Title' => 'Download',
			'Know_Title' => 'Know More',
			'Know_Link'  => 'https://plugins.miniorange.com/wordpress-azure-office365-integrations',
		),

		'Azure B2C'   => array(
			'Text'       => 'User Sync for Azure AD / Azure B2C plugin Offers WordPress integrations with Microsoft Azure AD Graph APIs and provides Bi-directional User Synchronization, PowerBI integration, Sharepoint integration, etc.',
			'Link'       => 'https://wordpress.org/plugins/user-sync-for-azure-office365/',
			'Heading'    => 'User Sync for Azure AD / Azure B2C',
			'Link_Title' => 'Download',
			'Know_Title' => 'Know More',
			'Know_Link'  => 'https://plugins.miniorange.com/wordpress-azure-office365-integrations',
		),
		'SalesForce'  => array(
			'Text'       => 'Object Data Sync For Salesforce plugin provides a bi-directional data synchronization between WP and Salesforce objects',
			'Link'       => 'https://wordpress.org/plugins/object-data-sync-for-salesforce/',
			'Heading'    => 'Object Data Sync For Salesforce',
			'Link_Title' => 'Download',
			'Know_Title' => 'Know More',
			'Know_Link'  => 'https://plugins.miniorange.com/wordpress-object-sync-for-salesforce',
		),
		'Community'   => array(
			'Text'       => 'Object Data Sync For Salesforce plugin provides a bi-directional data synchronization between WP and Salesforce objects',
			'Link'       => 'https://wordpress.org/plugins/object-data-sync-for-salesforce/',
			'Heading'    => 'Object Data Sync For Salesforce',
			'Link_Title' => 'Download',
			'Know_Title' => 'Know More',
			'Know_Link'  => 'https://plugins.miniorange.com/wordpress-object-sync-for-salesforce',
		),
		'Windows SSO' => array(
			'Text'       => 'miniOrange SAML Single Sign On Plugin allows users in a corporate Active Directory setup to log into WordPress using their Windows Credentials. Once the user is logged in to a domain joined machine, they will not have to re-enter credentials in order to log into WordPress.',
			'Link'       => 'https://plugins.miniorange.com/saml-single-sign-on-sso-wordpress-using-adfs#step8',
			'Heading'    => 'Enable Windows SSO',
			'Link_Title' => 'See Configuration',
		),
	);
}

/**
 * Defines constants for recommended addons.
 */
class Mo_Saml_Options_Suggested_Add_Ons extends  Mo_SAML_Basic_Enum {
	/**
	 * An array of arrays defining the text and links for the addons based on its name.
	 *
	 * @var array
	 */
	public static $suggested_addons = array(
		'page-restriction' => array(
			'title'    => 'Page / Post Restriction',
			'text'     => 'Restrict access to WordPress pages/posts based on user roles and their login status, thereby protecting these pages/posts from unauthorized access.',
			'link'     => 'https://wordpress.org/plugins/page-and-post-restriction/',
			'knw-link' => 'https://plugins.miniorange.com/wordpress-page-restriction',
		),
		'scim'             => array(
			'title'    => 'SCIM User Provisioning',
			'text'     => 'Allows real-time user sync (automatic user create, delete, and update) from your Identity Provider such as Azure, Okta, Onelogin into your WordPress site.',
			'link'     => 'https://wordpress.org/plugins/scim-user-provisioning/',
			'knw-link' => 'https://plugins.miniorange.com/wordpress-user-provisioning',
		),
	);
}

/**
 * Defines IDP constants.
 */
class Mo_Saml_Options_Plugin_Idp extends  Mo_SAML_Basic_Enum {
	/**
	 * An array of arrays defining the key and slug for the IDPs used by the admin notice ad.
	 *
	 * @var array
	 */
	public static $idp_list = array(
		'Azure AD'    => array(
			'key'  => 'azure-ad',
			'slug' => 'saml-single-sign-on-sso-wordpress-using-azure-ad',
		),
		'Azure B2C'   => array(
			'key'  => 'azure-b2c',
			'slug' => 'saml-single-sign-on-sso-wordpress-using-azure-b2c',
		),
		'Windows SSO' => array(
			'key'  => 'windows',
			'slug' => 'saml-single-sign-on-sso-wordpress-using-windows',
		),
		'Office 365'  => array(
			'key'  => 'office365',
			'slug' => 'saml-single-sign-on-sso-wordpress-using-office365',
		),
	);

	/**
	 * An array of array defining the key and slug for the IDP guides.
	 *
	 * @var array
	 */
	public static $idp_guides = array(
		'ADFS'           => array( 'adfs', 'saml-single-sign-on-sso-wordpress-using-adfs' ),
		'Azure AD'       => array( 'azure-ad', 'saml-single-sign-on-sso-wordpress-using-azure-ad' ),
		'Azure B2C'      => array( 'azure-b2c', 'saml-single-sign-on-sso-wordpress-using-azure-b2c' ),
		'Okta'           => array( 'okta', 'saml-single-sign-on-sso-wordpress-using-okta' ),
		'Keycloak'       => array( 'jboss-keycloak', 'saml-single-sign-on-sso-wordpress-using-jboss-keycloak' ),
		'Google Apps'    => array( 'google-apps', 'saml-single-sign-on-sso-wordpress-using-google-apps' ),
		'Windows SSO'    => array( 'windows', 'saml-single-sign-on-sso-wordpress-using-adfs' ),
		'SalesForce'     => array( 'salesforce', 'saml-single-sign-on-sso-wordpress-using-salesforce' ),
		'WordPress'      => array( 'wordpress', 'saml-single-sign-on-sso-between-two-wordpress-sites' ),
		'Office 365'     => array( 'office365', 'wordpress-office-365-single-sign-on-sso-login' ),
		'Auth0'          => array( 'auth0', 'saml-single-sign-on-sso-wordpress-using-auth0' ),
		'MiniOrange'     => array( 'miniorange', 'saml-single-sign-on-sso-wordpress-using-miniorange' ),
		'Community'      => array( 'salesforce', 'saml-single-sign-on-sso-wordpress-using-salesforce community' ),
		'Classlink'      => array( 'classlink', 'saml-single-sign-on-sso-login-wordpress-using-classlink' ),
		'OneLogin'       => array( 'onelogin', 'saml-single-sign-on-sso-wordpress-using-onelogin' ),
		'Centrify'       => array( 'centrify', 'saml-single-sign-on-sso-wordpress-using-centrify' ),
		'PingFederate'   => array( 'pingfederate', 'saml-single-sign-on-sso-wordpress-using-pingfederate' ),
		'Shibboleth 2'   => array( 'shibboleth2', 'saml-single-sign-on-sso-wordpress-using-shibboleth2' ),
		'Shibboleth 3'   => array( 'shibboleth3', 'saml-single-sign-on-sso-wordpress-using-shibboleth3' ),
		'AbsorbLMS'      => array( 'absorb-lms', 'saml-single-sign-on-sso-wordpress-using-absorb-lms' ),
		'Gluu Server'    => array( 'gluu-server', 'saml-single-sign-on-sso-wordpress-using-gluu-server' ),
		'Dynamic CRM'    => array( 'dynamic-crm', 'saml-single-sign-on-wordpress-using-dynamics-365-crm' ),
		'Sharepoint'     => array( 'sharepoint', 'saml-single-sign-on-wordpress-using-microsoft-sharepoint' ),
		'JumpCloud'      => array( 'jumpcloud', 'saml-single-sign-on-sso-wordpress-using-jumpcloud' ),
		'IdentityServer' => array( 'identityserver4', 'saml-single-sign-on-sso-wordpress-using-identityserver4' ),
		'VMware'         => array( 'vmware', 'saml-single-sign-on-sso-wordpress-using-vmware-identity-manager' ),
		'Degreed'        => array( 'degreed', 'saml-single-sign-on-sso-wordpress-using-degreed' ),
		'CyberArk'       => array( 'cyberark', 'saml-single-sign-on-sso-for-wordpress-using-cyberark' ),
		'Duo'            => array( 'duo', 'saml-single-sign-on-sso-wordpress-using-duo' ),
		'FusionAuth'     => array( 'fusionauth', 'saml-single-sign-on-sso-wordpress-using-fusionauth' ),
		'SiteMinder'     => array( 'siteminder', 'siteminder-saml-single-sign-on-sso-login-wordpress' ),
		'SecureAuth'     => array( 'secureauth', 'saml-single-sign-on-sso-wordpress-using-secureauth' ),
		'NetIQ'          => array( 'netiq', 'saml-single-sign-on-sso-wordpress-using-netIQ' ),
		'Fonteva'        => array( 'fonteva', 'saml-single-sign-on-sso-wordpress-using-fonteva' ),
		'SURFconext'     => array( 'surfconext', 'surfconext-saml-single-sign-on-sso-in-wordpress' ),
		'PhenixID'       => array( 'phenixid', 'phenixid-saml-single-sign-on-sso-login-wordpresss' ),
		'LastPass'       => array( 'lastpass', 'saml-single-sign-on-sso-wordpress-using-lastpass' ),
		'Authanvil'      => array( 'authanvil', 'saml-single-sign-on-sso-wordpress-using-authanvil' ),
		'Bitium'         => array( 'bitium', 'saml-single-sign-on-sso-wordpress-using-bitium' ),
		'CA Identity'    => array( 'ca-identity', 'saml-single-sign-on-sso-wordpress-using-ca-identity' ),
		'OpenAM'         => array( 'openam', 'saml-single-sign-on-sso-wordpress-using-openam' ),
		'OpenAthens'     => array( 'openathens', 'openathens-saml-single-sign-on-sso-login-wordpress' ),
		'Oracle'         => array( 'oracle-enterprise-manager', 'saml-single-sign-on-sso-wordpress-using-oracle-enterprise-manager' ),
		'PingOne'        => array( 'pingone', 'saml-single-sign-on-sso-wordpress-using-pingone' ),
		'RSA SecureID'   => array( 'rsa-secureid', 'saml-single-sign-on-sso-wordpress-using-rsa-secureid' ),
		'SimpleSAMLphp'  => array( 'simplesaml', 'saml-single-sign-on-sso-wordpress-using-simplesaml' ),
		'WSO2'           => array( 'wso2', 'saml-single-sign-on-sso-wordpress-using-wso2' ),
		'Drupal'         => array( 'drupal', 'wordpress-sso-login-with-drupal-idp' ),
		'Custom IDP'     => array( 'custom-idp', 'saml-single-sign-on-sso-wordpress-using-custom-idp' ),

	);
}

/**
 * Defines constants for IDP guide videos.
 */
class Mo_Saml_Options_Plugin_Idp_Videos extends  Mo_SAML_Basic_Enum {
	/**
	 * A map for idp key and video link's path.
	 *
	 * @var array
	 */
	public static $idp_videos = array(
		'azure-ad'                  => 'TfPJwgUq8z0',
		'azure-b2c'                 => 'B8zCYjhV3UU',
		'adfs'                      => 'rLBHbRbrY5E',
		'okta'                      => 'YHE8iYojUqM',
		'salesforce'                => 'LRQrmgr255Q',
		'google-apps'               => 'tu69SPBiFPo',
		'onelogin'                  => '_Hsot_RG9YY',
		'miniorange'                => 'eamf9s6JpbA',
		'jboss-keycloak'            => 'rPG8-lIIHHc',
		'absorb-lms'                => '',
		'degreed'                   => '',
		'pingfederate'              => '',
		'pingone'                   => '',
		'centrify'                  => '',
		'oracle-enterprise-manager' => '',
		'bitium'                    => '',
		'drupal'                    => '',
		'openathens'                => '',
		'lastpass'                  => '',
		'siteminder'                => '',
		'dynamic-crm'               => '',
		'vmware'                    => '',
		'sharepoint'                => '',
		'shibboleth2'               => '',
		'shibboleth3'               => '',
		'gluu-server'               => '',
		'simplesaml'                => '',
		'openam'                    => '',
		'authanvil'                 => '',
		'auth0'                     => '54pz6m5h9mk',
		'ca-identity'               => '',
		'wso2'                      => '',
		'rsa-secureid'              => '',
		'custom-idp'                => 'gilfhNFYsgc',
		'wordpress'                 => 'DA61F7PqnQU',
		'office365'                 => '4-zyFUFiOXU',
		'jumpcloud'                 => 'OTP35vbQrts',
		'identityserver4'           => '',
		'cyberark'                  => '',
		'duo'                       => '',
		'fusionauth'                => '',
		'secureauth'                => '',
		'netiq'                     => '',
		'fonteva'                   => '',
		'windows'                   => 'rLBHbRbrY5E',
		'surfconext'                => '',
		'phenixid'                  => '',
		'classlink'                 => '',
		'community'                 => '',

	);
}
/**
 * Defines addons constants.
 */
class Mo_Saml_Options_Addons extends Mo_SAML_Basic_Enum {

	/**
	 * A map for addons name and URL.
	 *
	 * @var array
	 */
	public static $addons_url = array(

		'salesforce_sync'             => 'https://plugins.miniorange.com/wordpress-object-sync-for-salesforce',
		'employee_directory'          => 'https://plugins.miniorange.com/employee-directory-and-staff-listing-for-wordpress',
		'azure_sync'                  => 'https://plugins.miniorange.com/wordpress-azure-office365-integrations',
		'scim'                        => 'https://plugins.miniorange.com/wordpress-user-provisioning',
		'page_restriction'            => 'https://plugins.miniorange.com/wordpress-page-restriction',
		'file_prevention'             => 'https://plugins.miniorange.com/wordpress-media-restriction',
		'ssologin'                    => 'https://plugins.miniorange.com/wordpress-sso-login-audit',
		'buddypress'                  => 'https://plugins.miniorange.com/wordpress-buddypress-integrator',
		'learndash'                   => 'https://plugins.miniorange.com/wordpress-learndash-integrator',
		'attribute_based_redirection' => 'https://plugins.miniorange.com/wordpress-attribute-based-redirection-restriction',
		'ssosession'                  => 'https://plugins.miniorange.com/sso-session-management',
		'fsso'                        => 'https://plugins.miniorange.com/incommon-federation-single-sign-on-sso',
		'paid_mem_pro'                => 'https://plugins.miniorange.com/paid-membership-pro-integrator',
		'memberpress'                 => 'https://plugins.miniorange.com/wordpress-memberpress-integrator',
		'wp_members'                  => 'https://plugins.miniorange.com/wordpress-members-integrator',
		'woocommerce'                 => 'https://plugins.miniorange.com/wordpress-woocommerce-integrator',
		'guest_login'                 => 'https://plugins.miniorange.com/guest-user-login',
		'profile_picture_add_on'      => 'https://plugins.miniorange.com/wordpress-profile-picture-map',
		'power_bi'                    => 'https://plugins.miniorange.com/microsoft-power-bi-embed-for-wordpress',
		'sharepoint'                  => 'https://plugins.miniorange.com/microsoft-sharepoint-wordpress-integration',


	);

	/**
	 * An array of array containg the URLs for the addons published on the WordPress repository.
	 *
	 * @var array
	 */
	public static $wp_addon_url = array(
		'wp-page-restriction' => array( 'Page Restriction WordPress (WP) - Protect WP Pages/Post', 'Protect content access for WordPress (WP) | Give access to specific WP pages and posts based on user\'s roles and logged in/logged out status...', 'https://wordpress.org/plugins/page-and-post-restriction/' ),
		'wp-scim'             => array( 'SCIM user provisioning', 'SCIM User Provisioning plugin, Create, Update, delete users from Azure AD, Okta, OneLogin, G-suite, Centrify, JumpCloud, Idaptive, Gluu, WS02 and all SCIM …', 'https://wordpress.org/plugins/scim-user-provisioning/' ),
	);

	/**
	 * A map for addon name and title.
	 *
	 * @var array
	 */
	public static $addon_title = array(

		'salesforce_sync'             => 'Object Data Sync for Salesforce',
		'power_bi'                    => 'Embed Power BI Report',
		'sharepoint'                  => 'Sharepoint/ One-Drive Integration',
		'employee_directory'          => 'Employee Staff Directory',
		'azure_sync'                  => 'Azure AD/Office 365 Integrations',
		'scim'                        => 'SCIM User Provisioning',
		'page_restriction'            => 'Page and Post Restriction',
		'file_prevention'             => 'Prevent File Access',
		'ssologin'                    => 'SSO Login Audit',
		'buddypress'                  => 'BuddyPress Integrator',
		'learndash'                   => 'Learndash Integrator',
		'attribute_based_redirection' => 'Attribute Based Redirection',
		'ssosession'                  => 'SSO Session Management',
		'fsso'                        => 'Federation Single Sign-On',
		'memberpress'                 => 'MemberPress Integrator',
		'wp_members'                  => 'WP-Members Integrator',
		'woocommerce'                 => 'WooCommerce Integrator',
		'guest_login'                 => 'Guest Login',
		'profile_picture_add_on'      => 'Profile Picture Add-on',
		'paid_mem_pro'                => 'PaidMembership Pro Integrator',
	);

	/**
	 * A map for plugins name and slug for displaying recommended addons.
	 *
	 * @var array
	 */
	public static $recommended_addons_path = array(
		'learndash'    => 'sfwd-lms/sfwd_lms.php',
		'buddypress'   => 'buddypress/bp-loader.php',
		'paid_mem_pro' => 'paid-memberships-pro/paid-memberships-pro.php',
		'memberpress'  => 'memberpress/memberpress.php',
		'wp_members'   => 'wp-members/wp-members.php',
		'woocommerce'  => 'woocommerce/woocommerce.php',

	);

	/**
	 * A map for displaying IDP specific addons.
	 *
	 * @var array
	 */
	public static $addon_specific = array(
		'power_bi'               => 'Embed Power BI Report',
		'sharepoint'             => 'Sharepoint/ One-Drive Integration',
		'employee_directory'     => 'Employee Staff Directory',
		'azure_sync'             => 'Azure AD/Office 365 Integrations',
		'profile_picture_add_on' => 'Profile Picture Add-on',
	);
}

/**
 * Defines constants for time zones used in the support form.
 */
class Mo_Saml_Time_Zones extends  Mo_SAML_Basic_Enum {

	/**
	 * A map for time zone and region.
	 *
	 * @var array
	 */
	public static $time_zones = array(
		'(GMT-11:00) Niue Time'                            => 'Pacific/Niue',
		'(GMT-11:00) Samoa Standard Time'                  => 'Pacific/Pago_Pago',
		'(GMT-10:00) Cook Islands Standard Time'           => 'Pacific/Rarotonga',
		'(GMT-10:00) Hawaii-Aleutian Standard Time'        => 'Pacific/Honolulu',
		'(GMT-10:00) Tahiti Time'                          => 'Pacific/Tahiti',
		'(GMT-09:30) Marquesas Time'                       => 'Pacific/Marquesas',
		'(GMT-09:00) Gambier Time'                         => 'Pacific/Gambier',
		'(GMT-09:00) Hawaii-Aleutian Time (Adak)'          => 'America/Adak',
		'(GMT-08:00) Alaska Time - Anchorage'              => 'America/Anchorage',
		'(GMT-08:00) Alaska Time - Juneau'                 => 'America/Juneau',
		'(GMT-08:00) Alaska Time - Metlakatla'             => 'America/Metlakatla',
		'(GMT-08:00) Alaska Time - Nome'                   => 'America/Nome',
		'(GMT-08:00) Alaska Time - Sitka'                  => 'America/Sitka',
		'(GMT-08:00) Alaska Time - Yakutat'                => 'America/Yakutat',
		'(GMT-08:00) Pitcairn Time'                        => 'Pacific/Pitcairn',
		'(GMT-07:00) Mexican Pacific Standard Time'        => 'America/Hermosillo',
		'(GMT-07:00) Mountain Standard Time - Creston'     => 'America/Creston',
		'(GMT-07:00) Mountain Standard Time - Dawson'      => 'America/Dawson',
		'(GMT-07:00) Mountain Standard Time - Dawson Creek' => 'America/Dawson_Creek',
		'(GMT-07:00) Mountain Standard Time - Fort Nelson' => 'America/Fort_Nelson',
		'(GMT-07:00) Mountain Standard Time - Phoenix'     => 'America/Phoenix',
		'(GMT-07:00) Mountain Standard Time - Whitehorse'  => 'America/Whitehorse',
		'(GMT-07:00) Pacific Time - Los Angeles'           => 'America/Los_Angeles',
		'(GMT-07:00) Pacific Time - Tijuana'               => 'America/Tijuana',
		'(GMT-07:00) Pacific Time - Vancouver'             => 'America/Vancouver',
		'(GMT-06:00) Central Standard Time - Belize'       => 'America/Belize',
		'(GMT-06:00) Central Standard Time - Costa Rica'   => 'America/Costa_Rica',
		'(GMT-06:00) Central Standard Time - El Salvador'  => 'America/El_Salvador',
		'(GMT-06:00) Central Standard Time - Guatemala'    => 'America/Guatemala',
		'(GMT-06:00) Central Standard Time - Managua'      => 'America/Managua',
		'(GMT-06:00) Central Standard Time - Regina'       => 'America/Regina',
		'(GMT-06:00) Central Standard Time - Swift Current' => 'America/Swift_Current',
		'(GMT-06:00) Central Standard Time - Tegucigalpa'  => 'America/Tegucigalpa',
		'(GMT-06:00) Easter Island Time'                   => 'Pacific/Easter',
		'(GMT-06:00) Galapagos Time'                       => 'Pacific/Galapagos',
		'(GMT-06:00) Mexican Pacific Time - Chihuahua'     => 'America/Chihuahua',
		'(GMT-06:00) Mexican Pacific Time - Mazatlan'      => 'America/Mazatlan',
		'(GMT-06:00) Mountain Time - Boise'                => 'America/Boise',
		'(GMT-06:00) Mountain Time - Cambridge Bay'        => 'America/Cambridge_Bay',
		'(GMT-06:00) Mountain Time - Denver'               => 'America/Denver',
		'(GMT-06:00) Mountain Time - Edmonton'             => 'America/Edmonton',
		'(GMT-06:00) Mountain Time - Inuvik'               => 'America/Inuvik',
		'(GMT-06:00) Mountain Time - Ojinaga'              => 'America/Ojinaga',
		'(GMT-06:00) Mountain Time - Yellowknife'          => 'America/Yellowknife',
		'(GMT-05:00) Acre Standard Time - Eirunepe'        => 'America/Eirunepe',
		'(GMT-05:00) Acre Standard Time - Rio Branco'      => 'America/Rio_Branco',
		'(GMT-05:00) Central Time - Bahia Banderas'        => 'America/Bahia_Banderas',
		'(GMT-05:00) Central Time - Beulah, North Dakota'  => 'America/North_Dakota/Beulah',
		'(GMT-05:00) Central Time - Center, North Dakota'  => 'America/North_Dakota/Center',
		'(GMT-05:00) Central Time - Chicago'               => 'America/Chicago',
		'(GMT-05:00) Central Time - Knox, Indiana'         => 'America/Indiana/Knox',
		'(GMT-05:00) Central Time - Matamoros'             => 'America/Matamoros',
		'(GMT-05:00) Central Time - Menominee'             => 'America/Menominee',
		'(GMT-05:00) Central Time - Merida'                => 'America/Merida',
		'(GMT-05:00) Central Time - Mexico City'           => 'America/Mexico_City',
		'(GMT-05:00) Central Time - Monterrey'             => 'America/Monterrey',
		'(GMT-05:00) Central Time - New Salem, North Dakota' => 'America/North_Dakota/New_Salem',
		'(GMT-05:00) Central Time - Rainy River'           => 'America/Rainy_River',
		'(GMT-05:00) Central Time - Rankin Inlet'          => 'America/Rankin_Inlet',
		'(GMT-05:00) Central Time - Resolute'              => 'America/Resolute',
		'(GMT-05:00) Central Time - Tell City, Indiana'    => 'America/Indiana/Tell_City',
		'(GMT-05:00) Central Time - Winnipeg'              => 'America/Winnipeg',
		'(GMT-05:00) Colombia Standard Time'               => 'America/Bogota',
		'(GMT-05:00) Eastern Standard Time - Atikokan'     => 'America/Atikokan',
		'(GMT-05:00) Eastern Standard Time - Cancun'       => 'America/Cancun',
		'(GMT-05:00) Eastern Standard Time - Jamaica'      => 'America/Jamaica',
		'(GMT-05:00) Eastern Standard Time - Panama'       => 'America/Panama',
		'(GMT-05:00) Ecuador Time'                         => 'America/Guayaquil',
		'(GMT-05:00) Peru Standard Time'                   => 'America/Lima',
		'(GMT-04:00) Amazon Standard Time - Boa Vista'     => 'America/Boa_Vista',
		'(GMT-04:00) Amazon Standard Time - Campo Grande'  => 'America/Campo_Grande',
		'(GMT-04:00) Amazon Standard Time - Cuiaba'        => 'America/Cuiaba',
		'(GMT-04:00) Amazon Standard Time - Manaus'        => 'America/Manaus',
		'(GMT-04:00) Amazon Standard Time - Porto Velho'   => 'America/Porto_Velho',
		'(GMT-04:00) Atlantic Standard Time - Barbados'    => 'America/Barbados',
		'(GMT-04:00) Atlantic Standard Time - Blanc-Sablon' => 'America/Blanc-Sablon',
		'(GMT-04:00) Atlantic Standard Time - Curaçao'     => 'America/Curacao',
		'(GMT-04:00) Atlantic Standard Time - Martinique'  => 'America/Martinique',
		'(GMT-04:00) Atlantic Standard Time - Port of Spain' => 'America/Port_of_Spain',
		'(GMT-04:00) Atlantic Standard Time - Puerto Rico' => 'America/Puerto_Rico',
		'(GMT-04:00) Atlantic Standard Time - Santo Domingo' => 'America/Santo_Domingo',
		'(GMT-04:00) Bolivia Time'                         => 'America/La_Paz',
		'(GMT-04:00) Chile Time'                           => 'America/Santiago',
		'(GMT-04:00) Cuba Time'                            => 'America/Havana',
		'(GMT-04:00) Eastern Time - Detroit'               => 'America/Detroit',
		'(GMT-04:00) Eastern Time - Grand Turk'            => 'America/Grand_Turk',
		'(GMT-04:00) Eastern Time - Indianapolis'          => 'America/Indiana/Indianapolis',
		'(GMT-04:00) Eastern Time - Iqaluit'               => 'America/Iqaluit',
		'(GMT-04:00) Eastern Time - Louisville'            => 'America/Kentucky/Louisville',
		'(GMT-04:00) Eastern Time - Marengo, Indiana'      => 'America/Indiana/Marengo',
		'(GMT-04:00) Eastern Time - Monticello, Kentucky'  => 'America/Kentucky/Monticello',
		'(GMT-04:00) Eastern Time - Nassau'                => 'America/Nassau',
		'(GMT-04:00) Eastern Time - New York'              => 'America/New_York',
		'(GMT-04:00) Eastern Time - Nipigon'               => 'America/Nipigon',
		'(GMT-04:00) Eastern Time - Pangnirtung'           => 'America/Pangnirtung',
		'(GMT-04:00) Eastern Time - Petersburg, Indiana'   => 'America/Indiana/Petersburg',
		'(GMT-04:00) Eastern Time - Port-au-Prince'        => 'America/Port-au-Prince',
		'(GMT-04:00) Eastern Time - Thunder Bay'           => 'America/Thunder_Bay',
		'(GMT-04:00) Eastern Time - Toronto'               => 'America/Toronto',
		'(GMT-04:00) Eastern Time - Vevay, Indiana'        => 'America/Indiana/Vevay',
		'(GMT-04:00) Eastern Time - Vincennes, Indiana'    => 'America/Indiana/Vincennes',
		'(GMT-04:00) Eastern Time - Winamac, Indiana'      => 'America/Indiana/Winamac',
		'(GMT-04:00) Guyana Time'                          => 'America/Guyana',
		'(GMT-04:00) Paraguay Time'                        => 'America/Asuncion',
		'(GMT-04:00) Venezuela Time'                       => 'America/Caracas',
		'(GMT-03:00) Argentina Standard Time - Buenos Aires' => 'America/Argentina/Buenos_Aires',
		'(GMT-03:00) Argentina Standard Time - Catamarca'  => 'America/Argentina/Catamarca',
		'(GMT-03:00) Argentina Standard Time - Cordoba'    => 'America/Argentina/Cordoba',
		'(GMT-03:00) Argentina Standard Time - Jujuy'      => 'America/Argentina/Jujuy',
		'(GMT-03:00) Argentina Standard Time - La Rioja'   => 'America/Argentina/La_Rioja',
		'(GMT-03:00) Argentina Standard Time - Mendoza'    => 'America/Argentina/Mendoza',
		'(GMT-03:00) Argentina Standard Time - Rio Gallegos' => 'America/Argentina/Rio_Gallegos',
		'(GMT-03:00) Argentina Standard Time - Salta'      => 'America/Argentina/Salta',
		'(GMT-03:00) Argentina Standard Time - San Juan'   => 'America/Argentina/San_Juan',
		'(GMT-03:00) Argentina Standard Time - San Luis'   => 'America/Argentina/San_Luis',
		'(GMT-03:00) Argentina Standard Time - Tucuman'    => 'America/Argentina/Tucuman',
		'(GMT-03:00) Argentina Standard Time - Ushuaia'    => 'America/Argentina/Ushuaia',
		'(GMT-03:00) Atlantic Time - Bermuda'              => 'Atlantic/Bermuda',
		'(GMT-03:00) Atlantic Time - Glace Bay'            => 'America/Glace_Bay',
		'(GMT-03:00) Atlantic Time - Goose Bay'            => 'America/Goose_Bay',
		'(GMT-03:00) Atlantic Time - Halifax'              => 'America/Halifax',
		'(GMT-03:00) Atlantic Time - Moncton'              => 'America/Moncton',
		'(GMT-03:00) Atlantic Time - Thule'                => 'America/Thule',
		'(GMT-03:00) Brasilia Standard Time - Araguaina'   => 'America/Araguaina',
		'(GMT-03:00) Brasilia Standard Time - Bahia'       => 'America/Bahia',
		'(GMT-03:00) Brasilia Standard Time - Belem'       => 'America/Belem',
		'(GMT-03:00) Brasilia Standard Time - Fortaleza'   => 'America/Fortaleza',
		'(GMT-03:00) Brasilia Standard Time - Maceio'      => 'America/Maceio',
		'(GMT-03:00) Brasilia Standard Time - Recife'      => 'America/Recife',
		'(GMT-03:00) Brasilia Standard Time - Santarem'    => 'America/Santarem',
		'(GMT-03:00) Brasilia Standard Time - Sao Paulo'   => 'America/Sao_Paulo',
		'(GMT-03:00) Chile Time'                           => 'America/Santiago',
		'(GMT-03:00) Falkland Islands Standard Time'       => 'Atlantic/Stanley',
		'(GMT-03:00) French Guiana Time'                   => 'America/Cayenne',
		'(GMT-03:00) Palmer Time'                          => 'Antarctica/Palmer',
		'(GMT-03:00) Punta Arenas Time'                    => 'America/Punta_Arenas',
		'(GMT-03:00) Rothera Time'                         => 'Antarctica/Rothera',
		'(GMT-03:00) Suriname Time'                        => 'America/Paramaribo',
		'(GMT-03:00) Uruguay Standard Time'                => 'America/Montevideo',
		'(GMT-02:30) Newfoundland Time'                    => 'America/St_Johns',
		'(GMT-02:00) Fernando de Noronha Standard Time'    => 'America/Noronha',
		'(GMT-02:00) South Georgia Time'                   => 'Atlantic/South_Georgia',
		'(GMT-02:00) St. Pierre & Miquelon Time'           => 'America/Miquelon',
		'(GMT-02:00) West Greenland Time'                  => 'America/Nuuk',
		'(GMT-01:00) Cape Verde Standard Time'             => 'Atlantic/Cape_Verde',
		'(GMT+00:00) Azores Time'                          => 'Atlantic/Azores',
		'(GMT+00:00) Coordinated Universal Time'           => 'UTC',
		'(GMT+00:00) East Greenland Time'                  => 'America/Scoresbysund',
		'(GMT+00:00) Greenwich Mean Time'                  => 'Etc/GMT',
		'(GMT+00:00) Greenwich Mean Time - Abidjan'        => 'Africa/Abidjan',
		'(GMT+00:00) Greenwich Mean Time - Accra'          => 'Africa/Accra',
		'(GMT+00:00) Greenwich Mean Time - Bissau'         => 'Africa/Bissau',
		'(GMT+00:00) Greenwich Mean Time - Danmarkshavn'   => 'America/Danmarkshavn',
		'(GMT+00:00) Greenwich Mean Time - Monrovia'       => 'Africa/Monrovia',
		'(GMT+00:00) Greenwich Mean Time - Reykjavik'      => 'Atlantic/Reykjavik',
		'(GMT+00:00) Greenwich Mean Time - São Tomé'       => 'Africa/Sao_Tome',
		'(GMT+01:00) Central European Standard Time - Algiers' => 'Africa/Algiers',
		'(GMT+01:00) Central European Standard Time - Tunis' => 'Africa/Tunis',
		'(GMT+01:00) Ireland Time'                         => 'Europe/Dublin',
		'(GMT+01:00) Morocco Time'                         => 'Africa/Casablanca',
		'(GMT+01:00) United Kingdom Time'                  => 'Europe/London',
		'(GMT+01:00) West Africa Standard Time - Lagos'    => 'Africa/Lagos',
		'(GMT+01:00) West Africa Standard Time - Ndjamena' => 'Africa/Ndjamena',
		'(GMT+01:00) Western European Time - Canary'       => 'Atlantic/Canary',
		'(GMT+01:00) Western European Time - Faroe'        => 'Atlantic/Faroe',
		'(GMT+01:00) Western European Time - Lisbon'       => 'Europe/Lisbon',
		'(GMT+01:00) Western European Time - Madeira'      => 'Atlantic/Madeira',
		'(GMT+01:00) Western Sahara Time'                  => 'Africa/El_Aaiun',
		'(GMT+02:00) Central Africa Time - Khartoum'       => 'Africa/Khartoum',
		'(GMT+02:00) Central Africa Time - Maputo'         => 'Africa/Maputo',
		'(GMT+02:00) Central Africa Time - Windhoek'       => 'Africa/Windhoek',
		'(GMT+02:00) Central European Time - Amsterdam'    => 'Europe/Amsterdam',
		'(GMT+02:00) Central European Time - Andorra'      => 'Europe/Andorra',
		'(GMT+02:00) Central European Time - Belgrade'     => 'Europe/Belgrade',
		'(GMT+02:00) Central European Time - Berlin'       => 'Europe/Berlin',
		'(GMT+02:00) Central European Time - Brussels'     => 'Europe/Brussels',
		'(GMT+02:00) Central European Time - Budapest'     => 'Europe/Budapest',
		'(GMT+02:00) Central European Time - Ceuta'        => 'Africa/Ceuta',
		'(GMT+02:00) Central European Time - Copenhagen'   => 'Europe/Copenhagen',
		'(GMT+02:00) Central European Time - Gibraltar'    => 'Europe/Gibraltar',
		'(GMT+02:00) Central European Time - Luxembourg'   => 'Europe/Luxembourg',
		'(GMT+02:00) Central European Time - Madrid'       => 'Europe/Madrid',
		'(GMT+02:00) Central European Time - Malta'        => 'Europe/Malta',
		'(GMT+02:00) Central European Time - Monaco'       => 'Europe/Monaco',
		'(GMT+02:00) Central European Time - Oslo'         => 'Europe/Oslo',
		'(GMT+02:00) Central European Time - Paris'        => 'Europe/Paris',
		'(GMT+02:00) Central European Time - Prague'       => 'Europe/Prague',
		'(GMT+02:00) Central European Time - Rome'         => 'Europe/Rome',
		'(GMT+02:00) Central European Time - Stockholm'    => 'Europe/Stockholm',
		'(GMT+02:00) Central European Time - Tirane'       => 'Europe/Tirane',
		'(GMT+02:00) Central European Time - Vienna'       => 'Europe/Vienna',
		'(GMT+02:00) Central European Time - Warsaw'       => 'Europe/Warsaw',
		'(GMT+02:00) Central European Time - Zurich'       => 'Europe/Zurich',
		'(GMT+02:00) Eastern European Standard Time - Cairo' => 'Africa/Cairo',
		'(GMT+02:00) Eastern European Standard Time - Kaliningrad' => 'Europe/Kaliningrad',
		'(GMT+02:00) Eastern European Standard Time - Tripoli' => 'Africa/Tripoli',
		'(GMT+02:00) South Africa Standard Time'           => 'Africa/Johannesburg',
		'(GMT+02:00) Troll Time'                           => 'Antarctica/Troll',
		'(GMT+03:00) Arabian Standard Time - Baghdad'      => 'Asia/Baghdad',
		'(GMT+03:00) Arabian Standard Time - Qatar'        => 'Asia/Qatar',
		'(GMT+03:00) Arabian Standard Time - Riyadh'       => 'Asia/Riyadh',
		'(GMT+03:00) East Africa Time - Juba'              => 'Africa/Juba',
		'(GMT+03:00) East Africa Time - Nairobi'           => 'Africa/Nairobi',
		'(GMT+03:00) Eastern European Time - Amman'        => 'Asia/Amman',
		'(GMT+03:00) Eastern European Time - Athens'       => 'Europe/Athens',
		'(GMT+03:00) Eastern European Time - Beirut'       => 'Asia/Beirut',
		'(GMT+03:00) Eastern European Time - Bucharest'    => 'Europe/Bucharest',
		'(GMT+03:00) Eastern European Time - Chisinau'     => 'Europe/Chisinau',
		'(GMT+03:00) Eastern European Time - Damascus'     => 'Asia/Damascus',
		'(GMT+03:00) Eastern European Time - Gaza'         => 'Asia/Gaza',
		'(GMT+03:00) Eastern European Time - Hebron'       => 'Asia/Hebron',
		'(GMT+03:00) Eastern European Time - Helsinki'     => 'Europe/Helsinki',
		'(GMT+03:00) Eastern European Time - Kiev'         => 'Europe/Kiev',
		'(GMT+03:00) Eastern European Time - Nicosia'      => 'Asia/Nicosia',
		'(GMT+03:00) Eastern European Time - Riga'         => 'Europe/Riga',
		'(GMT+03:00) Eastern European Time - Sofia'        => 'Europe/Sofia',
		'(GMT+03:00) Eastern European Time - Tallinn'      => 'Europe/Tallinn',
		'(GMT+03:00) Eastern European Time - Uzhhorod'     => 'Europe/Uzhgorod',
		'(GMT+03:00) Eastern European Time - Vilnius'      => 'Europe/Vilnius',
		'(GMT+03:00) Eastern European Time - Zaporozhye'   => 'Europe/Zaporozhye',
		'(GMT+03:00) Famagusta Time'                       => 'Asia/Famagusta',
		'(GMT+03:00) Israel Time'                          => 'Asia/Jerusalem',
		'(GMT+03:00) Kirov Time'                           => 'Europe/Kirov',
		'(GMT+03:00) Moscow Standard Time - Minsk'         => 'Europe/Minsk',
		'(GMT+03:00) Moscow Standard Time - Moscow'        => 'Europe/Moscow',
		'(GMT+03:00) Moscow Standard Time - Simferopol'    => 'Europe/Simferopol',
		'(GMT+03:00) Syowa Time'                           => 'Antarctica/Syowa',
		'(GMT+03:00) Turkey Time'                          => 'Europe/Istanbul',
		'(GMT+04:00) Armenia Standard Time'                => 'Asia/Yerevan',
		'(GMT+04:00) Astrakhan Time'                       => 'Europe/Astrakhan',
		'(GMT+04:00) Azerbaijan Standard Time'             => 'Asia/Baku',
		'(GMT+04:00) Georgia Standard Time'                => 'Asia/Tbilisi',
		'(GMT+04:00) Gulf Standard Time'                   => 'Asia/Dubai',
		'(GMT+04:00) Mauritius Standard Time'              => 'Indian/Mauritius',
		'(GMT+04:00) Réunion Time'                         => 'Indian/Reunion',
		'(GMT+04:00) Samara Standard Time'                 => 'Europe/Samara',
		'(GMT+04:00) Saratov Time'                         => 'Europe/Saratov',
		'(GMT+04:00) Seychelles Time'                      => 'Indian/Mahe',
		'(GMT+04:00) Ulyanovsk Time'                       => 'Europe/Ulyanovsk',
		'(GMT+04:00) Volgograd Standard Time'              => 'Europe/Volgograd',
		'(GMT+04:30) Afghanistan Time'                     => 'Asia/Kabul',
		'(GMT+04:30) Iran Time'                            => 'Asia/Tehran',
		'(GMT+05:00) French Southern & Antarctic Time'     => 'Indian/Kerguelen',
		'(GMT+05:00) Maldives Time'                        => 'Indian/Maldives',
		'(GMT+05:00) Mawson Time'                          => 'Antarctica/Mawson',
		'(GMT+05:00) Pakistan Standard Time'               => 'Asia/Karachi',
		'(GMT+05:00) Tajikistan Time'                      => 'Asia/Dushanbe',
		'(GMT+05:00) Turkmenistan Standard Time'           => 'Asia/Ashgabat',
		'(GMT+05:00) Uzbekistan Standard Time - Samarkand' => 'Asia/Samarkand',
		'(GMT+05:00) Uzbekistan Standard Time - Tashkent'  => 'Asia/Tashkent',
		'(GMT+05:00) West Kazakhstan Time - Aqtau'         => 'Asia/Aqtau',
		'(GMT+05:00) West Kazakhstan Time - Aqtobe'        => 'Asia/Aqtobe',
		'(GMT+05:00) West Kazakhstan Time - Atyrau'        => 'Asia/Atyrau',
		'(GMT+05:00) West Kazakhstan Time - Oral'          => 'Asia/Oral',
		'(GMT+05:00) West Kazakhstan Time - Qyzylorda'     => 'Asia/Qyzylorda',
		'(GMT+05:00) Yekaterinburg Standard Time'          => 'Asia/Yekaterinburg',
		'(GMT+05:30) Indian Standard Time - Colombo'       => 'Asia/Colombo',
		'(GMT+05:30) Indian Standard Time - Kolkata'       => 'Asia/Kolkata',
		'(GMT+05:45) Nepal Time'                           => 'Asia/Kathmandu',
		'(GMT+06:00) Bangladesh Standard Time'             => 'Asia/Dhaka',
		'(GMT+06:00) Bhutan Time'                          => 'Asia/Thimphu',
		'(GMT+06:00) East Kazakhstan Time - Almaty'        => 'Asia/Almaty',
		'(GMT+06:00) East Kazakhstan Time - Kostanay'      => 'Asia/Qostanay',
		'(GMT+06:00) Indian Ocean Time'                    => 'Indian/Chagos',
		'(GMT+06:00) Kyrgyzstan Time'                      => 'Asia/Bishkek',
		'(GMT+06:00) Omsk Standard Time'                   => 'Asia/Omsk',
		'(GMT+06:00) Urumqi Time'                          => 'Asia/Urumqi',
		'(GMT+06:00) Vostok Time'                          => 'Antarctica/Vostok',
		'(GMT+06:30) Cocos Islands Time'                   => 'Indian/Cocos',
		'(GMT+06:30) Myanmar Time'                         => 'Asia/Yangon',
		'(GMT+07:00) Barnaul Time'                         => 'Asia/Barnaul',
		'(GMT+07:00) Christmas Island Time'                => 'Indian/Christmas',
		'(GMT+07:00) Davis Time'                           => 'Antarctica/Davis',
		'(GMT+07:00) Hovd Standard Time'                   => 'Asia/Hovd',
		'(GMT+07:00) Indochina Time - Bangkok'             => 'Asia/Bangkok',
		'(GMT+07:00) Indochina Time - Ho Chi Minh City'    => 'Asia/Ho_Chi_Minh',
		'(GMT+07:00) Krasnoyarsk Standard Time - Krasnoyarsk' => 'Asia/Krasnoyarsk',
		'(GMT+07:00) Krasnoyarsk Standard Time - Novokuznetsk' => 'Asia/Novokuznetsk',
		'(GMT+07:00) Novosibirsk Standard Time'            => 'Asia/Novosibirsk',
		'(GMT+07:00) Tomsk Time'                           => 'Asia/Tomsk',
		'(GMT+07:00) Western Indonesia Time - Jakarta'     => 'Asia/Jakarta',
		'(GMT+07:00) Western Indonesia Time - Pontianak'   => 'Asia/Pontianak',
		'(GMT+08:00) Australian Western Standard Time - Casey' => 'Antarctica/Casey',
		'(GMT+08:00) Australian Western Standard Time - Perth' => 'Australia/Perth',
		'(GMT+08:00) Brunei Darussalam Time'               => 'Asia/Brunei',
		'(GMT+08:00) Central Indonesia Time'               => 'Asia/Makassar',
		'(GMT+08:00) China Standard Time - Macao'          => 'Asia/Macau',
		'(GMT+08:00) China Standard Time - Shanghai'       => 'Asia/Shanghai',
		'(GMT+08:00) Hong Kong Standard Time'              => 'Asia/Hong_Kong',
		'(GMT+08:00) Irkutsk Standard Time'                => 'Asia/Irkutsk',
		'(GMT+08:00) Malaysia Time - Kuala Lumpur'         => 'Asia/Kuala_Lumpur',
		'(GMT+08:00) Malaysia Time - Kuching'              => 'Asia/Kuching',
		'(GMT+08:00) Philippine Standard Time'             => 'Asia/Manila',
		'(GMT+08:00) Singapore Standard Time'              => 'Asia/Singapore',
		'(GMT+08:00) Taipei Standard Time'                 => 'Asia/Taipei',
		'(GMT+08:00) Ulaanbaatar Standard Time - Choibalsan' => 'Asia/Choibalsan',
		'(GMT+08:00) Ulaanbaatar Standard Time - Ulaanbaatar' => 'Asia/Ulaanbaatar',
		'(GMT+08:45) Australian Central Western Standard Time' => 'Australia/Eucla',
		'(GMT+09:00) East Timor Time'                      => 'Asia/Dili',
		'(GMT+09:00) Eastern Indonesia Time'               => 'Asia/Jayapura',
		'(GMT+09:00) Japan Standard Time'                  => 'Asia/Tokyo',
		'(GMT+09:00) Korean Standard Time - Pyongyang'     => 'Asia/Pyongyang',
		'(GMT+09:00) Korean Standard Time - Seoul'         => 'Asia/Seoul',
		'(GMT+09:00) Palau Time'                           => 'Pacific/Palau',
		'(GMT+09:00) Yakutsk Standard Time - Chita'        => 'Asia/Chita',
		'(GMT+09:00) Yakutsk Standard Time - Khandyga'     => 'Asia/Khandyga',
		'(GMT+09:00) Yakutsk Standard Time - Yakutsk'      => 'Asia/Yakutsk',
		'(GMT+09:30) Australian Central Standard Time'     => 'Australia/Darwin',
		'(GMT+09:30) Central Australia Time - Adelaide'    => 'Australia/Adelaide',
		'(GMT+09:30) Central Australia Time - Broken Hill' => 'Australia/Broken_Hill',
		'(GMT+10:00) Australian Eastern Standard Time - Brisbane' => 'Australia/Brisbane',
		'(GMT+10:00) Australian Eastern Standard Time - Lindeman' => 'Australia/Lindeman',
		'(GMT+10:00) Chamorro Standard Time'               => 'Pacific/Guam',
		'(GMT+10:00) Chuuk Time'                           => 'Pacific/Chuuk',
		'(GMT+10:00) Dumont-d’Urville Time'                => 'Antarctica/DumontDUrville',
		'(GMT+10:00) Eastern Australia Time - Currie'      => 'Australia/Currie',
		'(GMT+10:00) Eastern Australia Time - Hobart'      => 'Australia/Hobart',
		'(GMT+10:00) Eastern Australia Time - Melbourne'   => 'Australia/Melbourne',
		'(GMT+10:00) Eastern Australia Time - Sydney'      => 'Australia/Sydney',
		'(GMT+10:00) Papua New Guinea Time'                => 'Pacific/Port_Moresby',
		'(GMT+10:00) Vladivostok Standard Time - Ust-Nera' => 'Asia/Ust-Nera',
		'(GMT+10:00) Vladivostok Standard Time - Vladivostok' => 'Asia/Vladivostok',
		'(GMT+10:30) Lord Howe Time'                       => 'Australia/Lord_Howe',
		'(GMT+11:00) Bougainville Time'                    => 'Pacific/Bougainville',
		'(GMT+11:00) Kosrae Time'                          => 'Pacific/Kosrae',
		'(GMT+11:00) Macquarie Island Time'                => 'Antarctica/Macquarie',
		'(GMT+11:00) Magadan Standard Time'                => 'Asia/Magadan',
		'(GMT+11:00) New Caledonia Standard Time'          => 'Pacific/Noumea',
		'(GMT+11:00) Norfolk Island Time'                  => 'Pacific/Norfolk',
		'(GMT+11:00) Ponape Time'                          => 'Pacific/Pohnpei',
		'(GMT+11:00) Sakhalin Standard Time'               => 'Asia/Sakhalin',
		'(GMT+11:00) Solomon Islands Time'                 => 'Pacific/Guadalcanal',
		'(GMT+11:00) Srednekolymsk Time'                   => 'Asia/Srednekolymsk',
		'(GMT+11:00) Vanuatu Standard Time'                => 'Pacific/Efate',
		'(GMT+12:00) Anadyr Standard Time'                 => 'Asia/Anadyr',
		'(GMT+12:00) Fiji Time'                            => 'Pacific/Fiji',
		'(GMT+12:00) Gilbert Islands Time'                 => 'Pacific/Tarawa',
		'(GMT+12:00) Marshall Islands Time - Kwajalein'    => 'Pacific/Kwajalein',
		'(GMT+12:00) Marshall Islands Time - Majuro'       => 'Pacific/Majuro',
		'(GMT+12:00) Nauru Time'                           => 'Pacific/Nauru',
		'(GMT+12:00) New Zealand Time'                     => 'Pacific/Auckland',
		'(GMT+12:00) Petropavlovsk-Kamchatski Standard Time' => 'Asia/Kamchatka',
		'(GMT+12:00) Tuvalu Time'                          => 'Pacific/Funafuti',
		'(GMT+12:00) Wake Island Time'                     => 'Pacific/Wake',
		'(GMT+12:00) Wallis & Futuna Time'                 => 'Pacific/Wallis',
		'(GMT+12:45) Chatham Time'                         => 'Pacific/Chatham',
		'(GMT+13:00) Apia Time'                            => 'Pacific/Apia',
		'(GMT+13:00) Phoenix Islands Time'                 => 'Pacific/Enderbury',
		'(GMT+13:00) Tokelau Time'                         => 'Pacific/Fakaofo',
		'(GMT+13:00) Tonga Standard Time'                  => 'Pacific/Tongatapu',
		'(GMT+14:00) Line Islands Time'                    => 'Pacific/Kiritimati',
	);
}

/**
 * Defines error log contants.
 */
class Mo_Saml_Error_Log extends Mo_SAML_Basic_Enum {
	const PLUGIN_CONFIGURATIONS                             = '[Downloading Debug Logs] Plugin Configurations on Download: 
 	                                [   SSO_Login = {{SSO_Login}},
 	                                    Service_Provider_Metadata = {{Identity_Provider}},
 	                                    Service_Provider_Setup = {{Service_Provider}},
 	                                    Attribute_Mapping = {{Attribute_Mapping}},
 	                                    Role_Mapping = {{Role_Mapping}},
 	                                    Version_Dependencies = {{Version_dependencies}}
 	                                ]';
	const INVALID_CONFIGURATION_SETTING                     = 'Configuration Empty or NULL';
	const INVALID_CERT                                      = 'Invalid x509 certificate Provided';
	const CLEAR_ATTR_LIST                                   = 'Attributes List cleared';
	const SERVICE_PROVIDER_CONF                             = '[Service Provider Setup] Configuration saved: 
                                   [ saml_identity_name = {{saml_identity_name}},
                                     saml_login_url = {{saml_login_url}} ,
                                     saml_issuer = {{saml_issuer}},
                                     saml_x509_certificate = {{saml_x509_certificate}},
                                     mo_saml_encoding_enabled = {{mo_saml_encoding_enabled}}
                                    ]';
	const SERVICE_PROVIDER_NOT_FOUND                        = '[Add SSO Button] Error in adding a SSO button on WordPress login page. Plugin\'s "Service Provider Setup" tab not configured.';
	const IDP_NAME_EMPTY                                    = '[Upload IDP Metadata] Identity Provider Name Empty.';
	const INVALID_IDP_NAME_FORMAT                           = 'INVALID_IDP_NAME_FORMAT IDP_NAME = {{saml_identity_name}}';
	const SP_ENTITY_ID                                      = '[Service Provider Metadata] Configuration saved:[ sp_entity_id = {{mo_saml_sp_entity_id}} ]';
	const DEFAULT_ROLE_ID                                   = '[Role Mapping] Configuration saved:[ default_user_role = {{saml_am_default_user_role}} ]';
	const SSO_SETTINGS                                      = '[SSO Settings] Configuration saved: [ SSO button added on WP login page  = {{mo_saml_add_sso_button_wp}} ].';
	const UPLOAD_METADATA_EMPTY                             = '[Upload IDP Metadata] IDP Metadata is empty';
	const UPLOAD_METADATA_NAME_EMPTY                        = '[Upload IDP Metadata] IDP Metadata File Name is empty';
	const UPLOAD_METADATA_SUCCESS                           = '[Upload IDP Metadata] IDP Metadata fetched from file';
	const UPLOAD_METADATA_CURL_ERROR                        = '[Upload IDP Metadata] PHP CURL disabled. Can\'t fetch IDP metadata from URL';
	const UPLOAD_METADATA_URL                               = '[Upload IDP Metadata] Fetching IDP metadata from URL: {{url}} ';
	const UPLOAD_METADATA_SUCCESS_FROM_URL                  = '[Upload IDP Metadata] IDP metadata fetched from URL ';
	const UPLOAD_METADATA_ERROR_FROM_URL                    = '[Upload IDP Metadata] An error occurred while fetching IDP metadata from the URL';
	const UPLOAD_METADATA_INVALID_FILE                      = '[Upload IDP Metadata] IDP Metadata file is invalid';
	const UPLOAD_METADATA_INVALID_URL                       = '[Upload IDP Metadata] IDP Metadata URL is invalid';
	const UPLOAD_METADATA_INVALID_CONFIGURATION             = '[Upload IDP Metadata] Invalid IDP Configuration found in metadata';
	const UPLOAD_METADATA_CONFIGURATION_SAVED               = '[Upload IDP Metadata] Configuration saved from IDP metadata: 
                                                  [ saml_login_url = {{saml_login_url}} ,
                                                   saml_issuer = {{saml_issuer}},
                                                   saml_x509_certificate = {{saml_x509_certificate}},
                                                 ]';
	const LOGIN_WIDGET_AUTHN_REQUEST                        = '[MO SAML SSO] Initiating SAML Request:
                          [ SSO URL = {{ssoUrl}},
                            ACS URL = {{acsUrl}},
                            SP Entity ID = {{spEntityId}},
                            RelayState = {{sendRelayState}} ]';
	const LOGIN_WIDGET_SAML_REQUEST                         = '[MO SAML SSO] SAML Request generated: [ SAMLRequest = {{samlRequest}} ]';
	const LOGIN_WIDGET_RELAYSTATE_SENT                      = '[MO SAML SSO] Sending SAML Request to IDP SSO URL: {{redirect}}';
	const LOGIN_WIDGET_SAML_RESPONSE                        = '[MO SAML SSO] SAML Response received from the IDP: {{samlResponse}}';
	const LOGIN_WIDGET_RELAYSTATE_RECEIVED                  = '[MO SAML SSO] RelayState received from the IDP:{{relayState}}';
	const LOGIN_WIDGET_SAML_STATUS                          = '[MO SAML SSO] SAML Response Status:[ Status Code =  {{status}},Status Message =  {[statusMessage}}  ]';
	const LOGIN_WIDGET_INVAILD_SAML_STATUS                  = '[MO SAML SSO] Invalid Status Code in the SAML Response';
	const LOGIN_WIDGET_RESPONSE_ASSERATION_NOT_SIGNED       = '[MO SAML SSO] SAML Response and Assertion are not signed.';
	const LOGIN_WIDGET_VAILD_RESPONSE                       = '[MO SAML SSO] SAML Response validated. Performing user mapping check.';
	const ATTRIBUTES_RECEIVED_IN_TEST_CONFIGURATION         = '[MO SAML SSO TEST-VALIDATE] {{{attrs}}}';
	const LOGIN_WIDGET_USER_EXISTS                          = '[MO SAML SSO] User found with username: {{userName}}';
	const LOGIN_WIDGET_CERT_NOT_MATCHED                     = '[MO SAML SSO] Certificate mismatch or Invalid Signature found in the SAML Response';
	const LOGIN_WIDGET_EMAIL_EXISTS                         = '[MO SAML SSO] User found with email: {{user_email}}';
	const LOGIN_WIDGET_NEW_USER                             = '[MO SAML SSO] User not found. Creating a new user account:[ Username = {{user_email}},
                                                                                               Email =  {{user_email}},
                                                                                                 User ID = {{user_id}} ]';
	const LOGIN_WIDGET_USER_CREATION_FAILED                 = '[MO SAML SSO] An error occurred while creating the user';
	const LOGIN_WIDGET_USERNAME_LENGTH_LIMIT_EXCEEDED       = '[MO SAML SSO] Username/NameID value is more than 60 characters long.';
	const LOGIN_WIDGET_DEFAULT_ROLE                         = '[MO SAML SSO] Assigning user to the default role :{{defaultRole}}';
	const LOGIN_WIDGET_USER_CREATION_FAILED_USERNAME_EXISTS = '[MO SAML SSO] An error occurred while creating the user. A user with the same username already exists in the WordPress site';
	const LOGIN_WIDGET_COOKIE_CREATED                       = '[MO SAML SSO] Generating Auth Cookie for User ID: {{user_id}}';
	const LOGIN_WIDGET_REDIRECT_URL_AFTER_LOGIN             = '[MO SAML SSO] Redirecting the user to URL: {{redirect_url}}';
	const UTILITIES_INVALID_AUDIENCE_URI                    = '[MO SAML SSO] Invalid Audience URI found in the SAML Response:
                                            [ SP Entity ID = {{spEntityId}}, 
                                              Assertion Audience = {{audiences}}, ]';
	const UTILITIES_INVALID_ISSUER                          = '[MO SAML SSO] Invalid Issuer found for the SAML Response:
                                     [ IDP Entity ID = {{issuerToValidateAgainst}},
                                     Issuer = {{issuer}} ]';
	const LOGIN_WIDGET_CERT_NOT_MATCHED_ENCODED             = '[MO SAML SSO] Certificate mismatched after the iconv encoding.';
	const LOGIN_WIDGET_UNABLE_TO_PROCESS_RESPONSE           = '[MO SAML SSO] Unable to process the SAML response';

	/**
	 * Prints message in the log file.
	 *
	 * @param string $message The message constant to be printed in the log file.
	 * @param array  $data Any additional data to be printed in the log file.
	 * @return string The message printed in the log file.
	 */
	public static function mo_saml_write_message( $message, $data = array() ) : string {
		$message = constant( 'self::' . $message );
		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$value = wp_json_encode( $value, JSON_UNESCAPED_SLASHES );
				}
				$message = str_replace( '{{' . $key . '}}', $value, $message );
				$message = preg_replace( '/\s+/', ' ', $message );
			}
		}
		return $message;
	}


}

/**
 * Defines admin notice messages.
 */
class Mo_Saml_Messages extends Mo_SAML_Basic_Enum {
	const IDP_DETAILS_SUCCESS      = 'Identity Provider details saved successfully.';
	const INVALID_CERT             = 'Invalid certificate: Please provide a valid X.509 certificate.';
	const FIELDS_EMPTY             = 'All the fields are required. Please enter valid entries.';
	const INVALID_FORMAT           = 'Please match the requested format for Identity Provider Name. Only alphabets, numbers and underscore is allowed.';
	const METADATA_EMPTY           = 'Please upload a valid metadata file or URL.';
	const IDP_NAME_EMPTY           = 'IDP Name cannot be empty. Please enter a valid Identity Provider Name.';
	const METADATA_NAME_EMPTY      = 'Error uploading metadata. Please upload a valid metadata file.';
	const INVALID_IDP_NAME_FORMAT  = 'Please match the requested format for Identity Provider Name. Only alphabets, numbers and underscore is allowed.';
	const INVALID_METADATA_FILE    = 'Please provide a valid metadata file.';
	const INVALID_METADATA_URL     = 'Please provide a valid metadata URL.';
	const INVALID_METADATA_CONFIG  = 'Unable to fetch Metadata. Please check your metadata again.';
	const METADATA_UPLOAD_SUCCESS  = 'Identity Provider details saved successfully.';
	const SETTINGS_UPDATED         = 'Settings updated successfully.';
	const CONTACT_EMAIL_EMPTY      = 'Please fill up required fields to submit your query.';
	const CONTACT_EMAIL_INVALID    = 'Please enter a valid email address.';
	const CALL_SETUP_DETAILS_EMPTY = 'Please fill up Schedule Call Details to submit your query.';
	const QUERY_NOT_SUBMITTED      = 'Your query could not be submitted. Please try again.';
	const QUERY_SUBMITTED          = 'Thanks for getting in touch! We will reach out on your email shortly.';
	const UPDATED_DEFAULT_ROLE     = 'Role Mapping details saved successfully.';
	const DEMO_REQUEST_FAILED      = 'Something went wrong. Please reach out to us using the Support/Contact Us form to get help with the demo.';
	const PASSWORD_PATTERN_INVALID = 'Minimum 6 characters should be present. Maximum 15 characters should be present. Only following symbols (!@#.$%^&*-_) are allowed.';
	const PASSWORD_MISMATCH        = 'Passwords do not match.';
	const ACCOUNT_EXISTS           = 'You already have an account with miniOrange. Please enter a valid password.';
	const INVALID_CREDENTIALS      = 'Invalid username or password. Please try again.';
	const REGISTER_SUCCESS         = 'Thank you for registering with miniOrange.';
	const CUSTOMER_FOUND           = 'Customer retrieved successfully.';
	const ATTRIBUTES_CLEARED       = 'List of attributes cleared.';
	const LOG_FILE_NOT_FOUND       = "Log file doesn't exists.";
	const LOG_FILE_CLEARED         = 'Successfully cleared log files.';
	const WPCONFIG_ERROR           = 'WP-config.php is not writable, please follow the manual steps to enable/disable the debug logs.';
	const PLUGIN_DEACTIVATED       = 'Plugin deactivated successfully.';
	const FEEDBACK_SUCCESS         = 'Thank you for the feedback.';

}
/**
 * Defines Save Status Constants.
 */
class Mo_Saml_Save_Status_Constants extends Mo_SAML_Basic_Enum {
	const ERROR   = 'ERROR';
	const SUCCESS = 'SUCCESS';
}

/**
 * Defines API response Status Constants.
 */
class Mo_Saml_Api_Status_Constants extends Mo_SAML_Basic_Enum {
	const ERROR   = 'ERROR';
	const SUCCESS = 'SUCCESS';
}

/**
 * Defines Encoding Methods constants.
 */
class Mo_Saml_Options_Enum_Encoding extends Mo_Saml_Basic_Enum {
	const ENCODING_CP1252 = 'CP1252';
	const ENCODING_UTF_8  = 'UTF-8';
}

// phpcs:enable Generic.Files.OneObjectStructurePerFile.MultipleFound
