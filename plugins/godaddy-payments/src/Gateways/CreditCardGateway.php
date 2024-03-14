<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Gateways;

use GoDaddy\WooCommerce\Poynt\API\Cards\TokenizeChargeResponse;
use GoDaddy\WooCommerce\Poynt\API\Cards\TokenizeResponse;
use GoDaddy\WooCommerce\Poynt\API\GatewayAPI;
use GoDaddy\WooCommerce\Poynt\Blocks\CreditCardCheckoutBlockIntegration;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\CredentialsHelper;
use GoDaddy\WooCommerce\Poynt\Plugin;
use GoDaddy\WooCommerce\Poynt\Support\Client;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use stdClass;
use WC_Customer;
use WC_HTTPS;
use WC_Order;
use WP_Error;

defined('ABSPATH') or exit;

/**
 * Credit card gateway handler.
 *
 * @since 1.0.0
 *
 * @method Plugin get_plugin()
 * @method PaymentForm|null get_payment_form_instance()
 */
#[\AllowDynamicProperties]
class CreditCardGateway extends Framework\SV_WC_Payment_Gateway_Direct
{
    /** @var string additional card type used by this gateway */
    const CARD_TYPE_UNIONPAY = 'unionpay';

    /** @var GatewayAPI instance */
    private $api;

    /** @var string the configured production app ID */
    protected $appId;

    /** @var string the configured production private key */
    protected $privateKey;

    /** @var string the configured staging app ID */
    protected $stagingAppId;

    /** @var string the configured staging private key */
    protected $stagingPrivateKey;

    /** @var CreditCardCheckoutBlockIntegration|null */
    protected ?CreditCardCheckoutBlockIntegration $creditCardCheckoutBlockIntegration = null;

    /**
     * Credit card gateway constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $cardTypeOptions = [];
        $supportedCardTypes = [
            Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_AMEX,
            Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_DINERSCLUB,
            Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_DISCOVER,
            Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_JCB,
            Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_MASTERCARD,
            self::CARD_TYPE_UNIONPAY,
            Framework\SV_WC_Payment_Gateway_Helper::CARD_TYPE_VISA,
        ];

        foreach ($supportedCardTypes as $supportedCardType) {
            $cardTypeOptions[$supportedCardType] = Framework\SV_WC_Payment_Gateway_Helper::payment_type_to_name($supportedCardType);
        }

        $plugin = poynt_for_woocommerce();

        parent::__construct(
            Plugin::CREDIT_CARD_GATEWAY_ID,
            $plugin,
            [
                'method_title'       => esc_html__('GoDaddy Payments', 'godaddy-payments'),
                'method_description' => $this->method_description = $this->getDescription(),
                'payment_type'       => self::PAYMENT_TYPE_CREDIT_CARD,
                'card_types'         => $cardTypeOptions,
                'environments'       => [
                    $plugin::ENVIRONMENT_PRODUCTION => esc_html_x('Production', 'Gateway environment', 'godaddy-payments'),
                    $plugin::ENVIRONMENT_STAGING    => esc_html_x('Staging', 'Gateway environment', 'godaddy-payments'),
                ],
                'supports' => [
                    // @TODO review these feature flags before release as we move forward with other stories {FN 2020-02-04}
                    self::FEATURE_PRODUCTS,
                    self::FEATURE_CARD_TYPES,
                    self::FEATURE_PAYMENT_FORM,
                    self::FEATURE_CREDIT_CARD_CHARGE,
                    self::FEATURE_CREDIT_CARD_CHARGE_VIRTUAL,
                    self::FEATURE_CREDIT_CARD_AUTHORIZATION,
                    self::FEATURE_CREDIT_CARD_CAPTURE,
                    self::FEATURE_DETAILED_CUSTOMER_DECLINE_MESSAGES,
                    self::FEATURE_REFUNDS,
                    self::FEATURE_VOIDS,
                    self::FEATURE_TOKENIZATION,
                    self::FEATURE_ADD_PAYMENT_METHOD,
                    self::FEATURE_TOKEN_EDITOR,
                ],
            ]
        );

        add_filter("woocommerce_settings_api_sanitized_fields_{$this->get_id()}", [$this, 'formatSanitizedFields']);
    }

    /**
     * Gets the method description.
     *
     * @return string
     */
    protected function getDescription() : string
    {
        switch (poynt_for_woocommerce()->getCountry()) {
            case 'CA':
                $pricing = __('2.7&#37; + 0&#162;', 'godaddy-payments');
                break;
            default:
                $pricing = __('2.3&#37; + 30&#162;', 'godaddy-payments');
        }

        return sprintf(
            /* translators: Placeholders: %1$s - pricing, %2$s - <a> tag, %3$s - </a> tag */
            __('Securely accept credit/debit cards in your checkout, and keep more of your money with the industry\'s lowest fees—just %1$s per online transaction. Get paid fast with deposits as soon as the next day. %2$sGoDaddy Payments Terms apply%3$s.', 'godaddy-payments'),
            $pricing,
            '<a href="https://www.godaddy.com/legal/agreements/commerce-services-agreement" target="_blank">',
            ' <span class="dashicons dashicons-external"></span></a>'
        );
    }

