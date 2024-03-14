<?php

/**
 * Paid-memberships-pro helper
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

if (is_plugin_active('paid-memberships-pro/paid-memberships-pro.php') === false) {
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
 * SA_Paidmembershipspro class 
 */
class SA_Paidmembershipspro extends FormInterface
{
    /**
     * Form Session Variable
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::PAID_MEMBERSHIP_PRO;
    
    /**
     * Construct function
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('pmpro_before_change_membership_level', array( $this, 'sendSmsMembershipCancel' ), 10, 4);
        add_action('pmpro_after_change_membership_level', array( $this, 'sendSmsMembershipActive' ), 10, 3);
        add_filter('sa_get_user_phone_no', array( $this, 'saUpdateBillingPhone' ), 10, 2);
        add_action('pmpro_after_checkout', array( $this, 'triggerSmsOrderStatusSuccess' ), 10, 2);
        add_action('pmpro_order_status_cancelled', array( $this, 'triggerSmsStatusCancelled' ), 10, 2);
        add_action('pmpro_order_status_error', array( $this, 'triggerSmsStatusError' ), 10, 2);
        add_action('pmpro_order_status_pending', array( $this, 'triggarSmsStatusPending' ), 10, 2);
        add_action('pmpro_order_status_refunded', array( $this, 'triggerSmsStatusRefunded' ), 10, 2);
        add_action('pmpro_order_status_review', array( $this, 'triggerSmsStatusReview' ), 10, 2);
        add_action('pmpro_order_status_token', array( $this, 'triggerSmsStatusToken' ), 10, 2);
        add_action('pmpro_checkout_after_user_fields', array( $this, 'addPhoneField' ), 10);
        add_action('pmpro_checkout_before_submit_button', array( $this, 'pmproFormCheckoutOtp' ), 10);
        add_filter('login_form_top', array( $this,'pmproFormLoginOtp' ), 10);
        add_action('smsalert_followup_sms',  array($this, 'sendReminderSms'));
    }
    
    /**
     * Add default settings to savesetting in setting-options
     *
     * @param array $defaults defaults
     *
     * @return array
     */
    public static function add_default_setting($defaults = array())
    {
        $memberStatuses = pmpro_getOrderStatuses();
        $memberStatuses['active'] = 'active';
        $memberStatuses['cancel'] = 'cancel';
        foreach ($memberStatuses as $ks => $vs) {
            $defaults['smsalert_pmp_general']['customer_pmp_notify_' . $vs]   = 'off';
            $defaults['smsalert_pmp_message']['customer_sms_pmp_body_' . $vs] = '';
            $defaults['smsalert_pmp_general']['admin_pmp_notify_' . $vs]      = 'off';
            $defaults['smsalert_pmp_message']['admin_sms_pmp_body_' . $vs]    = '';
        }
        $defaults['smsalert_pmp_general']['otp_enable']                       = 'off';
        $defaults['smsalert_pmp_general']['customer_notify']                  = 'off';
        $defaults['smsalert_pmp_renewal']['customer_notify']                  = 'off';
        $defaults['smsalert_pmp_renewal_scheduler']['cron'][0]['frequency']   = '1';
        $defaults['smsalert_pmp_renewal_scheduler']['cron'][0]['message']     = '';
        $defaults['smsalert_pmp_renewal_scheduler']['cron'][1]['frequency']   = '2';
        $defaults['smsalert_pmp_renewal_scheduler']['cron'][1]['message']     = '';
        return $defaults;
    }

    /**
     * Add phone field.
     *
     * @return void
     */
    public function addPhoneField()
    {  
        global $pmpro_requirebilling;
        if (!$pmpro_requirebilling) {
              echo '<label for="billing_phone">'. esc_html__('Phone', 'sms-alert').'</label>
				<input id="bphone" name="billing_phone" type="text" class="billing_phone pmpro_required" size="30" value="" autocomplete="off"/>';
        }    
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
        if (isset($_POST['bphone'])) {
            $phone = $_POST['bphone'];
            return ( ! empty($billing_phone) ) ? $billing_phone : $phone;
        }
        return $billing_phone;
    }
    
