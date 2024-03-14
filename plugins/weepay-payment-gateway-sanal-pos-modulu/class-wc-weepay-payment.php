<?php

/*
 * Plugin Name: weepay Payment Gateway | Sanal POS Modülü
 * Plugin URI: https://weepay.co
 * Description: weepay Payment Gateway For Woocommerce | tüm kredi kartları ile taksitli alışveriş | Sanal POS | weepay woocommerce Sanal POS modülü
 * Version: 1.0.3
 * Author: weepay.co
 * Author URI: https://weepay.co/
 * Domain Path: /i18n/languages/
 * WC requires at least: 3.0.0
 * WC tested up to: 8.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}
global $weepay_db_version;
$weepay_db_version = '1.0.3';
register_deactivation_hook(__FILE__, 'weepay_deactivation');
register_activation_hook(__FILE__, 'weepay_activate');
add_action('plugins_loaded', 'weepay_update_db_check');

function weepay_update_db_check()
{
    global $weepay_db_version;
    global $wpdb;
    $installed_ver = get_option("weepay_db_version");
    if ($installed_ver != $weepay_db_version) {
        weepay_update();
    }
}

function weepay_update()
{
    global $weepay_db_version;
    update_option("weepay_db_version", $weepay_db_version);
}

function weepay_activate()
{
    global $wpdb;
    global $weepay_db_version;
    $weepay_db_version = '1.0.3';

    $charset_collate = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    add_option('weepay_db_version', $weepay_db_version);
}

function weepay_deactivation()
{
    global $wpdb;
    global $weepay_db_version;

    delete_option('weepay_db_version');
    flush_rewrite_rules();
}

function weepay_install_data()
{
    global $wpdb;
}

add_action('plugins_loaded', 'woocommerce_weepay_payment_init', 0);

function woocommerce_weepay_payment_init()
{
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    class WC_Gateway_Weepay extends WC_Payment_Gateway
    {

        public function __construct()
        {
            $this->id = 'weepay';
            $this->method_title = __('weepay Checkout form', 'weepay-payment');
            $this->method_description = __('weepay Payment Module', 'weepay-payment');
            $iconUrl = plugin_dir_url(__FILE__);
            $this->icon = $iconUrl . 'img/cards.png';
            $this->has_fields = false;
            $this->supports = array('products', 'refunds');
            $this->init_form_fields();
            $this->init_settings();
            $this->weepay_payment_bayi_id = $this->settings['weepay_payment_bayi_id'];
            $this->weepay_payment_bayi_api = $this->settings['weepay_payment_bayi_api'];
            $this->weepay_payment_bayi_secret = $this->settings['weepay_payment_bayi_secret'];
            $this->weepay_payment_bayi_form_type = $this->settings['weepay_payment_bayi_form_type'];
            $this->weepay_payment_bayi_installement = $this->settings['weepay_payment_bayi_installement'];
            $this->title = $this->settings['title'];
            $this->description = $this->settings['description'];
            $this->enabled = $this->settings['enabled'];
            $this->order_button_text = $this->settings['button_title'];
            add_action('init', array(&$this, 'check_weepay_response'));
            add_action('woocommerce_api_wc_gateway_weepay', array($this, 'check_weepay_response'));
            add_action('admin_notices', array($this, 'checksFields'));
            if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            } else {
                add_action('woocommerce_update_options_payment_gateways', array($this, 'process_admin_options'));
            }
            add_action('woocommerce_receipt_weepay', array($this, 'receipt_page'));
        }

        function checksFields()
        {
            global $woocommerce;

            if ($this->enabled == 'no') {
                return;
            }
        }

        function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'weepay-payment'),
                    'label' => __('Enable weepay Payment', 'weepay-payment'),
                    'type' => 'checkbox',
                    'default' => 'no',
                ),
                'title' => array(
                    'title' => __('Title', 'weepay-payment'),
                    'type' => 'text',
                    'description' => __('This message will show to the user during checkout.', 'weepay-payment'),
                    'default' => 'Kredi Kartı İle Öde',
                ),
                'description' => array(
                    'title' => __('Description.', 'weepay-payment'),
                    'type' => 'text',
                    'description' => __('This controls the description which the user sees during checkout.', 'weepay-payment'),
                    'default' => __('Pay with your credit card via weepay.', 'weepay-payment'),
                    'desc_tip' => true,
                ),
                'button_title' => array(
                    'title' => __('Checkout Button.', 'weepay-payment'),
                    'type' => 'text',
                    'description' => __('Checkout Button.', 'weepay-payment'),
                    'default' => __('Pay With Credit Card.', 'weepay-payment'),
                    'desc_tip' => true,
                ),
                'weepay_payment_bayi_id' => array(
                    'title' => __('weepay Dealer ID.', 'weepay-payment'),
                    'type' => 'text',
                    'desc_tip' => __('Dealer ID Given by weepay System.', 'weepay-payment'),
                ),
                'weepay_payment_bayi_api' => array(
                    'title' => __('weepay API Key.', 'weepay-payment'),
                    'type' => 'text',
                    'desc_tip' => __('API key Given by weepay System.', 'weepay-payment'),
                ),
                'weepay_payment_bayi_secret' => array(
                    'title' => __('weepay Secret Key.', 'weepay-payment'),
                    'type' => 'text',
                    'desc_tip' => __('Secret key Given by weepay System.', 'weepay-payment'),
                ),
                'weepay_payment_bayi_form_type' => array(
                    'title' => __('weepay Checkout Form Type.', 'weepay-payment'),
                    'type' => 'select',
                    'default' => 'popup',
                    'options' => array(
                        'popup' => __('Popup', 'weepay-payment'),
                        'responsive' => __('Responsive', 'weepay-payment'),
                    ),
                ),
                'weepay_payment_bayi_installement' => array(
                    'title' => __('Installments Options.', 'weepay-payment'),
                    'type' => 'select',
                    'default' => 'off',
                    'options' => array(
                        'off' => __('OFF', 'weepay-payment'),
                        'on' => __('ON', 'weepay-payment'),
                    ),
                ),
            );
        }

        public function admin_options()
        {
            $weepay_url = plugin_dir_url(__FILE__) . 'img/logo.png';
            echo '<img src="' . esc_html($weepay_url) . '" width="150px"/>';
            echo '<h2>weepay Ödeme ayarları</h2><hr/>';
            echo '<table class="form-table">';
            $this->generate_settings_html();
            echo '</table>';
        }
        private function setcookieSameSite($name, $value, $expire, $path, $domain, $secure, $httponly)
        {

            if (PHP_VERSION_ID < 70300) {

                setcookie($name, $value, $expire, "$path; samesite=None", $domain, $secure, $httponly);
            } else {
                setcookie($name, $value, [
                    'expires' => $expire,
                    'path' => $path,
                    'domain' => $domain,
                    'samesite' => 'None',
                    'secure' => $secure,
                    'httponly' => $httponly,
                ]);
            }
        }

        function CreateCheckOutFormweePay($order_id)
        {
            global $woocommerce;
            if (version_compare(get_bloginfo('version'), '4.5', '>=')) {
                wp_get_current_user();
            } else {
                get_currentuserinfo();
            }

            $order = new WC_Order($order_id);
            $customerCart = $woocommerce->cart->get_cart();
            $ProductsBasket = $this->generateBasketItems($customerCart, $order);

            $ip = $_SERVER['REMOTE_ADDR'];
            $siteLanguage = get_locale();
            $user_meta = $order->get_user_id();
            $siteLang = explode('_', get_locale());
            $locale = ($siteLang[0] == "tr") ? "tr" : "en";
            $phone = !empty($order->get_billing_phone()) ? $order->get_billing_phone() : 'NOT PROVIDED';
            $email = !empty($order->get_billing_email()) ? $order->get_billing_email() : 'NOT PROVIDED';
            $city_buyer = WC()->countries->states[$order->get_billing_country()][$order->get_billing_state()];

            $city = !empty($city_buyer) ? $city_buyer : 'NOT PROVIDED';

            $order_amount = $order->get_total();
            $currency = $order->get_currency();

            $city1 = isset(WC()->countries->states[$order->get_shipping_country()][$order->get_shipping_state()]) ? WC()->countries->states[$order->get_shipping_country()][$order->get_shipping_state()] : '';
            $city2 = isset(WC()->countries->states[$order->get_shipping_state()]) ? WC()->countries->states[$order->get_shipping_state()] : '';
            $countryShipping = isset(WC()->countries->countries[$order->get_shipping_country()]) ? WC()->countries->countries[$order->get_shipping_country()] : '';
            $cityShipping = $city1 . $city2;
            $wooCommerceCookieKey = 'wp_woocommerce_session_';
            foreach ($_COOKIE as $name => $value) {
                if (stripos($name, $wooCommerceCookieKey) === 0) {
                    $wooCommerceCookieKey = $name;
                }
            }
            $wooCommerceCookieKey = sanitize_text_field($wooCommerceCookieKey);
            $setCookie = $this->setcookieSameSite($wooCommerceCookieKey, $_COOKIE[$wooCommerceCookieKey], time() + 86400, "/", $_SERVER['SERVER_NAME'], true, true);

            if ($currency == 'TRY') {
                $currency = "TL";
            }
            $endPointUrl = "https://api.weepay.co/Payment/PaymentCreate";
            $weepayArray = array();
            $weepayArray['Auth'] = array(
                'bayiId' => $this->weepay_payment_bayi_id,
                'apiKey' => $this->weepay_payment_bayi_api,
                'secretKey' => $this->weepay_payment_bayi_secret,
            );
            $shippingName = $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name();
            $shippingAddress = $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2();
            $shippingZip = $order->get_shipping_postcode();

            $billingName = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            $billinAdress = $order->get_billing_address_1() . ' ' . $order->get_billing_address_2();
            $billingCity = WC()->countries->states[$order->get_billing_country()][$order->get_billing_state()];
            $billingCity = !empty($billingCity) ? $billingCity : 'NOT PROVIDED';
            $billingCountry = WC()->countries->countries[$order->get_billing_country()];
            $billingZip = $order->get_billing_postcode();

            $weepayArrayData = array(
                'Data' => array(
                    'callBackUrl' => add_query_arg('wc-api', 'WC_Gateway_Weepay', $order->get_checkout_order_received_url()),
                    'paidPrice' => round($order_amount, 2),
                    'locale' => $locale,
                    'ipAddress' => $_SERVER['REMOTE_ADDR'],
                    'orderId' => $order_id,
                    'description' => $order->get_customer_note(),
                    'currency' => $currency,
                    'paymentGroup' => 'PRODUCT',
                    'paymentSource' => 'WOOCOMMERCE|' . WOOCOMMERCE_VERSION . '|' . '1.0.3',
                    'channel' => 'Module',
                ),
                'Customer' => [
                    'customerId' => $user_meta,
                    'customerName' => $order->get_billing_first_name(),
                    'customerSurname' => $order->get_billing_last_name(),
                    'gsmNumber' => $phone,
                    'email' => $email,
                    'identityNumber' => '11111111111',
                    'city' => $city,
                    'country' => $billingCountry,
                ],
                'BillingAddress' => [
                    'contactName' => $billingName,
                    'address' => $billinAdress,
                    'city' => $billingCity,
                    'country' => $billingCountry,
                    'zipCode' => $billingZip,
                ],

                'ShippingAddress' => [
                    'contactName' => !empty($shippingName) ? $shippingName : $billingName,
                    'address' => !empty($shippingAddress) ? $shippingAddress : $billinAdress,
                    'city' => !empty($cityShipping) ? $cityShipping : $billingCity,
                    'country' => !empty($countryShipping) ? $countryShipping : $billingCountry,
                    'zipCode' => !empty($shippingZip) ? $shippingZip : $billingZip,
                ],
                'Products' => $ProductsBasket,

            );

            $resultArray = array_merge($weepayArray, $weepayArrayData);
            $endPointUrl = "https://api.weepay.co/Payment/PaymentCreate";

            $response = json_decode($this->curlPostExt(json_encode($resultArray), $endPointUrl, true));

            return $response;
        }

        public function generateBasketItems($items, $order)
        {

            $itemSize = count($items);

            if (!$itemSize) {

                return $this->calcProduct($order);
            }

            $keyNumber = 0;

            foreach ($items as $key => $item) {

                $productId = $item['product_id'];
                $product = wc_get_product($productId);
                $realPrice = $this->realPrice($item['line_subtotal'], $product->get_price());

                if ($realPrice && $realPrice != '0' && $realPrice != '0.0' && $realPrice != '0.00' && $realPrice != false) {

                    $basketItems[$keyNumber] = new stdClass();

                    $basketItems[$keyNumber]->productId = $item['product_id'];
                    $basketItems[$keyNumber]->productPrice = $this->priceParser(round($realPrice, 2));
                    $basketItems[$keyNumber]->name = $product->get_title();
                    ($product->is_virtual() || $product->is_downloadable()) ? $pType = "VIRTUAL" : $pType = "PHYSICAL";
                    $basketItems[$keyNumber]->itemType = $pType;

                    $keyNumber++;
                }
            }

            $shipping = $order->get_total_shipping() + $order->get_shipping_tax();

            if ($shipping && $shipping != '0' && $shipping != '0.0' && $shipping != '0.00' && $shipping != false) {

                $endKey = count($basketItems);

                $basketItems[$endKey] = new stdClass();

                $basketItems[$endKey]->productId = 11;
                $basketItems[$endKey]->productPrice = $this->priceParser($shipping);
                $basketItems[$endKey]->name = 'Cargo';
                $basketItems[$endKey]->itemType = 'PHYSICAL';
            }

            return $basketItems;
        }
        public function realPrice($salePrice, $regularPrice)
        {

            if (empty($salePrice)) {

                $salePrice = $regularPrice;
            }

            return $salePrice;
        }
        public function priceParser($price)
        {

            if (strpos($price, ".") === false) {
                return $price . ".0";
            }
            $subStrIndex = 0;
            $priceReversed = strrev($price);
            for ($i = 0; $i < strlen($priceReversed); $i++) {
                if (strcmp($priceReversed[$i], "0") == 0) {
                    $subStrIndex = $i + 1;
                } else if (strcmp($priceReversed[$i], ".") == 0) {
                    $priceReversed = "0" . $priceReversed;
                    break;
                } else {
                    break;
                }
            }

            return strrev(substr($priceReversed, $subStrIndex));
        }

        public function calcProduct($order)
        {

            $keyNumber = 0;

            $basketItems[$keyNumber] = new stdClass();

            $basketItems[$keyNumber]->productId = $order->get_id();
            $basketItems[$keyNumber]->productPrice = $this->priceParser(round($order->get_total(), 2));
            $basketItems[$keyNumber]->name = 'Woocommerce - Custom Order Page';
            $basketItems[$keyNumber]->itemType = 'PHYSICAL';

            return $basketItems;
        }
        function receipt_page($orderid)
        {
            global $woocommerce;
            $error_message = false;
            $order = new WC_Order($orderid);
            $status = $order->get_status();
            $showtotal = $order->get_total();
            $currency = $order->get_currency();
            $form_class = $this->weepay_payment_bayi_form_type;
            $text_credit_card = __('Credit Cart Form', 'weepay-payment');
            $checkOutFormData = $this->CreateCheckOutFormweePay($orderid);

            if ($checkOutFormData->status == 'failure') {

                $error_message = $checkOutFormData->message;
            } else {

                $CheckoutForm = $checkOutFormData->CheckoutFormData;
            }

            include dirname(__FILE__) . '/weepay.php';
        }

        public function check_weepay_response()
        {

            global $woocommerce;

            try {
                $paymentStatus = wc_clean($_POST['paymentStatus']);
                if ($paymentStatus == true) {
                    $orderId = wc_clean($_POST['orderId']);
                    $order = new WC_Order($orderId);
                    $paymentId = wc_clean($_POST['paymentId']);
                    $Result = $this->GetOrderData($paymentId);
                    $installment = $Result->data->installement;
                    if ($Result->paymentStatus == 'SUCCESS') {
                        if ($installment > 1) {
                            $order_amount = $order->get_total();
                            $order_amount = round($order_amount, 2);
                            $installment_fee = $Result->data->price - $order_amount;
                            $order_fee = new stdClass();
                            $order_fee->id = 'Installment Fee';
                            $order_fee->name = __('Installment Fee', 'weepay-payment');
                            $order_fee->amount = $installment_fee;
                            $order_fee->taxable = false;
                            $order_fee->tax = 0;
                            $order_fee->tax_data = array();
                            $order_fee->tax_class = '';
                            $fee_id = $order->add_fee($order_fee);
                            $order->calculate_totals(true);
                            update_post_meta($order_id, 'weepay_installment_number', esc_sql($installment));
                            update_post_meta($order_id, 'weepay_installment_fee', $installment_fee);
                        }

                        $orderMessage = 'Payment ID: ' . $paymentId;
                        $order->add_order_note($orderMessage, 0, true);
                        $order->payment_complete();
                        WC()->cart->empty_cart();
                        $woocommerce->cart->empty_cart();

                        $checkoutOrderUrl = $order->get_checkout_order_received_url();

                        $redirectUrl = add_query_arg(array('msg' => 'Thank You', 'type' => 'woocommerce-message'), $checkoutOrderUrl);
                        return wp_redirect($redirectUrl);
                    } else {

                        $errorMessage = 'FAILURE';
                        throw new \Exception($errorMessage);
                    }
                } else if (isset($_POST['message'])) {
                    $message = sanitize_text_field($_POST['message']);
                    $errorMessage = isset($message) ? $requestResponse->errorMessage : 'Failed';
                    throw new \Exception($errorMessage);
                }
            } catch (\Exception $th) {
                $message = $th->getMessage();
                $message = sanitize_text_field($_POST['message']);
                $message = !empty($message) ? $message : "Invalid Request";
                $order = new WC_Order($_POST['orderId']);
                $order->update_status('failed', sprintf(__('weepay payment failed', 'weepay-payment'), $message));
                $order->add_order_note($message, 0, true);
                wc_add_notice(__($message, 'weepay-payment'), 'error');
                $redirectUrl = $woocommerce->cart->get_cart_url();
                return wp_redirect($redirectUrl);
            }
        }

        function GetOrderData($id_order)
        {
            $weepayArray = array();
            $weepayArray['Auth'] = array(
                'bayiId' => $this->weepay_payment_bayi_id,
                'apiKey' => $this->weepay_payment_bayi_api,
                'secretKey' => $this->weepay_payment_bayi_secret,
            );
            $weepayArray['Data'] = array(
                'paymentId' => $id_order,
            );
            $weepayEndPoint = "https://api.weepay.co/GetPayment/Detail";
            return json_decode($this->curlPostExt(json_encode($weepayArray), $weepayEndPoint, true));
        }

        function curlPostExt($data, $url, $json = false)
        {
            $response = wp_remote_post($url, array(
                'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
                'body' => $data,
                'method' => 'POST',
                'data_format' => 'body',
            ));
            $body = wp_remote_retrieve_body($response);

            return $body;
        }
        function process_payment($order_id)
        {
            global $woocommerce;
            $order = new WC_Order($order_id);

            if (version_compare(WOOCOMMERCE_VERSION, '2.1.0', '>=')) {
                /* 2.1.0 */
                $checkout_payment_url = $order->get_checkout_payment_url(true);
            } else {
                /* 2.0.0 */
                $checkout_payment_url = get_permalink(get_option('woocommerce_pay_page_id'));
            }

            return array(
                'result' => 'success',
                'redirect' => $checkout_payment_url,
            );
        }
    }
}

add_filter('woocommerce_payment_gateways', 'woocommerce_add_weepay_checkout_form_gateway');

function woocommerce_add_weepay_checkout_form_gateway($methods)
{
    $methods[] = 'WC_Gateway_Weepay';
    return $methods;
}

function weepay_checkout_form_load_plugin_textdomain()
{
    load_plugin_textdomain('weepay-payment', false, plugin_basename(dirname(__FILE__)) . '/i18n/languages/');
}

add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

add_action('plugins_loaded', 'weepay_checkout_form_load_plugin_textdomain');