    /**
     * Marks an order as voided.
     *
     * Because WC has no status for "void", we use refunded.
     *
     * Overrides to cancel the fully refunded void transaction as cancelled all the time.
     *
     * @since 1.3.0
     *
     * @param WC_Order $order order object
     * @param Framework\SV_WC_Payment_Gateway_API_Response $response object
     */
    public function mark_order_as_voided($order, $response)
    {
        $message = sprintf(
            /* translators: Placeholders: %1$s - payment gateway title, %2$s - a monetary amount. Void as in to void an order. */
            esc_html__('%1$s Void in the amount of %2$s approved.', 'godaddy-payments'),
            $this->get_method_title(),
            wc_price($order->refund->amount, [
                'currency' => $order->get_currency(),
            ])
        );

        // adds the transaction id (if any) to the order note
        if ($response->get_transaction_id()) {
            $message .= ' '.sprintf(esc_html__('(Transaction ID %s)', 'godaddy-payments'), $response->get_transaction_id());
        }

        // mark order as cancelled, since no money was actually transferred
        $this->voided_order_message = $message;

        add_filter('woocommerce_order_fully_refunded_status', [$this, 'maybe_cancel_voided_order'], 10, 2);

        $order->add_order_note($message);
    }

    /**
     * Maybe change the order status for a voided order to cancelled.
     *
     * Overrides to remove duplicate order notes being added
     *
     * @internal
     * @see SV_WC_Payment_Gateway::mark_order_as_voided()
     *
     * @since 1.3.0
     *
     * @param string $order_status default order status for fully refunded orders
     * @param int $order_id order ID
     * @return string 'cancelled'
     */
    public function maybe_cancel_voided_order($order_status, $order_id)
    {
        if (empty($this->voided_order_message)) {
            return $order_status;
        }

        return 'cancelled';
    }

    /**
     * Marks the given order as failed and set the order note.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order the order
     * @param string $error_message a message to display inside the "Payment Failed" order note
     * @param Framework\SV_WC_Payment_Gateway_API_Response $response the transaction response object (optional)
     * @return void
     */
    public function mark_order_as_failed($order, $error_message, $response = null)
    {
        parent::mark_order_as_failed($order, $error_message, $response);
        if (Framework\SV_WC_Helper::str_exists($error_message, __('invalid country code', 'godaddy-payments'))) {
            Framework\SV_WC_Helper::wc_add_notice($error_message, 'error');
            update_option('godaddy_payments_has_invalid_country_code', true);
        }
    }

    /**
     * Processes a void order.
     *
     * Overrides to avoid duplicate voids.
     *
     * @since 1.3.0
     *
     * @param WC_Order $order order object (with refund class member already added)
     * @return bool|WP_Error true on success, or a WP_Error object on failure/error
     */
    protected function process_void(WC_Order $order)
    {
        // return if the void is already processed
        if (! empty($order->get_meta('_wc_poynt_credit_card_void_trans_id'))) {
            return true;
        }

        return parent::process_void($order);
    }

    /**
     * Processes and saves options.
     *
     * @NOTE: this method is called by WooCommerce, so it needs to remain snake_case.
     *
     * @since 1.3.1
     *
     * @return bool was anything saved?
     */
    public function process_admin_options()
    {
        if (! $this->get_option('stagingAppId') || ! $this->get_option('stagingAppId')) {
            $_POST['woocommerce_'.$this->get_id().'_environment'] = self::ENVIRONMENT_PRODUCTION;
        }

        $isStored = parent::process_admin_options();
        $this->storePoyntData();

        return $isStored;
    }

    /**
     * Marks the given order as 'on-hold', set an order note and display a message to the customer.
     *
     * Overrides the order note.
     *
     * @since 1.3.0
     *
     * @param WC_Order $order the order
     * @param string $message a message to display within the order note
     * @param Framework\SV_WC_Payment_Gateway_API_Response $response optional, the transaction response object
     */
    public function mark_order_as_held($order, $message, $response = null)
    {
        $order->add_order_note(self::generateTransactionOrderNote($order));
        parent::mark_order_as_held($order, $message, $response);
    }

    /**
     * Adds the standard refund transaction data to the order.
     *
     * Overrides the refund meta_key
     *
     * @since 1.3.0
     *
     * @param WC_Order $order the order object
     * @param Framework\SV_WC_Payment_Gateway_API_Response $response transaction response
     */
    protected function add_refund_data(WC_Order $order, $response)
    {
        // indicate the order was refunded along with the refund amount
        $this->add_order_meta($order, 'refund_amount', $order->refund->amount);

        // add refund transaction ID
        if ($response && $response->get_transaction_id()) {
            $this->add_order_meta($order, 'refund_remoteId', $response->get_transaction_id());
        }
    }

