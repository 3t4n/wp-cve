<?php

namespace PlatiOnlinePO6\Inc\Core;

/**
 * @link              https://plati.online
 * @since             6.0.0
 * @package           PlatiOnlinePO6
 *
 */

use PlatiOnlinePO6\Inc\Core\WC_PlatiOnline as WC_PlatiOnline;
use PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_Woocommerce_Subscriptions as WC_PlatiOnline_Woocommerce_Subscriptions;
use PlatiOnlinePO6\Inc\Libraries\PO5 as PO5;

class WC_PlatiOnline_Process
{
    public function plationline_init()
    {
        if (\class_exists('WC_Payment_Gateway')) {
            new WC_PlatiOnline();
        }
    }

    public function plationline_gateway($methods)
    {
        $methods[] = 'PlatiOnlinePO6\Inc\Core\WC_PlatiOnline';
        $methods[] = 'PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_PR';
        $methods[] = 'PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_Recurrence';
        $methods[] = 'PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_Additional';
        if (in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $methods[] = 'PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_Woocommerce_Subscriptions';
        }
        return $methods;
    }

    public function add_po_statuses_to_reports_admin($statuses)
    {
        if (!is_array($statuses)) {
            $statuses = array();
        }
        $statuses[] = 'po-autorizata';
        $statuses[] = 'po-incasata';
        return $statuses;
    }

    public function add_order_statuses($order_statuses)
    {
        $new_order_statuses = array();
        // add new order status after processing
        if (!empty(WC_PlatiOnline::$po_order_statuses)) {
            foreach (WC_PlatiOnline::$po_order_statuses as $key => $status) {
                $new_order_statuses[$key] = $status;
            }
        }
        return \array_merge($new_order_statuses, $order_statuses);
    }

    public function register_po_order_statuses()
    {
        if (!empty(WC_PlatiOnline::$po_order_statuses)) {
            foreach (WC_PlatiOnline::$po_order_statuses as $key => $value) {
                register_post_status($key, array(
                    'label' => $value,
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop($value . ' <span class="count">(%s)</span>', $value . ' <span class="count">(%s)</span>'),
                ));
            }
        }
    }

    public function receipt_page($order_id)
    {
        echo '<h2>' . __('Thank you for your order, you will be redirected to PlatiOnline payment page!', 'plationline') . '</h2>';
        $this->setFRequestAuth($order_id, 2);
    }

    public function receipt_page_pr($order_id)
    {
        echo '<h2>' . __('Thank you for your order, you will be redirected to PlatiOnline payment page!', 'plationline') . '</h2>';
        $this->setFRequestAuth($order_id, 12);
    }

    public function receipt_page_recurrence($order_id)
    {
        echo '<h2>' . __('Thank you for your order, you will be redirected to PlatiOnline payment page!', 'plationline') . '</h2>';
        $this->setFRequestAuth($order_id, 20);
    }

    public function receipt_page_additional($order_id)
    {
        echo '<h2>' . __('Thank you for your order, you will be redirected to PlatiOnline payment page!', 'plationline') . '</h2>';
        WC_PlatiOnline_Additional::setFRequestAuth($order_id, 2);
    }

    public function receipt_page_woocommerce_subscriptions($order_id)
    {
        echo '<h2>' . __('Thank you for your order, you will be redirected to PlatiOnline payment page!', 'plationline') . '</h2>';
        WC_PlatiOnline_Woocommerce_Subscriptions::setFRequestAuth($order_id, 20);
    }

    public static function po_order_received($order_id)
    {
        do_shortcode('[plationline_response]');
    }

    public function plationline_change_order_received_title($title, $endpoint)
    {
        if (is_order_received_page()) {
            global $wp;

            $order_id = apply_filters('woocommerce_thankyou_order_id', absint($wp->query_vars['order-received']));
            $order_key = apply_filters('woocommerce_thankyou_order_key', empty($_GET['key']) ? '' : wc_clean($_GET['key']));

            if ($order_id > 0) {
                $order = wc_get_order($order_id);
                if ($order->get_order_key() != $order_key) {
                    unset($order);
                }
            }

            if (isset($order)) {
                $payment_method = $order->get_payment_method();
                if (stripos($payment_method, 'plationline') !== false) {
                    return __('PlatiOnline response', 'plationline');
                }
            }
        }
        return $title;
    }

    public function plationline_change_order_received_text($text, $order)
    {
        if (is_order_received_page() && is_a($order, 'WC_Order')) {
            $payment_method = $order->get_payment_method();
            if (stripos($payment_method, 'plationline') !== false) {
                return '';
            }
        }
        return $text;
    }

    public function check_plationline_response()
    {
        $po5_settings = (new WC_PlatiOnline())->settings;
        $relay_method = $po5_settings['relay_method'];
        if ($relay_method === 'SOAP_PO_PAGE') {
            $soap_xml = file_get_contents("php://input");
            if (!empty($soap_xml)) {
                do_action("valid-plationline-response", null, $soap_xml);
            }
        } else {
            $response = $_POST;
            if (!empty($response)) {
                do_action("valid-plationline-response", $response, null);
            }
        }
    }

