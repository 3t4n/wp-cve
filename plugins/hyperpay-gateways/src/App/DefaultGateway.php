<?php

namespace Hyperpay\Gateways\App;

use Exception;
use Hyperpay\Gateways\Main;
use WC_Order;
use WC_Payment_Gateway;


/**
 * Hyperpay main class created to extends from it 
 * when create a new payments Gateways
 * 
 */
class DefaultGateway extends WC_Payment_Gateway
{
    use HyperpayBlocks;
    /**
     * Gateway admin options
     */
    public $testMode, $title, $trans_type;
    public $accessToken, $entityId, $brands, $order_status;
    public $custom_style, $latin_validation;
    public $currency, $is_arabic, $supported_network;
    public $server_to_server = false;



    /**
     * connecter type on test mode
     * 
     * @var string INTERNAL|EXTERNAL
     */

    public $trans_mode = 'INTERNAL';
    public $description;

    /**
     * if payments have direct fields on checkout page 
     * 
     * @var boolean
     */
    public $has_fields = false;

    /**
     * used to display the invoice id
     * at success order page
     * @var string|null
     */
    public $invoice_id;



    /**
     * Mada BlackBins
     * 
     * @var array
     */
    protected $blackBins = [];

    /**
     * supported brands thats will showing on settings and checkout page
     * 
     * @var array
     */
    protected $supported_brands = [];

    /**
     * displayed error msg
     * @var string
     */
    protected $failed_message = '';

    /**
     * displayed success msg
     * @var string
     */
    protected $success_message = '';

    /**
     * regular expressions
     * @var string
     */

    public $successCodePattern = '/^(000\.000\.|000\.100\.1|000\.[36])/';
    public $successManualReviewCodePattern = '/^(000\.400\.0|000\.400\.100)/';
    public $pendingCodePattern = '/^(800\.400\.5|100\.400\.500)/';

    /**
     * CopyAndPay script URL
     * 
     * @var string
     */
    protected $script_url = "https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId=";

    /**
     * CopyAndPay prepare checkout link
     * 
     * @method POST
     * @var string
     */
    protected $token_url = "https://eu-prod.oppwa.com/v1/checkouts";


    /**
     * get transaction status
     * @method GET
     * @var string
     * 
     * ##TOKEN## will replace with transaction id when fire the request
     */
    protected $transaction_status_url = "https://eu-prod.oppwa.com/v1/checkouts/##TOKEN##/payment";

    /**
     * back-office end-point
     * @method GET
     * @var string
     * 
     */
    protected $server_to_server_url = "https://eu-prod.oppwa.com/v1/payments";

    /** 
     * Query transaction report
     * 
     * @method GET
     * @var string
     */
    protected $query_url = "https://eu-prod.oppwa.com/v1/query";


    protected $ACI_base_url = "https://eu-prod.oppwa.com";
    /**
     * payment styles that will show in settings 
     * 
     * @var array
     * 
     */
    protected  $payment_style = [
        'card' =>  'Card',
        'plain' =>  'Plain'
    ];


