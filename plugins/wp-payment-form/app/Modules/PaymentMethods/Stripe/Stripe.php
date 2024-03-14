<?php

namespace WPPayForm\App\Modules\PaymentMethods\Stripe;

use WPPayForm\App\Models\Submission;
use WPPayForm\App\Services\AccessControl;
use WPPayForm\Framework\Support\Arr;
use WPPayFormPro\Classes\PaymentsHelper;
use WPPayForm\App\Modules\PaymentMethods\PaymentHelper;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Stripe Specific Actions Here
 * @since 1.0.0
 */
class Stripe
{
    public function registerHooks()
    {
        // Register The Component
        new StripeCardElementComponent();

        // Register The Action and Filters
        add_filter('wppayform/parsed_entry', array($this, 'addAddressToView'), 10, 2);
        add_filter('wppayform/submission_data_formatted', array($this, 'pushAddressToInput'), 10, 3);

        add_filter('wppayform/entry_transactions_stripe', array($this, 'addTransactionUrl'), 10, 2);
        add_filter('wppayform/choose_payment_method_for_submission', array($this, 'choosePaymentMethod'), 10, 4);


        /*
         * This is required
         */
        add_action('wppayform/after_submission_data_insert_stripe', array($this, 'addPaymentMethodStyle'), 10, 3);
        add_action('wppayform/form_submission_make_payment_stripe', array($this, 'routePaymentHandler'), 10, 6);

        // ajax endpoints for admin
        add_action('wp_ajax_wpf_save_stripe_settings', array($this, 'savePaymentSettings'));
        add_action('wp_ajax_wpf_get_stripe_settings', array($this, 'getPaymentSettings'));

        add_filter('wppayform/checkout_vars', array($this, 'addLocalizeVars'));

        /*
         * Push signup fees to single payment item
         */
        add_filter('wppayform/submitted_payment_items_stripe', array($this, 'maybeSignupFeeToPaymentItems'), 10, 4);

        // fetch all subscription entry wise
        add_action('wppayform/subscription_settings_sync_stripe', array($this, 'makeSubscriptionSync'), 10, 2);

        // cancel subscription
        add_action('wppayform/subscription_settings_cancel_stripe', array($this, 'cancelSubscription'), 10, 3);
    }


    public function makeSubscriptionSync($formId, $submissionId)
    {
        return (new StripeHostedHandler())->syncSubscription($formId, $submissionId);
    }

    public function cancelSubscription($formId, $submission, $subscription)
    {
        return (new StripeHostedHandler()) ->cancelSubscription($formId, $submission, $subscription);
    }

    public function addLocalizeVars($vars)
    {
        $formId = Arr::get($vars, 'form_id');
        $paymentSettings = $this->getStripeSettings();
        $vars['stripe_checkout_title'] = $paymentSettings['company_name'];
        $vars['stripe_checkout_logo'] = $paymentSettings['checkout_logo'];
        $vars['stripe_pub_key'] = $this->getPubKey($formId);
        $vars['stripe_secret_key'] = $this->getSecretKey($formId);
        return $vars;
    }

    public function choosePaymentMethod($paymentMethod, $elements, $formId, $form_data)
    {
        if ($paymentMethod) {
            // Already someone choose that it's their payment method
            return $paymentMethod;
        }

        // Now We have to analyze the elements and return our payment method
        foreach ($elements as $element) {
            if (isset($element['type']) && $element['type'] == 'stripe_card_element') {
                return 'stripe';
            }
        }
        return $paymentMethod;
    }

    public function addPaymentMethodStyle($submissionId, $formId, $paymentMethodElement)
    {
        $style = $this->getStripePaymentMethodByElement($paymentMethodElement);
        $submissionModel = new Submission();
        $submissionModel->updateMeta($submissionId, 'stripe_payment_style', $style);
    }

