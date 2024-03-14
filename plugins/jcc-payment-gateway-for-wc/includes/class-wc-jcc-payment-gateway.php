<?php

/**
 *
 * @package   Woocommerce JCC Payment Gateway
 * @category Payment Gateway
 * @author   JCC
 */
if (!class_exists('WooCommerce_JCC_Payment_Gateway')):

    class WooCommerce_JCC_Payment_Gateway extends WC_Payment_Gateway
{

        /**
         * Init and hook in the payment_gateway.
         */
        public function __construct()
    {
            global $woocommerce;
            $this->id = 'jcc_payment_gateway';
            $this->method_title = __('JCC Payment Gateway', 'woocommerce_jcc_payment_gateway');
            $this->method_description = __('JCC’s payment gateway offers real-time '
                . 'and batch payment processing. It uses all available '
                . 'security measures to prevent fraudulent transactions and '
                . 'ensure data safety yet it’s easy to integrate with '
                . 'merchants’ systems. In addition, it allows merchants to '
                . 'review and manage transactions, prepare reports, etc. '
                . 'through a user-friendly, intuitive administration interface. '
                . 'Another feature the plugin offers, is the ability for the merchant to define a prefix value that will be appended in the merchant order id that is sent to JCCgateway. '
                . 'The current plugin supports making payment via HTTP Post '
                . 'redirect to JCC payment gateway and also refunds via JCC '
                . 'Web Services\'s endpoint, called Financial Service.', 'woocommerce_jcc_payment_gateway');
            $this->icon = apply_filters('woocommerce_jcc_gateway_checkout_icon', plugins_url('../assets/img/JCCgateway_checkout.jpg', __FILE__));
            $this->has_fields = false;
            $this->order_button_text = __('Proceed to JCC Gateway ', 'woocommerce_jcc_payment_gateway');
            $this->notify_url = WC()->api_request_url('WooCommerce_JCC_Payment_Gateway');

            $this->supports = array(
                'products',
                'refunds',
            );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('title');

            // Define user set variables
            $this->test_mode = $this->get_option('test_mode');
            // Test configurations
            $this->test_financial_service_wsdl = $this->get_option('test_financial_service_wsdl');
            $this->test_request_url = $this->get_option('test_request_url');
            $this->test_merchant_id = $this->get_option('test_merchant_id');
            $this->test_password = $this->get_option('test_password');
            // Production configurations
            $this->production_financial_service_wsdl = $this->get_option('production_financial_service_wsdl');
            $this->production_request_url = $this->get_option('production_request_url');
            $this->production_merchant_id = $this->get_option('production_merchant_id');
            $this->production_password = $this->get_option('production_password');
            // General configurations
            $this->merchant_order_id_prefix = $this->get_option('merchant_order_id_prefix');
            $this->custom_order_id = $this->get_option('custom_order_id');
            $this->version = $this->get_option('version');
            $this->acquirer_id = $this->get_option('acquirer_id');
            $this->capture_flag = $this->get_option('capture_flag');
            $this->signature_method = $this->get_option('signature_method');

            // Actions
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_receipt_jcc_payment_gateway', array($this, 'receipt_jcc_payment_gateway'));
            // Payment listener/API hook
            add_action('woocommerce_api_woocommerce_jcc_payment_gateway', array($this, 'jcc_payment_gateway_response'));

            // Check if the gateway can be used
            if (!$this->is_valid_for_use()) {
                $this->enabled = false;
            }
        }

        /**
         * Check if the store currency is set to 'EUR', 'USD', 'GBP', 'CHF' or 'RUB'
         * */
        public function is_valid_for_use()
    {
            $is_valid_for_use = false;

            if (!in_array(get_woocommerce_currency(), array('EUR', 'USD', 'GBP', 'CHF', 'RUB'))) {
                $this->msg = __('JCC doesn\'t support your store currency, set it either to EURO (EUR), United States Dollar (USD), Greate Britain Pound (GBP), Swiss Franc (CHF), Russinan Rubble (RUB) <a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=general">here</a>', 'woocommerce_jcc_payment_gateway');
                $is_valid_for_use = false;
            }
            if (intval(wc_get_price_decimals()) != 2) {
                $this->msg = 'JCC doesn\'t support your store currency number of decimals, set it 2. <a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=general">here</a>';
                $is_valid_for_use = false;
            }
            try {
                $wc_jcc_api = $this->get_wc_jcc_api_instance();
                //syslog(LOG_INFO, 'WooCommerce_JCC_Payment_Gateway::: is_valid_for_use: wc_jcc_api: ' . print_r($wc_jcc_api,true));
                $is_valid_for_use = true;
            } catch (Exception $exception) {
                WC_Admin_Settings::add_error(esc_html__($exception->getMessage()));
                $is_valid_for_use = false;
            }
            return $is_valid_for_use;
        }