    function __construct()
    {

        $this->init_settings(); // <== to get saved settings from database
        $this->init_form_fields(); // <== render form inside admin panel
        $this->is_arabic = substr(get_locale(), 0, 2) == 'ar'; // <== to get current locale 

        $this->testMode = $this->get_option('testmode'); // <== check if payments on test mode 
        $this->title = $this->get_option('title'); // <== get title from setting
        $this->trans_type = $this->get_option('trans_type'); // <== get transaction type [DB / Pre-Auth] from setting
        $this->accessToken = $this->get_option('accesstoken'); // <== get access toke from setting
        $this->entityId = $this->getEntity(); // <== get entityId from setting

        $this->brands = is_array($this->get_option('hyper_pay_brands')) ? $this->get_option('hyper_pay_brands') : [$this->get_option('hyper_pay_brands')]; // <== get brands from setting

        $this->payment_style = $this->get_option('payment_style'); // <== get style from setting

        $this->order_status = $this->get_option('order_status'); // <== get order status after success from setting
        $this->custom_style = $this->get_option('custom_style'); // <== get custom style from setting




        $this->latin_validation = $this->get_option('latin_validation'); // <== get custom style from setting
        $this->currency = get_woocommerce_currency();



        /**
         * if test mode is one 
         * overwrite currents URLs ti test URLs
         */
        if ($this->testMode) {
            $this->query_url = "https://eu-test.oppwa.com/v1/query";
            $this->token_url = "https://eu-test.oppwa.com/v1/checkouts";
            $this->script_url = "https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId=";
            $this->transaction_status_url = "https://eu-test.oppwa.com/v1/checkouts/##TOKEN##/payment";
            $this->server_to_server_url = "https://eu-test.oppwa.com/v1/payments";
            $this->ACI_base_url = "https://eu-test.oppwa.com";
        }

        $this->query_url .= "?entityId=" . $this->entityId;
        $this->transaction_status_url .= "?entityId=" . $this->entityId;

        /**
         * default failed message 
         * @var string
         */
        $this->failed_message =  __('Your transaction not completed .',  'hyperpay-payments');
        $this->success_message = __('Your payment has been processed successfully.', 'hyperpay-payments');

        /**
         * overwrite default update function 
         * 
         * @param woocommerce_update_options_payment_gateways_<payment_id>
         * @param array[class,function_name]
         */

        if (!has_action("woocommerce_update_options_payment_gateways_{$this->id}")) {
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }


        /**
         * prepare checkout form
         * 
         * @param string woocommerce_receipt_<payments_id>
         * @param array[class,function_name]
         */

        if (!has_action("woocommerce_receipt_{$this->id}")) {
            add_action("woocommerce_receipt_{$this->id}", [$this, 'receipt_page']);
        }


        /**
         * set payments icon from src/assets/images/BRAND-log.png 
         * 
         * make sure when add new image to rename image according this format BRAND_NAME-logo.svg
         * 
         * @param string woocommerce_gateway_icon
         * @param array[class,function_name]
         * 
         */
        add_filter('woocommerce_gateway_icon', [$this, 'set_icons'], 10, 2);

        /**
         * to include src/assets/js/admin.js <JavaScript>
         * 
         * @param string admin_enqueue_scripts
         * @param array[class,function_name]
         *          
         */
        add_action('admin_enqueue_scripts', [$this, 'admin_script']);

        add_action("woocommerce_thankyou_order_received_text", [$this, "order_received_text"], 10, 2);

        add_action('before_woocommerce_pay', [$this, 'action_before_woocommerce_pay'], 10, 0);

        if (!has_action("woocommerce_order_action_capture_payment")) {
            add_action('woocommerce_order_action_capture_payment', [$this, 'capture_payment']);
        }
    }



    public function getEntity()
    {
        $available_currencies =  $this->get_option('currencies_ids');
        $current_currency = get_woocommerce_currency();

        if (isset($available_currencies[$current_currency])) {
            return $available_currencies[$current_currency];
        }

        return $this->get_option('entityId');
    }


    public function process_admin_options()
    {
        $this->init_settings();

        $post_data = $this->get_post_data();

        foreach ($this->get_form_fields() as $key => $field) {
            if ('title' !== $this->get_field_type($field)) {
                try {
                    $this->settings[$key] = $this->get_field_value($key, $field, $post_data);
                    if ('select' === $field['type'] || 'checkbox' === $field['type']) {
                        /**
                         * Notify that a non-option setting has been updated.
                         *
                         * @since 7.8.0
                         */
                        do_action(
                            'woocommerce_update_non_option_setting',
                            array(
                                'id'    => $key,
                                'type'  => $field['type'],
                                'value' => $this->settings[$key],
                            )
                        );
                    } elseif ('currencies_ids_field' === $key && isset($post_data['currencies_ids'])) {
                        $this->settings["currencies_ids"] = array_reduce($post_data['currencies_ids'], function ($result, $item) {
                            if ($item['value'] && $item['name'])
                                $result[$item['name']] = $item['value'];
                            return $result;
                        }, array());
                    }
                } catch (Exception $e) {
                    $this->add_error($e->getMessage());
                }
            }
        }

        $option_key = $this->get_option_key();
        do_action('woocommerce_update_option', array('id' => $option_key)); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
        return update_option($option_key, apply_filters('woocommerce_settings_api_sanitized_fields_' . $this->id, $this->settings), 'yes'); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
    }

