<?php
/**
 * This file handles login authentication via sms notification
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
/* if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    return; } */

/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * WPLogin class.
 **/
class WPLogin extends FormInterface
{

    /**
     * Form Session Variable.
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::WP_LOGIN_REG_PHONE;

    /**
     * Form Session Variable for login in popup.
     *
     * @var stirng
     */
    private $form_session_var2 = FormSessionVars::WP_DEFAULT_LOGIN;

    /**
     * Form Session Variable for login with otp.
     *
     * @var stirng
     */
    private $form_session_var3 = FormSessionVars::WP_LOGIN_WITH_OTP;

    /**
     * Phone Field Key.
     *
     * @var stirng
     */
    private $phone_number_key;

    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        $this->phone_number_key = 'billing_phone';
        if (! empty($_REQUEST['learn-press-register-nonce']) ) {
            return;
        }
        $enabled_login_popup = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');
		$is_admin_login = (is_login()) ? true : false;
        $this->routeData();
        $enable_login_with_admin_otp = smsalert_get_option('login_with_admin_otp', 'smsalert_general');
        $enabled_login_with_otp = smsalert_get_option('login_with_otp', 'smsalert_general');
        $default_login_otp      = smsalert_get_option('buyer_login_otp', 'smsalert_general');

        if ('on' === $default_login_otp ) {
            //if ( 'on' === $enabled_login_popup ) {
            if (is_plugin_active('easy-login-woocommerce/xoo-el-main.php') || is_plugin_active('easy-login-woocommerce-premium/xoo-el-main.php') ) {
                add_action('xoo_el_login_add_fields', array( $this, 'xooElAddLoginOtpPopup' ));
            }
            add_action('woocommerce_login_form_end', array( $this, 'addLoginOtpPopup' ));
            //} else {
            add_filter('authenticate', array( $this, 'handleSmsalertWpLogin' ), 99, 4);
			add_action('thim_after_login_form', array( $this, 'edumaLoginOtpPopup' ),10);
            //}
        }

