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

if (is_plugin_active('wp-cafe/wpcafe.php') === false) {
    return;
}

use WpCafe\Utils;
/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * WPCafe class 
 */
class SAWPCafe extends FormInterface
{
    /**
     * Form Session Variable.
     *
     * @return stirng
     */
    private $form_session_var = FormSessionVars::WP_CAFE;
    
    /**
     * Construct function.
     *
     * @return stirng
     */
    public function handleForm()
    {
        add_action('wpc_before_minicart', array($this, 'getFormField'), 10);
        add_action('booking_reminder_sendsms_hook', array($this, 'sendReminderSms'), 10);
        add_filter('wpcafe_pro/action/extra_field', array($this, 'sendsmsNewBooking'), 10, 2);
        add_filter('wpcafe/reservation_with_food/extra_field', array($this, 'bookingUpdate'), 10, 1);       
    }

    /**
     * Add Shortcode for OTP and Add additional js code to your script
     *
     * @return stirng
     * */
    public function getFormField()
    { 
       if (smsalert_get_option('otp_enable', 'smsalert_wcf_general') === 'on') {
        $uniqueNo = rand();       
        $inline_script = 'jQuery(document).ready(function(){
			add_smsalert_button(".confirm_booking_btn",".wpc_check_phone","'.$uniqueNo.'");
			setTimeout(function(){ jQuery(".confirm_booking_btn#sa_verify_'.$uniqueNo.'").unbind("click"); }, 3000);
						
			jQuery(document).on("click", "#sa_verify_'.$uniqueNo.'",function(event){	
				event.stopImmediatePropagation();
				send_otp(this,".confirm_booking_btn",".wpc_check_phone","","");
				return false;									
			});
		});
        setTimeout(function() {
            if (jQuery(".modal.smsalertModal").length==0)    
            {            
            var popup = \''.str_replace(array("\n","\r","\r\n"), "", (get_smsalert_template("template/otp-popup.php", array(), true))).'\';
            jQuery("body").append(popup);
            }
        }, 200);
        ';		
		if ( ! wp_script_is( 'sainlinescript-handle-footer', 'enqueued' ) ) {
         wp_register_script( 'sainlinescript-handle-footer', '', [], '', true );
         wp_enqueue_script( 'sainlinescript-handle-footer'  );
		}		
		wp_add_inline_script( "sainlinescript-handle-footer", $inline_script);
        }   
    } 

    /**
     * Set booking reminder.
     *
     * @param int $post_arr        post_arr .
     * @param int $bookingStatuses bookingStatuses .
     * @param int $pid             pid .
     *
     * @return stirng
     */
    public static function setBookingReminder($post_arr, $bookingStatuses, $pid)
    {
        if (empty($post_arr) === true) {
            return;
        }
        $startTime       = $post_arr['wpc_from_time'];
        $date            = $post_arr['wpc_booking_date'];        
        $bookingId       = $pid;
        $bookingStatuses = get_post_meta($bookingId, 'wpc_reservation_state', true);        
        $bookingStart    = date('Y-m-d H:i:s', strtotime($date .' ' . $startTime));
        $buyerMob        = $post_arr['wpc_phone'];
        $customerNotify  = smsalert_get_option('customer_notify', 'smsalert_wcf_general', 'on');
        global $wpdb;
        $tableName           = $wpdb->prefix . 'smsalert_booking_reminder';
        $source = 'wc-cafe';
        $booking_details = $wpdb->get_results("SELECT * FROM $tableName WHERE booking_id = $bookingId and source = '$source'");
        if ($bookingStatuses === 'confirmed' && $customerNotify === 'on') {
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
        if (smsalert_get_option('customer_notify', 'smsalert_wcf_general') !== 'on') {
            return;
        }
        global $wpdb;
        $cronFrequency = BOOKING_REMINDER_CRON_INTERVAL; // pick data from previous CART_CRON_INTERVAL min
        $tableName     = $wpdb->prefix . 'smsalert_booking_reminder';        
        $source        = 'wc-cafe';        
        $schedulerData = get_option('smsalert_wcf_reminder_scheduler');
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
                        $obj[$key]['number']    = $data['phone'];
                        $pid                    = $data['booking_id'];
                        $obj[$key]['sms_body']  = self::parseSmsBody($pid, $customerMessage);
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
        $obj = new WpCafe\Utils\Wpc_Utilities();
        $bookingStatuses = $obj->get_reservation_states();
        foreach ($bookingStatuses as $ks => $vs) {
            $defaults['smsalert_wcf_general']['customer_wcf_notify_' . $ks]   = 'off';
            $defaults['smsalert_wcf_message']['customer_sms_wcf_body_' . $ks] = '';
            $defaults['smsalert_wcf_general']['admin_wcf_notify_' . $ks]      = 'off';
            $defaults['smsalert_wcf_message']['admin_sms_wcf_body_' . $ks]    = '';
        }
        $defaults['smsalert_wcf_general']['otp_enable'] = 'off';
        $defaults['smsalert_wcf_general']['customer_notify'] = 'off';
        $defaults['smsalert_wcf_reminder_scheduler']['cron'][0]['frequency'] = '1';
        $defaults['smsalert_wcf_reminder_scheduler']['cron'][0]['message']   = '';
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
            'checkTemplateFor' => 'wcf_customer',
            'templates'        => self::getCustomerTemplates(),
        );

        $admin_param = array(
            'checkTemplateFor' => 'wcf_admin',
            'templates'        => self::getAdminTemplates(),
        );

        $reminderParam = array(
            'checkTemplateFor' => 'wc_wpcafe_reminder',
            'templates'        => self::getReminderTemplates(),
        );

        $tabs['wp_cafe']['nav']  = 'WP Cafe';
        $tabs['wp_cafe']['icon'] = 'dashicons-food';

        $tabs['wp_cafe']['inner_nav']['wp_cafe_cust']['title']        = 'Customer Notifications';
        $tabs['wp_cafe']['inner_nav']['wp_cafe_cust']['tab_section']  = 'wpcafecusttemplates';
        $tabs['wp_cafe']['inner_nav']['wp_cafe_cust']['first_active'] = true;
        $tabs['wp_cafe']['inner_nav']['wp_cafe_cust']['tabContent']   = $customerParam;
        $tabs['wp_cafe']['inner_nav']['wp_cafe_cust']['filePath']     = 'views/message-template.php';

        $tabs['wp_cafe']['inner_nav']['wp_cafe_admin']['title']       = 'Admin Notifications';
        $tabs['wp_cafe']['inner_nav']['wp_cafe_admin']['tab_section'] = 'wpcafeadmintemplates';
        $tabs['wp_cafe']['inner_nav']['wp_cafe_admin']['tabContent']  = $admin_param;
        $tabs['wp_cafe']['inner_nav']['wp_cafe_admin']['filePath']    = 'views/message-template.php';
        $tabs['wp_cafe']['inner_nav']['wp_cafe_reminder']['title']       = 'Booking Reminder';
        $tabs['wp_cafe']['inner_nav']['wp_cafe_reminder']['tab_section'] = 'bookingremindertemplates';
        $tabs['wp_cafe']['inner_nav']['wp_cafe_reminder']['tabContent']  = $reminderParam;
        $tabs['wp_cafe']['inner_nav']['wp_cafe_reminder']['filePath']    = 'views/booking-reminder-template.php';
		$tabs['wp_cafe']['help_links'] = array(
			'kb_link'      => array(
			'href'   => 'https://kb.smsalert.co.in/knowledgebase/wpcafe-sms-integration/',
			'target' => '_blank',
			'alt'    => 'Read how to integrate with WPCafe',
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
        $currentVal      = smsalert_get_option('customer_notify', 'smsalert_wcf_general', 'on');
        $checkboxNameId  = 'smsalert_wcf_general[customer_notify]';

        $schedulerData  = get_option('smsalert_wcf_reminder_scheduler');
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

            $textAreaNameId   = 'smsalert_wcf_reminder_scheduler[cron][' . $count . '][message]';
            $selectNameId     = 'smsalert_wcf_reminder_scheduler[cron][' . $count . '][frequency]';
            $textBody         = $data['message'];
            $templates[$key]['notify_id']      = 'wc-cafe';
            $templates[$key]['frequency']      = $data['frequency'];
            $templates[$key]['enabled']        = $currentVal;
            $templates[$key]['title']          = 'Send booking reminder to customer';
            $templates[$key]['checkboxNameId'] = $checkboxNameId;
            $templates[$key]['text-body']      = $textBody;
            $templates[$key]['textareaNameId'] = $textAreaNameId;
            $templates[$key]['selectNameId']   = $selectNameId;
            $templates[$key]['token']          = self::getWPCafevariables();

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

        $obj = new WpCafe\Utils\Wpc_Utilities();
        $bookingStatuses = $obj->get_reservation_states();
        $templates = array();
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal = smsalert_get_option('customer_wcf_notify_' . strtolower($vs), 'smsalert_wcf_general', 'on');
            $checkboxNameId = 'smsalert_wcf_general[customer_wcf_notify_' . strtolower($vs) . ']';
            $textareaNameId = 'smsalert_wcf_message[customer_sms_wcf_body_' . strtolower($vs) . ']';
            $defaultTemplate = smsalert_get_option('admin_sms_wcf_body_' . strtolower($vs), 'smsalert_wcf_message', sprintf(__('Hello %1$s, status of your booking #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[name]', '[booking_id]', '[store_name]', $vs, PHP_EOL, PHP_EOL));
            $textBody = smsalert_get_option('customer_sms_wcf_body_' . strtolower($vs), 'smsalert_wcf_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When customer booking is ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getWPCafevariables();
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
        $obj = new WpCafe\Utils\Wpc_Utilities();
        $bookingStatuses = $obj->get_reservation_states();
        $templates = array();
        foreach ($bookingStatuses as $ks  => $vs) {
            $currentVal     = smsalert_get_option('admin_wcf_notify_' . strtolower($vs), 'smsalert_wcf_general', 'on');
            $checkboxNameId = 'smsalert_wcf_general[admin_wcf_notify_' . strtolower($vs) . ']';
            $textareaNameId = 'smsalert_wcf_message[admin_sms_wcf_body_' . strtolower($vs) . ']';

            $defaultTemplate = smsalert_get_option('admin_sms_wcf_body_' . strtolower($vs), 'smsalert_wcf_message', sprintf(__('Hello admin, status of your booking with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', $vs, PHP_EOL, PHP_EOL));


            $textBody = smsalert_get_option('admin_sms_wcf_body_' . strtolower($vs), 'smsalert_wcf_message', $defaultTemplate);

            $templates[$ks]['title']          = 'When admin change status to ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getWPCafevariables();
        }
        return $templates;
    }

    /**
     * Send sms new booking.
     *
     * @param int $pid      pid
     * @param int $post_arr post_arr
     *
     * @return void
     */
    public function sendsmsNewBooking($pid , $post_arr)
    {    
        $bookingStatuses =  get_post_meta($pid, 'wpc_reservation_state', true);
        $this->setBookingReminder($post_arr, $bookingStatuses, $pid);        
        $buyerNumber   = $post_arr['wpc_phone'];
        $customerMessage  = smsalert_get_option('customer_sms_wcf_body_' . $bookingStatuses, 'smsalert_wcf_message', '');
        $customerRrNotify = smsalert_get_option('customer_wcf_notify_' . $bookingStatuses, 'smsalert_wcf_general', 'on');

        if ($customerRrNotify === 'on' && $customerMessage !== '') {
            $buyerMessage = $this->parseSmsBody($pid, $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }

        // Send msg to admin.
        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');

        $nos                = explode(',', $adminPhoneNumber);
        $adminPhoneNumber = array_diff($nos, array('postauthor', 'post_author'));
        $adminPhoneNumber = implode(',', $adminPhoneNumber);

        if (empty($adminPhoneNumber) === false) {

            $adminRrNotify = smsalert_get_option('admin_wcf_notify_' . $bookingStatuses, 'smsalert_wcf_general', 'on');
            $adminMessage   = smsalert_get_option('admin_sms_wcf_body_' . $bookingStatuses, 'smsalert_wcf_message', '');

            if ('on' === $adminRrNotify && '' !== $adminMessage) {
                $adminMessage = $this->parseSmsBody($pid,  $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }
    }
    
    /**
     * Send sms approved pending.
     *
     * @param array $post_arr post_arr
     *
     * @return void
     */
    function bookingUpdate($post_arr)
    {    
        $pid               = $post_arr['ID'];    
        $buyerNumber       = $post_arr['wpc_phone'];
        $bookingStatuses   = $post_arr['wpc_reservation_state'];
        $this->setBookingReminder($post_arr, $bookingStatuses, $pid);        
        $customerMessage   = smsalert_get_option('customer_sms_wcf_body_' . $bookingStatuses, 'smsalert_wcf_message', '');
        $customerRrNotify  = smsalert_get_option('customer_wcf_notify_' . $bookingStatuses, 'smsalert_wcf_general', 'on');
        if ($customerRrNotify === 'on' && $customerMessage !== '') {
            $buyerMessage = $this->parseSmsBody($pid,  $customerMessage);
            do_action('sa_send_sms', $buyerNumber, $buyerMessage);
        }
        // Send msg to admin.
        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
        $nos                = explode(',', $adminPhoneNumber);
        $adminPhoneNumber = array_diff($nos, array('postauthor', 'post_author'));
        $adminPhoneNumber = implode(',', $adminPhoneNumber);

        if (empty($adminPhoneNumber) === false) {

            $adminRrNotify = smsalert_get_option('admin_wcf_notify_' . $bookingStatuses, 'smsalert_wcf_general', 'on');
            $adminMessage   = smsalert_get_option('admin_sms_wcf_body_' . $bookingStatuses, 'smsalert_wcf_message', '');

            if ('on' === $adminRrNotify && '' !== $adminMessage) {
                $adminMessage = $this->parseSmsBody($pid, $adminMessage);
                do_action('sa_send_sms', $adminPhoneNumber, $adminMessage);
            }
        }
    }
   

    /**
     * Parse sms body.
     *
     * @param array  $pid     pid.
     * @param string $content content.
     *
     * @return string
     */
    public function parseSmsBody($pid , $content  )
    { 
        $bookingStatuses =  get_post_meta($pid, 'wpc_reservation_state', true);
        $find = array(
            '[booking_id]',
            '[name]',
            '[email]',
            '[shop_url]',
            '[store_name]',
            '[post_status]',
            
        );

        $replace = array(
            $pid,
            '{user_name}',
            '{user_email}',
            '{site_link}',
            '{site_name}',
        $bookingStatuses,
        );
        
        $content = str_replace($find, $replace, $content);        
        $pattern = '/\[\w+\]/i';            
        $matches = preg_match_all($pattern, $content, $output);
        foreach ($output as $match) {
            foreach ($match as $matches) {                
                $fmvalue =    str_replace(array('[',']'), '', $matches);
                $withbrackets = '{' . $fmvalue . '}';             
                $content = str_replace($matches, $withbrackets, $content);
            } 
        }
        
        $content = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags($pid, $content, $invoice="");
        return $content;
        
        
    }//end parseSmsBody()


    /**
     * Get WP Cafe variables.
     *
     * @return array
     */
    public static function getWPCafevariables()
    {
        $variable['[booking_id]']   = 'Booking Id';
        $variable['[name]']         = 'Name';
        $variable['[email]']        = 'Email';
        $variable['[phone]']        = 'Phone';
        $variable['[current_time]'] = 'Current Time';        
        $variable['[date]']         = 'Booking Date';
        $variable['[party]']        = 'Guest';
        $variable['[message]']      = 'Note';
        $variable['[post_status]']  = 'Post Status';
        $variable['[invoice_no]']   = 'Invoice Id';
        return $variable;
    }//end

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('wp-cafe/wpcafe.php') === true) {
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
        if ((is_plugin_active('wp-cafe/wpcafe.php') === true) && ($islogged === true)) {
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
new SAWPCafe();