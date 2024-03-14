<?php


if (!defined('ABSPATH')) {
    exit;
}

require_once 'classes/HelcimJSService.php';
require_once 'classes/HelcimLineItem.php';
require_once 'classes/HelcimLineItemService.php';
require_once 'classes/HelcimCurl.php';
require_once 'classes/HelcimDirectService.php';
require_once 'classes/HelcimApiFactory.php';
require_once 'classes/constants/COUNTRIES.php';

/**
 * Helcim Commerce
 *
 * Provides integration between WooCommerce and Helcim Commerce .js
 *
 * @uses WC_Payment_Gateway::validate_fields()
 * @class        WCHelcimGateway
 * @extends    WC_Payment_Gateway
 * @version    4.0.3
 * @author        Helcim Inc.
 */
class WCHelcimGateway extends WC_Payment_Gateway
{
    private const HELCIM_JS_FILE = 'https://secure.myhelcim.com/js/version2.js';
    private const GOOGLE_RECAPTCHA_URL = 'https://www.google.com/recaptcha/api.js';
    private const GOOGLE_RECAPTCHA_TEST_SITE_KEY = '6LcgxK0UAAAAAH9gzsILRr82KZSLrBcry9RynMn4';
    private const GOOGLE_RECAPTCHA_LIVE_SITE_KEY = '6LdixK0UAAAAABmBXVo_jyFJSkQ5Doj9kloLyxGG';
    private const TRANSACTION_TYPE_PURCHASE = 'purchase';
    private const TRANSACTION_TYPE_PREAUTH = 'preauth';

    public const PLUGIN_NAME = 'woocommerce';
    public const VERSION = '4.0.1';
    public const HELCIM_CARD_TOKEN = 'helcim-card-token';
    /**
     * @deprecated please use self::API_V2_ENDPOINT
     */
    public const API_ENDPOINT = 'https://secure.myhelcim.com/api/';
    public const API_V2_ENDPOINT = 'https://api.helcim.com/v2/';
    public const HELCIM_SERVER_TIMEZONE = 'America/Edmonton';
    public const FORCE_HELCIM_JS_TO_RUN_VERIFY = 1;

    private $test;
    private $method;
    private $jsToken;
    private $jsSecretKey;
    private $accountId;
    private $apiToken;
    private $apiTokenV2;
    private $show_logo;
    private $transactionType;
    private $terminalId;

    private $helcimJSService;
    private $helcimDirectService;
    private $helcimLineItemService;
    private static $log;

    public function __construct()
    {
        $this->id = 'helcimjs';
        $this->method_title = __('HelcimCommerce', 'woocommerce');

        $this->init_settings();
        $this->initFormFields();

        $this->test = $this->get_option('test');
        $this->method = $this->get_option('method');
        $this->jsToken = $this->get_option('jsToken');
        $this->jsSecretKey = $this->get_option('jsSecretKey');
        $this->accountId = $this->get_option('accountId');
        $this->apiToken = $this->get_option('apiToken');
        $this->apiTokenV2 = $this->get_option('apiTokenV2');
        $this->title = '';
        $this->description = $this->get_option('description');
        $this->show_logo = $this->get_option('show_logo') === 'yes' ? 1 : 0;
        $this->transactionType = $this->get_option('transactionType');
        $this->terminalId = $this->get_option('terminalId');
        $this->supports[] = 'refunds';

        $this->initialize();
    }

    private function initialize(): void
    {
        if ($this->show_logo) {
            $this->title = 'Credit Card';
            $this->icon = apply_filters(
                'woocommerce_helcim_checkout_icon',
                plugins_url('assets/images/helcim_checkout_logo.png', __FILE__)
            );
        } else {
            $this->title = 'Credit Card - Helcim';
        }
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        wp_register_script(
            'helcim_scripts',
            untrailingslashit(plugins_url('/', __FILE__)) . '/assets/js/helcim-scripts.js',
            [],
            self::VERSION
        );
        wp_enqueue_script('helcim_scripts');
    }