    public function capture_payment($order)
    {

        $uniqueId = $order->get_meta('transaction_id');
        $url = $this->server_to_server_url . $uniqueId;

        $orderAmount = number_format($order->get_total(), 2, '.', '');
        $amount = number_format(round($orderAmount, 2), 2, '.', '');

        $gateway_name = 'WC_' . ucfirst($order->get_payment_method()) . "_Gateway";
        $gateway = new $gateway_name();

        $data = [
            'headers' => [
                "Authorization" => "Bearer {$gateway->accessToken}"
            ],
            'body' => [
                "entityId" => $gateway->entityId,
                "amount" => $amount,
                "currency" => $gateway->currency,
                "paymentType" => 'CP',
            ]
        ];

        $response = Http::post($url, $data);
        $resultCode = $response['result']['code'] ?? '';

        if (preg_match($this->successCodePattern, $resultCode) || preg_match($this->successManualReviewCodePattern, $resultCode)) {
            $order->add_order_note("Captured Successfully");
            $order->update_status($this->order_status);
        } else {
            $order->add_order_note("Captured Failed" . $resultCode['result']['description'] ?? 'Unknown reason');
        }

        $location = $_SERVER['HTTP_REFERER'];
        wp_safe_redirect($location);
        die;
    }

    public function action_before_woocommerce_pay()
    {
        global $wp;

        $order_id = absint($wp->query_vars['order-pay']); // The order ID

        $order    = wc_get_order($order_id);

        if ($order->has_status('on-hold')) {
            $order->update_status('pending');
        } elseif ($order->has_status($this->order_status)) {
            wp_redirect($this->get_return_url($order));
        }
    }

    public function order_received_text($thanks_text, $order)
    {

        $msg = $order->get_meta('gateway_note');
        if ($order->get_payment_method() == $this->id && $order->get_status() == 'on-hold' &&  !empty($msg)) {
            wc_add_notice($msg, "notice");
            wc_print_notices();
        } else {
            return $thanks_text;
        }
    }

    /**
     * for validate settings form
     * @return void
     */

    public function admin_script(): void
    {
        global  $current_tab, $current_section;

        /**
         * to make sure load admin.js just when currents payments opened
         * 
         */
        if ($current_tab == 'checkout' && $current_section == $this->id) {

            $data = [
                'id' => $this->id,
                'url' => $this->token_url,
                'code_setting' => wp_enqueue_code_editor(['type' => 'text/css'])
            ];

            wp_enqueue_script('hyperpay_admin',  HYPERPAY_PLUGIN_DIR . '/src/assets/js/admin.js', ['jquery'], false, true);
            wp_localize_script('hyperpay_admin', 'data', $data);
        }
    }


    public function iconSrc()
    {
        $icons = [];
        foreach ($this->brands as  $brand) {
            $img =  HYPERPAY_PLUGIN_DIR .  '/src/assets/images/default.png';
            if (file_exists(Main::ROOT_PATH . '/assets/images/' . esc_attr($brand) . "-logo.svg"))
                $img = HYPERPAY_PLUGIN_DIR . '/src/assets/images/' . esc_attr($brand) . "-logo.svg";

            $icons[] = $img;
        }
        return $icons;
    }

    /**
     * to set payment icon based on supported brands
     * 
     * @param string $icon
     * @param string $id current payment id
     * 
     * @return string  $icon new icon
     * 
     */

    public function set_icons($icon, $id)
    {

        if ($id == $this->id) {
            $icons = "";
            foreach ($this->iconSrc() as  $src) {
                $icons .= "<img  style='padding:2px ; ' src='$src' >";
            }
            return $icons;
        }

        return $icon;
    }

