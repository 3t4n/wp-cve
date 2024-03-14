<?php
/**
 * Wpadverts helper.
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

if (is_plugin_active('wpadverts/wpadverts.php') === false) {
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
 * SA_WPadverts class 
 */
class SA_WPadverts extends FormInterface
{
    /**
     * Construct function.
     *
     * @return void
     */
    public function handleForm()
    {
        
        add_filter('sa_get_user_phone_no', array( $this, 'setUserPhoneNo' ), 10, 2);
        add_action('advert_tmp_to_publish', array($this, 'sendSmsOnUserAdvertPublish'), 1000, 1);
        add_action('advert-pending_to_publish', array($this, 'sendSmsOnUserAdvertPublish'), 1000, 1);
        add_action('pending_to_publish', array($this, 'sendSmsOnAdvertPendingToPublish'), 10, 1); 
        add_action('advert_tmp_to_advert-pending', array($this, 'sendSmsOnUserDraftToPending'), 10, 1);
        add_action('advert_tmp_to_pending', array($this, 'sendSmsOnUserDraftToPending'), 10, 1); 
        add_action('adverts_payment_new_to_pending', array($this, 'sendSmsOnUserDraftToPending'), 10, 1); 
        add_action('publish_to_expired', array($this, 'sendSmsOnAdvertPublishToExpire'), 10, 1);
        add_action('expired_to_publish', array($this, 'sendSmsOnAdvertExpiredToPublish'), 10, 1);
        add_action('expired_to_pending', array($this, 'sendSmsOnAdvertExpiredToPending'), 10, 1);
        add_action('pending_to_trash', array($this, 'sendSmsOnAdvertPendingToTrash'), 10, 1);    
        add_action('adverts_payment_completed', array( $this, 'advertsPostUpdated'), 100);
        add_action("adext_contact_form_send", array( $this, "sendSmsAuthor" ), 10, 2);        
        add_action('smsalert_followup_sms',  array($this, 'sendReminderSms'));
    }
    
