<?php
/**
 * This file handles WooCommerceRegistrationForm via sms notification
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

/**
 * Woocommerce Registration handler class.
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */
class WooCommerceRegistrationForm extends FormInterface
{

    /**
     * Woocommerce default registration form key
     *
     * @var $form_session_var Woocommerce default registration form key
     */
    private $form_session_var = FormSessionVars::WC_DEFAULT_REG;
    /**
     * Woocommerce registration popup form key
     *
     * @var $form_session_var2 Woocommerce registration popup form key
     */
    private $form_session_var2 = FormSessionVars::WC_REG_POPUP;
    /**
     * Woocommerce registration with mobile form key
     *
     * @var $form_session_var3 Woocommerce registration with mobile form key
     */
    private $form_session_var3 = FormSessionVars::WC_REG_WTH_MOB;
    /**
     * If OTP in popup is enabled or not
     *
     * @var $popup_enabled If OTP in popup is enabled or not
     */
    private $popup_enabled;

    /**
     * Handles registration form submit.
     *
     * @return string
     */
    public function handleForm()
    {
        
        $this->popup_enabled = ( smsalert_get_option('otp_in_popup', 'smsalert_general', 'on') === 'on' ) ? true : false;
        $buyer_signup_otp = smsalert_get_option('buyer_signup_otp', 'smsalert_general');
        
        if ('on' === $buyer_signup_otp ) {
            if (isset($_REQUEST['register']) ) {
				if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                 wc_clear_notices();
				}
                add_filter('woocommerce_registration_errors', array( $this, 'woocommerceSiteRegistrationErrors' ), 10, 3);
            }

            if (is_plugin_active('dokan-lite/dokan.php') ) {
                add_action('dokan_reg_form_field', array( $this, 'smsalertAddDokanPhoneField' ));
                add_action('dokan_vendor_reg_form_start', array( $this, 'smsalertAddDokanPhoneField' ));
                add_action('dokan_vendor_reg_form_start', array( $this, 'smsalertAddDokanVendorRegField' ));
            } else {
                add_action('woocommerce_register_form', array( $this, 'smsalertAddPhoneField' ), 1);
            }

            if (is_plugin_active('dc-woocommerce-multi-vendor/dc_product_vendor.php') ) {
                add_action('wcmp_vendor_register_form', array( $this, 'smsalertAddPhoneField' ));
            } 

            if ($this->popup_enabled ) {
                add_action('woocommerce_register_form_end', array( $this, 'smsalertDisplayRegisterOTPBtn' ));
                if (is_plugin_active('easy-login-woocommerce/xoo-el-main.php') || is_plugin_active('easy-login-woocommerce-premium/xoo-el-main.php') ) {
                    add_action('xoo_el_register_add_fields', array( $this, 'smsalertAddPhoneField' ));    
                    add_action('xoo_el_register_add_fields', array( $this, 'xooElSmsalertDisplayRegisterOTPBtn' ));
                }
				
            }
			add_action('register_form', array( $this, 'edumaSmsalertDisplayRegisterOTPBtn' ), 10);
        }
        
        $enable_otp_user_update = get_option('smsalert_otp_user_update', 'on');
        if ('on' === $enable_otp_user_update ) {
            add_action('woocommerce_after_save_address_validation', array( $this, 'validateWoocommerceSaveAddress' ), 10, 3);        
            add_filter('woocommerce_address_to_edit', array( $this, 'getBillingFieldsProfilepage' ), 10, 2);
        }        

