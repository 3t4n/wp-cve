<?php

/**
 *
 * @package   Woocommerce JCC Payments Gateway Integration
 * @category Integration
 * @author   JCC
 */
if (!class_exists('WC_JCC_API')):

    class WC_JCC_API
{

        private $test_mode; // input from admin console
        // Request attributes
        // Test configurations
        private $test_financial_service_wsdl; // input from admin console
        private $test_request_url; // input from admin console
        private $test_merchant_id; // input from admin console
        private $test_password; // input from admin console
        // Production configurations
        private $production_financial_service_wsdl; // input from admin console
        private $production_request_url; // input from admin console
        private $production_merchant_id; // input from admin console
        private $production_password; // input from admin console
        // General configurations
        private $payment_gateway_response_url; // corresponds to WooCommerce's hook called 'nofify_url'
        private $version; // input from admin console
        private $acquirer_id; // input from admin console
        private $currency; // it depends from WooCommerce configs
        private $currency_exp; // constant, always 2
        private $capture_flag; // input from admin console
        private $order_id; // WC order's property and JCC response's property
        private $purchase_amt; // WC order's property
        private $padded_purchase_amt; // calculated from purchase_amt
        private $formatted_purchase_amt; // calculated from padded_purchase_amt
        private $to_encrypt; // calculated
        private $signature; // calculated
        private $base_64_signature; // calculated
        private $signature_method; // input from admin console
        private $send_billing_info; //input from admin console
        private $send_shipping_info; //input from admin console
        private $send_general_info; //input from admin console
        // Response attributes
        private $response_code; // JCC response's property
        private $reason_code; // JCC response's property
        private $reason_code_description; // JCC response's property
        private $reference_number; // JCC response's property
        private $padded_card_number; // JCC response's property
        private $response_signature; // JCC response's property
        private $authorization_code; // JCC response's property
        private $merchant_order_id_prefix; //input from admin console
        private $custom_order_id; //input from admin console

