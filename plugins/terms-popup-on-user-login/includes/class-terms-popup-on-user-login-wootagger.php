<?php

class TPUL_Woo_Tagger {
    public function __construct() {
        // add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'display_cookie_value_in_order'), 10, 1);
        // add_action('woocommerce_checkout_create_order', array($this, 'associate_cookie_with_order'), 10, 2);
    }

    public function display_cookie_value_in_order($order) {
        if (class_exists('WooCommerce')) {

            // Get the cookie value from order meta data
            // $order = wc_get_order($order_id);
            if (false === $order) {
                // Log error
                // Display an error message
                return;
            }
            $tpul_visitor_id = $order->get_meta('tpul_visitor_id');

            // Display the cookie value
            if ($tpul_visitor_id) {
                echo '<p><strong>Terms Popup Acceptance Visitor ID:</strong><br />';
                echo esc_html($tpul_visitor_id) . '</p>';
            } else {
                echo '<p><strong>Terms Popup Acceptance Visitor ID:</strong> ';
            }
        }
    }

    public function associate_cookie_with_order($order, $data) {
        if (class_exists('WooCommerce')) {


            // Get the cookie value
            $tpul_visitor_id = isset($_COOKIE['tpul_visitor_id']) ? $_COOKIE['tpul_visitor_id'] : '';

            // Store the cookie value as order meta data
            if ($tpul_visitor_id) {
                $order->update_meta_data('tpul_visitor_id', $tpul_visitor_id);
                $order->save();
            }
        }
    }

    public function log_cookie_email_association($order, $data) {

        // Get the email address provided during checkout
        $email_address = $data['billing_email'];

        // Get the cookie value
        $tpul_visitor_id = isset($_COOKIE['tpul_visitor_id']) ? $_COOKIE['tpul_visitor_id'] : '';

        // Format the data

        // Path to the log file

        /**
         * Log Action if Addvanced Logging is turned on
         */


        //  $data = [
        //     'created_at' => current_time('mysql'),
        //     'the_user_id' => $user_id,
        //     'user_displayname' => $user->display_name,
        //     'user_username' => $user->user_login,
        //     'user_action' => 'Accepted'
        // ];

        $logging = get_option('tpul_addv_logging');
        if ($logging) {
            $order_id = $order->get_id();
            $data = [
                'created_at' => current_time('mysql'),
                'the_user_id' => "visitor",
                'user_displayname' => "Order: {$order_id} Visitor ID: {$tpul_visitor_id}",
                'user_username' => $email_address,
                'user_action' => 'Accepted'
            ];

            $create_log_table = termspul\Tpul_DB::insert($data);
        }

        // $log_data = date('Y-m-d H:i:s') . ' - Email: ' . $email_address . ', Cookie: ' . $tpul_visitor_id . PHP_EOL;
        // $log_data = json_encode($data) . PHP_EOL;
        // $log_file = WP_CONTENT_DIR . '/TPUL_user_woo_accept.txt';
        // file_put_contents($log_file, $log_data, FILE_APPEND);
    }
}

// Instantiate the class
new TPUL_Woo_Tagger();