    public function routePaymentHandler($transactionId, $submissionId, $form_data, $form, $hasSubscriptions, $totalPayable = 0)
    {
        $submissionModel = new Submission();
        $handler = $submissionModel->getMeta($submissionId, 'stripe_payment_style', 'stripe_hosted');

        do_action('wppayform/form_submission_make_payment_' . $handler, $transactionId, $submissionId, $form_data, $form, $hasSubscriptions, $totalPayable);
    }

    public function maybeSignupFeeToPaymentItems($paymentItems, $formattedElements, $form_data, $subscriptionItems)
    {
        if (!$subscriptionItems) {
            return $paymentItems;
        }
        foreach ($subscriptionItems as $subscriptionItem) {
            if ($subscriptionItem['initial_amount']) {
                $signupLabel = __('Signup Fee for', 'wp-payment-form');
                $signupLabel .= ' ' . $subscriptionItem['item_name'];
                $signupLabel = apply_filters('wppayform/signup_fee_label', $signupLabel, $subscriptionItem, $form_data);
                $paymentItems[] = array(
                    'type' => 'signup_fee',
                    'parent_holder' => $subscriptionItem['element_id'],
                    'item_name' => $signupLabel,
                    'quantity' => 1,
                    'item_price' => $subscriptionItem['initial_amount'],
                    'line_total' => $subscriptionItem['initial_amount'],
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql'),
                );
            }
        }
        return $paymentItems;
    }

    public function addTransactionUrl($transactions, $formId)
    {
        foreach ($transactions as $transaction) {
            if ($transaction->payment_method == 'stripe' && $transaction->charge_id) {
                if ($transaction->payment_mode == 'test') {
                    $transactionUrl = 'https://dashboard.stripe.com/test/payments/' . $transaction->charge_id;
                } else {
                    $transactionUrl = 'https://dashboard.stripe.com/payments/' . $transaction->charge_id;
                }
                $transaction->transaction_url = $transactionUrl;
            }
        }
        return $transactions;
    }

    public function pushAddressToInput($inputItems, $formData, $formId)
    {
        if (isset($formData['__stripe_billing_address_json'])) {
            $billingAddressDetails = $formData['__stripe_billing_address_json'];
            $inputItems['__checkout_billing_address_details'] = json_decode($billingAddressDetails, true);
        }

        if (isset($formData['__stripe_shipping_address_json'])) {
            $shippingAddressDetails = $formData['__stripe_shipping_address_json'];
            $inputItems['__checkout_shipping_address_details'] = json_decode($shippingAddressDetails, true);
        }

        return $inputItems;
    }

    public function addAddressToView($parsed, $submission)
    {
        $fomattedData = $submission->form_data_formatted;
        if (isset($fomattedData['__checkout_billing_address_details'])) {
            $address = $fomattedData['__checkout_billing_address_details'];
            $parsed['__checkout_billing_address_details'] = array(
                'label' => __('Billing Address', 'wp-payment-form'),
                'value' => $this->formatAddress($address),
                'type' => '__checkout_billing_address_details'
            );
        }

        if (isset($fomattedData['__checkout_shipping_address_details'])) {
            $address = $fomattedData['__checkout_shipping_address_details'];
            $parsed['__checkout_shipping_address_details'] = array(
                'label' => 'Shipping Address',
                'value' => $this->formatAddress($address),
                'type' => '__checkout_shipping_address_details'
            );
        }

        if (!empty($fomattedData['__stripe_phone'])) {
            $parsed['__stripe_phone'] = array(
                'label' => __('Phone', 'wp-payment-form'),
                'value' => $fomattedData['__stripe_phone'],
                'type' => '__stripe_phone'
            );
        }


        if (!empty($fomattedData['__stripe_name'])) {
            $parsed['__stripe_name'] = array(
                'label' => __('Name on Card', 'wp-payment-form'),
                'value' => $fomattedData['__stripe_name'],
                'type' => '__stripe_name'
            );
        }


        return $parsed;
    }

