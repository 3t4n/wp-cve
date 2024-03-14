<?php

/**
 * Wp-loyalty-rules helper.
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */

if (defined('ABSPATH') === false) {
    exit;
}


if (is_plugin_active('wp-loyalty-rules/wp-loyalty-rules.php') === false) {
    return;
}
use Wlr\App\Helpers\Base;
 
/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * WpLoyaltyRules class 
 */
class WpLoyalty extends FormInterface
{
    /**
     * Construct function.
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('wlr_after_add_earn_point', array($this, 'sendEarnPointSMS'), 10, 4); 
  
        /*   add_action('wlr_notify_after_add_earn_reward', array($this, 'sendRewardSMS'), 10, 4); */
      
      
        add_filter('wlr_after_save_extra_transaction', array($this, 'sendRewardSMS'), 100, 2);
        
    
    }

    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public static function add_default_setting($defaults = array())
    {
        $bookingStatuses = [
            'rewards',
            'points',
        ];
        foreach ($bookingStatuses as $ks) {
            $defaults['smsalert_wpl_general']['customer_wpl_notify_' . $ks]   = 'off';
            $defaults['smsalert_wpl_message']['customer_sms_wpl_body_' . $ks] = '';
        }
        
        return $defaults;
    }

    /**
     * Add tabs to smsalert settings at backend.
     *
     * @param array $tabs tabs.
     *
     * @return array
     */
    public static function addTabs($tabs = array())
    {
        
        $customerParam = array(
            'checkTemplateFor' => 'points',
            'templates'        => self::getCustomerTemplates(),
        );
        
        $customerParam = array(
            'checkTemplateFor' => 'rewards',
            'templates'        => self::getCustomerTemplates(),
        );

       

        $tabs['wp-loyalty']['nav']           = 'WPLoyalty';
        $tabs['wp-loyalty']['icon']          = 'dashicons-megaphone';

        $tabs['wp-loyalty']['inner_nav']['wp-loyalty_cust']['title']          = 'Customer Notifications';
        $tabs['wp-loyalty']['inner_nav']['wp-loyalty_cust']['tab_section']    = 'wployaltycusttemplates';
        $tabs['wp-loyalty']['inner_nav']['wp-loyalty_cust']['first_active']   = true;
        $tabs['wp-loyalty']['inner_nav']['wp-loyalty_cust']['tabContent']     = $customerParam;
        $tabs['wp-loyalty']['inner_nav']['wp-loyalty_cust']['filePath']       = 'views/message-template.php';
        $tabs['wp-loyalty']['help_links'] = [
            /* 'youtube_link' => [
                'href'   => 'https://youtu.be/4BXd_XZt9zM',
                'target' => '_blank',
                'alt'    => 'Watch steps on Youtube',
                'class'  => 'btn-outline',
                'label'  => 'Youtube',
                'icon'   => '<span class="dashicons dashicons-video-alt3" style="font-size: 21px;"></span> ',

            ], */
            'kb_link'      => [
                'href'   => 'https://kb.smsalert.co.in/knowledgebase/wployalty-sms-integration/',
                'target' => '_blank',
                'alt'    => 'Read how to integrate with wployalty',
                'class'  => 'btn-outline',
                'label'  => 'Documentation',
                'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
            ],
        ];
        return $tabs;
    }

    /**
     * Get customer templates.
     *
     * @return array
     */
    public static function getCustomerTemplates()
    {
        
        $bookingStatuses = [
            'rewards',
            'points',
        ];
        $templates           = [];
        $currentVal      = smsalert_get_option('customer_wpl_notify_points', 'smsalert_wpl_general', 'on');

        $checkboxNameId  = 'smsalert_wpl_general[customer_wpl_notify_points]';
        $textareaNameId  = 'smsalert_wpl_message[customer_sms_wpl_body_points]';

        $defaultTemplate = smsalert_get_option('customer_sms_wpl_body_points', 'smsalert_wpl_message', sprintf(__('%1$s: %2$s store credits added in your account! Total store credits are %3$s. %4$sPowered by%5$swww.smsalert.co.in', 'sms-alert'), '[store_name]', '[points]', '[total_points]', PHP_EOL, PHP_EOL));


        $textBody       = smsalert_get_option('customer_sms_wpl_body_points', 'smsalert_wpl_message', $defaultTemplate);

        $templates[0]['title']          = 'When customer add points ';
        $templates[0]['enabled']        = $currentVal;
        $templates[0]['status']         = 'points';
        $templates[0]['text-body']      = $textBody;
        $templates[0]['checkboxNameId'] = $checkboxNameId;
        $templates[0]['textareaNameId'] = $textareaNameId;
        $templates[0]['token']          = self::getWpLoyaltyvariables();
              
        $currentVal      = smsalert_get_option('customer_wpl_notify_rewards', 'smsalert_wpl_general', 'on');

        $checkboxNameId  = 'smsalert_wpl_general[customer_wpl_notify_rewards]';
        $textareaNameId  = 'smsalert_wpl_message[customer_sms_wpl_body_rewards]';

        $defaultTemplate = smsalert_get_option('customer_sms_wpl_body_rewards', 'smsalert_wpl_message', sprintf(__('Dear %1$s, %2$s loyalty points has been debited from your account.Your total points : %3$s. %4$sPowered by%5$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '[points]', '[total_points]', PHP_EOL, PHP_EOL));


        $textBody       = smsalert_get_option('customer_sms_wpl_body_rewards', 'smsalert_wpl_message', $defaultTemplate);

        $templates[1]['title']          = 'When customer redeem reward' ;
        $templates[1]['enabled']        = $currentVal;
        $templates[1]['status']         = 'rewards';
        $templates[1]['text-body']      = $textBody;
        $templates[1]['checkboxNameId'] = $checkboxNameId;
        $templates[1]['textareaNameId'] = $textareaNameId;
        $templates[1]['token']          = self::getWpLoyaltyvariables();
        
        return $templates;
    }

    /**
     * Send sms approved pending.
     *  
     * When earn points 
     *
     * @param int $email       email
     * @param int $points      points
     * @param int $action_type action_type
     * @param int $data        data
     *
     * @return void
     */
    public function sendEarnPointSMS($email, $points, $action_type,$data)
    {
        
        $action_type == 'signup';    
        $action_type == 'purchase_histories';
        $action_type == 'point_for_purchase';
        $action_type == 'subtotal';
        $action_type == 'product_review';
        $action_type == 'referral';
        $action_type == 'birthday';
        $action_type == 'facebook_share';
        $action_type == 'email_share';
        $action_type == 'whatsapp_share';
        $action_type == 'twitter_share';
    
        switch ($action_type) {
        case "signup":
            $user_email = $data['user_email'];
            $user = get_user_by('email', $user_email);
            $data['ID'] = $user->data->ID;
            $data['user_login'] = $user->data->user_login;
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true);
            $buyerNumber = $data['billing_phone'];
            break;
            
        case "purchase_histories":
            $buyerNumber       = $data['order']->data['billing']['phone'];
            $first_name              =  $data['order']->data['billing']['first_name'];
            $data['user_login'] = $first_name;
            $data['billing_phone'] = $buyerNumber ;
                
            break;
            
        case "point_for_purchase" :
            $buyerNumber       = $data['order']->data['billing']['phone'];
            $first_name              =  $data['order']->data['billing']['first_name'];
            $data['user_login'] = $first_name;
            $data['billing_phone'] = $buyerNumber ;
                
            break;
            
        case "subtotal" :
            $buyerNumber       = $data['order']->data['billing']['phone'];
            $first_name              =  $data['order']->data['billing']['first_name'];
            $data['user_login'] = $first_name;
            $data['billing_phone'] = $buyerNumber ;
                 
            break;
            
        case "product_review" :
                
            $user_email = $data['user_email'];
            $user = get_user_by('email', $user_email);
            $data['ID'] = $user->data->ID;
            $data['user_login'] = $user->data->user_login;
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true);
            $buyerNumber = $data['billing_phone'];
                
            break; 
       
        case "referral" :
                
            $buyerNumber       = $data['order']->data['billing']['phone'];
            $first_name              =  $data['order']->data['billing']['first_name'];
            $data['user_login'] = $first_name;
            $data['billing_phone'] = $buyerNumber ;
               
            break;
        
        case "birthday" : 
            $user_email = $data['user_email'];
            $user = get_user_by('email', $user_email); 
            $data['ID'] = $user->data->ID;
            $data['user_login'] = $user->data->user_login;
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true); 
            $buyerNumber = $data['billing_phone'];
            break;
           
        case "facebook_share" :
            $user_email = $data['user_email']; 
            $user = get_user_by('email', $user_email); 
            $data['ID'] = $user->data->ID;
            $data['user_login'] = $user->data->user_login;
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true); 
            $buyerNumber = $data['billing_phone'];
                
            break;
        case "email_share" : 
                
            $user_email = $data['user_email'];
                 
            $user = get_user_by('email', $user_email);
                 
            $data['ID'] = $user->data->ID;
                
            $data['user_login'] = $user->data->user_login;
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true);
                 
            $buyerNumber = $data['billing_phone'];
                
            break;
        case "whatsapp_share" :
            $user_email = $data['user_email'];
            $user = get_user_by('email', $user_email);
            $data['ID'] = $user->data->ID;
            $data['user_login'] = $user->data->user_login;
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true);
            $buyerNumber = $data['billing_phone'];
                
            break;
               
        case "twitter_share" :
                
            $user_email = $data['user_email'];
                 
            $user = get_user_by('email', $user_email);
                 
            $data['ID'] = $user->data->ID;
                
            $data['user_login'] = $user->data->user_login;
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true);
                 
            $buyerNumber = $data['billing_phone'];
            break;
        }
        $this->sendSmsOn($buyerNumber, $points, $action_type, $data); 
    }
    
    /**
     * Send sms Reward.
     *
     * @param int $insert_id insert_id
     * @param int $data      data
     *
     * @return void
     */
    public function sendRewardSMS($insert_id, $data)
    { 
        $this->sendSmsOnReward($insert_id, $data);
        
    }
    
     /**
      * Send sms approved pending.
      *
      * @param int $buyerNumber buyerNumber
      * @param int $points      points
      * @param int $action_type action_type
      * @param int $data        data
      *
      * @return void
      */
    public function sendSmsOn($buyerNumber, $points, $action_type,$data)
    {    
        $customerMessage   = smsalert_get_option('customer_sms_wpl_body_points', 'smsalert_wpl_message', '');
        
        $customerNotify    = smsalert_get_option('customer_wpl_notify_points', 'smsalert_wpl_general', 'on');
    
        if (($customerNotify === 'on' && $customerMessage !== '')) {
            $buyerMessage = $this->parseSmsBody($points, $data, $customerMessage);
                
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }
        
    }
    
    
    /**
     * Parse sms body.
     *
     * @param string $insert_id insert_id.
     * @param array  $data      data.
     *
     * @return string
     */
    public function sendSmsOnReward($insert_id,$data)
    {
        if ($data['action_type'] =='redeem_point' 
            && $data['transaction_type'] =='debit'
        ) {
            $obj = new Wlr\App\Helpers\Base();
        
            $total_points = $obj->getUserPoint($data['user_email']);
            
            
            
            $user_email = $data['user_email'];               
            $points = $data['points'];                 
            $user = get_user_by('email', $user_email);  
            $data['ID'] = $user->data->ID;
            $data['user_left_point'] = $total_points-$points;
            $data['user_login'] = $user->data->user_login;
            $first_name = $data['user_login'];
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true);
            
            $buyerNumber = $data['billing_phone'];
            $customerMessage   = smsalert_get_option('customer_sms_wpl_body_rewards', 'smsalert_wpl_message', '');
            $customerNotify    = smsalert_get_option('customer_wpl_notify_rewards', 'smsalert_wpl_general', 'on');
            
            
            
            
        } elseif ($data['action_type'] =='admin_change' 
            && $data['transaction_type'] =='credit'
        ) {
            $user_email = $data['user_email']; 
            $points = $data['points']; 
            $user = get_user_by('email', $user_email);      
            $data['ID'] = $user->data->ID;
            $data['user_login'] = $user->data->user_login;  
            $first_name = $data['user_login'];
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true);  
            $buyerNumber = $data['billing_phone'];
            $customerMessage   = smsalert_get_option('customer_sms_wpl_body_points', 'smsalert_wpl_message', '');
            $customerNotify    = smsalert_get_option('customer_wpl_notify_points', 'smsalert_wpl_general', 'on');
        } elseif ($data['action_type'] =='new_user_add' 
            && $data['transaction_type'] =='credit'
        ) {
            $user_email = $data['user_email'];         
            $points = $data['points'];               
            $user = get_user_by('email', $user_email);      
            $data['ID'] = $user->data->ID;            
            $data['user_login'] = $user->data->user_login;
            $first_name = $data['user_login'];
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true); 
            $buyerNumber = $data['billing_phone'];
            $customerMessage   = smsalert_get_option('customer_sms_wpl_body_points', 'smsalert_wpl_message', '');
            $customerNotify    = smsalert_get_option('customer_wpl_notify_points', 'smsalert_wpl_general', 'on');
        } else if ($data['action_type'] =='admin_change' 
            && $data['transaction_type'] =='debit'
        ) {
           
            $user_email = $data['user_email'];  
            $points = $data['points'];                 
            $user = get_user_by('email', $user_email);            
            $data['ID'] = $user->data->ID;
            $data['user_login'] = $user->data->user_login;
            $first_name = $data['user_login'];
            $data['billing_phone'] = get_user_meta($data['ID'], 'billing_phone', true);
            $buyerNumber = $data['billing_phone'];
            $customerMessage   = smsalert_get_option('customer_sms_wpl_body_rewards', 'smsalert_wpl_message', '');
            $customerNotify    = smsalert_get_option('customer_wpl_notify_rewards', 'smsalert_wpl_general', 'on');
        }

        if (($customerNotify === 'on' && $customerMessage !== '')) {
            $buyerMessage = $this->parseSmsBody($points, $data, $customerMessage);
          
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }
    }
    /**
     * Parse sms body.
     *
     * @param string $points  points.
     * @param array  $data    data.
     * @param string $content content.
     *
     * @return string
     */
    public function parseSmsBody($points, $data, $content = null)
    {  
            
            
    
    
    
        $id                       = !empty($data['order']->id) ? $data['order']->id : $data['ID'];
        $phone                        = $data['billing_phone'];     
        $first_name               =  $data['user_login'];
        $points                   = $points;
        
        if (!empty($data['user_left_point'])) {
            $total_points = $data['user_left_point'];
        } else {
            $obj = new Wlr\App\Helpers\Base();
            $total_points = $obj->getUserPoint($data['user_email']);
            
        }
        
        
        
        $find = array(
            '[id]',
        '[phone]',
            '[first_name]',
        '[points]',   
        '[total_points]',   
        );
        $replace = array(
        $id,
        $phone,
        $first_name,
        $points,    
        $total_points,    
        );
        $content = str_replace($find, $replace, $content);
        return $content;
    }

    /**
     * Get wployalty variables.
     *
     * @return array
     */
    public static function getWpLoyaltyvariables()
    {
        $variable['[id]']                = 'Id';
        $variable['[phone]']             = 'Phone';
        $variable['[first_name]']        = 'Name';
        $variable['[points]']            = 'Points';
        $variable['[total_points]']      = 'Total Points';
    
        return $variable;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {

        if (is_plugin_active('wp-loyalty-rules/wp-loyalty-rules.php') === true) {
            add_filter('sAlertDefaultSettings', __CLASS__ . '::add_default_setting', 1);
            add_action('sa_addTabs', array($this, 'addTabs'), 10);
        }
    }

    /**
     * Check your otp setting is enabled or not.
     *
     * @return bool
     */
    public function isFormEnabled()
    {

        $userAuthorize = new smsalert_Setting_Options();
        $islogged      = $userAuthorize->is_user_authorised();
        if ((is_plugin_active('wp-loyalty-rules/wp-loyalty-rules.php') === true) && ($islogged === true)) {
            return true;
        } else {
            return false;
        }
    }

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
     * @param bool $isAjax bool value for form type.
     *
     * @return bool
     */
    public function is_ajax_form_in_play($isAjax)
    {
            return $isAjax;
    }
}
new WpLoyalty();


