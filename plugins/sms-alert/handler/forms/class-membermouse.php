<?php

/**
 * Membermouse helper.
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

if (is_plugin_active('membermouse/index.php') === false) {
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
 * SAMemberMouses class 
 */
class SAMemberMouses extends FormInterface
{
    /**
     * Construct function.
     *
     * @return void
     */
    public function handleForm()
    {
        add_action(
            'mm_member_add', array($this,
            'sendSmsOnStatusMemberAdd')
        );
        
        add_action('mm_member_membership_change', array($this, 'sendSmsOnStatusMemberMembershipChange'));
         
        add_action('mm_member_status_change',     array($this, 'sendSmsOnStatusMemberStatusChange'));
         
        add_action('mm_bundles_add',              array($this, 'sendSmsOnStatusBundlesAddedToMember'));
     
        add_action('mm_bundles_status_change',    array($this, 'sendSmsOnStatusBundlesStatusChange'));
        
        add_action('mm_payment_received',         array($this, 'sendSmsOnStatusPaymentReceived'));
        
        add_action('mm_refund_issued',            array($this, 'sendSmsOnStatusRefundIssued'));
        
        add_action(
            'smsalert_followup_sms',  array($this,
            'smsalertSendSms')
        );
       
    }


    /**
     * Set renewal reminder.
     *
     * @param array $data   data.
     * @param array $status status.
     *
     * @return void
     */
    public function setRenewalReminder( $data, $status )
    {
        $customer_notify = smsalert_get_option('customer_notify', 'smsalert_mm_renewal', 'on');

        $source          = 'membermouse';
       
        $mm_user = new MM_User($data['member_id']);
        
        $status_name = $data['status_name'];
        $member_id =$data['member_id'];
        $next_payment_date_dt = $mm_user->getExpirationDate();
    
        global $wpdb;
        $table_name           = $wpdb->prefix . 'smsalert_renewal_reminders';
        $subscription_details = $wpdb->get_results("SELECT next_payment_date, notification_sent_date FROM $table_name WHERE subscription_id = $member_id and source = '$source'");
        if ('Active' === $status_name && 'on' === $customer_notify && $next_payment_date_dt ) {
            $scheduler_data = get_option('smsalert_mm_renewal_scheduler');
            if (isset($scheduler_data['cron']) && ! empty($scheduler_data['cron']) ) {
                foreach ( $scheduler_data['cron'] as $sdata ) {
                    
                    $next_payment_date    = date('Y-m-d', strtotime($next_payment_date_dt));
                
                    $notify_days_before   = date('Y-m-d', strtotime('-' . $sdata['frequency'] . ' days', strtotime($next_payment_date)));
                        
                    if ($sdata['frequency'] > 0 && $sdata['message'] != '' ) {
                      
                        if ($subscription_details ) {
                      
                            $wpdb->update(
                                $table_name,
                                array(
                                'next_payment_date' => $next_payment_date,
                                'source'     => $source,
                                'subscription_text' => $sdata['message'],
                                'notification_sent_date' => $notify_days_before,
                                ),
                                array( 'subscription_id' => $member_id )
                            );
                        } else {
                           
                            $wpdb->insert(
                                $table_name,
                                array(
                                'subscription_id'   => $member_id,
                                'subscription_text' => $sdata['message'],
                                'next_payment_date' => $next_payment_date,
                                'source'     => $source,
                                'notification_sent_date' => $notify_days_before,
                                )
                            );                            
                            
                        }
                    }
                }
            }
        } else {
            
            $wpdb->delete($table_name, array( 'subscription_id' => $member_id ));
        }
    }