    /**
     * Here you can define all fields thats will showing in setting page
     * @return void
     */
    public function init_form_fields(): void
    {

        $this->form_fields = [
            'enabled' => [
                'title' => __('Enable/Disable', 'hyperpay-payments'),
                'type' => 'checkbox',
                'label' => __('Enable Payment Module.', 'hyperpay-payments'),
                'default' => 'no'
            ],
            'testmode' => [
                'title' => __('Test mode', 'hyperpay-payments'),
                'type' => 'select',
                'options' => ['0' => __('Off', 'hyperpay-payments'), '1' => __('On', 'hyperpay-payments')]
            ],
            'title' => [
                'title' => __('Title:', 'hyperpay-payments'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'hyperpay-payments'),
                'default' => $this->method_title ??  __('Credit Card', 'hyperpay-payments')
            ],
            'trans_type' => [
                'title' => __('Transaction type', 'hyperpay-payments'),
                'type' => 'select',
                'options' => $this->get_hyperpay_trans_type(),
            ],
            'accesstoken' => [
                'title' => __('Access Token', 'hyperpay-payments'),
                'type' => 'text',
            ],
            'entityId' => [
                'title' => __('Entity ID', 'hyperpay-payments'),
                'type' => 'text',
                'description' => __('This will used as default if multi-currency not configured', 'hyperpay-payments'),
            ],
            'currencies_ids_field' => [
                'custom_attributes' => [
                    'data-currencies' => $this->get_option("currencies_ids") ? json_encode($this->get_option("currencies_ids")) : null,
                    'data-currencies_list' => json_encode(array_keys(get_woocommerce_currencies()))
                ],
                'title' => __('Multi-currency', 'hyperpay-payments'),
                'type' => 'hidden',
                'description' => __('In case you have a multi-currency store', 'hyperpay-payments'),
            ],
            'secret' => [
                'title' => __('Webhook Key', 'hyperpay-payments'),
                'type' => 'text',
            ],
            '_webhock' => [
                'title' => __('Webhook URL', 'hyperpay-payments'),
                'type' => 'text',
                'class' => 'disabled',
                'default' => get_site_url() . "/?rest_route=/hyperpay/v1/" . \str_replace("\\", "/", get_class($this))
            ],
            'hyper_pay_brands' => [
                'title' => __('Brands', 'hyperpay-payments'),
                'class' => count($this->supported_brands) !== 1 ?:  'disabled',
                'type' => count($this->supported_brands) > 1 ? 'multiselect' : 'select',
                'options' => $this->supported_brands,
            ],
            'payment_style' => [
                'title' => __('Payment Style', 'hyperpay-payments'),
                'type' => 'select',
                'class' => count($this->payment_style) !== 1 ?:  'disabled',
                'options' => $this->payment_style,
                'default' => 'plain'
            ],
            'custom_style' => [
                'title' => __('Custom Style', 'hyperpay-payments'),
                'type' => 'textarea',
                'description' => 'Input custom css for payment (Optional)',
                'class' => 'hyperpay_custom_style'
            ],
            'latin_validation' => [
                'title' => __('Enable Input validation (Accept English Characters only)', 'hyperpay-payments'),
                'type' => 'checkbox',
                'label' => __('Yes'),
                'default' => 'yes',
                'description' => __('Disable this option may cause transaction declined by bank due to 3DSecure', 'hyperpay-payments'),
            ],
            'order_status' => [
                'title' => __('Status Of Order', 'hyperpay-payments'),
                'type' => 'select',
                'options' => $this->get_order_status(),
                'description' => __("select order status after success transaction.", 'hyperpay-payments')
            ],

        ];
    }


    /**
     *  to fill order_status select fields
     * 
     * @return array
     */
    function get_order_status(): array
    {
        $order_status = [

            'processing' =>  __('Processing', 'hyperpay-payments'),
            'completed' =>  __('Completed', 'hyperpay-payments')
        ];

        return $order_status;
    }

    /**
     *  to fill trans_type select fields
     * 
     * @return array
     */
    function get_hyperpay_trans_type(): array
    {
        $hyperpay_trans_type = [
            'DB' => 'Debit',
            'PA' => 'Pre-Authorization'
        ];

        return $hyperpay_trans_type;
    }


