<?php
/**
 * This file handles RegistrationMagic sms notification
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
if (! is_plugin_active('custom-registration-form-builder-with-submission-manager/registration_magic.php') ) {
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
 * SARegistrationMagicForm class.
 */
class SARegistrationMagicForm extends FormInterface
{

    /**
     * Form Session Variable.
     *
     * @var stirng
     */
     
    private $form_session_var = FormSessionVars::REGISTRATIONMAGIC_FORM;
    
    /**
     * Handles registration form submit.
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('rm_submission_completed', array( $this, 'smsalertUserRegister' ), 10, 3);
        add_action('rm_form_rendered', array( $this, 'bpSiteRegistrationOtp' ), 10, 1);
    }    
    
    /**
     * This function displays a OTP button on registration form.
     *
     * @param bool $form form.
     *
     * @return void
     */
    public function bpSiteRegistrationOtp($form)
    { 
        $form_id = $form->form_slug;
        $enabled_register_popup = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');
        $buyer_signup_otp = smsalert_get_option('buyer_signup_otp', 'smsalert_general');        
        if ('on' === $buyer_signup_otp && 'on' === $enabled_register_popup ) {
            echo do_shortcode('[sa_verify phone_selector=".billing_phone" submit_selector="#'. $form_id .' .rm_next_btn"]');    
        }      
    }
    
    
    /**
     * 
     * This function gets role display name from system name.
     *
     * @param bool $system_name System name of the role.
     *
     * @return void
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
     * This function gets role display name from system name.
     *
     * @param bool $form_id         form_id.
     * @param bool $user_id         user_id.
     * @param bool $submission_data submission_data.
     *
     * @return void
     */
    public static function smsalertUserRegister( $form_id,$user_id,$submission_data )
    {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}rm_fields` where `form_id` = %s", array( $form_id)));
        foreach ($rows as $row) {
            $field_options = maybe_unserialize($row->field_options);            
            if ($field_options->field_css_class == 'billing_phone') {
                $field_id = $row->field_id;
                $billing_phone = $submission_data[$field_id]->value;
                break;
            }            
        }
        $user                = get_userdata($user_id['user_id']);
        $userdata = $user->data;        
        $role                = ( ! empty($user->roles[0]) ) ? $user->roles[0] : '';
        $role_display_name   = ( ! empty($role) ) ? self::get_user_roles($role) : '';
        $smsalert_reg_notify = smsalert_get_option('wc_user_roles_' . $role, 'smsalert_signup_general', 'off');
        $sms_body_new_user   = smsalert_get_option('signup_sms_body_' . $role, 'smsalert_signup_message', SmsAlertMessages::showMessage('DEFAULT_NEW_USER_REGISTER'));
        $smsalert_reg_admin_notify = smsalert_get_option('admin_registration_msg', 'smsalert_general', 'off');
        $sms_admin_body_new_user   = smsalert_get_option('sms_body_registration_admin_msg', 'smsalert_message', SmsAlertMessages::showMessage('DEFAULT_ADMIN_NEW_USER_REGISTER'));
        $admin_phone_number        = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
        $store_name = trim(get_bloginfo());
        if ('on' === $smsalert_reg_notify && ! empty($billing_phone) ) {
            $search = array(
            '[username]',
            '[email]',
            '[billing_phone]',
            );

            $replace           = array(
            $userdata->user_login,
            $userdata->user_email,
            $billing_phone,
            );
            $sms_body_new_user = str_replace($search, $replace, $sms_body_new_user);
            do_action('sa_send_sms', $billing_phone, $sms_body_new_user);
        }

        if ('on' === $smsalert_reg_admin_notify && ! empty($admin_phone_number) ) {
            $search = array(
            '[username]',
            '[store_name]',
            '[email]',
            '[billing_phone]',
            '[role]',
            );

            $replace = array(
            $userdata->user_login,
            $store_name,
            $userdata->user_email,
            $billing_phone,
            $role_display_name,
            );

            $sms_admin_body_new_user = str_replace($search, $replace, $sms_admin_body_new_user);
            $nos                     = explode(',', $admin_phone_number);
            $admin_phone_number      = array_diff($nos, array( 'postauthor', 'post_author' ));
            $admin_phone_number      = implode(',', $admin_phone_number);
            do_action('sa_send_sms', $admin_phone_number, $sms_admin_body_new_user);
        }
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
        if (! isset($_SESSION[ $this->form_session_var ]) ) {
            return;
        }
        if (isset($_SESSION[ $this->form_session_var ]) ) {
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
        if (isset($_SESSION[ $this->form_session_var ]) || ((empty($_REQUEST['option']) === false ) && sanitize_text_field(wp_unslash($_REQUEST['option'])) === 'smsalert-validate-otp-form')) {
            
            $_SESSION['bp_mobile_verified'] = true;
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
  new SARegistrationMagicForm();
