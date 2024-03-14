<?php
/**
 * Awesome Suppor helper.
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
if (! is_plugin_active('awesome-support/awesome-support.php') ) {
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
 * AwesomeSupport class 
 */
class SaAwesomeSupport extends FormInterface
{
    
    /**
     * Form Session Variable.
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::AWESOME_SUPPORT;
    
   
    /**
     * Construct function.
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('wpas_after_registration_fields', array($this, 'addPhoneField'), 10, 1);
        add_filter('sa_get_user_phone_no', array( $this, 'saUpdateBillingPhone' ), 10, 2);
        add_action('wpas_open_ticket_after', array( $this, 'newTicketSubmission' ), 10, 2);
        add_action('wpas_ticket_after_update_admin_success', array( $this, 'updateTicketStatus' ), 10, 3);
        add_action('wpas_after_login_fields', array( $this, 'saSiteLoginOtp' ), 10);
        add_action('wpas_after_registration_fields', array( $this, 'saRegisterOtp' ), 10);
        add_action('wpas_after_close_ticket', array( $this,'closeTicket' ), 10, 3);
        add_action('wpas_after_reopen_ticket', array( $this,'reopenTicket' ), 10, 2);
        add_filter('wp_pre_insert_user_data', array( $this,'siteRegistrationErrors' ), 10, 4);
    }//end handleForm()
    
    /**
     * Register  Otp
     *
     * @return void
     */
    public function saRegisterOtp()
    {
        echo '<script>       
		jQuery( "#wpas_billling_phone" ).addClass("phone-valid");		
		</script>';
        $buyer_signup_otp = smsalert_get_option('buyer_signup_otp', 'smsalert_general');
        if ('on' === $buyer_signup_otp ) {
            echo do_shortcode('[sa_verify phone_selector="wpas_billling_phone" submit_selector="#wpas_form_registration .wpas-btn"]');    
        }
    }
    
    /**
     * Site Login Otp
     *
     * @return void
     */
    public function saSiteLoginOtp()
    {
        $default_login_otp   = smsalert_get_option('buyer_login_otp', 'smsalert_general');       
        if ('on' === $default_login_otp ) {
            echo do_shortcode('[sa_verify id="#wpas_form_login" user_selector="#wpas_log" pwd_selector="#wpas_pwd" submit_selector="#wpas_form_login .wpas-btn"]');
        }
    }
    
    /**
     * Add phone field
     *
     * @param string $moblie moblie
     *
     * @return void
     */
    public function addPhoneField($moblie)
    {
        $billling_phone_desc = wpas_get_option('reg_billling_phone_desc', '');
        $mobile = new WPAS_Custom_Field(
            'billling_phone', array(
            'name' => 'billling_phone',
            'args' => array(
            'required'    => true,
            'field_type'  => 'text',
            'label'       => __('Phone', 'sms-alert'),
            'placeholder' => __('Phone', 'sms-alert'),
            'sanitize'    => 'sanitize_text_field',
            'desc'          => $billling_phone_desc,
            'default'      => ( isset($_SESSION["wpas_registration_form"]["billling_phone"]) && $_SESSION["wpas_registration_form"]["billling_phone"] ) ? $_SESSION["wpas_registration_form"]["billling_phone"] : ""
            )
            ) 
        );
        echo $mobile->get_output();
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
        if (! empty($_POST['wpas_billling_phone'])) {
            return  $_POST['wpas_billling_phone'];
        }
        return $billing_phone;
    }
    
    /**
     * Show site registration errors.
     *
     * @param array $data     data
     * @param array $update   update
     * @param array $user_id  user_id
     * @param array $userdata userdata
     *
     * @return array
     */
    public function siteRegistrationErrors($data, $update, $user_id, $userdata)
    {
        SmsAlertUtility::checkSession();
		 $phone = ( ! empty($_POST['wpas_billling_phone']) ) ?sanitize_text_field(wp_unslash($_POST['wpas_billling_phone'])) : '';
		if (! SmsAlertcURLOTP::validateCountryCode($phone)){		
			return $data;
		}
        if (isset($_SESSION['sa_mobile_verified']) ) {                
              unset($_SESSION['sa_mobile_verified']);
              return $data;
        }
        if (!$update ) {
            $password = ( ! empty($_REQUEST['wpas_password']) ) ?sanitize_text_field(wp_unslash($_REQUEST['wpas_password'])) : '';           
            $userEmail = ( ! empty($_REQUEST['wpas_email']) ) ?sanitize_text_field(wp_unslash($_REQUEST['wpas_email'])) : '';
            if (isset($_REQUEST['option']) && sanitize_text_field(wp_unslash($_REQUEST['option']) === 'smsalert_wpas_form_otp')) {
                SmsAlertUtility::initialize_transaction($this->form_session_var);
            } else {
                return $data;
            }        
            
            if (smsalert_get_option('allow_multiple_user', 'smsalert_general') !== 'on' && ! SmsAlertUtility::isBlank($phone) ) {            
                $getusers = SmsAlertUtility::getUsersByPhone('billling_phone', $phone);  
                if (count($getusers) > 0 ) {
                    wp_send_json(SmsAlertUtility::_create_json_response('An account is already registered with this mobile number!', 'error'));                    
                }      
            }     
             return $this->processFormFields($userEmail, $password, $phone);
        }
        return $data;
    } 