        $signup_with_mobile = smsalert_get_option('signup_with_mobile', 'smsalert_general', 'off');
        if ('on' === $signup_with_mobile ) {
            if (is_plugin_active('easy-login-woocommerce/xoo-el-main.php') || is_plugin_active('easy-login-woocommerce-premium/xoo-el-main.php') ) {
                add_action('xoo_el_register_form_end', array( $this, 'smsalertDisplaySignupWithMobile' ), 10);
                add_action('xoo_el_form_end', array( $this, 'smsalertDisplaySignupWithMobile' ), 10);
            }
            add_action('woocommerce_register_form_end', array( $this, 'smsalertDisplaySignupWithMobile' ), 10);	
			
        }
		add_filter( 'before_sa_campaign_send',array( $this, 'modifyMessage' ),10, 3 );
        $this->routeData();
    }
	
    /**
     * replace sms campaign text variable.
     *
     * @param string $message message.
     * @param string $type type.
     * @param int $id id.
     *
     * @return string
     */
	public function modifyMessage($message, $type, $post_id) {
		if( 'users_data' === $type)
		{
			$user = get_userdata($post_id);
			$search = array(
            '[username]',
            '[email]',
            '[billing_phone]',
            );

            $replace           = array(
            $user->user_login,
            $user->user_email,
            get_user_meta( $post_id, 'billing_phone', true )
            );
            $message = str_replace($search, $replace, $message);
		}
		return $message;
	}

    /**
     * Modify billing phone on page load at profile page.
     *
     * @param $address      address.
     * @param $load_address load_address.
     *
     * @return string     
     **/
    public function getBillingFieldsProfilepage( $address, $load_address )
    {
        foreach ( $address as $key => $field ) {
            if ('billing_phone' === $key ) {
                $address['billing_phone']['value'] = SmsAlertUtility::formatNumberForCountryCode($field['value']);
            }
        }
        return $address;
        // $args['value'] = SmsAlertUtility::formatNumberForCountryCode( $value );
    }

    /**
     * Sign up with otp starts.
     *
     * @param $form form.
     * @param $args args.
     *
     * @return void    
     **/
    public function smsalertDisplaySignupWithMobile($form = null, $args=array())
    {
        if ($form == null || $form == 'register') {
            echo wp_kses_post('<div class="lwo-container"><div class="sa_or">OR</div><button type="button" class="button sa_myaccount_btn" name="sa_myaccount_btn_signup" value="' . __('Signup with Mobile', 'sms-alert') . '" style="width: 100%; display:block"><span class="button__text">' . __('Signup with Mobile', 'sms-alert') . '</span></button></div>');

            if (is_plugin_active('google-captcha/google-captcha.php')) {
                add_action('wp_footer', array( $this, 'addSignupwithmobileShortcode' ), 1);
			}
			else{
				add_action('wp_footer', array( $this, 'addSignupwithmobileShortcode' ), 15); 
			} 
        }
    }
	
	/**
     * Add signup with mobile shortcode.
     *
     * @return string
     */
    public static function addSignupwithmobileShortcode()
    {
        echo '<div class="signupwithmobile">'.do_shortcode('[sa_signupwithmobile]').'</div>';
        echo '<style>.signupwithmobile .sa-signupwithotp-form{display:none;}.signupwithmobile .sa_default_signup_form{display:block;}</style>';
    }

    /**
     * Add smsalert phone button in ultimate form.
     *
     * @param int    $user_id      Userid of the user.
     * @param string $load_address Currently not in use in this function.
     * @param string $address      Currently not in use in this function.
     *
     * @return void
     */
    public function validateWoocommerceSaveAddress( $user_id, $load_address, $address )
    {
        $db_billing_phone = get_post_meta($user_id, '_billing_phone', true);
        $user_phone       = ( ! empty($_POST['billing_phone']) ) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';
        if ($db_billing_phone !== $user_phone ) {
            if (smsalert_get_option('allow_multiple_user', 'smsalert_general') !== 'on' && ! SmsAlertUtility::isBlank($user_phone) ) {
                $_POST['billing_phone'] = SmsAlertcURLOTP::checkPhoneNos($user_phone);

                $getusers = SmsAlertUtility::getUsersByPhone('billing_phone', $user_phone, array( 'exclude' => array( $user_id ) ));
                if (count($getusers) > 0 ) {
                    wc_add_notice(sprintf(__('An account is already registered with this mobile number.', 'woocommerce'), '<strong>Billing Phone</strong>'), 'error');
                }
            }
        }
    }

    /**
     * This function checks whether this form is enabled or not.
     *
     * @return void
     */
    public static function isFormEnabled()
    {
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();
        return ( $islogged && (smsalert_get_option('buyer_signup_otp', 'smsalert_general') === 'on' || smsalert_get_option('signup_with_mobile', 'smsalert_general') === 'on') ) ? true : false;
    }

    /**
     * This function is used to route the request.
     *
     * @return void
     */
    public function routeData()
    {
        if (! array_key_exists('option', $_REQUEST) ) {
            return;
        }
        switch ( trim(sanitize_text_field(wp_unslash($_REQUEST['option']))) ) {
        case 'smsalert_register_otp_validate_submit':
            $this->handleAjaxRegisterValidateOtp($_REQUEST);
            break;
        case 'smsalert-registration-with-mobile':
            $this->handleSignWthOtp();
            break;

        case 'signwthmob':
            $this->processRegistration();
            break;
        }
    }

    /**
     * This function check mobile number exists or no when users signup with mobile number.
     *
     * @return void
     */
    public function handleSignWthOtp()
    {
        $verify = check_ajax_referer('smsalert_wp_signupwithmobile_nonce', 'smsalert_signupwithmobile_nonce', false);
        if (!$verify) {
            wp_send_json(SmsAlertUtility::_create_json_response(__('Sorry, nonce did not verify.', 'sms-alert'), 'error'));
        }
		if (is_plugin_active('google-captcha/google-captcha.php')) {
			$check_result = apply_filters( 'gglcptch_verify_recaptcha', true, 'string', 'sa_swm_form' );
			if ( true !== $check_result ) { 
			  wp_send_json(SmsAlertUtility::_create_json_response(__('The reCaptcha verification failed. Please try again.', 'sms-alert'), 'error'));
			}
		}
        global $phoneLogic;
        SmsAlertUtility::checkSession();
        if (isset($_SESSION['sa_mobile_verified']) ) {
            unset($_SESSION['sa_mobile_verified']);
        }
        if (isset($_REQUEST['option']) && sanitize_text_field(wp_unslash($_REQUEST['option']) === 'smsalert-registration-with-mobile') ) {
            $phone_no = ! empty($_REQUEST['billing_phone']) ? sanitize_text_field(wp_unslash($_REQUEST['billing_phone'])) : '';

            $billing_phone = SmsAlertcURLOTP::checkPhoneNos($phone_no);
            if (SmsAlertUtility::isBlank($phone_no)) {
                wp_send_json(SmsAlertUtility::_create_json_response(__('Please enter phone number.', 'sms-alert'), 'error'));
            } else if (! $billing_phone ) {

                $message = str_replace('##phone##', $phone_no, $phoneLogic->_get_otp_invalid_format_message());

                wp_send_json(SmsAlertUtility::_create_json_response($message, 'error'));
            }
            $user_info  = WPLogin::getUserFromPhoneNumber($billing_phone, 'billing_phone');
            $user_login = ( $user_info ) ? $user_info->data->user_login : '';
            $user = get_user_by('login', $user_login);
            $password='';
            //added for new user approve plugin
            $user = apply_filters('wp_authenticate_user', $user, $password);
            if (is_wp_error($user) ) {
                $msg   = SmsAlertUtility::_create_json_response(current($user->errors), 'error');
                wp_send_json($msg);
                exit();
            }  
            //-added for new user approve plugin
            SmsAlertUtility::initialize_transaction($this->form_session_var3);
            smsalert_site_challenge_otp(null, null, null, $billing_phone, 'phone', null, SmsAlertUtility::currentPageUrl(), true);
        }
    }

    /**
     * This function validates the OTP entered by user.
     *
     * @param int $data Request array.
     *
     * @return void
     */
    public function handleAjaxRegisterValidateOtp( $data )
    {
        SmsAlertUtility::checkSession();
        if (( ! isset($_SESSION[ $this->form_session_var2 ]) ) && ( ! isset($_SESSION[ $this->form_session_var3 ]) ) ) {
            return;
        }

        if (strcmp($_SESSION['phone_number_mo'], $data['billing_phone']) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('PHONE_MISMATCH'), 'error'));
        } else {
            do_action('smsalert_validate_otp', 'phone');
        }
    }

    /**
     * This function displays a OTP button on registration form.
     *
     * @return void
     */
    public static function smsalertDisplayRegisterOTPBtn()
    {
        $unique_class    = 'sa-class-'.mt_rand(1, 100);
        echo '<script>
		jQuery("form.register").each(function () 
		{
			if(!jQuery(this).hasClass("sa-reg-form"))
			{
			jQuery(this).addClass("'.$unique_class.' sa-reg-form");
			}		
		});		
		</script>';
        echo do_shortcode('[sa_verify phone_selector="#reg_billing_phone" submit_selector= ".'.$unique_class.'.register .woocommerce-Button"]');
    }
	 
    
    /**
     * This function displays a OTP button on registration form.
     *
     * @return void.
     */
    public static function xooElSmsalertDisplayRegisterOTPBtn()
    {
        $unique_class    = 'sa-class-'.mt_rand(1, 100);
        echo '<script>
		jQuery("form.xoo-el-form-register").each(function () 
		{
			if(!jQuery(this).hasClass("sa-reg-form"))
			{
			jQuery(this).addClass("'.$unique_class.' sa-reg-form");
			}		
		});		
		</script>';
        echo do_shortcode('[sa_verify phone_selector="#reg_billing_phone" submit_selector= ".'.$unique_class.' .xoo-el-register-btn"]');
    }
	/**
     * This function displays a OTP button on registration form.
     *
     * @return void.
     */
    public static function edumaSmsalertDisplayRegisterOTPBtn()
    {         
         echo do_shortcode('[sa_verify phone_selector="#reg_billing_phone" submit_selector= ".thim-register .submit .button"]'); 
    }

    /**
     * This function shows error message.
     *
     * @param int    $error_hook Error hook.
     * @param string $err_msg    Error message.
     * @param string $type       Type.
     *
     * @return void.
     */
    public function show_error_msg( $error_hook = null, $err_msg = null, $type = null )
    {
        if (isset($_SESSION[ $this->form_session_var2 ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response($err_msg, $type));
        } else {
            return new WP_Error($error_hook, $err_msg);
        }
    }

    /**
     * This function shows registration error message.
     *
     * @param array  $errors   Errors array.
     * @param string $username Username.
     * @param string $email    Email Id.
     *
     * @return void.
     */
    public function woocommerceSiteRegistrationErrors( $errors, $username, $email )
    {
        SmsAlertUtility::checkSession();
		$user_phone = ( ! empty($_POST['billing_phone']) ) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';		
		if (! SmsAlertcURLOTP::validateCountryCode($user_phone)) {		
			return $errors;
		}
	
        if (isset($_SESSION['sa_mobile_verified']) ) {
            unset($_SESSION['sa_mobile_verified']);
            return $errors;
        }
        $password = ! empty($_REQUEST['password']) ? sanitize_text_field(wp_unslash($_REQUEST['password'])) : '';
        if (! SmsAlertUtility::isBlank(array_filter($errors->errors)) ) {
            return $errors;
        }
        if (isset($_REQUEST['option']) && sanitize_text_field(wp_unslash($_REQUEST['option']) === 'smsalert_register_with_otp') ) {
            SmsAlertUtility::initialize_transaction($this->form_session_var2);
        } else {
            SmsAlertUtility::initialize_transaction($this->form_session_var);
        }

       

        if (smsalert_get_option('allow_multiple_user', 'smsalert_general') !== 'on' && ! SmsAlertUtility::isBlank($user_phone) ) {

            $getusers = SmsAlertUtility::getUsersByPhone('billing_phone', $user_phone);
            if (count($getusers) > 0 ) {
                return new WP_Error('registration-error-number-exists', __('An account is already registered with this mobile number. Please login.', 'sms-alert'));
            }
        }

        if (isset($user_phone) && SmsAlertUtility::isBlank($user_phone) ) {
            return new WP_Error('registration-error-invalid-phone', __('Please enter phone number.', 'sms-alert'));
        }

        do_action('woocommerce_register_post', $username, $email, $errors);

        if ($errors->get_error_code() ) {
            throw new Exception($errors->get_error_message());
        }
        
        return $this->processFormFields($username, $email, $errors, $password);
    }

    /**
     * This function processed form fields.
     *
     * @param string $username User name.
     * @param string $email    Email Id.
     * @param array  $errors   Errors array.
     * @param string $password Password.
     *
     * @return void.
     */
    public function processFormFields( $username, $email, $errors, $password )
    {
        global $phoneLogic;
        $phone_no  = ( ! empty($_POST['billing_phone']) ) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';
        $phone_num = preg_replace('/[^0-9]/', '', $phone_no);

        if (! isset($phone_num) || ! SmsAlertUtility::validatePhoneNumber($phone_num) ) {
            return new WP_Error('billing_phone_error', str_replace('##phone##', $phone_num, $phoneLogic->_get_otp_invalid_format_message()));
        }
        smsalert_site_challenge_otp($username, $email, $errors, $phone_num, 'phone', $password);
    }

    /**
     * This function adds a phone field.
     *
     * @return void.
     */
    public function smsalertAddPhoneField()
    {
        woocommerce_form_field(
            'billing_phone',
            array(
            'type'        => 'tel',
            'required'    => true,
            'input_class' => array('phone-valid'),
            'label'       => SmsAlertMessages::showMessage('Phone'),
            'id'       => 'reg_billing_phone',
            ),
            ( isset($_POST['billing_phone']) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '' )
        );
        remove_action('woocommerce_register_form', array( $this,'smsalertAddPhoneField' ));
    }

    /**
     * This function adds phone field to Dokan form.
     *
     * @return void.
     */
    public function smsalertAddDokanPhoneField()
    {
        $this->smsalertAddPhoneField();
        $inline_script = 'jQuery( window ).on("load", function() {
            jQuery( "#shop-phone" ).addClass("phone-valid");
            jQuery(".user-role input[type=\"radio\"]").change(function(e){
                if(jQuery(this).val() == "seller") {
					initialiseCountrySelector("#shop-phone");
                    jQuery("#reg_billing_phone").parent().hide();
                    jQuery("label[for=reg_billing_phone]").hide();
                    
                }
                else {
                    jQuery("#reg_billing_phone").parent().show();
                    jQuery("label[for=reg_billing_phone]").show();
                }
            });
            jQuery( "#shop-phone" ).change(function() {
                jQuery("#reg_billing_phone").val(this.value);
                if( typeof sa_otp_settings !=  "undefined" && sa_otp_settings["show_countrycode"] == "on" )
                {
                    var default_cc = jQuery(this).intlTelInput("getSelectedCountryData");    
                    jQuery("#reg_billing_phone").intlTelInput("setCountry",default_cc.iso2);
                    var phone_num = jQuery("#reg_billing_phone").intlTelInput("getNumber");
                    //var phone_num = jQuery("input:hidden[name=phone]").val();
                    jQuery("#reg_billing_phone").next("[name=billing_phone]").val(phone_num);
                }
            });
        });
        jQuery(document).ready(function(){
            jQuery("#shop-phone").on("countrychange", function () {
                var default_cc = jQuery(this).intlTelInput("getSelectedCountryData");
                jQuery("#reg_billing_phone").intlTelInput("setCountry",default_cc.iso2);
            });
        });';
		if ( ! wp_script_is( 'sainlinescript-handle-footer', 'enqueued' ) ) {
         wp_register_script( 'sainlinescript-handle-footer', '', [], '', true );
         wp_enqueue_script( 'sainlinescript-handle-footer'  );
		}		
		wp_add_inline_script( "sainlinescript-handle-footer", $inline_script);
    }

    /**
     * This function is executed on dokan vendor registration form.
     *
     * @return void.
     */
    public function smsalertAddDokanVendorRegField()
    {
        ?>
        <script>
            jQuery('#reg_billing_phone').parent().hide();
        </script>
        <?php
    }

    /**
     * This function handles the failed verification.
     *
     * @param string $user_login   User login.
     * @param string $user_email   Email Id.
     * @param string $phone_number Phone number.
     *
     * @return void.
     */
    public function handle_failed_verification( $user_login, $user_email, $phone_number )
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) && ! isset($_SESSION[ $this->form_session_var2 ]) && ! isset($_SESSION[ $this->form_session_var3 ]) ) {
            return;
        }
        if (isset($_SESSION[ $this->form_session_var ]) ) {
            smsalert_site_otp_validation_form($user_login, $user_email, $phone_number, SmsAlertUtility::_get_invalid_otp_method(), 'phone', false);
        }
        if (isset($_SESSION[ $this->form_session_var2 ]) || isset($_SESSION[ $this->form_session_var3 ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
        }
    }

    /**
     * This function is executed after verification code is executed.
     *
     * @param string $redirect_to  Url to be redirected to.
     * @param string $user_login   User login.
     * @param string $user_email   Email Id.
     * @param string $password     Password.
     * @param string $phone_number Phone number.
     * @param array  $extra_data   Extra fields of the form.
     *
     * @return void.
     */
    public function handle_post_verification( $redirect_to, $user_login, $user_email, $password, $phone_number, $extra_data )
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) && ! isset($_SESSION[ $this->form_session_var2 ]) && ! isset($_SESSION[ $this->form_session_var3 ]) ) {
            return;
        }
        $_SESSION['sa_mobile_verified'] = true;
        if (isset($_SESSION[ $this->form_session_var2 ]) || isset($_SESSION[ $this->form_session_var3 ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('VALID_OTP'), 'success'));
        }
    }

    /**
     * This function removes otp session.
     *
     * @return void.
     */
    public function unsetOTPSessionVariables()
    {
        unset($_SESSION[ $this->tx_session_id ]);
        unset($_SESSION[ $this->form_session_var ]);
        unset($_SESSION[ $this->form_session_var2 ]);
        unset($_SESSION[ $this->form_session_var3 ]);
    }

    /**
     * This function checks if the ajax form is activated or not.
     *
     * @param bool $is_ajax whether this is an ajax request or not.
     *
     * @return void.
     */
    public function is_ajax_form_in_play( $is_ajax )
    {
        SmsAlertUtility::checkSession();
        return ( isset($_SESSION[ $this->form_session_var2 ]) || isset($_SESSION[ $this->form_session_var3 ]) ) ? true : $is_ajax;
    }

    /**
     * This function handles form options.
     *
     * @return void.
     */
    public function handleFormOptions()
    { 
    }

    /**
     * This function gets role display name from system name.
     *
     * @param bool $system_name System name of the role.
     *
     * @return array.
     */
    public static function get_user_roles( $system_name = null )
    {
        global $wp_roles;
        $roles = $wp_roles->roles;

        if (! empty($system_name) && array_key_exists($system_name, $roles) ) {
            return $roles[ $system_name ]['name'];
        } else {
            return $roles;
        }
    }


    /**
     * Process registration function.
     *
     * @return array.
     */
    public function processRegistration()
    {
        $tname = '';
        $phone = '';
        SmsAlertUtility::checkSession();
        if (isset($_POST['smsalert_name']) && $_POST['smsalert_name']!='' && isset($_SESSION['sa_mobile_verified'])) {

            $mail = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';

            $error = '';
            $page  = 2;

            $m  = isset($_REQUEST['billing_phone']) ? sanitize_text_field(wp_unslash($_REQUEST['billing_phone'])) : '';
            //number already exists then auto login
            $user_info  = WPLogin::getUserFromPhoneNumber($m, 'billing_phone');
            if ($user_info) {
                $user_login  = $user_info->data->user_login;
                if (! empty($_POST['redirect']) ) {
                    $redirect = wp_sanitize_redirect(wp_unslash($_POST['redirect']));
                } elseif (wp_get_referer() ) {
                    $redirect = wp_get_referer();
                }
                $user = get_user_by('login', $user_login);
                wp_set_auth_cookie($user->data->ID);
                $redirect        = apply_filters('woocommerce_login_redirect', $redirect, $user);
                wp_redirect($redirect);
                exit();
            }

            // important.
            $mobileaccp = 1;
			$userdata = array();
            if ($mobileaccp > 0 ) {

                $m = isset($_REQUEST['billing_phone']) ? sanitize_text_field(wp_unslash($_REQUEST['billing_phone'])) : '';
                if (is_numeric($m) ) {
                    $m     = sanitize_text_field($m);
                    $phone = $m;

                }

                $ulogin = str_replace('+', '', $phone);

                $password = '';
                if (empty($password) ) {
                    $password = wp_generate_password();
                }
                $prefix = 1;
                $mail = $ulogin . '@nomail.com';
                while (1) { 
                    $user = get_user_by('email', $mail);
                    if (!$user) {
                        break;
                    } else {
                        $mail = $prefix.'-'.$ulogin . '@nomail.com';
                        $prefix++;
                    }
                }    
                $prefix = 1;
                $username = $ulogin;                
                while (1) { 
                    $user = get_user_by('login', $username);
                    if (!$user) {
                        break;
                    } else {
                        $username = $prefix.'-'.$ulogin;
                        $prefix++;
                    }
                }
                $new_customer = wp_create_user($username, $password, $mail);
                if (! is_wp_error($new_customer) ) {

					$smsalert_defaultuserrole = get_option('smsalert_defaultuserrole', 'customer');

					$userdata = array(
					'ID'         => $new_customer,
					'user_login' => $username,
					'user_email' => $mail,
					'role'       => $smsalert_defaultuserrole,
					);

					$role = array(
					'ID'   => $new_customer,
					'role' => $smsalert_defaultuserrole,
					);

					wp_update_user($role);
				}
            }
            
            //added for new user approve plugin
            $user = get_user_by('email', $mail);
            $user = apply_filters('wp_authenticate_user', $user, $password);
            
            if (is_wp_error($user) ) {
                wc_add_notice(apply_filters('login_errors', $user->get_error_message()), 'error');
                do_action('woocommerce_login_failed');
                return true;
            }
            //-/added for new user approve plugin    

            if (! is_wp_error($new_customer) ) {
                $new_customer_data = apply_filters('woocommerce_new_customer_data', $userdata);
                wp_update_user($new_customer_data);

                apply_filters('woocommerce_registration_auth_new_customer', true, $new_customer);
                $new_customer_data['user_pass']     = $password;
                $new_customer_data['billing_phone'] = $phone;

                wp_set_auth_cookie($new_customer);

                if (! empty($_POST['redirect']) ) {
                    $redirect = sanitize_text_field(wp_unslash($_POST['redirect']));
                } elseif (wp_get_referer() ) {
                    $redirect = wp_get_referer();
                }

                $msg             = SmsAlertUtility::_create_json_response('Register successful', 'success');
                $redirect        = apply_filters('sa_woocommerce_regwithmob_redirect', $redirect, $new_customer);
                wp_redirect($redirect);
                exit();
            } else {
                //$validation_error->add('Error',__('Please try again','sms-alert'));
                wp_send_json(
                    SmsAlertUtility::_create_json_response(
                        'Please try again',
                        'success'
                    )
                );
                exit();
            }
        }
    }
}
new WooCommerceRegistrationForm();