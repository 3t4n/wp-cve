<?php
/**
 * This file handles buddypress sms notification
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
if (! is_plugin_active('buddypress/bp-loader.php') ) {
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
 *
 * BuddyPressRegistrationForm class.
 */
class BuddyPressRegistrationForm extends FormInterface
{

    /**
     * Form Session Variable.
     *
     * @var stirng
     */
     

    private $form_session_var2 = FormSessionVars::BUDDYPRESS_DEFAULT_REG;
    
    /**
     * Handles registration form submit.
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_filter('sa_get_user_phone_no', array( $this, 'saUpdateBillingPhone' ), 10, 2);
        
        add_filter('bp_nouveau_get_signup_fields', array( $this,'smsalertBpAddSignupPhoneField' ), 10);
        
        add_action('bp_before_registration_submit_buttons', array( $this, 'bpSiteRegistrationOtp' ), 10);
        
        add_filter('login_form_bottom', array( $this, 'bpSiteLoginOtp' ), 10, 2);
        $enabled_login_with_otp = smsalert_get_option('login_with_otp', 'smsalert_general');        
        if ('on' === $enabled_login_with_otp ) {        
              add_action('bp_login_widget_form', array( $this, 'bpSiteLoginMobile' ), 10);
        }
        $buyer_signup_otp = smsalert_get_option('buyer_signup_otp', 'smsalert_general'); 
        $desable_register_popup = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');
        
        if ('on' === $buyer_signup_otp && 'off' === $desable_register_popup ) {
                add_action('bp_signup_validate', array( $this, 'bpSiteRegistrationErrors' ), 10);
        }
               
    }
    
    /**
     * This function displays a OTP button on registration form.
     *
     * @return void
     */
    public function bpSiteRegistrationOtp()
    {
        $enabled_register_popup = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');
        $buyer_signup_otp = smsalert_get_option('buyer_signup_otp', 'smsalert_general');
        if ('on' === $buyer_signup_otp && 'on' === $enabled_register_popup ) {
            echo do_shortcode('[sa_verify phone_selector=".billing_phone" submit_selector="signup_submit"]');    
        }      
    }
    
    /**
     * Add shortcode to buddypress form.
     *
     * @param array $content form content.
     * @param array $args    form fields.
     *
     * @return void
     */
    public function bpSiteLoginOtp($content = '', $args = array())
    {
        $default_login_otp   = smsalert_get_option('buyer_login_otp', 'smsalert_general');
        $enabled_login_popup = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');        
        if ('on' === $default_login_otp && 'on' === $enabled_login_popup ) {
            return  do_shortcode('[sa_verify user_selector="#bp-login-widget-user-login" pwd_selector="#bp-login-widget-user-pass" submit_selector="#bp-login-widget-submit"]');
        }
    }
    