    /**
     * Initialise the otp verification.
     *
     * @param string $userEmail userEmail
     * @param string $password  password
     * @param string $phone     phone
     *
     * @return array
     */    
    public function processFormFields($userEmail,$password,$phone)
    {
		global $phoneLogic;		
        $extra_data= null;
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (! isset($phone) || ! SmsAlertUtility::validatePhoneNumber($phone)) {
            return new WP_Error('billing_phone_error', str_replace('##phone##', SmsAlertcURLOTP::checkPhoneNos($phone), $phoneLogic->_get_otp_invalid_format_message()));
        }       
        smsalert_site_challenge_otp('test', $userEmail, $password, $phone, 'phone', $extra_data); 
    
    } 
    
    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public static function add_default_setting($defaults=[])
    {
        $ticketStatuses = wpas_get_post_status();    
        $ticketStatuses['open'] = 'open';    
        $ticketStatuses['closed'] = 'closed';            
        foreach ($ticketStatuses as $ks => $vs) {
            $defaults['smsalert_ast_general']['customer_ast_notify_'.$ks]   = 'off';
            $defaults['smsalert_ast_message']['customer_sms_ast_body_'.$ks] = '';
            $defaults['smsalert_ast_general']['admin_ast_notify_'.$ks]      = 'off';
            $defaults['smsalert_ast_message']['admin_sms_ast_body_'.$ks]    = '';
        }
        $defaults['smsalert_ast_general']['otp_enable']      = 'off';
        $defaults['smsalert_ast_general']['customer_notify'] = 'off';       
        return $defaults;

    }//end add_default_setting()

    /**
     * Add tabs to smsalert settings at backend.
     *
     * @param array $tabs tabs.
     *
     * @return array
     */
    public static function addTabs($tabs=[])
    {
        $customerParam = [
            'checkTemplateFor' => 'ast_customer',
            'templates'        => self::getCustomerTemplates(),
        ];

        $adminParam = [
            'checkTemplateFor' => 'ast_admin',
            'templates'        => self::getAdminTemplates(),
        ];

        $tabs['awesome_support']['nav']  = 'Awesome Support';
        $tabs['awesome_support']['icon'] = 'dashicons-admin-users';

        $tabs['awesome_support']['inner_nav']['awesome_support_cust']['title']        = 'Customer Notifications';
        $tabs['awesome_support']['inner_nav']['awesome_support_cust']['tab_section']  = 'ticketcusttemplates';
        $tabs['awesome_support']['inner_nav']['awesome_support_cust']['first_active'] = true;
        $tabs['awesome_support']['inner_nav']['awesome_support_cust']['tabContent']   = $customerParam;
        $tabs['awesome_support']['inner_nav']['awesome_support_cust']['filePath']     = 'views/message-template.php';

        $tabs['awesome_support']['inner_nav']['awesome_support_admin']['title']          = 'Admin Notifications';
        $tabs['awesome_support']['inner_nav']['awesome_support_admin']['tab_section']    = 'ticketadmintemplates';
        $tabs['awesome_support']['inner_nav']['awesome_support_admin']['tabContent']     = $adminParam;
        $tabs['awesome_support']['inner_nav']['awesome_support_admin']['filePath']       = 'views/message-template.php';       
         $tabs['awesome_support']['help_links']                        = array(        
        'kb_link'      => array(
        'href'   => 'https://kb.smsalert.co.in/knowledgebase/awesome-support-sms-integration/',
        'target' => '_blank',
        'alt'    => 'Read how to integrate with Awesome Support',
        'class'  => 'btn-outline',
        'label'  => 'Documentation',
        'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
        ),
        );
        return $tabs;

    }//end addTabs()