    private function formatAddress($address)
    {
        $addressSerials = [
            'line1',
            'line2',
            'city',
            'state',
            'postal_code',
            'country'
        ];
        $formattedAddress = [];
        $address = (array)$address;

        foreach ($addressSerials as $addressSerial) {
            if (!empty($address[$addressSerial])) {
                $formattedAddress[] = $address[$addressSerial];
            }
        }

        if ($formattedAddress) {
            return implode(', ', $formattedAddress);
        }

        return implode(', ', array_filter($address));
    }

    public function savePaymentSettings($request)
    {
        AccessControl::checkAndPresponseError('set_payment_settings', 'global');
        $defaults = array(
            'payment_mode' => 'test',
            'live_pub_key' => '',
            'live_secret_key' => '',
            'test_pub_key' => '',
            'test_secret_key' => '',
            'company_name' => get_bloginfo('name'),
            'checkout_logo' => '',
            'send_meta_data' => 'no',
            'is_encrypted' => 'no'
        );
        $settings = $this->mapper($defaults, $request, false);
        // Validate the data first
        $mode = Arr::get($settings, 'payment_mode');
        if ($mode == 'test') {
            // We require test keys
            if (empty(Arr::get($settings, 'test_pub_key')) || empty(Arr::get($settings, 'test_secret_key'))) {
                wp_send_json_error(array(
                    'message' => __('Please provide Test Publishable key and Test Secret Key', 'wp-payment-form')
                ), 423);
            }
        }

        if ($mode == 'live' && !$this->isStripeKeysDefined()) {
            if (empty(Arr::get($settings, 'live_pub_key')) || empty(Arr::get($settings, 'live_secret_key'))) {
                wp_send_json_error(array(
                    'message' => __('Please provide Live Publishable key and Live Secret Key', 'wp-payment-form')
                ), 423);
            }
        }

        // Validation Passed now let's make the data
        $data = array(
            'payment_mode' => sanitize_text_field($mode),
            'live_pub_key' => sanitize_text_field(Arr::get($settings, 'live_pub_key')),
            'live_secret_key' => sanitize_text_field(Arr::get($settings, 'live_secret_key')),
            'test_pub_key' => sanitize_text_field(Arr::get($settings, 'test_pub_key')),
            'test_secret_key' => sanitize_text_field(Arr::get($settings, 'test_secret_key')),
            'company_name' => wp_unslash(Arr::get($settings, 'company_name')),
            'checkout_logo' => sanitize_text_field(Arr::get($settings, 'checkout_logo')),
        );

        if (isset($settings['send_meta_data'])) {
            $data['send_meta_data'] = sanitize_text_field(Arr::get($settings, 'send_meta_data'));
        }
        do_action('wppayform/before_save_stripe_settings', $data);
        $data = self::encryptKeys($data);
        update_option('wppayform_stripe_payment_settings', $data, false);
        do_action('wppayform/after_save_stripe_settings', $data);

        return array(
            'message' => __('Settings successfully updated', 'wp-payment-form')
        );
    }

    public function getPaymentSettings()
    {
        return array(
            'settings' => $this->getDynamicStripeSettings(),
            'webhook_url' => site_url('?wpf_stripe_listener=1'),
            'is_key_defined' => $this->isStripeKeysDefined()
        );
    }

    public static function encryptKeys($settings)
    {
        $settings['live_secret_key'] = PaymentHelper::encryptKey($settings['live_secret_key']);
        $settings['test_secret_key'] = PaymentHelper::encryptKey($settings['test_secret_key']);

        $settings['is_encrypted'] = 'yes';
        return $settings;
    }

    public static function maybeDecryptKeys($settings)
    {
        if (Arr::get($settings, 'is_encrypted') == 'yes') {
            if (!empty($settings['live_secret_key'])) {
                $settings['live_secret_key'] = PaymentHelper::decryptKey($settings['live_secret_key']);
            }

            if (!empty($settings['test_secret_key'])) {
                $settings['test_secret_key'] = PaymentHelper::decryptKey($settings['test_secret_key']);
            }
        } else {
            $encrypted = self::encryptKeys($settings);
            update_option('wppayform_stripe_payment_settings', $encrypted);
        }

        $settings['is_encrypted'] = 'yes';

        return $settings;
    }