    /**
     * Generates the order note for the transaction.
     *
     * @since 1.3.0
     *
     * @param WC_Order $order
     * @return string
     */
    public static function generateTransactionOrderNote($order)
    {
        $note = '';

        if ($amount = $order->get_meta('_wc_poynt_credit_card_authorization_amount')) {
            $formattedAmount = wc_price($amount, ['currency' => $order->get_currency()]);
            $note .= sprintf(__('GoDaddy Payments payment in the amount of %s approved. ', 'godaddy-payments'), $formattedAmount);
        }

        if ($transactionId = $order->get_meta('_wc_poynt_credit_card_trans_id')) {
            $note .= sprintf(esc_html__('Transaction ID: %s.', 'godaddy-payments', 'godaddy-payments'), $transactionId);
        }

        $lastFour = $order->get_meta('_wc_poynt_credit_card_account_four') ?: '';

        if ($cardType = $order->get_meta('_wc_poynt_credit_card_card_type')) {
            $note .= ' '.Framework\SV_WC_Payment_Gateway_Helper::payment_type_to_name($cardType);
        }

        if ($lastFour) {
            $note .= ' '.sprintf(
                /* translators: Placeholder: %1$s - last four digits of an account number (e.g. credit card) */
                esc_html__('ending in %1$s', 'godaddy-payments'), $lastFour
            );
        }

        if ($expiryDate = $order->get_meta('_wc_poynt_credit_card_card_expiry_date')) {
            [$expYear, $expMonth] = explode('-', $expiryDate);
            $note .= ' '.sprintf(__('(expires %s)', 'godaddy-payments'), "{$expMonth}/{$expYear}");
        }

        if ('yes' !== $order->get_meta('_wc_poynt_credit_card_charge_captured')) {
            $note .= ' '.__('(Authorization only transaction)', 'godaddy-payments');
        }

        return $note;
    }

    /**
     * Stores Poynt data to the options table after fetching it from the API.
     *
     * @since 1.3.1
     *
     * @return void
     */
    protected function storePoyntData()
    {
        if (! $this->is_configured()) {
            $this->deletePoyntOptions();

            return;
        }

        if (! $this->hasConnectionSettingsChanged()) {
            return;
        }

        try {
            $businessResponse = $this->get_api()->getBusinessDetails();
            update_option('wc_poynt_appId', $businessResponse->getAppId());
            update_option('wc_poynt_serviceId', $businessResponse->getServiceId());
            update_option('wc_poynt_businessId', $this->getBusinessId());

            // todo: Move this into a cronjob/scheduled action (@sahmed2-godaddy: 2021-12-14)
            $businessStoresResponse = $this->get_api()->getBusinessStores();
            update_option('wc_poynt_storeId', $businessStoresResponse->getStoreId());
            update_option('wc_poynt_payinperson_terminal_activated', $businessStoresResponse->hasActiveTerminalDevices());
        } catch (Framework\SV_WC_API_Exception $e) {
            if ($this->debug_log()) {
                $this->get_plugin()->log($e->getMessage(), $this->get_id());
            }
            $this->deletePoyntOptions();
        }
    }

    /**
     * Delete Poynt options stored in wp_options.
     *
     * @since 1.3.1
     *
     * @return void
     */
    protected function deletePoyntOptions()
    {
        delete_option('wc_poynt_appId');
        delete_option('wc_poynt_serviceId');
        delete_option('wc_poynt_businessId');
        delete_option('wc_poynt_storeId');
        delete_option('wc_poynt_payinperson_terminal_activated');
    }

    /**
     * Determines if the connection settings (appId or privateKey) has changed.
     *
     * @since 1.3.1
     *
     * @return bool has it changed?
     */
    protected function hasConnectionSettingsChanged() : bool
    {
        $environment = $this->get_environment();

        return
            $this->getCredential('appId', $environment, true) !== $this->getCredential('appId', $environment, false)
            || $this->getCredential('privateKey', $environment, true) !== $this->getCredential('privateKey', $environment, false);
    }

    /**
     * Gets the gateway API instance.
     *
     * @since 1.0.0
     *
     * @return GatewayAPI
     */
    public function get_api() : GatewayAPI
    {
        if (! $this->api instanceof GatewayAPI) {
            $environment = $this->get_environment();

            $this->api = new GatewayAPI($this->getAppId(), $this->getBusinessId(), $this->getPrivateKey(), is_string($environment) ? $environment : '');
        }

        return $this->api;
    }

    /**
     * Gets the checkout block integration instance.
     *
     * @since 1.7.0
     *
     * @return CreditCardCheckoutBlockIntegration
     */
    public function get_checkout_block_integration_instance() : ?Framework\Payment_Gateway\Blocks\Gateway_Checkout_Block_Integration
    {
        if (null === $this->creditCardCheckoutBlockIntegration) {
            require_once $this->get_plugin()->get_plugin_path().'/src/Blocks/CreditCardCheckoutBlockIntegration.php';

            $this->creditCardCheckoutBlockIntegration = new CreditCardCheckoutBlockIntegration($this->get_plugin(), $this);
        }

        return $this->creditCardCheckoutBlockIntegration;
    }

