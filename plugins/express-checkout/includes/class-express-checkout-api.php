<?php

if (!defined('ABSPATH'))
    exit;

class Express_Checkout_API {

    const PRODUCTION_ENDPOINT = 'https://api-3t.paypal.com/nvp';
    const SANDBOX_ENDPOINT = 'https://api-3t.sandbox.paypal.com/nvp';
    const VERSION = '115';

    public $request_method = 'POST';
    public $request_http_version;
    public $gateway_id;
    public $order;
    public $express_checkout_utility;
    public $response;

    public function __construct($gateway_id, $api_environment, $api_username, $api_password, $api_signature, $debug) {
        $this->gateway_id = $gateway_id;
        $this->request_uri = ( $api_environment === false ) ? self::PRODUCTION_ENDPOINT : self::SANDBOX_ENDPOINT;
        $this->request_http_version = '1.1';
        $this->api_username = $api_username;
        $this->api_password = $api_password;
        $this->api_signature = $api_signature;
        $this->debug = $debug;
        $this->log = "";
        $this->setmethods = '';
    }

    public function ex_set_express_checkout($args) {
        $this->setmethods = 'SetExpressCheckout';
        $request = $this->ex_new_request();
        $request->ex_set_express_checkout($args);

        $this->ex_request_response_log_write($request->parameters);
        return $this->ex_perform_request($request);
    }

    public function ex_get_express_checkout_details($token) {
        $this->setmethods = 'GetExpressCheckoutDetails';
        $request = $this->ex_new_request();
        $request->ex_get_express_checkout_details($token);
        $this->ex_request_response_log_write($request->parameters);
        return $this->ex_perform_request($request);
    }

    public function ex_get_express_checkout_transaction($PayPalConfig) {

        $NVPCredentials = 'USER=' . $PayPalConfig['APIUsername'] . '&PWD=' . $PayPalConfig['APIPassword'] . '&VERSION=' . $this->request_http_version . '&BUTTONSOURCE=' . $PayPalConfig['Buttonsource'];
        $NVPCredentials .= $PayPalConfig['APISubject'] != '' ? '&SUBJECT=' . $PayPalConfig['APISubject'] : '';
        $NVPCredentials .= $PayPalConfig['APISignature'] != '' ? '&SIGNATURE=' . $PayPalConfig['APISignature'] : '';
        $NVPCredentials .= $PayPalConfig['APIMethod'] != '' ? '&METHOD=' . $PayPalConfig['APIMethod'] : '';
        $NVPCredentials .= $PayPalConfig['transactionid'] != '' ? '&TRANSACTIONID=' . $PayPalConfig['transactionid'] : '';
        $response = $this->ex_do_remote_request($this->ex_get_request_uri(), $this->Ex_GetTransactionDetails($NVPCredentials));
        $response = $this->ex_handle_response($response);

        return $response;
    }

    public function Ex_GetTransactionDetails($NVPCredentials) {
        $args = array(
            'method' => 'POST',
            'timeout' => 60,
            'redirection' => 0,
            'httpversion' => $this->request_http_version,
            'sslverify' => FALSE,
            'blocking' => true,
            'user-agent' => 'Express_checkout',
            'headers' => array(),
            'body' => $NVPCredentials,
            'cookies' => array(),
        );
        return apply_filters('ex_' . $this->gateway_id . '_http_request_args', $args, $this);
    }

    public function express_checkout_authorization($order) {
        $this->setmethods = 'DoExpressCheckoutPayment';
        $this->order = $order;
        $request = $this->ex_new_request();
        $request->ex_do_payment($order);
        $this->ex_request_response_log_write($request->parameters);
        return $this->ex_perform_request($request);
    }

    public function ex_new_request($type = array()) {
        return new Express_Checkout_API_Request($this->api_username, $this->api_password, $this->api_signature, self::VERSION);
    }

    public function ex_get_plugin() {
        return 'express_checkout';
    }

    public function ex_perform_request($request) {
        $this->ex_reset_response();
        $this->request = $request;
        $response = $this->ex_do_remote_request($this->ex_get_request_uri(), $this->ex_get_request_args());
        $response = $this->ex_handle_response($response);
        $this->ex_request_response_log_write($response);
        return $response;
    }

    public function ex_do_remote_request($request_uri, $request_args) {
        return wp_safe_remote_request($request_uri, $request_args);
    }

    public function ex_reset_response() {
        $this->response = null;
    }

    public function ex_get_request_uri() {
        $uri = $this->request_uri . '';
        return apply_filters('ex_' . $this->gateway_id . '_api_request_uri', $uri, $this);
    }

    public function ex_get_request_args() {
        $args = array(
            'method' => $this->ex_get_request_method(),
            'timeout' => 60,
            'redirection' => 0,
            'httpversion' => $this->request_http_version,
            'sslverify' => FALSE,
            'blocking' => true,
            'user-agent' => 'Express_checkout',
            'headers' => array(),
            'body' => $this->ex_get_request()->ex_to_string(),
            'cookies' => array(),
        );
        return apply_filters('ex_' . $this->gateway_id . '_http_request_args', $args, $this);
    }

    public function ex_get_request_method() {
        return $this->request_method;
    }

    public function ex_get_request() {
        return $this->request;
    }

    public function ex_handle_response($response) {
        if (isset($response->errors['http_request_failed'])) {
            $this->response = $response->errors;
        } else if (isset($response->errors['http_failure'])) {
            $this->response = $response->errors;
        } else {
            $this->response = $this->ex_get_parsed_response($response);
        }
        return $this->response;
    }

    public function ex_get_parsed_response($response) {
        if (is_wp_error($response)) {
            return;
        }
        parse_str($response['body'], $parsed_response);
        return $parsed_response;
    }

    public function ex_request_response_log_write($message) {
        if ($this->debug) {
            if (empty($this->log)) {
                $this->log = new WC_Logger();
            }
            $message = $this->ex_request_response_personal_detail_sequre($message);
            $this->log->add('express_chekout', $this->setmethods . print_r($message, true));
        }
        return true;
    }

    public function ex_request_response_personal_detail_sequre($message) {

        foreach ($message as $key => $value) {
            if ($key == "USER" || $key == "PWD" || $key == "SIGNATURE") {
                $str_length = strlen($value);
                $ponter_data = "";
                for ($i = 0; $i <= $str_length; $i++) {
                    $ponter_data .= '*';
                }
                $message[$key] = $ponter_data;
            }
        }

        return $message;
    }

    public function express_checkout_refund($order_id, $amount, $reason) {
        $order = wc_get_order($order_id);
        $this->setmethods = 'RefundTransaction';
        $this->order = $order;
        $request = $this->ex_new_request();
        $request->ex_do_refund($order_id, $amount, $reason);
        $this->ex_request_response_log_write($request->parameters);
        return $this->ex_perform_request($request);
    }

}
