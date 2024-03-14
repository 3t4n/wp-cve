<?php

/**
 * Amelia booking helper.
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

if (is_plugin_active('ameliabooking/ameliabooking.php') === false) {
    return;
}
use AmeliaBooking\Domain\ValueObjects\String\BookingStatus;
/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 *
 * SAameliabooking class
 */
class SAameliabooking extends FormInterface
{
   
    /**
     * Construct function.
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('AmeliaCustomerWPCreated', [$this, 'ameliaNewCustomer'], 10, 2);
        add_action('AmeliaBookingAdded', [$this, 'sendsmsNewBooking'], 10, 3);
        add_action('AmeliaBookingCanceled', [$this, 'sendsmsBookingCanceled'], 10, 3);
        add_action('AmeliaBookingStatusUpdated', [$this, 'sendBookingUpdated'], 10, 3);
        add_action('booking_reminder_sendsms_hook', [$this, 'sendReminderSms'], 10);

    }//end handleForm()
    
    /**
     * Amelia New Customer.
     *
     * @param int $reservation reservation.
     * @param int $booking     booking.
     *
     * @return void
     */
    public function ameliaNewCustomer($reservation, $booking)
    {
        $buyerNumber= $reservation['phone'];        
        $defaultSms = SmsAlertMessages::showMessage('DEFAULT_CONTACT_FORM_CUSTOMER_MESSAGE');
        do_action('sa_send_sms', $buyerNumber, $defaultSms);        
    }

    /**
     * Set booking reminder.
     *
     * @param int $reservation reservation.
     *
     * @return void
     */
    public static function setBookingReminder($reservation)
    {
        if (empty($reservation) === true) {
            return;
        }
        foreach ($reservation['bookings'] as $booking) {
            $info           = json_decode($booking['info']);
            $bookingStatus = $booking['status'];
            $buyer_mob      = $info->phone;          
        }
        $booking_id         = $reservation['id'];
        $booking_start      = $reservation['bookingStart'];
        $customerNotify     = smsalert_get_option('customer_notify', 'smsalert_alb_general', 'on');
        global $wpdb;
        $tableName       = $wpdb->prefix.'smsalert_booking_reminder';
        $source          = 'amelia-booking';
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
     * Send Reminder sms function.
     *
     * @return void
     */
    function sendReminderSms()
    {
        if (smsalert_get_option('customer_notify', 'smsalert_alb_general') !== 'on') {
            return;
        }

        global $wpdb;
        $cronFrequency = BOOKING_REMINDER_CRON_INTERVAL;
        // pick data from previous CART_CRON_INTERVAL min
        $tableName     = $wpdb->prefix.'smsalert_booking_reminder';
        
        $source        = 'amelia-booking';
        $schedulerData = get_option('smsalert_alb_reminder_scheduler');

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
                        $id                    = $data['booking_id'];
                        $obj[$key]['number']   = $data['phone'];                        
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'amelia_customer_bookings';
                        $table_name1 = $wpdb->prefix . 'amelia_appointments';
                        $reservation     = $wpdb->get_results("SELECT * FROM ".$table_name." INNER JOIN ".$table_name1." ON  ".$table_name.".appointmentId = ".$table_name1.".id WHERE ".$table_name.".appointmentId= ".$id."");
                        $obj[$key]['sms_body'] = self::parseSmsReminder($reservation, $customerMessage);                        
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
            BookingStatus::CANCELED,
            BookingStatus::APPROVED,
            BookingStatus::PENDING,
            BookingStatus::REJECTED,
        ]; 
        foreach ($bookingStatuses as $ks => $vs) {		
            $defaults['smsalert_alb_general']['customer_alb_notify_'.$vs]   = 'off';
            $defaults['smsalert_alb_message']['customer_sms_alb_body_'.$vs] = '';
            $defaults['smsalert_alb_general']['admin_alb_notify_'.$vs]      = 'off';
            $defaults['smsalert_alb_message']['admin_sms_alb_body_'.$vs]    = '';
        }
        $defaults['smsalert_alb_reminder_scheduler']['cron'][0]['frequency'] = '1';
        $defaults['smsalert_alb_reminder_scheduler']['cron'][0]['message']   = '';
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
            'checkTemplateFor' => 'alb_customer',
            'templates'        => self::getCustomerTemplates(),
        ];

        $adminParam = [
            'checkTemplateFor' => 'alb_admin',
            'templates'        => self::getAdminTemplates(),
        ];