    /**
     * Gets a credential based on the environment.
     *
     * @since 1.0.0
     *
     * @param string $credentialKey key of the credential to get
     * @param string $environment the desired environment, defaults to the configured setting
     * @param bool $getFromOption, tells if we should get data from options, defaults to true
     * @return string
     */
    protected function getCredential(string $credentialKey, string $environment = '', bool $getFromOption = true) : string
    {
        if (! $environment) {
            $environment = $this->get_environment();
        }

        // prefix with the environment if not production
        if (Plugin::ENVIRONMENT_PRODUCTION !== $environment) {
            $credentialKey = $environment.ucfirst($credentialKey);
        }

        if ($getFromOption) {
            $credential = $this->get_option($credentialKey) ?? '';
        } else {
            $credential = $this->{$credentialKey} ?? '';
        }

        return is_string($credential) ? $credential : '';
    }

    /**
     * Gets the configured app ID for the given environment.
     *
     * @since 1.0.0
     *
     * @param string $environment optional environment, defaults to current configured environment
     * @return string
     */
    public function getAppId(string $environment = '') : string
    {
        $credential = $this->getCredential('appId', $environment);
        $credentialPieces = explode('=', $credential);

        return end($credentialPieces);
    }

    /**
     * Gets the configured business ID for the given environment.
     *
     * @since 1.0.0
     *
     * @param string $environment optional environment, defaults to current configured environment
     * @return string
     */
    public function getBusinessId(string $environment = '') : string
    {
        $credential = $this->getCredential('appId', $environment);
        $credentialPieces = explode('=', $credential);

        return current($credentialPieces);
    }

    /**
     * Gets the checkout pay page order if available.
     *
     * @return WC_Order|null
     */
    protected function getPendingPayPageOrder() : ?WC_Order
    {
        return is_checkout_pay_page()
                && ($order = wc_get_order($this->get_checkout_pay_page_order_id()))
                && $order instanceof WC_Order
                    ? $order
                    : null;
    }

    /**
     * Gets the current customer's name formatted for checkout.
     *
     * @since 1.7.0
     *
     * @return array{
     *     firstName: string,
     *     lastName: string,
     * }
     */
    public function getCustomerName() : array
    {
        $name = [
            'firstName' => '',
            'lastName'  => '',
        ];

        // if on the checkout pay page use the order's billing name details
        if ($order = $this->getPendingPayPageOrder()) {
            $name['firstName'] = $order->get_billing_first_name();
            $name['lastName'] = $order->get_billing_last_name();

            return $name;
        }

        // get the current customer's name if available
        if (WC()->customer instanceof WC_Customer) {
            $name['firstName'] = WC()->customer->get_first_name();
            $name['lastName'] = WC()->customer->get_last_name();
        }

        return $name;
    }

    /**
     * Gets the current customer's address formatted for checkout.
     *
     * @since 1.7.0
     *
     * @return array{
     *     line1: string,
     *     line2: string,
     *     city: string,
     *     state: string,
     *     country: string,
     *     postCode: string,
     * }
     */
    public function getCustomerAddress() : array
    {
        $address = [
            'line1'    => '',
            'line2'    => '',
            'city'     => '',
            'state'    => '',
            'country'  => '',
            'postcode' => '',
        ];

        // if on the checkout pay page use the order's address details
        if ($order = $this->getPendingPayPageOrder()) {
            $address['line1'] = $order->get_billing_address_1();
            $address['line2'] = $order->get_billing_address_2();
            $address['city'] = $order->get_billing_city();
            $address['state'] = $order->get_billing_state();
            $address['country'] = $order->get_billing_country();
            $address['postcode'] = $order->get_billing_postcode();

            return $address;
        }

        // get the current customer's address details if available
        if (WC()->customer instanceof WC_Customer) {
            $address['line1'] = WC()->customer->get_billing_address_1();
            $address['line2'] = WC()->customer->get_billing_address_2();
            $address['city'] = WC()->customer->get_billing_city();
            $address['state'] = WC()->customer->get_billing_state();
            $address['country'] = WC()->customer->get_billing_country();
            $address['postcode'] = WC()->customer->get_billing_postcode();
        }

        return $address;
    }

    /**
     * Gets the current customer's contact information for checkout.
     *
     * @since 1.7.1
     *
     * @return array{
     *     phone: string,
     *     emailAddress: string,
     * }
     */
    public function getCustomerContactInformation() : array
    {
        $contactInformation = [
            'emailAddress' => '',
            'phone'        => '',
        ];

        // if on the checkout pay page use the order's contact information
        if ($order = $this->getPendingPayPageOrder()) {
            $contactInformation['emailAddress'] = $order->get_billing_email();
            $contactInformation['phone'] = $order->get_billing_phone();

            return $contactInformation;
        }

        // get the current customer's contact information details if available
        if (WC()->customer instanceof WC_Customer) {
            $contactInformation['emailAddress'] = WC()->customer->get_billing_email();
            $contactInformation['phone'] = WC()->customer->get_billing_phone();
        }

        return $contactInformation;
    }