    public function getJsSecretKey(): string
    {
        return $this->jsSecretKey;
    }

    public function getHelcimDirectService(): ?HelcimDirectService
    {
        return $this->helcimDirectService;
    }

    public function setHelcimDirectService(HelcimDirectService $helcimDirectService): WCHelcimGateway
    {
        $this->helcimDirectService = $helcimDirectService;
        return $this;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getAPIToken(): string
    {
        return $this->apiToken;
    }

    public function getAPITokenV2(): string
    {
        return $this->apiTokenV2;
    }

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    public function getTerminalId(): string
    {
        return $this->terminalId;
    }

    /**
     * Admin Panel Options
     * - Options for bits like 'title' and availability on a country-by-country basis
     *
     * @return void
     * @uses WC_Payment_Gateway::admin_options()
     * @access public
     */
    public function admin_options(): void
    {
        ?>
        <h3><?php
            _e('Helcim Commerce', 'woocommerce'); ?></h3>
        <p><?php
            _e('Accept credit cards in your Woocommerce shop.', 'woocommerce'); ?></p>

        <table class="form-table">
            <?php
            $this->generate_settings_html(); ?>
        </table>
        <?php
    }

    public function isJS(): bool
    {
        return $this->method === 'js';
    }

    public function isDirect(): bool
    {
        return $this->method === 'direct';
    }

    public function isTest(): int
    {
        return $this->test === 'yes' ? 1 : 0;
    }

    private function initFormFields(): void
    {
        $methods = ['js' => __('Helcim JS', 'woocommerce'), 'direct' => __('Direct Integration')];
        $transactionTypes = [
            self::TRANSACTION_TYPE_PURCHASE => __('Purchase', 'woocommerce'),
            self::TRANSACTION_TYPE_PREAUTH => __('Pre-Authorization')
        ];
        $this->form_fields['enabled'] = [
            'title' => __('Enable/Disable', 'woocommerce'),
            'type' => 'checkbox',
            'label' => __('Enable Helcim Payment Module', 'woocommerce'),
            'default' => 'yes'
        ];
        $this->form_fields['test'] = [
            'title' => __('Test', 'woocommerce'),
            'type' => 'checkbox',
            'description' => __('Only used for verify transactions. Purchase & preauth will not be processed as test. please assign a demo terminal to your currency in commerce if you want to run a test purchase or preauth', 'woocommerce'),
            'label' => __('Test Mode', 'woocommerce'),
            'default' => 'no'
        ];
        $this->form_fields['method'] = [
            'title' => __('Payment Method', 'woocommerce'),
            'type' => 'select',
            'options' => $methods,
            'description' => __(
                'Choose between using Helcim.js and Direct Integration - <a href="https://learn.helcim.com/docs/accepting-payments-using-woocommerce" target="_blank">Support Page</a>',
                'woocommerce'
            ),
            'default' => 'js'
        ];
        $this->form_fields['jsToken'] = [
            'title' => __('Helcim JS Token', 'woocommerce'),
            'type' => 'text',
            'description' => __('Your Helcim.js Configuration Token(Required for Helcim JS Method)', 'woocommerce'),
            'default' => ''
        ];
        $this->form_fields['jsSecretKey'] = [
            'title' => __('Helcim JS Secret Key', 'woocommerce'),
            'type' => 'text',
            'description' => __(
                'Your Helcim.js Configuration Secret Key(Required for Helcim JS Method)',
                'woocommerce'
            ),
            'default' => ''
        ];
        $this->form_fields['accountId'] = [
            'title' => __('Account Id', 'woocommerce'),
            'type' => 'text',
            'description' => __('Your Helcim Commerce Account Id', 'woocommerce'),
            'default' => ''
        ];
        $this->form_fields['apiTokenV2'] = [
            'title' => __('API Token', 'woocommerce'),
            'type' => 'text',
            'description' => __('Your Helcim Commerce API Token', 'woocommerce'),
            'default' => '',
        ];
        $this->form_fields['apiToken'] = [
            'title' => __('API Token - Deprecated', 'woocommerce'),
            'type' => 'text',
            'description' => __('Deprecated. Please fill API Token instead', 'woocommerce'),
            'default' => '',
            'custom_attributes' => [
                'disabled' => 'disabled',
            ],
        ];
        $this->form_fields['transactionType'] = [
            'title' => __('Transaction Type', 'woocommerce'),
            'type' => 'select',
            'options' => $transactionTypes,
            'description' => __('Only used for Direct Integration', 'woocommerce'),
            'default' => 'preauth'
        ];
        $this->form_fields['terminalId'] = [
            'title' => __('Terminal Id - Deprecated', 'woocommerce'),
            'type' => 'number',
            'description' => __('Deprecated. Default terminal for the currency will be used', 'woocommerce'),
            'default' => '0',
            'custom_attributes' => [
                'disabled' => 'disabled',
            ],
        ];
        $this->form_fields['description'] = [
            'title' => __('Description', 'woocommerce'),
            'type' => 'textarea',
            'description' => __('This controls the description which the user sees during checkout', 'woocommerce'),
            'default' => __('Pay via credit card', 'woocommerce'),
        ];
        $this->form_fields['show_logo'] = [
            'title' => __('Helcim Logo', 'woocommerce'),
            'type' => 'checkbox',
            'label' => __('Show Helcim logo on checkout', 'woocommerce'),
            'default' => 'yes'
        ];
    }

    private function completeOrder(
        WC_Order $order,
        string $approvalCode,
        string $transactionId,
        string $cardToken
    ): void {
        $order->update_meta_data(self::HELCIM_CARD_TOKEN, $cardToken);
        $order->add_order_note(
            __('Helcim payment completed', 'woocommerce') . ' (Approval Code: ' . $approvalCode . ')'
        );
        $order->payment_complete($transactionId);
        self::log('ORDER ' . $order->get_id() . ' APPROVED - ' . $approvalCode);
        WC()->cart->empty_cart();
    }

    /**
     * Process payment
     *
     * @uses WC_Payment_Gateway::process_payment()
     * @access public
     * @param int $orderId
     * @return array
     */
    public function process_payment($orderId): array
    {
        if (!$this->getHelcimJSService() instanceof HelcimJSService) {
            $this->setHelcimJSService(new HelcimJSService());
        }
        if (!$this->getHelcimDirectService() instanceof HelcimDirectService) {
            $this->setHelcimDirectService(new HelcimDirectService(new HelcimCurl()));
        }
        $order = wc_get_order($orderId);
        if (!self::FORCE_HELCIM_JS_TO_RUN_VERIFY && $this->isJS()) {
            $xmlObject = $this->getHelcimJSService()->parseXML($_POST['xml'], $_POST['xmlHash'], $this);
            if (!$xmlObject instanceof SimpleXMLElement) {
                wc_add_notice(
                    '<b>Payment error:</b> Something went wrong please contact the Merchant',
                    'error'
                );
                self::log("Helcim JS - ERROR - {$this->getHelcimJSService()->getError()}" . print_r($xmlObject, true));
                $order->add_order_note("ERROR - {$this->getHelcimJSService()->getError()}");
                return [];
            }
            if (!$this->getHelcimJSService()->processPayment($order, $xmlObject, $this)) {
                return [];
            }
            $this->completeOrder(
                $order,
                (string)$xmlObject->approvalCode,
                isset($xmlObject->transactionId) ? (string)$xmlObject->transactionId : '',
                isset($xmlObject->cardToken) ? (string)$xmlObject->cardToken : ''
            );
            return [
                'result' => 'success',
                'redirect' => $this->get_return_url($order),
            ];
        }
        if(empty($this->apiTokenV2)){
            // todo remove whole if condition
            $xmlObject = $this->getHelcimDirectService()->processPurchasePreauth($order, $this);
            if (!$xmlObject instanceof SimpleXMLElement) {
                wc_add_notice('<b>Payment error:</b> Something went wrong please contact the Merchant', 'error');
                self::log("ORDER {$order->get_id()} ERROR - {$this->getHelcimDirectService()->getError()}");
                $order->add_order_note(
                    "Helcim Payment Failed - {$this->getHelcimDirectService()->getError()}"
                );
                return [];
            }
            $this->completeOrder(
                $order,
                (string)$xmlObject->transaction->approvalCode ?: '',
                isset($xmlObject->transaction->transactionId) ? (string)$xmlObject->transaction->transactionId : '0',
                (string)$xmlObject->transaction->cardToken ?: ''
            );
        }else{
            $responsePayload = [];

            // create customer
            $customerCode = '';
            if(!empty($_POST['customerCode']) && $this->isJS()){
                $customerCode = (string)$_POST['customerCode'];
                if(get_current_user_id() && (string)$_POST['customerCode'] !== (string)get_current_user_id()){
                    $customerCode = (string)get_current_user_id();
                    $customerIdOld = $this->getHelcimDirectService()->getCustomerId((string)$_POST['customerCode'], $this);
                    $customerIdCurrent = $this->getHelcimDirectService()->getCustomerId($customerCode, $this);
                    if(!$customerIdCurrent){
                        if($customerIdOld){
                            $this->getHelcimDirectService()->updateCustomer($customerIdOld, $customerCode, $order, $this);
                        }else{
                            $this->getHelcimDirectService()->createCustomer($customerCode, $order, $this);
                        }
                    }else{
                        self::log("cannot update customer code from(".(string)$_POST['customerCode'].") to(".(string)get_current_user_id()."). new customer code is already used");
                    }
                }
            }elseif(get_current_user_id()){
                $customerCode = (string)get_current_user_id();
                $customerIdCurrent = $this->getHelcimDirectService()->getCustomerId($customerCode, $this);
                if(!$customerIdCurrent){
                    $this->getHelcimDirectService()->createCustomer($customerCode, $order, $this);
                }
            }

            // process
            if($this->transactionType === self::TRANSACTION_TYPE_PURCHASE){
                $responsePayload = $this->getHelcimDirectService()->processPurchase($order, $this, $customerCode);
                if (!is_array($responsePayload)) {
                    $errorLog = 'Something went wrong please contact the Merchant';
                    if (str_contains($this->getHelcimDirectService()->getError(), 'must be a valid Phone Number')) {
                        $errorLog = 'Phone must be valid phone number (10-15 digits)';
                    }
                    wc_add_notice("<b>Payment error:</b> $errorLog", 'error');
                    self::log("ORDER {$order->get_id()} ERROR - {$this->getHelcimDirectService()->getError()}");
                    $order->add_order_note(
                        "Helcim Payment Failed - {$this->getHelcimDirectService()->getError()}"
                    );
                    return [];
                }
            }elseif($this->transactionType === self::TRANSACTION_TYPE_PREAUTH){
                $responsePayload = $this->getHelcimDirectService()->processPreauth($order, $this, $customerCode);
                if (!is_array($responsePayload)) {
                    $errorLog = 'Something went wrong please contact the Merchant';
                    if (str_contains($this->getHelcimDirectService()->getError(), 'must be a valid Phone Number')) {
                        $errorLog = 'Phone must be valid phone number (10-15 digits)';
                    }
                    wc_add_notice("<b>Payment error:</b> $errorLog", 'error');
                    self::log("ORDER {$order->get_id()} ERROR - {$this->getHelcimDirectService()->getError()}");
                    $order->add_order_note(
                        "Helcim Payment Failed - {$this->getHelcimDirectService()->getError()}"
                    );
                    return [];
                }
            }
            $this->completeOrder(
                $order,
                (string)$responsePayload['approvalCode'],
                (string)$responsePayload['transactionId'],
                (string)$responsePayload['cardToken']
            );
        }
        return [
            'result' => 'success',
            'redirect' => $this->get_return_url($order)
        ];
    }

    /**
     * @uses WC_Payment_Gateway::process_refund()
     * @param int $orderId
     * @param null $amount
     * @param string $reason
     * @return bool
     */
    public function process_refund($orderId, $amount = null, $reason = ''): bool
    {
        if (!$this->getHelcimDirectService() instanceof HelcimDirectService) {
            $this->setHelcimDirectService(new HelcimDirectService(new HelcimCurl()));
        }

        $amount = (float)$amount;
        $order = wc_get_order($orderId);
        if(empty($this->apiTokenV2)) {
            // todo remove whole if condition
            $approvalCode = $this->getHelcimDirectService()->processRefund($order, $amount, $this);
        }else{
            $approvalCode = $this->getHelcimDirectService()->processRefundV2($order, $amount, $this);
        }
        if (!is_string($approvalCode)) {
            wc_add_notice("Refund Failed - {$this->getHelcimDirectService()->getError()}", 'error');
            self::log("ORDER {$order->get_id()} Failed to Refund - {$this->getHelcimDirectService()->getError()}");
            $order->add_order_note(
                "Helcim Refund Failed - {$this->getHelcimDirectService()->getError()}"
            );
            return false;
        }
        wc_add_notice("Refund Approved - $approvalCode");
        $order->add_order_note(
            "Helcim Refund completed (Approval Code: $approvalCode)"
        );
        return true;
    }

    /**
     * Payment Fields
     *
     * @uses WC_Payment_Gateway::payment_fields()
     * @access public
     * @return void
     */
    public function payment_fields(): void
    {
        if (!$this->getHelcimJSService() instanceof HelcimJSService) {
            $this->setHelcimJSService(new HelcimJSService());
        }
        if (!$this->getHelcimLineItemService() instanceof HelcimLineItemService) {
            $this->setHelcimLineItemService(new HelcimLineItemService());
        }
        if (absint(get_query_var('order-pay')) > 0) {
            $order_id = absint(get_query_var('order-pay'));
        } else {
            $order_id = WC()->session->order_awaiting_payment;
            $checkRequiredFields = true;
        }
        $order = wc_get_order($order_id);
        if ($order instanceof WC_Order || $order instanceof WC_Order_Refund) {
            $amountTotal = $order->get_total();
            $amountShipping = $order->get_shipping_total('');
            $amountTax = $order->get_total_tax('');
            $amountDiscount = $order->get_total_discount();
            $orderNumber = $this->setOrderNumber((string)$order->get_order_number());
        } else {
            $amountTotal = WC()->cart->get_total('');
            $amountShipping = WC()->cart->get_shipping_total();
            $amountTax = WC()->cart->get_taxes_total();
            $amountDiscount = WC()->cart->get_discount_total();
            $orderNumber = '';
        }
        $cartItems = WC()->cart->get_cart();
        if (is_array($cartItems)) {
            $helcimLineItems = $this->getHelcimLineItemService()->buildLineItemsFromCart($cartItems);
        } else {
            $helcimLineItems = [];
        }
        ?>
        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
        <?php
        if ($this->isTest()): ?>
            <script type='text/javascript'
                    src='<?php
                    echo self::GOOGLE_RECAPTCHA_URL; ?>?render=<?php
                    echo self::GOOGLE_RECAPTCHA_TEST_SITE_KEY; ?>'></script>
            <script>hcm_site_key = '<?php echo self::GOOGLE_RECAPTCHA_TEST_SITE_KEY; ?>';</script>
        <?php
        else: ?>
            <script type='text/javascript'
                    src='<?php
                    echo self::GOOGLE_RECAPTCHA_URL; ?>?render=<?php
                    echo self::GOOGLE_RECAPTCHA_LIVE_SITE_KEY; ?>'></script>
            <script>hcm_site_key = '<?php echo self::GOOGLE_RECAPTCHA_LIVE_SITE_KEY; ?>';</script>
        <?php
        endif; ?>
        <script>
            function hcmGenerateCaptchaToken() {
                return new Promise(function (resolve, reject) {
                    grecaptcha.ready(function () {
                        grecaptcha.execute(hcm_site_key, {action: 'helcimJSCheckout'})
                            .then(function (token) {
                                document.getElementById('g-recaptcha-response').value = token;
                                resolve(token);
                            })
                            .catch(function (error) {
                                reject(error);
                            })
                    });
                });
            }
        </script>
        <?php
        if ($this->isJS()): ?>
            <script type="text/javascript" src="<?php
            echo self::HELCIM_JS_FILE; ?>"></script>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    hcmUpdateSubmit();
                    hcmUpdateAVSFields();
                });
            </script>
            <div id="LoadingScreen1"
                 style="position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.65); padding-top: 25vh; box-sizing: border-box; display: none;">
                <div align="center">
                    <div style="border: 2px solid #81B1D1; border-top: 2px solid #127295; border-radius: 50%; width: 32px; height: 32px; animation: AnimationLoader1 0.75s linear infinite;"></div>
                    <div style="padding: 20px 0 0 0; font-size: 17px; color: white;">Processing, Please Wait...
                    </div>
                </div>
            </div>
            <div id="helcimResults"></div><br/>
            <input type="hidden" id="woocommerce" value="1">
            <input type="hidden" id="plugin" value="<?php
            echo self::PLUGIN_NAME; ?>">
            <input type="hidden" id="pluginVersion" value="<?php
            echo self::VERSION; ?>">
            <input type="hidden" id="token" value="<?php
            echo $this->jsToken; ?>">
            <input type="hidden" id="test" value="<?php
            echo $this->isTest(); ?>">

