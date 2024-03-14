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

if (! is_plugin_active('jetformbuilder/jet-form-builder.php') ) {
    return; 
}
use Jet_Form_Builder\Classes\Tools;
use Jet_Form_Builder\Form_Manager;
use Jet_Form_Builder\Base;
use Jet_Form_Builder\Blocks\Block_Helper;


/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * JetForm class.
 */
class JetForm extends FormInterface
{

    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_action(
            'jet-form-builder/form-handler/after-send',
            array( $this, 'jetformSubmissionComplete' ), 10, 2
        );        
    }
    
    /**
     * Process jetform form submission and send sms
     *
     * @param array $form_data form data.
     * @param array $entry_id  entry_id.
     *
     * @return void
     */
    public function jetformSubmissionComplete($form_data, $entry_id )
    {
        global $form_id;          
        $form_id          = $form_data ->action_handler->form_id;
        $form_enable      = smsalert_get_option(
            'jetform_order_status_' . $form_id,
            'smsalert_jetform_general', 'on'
        );
        
        $phone_field      = smsalert_get_option(
            'jetform_sms_phone_' . $form_id, 
            'smsalert_jetform_general', ''
        );
        
        $buyer_sms_notify = smsalert_get_option(
            'jetform_message_' . $form_id, 
            'smsalert_jetform_general', 'on'
        );
        $admin_sms_notify = smsalert_get_option(
            'jetform_admin_notification_' . $form_id,
            'smsalert_jetform_general', 'on'
        );        
        if ('on' === $form_enable && 'on' === $buyer_sms_notify ) {
            $buyer_sms_content = smsalert_get_option(
                'jetform_sms_body_' . $form_id, 
                'smsalert_jetform_message', ''
            );    
            
            $formiii = $form_data->request_handler->_fields;
            $request_data = $form_data->action_handler->request_data;
            
            $mobile ='';            
            foreach ($request_data as $key=>$value) { 
           
                if ($key == $phone_field) {                   
                    $mobile = $value;
                }  
            }  
            $msg = self::parseSmsContent($buyer_sms_content, $form_data);
            do_action('sa_send_sms', $mobile, $msg);
        }
        if ('on' === $admin_sms_notify ) {
            $admin_phone_number = smsalert_get_option(
                'sms_admin_phone', 'smsalert_message', ''
            );
            $admin_phone_number = str_replace(
                'post_author', '', $admin_phone_number
            );
            if (! empty($admin_phone_number) ) {
                $admin_sms_content = smsalert_get_option(
                    'jetform_admin_sms_body_' . $form_id, 
                    'smsalert_jetform_message', ''
                );
                do_action(
                    'sa_send_sms', $admin_phone_number,
                    self::parseSmsContent($admin_sms_content, $form_data)
                );
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
        return ( is_plugin_active('jetformbuilder/jet-form-builder.php') 
        && $islogged ) ? true : false;
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
    public function handle_failed_verification($user_login,$user_email,$phone_number)
    {
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
    public function handle_post_verification($redirect_to,$user_login,$user_email,
        $password, $phone_number, $extra_data 
    ) {
    }

    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {
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
        return $is_ajax;
    }

    /**
     * Replace variables for sms contennt
     *
     * @param string $content   sms content to be sent.
     * @param array  $formdatas values of varibles.
     *
     * @return string
     */
    public static function parseSmsContent($content = null,$formdatas = array())
    {
        $find          = array();
        $replace       = array();        
        foreach ( $formdatas->action_handler->request_data
        as $key => $data ) { 
        
            $find[]    = "[".$key."]";            
            $replace[] = $data;            
        }
        $content       =str_replace($find, $replace, $content);            
        return $content; 
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('jetformbuilder/jet-form-builder.php') ) {
            add_filter(
                'sAlertDefaultSettings',
                __CLASS__ . '::add_default_settings', 1, 2
            );
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
        $tabs['jetform']
        ['nav']      ='JetForm';
        $tabs['jetform']
        ['icon']       = 'dashicons-list-view';
        $tabs['jetform']['inner_nav']
        ['jetform_cust']['title'] = 'Customer Notifications';
        $tabs['jetform']['inner_nav']
        ['jetform_cust']['tab_section'] = 'jetformcsttemplates';
        $tabs['jetform']['inner_nav']
        ['jetform_cust']['first_active'] = true;
        $tabs['jetform']['inner_nav']
        ['jetform_cust']['tabContent'] = array();
        $tabs['jetform']['inner_nav']
        ['jetform_cust']['filePath'] = 'views/jetform_customer_template.php';
        $tabs['jetform']['inner_nav']
        ['jetform_admin']['title'] = 'Admin Notifications';
        $tabs['jetform']['inner_nav']
        ['jetform_admin']['tab_section'] = 'jetformadmintemplates';
        $tabs['jetform']['inner_nav']
        ['jetform_admin']['tabContent'] = array();
        $tabs['jetform']['inner_nav']
        ['jetform_admin']['filePath'] = 'views/jetform_admin_template.php';
        $tabs['jetform']['inner_nav']
        ['jetform_admin']['icon'] = 'dashicons-list-view';
        $tabs['jetform']['inner_nav']
        ['jetform_cust']['icon']  = 'dashicons-admin-users'; 
		$tabs['jetform']['help_links']  = array(
			'kb_link'      => array(
			'href'   => 'https://kb.smsalert.co.in/knowledgebase/jetform-sms-integration/',
			'target' => '_blank',
			'alt'    => 'Read how to integrate with JetFormBuilder',
			'class'  => 'btn-outline',
			'label'  => 'Documentation',
			'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
			),
        ); 
        return $tabs;
    }

    /**
     * Get variables to show variables above sms
          content template at backend settings.
     *
     * @param int $form_id form id.
     *
     * @return array
     */
    public static function getJetformVariables( $form_id = null )
    {
        $variables = array();        
        $forms  =   Block_Helper::get_blocks_by_post($form_id);                   
        foreach ( $forms as $form ) {        
            $name = $form['attrs'];                  
            if (isset($name['name'])) {                    
                if (!empty($name)) {                    
                    $field_name = strtolower($name['name']);
                    $field_label      = !empty($name['name'])?$name['name']:''; 
                    $variables[$field_name] = strtolower($field_label); 
                }
            } elseif (isset($form['innerBlocks'])) {                
                foreach ($form['innerBlocks'] as $innerBlocks) {                          
                    foreach ($innerBlocks['innerBlocks'] as  $form_innerBlocks) {
                           $attrs=$form_innerBlocks['attrs'];                        
                        if (isset($attrs['name'])) {
                            if (!empty($attrs)) {
                                      $field_name    = strtolower($attrs['name']);
                                      $field_label = !empty($attrs['name'])?$attrs['name']:'';
                                      $variables[$field_name] = strtolower($field_label);                
                            }
                        }                        
                    }
                }            
            }           
        }
        return $variables;
    }

    /**
     * Set variables.
     *
     * @param array $datas jetform form datas array.
     *
     * @return array
     */
    public static function create_variables( $datas = array() )
    {
        $variables = array();
        foreach ( $datas as $field ) {
            if (array_key_exists('fields', $field) ) {
                foreach ( $field['fields'] as $key => $farray ) {
                    $variables[ '' . $key . '' ] = ucwords(
                        str_replace('_', ' ', $key)
                    );
                }
            } else {
                if (array_key_exists('name', $field['attributes']) ) {
                    $variables[ '' . $field['attributes']
                    ['name'] . '' ] = ucwords(
                        str_replace(
                            '_', ' ', $field['attributes']['name']
                        )
                    );
                }
            }
        }
        return $variables;
    }

    /**
     * Get default settings for the smsalert jetform forms.
     *
     * @param array $defaults smsalert backend settings default values.
     *
     * @return array
     */
    public static function add_default_settings( $defaults = array() )
    {
        $wpam_statuses = self::getJetformForms();
        foreach ( $wpam_statuses as $ks => $vs ) {
            $defaults['smsalert_jetform_general']
            [ 'jetform_admin_notification_' . $ks ] = 'off';
            $defaults['smsalert_jetform_general']
            [ 'jetform_order_status_' . $ks ]       = 'off';
            $defaults['smsalert_jetform_general']
            [ 'jetform_message_' . $ks ]            = 'off';
            $defaults['smsalert_jetform_message']
            [ 'jetform_admin_sms_body_' . $ks ]     = '';
            $defaults['smsalert_jetform_message']
            [ 'jetform_sms_body_' . $ks ]           = '';
            $defaults['smsalert_jetform_general']
            [ 'jetform_sms_phone_' . $ks ]          = '';            
        }
        return $defaults;
    }

    /**
     * Get jetform forms.
     *
     * @return array
     */
    public static function getJetformForms()
    {
        $jetform_forms = array();        
        $forms       = Tools::get_forms_list_for_js(true);        
        foreach ( $forms as $form_id => $form_name ) {            
            $jetform_forms[ $form_id ] = $form_name;
        }
        return $jetform_forms;
    }
}
new JetForm();