    /**
     * Gets the current shipping information for checkout.
     *
     * @since 1.7.1
     *
     * @return array{
     *     line1: string,
     *     line2: string,
     *     city: string,
     *     state: string,
     *     postcode: string,
     * }
     */
    public function getShippingInformation() : array
    {
        $shippingInformation = [
            'line1'         => '',
            'line2'         => '',
            'city'          => '',
            'state'         => '',
            'postcode'      => '',
            'needsShipping' => false,
        ];

        // if on the checkout pay page use the order's address details
        if ($order = $this->getPendingPayPageOrder()) {
            $shippingInformation['line1'] = $order->get_shipping_address_1();
            $shippingInformation['line2'] = $order->get_shipping_address_2();
            $shippingInformation['city'] = $order->get_shipping_city();
            $shippingInformation['state'] = $order->get_shipping_state();
            $shippingInformation['postcode'] = $order->get_shipping_postcode();
            $shippingInformation['needsShipping'] = $order->needs_shipping_address();
        } elseif (WC()->cart && WC()->cart->needs_shipping()) {
            $shippingInformation['needsShipping'] = true;
        }

        return $shippingInformation;
    }

    /**
     * Gets the Poynt Collect `mount()` options.
     *
     * @since 1.7.0
     *
     * @param string $context optional context (must be 'block' or 'shortcode')
     * @return array<string, mixed>
     */
    public function getMountOptions(string $context = 'shortcode') : array
    {
        $showZip = $this->shouldShowZipField();
        $mountOptions = [
            'locale'           => $this->get_plugin()->getLocale(),
            'inlineErrors'     => $this->debug_checkout(),
            'enableReCaptcha'  => true,
            'reCaptchaOptions' => [
                'type' => 'TEXT',
            ],
        ];

        if ('shortcode' === $context) {
            $mountOptions = array_merge($mountOptions, [
                'iFrame' => [
                    'border'       => '0px',
                    'borderRadius' => '0px',
                    'boxShadow'    => 'none',
                    'height'       => $showZip ? '360px' : '320px',
                    'width'        => 'auto',
                ],
                'style' => [
                    'theme' => 'checkout',
                ],
                'displayComponents' => [
                    'firstName'    => false,
                    'lastName'     => false,
                    'emailAddress' => false,
                    'zipCode'      => $showZip,
                    'labels'       => true,
                    'submitButton' => false,
                ],
                'customCss' => [
                    'container' => [
                        'margin-top' => '10px',
                    ],
                    'inputLabel' => [
                        'font-size' => '14px',
                    ],
                ],
            ]);
        }

        /*
         * Filters the Poynt Collect JS options.
         *
         * For configuration options, see:
         * @link https://docs.poynt.com/app-integration/poynt-collect/#collect-mount
         *
         * For CSS options, see:
         * @link https://docs.poynt.com/app-integration/poynt-collect/#passing-in-custom-css-optional
         * @link https://github.com/medipass/react-payment-inputs#styles
         *
         * @since 1.7.0
         *
         * @param array{
         *     iFrame?: array<string, mixed>,
         *     style?:  array<string, mixed>,
         *     displayComponents?: array<string, mixed>,
         *     fields?: array<string, mixed>,
         *     additionalFieldsToValidate?: string[],
         *     customCss?: array<string, mixed>,
         *     locale?: string,
         *     inlineErrors?: bool,
         *     enableReCaptcha?: bool,
         *     reCaptchaOptions?: array<string, mixed>,
         * } $options
         */
        return ArrayHelper::wrap(apply_filters('wc_'.$this->get_id().'_collect_options', $mountOptions, $this));
    }

    /**
     * Gets the configured private key for the given environment.
     *
     * @since 1.0.0
     *
     * @param string $environment optional environment, defaults to current configured environment
     * @return string
     */
    public function getPrivateKey(string $environment = '') : string
    {
        return $this->getCredential('privateKey', $environment);
    }

    /**
     * Gets the nonce token from posted data.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function getNonce() : string
    {
        $nonce = Framework\SV_WC_Helper::get_posted_value('wc_'.$this->get_id().'_nonce', null);

        return is_string($nonce) ? $nonce : '';
    }

    /**
     * Determines if a nonce was posted.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    private function hasNonce() : bool
    {
        return (bool) $this->getNonce();
    }

    /**
     * Determines whether the payment form should show the ZIP (postcode) field.
     *
     * @since 1.7.0
     *
     * @return bool
     */
    protected function shouldShowZipField() : bool
    {
        return ! ArrayHelper::get($this->getCustomerAddress(), 'postcode') && ! $this->hasCheckoutPostcodeField();
    }

