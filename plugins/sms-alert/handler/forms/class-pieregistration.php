<?php
/**
 * This file handles pie registration form authentication via sms notification
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */

if (! defined('ABSPATH') ) {
    exit;
}
if (! is_plugin_active('pie-register/pie-register.php') ) {
    return; 
}

/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * PieRegistrationForm class.
 */
class PieRegistrationForm extends FormInterface
{

    /**
     * Woocommerce registration popup form key
     *
     * @var $form_session_var2 Woocommerce registration popup form key
     */
    private $form_session_var2 = FormSessionVars::PIE_POPUP;

    /**
     * Phone Field Key.
     *
     * @var stirng
     */
    private $phone_field_key;
    
    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        $this->phone_field_key = 'phone';
        add_action('pieregister_registration_validation_after', array( $this, 'smsalertPieUserRegistration' ), 99, 2);
        add_filter('pie_register_frontend_output_after', array( $this, 'addRegisterOtp' ), 99, 1);
		add_filter('pie_register_frontend_login_output_after', array( $this, 'addLoginOtp' ), 99, 1);
        add_filter('sa_get_user_phone_no', array( $this, 'saUpdateBillingPhone' ), 10, 2);
    }
    
    /**
     * Update billing phone after registration.
     *
     * @param int $billing_phone billing phone.
     * @param int $user_id       user id.
     *
     * @return void
     */
    public function saUpdateBillingPhone( $billing_phone, $user_id )
    {
        if ( ! isset($_SESSION[ $this->form_session_var2 ]) ) {
            return $billing_phone;
        }
        if (isset($_SESSION['sa_mobile_pie']) ) {
            $phone = $_SESSION['sa_mobile_pie'];
            unset($_SESSION['sa_mobile_pie']);
            return ( ! empty($billing_phone) ) ? $billing_phone : $phone;
        }
        return $billing_phone;
    }
    
    /**
     * Add smsalert button to pie form.
     *
     * @param int $data form data.
     *
     * @return string
     */
    public function addLoginOtp( $data )
    {
		$default_login_otp   = smsalert_get_option('buyer_login_otp', 'smsalert_general');       
        if ('on' === $default_login_otp ) {
          $data .= do_shortcode('[sa_verify id="#piereg_login_form" user_selector="#user_login" pwd_selector="#user_pass" submit_selector="#wp-login-submit"]'); 
		}
        return $data;
    }
	
	/**
     * Add smsalert button to pie form.
     *
     * @param int $data form data.
     *
     * @return string
     */
    public function addRegisterOtp( $data )
    {
		$buyer_signup_otp = smsalert_get_option('buyer_signup_otp', 'smsalert_general');
        if ('on' === $buyer_signup_otp ) {
			$phone_field         = $this->getPhoneFieldKey();
			$enabled_country    = smsalert_get_option('checkout_show_country_code', 'smsalert_general');
			$data .= do_shortcode('[sa_verify phone_selector="#'.$phone_field.'" submit_selector= ".pie_submit"]');
			$inline_script = 'jQuery(".pie_register_reg_form .pie_wrap_buttons").prepend(\'<input name="pie_submit" type="hidden" value="Submit">\');'; 
			if ('on' === $enabled_country ) {
				$inline_script .='jQuery("#'.$phone_field.'").addClass("phone-valid");
				setTimeout(addStyle, 1000);
				function addStyle() {
					jQuery(".phone-valid").closest(".fieldset").parent().css({"overflow":"inherit"});
					jQuery(".phone-valid").closest(".fieldset").css({"overflow":"inherit"});
				}';
			}
			$data .= '<style>.sa-hide{display:none !important}.pie_register_reg_form .iti{float: left;width: 70%;}</style>';
			if ( ! wp_script_is( 'sainlinescript-handle-footer', 'enqueued' ) ) {
			 wp_register_script( 'sainlinescript-handle-footer', '', [], '', true );
			 wp_enqueue_script( 'sainlinescript-handle-footer'  );
			}		
			wp_add_inline_script( "sainlinescript-handle-footer", $inline_script);
		}
        return $data;
    }

    /**
     * This function shows registration error message.
     *
     * @param int $data   form data.
     * @param int $errors form errors.
     *
     * @return string
     */
    function smsalertPieUserRegistration( $data, $errors )
    { 
        SmsAlertUtility::checkSession();
		$phone_field = $this->getPhoneFieldKey();
        $user_phone = !SmsAlertUtility::isBlank($phone_field) ? $_POST[$phone_field] : null;
		if (! SmsAlertcURLOTP::validateCountryCode($user_phone)){                
			return $errors;
		}
        if (isset($_SESSION['sa_mobile_verified']) ) {
            unset($_SESSION['sa_mobile_verified']);
            return $errors;
        }
        $verify = check_ajax_referer('piereg_wp_registration_form_nonce', 'piereg_registration_form_nonce', false);
        if (!$verify) {
            return $errors->add('registration-error-invalid-nonce', __('Sorry, nonce did not verify.', 'sms-alert'));
        }
        if (sizeof($errors->errors) > 0) {
            return $errors;
        }
        if (isset($_REQUEST['option']) && 'smsalert_register_with_otp' === sanitize_text_field(wp_unslash($_REQUEST['option'])) ) {
            SmsAlertUtility::initialize_transaction($this->form_session_var2);
        }

        if ('on' !== smsalert_get_option('allow_multiple_user', 'smsalert_general') && ! SmsAlertUtility::isBlank($user_phone) ) {

            $getusers = SmsAlertUtility::getUsersByPhone('billing_phone', $user_phone);
            if (count($getusers) > 0 ) {
                return $errors->add("registration-error-number-exists", __('An account is already registered with this mobile number. Please login.', 'sms-alert'));
            }
        }

        if (isset($user_phone) && SmsAlertUtility::isBlank($user_phone) ) {
            return $errors->add("registration-error-invalid-phone", __('Please enter phone number.', 'pie-register'));
        }

        return $this->processFormFields($_POST['username'], $_POST['e_mail'], $user_phone, $errors);
    }
    
    /**
     * Get phone field key
     *
     * @return void
     */    
    function getPhoneFieldKey()
    {
        $fields = unserialize(get_option('pie_fields'));
        $keys = (is_array($fields)) ? array_keys($fields) : array();
        foreach ($keys as $key) {
            if (strcasecmp(strtolower($fields[$key]['label']), $this->phone_field_key)==0) {
                return str_replace(
                    "-", "_", sanitize_title(
                        $fields[$key]['type']."_"
                        .(isset($fields[$key]['id']) ? $fields[$key]['id'] : "")
                    )
                );
            }
        }
    }
    
    /**
     * This function processed form fields.
     *
     * @param string $username User name.
     * @param string $email    Email.
     * @param string $phone    Phone.
     * @param string $errors   errors.
     *
     * @return void
     */
    public function processFormFields( $username, $email, $phone, $errors )
    {
        global $phoneLogic;
        $phone_num = preg_replace('/[^0-9]/', '', $phone);

        if (! isset($phone_num) || ! SmsAlertUtility::validatePhoneNumber($phone_num) ) {
            return $errors->add("billing_phone_error", str_replace('##phone##', $phone_num, $phoneLogic->_get_otp_invalid_format_message()));
        }
        smsalert_site_challenge_otp($username, $email, $errors, $phone_num, 'phone');
    }

    /**
     * Check your otp setting is enabled or not.
     *
     * @return bool
     */
    public static function isFormEnabled()
    {
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();
        return ( $islogged && smsalert_get_option('buyer_signup_otp', 'smsalert_general') === 'on' ) ? true : false;
    }

    /**
     * Handle after failed verification
     *
     * @param object $user_login   users object.
     * @param string $user_email   user email.
     * @param string $phone_number phone number.
     *
     * @return void
     */
    public function handle_failed_verification( $user_login, $user_email, $phone_number )
    {
        SmsAlertUtility::checkSession();
        if ( ! isset($_SESSION[ $this->form_session_var2 ]) ) {
            return;
        }
        if (isset($_SESSION[ $this->form_session_var2 ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
        }
    }

    /**
     * Handle after post verification
     *
     * @param string $redirect_to  redirect url.
     * @param object $user_login   user object.
     * @param string $user_email   user email.
     * @param string $password     user password.
     * @param string $phone_number phone number.
     * @param string $extra_data   extra hidden fields.
     *
     * @return void
     */
    public function handle_post_verification( $redirect_to, $user_login, $user_email, $password, $phone_number, $extra_data )
    {
        SmsAlertUtility::checkSession();
        if ( ! isset($_SESSION[ $this->form_session_var2 ]) ) {
            return;
        }
        $_SESSION['sa_mobile_verified'] = true;
        $_SESSION['sa_mobile_pie']  = $phone_number;
        if (isset($_SESSION[ $this->form_session_var2 ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('VALID_OTP'), 'success'));
        }
    }

    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {
        unset($_SESSION[ $this->tx_session_id ]);
        unset($_SESSION[ $this->form_session_var2 ]);
    }

    /**
     * Check current form submission is ajax or not
     *
     * @param bool $is_ajax bool value for form type.
     *
     * @return bool
     */
    public function is_ajax_form_in_play( $is_ajax )
    {
        SmsAlertUtility::checkSession();
        return isset($_SESSION[ $this->form_session_var2 ]) ? true : $is_ajax;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {  
    }
}
new PieRegistrationForm();
