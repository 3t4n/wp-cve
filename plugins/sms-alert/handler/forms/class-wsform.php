<?php
/**
 * This file handles wp forms via sms notification
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

if (! is_plugin_active('ws-form/ws-form.php') ) {
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
 * SA_Wsform class.
 */
class SA_Wsform extends FormInterface
{
    
    /**
     * Form Session Variable.
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::WSFORM;

    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_filter('wsf_api_submit_response_data', array( $this, 'wsFormResponseMessage' ), 10, 2);
        add_filter('wsf_pre_render', array( $this, 'addSmsalertShortcode' ), 10, 2);       
    }
    
    /**
     * Add Shortcode for OTP and Add additional js code to your script
     *
     * @param array $form_object form_object.
     * @param array $preview     preview.
     *
     * @return void     
     * */     
    public function addSmsalertShortcode($form_object, $preview)
    {        
        $id = $form_object->id;
        $form_enable = smsalert_get_option('wsform_form_status_' . $id, 'smsalert_wsform_general', 'on');
        $otp_enable  = smsalert_get_option('wsform_otp_' . $id, 'smsalert_wsform_general', 'on');
        $phone_field = smsalert_get_option('wsform_sms_phone_' . $id, 'smsalert_wsform_general', '');
        $phone_field = preg_replace('/(\w+)_(\d+)/i', 'field_$2', $phone_field);    
        $phonevalue = explode("_", $phone_field);    
        $fieldid = !empty($phonevalue[1])?$phonevalue[1]:'';                            
        if ('on' === $form_enable && 'on' === $otp_enable && '' !== $phone_field ) {            
            if (empty($_POST['wsf_post_id'])) {                 
                echo do_shortcode('[sa_verify phone_selector="field_'.$fieldid.'" submit_selector= ".wsf-section:last :submit"]');
            }
        }        
        return $form_object;
    }    
    