    /**
     * Determines whether checkout has the postcode field.
     *
     * @since 1.7.0
     *
     * @return bool
     */
    protected function hasCheckoutPostcodeField() : bool
    {
        $wc = WC();

        return ArrayHelper::has($wc ? $wc->checkout()->get_checkout_fields() : [], 'billing.billing_postcode');
    }

    /**
     * Gets an order object with additional payment data.
     *
     * @since 1.0.0
     *
     * @param int|WC_Order $order
     * @return WC_Order
     */
    public function get_order($order) : WC_Order
    {
        $order = parent::get_order($order);

        if (! isset($order->payment)) {
            $order->payment = new stdClass();
        }

        if ($nonce = $this->getNonce()) {
            $order->payment->nonce = wc_clean($nonce);
        }

        return $order;
    }

    /**
     * Gets the order ready for a refund.
     *
     * @since 1.0.0
     *
     * @param int|WC_Order $order order object or ID
     * @param float $amount amount to refund
     * @param string $reason refund reason
     * @return WC_Order|WP_Error
     */
    protected function get_order_for_refund($order, $amount, $reason)
    {
        $order = parent::get_order_for_refund($order, $amount, $reason);

        // if there was a capture ID, use that for the refund
        if ($order instanceof WC_Order && ($captureTransactionId = $this->get_order_meta($order, 'capture_trans_id'))) {
            $order->refund->trans_id = $captureTransactionId;
        }

        return $order;
    }

    /**
     * Gets the payment gateway customer ID.
     *
     * @since 1.0.0
     *
     * @param int $userId user ID
     * @param array $args array of arguments
     * @return false
     */
    public function get_customer_id($userId, $args = [])
    {
        // not available for this gateway - implements parent method
        return false;
    }

    /**
     * Gets the payment gateway guest customer ID.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order order object
     * @return string|bool
     */
    public function get_guest_customer_id(WC_Order $order)
    {
        // not available for this gateway - implements parent method
        return false;
    }

    /**
     * Formats sanitized fields for credentials.
     *
     * @internal
     *
     * @since 1.0.0
     *
     * @param array $sanitized_fields array of sanitized fields
     * @return array
     */
    public function formatSanitizedFields(array $sanitized_fields) : array
    {
        if (isset($sanitized_fields['privateKey'])) {
            $sanitized_fields['privateKey'] = CredentialsHelper::formatPrivateKey($sanitized_fields['privateKey']);
        }

        if (isset($sanitized_fields['stagingPrivateKey'])) {
            $sanitized_fields['stagingPrivateKey'] = CredentialsHelper::formatPrivateKey($sanitized_fields['stagingPrivateKey']);
        }

        return $sanitized_fields;
    }

    /**
     * Gets the method description.
     *
     * Also prepends an HTML container for the support form.
     * @see Client::SUPPORT_FORM_CONTAINER
     *
     * @internal
     *
     * @since 1.2.0
     *
     * @return string
     */
    public function get_method_description() : string
    {
        return Client::SUPPORT_FORM_CONTAINER.parent::get_method_description();
    }

