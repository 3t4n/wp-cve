<?php

/**
 * Restaurant reservation helper.
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

if (is_plugin_active('restaurant-reservations/restaurant-reservations.php') === false) {
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
 * Restaurantreservation class 
 */
class Restaurantreservation extends FormInterface
{
    /**
     * Form Session Variable.
     *
     * @return stirng
     */
    private $form_session_var = FormSessionVars::WP_RES_RESERVATION;
    
    /**
     * 
     * Construct function.
     *
     * @return stirng
     */
    public function handleForm()
    {
        add_action('rtb_booking_form_after_fields', array($this, 'getFormField'), 20);
        add_action('booking_reminder_sendsms_hook', array($this, 'sendReminderSms'), 10);
        add_action('rtb_insert_booking', array($this, 'sendsmsNewBooking'));
        add_action('rtb_update_booking', array($this, 'sendsmsBookingUpdate'));
        add_filter('rtb_bookings_table_bulk_action', array($this, 'sendsmsBulkBookingUpdate'), 10, 3);
    }

    /**
     * Add Shortcode for OTP and Add additional js code to your script
     *
     * @return stirng
     * */
    public function getFormField()
    {
        if (smsalert_get_option('otp_enable', 'smsalert_rr_general') === 'on') {
            echo do_shortcode('[sa_verify phone_selector="#rtb-phone" submit_selector= ".rtb-form-submit button"]');
        }
    }

    /**
     * Set booking reminder.
     *
     * @param int $booking booking .
     *
     * @return stirng
     */
    public static function setBookingReminder($booking)
    {

        if (empty($booking) === true) {
            return;
        }
        $bookingStatus = $booking->post_status;
        $bookingId     = $booking->ID;
        $bookingStart  = date('Y-m-d H:i:s', strtotime($booking->date));
        $buyerMob      = $booking->phone;
        $customerNotify = smsalert_get_option('customer_notify', 'smsalert_rr_general', 'on');
        global $wpdb;
        $tableName           = $wpdb->prefix . 'smsalert_booking_reminder';
        $source = 'restaurant-reservation';
        $booking_details = $wpdb->get_results("SELECT * FROM $tableName WHERE booking_id = $bookingId and source = '$source'");
        if ($bookingStatus === 'confirmed' && $customerNotify === 'on') {
            if ($booking_details) {
                $wpdb->update(
                    $tableName,
                    array(
                        'start_date' => $bookingStart,
                        'phone' => $buyerMob
                    ),
                    array('booking_id' => $bookingId)
                );
            } else {
                $wpdb->insert(
                    $tableName,
                    array(
                        'booking_id'   => $bookingId,
                        'phone' => $buyerMob,
                        'source' => $source,
                        'start_date' => $bookingStart
                    )
                );
            }
        } else {
            $wpdb->delete($tableName, array('booking_id' => $bookingId));
        }
    }


