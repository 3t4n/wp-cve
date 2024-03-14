<?php
defined('ABSPATH') or exit;

if (!class_exists('Montonio_Payments_Settings')) {
    require_once 'class-montonio-payments-settings.php';
}

if (!class_exists('Montonio_Payments_Checkout')) {
    require_once 'class-montonio-payments-checkout.php';
}

if (!class_exists('MontonioPaymentsSDK')) {
    require_once dirname(dirname(__FILE__)) . '/libraries/MontonioPaymentsSDK.php';
}

if (!class_exists('MontonioOrderPrefixer')) {
    require_once dirname(dirname(__FILE__)) . '/libraries/MontonioOrderPrefixer.php';
}

if (!class_exists('MontonioHelper')) {
    require_once dirname(dirname(__FILE__)) . '/libraries/MontonioHelper.php';
}

class Montonio_Payments extends WC_Payment_Gateway
{

    /**
     * @var object
     */
    protected $pluginSettings;

    public function __construct()
    {
        $this->id                 = 'montonio_payments';
        $this->icon               = 'https://public.montonio.com/logo/montonio-logomark-s.png';
        $this->has_fields         = false;
        $this->method_title       = __('Montonio Payments (old)', 'montonio-for-woocommerce');
        $this->method_description = __('Montonio Payment Initiation Service. Old version. Support ending in December 2023.', 'montonio-for-woocommerce');
        $this->init_form_fields();
        $this->init_settings();

        $this->title = __($this->get_option('title', __('Pay with your bank', 'montonio-for-woocommerce')), 'montonio-for-woocommerce');

        /**
         * Register the callback function for when returning from Montonio
         */
        add_action('woocommerce_api_montonio_payments', array($this, 'check_payment_response'));

        /**
         * Register payment notification webhook handler
         */
        add_action('woocommerce_api_montonio_payments_notification', array($this, 'check_payment_notification'));

        /**
         * Show Montonio settings in Woocommerce->settings->payments
         */
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        /**
         * Add CSS class to Montonio Logo at checkout
         */
        add_filter('woocommerce_gateway_icon', array($this, 'add_montonio_logo_callback'), 10, 3);

        /**
         * Check that currency is supported
         */
        add_filter('woocommerce_available_payment_gateways', array($this, 'validate_currency_supported'), 10, 3);

        add_filter('montonio_payments_decode_payment_token', array($this, 'decode_payment_token_or_throw'), 10, 3);
    }

    public function validate_currency_supported($available_gateways) {
        if ($this->enabled !== 'yes') {
            return $available_gateways;
        }

        if (!MontonioHelper::isClientCurrencySupported()) {
            unset($available_gateways['montonio_payments']);
        }

        return $available_gateways;
    }

    /**
     * Setup how Montonio Payments looks at checkout
     *
     * @return string - the description HTML
     */
    public function get_description()
    {
        $enqueueMode = $this->get_option('montonioPaymentsEnqueueMode', 'enqueue');

        if ($enqueueMode == 'echo') {
            echo '<link rel="stylesheet" href="' . WC_MONTONIO_PLUGIN_URL . '/payments/assets/css/payment-handle/payment-handle.css">';
        } else {
            wp_enqueue_style(
                'montonio-payments-payment-handle-logo',
                WC_MONTONIO_PLUGIN_URL . '/payments/assets/css/payment-handle/payment-handle.css'
            );
        }

        $currency = MontonioHelper::getCurrency();

        $checkout = new Montonio_Payments_Checkout();
        $checkout->set_currency($currency);
        $checkout->set_description(__($this->get_option('description'), 'montonio-for-woocommerce'));
        $checkout->set_always_show_description($this->get_option('alwaysShowDescription', 'no') == 'yes');

        if ($this->get_option('showPlaceOrderInstructions', 'no') == 'yes') {
            $checkout->set_place_order_instructions($this->get_option('instructions'));
        }

        /**
         * Get the preselected country for the dropdown
         */
        $defaultCountry          = $this->get_option('montonioPaymentsDefaultCountry', 'EE');
        $optionChangeCountryMode = $this->get_option('montonioPaymentsAutomaticallyChangeCountry', 'manual');

        if ($optionChangeCountryMode == 'wpml') {
            $wpmlCustomerLanguage = apply_filters('wpml_current_language', null);
            // TODO: Check if not exists
            $regionsByLanguage = array(
                'et' => 'EE',
                'fi' => 'FI',
                'lv' => 'LV',
                'lt' => 'LT',
                'pl' => 'PL'
            );
            foreach ($regionsByLanguage as $locale => $countryCode) {
                if ($locale == $wpmlCustomerLanguage) {
                    $defaultCountry = $countryCode;
                }
            }
        }
        $checkout->set_preferred_country($defaultCountry);

        /**
         * Translate the dropdown if needed
         */
        $optionTranslateDropdown = $this->get_option('montonioPaymentsTranslateCountryDropdown', 'english');
        if ($optionTranslateDropdown == 'translated') {
            $checkout->set_regions(array(
                'EE' => __('Estonia', 'montonio-for-woocommerce'),
                'LT' => __('Lithuania', 'montonio-for-woocommerce'),
                'LV' => __('Latvia', 'montonio-for-woocommerce'),
                'FI' => __('Finland', 'montonio-for-woocommerce'),
                'PL' => __('Poland', 'montonio-for-woocommerce'),
            ));
        }

        $checkout->set_payment_handle_style($this->get_option('paymentHandleStyle'));
        $checkout->set_payment_handle_extra_css($this->get_option('montonioPaymentsPaymentHandleCss'));
        $checkout->set_payment_methods($this->get_option('montonioPaymentsBankList'));
        $description = $checkout->get_description_html();

        $checkout->register_payment_handle_style($enqueueMode);
        $checkout->register_payment_handle_script($enqueueMode);

        return $description;
    }