    /**
     * Gets the credit card form fields.
     *
     * @since 1.0.0
     *
     * @return array $formFields
     */
    protected function get_method_form_fields() : array
    {
        return [
            'enabled' => [
                'title'    => esc_html__('Enable / Disable', 'godaddy-payments'),
                'label'    => esc_html__('Enable this gateway', 'godaddy-payments'),
                'desc_tip' => esc_html__('Enable to add the payment method to your checkout.', 'godaddy-payments'),
                'type'     => 'checkbox',
                'default'  => 'no',
            ],
            'title' => [
                'title'    => esc_html__('Title in Checkout', 'godaddy-payments'),
                'type'     => 'text',
                'desc_tip' => esc_html__('Payment method title that the customer will see during checkout.', 'godaddy-payments'),
                'default'  => $this->get_default_title(),
            ],
            'description' => [
                'title'    => esc_html__('Description in Checkout', 'godaddy-payments'),
                'type'     => 'textarea',
                'desc_tip' => esc_html__('Payment method description that the customer will see during checkout.', 'godaddy-payments'),
                'default'  => $this->get_default_description(),
            ],
            'card_types' => [
                'title'       => esc_html__('Accepted Card Logos', 'godaddy-payments'),
                'type'        => 'multiselect',
                'desc_tip'    => __('These are the card logos that are displayed to customers as accepted during checkout.', 'godaddy-payments'),
                'description' => sprintf(
                    /* translators: Placeholders: %1$s - <strong> tag, %2$s - </strong> tag */
                    __('This setting %1$sdoes not%2$s change which card types the gateway will accept.', 'godaddy-payments'),
                    '<strong>',
                    '</strong>'
                ),
                'default' => array_keys($this->get_available_card_types()),
                'class'   => 'wc-enhanced-select',
                'css'     => 'width: 350px;',
                'options' => $this->get_available_card_types(),
            ],
            'tokenization' => [
                'title'   => esc_html__('Saved Cards', 'godaddy-payments'),
                'label'   => esc_html__('Allow customers to securely save their payment details for future checkout.', 'godaddy-payments'),
                'type'    => 'checkbox',
                'default' => 'no',
            ],
            'connectionSettings' => [
                'type'        => 'title',
                'title'       => __('Connection settings', 'godaddy-payments'),
                'description' => sprintf(
                    /* translators: Placeholders: %1$s - opening <a> HTML link tag, %2$s - closing </a> HTML link tag, %3$s - opening <a> HTML link tag, %4$s - closing </a> HTML link tag */
                    __('To connect to GoDaddy Payments, %1$screate a GoDaddy Payments account%2$s. Then copy your Application ID and Private key from the Business Settings under Advanced Tools in your Payments dashboard and paste them in the fields below. %3$sLearn more%4$s', 'godaddy-payments'),
                    '<a href="'.esc_url($this->get_plugin()->getSignupUrl()).'" target="_blank">', '</a>',
                    '<a href="https://www.godaddy.com/help/what-is-the-poynt-for-woocommerce-plugin-40417" target="_blank">', '</a>'
                ),
                'class' => 'environment-field production-field',
            ],
            'stagingConnectionSettings' => [
                'type'        => 'title',
                'title'       => __('Connection settings', 'godaddy-payments'),
                'description' => sprintf(
                    /* translators: Placeholders: %1$s - opening <a> HTML link tag, %2$s - closing </a> HTML link tag, %3$s - opening <a> HTML link tag, %4$s - closing </a> HTML link tag */
                    __('To connect to GoDaddy Payments, %1$screate a GoDaddy Payments account%2$s. Then copy your Application ID and Private key from the Business Settings under Advanced Tools in your Payments dashboard and paste them in the fields below. %3$sLearn more%4$s', 'godaddy-payments'),
                    '<a href="'.esc_url($this->get_plugin()->getSignupUrl()).'" target="_blank">', '</a>',
                    '<a href="https://www.godaddy.com/help/what-is-the-poynt-for-woocommerce-plugin-40417" target="_blank">', '</a>'
                ),
                'class' => 'environment-field staging-field',
            ],
            'appId' => [
                'title'    => __('Application ID', 'godaddy-payments'),
                'type'     => 'text',
                'desc_tip' => __('Enter your Application ID', 'godaddy-payments'),
                'class'    => 'environment-field production-field',
            ],
            'privateKey' => [
                'title'    => __('Private Key', 'godaddy-payments'),
                'type'     => 'textarea',
                'desc_tip' => __('Enter your Private Key', 'godaddy-payments'),
                'class'    => 'environment-field production-field',
            ],
            'stagingAppId' => [
                'title'    => __('Staging Application ID', 'godaddy-payments'),
                'type'     => 'text',
                'desc_tip' => __('Enter your staging Application ID', 'godaddy-payments'),
                'class'    => 'environment-field staging-field',
            ],
            'stagingPrivateKey' => [
                'title'    => __('Staging Private Key', 'godaddy-payments'),
                'type'     => 'textarea',
                'desc_tip' => __('Enter your staging Private Key', 'godaddy-payments'),
                'class'    => 'environment-field staging-field',
            ],
        ];
    }

    /**
     * Adds credit card authorization and charge fields.
     *
     * @since 1.0.0
     *
     * @param array $formFields
     * @return array
     */
    protected function add_authorization_charge_form_fields($formFields) : array
    {
        $formFields = parent::add_authorization_charge_form_fields($formFields);

        $formFields['enable_paid_capture']['default'] = 'yes';

        return $formFields;
    }

    /**
     * Adds the enable Card Security Code form fields.
     *
     * @since 1.0.0
     *
     * @param array $formFields
     *
     * @return array
     */
    protected function add_csc_form_fields($formFields) : array
    {
        $formFields = parent::add_csc_form_fields($formFields);

        unset($formFields['card-csc'], $formFields['enable_csc']);

        if (isset($formFields['enable_token_csc'])) {
            unset($formFields['enable_token_csc']);
        }

        return $formFields;
    }

    /**
     * Adds gateway-specific transaction data to the order.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order the order object
     * @param Framework\SV_WC_Payment_Gateway_API_Customer_Response $response the transaction response
     */
    public function add_payment_gateway_transaction_data($order, $response)
    {
        if (TokenizeChargeResponse::CAPTURED_STATUS !== $response->get_status_code()) {
            $this->update_order_meta($order, 'charge_captured', 'no');
        }
    }