        if ('on' === $enabled_login_with_otp ) {
            if (is_plugin_active('easy-login-woocommerce/xoo-el-main.php') || is_plugin_active('easy-login-woocommerce-premium/xoo-el-main.php') ) {
                add_action('xoo_el_login_form_end', array( $this, 'smsalertDisplayLoginWithOtp' ));
                add_action('xoo_el_form_end', array( $this, 'smsalertDisplayLoginWithOtp' ), 10, 2);
            }
            add_action('woocommerce_login_form_end', array( $this, 'smsalertDisplayLoginWithOtp' ));
            add_action('um_after_login_fields', array( $this, 'smsalertDisplayLoginWithOtp' ), 1002);
			add_action('thim_after_login_form', array( $this, 'smsalertDisplayLoginWithOtp' ),10);			
        }
		if ('on' === $enable_login_with_admin_otp &&  $is_admin_login ) {			
				add_action('login_form', array( $this, 'showLoginWithOtpAdmin' ),10);					
        }
        if (is_plugin_active('google-captcha/google-captcha.php')) {
            add_filter( 'gglcptch_add_custom_form', array( $this, 'add_custom_recaptcha_forms'),10,1 );
        }		
		
    }
	
	
	
	    
	/**
     * Handle google recaptcha.
     *
     * @param array $forms forms.
     *
     * @return void
     */
    public function add_custom_recaptcha_forms( $forms )
    {
		$forms['sa_lwo_form'] = array( "form_name" => "SMS Alert Login With OTP" );
		$forms['sa_swm_form'] = array( "form_name" => "SMS Alert Signup With Mobile" );
        return $forms;
	} 

    /**
     * Handle post data via ajax submit
     *
     * @return void
     */
    public function routeData()
    {
        if (! array_key_exists('option', $_REQUEST) ) {
            return;
        }
        switch ( trim(sanitize_text_field(wp_unslash($_REQUEST['option']))) ) {
        case 'smsalert-ajax-otp-generate':
            $this->handleWpLoginAjaxSendOtp($_POST);
            break;
        case 'smsalert-ajax-otp-validate':
            $this->handleWpLoginAjaxFormValidateAction($_POST);
            break;
        case 'smsalert_ajax_form_validate':
            $this->handleWpLoginCreateUserAction($_POST);
            break;
        case 'smsalert_ajax_login_with_otp':
            $this->handleLoginWithOtp();
            break;
        case 'smsalert_ajax_login_popup':
            $this->handleLoginPopup();
            break;
        case 'smsalert_verify_login_with_otp':
            $this->processLoginWithOtp();
            break;
        }
    }

    /**
     * Handle login popup submit
     *
     * @return object
     */
    public function handleLoginPopup()
    {
        $username = ! empty($_REQUEST['username']) ? sanitize_text_field(wp_unslash($_REQUEST['username'])) : '';
        $password = ! empty($_REQUEST['password']) ? sanitize_text_field(wp_unslash($_REQUEST['password'])) : '';

        // check user with username and password.
        $user = $this->getUserIfUsernameIsPhoneNumber(null, $username, $password, $this->phone_number_key);

        if (! $user ) {
            $user = wp_authenticate($username, $password);
        }
        //added for new user approve plugin
        $user = apply_filters('wp_authenticate_user', $user, $password);
        if (is_wp_error($user) ) {
            $msg   = SmsAlertUtility::_create_json_response(current($user->errors), 'error');
            wp_send_json($msg);
            exit();
        }  
        //-added for new user approve plugin
        $user_meta    = get_userdata($user->data->ID);
        $user_role    = $user_meta->roles;
        $phone_number = get_user_meta($user->data->ID, $this->phone_number_key, true);
		if (! SmsAlertcURLOTP::validateCountryCode($phone_number)){		
			return $data;
		}
        if (empty($phone_number) ) {
            return $user;
        } 
        if ($this->byPassLogin($user_role) ) {
            return $user;
        }

        SmsAlertUtility::initialize_transaction($this->form_session_var3);
        smsalert_site_challenge_otp($username, null, null, $phone_number, 'phone', $password, SmsAlertUtility::currentPageUrl(), true);
    }

    /**
     * Handle login with otp
     *
     * @return void
     */
    public function handleLoginWithOtp()
    {
        $verify = check_ajax_referer('smsalert_wp_loginwithotp_nonce', 'smsalert_loginwithotp_nonce', false);
        if (!$verify) {
            wp_send_json(SmsAlertUtility::_create_json_response(__('Sorry, nonce did not verify.', 'sms-alert'), 'error'));
        }
		if (is_plugin_active('google-captcha/google-captcha.php')) {
			$check_result = apply_filters( 'gglcptch_verify_recaptcha', true, 'string', 'sa_lwo_form' );
			if ( true !== $check_result ) { 
			  wp_send_json(SmsAlertUtility::_create_json_response(__('The reCaptcha verification failed. Please try again.', 'sms-alert'), 'error'));
			}
		}
        if (isset($_REQUEST['username']) ) {
            global $phoneLogic;
            $phone_number = ! empty($_REQUEST['username']) ? sanitize_text_field(wp_unslash($_REQUEST['username'])) : '';
            $billing_phone = SmsAlertcURLOTP::checkPhoneNos($phone_number);
            if (SmsAlertUtility::isBlank($phone_number)) {
                wp_send_json(SmsAlertUtility::_create_json_response(__('Please enter phone number.', 'sms-alert'), 'error'));
            } else if (! $billing_phone ) {

                $message = str_replace('##phone##', $phone_number, $phoneLogic->_get_otp_invalid_format_message());

                wp_send_json(SmsAlertUtility::_create_json_response($message, 'error'));
            }
            $user_info  = $this->getUserFromPhoneNumber($billing_phone, $this->phone_number_key);
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

            if (! empty($user_login) ) {
                SmsAlertUtility::initialize_transaction($this->form_session_var3);
                smsalert_site_challenge_otp(null, null, null, $billing_phone, 'phone', null, SmsAlertUtility::currentPageUrl(), true);
            } else {
                wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('PHONE_NOT_FOUND'), 'error'));
            }
        }
    }

    /**
     * Display Button login with otp
     *
     * @param array $form get all wp form
     * @param array $args get args.
     *
     * @return void
     */
    public function smsalertDisplayLoginWithOtp($form = null, $args=array())
    {
        if ($form == null || is_array($form) || $form == 'login') {
            echo '<div class="lwo-container"><div class="sa_or">OR</div><button type="button" class="button sa_myaccount_btn" name="sa_myaccount_btn_login" value="' . __('Login with OTP', 'sms-alert') . '" style="width: 100%;box-sizing: border-box;display:block">' . __('Login with OTP', 'sms-alert') . '</button></div>';
            if (is_plugin_active('google-captcha/google-captcha.php')) {
                add_action('wp_footer', array( $this, 'addLoginwithotpShortcode' ), 1);
			}
			else{
				add_action('wp_footer', array( $this, 'addLoginwithotpShortcode' ), 15);
			}
        }        
    }
	
	/**
     * Add login otp in admin login form page.
     *
     * @return void
     */
	function showLoginWithOtpAdmin()
	{		
		 echo '<div class="lwo-container"><div class="sa_or">OR</div><button type="button" class="button sa_myaccount_btn" name="sa_myaccount_btn_login" value="' . __('Login with OTP', 'sms-alert') . '" style="box-sizing: border-box">' . __('Login with OTP', 'sms-alert') . '</button></div>';
		add_action('login_footer', array( $this, 'addAdminLoginWithOtpShortcode' ),15);
		  
	}
     
	/**
     * Add login otp in admin login form page.
     *
     * @return void
     */
	public static function addAdminLoginWithOtpShortcode()
    {	
        echo '<div class="loginwithotp adminlgin" >'.do_shortcode('[sa_loginwithotp]').'</div>';
        echo '<style>.loginwithotp .sa_loginwithotp-form{display:none;}.loginwithotp .sa_default_login_form{display:block;}</style>';
    }

    /**
     * Add login otp in popup in login form page.
     *
     * @return void
     */
    public function xooElAddLoginOtpPopup()
    {
        $enabled_login_popup    = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');
        $default_login_otp      = smsalert_get_option('buyer_login_otp', 'smsalert_general');
        if ('on' === $enabled_login_popup && 'on' === $default_login_otp) {
            $unique_class    = 'sa-class-'.mt_rand(1, 100);
            echo '<script>
		jQuery("form.xoo-el-form-login").each(function () 
		{
			if(!jQuery(this).hasClass("sa-login-form"))
			{
			jQuery(this).addClass("'.$unique_class.' sa-login-form");
			}		
		});		
		</script>';    
            echo do_shortcode('[sa_verify user_selector="xoo-el-username" pwd_selector="xoo-el-password" submit_selector=".'.$unique_class.' .xoo-el-login-btn"]');
        }
    }
	
    
    /**
     * Add login with otp form code in login form page.
     *
     * @return void
     */
    public function addLoginOtpPopup()
    {
        $enabled_login_popup    = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');
        $default_login_otp      = smsalert_get_option('buyer_login_otp', 'smsalert_general');
        if ('on' === $enabled_login_popup && 'on' === $default_login_otp) {
            $unique_class    = 'sa-class-'.mt_rand(1, 100);
            echo '<script>
		jQuery("form.login").each(function () 
		{
			if(!jQuery(this).hasClass("sa-login-form"))
			{
			jQuery(this).addClass("'.$unique_class.' sa-login-form");
			}		
		});		
		</script>';
            echo do_shortcode('[sa_verify user_selector="#username" pwd_selector="#password" submit_selector=".'.$unique_class.'.login :submit"]');
        }
    }
	/**
     * Add login with otp form code in login form page.
     *
     * @return void
     */
    public function edumaLoginOtpPopup()
    {
        $enabled_login_popup    = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');
        $default_login_otp      = smsalert_get_option('buyer_login_otp', 'smsalert_general');
        if ('on' === $enabled_login_popup && 'on' === $default_login_otp) {
            $unique_class    = 'sa-class-'.mt_rand(1, 100);
            echo '<script>
		jQuery("form[name=loginpopopform]").each(function () 
		{
			if(!jQuery(this).hasClass("sa-login-form"))
			{
			jQuery(this).addClass("'.$unique_class.' sa-login-form");
			}		
		});		
		</script>';
             echo do_shortcode('[sa_verify user_selector="log" pwd_selector="pwd" submit_selector=".thim-login .login-submit .button"]'); 
        }
    }
    
    /**
     * Add login with otp shortcode.
     *
     * @return string
     */
    public static function addLoginwithotpShortcode()
    {
        echo '<div class="loginwithotp">'.do_shortcode('[sa_loginwithotp]').'</div>';
        echo '<style>.loginwithotp .sa_loginwithotp-form{display:none;}.loginwithotp .sa_default_login_form{display:block;}</style>';
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
        return ( $islogged && ( smsalert_get_option('buyer_login_otp', 'smsalert_general') === 'on' || smsalert_get_option('login_with_otp', 'smsalert_general') === 'on' ) ) ? true : false;
    }

    /**
     * Check wp_login_register_phon.
     *
     * @return bool
     */
    public function checkWpLoginRegisterPhone()
    {
        return true;
    }

    /**
     * Check wp_login_by_phone_number.
     *
     * @return bool
     */
    public function checkWpLoginByPhoneNumber()
    {
        return true;
    }

    /**
     * By Pass Login if any role is required to escape from login authentication.
     *
     * @param array $user_role get all wp user roles.
     *
     * @return bool
     */
    public function byPassLogin( $user_role )
    {
        $current_role   = array_shift($user_role);
        $excluded_roles = smsalert_get_option('admin_bypass_otp_login', 'smsalert_general', array());
        $otp_for_roles              = smsalert_get_option('otp_for_roles', 'smsalert_general', 'on');
        if ('on' !== $otp_for_roles) {
            return false;
        }
        if (! is_array($excluded_roles) ) {
            $excluded_roles = ( 'administrator' === $current_role ) ? array( 'administrator' ) : array();
        }
        return in_array($current_role, $excluded_roles, true) ? true : false;
    }

    /**
     * Check wp login restrict duplicates.
     *
     * @return bool
     */
    public function checkWpLoginRestrictDuplicates()
    {
        return ( smsalert_get_option('allow_multiple_user', 'smsalert_general') === 'on' ) ? true : false;
    }

    /**
     * Handle wp login create user action.
     *
     * @param array $postdata posted data by user.
     *
     * @return void
     */
    public function handleWpLoginCreateUserAction( $postdata )
    {
        $redirect_to = isset($postdata['redirect_to']) ? $postdata['redirect_to'] : null;
        // added this line on 28-11-2018 due to affiliate login redirect issue.

        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ])
            || 'validated' !== $_SESSION[ $this->form_session_var ] 
        ) {
            return;
        }

        $user = is_email($postdata['log']) ? get_user_by('email', $postdata['log']) : get_user_by('login', $postdata['log']);
        if (! $user ) {
            $user = is_email($postdata['username']) ? get_user_by('email', $postdata['username']) : get_user_by('login', $postdata['username']);
        }

        update_user_meta($user->data->ID, $this->phone_number_key, sanitize_text_field($postdata['sa_phone_number']));
        $this->loginWpUser($user->data->user_login, $redirect_to);
    }

    /**
     * If your user is authenticated then redirect him to page.
     *
     * @param object $user_log   logged user details.
     * @param string $extra_data get hidden fields.
     *
     * @return void
     */
    public function loginWpUser( $user_log, $extra_data = null )
    {
        $user = get_user_by('login', $user_log);
        wp_set_current_user($user->data->ID, $user->user_login);
        wp_set_auth_cookie($user->data->ID);
        $this->unsetOTPSessionVariables();
        do_action('wp_login', $user->user_login, $user);
        $redirect = SmsAlertUtility::isBlank($extra_data) ? site_url() : $extra_data;
        $redirect        = apply_filters('woocommerce_login_redirect', $redirect, $user);
        wp_redirect($redirect);
        exit;
    }

    /**
     * Process login with otp.
     *
     * @return void
     */
    public function processLoginWithOtp()
    {
        SmsAlertUtility::checkSession();
        $login_with_otp_enabled = ( smsalert_get_option('login_with_otp', 'smsalert_general') === 'on' ) ? true : false;
        $password='';
        if (empty($password) ) {
            if (! empty($_REQUEST['username']) ) {
                $phone_number = ! empty($_REQUEST['username']) ? sanitize_text_field(wp_unslash($_REQUEST['username'])) : '';
                $user_info    = $this->getUserFromPhoneNumber($phone_number, $this->phone_number_key);
                $user_login   = ( $user_info ) ? $user_info->data->user_login : '';
            }
        }
        if ($login_with_otp_enabled && empty($password) && ! empty($user_login) && ! empty($_SESSION['login_otp_success']) ) {
            if (! empty($_POST['redirect']) ) {
                $redirect = wp_sanitize_redirect(wp_unslash($_POST['redirect']));
            } elseif (wc_get_raw_referer() ) {
                $redirect = wc_get_raw_referer();
            }
            unset($_SESSION['login_otp_success']);
            $this->loginWpUser($user_login, $redirect);
        }
    }

    /**
     * Handle smsalert login after submitted by user.
     *
     * @param array  $user     user data.
     * @param string $username wp username.
     * @param stirng $password wp password.
     *
     * @return object
     */
    public function handleSmsalertWpLogin( $user, $username, $password )
    {
        SmsAlertUtility::checkSession();
        $login_with_otp_enabled = ( smsalert_get_option('login_with_otp', 'smsalert_general') === 'on' ) ? true : false;
        if (empty($password) ) {
            if (! empty($_REQUEST['username']) ) {
                $phone_number = ! empty($_REQUEST['username']) ? sanitize_text_field(wp_unslash($_REQUEST['username'])) : '';
                $user_info    = $this->getUserFromPhoneNumber($phone_number, $this->phone_number_key);
                $user_login   = ( $user_info ) ? $user_info->data->user_login : '';
            }
        }

        if ($login_with_otp_enabled && empty($password) && ! empty($user_login) && ! empty($_SESSION['login_otp_success']) ) {
            if (! empty($_POST['redirect']) ) {
                $redirect = wp_sanitize_redirect(wp_unslash($_POST['redirect']));
            } elseif (wc_get_raw_referer() ) {
                $redirect = wc_get_raw_referer();
            } else {
                $redirect = wc_get_page_permalink('myaccount');
            }
            unset($_SESSION['login_otp_success']);
            $this->loginWpUser($user_login, $redirect);
        }

        if (( is_array($_SESSION) && array_key_exists($this->form_session_var, $_SESSION) && strcasecmp($_SESSION[ $this->form_session_var ], 'validated') === 0 ) && ! empty($_POST['sa_phone_number']) ) {
            update_user_meta($user->data->ID, $this->phone_number_key, sanitize_text_field(wp_unslash($_POST['sa_phone_number'])));
            $this->unsetOTPSessionVariables();
        }
        if (isset($_SESSION['login_otp_success']) ) {
            unset($_SESSION['login_otp_success']);
            return $user;
        }
        if (isset($_SESSION['sa_login_mobile_verified']) ) {
            unset($_SESSION['sa_login_mobile_verified']);
            return $user;
        }
		if (!empty($_REQUEST['log']) && !empty($_REQUEST['piereg_login_form_nonce'])) {
            return $user;
        }
        $user = $this->getUserIfUsernameIsPhoneNumber($user, $username, $password, $this->phone_number_key);

        if (is_wp_error($user) ) {
            return $user;
        } 

        $user_meta    = get_userdata($user->data->ID);
        $user_role    = $user_meta->roles;
        $phone_number = get_user_meta($user->data->ID, $this->phone_number_key, true);
		if(! SmsAlertcURLOTP::validateCountryCode($phone_number)){					 
			return $user;
		}
        if ($this->byPassLogin($user_role) ) {
            return $user;
        }

        if (( smsalert_get_option('buyer_login_otp', 'smsalert_general') === 'off' && smsalert_get_option('login_with_otp', 'smsalert_general') === 'on' ) ) {
            return $user;
        }
       
        $this->askPhoneAndStartVerification($user, $this->phone_number_key, $username, $phone_number);
        $this->fetchPhoneAndStartVerification($user, $this->phone_number_key, $username, $password, $phone_number);
        return $user;
    }

    /**
     * Get User If Username Is PhoneNumber.
     *
     * @param array  $user     user data.
     * @param string $username wp username.
     * @param string $password wp password.
     * @param string $key      phone field name.
     *
     * @return object
     */
    public function getUserIfUsernameIsPhoneNumber( $user, $username, $password, $key )
    {
        if (! $this->checkWpLoginByPhoneNumber() || ! SmsAlertUtility::validatePhoneNumber($username) ) {
            return $user;
        }
        $user_info = $this->getUserFromPhoneNumber($username, $key);
        $username  = is_object($user_info) ? $user_info->data->user_login : $username; // added on 20-05-2019.
        return wp_authenticate_username_password(null, $username, $password);
    }

    /**
     * Get User From PhoneNumber.
     *
     * @param string $username wp username.
     * @param string $key      phone field name.
     *
     * @return object
     */
    public static function getUserFromPhoneNumber( $username, $key )
    {
        global $wpdb;

        $wcc_ph     = SmsAlertcURLOTP::checkPhoneNos($username);
        $wocc_ph    = SmsAlertcURLOTP::checkPhoneNos($username, false);
        $wth_pls_ph = '+' . $wcc_ph;

        $results = $wpdb->get_row("SELECT `user_id` FROM {$wpdb->base_prefix}usermeta inner join {$wpdb->base_prefix}users on ({$wpdb->base_prefix}users.ID = {$wpdb->base_prefix}usermeta.user_id) WHERE `meta_key` = '$key' AND `meta_value` in('$wcc_ph','$wocc_ph','$wth_pls_ph') order by user_id desc");
        $user_id = ( ! empty($results) ) ? $results->user_id : 0;
        return get_userdata($user_id);
    }

    /**
     * Ask Phone And Start Verification.
     *
     * @param object $user         wp user object.
     * @param string $key          phone field name.
     * @param string $username     wp username.
     * @param string $phone_number user phone number.
     *
     * @return object
     */
    public function askPhoneAndStartVerification( $user, $key, $username, $phone_number )
    {
		       
        if (! SmsAlertUtility::isBlank($phone_number) ) {
            return;
        }
        if (! $this->checkWpLoginRegisterPhone() ) {
            smsalert_site_otp_validation_form(null, null, null, SmsAlertMessages::showMessage('PHONE_NOT_FOUND'), null, null);
        } else {
            SmsAlertUtility::initialize_transaction($this->form_session_var);
            smsalert_external_phone_validation_form(SmsAlertUtility::currentPageUrl(), $user->data->user_login, __('A new security system has been enabled for you. Please register your phone to continue.', 'sms-alert'), $key, array( 'user_login' => $username ));
        }
    }

    /**
     * Fetch Phone and start verification
     *
     * @param object $user         users object.
     * @param string $key          phone key.
     * @param string $username     username.
     * @param string $password     password.
     * @param string $phone_number phone number.
     *
     * @return void
     */
    public function fetchPhoneAndStartVerification( $user, $key, $username, $password, $phone_number )
    {
        if (( array_key_exists($this->form_session_var, $_SESSION) && strcasecmp($_SESSION[ $this->form_session_var ], 'validated') === 0 )
            || ( array_key_exists($this->form_session_var2, $_SESSION) && strcasecmp($_SESSION[ $this->form_session_var2 ], 'validated') === 0 ) 
        ) {
            return;
        }
        SmsAlertUtility::initialize_transaction($this->form_session_var2); 
        smsalert_site_challenge_otp($username, null, null, $phone_number, 'phone', $password, SmsAlertUtility::currentPageUrl(), false);
    }

    /**
     * Handle otp ajax send otp
     *
     * @param object $data users data.
     *
     * @return void
     */
    public function handleWpLoginAjaxSendOtp( $data )
    {
        SmsAlertUtility::checkSession();
        if (! $this->checkWpLoginRestrictDuplicates()
            && ! SmsAlertUtility::isBlank($this->getUserFromPhoneNumber($data['billing_phone'], $this->phone_number_key)) 
        ) {
            wp_send_json(SmsAlertUtility::_create_json_response(__('Phone Number is already in use. Please use another number.', 'sms-alert'), SmsAlertConstants::ERROR_JSON_TYPE));
        } elseif (isset($_SESSION[ $this->form_session_var ]) ) {
            smsalert_site_challenge_otp('ajax_phone', '', null, trim($data['billing_phone']), 'phone', null, $data, null);
        }
    }

    /**
     * Handle validation otp ajax sentotp
     *
     * @param object $data users data.
     *
     * @return void
     */
    public function handleWpLoginAjaxFormValidateAction( $data )
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) && ! isset($_SESSION[ $this->form_session_var2 ]) && ! isset($_SESSION[ $this->form_session_var3 ]) ) {
            return;
        }

        if (strcmp($_SESSION['phone_number_mo'], $data['billing_phone']) && isset($data['billing_phone']) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('PHONE_MISMATCH'), 'error'));
        } else {
            do_action('smsalert_validate_otp', 'phone');
        }
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
        if (! isset($_SESSION[ $this->form_session_var ]) && ! isset($_SESSION[ $this->form_session_var2 ]) && ! isset($_SESSION[ $this->form_session_var3 ]) ) {
            return;
        }

        if (isset($_SESSION[ $this->form_session_var ]) ) {
            $_SESSION[ $this->form_session_var ] = 'verification_failed';
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
        }
        if (isset($_SESSION[ $this->form_session_var2 ]) ) {
            smsalert_site_otp_validation_form($user_login, $user_email, $phone_number, SmsAlertMessages::showMessage('INVALID_OTP'), 'phone', false);
        }
        if (isset($_SESSION[ $this->form_session_var3 ]) ) {
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
        if (! isset($_SESSION[ $this->form_session_var ]) && ! isset($_SESSION[ $this->form_session_var2 ]) && ! isset($_SESSION[ $this->form_session_var3 ]) ) {
            return;
        }

        if (isset($_SESSION[ $this->form_session_var ]) ) {
            $_SESSION['sa_login_mobile_verified'] = true;
            $_SESSION[ $this->form_session_var ]  = 'validated';
            wp_send_json(SmsAlertUtility::_create_json_response('successfully validated', 'success'));
        } elseif (isset($_SESSION[ $this->form_session_var3 ]) ) {
            $_SESSION['login_otp_success'] = true;
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('VALID_OTP'), 'success'));
        } else {
            $_SESSION['sa_login_mobile_verified'] = true;
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
        unset($_SESSION[ $this->form_session_var ]);
        unset($_SESSION[ $this->form_session_var2 ]);
        unset($_SESSION[ $this->form_session_var3 ]);
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
        return ( isset($_SESSION[ $this->form_session_var ]) || isset($_SESSION[ $this->form_session_var3 ]) ) ? true : $is_ajax;
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
    new WPLogin();