    /**
     * This function fire when click on Place order at checkout page
     * @param int $order_id
     * 
     * @return void
     */
    function receipt_page($order_id)
    {
        $order = new WC_Order($order_id);

        // if we have id param that mean the page result ACI redirection 
        if (isset($_GET['resourcePath'])) {
            $resourcePath = sanitize_text_field($_GET['resourcePath']);
            $url = $this->ACI_base_url . $resourcePath;
            // set header request to contain access token
            $auth = [
                'headers' => ['Authorization' => 'Bearer ' . $this->accessToken],
                "body" => [
                    "entityId" => $this->entityId
                ]
            ];

            $response = Http::get($url, $auth);



            //Dynamic fire function based on status 
            $status = $this->check_status($response);
            return $this->$status($order, $response);
        } elseif( $result = $this->prepareCheckout($order_id)) { // process a new transaction
            return $this->renderPaymentForm($order, $result);
        }
    }

    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * 
     * render CopyAndPay form
     * @param WC_Order $order
     * @param string $token
     * @return void
     */
    public function renderPaymentForm(WC_Order $order, $result)
    {

        if ($this->server_to_server) {
            $redirect = $result['response']['redirect'];
            $extra_params = [];
            $query_url = parse_url($redirect['url'], PHP_URL_QUERY);
            if ($query_url)
                parse_str(parse_url($redirect['url'], PHP_URL_QUERY), $extra_params);


            return View::render('server-to-server.html', \compact('redirect', 'extra_params'));
        }

        $token = $result['response']['id'];
        $postBackURL = $result['postBackURL'];


        $scriptURL = $this->script_url;
        $scriptURL .= $token;

        $payment_brands = $this->brands;

        if (is_array($this->brands))
            $payment_brands = implode(' ', $this->brands);


        $dataObj = [
            'is_arabic' => esc_js($this->is_arabic),
            'style' => esc_html($this->payment_style),
            'postBackURL' => ($postBackURL),
            'payment_brands' => esc_html($payment_brands),
            'custom_style' => esc_html($this->custom_style),
            'scriptURL' => esc_html($scriptURL)
        ];


        if ($this->supported_network) {
            $dataObj['supported_network'] = $this->supported_network;
        }

        $custom_style = $this->custom_style;
        return View::render('copy-and-pay.html', compact('dataObj'));
    }


    /**
     * Process the payment and return the result
     * @param int $order_id
     * @return array[redirect,token,result]
     * 
     */
    public function process_payment($order_id)
    {

        $order = new WC_Order($order_id);
        /**
         * 
         * validate data to prevent arabic character 
         */

        if ($this->latin_validation == 'yes') {
            $firstName = $order->get_billing_first_name();
            $family = $order->get_billing_last_name();
            $street = $order->get_billing_address_1();
            $city = $order->get_billing_city();
            $email = $order->get_billing_email();


            $data_to_validate = [
                'first name' => $firstName,
                'last name' => $family,
                'street' => $street,
                'city' => $city,
                'email' =>  $email,
            ];

            if ($order->get_billing_state()) {
                $data_to_validate['state'] = $order->get_billing_state();
            }


            // raise a validation error if validation valid
            $this->validate_form($data_to_validate);
        }


        return [
            'result' => 'success',
            'redirect' => $order->get_checkout_payment_url(true)
        ];
    }

    public function getCheckoutData($order_id)
    {
        $order = new WC_Order($order_id);

        $shipping_cost = number_format($order->get_shipping_total(), 2, '.', '');
        $amount = number_format($order->get_total(), 2, '.', '');
        $transactionKey =  rand(11111111, 99999999);

        $postBackURL = $order->get_checkout_payment_url(true);


        $postBackURL .=  parse_url($postBackURL, PHP_URL_QUERY) ? '&' : '?';
        $postBackURL .= 'callback=true';
        $postBackURL .= "&transaction-key=$transactionKey";

        $data = [
            'headers' => [
                "Authorization" => "Bearer {$this->accessToken}"
            ],
            'body' => [
                "entityId" => $this->entityId,
                "amount" => $amount,
                "currency" => $this->currency,
                "paymentType" => $this->trans_type,
                "merchantTransactionId" => $order_id . "I" . $transactionKey,
                "customer.email" =>  $order->get_billing_email(),
                "notificationUrl" =>  $order->get_checkout_payment_url(true),
                "customParameters[bill_number]" => $order_id,
                "customer.givenName" => $order->get_billing_first_name(),
                "customer.surname" => $order->get_billing_last_name(),
                "billing.street1" => $order->get_billing_address_1(),
                "billing.city" => $order->get_billing_city(),
                "billing.state" => $order->get_billing_state(),
                "billing.country" => $order->get_billing_country(),
                "billing.postcode" =>  $order->get_billing_postcode(),
                "shipping.postcode" =>  $order->get_billing_postcode(),
                "shipping.street1" => $order->get_billing_address_1(),
                "shipping.city" => $order->get_billing_city(),
                "shipping.state" => $order->get_billing_state(),
                "shipping.country" => $order->get_billing_country(),
                "shipping.cost" => $shipping_cost,
                "customParameters[branch_id]" => '1',
                "customParameters[teller_id]" => '1',
                "customParameters[device_id]" => '1',
                "customParameters[plugin]" => 'wordpress',
                "locale" => get_locale()

            ]
        ];


        if ($this->testMode) {
            $data['body']["testMode"] = $this->trans_mode;
        }

        if ($this->server_to_server) {
            $data['body']['shopperResultUrl'] = $postBackURL;
            $data['body']['paymentBrand'] = $this->brands[0];
        }



        // add extra parameters if exists
        return [
            "data" => array_replace_recursive($data, $this->setExtraData($order)),
            "postBackURL" => $postBackURL
        ];
    }