    /**
     * Get customer templates.
     *
     * @return array
     */
    public static function getCustomerTemplates()
    {
        $ticketStatuses = wpas_get_post_status();
        $ticketStatuses['open'] = 'open';
        $ticketStatuses['closed'] = 'closed';        
        $templates = [];
        foreach ($ticketStatuses as $ks  => $vs) {            
            $currentVal = smsalert_get_option('customer_ast_notify_'.strtolower($ks), 'smsalert_ast_general', 'on');
            
            $checkboxMameId = 'smsalert_ast_general[customer_ast_notify_'.strtolower($ks).']';
            
            $textareaNameId = 'smsalert_ast_message[customer_sms_ast_body_'.strtolower($ks).']';
            
            $defaultTemplate = sprintf(__('Hello %1$s, status of your ticket #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[client_first_name]', '[ticket_id]', '[store_name]', $vs, PHP_EOL, PHP_EOL);
            
            $textBody = smsalert_get_option('customer_sms_ast_body_'.strtolower($ks), 'smsalert_ast_message', $defaultTemplate);
            
            $templates[$ks]['title']          = 'When customer ticket is '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $ks;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxMameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getvariables();
        }//end foreach
        return $templates;

    }//end getCustomerTemplates()


    /**
     * Get admin templates.
     *
     * @return array
     */
    public static function getAdminTemplates()
    {
        $ticketStatuses =  wpas_get_post_status();
        $ticketStatuses['open'] = 'open';
        $ticketStatuses['closed'] = 'closed';
        $templates = [];
        foreach ($ticketStatuses as $ks  => $vs) {
            $currentVal     = smsalert_get_option('admin_ast_notify_'.strtolower($ks), 'smsalert_ast_general', 'on');
            $checkboxMameId = 'smsalert_ast_general[admin_ast_notify_'.strtolower($ks).']';
            $textareaNameId = 'smsalert_ast_message[admin_sms_ast_body_'.strtolower($ks).']';

            $defaultTemplate = sprintf(__('%1$s: Your ticket #%2$s is %3$s. %4$sPowered by%5$swww.smsalert.co.in', 'sms-alert'), '[store_name]', '[ticket_id]', $vs, PHP_EOL, PHP_EOL);

            $textBody = smsalert_get_option('admin_sms_ast_body_'.strtolower($ks), 'smsalert_ast_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When admin ticket status to '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $ks;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxMameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getvariables();
        }//end foreach

        return $templates;

    }//end getAdminTemplates()

    /**
     * New Ticket Submission.
     *
     * @param int $ticket_id ticket_id
     * @param int $data      data
     *
     * @return void
     */
    public function newTicketSubmission($ticket_id, $data)
    {
        
        $ticketStatus   = $data['post_status'];
        $this->sendSms($ticket_id, $data, $ticketStatus);        
    }    //end newTicketSubmission()
    
    /**
     * Update Ticket Status.
     *
     * @param int $ticket_id    ticket_id
     * @param int $old_assignee old_assignee
     * @param int $data         data
     *
     * @return void
     */
    public function updateTicketStatus($ticket_id, $old_assignee, $data)
    {
		
        $oldStatus = $data['original_post_status']; 
        $ticketStatus = $data['post_status_override'];
		if ( ($oldStatus != $ticketStatus) && 	empty($data['wpas_do'])) {  	
              $this->sendSms($ticket_id, $data, $ticketStatus);
        }
    }
    
    /**
     * Reopen Ticket Submission.
     *
     * @param int $ticket_id ticket_id
     * @param int $update    update
     *
     * @return void
     */
    public function reopenTicket($ticket_id, $update)
    {
        global $post;
        $datas = get_post($ticket_id);
        $data = json_decode(json_encode($datas), true);
        $ticketStatus = get_post_meta($ticket_id, '_wpas_status', true);
        $this->sendSms($ticket_id, $data, $ticketStatus);
        
    }
    
    /**
     * Close Ticket Submission.
     *
     * @param int $ticket_id ticket_id
     * @param int $update    update
     * @param int $user_id   user_id
     *
     * @return void
     */
    public function closeTicket($ticket_id, $update, $user_id )
    {        
        global $post;
        $datas = get_post($ticket_id);    
        $data = json_decode(json_encode($datas), true);        
        $ticketStatus = $data['comment_status'];
        $this->sendSms($ticket_id, $data, $ticketStatus);
        
    }
    
    /**
     * Send  Ticket Sms.
     *
     * @param int $ticket_id    ticket_id
     * @param int $data         data
     * @param int $ticketStatus ticketStatus
     *
     * @return void
     */
    public function sendSms($ticket_id, $data,$ticketStatus)
    { 
        if ($data['post_type'] == 'ticket') {
            $user_id    = $data['post_author'];     
            $buyerNumber        = get_user_meta($user_id, 'billing_phone', true);        
            $customerMessage    = smsalert_get_option('customer_sms_ast_body_'.$ticketStatus, 'smsalert_ast_message', '');
            $customerNotify        = smsalert_get_option('customer_ast_notify_'.$ticketStatus, 'smsalert_ast_general', 'on');
            if (($customerNotify === 'on' && $customerMessage !== '')) {
                $buyerMessage = $this->parseSmsBody($data, $customerMessage, $ticket_id);
                do_action('sa_send_sms', $buyerNumber, $buyerMessage);
            }
            // Send msg to admin.
            $adminPhoneNumber     = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

            if (empty($adminPhoneNumber) === false) {
                $adminNotify      = smsalert_get_option('admin_ast_notify_'.$ticketStatus, 'smsalert_ast_general', 'on');

                $adminMessage     = smsalert_get_option('admin_sms_ast_body_'.$ticketStatus, 'smsalert_ast_message', '');

                $nos              = explode(',', $adminPhoneNumber);
                $adminPhoneNumber = array_diff($nos, ['postauthor', 'post_author']);
                $adminPhoneNumber = implode(',', $adminPhoneNumber);

                if ($adminNotify === 'on' && $adminMessage !== '') {
                    $adminMessage = $this->parseSmsBody($data, $adminMessage, $ticket_id);
                    do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
                } 
            }
        }
    }

    /**
     * Parse sms body.
     *
     * @param array  $data      data.
     * @param string $content   content.
     * @param string $ticket_id ticket_id.
     *
     * @return void
     */
    public function parseSmsBody($data, $content, $ticket_id)
    {    
        $ticketStatuses  = wpas_get_post_status();
        $ticketStatus   = !empty($data['post_status_override'])?$data['post_status_override']:(!empty($data['post_status']) ? $data['post_status'] :$data['comment_status']);
        $user_id          = $data['post_author'];        
        $meta             = get_user_meta($user_id);        
        $status           = $ticketStatuses[$ticketStatus]; 
        $store_name       = trim(get_bloginfo());        
        $find = [
            '[billing_phone]',
            '[ticket_status]',
            '[store_name]',
        ];
        $replace = [
           get_user_meta($user_id, 'billing_phone', true),
           $status,
           $store_name,
        ];
        
        $content = str_replace($find, $replace, $content); 
        $obj              = new WPAS_Email_Notification($ticket_id);
        $matches = str_replace(array( '[', ']' ), array('{', '}'), $content);
        $content            = $obj->fetch($matches);                
        return $content;
    }//end parseSmsBody()


    /**
     * Get variables.
     *
     * @return array
     */
    public static function getvariables()
    {        
        $list_tags                        = WPAS_Email_Notification::get_tags();
        $variables                        =array();
        foreach ($list_tags as $token) {
            $value                        = $token['tag'];            
            preg_match_all('/{(\w+)}/', $value, $matches);
            $token                        = $matches[1][0];            
            $variables["[".$token."]"]    = $token;                   
        }
        $variables["[billing_phone]"]     = "Billing Phone";    
        $variables["[ticket_status]"]       = "Ticket Status";                
        return $variables;
        

    }//end getvariables()


    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('awesome-support/awesome-support.php') === true) {
            add_filter('sAlertDefaultSettings', __CLASS__.'::add_default_setting', 1);
            add_action('sa_addTabs', [$this, 'addTabs'], 10);
        }
    }//end handleFormOptions()


