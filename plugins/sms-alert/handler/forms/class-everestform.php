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

if (! is_plugin_active('everest-forms/everest-forms.php')) {
    return; 
}


/**
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 *
 * EverestForm class.
 */
class EverestForm extends FormInterface
{

    /**
     * Form Session Variable.
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::EVEREST_FORM;

    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_filter(
            'everest_forms_builder_settings_section', array( $this,
            'saEverestformBuilderSettingsSections'), 10, 2
        );
        add_action(
            'everest_forms_builder_content_settings', array( $this,
            'saEverestformFormSettingsPanelContent' ), 10
        );        
        add_action(
            'everest_forms_process_complete', array( 
            $this, 'saSendSmsOnSubmission' ), 10, 4
        );
        add_action(
            'everest_forms_display_submit_after', array( 
            $this, 'saHandleOtpEvfForm' ), 10, 1
        );  
    }
    
    /**
     * Handle smsalert everest form otp shortcode.
     *
     * @param rray $form_data form_data.
     *
     * @return string
     */     
    public function saHandleOtpEvfForm( $form_data)
    {    
        $form_id         = $form_data['id'];
        $form_field     = $form_data['form_fields'];        
        $unique_class   = 'sa-class-'.mt_rand(1, 100);
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();
        $phone_field    = !empty(
            $form_data['settings']
            ['smsalert']['visitor_phone']
        )? $form_data['settings']
        ['smsalert']['visitor_phone']:''; 
        $otp_enable = $form_data['settings']
        ['smsalert']['otp_enable'];
        if (!empty($otp_enable) && !empty($phone_field)) {                
            $phone_fields = '';
            foreach ($form_field as $field) {                                    
                $phone_field == $form_data['settings']
                ['smsalert']['visitor_phone'];
                if (strpos($phone_field, $field['id'])) {
                    $phone_fields = $field['id'];    
                }                    
            }    
            $field_id = "evf-". $form_id . "-field_" . $phone_fields;        
            echo do_shortcode(
                '[sa_verify  phone_selector="#'
                .$field_id .'" submit_selector= ".evf-submit" ]'
            );
        }            
    }    
    
    /**
     * Get form data
     *
     * @return array form data.
     */
    public function form_data()
    {
        $form_data = array();
        if (! empty($_GET['form_id']) ) {
            $form_data = evf()->form->get(
                absint(
                    $_GET['form_id']
                ), array( 'content_only' => true )
            );    
        }
        return $form_data;
    }

    /**
     * Add Tab smsalert setting in evrestform builder section
     *
     * @param array $tab       form tab.
     * @param array $form_data form data.
     *
     * @return array
     */
    public function saEverestformBuilderSettingsSections($tab, $form_data)
    {
        $tab['smsalert']= esc_html__(
            'SMS Alert', 'smsalert_form'
        );
        return $tab;
    }

    /**
     * Add Tab panel smsalert setting in evrestform builder section
     *
     * @return void
     */
    public function saEverestformFormSettingsPanelContent()
    {
        $form_data = $this->form_data();    
        $settings = isset($form_data['settings']) ? 
        $form_data['settings'] : array();        
        echo '<div class="evf-content-section evf-content-smsalert-settings">';
        echo '<div class="evf-content-section-title">';
        esc_html_e('SMS Alert Message Configuration', 'smsalert_form');
        echo '</div>';
        echo '<a href="https://kb.smsalert.co.in/knowledgebase/everest-forms-sms-integration/" target="_blank" class="btn-outline"><span class="dashicons dashicons-format-aside"></span> Documentation</a><br>';        
        everest_forms_panel_field(
            'checkbox',
            'smsalert',
            'enable_message',
            $form_data,
            esc_html__('Enable Message', 'sms-alert'),
            array(
            'default' => isset($this->form->enable_message) ? 
            $this->form->enable_message : '',
            'tooltip' => esc_html__(
                'Enable to send customer and admin notifications', 'sms-alert'
            ),
            'parent'     => 'settings',
            )
        ); 
        everest_forms_panel_field(
            'checkbox',
            'smsalert',
            'otp_enable',
            $form_data,
            esc_html__('Enable Mobile Verification', 'sms-alert'),
            array(
            'default' => isset($this->form->otp_enable) ? 
            $this->form->otp_enable : '',
            'tooltip' => esc_html__('Enable Mobile Verification', 'sms-alert'),
            'parent'     => 'settings',
            )
        );
        everest_forms_panel_field(
            'text',
            'smsalert',
            'admin_number',
            $form_data,
            esc_html__('Send Admin SMS To', 'sms-alert'),            
            array(
            'default' => isset($this->form->admin_number) ?
            $this->form->admin_number : '',
            'tooltip' => esc_html__(
                'Admin sms notifications will be sent to this number', 'sms-alert'
            ),
            'smarttags'  => array(
                                'type'        => 'fields',
                                'form_fields' => 'smsalert',
                            ),
                            'parent'     => 'settings',
            )
        );
        everest_forms_panel_field(
            'textarea',
            'smsalert',
            'admin_message',
            $form_data,
            esc_html__('Admin Message', 'sms-alert'),            
            array(
            'default' => SmsAlertMessages::showMessage(
                'DEFAULT_CONTACT_FORM_ADMIN_MESSAGE'
            ),
            'smarttags'  => array(
                                'type'        => 'fields',
                                'form_fields' => 'smsalert',
                            ),
                            'parent'     => 'settings',                
            )
        );
        everest_forms_panel_field(
            'text',
            'smsalert',
            'visitor_phone',
            $form_data,
            esc_html__('Select Phone Field', 'sms-alert'),            
            array(
            'default' => isset($this->form->visitor_phone) ?
            $this->form->visitor_phone : '',
            'tooltip' => esc_html__(
                'Customer sms notifications will be sent to this number', 'sms-alert'
            ),
            'smarttags'  => array(
                                'type'        => 'fields',
                                'form_fields' => 'smsalert',
                            ),
                            'parent'     => 'settings',                
            )            
        );
        everest_forms_panel_field(
            'textarea',            
            'smsalert',
            'visitor_message',
            $form_data,
            esc_html__('Visitor Message', 'sms-alert'),            
            array(
            'default' => SmsAlertMessages::showMessage(
                'DEFAULT_CONTACT_FORM_CUSTOMER_MESSAGE'
            ),
            'smarttags'  => array(
                                'type'        => 'fields',
                                'form_fields' => 'smsalert',
                            ),
                            'parent'     => 'settings',                
            )
        );
        echo '</div>';
    }

