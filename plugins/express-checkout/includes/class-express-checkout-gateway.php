<?php
if (!defined('ABSPATH')) {
    exit;
}

class Express_Checkout_Gateway extends WC_Payment_Gateway {

    protected $express_checkout_utility;
    protected $log;
    protected $enable_guest_checkout;
    protected $response;

    public function __construct() {
        try {
            $this->express_checkout_utility = new Express_Checkout_Utility($this, $this->id);
            $this->id = 'express_checkout';
            $this->method_title = __('PayPal Express Checkout [Deprecated : Please use PayPal Checkout]', 'express-checkout');
            $this->method_description = __('Express Checkout is a fast, easy way for buyers to pay with PayPal.', 'express-checkout');
            //$this->icon = apply_filters('woocommerce_payment_gateway_paypal_express_checkout_icon', EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/images/card-paypal.png');
            $this->has_fields = true;
            $this->supports = array(
                'products',
                'refunds'
            );
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->enable_guest_checkout = $this->get_option('woocommerce_enable_guest_checkout') == 'yes' ? true : false;
            $this->environment = $this->get_option('testmode') === "yes" ? true : false;
            $this->brand_name = $this->get_option('brand_name');
            $this->paypal_account_optional = $this->get_option('paypal_account_optional') === "yes" ? 'yes' : 'no';
            $this->landing_page = $this->get_option('landing_page');
            $this->payment_action = $this->get_option('payment_action');
            $this->show_on_checkout = $this->get_option('show_on_checkout') === "yes" ? true : false;
            $this->checkout_logo = $this->get_option('checkout_logo');
            $this->checkout_logo_hdrimg = $this->get_option('checkout_logo_hdrimg');
            $this->customer_service_number = $this->get_option('customer_service_number');
            $this->init_form_fields();
            $this->init_settings();
            $this->order_button_text = ($this->express_checkout_utility->ex_is_express_checkout()) ? apply_filters('express_checkout_complete_order_text', __("Complete Order", 'express-checkout')) : apply_filters('express_checkout_proceed_to_paypal_text', __('Proceed to PayPal', 'express-checkout'));
            $this->api = "";
            $this->response;
            $this->is_order_completed = true;
            $this->error_display_type = $this->get_option('error_display_type') ? $this->get_option('error_display_type') : 'detailed';
            $this->error_email_notify = $this->get_option('error_email_notify') === "yes" ? true : false;
            $this->express_checkout_notifyurl = site_url('?Express_Checkout&action=ipn_handler');

            $this->show_paypal_credit = $this->get_option('show_paypal_credit') === "yes" ? true : false;
            $this->debug = $this->get_option('debug') == 'yes' ? true : false;
            $this->skip_review_order = $this->get_option('skip_review_order') == 'yes' ? true : false;
            if ($this->environment) {
                $this->api_username = $this->get_option('sandbox_api_username');
                $this->api_password = $this->get_option('sandbox_api_password');
                $this->api_signature = $this->get_option('sandbox_api_signature');
                $this->paypal_url = 'https://api-3t.sandbox.paypal.com/nvp';
                $this->url_endpoint = 'https://www.sandbox.paypal.com/webscr';
            } else {
                $this->api_username = $this->get_option('api_username');
                $this->api_password = $this->get_option('api_password');
                $this->api_signature = $this->get_option('api_signature');
                $this->paypal_url = 'https://api-3t.paypal.com/nvp';
                $this->url_endpoint = 'https://www.paypal.com/cgi-bin/webscr';
            }

            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            if (!has_action('woocommerce_api_' . strtolower(get_class($this)))) {
                add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'handle_wc_api'));
            }

