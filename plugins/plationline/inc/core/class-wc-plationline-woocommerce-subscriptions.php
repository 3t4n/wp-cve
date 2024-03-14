<?php

namespace PlatiOnlinePO6\Inc\Core;

use PlatiOnlinePO6\Inc\Core\WC_PlatiOnline as WC_PlatiOnline;
use PlatiOnlinePO6\Inc\Core\WC_Plationline_Process as WC_Plationline_Process;
use PlatiOnlinePO6\Inc\Libraries\PO5 as PO5;

/**
 * @link              https://plati.online
 * @since             6.2.0
 * @package           PlatiOnlinePO6
 *
 */
class WC_PlatiOnline_Woocommerce_Subscriptions extends \WC_Payment_Gateway
{
    public function __construct()
    {
        $this->id = 'plationline_woocommerce_subscriptions';
        $this->method_title = __('PlatiOnline Subscriptions', 'plationline');
        $this->method_description = __('Process recurrent payments with PlatiOnline using Woocommerce Subscriptions plugin', 'plationline');

        $this->has_fields = false;
        $this->init_form_fields();
        $this->init_settings();

        $this->icon = (!empty($this->settings['show_logos']) && $this->settings['show_logos'] === 'yes') ? 'https://media.plationline.ro/logo/logo-po-subscription.png' : '';
        $this->title = $this->settings['title'];
        $this->description = $this->settings['description'];
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        add_filter('woocommerce_available_payment_gateways', array($this, 'plationline_woocommerce_subscriptions_filter_woocommerce_available_payment_gateways'), 10, 1);
        add_filter('woocommerce_thankyou_' . $this->id, array($this, 'plationline_woocommerce_subscriptions_order_received'), 10, 1);
        add_action('woocommerce_subscription_status_updated', array($this, 'plationline_woocommerce_subscriptions_action_status_changed'), 10, 3);

        if (!$this->is_valid_for_use()) {
            $this->enabled = 'no';
        }

        // check Mixed Checkout, we don't allow that
        if (\wc_string_to_bool(get_option('woocommerce_subscriptions_multiple_purchase')) == true) {
            $this->enabled = 'no';
        }

        // support for Woocommerce subscriptions
        $this->supports = array(
            'subscriptions',
            'subscription_cancellation',
            'subscription_suspension',
            'subscription_reactivation',
            'gateway_scheduled_payments',
        );
    }

    public function init_settings()
    {
        parent::init_settings();
    }

    public function is_valid_for_use()
    {
        return in_array(\get_woocommerce_currency(), array('RON', 'EUR', 'USD'));
    }

    public function plationline_woocommerce_subscriptions_filter_woocommerce_available_payment_gateways($gateways)
    {
        if (is_checkout()) {
            $carts = \WC()->cart->recurring_carts;
            if (!empty($carts)) {
                foreach ($carts as $cart) {
                    $tick = \wcs_cart_pluck($cart, 'subscription_period_interval');
                    $interval = \wcs_cart_pluck($cart, 'subscription_period', '');
                    $length = \wcs_cart_pluck($cart, 'subscription_length');
                    $signup_fee = \wcs_cart_pluck($cart, 'subscription_sign_up_fee');
                    $trial = \wcs_cart_pluck($cart, 'subscription_trial_length');

                    if (self::validate_recurrence_params_for_plationline($tick, $interval, $length) == false) {
                        unset($gateways[$this->id]);
                        break;
                    }
                    // momentan nu suportam signup fee
                    if ((int)$signup_fee > 0) {
                        unset($gateways[$this->id]);
                        break;
                    }
                    // momentan nu suportam trial period
                    if ((int)$trial > 0) {
                        unset($gateways[$this->id]);
                        break;
                    }
                }
            } else {
                unset($gateways[$this->id]);
            }
        }
        return $gateways;
    }

    public function validate_recurrence_params_for_plationline($tick, $interval, $length)
    {
        $is_valid = true;
        switch ($interval) {
            case 'day':
                $is_valid = false;
                break;
            case 'week':
                if (!in_array($tick, array(1, 2))) {
                    $is_valid = false;
                }
                break;
            case 'month':
                if (!in_array($tick, array(1, 3, 6))) {
                    $is_valid = false;
                    break;
                }
                if ($length > 60) {
                    $is_valid = false;
                    break;
                }
                break;
            case 'year':
                if ($tick != 1) {
                    $is_valid = false;
                    break;
                }
                break;
        }

        return $is_valid;
    }

