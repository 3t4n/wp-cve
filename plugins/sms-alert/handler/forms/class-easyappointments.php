<?php

/**
 * Easy-appointments helper.
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

if (is_plugin_active('easy-appointments/main.php') === false) {
    return;
}

require_once WP_PLUGIN_DIR . '/easy-appointments/src/dbmodels.php';


/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * 
 * SA_Easyappointments class 
 */
class SA_Easyappointments extends FormInterface
{
    /**
     * Form Session Variable.
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::WP_EASY_APPOINTMENTS;
    
    /**
     * 
     * Construct function.
     *
     * @return void
     */
    public function handleForm()
    {
        add_action('ea_new_app', array($this, 'sendSmsOnNewAppointment'), 10, 2); 
        add_action('ea_edit_app', array($this, 'sendSmsOnUpdateAppointment'), 10, 1);
        add_action('booking_reminder_sendsms_hook', array($this, 'sendReminderSms'), 10);
        add_filter('ea_checkout_script', array($this, 'getFormField'), 20);
    }
    
    /**
     * Add Shortcode for OTP and Add additional js code to your script
     *
     * @return void
     * */
    public function getFormField()
    {
        if (smsalert_get_option('otp_enable', 'smsalert_eap_general') === 'on') {
            echo do_shortcode('[sa_verify phone_selector="#phone" submit_selector= ".ea-submit"]');
        }
    }

