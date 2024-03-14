<?php /** @noinspection DuplicatedCode */
/**
 * Plugin Name: Paylink
 * Description: Use this woocommerce payment gateway plugin to enable clients of your store to pay using Paylink gateway.
 * Version: 2.02
 * Author: Paylink Co
 * text-domain: paylink
 * Plugin URI: https://paylink.sa
 * Domain Path: /languages
 */

add_action('plugins_loaded', 'init_PayLinkAPI', 0);

function init_PayLinkAPI()
{
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }

    // Register the Paylink payment gateway.
    function paylinkwc_register_gateway($gateways)
    {
        $gateways[] = 'WcPayLink';
        return $gateways;
    }

    add_filter('woocommerce_payment_gateways', 'paylinkwc_register_gateway');

    // Add the Paylink order meta.
    function paylinkwc_add_order_meta($order_id)
    {
        update_post_meta($order_id, '_paylink_transaction_no', $_GET['transactionNo']);
    }

    add_action('woocommerce_thankyou', 'paylinkwc_add_order_meta');

    // Display the Paylink order reference in the order details.
    function paylinkwc_display_order_reference($order)
    {
        $paylink_transaction_no = $order->get_meta('_paylink_transaction_no');
        if ($paylink_transaction_no) {
            echo '<p><strong>' . __('Paylink Transaction No', 'paylinkwc') . ':</strong> ' . $paylink_transaction_no . '</p>';
        }
    }

    add_action('woocommerce_order_details_after_order_table', 'paylinkwc_display_order_reference');

    /**
     * The main class representing the PaylinkWC payment gateway.
     */
    class WcPayLink extends WC_Payment_Gateway
    {
        private $_token_auth;
        private string $_get_invoice_url;
        private string $_add_invoice_url;
        private string $_login_url;
        private string $_base_server_url;
        private string $_is_testing;

        private function postError($error, $calledUrl, $method)
        {
            try {
                $apiId = $this->get_option('app_id');
                $apiKey = $this->get_option('secret_key');

                $post_parameter = [
                    'error' => $error,
                    'calledUrl' => $calledUrl,
                    'apiId' => $apiId,
                    'apiKey' => $apiKey,
                    'method' => $method
                ];
                wp_safe_remote_post('https://paylinkapp.paylink.sa/careapi/wp_log_error', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => wp_json_encode($post_parameter),
                ]);
            } catch (Exception $ex) {
            }
        }

        public function __construct()
        {
            load_plugin_textdomain('paylinkwc', false, 'paylink_wc_payment_gateway/languages');

            $this->id = 'paylinkwc';
            $this->icon = apply_filters('woocommerce_paylink_icon', 'https://paylink.sa/assets/img/paylink-logo.png');
            $this->has_fields = true;
            $this->method_title = __('Paylink Payment Gateway', 'paylink');
            $this->method_description = __('It provides your customers with the popular payment methods in the Kingdom of Saudi Arabia, such as Mada, Visa, MasterCard, and Apple Pay', 'paylink');

            $this->supports = [
                'products',
                'refunds'
            ];

            $this->init_form_fields();
            $this->init_settings();

            $this->enabled = $this->get_option('enabled');
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');

            $this->_is_testing = $this->get_option('is_testing_env');
            if ($this->_is_testing === 'on' || $this->_is_testing === 'yes') {
                $this->_base_server_url = 'https://restpilot.paylink.sa';
            } else {
                $this->_base_server_url = 'https://restapi.paylink.sa';
            }
            $this->_get_invoice_url = $this->_base_server_url . '/api/getInvoice';
            $this->_login_url = $this->_base_server_url . '/api/auth';
            $this->_add_invoice_url = $this->_base_server_url . '/api/addInvoice';

            add_action('init', [$this, 'check_paylink_response']);
            add_action('woocommerce_api_wc_paylink', [&$this, 'check_paylink_response']);
            add_action('woocommerce_api_paylink', [&$this, 'check_paylink_response']);
            add_action('woocommerce_api_' . strtolower(get_class($this)), [&$this, 'check_paylink_response']);

            add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);

            add_action('woocommerce_receipt_paylinkwc', [&$this, 'receipt_page']);
            add_action('woocommerce_receipt_WC_paylinkwc', [&$this, 'receipt_page']);

            add_action('wp_head', [&$this, 'write_order_payment_header']);
        }

        public function process_admin_options()
        {
            parent::process_admin_options();
            if ($this->settings['is_testing_env'] == 'yes' || $this->settings['is_testing_env'] == 'on') {
//                $this->add_error(__("<span style='color:red;font-weight: bold'>You are using the testing environment</span>. Please use the testing credentials, <b>APP ID: APP_ID_1123453311</b>, <b>Secret Key: 0662abb5-13c7-38ab-cd12-236e58f43766</b>", 'paylink'));
                $this->wc_add_notice(__("<span style='color:red;font-weight: bold'>You are using the testing environment</span>. Please use the testing credentials, <b>APP ID: APP_ID_1123453311</b>, <b>Secret Key: 0662abb5-13c7-38ab-cd12-236e58f43766</b>", 'paylink'), 'error');
                $this->display_errors();
            }
        }

        public function init_form_fields()
        {
            $this->form_fields = [
                'enabled' => [
                    'title' => __('Enable/Disable', 'paylink'),
                    'type' => 'checkbox',
                    'label' => __('Enable Paylink payment', 'paylink'),
                    'default' => 'yes'
                ],
                'title' => [
                    'title' => __('Title', 'paylink'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'paylink'),
                    'default' => __('Pay with Paylink', 'paylink'),
                    'desc_tip' => true
                ],
                'description' => [
                    'title' => __('Description', 'paylink'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'paylink'),
                    'default' => __('Pay using Paylink', 'paylink'),
                    'desc_tip' => true
                ],
                'app_id' => [
                    'title' => __('APP ID', 'paylink'),
                    'type' => 'text',
                    'description' => __('This is the APP ID assigned to you by Paylink.', 'paylink'),
                    'default' => '',
                    'desc_tip' => true,
                    'required' => true
                ],
                'secret_key' => [
                    'title' => __('Secret Key', 'paylink'),
                    'type' => 'text',
                    'description' => __('This is the API Secret key assigned to you by Paylink.', 'paylink'),
                    'default' => '',
                    'desc_tip' => true,
                    'required' => true
                ],
                'callback_url' => [
                    'title' => __('Callback URL', 'paylink'),
                    'type' => 'text',
                    'description' => __('This is the URL where Paylink will send the payment status callback.', 'paylink'),
//                    'default' => get_site_url() . '/wc-api/paylinkwc/',
                    'default' => get_site_url() . '/wc-api/' . strtolower(get_class($this)),
                    'desc_tip' => true,
                    'custom_attributes' => [
                        'readonly' => 'readonly'
                    ]
                ],
//                'payment_form' => [
//                    'title' => __('Payment Form', 'paylink'),
//                    'type' => 'select',
//                    'description' => __('This controls the payment form which the user sees during checkout. On-Site will display payment in the same of the merchant website. The off-site fee will redirect income to Paylink payment pages.', 'paylink'),
//                    'options' => [
//                        'onsite' => __('On Site', 'paylink'),
//                        'offsite' => __('Off Site', 'paylink'),
//                    ],
//                    'desc_tip' => true
//                ],
                'display_thank_you' => [
                    'title' => __('Display Thank you page', 'paylink'),
                    'type' => 'select',
                    'description' => __('Display a Thank you page before redirecting to the Paylink payment page.', 'paylink'),
                    'options' => [
                        'yes' => __('Yes', 'paylink'),
                        'no' => __('No', 'paylink'),
                    ],
                    'desc_tip' => true
                ],
                'is_testing_env' => [
                    'title' => __('Environment', 'paylink'),
                    'type' => 'select',
                    'description' => __('This controls the payment gateway backend, either the production or testing environment.', 'paylink'),
                    'options' => [
                        'yes' => __('Testing Environment', 'paylink'),
                        'no' => __('Production Environment', 'paylink'),
                    ],
                    'desc_tip' => true
                ],
                'fail_msg' => [
                    'title' => __('Failed Payment Message', 'paylink'),
                    'description' => __('This controls the error message that appears to the user if the payment fails.', 'paylink'),
                    'type' => 'textarea',
                    'desc_tip' => true
                ],
            ];
        }

        public function receipt_page($order_id)
        {
            $paymentForm = $this->get_option('payment_form');
//            if ($paymentForm == 'onsite') {
//                wp_enqueue_script('paylinkwc', 'https://paylink.sa/assets/js/paylink.js', [], '1.0.0');
//                echo '<p>' . __('Thank you for your order', 'paylink') . $this->get_payment_button_html($order_id) . '</p>';
//            } else {
            $url = $this->generate_paylink_url($order_id);
            if ($url) {
                $display_thank_you = $this->get_option('display_thank_you');

                if ($display_thank_you == 'no') {
                    wp_redirect($url);
                    exit;
                } else {
                    echo '<p>' . __('Thank you for your order, you will be redirected to Paylink payment page.', 'paylink') . '</p>';
                    echo "<script>setTimeout(function() {window.location.href = '" . $url . "';}, 2000);</script>";
                }
            } else {
                echo '<p>' . __('Something error. Please try again by refreshing the page', 'paylink') . '</p>';
            }
//            }
        }

        private function generate_paylink_url($order_id)
        {
            $this->_token_auth = $this->login();
            if ($this->_token_auth) {
                update_post_meta($order_id, 'login_token', $this->_token_auth);
                return $this->createPaylinkInvoiceUrl($this->_token_auth, $order_id);
            } else {
                $this->postError(print_r($this->_token_auth, true), 'n/a', 'generate_paylink_url');
                return false;
            }
        }

        private function createPaylinkInvoiceUrl($token, $order_id)
        {
            $order = wc_get_order($order_id);
//            $callbackUrl = get_site_url() . '/wc-api/paylinkwc'; <-- this did not work.

            $redirect_url = $order->get_checkout_order_received_url();
            if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
                $redirect_url = add_query_arg('wc-api', strtolower(get_class($this)), $redirect_url);
            }

            $total = $order->get_total();
            $customerEmail = $order->get_billing_email();
            $note = '';
            $lang = 'ar';
            $customerName = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            $customerPhone = $order->get_billing_phone();

            $j_array = [
                'amount' => $total,
                'orderNumber' => $order_id,
                'callBackUrl' => $redirect_url,
                'note' => $note,
                'clientEmail' => $customerEmail,
                'clientName' => $customerName,
                'clientMobile' => $customerPhone,
                'lang' => $lang,
                'products' => []
            ];

            $json = wp_json_encode($j_array);

            $parameter = [
                'body' => $json,
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 60,
                'httpversion' => '1.1',
                'user-agent' => '1.0',
            ];
            try {
                $response = wp_safe_remote_post($this->_add_invoice_url, $parameter);
                return json_decode($response['body'])->url;
            } catch (Exception $exception) {
                error_log(print_r($exception, true));
                $this->postError(print_r($exception, true), $this->_add_invoice_url, 'createPaylinkInvoiceUrl');
                return false;
            }
        }

        private function login()
        {
            try {
                $app_id = $this->get_option('app_id');
                $secret_key = $this->get_option('secret_key');

                $login_parameter = [
                    'apiId' => $app_id,
                    'secretKey' => $secret_key,
                    'persistToken' => true
                ];
                $login_response = wp_safe_remote_post($this->_login_url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => wp_json_encode($login_parameter),
                ]);
                $this->postError('logged in', $this->_login_url, 'login');
                $this->postError(print_r($login_response, true), $this->_login_url, 'login');
                return json_decode($login_response['body'])->id_token;
            } catch (Exception $ex) {
                error_log(print_r($ex, true));
                $this->postError(print_r($ex, true), $this->_login_url, 'login');
                return false;
            }
        }

        public function process_payment($order_id)
        {
            // Get the order object
            $order = wc_get_order($order_id);

            // Add custom order notes
            $order->add_order_note('Thank you for your order. Please complete your payment using the payment button below.');

            // Get the payment gateway URL
//            $checkout_payment_url = $order->get_checkout_payment_url(true);
            if (version_compare(WOOCOMMERCE_VERSION, '2.1.0', '>=')) {
                /* 2.1.0 */
                $checkout_payment_url = $order->get_checkout_payment_url(true);
            } else {
                /* 2.0.0 */
                $checkout_payment_url = get_permalink(get_option('woocommerce_pay_page_id'));
            }
            // Return the payment button HTML
            return [
                'result' => 'success',
                'redirect' => add_query_arg(
                    'order-pay',
                    $order->get_id(),
                    add_query_arg(
                        'key',
                        $order->get_order_key(),
                        $checkout_payment_url
                    )
                )
            ];
        }

        public function check_paylink_response()
        {
            global $woocommerce;
            $order_id = sanitize_text_field($_REQUEST['orderNumber']);
            $transaction_no = sanitize_text_field($_REQUEST['transactionNo']);

            $checkout_url = wc_get_checkout_url() . '&transactionNo=' . $transaction_no;

            if (isset($order_id) && isset($transaction_no)) {
                $order = wc_get_order($order_id);

                $this->_token_auth = get_post_meta($order_id, 'token_auth');
                if (!$this->_token_auth) {
                    $this->_token_auth = $this->login();
                }
                $options = [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->_token_auth
                    ],
                    'timeout' => 60,
                    'httpversion' => '1.1',
                    'user-agent' => '1.0',
                ];
                $response = wp_safe_remote_get($this->_get_invoice_url . '/' . $transaction_no, $options);
                try {
                    $order_status = sanitize_text_field(json_decode($response['body'])->{'orderStatus'});
                    $order_status = mb_convert_case($order_status, MB_CASE_LOWER, 'UTF-8');

                    // validating order status value
                    if ($order_status == 'paid' || $order_status == 'completed') {
                        $checkout_url = $order->get_checkout_order_received_url() . '&transactionNo=' . $transaction_no;
                        $order->payment_complete($transaction_no);
                        $woocommerce->cart->empty_cart();
                    } else {
                        $msg['class'] = 'error';
                        $msg['message'] = $this->get_option('fail_msg');
                        if (function_exists('wc_add_notice')) {
                            wc_add_notice($msg['message'], $msg['class']);
                        } else {
//                            $woocommerce->add_error(esc_html($msg['message']));
                            $woocommerce->wc_add_notice($msg['message'], 'error');
                            $woocommerce->set_messages();
                        }
                    }
                } catch (Exception $ex) {
                    error_log(print_r($ex, true));
//                    $woocommerce->add_error('Internal Server Error. Try Again Please.');
                    $woocommerce->wc_add_notice('Internal Server Error. Try Again Please.', 'error');
                    $woocommerce->set_messages();
                }
            }
            wp_redirect($checkout_url);
            //            if (isset($_REQUEST['transactionNo'])) {
