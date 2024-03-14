<?php

/**
 * Fat Service Booking helper.
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

if (is_plugin_active('fat-services-booking/fat-services-booking.php') === false) {
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
 * FatServiceBooking class 
 */
class FatServiceBooking extends FormInterface
{

 

    public static $b_status = array(
    '0' => 'pending',
    '1' => 'approved',
    '2' => 'canceled',
    '3' => 'rejected',
    );    
    
    
    
    /**
     * Construct function.
     *
     * @return stirng
     */
    public function handleForm()
    {
        
        add_action('fat_after_add_booking', array($this, 'sendSmsOn'), 10, 2);
        add_action('fat_after_update_booking_status', array($this, 'sendsmsBookingUpdate'), 10, 2);
        add_action('booking_reminder_sendsms_hook', array( $this, 'sendReminderSms' ), 10);        
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
        $bookingStatus = self::$b_status[$booking->b_process_status];
        $bookingId     = $booking->b_id;
        $bookingDate          = $booking->b_date;
        $bookingTime          = date("H:i", mktime(0, $booking->b_time));        
        $bookingStart    = $bookingDate . ' ' . $bookingTime;         
        $buyerMob      = $booking->c_phone;
        $customerNotify = smsalert_get_option('customer_notify', 'smsalert_fsb_general', 'on');
        global $wpdb;
        $tableName           = $wpdb->prefix . 'smsalert_booking_reminder';
        $source = 'fat_services_booking';
        $booking_details = $wpdb->get_results("SELECT * FROM $tableName WHERE booking_id = $bookingId and source = '$source'");
        if ($bookingStatus === 'approved' && $customerNotify === 'on') {
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
     * Send reminder sms function.
     *
     * @return void
     */
    function sendReminderSms()
    {
        if (smsalert_get_option('customer_notify', 'smsalert_fsb_general') !== 'on') {
            return;
        }
        global $wpdb;
        $cronFrequency = BOOKING_REMINDER_CRON_INTERVAL; // pick data from previous CART_CRON_INTERVAL min
        $tableName     = $wpdb->prefix . 'smsalert_booking_reminder';
        
        $source        = 'fat_services_booking';
        $schedulerData = get_option('smsalert_fsb_reminder_scheduler');

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
                    $obj = array();
                    foreach ($rowsToPhone as $key => $data) {                    
                        //$b_id = $data['booking_id'];
                        $booking = $this->get_booking_by_id($data['booking_id']);
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
    }  //end sendReminderSms()

   
    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public static function add_default_setting($defaults = array())
    {
        $bookingStatuses = array('Pending', 'Canceled','Approved','Rejected');

        foreach ($bookingStatuses as $ks => $vs) {
            $defaults['smsalert_fsb_general']['customer_fsb_notify_' . $vs]   = 'off';
            $defaults['smsalert_fsb_message']['customer_sms_fsb_body_' . $vs] = '';
            $defaults['smsalert_fsb_general']['admin_fsb_notify_' . $vs]      = 'off';
            $defaults['smsalert_fsb_message']['admin_sms_fsb_body_' . $vs]    = '';
        }
        $defaults['smsalert_fsb_reminder_scheduler']['cron'][0]['frequency'] = '1';
        $defaults['smsalert_fsb_reminder_scheduler']['cron'][0]['message']   = '';
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
            'checkTemplateFor' => 'fsb_customer',
            'templates'        => self::getCustomerTemplates(),
        );

        $admin_param = array(
            'checkTemplateFor' => 'fsb_admin',
            'templates'        => self::getAdminTemplates(),
        );

        $reminderParam = array(
            'checkTemplateFor' => 'wc_fatservices_booking_reminder',
            'templates'        => self::getReminderTemplates(),
        );

        $tabs['fatservices_booking']['nav']  = 'FAT Services Booking';
        $tabs['fatservices_booking']['icon'] = 'dashicons-food';

        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_cust']['title']        = 'Customer Notifications';
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_cust']['tab_section']  = 'fatservicesbookingcusttemplates';
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_cust']['first_active'] = true;
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_cust']['tabContent']   = $customerParam;
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_cust']['filePath']     = 'views/message-template.php';

        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_admin']['title']       = 'Admin Notifications';
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_admin']['tab_section'] = 'fatservicesbookingadmintemplates';
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_admin']['tabContent']  = $admin_param;
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_admin']['filePath']    = 'views/message-template.php';
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_reminder']['title']       = 'Booking Reminder';
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_reminder']['tab_section'] = 'bookingremindertemplates';
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_reminder']['tabContent']  = $reminderParam;
        $tabs['fatservices_booking']['inner_nav']['fatservices_booking_reminder']['filePath']    = 'views/booking-reminder-template.php';

        $tabs['fatservices_booking']['help_links'] = [
            'kb_link'      => [
                'href'   => 'https://kb.smsalert.co.in/knowledgebase/fat-services-booking-sms-integration/',
                'target' => '_blank',
                'alt'    => 'Read how to integrate with Fat Services Booking',
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
        $currentVal      = smsalert_get_option('customer_notify', 'smsalert_fsb_general', 'on');
        $checkboxNameId  = 'smsalert_fsb_general[customer_notify]';
        $schedulerData  = get_option('smsalert_fsb_reminder_scheduler');
        $templates      = array();
        $count          = 0;
        if (empty($schedulerData) === true) {
			$schedulerData  = array();
            $schedulerData['cron'][] = array(
                'frequency' => '1',
                'message'   => sprintf(__('Hello %1$s, your booking %2$s with %3$s is fixed on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[c_first_name]', '#[b_id]', '[store_name]', '[b_date]', PHP_EOL, PHP_EOL),
            );
        }
        foreach ($schedulerData['cron'] as $key => $data) {
            $textAreaNameId = 'smsalert_fsb_reminder_scheduler[cron][' . $count . '][message]';
            $selectNameId    = 'smsalert_fsb_reminder_scheduler[cron][' . $count . '][frequency]';
            $textBody         = $data['message'];
            $templates[$key]['notify_id']      = 'restaurant-reservation';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']          = 'Send booking reminder to customer';
            $templates[$key]['checkboxNameId'] = $checkboxNameId;
            $templates[$key]['text-body']      = $textBody;
            $templates[$key]['textareaNameId'] = $textAreaNameId;
            $templates[$key]['selectNameId']   = $selectNameId;
            $templates[$key]['token']          = self::getFatServiceBookingvariables();
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
            '[canceled]' => 'Canceled',
            '[approved]'    => 'Approved',
            '[rejected]'    => 'Rejected',
        );

        $templates = array();
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal = smsalert_get_option('customer_fsb_notify_' . strtolower($vs), 'smsalert_fsb_general', 'on');
            $checkboxNameId = 'smsalert_fsb_general[customer_fsb_notify_' . strtolower($vs) . ']';
            $textareaNameId = 'smsalert_fsb_message[customer_sms_fsb_body_' . strtolower($vs) . ']';
            $defaultTemplate = smsalert_get_option('admin_sms_fsb_body_' . strtolower($vs), 'smsalert_fsb_message', sprintf(__('Hello %1$s, status of your booking #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[c_first_name]', '[b_id]', '[store_name]', strtolower($vs), PHP_EOL, PHP_EOL));
            $textBody = smsalert_get_option('customer_sms_fsb_body_' . strtolower($vs), 'smsalert_fsb_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When customer booking is ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getFatServiceBookingvariables();
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
            '[canceled]' => 'Canceled',
            '[approved]'    => 'Approved',
            '[rejected]'    => 'Rejected',
        );
        $templates = array();
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal     = smsalert_get_option('admin_fsb_notify_' . strtolower($vs), 'smsalert_fsb_general', 'on');
            $checkboxNameId = 'smsalert_fsb_general[admin_fsb_notify_' . strtolower($vs) . ']';
            $textareaNameId = 'smsalert_fsb_message[admin_sms_fsb_body_' . strtolower($vs) . ']';
            $defaultTemplate = smsalert_get_option('admin_sms_fsb_body_' . strtolower($vs), 'smsalert_fsb_message', sprintf(__('Hello admin, status of your booking with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', strtolower($vs), PHP_EOL, PHP_EOL));
            $textBody = smsalert_get_option('admin_sms_fsb_body_' . strtolower($vs), 'smsalert_fsb_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When admin change status to ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getFatServiceBookingvariables();
        }
        return $templates;
    }

    /**
     * Get booking by id.
     *
     * @param int $booking_id booking id
     *
     * @return void
     */   
    public function get_booking_by_id($booking_id)
    {
        // code
        global $wpdb;
        $sql = "SELECT *
                FROM {$wpdb->prefix}fat_sb_booking
                INNER JOIN {$wpdb->prefix}fat_sb_customers
                    ON {$wpdb->prefix}fat_sb_booking.b_customer_id = {$wpdb->prefix}fat_sb_customers.c_id
                    AND {$wpdb->prefix}fat_sb_booking.b_id=%d
                INNER JOIN {$wpdb->prefix}fat_sb_employees
                    ON {$wpdb->prefix}fat_sb_booking.b_employee_id = {$wpdb->prefix}fat_sb_employees.e_id
                INNER JOIN {$wpdb->prefix}fat_sb_locations
                    ON {$wpdb->prefix}fat_sb_booking.b_loc_id = {$wpdb->prefix}fat_sb_locations.loc_id
                INNER JOIN {$wpdb->prefix}fat_sb_services
                    ON {$wpdb->prefix}fat_sb_booking.b_service_id = {$wpdb->prefix}fat_sb_services.s_id
                ";
        $sql = $wpdb->prepare($sql, $booking_id);
        $booking = $wpdb->get_results($sql);
        if (count($booking) > 0) { 
            return $booking[0];
        } else {
            return array();
        }
    }
    
    /**
     * Send sms approved pending.
     *
     * @param array $b_id   b_id
     * @param int   $status status
     *
     * @return void
     */
    public function sendsmsBookingUpdate($b_id, $status)
    {        
        $booking = $this->get_booking_by_id($b_id);
        $this->sendSmsOn($b_id, $booking);        
    }

    /**
     * Send sms approved pending.
     *
     * @param int $booking_id booking_id
     * @param int $booking    booking
     *
     * @return void
     */
    public function sendSmsOn($booking_id, $booking)
    {
        $booking = $this->get_booking_by_id($booking_id);
        $this->setBookingReminder($booking);
        $buyerNumber   = $booking->c_phone;    
        
        $bookingStatus   = self::$b_status[$booking->b_process_status];      
        $customerMessage = smsalert_get_option('customer_sms_fsb_body_' . $bookingStatus, 'smsalert_fsb_message', '');        
        $customerNotify = smsalert_get_option('customer_fsb_notify_' . $bookingStatus, 'smsalert_fsb_general', 'on');
        if (($customerNotify === 'on' && $customerMessage !== '')) {
            $buyerMessage = $this->parseSmsBody($booking, $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }
        // Send msg to admin.
         $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

        if (empty($adminPhoneNumber) === false) {

            $adminNotify  = smsalert_get_option('admin_fsb_notify_' . $bookingStatus, 'smsalert_fsb_general', 'on');

            $adminMessage = smsalert_get_option('admin_sms_fsb_body_' . $bookingStatus, 'smsalert_fsb_message', '');

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
     * @param array  $booking booking.
     * @param string $content content.
     *
     * @return string
     */
    public function parseSmsBody($booking, $content = null)
    {    
        $bookibgId            = $booking->b_id;        
        $bookingDate          = $booking->b_date;
        $bookingTime           = date("H:i", mktime(0, $booking->b_time));
        $bookingTotalpay      = $booking->b_total_pay;
        $bookingDescription   = $booking->b_description;
        $bookingSatus         = $this->b_status[$booking->b_process_status];
        $bookingCoupon        = $booking->b_coupon_code;
        $bookingDiscount      = $booking->b_discount;
        $cst_Id               = $booking->c_id;
        $cst_firstName        = $booking->c_first_name;
        $cst_lastName         = $booking->c_last_name;
        $cst_gender           = $data->c_gender;
        $cst_phone            = $booking->c_phone;
        $cst_email            = $booking->c_email;
        $cst_dob              = $booking->c_dob;
        $cst_description      = $booking->c_description;
        $price                = $booking->s_price;       
        $service_duration     = $booking->s_duration;
        $service_minimumPerson     = $booking->s_minimum_person;
        $service_link              = $booking->s_link;
        $emp_id                    = $booking->e_id;
        $emp_firstName             = $booking->e_first_name;
        $emp_lastName              = $booking->e_last_name;
        $emp_phone                 = $booking->e_phone;
        $emp_email                 = $booking->e_email;
        $emp_description           = $booking->e_description;
        $loc_id                    = $booking->loc_id;
        $loc_name                  = $booking->loc_name;
        $loc_address               = $booking->loc_address;
        $loc_link                  = $booking->loc_link;
        $loc_description          = $booking->loc_description;
        $find = array(           
                '[b_id]',
                '[b_date]',
                '[b_time]',
                '[b_total_pay]',
                '[b_description]',
                '[b_process_status]',
                '[b_coupon_code]',
                '[b_discount]',
                '[c_id]',
                '[c_first_name]',
                '[c_last_name]',
                '[c_gender]',
                '[c_phone]',
                '[c_email]',
                '[c_dob]',
                '[c_description]',
                '[s_id]',
                '[s_name]',
                '[s_description]',
                '[s_price]',
                '[s_duration]',
                '[s_minimum_person]',
                '[s_link]',
                '[e_id]',
                '[e_first_name]',
                '[e_last_name]',
                '[e_phone]',
                '[e_email]',
                '[e_description]',
                '[loc_id]',
                '[loc_name]',
                '[loc_address]',
                '[loc_link]',
                '[loc_description]',
        );

        $replace = array(
            $bookibgId,
        $bookingDate,
        $bookingTime,
        $bookingTotalpay,
        $bookingDescription,
        $bookingSatus,
        $bookingCoupon,
        $bookingDiscount,
        $cst_Id,
        $cst_firstName,
        $cst_lastName,
        $cst_gender,
        $cst_phone,
        $cst_email,
        $cst_dob,
        $cst_description,
        $price,       
        $service_duration,
        $service_minimumPerson,
        $service_link,
        $emp_id,
        $emp_firstName,
        $emp_lastName,
        $emp_phone,
        $emp_email,
        $emp_description,
        $loc_id,
        $loc_name,
        $loc_address,
        $loc_link,
        $loc_description,
        );        
        $content = str_replace($find, $replace, $content);
        return $content;
    }//end parseSmsBody()


    /**
     * Get Fat Service Bookin variables.
     *
     * @return array
     */
    public static function getFatServiceBookingvariables()
    {
        $variable['[b_id]']               = 'Booking Id';
        $variable['[b_date]']             = 'Booking Date';
        $variable['[b_time]']             = 'Booking Time';
        $variable['[b_total_pay]']        = 'Total Pay';
        $variable['[b_description]']      = 'Booking Description';
        $variable['[b_process_status]']   = 'Booking Status';
        $variable['[b_coupon_code]']      = 'Coupon Code';
        $variable['[b_discount]']         = 'Booking Discount';
        $variable['[c_id]']               = 'Customer Id';
        $variable['[c_first_name]']       = 'Customer First Name';
        $variable['[c_last_name]']        = 'Customer Last Name';
        $variable['[c_gender]']           = 'Customer Gender';
        $variable['[c_phone]']            = 'Customer Phone';
        $variable['[c_email]']            = 'Customer Email';
        $variable['[c_dob]']              = 'Customer Booking Date';
        $variable['[c_description]']      = 'Customer Description';
        $variable['[s_id]']               = 'Service Id';
        $variable['[s_name]']             = 'Service Name';
        $variable['[s_description]']      = 'Service Description';
        $variable['[s_price]']            = 'Service Price';
        $variable['[s_duration]']         = 'Service Duration';
        $variable['[s_minimum_person]']   = 'Service Minmum Persion';
        $variable['[s_link]']             = 'Service Link';
        $variable['[e_id]']               = 'Employee Id';
        $variable['[e_first_name]']       = 'Employee First Name';
        $variable['[e_last_name]']        = 'Employee Last Name';
        $variable['[e_phone]']            = 'Employee Phone';
        $variable['[e_email]']            = 'Employee Email';
        $variable['[e_description]']      = 'Employee Description';
        $variable['[loc_id]']             = 'Location Id';
        $variable['[loc_name]']           = 'Location Name';
        $variable['[loc_address]']        = 'Location Address';
        $variable['[loc_link]']           = 'Location Link';
        $variable['[loc_description]']    = 'Location Description';
           
        return $variable;
    }//end

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('fat-services-booking/fat-services-booking.php') === true) {
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
        if ((is_plugin_active('fat-services-booking/fat-services-booking.php') === true) && ($islogged === true)) {
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
new FatServiceBooking();
