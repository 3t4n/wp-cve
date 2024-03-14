<?php
/**
 * Salon booking helper.
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
if (! is_plugin_active('salon-booking-system/salon.php') ) {
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
 * Salonbooking class 
 */
class Salonbooking extends FormInterface
{
    
    /**
     * Construct function.
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('transition_post_status', array($this, 'sendBookingSms'), 10, 3);
        add_action('booking_reminder_sendsms_hook', [$this, 'sendReminderSms'], 10);

    }//end handleForm()

    /**
     * Set booking reminder.
     *
     * @param int $post post.
     *
     * @return void
     */
    public static function setBookingReminder($post)
    {
        if (empty($post) === true) {
            return;
        }
        $booking_id     = $post->ID;
        $meta           = get_post_meta($post->ID); 
        $buyer_mob      = current($meta['_sln_booking_phone']);
        $bookingStatus  = $post->post_status;
        $bookingDate    = current($meta['_sln_booking_date']);
        $bookingTime    = current($meta['_sln_booking_time']); 
        $booking_start  = $bookingDate . ' ' . $bookingTime;
        $customerNotify = smsalert_get_option('customer_notify', 'smsalert_sln_general', 'on');
        global $wpdb;
        $tableName       = $wpdb->prefix.'smsalert_booking_reminder';
        $source          = 'salon-booking';
        $booking_details = $wpdb->get_results("SELECT * FROM $tableName WHERE booking_id = $booking_id and source = '$source'");
        if ($bookingStatus === 'sln-b-confirmed' && $customerNotify === 'on') {
            if ($booking_details) {
                $wpdb->update(
                    $tableName,
                    [
                        'start_date' => $booking_start,
                        'phone'      => $buyer_mob,
                    ],
                    ['booking_id' => $booking_id]
                );
            } else {
                $wpdb->insert(
                    $tableName,
                    [
                        'booking_id' => $booking_id,
                        'phone'      => $buyer_mob,
                        'source'     => $source,
                        'start_date' => $booking_start,
                    ]
                );
            }//end if
        } else {
            $wpdb->delete($tableName, ['booking_id' => $booking_id]);
        }//end if

    }//end setBookingReminder()

    /**
     * Send sms function.
     *
     * @return void
     */
    function sendReminderSms()
    {
        if (smsalert_get_option('customer_notify', 'smsalert_sln_general') !== 'on') {
            return;
        }

        global $wpdb, $post;
        $cronFrequency = BOOKING_REMINDER_CRON_INTERVAL;
        // pick data from previous CART_CRON_INTERVAL min
        $tableName     = $wpdb->prefix.'smsalert_booking_reminder';
        $source        = 'salon-booking';
        $schedulerData = get_option('smsalert_sln_reminder_scheduler');        
        foreach ($schedulerData['cron'] as $sdata) {
            $datetime = current_time('mysql');
            $fromdate = date('Y-m-d H:i:s', strtotime('+'.($sdata['frequency'] * 60 - $cronFrequency).' minutes', strtotime($datetime)));
        
            $todate = date('Y-m-d H:i:s', strtotime('+'.$cronFrequency.' minutes', strtotime($fromdate)));
            
            $rowsToPhone = $wpdb->get_results(
                'SELECT * FROM '.$tableName." WHERE start_date > '".$fromdate."' AND start_date <= '".$todate."' AND source = '$source' ",
                ARRAY_A
            );
            if ($rowsToPhone) {
                // If we have new rows in the database
                $customerMessage = $sdata['message'];
                $frequencyTime   = $sdata['frequency'];
                if ($customerMessage !== '' && $frequencyTime !== 0) {
                    $obj = [];
                    foreach ($rowsToPhone as $key => $data) {
                    
                        $obj[$key]['number']   = $data['phone'];
                        $id                    = $data['booking_id'];
                        
                        $obj[$key]['sms_body'] = self::parseSmsBody($post, $customerMessage, $id);
                    }
                    $response    = SmsAlertcURLOTP::sendSmsXml($obj);
                    $responseArr = json_decode($response, true);
                    if (!empty($responseArr['status']) && 'success' === $responseArr['status'] ) {
                        foreach ($rowsToPhone as $data) {
                            $lastMsgCount = $data['msg_sent'];
                            $totalMsgSent = ($lastMsgCount + 1);
                            $wpdb->update(
                                $tableName,
                                ['msg_sent' => $totalMsgSent],
                                [
                                    'booking_id' => $data['booking_id'],
                                    'source'     => $source,
                                ]
                            );
                        } 
                    }
                }//end if
            }//end if
        }//end foreach

    }//end sendReminderSms()

    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public static function add_default_setting($defaults=[])
    {
        $bookingStatuses = SLN_Enum_BookingStatus::getLabels();
        foreach ($bookingStatuses as $ks => $vs) {
            $defaults['smsalert_sln_general']['customer_sln_notify_'.$ks]   = 'off';
            $defaults['smsalert_sln_message']['customer_sms_sln_body_'.$ks] = '';
            $defaults['smsalert_sln_general']['admin_sln_notify_'.$ks]      = 'off';
            $defaults['smsalert_sln_message']['admin_sms_sln_body_'.$ks]    = '';
        }
        $defaults['smsalert_sln_general']['otp_enable']      = 'off';
        $defaults['smsalert_sln_general']['customer_notify'] = 'off';
        $defaults['smsalert_sln_reminder_scheduler']['cron'][0]['frequency'] = '1';
        $defaults['smsalert_sln_reminder_scheduler']['cron'][0]['message']   = '';
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
            'checkTemplateFor' => 'sln_customer',
            'templates'        => self::getCustomerTemplates(),
        ];