    /**
     * Send sms function.
     *
     * @return void
     */
    function sendReminderSms()
    {
        if (smsalert_get_option('customer_notify', 'smsalert_rr_general') !== 'on') {
            return;
        }

        global $wpdb;
        $cronFrequency = BOOKING_REMINDER_CRON_INTERVAL; // pick data from previous CART_CRON_INTERVAL min
        $tableName     = $wpdb->prefix . 'smsalert_booking_reminder';
        $source        = 'restaurant-reservation';
        $schedulerData = get_option('smsalert_rr_reminder_scheduler');

        foreach ($schedulerData['cron'] as $sdata) {

            $datetime = current_time('mysql');

            $fromdate = date('Y-m-d H:i:s', strtotime('+' . ($sdata['frequency'] * 60 - $cronFrequency) . ' minutes', strtotime($datetime)));

            $todate   = date('Y-m-d H:i:s', strtotime('+' . $cronFrequency . ' minutes', strtotime($fromdate)));

            $rowsToPhone = $wpdb->get_results(
                'SELECT * FROM ' . $tableName . " WHERE start_date > '" . $fromdate . "' AND start_date <= '" . $todate . "' AND source = '$source' ",
                ARRAY_A
            );
            if ($rowsToPhone) { // If we have new rows in the database

                $customerMessage = $sdata['message'];
                $frequencyTime   = $sdata['frequency'];
                if ($customerMessage !== '' && $frequencyTime !==  0) {
                    include_once RTB_PLUGIN_DIR . '/includes/Booking.class.php';
                    $obj = array();
                    foreach ($rowsToPhone as $key => $data) {
                        $booking = new rtbBooking();
                        $booking->load_post($data['booking_id']);
                        $obj[$key]['number']    = $data['phone'];
                        $obj[$key]['sms_body']  = self::parseSmsBody($booking, $customerMessage);
                    }
                    $response    = SmsAlertcURLOTP::sendSmsXml($obj);
                    $responseArr = json_decode($response, true);
                    if (!empty($responseArr['status']) && 'success' === $responseArr['status'] ) {
                        foreach ($rowsToPhone as $data) {
                            $lastMsgCount = $data['msg_sent'];
                            $totalMsgSent = $lastMsgCount + 1;
                            $wpdb->update(
                                $tableName,
                                array(
                                    'msg_sent' => $totalMsgSent
                                ),
                                array('booking_id' => $data['booking_id'], 'source' => $source)
                            );
                        }
                    }
                } //end if
            } //end if
        } //end foreach
    } //end sendReminderSms()

   
    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public static function add_default_setting($defaults = array())
    {
        $bookingStatuses = array('pending', 'confirmed','closed');

        foreach ($bookingStatuses as $ks => $vs) {
            $defaults['smsalert_rr_general']['customer_rr_notify_' . $vs]   = 'off';
            $defaults['smsalert_rr_message']['customer_sms_rr_body_' . $vs] = '';
            $defaults['smsalert_rr_general']['admin_rr_notify_' . $vs]      = 'off';
            $defaults['smsalert_rr_message']['admin_sms_rr_body_' . $vs]    = '';
        }
        $defaults['smsalert_rr_general']['otp_enable'] = 'off';
        $defaults['smsalert_rr_general']['customer_notify'] = 'off';
        $defaults['smsalert_rr_reminder_scheduler']['cron'][0]['frequency'] = '1';
        $defaults['smsalert_rr_reminder_scheduler']['cron'][0]['message']   = '';
        return $defaults;

    }//end add_default_setting()