        public function __construct($data)
    {

            $this->check_not_null_or_not_empty('data', $data);

            if (isset($data['test_mode'])) {
                $this->test_mode = $data['test_mode'] === 'yes' ? true : false;
            }

            if (isset($data['test_financial_service_wsdl'])) {
                $this->test_financial_service_wsdl = $data['test_financial_service_wsdl'];
            }

            if ($this->test_mode) {
                $this->check_not_null_or_not_empty('test_financial_service_wsdl', $this->test_financial_service_wsdl);
            }

            if (isset($data['test_request_url'])) {
                $this->test_request_url = $data['test_request_url'];
            }

            if ($this->test_mode) {
                $this->check_not_null_or_not_empty('test_request_url', $this->test_request_url);
            }

            if (isset($data['test_merchant_id'])) {
                $this->test_merchant_id = $data['test_merchant_id'];
            }

            if ($this->test_mode) {
                $this->is_valid_input('test_merchant_id', $this->test_merchant_id, '20');
            }

            if (isset($data['test_password'])) {
                $this->test_password = $data['test_password'];
            }

            if ($this->test_mode) {
                $this->check_not_null_or_not_empty('test_password', $this->test_password);
            }

            if (isset($data['production_financial_service_wsdl'])) {
                $this->production_financial_service_wsdl = $data['production_financial_service_wsdl'];
            }

            if (!$this->test_mode) {
                $this->check_not_null_or_not_empty('production_financial_service_wsdl', $this->production_financial_service_wsdl);
            }

            if (isset($data['production_request_url'])) {
                $this->production_request_url = $data['production_request_url'];
            }

            if (!$this->test_mode) {
                $this->check_not_null_or_not_empty('production_request_url', $this->production_request_url);
            }

            if (isset($data['production_merchant_id'])) {
                $this->production_merchant_id = $data['production_merchant_id'];
            }

            if (!$this->test_mode) {
                $this->is_valid_input('production_merchant_id', $this->production_merchant_id, '20');
            }

            if (isset($data['production_password'])) {
                $this->production_password = $data['production_password'];
            }

            if (!$this->test_mode) {
                $this->check_not_null_or_not_empty('production_password', $this->production_password);
            }

            if (isset($data['payment_gateway_response_url'])) {
                $this->payment_gateway_response_url = $data['payment_gateway_response_url'];
            }

            $this->check_not_null_or_not_empty('payment_gateway_response_url', $this->payment_gateway_response_url);

            if (isset($data['version'])) {
                $this->version = $data['version'];
            }

            $this->check_not_null_or_not_empty('version', $this->version);

            if (isset($data['acquirer_id'])) {
                $this->acquirer_id = $data['acquirer_id'];
            }

            $this->is_valid_input('acquirer_id', $this->acquirer_id, '20');

            if (isset($data['currency'])) {
                $this->currency = $data['currency'];
            }

            $this->check_not_null_or_not_empty('currency', $this->currency);
            $this->get_currency_iso_code();

            if (isset($data['currency_exp'])) {
                $this->currency_exp = $data['currency_exp'];
            }

            $this->check_not_null_or_not_empty('currency_exp', $this->currency_exp);

            $this->check_not_null_or_not_empty('currency_exp_invalid_format', intval($this->currency_exp) == 2, __('Currency exponent (a.k.a. Number of decimals) should be equal to 2.', 'woocommerce_jcc_payment_gateway'));

            if (isset($data['capture_flag'])) {
                $this->capture_flag = $data['capture_flag'];
            }
            $this->check_not_null_or_not_empty('capture_flag', $this->capture_flag);

            if (isset($data['signature_method'])) {
                $this->signature_method = $data['signature_method'];
            }

            if (isset($data['send_billing_info'])) {
                $this->send_billing_info = $data['send_billing_info'] === 'yes' ? true : false;
            }

            if (isset($data['send_shipping_info'])) {
                $this->send_shipping_info = $data['send_shipping_info'] === 'yes' ? true : false;
            }

            if (isset($data['send_general_info'])) {
                $this->send_general_info = $data['send_general_info'] === 'yes' ? true : false;
            }

            if (isset($data['merchant_order_id_prefix'])) {
                $this->merchant_order_id_prefix = $data['merchant_order_id_prefix'];
            }

            $this->is_valid_input('merchant_order_id_prefix', $this->merchant_order_id_prefix, '10');

            if ($this->test_mode) {
                $password = $this->test_password;
                $merchant_id = $this->test_merchant_id;
            } else {
                $password = $this->production_password;
                $merchant_id = $this->production_merchant_id;
            }

            if (isset($data['custom_order_id'])) {
                $this->custom_order_id = $data['custom_order_id'];
            }

            $this->check_not_null_or_not_empty('custom_order_id', $this->custom_order_id);

            if (isset($data['order_id'])) {
                $this->order_id = $data['order_id'];
            }

            if (isset($data['purchase_amt'])) {
                $this->purchase_amt = $data['purchase_amt'];
            }

            // Init request payload
            if ($this->purchase_amt) {

                $this->check_not_null_or_not_empty('order_id', $this->order_id);

                $this->padded_purchase_amt = $this->pad_purchase_amount($this->purchase_amt);
                $this->check_not_null_or_not_empty('padded_purchase_amt', $this->padded_purchase_amt);

                $this->formatted_purchase_amt = $this->format_purchase_amount($this->padded_purchase_amt);
                $this->check_not_null_or_not_empty('formatted_purchase_amt', $this->formatted_purchase_amt);

                $this->to_encrypt = $this->data_to_encrypt(
                    $password, $merchant_id, $this->acquirer_id, $this->order_id, $this->formatted_purchase_amt, $this->get_currency_iso_code());
                $this->check_not_null_or_not_empty('to_encrypt', $this->to_encrypt);

                $this->signature = $this->sha1_encryption($this->to_encrypt);
                $this->check_not_null_or_not_empty('signature', $this->signature);

                $this->base_64_signature = $this->base64_encoding($this->signature);
                $this->check_not_null_or_not_empty('base_64_signature', $this->base_64_signature);
            }

            if (isset($data['response_code'])) {
                $this->response_code = $data['response_code'];
            }

            if (isset($data['reason_code'])) {
                $this->reason_code = $data['reason_code'];
            }

            if (isset($data['reason_code_description'])) {
                $this->reason_code_description = $data['reason_code_description'];
            }

            if (isset($data['reference_number'])) {
                $this->reference_number = $data['reference_number'];
            }

            if (isset($data['padded_card_number'])) {
                $this->padded_card_number = $data['padded_card_number'];
            }

            if (isset($data['response_signature'])) {
                $this->response_signature = $data['response_signature'];
            }

            // Authorization code is only returned in case of successful transaction,
            // indicated with a value of 1 for both response code and reason code
            if ($this->response_code == 1 && $this->reason_code == 1) {
                $this->authorization_code = $data['authorization_code'];
                $this->check_not_null_or_not_empty('authorization_code', $this->authorization_code);
            }

            // Init response payload
            if ($this->response_code || $this->reason_code) {

                $this->check_not_null_or_not_empty('order_id', $this->order_id);

                $this->check_not_null_or_not_empty('reason_code_description', $this->reason_code_description);

                $this->check_not_null_or_not_empty('response_signature', $this->response_signature);

                $this->to_encrypt = $this->data_to_encrypt(
                    $password, $merchant_id, $this->acquirer_id, $this->order_id, $this->response_code, $this->reason_code);
                $this->check_not_null_or_not_empty('to_encrypt', $this->to_encrypt);

                $this->signature = $this->sha1_encryption($this->to_encrypt);
                $this->check_not_null_or_not_empty('signature', $this->signature);

                $this->base_64_signature = $this->base64_encoding($this->signature);
                $this->check_not_null_or_not_empty('base_64_signature', $this->base_64_signature);
            }
        }

