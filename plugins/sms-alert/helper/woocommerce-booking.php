<?php
/**
 * Woocommerce booking helper.
 * PHP version 5
 *
 * @category Helper
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */

if (! defined('ABSPATH') ) {
    exit;
}
if (! is_plugin_active('woocommerce-bookings/woocommerce-bookings.php') || ! is_plugin_active('woocommerce/woocommerce.php') ) {
    return;
}
/**
 * PHP version 5
 *
 * @category Helper
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * SmsAlertWcBooking class
 */
class SmsAlertWcBooking
{
    /**
     * Construct function
     *
     * @return array
     */
    public function __construct()
    {
        include_once WP_PLUGIN_DIR . '/woocommerce-bookings/includes/wc-bookings-functions.php';
        add_filter('sAlertDefaultSettings', __CLASS__ . '::add_default_setting', 1);
        self::addActionForBookingStatus();
        add_action('sa_addTabs', array( $this, 'addTabs' ), 10);
        add_action('booking_reminder_sendsms_hook', array( $this, 'sendReminderSms' ), 10);
    }
    
    /**
     * Set booking reminder.
     *
     * @param int $booking_id booking id.
     *
     * @return array
     */
    public static function setBookingReminder( $booking_id )
    {
        $object = get_wc_booking($booking_id);
        if (! is_object($object) ) {
            return;
        }
        $booking_status = $object->status;
        $bookings      = get_post_custom($booking_id);
        $booking_start = date('Y-m-d H:i:s', strtotime(array_shift($bookings['_booking_start'])));
		if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
		   $buyer_mob         = get_post_meta( $order_id, '_billing_phone', true );
		} else {
		   $order     = wc_get_order($object->order_id);
           $buyer_mob = $order->get_meta('_billing_phone');
		}
        