    // =========================================================================
    // Checkout style
    // =========================================================================
    // public function register_checkout_style()
    // {
    //     $this->title = $this->get_option('title');
    //     $this->icon  = 'https://public.montonio.com/logo/montonio_dark_icononly.png';
    // }

    public function add_montonio_logo_callback($icon, $id)
    {
        if ($id == $this->id) {
            return str_replace('src="', 'id="montonio-payments-checkout-logo" src="', $icon);
        }
        return $icon;
    }

    // =========================================================================
    // Plugin settings and setup
    // =========================================================================

    public function init_form_fields()
    {
        $this->setPluginSettings(Montonio_Payments_Settings::create());
        $this->form_fields = $this->getPluginSettings()->getFormFields();
    }

    public function init_settings()
    {
        parent::init_settings();

        /**
         * Save current settings to the Montonio_Payments_Settings instance
         */
        $settingsClass = $this->getPluginSettings();
        $settingsClass->setSettings($this->settings);
    }

    // =========================================================================
    // Payment Flow: Redirect and Callback
    // =========================================================================

    /**
     * Process Payment.
     *
     * @param int $order_id Order ID.
     * @return array
     */
    public function process_payment($order_id)
    {

        /**
         * Get the newly created order and prepare it for submission
         */
        $order = wc_get_order($order_id);
        $order->add_order_note(__('Montonio Payments: Checkout process is started', 'montonio-for-woocommerce'));

        $merchantName = $this->get_option('montonioPaymentsMerchantName');
        $storePrefix  = $this->get_option('montonioPaymentsOrderPrefix');

        /**
         * Create new Montonio Payments SDK instance
         */
        $montonioPayments = new MontonioPaymentsSDK(
            $this->get_option('montonioPaymentsAccessKey'),
            $this->get_option('montonioPaymentsSecretKey'),
            $this->get_option('montonioPaymentsEnvironment')
        );

        // Entry point for custom integrations
        $montonioPayments = apply_filters('montonio_payments_init_sdk', $montonioPayments);

        /**
         * Prepare Payment Data for Montonio Payments
         */
        $paymentData = array(
            'amount'                    => $order->get_total(),
            'currency'                  => $order->get_currency(),
            'merchant_reference'        => MontonioOrderPrefixer::addPrefix($storePrefix, $order_id),
            'merchant_name'             => $merchantName,
            'checkout_email'            => (string) $order->get_billing_email(),
            'checkout_first_name'       => (string) $order->get_billing_first_name(),
            'checkout_last_name'        => (string) $order->get_billing_last_name(),
            'checkout_phone_number'     => (string) $order->get_billing_phone(),
            'merchant_notification_url' => add_query_arg('wc-api', 'montonio_payments_notification', home_url('/')),
            'merchant_return_url'       => add_query_arg('wc-api', 'Montonio_Payments', home_url('/')),
        );

        /**
         * Add preselected Country to query if provided
         */
        if ($_POST && array_key_exists('montonio_payments_preselected_country', $_POST) && $_POST['montonio_payments_preselected_country']) {
            $paymentData['preselected_country'] = $_POST['montonio_payments_preselected_country'];
        } else {
            $paymentData['preselected_country'] = $this->get_option('montonioPaymentsDefaultCountry');
        }

        /**
         * Add preselected ASPSP to query if provided
         */
        if ($_POST && array_key_exists('montonio_payments_preselected_aspsp', $_POST) && $_POST['montonio_payments_preselected_aspsp']) {
            $paymentData['preselected_aspsp'] = $_POST['montonio_payments_preselected_aspsp'];
        }

        /**
         * Add preselected locale if possible
         */
        $wpmlCustomerLanguage = apply_filters('wpml_current_language', null);
        if ($wpmlCustomerLanguage) {
            $paymentData['preselected_locale'] = MontonioHelper::getLocale($wpmlCustomerLanguage);
        } else {
            $paymentData['preselected_locale'] = MontonioHelper::getLocale(get_locale());
        }

        $paymentData = apply_filters('montonio_payments_before_payment_data_submission', $paymentData, $order);

        $montonioPayments->setPaymentData($paymentData);
        $paymentUrl = $montonioPayments->getPaymentUrl();

        /**
         * Return response after which redirect to Montonio Payments will happen
         */
        return array(
            'result'   => 'success',
            'redirect' => $paymentUrl,
        );
    }