//                $transaction_no = $_REQUEST['transactionNo'];
//                $order_id = $_REQUEST['order_id'];
//                $status = $_REQUEST['status'];
//
//                $order = wc_get_order($order_id);
//
//                if ($order) {
//                    if ($status == 'completed') {
//                        $order->update_status('processing');
//                    } else {
//                        $order->update_status('failed');
//                    }
//
//                    wc_reduce_stock_levels($order_id);
//                    wc_delete_product_transients();
//
//                    $thankyou_page = $this->get_return_url($order);
//
//                    wc_get_template('checkout/thankyou.php', [
//                        'order' => $order,
//                        'thankyou_page' => $thankyou_page
//                    ]);
//                    exit;
//                }
//            }
        }

        public function payment_fields()
        {
            if ($this->description) {
                echo wpautop(wptexturize($this->description));
            }
        }

        public function write_order_payment_header()
        {
            if (!isset($_GET['key'])) {
                return '';
            }
            $key = $_GET['key'];
            $order_id = wc_get_order_id_by_order_key($key);
            $order = wc_get_order($order_id);
//            $callbackUrl = get_site_url() . '/wc-api/paylinkwc';
//            $callbackUrl = get_site_url() . '/wc-api/' . strtolower(get_class($this));

            $callbackUrl = $order->get_checkout_order_received_url();
            if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
                $callbackUrl = add_query_arg('wc-api', strtolower(get_class($this)), $callbackUrl);
            }

            $amount = $order->get_total();
            $orderNumber = $order_id;
            $currency = $order->get_currency();

            $clientName = '';
            if ($order->get_billing_first_name() || $order->get_billing_last_name()) {
                $clientName = ($order->get_billing_first_name() ?? ' ') . ' ' . ($order->get_billing_last_name() ?? ' ');
            } elseif ($order->get_shipping_first_name() || $order->get_shipping_last_name()) {
                $clientName = ($order->get_shipping_first_name() ?? ' ') . ' ' . ($order->get_shipping_last_name() ?? ' ');
            }

            $clientMobile = '';
            if ($order->get_billing_phone()) {
                $clientMobile = $order->get_billing_phone();
            } elseif ($order->get_shipping_phone()) {
                $clientMobile = $order->get_shipping_phone();
            }

            $items = $order->get_items();
            $products = '';
            foreach ($items as $item) {
                $product_id = $item->get_product_id();
                $product_name = $item->get_name();
                $product_quantity = $item->get_quantity();
                $product_total = $item->get_total();
                $products .= "{title: '$product_id - $product_name', price: $product_total, qty: $product_quantity},";
            }

            $mode = $this->_is_testing == 'on' || $this->_is_testing == 'yes' ? 'test' : 'production';
            $token = $this->login();

            echo "
            <script>
            function submitPaylinkPayment() {
                // paylink
                let paylinkOrder = new Order({
                    callBackUrl: '$callbackUrl', // callback page URL (for example http://localhost:6655 processPayment.php) in your site to be called after payment is processed. (mandatory)
                    clientName: '$clientName', // the name of the buyer. (mandatory)
                    clientMobile: '$clientMobile', // the mobile of the buyer. (mandatory)
                    amount: $amount, // the total amount of the order (including VAT or discount). (mandatory). NOTE: This amount is used regardless of total amount of products listed below.
                    currency: '$currency',
                    clientEmail: '$clientName',
                    orderNumber: '$orderNumber', // the order number in your system. (mandatory)
                    products: [ // list of products (optional)
                        $products
                    ],
                });
                let payment = new PaylinkPayments({mode: '$mode', defaultLang: 'ar', backgroundColor: '#EEE'});
                payment.openPayment('$token', paylinkOrder);
            }
            
            function submitPaylinkApplePay() {
                // paylink
                let paylinkOrder = new Order({
                    callBackUrl: '$callbackUrl', // callback page URL (for example http://localhost:6655 processPayment.php) in your site to be called after payment is processed. (mandatory)
                    clientName: '$clientName', // the name of the buyer. (mandatory)
                    clientMobile: '$clientMobile', // the mobile of the buyer. (mandatory)
                    amount: $amount, // the total amount of the order (including VAT or discount). (mandatory). NOTE: This amount is used regardless of total amount of products listed below.
                    currency: '$currency',
                    clientEmail: '$clientName',
                    orderNumber: '$orderNumber', // the order number in your system. (mandatory)
                    products: [ // list of products (optional)
                        $products
                    ],
                });
                let payment = new PaylinkPayments({mode: '$mode', defaultLang: 'ar', backgroundColor: '#EEE'});
                payment.openApplePay('$token', paylinkOrder);
            }
        </script>
            ";
        }

        private function get_payment_button_html($order_id)
        {
            $order = wc_get_order($order_id);

            if ($order->get_status() == 'pending') {

                $title = $this->get_option('title') ?? 'Pay';

                // Build the JavaScript code to be executed on the client side
                $javascript_code = "<div>
                                        <button onclick='submitPaylinkPayment()'>$title by Bank Card</button>
                                        <button onclick='submitPaylinkApplePay()'>$title by ApplePay</button>
                                    </div>";
                // Return the HTML for the payment button
                return $javascript_code;
            }
        }
    }