    public function plationline_email_payment_link($order, $sent_to_admin, $plain_text, $email)
    {
        if (is_a($order, 'WC_Order') && !$order->is_paid() && $order->get_meta('plationline_redirect_url')) {
            echo '<a style="color: #000;padding: 10px;display: inline-block;text-decoration: none;margin-bottom: 20px;background: #E7E8E9;border-radius: 5px;box-shadow: 2px 2px 2px #eee;border: 1px solid #ccc;text-align: center;font-weight:bold" href="' . $order->get_meta('plationline_redirect_url') . '"><span>' . __('Retry failed payment by PlatiOnline', 'plationline') . '</span></a>';
        }
    }

    public function plationline_email_payment_link_format_string($string, $email)
    {
        $order = $email->object;
        if (is_a($order, 'WC_Order') && !$order->is_paid() && $order->get_meta('plationline_redirect_url') && isset($email->customer_note)) {
            $placeholder = '{plationline_email_payment_link}';
            $value = '<a style="color: #000;padding: 10px;display: inline-block;text-decoration: none;margin-bottom: 20px;background: #E7E8E9;border-radius: 5px;box-shadow: 2px 2px 2px #eee;border: 1px solid #ccc;text-align: center;font-weight:bold" href="' . $order->get_meta('plationline_redirect_url') . '"><span>' . __('Retry failed payment by PlatiOnline', 'plationline') . '</span></a>';
            $email->customer_note = str_replace($placeholder, $value, $email->customer_note);
        }
        return $string;
    }

    public function po_response($posted = null, $soap_xml = null)
    {
        $this->processPOResponse($posted, $soap_xml);
    }

    public function itsn($posted)
    {
        $this->processPOITSN($posted);
    }

    public function check_itsn_response()
    {
        if (!empty($_POST)) {
            do_action("valid-plationline-itsn-request", $_POST);
        } else {
            wp_die("PlatiOnline ITSN failure");
        }
    }