    public static function recurrence_calculate_f_recurring_frequency($tick, $interval, $length)
    {
        $f_recurring_frequency = null;
        switch ($interval) {
            case 'week':
                switch ($tick) {
                    case 1:
                        $f_recurring_frequency = 1;
                        break;
                    case 2:
                        $f_recurring_frequency = 2;
                        break;
                }
                if ($length == 0) {
                    $length = 260; // 260 saptamani = 5 ani
                }
                break;
            case 'month':
                switch ($tick) {
                    case 1:
                        $f_recurring_frequency = 3;
                        break;
                    case 3:
                        $f_recurring_frequency = 4;
                        break;
                    case 6:
                        $f_recurring_frequency = 5;
                        break;
                }
                if ($length == 0) {
                    $length = 60; // 60 luni = 5 ani
                }
                break;

            case 'year':
                if ($tick == 1) {
                    $f_recurring_frequency = 8;
                }
                if ($length == 0) {
                    $length = 5; // 5 ani
                }
                break;
        }

        return array('f_recurring_frequency' => $f_recurring_frequency, 'length' => $length);
    }

    public function admin_options()
    {
        echo '<h2>' . \esc_html($this->get_method_title());
        \wc_back_link(__('Return to payments', 'woocommerce'), \admin_url('admin.php?page=wc-settings&tab=checkout'));
        echo '</h2>';
        echo '<div class="inline error">
			<p>' . __('Available if Mixed Checkout is turned off', 'plationline') . '</p>
			<p>' . __('Available for the following recurrence frequencies:', 'plationline') . '</p>
			<ol>
				<li>' . __('Weekly', 'plationline') . '</li>
				<li>' . __('Every 2 weeks', 'plationline') . '</li>
				<li>' . __('Monthly', 'plationline') . '</li>
				<li>' . __('Quarterly', 'plationline') . '</li>
				<li>' . __('Semestrial', 'plationline') . '</li>
				<li>' . __('Annually', 'plationline') . '</li>
			</ol>
			<p>' . __('Available for the following recurrence durations:', 'plationline') . '</p>
			<ol>
				<li>' . \sprintf(__('%d months', 'plationline'), 6) . '</li>
				<li>' . \sprintf(__('%d months', 'plationline'), 12) . '</li>
				<li>' . \sprintf(__('%d months', 'plationline'), 24) . '</li>
				<li>' . \sprintf(__('%d months', 'plationline'), 36) . '</li>
				<li>' . \sprintf(__('%d months', 'plationline'), 48) . '</li>
				<li>' . \sprintf(__('%d months', 'plationline'), 60) . '</li>
			</ol>
			</div>';

        // Generate the HTML For the settings form.
        echo '<table class="form-table">';
        $this->generate_settings_html();
        echo '</table>';
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Active', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Activate recurrent payments by PlatiOnline for Woocommerce Subscriptions', 'plationline'),
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Payment method title', 'plationline'),
                'type' => 'text',
                'description' => __('This title will be shown in frontend to the customer', 'plationline'),
                'default' => __('Recurrent payments by PlatiOnline for Woocommerce Subscriptions', 'plationline'),
            ),
            'description' => array(
                'title' => __('Payment method description', 'plationline'),
                'type' => 'textarea',
                'default' => __('You will be redirected to PlatiOnline page', 'plationline'),
            ),
            'show_logos' => array(
                'title' => __('Show payment logos in frontend', 'plationline'),
                'type' => 'checkbox',
                'label' => __('Show logos', 'plationline'),
                'description' => __('Show PlatiOnline Recurrence logos in checkout', 'plationline'),
                'default' => 'yes',
            ),
        );
    }

    /**
     * Process the payment and return the result
     **/
    public function process_payment($order_id)
    {
        $order = new \WC_Order($order_id);
        $order->update_status('pending', __('PO Pending Authorization', 'plationline'));
        return array('result' => 'success', 'redirect' => $order->get_checkout_payment_url(true));
    }

    public static function setFRequestAuth($order_id, $f_action = 20)
    {
        if (\wcs_order_contains_subscription($order_id)) {
            $order = new \WC_Order($order_id);

            $subscriptions = \wcs_get_subscriptions_for_order($order);
            $subscription = \array_pop($subscriptions);

            $f_request = array();
            $f_request['f_order_number'] = $order_id;
            $f_request['f_amount'] = \round($subscription->get_total(), 2);
            $f_request['f_currency'] = $order->get_currency();

            if (\strtolower($f_request['f_currency']) == 'lei') {
                $f_request['f_currency'] = 'RON';
            }

            $f_request['f_language'] = \strtolower(\substr(get_bloginfo('language'), 0, 2));

            $permitted_languages = array('en', 'ro', 'it', 'fr', 'de', 'es');
            if (!in_array($f_request['f_language'], $permitted_languages)) {
                $f_request['f_language'] = 'en';
            }

            $customer_info = array();

            //contact
            if ($order->get_billing_email()) {
                $customer_info['contact']['f_email'] = $order->get_billing_email();
            }
            if (\strlen($order->get_billing_phone()) >= 4) {
                $customer_info['contact']['f_phone'] = $order->get_billing_phone();
                $customer_info['contact']['f_mobile_number'] = $order->get_billing_phone();
            }
            $customer_info['contact']['f_send_sms'] = 1; // 1 - sms client notification 0 - no notification
            if (\substr($order->get_billing_first_name(), 0, 50)) {
                $customer_info['contact']['f_first_name'] = \substr($order->get_billing_first_name(), 0, 50);
            }
            if (\substr($order->get_billing_last_name(), 0, 50)) {
                $customer_info['contact']['f_last_name'] = \substr($order->get_billing_last_name(), 0, 50);
            }

            if (empty($customer_info['contact']['f_first_name']) || empty($customer_info['contact']['f_last_name'])) {
                $name = explode(' ', $order->get_billing_first_name(), 2);
                $customer_info['contact']['f_first_name'] = $name[0];
                $customer_info['contact']['f_last_name'] = $name[1];
            }

            if (empty($customer_info['contact']['f_first_name'])) {
                unset($customer_info['contact']['f_first_name']);
            }

            if (empty($customer_info['contact']['f_last_name'])) {
                unset($customer_info['contact']['f_last_name']);
            }

            //$customer_info['contact']['f_middle_name'] 	 = '';

            //invoice
            $customer_info['invoice']['f_company'] = $order->get_billing_company() ? \substr($order->get_billing_company(), 0, 50) : '-';
            $customer_info['invoice']['f_cui'] = '-';
            $customer_info['invoice']['f_reg_com'] = '-';
            $customer_info['invoice']['f_cnp'] = '-';
            $customer_info['invoice']['f_zip'] = $order->get_billing_postcode() ?: '-';

            if ($order->get_billing_country()) {
                $customer_info['invoice']['f_country'] = $order->get_billing_country();
            }
            if ($order->get_billing_state()) {
                $customer_info['invoice']['f_state'] = $order->get_billing_state();
            }
            if ($order->get_billing_city()) {
                $customer_info['invoice']['f_city'] = $order->get_billing_city();
            }
            if (\substr($order->get_billing_address_1() . $order->get_billing_address_2(), 0, 100)) {
                $customer_info['invoice']['f_address'] = \substr($order->get_billing_address_1() . ' ' . $order->get_billing_address_2(), 0, 100);
            }

            $f_request['customer_info'] = $customer_info;
            if ($order->has_shipping_address()) {
                $shipping_info = array();

                $shipping_info['same_info_as'] = 0; // 0 - different info, 1- same info as customer_info

                //contact
                $shipping_info['contact']['f_email'] = $order->get_billing_email();

                if (\strlen($order->get_billing_phone()) >= 4) {
                    $shipping_info['contact']['f_phone'] = $order->get_billing_phone();
                    $shipping_info['contact']['f_mobile_number'] = $order->get_billing_phone();
                }

                $shipping_info['contact']['f_send_sms'] = 1; // 1 - sms client notification 0 - no notification
                if ($order->get_shipping_first_name()) {
                    $shipping_info['contact']['f_first_name'] = \substr($order->get_shipping_first_name(), 0, 50);
                }
                if ($order->get_shipping_last_name()) {
                    $shipping_info['contact']['f_last_name'] = \substr($order->get_shipping_last_name(), 0, 50);
                }
                $shipping_info['contact']['f_middle_name'] = '';

                if (empty($shipping_info['contact']['f_first_name']) || empty($shipping_info['contact']['f_last_name'])) {
                    $name = explode(' ', $order->get_shipping_first_name(), 2);
                    $shipping_info['contact']['f_first_name'] = $name[0];
                    $shipping_info['contact']['f_last_name'] = $name[1];
                }

                if (empty($shipping_info['contact']['f_first_name'])) {
                    unset($shipping_info['contact']['f_first_name']);
                }

                if (empty($shipping_info['contact']['f_last_name'])) {
                    unset($shipping_info['contact']['f_last_name']);
                }

                //address
                $shipping_info['address']['f_company'] = $order->get_shipping_company() ? \substr($order->get_shipping_company(), 0, 50) : '-';
                $shipping_info['address']['f_zip'] = $order->get_shipping_postcode() ?: '-';
                if ($order->get_shipping_country()) {
                    $shipping_info['address']['f_country'] = $order->get_shipping_country();
                }
                if ($order->get_shipping_state()) {
                    $shipping_info['address']['f_state'] = $order->get_shipping_state();
                }
                if ($order->get_shipping_city()) {
                    $shipping_info['address']['f_city'] = $order->get_shipping_city();
                }
                if (\substr($order->get_shipping_address_1() . $order->get_shipping_address_2(), 0, 100)) {
                    $shipping_info['address']['f_address'] = \substr($order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2(), 0, 100);
                }

                $f_request['shipping_info'] = $shipping_info;
            }

            $po5_settings = (new WC_PlatiOnline())->settings;

            $transaction_relay_response = array();
            $transaction_relay_response['f_relay_response_url'] = $order->get_checkout_order_received_url();
            $transaction_relay_response['f_relay_method'] = $po5_settings['relay_method'];
            $transaction_relay_response['f_post_declined'] = 1;
            $transaction_relay_response['f_relay_handshake'] = 1;

            $f_request['transaction_relay_response'] = $transaction_relay_response;

            $f_request['f_order_cart'] = array();

            foreach ($subscription->get_items() as $product) {
                $item = array();
                $item['prodid'] = $product['product_id'];
                $item['name'] = \substr(\strip_tags(\html_entity_decode($product['name'], ENT_QUOTES)), 0, 250);
                $item['description'] = '';
                $item['qty'] = $product['qty'];
                $item['itemprice'] = \round($product['line_subtotal'] / $product['qty'], 2);
                $item['vat'] = \round($product['line_subtotal_tax'], 2);
                $item['stamp'] = \date('Y-m-d');
                $item['prodtype_id'] = 0;
                $f_request['f_order_cart'][] = $item;
            }

            if (!empty($order->get_fees())) {
                foreach ($order->get_fees() as $fee) {
                    if ($fee->get_total() > 0) {
                        //adaug doar costurile aditionale, reducerile mai tarziu
                        $item = array();
                        $item['prodid'] = 'fee';
                        $item['name'] = \substr(\strip_tags(\html_entity_decode($fee->get_name(), ENT_QUOTES)), 0, 250);
                        $item['description'] = '';
                        $item['qty'] = 1;
                        $item['itemprice'] = \round($fee->get_total(), 2);
                        $item['vat'] = \round($fee->get_total_tax(), 2);
                        $item['stamp'] = \date('Y-m-d');
                        $item['prodtype_id'] = 0;
                        $f_request['f_order_cart'][] = $item;
                    }
                }
            }

            $i = 1;
            if ($order->get_total_discount()) {
                $coupon = array();
                $coupon['key'] = 'cart discount';
                $coupon['value'] = \round($order->cart_discount, 2);
                $coupon['percent'] = 0;
                $coupon['workingname'] = 'Cart discount';
                $coupon['type'] = 0;
                $coupon['scop'] = 0;
                $coupon['vat'] = \round($order->cart_discount_tax, 2);
                $f_request['f_order_cart']['coupon' . $i] = $coupon;
                $i++;
            }

            if (!empty($order->get_fees())) {
                foreach ($order->get_fees() as $fee) {
                    if ($fee->get_total() < 0) {
                        // e reducere
                        $coupon = array();
                        $coupon['key'] = 'fee discount';
                        $coupon['value'] = \round(abs($fee->get_total()), 2);
                        $coupon['percent'] = 0;
                        $coupon['workingname'] = \substr(\strip_tags(\html_entity_decode($fee->get_name(), ENT_QUOTES)), 0, 250);
                        $coupon['type'] = 0;
                        $coupon['scop'] = 0;
                        $coupon['vat'] = \round(abs($fee->get_total_tax()), 2);
                        $f_request['f_order_cart']['coupon' . $i] = $coupon;
                        $i++;
                    }
                }
            }

            $shipping = array();
            $shipping['name'] = \substr($order->get_shipping_method(), 0, 250);
            $shipping['price'] = \round($order->get_shipping_total(), 2);
            $shipping['pimg'] = 0;
            $shipping['vat'] = \round($order->get_shipping_tax(), 2);

            $f_request['f_order_cart']['shipping'] = $shipping;
            $f_request['f_order_string'] = \sprintf(__('Order #%s on website %s', 'plationline'), $order_id, get_bloginfo('url'));


            $f_request['paylink'] = array(
                'email2client' => 0,
                'sms2client' => 0,
                'daysofvalability' => 1,
            );
            $order->update_meta_data('_plationline_daysofvalability', 1);

            $tick = $subscription->get_billing_interval();
            $interval = $subscription->get_billing_period();
            $length = \wcs_estimate_periods_between($subscription->get_time('date_created'), $subscription->get_time('end'), $subscription->get_billing_period());

            if ($length == 0) {
                // subscriptia nu are termen de expirare, il setez la 5 ani
                $subscription->update_dates(array('end' => gmdate('Y-m-d H:i:s', strtotime("+5 years"))));
            }

            $recurrence_params = self::recurrence_calculate_f_recurring_frequency($tick, $interval, $length);
            $f_request['f_recurring_frequency'] = (int)$recurrence_params['f_recurring_frequency'];
            $f_request['f_recurring_expiration_date'] = date('Y-m-d', strtotime("+" . ((int)$recurrence_params['length']) . " " . $interval));

            $po = new PO5();
            $po->f_login = $po5_settings['merchant_id_' . \strtolower($f_request['f_currency'])];
            if (empty($po->f_login)) {
                wp_die('F_LOGIN is empty for currency: ' . $f_request['f_currency']);
            }
            $urlparts = parse_url(home_url());
            $domain = preg_replace('/www\./i', '', $urlparts['host']);
            $f_request['f_website'] = $domain;
            $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
            $po->setIV($po5_settings['iv_auth']);
            $po->test_mode = ($po5_settings['test_mode'] == 'DEMO' ? 1 : 0);
            global $woocommerce;
            $mailer = $woocommerce->mailer();
            // Email client with new order email
            $email = $mailer->emails['WC_Email_Customer_On_Hold_Order'];
            $email->trigger($order_id);

            // Email admin with new order email
            $email = $mailer->emails['WC_Email_New_Order'];
            $email->trigger($order_id);

            $response = $po->auth($f_request, $f_action);

            if (!empty($response['redirect_url'])) {
                \update_post_meta($order_id, 'plationline_redirect_url', sanitize_text_field($response['redirect_url']));
                \update_post_meta($order_id, '_transaction_id', sanitize_text_field($response['x_trans_id']));
                \WC()->cart->empty_cart();
                if (wp_redirect($response['redirect_url'])) {
                    exit;
                }
            }
            die();
        } else {
            wp_die(__('No subscriptions found in cart', 'plationline'));
        }
    }

    public function plationline_woocommerce_subscriptions_order_received($order_id)
    {
        $po5_settings = (new WC_PlatiOnline())->settings;
        $relay_method = $po5_settings['relay_method'];
        if ($relay_method === 'SOAP_PO_PAGE') {
            $response = file_get_contents("php://input");
        } else {
            $response = $_POST;
        }

        if (empty($response['F_Relay_Message'])) {
            return;
        }

        $po = new PO5();
        $po->setRSAKeyDecrypt($po5_settings['rsa_itsn']);
        $po->setIVITSN($po5_settings['iv_itsn']);

        switch ($relay_method) {
            case 'PTOR':
                $authorization_response = $po->auth_response(sanitize_text_field($response['F_Relay_Message']), sanitize_text_field($response['F_Crypt_Message']));
                $X_RESPONSE_CODE = $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_CODE');

                $order_id = $po->get_xml_tag_content($authorization_response, 'F_ORDER_NUMBER');
                $trans_id = $po->get_xml_tag_content($authorization_response, 'X_TRANS_ID');
                $order = new \WC_Order($order_id);

                $subscriptions = \wcs_get_subscriptions_for_order($order);

                switch ($X_RESPONSE_CODE) {
                    case '2':
                        //	authorized
                        \printf('<h2 style="text-align:center">' . __('Congratulations, the transaction for order #%s was successfully authorized!', 'plationline') . '</h2>', $order->get_order_number());

                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('wc-processing', __('PO Payment Authorized', 'plationline'));
                            foreach ($subscriptions as $subscription) {
                                $subscription->payment_complete($trans_id);
                            }
                            WC_Plationline_Process::plationline_update_order_status($order, 'wc-processing');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                            $order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($order_id);
                            \wc_update_coupon_usage_counts($order_id);

                            if (!WC_Plationline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                                $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                                WC_Plationline_Process::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                            }

                            if (!empty($po5_settings['tracking_script'])) {
                                echo $po5_settings['tracking_script'];
                            }
                        }
                        break;
                    case '13':
                        //	on hold
                        \printf('<h2 style="text-align:center">' . __('The transaction for order #%s is on hold, additional verification is needed!', 'plationline') . '</h2>', $order->get_order_number());
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('wc-on-hold', __('PO On-Hold', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'wc-on-hold');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                        }
                        break;
                    case '8':
                        //	declined
                        \printf('<h2 style="text-align:center">' . __('The transaction for order #%s was declined! Reason: %s', 'plationline') . '</h2>', $order->get_order_number(), $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_REASON_TEXT'));
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'po-refuzata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                        }
                        break;
                    case '10':
                    case '16':
                    case '17':
                        //	error
                        \printf('<h2 style="text-align:center">' . __('An error was encountered in authorization process for order #%s', 'plationline') . '</h2>', $order->get_order_number());
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'po-eroare');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Authorization Error');
                        }
                        break;
                }
                break;

            case 'POST_S2S_PO_PAGE':
                $authorization_response = $po->auth_response(sanitize_text_field($response['F_Relay_Message']), sanitize_text_field($response['F_Crypt_Message']));
                $X_RESPONSE_CODE = $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_CODE');
                $order_id = $po->get_xml_tag_content($authorization_response, 'F_ORDER_NUMBER');
                $trans_id = $po->get_xml_tag_content($authorization_response, 'X_TRANS_ID');
                $order = new \WC_Order($order_id);
                $subscriptions = \wcs_get_subscriptions_for_order($order);
                $raspuns_procesat = true;
                switch ($X_RESPONSE_CODE) {
                    case '2':
                        //	authorized
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('wc-processing', __('PO Payment Authorized', 'plationline'));
                            foreach ($subscriptions as $subscription) {
                                $subscription->payment_complete($trans_id);
                            }
                            WC_Plationline_Process::plationline_update_order_status($order, 'wc-processing');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                            $order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($order_id);
                            \wc_update_coupon_usage_counts($order_id);

                            if (!WC_Plationline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                                $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                                WC_Plationline_Process::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                            }
                        }
                        break;
                    case '13':
                        //	on hold
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('wc-on-hold', __('PO On-Hold', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'wc-on-hold');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                        }
                        break;
                    case '8':
                        //	declined
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'po-refuzata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                        }
                        break;
                    case '10':
                    case '16':
                    case '17':
                        //	error
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'po-eroare');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Authorization Error');
                        }
                        break;
                    default:
                        $raspuns_procesat = false;
                }

                \header('User-Agent:Mozilla/5.0 (Plati Online Relay Response Service)');

                if ($raspuns_procesat) {
                    \header('PO_Transaction_Response_Processing: true');
                } else {
                    \header('PO_Transaction_Response_Processing: retry');
                }
                break;

            case 'SOAP_PO_PAGE':
                $soap_parsed = $po->parse_soap_response($response);
                $authorization_response = $po->auth_response(sanitize_text_field($soap_parsed['PO_RELAY_REPONSE']['F_RELAY_MESSAGE']), sanitize_text_field($soap_parsed['PO_RELAY_REPONSE']['F_CRYPT_MESSAGE']));
                $X_RESPONSE_CODE = $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_CODE');
                $order_id = $po->get_xml_tag_content($authorization_response, 'F_ORDER_NUMBER');
                $trans_id = $po->get_xml_tag_content($authorization_response, 'X_TRANS_ID');
                $order = new \WC_Order($order_id);
                $subscriptions = \wcs_get_subscriptions_for_order($order);
                $raspuns_procesat = true;

                switch ($X_RESPONSE_CODE) {
                    case '2':
                        //	authorized
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('wc-processing', __('PO Payment Authorized', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'wc-processing');
                            foreach ($subscriptions as $subscription) {
                                $subscription->payment_complete($trans_id);
                            }
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                            $order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($order_id);
                            \wc_update_coupon_usage_counts($order_id);

                            if (!WC_Plationline_Process::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                                $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                                WC_Plationline_Process::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                            }
                        }
                        break;
                    case '13':
                        //	on hold
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('wc-on-hold', __('PO On-Hold', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'wc-on-hold');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                        }
                        break;
                    case '8':
                        //	declined
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'po-refuzata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                        }
                        break;
                    case '10':
                    case '16':
                    case '17':
                        //	error
                        if (!WC_Plationline_Process::plationline_check_order_had_status($order, 'wc-processing')) {
                            $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                            WC_Plationline_Process::plationline_update_order_status($order, 'po-eroare');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Authorization Error');
                        }
                        break;
                    default:
                        $raspuns_procesat = false;
                }

                \header('User-Agent:Mozilla/5.0 (Plati Online Relay Response Service)');

                if ($raspuns_procesat) {
                    \header('PO_Transaction_Response_Processing: true');
                } else {
                    \header('PO_Transaction_Response_Processing: retry');
                }
                break;
        }
    }

    public static function remove_next_payment_date_and_update_end_date($order)
    {
        $subscriptions = \wcs_get_subscriptions_for_order($order);
        if (!empty($subscriptions)) {
            try {
                foreach ($subscriptions as $subscription) {
                    $next_payment = $subscription->calculate_date('next_payment');
                    if (!empty($next_payment)) {
                        $subscription->delete_date('next_payment');
                        $subscription->update_dates(array('end' => $next_payment));
                    }
                }
            } catch (\Exception $e) {
            }
        }
    }

    public static function cancel_subscription_for_order($order)
    {
        $subscriptions = \wcs_get_subscriptions_for_order($order);
        if (!empty($subscriptions)) {
            try {
                foreach ($subscriptions as $subscription) {
                    if (!in_array($subscription->get_status(), array('pending-cancel', 'cancelled'))) {
                        $subscription->update_status('pending-cancel');
                    }
                }
            } catch (\Exception $e) {
            }
        }
    }

    public static function process_itsn($order, $trans_id, $starefin1)
    {
        $subscriptions = \wcs_get_subscriptions_for_order($order);
        if (!empty($subscriptions)) {
            try {
                foreach ($subscriptions as $subscription) {
                    $renewal_orders = $subscription->get_related_orders('all', 'renewal');
                    $new_order = false;
                    foreach ($renewal_orders as $renewal_order) {
                        if ($renewal_order->get_transaction_id() == $trans_id) {
                            $new_order = $renewal_order;
                            break;
                        }
                    }
                    if (!$new_order) {
                        $new_order = \wcs_create_renewal_order($subscription);
                        $new_order->set_transaction_id($trans_id);
                        $new_order->set_payment_method($subscription->get_payment_method());
                        $new_order->set_payment_method_title($subscription->get_payment_method_title());
                    }
                    switch ($starefin1) {
                        case 2:
                            $subscription->payment_complete($trans_id);
                            $subscription->update_dates(array('next_payment' => $subscription->calculate_date('next_payment')));
                            $new_order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Authorized');

                            $new_order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($new_order->get_id());
                            \wc_update_coupon_usage_counts($new_order->get_id());

                            $po5_settings = (new WC_PlatiOnline())->settings;
                            if (!WC_Plationline_Process::plationline_check_order_had_status($new_order, $po5_settings['authorized_order_status'])) {
                                $new_order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                                WC_Plationline_Process::plationline_update_order_status($new_order, $po5_settings['authorized_order_status']);
                            }
                            break;
                        case 8:
                            $subscription->payment_failed();
                            $new_order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Declined');
                            break;
                    }
                }
            } catch (\Exception $e) {
                $stare1 = '<f_response_code>0</f_response_code>';
                /* send ITSN response */
                $raspuns_xml = '<?xml version="1.0" encoding="UTF-8"?>';
                $raspuns_xml .= '<itsn>';
                $raspuns_xml .= '<x_trans_id>' . $trans_id . '</x_trans_id>';
                $raspuns_xml .= '<merchServerStamp>' . \date('Y-m-d\TH:i:sP') . '</merchServerStamp>';
                $raspuns_xml .= $stare1;
                $raspuns_xml .= '</itsn>';
                \header('Content-type: application/xml');
                echo $raspuns_xml;
                exit;
            }
        }

        $stare1 = '<f_response_code>1</f_response_code>';
        /* send ITSN response */
        $raspuns_xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $raspuns_xml .= '<itsn>';
        $raspuns_xml .= '<x_trans_id>' . $trans_id . '</x_trans_id>';
        $raspuns_xml .= '<merchServerStamp>' . \date('Y-m-d\TH:i:sP') . '</merchServerStamp>';
        $raspuns_xml .= $stare1;
        $raspuns_xml .= '</itsn>';
        \header('Content-type: application/xml');
        echo $raspuns_xml;
        exit;
    }

    public function plationline_woocommerce_subscriptions_action_status_changed($subscription, $new_status, $old_status)
    {
        if ($subscription->get_type() == 'shop_subscription' && in_array($new_status, array('pending-cancel', 'cancelled')) && !in_array($old_status, array('pending-cancel', 'cancelled'))) {
            // trebuie sa anulez recurenta in PO
            $po5_settings = (new WC_PlatiOnline())->settings;
            $po = new PO5();
            $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
            $po->setIV($po5_settings['iv_auth']);

            $parent_order_id = $subscription->get_parent_id();
            $parent_order = new \WC_Order($parent_order_id);
            $transid = (int)$parent_order->get_transaction_id();
            $po->f_login = $po5_settings['merchant_id_' . \strtolower($parent_order->get_currency())];
            $urlparts = \parse_url(\home_url());
            $domain = \preg_replace('/www\./i', '', $urlparts['host']);
            $f_request['f_website'] = $domain;
            $f_request['f_order_number'] = $parent_order->get_id();
            $f_request['x_trans_id'] = $transid;

            $response_cancel_recurrence = $po->cancel_recurrence($f_request, 26);
            if ($po->get_xml_tag_content($response_cancel_recurrence, 'PO_ERROR_CODE') == 1) {
                $parent_order->add_order_note('Requested <b>CANCEL RECURRENCE</b> for Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>' . $po->get_xml_tag_content($response_cancel_recurrence, 'PO_ERROR_REASON') . '</b>');
            } else {
                switch ($po->get_xml_tag_content($response_cancel_recurrence, 'X_RESPONSE_CODE')) {
                    case '26':
                        $parent_order->add_order_note('Successfully requested <b>CANCEL RECURRENCE</b> for Plationline Transaction ID: <b>' . $transid . '</b>');
                        break;
                    case '10':
                        $parent_order->add_order_note('Requested <b>CANCEL RECURRENCE</b> for Plationline Transaction ID: <b>' . $transid . '</b> but got error <b>10</b>');
                        break;
                }
            }
        }
    }
}