            <input type="hidden" id="customerCode" value="<?php
            echo get_current_user_id(); ?>">

            <?php
            if (self::FORCE_HELCIM_JS_TO_RUN_VERIFY): ?>
                <input type="hidden" id="amount" value="0">
            <?php
            else: ?>
                <input type="hidden" id="currency" value="<?php
                echo $this->woocommerceCurrencyAbbreviation(); ?>">
                <input type="hidden" id="amount" value="<?php
                echo number_format($amountTotal, 2, '.', ''); ?>">
                <input type="hidden" id="amountShipping" value="<?php
                echo number_format($amountShipping, 2, '.', ''); ?>">
                <input type="hidden" id="amountTax" value="<?php
                echo number_format($amountTax, 2, '.', ''); ?>">
                <input type="hidden" id="amountDiscount" value="<?php
                echo number_format($amountDiscount, 2, '.', ''); ?>">
                <input type="hidden" id="orderNumber" value="<?php
                echo $orderNumber; ?>">
                <?php
                echo $this->getHelcimJSService()->htmlLineItems($helcimLineItems); ?>
            <?php
            endif; ?>

            <?php
            if (isset($checkRequiredFields) && $checkRequiredFields) {
                $this->generateRequiredInputFields();
            }
            ?>
        <?php
        elseif ($this->isDirect()): ?>
            <!-- SCRIPT -->
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    hcmUpdateAVSFields();
                });
            </script>
        <?php
        endif; ?>
        <fieldset id="wc-<?php
        echo esc_attr($this->id); ?>-cc-form" class='wc-credit-card-form wc-payment-form'>

            <?php
            if ($this->description): ?>
                <p class="form-row form-row-wide"><?php
                    _e($this->description, 'woocommerce'); ?></p>
            <?php
            endif; ?>

            <p id="tr_cardHolderName" class="form-row form-row-wide" style="display: none;">
                <label for="cardHolderName"><?php
                    _e('Name', 'woocommerce'); ?> <span class="required">*</span></label>
                <input type="text" class="input-text" value="" id="cardHolderName" name="cardHolderName">
            </p>

            <p class="form-row form-row-wide">
                <label for="cardNumber"><?php
                    _e('Card Number', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="input-text wc-credit-card-form-card-number" value="" id="cardNumber"
                       name="cardNumber" onchange="hcmClearData('cardToken')" inputmode="numeric"
                       autocomplete="cc-number" autocorrect="no" autocapitalize="no" spellcheck="no"
                       placeholder="&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;">
            </p>

            <p class="form-row form-row-first">
                <label for="cardExpiryMonth"><?php
                    _e('Expiry Month', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                <select id="cardExpiryMonth" name="cardExpiryMonth" class="input-text wc-credit-card-form-card-expiry"
                        onchange="hcmClearData('cardToken')">
                    <option value="">--</option>
                    <?php
                    for ($month = 1; $month <= 12; $month++): ?>
                        <option value="<?php
                        echo sprintf('%02d', $month); ?>"><?php
                            echo sprintf('%02d', $month); ?></option>
                    <?php
                    endfor; ?>
                </select>
            </p>

            <p class="form-row form-row-last">
                <label for="cardExpiryYear"><?php
                    _e('Expiry Year', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                <select id="cardExpiryYear" name="cardExpiryYear" class="input-text wc-credit-card-form-card-expiry"
                        onchange="hcmClearData('cardToken')">
                    <option value="">--</option>
                    <?php
                    for ($year = 0; $year <= 10; $year++): ?>
                        <?php
                        $yearToPrint = (int)date('Y') + $year; ?>
                        <option
                                value="<?php
                                echo $yearToPrint; ?>"><?php
                            echo $yearToPrint; ?></option>
                    <?php
                    endfor; ?>
                </select>
            </p>

            <p class="form-row form-row-first">
                <label for="cardCVV"><?php
                    _e('CVV2', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                <input id="cardCVV" onchange="hcmClearData('cardToken')" class="input-text wc-credit-card-form-card-cvc"
                       inputmode="numeric" autocomplete="off" autocorrect="no" autocapitalize="no" spellcheck="no"
                       maxlength="4" placeholder="<?php
                _e('CVC', 'woocommerce'); ?>" name="cardCVV" style="width:100px"/>
            </p>

            <p id="tr_cardHolderAddress" class="form-row form-row-wide" style="display: none;">
                <label for="cardHolderAddress"><?php
                    _e('Street Address', 'woocommerce'); ?> <span class="required">*</span></label>
                <input type="text" class="input-text" value="" id="cardHolderAddress" name="cardHolderAddress">
            </p>

            <p id="tr_cardHolderPostalCode" class="form-row form-row-wide" style="display: none;">
                <label for="cardHolderPostalCode"><?php
                    _e('Postal/ZIP Code', 'woocommerce'); ?> <span class="required">*</span></label>
                <input type="text" class="input-text" value="" id="cardHolderPostalCode" name="cardHolderPostalCode">
            </p>
        </fieldset>
        <?php
    }

    /**
     * Validate payment fields
     *
     * @return void
     * @uses WC_Payment_Gateway::validate_fields()
     * @access public
     */
    public function validate_fields(): void
    {
        if (!$this->getHelcimJSService() instanceof HelcimJSService) {
            $this->setHelcimJSService(new HelcimJSService());
        }
        if ($this->isJS()) {
            if (!$this->getHelcimJSService()->isValidFields($_POST, $this)) {
                wc_add_notice('<b>Payment error:</b> Something went wrong please contact the Merchant', 'error');
                self::log("Helcim JS Validation Failed - {$this->getHelcimJSService()->getError()}");
                return;
            }
            if (!self::FORCE_HELCIM_JS_TO_RUN_VERIFY) {
                $objectXML = $this->getHelcimJSService()->parseXML($_POST['xml'], $_POST['xmlHash'], $this);
                $transactionType = isset($objectXML->type) ? strtoupper((string)$objectXML->type) : 'UNKNOWN';
                $transactionId = isset($objectXML->transactionId) ? (string)$objectXML->transactionId : '0';
                $approvalCode = (string)$objectXML->approvalCode ?: '';
                self::log("$transactionType #$transactionId Approved - $approvalCode");
                $this->voidTransactionOnFailedCheckout((int)$transactionId);
            }
            return;
        }
        if ($this->isDirect()) {
            $cardNumber = @trim($_POST['cardNumber']);
            if (empty($cardNumber)) {
                wc_add_notice('Missing Card Number', 'error');
                self::log('Missing Card Number');
            }
            return;
        }
        wc_add_notice('Something went wrong please contact the Merchant', 'error');
        self::log("Unknown Payment Method - {$this->method}");
    }

    public function setOrderNumber(string $order_id): string
    {
        return $order_id . '-WC' . rand(0, 99) . substr(time(), -2);
    }

    private function generateRequiredInputFields(): void
    {
        $inputFields = [];
        foreach (WC()->checkout()->get_checkout_fields('billing') as $key => $field) {
            if (!isset($field['required']) || !$field['required']) {
                continue;
            }
            $inputFields[] = [
                "key" => $key,
                "label" => $field['label'] ?? '',
            ];
        }
        if (count($inputFields) > 0) {
            ?>
            <script type="text/javascript">
                required_fields = '<?php echo json_encode($inputFields); ?>';
            </script><?php
        }
    }

    public static function log(string $message): void
    {
        if (!self::$log instanceof WC_Logger) {
            self::$log = new WC_Logger();
        }
        self::$log->add('helcim-commerce', $message);
    }

    public function getHelcimJSService(): ?HelcimJSService
    {
        return $this->helcimJSService;
    }

    public function setHelcimJSService(HelcimJSService $helcimJSService): WCHelcimGateway
    {
        $this->helcimJSService = $helcimJSService;
        return $this;
    }

    public function getHelcimLineItemService(): ?HelcimLineItemService
    {
        return $this->helcimLineItemService;
    }

    public function setHelcimLineItemService(HelcimLineItemService $helcimLineItemService): WCHelcimGateway
    {
        $this->helcimLineItemService = $helcimLineItemService;
        return $this;
    }

    public function woocommerceCurrencyAbbreviation(): string
    {
        return strtoupper((string)preg_replace("/[^a-zA-Z]/", '', get_woocommerce_currency()));
    }

    /**
     * @param int $transactionId
     * @uses voidTransaction
     */
    private function voidTransactionOnFailedCheckout(int $transactionId): void
    {
        $arguments = [
            'transactionId' => $transactionId,
            'gateway' => $this,
        ];
        add_action(
            'woocommerce_checkout_order_exception',
            function () use ($arguments) {
                $arguments['gateway']->voidTransaction((int)$arguments['transactionId']);
            }
        );

        add_action(
            'woocommerce_after_checkout_validation',
            function () use ($arguments) {
                if (wc_notice_count('error') <= 0) {
                    return;
                }
                $arguments['gateway']->voidTransaction((int)$arguments['transactionId']);
            }
        );
    }

    public function voidTransaction(int $transactionId): void
    {
        if (!$this->getHelcimDirectService() instanceof HelcimDirectService) {
            $this->setHelcimDirectService(new HelcimDirectService(new HelcimCurl()));
        }
        $objectXML = $this->getHelcimDirectService()->processVoid($transactionId, $this);
        if (!$objectXML instanceof SimpleXMLElement) {
            wc_add_notice("Void Failed", 'error');
            self::log("FAILED TO VOID TRANSACTION #{$transactionId} - {$this->getHelcimDirectService()->getError()}");
            return;
        }
        $approvalCode = isset($objectXML->transaction->approvalCode) ? (string)$objectXML->transaction->approvalCode : '';
        self::log("VOID #{$transactionId} Approved");
    }
}