            add_action('woocommerce_checkout_billing', array($this, 'ex_set_checkout_post_data'));
            add_action('woocommerce_available_payment_gateways', array($this, 'ex_disable_gateways'));
            add_filter('body_class', array($this, 'ex_add_body_class'));
            add_action('woocommerce_checkout_fields', array($this, 'ex_display_checkout_fields'));
            add_action('woocommerce_before_checkout_billing_form', array($this, 'ex_formatted_billing_address'), 9);
            add_action('woocommerce_review_order_after_submit', array($this, 'ex_cancel_link'));
            add_filter('woocommerce_terms_is_checked_default', array($this, 'ex_terms_express_checkout'));
            add_action('woocommerce_cart_emptied', array($this, 'ex_clear_session_data'));
            add_filter('woocommerce_thankyou_order_received_text', array($this, 'ex_order_received_text'), 10, 2);
        } catch (Exception $ex) {
            wc_add_notice('<strong>' . __('Payment error', 'express-checkout') . '</strong>: ' . $ex->getMessage(), 'error');
            return;
        }
    }

    /**
     * Javascript code to move it in to button add to cart wrap
     */
    public function handle_wc_api() {
        if (!isset($_GET['action'])) {
            return;
        }
        $cancel_url = wc_get_cart_url();
        $checkout_url = wc_get_checkout_url();
        if (WC()->cart->cart_contents_total <= 0) {
            wc_add_notice(__('your order amount is zero, We were unable to process your order, please try again.', 'express-checkout'), 'error');
            wp_redirect($cancel_url);
            exit;
        }
        switch ($_GET['action']) {
            case 'ex_set_express_checkout':
                $return_url = $this->express_checkout_utility->ex_get_checkout_url('ex_get_express_checkout_details');
                try {
                    $this->response = $this->ex_get_api()->ex_set_express_checkout(array(
                        'return_url' => $return_url,
                        'cancel_url' => $cancel_url,
                        'use_bml' => $this->show_paypal_credit && isset($_GET['use_bml']) && $_GET['use_bml'],
                        'landing_page' => $this->landing_page,
                        'brand_name' => $this->brand_name,
                        'page_style' => '',
                        'hdrimg' => $this->checkout_logo_hdrimg,
                        'logoimg' => $this->checkout_logo,
                        'customerservicenumber' => $this->customer_service_number,
                        'paypal_account_optional' => $this->paypal_account_optional,
                        'notifyurl' => $this->express_checkout_notifyurl,
                        'payment_action' => $this->payment_action,
                        'localecode' => $this->express_checkout_utility->ex_get_locale(),
                    ));
                    if (isset($this->response['TOKEN']) && !empty($this->response['TOKEN'])) {
                        wp_redirect($this->ex_get_payment_url($this->response['TOKEN']));
                    } else {
                        $message = '';
                        if ($this->error_email_notify) {
                            if ((!isset($this->response['http_request_failed'])) || (!isset($this->response['http_failure']))) {
                                $admin_email = get_option('admin_email');
                                $message .= __("SetExpressCheckout API call failed.", "express-checkout") . "\n\n";
                                $message .= __('Error Code: ', 'express-checkout') . $this->response['L_ERRORCODE0'] . "\n";
                                $message .= __('Error Severity Code: ', 'express-checkout') . $this->response['L_SEVERITYCODE0'] . "\n";
                                $message .= __('Short Error Message: ', 'express-checkout') . $this->response['L_SHORTMESSAGE0'] . "\n";
                                $message .= __('Detailed Error Message: ', 'express-checkout') . $this->response['L_LONGMESSAGE0'] . "\n";
                                $message .= __('User IP: ', 'express-checkout') . $this->ex_get_user_ip() . "\n";
                                $error_email_notify_mes = apply_filters('ae_ppec_error_email_message', $message, $this->response['L_ERRORCODE0'], $this->response['L_SEVERITYCODE0'], $this->response['L_SHORTMESSAGE0'], $this->response['L_LONGMESSAGE0']);
                                $subject = "PayPal Express Checkout Error Notification";
                                $error_email_notify_subject = apply_filters('ae_ppec_error_email_subject', $subject);
                                wp_mail($admin_email, $error_email_notify_subject, $error_email_notify_mes);
                            }
                        }
                        if ($this->error_display_type == 'detailed') {
                            $this->ex_timeout_errors_detailed();
                        } else {
                            $this->ex_timeout_errors_generic('An error occurred, We were unable to process your order, please try again.');
                        }
                        $this->is_order_completed = false;
                        wp_redirect($checkout_url);
                    }
                } catch (Exception $e) {
                    wc_add_notice(__('An error occurred, We were unable to process your order, please try again.', 'express-checkout'), 'error');
                    wp_redirect($checkout_url);
                }
                exit;

            case 'ex_get_express_checkout_details':
                if (!isset($_GET['token'])) {
                    return;
                }
                $token = wc_clean(wp_unslash($_GET['token']));
                try {
                    $this->response = $this->ex_get_api()->ex_get_express_checkout_details($token);
                    if ($this->response['ACK'] == 'Success') {
                        $express_checkout_api_response = new Express_Checkout_API_Response();
                        $paypal_express_checkout = array(
                            'token' => $token,
                            'shipping_details' => $express_checkout_api_response->ex_get_shipping_details($this->response),
                            'order_note' => $express_checkout_api_response->ex_get_note_text($this->response),
                            'payer_id' => $express_checkout_api_response->ex_get_payer_id($this->response),
                            'ExpresscheckoutDetails' => $this->response
                        );
                        WC()->session->set('paypal_express_checkout', $paypal_express_checkout);
                        WC()->session->shiptoname = $this->response['FIRSTNAME'] . ' ' . $this->response['LASTNAME'];
                        WC()->session->payeremail = $this->response['EMAIL'];
                        WC()->session->chosen_payment_method = $this->id;
                    } else {
                        $message = '';
                        if ($this->error_email_notify) {
                            if ((!isset($this->response['http_request_failed'])) || (!isset($this->response['http_failure']))) {
                                $admin_email = get_option("admin_email");
                                $message .= __("GetExpressCheckout API call failed.", "express-checkout") . "\n\n";
                                $message .= __('Error Code: ', 'express-checkout') . $this->response["L_ERRORCODE0"] . "\n";
                                $message .= __('Error Severity Code: ', 'express-checkout') . $this->response["L_SEVERITYCODE0"] . "\n";
                                $message .= __('Short Error Message: ', 'express-checkout') . $this->response["L_SHORTMESSAGE0"] . "\n";
                                $message .= __('Detailed Error Message: ', 'express-checkout') . $this->response["L_LONGMESSAGE0"] . "\n";
                                $message .= __('User IP: ', 'express-checkout') . $this->ex_get_user_ip() . "\n";
                                $error_email_notify_mes = apply_filters('ae_ppec_error_email_message', $message, $this->response["L_ERRORCODE0"], $this->response["L_SEVERITYCODE0"], $this->response["L_SHORTMESSAGE0"], $this->response["L_LONGMESSAGE0"]);
                                $subject = "PayPal Express Checkout Error Notification";
                                $error_email_notify_subject = apply_filters('ae_ppec_error_email_subject', $subject);
                                wp_mail($admin_email, $error_email_notify_subject, $error_email_notify_mes);
                            }
                        }
                        if ($this->error_display_type == 'detailed') {
                            $this->ex_timeout_errors_detailed();
                        } else {
                            $this->ex_timeout_errors_generic('An error occurred, We were unable to process your order, please try again.');
                        }

                        $this->is_order_completed = false;
                    }

                    if ($this->skip_review_order) {
                        WC()->checkout->posted = WC()->session->get('post_data');
                        $_POST = WC()->session->get('post_data');
                        $this->posted = WC()->session->get('post_data');
                        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
                        if (isset($_POST['shipping_method']) && is_array($_POST['shipping_method']))
                            foreach ($_POST['shipping_method'] as $i => $value)
                                $chosen_shipping_methods[$i] = wc_clean($value);
                        WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods);
                        if (WC()->cart->needs_shipping()) {
                            // Validate Shipping Methods
                            $packages = WC()->shipping->get_packages();
                            WC()->checkout()->shipping_methods = WC()->session->get('chosen_shipping_methods');
                        }
                        if (empty($this->posted)) {
                            $this->posted = array('payment_method' => $this->id);
                        }
                        $order_id = WC()->checkout()->create_order($this->posted);
                        if (is_wp_error($order_id)) {
                            throw new Exception($order_id->get_error_message());
                        }
                        $order = wc_get_order($order_id);
                        $order->set_address(WC()->session->paypal_express_checkout['shipping_details'], 'billing');
                        $order->set_address(WC()->session->paypal_express_checkout['shipping_details'], 'shipping');
                        $order->set_payment_method($this->id);

                        update_post_meta($order_id, '_payment_method', $this->id);
                        update_post_meta($order_id, '_payment_method_title', $this->title);

                        update_post_meta($order_id, '_customer_user', get_current_user_id());
                        if (!empty(WC()->session->post_data['billing_phone'])) {
                            update_post_meta($order_id, '_billing_phone', WC()->session->post_data['billing_phone']);
                        }
                        if (!empty(WC()->session->post_data['order_comments'])) {
                            update_post_meta($order_id, 'order_comments', WC()->session->post_data['order_comments']);
                            $my_post = array(
                                'ID' => $order_id,
                                'post_excerpt' => WC()->session->post_data['order_comments'],
                            );
                            wp_update_post($my_post);
                        }
                        do_action('woocommerce_checkout_order_processed', $order_id, array());
                        $this->ex_skip_reviwe_order_form($order_id);
                    } else {
                        wp_redirect(wc_get_checkout_url());
                    }
                } catch (Exception $e) {
                    wc_add_notice(__('An error occurred, We were unable to process your order, please try again.', 'express-checkout'), 'error');
                    wp_redirect($checkout_url);
                }
                exit;

            case 'doexpresscheckoutpayment':

                if (!isset($_GET['orderid'])) {
                    return;
                }
                $this->ex_skip_reviwe_order_form(wc_clean(wp_unslash($_GET['orderid'])));
                exit;
        }
    }

    public function ex_skip_reviwe_order_form($orderid) {

        try {
            $cancel_url = wc_get_cart_url();
            $checkout_url = wc_get_checkout_url();
            $order = wc_get_order($orderid);
            $this->response = $this->ex_get_api()->express_checkout_authorization($order);
            if ($this->response['ACK'] == 'Success' || $this->response['ACK'] == 'SuccessWithWarning') {
                $order->add_order_note(sprintf(__('%s payment Transaction ID: %s', 'express-checkout'), $this->title, isset($this->response['PAYMENTINFO_0_TRANSACTIONID']) ? $this->response['PAYMENTINFO_0_TRANSACTIONID'] : ''));
                $paypal_express_checkout = WC()->session->get('paypal_express_checkout');
                if (!empty($paypal_express_checkout['ExpresscheckoutDetails']['PAYERSTATUS'])) {
                    $order->add_order_note(sprintf(__('Payer Status: %s', 'express-checkout'), '<strong>' . $paypal_express_checkout['ExpresscheckoutDetails']['PAYERSTATUS'] . '</strong>'));
                }
                if (!empty($paypal_express_checkout['ExpresscheckoutDetails']['ADDRESSSTATUS'])) {
                    $order->add_order_note(sprintf(__('Address Status: %s', 'express-checkout'), '<strong>' . $paypal_express_checkout['ExpresscheckoutDetails']['ADDRESSSTATUS'] . '</strong>'));
                }
                if (isset($this->response['PAYMENTINFO_0_TRANSACTIONID']) && !is_array($this->response['PAYMENTINFO_0_TRANSACTIONID'])) {
                    $transaction_id = $this->response['PAYMENTINFO_0_TRANSACTIONID'];
                } else {
                    $transaction_id = '';
                }
                $old_wc = version_compare(WC_VERSION, '3.0', '<');
                if ($this->response['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {
                    $order->payment_complete($transaction_id);
                } else {
                    $this->update_payment_status_by_paypal_responce($orderid, $this->response);
                    if ($old_wc) {
                        update_post_meta($order->id, '_transaction_id', $transaction_id);
                    } else {
                        update_post_meta($order->get_id(), '_transaction_id', $transaction_id);
                    }

                    WC()->cart->empty_cart();
                }
                if ($old_wc) {
                    update_post_meta($order->id, '_express_chekout_transactionid', $transaction_id);
                } else {
                    update_post_meta($order->get_id(), '_express_chekout_transactionid', $transaction_id);
                }
                WC()->cart->empty_cart();
                wc_clear_notices();
                wp_redirect($this->get_return_url($order));
                exit();
            } else {
                $message = '';
                if ($this->error_email_notify) {
                    if ((!isset($this->response['http_request_failed'])) || (!isset($this->response['http_failure']))) {
                        $admin_email = get_option("admin_email");
                        $message .= __("DoExpressCheckoutPayment API call failed.", "express-checkout") . "\n\n";
                        $message .= __('Error Code: ', 'express-checkout') . $this->response["L_ERRORCODE0"] . "\n";
                        $message .= __('Error Severity Code: ', 'express-checkout') . $this->response["L_SEVERITYCODE0"] . "\n";
                        $message .= __('Short Error Message: ', 'express-checkout') . $this->response["L_SHORTMESSAGE0"] . "\n";
                        $message .= __('Detailed Error Message: ', 'express-checkout') . $this->response["L_LONGMESSAGE0"] . "\n";
                        $message .= __('User IP: ', 'express-checkout') . $this->ex_get_user_ip() . "\n";
                        $message .= __('Order ID: ') . version_compare(WC_VERSION, '3.0', '<') ? $order->id : $order->get_id() . "\n";
                        $message .= __('Customer Name: ') . WC()->session->shiptoname . "\n";
                        $message .= __('Customer Email: ') . WC()->session->payeremail . "\n";
                        WC()->session->shiptoname = '';
                        WC()->session->payeremail = '';
                        $error_email_notify_mes = apply_filters('ae_ppec_error_email_message', $message, $this->response["L_ERRORCODE0"], $this->response["L_SEVERITYCODE0"], $this->response["L_SHORTMESSAGE0"], $this->response["L_LONGMESSAGE0"]);
                        $subject = "PayPal Express Checkout Error Notification";
                        $error_email_notify_subject = apply_filters('ae_ppec_error_email_subject', $subject);
                        wp_mail($admin_email, $error_email_notify_subject, $error_email_notify_mes);
                    }
                }

                if ($this->error_display_type == 'detailed') {
                    $this->ex_timeout_errors_detailed();
                } else {
                    $this->ex_timeout_errors_generic('There was a problem paying with PayPal, please try again.');
                }
                wp_redirect($checkout_url);
            }
            return;
        } catch (Exception $e) {
            wc_add_notice(__('An error occurred, We were unable to process your order, please try again.', 'express-checkout'), 'error');
            wp_redirect($cancel_url);
        }
    }

    public function update_payment_status_by_paypal_responce($orderid, $result) {

        $order = wc_get_order($orderid);
        switch (strtolower($result['PAYMENTINFO_0_PAYMENTSTATUS'])) :
            case 'completed' :
                if ($order->status == 'completed') {
                    break;
                }
                if (!in_array(strtolower($result['PAYMENTINFO_0_TRANSACTIONTYPE']), array('cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money'))) {
                    break;
                }
                $order->add_order_note(__('Payment Completed via Express Checkout', 'express-checkout'));
                $order->payment_complete($result['PAYMENTINFO_0_TRANSACTIONID']);
                $order->add_order_note(sprintf(__('%s payment Transaction ID: %s', 'express-checkout'), $this->title, isset($result['PAYMENTINFO_0_TRANSACTIONID']) ? $result['PAYMENTINFO_0_TRANSACTIONID'] : ''));
                $paypal_express_checkout = WC()->session->get('paypal_express_checkout');
                if (!empty($paypal_express_checkout['ExpresscheckoutDetails']['PAYERSTATUS'])) {
                    $order->add_order_note(sprintf(__('Payer Status: %s', 'express-checkout'), '<strong>' . $paypal_express_checkout['ExpresscheckoutDetails']['PAYERSTATUS'] . '</strong>'));
                }
                if (!empty($paypal_express_checkout['ExpresscheckoutDetails']['ADDRESSSTATUS'])) {
                    $order->add_order_note(sprintf(__('Address Status: %s', 'express-checkout'), '<strong>' . $paypal_express_checkout['ExpresscheckoutDetails']['ADDRESSSTATUS'] . '</strong>'));
                }
                break;
            case 'pending' :
                if (!in_array(strtolower($result['PAYMENTINFO_0_TRANSACTIONTYPE']), array('cart', 'instant', 'express_checkout', 'web_accept', 'masspay', 'send_money'))) {
                    break;
                }
                switch (strtolower($result['PAYMENTINFO_0_PENDINGREASON'])) {
                    case 'address':
                        $pending_reason = __('Address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile.', 'express-checkout');
                        break;
                    case 'authorization':
                        $pending_reason = __('Authorization: The payment is pending because it has been authorized but not settled. You must capture the funds first.', 'express-checkout');
                        break;
                    case 'echeck':
                        $pending_reason = __('eCheck: The payment is pending because it was made by an eCheck that has not yet cleared.', 'express-checkout');
                        break;
                    case 'intl':
                        $pending_reason = __('intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview.', 'express-checkout');
                        break;
                    case 'multicurrency':
                    case 'multi-currency':
                        $pending_reason = __('Multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment.', 'express-checkout');
                        break;
                    case 'order':
                        $pending_reason = __('Order: The payment is pending because it is part of an order that has been authorized but not settled.', 'express-checkout');
                        break;
                    case 'paymentreview':
                        $pending_reason = __('Payment Review: The payment is pending while it is being reviewed by PayPal for risk.', 'express-checkout');
                        break;
                    case 'unilateral':
                        $pending_reason = __('Unilateral: The payment is pending because it was made to an email address that is not yet registered or confirmed.', 'express-checkout');
                        break;
                    case 'verify':
                        $pending_reason = __('Verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment.', 'express-checkout');
                        break;
                    case 'other':
                        $pending_reason = __('Other: For more information, contact PayPal customer service.', 'express-checkout');
                        break;
                    case 'none':
                    default:
                        $pending_reason = __('No pending reason provided.', 'express-checkout');
                        break;
                }
                $order->add_order_note(sprintf(__('Payment via Express Checkout Pending. PayPal reason: %s.', 'express-checkout'), $pending_reason));
                $order->update_status('on-hold');
                break;
            case 'denied' :
            case 'expired' :
            case 'failed' :
            case 'voided' :
                // Order failed
                $order->update_status('failed', sprintf(__('Payment %s via Express Checkout.', 'express-checkout'), strtolower($result['PAYMENTINFO_0_PAYMENTSTATUS'])));
                break;
            default:
                break;
        endswitch;

        return;
    }

    public function ex_timeout_errors_detailed() {

        if (isset($this->response['http_request_failed']) && count($this->response['http_request_failed']) > 0) {
            wc_add_notice(__(urldecode($this->response['http_request_failed'][0]), 'express-checkout'), 'error');
            $error_email_notify_mes = apply_filters('ae_ppec_error_email_message', $this->response['http_request_failed'][0], '', '', '', $this->response['http_request_failed'][0]);
            $subject = "PayPal Express Checkout Error Notification";
            $error_email_notify_subject = apply_filters('ae_ppec_error_email_subject', $subject);
            wp_mail(get_option('admin_email'), $error_email_notify_subject, $error_email_notify_mes);
        }
        if (isset($this->response['http_failure']) && count($this->response['http_failure']) > 0) {
            wc_add_notice(__(urldecode($this->response['http_failure'][0]), 'express-checkout'), 'error');
            $error_email_notify_mes = apply_filters('ae_ppec_error_email_message', $this->response['http_failure'][0], '', '', '', $this->response['http_failure'][0]);
            $subject = "PayPal Express Checkout Error Notification";
            $error_email_notify_subject = apply_filters('ae_ppec_error_email_subject', $subject);
            wp_mail(get_option('admin_email'), $error_email_notify_subject, $error_email_notify_mes);
        } else {
            wc_add_notice(__(urldecode($this->response["L_ERRORCODE0"]) . '-' . urldecode($this->response["L_LONGMESSAGE0"]), 'express-checkout'), 'error');
        }
    }

    public function ex_timeout_errors_generic($msg_errors) {

        if (isset($this->response['http_request_failed']) && count($this->response['http_request_failed']) > 0) {
            wc_add_notice(__('An Timed out error occurred, We were unable to process your order, please try again.', 'express-checkout'), 'error');
            $error_email_notify_mes = apply_filters('ae_ppec_error_email_message', 'An Timed out error occurred, We were unable to process your order, please try again.', '', '', '', 'An Timed out error occurred, We were unable to process your order, please try again.');
            $subject = "PayPal Express Checkout Error Notification";
            $error_email_notify_subject = apply_filters('ae_ppec_error_email_subject', $subject);
            wp_mail(get_option('admin_email'), $error_email_notify_subject, $error_email_notify_mes);
        }
        if (isset($this->response['http_failure']) && count($this->response['http_failure']) > 0) {
            wc_add_notice(__('There are no HTTP transports available which can complete the requested request.', 'express-checkout'), 'error');
            $error_email_notify_mes = apply_filters('ae_ppec_error_email_message', 'An Timed out error occurred, We were unable to process your order, please try again.', '', '', '', 'An Timed out error occurred, We were unable to process your order, please try again.');
            $subject = "PayPal Express Checkout Error Notification";
            $error_email_notify_subject = apply_filters('ae_ppec_error_email_subject', $subject);
            wp_mail(get_option('admin_email'), $error_email_notify_subject, $error_email_notify_mes);
        } else {
            wc_add_notice(__($msg_errors, 'express-checkout'), 'error');
        }
    }

    public function ex_get_payment_url($token) {
        $params = array(
            'cmd' => '_express-checkout',
            'token' => $token,
        );
        return add_query_arg($params, $this->url_endpoint);
    }

    public function ex_get_api() {
        if (is_object($this->api)) {
            return $this->api;
        }
        return $this->api = new Express_Checkout_API($this->id, $this->environment, $this->api_username, $this->api_password, $this->api_signature, $this->debug);
    }

    public function ex_get_user_ip() {
        return (isset($_SERVER['HTTP_X_FORWARD_FOR']) && !empty($_SERVER['HTTP_X_FORWARD_FOR'])) ? $_SERVER['HTTP_X_FORWARD_FOR'] : $_SERVER['REMOTE_ADDR'];
    }

    public function admin_options() {
        ?>
        <h3><?php _e($this->title, 'express-checkout'); ?></h3>
        <p><?php _e($this->method_description, 'express-checkout'); ?></p>
        <table class="form-table">
            <?php $this->generate_settings_html(); ?>
        </table>
        <script type="text/javascript">
            jQuery('#woocommerce_express_checkout_testmode').change(function () {
                var sandbox = jQuery('#woocommerce_express_checkout_sandbox_api_username, #woocommerce_express_checkout_sandbox_api_password, #woocommerce_express_checkout_sandbox_api_signature').closest('tr'),
                        production = jQuery('#woocommerce_express_checkout_api_username, #woocommerce_express_checkout_api_password, #woocommerce_express_checkout_api_signature').closest('tr');
                if (jQuery(this).is(':checked')) {
                    sandbox.show();
                    production.hide();
                } else {
                    sandbox.hide();
                    production.show();
                }
            }).change();
            jQuery('#woocommerce_express_checkout_enable_notifyurl').change(function () {
                var notifyurl = jQuery('#woocommerce_express_checkout_notifyurl').closest('tr');
                if (jQuery(this).is(':checked')) {
                    notifyurl.show();
                } else {
                    notifyurl.hide();
                }
            }).change();
        </script>
        <?php
    }

    public function init_form_fields() {
        $this->form_fields = include EXPRESS_CHECKOUT_DIR . '/includes/gateways/settings-express-checkout.php';
    }

    public function ex_is_available() {
        return $this->express_checkout_utility->express_checkout_is_available();
    }

    public function ex_set_checkout_post_data() {
//        if ('POST' == $_SERVER['REQUEST_METHOD']) {
//            return;
//        }
        if (!$this->express_checkout_utility->ex_is_express_checkout() || !$this->ex_get_session_data('shipping_details')) {
            return;
        }
        foreach ($this->ex_get_session_data('shipping_details') as $field => $value) {
            if ($value) {
                $_POST['billing_' . $field] = wc_clean(wp_unslash($value));
            }
        }
        $order_note = $this->ex_get_session_data('order_note');
        if (!empty($order_note)) {
            $_POST['order_comments'] = $this->ex_get_session_data('order_note');
        }
        $this->chosen = true;
    }

    public function ex_get_session_data($key = '') {
        $session_data = null;
        if (empty($key)) {
            $session_data = WC()->session->paypal_express_checkout;
        } elseif (isset(WC()->session->paypal_express_checkout[$key])) {
            $session_data = WC()->session->paypal_express_checkout[$key];
        }
        return $session_data;
    }

    public function ex_display_checkout_fields($checkout_fields) {
        if ($this->express_checkout_utility->ex_is_express_checkout() && $this->ex_get_session_data('shipping_details')) {
            foreach ($this->ex_get_session_data('shipping_details') as $field => $value) {
                if (isset($checkout_fields['billing']) && isset($checkout_fields['billing']['billing_' . $field])) {
                    $required = isset($checkout_fields['billing']['billing_' . $field]['required']) && $checkout_fields['billing']['billing_' . $field]['required'];
                    if (!$required || $required && $value) {
                        $checkout_fields['billing']['billing_' . $field]['class'][] = 'express-provided';
                        $checkout_fields['billing']['billing_' . $field]['class'][] = 'hidden';
                    }
                }
            }
        }
        return $checkout_fields;
    }

    public function ex_formatted_address($type) {
        if (!$this->express_checkout_utility->ex_is_express_checkout()) {
            return;
        }
        if (!$this->is_order_completed) {
            return;
        }
        ?>
        <div class="express-provided-address">
            <a href="#" class="ex-show-address-fields" data-type="<?php echo esc_attr($type); ?>"><?php esc_html_e('Edit', 'express-checkout'); ?></a>
            <address>
                <?php
                $address = array(
                    'first_name' => WC()->checkout->get_value($type . '_first_name'),
                    'last_name' => WC()->checkout->get_value($type . '_last_name'),
                    'company' => WC()->checkout->get_value($type . '_company'),
                    'address_1' => WC()->checkout->get_value($type . '_address_1'),
                    'address_2' => WC()->checkout->get_value($type . '_address_2'),
                    'city' => WC()->checkout->get_value($type . '_city'),
                    'state' => WC()->checkout->get_value($type . '_state'),
                    'postcode' => WC()->checkout->get_value($type . '_postcode'),
                    'country' => WC()->checkout->get_value($type . '_country'),
                );
                echo WC()->countries->get_formatted_address($address);
                ?>
            </address>
        </div>
        <?php
    }

    public function ex_disable_gateways($gateways) {
        if ($this->express_checkout_utility->ex_is_express_checkout()) {
            foreach ($gateways as $id => $gateway) {
                if ($id !== $this->id) {
                    unset($gateways[$id]);
                }
            }
        }
        return $gateways;
    }

    public function ex_add_body_class($classes) {
        if ($this->ex_is_checkout() && $this->express_checkout_utility->ex_is_express_checkout()) {
            $classes[] = 'express-checkout';
            if ($this->show_on_checkout && isset(WC()->session->paypal_express_terms)) {
                $classes[] = 'express-hide-terms';
            }
        }
        return $classes;
    }

    public function ex_formatted_billing_address() {
        $this->ex_formatted_address('billing');
    }

    public function ex_cancel_link() {
        if (!$this->ex_is_available() || !$this->express_checkout_utility->ex_is_express_checkout()) {
            return;
        }
        printf(
                '<a href="%1$s" class="ex-paypal-express-cancel">%2$s</a>', esc_url(add_query_arg(array('wc_paypal_express_clear_session' => true), wc_get_cart_url())), esc_html__('Cancel', 'express-checkout')
        );
    }

    public function ex_terms_express_checkout($checked_default) {
        if (!$this->ex_is_available() || !$this->express_checkout_utility->ex_is_express_checkout()) {
            return $checked_default;
        }
        if ($this->show_on_checkout && isset(WC()->session->paypal_express_terms)) {
            $checked_default = true;
        }
        return $checked_default;
    }

    public function ex_clear_session_data() {
        unset(WC()->session->paypal_express_checkout);
        unset(WC()->session->paypal_express_terms);
    }

    public function ex_is_checkout() {
        return is_page(wc_get_page_id('checkout')) || apply_filters('woocommerce_is_checkout', false);
    }

    public function ex_order_received_text($text, $order) {
        if ($order && $order->has_status('on-hold') && isset(WC()->session->held_order_received_text)) {
            $text = WC()->session->held_order_received_text;
            unset(WC()->session->held_order_received_text);
        }
        return $text;
    }

    public function process_payment($order_id) {
        if ($this->express_checkout_utility->ex_is_express_checkout()) {
            $return_url = $this->express_checkout_utility->ex_get_checkout_url('doexpresscheckoutpayment', $order_id);
            $return_url = add_query_arg('orderid', $order_id, $return_url);
            $args = array(
                'result' => 'success',
                'redirect' => $return_url,
            );
            if (isset($_POST['terms']) && wc_get_page_id('terms') > 0) {
                WC()->session->paypal_express_terms = 1;
            }
            if (is_ajax()) {
                if ($this->express_checkout_utility->ex_is_version_gte_2_4()) {
                    wp_send_json($args);
                } else {
                    echo '<!--WC_START-->' . json_encode($args) . '<!--WC_END-->';
                }
            } else {
                wp_redirect($args['redirect']);
            }
            exit;
        } else {
            wc_add_notice('express checkout false', 'error');
        }
    }

    public function ex_get_enable_notifyurl() {
        return $this->express_checkout_notifyurl;
    }

    public function get_icon() {

        $image_path = EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/images/paypal.png';

        $show_paypal_credit = ($this->get_option('show_paypal_credit')) ? $this->get_option('show_paypal_credit') : 'no';
        $is_us = false;

        if ($show_paypal_credit == 'yes') {
            if (substr(get_option("woocommerce_default_country"), 0, 2) == 'US') {
                $is_us = true;
            }
        }
        if ($is_us) {
            $image_path = EXPRESS_CHECKOUT_ASSET_URL . 'includes/assets/images/paypal-credit-card-logos.png';
        }

        $icon = "<img src=\"$image_path\" alt='" . __('Pay with PayPal', 'express-checkout') . "'/>";
        return apply_filters('woocommerce_gateway_icon', $icon, $this->id);
    }

    /**
     * Process a refund if supported
     * @param  int $order_id
     * @param  float $amount
     * @param  string $reason
     * @return  bool|wp_error True or false based on success, or a WP_Error object
     */
    public function process_refund($order_id, $amount = null, $reason = '') {
        $order = wc_get_order($order_id);
        $response = $this->ex_get_api()->express_checkout_refund($order_id, $amount, $reason);
        if ($response['ACK'] == 'Success' || $response['ACK'] == 'SuccessWithWarning') {
            $order->add_order_note('Refund Transaction ID:' . $response['REFUNDTRANSACTIONID']);
            $max_remaining_refund = wc_format_decimal($order->get_total() - $order->get_total_refunded());
            if (!$max_remaining_refund > 0) {
                $order->update_status('refunded');
            }
            return true;
        } else {
            return false;
        }
    }

}
