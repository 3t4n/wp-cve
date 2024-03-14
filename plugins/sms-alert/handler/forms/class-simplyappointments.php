<?php

/**
 * Simply Appoinments booking helper.
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
if (is_plugin_active('simply-schedule-appointments/simply-schedule-appointments.php') === false) {
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
 * SimplyAppoinments class
 */
class SimplyAppoinments extends FormInterface
{
   
    /**
     * Construct function.
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('ssa/appointment/booked', [$this, 'sendsmsNewBooking'], 10, 4);
        add_action('ssa/appointment/canceled', [$this, 'sendBookingUpdate'], 10, 4);        
        add_action('ssa/appointment/abandoned', [$this, 'sendBookingUpdate'], 10, 4);        
        add_action('ssa/appointment/pending', [$this, 'sendBookingUpdate'], 10, 4);        
        add_action('booking_reminder_sendsms_hook', [$this, 'sendReminderSms'], 10);

    }//end handleForm()    
   

    /**
     * Set booking reminder.
     *
     * @param int $appointment_id   appointment_id.
     * @param int $form_data        form_data.
     * @param int $appoinmentStatus appoinmentStatus.
     *
     * @return void
     */
    public static function setBookingReminder($appointment_id,$form_data,$appoinmentStatus)
    {
        
        if (empty($form_data) === true) {
            return;
        }       
        $bookingStatus      = $appoinmentStatus;
        $buyer_mob          = $form_data['customer_information']['Phone'];;
        $booking_id         = $appointment_id;
        $booking_start      = $form_data['start_date'];;
        $customerNotify     = smsalert_get_option('customer_notify', 'smsalert_ssa_general', 'on');
        global $wpdb;
        $tableName          = $wpdb->prefix.'smsalert_booking_reminder';
        $source              = 'simply-appointments';
        $booking_details = $wpdb->get_results("SELECT * FROM $tableName WHERE booking_id = $booking_id and source = '$source'");
        if ($bookingStatus === 'booked' && $customerNotify === 'on') {
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
     * Send Reminder sms function.
     *
     * @return void
     */
    function sendReminderSms()
    {
        if (smsalert_get_option('customer_notify', 'smsalert_ssa_general') !== 'on') {
            return;
        }
        global $wpdb;
        $cronFrequency = BOOKING_REMINDER_CRON_INTERVAL;
        // pick data from previous CART_CRON_INTERVAL min
        $tableName     = $wpdb->prefix.'smsalert_booking_reminder';        
        $source        = 'simply-appointments';
        $schedulerData = get_option('smsalert_ssa_reminder_scheduler');

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
                        $appointment_id               = $data['booking_id'];
                        $obj[$key]['number']   = $data['phone'];
                        $table_name = $wpdb->prefix . 'ssa_appointments';                        
                        $form_data     = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id=".$appointment_id."", ARRAY_A);
						$appoinmentStatus = '';
                        if (!empty($form_data)) {        
                            $appoinmentStatus = $form_data['status'];
                        }
                        $obj[$key]['sms_body'] = self::parseSmsReminder($appointment_id, $form_data, $appoinmentStatus, $customerMessage);                        
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
        'booked',
        'canceled' ,
        'abandoned',
        'pending_payment',
        ]; 
        foreach ($bookingStatuses as $ks => $vs) {        
            $defaults['smsalert_ssa_general']['customer_ssa_notify_'.$vs]   = 'off';
            $defaults['smsalert_ssa_message']['customer_sms_ssa_body_'.$vs] = '';
            $defaults['smsalert_ssa_general']['admin_ssa_notify_'.$vs]      = 'off';
            $defaults['smsalert_ssa_message']['admin_sms_ssa_body_'.$vs]    = '';
        }
        $defaults['smsalert_ssa_reminder_scheduler']['cron'][0]['frequency'] = '1';
        $defaults['smsalert_ssa_reminder_scheduler']['cron'][0]['message']   = '';
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
            'checkTemplateFor' => 'ssa_customer',
            'templates'        => self::getCustomerTemplates(),
        ];

        $adminParam = [
            'checkTemplateFor' => 'ssa_admin',
            'templates'        => self::getAdminTemplates(),
        ];

        $reminderParam = [
            'checkTemplateFor' => 'wc_simply-appointments_reminder',
            'templates'        => self::getReminderTemplates(),
        ];

        $tabs['simply-appointments']['nav']  = 'Simply Appointments';
        $tabs['simply-appointments']['icon'] = 'dashicons-calendar-alt';

        $tabs['simply-appointments']['inner_nav']['simply-appointments_cust']['title']        = 'Customer Notifications';
        $tabs['simply-appointments']['inner_nav']['simply-appointments_cust']['tab_section']  = 'simplyappointmentscusttemplates';
        $tabs['simply-appointments']['inner_nav']['simply-appointments_cust']['first_active'] = true;
        $tabs['simply-appointments']['inner_nav']['simply-appointments_cust']['tabContent']   = $customerParam;
        $tabs['simply-appointments']['inner_nav']['simply-appointments_cust']['filePath']     = 'views/message-template.php';

        $tabs['simply-appointments']['inner_nav']['simply-appointments_admin']['title']          = 'Admin Notifications';
        $tabs['simply-appointments']['inner_nav']['simply-appointments_admin']['tab_section']    = 'simplyappointmentsadmintemplates';
        $tabs['simply-appointments']['inner_nav']['simply-appointments_admin']['tabContent']     = $adminParam;
        $tabs['simply-appointments']['inner_nav']['simply-appointments_admin']['filePath']       = 'views/message-template.php';
        $tabs['simply-appointments']['inner_nav']['simply-appointments_reminder']['title']       = 'Booking Reminder';
        $tabs['simply-appointments']['inner_nav']['simply-appointments_reminder']['tab_section'] = 'bookingremindertemplates';
        $tabs['simply-appointments']['inner_nav']['simply-appointments_reminder']['tabContent']  = $reminderParam;
        $tabs['simply-appointments']['inner_nav']['simply-appointments_reminder']['filePath']    = 'views/booking-reminder-template.php';
        $tabs['simply-appointments']['help_links']     = array(
			'kb_link'      => array(
			'href'   => 'https://kb.smsalert.co.in/knowledgebase/simply-appointments-sms-integration/',
			'target' => '_blank',
			'alt'    => 'Read how to integrate with Simply Appointments',
			'class'  => 'btn-outline',
			'label'  => 'Documentation',
			'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
			),
        );
        return $tabs;

    }//end addTabs()