        public function do_refund($order_id, $amount = null, $reason = '')
    {
            $this->check_not_null_or_not_empty('order_id', $order_id);
            $this->check_not_null_or_not_empty('amount', $amount);

            ini_set('soap.wsdl_cache_enabled', 0);
            ini_set('soap.wsdl_cache_ttl', 900);
            ini_set('default_socket_timeout', 15);

            $options = array(
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => true,
                'exceptions' => 1,
                'soap_version' => SOAP_1_1,
            );

            if ($this->test_mode) {
                $financial_service_wsdl = $this->test_financial_service_wsdl;
                $password = $this->test_password;
                $merchant_id = $this->test_merchant_id;
            } else {
                $financial_service_wsdl = $this->production_financial_service_wsdl;
                $password = $this->production_password;
                $merchant_id = $this->production_merchant_id;
            }

            $financial_service_soap_client = new SoapClient($financial_service_wsdl, $options);

            $AuthHeader = new stdClass();
            $AuthHeader->MerchantID = $merchant_id;
            $AuthHeader->AcquirerID = $this->acquirer_id;
            $AuthHeader->Password = $password;

            $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
            $xml = file_get_contents($financial_service_wsdl, false, $context);
            $xml = simplexml_load_string($xml);
            $target_namespace = $xml->attributes()->targetNamespace;

            $header = new SoapHeader($target_namespace, 'AuthHeader', $AuthHeader, false);
            //set the Headers of Soap Client.
            $financial_service_soap_client->__setSoapHeaders($header);

            $Culture = '';
            $sError = 'Success';

            $refundAmountClass = new stdClass();
            $refundAmountClass->OrderID = $order_id;
            $refundAmountClass->Amount = $amount;
            $refundAmountClass->Culture = $Culture;
            $refundAmountClass->sError = $sError;

            $financial_service_response = $financial_service_soap_client->RefundAmount($refundAmountClass);

            return $financial_service_response;
        }

        public function get_currency_iso_code()
    {
            $this->check_not_null_or_not_empty('currency', $this->currency);
            $iso_code = null;
            if ($this->currency === 'EUR') {
                $iso_code = 978;
            } else if ($this->currency === 'USD') {
            $iso_code = 840;
        } else if ($this->currency === 'GBP') {
            $iso_code = 826;
        } else if ($this->currency === 'CHF') {
            $iso_code = 756;
        } else if ($this->currency === 'RUB') {
            $iso_code = 643;
        }

        $this->check_not_null_or_not_empty('iso_code', $iso_code, sprintf(__('Currency "%s" is not supported by JCC Payment Gateway.', 'woocommerce_jcc_payment_gateway'), $this->currency));
        return $iso_code;
    }

    public function get_test_mode()
    {
        return $this->test_mode;
    }

    public function get_test_financial_service_wsdl()
    {
        return $this->test_financial_service_wsdl;
    }

    public function get_test_request_url()
    {
        return $this->test_request_url;
    }

    public function get_test_merchant_id()
    {
        return $this->test_merchant_id;
    }

    public function get_payment_gateway_response_url()
    {
        return $this->payment_gateway_response_url;
    }

    public function get_test_password()
    {
        return $this->test_password;
    }

    public function get_production_financial_service_wsdl()
    {
        return $this->production_financial_service_wsdl;
    }