    public function getMode($formId = false)
    {
        if ($formId && defined('WPPAYFORMHASPRO')) {
            $formPaymentSettings = (new PaymentsHelper())->getPaymentSettings($formId, 'admin');
            if (Arr::get($formPaymentSettings, 'stripe_account_type') == 'custom') {
                return Arr::get($formPaymentSettings, 'stripe_custom_config.payment_mode') == 'live' ? 'live' : 'test';
            }
        }

        $paymentSettings = $this->getStripeSettings();
        return ($paymentSettings['payment_mode'] == 'live') ? 'live' : 'test';
    }

    // wpfGetStripePaymentSettings
    private function getStripeSettings($decrypted = true)
    {
        $settings = get_option('wppayform_stripe_payment_settings', array());
        $defaults = array(
            'payment_mode' => 'test',
            'live_pub_key' => '',
            'live_secret_key' => '',
            'test_pub_key' => '',
            'test_secret_key' => '',
            'company_name' => get_bloginfo('name'),
            'checkout_logo' => '',
            'send_meta_data' => 'no',
            'is_encrypted' => 'no'
        );
        $settings = wp_parse_args($settings, $defaults);

        if ($decrypted) {
            $settings = self::maybeDecryptKeys($settings);
        }
        return $settings;
    }

    private function getDynamicStripeSettings () {
        $settings = $this->getStripeSettings();
        return $this->mapSettings($settings);
    }

    private function mapSettings($settings) 
    {
        $defaults = array(
            'payment_mode' => array(
                'value' => 'test',
                'label' => __('Payment Mode', 'wp-payment-form'),
                'options' => array(
                    'test' => __('Test Mode', 'wp-payment-form'),
                    'live' => __('Live Mode', 'wp-payment-form')
                ),
                'type' => 'payment_mode'
            ),
            'live_pub_key' => array(
                'value' => 'live',
                'label' => __('Live public key', 'wp-payment-form'),
                'type' => 'live_pub_key',
                'placeholder' => __('Live public key', 'wp-payment-form')
            ),
            'test_pub_key' => array(
                'value' => 'test',
                'label' => __('Test public key', 'wp-payment-form'),
                'type' => 'test_pub_key',
                'placeholder' => __('Test public key', 'wp-payment-form')
            ),
            'live_secret_key' => array(
                'value' => '',
                'label' => __('Live Secret Key', 'wp-payment-form'),
                'type' => 'live_secret_key',
                'placeholder' => __('Live Secret Key', 'wp-payment-form')
            ),
            'test_secret_key' => array(
                'value' => '',
                'label' => __('Test Secret Key', 'wp-payment-form'),
                'type' => 'test_secret_key',
                'placeholder' => __('Test Secret Key', 'wp-payment-form')
            ),
            'company_name' => array(
                'value' => get_bloginfo('name'),
                'label' => __('Company Name', 'wp-payment-form'),
                'type' => 'text',
                'placeholder' => __('Company Name', 'wp-payment-form'),
            ),
            'send_meta_data' => array(
                'value' => 'no',
                'label' => __('Stripe Meta Data', 'wp-payment-form'),
                'type' => 'checkbox',
                'tooltip' => __('You can choose what will show after a successful payment<br /> or if there is any fails.', 'wp-payment-form'),
                'desc' => __('Send Form input data to stripe metadata', 'wp-payment-form'),
            ),
            // 'checkout_logo' => array(
            //     'value' => '',
            //     'label' => __('Checkout Logo', 'wp-payment-form'),
            //     'type' => 'image'
            // ),
            'webhook_desc' => array(
                'value' => "<h3>Stripe Webhook (For Subscription Payments) </h3> <p>In order for Stripe to function completely for subscription/recurring payments, you must configure your Stripe webhooks. Visit your <a href='https://dashboard.stripe.com/account/webhooks' target='_blank' rel='noopener'>account dashboard</a> to configure them. Please add a webhook endpoint for the URL below. </p> <p><b>Webhook URL: </b><code> ". site_url('?wpf_stripe_listener=1') . "</code></p> <p>See <a href='https://paymattic.com/docs/how-to-configure-stripe-payment-gateway-in-wordpress-with-paymattic/' target='_blank' rel='noopener'>our documentation</a> for more information.</p> <div> <p><b>Please enable the following Webhook events for this URL:</b></p> <ul> <li><code>charge.succeeded</code></li> <li><code>invoice.payment_succeeded</code></li> <li><code>charge.refunded</code></li> <li><code>customer.subscription.deleted</code></li> <li><code>checkout.session.completed</code></li> </ul> </div>",
                'label' => __('Webhook URL', 'wp-payment-form'),
                'type' => 'html_attr'
            ),
            'is_pro_item' => array(
                'value' => 'no',
                'label' => __('PayPal', 'wp-payment-form'),
            ),
        );



        return $this->mapper($defaults, $settings);
    }