// Add the Paylink callback endpoint.
//    function paylinkwc_add_callback_endpoint()
//    {
//        add_rewrite_endpoint('paylinkwc', EP_ROOT);
//    }
//
//    add_action('init', 'paylinkwc_add_callback_endpoint');
// Handle the Paylink callback.
//    function paylinkwc_handle_callback()
//    {
//        $gateway = new PaylinkWcGateway();
//        $gateway->process_callback();
//    }
//    add_action('woocommerce_api_paylinkwc', 'paylinkwc_handle_callback');
}
//    public function process_payment($order_id)
//    {
//        $order = wc_get_order($order_id);
//
//        $params = [
//            'app_id' => $this->get_option('app_id'),
//            'secret_key' => $this->get_option('secret_key'),
//            'amount' => $order->get_total(),
//            'currency' => get_woocommerce_currency(),
//            'order_id' => $order_id,
//            'callback_url' => $this->get_option('callback_url'),
//            'success_url' => $this->get_return_url($order),
//            'cancel_url' => $order->get_cancel_order_url_raw()
//        ];
//
//        $response = wp_remote_post('https://api.paylink.sa/api/v1/transactions', [
//            'method' => 'POST',
//            'headers' => [
//                'Content-Type' => 'application/json'
//            ],
//            'body' => json_encode($params),
//            'timeout' => 60
//        ]);
//
//        if (is_wp_error($response)) {
//            throw new Exception(__('An error occurred while processing your payment. Please try again.', 'paylinkwc'));
//        }
//
//        $response_body = json_decode(wp_remote_retrieve_body($response));
//
//        if ($response_body->success) {
//            return [
//                'result' => 'success',
//                'redirect' => $response_body->data->payment_url
//            ];
//        } else {
//            throw new Exception(__('An error occurred while processing your payment. Please try again.', 'paylinkwc'));
//        }
//    }

