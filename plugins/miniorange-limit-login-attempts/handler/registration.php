<?php

	class Mo_lla_RegistrationHandler
	{
		function __construct()
		{
			add_filter( 'registration_errors' , array($this, 'mo_lla_registration_validations' ), 10, 3 );			
		}

		function mo_lla_registration_validations( $errors, $sanitized_user_login, $user_email ) 
		{
			global $mollaUtility;

			if(get_option('mo_lla_activate_recaptcha_for_registration')){
                if(get_option('mo_lla_recaptcha_version')=='reCAPTCHA_v3')
                    $recaptchaError = $mollaUtility->verify_recaptcha_3(sanitize_text_field($_POST['g-recaptcha-response']));
                else if(get_option('mo_lla_recaptcha_version')=='reCAPTCHA_v2')
                    $recaptchaError = $mollaUtility->verify_recaptcha(sanitize_text_field($_POST['g-recaptcha-response']));
                if(!empty($recaptchaError->errors))
                $errors = $recaptchaError;
            }

			if($mollaUtility->check_if_valid_email($user_email) && empty($recaptchaError->errors))
				$errors->add( 'blocked_email_error', __( '<strong>ERROR</strong>: Your email address is not allowed to register. Please select different email address.') );
			else if(!empty($recaptchaError->errors))
				$errors = $recaptchaError;
				
			return $errors;
		}

	}
	new Mo_lla_RegistrationHandler;