        /**
         * Check if this gateway is enabled
         */
        public function is_available()
    {
            $is_available = ('yes' === $this->enabled);
            try {
                $wc_jcc_api = $this->get_wc_jcc_api_instance();
                $is_available = true;
            } catch (Exception $exception) {
                WC_Admin_Settings::add_error(esc_html__($exception->getMessage()));

                $is_available = false;
            }
            return parent::is_available();
        }

        /*
         * Returns an instance of WC_JCC_API
         */

        private function get_wc_jcc_api_instance($data_array = array())
    {
            $wc_jcc_payment_gateway_id = $this->id;
            $wc_jcc_payment_gateway_settings = get_option('woocommerce_' . $wc_jcc_payment_gateway_id . '_settings');
            $wc_jcc_payment_gateway_settings['payment_gateway_response_url'] = $this->notify_url;
            $wc_jcc_payment_gateway_settings['currency'] = get_woocommerce_currency();
            $wc_jcc_payment_gateway_settings['currency_exp'] = wc_get_price_decimals();

            $data = array_merge($wc_jcc_payment_gateway_settings, $data_array);

            include_once 'class-wc-jcc-api.php';
            return new WC_JCC_API($data);
        }

        /**
         * Initialize payment gateway settings form fields.
         */
        public function init_form_fields()
    {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'checkbox',
                    'label' => 'Enable/Disable JCC Payment Gateway',
                    'description' => __('Enable or disable the gateway.', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => 'no',
                ),
                'title' => array(
                    'title' => __('Title', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => 'Credit/Debit card',
                    'css' => 'width:350px;',
                ),
                'test_mode' => array(
                    'title' => __('Test Mode', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'checkbox',
                    'label' => 'Enable Test Mode',
                    'default' => 'yes',
                    'description' => __('Test mode enables you to test JCC payment gateway before going live.', 'woocommerce_jcc_payment_gateway'),
                ),
                'test_financial_service_wsdl' => array(
                    'title' => __('Test Financial Service WSDL', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('Enter Test Financial Service WSDL, check JCC Payment Gateway – Developer’s Guide', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => 'https://tjccpg.jccsecure.com/PgWebService/services/FinancialService?wsdl',
                    'css' => 'width:350px;',
                ),
                'test_request_url' => array(
                    'title' => __('Test Request URL', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('Enter Test Request URL, check JCC Payment Gateway – Developer’s Guide', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => 'https://tjccpg.jccsecure.com/EcomPayment/RedirectAuthLink',
                    'css' => 'width:350px;',
                ),
                'test_merchant_id' => array(
                    'title' => __('Test Merchant ID', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('Enter Test Merchant ID as provided by JCC', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => '',
                    'css' => 'width:350px;',
                ),
                'test_password' => array(
                    'title' => __('Test Password', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'password',
                    'description' => __('Enter Test Password as provided by JCC', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => '',
                    'css' => 'width:350px;',
                ),
                'production_financial_service_wsdl' => array(
                    'title' => __('Production Financial Service WSDL', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('Enter Production Financial Service WSDL, check JCC Payment Gateway – Developer’s Guide', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => 'https://jccpg.jccsecure.com/PgWebService/services/FinancialService?wsdl',
                    'css' => 'width:350px;',
                ),
                'production_request_url' => array(
                    'title' => __('Production Request URL', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('Enter Production Request URL, check JCC Payment Gateway – Developer’s Guide', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => 'https://jccpg.jccsecure.com/EcomPayment/RedirectAuthLink',
                    'css' => 'width:350px;',
                ),
                'production_merchant_id' => array(
                    'title' => __('Production Merchant ID', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('Enter Production Merchant ID as provided by JCC', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => '',
                    'css' => 'width:350px;',
                ),
                'production_password' => array(
                    'title' => __('Production Password', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'password',
                    'description' => __('Enter Production Password as provided by JCC', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => '',
                    'css' => 'width:350px;',
                ),
                'custom_order_id' => array(
                    'title' => __('Merchant Order ID format', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'select',
                    'description' => __('Format of the merchant order ID which will be sent to JJC', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => 'Numeric',
                    'options' => array(
                        'Alphanumeric1' => __('Alphanumeric starting with the prefix "wc_order_"', 'woocommerce_jcc_payment_gateway'),
                        'Alphanumeric2' => __('Alphanumeric', 'woocommerce_jcc_payment_gateway'),
                        'Numeric' => __('Numeric (matches the Order # found in the Orders section of admin \'s page)', 'woocommerce_jcc_payment_gateway'),
                    ),
                ),
                'merchant_order_id_prefix' => array(
                    'title' => __('Merchant Order ID Prefix', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('Optional.Enter a prefix up to 10 characters.It will be appended to the order ID which is sent to JCC.', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => '',
                    'css' => 'width:350px;',
                ),
                'version' => array(
                    'title' => __('Version', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('Always the same, check JCC Payment Gateway – Developer’s Guide ', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => '1.0.0',
                    'css' => 'width:350px;',
                ),
                'acquirer_id' => array(
                    'title' => __('Acquirer ID', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'text',
                    'description' => __('Always the same, check JCC Payment Gateway – Developer’s Guide ', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => '402971',
                    'css' => 'width:350px;',
                ),
                'capture_flag' => array(
                    'title' => __('Capture Flag', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'select',
                    'description' => __('Specify we want not only to authorize the amount but also capture at the same time. Alternative value could be Manual (for capturing later)', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => 'A',
                    'options' => array(
                        'A' => __('Automatic', 'woocommerce_jcc_payment_gateway'),
                        'M' => __('Manual', 'woocommerce_jcc_payment_gateway'),
                    ),
                ),
                'signature_method' => array(
                    'title' => __('Signature Method', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'select',
                    'description' => __('Hash algorithm used to create the signature; can be MD5 or SHA1. SHA1 is preferred.', 'woocommerce_jcc_payment_gateway'),
                    'desc_tip' => true,
                    'default' => 'SHA1',
                    'options' => array(
                        'SHA1' => __('SHA1', 'woocommerce_jcc_payment_gateway'),
                    ),
                ),
                'send_billing_info' => array(
                    'title' => __('Billing Info', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'checkbox',
                    'label' => 'Send Billing Info',
                    'default' => 'no',
                    'description' => __('Allow the plugin to send billing info to the Issuing Bank in order to perform a real-time risk scoring of the transaction according to EMV 3DS
					                    (Billing Address 1
					                    , Billing Address 2
					                    , Billing City
					                    , Billing Country
					                    , Billing First Name
					                    , Billing Last Name
					                    , Billing Postal Code
					                    , Billing Email
					                    , Billing Mobile Phone
					                    , Billing State)', 'woocommerce_jcc_payment_gateway'),
                ),
                'send_shipping_info' => array(
                    'title' => __('Shipping Info', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'checkbox',
                    'label' => 'Send Shipping Info',
                    'default' => 'no',
                    'description' => __('Allow the plugin to send shipping info to the Issuing Bank in order to perform a real-time risk scoring of the transaction according to EMV 3DS
					                    (Shipping Address 1
					                    , Shipping Address 2
					                    , Shipping State
					                    , Shipping City
					                    , Shipping Country
					                    , Shipping Postal Code
					                    , Shipping Method Indicator
					                    , Shipping First Name
					                    , Shipping Last Name)', 'woocommerce_jcc_payment_gateway'),
                ),
                'send_general_info' => array(
                    'title' => __('General Info', 'woocommerce_jcc_payment_gateway'),
                    'type' => 'checkbox',
                    'label' => 'Send General Info',
                    'default' => 'no',
                    'description' => __('Allow the plugin to send general info to the Issuing Bank in order to perform a real-time risk scoring of the transaction according to EMV 3DS
					                    (Shipping And Billing Addresses Match
					                    , Order Description
					                    , Delivery Time Frame
					                    , Delivery Email)', 'woocommerce_jcc_payment_gateway'),
                ),
            );
        }

        public function process_payment($order_id)
    {
            global $woocommerce;
            $order = new WC_Order($order_id);
            $order_key = $order->get_order_key();
            if ($this->custom_order_id == 'Alphanumeric2') {
                $order_key = substr($order_key, strlen('wc_order_'));
            } else if ($this->custom_order_id == 'Numeric') {
            $order_key = $order_id;
        }

        if ($this->merchant_order_id_prefix) {
            $len = strlen($this->merchant_order_id_prefix);
            if (!(substr($order_key, 0, $len) === $this->merchant_order_id_prefix)) {
                $order_key = $this->merchant_order_id_prefix . '-' . $order_key;
            }
        }
        $order->set_order_key($order_key);
        $order->save();
        return array(
            'result' => 'success',
            'redirect' => $order->get_checkout_payment_url(true),
        );
    }

    /**
     * Output for the order received page.
     * */
    public function receipt_jcc_payment_gateway($order_id)
    {
        $order = wc_get_order($order_id);

        $data_array = array(
            'order_id' => $order->get_order_key(),
            'purchase_amt' => $order->get_total(),
        );
        try {
            $wc_jcc_api = $this->get_wc_jcc_api_instance($data_array);

            if ($wc_jcc_api->get_test_mode()) {
                $request_url = $wc_jcc_api->get_test_request_url();
                $merchant_id = $wc_jcc_api->get_test_merchant_id();
                $password = $wc_jcc_api->get_test_password();
            } else {
                $request_url = $wc_jcc_api->get_production_request_url();
                $merchant_id = $wc_jcc_api->get_production_merchant_id();
                $password = $wc_jcc_api->get_production_password();
            }

            //gather data for the extra fields
            $checkout_fields = $this->get_woocommerce_checkout_fields(new WC_Checkout());
            $address_match = $this->generate_address_match($checkout_fields['shipping_address_1'], $checkout_fields['billing_address_1']);
            $shipping_method_indicator = $this->generate_shipping_method_indicator(WC()->cart->needs_shipping(), $checkout_fields['address_match']);
            $order_description = $this->generate_order_description();

            $form = '<div>
          <form method="post" name="paymentForm" id="paymentForm" action="' . $request_url . '">
              <input type="hidden" name="Version" value="' . $wc_jcc_api->get_version() . '"><br>
              <input type="hidden" name="MerID" value="' . $merchant_id . '"><br>
              <input type="hidden" name="AcqID" value="' . $wc_jcc_api->get_acquirer_id() . '"><br>
              <input type="hidden" name="MerRespURL" value="' . $wc_jcc_api->get_payment_gateway_response_url() . '"><br>
              <input type="hidden" name="PurchaseAmt" value="' . $wc_jcc_api->get_formatted_purchase_amt() . '"><br>
              <input type="hidden" name="PurchaseCurrency" value="' . $wc_jcc_api->get_currency_iso_code() . '"><br>
              <input type="hidden" name="PurchaseCurrencyExponent" value="' . $wc_jcc_api->get_currency_exp() . '"><br>
              <input type="hidden" name="OrderID" value="' . $wc_jcc_api->get_order_id() . '"><br>
              <input type="hidden" name="CaptureFlag" value="' . $wc_jcc_api->get_capture_flag() . '"><br>
              <input type="hidden" name="Signature" value="' . $wc_jcc_api->get_base_64_signature() . '"><br>
              <input type="hidden" name="SignatureMethod" value="' . $wc_jcc_api->get_signature_method() . '"><br>';
            if ($wc_jcc_api->get_send_billing_info()) {
                $form .= '
                  <input type="hidden" name="billAddress1" value="' . $checkout_fields['billing_address_1'] . '"><br>
                  <input type="hidden" name="billCity" value="' . $checkout_fields['billing_city'] . '"><br>
                  <input type="hidden" name="billCountry" value="' . $this->aplpha2_to_numeric_country_code($checkout_fields['billing_country']) . '"><br>
                  <input type="hidden" name="billFirstName" value="' . $checkout_fields['billing_first_name'] . '"><br>
                  <input type="hidden" name="billLastName" value="' . $checkout_fields['billing_last_name'] . '"><br>
                  <input type="hidden" name="billPostalCode" value="' . $checkout_fields['billing_postcode'] . '"><br>
                  <input type="hidden" name="billEmail" value="' . $checkout_fields['billing_email'] . '"><br>
                  <input type="hidden" name="billMobilePhone" value="' . $checkout_fields['billing_phone'] . '"><br>
                  <input type="hidden" name="billAddress2" value="' . $checkout_fields['billing_address_2'] . '"><br>
                  <input type="hidden" name="billState" value="' . $this->state_to_numeric_code($checkout_fields['billing_state']) . '"><br>';
            }
            if ($wc_jcc_api->get_send_shipping_info()) {
                $form .= '
                <input type="hidden" name="shipAddress1" value="' . $checkout_fields['shipping_address_1'] . '"><br>
                <input type="hidden" name="shipState" value="' . $this->state_to_numeric_code($checkout_fields['shipping_state']) . '"><br>
                <input type="hidden" name="shipCity" value="' . $checkout_fields['shipping_city'] . '"><br>
                <input type="hidden" name="shipCountry" value="' . $this->aplpha2_to_numeric_country_code($checkout_fields['shipping_country']) . '"><br>
                <input type="hidden" name="shipAddress2" value="' . $checkout_fields['shipping_address_2'] . '"><br>
                <input type="hidden" name="shipPostalCode" value="' . $checkout_fields['shipping_postcode'] . '"><br>
                <input type="hidden" name="shippingMethodIndicator" value="' . $shipping_method_indicator . '"><br>
                <input type="hidden" name="shippingFirstName" value="' . $checkout_fields['shipping_first_name'] . '"><br>
                <input type="hidden" name="shippingLastName" value="' . $checkout_fields['shipping_last_name'] . '"><br>';
            }
            if ($wc_jcc_api->get_send_general_info()) {
                $form .= '
                  <input type="hidden" name="addressMatch" value="' . $address_match . '"><br>
                  <input type="hidden" name="orderDescription" value="' . $order_description . '"><br>
                  <input type="hidden" name="deliveryTimeframe" value="' . $this->generate_delivery_timeframe(WC()->cart->needs_shipping()) . '"><br>
                  <input type="hidden" name="deliveryEmail" value="' . $checkout_fields['billing_email'] . '"><br>';
            }

            $form .= '</form>
            <script language="JavaScript">document.forms["paymentForm"].submit();</script>
            </div>';
            echo $form;

        } catch (Exception $exception) {
            $notice_type = 'error';
            wc_add_notice($exception->getMessage(), $notice_type);
            return new WP_Error($exception->getMessage());
        }
    }

    public function jcc_payment_gateway_response()
    {
        //Parameters returned from JCC
        $data_array = array(
            'acquirer_id' => sanitize_text_field($_POST['AcqID']),
            'order_id' => sanitize_text_field($_POST['OrderID']), // corresponds to WC's order_key
            'response_code' => intval($_POST['ResponseCode']),
            'reason_code' => intval($_POST['ReasonCode']),
            'reason_code_description' => sanitize_text_field($_POST['ReasonCodeDesc']),
            'reference_number' => sanitize_text_field($_POST['ReferenceNo']),
            'padded_card_number' => sanitize_text_field($_POST['PaddedCardNo']),
            'response_signature' => sanitize_text_field($_POST['ResponseSignature']),
            'authorization_code' => sanitize_text_field($_POST['ResponseSignature']),
        );

        $test_mode = $this->test_mode === 'yes' ? true : false;

        if ($test_mode) {
            $data_array['test_merchant_id'] = sanitize_text_field($_POST['MerID']);
        } else {
            $data_array['production_merchant_id'] = sanitize_text_field($_POST['MerID']);
        }

        $wc_order_key = $data_array['order_id'];
        $order_id = wc_get_order_id_by_order_key($wc_order_key);
        $order = wc_get_order($order_id);
        $order_status = $order->get_status();

        $note = sprintf(__('JCC payment gateway response payload.<br> %s', 'woocommerce_jcc_payment_gateway'), print_r($data_array, true));

        //Add Admin Order Note
        $order->add_order_note($note);

        $order->update_meta_data('jcc_merchant_id', sanitize_text_field($_POST['MerID']));
        $order->update_meta_data('jcc_order_id', $wc_order_key);
        $order->update_meta_data('jcc_response_code', $data_array['response_code']);
        $order->update_meta_data('jcc_reason_code', $data_array['reason_code']);
        $order->update_meta_data('jcc_reason_code_description', $data_array['reason_code_description']);
        $order->update_meta_data('jcc_reference_no', $data_array['reference_number']);
        $order->update_meta_data('jcc_plugin_version', YOURPLUGIN_CURRENT_VERSION);

        //If order is already in processing status (completed) do not change it's status
        if ($order_status == 'processing') {
            wc_empty_cart();
            wp_redirect($this->get_return_url($order));
            exit;
        }

        try {
            $wc_jcc_api = $this->get_wc_jcc_api_instance($data_array);

            if ($data_array['response_signature'] != $wc_jcc_api->get_base_64_signature()) {
                // Failed
                $notice = sprintf(__('Order ID: (%s) failed due to invalid signature. Please check your credentials input at the plugin settings page.', 'woocommerce_jcc_payment_gateway'), $wc_order_key);

                //Add Admin Order Note
                $order->add_order_note($notice);

                $order->update_status('failed', $notice);

                wp_redirect($this->get_return_url($order));
                exit;
            }

            if ($wc_jcc_api->get_response_code() == 1 && $wc_jcc_api->get_reason_code() == 1) {
                // Success
                $notice = sprintf(__('Payment via JCC Payment Gateway is successful (Order ID: %s)', 'woocommerce_jcc_payment_gateway'), $wc_order_key);

                $order->payment_complete();

                //Add Admin Order Note
                $order->add_order_note($notice);

                wc_empty_cart();

                wp_redirect($this->get_return_url($order));
                exit;
            } else {

                // Failed
                $notice = sprintf(__('Order ID: (%s) failed due to "%s".', 'woocommerce_jcc_payment_gateway'), $wc_order_key, $data_array['reason_code_description']);

                //Add Admin Order Note
                $order->add_order_note($notice);

                $order->update_status('failed', $notice);

                wp_redirect($this->get_return_url($order));
                exit;

            }
        } catch (Exception $exception) {
            // Failed
            $notice = sprintf(__('Order ID: (%s) failed due to "%s".', 'woocommerce_jcc_payment_gateway'), $wc_order_key, $data_array['reason_code_description']);
            //Add Admin Order Note
            $order->add_order_note($notice);
            $order->update_status('failed', $notice);
            wp_redirect($this->get_return_url($order));
        }
    }

    public function process_refund($order_id, $amount = null, $reason = '')
    {

        $order = wc_get_order($order_id);

        if (!$amount) {
            $amount = $order->get_total();
        }

        $order_key = $order->get_order_key();
        try {
            $wc_jcc_api = $this->get_wc_jcc_api_instance();

            $jcc_financial_service_response = $wc_jcc_api->do_refund($order_key, $amount, $reason);
            //syslog(LOG_INFO, 'process_refund::: jcc_financial_service_response: ' . print_r($jcc_financial_service_response, true));

            $refundAmountResult = $jcc_financial_service_response->RefundAmountResult; // boolean
            $sError = $jcc_financial_service_response->sError;

            $order->update_meta_data('jcc_refund_amount', $refundAmountResult);
            $order->update_meta_data('jcc_refund_error', $sError);

            if ($jcc_financial_service_response) {

                if ($refundAmountResult == true && $sError == 'Success') {
                    $notice = sprintf(__('Order ID (%s) and key (%s) with the amount of %s has been successfully refunded.', 'woocommerce_jcc_payment_gateway'), $order_id, $order_key, $amount);
                    $order->add_order_note($notice);
                    $order->update_status('refunded', $notice);
                    return true;
                } else {
                    $notice = sprintf(__('Order ID (%s) and key (%s) failed to be refunded due to %s. Please contact admininstrator for more help.', 'woocommerce_jcc_payment_gateway'), $order_id, $order_key, $sError);
                    $order->add_order_note($notice);
                    return new WP_Error('wc_' . $this->id . '_refund_failed', $notice);
                }
            } else {
                $notice = sprintf(__('Order ID (%s) and key (%s) failed to be refunded. Please contact admininstrator for more help.', 'woocommerce_jcc_payment_gateway'), $order_id, $order_key);
                $order->add_order_note($notice);
                return new WP_Error('wc_' . $this->id . '_refund_failed', $notice);
            }
        } catch (Exception $exception) {
            $notice = sprintf(__('Order ID (%s) failed to be refunded. Please contact admininstrator for more help.', 'woocommerce_jcc_payment_gateway'), $order_id);
            $notice_type = 'error';
            wc_add_notice($notice, $notice_type);
            return new WP_Error('wc_' . $this->id . '_refund_amount_failed_due_to', $exception->getMessage());
        }

        return false;
    }
    private function generate_address_match($first_address, $second_address)
    {
        if ($first_address == $second_address) {
            return 'Y';
        } else {
            return 'N';
        }
    }

    private function generate_shipping_method_indicator($needs_shipping, $address_match)
    {
        if (!$needs_shipping) {
            return '03'; //Digital goods
        } else if ($address_match) {
            return '01'; //Ship to cardholder billing address
        } else {
            return '02'; // Ship to another address
        }
    }

    public function generate_order_description()
    {
        $order_description = '';
        // Loop over $cart items
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $product = $cart_item['data'];
            $order_description .= $product->get_name() . ', ';
        }
        // "–" is not an  ASCII 128 char. Replace it with the Hyphen/dash/minus("-") char
        $order_description = str_replace('–', '-',$order_description);
        $order_description = $this->sanitize_checkout_field($order_description, 256);
        return $order_description;
    }

    private function get_woocommerce_checkout_fields($WC_Checkout)
    {
        return array(
            'billing_address_1' => $this->sanitize_checkout_field($WC_Checkout->get_value('billing_address_1'), 50),
            'billing_city' => $this->sanitize_checkout_field($WC_Checkout->get_value('billing_city'), 50),
            'billing_country' => $WC_Checkout->get_value('billing_country'),
            'billing_first_name' => $this->sanitize_checkout_field($WC_Checkout->get_value('billing_first_name'), 22),
            'billing_last_name' => $this->sanitize_checkout_field($WC_Checkout->get_value('billing_last_name'), 22),
            'billing_postcode' => $this->sanitize_checkout_field($WC_Checkout->get_value('billing_postcode'), 16),
            'billing_email' => $this->sanitize_checkout_field($WC_Checkout->get_value('billing_email'), 254),
            'billing_phone' => substr(str_replace('-', '', str_replace('+', '', $WC_Checkout->get_value('billing_phone'))), 0, 20),
            'billing_address_2' => $this->sanitize_checkout_field($WC_Checkout->get_value('billing_address_2'), 50),
            'billing_state' => $WC_Checkout->get_value('billing_state'),
            'shipping_address_1' => $this->sanitize_checkout_field($WC_Checkout->get_value('shipping_address_1'), 50),
            'shipping_state' => $WC_Checkout->get_value('shipping_state'),
            'shipping_city' => $this->sanitize_checkout_field($WC_Checkout->get_value('shipping_city'), 50),
            'shipping_country' => $WC_Checkout->get_value('shipping_country'),
            'shipping_address_2' => $this->sanitize_checkout_field($WC_Checkout->get_value('shipping_address_2'), 50),
            'shipping_postcode' => $this->sanitize_checkout_field($WC_Checkout->get_value('shipping_postcode'), 16),
            'shipping_first_name' => $this->sanitize_checkout_field($WC_Checkout->get_value('shipping_first_name'), 50),
            'shipping_last_name' => $this->sanitize_checkout_field($WC_Checkout->get_value('shipping_last_name'), 50),
        );
    }

    //double sanitizes the field because it will later on be used on echo form. When echo is used it unbder the hood decodes the string,
    // so if we want an html encoded string to be printed, we encode it  2 times
    public function sanitize_checkout_field($input, $max_length)
    {
        if ($input == null) {
            return null;
        }
        return substr(str_replace("#039", "apos", htmlspecialchars(htmlspecialchars($input, ENT_QUOTES), ENT_QUOTES)), 0, $max_length);
    }

    private function aplpha2_to_numeric_country_code($code)
    {
        $code = strtoupper($code);

        $countryList = array(
            'AF' => '4',
            'AX' => '248',
            'AL' => '8',
            'DZ' => '12',
            'AS' => '16',
            'AD' => '20',
            'AO' => '24',
            'AI' => '660',
            'AQ' => '10',
            'AG' => '28',
            'AR' => '32',
            'AM' => '51',
            'AW' => '533',
            'AU' => '36',
            'AT' => '40',
            'AZ' => '31',
            'BS' => '44',
            'BH' => '48',
            'BD' => '50',
            'BB' => '52',
            'BY' => '112',
            'BE' => '56',
            'BZ' => '84',
            'BJ' => '204',
            'BM' => '60',
            'BT' => '64',
            'BO' => '68',
            'BQ' => '535',
            'BA' => '70',
            'BW' => '72',
            'BV' => '74',
            'BR' => '76',
            'IO' => '86',
            'BN' => '96',
            'BG' => '100',
            'BF' => '854',
            'BI' => '108',
            'KH' => '116',
            'CM' => '120',
            'CA' => '124',
            'CV' => '132',
            'KY' => '136',
            'CF' => '140',
            'TD' => '148',
            'CL' => '152',
            'CN' => '156',
            'CX' => '162',
            'CC' => '166',
            'CO' => '170',
            'KM' => '174',
            'CG' => '178',
            'CD' => '180',
            'CK' => '184',
            'CR' => '188',
            'CI' => '384',
            'HR' => '191',
            'CU' => '192',
            'CW' => '531',
            'CY' => '196',
            'CZ' => '203',
            'DK' => '208',
            'DJ' => '262',
            'DM' => '212',
            'DO' => '214',
            'EC' => '218',
            'EG' => '818',
            'SV' => '222',
            'GQ' => '226',
            'ER' => '232',
            'EE' => '233',
            'ET' => '231',
            'FO' => '234',
            'FK' => '238',
            'FJ' => '242',
            'FI' => '246',
            'FR' => '250',
            'GF' => '254',
            'PF' => '258',
            'TF' => '260',
            'GA' => '266',
            'GM' => '270',
            'GE' => '268',
            'DE' => '276',
            'GH' => '288',
            'GI' => '292',
            'GR' => '300',
            'GL' => '304',
            'GD' => '308',
            'GP' => '312',
            'GU' => '316',
            'GT' => '320',
            'GG' => '831',
            'GN' => '324',
            'GW' => '624',
            'GY' => '328',
            'HT' => '332',
            'HM' => '334',
            'VA' => '336',
            'HN' => '340',
            'HK' => '344',
            'HU' => '348',
            'IS' => '352',
            'IN' => '356',
            'ID' => '360',
            'IR' => '364',
            'IQ' => '368',
            'IE' => '372',
            'IM' => '833',
            'IL' => '376',
            'IT' => '380',
            'JM' => '388',
            'JP' => '392',
            'JE' => '832',
            'JO' => '400',
            'KZ' => '398',
            'KE' => '404',
            'KI' => '296',
            'KP' => '408',
            'KR' => '410',
            'KW' => '414',
            'KG' => '417',
            'LA' => '418',
            'LV' => '428',
            'LB' => '422',
            'LS' => '426',
            'LR' => '430',
            'LY' => '434',
            'LI' => '438',
            'LT' => '440',
            'LU' => '442',
            'MO' => '446',
            'MK' => '807',
            'MG' => '450',
            'MW' => '454',
            'MY' => '458',
            'MV' => '462',
            'ML' => '466',
            'MT' => '470',
            'MH' => '584',
            'MQ' => '474',
            'MR' => '478',
            'MU' => '480',
            'YT' => '175',
            'MX' => '484',
            'FM' => '583',
            'MD' => '498',
            'MC' => '492',
            'MN' => '496',
            'ME' => '499',
            'MS' => '500',
            'MA' => '504',
            'MZ' => '508',
            'MM' => '104',
            'NA' => '516',
            'NR' => '520',
            'NP' => '524',
            'NL' => '528',
            'NC' => '540',
            'NZ' => '554',
            'NI' => '558',
            'NE' => '562',
            'NG' => '566',
            'NU' => '570',
            'NF' => '574',
            'MP' => '580',
            'NO' => '578',
            'OM' => '512',
            'PK' => '586',
            'PW' => '585',
            'PS' => '275',
            'PA' => '591',
            'PG' => '598',
            'PY' => '600',
            'PE' => '604',
            'PH' => '608',
            'PN' => '612',
            'PL' => '616',
            'PT' => '620',
            'PR' => '630',
            'QA' => '634',
            'RE' => '638',
            'RO' => '642',
            'RU' => '643',
            'RW' => '646',
            'BL' => '652',
            'SH' => '654',
            'KN' => '659',
            'LC' => '662',
            'MF' => '663',
            'PM' => '666',
            'VC' => '670',
            'WS' => '882',
            'SM' => '674',
            'ST' => '678',
            'SA' => '682',
            'SN' => '686',
            'RS' => '688',
            'SC' => '690',
            'SL' => '694',
            'SG' => '702',
            'SX' => '534',
            'SK' => '703',
            'SI' => '705',
            'SB' => '90',
            'SO' => '706',
            'ZA' => '710',
            'GS' => '239',
            'SS' => '728',
            'ES' => '724',
            'LK' => '144',
            'SD' => '729',
            'SR' => '740',
            'SJ' => '744',
            'SZ' => '748',
            'SE' => '752',
            'CH' => '756',
            'SY' => '760',
            'TW' => '158',
            'TJ' => '762',
            'TZ' => '834',
            'TH' => '764',
            'TL' => '626',
            'TG' => '768',
            'TK' => '772',
            'TO' => '776',
            'TT' => '780',
            'TN' => '788',
            'TR' => '792',
            'TM' => '795',
            'TC' => '796',
            'TV' => '798',
            'UG' => '800',
            'UA' => '804',
            'AE' => '784',
            'GB' => '826',
            'US' => '840',
            'UM' => '581',
            'UY' => '858',
            'UZ' => '860',
            'VU' => '548',
            'VE' => '862',
            'VN' => '704',
            'VG' => '92',
            'VI' => '850',
            'WF' => '876',
            'EH' => '732',
            'YE' => '887',
            'ZM' => '894',
            'ZW' => '716',
        );

        if (!$countryList[$code]) {
            return $code;
        } else {
            return $countryList[$code];
        }

    }

    public function state_to_numeric_code($state)
    {
        $state = strtoupper($state);
        if ($state == 'LEFKOSIA' || $state == 'NICOSIA' || $state == 'ΛΕΥΚΩΣΙΑ' || $state == 'ΛΕΥΚΩΣΊΑ') {
            return '01';
        } elseif ($state == 'LEMESOS' || $state == 'LIMASOL' || $state == 'LIMASSOL' || $state == 'ΛΕΜΕΣΟΣ' || $state == 'ΛΕΜΕΣΌΣ') {
            return '02';
        } elseif ($state == 'LARNAKA' || $state == 'LARNACA' || $state == 'ΛΑΡΝΑΚΑ' || $state == 'ΛΆΡΝΑΚΑ') {
            return '03';
        } elseif ($state == 'AMMOCHOSTOS' || $state == 'AMMOXOSTOS' || $state == 'AMOCHOSTOS' || $state == 'ΑΜΜΟΧΩΣΤΟΣ' || $state == 'ΑΜΜΌΧΩΣΤΟΣ') {
            return '04';
        } elseif ($state == 'PAPHOS' || $state == 'PAFOS' || $state == 'ΠΑΦΟΣ' || $state == 'ΠΆΦΟΣ') {
            return '05';
        } elseif ($state == 'KERYNEIA' || $state == 'KERINIA' || $state == 'ΚΕΡΥΝΕΙΑ' || $state == 'ΚΕΡΎΝΕΙΑ') {
            return '06';
        }

    }

    public function generate_delivery_timeframe($needs_shipping)
    {
        if ($needs_shipping) {
            return '01';
        }

    }

}

endif;
