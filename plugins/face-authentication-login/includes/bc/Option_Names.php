<?php
/**
 * Store constants that share in this plugin
 */

namespace DataPeen\FaceAuth;


class Option_Names {
	const PIN_METHODS = 'selected_pin_methods';
	const SITE_VERIFIED = 'site_verified'; //whether the user has verified the site
	const VERIFY_TOKEN = 'verify_token'; //the field for verify token. This token is common for all users, similar to secret token
	const SECRET_TOKEN = 'datapeen_face_factor_secret_token'; //we don't store this key for every user. This would be an option
	const FACE_DATA_OK = 'face_data_ok'; //whether the user has entered sufficent face data
	const SECRET_TOKEN_VERIFIED = 'secret_token_verified';//whether the secret token is valid

	const EMAIL_TO_RECEIVE_TOKEN = 'email_receiver';


	const FACE_IMAGE_01 = 'face_image_01';
	const FACE_IMAGE_02 = 'face_image_02';
	const FACE_IMAGE_03 = 'face_image_03';
	const FACE_IMAGE_04 = 'face_image_04';
	const FACE_IMAGE_05 = 'face_image_05';

	const SESSION_USERNAME = 'datapeen_ff_username';
	const SESSION_KEY = 'datapeen_ff_session_key';//this session key is generated every time user try to login

	const AUTHENTICATOR_VERIFICATION_CODE = 'authenticator_verification_code';
	const AUTHENTICATOR_VERIFICATION_BUTTON = 'authenticator_verification_button';
	const AUTHENTICATOR_VERIFIED = 'authenticator_verified'; //if the method is verified by the current user
	const AUTHENTICATOR_KEY = 'authenticator_key'; //if the method is verified by the current user
	const AUTHENTICATOR_EMAIL = 'authenticator_email_address'; //address to receive authenticator code




}