    /**
     * Set booking reminder.
     *
     * @param array $appointmentId appointmentId.
     *
     * @return void
     */
    public static function setBookingReminder($appointmentId)
    {  
        if (empty($appointmentId) === true) {
            return;
        }  
        global $wpdb;
        $mydatas          = new EADBModels($wpdb, null, null);
        $datas              = $mydatas->get_appintment_by_id($appointmentId);       
        $bookingStatus   = $datas['status'];    
        $bookingId       = $datas['id'];        
        $date = $datas['date'];        
        $time = $datas['start'];
        $bookingStart    = $date .' ' . $time;
        $buyerMob        = $datas['phone'];
        $customerNotify  = smsalert_get_option('customer_notify', 'smsalert_eap_general', 'on');
        global $wpdb;
        $tableName       = $wpdb->prefix . 'smsalert_booking_reminder';
        $source          = 'easy-appointments';
        
        $booking_details = $wpdb->get_results("SELECT * FROM $tableName WHERE booking_id = $bookingId and source = '$source'");
        
        if ($bookingStatus === 'confirmed' && $customerNotify === 'on') {
            
            if ($booking_details) {
                
                $wpdb->update(
                    $tableName,
                    array(
                        'start_date'   => $bookingStart,
                        'phone'        => $buyerMob
                    ),
                    array('booking_id' => $bookingId)
                );
            } else {
                
                $wpdb->insert(
                    $tableName,
                    array(
                        'booking_id'   => $bookingId,
                        'phone'        => $buyerMob,
                        'source'       => $source,
                        'start_date'   => $bookingStart
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
        if (smsalert_get_option('customer_notify', 'smsalert_eap_general') !=='on') {
            return;
        }
    
        global $wpdb;
        $cronFrequency   = BOOKING_REMINDER_CRON_INTERVAL; // pick data from previous CART_CRON_INTERVAL min
        $tableName       = $wpdb->prefix . 'smsalert_booking_reminder';
        
        $source          = 'easy-appointments';
        
        $schedulerData   = get_option('smsalert_eap_reminder_scheduler');
     
        foreach ($schedulerData['cron'] as $sdata) {
            
            $datetime    = current_time('mysql');
            $fromdate    = date('Y-m-d H:i:s', strtotime('+' . ($sdata['frequency'] * 60 - $cronFrequency) . ' minutes', strtotime($datetime)));
        
            $todate      = date('Y-m-d H:i:s', strtotime('+' . $cronFrequency . ' minutes', strtotime($fromdate)));
            
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
                        
                        $mydatas = new EADBModels($wpdb, null, null);
                        $booking=$mydatas->get_appintment_by_id($data['booking_id']);
                            
                        $obj[$key]['number']    = $data['phone'];
                                
                        $obj[$key]['sms_body']  = self::parseSmsBody($appointmentId, $booking, $customerMessage);
                        $usau = $obj[$key]['sms_body'];

                    }
                    $response    = SmsAlertcURLOTP::sendSmsXml($obj);
                    $responseArr = json_decode($response, true);
                    if (!empty($responseArr['status']) && 'success' === $responseArr['status']) {
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
                }
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
      
        $bookingStatuses =(new EALogic($wpdb, null, null, null))->getStatus();
        
        foreach ($bookingStatuses as $ks => $vs) {
            $defaults['smsalert_eap_general']['customer_eap_notify_' . $vs] = 'off';
            $defaults['smsalert_eap_message']['customer_sms_eap_body_' . $vs] = '';
            $defaults['smsalert_eap_general']['admin_eap_notify_' . $vs] = 'off';
            $defaults['smsalert_eap_message']['admin_sms_eap_body_' . $vs]    = '';
        }
        $defaults['smsalert_eap_general']['otp_enable']                      = 'off';
        $defaults['smsalert_eap_general']['customer_notify']                 = 'off';
        $defaults['smsalert_eap_reminder_scheduler']['cron'][0]['frequency'] = '1';
        $defaults['smsalert_eap_reminder_scheduler']['cron'][0]['message']   = '';
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
            'checkTemplateFor' => 'eap_customer',
            'templates'        => self::getCustomerTemplates(),
        );
        $admin_param = array(
            'checkTemplateFor' => 'eap_admin',
            'templates'        => self::getAdminTemplates(),
        );
        $reminderParam = array(
            'checkTemplateFor' => 'wc_easy-appointments_reminder',
            'templates'        => self::getReminderTemplates(),
        );
        $tabs['easy-appointments']['nav']  = 'Easy Appointments';
        $tabs['easy-appointments']['icon'] = 'dashicons-calendar-alt';
        $tabs['easy-appointments']['inner_nav']['easy-appointments_cust']['title']          = 'Customer Notifications';
        $tabs['easy-appointments']['inner_nav']['easy-appointments_cust']['tab_section']    = 'bookingcalendarcusttemplates';
        $tabs['easy-appointments']['inner_nav']['easy-appointments_cust']['first_active']   = true;
        $tabs['easy-appointments']['inner_nav']['easy-appointments_cust']['tabContent']     = $customerParam;
        $tabs['easy-appointments']['inner_nav']['easy-appointments_cust']['filePath']       = 'views/message-template.php';
        $tabs['easy-appointments']['inner_nav']['easy-appointments_admin']['title']         = 'Admin Notifications';
        $tabs['easy-appointments']['inner_nav']['easy-appointments_admin']['tab_section']   = 'bookingcalendaradmintemplates';
        $tabs['easy-appointments']['inner_nav']['easy-appointments_admin']['tabContent']    = $admin_param;
        $tabs['easy-appointments']['inner_nav']['easy-appointments_admin']['filePath']      = 'views/message-template.php';
        $tabs['easy-appointments']['inner_nav']['easy-appointments_reminder']['title']      = 'Appointment Reminder';
        $tabs['easy-appointments']['inner_nav']['easy-appointments_reminder']['tab_section']= 'appointmentremindertemplates';
        $tabs['easy-appointments']['inner_nav']['easy-appointments_reminder']['tabContent'] = $reminderParam;
        $tabs['easy-appointments']['inner_nav']['easy-appointments_reminder']['filePath']   = 'views/booking-reminder-template.php';
        $tabs['easy-appointments']['help_links'] = [
            /* 'youtube_link' => [
                'href'   => 'https://youtu.be/4BXd_XZt9zM',
                'target' => '_blank',
                'alt'    => 'Watch steps on Youtube',
                'class'  => 'btn-outline',
                'label'  => 'Youtube',
                'icon'   => '<span class="dashicons dashicons-video-alt3" style="font-size: 21px;"></span> ',

            ], */
            'kb_link'      => [
                'href'   => 'https://kb.smsalert.co.in/knowledgebase/easyappointments-sms-integration',
                'target' => '_blank',
                'alt'    => 'Read how to integrate with easyappointments',
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
     */
    public static function getReminderTemplates()
    {
        $currentVal     = smsalert_get_option('customer_notify', 'smsalert_eap_general', 'on');
        $checkboxNameId = 'smsalert_eap_general[customer_notify]';
        $schedulerData  = get_option('smsalert_eap_reminder_scheduler');
        $templates      = array();
        $count          = 0;
        if (empty($schedulerData) === true) {
			$schedulerData  = array();
            $schedulerData['cron'][] = array(
                'frequency' => '1',
                'message'   => sprintf(__('Hello %1$s, your appointment %2$s with %3$s is fixed on %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[name]', '#[id]', '[store_name]', '[bookingdate]', PHP_EOL, PHP_EOL),
            );
        }
        foreach ($schedulerData['cron'] as $key => $data) {
            $textAreaNameId  = 'smsalert_eap_reminder_scheduler[cron][' . $count . '][message]';
            $selectNameId    = 'smsalert_eap_reminder_scheduler[cron][' . $count . '][frequency]';
            $textBody        = $data['message'];

            $templates[$key]['notify_id']      = 'easy-appointments';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']   = 'Send appointment reminder to customer';
            $templates[$key]['checkboxNameId'] = $checkboxNameId;
            $templates[$key]['text-body']      = $textBody;
            $templates[$key]['textareaNameId'] = $textAreaNameId;
            $templates[$key]['selectNameId']   = $selectNameId;
            $templates[$key]['token']   = self:: getEasyappointmentsvariables();

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
        global $wpdb;
        $bookingStatuses =(new EALogic($wpdb, null, null, null))->getStatus();
        $templates           = [];
        foreach ($bookingStatuses as $ks => $vs) {
            $currentVal      = smsalert_get_option('customer_eap_notify_' . strtolower($vs), 'smsalert_eap_general', 'on');
            $checkboxNameId  = 'smsalert_eap_general[customer_eap_notify_' . strtolower($vs) . ']';
            $textareaNameId  = 'smsalert_eap_message[customer_sms_eap_body_' . strtolower($vs) . ']';

            $defaultTemplate = smsalert_get_option('customer_sms_eap_body_' . strtolower($vs), 'smsalert_eap_message', sprintf(__('Hello %1$s, status of your appointment #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[name]', '[id]', '[store_name]', $vs, PHP_EOL, PHP_EOL));

            $textBody       = smsalert_get_option('customer_sms_eap_body_' . strtolower($vs), 'smsalert_eap_message', $defaultTemplate);
            $templates[$ks]['title']  = 'When appointment is ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']  = self:: getEasyappointmentsvariables();
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
        global $wpdb;
        $bookingStatuses =(new EALogic($wpdb, null, null, null))->getStatus();
        $templates           = [];
        
        foreach ($bookingStatuses as $ks => $vs) {

            $currentVal      = smsalert_get_option('admin_eap_notify_' . strtolower($vs), 'smsalert_eap_general', 'on');
            $checkboxNameId  = 'smsalert_eap_general[admin_eap_notify_' . strtolower($vs) . ']';
            $textareaNameId  = 'smsalert_eap_message[admin_sms_eap_body_' . strtolower($vs) . ']';

            $defaultTemplate = smsalert_get_option('admin_sms_eap_body_' . strtolower($vs), 'smsalert_eap_message', sprintf(__('Hello admin, status of your appointment with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', $vs, PHP_EOL, PHP_EOL));


            $textBody = smsalert_get_option('admin_sms_eap_body_' . strtolower($vs), 'smsalert_eap_message', $defaultTemplate);

            $templates[$ks]['title']   = 'When appointment is ' . $vs;
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']  = self:: getEasyappointmentsvariables();
        }
        return $templates;
    }

    /**
     * Send sms approved pending.
     *
     * @param int $appointmentId appointmentId
     * @param int $data          data
     *
     * @return array
     */
    public function sendSmsOnNewAppointment($appointmentId, $data)
    {
        $this->sendSmsOn($appointmentId, $data); 
    }    

    /**
     * Send sms approved pending.
     *
     * @param int $appointmentId appointmentId
     *
     * @return array
     */
    public function sendSmsOnUpdateAppointment($appointmentId)
    {
        
        $this->sendSmsOn($appointmentId, $data); 
    } 

     /**
      * Send sms approved pending.
      *
      * @param int $appointmentId appointmentId
      * @param int $data          data
      *
      * @return void
      */
    public function sendSmsOn($appointmentId,$data)
    {
       
        global $wpdb;
        $mydatas = new EADBModels($wpdb, null, null);
        $datas = $mydatas->get_appintment_by_id($appointmentId);
        
        $this->setBookingReminder($appointmentId);
       
        $bookingStatus     = ($datas['status'] == 'canceled') ? "cancelled" : $datas['status'];
        $buyerNumber       = $datas['phone'];
        if (!empty($buyerNumber)) {
            $customerMessage   = smsalert_get_option('customer_sms_eap_body_' . $bookingStatus, 'smsalert_eap_message', '');
            
            $customerNotify    = smsalert_get_option('customer_eap_notify_' . $bookingStatus, 'smsalert_eap_general', 'on');
            
            if (($customerNotify === 'on' && $customerMessage !== '')) {
                $buyerMessage = $this->parseSmsBody($appointmentId, $datas, $customerMessage);
                do_action('sa_send_sms', $buyerNumber, $buyerMessage);
            }
            // Send msg to admin.
            $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
            if (empty($adminPhoneNumber) === false) {
                $adminNotify        = smsalert_get_option('admin_eap_notify_' . $bookingStatus, 'smsalert_eap_general', 'on');
                $adminMessage       = smsalert_get_option('admin_sms_eap_body_' . $bookingStatus, 'smsalert_eap_message', '');
                $nos = explode(',', $adminPhoneNumber);
                $adminPhoneNumber   = array_diff($nos, array('postauthor', 'post_author'));
                $adminPhoneNumber   = implode(',', $adminPhoneNumber);
                if ($adminNotify === 'on' && $adminMessage !== '') {
                    $adminMessage   = $this->parseSmsBody($appointmentId, $datas, $adminMessage);
                    do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
                }
            }
        } 
    }
    
    /**
     * Parse sms body.
     *
     * @param array  $appointmentId appointmentId.
     * @param array  $datas         datas.
     * @param string $content       content.
     *
     * @return string
     */
    public function parseSmsBody($appointmentId, $datas, $content = null)
    {
        
        $id               = $datas['id'];
        $location         = $datas['location'];
        $service          = $datas['service'];
        $worker           = $datas['worker'];
        $name             = $datas['name'];
        $email            = $datas['email'];
        $phone            = $datas['phone'];
        $date             = $datas['date']; 
        $start            = $datas['start'];
        $end              = $datas['end'];
        $end_date         = $datas['end_date'];
        $bookingdate      = $date .' ' . $start;
        $description      = $datas['description'];
        $status           = $datas['description'];
        $created          = $datas['created'];
        $price            = $datas['price'];
        $service_name     = $datas['service_name'];
        $service_duration = $datas['service_duration'];
        $worker_name      = $datas['worker_name'];
        $worker_email     = $datas['worker_email'];
        $worker_phone     = $datas['worker_phone']; 
        $location_name    = $datas['location_name'];
        $location_address = $datas['location_address'];
        $find = array(
            '[id]',
            '[location]',
            '[service]',
            '[worker]',
            '[name]',
            '[email]',
            '[phone]',
            '[date]',
            '[start]',
            '[end]',
            '[end_date]',
            '[bookingdate]',
            '[description]',
            '[status]',
            '[created]',
            '[price]',
            '[service_name]',
            '[service_duration]',
            '[worker_name]',
            '[worker_email]',
            '[worker_phone]',
            '[location_name]',
            '[location_address]',
            
        );

        $replace = array(
        $id,
        $location,
        $service,
        $worker,
        $name,
        $email,
        $phone,
        $date, 
        $start,
        $end,
        $end_date,
        $bookingdate,
        $description,
        $status,
        $created,
        $price,
        $service_name, 
        $service_duration,
        $worker_name,
        $worker_email,
        $worker_phone,
        $location_name,
        $location_address,
        );
        $content = str_replace($find, $replace, $content);
        return $content;
    }

    /**
     * Get Easy Appointments variables.
     *
     * @return array
     */
    public static function getEasyappointmentsvariables()
    {
        $variable['[id]']                     = 'Id';
        $variable['[location]']               = 'Location';
        $variable['[service]']                = 'Service';
        $variable['[worker]']                 = 'Worker';
        $variable['[name]']                   = 'Name';
        $variable['[email]']                  = 'Email';
        $variable['[phone]']                  = 'Phone';
        $variable['[date]']                   = 'Date';
        $variable['[start]']                  = 'Start';
        $variable['[end]']                    = 'End';
        $variable['[end_date]']               = 'End Date';
        $variable['[bookingdate]']            = 'Booking Date';
        $variable['[description]']            = 'Description';
        $variable['[status]']                 = 'Status';
        $variable['[user]']                   = 'User';
        $variable['[created]']                = 'Created';
        $variable['[price]']                  = 'Price';
        $variable['[service_name]']           = 'Service Name';
        $variable['[service_duration]']       = 'Service Duration';
        $variable['[worker_name]']            = 'Worker Name';
        $variable['[worker_email]']           = 'Worker Email';
        $variable['[worker_phone]']           = 'Worker Phone';
        $variable['[location_name]']          = 'Location Name';
        $variable['[location_address]']       = 'Location Address';
        return $variable;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('easy-appointments/main.php') === true) {
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
        if ((is_plugin_active('easy-appointments/main.php') === true) && ($islogged === true)) {
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
        if ($_SESSION[$this->form_session_var] === true) {
            return true;
        } else {
            return $isAjax;
        }
    }
}
new SA_Easyappointments();