    /**
     * Add tabs to smsalert settings at backend.
     *
     * @param array $tabs tabs.
     *
     * @return array
     */
    public static function addTabs($tabs= array())
    {
        $customerParam = array(
            'checkTemplateFor' => 'rr_customer',
            'templates'        => self::getCustomerTemplates(),
        );

        $admin_param = array(
            'checkTemplateFor' => 'rr_admin',
            'templates'        => self::getAdminTemplates(),
        );

        $reminderParam = array(
            'checkTemplateFor' => 'wc_restauran_reservation_reminder',
            'templates'        => self::getReminderTemplates(),
        );

        $tabs['restauran_reservation']['nav']  = 'Restaurant Reservations';
        $tabs['restauran_reservation']['icon'] = 'dashicons-food';

        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_cust']['title']        = 'Customer Notifications';
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_cust']['tab_section']  = 'restaurantreservationcusttemplates';
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_cust']['first_active'] = true;
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_cust']['tabContent']   = $customerParam;
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_cust']['filePath']     = 'views/message-template.php';

        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_admin']['title']       = 'Admin Notifications';
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_admin']['tab_section'] = 'restaurantreservationadmintemplates';
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_admin']['tabContent']  = $admin_param;
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_admin']['filePath']    = 'views/message-template.php';
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_reminder']['title']       = 'Booking Reminder';
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_reminder']['tab_section'] = 'bookingremindertemplates';
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_reminder']['tabContent']  = $reminderParam;
        $tabs['restauran_reservation']['inner_nav']['restauran_reservation_reminder']['filePath']    = 'views/booking-reminder-template.php';

        $tabs['restauran_reservation']['help_links'] = [
            /* 'youtube_link' => [
                'href'   => 'https://youtu.be/4BXd_XZt9zM',
                'target' => '_blank',
                'alt'    => 'Watch steps on Youtube',
                'class'  => 'btn-outline',
                'label'  => 'Youtube',
                'icon'   => '<span class="dashicons dashicons-video-alt3" style="font-size: 21px;"></span> ',

            ], */
            'kb_link'      => [
                'href'   => 'https://kb.smsalert.co.in/knowledgebase/restaurantreservation-sms-integration/',
                'target' => '_blank',
                'alt'    => 'Read how to integrate with restaurant reservation',
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
        $currentVal      = smsalert_get_option('customer_notify', 'smsalert_rr_general', 'on');
        $checkboxNameId  = 'smsalert_rr_general[customer_notify]';

        $schedulerData  = get_option('smsalert_rr_reminder_scheduler');
        $templates      = array();
        $count          = 0;
        if (empty($schedulerData) === true) {
			$schedulerData  = array();
            $schedulerData['cron'][] = array(
                'frequency' => '1',
                'message'   => sprintf(__('Hello %1$s, your booking %2$s with %3$s is fixed on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[name]', '#[booking_id]', '[store_name]', '[date]', PHP_EOL, PHP_EOL),
            );
        }
        foreach ($schedulerData['cron'] as $key => $data) {

            $textAreaNameId = 'smsalert_rr_reminder_scheduler[cron][' . $count . '][message]';
            $selectNameId    = 'smsalert_rr_reminder_scheduler[cron][' . $count . '][frequency]';
            $textBody         = $data['message'];

            $templates[$key]['notify_id']      = 'restaurant-reservation';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']          = 'Send booking reminder to customer';
            $templates[$key]['checkboxNameId'] = $checkboxNameId;
            $templates[$key]['text-body']      = $textBody;
            $templates[$key]['textareaNameId'] = $textAreaNameId;
            $templates[$key]['selectNameId']   = $selectNameId;
            $templates[$key]['token']          = self::getRestauranReservationvariables();

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
        $bookingStatuses = array(
            '[pending]'  => 'Pending',
            '[confirmed]' => 'Confirmed',
            '[closed]'    => 'Closed',
        );

        $templates = array();
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal = smsalert_get_option('customer_rr_notify_' . strtolower($vs), 'smsalert_rr_general', 'on');

            $checkboxNameId = 'smsalert_rr_general[customer_rr_notify_' . strtolower($vs) . ']';
            $textareaNameId = 'smsalert_rr_message[customer_sms_rr_body_' . strtolower($vs) . ']';

            $defaultTemplate = smsalert_get_option('admin_sms_rr_body_' . strtolower($vs), 'smsalert_rr_message', sprintf(__('Hello %1$s, status of your booking #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[name]', '[booking_id]', '[store_name]', $vs, PHP_EOL, PHP_EOL));

            $textBody = smsalert_get_option('customer_sms_rr_body_' . strtolower($vs), 'smsalert_rr_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When customer booking is ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getRestauranReservationvariables();
        }
        return $templates;
    }//end getCustomerTemplates()

    /**
     * Get admin templates.
     *
     * @return array
     */
    public static function getAdminTemplates()
    {
        $bookingStatuses = array(
            '[pending]'  => 'Pending',
            '[confirmed]' => 'Confirmed',
            '[closed]'    => 'Closed',
        );

        $templates = array();
        foreach ($bookingStatuses as $ks  => $vs) {

            $currentVal     = smsalert_get_option('admin_rr_notify_' . strtolower($vs), 'smsalert_rr_general', 'on');
            $checkboxNameId = 'smsalert_rr_general[admin_rr_notify_' . strtolower($vs) . ']';
            $textareaNameId = 'smsalert_rr_message[admin_sms_rr_body_' . strtolower($vs) . ']';

            $defaultTemplate = smsalert_get_option('admin_sms_rr_body_' . strtolower($vs), 'smsalert_rr_message', sprintf(__('Hello admin, status of your booking with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', $vs, PHP_EOL, PHP_EOL));


            $textBody = smsalert_get_option('admin_sms_rr_body_' . strtolower($vs), 'smsalert_rr_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When admin change status to ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getRestauranReservationvariables();
        }
        return $templates;
    }

    /**
     * Send sms new booking.
     *
     * @param int $booking booking
     *
     * @return void
     */
    public function sendsmsNewBooking($booking)
    {
        $buyerNumber   = $booking->phone;
        $buyerSmsData = array();
        $customerMessage  = smsalert_get_option('customer_sms_rr_body_pending', 'smsalert_rr_message', '');
        $customerRrNotify = smsalert_get_option('customer_rr_notify_pending', 'smsalert_rr_general', 'on');

        if ($customerRrNotify === 'on' && $customerMessage !== '') {
            $buyerMessage = $this->parseSmsBody($booking, $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }

        // Send msg to admin.
        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

        $nos                = explode(',', $adminPhoneNumber);
        $adminPhoneNumber = array_diff($nos, array('postauthor', 'post_author'));
        $adminPhoneNumber = implode(',', $adminPhoneNumber);

        if (empty($adminPhoneNumber) === false) {

            $adminRrNotify = smsalert_get_option('admin_rr_notify_pending', 'smsalert_rr_general', 'on');
            $adminMessage   = smsalert_get_option('admin_sms_rr_body_pending', 'smsalert_rr_message', '');

            if ('on' === $adminRrNotify && '' !== $adminMessage) {
                $adminMessage = $this->parseSmsBody($booking, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }
    }
    
    /**
     * Send sms approved pending.
     *
     * @param array  $results results
     * @param int    $id      id
     * @param string $action  action
     *
     * @return void
     */
    public function sendsmsBulkBookingUpdate($results, $id, $action)
    {
        include_once RTB_PLUGIN_DIR . '/includes/Booking.class.php';
        $booking = new rtbBooking();
        $booking->load_post($id);
        $this->sendsmsBookingUpdate($booking);
    }

    /**
     * Send sms approved pending.
     *
     * @param int $booking booking
     *
     * @return void
     */
    public function sendsmsBookingUpdate($booking)
    {
        $buyerNumber   = $booking->phone;
        $bookingStatus   = $booking->post_status;
        $this->setBookingReminder($booking);
        $customerMessage = smsalert_get_option('customer_sms_rr_body_' . $bookingStatus, 'smsalert_rr_message', '');
        $customerNotify = smsalert_get_option('customer_rr_notify_' . $bookingStatus, 'smsalert_rr_general', 'on');
        if (($customerNotify === 'on' && $customerMessage !== '')) {
            $buyerMessage = $this->parseSmsBody($booking, $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }

        // Send msg to admin.
        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

        if (empty($adminPhoneNumber) === false) {

            $adminNotify  = smsalert_get_option('admin_rr_notify_' . $bookingStatus, 'smsalert_rr_general', 'on');

            $adminMessage = smsalert_get_option('admin_sms_rr_body_' . $bookingStatus, 'smsalert_rr_message', '');

            $nos = explode(',', $adminPhoneNumber);
            $adminPhoneNumber = array_diff($nos, array('postauthor', 'post_author'));
            $adminPhoneNumber = implode(',', $adminPhoneNumber);

            if ($adminNotify === 'on' && $adminMessage !== '') {
                $adminMessage = $this->parseSmsBody($booking, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }
    }//end sendsmsBookingUpdate()

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
        $bookingId    = $data->ID;
        $name         = $data->name;
        $email        = $data->email;
        $phone        = $data->phone;
        $requestTime  = $data->request_time;
		
        $requestDate  = $data->request_date;
        $date         = date('M d,Y H:i', strtotime($data->date));
        $party        = $data->party;
        $postStatus   = $data->post_status;

        $find = array(
            '[booking_id]',
            '[name]',
            '[email]',
            '[phone]',
            '[request_time]',
            '[request_date]',
            '[date]',
            '[party]',
            '[post_status]'
        );

        $replace = array(
            $bookingId,
            $name,
            $email,
            $phone,
            $requestTime,
            $requestDate,
            $date,
            $party,
            $postStatus
        );
        $content = str_replace($find, $replace, $content);
        return $content;
    }//end parseSmsBody()


    /**
     * Get Restaurant Reservations variables.
     *
     * @return array
     */
    public static function getRestauranReservationvariables()
    {
        $variable['[booking_id]']   = 'Booking Id';
        $variable['[date]']         = 'Booking Date';
        $variable['[request_date]'] = 'Request Date';
        $variable['[request_time]'] = 'Request Time';
        $variable['[name]']         = 'Name';
        $variable['[party]']        = 'Party';
        $variable['[email]']        = 'Email';
        $variable['[phone]']        = 'Phone';
        $variable['[post_status]']  = 'Post Status';
        return $variable;
    }//end

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('restaurant-reservations/restaurant-reservations.php') === true) {
            add_filter('sAlertDefaultSettings', __CLASS__ . '::add_default_setting', 1);
            add_action('sa_addTabs', array($this, 'addTabs'), 10);
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
        if ((is_plugin_active('restaurant-reservations/restaurant-reservations.php') === true) && ($islogged === true)) {
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
        if (isset($_SESSION[$this->form_session_var]) === false) {
            return;
        }
        if ((empty($_REQUEST['option']) === false) && sanitize_text_field(wp_unslash($_REQUEST['option'])) === 'smsalert-validate-otp-form') {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
            exit();
        } else {
            $_SESSION[$this->form_session_var] = 'verification_failed';
        }

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
    }//end handle_post_verification()


    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {
        unset($_SESSION[$this->form_session_var]);

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
        SmsAlertUtility::checkSession();
        if ($_SESSION[$this->form_session_var] === true) {
            return true;
        } else {
            return $isAjax;
        }

    }//end is_ajax_form_in_play()


}//end class
new Restaurantreservation();