    /**
     * Check your otp setting is enabled or not.
     *
     * @return bool
     */
    public function isFormEnabled()
    {
        $userAuthorize = new smsalert_Setting_Options();
        $islogged      = $userAuthorize->is_user_authorised();
        if ((is_plugin_active('awesome-support/awesome-support.php') === true) && ($islogged === true)) {
            return true;
        } else {
            return false;
        }

    }//end isFormEnabled()


    /**
     * Handle after failed verification
     *
     * @param object $userLogin   users object.
     * @param string $userEmail   user email.
     * @param string $phoneNumber phone number.
     *
     * @return void
     */
    public function handle_failed_verification($userLogin, $userEmail, $phoneNumber)
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ])) {
            return;
        }
        if (isset($_SESSION[ $this->form_session_var ])) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
        }
    }


    /**
     * Handle after post verification
     *
     * @param string $redirectTo  redirect url.
     * @param object $userLogin   user object.
     * @param string $userEmail   user email.
     * @param string $password    user password.
     * @param string $phoneNumber phone number.
     * @param string $extraData   extra hidden fields.
     *
     * @return void
     */
    public function handle_post_verification($redirectTo, $userLogin, $userEmail, $password, $phoneNumber, $extraData)
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ])) {
            return;
        }        
         $_SESSION['sa_mobile_verified'] = true;     
        if (isset($_SESSION[ $this->form_session_var ])) {
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
        unset($_SESSION[$this->form_session_var]);
    }


    /**
     * Check current form submission is ajax or not
     *
     * @param bool $isAjax bool value for form type.
     *
     * @return bool
     */
    public function is_ajax_form_in_play($isAjax)
    {
        SmsAlertUtility::checkSession();
        return isset($_SESSION[ $this->form_session_var ]) ? true : $isAjax;
    }
}
new SaAwesomeSupport();