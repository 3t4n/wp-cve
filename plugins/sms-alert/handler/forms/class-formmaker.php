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

if (! is_plugin_active('form-maker/form-maker.php')) {
    return; 
}

require_once WP_PLUGIN_DIR . '/form-maker/admin/models/model.php';
require_once WP_PLUGIN_DIR . '/form-maker/admin/models/Submissions_fm.php';

/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 *
 * SAFormMaker class.
 */
class SAFormMaker extends FormInterface
{

    /**
     * Form Session Variable.
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::FORM_MAKER;

    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('fm_addon_frontend_init', array( $this, 'formmakerSubmissionComplete' ), 10, 1);
        add_action('WD_FM_SAVE_PROG_init', array( $this, 'addSmsalertShortcode' ), 10, 1);
       
    }
     
    
    /**
     * Add smsalert shortcode
     *
     * @param string $form form.
     *
     * @return void
     * 
     */    
    public function addSmsalertShortcode($form)
    {
        $unique_class    = 'sa-class-'.mt_rand(1, 100);
        $form_id     = $form['form']->id;
     
        $form_enable = smsalert_get_option('formmarker_order_status_' . $form_id, 'smsalert_formmarker_general', 'on'); 
        $otp_enable  = smsalert_get_option('formmarker_otp_' . $form_id, 'smsalert_formmarker_general', 'off');
		
        $phone_field = smsalert_get_option('formmarker_sms_phone_' . $form_id, 'smsalert_formmarker_general', '');
        $phonevalue = explode(":", $phone_field);
        $fieldid = !empty($phonevalue[1])?$phonevalue[1]:'';
        if ('on' === $form_enable && 'on' === $otp_enable && '' !== $phone_field ) {
            $uniqueNo = rand();
            $inline_script = 'jQuery(document).ready(function(){
				
				jQuery("form#form'. $form_id .'").each(function () 
				{
				  	if(!jQuery(this).hasClass("sa-wp-form"))
					{
					    jQuery(this).addClass("'.$unique_class.' sa-wp-form");
					}		
				});				
			});';
            if ( ! wp_script_is( 'sainlinescript-handle-footer', 'enqueued' ) ) {
			 wp_register_script( 'sainlinescript-handle-footer', '', [], '', true );
			 wp_enqueue_script( 'sainlinescript-handle-footer'  );
			}		
			wp_add_inline_script( "sainlinescript-handle-footer", $inline_script);			   
            echo do_shortcode('[sa_verify id="form'. esc_attr($form_id) . '" phone_selector="#wdform_'. $fieldid .'_element'. $form_id .'" submit_selector= "#form'. $form_id .' .button-submit" ]');
        }
    }


    /**
     * Process formmaker form submission and send sms
     *
     * @param int $form form.
     *
     * @return void
     */
    public function formmakerSubmissionComplete($form )
    {
		
        $form_id          = $form['form_id'];
        $form_enable      = smsalert_get_option('formmarker_order_status_' . $form_id, 'smsalert_formmarker_general', 'on');
		
        $phone_field      = smsalert_get_option('formmarker_sms_phone_' . $form_id, 'smsalert_formmarker_general', ''); 
        $buyer_sms_notify = smsalert_get_option('formmarker_message_' . $form_id, 'smsalert_formmarker_general', 'on'); 
        $admin_sms_notify = smsalert_get_option('formmarker_admin_notification_' . $form_id, 'smsalert_formmarker_general', 'on'); 
        if ('on' === $form_enable && 'on' === $buyer_sms_notify) {
            $buyer_sms_content = smsalert_get_option('formmarker_sms_body_' . $form_id, 'smsalert_formmarker_message', '');          
            $datas = $form['fvals'] ;
            $fmmobile = explode(":", $phone_field);
            $phoneId ='{' .$fmmobile[1]. '}';
            if (!empty($datas[$phoneId])) {            
                $mobileno =  $datas[$phoneId];
            }
            do_action('sa_send_sms', $mobileno, self::parseSmsContent($buyer_sms_content, $form));
        }
        if ('on' === $form_enable && 'on' === $admin_sms_notify ) {
            $admin_phone_number = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
            $admin_phone_number = str_replace('post_author', '', $admin_phone_number);
            if (! empty($admin_phone_number) ) {
                $admin_sms_content = smsalert_get_option('formmarker_admin_sms_body_'. $form_id, 'smsalert_formmarker_message', '');
                do_action('sa_send_sms', $admin_phone_number, self::parseSmsContent($admin_sms_content, $form));
            }
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
        return ( is_plugin_active('form-maker/form-maker.php') && $islogged ) ? true : false;
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
     * Replace variables for sms contennt
     *
     * @param string $content sms content to be sent.
     * @param array  $form    form.
     *
     * @return string
     */
    public static function parseSmsContent( $content = null, $form = array() )
    {
        $pattern = '/\[\w+:\d+\]/i';            
        $matches = preg_match_all($pattern, $content, $output);
        foreach ($output as $match) {
            foreach ($match as $matches) {                
                $fmvalue =    str_replace(array('[',']'), '', $matches);
                $fmvalues = explode(":", $fmvalue);            
                $fieldsId =$fmvalues[1];
                $withbrackets = '{' . $fieldsId . '}';             
                $content = str_replace($matches, $withbrackets, $content);
            } 
        }
        $content = str_replace(array_keys($form['fvals']), array_values($form['fvals']), $content);
        return $content;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('form-maker/form-maker.php') ) {
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
        $tabs['formmarker']['nav']  = 'Form Maker';
        $tabs['formmarker']['icon'] = 'dashicons-list-view';

        $tabs['formmarker']['inner_nav']['formmarker_cust']['title']        = 'Customer Notifications';
        $tabs['formmarker']['inner_nav']['formmarker_cust']['tab_section']  = 'formmarkertemplates';
        $tabs['formmarker']['inner_nav']['formmarker_cust']['first_active'] = true;
        $tabs['formmarker']['inner_nav']['formmarker_cust']['tabContent']   = array();
        $tabs['formmarker']['inner_nav']['formmarker_cust']['filePath']     = 'views/formmaker_customer_template.php';

        $tabs['formmarker']['inner_nav']['formmarker_admin']['title']       = 'Admin Notifications';
        $tabs['formmarker']['inner_nav']['formmarker_admin']['tab_section'] = 'formmarkeradmintemplates';
        $tabs['formmarker']['inner_nav']['formmarker_admin']['tabContent']  = array();
        $tabs['formmarker']['inner_nav']['formmarker_admin']['filePath']    = 'views/formmaker_admin_template.php';

        $tabs['formmarker']['inner_nav']['formmarker_admin']['icon'] = 'dashicons-list-view';
        $tabs['formmarker']['inner_nav']['formmarker_cust']['icon']  = 'dashicons-admin-users';
		$tabs['formmarker']['help_links']                        = array(
        
        'kb_link'      => array(
        'href'   => 'https://kb.smsalert.co.in/knowledgebase/form-maker-sms-integration/',
        'target' => '_blank',
        'alt'    => 'Read how to integrate with Form Maker',
        'class'  => 'btn-outline',
        'label'  => 'Documentation',
        'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
        ),
        );
        
        return $tabs;
    }

    /**
     * Get variables to show variables above sms content template at backend settings.
     *
     * @param int $form form.
     *
     * @return array
     */
    public static function getFormMakerVariables( $form = null )
    {
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}formmaker where `id` = %s", array( $form )));
        $fields = explode('*:*new_field*:*', $row->form_fields);
        $ids    = array();
        $types  = array();
        $labels = array();
        foreach ( $fields as $field ) {    
            if (!empty($field)) {            
                $temp = explode('*:*id*:*', $field);
                $id = $temp[0];
                if (array_key_exists(1, $temp) ) {
                    $temp = explode('*:*type*:*', $temp[1]);
                    array_push($types, $temp[0]);
                    $temp = explode('*:*w_field_label*:*', $temp[1]);
                }
                $label = str_replace(" ", "_", $temp[0]);
                $labels[$label.":".$id] = $temp[0];
            }
        }
        return $labels;
    }
  
    /**
     * Get default settings for the smsalert formmaker forms.
     *
     * @param array $defaults smsalert backend settings default values.
     *
     * @return array
     */
    public static function add_default_settings( $defaults = array() )
    {
        $wpam_statuses = self::getFormMaker();
        foreach ( $wpam_statuses as $ks => $vs ) {
            $defaults['smsalert_formmarker_general'][ 'formmarker_admin_notification_' . $ks ] = 'off';
            $defaults['smsalert_formmarker_general'][ 'formmarker_order_status_' . $ks ]       = 'off';
            $defaults['smsalert_formmarker_general'][ 'formmarker_message_' . $ks ]            = 'off';
            $defaults['smsalert_formmarker_message'][ 'formmarker_admin_sms_body_' . $ks ]     = '';
            $defaults['smsalert_formmarker_message'][ 'formmarker_sms_body_' . $ks ]           = '';
            $defaults['smsalert_formmarker_general'][ 'formmarker_sms_phone_' . $ks ]          = '';
            $defaults['smsalert_formmarker_general'][ 'formmarker_sms_otp_' . $ks ]            = '';
            $defaults['smsalert_formmarker_general'][ 'formmarker_otp_' . $ks ]                = '';
            $defaults['smsalert_formmarker_message'][ 'formmarker_otp_sms_' . $ks ]            = '';
        }
        return $defaults;
    }

    /**
     * Get formmaker forms.
     *
     * @return array
     */
    public static function getFormMaker()
    {
        $form_list = array();    
        $obj        = new FMModelSubmissions_fm();
        $forms      = $obj->get_forms();
        foreach ( $forms as $form ) {
            $form_id             = $form->id;
            $form_list[$form_id] = $form->title;
        }
        return $form_list;      
    }
}
new SAFormMaker();