    public function get_production_request_url()
    {
        return $this->production_request_url;
    }

    public function get_production_merchant_id()
    {
        return $this->production_merchant_id;
    }

    public function get_production_password()
    {
        return $this->production_password;
    }

    public function get_version()
    {
        return $this->version;
    }

    public function get_acquirer_id()
    {
        return $this->acquirer_id;
    }

    public function get_purchase_amt()
    {
        return $this->purchase_amt;
    }

    public function get_padded_purchase_amt()
    {
        return $this->padded_purchase_amt;
    }

    public function get_formatted_purchase_amt()
    {
        return $this->formatted_purchase_amt;
    }

    public function get_currency()
    {
        return $this->currency;
    }

    public function get_currency_exp()
    {
        return $this->currency_exp;
    }

    public function get_order_id()
    {
        return $this->order_id;
    }

    public function get_capture_flag()
    {
        return $this->capture_flag;
    }

    public function get_to_encrypt()
    {
        return $this->to_encrypt;
    }

    public function get_signature()
    {
        return $this->signature;
    }

    public function get_base_64_signature()
    {
        return $this->base_64_signature;
    }

    public function get_signature_method()
    {
        return $this->signature_method;
    }

    public function get_send_billing_info()
    {
        return $this->send_billing_info;
    }

    public function get_send_shipping_info()
    {
        return $this->send_shipping_info;
    }

    public function get_send_general_info()
    {
        return $this->send_general_info;
    }

    public function get_response_code()
    {
        return $this->response_code;
    }

    public function get_reason_code()
    {
        return $this->reason_code;
    }

    public function get_reason_code_description()
    {
        return $this->reason_code_description;
    }

    public function get_reference_number()
    {
        return $this->reference_number;
    }

    public function get_padded_card_number()
    {
        return $this->padded_card_number;
    }

    public function get_response_signature()
    {
        return $this->response_signature;
    }

    public function get_authorization_code()
    {
        return $this->authorization_code;
    }

    private function pad_purchase_amount($purchase_amount)
    {
        $this->check_not_null_or_not_empty('purchase_amount', $purchase_amount);
        return str_pad($purchase_amount, 13, "0", STR_PAD_LEFT);
    }

    private function format_purchase_amount($padded_amount)
    {
        $this->check_not_null_or_not_empty('padded_amount', $padded_amount);
        return substr($padded_amount, 0, 10) . substr($padded_amount, 11);
    }

    private function data_to_encrypt(
        $password, $merchant_id, $acquirer_id, $order_id, $var1, $var2) {
        $this->check_not_null_or_not_empty('password', $password);
        $this->check_not_null_or_not_empty('merchant_id', $merchant_id);
        $this->check_not_null_or_not_empty('acquirer_id', $acquirer_id);
        $this->check_not_null_or_not_empty('order_id', $order_id);
        $this->check_not_null_or_not_empty('var1', $var1); // if request then use formatted_purchase_amt, else response_code
        $this->check_not_null_or_not_empty('var2', $var2); // if request then use currency, else reason_code
        return $password . $merchant_id . $acquirer_id . $order_id . $var1 . $var2;
    }

    private function sha1_encryption($data)
    {
        $this->check_not_null_or_not_empty('data', $data);
        return sha1($data);
    }

    private function base64_encoding($data)
    {
        $this->check_not_null_or_not_empty('data', $data);
        return base64_encode(pack("H*", $data));
    }

    private function check_not_null_or_not_empty($key, $value, $error_message = null)
    {
        if (!$error_message) {
            $error_message = sprintf(__('%s cannot be null or empty.', 'woocommerce_jcc_payment_gateway'), $key);
        }

        if (!isset($value) || empty($value)) {
            throw new Exception(__($error_message, 'woocommerce_jcc_payment_gateway'));
        }
        return $value;
    }

    private function is_valid_input($key, $value, $max_length, $error_message = null)
    {
        if (!$error_message) {
            $error_message = sprintf(__('%s is invalid,alphanumeric values up to ' . $max_length . ' characters are accepted.', 'woocommerce_jcc_payment_gateway'), $key);
        }

        if ((strlen($value) > $max_length || !ctype_alnum($value)) && (isset($value) && !empty($value))) {
            throw new Exception(__($error_message, 'woocommerce_jcc_payment_gateway'));
        }
    }
}

endif;