    public static function setFRequestAuth($order_id, $f_action = 2)
    {
        $order = new \WC_Order($order_id);

        $f_request = array();
        $f_request['f_order_number'] = $order_id;
        $f_request['f_amount'] = \round($order->get_total(), 2);
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
        $transaction_relay_response['f_relay_response_url'] = !empty($po5_settings['relay_response_url']) ? $po5_settings['relay_response_url'] : $order->get_checkout_order_received_url();
        $transaction_relay_response['f_relay_method'] = $po5_settings['relay_method'];
        $transaction_relay_response['f_post_declined'] = 1;
        $transaction_relay_response['f_relay_handshake'] = 1;

        $f_request['transaction_relay_response'] = $transaction_relay_response;

        $f_request['f_order_cart'] = array();

        foreach ($order->get_items() as $product) {
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

        if (!empty($po5_settings['daysofvalability']) && (int)$po5_settings['daysofvalability'] > 0) {
            $f_request['paylink'] = array(
                'email2client' => 0,
                'sms2client' => 0,
                'daysofvalability' => (int)$po5_settings['daysofvalability'],
            );
            $order->update_meta_data('_plationline_daysofvalability', (int)$po5_settings['daysofvalability']);
        }

        if (!empty(WC()->session->get('x_payment_token'))) {
            $f_request['oauth_payment_token'] = WC()->session->get('x_payment_token');
        }

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

        // recurrence
        if ($f_action == 20) {
            $frequency = $order->get_meta('_plationline_recurrence_frequency');
            $duration = $order->get_meta('_plationline_recurrence_duration');
            if (!empty($frequency) && !empty($duration)) {
                $f_request['f_recurring_frequency'] = (int)$frequency;
                $f_request['f_recurring_expiration_date'] = date('Y-m-d', strtotime("+" . ((int)$duration) . " month"));
            }
        }

        $response = $po->auth($f_request, $f_action);

        if (!empty($response['redirect_url'])) {
            $order->update_meta_data('plationline_redirect_url', $response['redirect_url']);
            $order->set_transaction_id($response['x_trans_id']);
            if ($f_action == 12) {
                $order->update_status('po-posta', __('PO Pending cash payment at Posta Romana', 'plationline'));
                self::plationline_update_order_status($order, 'po-posta');
            } else {
                $order->update_status('po-autorizare', __('PO Pending Authorization', 'plationline'));
                self::plationline_update_order_status($order, 'po-autorizare');
            }
            \WC()->cart->empty_cart();
            if (wp_redirect($response['redirect_url'])) {
                exit;
            }
        }
    }

    public static function processPOResponse($response = null, $soap_xml = null)
    {
        if (empty($response['F_Relay_Message'])) {
            return;
        }

        $po5_settings = (new WC_PlatiOnline())->settings;
        $po = new PO5();
        $po->setRSAKeyDecrypt($po5_settings['rsa_itsn']);
        $po->setIVITSN($po5_settings['iv_itsn']);
        $po_f_relay_method = $po5_settings['relay_method'];

        if (empty($po5_settings['authorized_order_status'])) {
            $po5_settings['authorized_order_status'] = 'po-autorizata';
        }

        switch ($po_f_relay_method) {
            case 'PTOR':
                $authorization_response = $po->auth_response(sanitize_text_field($response['F_Relay_Message']), sanitize_text_field($response['F_Crypt_Message']));
                $X_RESPONSE_CODE = $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_CODE');
                $order_id = $po->get_xml_tag_content($authorization_response, 'F_ORDER_NUMBER');
                $trans_id = $po->get_xml_tag_content($authorization_response, 'X_TRANS_ID');
                $order = new \WC_Order($order_id);

                switch ($X_RESPONSE_CODE) {
                    case '2':
                        //	authorized
                        \printf('<h2 style="text-align:center">' . __('Congratulations, the transaction for order #%s was successfully authorized!', 'plationline') . '</h2>', $order->get_order_number());
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                            self::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                            $order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($order_id);
                            \wc_update_coupon_usage_counts($order_id);
                            if (!empty($po5_settings['tracking_script'])) {
                                echo $po5_settings['tracking_script'];
                            }
                        }
                        break;
                    case '13':
                        //	on hold
                        \printf('<h2 style="text-align:center">' . __('The transaction for order #%s is on hold, additional verification is needed!', 'plationline') . '</h2>', $order->get_order_number());
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-onhold', __('PO On-Hold', 'plationline'));
                            self::plationline_update_order_status($order, 'po-onhold');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                        }
                        break;
                    case '8':
                        //	declined
                        \printf('<h2 style="text-align:center">' . __('The transaction for order #%s was declined! Reason: %s', 'plationline') . '</h2>', $order->get_order_number(), $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_REASON_TEXT'));
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                            self::plationline_update_order_status($order, 'po-refuzata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                        }
                        break;
                    case '10':
                    case '16':
                    case '17':
                        //	error
                        \printf('<h2 style="text-align:center">' . __('An error was encountered in authorization process for order #%s', 'plationline') . '</h2>', $order->get_order_number());
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                            self::plationline_update_order_status($order, 'po-eroare');
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

                $raspuns_procesat = true;
                switch ($X_RESPONSE_CODE) {
                    case '2':
                        //	authorized
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                            self::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                            $order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($order_id);
                            \wc_update_coupon_usage_counts($order_id);
                        }
                        break;
                    case '13':
                        //	on hold
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-onhold', __('PO On-Hold', 'plationline'));
                            self::plationline_update_order_status($order, 'po-onhold');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                        }
                        break;
                    case '8':
                        //	declined
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                            self::plationline_update_order_status($order, 'po-refuzata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                        }
                        break;
                    case '10':
                    case '16':
                    case '17':
                        //	error
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                            self::plationline_update_order_status($order, 'po-eroare');
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
                $soap_parsed = $po->parse_soap_response($soap_xml);
                $authorization_response = $po->auth_response(sanitize_text_field($soap_parsed['PO_RELAY_REPONSE']['F_RELAY_MESSAGE']), sanitize_text_field($soap_parsed['PO_RELAY_REPONSE']['F_CRYPT_MESSAGE']));
                $X_RESPONSE_CODE = $po->get_xml_tag_content($authorization_response, 'X_RESPONSE_CODE');
                $order_id = $po->get_xml_tag_content($authorization_response, 'F_ORDER_NUMBER');
                $trans_id = $po->get_xml_tag_content($authorization_response, 'X_TRANS_ID');
                $order = new \WC_Order($order_id);

                $raspuns_procesat = true;

                switch ($X_RESPONSE_CODE) {
                    case '2':
                        //	authorized
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                            self::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                            $order->payment_complete($trans_id);
                            \wc_reduce_stock_levels($order_id);
                            \wc_update_coupon_usage_counts($order_id);
                        }
                        break;
                    case '13':
                        //	on hold
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-onhold', __('PO On-Hold', 'plationline'));
                            self::plationline_update_order_status($order, 'po-onhold');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                        }
                        break;
                    case '8':
                        //	declined
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                            self::plationline_update_order_status($order, 'po-refuzata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                        }
                        break;
                    case '10':
                    case '16':
                    case '17':
                        //	error
                        if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                            $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                            self::plationline_update_order_status($order, 'po-eroare');
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

    public static function processPOITSN($response)
    {
        $po5_additional = new WC_PlatiOnline_Additional();
        $po5_additional_enabled = \wc_string_to_bool($po5_additional->enabled);

        if ($po5_additional_enabled) {
            $additional_f_logins = array($po5_additional->settings['merchant_id_ron'], $po5_additional->settings['merchant_id_eur'], $po5_additional->settings['merchant_id_usd']);

            // daca f_login din call se regaseste in vreun f_login din metoda aditionala incerc sa decriptez
            if (in_array(sanitize_text_field(!empty($response['f_login']) ? $response['f_login'] : ''), $additional_f_logins)) {
                $po = new PO5();
                $po->setRSAKeyDecrypt($po5_additional->settings['rsa_itsn']);
                $po->setIVITSN($po5_additional->settings['iv_itsn']);

                $call_itsn = $po->itsn(sanitize_text_field($response['f_itsn_message']), sanitize_text_field($response['f_crypt_message']));

                $po->f_login = $po5_additional->settings['merchant_id_' . \strtolower($po->get_xml_tag_content($call_itsn, 'F_CURRENCY'))];
                if (empty($po->f_login)) {
                    wp_die('Additional F_LOGIN is empty for currency: ' . $po->get_xml_tag_content($call_itsn, 'F_CURRENCY'));
                }
                $po5_settings = $po5_additional->settings;
            }
        }

        if (empty($po5_settings)) {
            $po5_settings = (new WC_PlatiOnline())->settings;
        }

        $po = new PO5();
        $po->setRSAKeyDecrypt($po5_settings['rsa_itsn']);
        $po->setIVITSN($po5_settings['iv_itsn']);
        $call_itsn = $po->itsn(sanitize_text_field($response['f_itsn_message']), sanitize_text_field($response['f_crypt_message']));
        $po->f_login = $po5_settings['merchant_id_' . \strtolower($po->get_xml_tag_content($call_itsn, 'F_CURRENCY'))];
        if (empty($po->f_login)) {
            wp_die('F_LOGIN is empty for currency: ' . $po->get_xml_tag_content($call_itsn, 'F_CURRENCY'));
        }
        $po->setRSAKeyEncrypt($po5_settings['rsa_auth']);
        $po->setIV($po5_settings['iv_auth']);
        $urlparts = \parse_url(\home_url());
        $domain = \preg_replace('/www\./i', '', $urlparts['host']);
        $f_request['f_website'] = $domain;
        $f_request['f_order_number'] = $po->get_xml_tag_content($call_itsn, 'F_ORDER_NUMBER');
        $f_request['x_trans_id'] = $po->get_xml_tag_content($call_itsn, 'X_TRANS_ID');

        $raspuns_itsn = $po->query($f_request, 0);

        if (empty($po5_settings['authorized_order_status'])) {
            $po5_settings['authorized_order_status'] = 'po-autorizata';
        }

        if ($po->get_xml_tag_content($raspuns_itsn, 'PO_ERROR_CODE') == 1) {
            wp_die($po->get_xml_tag_content($raspuns_itsn, 'PO_ERROR_REASON'));
        } else {
            $itsn_order = $po->get_xml_tag($raspuns_itsn, 'ORDER');
            $tranzaction = $po->get_xml_tag($itsn_order, 'TRANZACTION');
            $starefin1 = $po->get_xml_tag_content($po->get_xml_tag($tranzaction, 'STATUS_FIN1'), 'CODE');
            $starefin2 = $po->get_xml_tag_content($po->get_xml_tag($tranzaction, 'STATUS_FIN2'), 'CODE');
            $trans_id = (int)$po->get_xml_tag_content($tranzaction, 'X_TRANS_ID');
            $order_id = (int)$po->get_xml_tag_content($itsn_order, 'F_ORDER_NUMBER');

            if (empty($order_id)) {
                // fix pentru link de plata
                $raspuns_xml = '<?xml version="1.0" encoding="UTF-8"?>';
                $raspuns_xml .= '<itsn>';
                $raspuns_xml .= '<x_trans_id>' . $trans_id . '</x_trans_id>';
                $raspuns_xml .= '<merchServerStamp>' . \date('Y-m-d\TH:i:sP') . '</merchServerStamp>';
                $raspuns_xml .= '<f_response_code>1</f_response_code>';
                $raspuns_xml .= '</itsn>';
                \header('Content-type: application/xml');
                echo $raspuns_xml;
                exit;
            }

            $order = new \WC_Order($order_id);
            $parent_trans_id = (int)$po->get_xml_tag_content($tranzaction, 'X_PARENT_TRANS_ID');
            $current_status = $order->get_status();
            $cc_type = $po->get_xml_tag_content($tranzaction, 'F_CC_TYPE');

            if (in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                // tratez cazul in care tranzactia a plecat cu recurenta dar s-a transformat in plata simpla, actualizez end date pentru subscriptie sa fie next_payment_date
                if ($cc_type == 'CC' && !empty(\wcs_get_subscriptions_for_order($order))) {
                    WC_PlatiOnline_Woocommerce_Subscriptions::remove_next_payment_date_and_update_end_date($order);
                }

                $status_sc = (int)$po->get_xml_tag_content($po->get_xml_tag($tranzaction, 'STATUS_SC'), 'CODE');
                // este tranzactie master recurenta si recurenta este anulata
                if ($cc_type == 'XR' && empty($parent_trans_id) && !empty(\wcs_get_subscriptions_for_order($order)) && $status_sc == 4) {
                    WC_PlatiOnline_Woocommerce_Subscriptions::cancel_subscription_for_order($order);
                }

                // itsn subscription onhold to auth or declined
                if ($cc_type == 'XR' && empty($parent_trans_id) && !$order->is_paid()) {
                    $subscriptions = \wcs_get_subscriptions_for_order($order);
                    if (!empty($subscriptions)) {
                        foreach ($subscriptions as $subscription) {
                            switch ($starefin1) {
                                case 2:
                                    $subscription->payment_complete($trans_id);
                                    $subscription->update_dates(array('next_payment' => $subscription->calculate_date('next_payment')));
                                    break;
                                case 8:
                                    $subscription->payment_failed();
                                    break;
                            }
                        }
                    }
                }
            }

            // recurrence
            if (!empty($parent_trans_id)) {
                // e recurenta
                // trebuie sa verific ce fel de recurenta e, daca e Woocommerce Subscriptions sau recurenta PO Woocommerce
                // verific daca e recurenta pe Woocommerce Subscriptions
                if (in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                    WC_PlatiOnline_Woocommerce_Subscriptions::process_itsn($order, $trans_id, $starefin1);
                }
                $plationline_transaction_type = $order->get_meta('_plationline_transaction_type');
                // verific daca e recurenta clasica implementata de PO pe Woocommerce
                if ($plationline_transaction_type == 'plationline_recurrence_master' && $order->get_transaction_id() == $parent_trans_id) {
                    // sunt pe comanda parinte si are transid corect
                    $child_transid = $trans_id;
                    $child_order = self::plationline_recurrence_get_child_orders_by_transid($order, $child_transid);
                    if ($child_order == false) {
                        // nu am gasit transid pe nici o comanda copil
                        $new_order = self::clone_order($order);
                        $new_order->update_meta_data('_plationline_recurrence_master_order_id', $order_id);
                        $new_order->update_meta_data('_plationline_recurrence_master_transaction_id', $parent_trans_id);
                        $new_order->update_meta_data('_plationline_transaction_type', 'plationline_recurrence_child');

                        $new_order->set_transaction_id($child_transid);
                        self::plationline_update_order_status($new_order, 'po-autorizare');
                        $new_order->add_order_note(\sprintf(__('PlatiOnline Recurrence, copied order data from #%s to #%s', 'plationline'), $order_id, $new_order->get_id()));
                        $order->add_order_note(\sprintf(__('PlatiOnline Recurrence, copied order data from #%s to #%s', 'plationline'), $order_id, $new_order->get_id()));

                        self::plationline_recurrence_update_child_orders($order, $new_order, $child_transid);

                        global $woocommerce;
                        $mailer = $woocommerce->mailer();
                        // Email admin with new order email, no matter the status
                        $email = $mailer->emails['WC_Email_New_Order'];
                        $email->trigger($new_order->get_id());
                        if ($starefin1 == '2') {
                            // Email client with new order email only if authorized
                            $email = $mailer->emails['WC_Email_Customer_Processing_Order'];
                            $email->trigger($new_order->get_id());
                        }
                    } else {
                        $new_order = new \WC_Order($child_order);
                    }
                    // FOARTE IMPORTANT!!! trebuie sa actualizez starea noii comenzi, nu cea a comenzii parinte
                    $order = $new_order;
                    $order_id = $new_order->get_id();
                }
            }
            // END recurrence

            $stare1 = '<f_response_code>1</f_response_code>';
            switch ($starefin1) {
                case '13':
                    if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                        $order->update_status('po-onhold', __('PO On-Hold', 'plationline'));
                        self::plationline_update_order_status($order, 'po-onhold');
                        $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO On-Hold');
                    }
                    break;
                case '2':
                    if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                        $order->update_status($po5_settings['authorized_order_status'], __('PO Payment Authorized', 'plationline'));
                        self::plationline_update_order_status($order, $po5_settings['authorized_order_status']);
                        $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Authorized');
                        $order->payment_complete($trans_id);
                        \wc_reduce_stock_levels($order_id);
                        \wc_update_coupon_usage_counts($order_id);
                    }
                    break;
                case '8':
                    if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                        $order->update_status('po-refuzata', __('PO Payment Declined', 'plationline'));
                        self::plationline_update_order_status($order, 'po-refuzata');
                        $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Payment Declined');
                    }
                    break;
                case '3':
                    if ($current_status === 'completed' && $po5_settings['completed_final_status'] === 'yes') {
                        // do nothing, do not update to pending settle
                    } else {
                        $order->update_status('po-incasare', __('PO Pending Settle', 'plationline'));
                        self::plationline_update_order_status($order, 'po-incasare');
                    }
                    $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Pending Settle');
                    break;
                case '5':
                    /* Verify starefin2 status*/
                    switch ($starefin2) {
                        case '1':
                            $order->update_status('po-creditare', __('PO Pending Refund', 'plationline'));
                            self::plationline_update_order_status($order, 'po-creditare');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Pending Refund');
                            break;
                        case '2':
                            $order->update_status('po-creditata', __('PO Refunded', 'plationline'));
                            self::plationline_update_order_status($order, 'po-creditata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Refunded');
                            break;
                        case '3':
                            $order->update_status('po-refuz-plata', __('PO Chargeback', 'plationline'));
                            self::plationline_update_order_status($order, 'po-refuz-plata');
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Chargeback');
                            break;
                        case '4':
                            if ($current_status === 'completed' && $po5_settings['completed_final_status'] === 'yes') {
                                // do nothing, do not update to settled
                            } else {
                                $order->update_status('po-incasata', __('PO Settled', 'plationline'));
                                self::plationline_update_order_status($order, 'po-incasata');
                            }
                            $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Settled');
                            break;
                    }
                    break;
                case '6':
                    $order->update_status('po-anulare', __('PO Pending Void', 'plationline'));
                    self::plationline_update_order_status($order, 'po-anulare');
                    $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Pending Void');
                    break;
                case '7':
                    $order->update_status('po-anulata', __('PO Voided', 'plationline'));
                    self::plationline_update_order_status($order, 'po-anulata');
                    $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Voided');
                    \wc_increase_stock_levels($order_id);
                    break;
                case '9':
                    $order->update_status('po-expira', __('PO Expired', 'plationline'));
                    self::plationline_update_order_status($order, 'po-expira');
                    $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Expired');
                    break;
                case '10':
                case '16':
                case '17':
                    if (!self::plationline_check_order_had_status($order, $po5_settings['authorized_order_status'])) {
                        $order->update_status('po-eroare', __('PO Authorization Error', 'plationline'));
                        self::plationline_update_order_status($order, 'po-eroare');
                        $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />PO Authorization Error');
                    }
                    break;
                case '1':
                    $order->update_status('po-autorizare', __('PO Pending Authorization', 'plationline'));
                    self::plationline_update_order_status($order, 'po-autorizare');
                    $order->add_order_note('PlatiOnline Transaction ID: ' . $trans_id . '<br />Pending payment');
                    break;
                default:
                    $stare1 = '<f_response_code>0</f_response_code>';
            }

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

    public function plationline_retry_payment($order_id)
    {
        $order = new \WC_Order($order_id);
        $current_status = $order->get_status();
        $permitted_statuses = array('po-refuzata', 'po-eroare');

        if (in_array($current_status, $permitted_statuses)) {
            $plationline_redirect_url = $order->get_meta('plationline_redirect_url');
            if (!empty($plationline_redirect_url)) {
                // determin daca am optiunea de zile activa
                $plationline_daysofvalability = (int)$order->get_meta('_plationline_daysofvalability');
                $po5_settings = (new WC_PlatiOnline())->settings;
                //determin daca am optiunea de stoc activa
                $plationline_disable_retry_payment_out_of_stock = \wc_string_to_bool($po5_settings['disable_retry_payment_out_of_stock']);
                //determin daca managementul de stoc e activ
                $woocommerce_stock_management = \wc_string_to_bool(get_option('woocommerce_manage_stock'));
                $use_stock_management = $plationline_disable_retry_payment_out_of_stock && $woocommerce_stock_management;

                $is_any_out_of_stock_now = false;
                if ($use_stock_management) {
                    foreach ($order->get_items() as $order_product) {
                        $product = $order_product->get_product();
                        if ($product->get_manage_stock() && $product->is_in_stock() == false) {
                            $is_any_out_of_stock_now = true;
                            break;
                        }
                    }
                }
                $numHoursPassed = abs($order->get_date_created()->getOffsetTimestamp() - current_time('timestamp')) / 60 / 60;
                if ($use_stock_management && $plationline_daysofvalability > 0) {
                    if ($numHoursPassed > (int)$plationline_daysofvalability * 24 || $is_any_out_of_stock_now == true) {
                        echo '<div id="po6-repay"><img alt="po6-repay-logo" id="po6-repay-logo" src="' . plugin_dir_url(__FILE__) . '../../assets/images/logo.png"><span>' . \sprintf(__('The order cannot be paid, more than %d days passed since it was received or one or more products are out of stock now', 'plationline'), $plationline_daysofvalability) . '</span></div>';
                        return;
                    }
                } elseif ($plationline_daysofvalability > 0 && $numHoursPassed > (int)$plationline_daysofvalability * 24) {
                    echo '<div id="po6-repay"><img alt="po6-repay-logo" id="po6-repay-logo" src="' . plugin_dir_url(__FILE__) . '../../assets/images/logo.png"><span>' . \sprintf(__('The order cannot be paid, more than %d days passed since it was received', 'plationline'), $plationline_daysofvalability) . '</span></div>';
                    return;
                } elseif ($use_stock_management && $is_any_out_of_stock_now == true) {
                    echo '<div id="po6-repay"><img alt="po6-repay-logo" id="po6-repay-logo" src="' . plugin_dir_url(__FILE__) . '../../assets/images/logo.png"><span>' . __('The order cannot be paid, one or more products are out of stock now', 'plationline') . '</span></div>';
                    return;
                }
                echo '<a id="po6-repay" href="' . $plationline_redirect_url . '"><img id="po6-repay-logo" alt="po6-repay-logo" src="' . plugin_dir_url(__FILE__) . '../../assets/images/logo.png"><span>' . __('Retry failed payment by PlatiOnline', 'plationline') . '</span></a>';
            }
        }
    }

    public static function plationline_check_order_had_status($order, $status)
    {
        $status_history = $order->get_meta('plationline_status_history') ?: array();
        return array_search($status, $status_history);
    }

    public static function plationline_update_order_status($order, $status)
    {
        $status_history = $order->get_meta('plationline_status_history');
        if (empty($status_history)) {
            $status_history = array();
        }
        $status_history[time()] = $status;
        $order->update_meta_data('plationline_status_history', $status_history);
        $order->save();
    }

    public static function plationline_recurrence_update_child_orders($master_order, $child_order, $transid)
    {
        $child_orders = $master_order->get_meta('_plationline_recurrence_child_orders');
        if (empty($child_orders)) {
            $child_orders = array();
        }
        $child_orders[$child_order->get_id()] = $transid;
        $master_order->update_meta_data('_plationline_recurrence_child_orders', $child_orders);
        $master_order->save();
    }

    public static function plationline_recurrence_get_child_orders_by_transid($master_order, $transid)
    {
        $child_order_ids = $master_order->get_meta('_plationline_recurrence_child_orders');
        return array_search($transid, $child_order_ids);
    }

    public static function clone_order($master_order = null)
    {
        if (!is_a($master_order, 'WC_Order')) {
            \wp_die(__('Could not find master order', 'Plationline'));
        }
        $order_data = array(
            'post_type' => 'shop_order',
            'post_status' => 'po-autorizare',
            'ping_status' => 'closed',
            'post_password' => \uniqid('order_'),
        );
        $child_order_id = \wp_insert_post($order_data, true);
        if (is_wp_error($child_order_id)) {
            \wp_die(__('Could not insert new order', 'Plationline'));
        } else {
            return self::cloned_order_data($master_order->get_id(), $child_order_id);
        }
    }

    private static function cloned_order_data($master_order_id, $child_order_id)
    {
        $master_order = \wc_get_order($master_order_id);
        $child_order = \wc_get_order($child_order_id);

        if (empty($master_order)) {
            \wp_die(__('Could not find master order', 'Plationline'));
        }
        if (empty($child_order)) {
            \wp_die(__('Could not find child order', 'Plationline'));
        }

        self::clone_order_header($master_order_id, $child_order_id);
        self::clone_order_billing_shipping($master_order_id, $child_order_id);
        self::clone_order_shipping_items($master_order, $child_order);
        self::clone_order_fees($master_order, $child_order);
        self::clone_order_coupons($master_order, $child_order);
        self::clone_order_items($master_order, $child_order);

        foreach ($child_order->get_items() as $item) {
            $item->delete_meta_data('_reduced_stock');
        }
        \update_post_meta($child_order_id, '_order_stock_reduced', \wc_bool_to_string(false));
        $child_order->calculate_totals();
        $child_order->save();
        return $child_order;
    }

    private static function clone_order_header($master_order_id, $child_order_id)
    {
        \update_post_meta($child_order_id, '_order_shipping', \get_post_meta($master_order_id, '_order_shipping', true));
        \update_post_meta($child_order_id, '_order_discount', \get_post_meta($master_order_id, '_order_discount', true));
        \update_post_meta($child_order_id, '_cart_discount', \get_post_meta($master_order_id, '_cart_discount', true));
        \update_post_meta($child_order_id, '_order_tax', \get_post_meta($master_order_id, '_order_tax', true));
        \update_post_meta($child_order_id, '_order_shipping_tax', \get_post_meta($master_order_id, '_order_shipping_tax', true));
        \update_post_meta($child_order_id, '_order_total', \get_post_meta($master_order_id, '_order_total', true));
        \update_post_meta($child_order_id, '_order_key', 'wc_' . \apply_filters('woocommerce_generate_order_key', uniqid('order_')));
        \update_post_meta($child_order_id, '_customer_user', \get_post_meta($master_order_id, '_customer_user', true));
        \update_post_meta($child_order_id, '_order_currency', \get_post_meta($master_order_id, '_order_currency', true));
        \update_post_meta($child_order_id, '_prices_include_tax', \get_post_meta($master_order_id, '_prices_include_tax', true));
        \update_post_meta($child_order_id, '_customer_ip_address', \get_post_meta($master_order_id, '_customer_ip_address', true));
        \update_post_meta($child_order_id, '_customer_user_agent', \get_post_meta($master_order_id, '_customer_user_agent', true));
    }

    private static function clone_order_billing_shipping($master_order_id, $child_order_id)
    {
        $master_order_metadata = \get_metadata('post', $master_order_id, '', true);
        unset($master_order_metadata['_order_key']);
        unset($master_order_metadata['plationline_redirect_url']);
        unset($master_order_metadata['_transaction_id']);
        unset($master_order_metadata['plationline_status_history']);
        unset($master_order_metadata['_plationline_recurrence_master_order_id']);
        unset($master_order_metadata['_plationline_recurrence_master_transaction_id']);
        unset($master_order_metadata['_plationline_transaction_type']);
        foreach ($master_order_metadata as $key => $value) {
            \update_post_meta($child_order_id, $key, $value[0]);
        }
    }

    private static function clone_order_fees($master_order, $child_order)
    {
        $fee_items = $master_order->get_fees();
        if (!empty($fee_items)) {
            foreach ($fee_items as $fee_key => $fee_value) {
                $fee_item = new \WC_Order_Item_Fee();
                $fee_item->set_props(array(
                    'name' => $fee_value['name'],
                    'tax_class' => $fee_value['tax_class'],
                    'tax_status' => $fee_value['tax_status'],
                    'total' => $fee_value['total'],
                    'total_tax' => $fee_value['total_tax'],
                    'taxes' => $fee_value['taxes'],
                ));
                $child_order->add_item($fee_item);
            }
        }
    }

    private static function clone_order_coupons($master_order, $child_order)
    {
        $coupon_items = $master_order->get_coupon_codes();
        if (!empty($coupon_items)) {
            foreach ($master_order->get_items('coupon') as $coupon_key => $coupon_values) {
                $coupon_item = new \WC_Order_Item_Coupon();
                $coupon_item->set_props(array(
                    'name' => $coupon_values['name'],
                    'code' => $coupon_values['code'],
                    'discount' => $coupon_values['discount'],
                    'discount_tax' => $coupon_values['discount_tax'],
                ));
                $child_order->add_item($coupon_item);
            }
        }
    }

    private static function clone_order_items($master_order, $child_order)
    {
        foreach ($master_order->get_items() as $order_key => $values) {
            if ($values['variation_id'] != 0) {
                $product = new \WC_Product_Variation($values['variation_id']);
            } else {
                $product = new \WC_Product($values['product_id']);
            }

            $item = new \WC_Order_Item_Product();
            $item->legacy_values = $values;
            $item->legacy_cart_item_key = $order_key;

            $item->set_props(array(
                'quantity' => $values['quantity'],
                'variation' => $values['variation'],
                'subtotal' => $values['line_subtotal'],
                'total' => $values['line_total'],
                'subtotal_tax' => $values['line_subtotal_tax'],
                'total_tax' => $values['line_tax'],
                'taxes' => $values['line_tax_data'],
            ));

            if ($product) {
                $item->set_props(array(
                    'name' => $product->get_name(),
                    'tax_class' => $product->get_tax_class(),
                    'product_id' => $product->is_type('variation') ? $product->get_parent_id() : $product->get_id(),
                    'variation_id' => $product->is_type('variation') ? $product->get_id() : 0,
                ));
            }

            foreach ($values->get_meta_data() as $name => $value) {
                $item->add_meta_data($value->key, $value->value, true);
            }
            $item->set_backorder_meta();
            $child_order->add_item($item);
        }
    }

    private static function clone_order_shipping_items($master_order, $child_order)
    {
        $master_order_shipping_items = $master_order->get_items('shipping');

        foreach ($master_order_shipping_items as $master_order_shipping_item) {

            $item_id = \wc_add_order_item($child_order->get_id(), array(
                'order_item_name' => $master_order_shipping_item['name'],
                'order_item_type' => 'shipping',
            ));
            if ($item_id) {
                \wc_add_order_item_meta($item_id, 'method_id', $master_order_shipping_item['method_id']);
                \wc_add_order_item_meta($item_id, 'cost', \wc_format_decimal($master_order_shipping_item['cost']));

                $meta_data = $master_order_shipping_item->get_meta_data();
                if (!empty($meta_data)) {
                    foreach ($meta_data as $meta) {
                        $meta_value = $meta->get_data();
                        \wc_add_order_item_meta($item_id, $meta_value['key'], $meta_value['value']);
                    }
                }
            }

        }
    }

    public function plationline_woocommerce_order_is_paid_statuses($statuses)
    {
        $statuses[] = 'po-autorizata';
        $statuses[] = 'po-incasare';
        $statuses[] = 'po-incasata';
        return $statuses;
    }
}