    /**
     * Add Shortcode for OTP and Add additional js code to your script
     * 
     * @return void
     * */
    public function pmproFormCheckoutOtp()
    {
        if (smsalert_get_option('otp_enable', 'smsalert_pmp_general') === 'on') {
            echo do_shortcode('[sa_verify phone_selector=" #bphone" submit_selector= " #pmpro_btn-submit"]');
        }
           
    }

    /**
     * Add Shortcode for OTP and Add additional js code to your script
     * 
     * @return void
     * */
    public function pmproFormLoginOtp()
    {
        $default_login_otp   = smsalert_get_option('buyer_login_otp', 'smsalert_general');
        $enabled_login_popup = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');
        if ('on' === $default_login_otp && 'on' === $enabled_login_popup ) {
            echo do_shortcode('[sa_verify user_selector="#user_login" pwd_selector="#user_pass" submit_selector="#wp-submit"]');
        }  
    }

    /**
     * Set membership reminder.
     *
     * @param array $order order.
     *
     * @return void
     */
    public static function setMembershipReminder($order)
    { 
        $user_id           = $order->user_id;
         
        $customerNotify    = smsalert_get_option('customer_notify', 'smsalert_pmp_renewal', 'on'); 
        $membership        = pmpro_getMembershipLevelForUser($user_id);
        
        $source            = 'paid-memberships-pro';
        $status     = !empty($order->status) ? $order->status : "";
        $order_id          = !empty($order->id) ? $order->id : "";
        ;
        global $wpdb; 
        $expired           = !empty($membership->enddate) ? $membership->enddate : "";
        $expiry            = wp_date("Y-m-d H:i:s", $expired); 
        $buyerMob          = $order->billing->phone;
        $table_name        = $wpdb->prefix .'smsalert_renewal_reminders';
        
        $subscription_details = $wpdb->get_results("SELECT next_payment_date, notification_sent_date FROM $table_name WHERE subscription_id = $order_id and source = '$source'");
        if ('success' === $status && 'on' === $customerNotify && $expiry) {
            $scheduler_data = get_option('smsalert_pmp_renewal_scheduler');
            if (isset($scheduler_data['cron']) && ! empty($scheduler_data['cron']) ) {
                foreach ( $scheduler_data['cron'] as $sdata ) {
                    $next_payment_date    = date('Y-m-d', strtotime($expiry));
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
                                array( 'subscription_id' => $order_id )
                            );
                        } else {  
                            $wpdb->insert(
                                $table_name,
                                array(
                                'subscription_id'        => $order_id,
                                'subscription_text'      => $sdata['message'],
                                'next_payment_date'      => $next_payment_date,
                                'source'                 => $source,
                                'notification_sent_date' => $notify_days_before,
                                
                                )
                            );
                        }
                    }
                }
            }
        } else {
            $wpdb->delete($table_name, array( 'subscription_id' => $order_id ));
        }
    }
    
    /**
     * Send sms function.
     *
     * @return void
     */
    function sendReminderSms()
    {    
        global $wpdb, $order;
        $customerNotify       = smsalert_get_option('customer_notify', 'smsalert_pmp_renewal', 'on');
        $source               = 'paid-memberships-pro';
        $table_name           = $wpdb->prefix . 'smsalert_renewal_reminders'; 
        $schedulerData        = get_option('smsalert_pmp_reminder_scheduler');
        $today                = new DateTime();
        $today                = $today->format('Y-m-d');
        $subscription_details = $wpdb->get_results("SELECT * FROM $table_name WHERE notification_sent_date = '$today' and source = '$source'");
        if ('on' === $customerNotify && $subscription_details) { 
            foreach ( $subscription_details as $subscription ) {
                $subscription_id                  = $subscription->subscription_id;
                $order = new MemberOrder($subscription_id);
                $customer_sms              = $subscription->subscription_text;
                $order_id                  = !empty($order->id) ? $order->id : "";
                $buyerMob                  = $order->billing->phone;
                $customer_msg               = $this->parseSmsBody($order, $customer_sms);
                $cust_sms_data['number']   = $buyerMob;
                $cust_sms_data['sms_body'] = $customer_msg;
                $customer_msg              = ( ! empty($cust_sms_data['sms_body']) ) ? $cust_sms_data['sms_body'] : '';
                do_action('sa_send_sms', $buyerMob, $customer_msg);
            }
        }
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
            'checkTemplateFor' => 'pmp_customer',
            'templates'        => self::getCustomerTemplates(),
        );
        $admin_param = array(
            'checkTemplateFor' => 'pmp_admin',
            'templates'        => self::getAdminTemplates(),
        );
        $reminderParam = array(
            'checkTemplateFor' => 'wc_paid_memberships_pro_reminder',
            'templates'        => self::getReminderTemplates(),
        );
        $tabs['paid_memberships_pro']['nav']           = 'Paid Memberships Pro';
        $tabs['paid_memberships_pro']['icon']          = 'dashicons-groups';

        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_cust']['title']          = 'Customer Notifications';
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_cust']['tab_section']    = 'paidmembershipsprocusttemplates';
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_cust']['first_active']   = true;
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_cust']['tabContent']     = $customerParam;
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_cust']['filePath']       = 'views/message-template.php';
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_admin']['title']         = 'Admin Notifications';
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_admin']['tab_section']   = 'paidmembershipsprotemplates';
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_admin']['tabContent']    = $admin_param;
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_admin']['filePath']      = 'views/message-template.php';
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_reminder']['title']      = 'Membership Reminder';
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_reminder']['tab_section']= 'paidmembershipsproremindertemplates';
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_reminder']['tabContent'] = $reminderParam;
        $tabs['paid_memberships_pro']['inner_nav']['paid_memberships_pro_reminder']['filePath']   = 'views/renewal-template.php';
        
        return $tabs;
    }
      
    /**
     * Get wc renewal templates function.
     *
     * @return array
     */
    public static function getReminderTemplates()
    {
        $currentVal     = smsalert_get_option('customer_notify', 'smsalert_pmp_renewal', 'on');
    
        $checkboxNameId = 'smsalert_pmp_renewal[customer_notify]';
        $schedulerData  = get_option('smsalert_pmp_renewal_scheduler');
        $templates      = array();
        $count          = 0;
        if (empty($schedulerData)) {
			$schedulerData = array();
            $schedulerData['cron'][] = array(
                'frequency' => '1',
                'message'   => sprintf(__('Hello %1$s, your membership %2$s with %3$s is expired on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[userName]', '#[order_id]', '[store_name]', '[membership_expiration_date]', PHP_EOL, PHP_EOL),
            );
            $schedulerData['cron'][] = array(
                'frequency' => '2',
                'message'   => sprintf(__('Hello %1$s, your membership %2$s with %3$s is expired on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[userName]', '#[order_id]', '[store_name]', '[membership_expiration_date]', PHP_EOL, PHP_EOL),
            );
        }
        foreach ($schedulerData['cron'] as $key => $data) {
            if (empty($data['message'])) {
                continue;
            }
            $textAreaNameId  = 'smsalert_pmp_renewal_scheduler[cron][' . $count . '][message]';
            $selectNameId    = 'smsalert_pmp_renewal_scheduler[cron][' . $count . '][frequency]';
            $textBody        = $data['message'];
            $templates[$key]['notify_id']      = 'paid-memberships-pro';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']          = 'Send renewal reminder message to customer';
            $templates[$key]['checkboxNameId'] = $checkboxNameId;
            $templates[$key]['text-body']      = $textBody;
            $templates[$key]['textareaNameId'] = $textAreaNameId;
            $templates[$key]['selectNameId']   = $selectNameId;
            $templates[$key]['token']          = self:: getPaidMembershipsProvariables();
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
        $orderStatuses  = pmpro_getOrderStatuses();
        $memberStatuses = array('active', 'cancel');
        $templates      =[];
        $ws = 0;
        foreach ($memberStatuses as $wws => $ms) {
            $currentVal      = smsalert_get_option('customer_pmp_notify_' . strtolower($ms), 'smsalert_pmp_general', 'on');
            $checkboxNameId  = 'smsalert_pmp_general[customer_pmp_notify_' . strtolower($ms) . ']';
            $textareaNameId  = 'smsalert_pmp_message[customer_sms_pmp_body_' . strtolower($ms) . ']';
            $defaultTemplate = smsalert_get_option('customer_sms_pmp_body_' . strtolower($ms), 'smsalert_pmp_message', sprintf(__('Hello %1$s, status of your membership #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[userName]', '[membership_id]', '[store_name]', $ms, PHP_EOL, PHP_EOL));
            $textBody       = smsalert_get_option('customer_sms_pmp_body_' . strtolower($ms), 'smsalert_pmp_message', $defaultTemplate);
            $templates[$ws]['title']          = 'When membership status is ' . ucwords($ms);
            $templates[$ws]['enabled']        = $currentVal;
            $templates[$ws]['status']         = $ms;
            $templates[$ws]['text-body']      = $textBody;
            $templates[$ws]['checkboxNameId'] = $checkboxNameId;
            $templates[$ws]['textareaNameId'] = $textareaNameId;
            $templates[$ws]['token']          = self::getPaidMembershipsProvariables();
            $ws++;
        }
        foreach ($orderStatuses as $ks => $vs) {
            if (!empty($vs)) {
                $currentVal      = smsalert_get_option('customer_pmp_notify_' . strtolower($vs), 'smsalert_pmp_general', 'on');
                $checkboxNameId  = 'smsalert_pmp_general[customer_pmp_notify_' . strtolower($vs) . ']';
                $textareaNameId  = 'smsalert_pmp_message[customer_sms_pmp_body_' . strtolower($vs) . ']';
                $defaultTemplate = smsalert_get_option('customer_sms_pmp_body_' . strtolower($vs), 'smsalert_pmp_message', sprintf(__('Hello %1$s, status of your order #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[first_name]', '[order_id]', '[store_name]', $vs, PHP_EOL, PHP_EOL));
                $textBody       = smsalert_get_option('customer_sms_pmp_body_' . strtolower($vs), 'smsalert_pmp_message', $defaultTemplate);
                $templates[$ws]['title']          = 'When order status is ' . ucwords($vs);
                $templates[$ws]['enabled']        = $currentVal;
                $templates[$ws]['status']         = $vs;
                $templates[$ws]['text-body']      = $textBody;
                $templates[$ws]['checkboxNameId'] = $checkboxNameId;
                $templates[$ws]['textareaNameId'] = $textareaNameId;
                $templates[$ws]['token']          = self::getPaidMembershipsProvariables();
                $ws++;
            }
        } 
        
        return $templates;
    }

    /**
     * Get admin templates.
     *
     * @return array
     */
    public static function getAdminTemplates()
    {
        $orderStatuses  = pmpro_getOrderStatuses();
        $memberStatuses = array('active', 'cancel');
        $templates      =[];
        $ws = 0;
        foreach ($memberStatuses as $wws => $ms) {
                $currentVal      = smsalert_get_option('admin_pmp_notify_' . strtolower($ms), 'smsalert_pmp_general', 'on');
                $checkboxNameId  = 'smsalert_pmp_general[admin_pmp_notify_' . strtolower($ms) . ']';
                $textareaNameId  = 'smsalert_pmp_message[admin_sms_pmp_body_' . strtolower($ms) . ']';
                $defaultTemplate = smsalert_get_option('admin_sms_pmp_body_' . strtolower($ms), 'smsalert_pmp_message', sprintf(__('Hello admin, status of your membership with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', $ms, PHP_EOL, PHP_EOL));
                $textBody = smsalert_get_option('admin_sms_pmp_body_' . strtolower($ms), 'smsalert_pmp_message', $defaultTemplate);
                $templates[$ws]['title']          = 'When admin change membership status to ' . $ms;
                $templates[$ws]['enabled']        = $currentVal;
                $templates[$ws]['status']         = $ms;
                $templates[$ws]['text-body']      = $textBody;
                $templates[$ws]['checkboxNameId'] = $checkboxNameId;
                $templates[$ws]['textareaNameId'] = $textareaNameId;
                $templates[$ws]['token']          = self::getPaidMembershipsProvariables();
            $ws++;
        }
        foreach ($orderStatuses as $ks => $vs) {
           
            if (!empty($vs)) {
                $currentVal      = smsalert_get_option('admin_pmp_notify_' . strtolower($vs), 'smsalert_pmp_general', 'on');
                $checkboxNameId  = 'smsalert_pmp_general[admin_pmp_notify_' . strtolower($vs) . ']';
                $textareaNameId  = 'smsalert_pmp_message[admin_sms_pmp_body_' . strtolower($vs) . ']';
                $defaultTemplate = smsalert_get_option('admin_sms_pmp_body_' . strtolower($vs), 'smsalert_pmp_message', sprintf(__('Hello admin, status of your membership with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', $vs, PHP_EOL, PHP_EOL));
                $textBody = smsalert_get_option('admin_sms_pmp_body_' . strtolower($vs), 'smsalert_pmp_message', $defaultTemplate);
                $templates[$ws]['title']          = 'When admin change status to ' . $vs;
                $templates[$ws]['enabled']        = $currentVal;
                $templates[$ws]['status']         = $vs;
                $templates[$ws]['text-body']      = $textBody;
                $templates[$ws]['checkboxNameId'] = $checkboxNameId;
                $templates[$ws]['textareaNameId'] = $textareaNameId;
                $templates[$ws]['token']          = self::getPaidMembershipsProvariables();
                $ws++;
            }
        }
        return $templates;
    }

    /**
     * Send sms membership active.
     *
     * @param int    $level_id     level id
     * @param int    $user_id      user id
     * @param string $cancel_level cancel_level
     *
     * @return void
     */
    public function sendSmsMembershipActive($level_id, $user_id, $cancel_level)
    {
        if (!empty($level_id)) {
            $data                 = (object) array('user_id'=>$user_id);
            $userName           = get_user_meta($user_id, 'nickname', true);
            $buyerNumber        = get_user_meta($user_id, 'billing_phone', true);
            $customerMessage     = smsalert_get_option('customer_sms_pmp_body_active', 'smsalert_pmp_message', '');
            $customerNotify    = smsalert_get_option('customer_pmp_notify_active', 'smsalert_pmp_general', 'on');
            if (($customerNotify === 'on' && $customerMessage !== '')) {
                  $buyerMessage = $this->parseSmsBody($data,  $customerMessage);
                do_action('sa_send_sms', $buyerNumber, $buyerMessage);
            }
        }
        
    } 
    /**
     * Send sms membership cancel.
     *
     * @param int    $level_id     level id
     * @param int    $user_id      user id
     * @param array  $old_levels   old_levels
     * @param string $cancel_level cancel_level
     *
     * @return void
     */
    public function sendSmsMembershipCancel($level_id, $user_id, $old_levels, $cancel_level)
    {
        if (!empty($cancel_level)) {
            $data                 = (object) array('user_id'=>$user_id);
            $buyerNumber        = get_user_meta($user_id, 'pmpro_bphone', true);
            $userName           = get_user_meta($user_id, 'nickname', true);
            $userEmail          = get_user_meta($user_id, 'pmpro_bemail', true);
            $customerMessage     = smsalert_get_option('customer_sms_pmp_body_cancel', 'smsalert_pmp_message', '');
            $customerNotify    = smsalert_get_option('customer_pmp_notify_cancel', 'smsalert_pmp_general', 'on');
            if (($customerNotify === 'on' && $customerMessage !== '')) {
                  $buyerMessage = $this->parseSmsBody($data,  $customerMessage);
                do_action('sa_send_sms', $buyerNumber, $buyerMessage);
            }
        }
    }  

     /**
      * Send sms order status success.
      *
      * @param int $user_id user_id
      * @param int $order   order
      *
      * @return void
      */
    public function triggerSmsOrderStatusSuccess($user_id, $order)
    {
        $this->setMembershipReminder($order);
        $this->sendSmsOn($order);
    } 

    /**
     * Send sms status cancelled.
     *
     * @param int $order   order
     * @param int $user_id user id
     *
     * @return void
     */
    public function triggerSmsStatusCancelled($order,$user_id)
    {
        return $this->sendSmsOn($order);
    }
    
    /**
     * Send sms status error.
     *
     * @param int $order   order
     * @param int $user_id user id
     *
     * @return void
     */
    public function triggerSmsStatusError($order,$user_id)
    {
        $this->sendSmsOn($order);
    }
    
    /**
     * Send sms status pending.
     *
     * @param int $order   order
     * @param int $user_id user id
     *
     * @return void
     */
    public function triggarSmsStatusPending($order,$user_id)
    {
        $this->sendSmsOn($order);
    }
    
    /**
     * Send sms status refunded.
     *
     * @param int $order   order
     * @param int $user_id user id
     *
     * @return void
     */
    public function triggerSmsStatusRefunded($order,$user_id)
    {
        $this->sendSmsOn($order);
    }
    
    /**
     * Send sms status review.
     *
     * @param int $order   order
     * @param int $user_id user id
     *
     * @return void
     */
    public function triggerSmsStatusReview($order,$user_id)
    {
        $this->sendSmsOn($order);
    }
    
    /**
     * Send sms status token.
     *
     * @param int $order   order
     * @param int $user_id user id
     *
     * @return void
     */
    public function triggerSmsStatusToken($order,$user_id)
    {
        $this->sendSmsOn($order);
    }

    /**
     * Send sms approved pending.
     *
     * @param int $order order
     *
     * @return void
     */
    public function sendSmsOn($order)
    {
        if (!empty($order->billing)) {
            $status          = $order->status;
            $customerMessage        = smsalert_get_option('customer_sms_pmp_body_' . $status, 'smsalert_pmp_message', '');
            $customerNotify         = smsalert_get_option('customer_pmp_notify_' . $status, 'smsalert_pmp_general', 'on');
            $user_id                = $order->user_id;
            $buyerNumber            = get_user_meta($user_id, 'pmpro_bphone', true);
            if (($customerNotify === 'on' && $customerMessage !== '')) {
                $buyerMessage       = $this->parseSmsBody($order, $customerMessage);
                do_action('sa_send_sms', $buyerNumber, $buyerMessage);
            }
            // Send msg to admin.
            $adminPhoneNumber   = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
            if (empty($adminPhoneNumber) === false) {
                $adminNotify        = smsalert_get_option('admin_pmp_notify_' . $status, 'smsalert_pmp_general', 'on');
                $adminMessage       = smsalert_get_option('admin_sms_pmp_body_' . $status, 'smsalert_pmp_message', '');
                $nos = explode(',', $adminPhoneNumber);
                $adminPhoneNumber   = array_diff($nos, array('postauthor', 'post_author'));
                $adminPhoneNumber   = implode(',', $adminPhoneNumber);
                if ($adminNotify === 'on' && $adminMessage !== '') {
                    $adminMessage   = $this->parseSmsBody($order, $adminMessage);
                    do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
                }
            }
        }
    }
    
    /**
     * Parse sms body.
     *
     * @param array  $order   order.
     * @param string $content content.
     *
     * @return string
     */
    public function parseSmsBody($order, $content = null)
    {  
        $user_id                     = !empty($order->user_id) ? $order->user_id : "";
        $membership                  = pmpro_getMembershipLevelForUser($user_id);
        $subscription_id             =!empty($membership ->subscription_id) ? $membership ->subscription_id : "";
        $billing_amount              =!empty($membership ->billing_amount) ? $membership ->billing_amount : "";
        $order_id                    = !empty($order->id) ? $order->id : "";
        $membership_id               = !empty($membership->ID) ? $membership->ID : "";
        $first_name                  = !empty($order->FirstName) ? $order->FirstName : "";
        $last_name                   = !empty($order->LastName) ? $order->LastName : "";
        $phone                       = get_user_meta($user_id, 'pmpro_bphone', true);
        $email                       = get_user_meta($user_id, 'pmpro_bemail', true);
        $userName                    = get_user_meta($user_id, 'nickname', true);
        $order_status                = !empty($order->status) ? $order->status : "";
        $membership_name             =!empty($membership ->name) ? $membership ->name : "";
        $start                       = !empty($membership->startdate) ? $membership->startdate : "";
        $membership_start_date       = wp_date("Y-m-d H:i:s", $start);  
        $expired                     = !empty($membership->enddate) ? $membership->enddate : "";
        $membership_expiration_date  = wp_date("Y-m-d H:i:s", $expired); 
        $address1                    = !empty($order->Address1) ? $order->Address1 : "";
        $address2                    = !empty($order->Address2) ? $order->Address2 : "";
        $city                        = !empty($order->billing->city) ? $order->billing->city : "";
        $state                       = !empty($order->billing->state) ? $order->billing->state : "";
        $zip                         = !empty($order->billing->zip) ? $order->billing->zip : "";
        $country                     = !empty($order->billing->country) ? $order->billing->country : "";
        $total                       = !empty($order->total) ? $order->total : "";
        $subtotal                    = !empty($order->subtotal) ? $order->subtotal : "";
        $payment_type                = !empty($order->payment_type) ? $order->payment_type : "";
        $payment_transaction_id      = !empty($order->payment_transaction_id) ? $order->payment_transaction_id : "";
        $invoce                      = !empty($order->code) ? $order->code : "";

        $find = array(
            '[user_id]',
            '[subscription_id]',
            '[billing_amount]',
            '[order_id]',
            '[membership_id]',
            '[first_name]',
            '[last_name]',
            '[phone]',
            '[email]',
            '[userName]',
            '[order_status]',
            '[membership_name]',
            '[membership_start_date]',
            '[membership_expiration_date]',
            '[address1]',
            '[address2]',
            '[city]',
            '[state]',
            '[zip]',
            '[country]',
            '[total]',
            '[subtotal]',
            '[payment_type]',
            '[payment_transaction_id]',
        '[invoce]',
        );

        $replace = array(
        $user_id,
        $subscription_id,
        $billing_amount,
        $order_id,
        $membership_id,
        $first_name,
        $last_name,
        $phone,
        $email,
        $userName,
        $order_status,
        $membership_name,
        $membership_start_date,
        $membership_expiration_date,
        $address1,
        $address1,
        $city,
        $state,
        $zip,
        $country,
        $total,
        $subtotal,
        $payment_type,
        $payment_transaction_id,
        $invoce,
        );
        $content = str_replace($find, $replace, $content);
        return $content;
    }

    /**
     * Get paid memberships pro variables.
     *
     * @return array
     */
    public static function getPaidMembershipsProvariables()
    {
        $variable['[user_id]']                     = 'User Id';
        $variable['[subscription_id]']             = 'Subscription Id';
        $variable['[billing_amount]']              = 'Billing Amount';
        $variable['[order_id]']                    = 'Order Id';
        $variable['[membership_id]']               = 'Membership Id';
        $variable['[first_name]']                  = 'First Name';
        $variable['[last_name]']                   = 'Last Name';
        $variable['[phone]']                       = 'Phone';
        $variable['[email]']                       = 'Email';
        $variable['[userName]']                    = 'userName';
        $variable['[order_status]']                = 'Order Status';
        $variable['[membership_name]']             = 'Membership Name';
        $variable['[membership_start_date]']       = 'Membership Start Date';
        $variable['[membership_expiration_date]']  = 'Membership Expiration Date';
        $variable['[address1]']                    = 'Address1';
        $variable['[address2]']                    = 'Address2';
        $variable['[city]']                        = 'City';
        $variable['[state]']                       = 'State';
        $variable['[zip]']                         = 'Zip';
        $variable['[country]']                     = 'Country';
        $variable['[total]']                       = 'Total Amount';
        $variable['[subtotal]']                    = 'Subtotal';
        $variable['[payment_type]']                = 'Payment Type';
        $variable['[payment_transaction_id]']      = 'Transaction Id';
        $variable['[invoce]']                      = 'Invoce Number';
        return $variable;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('paid-memberships-pro/paid-memberships-pro.php') === true) {
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
        if ((is_plugin_active('paid-memberships-pro/paid-memberships-pro.php') === true) && ($islogged === true)) {
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
        SmsAlertUtility::checkSession();
        if (isset($_SESSION[$this->form_session_var]) === false) {
            return;
        }
        if ((empty($_REQUEST['option']) === false) && sanitize_text_field(wp_unslash($_REQUEST['option'])) === 'smsalert-validate-otp-form') {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
            exit();
        } else {
            $_SESSION[$this->form_session_var] = 'verification_failed';
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
        if (isset($_SESSION[$this->form_session_var]) === false) {
            return;
        }
        if ((empty($_REQUEST['option']) === false ) && sanitize_text_field(wp_unslash($_REQUEST['option'])) === 'smsalert-validate-otp-form') {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('VALID_OTP'), 'success'));
            exit();
        } else {
            $_SESSION[$this->form_session_var] = 'validated';
        }
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
        if (!empty($_SESSION[$this->form_session_var]) && $_SESSION[$this->form_session_var] === true) {
            return true;
        } else {
            return $isAjax;
        }
    }
}
new SA_Paidmembershipspro();