    /**
     * Process everest form submission and send sms
     *
     * @param array $fields    form fields.
     * @param array $entry     form entries.
     * @param array $form_data form data.
     * @param int   $entry_id  entity id.
     *
     * @return void
     */
    public function saSendSmsOnSubmission($fields, $entry, $form_data, $entry_id)
    {        
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();
        $msg_enable     = !empty(
            $form_data['settings']
            ['smsalert']['enable_message']
        )?
        $form_data['settings']['smsalert']['enable_message']:'';        
        if ($msg_enable && $islogged) {            
            $phone_field     = $form_data['settings']
            ['smsalert']['visitor_phone'];             
            $admin_number    = $form_data['settings']
            ['smsalert']['admin_number'];        
            $visitor_message = $form_data['settings']
            ['smsalert']['visitor_message'];            
            $admin_message   = $form_data['settings']
            ['smsalert']['admin_message'];            
            if (! empty($phone_field) ) {
                $phone = '';                
                foreach ( $fields as $key => $field ) {
                    $evf_field  =! empty($field['value']['name']) ?
                    $field['value']['name'] : $field['name'];
                    $ev_field   = strtolower($evf_field);
                    $evf_fields = explode(" ", $ev_field);
                    $first_word = array_shift($evf_fields);                     
                    $new_words  = array_map(
                        function ($data) {
                            return ucwords($data);
                        }, $evf_fields
                    );             
                    $label_name   = implode("", $new_words);
                    $evform_field = $first_word.$label_name;    
                    $search       = '{field_id="'.$evform_field.'_' . $key . '"}';             
                    $replace      = $field['value'];                    
                    if ($phone_field == $search ) {
                               $phone = $field['value'];                         
                    }
                }                
                if (! empty($msg_enable) 
                    && ! empty($visitor_message) 
                    && ! empty($phone_field)
                ) {
                    $cst_sms = self::parseSmsContent($visitor_message, $fields);             
                    do_action('sa_send_sms', $phone, $cst_sms);
                }
                if (! empty($admin_number) ) {
                    $admin_sms = self::parseSmsContent($admin_message, $fields); 
                    do_action('sa_send_sms', $admin_number, $admin_sms);
                }
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
        return ( $islogged && (
        is_plugin_active('everest-forms/everest-forms.php') )) ? true : false;
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
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) ) {
            return;
        }
        if (! empty($_REQUEST['option']) && sanitize_text_field(
            wp_unslash($_REQUEST['option'])
        ) === 'smsalert-validate-otp-form' 
        ) {
            wp_send_json(
                SmsAlertUtility::_create_json_response(
                    SmsAlertMessages::showMessage('INVALID_OTP'), 'error'
                )
            );
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
        if (! empty($_REQUEST['option']) 
            && sanitize_text_field(
                wp_unslash(
                    $_REQUEST['option']
                )
            )==='smsalert-validate-otp-form' 
        ) {
            wp_send_json(
                SmsAlertUtility::_create_json_response(
                    'OTP Validated Successfully.', 'success'
                )
            );
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
     * @param array  $fields  fields.
     *
     * @return string
     */
    public static function parseSmsContent( $content, $fields)
    {
        $search= $replace = array();
        foreach ( $fields as $key => $field ) {    
                    $evf_field  = ! empty($field['value']['name']) ?
            $field['value']['name'] : $field['name'];        
                    $ev_field    = strtolower($evf_field);                      
            $evf_fields = explode(" ", $ev_field);
            $first_word = array_shift($evf_fields);                     
            $new_words = array_map(
                function ($data) {
                    return ucwords($data);
                }, $evf_fields
            );             
            $label_name = implode("", $new_words);//label eg firstName
            $evform_field = $first_word.$label_name;                    
            $search[]    = '{field_id="'
            .$evform_field.'_' . $key . '"}';                 
            $replace[]   =  is_array($field['value']) ?
            (!empty($field['value']['label']) ?
            (is_array($field['value']['label'])?
            implode(",", $field['value']['label']):$field['value']['label'])
            : implode(",", $field['value']) ): $field['value'];                     
        }
                       
        $content = str_replace($search, $replace, $content);
        return $content;
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
new EverestForm();