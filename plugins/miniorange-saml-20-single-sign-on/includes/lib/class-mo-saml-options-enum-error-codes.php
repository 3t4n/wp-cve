<?php
/**
 * This file contains all the Enums for error codes.
 *
 * @package miniorange-saml-20-single-sign-on\includes\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'class-mo-saml-basic-enum.php';

/**
 * This contains a list of error codes along with their cause and fix.
 */
class Mo_Saml_Options_Enum_Error_Codes extends Mo_SAML_Basic_Enum {
	/**
	 * Contains details associated with the error.
	 *
	 * @var array
	 */
	public static $error_codes = array(
		'WPSAMLERR001' => array(
			'code'           => 'WPSAMLERR001',
			'description'    => 'The Free Version of the plugin does not support encrypted assertion and IDP is sending Encrypted Assertion',
			'fix'            => 'Please turn off assertion encryption in your IDP to test the SSO flow.',
			'cause'          => 'Encrypted Assertion From IDP',
			'testConfig_msg' => 'Your IdP is sending encrypted assertion which is not supported in free version.',
		),
		'WPSAMLERR002' => array(
			'code'           => 'WPSAMLERR002',
			'description'    => 'This error occurs when the plugin cant find the nameID attribute in the IDP response.',
			'fix'            => 'Please configure your IDP to send a NameID attribute. If it is already configured then the user with which you are trying might have the blank NameID mapped attribute.',
			'cause'          => 'NameID missing',
			'testConfig_msg' => 'NameID may not be configured at the IDP or the user does not have a valid NameID value.',
		),
		'WPSAMLERR003' => array(
			'code'           => 'WPSAMLERR003',
			'description'    => 'No signature was found in the SAML Response or Assertion.',
			'fix'            => 'It is required by the SAML 2.0 standard that either the response or assertion is signed. Please enable the same in your IDP.',
			'cause'          => 'Unsigned Response or Assertion',
			'testConfig_msg' => 'No signature found in SAML Response or Assertion. Please sign at least one of them.',
		),
		'WPSAMLERR004' => array(
			'code'           => 'WPSAMLERR004',
			'description'    => 'This error ocurrs  when certificate present in SAML Response does not match with the certificate configured in the plugin.',
			'fix'            => 'To fix this error, copy the certificate value shown in the Test Configuration window and paste it in X.509 Certificate field in Service Provider Setup tab of the plugin.',
			'cause'          => 'Mismatch in Certificate',
			'testConfig_msg' => 'X.509 Certificate field in plugin does not match the certificate found in SAML Response.',
		),
		'WPSAMLERR005' => array(
			'code'           => 'WPSAMLERR005',
			'description'    => 'This error is displayed when there is an issue in creating user in WordPress.',
			'fix'            => 'There has been some issue with user creation in wordpress copy the error message and reach out us at <a href="mailto:samlsupport@xecurify.com">samlsupport@xecurify.com</a> with your registered email.',
			'cause'          => 'User Creation Failed',
			'testConfig_msg' => 'Something went wrong while creating the user. Please reach out to us with the debug logs.',
		),
		'WPSAMLERR006' => array(
			'code'           => 'WPSAMLERR006',
			'description'    => 'This error is Displayed when IDP returns a status code other than SUCCESS.<br/> The following are some of the common Status Code errors that you might encounter:<br/>
                                    <u>Requester:</u> The IDP sends this status code when it doesn\'t like the SAML request. For example: The IDP was expecting a signed request but it received an unsigned one.<br/>
                                    <u>Responder:</u> The IDP side of configuration is not correct. For ex: The ACS URL is not properly configured at the IDP end.<br/>
                                    <u>AuthnFailed:</u> Some IDPs send this status code if the signature verification of the SAML Request fails.',
			'fix'            => 'You will need to double check the configuration between your IDP and SP to fix this issue.',
			'cause'          => 'Invalid Status Code',
			'testConfig_msg' => 'Identity Provider has sent %s status code in SAML Response. Please check IdP logs.',
		),
		'WPSAMLERR007' => array(
			'code'           => 'WPSAMLERR007',
			'description'    => 'This can happen when your SP clock is behind the IDP clock.',
			'fix'            => 'You will need to sync the time between your IDP and SP or you can turn off the <b>Assertion Time Validity</b> toggle in the Service Provider Setup tab.',
			'cause'          => 'SP clock is behind IDP',
			'testConfig_msg' => '',
		),
		'WPSAMLERR008' => array(
			'code'           => 'WPSAMLERR008',
			'description'    => 'This can happen when your SP clock is ahead of the IDP clock.',
			'fix'            => 'You will need to sync the time between your IDP and SP or you can turn off the <b>Assertion Time Validity</b> toggle in the Service Provider Setup tab.',
			'cause'          => 'SP clock is ahead of IDP',
			'testConfig_msg' => '',
		),
		'WPSAMLERR009' => array(
			'code'           => 'WPSAMLERR009',
			'description'    => 'This happens when you have configured wrong Audience URL in your Identity Provider.',
			'fix'            => 'To fix this navigate to Service Provider Metadata tab and copy the Audience URL from metadata table and paste it in the Audience URL field in your IDP.',
			'cause'          => 'Wrong Audience URL',
			'testConfig_msg' => 'The value of \'Audience URI\' field on Identity Provider\'s side is incorrect',
		),
		'WPSAMLERR010' => array(
			'code'           => 'WPSAMLERR010',
			'description'    => 'This happens when you have configured wrong IDP Entity ID in the plugin.',
			'fix'            => 'To fix this navigate to Service Provider Setup tab and paste the correct IDP Entity ID in the required field.',
			'cause'          => 'Wrong IDP Entity ID',
			'testConfig_msg' => 'IdP Entity ID configured and the one found in SAML Response do not match',
		),
		'WPSAMLERR011' => array(
			'code'           => 'WPSAMLERR011',
			'description'    => 'This error is displayed when the Username value is greater than 60 characters.',
			'fix'            => 'To fix this issue, please configure your IDP to send a valid email address as the NameID value, which should be less than 60 characters in length.',
			'cause'          => 'Username length limit exceeded',
			'testConfig_msg' => 'The NameID value is greater than 60 characters in length. Please configure your IDP to send a proper NameID value.',
		),
		'WPSAMLERR012' => array(
			'code'           => 'WPSAMLERR012',
			'description'    => 'This error occurs when certificate present in SAML Response does not match with the certificate configured in the plugin after encoding.',
			'fix'            => 'To fix this error, turn off the <b>Character encoding</b> toggle in the Service Provider Setup tab.',
			'cause'          => 'Mismatch in Certificate',
			'testConfig_msg' => 'X.509 Certificate in plugin does not match the certificate found in SAML Response due to the character encoding.',
		),
	);
}
