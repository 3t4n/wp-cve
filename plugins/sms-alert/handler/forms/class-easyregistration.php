<?php
/**
 * This file handles easy registration form authentication via sms notification
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
if (! is_plugin_active('easy-registration-forms/erforms.php') ) {
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
 * EasyRegistration class.
 */
class EasyRegistration extends FormInterface
{

    /**
     * Form Session Variable.
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::ER_DEFAULT_REG;

    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_filter('sa_get_user_phone_no', array( $this, 'saUpdateBillingPhone' ), 10, 2);
        add_filter('erf_process_form_html', array( $this, 'saErHandleJsScript' ), 100, 2);
        add_filter('intel_dep', array( $this, 'setDependencyIntl' ), 10, 1);
        $this->routeData();
    }

    /**
     * Set intelinput dependency
     *
     * @param array $param dependencies.
     *
     * @return array
     */
    public function setDependencyIntl( $param )
    {

        if (is_plugin_active('easy-registration-forms/erforms.php') ) {
            return array_merge($param, array( 'intl-tel-input' ));
        } else {
            return $param;
        }
    }

    /**
     * Add js code to your script
     *
     * @param string $html form html.
     * @param array  $form form.
     *
     * @return string
     */
    public function saErHandleJsScript( $html, $form )
    {
        if (smsalert_get_option('buyer_signup_otp', 'smsalert_general') === 'on' ) {
            $fields  = erforms_get_form_input_fields($form['id']);
            $search  = array();
            $replace = array();
            foreach ( $fields as $field ) {
                if (array_key_exists('addUserFieldMap', $field) && 'billing_phone' === $field['addUserFieldMap'] ) {
                    array_push($search, "id='" . $field['name'] . "'");
                    array_push($replace, "id='billing_phone'");
                }
            }
            $html = str_ireplace($search, $replace, $html);

            $html .= do_shortcode('[sa_verify phone_selector="#billing_phone" submit_selector= ".erf-button .btn" ]');
        }
        return $html;
    }

    /**
     * Handle post data via ajax submit
     *
     * @return void
     */
    public function routeData()
    {
        if (! array_key_exists('option', $_GET) ) {
            return;
        }
        switch ( trim(sanitize_text_field(wp_unslash($_GET['option']))) ) {
        case 'smsalert-er-ajax-verify':
            $this->sendOtpErAjaxVerify($_POST);
            exit();
                break;
        }
    }

    /**
     * Initialize smsalert otp process.
     *
     * @param array $getdata posted getdata.
     *
     * @return void
     */
    public function sendOtpErAjaxVerify( $getdata )
    {
        SmsAlertUtility::checkSession();
        SmsAlertUtility::initialize_transaction($this->form_session_var);

        if (array_key_exists('user_phone', $getdata) && ! SmsAlertUtility::isBlank($getdata['user_phone']) ) {
            $_SESSION[ $this->form_session_var ] = trim($getdata['user_phone']);
            $message                             = str_replace('##phone##', $getdata['user_phone'], SmsAlertMessages::showMessage('OTP_SENT_PHONE'));
            smsalert_site_challenge_otp('test', null, null, trim($getdata['user_phone']), 'phone', null, null, true);
        } else {
            wp_send_json(SmsAlertUtility::_create_json_response('Enter a number in the following format : 9xxxxxxxxx', SmsAlertConstants::ERROR_JSON_TYPE));
        }
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
        if (isset($_POST['erform_id'])) {
            $fields  = erforms_get_form_input_fields($_POST['erform_id']);
            $user_phone = '';
            foreach ( $fields as $field ) {
                if (array_key_exists('addUserFieldMap', $field) && 'billing_phone' === $field['addUserFieldMap'] ) {
                    $user_phone = $_POST[$field['name']];
                }
            }
            return ( ! empty($billing_phone) ) ? $billing_phone : $user_phone;
        }
        return $billing_phone;
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
        if (! empty($_REQUEST['option']) && sanitize_text_field(wp_unslash($_REQUEST['option'])) === 'smsalert-validate-otp-form' ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
            exit();
        } else {
            $_SESSION[ $this->form_session_var ] = 'verification_failed';
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
        if (! isset($_SESSION[ $this->form_session_var ]) ) {
            return;
        }
        if (! empty($_REQUEST['option']) && sanitize_text_field(wp_unslash($_REQUEST['option'])) === 'smsalert-validate-otp-form' ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('VALID_OTP'), 'success'));
            exit();
        } else {
            $_SESSION[ $this->form_session_var ] = 'validated';
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
        return isset($_SESSION[ $this->form_session_var ]) ? true : $is_ajax;
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
new EasyRegistration();
