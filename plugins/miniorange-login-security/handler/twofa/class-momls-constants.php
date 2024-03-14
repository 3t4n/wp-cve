<?php
/**
 * File contains function for strings translation.
 *
 * @package miniOrange-login-security/handler/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Momls_Constants' ) ) {
	/**
	 * Class Mo2fConstants
	 */
	class Momls_Constants {
		/**
		 * Translates the strings.
		 *
		 * @param string $text The string to be translated.
		 * @return string
		 */
		public static function momls_lang_translate( $text ) {
			switch ( $text ) {
				case 'Successfully validated.':
					return __( 'Successfully validated.', 'miniorange-login-security' );
				case 'SCAN_QR_CODE':
					return __( 'Please scan the QR Code now.', 'miniorange-login-security' );
				case 'Security Questions':
					return __( 'Security Questions', 'miniorange-login-security' );
				case 'Google Authenticator':
					return __( 'Google Authenticator', 'miniorange-login-security' );
				case 'Authy Authenticator':
					return __( 'Authy Authenticator', 'miniorange-login-security' );
				case 'Transaction limit exceeded. Please contact your administrator':
					return __( 'Transaction limit exceeded. Please contact your administrator', 'miniorange-login-security' );
				case 'Free Trial has already been taken or expired for this plugin. Please upgrade to a premium plan.':
					return __( 'Free Trial has already been taken or expired for this plugin. Please upgrade to a premium plan.', 'miniorange-login-security' );
				case 'Invalid format.':
					return __( 'Invalid format.', 'miniorange-login-security' );
				case 'Mobile registration failed.':
					return __( 'Mobile registration failed.', 'miniorange-login-security' );
				case 'There was an error processing the challenge user request.':
					return __( 'There was an error processing the challenge user request.', 'miniorange-login-security' );
				case 'What is your first company name?':
					return __( 'What is your first company name?', 'miniorange-login-security' );
				case 'What was your childhood nickname?':
					return __( 'What was your childhood nickname?', 'miniorange-login-security' );
				case 'In what city did you meet your spouse/significant other?':
					return __( 'In what city did you meet your spouse/significant other?', 'miniorange-login-security' );
				case 'What is the name of your favorite childhood friend?':
					return __( 'What is the name of your favorite childhood friend?', 'miniorange-login-security' );
				case "What was your first vehicle's registration number?":
					return __( "What was your first vehicle's registration number?", 'miniorange-login-security' );
				case "What is your grandmother's maiden name?":
					return __( "What is your grandmother's maiden name?", 'miniorange-login-security' );
				case 'Who is your favourite sports player?':
					return __( 'Who is your favourite sports player?', 'miniorange-login-security' );
				case 'What is your favourite sport?':
					return __( 'What is your favourite sport?', 'miniorange-login-security' );
				case 'In what city or town was your first job':
					return __( 'In what city or town was your first job', 'miniorange-login-security' );
				case 'What school did you attend for sixth grade?':
					return __( 'What school did you attend for sixth grade?', 'miniorange-login-security' );
				case 'G_AUTH':
					return __( 'Google Authenticator', 'miniorange-login-security' );
				case 'AUTHY_2FA':
					return __( 'Authy 2-Factor Authentication', 'miniorange-login-security' );
				case 'An unknown error occurred while creating the end user.':
					return __( 'An unknown error occurred while creating the end user.', 'miniorange-login-security' );
				case 'An unknown error occurred while challenging the user':
					return __( 'An unknown error occurred while challenging the user.', 'miniorange-login-security' );
				case 'An unknown error occurred while generating QR Code for registering mobile.':
					return __( 'An unknown error occurred while generating QR Code for registering mobile.', 'miniorange-login-security' );
				case 'An unknown error occurred while validating the user\'s identity.':
					return __( 'An unknown error occurred while validating the user\'s identity.', 'miniorange-login-security' );
				case 'Customer not found.':
					return __( 'Customer not found.', 'miniorange-login-security' );
				case 'The customer is not valid ':
					return __( 'The customer is not valid', 'miniorange-login-security' );
				case 'The user is not valid ':
					return __( 'The user is not valid ', 'miniorange-login-security' );
				case 'Customer already exists.':
					return __( 'Customer already exists.', 'miniorange-login-security' );
				case 'Customer Name is null':
					return __( 'Customer Name is null', 'miniorange-login-security' );
				case 'Customer check request failed.':
					return __( 'Customer check request failed.', 'miniorange-login-security' );
				case 'Invalid username or password. Please try again.':
					return __( 'Invalid username or password. Please try again.', 'miniorange-login-security' );
				case 'You are not authorized to perform this operation.':
					return __( 'You are not authorized to perform this operation.', 'miniorange-login-security' );
				case 'Invalid request. No such challenge request was initiated.':
					return __( 'Invalid request. No such challenge request was initiated.', 'miniorange-login-security' );
				case 'No OTP Token for the given request was found.':
					return __( 'No OTP Token for the given request was found.', 'miniorange-login-security' );
				case 'Query submitted.':
					return __( 'Query submitted.', 'miniorange-login-security' );
				case 'Invalid parameters.':
					return __( 'Invalid parameters.', 'miniorange-login-security' );
				case 'Alternate email cannot be same as primary email.':
					return __( 'Alternate email cannot be same as primary email.', 'miniorange-login-security' );
				case 'CustomerId is null.':
					return __( 'CustomerId is null.', 'miniorange-login-security' );
				case 'You are not authorized to create users. Please upgrade to premium plan. ':
					return __( 'You are not authorized to create users. Please upgrade to premium plan. ', 'miniorange-login-security' );
				case 'Your user creation limit has been completed. Please upgrade your license to add more users.':
					return __( 'Your user creation limit has been completed. Please upgrade your license to add more users.', 'miniorange-login-security' );
				case 'Username cannot be blank.':
					return __( 'Username cannot be blank.', 'miniorange-login-security' );
				case 'End user created successfully.':
					return __( 'End user created successfully.', 'miniorange-login-security' );
				case 'There was an exception processing the update user request.':
					return __( 'There was an exception processing the update user request.', 'miniorange-login-security' );
				case 'End user found.':
					return __( 'End user found.', 'miniorange-login-security' );
				case 'End user found under different customer. ':
					return __( 'End user found under different customer. ', 'miniorange-login-security' );
				case 'End user not found.':
					return __( 'End user not found.', 'miniorange-login-security' );
				case 'Customer successfully registered.':
					return __( 'Customer successfully registered.', 'miniorange-login-security' );
				case 'Customer registration failed.':
					return __( 'Customer registration failed.', 'miniorange-login-security' );
				case 'There was an error processing the register mobile request.':
					return __( 'There was an error processing the register mobile request.', 'miniorange-login-security' );
				case 'There was an exception processing the get user request.':
					return __( 'There was an exception processing the get user request.', 'miniorange-login-security' );
				case 'End User retrieved successfully.':
					return __( 'End User retrieved successfully.', 'miniorange-login-security' );
				case 'COMPLETED_TEST':
					return __( 'You have successfully completed the test.', 'miniorange-login-security' );
				case 'INVALID_EMAIL_VER_REQ':
					return __( 'Invalid request. test case failed.', 'miniorange-login-security' );
				case 'INVALID_ENTRY':
					return __( 'All the fields are required. Please enter valid entries.', 'miniorange-login-security' );
				case 'INVALID_PASSWORD':
					return __( 'You already have an account with miniOrange. Please enter a valid password.', 'miniorange-login-security' );
				case 'INVALID_REQ':
					return __( 'Invalid request. Please try again', 'miniorange-login-security' );
				case 'INVALID_OTP':
					return __( 'Invalid OTP. Please try again.', 'miniorange-login-security' );
				case 'INVALID_EMAIL_OR_PASSWORD':
					return __( 'Invalid email or password. Please try again.', 'miniorange-login-security' );
				case 'PASSWORDS_MISMATCH':
					return __( 'Password and Confirm password do not match.', 'miniorange-login-security' );
				case 'ENTER_YOUR_EMAIL_PASSWORD':
					return __( 'Please enter your registered email and password.', 'miniorange-login-security' );
				case 'ERROR_DURING_REGISTRATION':
					return __( 'Error occured while registration. Please try again.', 'miniorange-login-security' );
				case 'ERROR_DURING_PROCESS':
					return __( 'An error occured while processing your request. Please Try again.', 'miniorange-login-security' );
				case 'ERROR_DURING_PROCESS_EMAIL':
					return __( 'An error occured while processing your request. Please check your SMTP server is configured.', 'miniorange-login-security' );
				case 'ERROR_WHILE_SENDING_SMS':
					return __( 'There was an error in sending sms. Please click on Resend OTP to try again.', 'miniorange-login-security' );
				case 'ERROR_DURING_USER_REGISTRATION':
					return __( 'Error occurred while registering the user. Please try again.', 'miniorange-login-security' );
				case 'SET_AS_2ND_FACTOR':
					return __( 'is set as your 2 factor authentication method.', 'miniorange-login-security' );
				case 'ERROR_WHILE_SAVING_KBA':
					return __( 'Error occured while saving your kba details. Please try again.', 'miniorange-login-security' );
				case 'ANSWER_SECURITY_QUESTIONS':
					return __( 'Please answer the following security questions.', 'miniorange-login-security' );
				case 'ERROR_FETCHING_QUESTIONS':
					return __( 'There was an error fetching security questions. Please try again.', 'miniorange-login-security' );
				case 'INVALID_ANSWERS':
					return __( 'Invalid Answers. Please try again.', 'miniorange-login-security' );
				case 'MIN_PASS_LENGTH':
					return __( 'Choose a password with minimum length 6.', 'miniorange-login-security' );
				case 'ACCOUNT_RETRIEVED_SUCCESSFULLY':
					return __( 'Your account has been retrieved successfully.', 'miniorange-login-security' );
				case 'DEFAULT_2ND_FACTOR':
					return __( 'has been set as your default 2nd factor method', 'miniorange-login-security' );
				case 'VERIFY':
					return __( 'for verification to', 'miniorange-login-security' );
				case 'ERROR_IN_SENDING_EMAIL':
					return __( 'There was an error in sending email. Please click on Resend OTP to try again.', 'miniorange-login-security' );
				case 'EMAIL_IN_USE':
					return __( 'The email is already used by other user. Please register with other email.', 'miniorange-login-security' );
				case 'EMAIL_MANDATORY':
					return __( 'Please submit your query with email', 'miniorange-login-security' );
				case 'ERROR_WHILE_SUBMITTING_QUERY':
					return __( 'Your query could not be submitted. Please try again.', 'miniorange-login-security' );
				case 'QUERY_SUBMITTED_SUCCESSFULLY':
					return __( 'Thanks for getting in touch! We shall get back to you shortly.', 'miniorange-login-security' );
				case 'SETTINGS_SAVED':
					return __( 'Your settings are saved successfully.', 'miniorange-login-security' );
				case 'AUTHENTICATION_FAILED':
					return __( 'Authentication failed. Please try again to test the configuration.', 'miniorange-login-security' );
				case 'REGISTER_WITH_MO':
					return __( 'Invalid request. Please register with miniOrange before configuring your mobile.', 'miniorange-login-security' );
				case 'ENTER_EMAILID':
					return __( 'Please enter email-id to register.', 'miniorange-login-security' );
				case 'ENTER_VALUE':
					return __( 'Please enter a value to test your authentication.', 'miniorange-login-security' );
				case 'ENTER_OTP':
					return __( 'Please enter the one time passcode below.', 'miniorange-login-security' );
				case 'ERROR_IN_SENDING_OTP':
					return __( 'There was an error in sending one time passcode. Please click on Resend OTP to try again.', 'miniorange-login-security' );
				case 'ERROR_WHILE_VALIDATING_OTP':
					return __( 'Error occurred while validating the OTP. Please try again.', 'miniorange-login-security' );
				case 'TEST_GAUTH_METHOD':
					return __( 'to test Google Authenticator method.', 'miniorange-login-security' );
				case 'ERROR_IN_SENDING_OTP_CAUSES':
					return __( 'Error occurred while validating the OTP. Please try again. Possible causes:', 'miniorange-login-security' );
				case 'APP_TIME_SYNC':
					return __( 'Your App Time is not in sync.Go to settings and tap on tap on Sync Time now .', 'miniorange-login-security' );
				case 'ERROR_WHILE_VALIDATING_USER':
					return __( 'Error occurred while validating the user. Please try again.', 'miniorange-login-security' );
				case 'ONLY_DIGITS_ALLOWED':
					return __( 'Only digits are allowed. Please enter again.', 'miniorange-login-security' );
				case 'TEST_AUTHY_2FA':
					return __( 'to test Authy 2-Factor Authentication method.', 'miniorange-login-security' );
				case 'METHOD':
					return __( 'method.', 'miniorange-login-security' );
				case 'TO_TEST':
					return __( 'to test', 'miniorange-login-security' );
				case 'SET_2FA':
					return __( 'is set as your Two-Factor method.', 'miniorange-login-security' );
				case 'ACCOUNT_CREATED':
					return __( 'Your account has been created successfully.', 'miniorange-login-security' );
				case 'ACCOUNT_REMOVED':
					return __( 'Your account has been removed. Please contact your administrator.', 'miniorange-login-security' );
				case 'REGISTRATION_SUCCESS':
					return __( 'You are registered successfully.', 'miniorange-login-security' );
				case 'DENIED_REQUEST':
					return __( 'You have denied the request.', 'miniorange-login-security' );
				case 'DISABLED_2FA':
					return __( 'Two-Factor plugin has been disabled.', 'miniorange-login-security' );
				case 'ERROR_WHILE_SAVING_SETTINGS':
					return __( 'Error occurred while saving the settings.Please try again.', 'miniorange-login-security' );
				case 'INVALID_REQUEST':
					return __( 'Invalid request. Please register with miniOrange and configure 2-Factor to save your login settings.', 'miniorange-login-security' );
				case 'ACCOUNT_ALREADY_EXISTS':
					return __( 'You already have an account with miniOrange, please sign in.', 'miniorange-login-security' );
				case 'CONFIGURE_2FA':
					return __( 'to configure another 2 Factor authentication method.', 'miniorange-login-security' );
				case 'CLICK_HERE':
					return __( 'Click Here', 'miniorange-login-security' );
				case 'ERROR_CREATE_ACC_OTP':
					return __( 'An error occured while creating your account. Please try again by sending OTP again.', 'miniorange-login-security' );
				default:
					return $text;
			}
		}
	}

	new Momls_Constants();
}