        $reminderParam = [
            'checkTemplateFor' => 'wc_amelia_booking_reminder',
            'templates'        => self::getReminderTemplates(),
        ];

        $tabs['amelia_booking']['nav']  = 'Amelia Booking';
        $tabs['amelia_booking']['icon'] = 'dashicons-calendar-alt';

        $tabs['amelia_booking']['inner_nav']['amelia_booking_cust']['title']        = 'Customer Notifications';
        $tabs['amelia_booking']['inner_nav']['amelia_booking_cust']['tab_section']  = 'ameliabookingcusttemplates';
        $tabs['amelia_booking']['inner_nav']['amelia_booking_cust']['first_active'] = true;
        $tabs['amelia_booking']['inner_nav']['amelia_booking_cust']['tabContent']   = $customerParam;
        $tabs['amelia_booking']['inner_nav']['amelia_booking_cust']['filePath']     = 'views/message-template.php';

        $tabs['amelia_booking']['inner_nav']['amelia_booking_admin']['title']          = 'Admin Notifications';
        $tabs['amelia_booking']['inner_nav']['amelia_booking_admin']['tab_section']    = 'ameliabookingadmintemplates';
        $tabs['amelia_booking']['inner_nav']['amelia_booking_admin']['tabContent']     = $adminParam;
        $tabs['amelia_booking']['inner_nav']['amelia_booking_admin']['filePath']       = 'views/message-template.php';
        $tabs['amelia_booking']['inner_nav']['amelia_booking_reminder']['title']       = 'Booking Reminder';
        $tabs['amelia_booking']['inner_nav']['amelia_booking_reminder']['tab_section'] = 'bookingremindertemplates';
        $tabs['amelia_booking']['inner_nav']['amelia_booking_reminder']['tabContent']  = $reminderParam;
        $tabs['amelia_booking']['inner_nav']['amelia_booking_reminder']['filePath']    = 'views/booking-reminder-template.php';
		