    /**
     * Send sms function
     *
     * @return void
     */
    public function smsalertSendSms()
    { 
        global $wpdb;
        $customer_notify      = smsalert_get_option('customer_notify', 'smsalert_mm_renewal', 'on');
        
        $source        = 'membermouse';
    
        $table_name           = $wpdb->prefix . 'smsalert_renewal_reminders';
        $today                = new DateTime();
        $today                = $today->format('Y-m-d');

        $subscription_details = $wpdb->get_results("SELECT * FROM $table_name WHERE notification_sent_date = '$today' and source = '$source'");

        if ('on' === $customer_notify && $subscription_details) {
            foreach ( $subscription_details as $subscription ) {
                $subscription_id           = $subscription->subscription_id;
    
                $mm_user = new MM_User($subscription_id);
                  
                $user_phone = $mm_user->getPhone();
            
                $customer_msg              = $subscription->subscription_text;
              
                $data = array(
                'first_name'                 =>$mm_user->getfirstName(),
                'last_name'                  =>$mm_user->getlastName(),
                'email'                      =>$mm_user->getemail(),
                'phone'                      =>$mm_user->getphone(),
                'member_id'                  =>$subscription_id,
                'registered'                 =>$mm_user->getregistrationDate(),
                'status_name'                =>$mm_user->getstatusName(),
                'membership_level_name'      =>$mm_user->getmembershipName(),
                'billing_address'            =>$mm_user->getbillingAddress(),
                'billing_city'               =>$mm_user->getbillingCity(),
                'billing_state'              =>$mm_user->getbillingState(),
                'billing_zip_code'           =>$mm_user->getbillingZipCode(),
                'billing_country'            =>$mm_user->getbillingCountry(),
                'shipping_address'           =>$mm_user->getshippingAddress(),
                'shipping_city'              =>$mm_user->getshippingCity(),
                'shipping_state'             =>$mm_user->getshippingState(),
                'shipping_zip_code'          =>$mm_user->getshippingZipCode(),
                'shipping_country'           =>$mm_user->getshippingCountry(),
                'subscription_id'            =>$subscription_id
                );
                 
                $customer_msg    = $this->parseSmsBody($data, $customer_msg);
              
                $cust_sms_data['number']   = $user_phone;
                
                $cust_sms_data['sms_body'] = $customer_msg;
                
                $customer_msg              = ( ! empty($cust_sms_data['sms_body']) ) ? $cust_sms_data['sms_body'] : ''; 
                do_action('sa_send_sms', $user_phone, $customer_msg);
            }
        }
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
        $temp_names = array('member_added','membership_level','mm_status','add_bundle','bundle_status','mm_payment','mm_payment_refund');
        foreach ($temp_names as $ks) {
            $defaults['smsalert_mm_general']['customer_mm_notify_' . $ks]   = 'off';
            $defaults['smsalert_mm_message']['customer_sms_mm_body_' . $ks] = '';
            $defaults['smsalert_mm_general']['admin_mm_notify_' . $ks]      = 'off';
            $defaults['smsalert_mm_message']['admin_sms_mm_body_' . $ks]    = '';
        }
        $defaults['smsalert_mm_renewal']['customer_notify']                  = 'off';
        $defaults['smsalert_mm_renewal_scheduler']['cron'][0]['frequency']  = '1';
        $defaults['smsalert_mm_renewal_scheduler']['cron'][0]['message']    = '';
        $defaults['smsalert_mm_renewal_scheduler']['cron'][1]['frequency']  = '2';
        $defaults['smsalert_mm_renewal_scheduler']['cron'][1]['message']    = '';
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
            'checkTemplateFor' => 'mm_customer',
            'templates'        => self::getCustomerTemplates(),
        );

        $admin_param = array(
            'checkTemplateFor' => 'mm_admin',
            'templates'        => self::getAdminTemplates(),
        );

        $reminderParam = array(
            'checkTemplateFor' => 'wc_Membermouse_reminder',
            'templates'        => self::getRenewalTemplates(),
        );

        $tabs['Membermouse']['nav']           = 'MemberMouse';
        $tabs['Membermouse']['icon']          = 'dashicons-groups';

        $tabs['Membermouse']['inner_nav']['Membermouse_cust']['title']          = 'Customer Notifications';
        $tabs['Membermouse']['inner_nav']['Membermouse_cust']['tab_section']    = 'membermousecusttemplates';
        $tabs['Membermouse']['inner_nav']['Membermouse_cust']['first_active']   = true;
        $tabs['Membermouse']['inner_nav']['Membermouse_cust']['tabContent']     = $customerParam;
        $tabs['Membermouse']['inner_nav']['Membermouse_cust']['filePath']       = 'views/message-template.php';

