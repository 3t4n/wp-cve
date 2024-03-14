<?php

/**
 * Bookit calendar helper.
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

if (is_plugin_active('bookit/bookit.php') === false) {
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
 * BookitCalendar class
 */
class BookitCalendar extends FormInterface
{
    
    /**
     * Construct function.
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('bookit_appointment_created', [$this, 'sendsmsNewBookit'], 10, 1);
        add_action('bookit_appointment_status_changed', [$this, 'sendsmsBookitStatusUpdated'], 10, 1);
        add_action('booking_reminder_sendsms_hook', [$this, 'sendReminderSms'], 10);

    }//end handleForm()

    /**
     * Set booking reminder.
     *
     * @param int $appointment appointment.
     *
     * @return void
     */
    public static function setBookingReminder($appointment)
    {

        if (empty($appointment) === true) {
            return;
        }

        $bookingStatus = $appointment->status;
        $booking_id    = $appointment->id;

        $booking_start  = date('Y-m-d H:i:s', $appointment->start_time);
        $buyer_mob      = $appointment->customer_phone;
        $customerNotify = smsalert_get_option('customer_notify', 'smsalert_bcc_general', 'on');
        global $wpdb;
        $tableName       = $wpdb->prefix.'smsalert_booking_reminder';
        $source          = 'bookit-calendar';
        $booking_details = $wpdb->get_results("SELECT * FROM $tableName WHERE booking_id = $booking_id and source = '$source'");
        if ($bookingStatus === 'approved' && $customerNotify === 'on') {
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
        if (smsalert_get_option('customer_notify', 'smsalert_bcc_general') !== 'on') {
            return;
        }

        global $wpdb;
        $cronFrequency = BOOKING_REMINDER_CRON_INTERVAL;
        // pick data from previous CART_CRON_INTERVAL min
        $tableName     = $wpdb->prefix.'smsalert_booking_reminder';
        $source        = 'bookit-calendar';
        $schedulerData = get_option('smsalert_bcc_reminder_scheduler');

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
                        $appointments = \Bookit\Classes\Database\Appointments::get_full_appointment_by_id($data['booking_id']);

                        $obj[$key]['number']   = $data['phone'];
                        $obj[$key]['sms_body'] = self::parseBookitSmsBody($appointments, $customerMessage);
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
        $bookingStatuses = [
            'new',
            'pending',
            'approved',
            'cancelled',
        ];

        foreach ($bookingStatuses as $ks => $vs) {
            $defaults['smsalert_bcc_general']['customer_bcc_notify_'.$vs]   = 'off';
            $defaults['smsalert_bcc_message']['customer_sms_bcc_body_'.$vs] = '';
            $defaults['smsalert_bcc_general']['admin_bcc_notify_'.$vs]      = 'off';
            $defaults['smsalert_bcc_message']['admin_sms_bcc_body_'.$vs]    = '';
        }

        $defaults['smsalert_bcc_general']['otp_enable']      = 'off';
        $defaults['smsalert_bcc_general']['customer_notify'] = 'off';
        $defaults['smsalert_bcc_reminder_scheduler']['cron'][0]['frequency'] = '1';
        $defaults['smsalert_bcc_reminder_scheduler']['cron'][0]['message']   = '';
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
            'checkTemplateFor' => 'bcc_customer',
            'templates'        => self::getCustomerTemplates(),
        ];

        $adminParam = [
            'checkTemplateFor' => 'bcc_admin',
            'templates'        => self::getAdminTemplates(),
        ];

        $reminderParam = [
            'checkTemplateFor' => 'wc_bookit_calendar_reminder',
            'templates'        => self::getReminderTemplates(),
        ];

        $tabs['bookit_calendar']['nav']  = 'Bookit Calendar';
        $tabs['bookit_calendar']['icon'] = 'dashicons-calendar-alt';

        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_cust']['title']        = 'Customer Notifications';
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_cust']['tab_section']  = 'bookitcusttemplates';
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_cust']['first_active'] = true;
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_cust']['tabContent']   = $customerParam;
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_cust']['filePath']     = 'views/message-template.php';

        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_admin']['title']          = 'Admin Notifications';
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_admin']['tab_section']    = 'bookitcalendaradmintemplates';
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_admin']['tabContent']     = $adminParam;
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_admin']['filePath']       = 'views/message-template.php';
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_reminder']['title']       = 'Booking Reminder';
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_reminder']['tab_section'] = 'bookingremindertemplates';
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_reminder']['tabContent']  = $reminderParam;
        $tabs['bookit_calendar']['inner_nav']['bookit_calendar_reminder']['filePath']    = 'views/booking-reminder-template.php';

        $tabs['bookit_calendar']['help_links'] = [
            'kb_link'      => [
                'href'   => 'https://kb.smsalert.co.in/knowledgebase/bookit-calendar-sms-integration/',
                'target' => '_blank',
                'alt'    => 'Read how to integrate with Bookit Calendar',
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
        $currentVal     = smsalert_get_option('customer_notify', 'smsalert_bcc_general', 'on');
        $checkboxMameId = 'smsalert_bcc_general[customer_notify]';

        $schedulerData = get_option('smsalert_bcc_reminder_scheduler');
        $templates     = [];
        $count         = 0;
        if (empty($schedulerData) === true) {
			$schedulerData  = array();
            $schedulerData['cron'][] = [
                'frequency' => '1',
                'message'   => sprintf(__('Hello %1$s, your booking %2$s with %3$s is fixed on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[customer_name]', '#[id]', '[store_name]', '[start_time]', PHP_EOL, PHP_EOL),
            ];
        }

        foreach ($schedulerData['cron'] as $key => $data) {
            $textAreaNameId = 'smsalert_bcc_reminder_scheduler[cron]['.$count.'][message]';
            $selectNameId   = 'smsalert_bcc_reminder_scheduler[cron]['.$count.'][frequency]';
            $textBody       = $data['message'];

            $templates[$key]['notify_id']      = 'bookit-calendar';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']          = 'Send bookit reminder to customer';
            $templates[$key]['checkboxNameId'] = $checkboxMameId;
            $templates[$key]['text-body']      = $textBody;
            $templates[$key]['textareaNameId'] = $textAreaNameId;
            $templates[$key]['selectNameId']   = $selectNameId;
            $templates[$key]['token']          = self::getBookitCalendarvariables();

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
        $bookingStatuses = [
            '[pending]'  => 'Pending',
            '[approved]' => 'Approved',
            '[trash]'    => 'Cancelled',
        ];

        $templates = [];
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal = smsalert_get_option('customer_bcc_notify_'.strtolower($vs), 'smsalert_bcc_general', 'on');

            $checkboxMameId = 'smsalert_bcc_general[customer_bcc_notify_'.strtolower($vs).']';
            $textareaNameId = 'smsalert_bcc_message[customer_sms_bcc_body_'.strtolower($vs).']';

            $defaultTemplate = sprintf(__('Hello %1$s, status of your booking #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[customer_name]', '[id]', '[store_name]', $vs, PHP_EOL, PHP_EOL);

            $textBody = smsalert_get_option('customer_sms_bcc_body_'.strtolower($vs), 'smsalert_bcc_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When customer booking is '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxMameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getBookitCalendarvariables();
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
        $bookingStatuses = [
        // '[new]'       => 'New',
            '[pending]'  => 'Pending',
            '[approved]' => 'Approved',
            '[trash]'    => 'Cancelled',
        ];

        $templates = [];
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal     = smsalert_get_option('admin_bcc_notify_'.strtolower($vs), 'smsalert_bcc_general', 'on');
            $checkboxMameId = 'smsalert_bcc_general[admin_bcc_notify_'.strtolower($vs).']';
            $textareaNameId = 'smsalert_bcc_message[admin_sms_bcc_body_'.strtolower($vs).']';

            $defaultTemplate = sprintf(__('Hello admin, status of your booking with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', $vs, PHP_EOL, PHP_EOL);

            $textBody = smsalert_get_option('admin_sms_bcc_body_'.strtolower($vs), 'smsalert_bcc_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When admin change status to '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxMameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getBookitCalendarvariables();
        }//end foreach

        return $templates;

    }//end getAdminTemplates()


    /**
     * Send sms new booking.
     *
     * @param int $app_id app id
     *
     * @return void
     */
    public function sendsmsNewBookit($app_id)
    {
        $appointment       = \Bookit\Classes\Database\Appointments::get_full_appointment_by_id($app_id);
        $buyerSmsData      = [];
        $buyerNumber       = $appointment->customer_phone;
        $customerMessage   = smsalert_get_option('customer_sms_bcc_body_pending', 'smsalert_bcc_message', '');
        $customerBccNotify = smsalert_get_option('customer_bcc_notify_pending', 'smsalert_bcc_general', 'on');

        if ($customerBccNotify === 'on' && $customerMessage !== '') {
            $buyerMessage = $this->parseBookitSmsBody($appointment, $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }

        // send msg to admin.
        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

        $nos = explode(',', $adminPhoneNumber);
        $adminPhoneNumber = array_diff($nos, ['postauthor', 'post_author']);
        $adminPhoneNumber = implode(',', $adminPhoneNumber);

        if (empty($adminPhoneNumber) === false) {
            $adminBccNotify = smsalert_get_option('admin_bcc_notify_pending', 'smsalert_bcc_general', 'on');
            $adminMessage   = smsalert_get_option('admin_sms_bcc_body_pending', 'smsalert_bcc_message', '');

            if ($adminBccNotify === 'on' && $adminMessage !== '') {
                $adminMessage = $this->parseBookitSmsBody($booking, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }

    }//end sendsmsNewBookit()


    /**
     * Send sms approved pending.
     *
     * @param int $app_id app_id
     *
     * @return void
     */
    public function sendsmsBookitStatusUpdated($app_id)
    {
        $appointment   = \Bookit\Classes\Database\Appointments::get_full_appointment_by_id($app_id);
        $buyerNumber   = $appointment->customer_phone;
        $bookingStatus = $appointment->status;

        $this->setBookingReminder($appointment);

        $customerMessage = smsalert_get_option('customer_sms_bcc_body_'.$bookingStatus, 'smsalert_bcc_message', '');

        $customerNotify = smsalert_get_option('customer_bcc_notify_'.$bookingStatus, 'smsalert_bcc_general', 'on');

        if (($customerNotify === 'on' && $customerMessage !== '')) {
            $buyerMessage = $this->parseBookitSmsBody($appointment, $customerMessage);

            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }

        // Send msg to admin.
        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

        if (empty($adminPhoneNumber) === false) {
            $adminNotify = smsalert_get_option('admin_bcc_notify_'.$bookingStatus, 'smsalert_bcc_general', 'on');

            $adminMessage = smsalert_get_option('admin_sms_bcc_body_'.$bookingStatus, 'smsalert_bcc_message', '');

            $nos = explode(',', $adminPhoneNumber);
            $adminPhoneNumber = array_diff($nos, ['postauthor', 'post_author']);
            $adminPhoneNumber = implode(',', $adminPhoneNumber);

            if ($adminNotify === 'on' && $adminMessage !== '') {
                $adminMessage = $this->parseBookitSmsBody($appointment, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }

    }//end sendsmsBookitStatusUpdated()


    /**
     * Parse sms body.
     *
     * @param array  $data    data.
     * @param string $content content.
     *
     * @return string
     */
    public function parseBookitSmsBody($data=[], $content=null)
    {
        $customerName  = $data->customer_name;
        $customerPhone = $data->customer_phone;
        $customerEmail = $data->customer_email;
        $customerId    = $data->customer_id;
        $serviceId     = $data->service_id;
        $serviceName   = $data->service_name;
        $price         = $data->price;
        $status        = $data->status;
        $staffName     = $data->staff_name;
        $staffPhone    = $data->staff_phone;
        $total         = $data->total;
        $startTime     = date('M d,Y H:i', $data->start_time);
        $endTime       = date('M d,Y H:i', $data->end_time);
        $id            = $data->id;

        $find = [
            '[customer_name]',
            '[customer_phone]',
            '[customer_email]',
            '[customer_id]',
            '[service_id]',
            '[service_name]',
            '[price]',
            '[status]',
            '[staff_name]',
            '[staff_phone]',
            '[total]',
            '[start_time]',
            '[end_time]',
            '[id]',
        ];

        $replace = [
            $customerName,
            $customerPhone,
            $customerEmail,
            $customerId,
            $serviceId,
            $serviceName,
            $price,
            $status,
            $staffName,
            $staffPhone,
            $total,
            $startTime,
            $endTime,
            $id,
        ];

        $content = str_replace($find, $replace, $content);
        return $content;

    }//end parseBookitSmsBody()


    /**
     * Get booking calendar variables.
     *
     * @return array
     */
    public static function getBookitCalendarvariables()
    {

        $variable['[customer_name]']  = 'Customer Name ';
        $variable['[customer_phone]'] = 'Customer Phone';
        $variable['[customer_email]'] = 'Customer Email';
        $variable['[customer_id]']    = 'Customer Id';
        $variable['[service_id]']     = 'Service Id';
        $variable['[service_name]']   = 'Service Name';
        $variable['[price]']          = 'Price';
        $variable['[status]']         = 'Status';
        $variable['[staff_name]']     = 'Staff Name';
        $variable['[staff_phone]']    = 'Staff Phone';
        $variable['[total]']          = 'Total Amount';
        $variable['[start_time]']     = 'Start Time';
        $variable['[end_time]']       = 'End Time';
        $variable['[id]'] = 'Id';
        return $variable;

    }//end getBookitCalendarvariables()


    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('bookit/bookit.php') === true) {
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
        if ((is_plugin_active('bookit/bookit.php') === true) && ($islogged === true)) {
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


}//end class
new  BookitCalendar();