        $customer_notify = smsalert_get_option('customer_notify', 'smsalert_wcbk_general', 'on');
        global $wpdb;
        $table_name           = $wpdb->prefix . 'smsalert_booking_reminder';
        $source = 'woocommerce-bookings';
        $booking_details = $wpdb->get_results("SELECT * FROM $table_name WHERE booking_id = $booking_id and source = '$source'");
        if ('confirmed' === $booking_status && 'on' === $customer_notify ) {
            if ($booking_details ) {
                $wpdb->update(
                    $table_name,
                    array(
                    'start_date' => $booking_start,
                    'phone' => $buyer_mob
                    ),
                    array( 'booking_id' => $booking_id, 'source'=>$source )
                );
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                    'booking_id'   => $booking_id,
                    'phone' => $buyer_mob,
                    'source' => $source,
                    'start_date' => $booking_start
                    )
                );
            }
        } else {
            $wpdb->delete($table_name, array( 'booking_id' => $booking_id ));
        }
    }
    
    /**
     * Send sms function.
     *
     * @return void
     */
    function sendReminderSms()
    {
        if ('on' !== smsalert_get_option('customer_notify', 'smsalert_wcbk_general') ) {
            return;
        }

        global $wpdb;
        $cron_frequency = BOOKING_REMINDER_CRON_INTERVAL; // pick data from previous CART_CRON_INTERVAL min
        $table_name     = $wpdb->prefix . 'smsalert_booking_reminder';
        $source = 'woocommerce-bookings';
        $scheduler_data = get_option('smsalert_wcbk_reminder_scheduler');

        foreach ( $scheduler_data['cron'] as $sdata ) {

            $datetime = current_time('mysql');
            
            $fromdate = date('Y-m-d H:i:s', strtotime('+' . ( $sdata['frequency']*60 - $cron_frequency ) . ' minutes', strtotime($datetime)));
            
            $todate = date('Y-m-d H:i:s', strtotime('+' . $cron_frequency . ' minutes', strtotime($fromdate)));

            $rows_to_phone = $wpdb->get_results(
                'SELECT * FROM ' . $table_name . " WHERE start_date > '" . $fromdate . "' AND start_date <= '" . $todate . "' AND source = '$source' ",
                ARRAY_A
            );
            if ($rows_to_phone ) { // If we have new rows in the database

                   $customer_message = $sdata['message'];
                   $frequency_time   = $sdata['frequency'];
                if ('' !== $customer_message && 0 !== $frequency_time ) {
                    $obj = array();
                    foreach ( $rows_to_phone as $key=>$data ) {
                        $obj[ $key ]['number']    = $data['phone'];
                                 $obj[ $key ]['sms_body']  = self::parseSmsBody($data['booking_id'], $customer_message);
                    }
                    $response     = SmsAlertcURLOTP::sendSmsXml($obj);
                    $response_arr = json_decode($response, true);
                    if (!empty($response_arr['status']) && 'success' === $response_arr['status'] ) {
                        foreach ( $rows_to_phone as $data ) {
                            $last_msg_count = $data['msg_sent'];
                            $total_msg_sent = $last_msg_count + 1;
                            $wpdb->update(
                                $table_name,
                                array(
                                'msg_sent' => $total_msg_sent
                                ),
                                array( 'booking_id' => $data['booking_id'], 'source'=>$source )
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * Add tabs to smsalert settings at backend.
     *
     * @param array $tabs tabs.
     *
     * @return array
     */
    public static function addTabs( $tabs = array() )
    {
        $customer_param = array(
        'checkTemplateFor' => 'wc_booking_customer',
        'templates'        => self::getCustomerTemplates(),
        );

        $admin_param = array(
        'checkTemplateFor' => 'wc_booking_admin',
        'templates'        => self::getAdminTemplates(),
        );
        
        $reminder_param = array(
        'checkTemplateFor' => 'wc_booking_reminder',
        'templates'        => self::getReminderTemplates(),
        );

        $tabs['woocommerce_booking']['nav']  = 'Woocommerce Booking';
        $tabs['woocommerce_booking']['icon'] = 'dashicons-admin-users';

        $tabs['woocommerce_booking']['inner_nav']['wcbk_customer']['title']        = 'Customer Notifications';
        $tabs['woocommerce_booking']['inner_nav']['wcbk_customer']['tab_section']  = 'wcbkcsttemplates';
        $tabs['woocommerce_booking']['inner_nav']['wcbk_customer']['first_active'] = true;
        $tabs['woocommerce_booking']['inner_nav']['wcbk_customer']['tabContent']   = $customer_param;
        $tabs['woocommerce_booking']['inner_nav']['wcbk_customer']['filePath']     = 'views/message-template.php';

        $tabs['woocommerce_booking']['inner_nav']['wcbk_admin']['title']       = 'Admin Notifications';
        $tabs['woocommerce_booking']['inner_nav']['wcbk_admin']['tab_section'] = 'wcbkadmintemplates';
        $tabs['woocommerce_booking']['inner_nav']['wcbk_admin']['tabContent']  = $admin_param;
        $tabs['woocommerce_booking']['inner_nav']['wcbk_admin']['filePath']    = 'views/message-template.php';
        
        $tabs['woocommerce_booking']['inner_nav']['wcbk_reminder']['title']       = 'Booking Reminder';
        $tabs['woocommerce_booking']['inner_nav']['wcbk_reminder']['tab_section'] = 'wcbkremindertemplates';
        $tabs['woocommerce_booking']['inner_nav']['wcbk_reminder']['tabContent']  = $reminder_param;
        $tabs['woocommerce_booking']['inner_nav']['wcbk_reminder']['filePath']    = 'views/booking-reminder-template.php';
        return $tabs;
    }

    /**
     * Get customer templates function.
     *
     * @return array
     */
    public static function getCustomerTemplates()
    {
        $wcbk_order_statuses = self::getBookingStatuses();
        $templates           = array();

        foreach ( $wcbk_order_statuses as $ks  => $vs ) {

            $current_val = smsalert_get_option('wcbk_order_status_' . $vs, 'smsalert_wcbk_general', 'on');

            $check_box_name_id = 'smsalert_wcbk_general[wcbk_order_status_' . $vs . ']';
            $text_area_name_id = 'smsalert_wcbk_message[wcbk_sms_body_' . $vs . ']';

            $text_body = smsalert_get_option('wcbk_sms_body_' . $vs, 'smsalert_wcbk_message', sprintf('Hello %1$s, status of your booking %2$s with %3$s has been changed to %4$s.', '[first_name]', '[booking_id]', '[store_name]', '[booking_status]'));

            $templates[ $ks ]['title']          = 'When Order is ' . ucwords($vs);
            $templates[ $ks ]['enabled']        = $current_val;
            $templates[ $ks ]['status']         = $ks;
            $templates[ $ks ]['text-body']      = $text_body;
            $templates[ $ks ]['checkboxNameId'] = $check_box_name_id;
            $templates[ $ks ]['textareaNameId'] = $text_area_name_id;
            $templates[ $ks ]['token']          = self::getWcBookingvariables();
        }
        return $templates;
    }

    /**
     * Get admin templates function.
     *
     * @return array
     */
    public static function getAdminTemplates()
    {
        $wcbk_order_statuses = self::getBookingStatuses();
        $templates           = array();

        foreach ( $wcbk_order_statuses as $ks  => $vs ) {

            $current_val = smsalert_get_option('wcbk_admin_notification_' . $vs, 'smsalert_wcbk_general', 'on');

            $check_box_name_id = 'smsalert_wcbk_general[wcbk_admin_notification_' . $vs . ']';
            $text_area_name_id = 'smsalert_wcbk_message[wcbk_admin_sms_body_' . $vs . ']';

            $text_body = smsalert_get_option('wcbk_admin_sms_body_' . $vs, 'smsalert_wcbk_message', sprintf('%1$s status of order %2$s has been changed to %3$s.', '[store_name]:', '#[booking_id]', '[booking_status]'));

            $templates[ $ks ]['title']          = 'When Order is ' . ucwords($vs);
            $templates[ $ks ]['enabled']        = $current_val;
            $templates[ $ks ]['status']         = $ks;
            $templates[ $ks ]['text-body']      = $text_body;
            $templates[ $ks ]['checkboxNameId'] = $check_box_name_id;
            $templates[ $ks ]['textareaNameId'] = $text_area_name_id;
            $templates[ $ks ]['token']          = self::getWcBookingvariables();
        }
        return $templates;
    }
    
    /**
     * Get wc renewal templates function.
     *
     * @return array
     * */
    public static function getReminderTemplates()
    {
        $current_val      = smsalert_get_option('customer_notify', 'smsalert_wcbk_general', 'on');
        $checkbox_name_id = 'smsalert_wcbk_general[customer_notify]';

        $scheduler_data = get_option('smsalert_wcbk_reminder_scheduler');
        $templates      = array();
        $count          = 0;
        if (empty($scheduler_data) ) {
			$scheduler_data = array();
            $scheduler_data['cron'][] = array(
            'frequency' => '1',
            'message'   => SmsAlertMessages::showMessage('DEFAULT_WCBK_REMINDER_MESSAGE'),
            );
        }
        foreach ( $scheduler_data['cron'] as $key => $data ) {

            $text_area_name_id = 'smsalert_wcbk_reminder_scheduler[cron][' . $count . '][message]';
            $select_name_id    = 'smsalert_wcbk_reminder_scheduler[cron][' . $count . '][frequency]';
            $text_body         = $data['message'];

            $templates[ $key ]['notify_id']      = 'woocommerce-bookings';
            $templates[ $key ]['frequency']      = $data['frequency'];
            $templates[ $key ]['enabled']        = $current_val;
            $templates[ $key ]['title']          = 'Send booking reminder to customer';
            $templates[ $key ]['checkboxNameId'] = $checkbox_name_id;
            $templates[ $key ]['text-body']      = $text_body;
            $templates[ $key ]['textareaNameId'] = $text_area_name_id;
            $templates[ $key ]['selectNameId']   = $select_name_id;
            $templates[ $key ]['token']          = self::getWcBookingvariables();

            $count++;
        }
        return $templates;
    }

    /**
     * Add action for booking statuses.
     *
     * @return array
     */
    public static function addActionForBookingStatus()
    {
        $wcbk_order_statuses = self::getBookingStatuses();
        foreach ( $wcbk_order_statuses as $wkey => $booking_status ) {
            add_action('woocommerce_booking_' . $booking_status, __CLASS__ . '::wcbkStatusChanged');
        }
    }

    /**
     * Trigger sms on status change of booking.
     *
     * @param int $booking_id booking id.
     *
     * @return array
     */
    public static function wcbkStatusChanged( $booking_id )
    {
        self::setBookingReminder($booking_id);
        $output = self::triggerSms($booking_id);
    }

    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public static function add_default_setting( $defaults = array() )
    {
        $wcbk_order_statuses = self::getBookingStatuses();

        foreach ( $wcbk_order_statuses as $ks => $vs ) {
            $defaults['smsalert_wcbk_general'][ 'wcbk_admin_notification_' . $vs ] = 'off';
            $defaults['smsalert_wcbk_general'][ 'wcbk_order_status_' . $vs ]       = 'off';
            $defaults['smsalert_wcbk_message'][ 'wcbk_admin_sms_body_' . $vs ]     = '';
            $defaults['smsalert_wcbk_message'][ 'wcbk_sms_body_' . $vs ]           = '';
        }
        $defaults['smsalert_wcbk_general']['customer_notify']                = 'off';
        $defaults['smsalert_wcbk_reminder_scheduler']['cron'][0]['frequency'] = '1';
        $defaults['smsalert_wcbk_reminder_scheduler']['cron'][0]['message']   = '';
        return $defaults;
    }

    /**
     * Display woocommerce booking variable at smsalert setting page.
     *
     * @return array
     */
    public static function getWcBookingvariables()
    {
        $variables = array(
        '[order_id]'        => 'Order Id',
        '[store_name]'      => 'Store Name',
        '[booking_id]'      => 'Booking Id',
        '[booking_status]'  => 'Booking status',
        '[product_name]'    => 'Product Name',
        '[booking_cost]'    => 'Booking Amt',
        '[booking_start]'   => 'Booking Start',
        '[booking_end]'     => 'Booking End',
        '[first_name]'      => 'Billing First Name',
        '[last_name]'       => 'Billing Last Name',
        '[booking_persons]' => 'Person Counts',
        '[resource_name]'   => 'Resource Name',
        );
        return $variables;
    }

    /**
     * Get woocommerce booking status.
     *
     * @return array
     */
    public static function getBookingStatuses()
    {
        $status = get_wc_booking_statuses('user', true);
        return array_keys($status);
    }

    /**
     * Trigger sms when woocommerce booking status is changed.
     *
     * @param int $booking_id booking id.
     *
     * @return array
     */
    public static function triggerSms( $booking_id )
    {
        if ($booking_id ) {
            if ('wc_booking' !== get_post_type($booking_id) ) {
                return;
            }

            $object = get_wc_booking($booking_id);
            if (! is_object($object) ) {
                return;
            }

            $booking_status = $object->status;
            $admin_message  = smsalert_get_option('wcbk_admin_sms_body_' . $booking_status, 'smsalert_wcbk_message', '');
            $is_enabled     = smsalert_get_option('wcbk_order_status_' . $booking_status, 'smsalert_wcbk_general');

            $admin_phone_number = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
            $admin_phone_number = str_replace('postauthor', 'post_author', $admin_phone_number);
            if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
			   $buyer_mob         = get_post_meta( $order_id, '_billing_phone', true );
			} else {
			   $order     = wc_get_order($object->order_id);
			   $buyer_mob = $order->get_meta('_billing_phone');
			}

            if ('' !== $buyer_mob && 'on' === $is_enabled ) {
                $buyer_message = smsalert_get_option('wcbk_sms_body_' . $booking_status, 'smsalert_wcbk_message', '');
                $content       = self::parseSmsBody($booking_id, $buyer_message);
                do_action('sa_send_sms', $buyer_mob, $content);
            }

            if ('on' === smsalert_get_option('wcbk_admin_notification_' . $booking_status, 'smsalert_wcbk_general') && '' !== $admin_phone_number ) {

                if (! empty($prod_id) ) {
                    $author_no          = apply_filters('sa_post_author_no', $prod_id);
                    $admin_phone_number = str_replace('post_author', $author_no, $admin_phone_number);
                }

                $admin_message = smsalert_get_option('wcbk_admin_sms_body_' . $booking_status, 'smsalert_wcbk_message', '');
                $content       = self::parseSmsBody($booking_id, $admin_message);
                do_action('sa_send_sms', $admin_phone_number, $content);
            }
        }
    }
    
    /**
     * Parse sms body function.
     *
     * @param int    $booking_id booking id.
     * @param string $content    content.
     *
     * @return array
     */
    public static function parseSmsBody( $booking_id, $content = null )
    {
        $object = get_wc_booking($booking_id);
        $booking_status = $object->status;
        $bookings      = get_post_custom($booking_id);
        $booking_start = date('M d,Y H:i', strtotime(array_shift($bookings['_booking_start'])));
        $booking_end   = date('M d,Y H:i', strtotime(array_shift($bookings['_booking_end'])));
        $person_counts = $object->get_persons_total();
        $resource_name = ( $object->get_resource() ) ? $object->get_resource()->post_title : '';
        $booking_amt   = array_shift($bookings['_booking_cost']);
        $user_info = get_userdata($object->customer_id);
        $first_name    = $user_info->first_name;
        $last_name     = $user_info->last_name;

        if ($object->get_product() ) {
            $product_name = $object->get_product()->get_title();
            $prod_id      = $object->get_product()->get_id();
        }

        if ($object->get_order() ) {
            $order_id = $object->get_order()->get_order_number();
        }

        $variables = array(
        '[order_id]'        => $order_id,
        '[booking_id]'      => $booking_id,
        '[booking_status]'  => $booking_status,
        '[product_name]'    => $product_name,
        '[booking_cost]'    => $booking_amt,
        '[booking_start]'   => $booking_start,
        '[booking_end]'     => $booking_end,
        '[first_name]'      => $first_name,
        '[last_name]'       => $last_name,
        '[booking_persons]' => $person_counts,
        '[resource_name]'   => $resource_name,
        );

        $content = str_replace(array_keys($variables), array_values($variables), $content);

        return $content;
    }
}
new SmsAlertWcBooking();
