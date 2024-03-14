<?php

/**
 * Travel engine helper.
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

if (is_plugin_active('wp-travel-engine/wp-travel-engine.php') === false) {
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
 * SAwptravelengine class 
 */
class SAwptravelengine extends FormInterface
{
    /**
     * Form Session Variable.
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::WP_TRAVEL_ENGINE;
    
    /**
     * If OTP is enabled only for guest users.
     *
     * @var $guest_check_out_only If OTP is enabled only for guest users.
     */
    private $guest_check_out_only;
    
    /**
     * Construct function.
     *
     * @return stirng
     */
    public function handleForm()
    {
        add_filter('wp_travel_engine_booking_fields_display', array( $this, 'addBookingPhoneField' ), 10);
        add_action('wp_travel_engine_after_booking_process_completed', array( $this, 'sendsmsNewBooking' ), 10, 1);
        add_filter('wte_before_update__prev_booking_status', array($this, 'sendSmsOnUpdate'), 10, 2);
        $this->guest_check_out_only      = ( smsalert_get_option('checkout_show_otp_guest_only', 'smsalert_general') === 'on' ) ? true : false;             
        add_action('wte_booking_before_submit_button', array($this, 'addShortcode'), 100);     
        $buyer_signup_otp = smsalert_get_option('buyer_signup_otp', 'smsalert_general');
        if ('on' === $buyer_signup_otp ) {
			if (isset($_REQUEST['register']) ) {
               add_filter('wp_travel_engine_registration_errors', array( $this, 'wptSiteRegistrationErrors' ), 10, 3);
			}
			add_action('wp_travel_engine_after_registration_form_password', array($this, 'addPhoneField'), 100, 1);			
        }		
    }
	
	/**
     * This function shows registration error message.
     *
     * @param array  $errors    Errors array.
     * @param string $username  User Name.
     * @param string $email     Email.
     *
     * @throws Exception Validation errors.
     *
     * @return void
     */
    public function wptSiteRegistrationErrors( $errors, $username, $email )
    {
        SmsAlertUtility::checkSession();
		 $user_phone = ( ! empty($_POST['billing_phone']) ) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';
		if (! SmsAlertcURLOTP::validateCountryCode($user_phone)){		
			return $errors;
		}
        if (isset($_SESSION['sa_mobile_verified']) ) {
            unset($_SESSION['sa_mobile_verified']);
            return $errors;
        }
		
        if ( $errors->get_error_code() ) {
			return $errors;
		}
        $username = ! empty($_REQUEST['username']) ? sanitize_text_field(wp_unslash($_REQUEST['username'])) : '';
        $email    = ! empty($_REQUEST['email']) ? sanitize_text_field(wp_unslash($_REQUEST['email'])) : '';
        $password = ! empty($_REQUEST['password']) ? sanitize_text_field(wp_unslash($_REQUEST['password'])) : '';
        if (isset($_REQUEST['option']) && 'smsalert_register_with_otp' === sanitize_text_field(wp_unslash($_REQUEST['option'])) ) {
            SmsAlertUtility::initialize_transaction($this->form_session_var);
        }

       
		
		if ('on' !== smsalert_get_option('allow_multiple_user', 'smsalert_general') && ! SmsAlertUtility::isBlank($user_phone) ) {

            $getusers = SmsAlertUtility::getUsersByPhone('billing_phone', $user_phone);
            if (count($getusers) > 0 ) {
                return new WP_Error('registration-error-number-exists', __('An account is already registered with this mobile number. Please login.', 'sms-alert'));
            }
        }

        if (isset($user_phone) && SmsAlertUtility::isBlank($user_phone) ) {
            return new WP_Error('registration-error-invalid-phone', __('Please enter phone number.', 'sms-alert'));
        }

        return $this->processFormFields($username, $email, $errors, $password);
    }
	