        $tabs['Membermouse']['inner_nav']['Membermouse_admin']['title']         = 'Admin Notifications';
        $tabs['Membermouse']['inner_nav']['Membermouse_admin']['tab_section']   = 'membermouseadmintemplates';
        $tabs['Membermouse']['inner_nav']['Membermouse_admin']['tabContent']    = $admin_param;
        $tabs['Membermouse']['inner_nav']['Membermouse_admin']['filePath']      = 'views/message-template.php';
        $tabs['Membermouse']['inner_nav']['Membermouse_reminder']['title']      = 'Membership Reminder';
        $tabs['Membermouse']['inner_nav']['Membermouse_reminder']['tab_section']= 'membermouseremindertemplates';
        $tabs['Membermouse']['inner_nav']['Membermouse_reminder']['tabContent'] = $reminderParam;
        $tabs['Membermouse']['inner_nav']['Membermouse_reminder']['filePath']   = 'views/renewal-template.php';
        $tabs['Membermouse']['help_links'] = [
            /* 'youtube_link' => [
                'href'   => 'https://youtu.be/4BXd_XZt9zM',
                'target' => '_blank',
                'alt'    => 'Watch steps on Youtube',
                'class'  => 'btn-outline',
                'label'  => 'Youtube',
                'icon'   => '<span class="dashicons dashicons-video-alt3" style="font-size: 21px;"></span> ',

            ], */
            'kb_link'      => [
                'href'   => 'https://kb.smsalert.co.in/knowledgebase/membermouse-sms-integration/',
                'target' => '_blank',
                'alt'    => 'Read how to integrate with membermouse',
                'class'  => 'btn-outline',
                'label'  => 'Documentation',
                'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
            ],
        ];
        return $tabs;
    }

     
    /**
     * Get wc renewal templates function.
     *
     * @return array
     * */
    public static function getRenewalTemplates()
    {
        $current_val      = smsalert_get_option('customer_notify', 'smsalert_mm_renewal', 'on');
        $checkbox_name_id = 'smsalert_mm_renewal[customer_notify]';

        $scheduler_data = get_option('smsalert_mm_renewal_scheduler');
        $templates      = array();
        $count          = 0;
        if (empty($scheduler_data) ) {
			$scheduler_data  = array();
            $scheduler_data['cron'][] = array(
            'frequency' => '1',
            'message'   => sprintf(__('Hello %1$s, your subscription %2$s with %3$s is due for renewal on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '#[member_id]', '[store_name]', '[next_payment_date]', PHP_EOL, PHP_EOL),
           
            );
            $scheduler_data['cron'][] = array(
            'frequency' => '2',
            'message'   => sprintf(__('Hello %1$s, your subscription %2$s with %3$s is due for renewal on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '#[member_id]', '[store_name]', '[next_payment_date]', PHP_EOL, PHP_EOL),
         
            );
        }
        foreach ( $scheduler_data['cron'] as $key => $data ) {

            $text_area_name_id = 'smsalert_mm_renewal_scheduler[cron][' . $count . '][message]';
            $select_name_id    = 'smsalert_mm_renewal_scheduler[cron][' . $count . '][frequency]';
            $text_body         = $data['message'];
            
            $templates[$key]['notify_id']        = 'membermouse';
            $templates[ $key ]['frequency']      = $data['frequency'];
            $templates[ $key ]['enabled']        = $current_val;
            $templates[ $key ]['title']          = 'Send renewal reminder message to customer';
            $templates[ $key ]['checkboxNameId'] = $checkbox_name_id;
            $templates[ $key ]['text-body']      = $text_body;
            $templates[ $key ]['textareaNameId'] = $text_area_name_id;
            $templates[ $key ]['selectNameId']   = $select_name_id;
            $templates[ $key ]['token']          = self::getMembermousevariables('member,remindersms');

            $count++;
        }
        return $templates;
    }
      
    /**
     * Get customer templates.
     *
     * @return array
     */
    public static function getCustomerTemplates()
    {
        $templates           = [];
        
         /*  When member add */
        $currentVal         = smsalert_get_option('customer_mm_notify_member_added', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[customer_mm_notify_member_added]';
            $textareaNameId  = 'smsalert_mm_message[customer_sms_mm_body_member_added]';
            $defaultTemplate = smsalert_get_option('customer_sms_mm_body_membership_status', 'smsalert_mm_message', sprintf(__('Hello %1$s, status of your membership #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '[member_id]', '[store_name]', '[status_name]', PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('customer_sms_mm_body_member_added', 'smsalert_mm_message', $defaultTemplate);

            $templates[0]['title']          = 'When new member is added';
            $templates[0]['enabled']        = $currentVal;
            $templates[0]['status']         = 'member_added';
            $templates[0]['text-body']      = $textBody;
            $templates[0]['checkboxNameId'] = $checkboxNameId;
            $templates[0]['textareaNameId'] = $textareaNameId;
            $templates[0]['token']          = self::getMembermousevariables('member');
         
         
        /*  When member status change*/
         
        $currentVal      = smsalert_get_option('customer_mm_notify_mm_status', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[customer_mm_notify_mm_status]';
            $textareaNameId  = 'smsalert_mm_message[customer_sms_mm_body_mm_status]';
            $defaultTemplate = smsalert_get_option('customer_sms_mm_body_mm_status', 'smsalert_mm_message', sprintf(__('Hello %1$s, status of your membership #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '[member_id]', '[store_name]', '[status_name]', PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('customer_sms_mm_body_mm_status', 'smsalert_mm_message', $defaultTemplate);

            $templates[1]['title']          = 'When membership status is changed';
            $templates[1]['enabled']        = $currentVal;
            $templates[1]['status']         = 'mm_status';
            $templates[1]['text-body']      = $textBody;
            $templates[1]['checkboxNameId'] = $checkboxNameId;
            $templates[1]['textareaNameId'] = $textareaNameId;
            $templates[1]['token']          = self::getMembermousevariables('member');
            
            
        /*  When membership level update*/

        $currentVal      = smsalert_get_option('customer_mm_notify_membership_level', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[customer_mm_notify_membership_level]';
            $textareaNameId  = 'smsalert_mm_message[customer_sms_mm_body_membership_level]';
            $defaultTemplate = smsalert_get_option('customer_sms_mm_body_membership_level', 'smsalert_mm_message', sprintf(__('Hello %1$s, status of your membership #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '[member_id]', '[store_name]', '[membership_level_name]', PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('customer_sms_mm_body_membership_level', 'smsalert_mm_message', $defaultTemplate);

            $templates[2]['title']          = 'When membership level is changed';
            $templates[2]['enabled']        = $currentVal;
            $templates[2]['status']         = 'membership_level';
            $templates[2]['text-body']      = $textBody;
            $templates[2]['checkboxNameId'] = $checkboxNameId;
            $templates[2]['textareaNameId'] = $textareaNameId;
            $templates[2]['token']          = self::getMembermousevariables('member');
            
            
        /*  When new bondle add*/
        $currentVal      = smsalert_get_option('customer_mm_notify_bundle_status', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[customer_mm_notify_add_bundle]';
            $textareaNameId  = 'smsalert_mm_message[customer_sms_mm_body_add_bundle]';
            $defaultTemplate = smsalert_get_option('customer_sms_mm_body_bundle_status', 'smsalert_mm_message', sprintf(__('Hello %1$s, status of your bundle #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '[bundle_id]', '[store_name]', '[bundle_name]', PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('customer_sms_mm_body_add_bundle', 'smsalert_mm_message', $defaultTemplate);

            $templates[3]['title']          = 'When new bundle is added';
            $templates[3]['enabled']        = $currentVal;
            $templates[3]['status']         = 'add_bundle';
            $templates[3]['text-body']      = $textBody;
            $templates[3]['checkboxNameId'] = $checkboxNameId;
            $templates[3]['textareaNameId'] = $textareaNameId;
            $templates[3]['token']          = self::getMembermousevariables('member,bundle');
            
            
        /*  When membership level update*/
        $currentVal      = smsalert_get_option('customer_mm_notify_bundle_status', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[customer_mm_notify_bundle_status]';
            $textareaNameId  = 'smsalert_mm_message[customer_sms_mm_body_bundle_status]';
            $defaultTemplate = smsalert_get_option('customer_sms_mm_body_bundle_status', 'smsalert_mm_message', sprintf(__('Hello %1$s, status of your bundle #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '[bundle_id]', '[store_name]', '[bundle_status_name]', PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('customer_sms_mm_body_bundle_status', 'smsalert_mm_message', $defaultTemplate);

            $templates[4]['title']          = 'When bundle status is changed';
            $templates[4]['enabled']        = $currentVal;
            $templates[4]['status']         = 'bundle_status';
            $templates[4]['text-body']      = $textBody;
            $templates[4]['checkboxNameId'] = $checkboxNameId;
            $templates[4]['textareaNameId'] = $textareaNameId;
            $templates[4]['token']          = self::getMembermousevariables('member,bundle');
            
            
        /*  When member payment*/
            
        $currentVal      = smsalert_get_option('customer_mm_notify_mm_payment', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[customer_mm_notify_mm_payment]';
            $textareaNameId  = 'smsalert_mm_message[customer_sms_mm_body_mm_payment]';
            $defaultTemplate = smsalert_get_option('customer_sms_mm_body_mm_payment', 'smsalert_mm_message', sprintf(__('Hello %1$s, payment status for your order #%2$s with %3$s has been changed to completed.%4$sPowered by%5$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '[order_transaction_id]', '[store_name]', PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('customer_sms_mm_body_mm_payment', 'smsalert_mm_message', $defaultTemplate);

            $templates[5]['title']          = 'When payment is changed';
            $templates[5]['enabled']        = $currentVal;
            $templates[5]['status']         = 'mm_payment';
            $templates[5]['text-body']      = $textBody;
            $templates[5]['checkboxNameId'] = $checkboxNameId;
            $templates[5]['textareaNameId'] = $textareaNameId;
            $templates[5]['token']          = self::getMembermousevariables('member,order');
            
            
        /*  When member payment refund*/
            
        $currentVal      = smsalert_get_option('customer_mm_notify_mm_payment_refund', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[customer_mm_notify_mm_payment_refund]';
            $textareaNameId  = 'smsalert_mm_message[customer_sms_mm_body_mm_payment_refund]';
            $defaultTemplate = smsalert_get_option('customer_sms_mm_body_mm_payment_refund', 'smsalert_mm_message', sprintf(__('Hello %1$s, payment status for your order #%2$s with %3$s has been changed to refunded.%4$sPowered by%5$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '[order_transaction_id]', '[store_name]', PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('customer_sms_mm_body_mm_payment_refund', 'smsalert_mm_message', $defaultTemplate);

            $templates[6]['title']          = 'When payment is refund';
            $templates[6]['enabled']        = $currentVal;
            $templates[6]['status']         = 'mm_payment_refund';
            $templates[6]['text-body']      = $textBody;
            $templates[6]['checkboxNameId'] = $checkboxNameId;
            $templates[6]['textareaNameId'] = $textareaNameId;
            $templates[6]['token']          = self::getMembermousevariables('member,order');
    
        return $templates;
    }

    /**
     * Get admin templates.
     *
     * @return array
     */
    public static function getAdminTemplates()
    {
        $templates           = [];
        $currentVal      = smsalert_get_option('admin_mm_notify_member_added', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[admin_mm_notify_member_added]';
            $textareaNameId  = 'smsalert_mm_message[admin_sms_mm_body_member_added]';
            $defaultTemplate = smsalert_get_option('admin_sms_mm_body_member_added', 'smsalert_mm_message', sprintf(__('Hello admin, status of your membership with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', '[status_name]', PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('admin_sms_mm_body_member_added', 'smsalert_mm_message', $defaultTemplate);

            $templates[0]['title']          = 'When new member is added';
            $templates[0]['enabled']        = $currentVal;
            $templates[0]['status']         = 'member_added';
            $templates[0]['text-body']      = $textBody;
            $templates[0]['checkboxNameId'] = $checkboxNameId;
            $templates[0]['textareaNameId'] = $textareaNameId;
            $templates[0]['token']          = self::getMembermousevariables('member');
        
        
        
        
            $currentVal      = smsalert_get_option('admin_mm_notify_mm_status', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[admin_mm_notify_mm_status]';
            $textareaNameId  = 'smsalert_mm_message[admin_sms_mm_body_mm_status]';
            $defaultTemplate = smsalert_get_option('admin_sms_mm_body_mm_status', 'smsalert_mm_message', sprintf(__('%1$s status of membership has been changed to %2$s.', 'sms-alert'), '[store_name]:', '[status_name]'));

            $textBody = smsalert_get_option('admin_sms_mm_body_mm_status', 'smsalert_mm_message', $defaultTemplate);

            $templates[1]['title']          = 'When membership status is changed';
            $templates[1]['enabled']        = $currentVal;
            $templates[1]['status']         = 'mm_status';
            $templates[1]['text-body']      = $textBody;
            $templates[1]['checkboxNameId'] = $checkboxNameId;
            $templates[1]['textareaNameId'] = $textareaNameId;
            $templates[1]['token']          = self::getMembermousevariables('member');
      
        $currentVal      = smsalert_get_option('admin_mm_notify_membership_level', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[admin_mm_notify_membership_level]';
            $textareaNameId  = 'smsalert_mm_message[admin_sms_mm_body_membership_level]';
            $defaultTemplate = smsalert_get_option('admin_sms_mm_body_membership_level', 'smsalert_mm_message', sprintf(__('%1$s status of membership has been changed to %2$s.', 'sms-alert'), '[store_name]:', '[membership_level_name]'));

            $textBody = smsalert_get_option('admin_sms_mm_body_membership_level', 'smsalert_mm_message', $defaultTemplate);

            $templates[2]['title']          = 'When membership level is changed';
            $templates[2]['enabled']        = $currentVal;
            $templates[2]['status']         = 'membership_level';
            $templates[2]['text-body']      = $textBody;
            $templates[2]['checkboxNameId'] = $checkboxNameId;
            $templates[2]['textareaNameId'] = $textareaNameId;
            $templates[2]['token']          = self::getMembermousevariables('member');
            
            
            
        $currentVal      = smsalert_get_option('admin_mm_notify_add_bundle', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[admin_mm_notify_add_bundle]';
            $textareaNameId  = 'smsalert_mm_message[admin_sms_mm_body_add_bundle]';
            $defaultTemplate = smsalert_get_option('admin_sms_mm_body_bundle_status', 'smsalert_mm_message', sprintf(__('%1$s status of bundle has been changed to %2$s.', 'sms-alert'), '[store_name]:', '[bundle_status_name]'));

            $textBody = smsalert_get_option('admin_sms_mm_body_add_bundle', 'smsalert_mm_message', $defaultTemplate);

            $templates[3]['title']          = 'When new bundle is added ';
            $templates[3]['enabled']        = $currentVal;
            $templates[3]['status']         = 'add_bundle';
            $templates[3]['text-body']      = $textBody;
            $templates[3]['checkboxNameId'] = $checkboxNameId;
            $templates[3]['textareaNameId'] = $textareaNameId;
            $templates[3]['token']          = self::getMembermousevariables('member,bundle');
            
            
            
        $currentVal      = smsalert_get_option('admin_mm_notify_bundle_status', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[admin_mm_notify_bundle_status]';
            $textareaNameId  = 'smsalert_mm_message[admin_sms_mm_body_bundle_status]';
            $defaultTemplate = smsalert_get_option('admin_sms_mm_body_bundle_status', 'smsalert_mm_message', sprintf(__('%1$s status of bundle has been changed to %2$s.', 'sms-alert'), '[store_name]:', '[bundle_status_name]'));

            $textBody = smsalert_get_option('admin_sms_mm_body_bundle_status', 'smsalert_mm_message', $defaultTemplate);

            $templates[4]['title']          = 'When bundle status is changed';
            $templates[4]['enabled']        = $currentVal;
            $templates[4]['status']         = 'bundle_status';
            $templates[4]['text-body']      = $textBody;
            $templates[4]['checkboxNameId'] = $checkboxNameId;
            $templates[4]['textareaNameId'] = $textareaNameId;
            $templates[4]['token']          = self::getMembermousevariables('member,bundle');
            
            
        $currentVal      = smsalert_get_option('admin_mm_notify_mm_payment', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[admin_mm_notify_mm_payment]';
            $textareaNameId  = 'smsalert_mm_message[admin_sms_mm_body_mm_payment]';
            $defaultTemplate = smsalert_get_option('admin_sms_mm_body_mm_payment', 'smsalert_mm_message', sprintf(__('%1$s status of membership has been changed to %2$s.', 'sms-alert'), '[store_name]:', '[status_name]'));

            $textBody = smsalert_get_option('admin_sms_mm_body_mm_payment', 'smsalert_mm_message', $defaultTemplate);

            $templates[5]['title']          = 'When payment is changed';
            $templates[5]['enabled']        = $currentVal;
            $templates[5]['status']         = 'mm_payment';
            $templates[5]['text-body']      = $textBody;
            $templates[5]['checkboxNameId'] = $checkboxNameId;
            $templates[5]['textareaNameId'] = $textareaNameId;
            $templates[5]['token']          = self::getMembermousevariables('member,order');
            
            
        $currentVal      = smsalert_get_option('admin_mm_notify_mm_payment_refund', 'smsalert_mm_general', 'on');
            $checkboxNameId  = 'smsalert_mm_general[admin_mm_notify_mm_payment_refund]';
            $textareaNameId  = 'smsalert_mm_message[admin_sms_mm_body_mm_payment_refund]';
            $defaultTemplate = smsalert_get_option('admin_sms_mm_body_mm_payment_refund', 'smsalert_mm_message', sprintf(__('%1$s status of membership has been changed to %2$s.', 'sms-alert'), '[store_name]:', '[status_name]'));

            $textBody = smsalert_get_option('admin_sms_mm_body_mm_payment_refund', 'smsalert_mm_message', $defaultTemplate);

            $templates[6]['title']          = 'When payment is refund';
            $templates[6]['enabled']        = $currentVal;
            $templates[6]['status']         = 'mm_payment_refund';
            $templates[6]['text-body']      = $textBody;
            $templates[6]['checkboxNameId'] = $checkboxNameId;
            $templates[6]['textareaNameId'] = $textareaNameId;
            $templates[6]['token']          = self::getMembermousevariables('member,order');
            
        return $templates;
    }

     /**
      * Send sms approved pending.
      *
      * @param int $data data
      *
      * @return void
      */
    public function sendSmsOnStatusMemberAdd($data)
    {
        $status = $data['status_name'];
        $this->sendSmsOn($data, $status, 'member_added');
        $this->setRenewalReminder($data, $status);         
    }

    /**
     * Send sms member membership change.
     *
     * @param int $data data
     *
     * @return void
     */
    public function sendSmsOnStatusMemberMembershipChange($data)
    {
        $status = $data['status_name'];
        $this->sendSmsOn($data, $status, 'membership_level');
        $this->setRenewalReminder($data, $status); 
    }

    /**
     * Send sms member membership change.
     *
     * @param int $data data
     *
     * @return void
     */
    public function sendSmsOnStatusMemberStatusChange($data)
    {
        $status = $data['status_name'];
        $this->sendSmsOn($data, $status, 'mm_status');
        $this->setRenewalReminder($data, $status); 
    } 
    
    /**
     * Send sms member membership change.
     *
     * @param int $data data
     *
     * @return void
     */
    public function sendSmsOnStatusBundlesAddedToMember($data)
    { 
        $status = $data['bundle_name'];
        $this->sendSmsOn($data, $status, 'add_bundle');
    } 
    
    /**
     * Send sms member membership change.
     *
     * @param int $data data
     *
     * @return void
     */
    public function sendSmsOnStatusBundlesStatusChange($data) 
    {
        $status = $data['bundle_status_name'];
        $this->sendSmsOn($data, $status, 'bundle_status');
    }
    
    /**
     * Send sms member membership change.
     *
     * @param int $data data
     *
     * @return void
     */
    public function sendSmsOnStatusPaymentReceived($data)
    { 
        $status = $data['order_transaction_id'];
        $this->sendSmsOn($data, $status, 'mm_payment');
    }
    
    /**
     * Send sms member membership change.
     *
     * @param int $data data
     *
     * @return void
     */
    public function sendSmsOnStatusRefundIssued($data)
    {
        $status = $data['order_transaction_id'];
        $this->sendSmsOn($data, $status,  'mm_payment_refund');
    }
    
    /**
     * Send sms approved pending.
     *
     * @param int $data   data
     * @param int $status status
     * @param int $entity entity
     *
     * @return void
     */
    public function sendSmsOn($data, $status,$entity)
    {
        
        $buyerNumber       = $data['phone'];
        
        $customerMessage   = smsalert_get_option('customer_sms_mm_body_'.$entity, 'smsalert_mm_message', '');
        
        $customerNotify    = smsalert_get_option('customer_mm_notify_'.$entity, 'smsalert_mm_general', 'on');
        
        if (($customerNotify === 'on' && $customerMessage !== '')) {
            
            $buyerMessage = $this->parseSmsBody($data, $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }
            // Send msg to admin.
            $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
        if (empty($adminPhoneNumber) === false) {
            $adminNotify        = smsalert_get_option('admin_mm_notify_'.$entity, 'smsalert_mm_general', 'on');
            $adminMessage       = smsalert_get_option('admin_sms_mm_body_'.$entity, 'smsalert_mm_message', '');
            $nos = explode(',', $adminPhoneNumber);
            $adminPhoneNumber   = array_diff($nos, array('postauthor', 'post_author'));
            $adminPhoneNumber   = implode(',', $adminPhoneNumber);
            if ($adminNotify === 'on' && $adminMessage !== '') {
                $adminMessage   = $this->parseSmsBody($data, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }  
    }
    
    /**
     * Parse sms body.
     *
     * @param array  $data    data.
     * @param string $content content.
     *
     * @return string
     */
    public function parseSmsBody($data, $content = null)
    {
        
        $mm_user = new MM_User($data['member_id']);
        $next_payment_date_dt = $mm_user->getExpirationDate();
        $data['next_payment_date'] = $next_payment_date_dt;
     
        $content = str_replace(
            array_map(
                function ($k) {
                    return '['.$k.']';
                }, array_keys($data)
            ), array_values($data), $content
        );
        return $content;
    }

    /**
     * Get Member mouse variables.
     *
     * @param int $entity entity
     *
     * @return array
     */
    public static function getMembermousevariables($entity)
    {
        $entities = explode(',', $entity);
        
        $variables = array(
            'member' => array(
               'first_name',
                'last_name',
                'email',
                'phone',
                'member_id',
                'registered',
                'status_name',
                'membership_level_name',
                'billing_address',
                'billing_city',
                'billing_state',
                'billing_zip_code',
                'billing_country',
                'shipping_address',
                'shipping_city',
                'shipping_state',
                'shipping_zip_code',
                'shipping_country',
            ),
            'bundle' => array(
                'bundle_id',
                'bundle_name',
                'bundle_status_name',
                'bundle_date_added',
                'bundle_last_updated',
            ),
            'order' => array(
                'order_number',
                'order_transaction_id',
                'order_total',
                'order_subtotal',
                'order_discount',
                'order_shipping',
                'order_shipping_method',
                'order_billing_address',
                'order_billing_city',
                'order_billing_state',
                'order_billing_zip_code',
                'order_billing_country',
                'order_shipping_address',
                'order_shipping_city',
                'order_shipping_state',
                'order_shipping_zip_code',
                'order_shipping_country',
            ),
            'product' => array(
                'product_id',
                'product_name',
                'product_amount',
                'product_quantity',
                'product_total',
                'product_recurring_amount',
                'product_rebill_period',
                'product_rebill_frequency',
            ),
            'remindersms' => array(
        'next_payment_date',
                
                
                
            ),
        );
        
        $obj=array();
        foreach ($entities as $ent) {
            $arr1 = array_map(
                function ($k) {
                    return '['.$k.']';
                }, array_values($variables[$ent])
            );
            $arr2 = array_map(
                function ($k) {
                    return ucwords(str_replace("_", " ", $k));
                }, array_values($variables[$ent])
            );
            
            $obj+=array_combine($arr1, $arr2);
            
        }
        return $obj;   
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {

        if (is_plugin_active('membermouse/index.php') === true) {
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
        if ((is_plugin_active('membermouse/index.php') === true) && ($islogged === true)) {
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
        if ($_SESSION[$this->form_session_var] === true) {
            return true;
        } else {
            return $isAjax;
        }
    }
}
new SAMemberMouses();