    /**
     * Displays the settings page and injects some inline JavaScript for handling some custom fields.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function admin_options()
    {
        parent::admin_options();

        wc_enqueue_js("

            if(
                ! $( '#woocommerce_poynt_credit_card_stagingAppId' ).val()
                || ! $( '#woocommerce_poynt_credit_card_stagingPrivateKey' ).val()
                || 'production' === $( '#woocommerce_".esc_js($this->get_id())."_environment' ).val() ){
                $( '#woocommerce_poynt_credit_card_environment' ).parents('tr').hide();
                $( '#woocommerce_".esc_js($this->get_id())."_environment' ).val('production');
            }

            $( '#woocommerce_".esc_js($this->get_id())."_environment' ).on( 'change', function( e ) {

                stagingDescription = $( '#woocommerce_poynt_credit_card_stagingConnectionSettings' );
                productionDescription = $( '#woocommerce_poynt_credit_card_connectionSettings' );

                if ( 'staging' === $(this).find('option:selected').val() ) {
                    stagingDescription.show().next( 'p' ).show();
                    productionDescription.hide().next( 'p' ).hide();
                } else {
                    stagingDescription.hide().next( 'p' ).hide();
                    productionDescription.show().next( 'p' ).show();
                }
            } ).trigger( 'change' );
        ");
    }

    /**
     * Flags whether the CSC feature is enabled.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function csc_enabled() : bool
    {
        return true;
    }

    /**
     * Flags whether the CSC feature should be enabled for tokens.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function csc_enabled_for_tokens() : bool
    {
        return false;
    }

    /**
     * Determines whether the gateway is configured.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function is_configured() : bool
    {
        return CredentialsHelper::isAppIdValid($this->getAppId()) && CredentialsHelper::isBusinessIdValid($this->getBusinessId());
    }

    /**
     * Flags whether tokenization takes place prior to transaction processing.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function tokenize_before_sale() : bool
    {
        return true;
    }

    /**
     * Performs a credit card transaction for the given order and returns the result.
     *
     * @since 1.0.0
     *
     * @param WC_Order $order
     * @param null $response
     * @return Framework\SV_WC_Payment_Gateway_API_Response|TokenizeResponse
     * @throws Framework\SV_WC_Plugin_Exception|Framework\SV_WC_API_Exception
     */
    protected function do_credit_card_transaction($order, $response = null) : Framework\SV_WC_Payment_Gateway_API_Response
    {
        if (! $response && $order instanceof WC_Order && empty($order->payment->token)) {
            $tokenize_response = $this->get_api()->tokenize_payment_method($order);

            if (! $tokenize_response->transaction_approved()) {
                return $tokenize_response;
            }

            $token = $tokenize_response->get_payment_token();

            $order->payment->token = $token->get_id();
            $order->payment->card_type = $token->get_card_type();
            $order->payment->exp_month = $token->get_exp_month();
            $order->payment->exp_year = $token->get_exp_year();

            $order->payment->last_four = $order->payment->account_number = $token->get_last_four();

            return parent::do_credit_card_transaction($order);
        }

        return parent::do_credit_card_transaction($order, $response);
    }

    /**
     * Determines if the posted credit card fields are valid or not.
     *
     * @since 1.0.0
     *
     * @param bool $valid whether credit card fields are valid
     * @return bool
     */
    protected function validate_credit_card_fields($valid) : bool
    {
        return $this->hasNonce();
    }

    /**
     * Initializes the payment form instance.
     *
     * @since 1.0.0
     *
     * @return PaymentForm
     */
    protected function init_payment_form_instance() : PaymentForm
    {
        return new PaymentForm($this);
    }

    /**
     * Enqueues the payment form scripts and styles.
     *
     * @since 1.0.0
     */
    protected function enqueue_gateway_assets()
    {
        if (is_order_received_page() || (is_account_page() && ! is_add_payment_method_page())) {
            return;
        }

        $environment = $this->get_environment();

        if ($environment) {
            $this->get_plugin()->registerPoyntCollect($environment, true);

            parent::enqueue_gateway_assets();
        }
    }

    /**
     * Gets the payment form localized script variables.
     *
     * @since 1.7.0
     *
     * @return array<string, string>
     */
    public function get_gateway_payment_form_localized_params() : array
    {
        $params = parent::get_gateway_payment_form_localized_params();

        if (! isset($params['generic_error'])) {
            $params['generic_error'] = _x('An error occurred, please try again or try an alternate form of payment.', 'Checkout error', 'godaddy-payments');
        }

        if (! isset($params['missing_card_details'])) {
            $params['missing_card_details'] = _x('Missing card details.', 'Credit or debit card', 'godaddy-payments');
        }

        if (! isset($params['missing_billing_fields'])) {
            $params['missing_billing_fields'] = _x('Missing billing details.', 'Checkout error', 'godaddy-payments');
        }

        return $params;
    }

    /**
     * Gets a payment method's type image URL.
     *
     * @since 1.0.0
     *
     * @param string $type payment method type
     * @return string|null
     */
    public function get_payment_method_image_url($type)
    {
        $image_type = strtolower($type);

        if (is_readable($this->get_plugin()->get_plugin_path().'/assets/images/card-'.$image_type.'.svg')) {
            return WC_HTTPS::force_https_url($this->get_plugin()->get_plugin_url().'/assets/images/card-'.$image_type.'.svg');
        }

        return parent::get_payment_method_image_url($type);
    }
}