	 /**
     * This function processed form fields.
     *
     * @param string $username User name.
     * @param string $email    Email Id.
     * @param array  $errors   Errors array.
     * @param string $password Password.
     *
     * @return void
     */
    public function processFormFields( $username, $email, $errors, $password )
    {
        global $phoneLogic;
        $phone_no  = ( ! empty($_POST['billing_phone']) ) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';
        $phone_num = preg_replace('/[^0-9]/', '', $phone_no);

        if (! isset($phone_num) || ! SmsAlertUtility::validatePhoneNumber($phone_num) ) {
            return new WP_Error('billing_phone_error', str_replace('##phone##', $phone_num, $phoneLogic->_get_otp_invalid_format_message()));
        }
        smsalert_site_challenge_otp($username, $email, $errors, $phone_num, 'phone', $password);
    }

    /**
     * Add Shortcode for OTP and Add additional js code to your script
     *
     * @return stirng
     * */
    public function addShortcode()
    {
        if ($this->guest_check_out_only && is_user_logged_in() ) {
            return;
        }
        if (smsalert_get_option('otp_enable', 'smsalert_te_general') === 'on') {
            echo do_shortcode('[sa_verify phone_selector="#billing_phone" submit_selector= "wp_travel_engine_nw_bkg_submit"]');
        }
    }
    
    /**
     * Add Shortcode for OTP and Add additional js code to your script
     *
     * @param int $setting setting.
     *
     * @return stirng
     * */
    public function addPhoneField($setting)
    {
        ?>
		<div class='wpte-lrf-field lrf-phone'>
            <label><?php echo esc_attr__('Phone', 'sms-alert'); ?><span class='required'>*</span></label>
            <input required data-parsley-required-message="<?php esc_attr_e('Please enter a valid phone number', 'sms-alert'); ?>" name='billing_phone' class="phone-valid" type='text' placeholder='<?php echo esc_attr__('Phone number', 'sms-alert'); ?>'/>
        </div>
        <?php
        echo do_shortcode('[sa_verify phone_selector="billing_phone" submit_selector= "register"]');       
    }
    
    /**
    * Add Booking Phone Field
    *     
    * @return array
    */
    public function addBookingPhoneField()
    {    
        $booking_phone = '';    
        $fields1 = WTE_Default_Form_Fields::booking();     
        $fields2 = array(
        'booking_phone'  => array(
        'type'           => 'text',
        'wrapper_class'  => 'wp-travel-engine-billing-details-field-wrap',
        'field_label'    => __('Phone', 'wp-travel-engine'),
        'label_class'    => 'wpte-bf-label',
        'name'           => 'wp_travel_engine_booking_setting[place_order][booking][phone]',
        'id'             => 'billing_phone',
        'validations'    => array(
        'required'  => true,
        'maxlength' => '50',
        'type'      => 'alphanum',
        ),
        'attributes'     => array(
        'data-msg'                      => __('Please enter your phone number', 'sms-alert'),
        'data-parsley-required-message' => __('Please enter your phone number', 'sms-alert'),
        ),
        'default'        => $booking_phone,
        'priority'       => 20,
        'default_field'  => true,
        'required_field' => true,
        )
        );
        $fields = array_merge($fields1, $fields2);
        return $fields;
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
        $bookingStatuses = array( 'pending', 'booked', 'refunded', 'canceled');         
        foreach ($bookingStatuses as $ks => $vs) {
            $defaults['smsalert_te_general']['customer_te_notify_' . $vs]   = 'off';
            $defaults['smsalert_te_message']['customer_sms_te_body_' . $vs] = '';
            $defaults['smsalert_te_general']['admin_te_notify_' . $vs]      = 'off';
            $defaults['smsalert_te_message']['admin_sms_te_body_' . $vs]    = '';
        }
        $defaults['smsalert_te_general']['otp_enable']                      = 'off';
        $defaults['smsalert_te_general']['customer_notify']                 = 'off';
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
            'checkTemplateFor' => 'te_customer',
            'templates'        => self::getCustomerTemplates(),
        );
        $admin_param = array(
            'checkTemplateFor' => 'te_admin',
            'templates'        => self::getAdminTemplates(),
        );
        $tabs['travel-engine']['nav']           = 'Travel Engine';
        $tabs['travel-engine']['icon']          = 'dashicons-calendar';
        $tabs['travel-engine']['inner_nav']['travel-engine_cust']['title']          = 'Customer Notifications';
        $tabs['travel-engine']['inner_nav']['travel-engine_cust']['tab_section']    = 'travelenginecusttemplates';
        $tabs['travel-engine']['inner_nav']['travel-engine_cust']['first_active']   = true;
        $tabs['travel-engine']['inner_nav']['travel-engine_cust']['tabContent']     = $customerParam;
        $tabs['travel-engine']['inner_nav']['travel-engine_cust']['filePath']       = 'views/message-template.php';