		$tabs['amelia_booking']['help_links']                        = array(
        
        'kb_link'      => array(
        'href'   => 'https://kb.smsalert.co.in/knowledgebase/amelia-booking-sms-integration/',
        'target' => '_blank',
        'alt'    => 'Read how to integrate with Amelia Booking',
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
        $currentVal     = smsalert_get_option('customer_notify', 'smsalert_alb_general', 'on');
        $checkboxMameId = 'smsalert_alb_general[customer_notify]';

        $schedulerData = get_option('smsalert_alb_reminder_scheduler');
        $templates     = [];
        $count         = 0;
        if (empty($schedulerData) === true) {
			$schedulerData = array();
            $schedulerData['cron'][] = [
                'frequency' => '1',
                'message'   => sprintf(__('Hello %1$s, your booking %2$s with %3$s is fixed on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[firstName]', '#[appointmentId]', '[store_name]', '[bookingStart]', PHP_EOL, PHP_EOL),
            ];
        }

        foreach ($schedulerData['cron'] as $key => $data) {
            $textAreaNameId = 'smsalert_alb_reminder_scheduler[cron]['.$count.'][message]';
            $selectNameId   = 'smsalert_alb_reminder_scheduler[cron]['.$count.'][frequency]';
            $textBody       = $data['message'];

            $templates[$key]['notify_id']      = 'amelia-booking';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']          = 'Send booking reminder to customer';
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
            '['.BookingStatus::CANCELED.']' => ucwords(BookingStatus::CANCELED),
            '['.BookingStatus::APPROVED.']' => ucwords(BookingStatus::APPROVED),
            '['.BookingStatus::PENDING.']' => ucwords(BookingStatus::PENDING),
            '['.BookingStatus::REJECTED.']' => ucwords(BookingStatus::REJECTED)            
        ]; 
        $templates = [];
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal = smsalert_get_option('customer_alb_notify_'.strtolower($vs), 'smsalert_alb_general', 'on');
            $checkboxMameId = 'smsalert_alb_general[customer_alb_notify_'.strtolower($vs).']';
            $textareaNameId = 'smsalert_alb_message[customer_sms_alb_body_'.strtolower($vs).']';

            $defaultTemplate = sprintf(__('Hello %1$s, status of your booking #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[firstName]', '[appointmentId]', '[store_name]', strtolower($vs), PHP_EOL, PHP_EOL);

            $textBody = smsalert_get_option('customer_sms_alb_body_'.strtolower($vs), 'smsalert_alb_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When customer booking is '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
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
            '['.BookingStatus::CANCELED.']' => ucwords(BookingStatus::CANCELED),
            '['.BookingStatus::APPROVED.']' => ucwords(BookingStatus::APPROVED),
            '['.BookingStatus::PENDING.']' => ucwords(BookingStatus::PENDING),
            '['.BookingStatus::REJECTED.']' => ucwords(BookingStatus::REJECTED)            
        ];

        $templates = [];
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal      = smsalert_get_option('admin_alb_notify_'.strtolower($vs), 'smsalert_alb_general', 'on');
            $checkboxMameId  = 'smsalert_alb_general[admin_alb_notify_'.strtolower($vs).']';
            $textareaNameId  = 'smsalert_alb_message[admin_sms_alb_body_'.strtolower($vs).']';

            $defaultTemplate = sprintf(__('Hello admin, status of your booking with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', $vs, PHP_EOL, PHP_EOL);

            $textBody       = smsalert_get_option('admin_sms_alb_body_'.strtolower($vs), 'smsalert_alb_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When admin change status to '.ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxMameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getBookingvariables();
        }//end foreach

        return $templates;

    }//end getAdminTemplates()


    /**
     * Send sms new booking.
     *
     * @param int $reservation reservation
     * @param int $bookings    bookings
     * @param int $container   container
     *
     * @return void
     */
    public function sendsmsNewBooking($reservation, $bookings, $container)
    {
        foreach ($reservation['bookings'] as $booking) {
            $bookingStatus = $booking['status'];              
        } 
        $this->sendsms($reservation, $bookings, $bookingStatus);
    }//end sendsmsNewBooking()
    
    
    /**
     * Send sms Bookin Canceled.
     *
     * @param int $reservation reservation
     * @param int $bookings    bookings
     * @param int $container   container
     *
     * @return void
     */
    public function sendsmsBookingCanceled($reservation, $bookings, $container)
    {
        foreach ($reservation['bookings'] as $booking) {
            $bookingStatus = $booking['status'];                  
        }    
        $this->sendsms($reservation, $bookings, $bookingStatus);
    }


    /**
     * Send sms Bookin Update.
     *
     * @param int $reservation reservation
     * @param int $bookings    bookings
     * @param int $container   container
     *
     * @return void
     */
    public function sendBookingUpdated($reservation, $bookings, $container)
    {        
        foreach ($reservation['bookings'] as $booking) {
            $bookingStatus = $booking['status'];                  
        }
        $this->sendsms($reservation, $bookings, $bookingStatus);
    }//end sendBookingUpdated()
    
    
    
    /**
     * Send sms  booking.
     *
     * @param int $reservation   reservation
     * @param int $bookings      bookings
     * @param int $bookingStatus bookingStatus
     *
     * @return void
     */
    public function sendsms($reservation, $bookings, $bookingStatus)
    {
        foreach ($reservation['bookings'] as $booking) {
            $info          = json_decode($booking['info']);
            $buyerNumber   = $info->phone;          
        }        
        $this->setBookingReminder($reservation);
        $customerMessage   = smsalert_get_option('customer_sms_alb_body_' . $bookingStatus, 'smsalert_alb_message', '');
        $customerNotify    = smsalert_get_option('customer_alb_notify_' . $bookingStatus, 'smsalert_alb_general', 'on');

        if ($customerNotify === 'on' && $customerMessage !== '') {
            $buyerMessage = $this->parseSmsBody($reservation, $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }

        // send msg to admin.
        $adminPhoneNumber       = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
        $nos                    = explode(',', $adminPhoneNumber);
        $adminPhoneNumber       = array_diff($nos, ['postauthor', 'post_author']);
        $adminPhoneNumber       = implode(',', $adminPhoneNumber);
        if (empty($adminPhoneNumber) === false) {
            $adminNotify        = smsalert_get_option('admin_alb_notify_' . $bookingStatus, 'smsalert_alb_general', 'on');
            $adminMessage       = smsalert_get_option('admin_sms_alb_body_' . $bookingStatus, 'smsalert_alb_message', '');
            if ($adminNotify === 'on' && $adminMessage !== '') {
                $adminMessage = $this->parseSmsBody($reservation, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        } 

    }//end sendsms()


    /**
     * Parse Reminder sms body.
     *
     * @param array  $reservation reservation.
     * @param string $content     content.
     *
     * @return string
     */
    public function parseSmsReminder($reservation, $content=null)
    {
        foreach ($reservation as $booking) {
            $info            = json_decode($booking->info);
            $firstName       = $info->firstName;
            $lastName        = $info->lastName;  
            $buyerNumber     = $info->phone;            
            $customerId      = $booking->customerId;
            $status          = $booking->status;
            $price           = $booking->price;
            $persons         = $booking->persons;
            $duration        = $booking->duration;
            $appointmentId   = $booking->appointmentId;
            $bookingStart    = $booking->bookingStart;                   
            $bookingEnd      = $booking->bookingEnd;
            $serviceId     = $reservation->serviceId;					
        } 
		$table_name = $wpdb->prefix . 'amelia_services';
        $service = $wpdb->get_results("SELECT `name` FROM " .$table_name. " WHERE id=".$serviceId);
		foreach ($service as $servicenames) {
			$servicename=$servicenames->name; 
		} 
        $find = [
            '[firstName]',
            '[lastName]',
            '[phone]',
            '[customerId]',
            '[status]',
            '[price]',
            '[persons]',
            '[duration]',
            '[appointmentId]',
            '[bookingStart]',
            '[bookingEnd]',
			'[serviceId]',
            '[service_name]',
			
        ];

        $replace = [
            $firstName,
            $lastName,
            $buyerNumber,
            $customerId,
            $status,
            $price,
            $persons,
            $duration,
            $appointmentId,
            $bookingStart,
            $bookingEnd,
			$serviceId,
			$servicename			
        ];

        $content = str_replace($find, $replace, $content);
        return $content;

    }//end parseReminderSmsBody()


    /**
     * Parse sms body.
     *
     * @param array  $reservation reservation.
     * @param string $content     content.
     *
     * @return string
     */
    public function parseSmsBody($reservation, $content=null)
    {    
	    global $wpdb;  
	    foreach ($reservation['bookings'] as $booking) {            
            $info          = json_decode($booking['info']);
            $firstName     = $info->firstName;
            $lastName      = $info->lastName;
            $buyerNumber   = $info->phone;
            $customerId    = $booking['customerId'];
            $status        = $booking['status'];
            $price         = $booking['price'];
            $persons       = $booking['persons'];
            $duration      = $booking['duration'];                  
        }        
        $appointmentId     = $reservation['id'];    
        $bookingStart      = $reservation['bookingStart'];
        $bookingEnd        = $reservation['bookingEnd'];
		$serviceId     = $reservation['serviceId'];
	    $table_name = $wpdb->prefix . 'amelia_services';
        $service = $wpdb->get_results("SELECT `name` FROM " .$table_name. " WHERE id=".$serviceId);
		foreach ($service as $servicenames) {
			$servicename=$servicenames->name; 			
		}

        $find = [
            '[firstName]',
            '[lastName]',
            '[phone]',
            '[customerId]',
            '[status]',
            '[price]',
            '[persons]',
            '[duration]',
            '[appointmentId]',
            '[bookingStart]',
            '[bookingEnd]',
            '[serviceId]',
            '[service_name]',
			
        ];

        $replace = [
            $firstName,
            $lastName,
            $buyerNumber,
            $customerId,
            $status,
            $price,
            $persons,
            $duration,
            $appointmentId,
            $bookingStart,
            $bookingEnd,
			$serviceId,
			$servicename
        ];

        $content = str_replace($find, $replace, $content);
        return $content;

    }//end parseSmsBody()


    /**
     * Get booking variables.
     *
     * @return array
     */
    public static function getBookingvariables()
    {

        $variable['[firstName]']        = 'First Name';
        $variable['[lastName]']         = 'Last Name';
        $variable['[phone]']            = 'Phone';
        $variable['[customerId]']       = 'Customer Id';
        $variable['[status]']           = 'Status';
        $variable['[price]']            = 'Price';
        $variable['[persons]']          = 'Persons';
        $variable['[duration]']         = 'Duration';
        $variable['[appointmentId]']    = 'Appointment Id';
        $variable['[bookingStart]']     = 'Booking Start';
        $variable['[bookingEnd]']       = 'Booking End';
        $variable['[serviceId]']        = 'Service Id';
        $variable['[service_name]']     = 'Service Name';
        return $variable;

    }//end getBookingvariables()


    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('ameliabooking/ameliabooking.php') === true) {
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
        if ((is_plugin_active('ameliabooking/ameliabooking.php') === true) && ($islogged === true)) {
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
new  SAameliabooking();