    /**
     * Process ws form submission and send sms
     *
     * @param array $response form response.
     * @param array $datas    form datas.
     *
     * @return void
     */
    public function wsFormResponseMessage($response,$datas)
    {        
        global  $get_meta, $get_groups;
        $form_id = $datas->form_id;        
        $form_enable      = smsalert_get_option('wsform_form_status_' . $form_id, 'smsalert_wsform_general', 'on');        
        $phone_field      = smsalert_get_option('wsform_sms_phone_'. $form_id, 'smsalert_wsform_general', '');
        $buyer_sms_notify = smsalert_get_option('wsform_message_' . $form_id, 'smsalert_wsform_general', 'on');
        $admin_sms_notify = smsalert_get_option('wsform_admin_notification_' . $form_id, 'smsalert_wsform_general', 'on');                        
        if ('on' === $form_enable && 'on' === $buyer_sms_notify) {
            $buyer_sms_content = smsalert_get_option('wsform_sms_body_'. $form_id, 'smsalert_wsform_message', '');  
            $phone_field = preg_replace('/(\w+)_(\d+)/i', 'field_$2', $phone_field);
            $data = $datas->meta;            
            $mobile = !empty($data[$phone_field]['value']) ? $data[$phone_field]['value'] : "";            
            do_action('sa_send_sms', $mobile, self::parseSmsContent($buyer_sms_content, $datas->meta));
        }
        if ('on' === $admin_sms_notify ) {
            $admin_phone_number = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
            $admin_phone_number = str_replace('post_author', '', $admin_phone_number);
            if (! empty($admin_phone_number) ) {
                $admin_sms_content = smsalert_get_option('wsform_admin_sms_body_' . $form_id, 'smsalert_wsform_message', '');
                do_action('sa_send_sms', $admin_phone_number, self::parseSmsContent($admin_sms_content, $datas->meta));
            }
        } 
        return $response;
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
        return ( is_plugin_active('ws-form/ws-form.php') && $islogged ) ? true : false;
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
        if (! empty($_REQUEST['option']) && 'smsalert-validate-otp-form' === sanitize_text_field(wp_unslash($_REQUEST['option'])) ) {
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
        if (! empty($_REQUEST['option']) && 'smsalert-validate-otp-form' === sanitize_text_field(wp_unslash($_REQUEST['option'])) ) {
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
     * Replace variables for sms contennt
     *
     * @param string $content  sms content to be sent.
     * @param array  $formdata formdata.
     *
     * @return string
     */
    public static function parseSmsContent( $content = null, $formdata = array() )
    {        
        $find          = array();
        $replace       = array();
        $content = preg_replace('/\[\w+_(\d+)\]/', '[field_$1]', $content);        
        foreach ( $formdata as $key=>$val) {
            if (strpos($key, "field_")!==false) {
                  $find[]    = "[".$key."]";     
                $replace[]  = is_array($val['value']) ? current($val['value']) : $val['value'];
            }
        } 
        $content      = str_replace($find, $replace, $content);      
        return $content;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('ws-form/ws-form.php') ) {
            add_filter('sAlertDefaultSettings', __CLASS__ . '::add_default_settings', 1, 2);
            add_action('sa_addTabs', array( $this, 'addTabs' ), 10);
        }
    }

    /**
     * Add tabs to smsalert settings at backend
     *
     * @param array $tabs list of tabs data.
     *
     * @return array
     */
    public static function addTabs( $tabs = array() )
    {
        $tabs['wsform']['nav']   = 'WS Form';
        $tabs['wsform']['icon']  = 'dashicons-list-view';
        $tabs['wsform']['inner_nav']['wsform_cust']['title']        = 'Customer Notifications';
        $tabs['wsform']['inner_nav']['wsform_cust']['tab_section']  = 'wsformcsttemplates';
        $tabs['wsform']['inner_nav']['wsform_cust']['first_active'] = true;
        $tabs['wsform']['inner_nav']['wsform_cust']['tabContent']   = array();
        $tabs['wsform']['inner_nav']['wsform_cust']['filePath']     = 'views/wsform_customer_template.php';
        $tabs['wsform']['inner_nav']['wsform_admin']['title']       = 'Admin Notifications';
        $tabs['wsform']['inner_nav']['wsform_admin']['tab_section'] = 'wsformadmintemplates';
        $tabs['wsform']['inner_nav']['wsform_admin']['tabContent']  = array();
        $tabs['wsform']['inner_nav']['wsform_admin']['filePath']    = 'views/wsform_admin_template.php';
        $tabs['wsform']['inner_nav']['wsform_admin']['icon']        = 'dashicons-list-view';
        $tabs['wsform']['inner_nav']['wsform_cust']['icon']         = 'dashicons-admin-users';
        
        return $tabs;
    }

    /**
     * Get variables to show variables above sms content template at backend settings.
     *
     * @param int $form_id    form id.
     * @param int $get_meta   get_meta.
     * @param int $get_groups get_groups.
     *
     * @return array
     */
    public static function getWsFormVariables($form_id = null,$get_meta = null,$get_groups = null)
    {
        $variables = array();
        $form = wsf_form_get_object($form_id, $get_meta = true, $get_groups = true);        
        $groups= $form->groups;
        foreach ($groups as $group) {
            $sections=$group->sections;
            foreach ($sections as $section) {
                $fields=$section->fields;
                foreach ($fields as $field) {            
                    $field_id= $field->id;                    
                    $forms=wsf_form_get_field($form, $field_id);
                    $type= $forms->type;
                    $labels= $forms->label;
                    $label = str_replace(' ', '', $labels);
                    if ($type!="submit" && $type!="tab_previous" && $type!="tab_next" && $type!="reset") {
                        $variables[$label."_".$field_id] = $label;    
                    }                    
                }                
            }    
        }
        return $variables; 
    }

    /**
     * Get default settings for the smsalert ws form.
     *
     * @param array $defaults smsalert backend settings default values.
     *
     * @return array
     */
    public static function add_default_settings( $defaults = array() )
    {
        $wpam_statuses = self::getWsForms();
        foreach ( $wpam_statuses as $ks => $vs ) {
            $defaults['smsalert_wsform_general'][ 'wsform_admin_notification_' . $ks ] = 'off';
            $defaults['smsalert_wsform_general'][ 'wsform_form_status_' . $ks ]        = 'off';
            $defaults['smsalert_wsform_general'][ 'wsform_message_' . $ks ]            = 'off';
            $defaults['smsalert_wsform_message'][ 'wsform_admin_sms_body_' . $ks ]     = '';
            $defaults['smsalert_wsform_message'][ 'wsform_sms_body_' . $ks ]           = '';
            $defaults['smsalert_wsform_general'][ 'wsform_sms_phone_' . $ks ]          = '';
            $defaults['smsalert_wsform_general'][ 'wsform_sms_otp_' . $ks ]            = '';
            $defaults['smsalert_wsform_general'][ 'wsform_otp_' . $ks ]                = '';
            $defaults['smsalert_wsform_message'][ 'wsform_otp_sms_' . $ks ]            = '';
        }
        return $defaults;
    }

    /**
     * Get Ws forms.
     *
     * @return array
     */
    public static function getWsForms()
    {
        $ws_forms = array(); 
        $obj        = new FL_WS_Form_Loader();
        $forms      = $obj->get_forms();        
        unset($forms[0]);
        foreach ( $forms as $form_id => $form_name) {
            $ws_forms[ $form_id ] = $form_name;
        }
        return $ws_forms; 
    } 
}
new SA_Wsform();