        $adminParam = [
            'checkTemplateFor' => 'sln_admin',
            'templates'        => self::getAdminTemplates(),
        ];

        $reminderParam = [
            'checkTemplateFor' => 'wc_salon_booking_reminder',
            'templates'        => self::getReminderTemplates(),
        ];

        $tabs['salon_booking']['nav']  = 'Salon Booking';
        $tabs['salon_booking']['icon'] = 'dashicons-calendar-alt';

        $tabs['salon_booking']['inner_nav']['salon_booking_cust']['title']        = 'Customer Notifications';
        $tabs['salon_booking']['inner_nav']['salon_booking_cust']['tab_section']  = 'salonbookingcusttemplates';
        $tabs['salon_booking']['inner_nav']['salon_booking_cust']['first_active'] = true;
        $tabs['salon_booking']['inner_nav']['salon_booking_cust']['tabContent']   = $customerParam;
        $tabs['salon_booking']['inner_nav']['salon_booking_cust']['filePath']     = 'views/message-template.php';

        $tabs['salon_booking']['inner_nav']['salon_booking_admin']['title']          = 'Admin Notifications';
        $tabs['salon_booking']['inner_nav']['salon_booking_admin']['tab_section']    = 'salonbookingadmintemplates';
        $tabs['salon_booking']['inner_nav']['salon_booking_admin']['tabContent']     = $adminParam;
        $tabs['salon_booking']['inner_nav']['salon_booking_admin']['filePath']       = 'views/message-template.php';
        $tabs['salon_booking']['inner_nav']['salon_booking_reminder']['title']       = 'Booking Reminder';
        $tabs['salon_booking']['inner_nav']['salon_booking_reminder']['tab_section'] = 'bookingremindertemplates';
        $tabs['salon_booking']['inner_nav']['salon_booking_reminder']['tabContent']  = $reminderParam;
        $tabs['salon_booking']['inner_nav']['salon_booking_reminder']['filePath']    = 'views/booking-reminder-template.php';
        $tabs['salon_booking']['help_links'] = [
            'kb_link'      => [
                'href'   => 'https://kb.smsalert.co.in/knowledgebase/salon-booking-sms-integration/',
                'target' => '_blank',
                'alt'    => 'Read how to integrate with Salon Booking System',
                'class'  => 'btn-outline',
                'label'  => 'Documentation',
                'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
            ],
        ];
        return $tabs;

    }//end addTabs()


    /**
     * Get wc renewal templates function.
     *
     * @return array
     * */
    public static function getReminderTemplates()
    {
        $currentVal     = smsalert_get_option('customer_notify', 'smsalert_sln_general', 'on');
        $checkboxMameId = 'smsalert_sln_general[customer_notify]';

        $schedulerData = get_option('smsalert_sln_reminder_scheduler');
        $templates     = [];
        $count         = 0;
        if (empty($schedulerData) === true) {
			$schedulerData  = array();
            $schedulerData['cron'][] = [
                'frequency' => '1',
                'message'   => sprintf(__('Hello %1$s, your booking %2$s with %3$s is fixed on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[_sln_booking_firstname]', '#[ID]', '[store_name]', '[booking_date]', PHP_EOL, PHP_EOL),
            ];
        }

        foreach ($schedulerData['cron'] as $key => $data) {
            $textAreaNameId = 'smsalert_sln_reminder_scheduler[cron]['.$count.'][message]';
            $selectNameId   = 'smsalert_sln_reminder_scheduler[cron]['.$count.'][frequency]';
            $textBody       = $data['message'];

            $templates[$key]['notify_id']      = 'salon-booking';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']          = 'Send bookit reminder to customer';
            $templates[$key]['checkboxNameId'] = $checkboxMameId;
            $templates[$key]['text-body']      = $textBody;
            $templates[$key]['textareaNameId'] = $textAreaNameId;
            $templates[$key]['selectNameId']   = $selectNameId;
            $templates[$key]['token']          = self::getSalonBookingvariables();

            $count++;
        }

        return $templates;

    }//end getReminderTemplates()


    /**
     * Get customer templates.
     *
     * @return array
     */
    public static function getCustomerTemplates()
    {
        $bookingStatuses = SLN_Enum_BookingStatus::getLabels();       
        $templates = [];
        foreach ($bookingStatuses as $ks  => $vs) {            
            $currentVal = smsalert_get_option('customer_sln_notify_'.strtolower($ks), 'smsalert_sln_general', 'on');
            $checkboxMameId = 'smsalert_sln_general[customer_sln_notify_'.strtolower($ks).']';
            $textareaNameId = 'smsalert_sln_message[customer_sms_sln_body_'.strtolower($ks).']';
            $defaultTemplate = sprintf(__('Hello %1$s, status of your booking #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[firstname]', '[booking_id]', '[store_name]', $vs, PHP_EOL, PHP_EOL);
            $textBody = smsalert_get_option('customer_sms_sln_body_'.strtolower($ks), 'smsalert_sln_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When customer booking is '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $ks;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxMameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getSalonBookingvariables();
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
        $bookingStatuses = SLN_Enum_BookingStatus::getLabels();
        $templates = [];
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal     = smsalert_get_option('admin_sln_notify_'.strtolower($ks), 'smsalert_sln_general', 'on');
            $checkboxMameId = 'smsalert_sln_general[admin_sln_notify_'.strtolower($ks).']';
            $textareaNameId = 'smsalert_sln_message[admin_sms_sln_body_'.strtolower($ks).']';

            $defaultTemplate = sprintf(__('Hello admin, status of your booking #%1$s with %2$s has been changed to %3$s. %4$sPowered by%5$swww.smsalert.co.in', 'sms-alert'), '[booking_id]', '[store_name]', $vs, PHP_EOL, PHP_EOL);

            $textBody = smsalert_get_option('admin_sms_sln_body_'.strtolower($ks), 'smsalert_sln_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When admin change status to '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $ks;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxMameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getSalonBookingvariables();
        }//end foreach

        return $templates;

    }//end getAdminTemplates()

    /**
     * Send  Booking Sms.
     *
     * @param int $new_status new_status
     * @param int $old_status old_status
     * @param int $post       post
     *
     * @return void
     */
    public function sendBookingSms($new_status, $old_status, $post)
    {
        $bookingStatus = $post->post_status;
        if ($post->post_type == 'sln_booking' && $old_status != $new_status && $bookingStatus != 'draft') {
            if ($bookingStatus === 'sln-b-confirmed') {
                $this->setBookingReminder($post);
            }
            $id                 = $post->ID;
            $meta               = get_post_meta($post->ID);
            $buyerNumber        = current($meta['_sln_booking_phone']);
            $customerMessage    = smsalert_get_option('customer_sms_sln_body_'.$bookingStatus, 'smsalert_sln_message', ''); 
            $customerNotify        = smsalert_get_option('customer_sln_notify_'.$bookingStatus, 'smsalert_sln_general', 'on'); 
            if (($customerNotify === 'on' && $customerMessage !== '')) {
                $buyerMessage = $this->parseSmsBody($post, $customerMessage, $id);
                do_action('sa_send_sms', $buyerNumber, $buyerMessage);
            }

            // Send msg to admin.
            $adminPhoneNumber     = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

            if (empty($adminPhoneNumber) === false) {
                $adminNotify      = smsalert_get_option('admin_sln_notify_'.$bookingStatus, 'smsalert_sln_general', 'on');

                $adminMessage     = smsalert_get_option('admin_sms_sln_body_'.$bookingStatus, 'smsalert_sln_message', '');

                $nos              = explode(',', $adminPhoneNumber);
                $adminPhoneNumber = array_diff($nos, ['postauthor', 'post_author']);
                $adminPhoneNumber = implode(',', $adminPhoneNumber);

                if ($adminNotify === 'on' && $adminMessage !== '') {
                    $adminMessage = $this->parseSmsBody($post, $adminMessage, $id);
                    do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
                }
            }    
     
        }
    }    //end sendBookingSms()

    /**
     * Parse sms body.
     *
     * @param array  $post    post.
     * @param string $content content.
     * @param string $id      id.
     *
     * @return string
     */
    public function parseSmsBody($post, $content, $id)   { 
        if ($_REQUEST['action'] != 'salon') {
            global $post;
        }
        $meta = get_post_meta($id);    
        $firstName       = current($meta['_sln_booking_firstname']); 
        $lastName        = current($meta['_sln_booking_lastname']); 
        $email           = current($meta['_sln_booking_email']);
        $phone           = current($meta['_sln_booking_phone']);
        $amount          = current($meta['_sln_booking_amount']);
        $status           = $post->post_status; 
        $bookingDuration = current($meta['_sln_booking_duration']);        
        $bookingDate     = current($meta['_sln_booking_date']);
        $bookingTime     = current($meta['_sln_booking_time']);
        $bookingDateTime  = $bookingDate . ' ' . $bookingTime;

        $find = [
            '[firstname]',
            '[lastname]',
            '[email]',
            '[phone]',
            '[booking_amount]',
            '[booking_status]',
            '[booking_duration]',
            '[booking_date]',
            '[booking_id]',
        ];

        $replace = [
            $firstName,
            $lastName,
            $email,
            $phone,
            $amount,
            $status,
            $bookingDuration,
            $bookingDateTime,
			$id
        ];
        $content = str_replace($find, $replace, $content);
        return $content;
    }//end parseSmsBody()


    /**
     * Get booking calendar variables.
     *
     * @return array
     */
    public static function getSalonBookingvariables()
    {
        $variable['[firstname]']    = 'First Name ';
        $variable['[lastname]']     = 'Last Name';
        $variable['[email]']        = 'Email';
        $variable['[phone]']        = 'Phone';
        $variable['[booking_amount]']       = 'Total Amount';
        $variable['[booking_status]']       = 'Status';
        $variable['[booking_duration]']     = 'Duration';
        $variable['[booking_date]']         = 'Booking Date';
        $variable['[booking_id]']         = 'Booking Id';
        return $variable;

    }//end getBookitCalendarvariables()


    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('salon-booking-system/salon.php') === true) {
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
        if ((is_plugin_active('salon-booking-system/salon.php') === true) && ($islogged === true)) {
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

    }//end handle_failed_verification()


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

    }//end handle_post_verification()


    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {

    }//end unsetOTPSessionVariables()


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
    }//end is_ajax_form_in_play()
}
new Salonbooking();