    /**
     * Display Button login with otp
     *
     * @param array $form form form.
     * @param array $args form args.
     *
     * @return void
     */
    public function bpSiteLoginMobile($form = null, $args=array())
    {
        if ($form == null || is_array($form) || $form == 'login') {
               echo '<div class="lwo-container"><div class="sa_or">OR</div><button type="button" class="button sa_myaccount_btn" name="sa_myaccount_btn_login" value="' . __('Login with OTP', 'sms-alert') . '" style="width: 100%;box-sizing: border-box">' . __('Login with OTP', 'sms-alert') . '</button></div>';
            add_action('wp_footer', array( $this, 'addLoginwithotpShortcode' ), 15);
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
        return ( $islogged && smsalert_get_option('buyer_signup_otp', 'smsalert_general') === 'on' ) ? true : false;
    }
    
    /**
     * Update phone field
     *
     * @param string $billing_phone billing phone
     * @param int    $user_id       user id
     *
     * @return void
     */
    public function saUpdateBillingPhone($billing_phone, $user_id)
    {
        if (isset($_POST['signup_phone'])) {
            $phone = $_POST['signup_phone'];
            return ( ! empty($billing_phone) ) ? $billing_phone : $phone;
        }
        return $billing_phone;
    }

    /**
     * Show buddypress site registration errors.
     *
     * @return array
     */
    public function bpSiteRegistrationErrors()
    {  
	
		$phone = ( ! empty($_POST['signup_phone']) ) ?sanitize_text_field(wp_unslash($_POST['signup_phone'])) : '';
	    if (! SmsAlertcURLOTP::validateCountryCode($phone)) {		
			return;
		}
        global $phoneLogic;
        $bp = buddypress();
        if (!empty($bp->signup->errors) ) {
            return;
        }
        $email = ! empty($_REQUEST['signup_email']) ? sanitize_text_field(wp_unslash($_REQUEST['signup_email'])) : '';
        $username = ! empty($_REQUEST['signup_username']) ? sanitize_text_field(wp_unslash($_REQUEST['signup_username'])) : '';
        $password = ! empty($_REQUEST['signup_password']) ? sanitize_text_field(wp_unslash($_REQUEST['signup_password'])) : '';
        
        SmsAlertUtility::checkSession();
        if (isset($_SESSION['sa_mobile_verified']) ) {              
            unset($_SESSION['sa_mobile_verified']);
            return ;
        }       
            SmsAlertUtility::initialize_transaction($this->form_session_var2);            
      
        if (! isset($phone) || ! SmsAlertUtility::validatePhoneNumber($phone)) {            
			$bp->signup->errors['signup_phone'] =  str_replace('##phone##', SmsAlertcURLOTP::checkPhoneNos($phone), $phoneLogic->_get_otp_invalid_format_message());
			$bp->signup->signup_phone = $_POST['signup_phone'];
			return;
        } 
                   
        if (smsalert_get_option('allow_multiple_user', 'smsalert_general') !== 'on' && ! SmsAlertUtility::isBlank($phone) ) {    
            $getusers = SmsAlertUtility::getUsersByPhone('billling_phone', $phone);
            if (count($getusers) > 0 ) {  
                $bp->signup->errors['signup_phone'] =  __('An account is already registered with this mobile number!', 'sms-alert');
                $bp->signup->signup_phone = $_POST['signup_phone'];
                return;
            }            
        }
        return $this->processFormFields($username, $email, $password, $phone);
    }

    /**
     * Initialise the otp verification.
     *
     * @param string $username username
     * @param string $email    email
     * @param string $password password
     * @param string $phone    phone
     *
     * @return array
     */    
    public function processFormFields($username, $email, $password, $phone)
    {
        global $phoneLogic;
        $extra_data= null;       
        $phone = preg_replace('/[^0-9]/', '', $phone);
        smsalert_site_challenge_otp('test', $email, $password, $phone, 'phone', $extra_data);
    } 

    /**
     * Add Phone field to buddypress registration form.
     *
     * @param array $fields form fields.
     *
     * @return array
     */
    public function smsalertBpAddSignupPhoneField( $fields )
    {
        $fields['account_details']['signup_phone'] = array(
        'label'          => __('Phone', 'sms-alert'),
        'required'       => true,
        'value'          => '',
        'attribute_type' => 'phone',
        'type'           => 'text',
        'class'          => 'billing_phone phone-valid',
        );
        return $fields;
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
        if (! isset($_SESSION[ $this->form_session_var2 ])) {
            return;
        }
        if (isset($_SESSION[ $this->form_session_var2 ]) ) {
            smsalert_site_otp_validation_form($user_login, $user_email, $phone_number, SmsAlertUtility::_get_invalid_otp_method(), 'phone', false);
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
        if ( ! isset($_SESSION[ $this->form_session_var2 ])) {
            return;
        }        
         $_SESSION['sa_mobile_verified'] = true;     
        
    }

    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {
        unset($_SESSION[ $this->tx_session_id ]);
        unset($_SESSION[$this->form_session_var2]);
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
        return $is_ajax;
    }

    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleFormOptions()
    {  
    }
}
    new BuddyPressRegistrationForm();