    public function decode_payment_token_or_throw($token, $sdk, $secretKey) {
        if (!is_string($token)) {
            return $token;
        }

        return $sdk::decodePaymentToken($token, $secretKey);
    }

    public function check_payment_notification()
    {
        return $this->check_payment_response(true);
    }

    /**
     * Check callback from Montonio
     * and redirect user: thankyou page for success, checkout on declined/failure
     *
     * @param bool $is_notification True if webhook notification, false otherwise
     *
     * @return void
     */
    public function check_payment_response($is_notification = false)
    {
        global $woocommerce;

        /**
         * Get Payment Token from request
         */
        if (!isset($_REQUEST['payment_token'])) {
            wc_add_notice(__('Unable to finish the payment. Please try again or choose a different payment method.', 'montonio-for-woocommerce'), 'notice');
            wp_redirect(wc_get_checkout_url());
            exit;
        }

        $token = sanitize_text_field($_REQUEST['payment_token']);

        /**
         * Return URL if payment for order failed
         */
        $returnUrl = wc_get_checkout_url();

        /**
         * Create new Montonio Payments SDK instance
         */
        $montonioPayments = new MontonioPaymentsSDK(
            $this->get_option('montonioPaymentsAccessKey'),
            $this->get_option('montonioPaymentsSecretKey'),
            $this->get_option('montonioPaymentsEnvironment')
        );

        try {
            $response = apply_filters('montonio_payments_decode_payment_token', $token, $montonioPayments, $this->get_option('montonioPaymentsSecretKey'));
        } catch (Throwable $exception) {
            wc_add_notice('There was a problem with processing the order. ', 'error');
            if ($is_notification) {
                http_response_code(401);
            } else {
                wp_redirect($returnUrl);
            }

            exit;
        }

        $paymentStatus = sanitize_text_field($response->status);

        $orderId = MontonioOrderPrefixer::removePrefix(sanitize_text_field($response->merchant_reference));

        /**
         * Get order
         */
        $order = wc_get_order($orderId);

        /**
         * Check that Montonio was used to place the order
         */
        if (!$order || $order->get_payment_method() !== $this->id) {
            http_response_code(404);
            die();
        }

        /**
         * Add ASPSP name to order notes if it was provided
         */
        $comment = null;
        if ($response->payment_method_name) {
            $comment = 'Montonio Payment Method: ' . $response->payment_method_name;
        }

        if (!empty($order->get_date_paid())) {
            $returnUrl = WC_Payment_Gateway::get_return_url($order);
        } else {
            if ($paymentStatus === 'finalized') {
                $order->payment_complete();
                if ($comment) {
                    $order->add_order_note($comment);
                }
                $returnUrl = WC_Payment_Gateway::get_return_url($order);
                $woocommerce->cart->empty_cart();
            } else {
                wc_add_notice(__('Unable to finish the payment. Please try again or choose a different payment method.', 'montonio-for-woocommerce'), 'notice');
            }
        }

        if ($is_notification) {
            http_response_code(200);
        } else {
            wp_redirect($returnUrl);
        }
        

        if ($is_notification) {
            http_response_code(200);
        } else {
            wp_redirect($returnUrl);
        }

        exit;
    }

    // =========================================================================
    // Getters and Setters
    // =========================================================================

    /**
     * @return object
     */
    public function getPluginSettings()
    {
        return $this->pluginSettings;
    }

    /**
     * @param object $pluginSettings
     */
    public function setPluginSettings($pluginSettings)
    {
        $this->pluginSettings = $pluginSettings;
    }
}