        $tabs['travel-engine']['inner_nav']['travel-engine_admin']['title']         = 'Admin Notifications';
        $tabs['travel-engine']['inner_nav']['travel-engine_admin']['tab_section']   = 'travelengineadmintemplates';
        $tabs['travel-engine']['inner_nav']['travel-engine_admin']['tabContent']    = $admin_param;
        $tabs['travel-engine']['inner_nav']['travel-engine_admin']['filePath']      = 'views/message-template.php';        
        return $tabs;
    }

    /**
     * Get customer templates.
     *
     * @return array
     */
    public static function getCustomerTemplates()
    {        
        $bookingStatuses = [
            '[pending]'  => 'Pending',
            '[booked]'  => 'Booked',
            '[refunded]' => 'Refunded',
            '[canceled]'    => 'Canceled',
        ];

        $templates           = [];
        foreach ($bookingStatuses as $ks => $vs) {
            $currentVal      = smsalert_get_option('customer_te_notify_' . strtolower($vs), 'smsalert_te_general', 'on');
            $checkboxNameId  = 'smsalert_te_general[customer_te_notify_' . strtolower($vs) . ']';
            $textareaNameId  = 'smsalert_te_message[customer_sms_te_body_' . strtolower($vs) . ']';
            $defaultTemplate = smsalert_get_option('customer_sms_te_body_' . strtolower($vs), 'smsalert_te_message', sprintf(__('Hello %1$s, status of your booking #%2$s with %3$s has been changed to %4$s.%5$sPowered by%6$swww.smsalert.co.in', 'sms-alert'), '[fname]', '[booking_id]', '[store_name]', strtolower($vs), PHP_EOL, PHP_EOL));
            $textBody       = smsalert_get_option('customer_sms_te_body_' . strtolower($vs), 'smsalert_te_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When customer booking is ' . ucwords($vs);
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getWpTraveVariable();
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
        $bookingStatuses = [
            '[pending]'  => 'Pending',
            '[booked]'  => 'Booked',
            '[refunded]' => 'Refunded',
            '[canceled]'    => 'Canceled',
        ];
        $templates           = [];
        foreach ($bookingStatuses as $ks => $vs) {
            $currentVal      = smsalert_get_option('admin_te_notify_' . strtolower($vs), 'smsalert_te_general', 'on');
            $checkboxNameId  = 'smsalert_te_general[admin_te_notify_' . strtolower($vs) . ']';
            $textareaNameId  = 'smsalert_te_message[admin_sms_te_body_' . strtolower($vs) . ']';
            $defaultTemplate = smsalert_get_option('admin_sms_te_body_' . strtolower($vs), 'smsalert_te_message', sprintf(__('Hello admin, status of your booking with %1$s has been changed to %2$s. %3$sPowered by%4$swww.smsalert.co.in', 'sms-alert'), '[store_name]', strtolower($vs), PHP_EOL, PHP_EOL));
            $textBody = smsalert_get_option('admin_sms_te_body_' . strtolower($vs), 'smsalert_te_message', $defaultTemplate);
            $templates[$ks]['title']          = 'When admin change status to ' . $vs;
            $templates[$ks]['enabled']        = $currentVal;
            $templates[$ks]['status']         = $vs;
            $templates[$ks]['text-body']      = $textBody;
            $templates[$ks]['checkboxNameId'] = $checkboxNameId;
            $templates[$ks]['textareaNameId'] = $textareaNameId;
            $templates[$ks]['token']          = self::getWpTraveVariable();
        }
        return $templates;
    }

    /**
     * Send sms On update.
     *
     * @param int   $booking_id booking_id
     * @param array $data       data
     *
     * @return void
     */
    public function sendSmsOnUpdate($booking_id, $data)
    {
        $booking_id = $data->ID;   
        $this->sendsmsNewBooking($booking_id);
    }      

     /**
      * Send sms new booking.
      *      
      * @param int $booking_id booking_id
      *
      * @return void
      */
    public function sendsmsNewBooking($booking_id)
    {         
        $booking_metas = get_post_meta($booking_id, 'wp_travel_engine_booking_setting', true);        
        $buyerNumber   = $booking_metas['place_order']['booking']['phone']; 
        $bookingStatuss = get_post_meta($booking_id, 'wp_travel_engine_booking_status', true);
        $bookingStatus = !empty($_POST['wp_travel_engine_booking_status']) ? $_POST['wp_travel_engine_booking_status'] : $bookingStatuss;        
        $customerMessage   = smsalert_get_option('customer_sms_te_body_' . $bookingStatus, 'smsalert_te_message', '');
        $customerNotify    = smsalert_get_option('customer_te_notify_' . $bookingStatus, 'smsalert_te_general', 'on');
        if (($customerNotify === 'on' && $customerMessage !== '')) {
            $buyerMessage = $this->parseSmsBody($booking_id, $customerMessage);
            $obj             = array();
                $obj['number']   = $buyerNumber;
                $obj['sms_body'] = $buyerMessage;
                SmsAlertcURLOTP::sendsms($obj);           

        }
            // Send msg to admin.
        $adminPhoneNumber = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
        if (empty($adminPhoneNumber) === false) {
            $adminNotify        = smsalert_get_option('admin_te_notify_' . $bookingStatus, 'smsalert_te_general', 'on');
            $adminMessage       = smsalert_get_option('admin_sms_te_body_' . $bookingStatus, 'smsalert_te_message', '');
            $nos = explode(',', $adminPhoneNumber);
            $adminPhoneNumber   = array_diff($nos, array('postauthor', 'post_author'));
            $adminPhoneNumber   = implode(',', $adminPhoneNumber);
            if ($adminNotify === 'on' && $adminMessage !== '') {
                $adminMessage   = $this->parseSmsBody($booking_id, $adminMessage);
                $obj             = array();
                $obj['number']   = $adminPhoneNumber;
                $obj['sms_body'] = $adminMessage;
                SmsAlertcURLOTP::sendsms($obj);
            }
        } 
    }
    
    /**
     * Parse sms body.
     *
     * @param array  $booking_id booking_id.
     * @param string $content    content.
     *
     * @return string
     */
    public function parseSmsBody($booking_id, $content = null)
    {
        $booking_metas         = get_post_meta($booking_id, 'wp_travel_engine_booking_setting', true);        
        $bookingStatuss     = get_post_meta($booking_id, 'wp_travel_engine_booking_status', true);        
        $bookingId             = $booking_id;
        $fname              = $booking_metas['place_order']['booking']['fname'];
        $lname              = $booking_metas['place_order']['booking']['lname'];
        $email                 = $booking_metas['place_order']['booking']['email'];
        $address            = $booking_metas['place_order']['booking']['address'];
        $city                 = $booking_metas['place_order']['booking']['city'];
        $country               = $booking_metas['place_order']['booking']['country'];
        $phone                 = $booking_metas['place_order']['booking']['phone'];
        $persons              = $booking_metas['place_order']['traveler'];
        $cost                  = $booking_metas['place_order']['cost'];
        $tripName              = $booking_metas['place_order']['tname'];
         $tripStartDate     = $booking_metas['place_order']['datetime'];
        $tripDuration       = $booking_metas['place_order']['tduration'];
        $tripEndDate        = $booking_metas['place_order']['tenddate'];
        $Adult              = $booking_metas['place_order']['Adult'];
        $Child              = $booking_metas['place_order']['Child'];
        $trip_package       = $booking_metas['place_order']['trip_package'];       
        $postStatus            = !empty($_POST['wp_travel_engine_booking_status']) ? $_POST['wp_travel_engine_booking_status'] : $bookingStatuss;

        $find = array(
            '[booking_id]',
            '[fname]',
            '[lname]',
            '[email]',
            '[address]',
            '[city]',
            '[country]',            
            '[phone]',
            '[persons]',
            '[cost]',
            '[tripName]',
            '[tripStartDate]',
            '[tripDuration]',
            '[tripEndDate]',
            '[Adult]',
            '[Child]',
            '[trip_package]',
            '[status]'
        );

        $replace = array(
        $bookingId,
        $fname,
        $lname,
        $email,
        $address,
        $city,
        $country,
        $phone,
        $persons,
        $cost,
        $tripName,
        $tripStartDate,
        $tripDuration,
        $tripEndDate,
        $Adult,
        $Child,
        $trip_package,    
        $postStatus,
        );
        $content = str_replace($find, $replace, $content);
        return $content;
    }

    /**
     * Get Restaurant Reservations variables.
     *
     * @return array
     */
    public static function getWpTraveVariable()
    {                   
        $variable['[booking_id]']          = 'Booking Id';
        $variable['[fname]']            = 'First Name';
        $variable['[lname]']               = 'Last Name';
        $variable['[email]']               = 'Email';
        $variable['[address]']          = 'Address';
        $variable['[city]']                = 'City';
        $variable['[country]']          = 'Country';
        $variable['[phone]']              = 'Phone';
        $variable['[persons]']            = 'TotalPersons';
        $variable['[cost]']               = 'Total Cost';
        $variable['[tripName]']         = 'Trip Name';
        $variable['[tripStartDate]']    = 'Trip Start Date';
        $variable['[tripEndDate]']      = 'Trip End Date';
        $variable['[Adult]']               = 'Total Adult';
        $variable['[Child]']               = 'Total Child';
        $variable['[trip_package]']     = 'Trip Package';
        $variable['[status]']             = 'Status';
        return $variable;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('wp-travel-engine/wp-travel-engine.php') === true) {
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
        if ((is_plugin_active('wp-travel-engine/wp-travel-engine.php') === true) && ($islogged === true)) {
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
        if (! isset($_SESSION[ $this->form_session_var ]) ) {
            return;
        }
        if (isset($_SESSION[ $this->form_session_var ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
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
        if (! isset($_SESSION[ $this->form_session_var ]) ) {
            return;
        }
        $_SESSION['sa_mobile_verified'] = true;
        if (isset($_SESSION[ $this->form_session_var ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('VALID_OTP'), 'success'));
        }
    }

    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {
       unset($_SESSION[ $this->tx_session_id ]);
       unset($_SESSION[ $this->form_session_var ]);
    }

    /**
     * Check current form submission is ajax or not
     *
     * @param bool $is_ajax bool value for form type.
     *
     * @return bool
     */
    public function is_ajax_form_in_play($is_ajax)
    {        
        SmsAlertUtility::checkSession();
        return isset($_SESSION[ $this->form_session_var ]) ? true : $is_ajax;
    }
}
new SAwptravelengine();