    public function mapper($defaults, $settings = [], $get = true) 
    {
        foreach ($defaults as $key => $value) {
            if($get) {
                if (isset($settings[$key])) {
                    $defaults[$key]['value'] = $settings[$key];
                }
            } else {
                if (isset($settings['settings'][$key])) {
                    $defaults[$key] = $settings['settings'][$key]['value'];
                }
            }
        }
        return $defaults;
    }
    
    public function getPubKey($formId = false)
    {
        if ($formId && defined('WPPAYFORMHASPRO')) {
            $formPaymentSettings = (new PaymentsHelper())->getPaymentSettings($formId, 'admin');
            if (Arr::get($formPaymentSettings, 'stripe_account_type') == 'custom') {
                if ( $this->getMode($formId) == 'test') {
                    return Arr::get($formPaymentSettings, 'stripe_custom_config.test_publishable_key');
                }
                return Arr::get($formPaymentSettings, 'stripe_custom_config.publishable_key');
            }
        }

        $paymentSettings = $this->getStripeSettings();
        if ($paymentSettings['payment_mode'] == 'live') {
            if ($this->isStripeKeysDefined()) {
                return WP_PAY_FORM_STRIPE_PUB_KEY;
            } else {
                return $paymentSettings['live_pub_key'];
            }
        }
        return $paymentSettings['test_pub_key'];
    }

    public function getSecretKey($formId = false)
    {
        if ($formId && defined('WPPAYFORMHASPRO')) {
            $formPaymentSettings = (new PaymentsHelper())->getPaymentSettings($formId, 'admin');
            if (Arr::get($formPaymentSettings, 'stripe_account_type') == 'custom') {
                if ($this->getMode($formId) == 'test') {
                    return Arr::get($formPaymentSettings, 'stripe_custom_config.test_secret_key');
                }
                return Arr::get($formPaymentSettings, 'stripe_custom_config.secret_key');
            }
        }

        $paymentSettings = $this->getStripeSettings();

        if ($paymentSettings['payment_mode'] == 'live') {
            if ($this->isStripeKeysDefined()) {
                return WP_PAY_FORM_STRIPE_SECRET_KEY;
            } else {
                return $paymentSettings['live_secret_key'];
            }
        }
        return $paymentSettings['test_secret_key'];
    }

    public function isStripeKeysDefined()
    {
        return defined('WP_PAY_FORM_STRIPE_SECRET_KEY') && defined('WP_PAY_FORM_STRIPE_PUB_KEY');
    }

    public function getStripePaymentMethodByElement($paymentMethodElement)
    {
        $method = Arr::get($paymentMethodElement, 'stripe_card_element.options.checkout_display_style.style');
        if (!$method) {
            $method = Arr::get($paymentMethodElement, 'choose_payment_method.options.method_settings.payment_settings.stripe.checkout_display_style.style');
        }
        if ($method == 'embeded_form') {
            return 'stripe_inline';
        }
        return 'stripe_hosted';
    }
}