    /**
     * Set sms reminder.
     *
     * @param array $post post.
     *
     * @return void
     */
    public static function setSmsReminder($post)
    {  
        $customerNotify  = smsalert_get_option('customer_notify', 'smsalert_adv_general', 'on'); 
        $source          = 'wpadverts';
        $status          = $post->post_status;
        $id              = $post->ID;
        global $wpdb;        
        $listing_type    = get_post_meta($id, "payments_listing_type", true);        
        $visible         = get_post_meta($listing_type, "adverts_visible", true);
        if ($visible > 0 ) {
            $publish     = get_the_date("Y-m-d H:i:s", $id, true);
            $expiry      = date("Y-m-d H:i:s", strtotime($publish . " +$visible DAYS")); 
        }
        $buyerMob        = get_post_meta($id, 'adverts_phone', true);
        global $wpdb;
        $table_name       = $wpdb->prefix .'smsalert_renewal_reminders';
        $subscription_details = $wpdb->get_results("SELECT next_payment_date, notification_sent_date FROM $table_name WHERE subscription_id = $id and source = '$source'");
        if ('publish' === $status && 'on' === $customerNotify && $expiry) {
            $scheduler_data = get_option('smsalert_adv_renewal_scheduler');
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
                                array( 'subscription_id' => $id )
                            );
                        } else {  
                            $wpdb->insert(
                                $table_name,
                                array(
                                'subscription_id'        => $id,
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
            $wpdb->delete($table_name, array( 'subscription_id' => $id ));
        }
    }

    /**
     * Send sms function.
     *
     * @return void
     */
    function sendReminderSms()
    {    
        global $wpdb;
        $customerNotify       = smsalert_get_option('customer_notify', 'smsalert_adv_general', 'on');
        $source               = 'wpadverts';
        $table_name           = $wpdb->prefix . 'smsalert_renewal_reminders'; 
        $schedulerData        = get_option('smsalert_adv_reminder_scheduler');
        $today                = new DateTime();
        $today                = $today->format('Y-m-d');
        $subscription_details = $wpdb->get_results("SELECT * FROM $table_name WHERE notification_sent_date = '$today' and source = '$source'");
        if ('on' === $customerNotify && $subscription_details) {
            foreach ( $subscription_details as $subscription ) {
                $id                        = $subscription->subscription_id;
                $customer_sms              = $subscription->subscription_text;
                $buyerMob                  = get_post_meta($id, 'adverts_phone', true);
                $adverd                      = get_post($id);
                $customer_msg               = $this->parseSmsBody($adverd, $customer_sms);
                $cust_sms_data['number']   = $buyerMob;
                $cust_sms_data['sms_body'] = $customer_msg;
                $customer_msg              = ( ! empty($cust_sms_data['sms_body']) ) ? $cust_sms_data['sms_body'] : ''; 
                do_action('sa_send_sms', $buyerMob, $customer_msg);
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
        $adverdStatuses =array('pending', 'publish', 'expired', 'trash');
        foreach ($adverdStatuses as $ks => $vs) {
            $defaults['smsalert_adv_general']['customer_adv_notify_' . $vs]   = 'off';
            $defaults['smsalert_adv_message']['customer_sms_adv_body_' . $vs] = '';
            $defaults['smsalert_adv_general']['admin_adv_notify_' . $vs]      = 'off';
            $defaults['smsalert_adv_message']['admin_sms_adv_body_' . $vs]    = '';
        }
        $defaults['smsalert_adv_general']['author_adv_notify_message']   = 'off';
        $defaults['smsalert_adv_message']['author_sms_adv_body_message'] = '';
        $defaults['smsalert_adv_renewal']['customer_notify']                  = 'off';
        $defaults['smsalert_adv_renewal_scheduler']['cron'][0]['frequency']   = '1';
        $defaults['smsalert_adv_renewal_scheduler']['cron'][0]['message']     = '';
        $defaults['smsalert_adv_renewal_scheduler']['cron'][1]['frequency']   = '2';
        $defaults['smsalert_adv_renewal_scheduler']['cron'][1]['message']     = '';
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
            'checkTemplateFor' => 'adv_customer',
            'templates'        => self::getCustomerTemplates(),
        );
        $authorParam = array(
            'checkTemplateFor' => 'adv_author',
            'templates'        => self::getAuthorTemplates(),
        );
        $admin_param = array(
            'checkTemplateFor' => 'adv_admin',
            'templates'        => self::getAdminTemplates(),
        );
        $reminderParam = array(
            'checkTemplateFor' => 'wc_wpadverts_reminder',
            'templates'        => self::getReminderTemplates(),
        );
        $tabs['wpadverts']['nav']           = 'WP Adverts';
        $tabs['wpadverts']['icon']          = 'dashicons-megaphone';
        $tabs['wpadverts']['inner_nav']['wpadverts_cust']['title']          = 'Customer Notifications';
        $tabs['wpadverts']['inner_nav']['wpadverts_cust']['tab_section']    = 'wpadvertscusttemplates';
        $tabs['wpadverts']['inner_nav']['wpadverts_cust']['first_active']   = true;
        $tabs['wpadverts']['inner_nav']['wpadverts_cust']['tabContent']     = $customerParam;
        $tabs['wpadverts']['inner_nav']['wpadverts_cust']['filePath']       = 'views/message-template.php'; 
        $tabs['wpadverts']['inner_nav']['wpadverts_admin']['title']          = 'Admin Notifications';
        $tabs['wpadverts']['inner_nav']['wpadverts_admin']['tab_section']    = 'wpadvertsadmintemplates';
        $tabs['wpadverts']['inner_nav']['wpadverts_admin']['tabContent']     = $admin_param;
        $tabs['wpadverts']['inner_nav']['wpadverts_admin']['filePath']       = 'views/message-template.php';
        $tabs['wpadverts']['inner_nav']['wpadverts_author']['title']          = 'Author Notification';
        $tabs['wpadverts']['inner_nav']['wpadverts_author']['tab_section']    = 'wpadvertsauthortemplates';
        $tabs['wpadverts']['inner_nav']['wpadverts_author']['tabContent']     = $authorParam;
        $tabs['wpadverts']['inner_nav']['wpadverts_author']['filePath']       = 'views/message-template.php';
        $tabs['wpadverts']['inner_nav']['wpadverts_reminder']['title']        = 'Adverts Reminder';
        $tabs['wpadverts']['inner_nav']['wpadverts_reminder']['tab_section']  = 'awpadvertsremindertemplates';
        $tabs['wpadverts']['inner_nav']['wpadverts_reminder']['tabContent']   = $reminderParam;
        $tabs['wpadverts']['inner_nav']['wpadverts_reminder']['filePath']     = 'views/renewal-template.php';
		$tabs['wpadverts']['help_links'] = array(
			'kb_link'      => array(
			'href'   => 'https://kb.smsalert.co.in/knowledgebase/integrate-with-wp-adverts/',
			'target' => '_blank',
			'alt'    => 'Read how to integrate with WP Adverts',
			'class'  => 'btn-outline',
			'label'  => 'Documentation',
			'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
			),
        );
        return $tabs;
    }

    /**
     * Get wc renewal templates function.
     *
     * @return array
     */
    public static function getReminderTemplates()
    {
        $currentVal     = smsalert_get_option('customer_notify', 'smsalert_adv_renewal', 'on');
        $checkboxNameId = 'smsalert_adv_renewal[customer_notify]';
        $schedulerData  = get_option('smsalert_adv_renewal_scheduler');
        $templates      = array();
        $count          = 0;
        if (empty($schedulerData)) {
			$schedulerData = array();
            $schedulerData['cron'][] = array(
                'frequency' => '1',
                'message'   => sprintf(__('Hello %1$s, your advertisement %2$s with %3$s is expired on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[authorName]', '#[id]', '[store_name]', '[expiration_date]', PHP_EOL, PHP_EOL),
            );
            $schedulerData['cron'][] = array(
                'frequency' => '2',
                'message'   => sprintf(__('Hello %1$s, your advertisement %2$s with %3$s is expired on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[authorName]', '#[id]', '[store_name]', '[expiration_date]', PHP_EOL, PHP_EOL),
            );
        }
        foreach ($schedulerData['cron'] as $key => $data) {
            if (empty($data['message'])) {
                continue;
            }
            $textAreaNameId  = 'smsalert_adv_renewal_scheduler[cron][' . $count . '][message]';
            $selectNameId    = 'smsalert_adv_renewal_scheduler[cron][' . $count . '][frequency]';
            $textBody        = $data['message'];
            $templates[$key]['notify_id']      = 'wpadverts';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']          = 'Send renewal reminder message to customer';
            $templates[$key]['checkboxNameId'] = $checkboxNameId;
            $templates[$key]['text-body']      = $textBody;
            $templates[$key]['textareaNameId'] = $textAreaNameId;
            $templates[$key]['selectNameId']   = $selectNameId;
            $templates[$key]['token']          = self:: getWpadvertsvariables();
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
        $adverdStatuses     = array('pending', 'publish', 'expired', 'trash', );
        $templates          = array();
        foreach ($adverdStatuses as $ks => $vs) {
            $currentVal      = smsalert_get_option('customer_adv_notify_' . strtolower($vs), 'smsalert_adv_general', 'on');
            $checkboxNameId  = 'smsalert_adv_general[customer_adv_notify_' . strtolower($vs) . ']';
            $textareaNameId  = 'smsalert_adv_message[customer_sms_adv_body_' . strtolower($vs) . ']';
            $defaultTemplate = smsalert_get_option('customer_sms_adv_body_' . strtolower($vs), 'smsalert_adv_message', sprintf(__('Hello %1$s, status of your advertisement #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[authorName]', '[id]', '[store_name]', $vs, PHP_EOL, PHP_EOL));
            $textBody       = smsalert_get_option('customer_sms_adv_body_' . strtolower($vs), 'smsalert_adv_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When advertisement status is ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self:: getWpadvertsvariables();
        }
        return $templates;
    } /**
       * Get author templates.
       *
       * @return array
       */
    public static function getAuthorTemplates()
    {
        $authorStatuses     = array('message');
        $templates           =array();
        foreach ($authorStatuses as $ks => $vs) {
            $currentVal      = smsalert_get_option('author_adv_notify_' . strtolower($vs), 'author_adv_general', 'on');
            $checkboxNameId  = 'smsalert_adv_general[author_adv_notify_' . strtolower($vs) . ']';
            $textareaNameId  = 'smsalert_adv_message[author_sms_adv_body_' . strtolower($vs) . ']';
            $defaultTemplate = smsalert_get_option('author_sms_adv_body_' . strtolower($vs), 'smsalert_adv_message', SmsAlertMessages::showMessage('DEFAULT_CONTACT_FORM_ADMIN_MESSAGE'));
            $textBody       = smsalert_get_option('author_sms_adv_body_' . strtolower($vs), 'smsalert_adv_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When customer send ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self:: getWpadvertsvariables();
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
         $adverdStatuses     = array('pending', 'publish', 'expired', 'trash', );
          $templates           =array();
        foreach ($adverdStatuses as $ks => $vs) {
            $currentVal      = smsalert_get_option('admin_adv_notify_' . strtolower($vs), 'smsalert_adv_general', 'on');
            $checkboxNameId  = 'smsalert_adv_general[admin_adv_notify_' . strtolower($vs) . ']';
            $textareaNameId  = 'smsalert_adv_message[admin_sms_adv_body_' . strtolower($vs) . ']';
            $defaultTemplate = smsalert_get_option('admin_sms_adv_body_' . strtolower($vs), 'smsalert_adv_message', sprintf(__('%1$s status of advertisement has been changed to %2$s.', 'sms-alert'), '[store_name]:', $vs));
            $textBody = smsalert_get_option('admin_sms_adv_body_' . strtolower($vs), 'smsalert_adv_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When advertisement status is ' . $vs;
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self:: getWpadvertsvariables();
        }
        return $templates;
    }

    /**
     * Set user no function.
     *
     * @param string $billing_phone billing phone.
     * @param int    $user_id       user id.
     *
     * @return int
     */
    public function setUserPhoneNo( $billing_phone, $user_id )
    {
        return ( ! empty($billing_phone) ) ? $billing_phone : get_post_meta($user_id, '_billing_phone', true);
 
    } 
    
    /**
     * Send sms advert publish.
     *
     * @param int $post post
     *
     * @return array
     */
    public function sendSmsOnUserAdvertPublish($post)
    { 
        $this->setSmsReminder($post);
        $this->sendSmsOn($post);
    }
    
    /**
     * Send sms advert pending.
     *
     * @param int $post post
     *
     * @return array
     */
    public function sendSmsOnUserDraftToPending($post)
    { 
        $this->sendSmsOn($post);
    }
    
    /**
     * Send sms advert seller.
     *
     * @param int $post_id post_id
     * @param int $form    form
     *
     * @return array
     */
    public function sendSmsAuthor($post_id, $form)
    {
         
        $post        = get_post($post_id);
        $authorName        = get_post_meta($post_id, 'adverts_person', true);
        $authorNumber = get_post_meta($post_id, 'adverts_phone', true);
        if (!empty($authorNumber)) {
            $authorMessage = smsalert_get_option('author_sms_adv_body_message', 'smsalert_adv_message', '');
            $authorNotify  = smsalert_get_option('author_adv_notify_message', 'smsalert_adv_general', 'on');
            if (($authorNotify === 'on' && $authorMessage !== '')) {
                $buyerMessage = $this->parseSmsBody($post, $authorMessage);
                do_action('sa_send_sms', $authorNumber, $buyerMessage);
            }
        } 
    }
     
    /**
     * Send sms advert publish.
     *
     * @param int $post post
     *
     * @return array
     */
    public function sendSmsOnAdvertPendingToPublish($post)
    {
        $this->sendSmsOn($post);
    }
    
    /**
     * Send sms advert expire.
     *
     * @param int $post post
     *
     * @return array
     */
    public function sendSmsOnAdvertPublishToExpire($post)
    {
        $expired         = get_post_meta($post->ID, '_expiration_date', true);
        $expiration_date =  date("Y-m-d H:i:s", $expired);
        $post->expired   = $expiration_date;
        $this->sendSmsOn($post);
    }
    
    /**
     * Send sms advert publish.
     *
     * @param int $post post
     *
     * @return array
     */
    public function sendSmsOnAdvertExpiredToPublish($post)
    {
        $expired         = get_post_meta($post->ID, '_expiration_date', true);
        $expiration_date =  date("Y-m-d H:i:s", $expired);
        $post->expired   = $expiration_date;
        $this->sendSmsOn($post);
    }
    
    /**
     * Send sms advert pending.
     *
     * @param int $post post
     *
     * @return array
     */
    public function sendSmsOnAdvertExpiredToPending($post)
    {
        $expired         = get_post_meta($post->ID, '_expiration_date', true);
        $expiration_date =  date("Y-m-d H:i:s", $expired);
        $post->expired   = $expiration_date;
        $this->sendSmsOn($post);
    }
    
    /**
     * Send sms advert trash.
     *
     * @param int $post post
     *
     * @return array
     */
    public function sendSmsOnAdvertPendingToTrash($post)
    {
        $this->sendSmsOn($post);
    } 
    
    /**
     * Send sms advert publish.
     *
     * @param int $post post
     *
     * @return array
     */
    public function sendSmsOnAdminAdvertPublish($post)
    {
        $this->sendSmsOn($post);
    } 
    
    /**
     * Send sms advert publish.
     *
     * @param int $post post
     *
     * @return array
     */
    public function advertsPostUpdated($post)
    {
        if (get_post_meta($post->ID, "_adverts_payment_type", true) == "adverts-renewal" ) {
            $object_id         = get_post_meta($post->ID, "_adverts_object_id", true);
            $pricing_id        = get_post_meta($post->ID, "_adverts_pricing_id", true);
            $expires           = get_post_meta($object_id, "_expiration_date", true);
            $expiration_date   =  gmdate("Y-m-d H:i:s", $expires);
            global $wpdb;
            $tableName       = $wpdb->prefix . 'smsalert_renewal_reminders';
            $source          = 'wpadverts';
            $wpdb->update(
                $tableName,
                array(
                        'next_payment_date' => $expiration_date
                     ),
                array('subscription_id' =>$object_id, 'source' => $source)
            );
            $this->sendSmsOn($post);
        }
    } 
    
     /**
      * Send sms approved pending.
      *
      * @param int $post post
      *
      * @return void
      */
    public function sendSmsOn($post)
    {
        $id          = $post->ID;
        $status      = $post->post_status;
        $status      = ($post->post_status == 'advert-pending') ? "pending" : $post->post_status;
        $authorNumber = get_post_meta($id, 'adverts_phone', true);
        
        if (!empty($authorNumber)) {
            $customerMessage = smsalert_get_option('customer_sms_adv_body_' . $status, 'smsalert_adv_message', '');
            $customerNotify  = smsalert_get_option('customer_adv_notify_' . $status, 'smsalert_adv_general', 'on');
            if (($customerNotify === 'on' && $customerMessage !== '')) {
                $buyerMessage = $this->parseSmsBody($post, $customerMessage);
                do_action('sa_send_sms', $authorNumber, $buyerMessage);
            }
        }

        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
        if (empty($adminPhoneNumber) === false) {
            $adminNotify        = smsalert_get_option('admin_adv_notify_' . $status, 'smsalert_adv_general', 'on');
            $adminMessage       = smsalert_get_option('admin_sms_adv_body_' . $status, 'smsalert_adv_message', '');
            $nos = explode(',', $adminPhoneNumber);
            $adminPhoneNumber   = array_diff($nos, array('postauthor', 'post_author'));
            $adminPhoneNumber   = implode(',', $adminPhoneNumber);
            if ($adminNotify === 'on' && $adminMessage !== '') {
                $adminMessage   = $this->parseSmsBody($post, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }        
    } 
    
    /**
     * Parse sms body.
     *
     * @param array  $adverd  adverd.
     * @param string $content content.
     *
     * @return string
     */
    public function parseSmsBody($adverd, $content = null)
    {
        $id              = $adverd->ID;
        $status          = $adverd->post_status;
        $authorNumber     = get_post_meta($id, 'adverts_phone', true);
        $authorName            = get_post_meta($id, 'adverts_person', true);
        $post_title      = $adverd->post_title;
        $post_date       = $adverd->post_date;
        $expired         = get_post_meta($id, '_expiration_date', true);
        $expiration_date = date("Y-m-d H:i:s", $expired);
        $post_url        = $adverd->guid;
        $find = array(
        '[id]',
        '[status]',
        '[authorNumber]',
        '[authorName]',
        '[post_title]',
        '[post_date]',
        '[expiration_date]',
        '[post_url]'  
        );

        $replace = array(
        $id,
        $status,
        $authorNumber,
        $authorName,
        $post_title,
        $post_date,
        $expiration_date,
        $post_url
        );
        $content = str_replace($find, $replace, $content);
        return $content;
    }

    /**
     * Get Wpadverts variables.
     *
     * @return array
     */
    public static function getWpadvertsvariables()
    {
        $variable['[id]']              = 'Id';
        $variable['[status]']          = 'Status';
        $variable['[authorNumber]']    = 'authorNumber';
        $variable['[authorName]']      = 'authorName';
        $variable['[post_title]']      = 'Post Title';
        $variable['[post_date]']       = 'Post Date';
        $variable['[expiration_date]'] = 'Expiration Date';
        $variable['[post_url]']        = 'Post Url';
        return $variable;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('wpadverts/wpadverts.php') === true) {
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
        if ((is_plugin_active('wpadverts/wpadverts.php') === true) && ($islogged === true)) {
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
new SA_WPadverts();