    /**
     * Get wc renewal templates function.
     *
     * @return array
     * */
    public static function getReminderTemplates()
    {
        $currentVal     = smsalert_get_option('customer_notify', 'smsalert_ssa_general', 'on');
        $checkboxMameId = 'smsalert_ssa_general[customer_notify]';
        $schedulerData  = get_option('smsalert_ssa_reminder_scheduler');
        $templates      = [];
        $count          = 0;
        if (empty($schedulerData) === true) {
			$schedulerData  = array();
            $schedulerData['cron'][] = [
                'frequency' => '1',
                'message'   => sprintf(__('Hello %1$s, your booking %2$s with %3$s is fixed on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[Name]', '#[id]', '[store_name]', '[start_date]', PHP_EOL, PHP_EOL),
            ];
        }
        foreach ($schedulerData['cron'] as $key => $data) {
            $textAreaNameId = 'smsalert_ssa_reminder_scheduler[cron]['.$count.'][message]';
            $selectNameId   = 'smsalert_ssa_reminder_scheduler[cron]['.$count.'][frequency]';
            $textBody       = $data['message'];

            $templates[$key]['notify_id']      = 'amelia-booking';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']          = 'Send appoinment reminder to customer';
            $templates[$key]['checkboxNameId'] = $checkboxMameId;
            $templates[$key]['text-body']      = $textBody;
            $templates[$key]['textareaNameId'] = $textAreaNameId;
            $templates[$key]['selectNameId']   = $selectNameId;
            $templates[$key]['token']          = self::getBookingvariables();
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
            'booked'          => 'Booked',
            'canceled'        => 'Canceled',
            'abandoned'       => 'Abandoned',           
            'pending_payment' => 'Pending Payment',           
         ];        
         $templates = [];
         foreach ($bookingStatuses as $ks  => $vs) {
             $currentVal = smsalert_get_option('customer_ssa_notify_'.strtolower($ks), 'smsalert_ssa_general', 'on');
             $checkboxMameId = 'smsalert_ssa_general[customer_ssa_notify_'.strtolower($ks).']';
             $textareaNameId = 'smsalert_ssa_message[customer_sms_ssa_body_'.strtolower($ks).']';

             $defaultTemplate = sprintf(__('Hello %1$s, status of your booking #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[Name]', '[id]', '[store_name]', strtolower($vs), PHP_EOL, PHP_EOL);

             $textBody = smsalert_get_option('customer_sms_ssa_body_'.strtolower($ks), 'smsalert_ssa_message', $defaultTemplate);
             $templates[$ks]['title']          = 'When customer appoinment is '.ucwords($vs);
             $templates[$ks]['enabled']        = $currentVal;
             $templates[$ks]['status']         = $ks;
             $templates[$ks]['text-body']      = $textBody;
             $templates[$ks]['checkboxNameId'] = $checkboxMameId;
             $templates[$ks]['textareaNameId'] = $textareaNameId;
             $templates[$ks]['token']          = self::getBookingvariables();
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
            'booked'          => 'Booked',
            'canceled'        => 'Canceled',
            'abandoned'       => 'Abandoned',
            'pending_payment' => 'Pending Payment',            
        ];
        $templates = [];
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal      = smsalert_get_option('admin_ssa_notify_'.strtolower($ks), 'smsalert_ssa_general', 'on');
            $checkboxMameId  = 'smsalert_ssa_general[admin_ssa_notify_'.strtolower($ks).']';
            $textareaNameId  = 'smsalert_ssa_message[admin_sms_ssa_body_'.strtolower($ks).']';

            $defaultTemplate = sprintf(__('Hello admin, status of your booking with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', $vs, PHP_EOL, PHP_EOL);

            $textBody       = smsalert_get_option('admin_sms_ssa_body_'.strtolower($ks), 'smsalert_ssa_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When admin change status to '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $ks;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxMameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getBookingvariables();
        }//end foreach

        return $templates;

    }//end getAdminTemplates()


    /**
     * Send sms New Booking.
     *
     * @param int $appointment_id appointment id
     * @param int $data           data
     * @param int $data_before    data before
     * @param int $response       response
     *
     * @return void
     */
    public function sendsmsNewBooking($appointment_id,$data,$data_before,$response)
    {
        if (empty($data['id'])) {
            $appoinmentStatus  = $data['status'];
        } else {
            $appoinmentStatus  = $data['status'];         
        }
        $this->sendsms($appointment_id, $data, $data_before, $appoinmentStatus);
    }//end sendsmsNewBooking()
    
   
    /**
     * Send sms  Booking update.
     *
     * @param int $appointment_id appointment id
     * @param int $data           data
     * @param int $data_before    data before
     * @param int $response       response
     *
     * @return void
     */
    public function sendBookingUpdate($appointment_id,$data,$data_before,$response)
    {
        $appoinmentStatus = $data['status'];                  
        $this->sendsms($appointment_id, $data, $data_before, $appoinmentStatus);
    }//end sendBookingCanceled() 
    
    /**
     * Send sms  booking.
     *
     * @param int $appointment_id   appointment id
     * @param int $data             data
     * @param int $data_before      data before
     * @param int $appoinmentStatus appoinmentStatus
     *
     * @return void
     */
    public function sendsms($appointment_id, $data, $data_before, $appoinmentStatus)
    {
        
        $form_data = !empty($data['appointment_type_id'])?$data:$data_before;
        $buyerNumber = $form_data['customer_information']['Phone'];        
        $this->setBookingReminder($appointment_id, $form_data, $appoinmentStatus);
        $customerMessage   = smsalert_get_option('customer_sms_ssa_body_' . $appoinmentStatus, 'smsalert_ssa_message', '');        
        $customerNotify    = smsalert_get_option('customer_ssa_notify_' . $appoinmentStatus, 'smsalert_ssa_general', 'on');        
        if ($customerNotify === 'on' && $customerMessage !== '') {            
            $buyerMessage = $this->parseSmsBody($appointment_id, $form_data, $appoinmentStatus, $customerMessage);            
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }
        // send msg to admin.
        $adminPhoneNumber       = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
        $nos                    = explode(',', $adminPhoneNumber);
        $adminPhoneNumber       = array_diff($nos, ['postauthor', 'post_author']);
        $adminPhoneNumber       = implode(',', $adminPhoneNumber);
        if (empty($adminPhoneNumber) === false) {
            $adminNotify        = smsalert_get_option('admin_ssa_notify_' . $appoinmentStatus, 'smsalert_ssa_general', 'on');
            $adminMessage       = smsalert_get_option('admin_sms_ssa_body_' . $appoinmentStatus, 'smsalert_ssa_message', '');
            if ($adminNotify === 'on' && $adminMessage !== '') {
                $adminMessage = $this->parseSmsBody($appointment_id, $form_data, $appoinmentStatus, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        } 

    }//end sendsms()


    /**
     * Parse Reminder sms body.
     *
     * @param array  $appointment_id   appointment_id.
     * @param string $form_data        form_data.
     * @param string $appoinmentStatus appoinmentStatus.
     * @param string $content          content.
     *
     * @return string
     */
    public function parseSmsReminder($appointment_id,$form_data,$appoinmentStatus, $content=null)
    {
        if (!empty($form_data)) {
            $form_data['customer_information'] = json_decode($form_data['customer_information'], true);            
            $content = $this->parseSmsBody($appointment_id, $form_data, $appoinmentStatus, $content);        
        }
        return $content;        
    }//end parseReminderSmsBody()


    /**
     * Parse sms body.
     *
     * @param array  $appointment_id   appointment_id.
     * @param string $form_data        form_data.
     * @param string $appoinmentStatus appoinmentStatus.
     * @param string $content          content.
     *
     * @return string
     */
    public function parseSmsBody($appointment_id,$form_data, $appoinmentStatus, $content=null)
    {
        
        $appointments = array();        
        foreach ($form_data['customer_information'] as $key=>$val) {
            $appointments['['.$key.']'] = $val;
        }
        unset($form_data['customer_information']);        
        foreach ($form_data as $key=>$val) {
            $appointments['['.$key.']'] = $val;
        }
        $appointments['[status]'] = $appoinmentStatus;        
        $appointments['[id]'] = $appointment_id;
        $content = str_replace(array_keys($appointments), array_values($appointments), $content);
        return $content;

    }//end parseSmsBody()


    /**
     * Get booking variables.
     *
     * @return array
     */
    public static function getBookingvariables()
    {

        $variable['[id]']                   = 'Id';
        $variable['[Name]']                 = 'Name';
        $variable['[Email]']                = 'Email';
        $variable['[Phone]']                = 'Phone';
        $variable['[Address]']              = 'Address';
        $variable['[City]']                 = 'City';
        $variable['[State]']                = 'State';
        $variable['[Zip]']                  = 'Zip';
        $variable['[Notes]']                = 'notes';
        $variable['[appointment_type_id]']  = 'Appointmen type Id';
        $variable['[status]']               = 'Status';
        $variable['[customer_id]']          = 'Customer Id';
        $variable['[start_date]']           = 'Start Date';
        $variable['[end_date]']             = 'End Date';
        $variable['[payment_received]']     = 'Payment Received';
        return $variable;
    }//end getBookingvariables()


    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('simply-schedule-appointments/simply-schedule-appointments.php') === true) {
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
        if ((is_plugin_active('simply-schedule-appointments/simply-schedule-appointments.php') === true) && ($islogged === true)) {
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
new  SimplyAppoinments();