    public  function prepareCheckout($order_id)
    {

        // set data to post 
        $url = $this->server_to_server ? $this->server_to_server_url : $this->token_url;

        $checkout = $this->getCheckoutData($order_id);

        // HTTP Request to oppwa to get checkout
        $response = Http::post($url, $checkout['data']);

        if (!\preg_match("/^(000\.200)/", $response['result']['code'] ?? '')) {
            $this->handleError($response);
            return false;
        }

        return [
            "response" => $response,
            "postBackURL" => $checkout['postBackURL']
        ];
    }


    /**
     * check if all data valid to post {English Characters}
     * @param array
     * @return void
     */
    function validate_form(array $data)
    {
        $errors = [];

        foreach ($data as $key => $field) {
            if (!preg_match("/^[a-zA-Z0-9-._!`'#%&,:;<>=@{}~\$\(\)\*\+\/\\\?\[\]\^\| +]+$/", $field) || strlen($field) < 3)
                $errors[$key] =  __($key, 'hyperpay-payments') . ' ' . __('format error', 'hyperpay-payments');
        }

        if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $data['email'])) {
            $errors['email'] =  __('Email format not valid', 'hyperpay-payments');
        }


        foreach ($errors as $msg) {
            if (self::has_checkout_block()) {
                throw new Exception($msg);
            } else {
                wc_add_notice('<strong>' . $msg  . '</strong>', 'error');
            }
        }

        // count equal to zero then no errors and it's valid
        return count($errors);
    }

    /**
     * 
     * GET request to transaction report to check if transaction exists or not
     * @param int
     * @return array $response
     * 
     */
    public function queryTransactionReport(string $merchantTrxId): array
    {
        $url =  $this->query_url . "&merchantTransactionId=$merchantTrxId";
        return Http::get($url, ["headers" => ["Authorization" => "Bearer {$this->accessToken}"]]);
    }



    /**
     * 
     * check the status
     * 
     * @param array $resultJson
     * @return string
     */
    public function check_status(array $resultJson): string
    {

        $status = 'failed';
        $resultCode = $resultJson['result']['code'] ?? '';

        if (preg_match($this->successCodePattern, $resultCode) || preg_match($this->successManualReviewCodePattern, $resultCode)) {
            $status = 'success';
        } elseif (preg_match($this->pendingCodePattern, $resultCode)) {
            $status = "pending";
        } elseif (isset($resultJson['card']['bin']) && $resultJson['result']['code'] == '800.300.401' && in_array($resultJson['card']['bin'], $this->blackBins)) {
            $this->failed_message = __('Sorry! Please select "mada" payment option in order to be able to complete your purchase successfully.', 'hyperpay-payments');
        }

        return $status;
    }


    /**
     * handel failed Payments 
     * @param WC_Order $order
     * @param string $message
     * @return void
     */
    public function failed(WC_Order $order,  $resultJson)
    {

        if (isset($_GET["callback"]) && isset($_GET['transaction-key'])) {

            $hpOrderId = $order->get_id();
            $transactionKey = sanitize_text_field($_GET['transaction-key']);
            $merchantTrxId = $hpOrderId . "I" . $transactionKey;
            $queryResponse = $this->queryTransactionReport($merchantTrxId);

            if (array_key_exists("payments", $queryResponse)) {
                return $this->processQueryResult($queryResponse, $order);
            }
        }

        return $this->handleError($resultJson);
    }

    public function handleError($resultJson)
    {        
        $order = wc_get_order(wc_clean(get_query_var('order-pay')));

        $error_code = $resultJson["result"]["code"];
        $error_description =  $resultJson["result"]["description"];
        $aci_msg = $error_description;
        $error_list = [];

        if( $error_code == "600.200.500"){
            $aci_msg =  "configuration error" ;
        }

        $order->add_order_note("{$this->failed_message} $error_code :  $error_description");
        $order->add_order_note("full response :  " . json_encode($resultJson));

        Log::write(["error" => $error_description, "response" => $resultJson]);

        $error_list = $this->getExtended($resultJson);

        if(empty($error_list)){
            wc_add_notice($this->failed_message, "error");
            wc_add_notice($aci_msg, "error");
        }

        foreach ($error_list  as $error) {
            wc_add_notice($error["error"], "error");
            $order->add_order_note("extended description : " .  $error["error"]);
        }
        
        $order->update_status("failed");

        wc_print_notices();
    }

    public function getExtended($resultJson)
    {
        $error_list = [];
        if (isset($resultJson["resultDetails"]["ExtendedDescription"])) {
            $resultDetails = $resultJson["resultDetails"]["ExtendedDescription"];
            if ($this->isJson($resultDetails)) {
                $resultDetails = json_decode($resultDetails, true);

                if (array_key_exists("details", $resultDetails)) {
                    $error_list[] = $resultDetails["details"];
                } elseif (array_key_exists("message", $resultDetails)) {
                    $error_list[] = ["error" => $resultDetails['message']];
                }
            }
        }

        return $error_list;
    }

    /**
     * check the result of transaction if success of failed 
     * 
     * @param array $resultJson
     * @param WC_Order $order
     * @return void
     */
    public function processQueryResult(array $resultJson, WC_Order $order)
    {
        unset($_GET["callback"]);

        $payment = end($resultJson["payments"]); // get the last transaction

        if (isset($payment["result"]["code"])) {
            $status = $this->check_status($payment);
            return $this->$status($order, $payment);
        }
    }

    /**
     * set customParameters of requested data 
     * @param WC_Order $order
     * @return array
     */
    public function setExtraData(WC_Order $order): array
    {
        return [];
    }

    /**
     * update success order 
     * @param WC_Order $order
     * @param array $resultJson
     * @return void
     */
    public function success(WC_Order $order, $resultJson)
    {
        global $woocommerce;


        $woocommerce->cart->empty_cart();
        $uniqueId = $resultJson["id"];

        //to add action in order details to capture the pre authorization payments
        if (array_key_exists('paymentType', $resultJson) && $resultJson['paymentType'] == "PA") {
            $order->add_meta_data("is_pre_authorization", true);
            $order->add_meta_data("transaction_id", $uniqueId);
            $order->add_order_note("pre authorization transaction, need to capture");
            $this->order_status = "on-hold";
        }

        if (array_key_exists("invoice_id", $resultJson["resultDetails"])) {
            $this->invoice_id =  $resultJson["resultDetails"]["invoice_id"];
            $order->add_meta_data("invoice_id", $this->invoice_id);
            $order->add_order_note("invoice id : " . $this->invoice_id);
        }

        $order->add_order_note($this->success_message . __("Transaction ID: ", "hyperpay-payments") . esc_html($uniqueId));
        $order->update_status($this->order_status);
        $order->save();



        wp_redirect($this->get_return_url($order));
        die;
    }

    /**
     * update pending order 
     * @param WC_Order $order
     * @param array $resultJson
     * @return void
     */
    public function pending(WC_Order $order, $resultJson)
    {
        global $woocommerce;


        $order->update_status("on-hold");
        $order->add_meta_data("gateway_note", __("Transaction is pending confirmation from ", "hyperpay-payments") . str_replace("hyperpay_", "", $order->get_payment_method()));
        $order->save();

        $woocommerce->cart->empty_cart();
        $uniqueId = $resultJson["id"];

        $order->add_order_note("the order waiting gateway confirmation" . __("Transaction ID: ", "hyperpay-payments") . esc_html($uniqueId));
        wp_redirect($this->get_return_url($order));
    }
}
