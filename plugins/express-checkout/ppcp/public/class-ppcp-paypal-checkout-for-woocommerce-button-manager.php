<?php

/**
 * @package    PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager
 * @subpackage PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager/public
 * @author     PayPal <mbjwebdevelopment@gmail.com>
 */
class PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager {

    private $plugin_name;
    private $version;
    public $request;
    public $woocommerce_ppcp_paypal_checkout_settings;
    public $checkout_details;
    public $procceed = 1;
    public $reject = 2;
    public $retry = 3;
    protected static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->plugin_name = 'express-checkout';
        $this->version = EXPRESS_CHECKOUT_VERSION;
        $this->checkout_details = '';
        if (empty($this->woocommerce_ppcp_paypal_checkout_settings)) {
            $this->woocommerce_ppcp_paypal_checkout_settings = get_option('woocommerce_ppcp_paypal_checkout_settings', true);
        }

        $this->get_properties();
        if ($this->is_valid_for_use() === true) {
            if (!has_action('woocommerce_api_' . strtolower('PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager'))) {
                add_action('woocommerce_api_' . strtolower('PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager'), array($this, 'handle_wc_api'));
            }
            include_once EXPRESS_CHECKOUT_PATH . '/ppcp/includes/class-ppcp-paypal-checkout-for-woocommerce-request.php';
            $this->request = new PPCP_Paypal_Checkout_For_Woocommerce_Request();
            $this->ppcp_add_hooks();
        }
    }

    public function get_properties() {
        $this->title = __('PayPal Checkout', 'express-checkout');
        $this->enabled = 'yes' === $this->ppcp_get_settings('enabled', 'no');
        $this->testmode = 'yes' === $this->ppcp_get_settings('testmode', 'no');
        if ($this->testmode) {
            $this->client_id = $this->ppcp_get_settings('sandbox_client_id');
            $this->secret = $this->ppcp_get_settings('sandbox_api_secret');
            $this->webhook_id = 'ppcp_sandbox_webhook_id';
            $this->client_token = get_transient('ppcp_sandbox_client_token');
        } else {
            $this->client_id = $this->ppcp_get_settings('api_client_id');
            $this->secret = $this->ppcp_get_settings('api_secret');
            $this->webhook_id = 'ppcp_live_webhook_id';
            $this->client_token = get_transient('ppcp_client_token');
        }
        $this->ppcp_currency_list = array('AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'INR', 'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD');
        $this->ppcp_currency = in_array(get_woocommerce_currency(), $this->ppcp_currency_list) ? get_woocommerce_currency() : 'USD';
        $this->enable_product_button = 'yes' === $this->ppcp_get_settings('enable_product_button', 'yes');
        $this->enable_cart_button = 'yes' === $this->ppcp_get_settings('enable_cart_button', 'yes');
        $this->enable_checkout_button = 'yes' === $this->ppcp_get_settings('enable_checkout_button', 'yes');
        $this->enable_checkout_button_top = 'yes' === $this->ppcp_get_settings('enable_checkout_button_top', 'no');
        $this->cart_button_top_position = 'yes' === $this->ppcp_get_settings('cart_button_top_position', 'no');
        $this->enable_mini_cart_button = 'yes' === $this->ppcp_get_settings('enable_mini_cart_button', 'yes');
        $this->order_review_page_title = $this->ppcp_get_settings('order_review_page_title', 'Confirm your PayPal order');
        $this->order_review_page_enable_coupons = 'yes' === $this->ppcp_get_settings('order_review_page_enable_coupons', 'no');
        $this->order_review_page_description = $this->ppcp_get_settings('order_review_page_description', __("<strong>You're almost done!</strong><br>Review your information before you place your order.", 'express-checkout'));
        $this->paymentaction = $this->ppcp_get_settings('paymentaction', 'capture');
        $this->advanced_card_payments = 'yes' === $this->ppcp_get_settings('enable_advanced_card_payments', 'no');
        if (ppcp_is_advanced_cards_available() === false) {
            $this->advanced_card_payments = false;
        }
        $this->set_billing_address = is_user_logged_in() ? false : true;
        $this->threed_secure_contingency = $this->ppcp_get_settings('3d_secure_contingency', 'SCA_WHEN_REQUIRED');
        $this->enabled_pay_later_messaging = 'yes' === $this->ppcp_get_settings('enabled_pay_later_messaging', 'no');
        $this->pay_later_messaging_page_type = $this->ppcp_get_settings('pay_later_messaging_page_type', array('home', 'category', 'product', 'cart', 'payment'));
        if (empty($this->pay_later_messaging_page_type)) {
            $this->enabled_pay_later_messaging = false;
        }
        $this->AVSCodes = array("A" => "Address Matches Only (No ZIP)",
            "B" => "Address Matches Only (No ZIP)",
            "C" => "This tranaction was declined.",
            "D" => "Address and Postal Code Match",
            "E" => "This transaction was declined.",
            "F" => "Address and Postal Code Match",
            "G" => "Global Unavailable - N/A",
            "I" => "International Unavailable - N/A",
            "N" => "None - Transaction was declined.",
            "P" => "Postal Code Match Only (No Address)",
            "R" => "Retry - N/A",
            "S" => "Service not supported - N/A",
            "U" => "Unavailable - N/A",
            "W" => "Nine-Digit ZIP Code Match (No Address)",
            "X" => "Exact Match - Address and Nine-Digit ZIP",
            "Y" => "Address and five-digit Zip match",
            "Z" => "Five-Digit ZIP Matches (No Address)");

        $this->CVV2Codes = array(
            "E" => "N/A",
            "M" => "Match",
            "N" => "No Match",
            "P" => "Not Processed - N/A",
            "S" => "Service Not Supported - N/A",
            "U" => "Service Unavailable - N/A",
            "X" => "No Response - N/A"
        );
    }

    public function ppcp_add_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));

        if ($this->enable_product_button) {
            add_action('woocommerce_after_add_to_cart_form', array($this, 'display_paypal_button_product_page'), 1);
        }
        if ($this->enable_cart_button) {
            add_action('woocommerce_proceed_to_checkout', array($this, 'display_paypal_button_cart_page'), 11);
            if ($this->cart_button_top_position) {
                add_action('woocommerce_before_cart_table', array($this, 'display_paypal_button_cart_page_top'));
            }
        }
        if ($this->enable_checkout_button) {
            add_action('display_paypal_button_checkout_page', array($this, 'display_paypal_button_checkout_page'));
        }
        add_action('init', array($this, 'init'));
        add_filter('clean_url', array($this, 'ppcp_clean_url'));
        add_action('wp_loaded', array($this, 'ppcp_session_manager'), 999);
        add_action('wp_head', array($this, 'ppcp_add_header_meta'), 0);
        add_filter('the_title', array($this, 'ppcp_endpoint_page_titles'));
        add_action('woocommerce_cart_emptied', array($this, 'maybe_clear_session_data'));
        add_action('woocommerce_checkout_init', array($this, 'ppcp_checkout_init'));
        add_action('woocommerce_available_payment_gateways', array($this, 'maybe_disable_other_gateways'));
        add_filter('woocommerce_default_address_fields', array($this, 'filter_default_address_fields'));
        add_filter('woocommerce_billing_fields', array($this, 'filter_billing_fields'));
        add_action('woocommerce_checkout_process', array($this, 'copy_checkout_details_to_post'));
        add_action('woocommerce_cart_shipping_packages', array($this, 'maybe_add_shipping_information'));
        add_filter('body_class', array($this, 'ppcp_add_class_order_review_page'));
        add_filter('woocommerce_coupons_enabled', array($this, 'ppcp_woocommerce_coupons_enabled'), 999, 1);
        add_action('woocommerce_before_checkout_form', array($this, 'ppcp_order_review_page_description'), 9);
        add_action('woocommerce_order_status_processing', array($this, 'ppcp_capture_payment'));
        add_action('woocommerce_order_status_completed', array($this, 'ppcp_capture_payment'));
        add_action('woocommerce_order_status_cancelled', array($this, 'ppcp_cancel_authorization'));
        add_action('woocommerce_order_status_refunded', array($this, 'ppcp_cancel_authorization'));
        add_filter('woocommerce_order_actions', array($this, 'ppcp_add_capture_charge_order_action'));
        add_action('woocommerce_order_action_ppcp_capture_charge', array($this, 'ppcp_maybe_capture_charge'));
        add_action('woocommerce_before_checkout_form', array($this, 'ppcp_update_checkout_field_details'));
        add_action('woocommerce_review_order_before_submit', array($this, 'ppcp_cancel_button'));
        add_action('wp_loaded', array($this, 'ppcp_create_webhooks'));
        add_action('woocommerce_pay_order_after_submit', array($this, 'ppcp_add_order_id'));
        add_action('wp_loaded', array($this, 'ppcp_prevent_add_to_cart_woo_action'), 1);
        if ($this->enable_checkout_button === true && $this->enable_checkout_button_top === true) {
            add_action('woocommerce_before_checkout_form', array($this, 'display_paypal_button_top_checkout_page'), 5);
        }
    }

    public function enqueue_scripts() {
        if (is_checkout() && $this->advanced_card_payments) {
            delete_transient('ppcp_sandbox_client_token');
            delete_transient('ppcp_client_token');
            $this->request->get_genrate_token();
            $this->get_properties();
        }
        $this->ppcp_paypal_button_style_properties();
        $ppcp_js_arg = array();
        $ppcp_js_arg['client-id'] = $this->client_id;
        $ppcp_js_arg['currency'] = $this->ppcp_currency;
        if (!isset($this->disable_funding['venmo'])) {
            $ppcp_js_arg['enable-funding'] = 'venmo';
        }
        if ($this->disable_funding !== false && count($this->disable_funding) > 0) {
            $ppcp_js_arg['disable-funding'] = implode(',', $this->disable_funding);
        }
        if ($this->testmode) {
            if (is_user_logged_in() && WC()->customer && WC()->customer->get_billing_country() && 2 === strlen(WC()->customer->get_billing_country())) {
                $ppcp_js_arg['buyer-country'] = WC()->customer->get_billing_country();
            }
        }


        $page = '';
        $is_pay_page = 'no';
        $button_selector = array();
        if (is_product() && $this->enable_product_button) {
            $page = 'product';
            $button_selector['ppcp_product_page'] = '#ppcp_product';
        } elseif (is_cart() && !WC()->cart->is_empty() && $this->enable_cart_button) {
            $page = 'cart';
            if ($this->cart_button_top_position) {
                $button_selector['ppcp_cart_top'] = '#ppcp_cart_top';
            }
            $button_selector['ppcp_cart'] = '#ppcp_cart';
        } elseif (is_checkout_pay_page()) {
            $page = 'checkout';
            $button_selector['ppcp_checkout'] = '#ppcp_checkout';
            $is_pay_page = 'yes';
        } elseif (is_checkout() && $this->enable_checkout_button) {
            $page = 'checkout';
            $button_selector['ppcp_checkout'] = '#ppcp_checkout';
            if ($this->enable_checkout_button_top === true) {
                $button_selector['ppcp_checkout_top'] = '#ppcp_checkout_top';
            }
        }
        $ppcp_js_arg['commit'] = ( $page === 'checkout' ) ? 'true' : 'false';
        $ppcp_js_arg['intent'] = ( $this->paymentaction === 'capture' ) ? 'capture' : 'authorize';
        $ppcp_js_arg['locale'] = get_button_locale_code();
        $components = array("buttons");
        if (is_checkout() && $this->advanced_card_payments) {
            array_push($components, "hosted-fields");
        }
        if ($this->enabled_pay_later_messaging) {
            array_push($components, 'messages');
        }
        if (!empty($components)) {
            $ppcp_js_arg['components'] = implode(',', $components);
        }
        $js_url = add_query_arg($ppcp_js_arg, 'https://www.paypal.com/sdk/js');
        wp_register_script('ppcp-checkout-js', $js_url, array(), null, false);
        wp_register_script('ppcp-paypal-checkout-for-woocommerce-public', EXPRESS_CHECKOUT_ASSET_URL . 'ppcp/public/js/ppcp-paypal-checkout-for-woocommerce-public.js', array('jquery'), time(), false);
        $this->style_layout = is_product() ? 'horizontal' : 'vertical';
        wp_localize_script('ppcp-paypal-checkout-for-woocommerce-public', 'ppcp_manager', array(
            'style_color' => $this->style_color,
            'style_shape' => $this->style_shape,
            'style_label' => $this->style_label,
            'style_layout' => $this->style_layout,
            'style_tagline' => $this->style_tagline,
            'page' => $page,
            'is_pay_page' => $is_pay_page,
            'checkout_url' => wc_get_checkout_url(),
            'display_order_page' => add_query_arg(array('ppcp_action' => 'display_order_page', 'utm_nooverride' => '1'), WC()->api_request_url('PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager')),
            'cc_capture' => add_query_arg(array('ppcp_action' => 'cc_capture', 'utm_nooverride' => '1'), WC()->api_request_url('PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager')),
            'create_order_url' => add_query_arg(array('ppcp_action' => 'create_order', 'utm_nooverride' => '1', 'from' => is_checkout_pay_page() ? 'pay_page' : $page), WC()->api_request_url('PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager')),
            'cancel_url' => wc_get_cart_url(),
            'cart_total' => WC()->cart->total,
            'paymentaction' => $this->paymentaction,
            'advanced_card_payments' => ($this->advanced_card_payments === true) ? 'yes' : 'no',
            'threed_secure_contingency' => $this->threed_secure_contingency,
            'woocommerce_process_checkout' => wp_create_nonce('woocommerce-process_checkout'),
            'button_selector' => $button_selector
                )
        );
        if (is_checkout() && empty($this->checkout_details)) {
            wp_enqueue_script('ppcp-paypal-checkout-for-woocommerce-order-review', EXPRESS_CHECKOUT_ASSET_URL . 'ppcp/public/js/ppcp-paypal-checkout-for-woocommerce-order-review.js', array('jquery'), $this->version, false);
        } elseif (is_checkout() && !empty($this->checkout_details)) {
            wp_enqueue_script('ppcp-paypal-checkout-for-woocommerce-order-review', EXPRESS_CHECKOUT_ASSET_URL . 'ppcp/public/js/ppcp-paypal-checkout-for-woocommerce-order-capture.js', array('jquery'), $this->version, false);
        }
    }

    public function enqueue_styles() {
        wp_enqueue_style('ppcp-paypal-checkout-for-woocommerce-public', plugin_dir_url(__FILE__) . 'css/ppcp-paypal-checkout-for-woocommerce-public.css', array(), $this->version, 'all');
    }

    public function is_valid_for_use() {
        if (!empty($this->client_id) && !empty($this->secret) && $this->enabled) {
            return true;
        }
        return false;
    }

    public function display_paypal_button_cart_page() {
        wp_enqueue_script('ppcp-checkout-js');
        wp_enqueue_script('ppcp-paypal-checkout-for-woocommerce-public');
        echo '<div class="ppcp-button-container"><div id="ppcp_cart"></div><div class="ppcp-proceed-to-checkout-button-separator">&mdash; ' . __('OR', 'express-checkout') . ' &mdash;</div></div>';
    }

    public function display_paypal_button_cart_page_top() {
        if (class_exists('WC_Subscriptions_Cart') && WC_Subscriptions_Cart::cart_contains_subscription()) {
            return false;
        }
        if (WC()->cart->needs_payment()) {
            wp_enqueue_script('ppcp-checkout-js');
            wp_enqueue_script('ppcp-paypal-checkout-for-woocommerce-public');
            wp_enqueue_style("ppcp-paypal-checkout-for-woocommerce-public");
            echo '<div class="ppcp-button-container"><div id="ppcp_cart_top"></div></div>';
        }
    }

    public function display_paypal_button_product_page() {
        global $product;
        if (!is_product() || !$product->is_in_stock() || $product->is_type('external') || $product->is_type('grouped')) {
            return;
        }
        wp_enqueue_script('ppcp-checkout-js');
        wp_enqueue_script('ppcp-paypal-checkout-for-woocommerce-public');
        echo '<div class="ppcp-button-container"><div id="ppcp_product"></div></div>';
    }

    public function display_paypal_button_checkout_page() {
        if (has_active_session() === false) {
            wp_enqueue_script('ppcp-checkout-js');
            wp_enqueue_script('ppcp-paypal-checkout-for-woocommerce-public');
            echo '<div class="ppcp-button-container"><div id="ppcp_checkout"></div><div class="ppcp-proceed-to-checkout-button-separator checkout_cc_separator">&mdash;&mdash; ' . __('OR', 'express-checkout') . ' &mdash;&mdash;</div></div>';
        }
    }

    public function display_paypal_button_top_checkout_page() {
        if (has_active_session() === false) {
            wp_enqueue_script('ppcp-checkout-js');
            wp_enqueue_script('ppcp-paypal-checkout-for-woocommerce-public');
            wp_enqueue_style("ppcp-paypal-checkout-for-woocommerce-public");
            echo apply_filters('ppcp_checkout_top_html', '<div class="ppcp-button-container"><fieldset><legend class="express-title">' . __('PayPal Checkout', 'woo-paypal-gateway') . '</legend><div class="payment_method_ppcp_paypal_checkout"><div class="express_payment_method_ppcp"><div id="ppcp_checkout_top"></div></div></div></fieldset><span class="express-divider">OR</span></div>');
        }
    }

    public function ppcp_add_header_meta() {
        if ($this->is_valid_for_use() === true) {
            echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
        }
    }

    public function ppcp_get_settings($key, $bool = false) {
        if (!empty($this->woocommerce_ppcp_paypal_checkout_settings)) {
            if (!empty($this->woocommerce_ppcp_paypal_checkout_settings[$key])) {
                return $this->woocommerce_ppcp_paypal_checkout_settings[$key];
            } else {
                return $bool;
            }
        } else {
            return $bool;
        }
    }

    public function ppcp_endpoint_page_titles($title) {
        if (!is_admin() && is_main_query() && in_the_loop() && is_page() && is_checkout() && !empty($this->checkout_details)) {
            $title = $this->order_review_page_title;
            remove_filter('the_title', array($this, 'ppcp_endpoint_page_titles'));
        }
        return $title;
    }

    public function handle_wc_api() {
        if (!empty($_GET['ppcp_action'])) {
            switch ($_GET['ppcp_action']) {
                case "webhook_handler":
                    $this->ppcp_handle_webhook_request();
                    ob_clean();
                    header('HTTP/1.1 200 OK');
                    exit();
                    break;
                case "cancel_order":
                    unset(WC()->session->ppcp_session);
                    wp_redirect(wc_get_cart_url());
                    exit();
                    break;
                case "create_order":
                    if (isset($_GET['from']) && 'pay_page' === $_GET['from']) {
                        $woo_order_id = $_POST['woo_order_id'];
                        ppcp_set_session('ppcp_woo_order_id', $woo_order_id);
                        $this->request->ppcp_create_order_request($woo_order_id);
                        exit();
                    } elseif (isset($_GET['from']) && 'checkout' === $_GET['from']) {

                        if (isset($_POST) && !empty($_POST)) {
                            add_action('woocommerce_after_checkout_validation', array($this, 'maybe_start_checkout'), 10, 2);
                            WC()->checkout->process_checkout();
                        } else {
                            $_GET['from'] = 'cart';
                            $this->request->ppcp_create_order_request();
                            exit();
                        }
                    } elseif (isset($_GET['from']) && 'product' === $_GET['from']) {
                        try {
                            PPCP_Paypal_Checkout_For_Woocommerce_Product::ppcp_add_to_cart_action();
                            $this->request->ppcp_create_order_request();
                            exit();
                        } catch (Exception $ex) {
                            
                        }
                    } else {
                        $this->request->ppcp_create_order_request();
                        exit();
                    }
                    break;
                case "display_order_page":
                    $this->ppcp_display_order_page();
                    break;
                case "cc_capture":
                    wc_clear_notices();
                    ppcp_set_session('ppcp_paypal_order_id', wc_clean($_GET['paypal_order_id']));
                    $this->ppcp_cc_capture();
                    break;
            }
        }
    }

    public function ppcp_checkout_init($checkout) {
        if (empty($this->checkout_details) || (isset($_GET['from']) && 'checkout' === $_GET['from'])) {
            return;
        }
        //remove_action('woocommerce_checkout_billing', array($checkout, 'checkout_form_billing'));
        //remove_action('woocommerce_checkout_shipping', array($checkout, 'checkout_form_shipping'));
        add_action('woocommerce_checkout_billing', array($this, 'paypal_billing_details'), 9);
        if (true === WC()->cart->needs_shipping_address()) {
            add_action('woocommerce_checkout_shipping', array($this, 'paypal_shipping_details'), 9);
        }
    }

    public function paypal_billing_details() {
        if (empty($this->checkout_details)) {
            return false;
        }
        ?>
        <div class="ppcp_billing_details">
        <?php if (wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()) : ?>
                <h3><?php esc_html_e('Billing &amp; Shipping', 'express-checkout'); ?>&nbsp;&nbsp;&nbsp;<a class="ppcp_edit_billing_address"><?php _e('Edit', 'express-checkout'); ?></a></h3>
            <?php else : ?>
                <h3><?php esc_html_e('Billing details', 'express-checkout'); ?>&nbsp;&nbsp;&nbsp;<a class="ppcp_edit_billing_address"><?php _e('Edit', 'express-checkout'); ?></a></h3>
            <?php
            endif;
            echo WC()->countries->get_formatted_address($this->get_mapped_billing_address($this->checkout_details));
            ?>

        </div>
        <?php
    }

    public function paypal_shipping_details() {
        if (empty($this->checkout_details)) {
            return false;
        }
        ?>
        <div class="ppcp_shipping_details">
            <h3><?php _e('Shipping details', 'express-checkout'); ?>&nbsp;&nbsp;&nbsp;<a class="ppcp_edit_shipping_address"><?php _e('Edit', 'express-checkout'); ?></a></h3>
        <?php
        echo WC()->countries->get_formatted_address($this->get_mapped_shipping_address($this->checkout_details));
        echo '</div>';
    }

    public function get_mapped_billing_address($checkout_details) {
        if (empty($this->checkout_details->payer)) {
            return array();
        }
        $phone = '';
        if (!empty($checkout_details->payer->phone->phone_number->national_number)) {
            $phone = $checkout_details->payer->phone->phone_number->national_number;
        } elseif (!empty($_POST['billing_phone'])) {
            $phone = wc_clean($_POST['billing_phone']);
        }
        $billing_address = array();
        $billing_address['first_name'] = !empty($this->checkout_details->payer->name->given_name) ? $this->checkout_details->payer->name->given_name : '';
        $billing_address['last_name'] = !empty($this->checkout_details->payer->name->surname) ? $this->checkout_details->payer->name->surname : '';
        $billing_address['company'] = !empty($this->checkout_details->payer->business_name) ? $this->checkout_details->payer->business_name : '';
        if (!empty($this->checkout_details->payer->address->address_line_1) && !empty($this->checkout_details->payer->address->postal_code)) {
            $billing_address['address_1'] = !empty($this->checkout_details->payer->address->address_line_1) ? $this->checkout_details->payer->address->address_line_1 : '';
            $billing_address['address_2'] = !empty($this->checkout_details->payer->address->address_line_2) ? $this->checkout_details->payer->address->address_line_2 : '';
            $billing_address['city'] = !empty($this->checkout_details->payer->address->admin_area_2) ? $this->checkout_details->payer->address->admin_area_2 : '';
            $billing_address['state'] = !empty($this->checkout_details->payer->address->admin_area_1) ? $this->checkout_details->payer->address->admin_area_1 : '';
            $billing_address['postcode'] = !empty($this->checkout_details->payer->address->postal_code) ? $this->checkout_details->payer->address->postal_code : '';
            $billing_address['country'] = !empty($this->checkout_details->payer->address->country_code) ? $this->checkout_details->payer->address->country_code : '';
            $billing_address['phone'] = $phone;
            $billing_address['email'] = !empty($this->checkout_details->payer->email_address) ? $this->checkout_details->payer->email_address : '';
        } else {
            $billing_address['address_1'] = !empty($this->checkout_details->purchase_units[0]->shipping->address->address_line_1) ? $this->checkout_details->purchase_units[0]->shipping->address->address_line_1 : '';
            $billing_address['address_2'] = !empty($this->checkout_details->purchase_units[0]->shipping->address->address_line_2) ? $this->checkout_details->purchase_units[0]->shipping->address->address_line_2 : '';
            $billing_address['city'] = !empty($this->checkout_details->purchase_units[0]->shipping->address->admin_area_2) ? $this->checkout_details->purchase_units[0]->shipping->address->admin_area_2 : '';
            $billing_address['state'] = !empty($this->checkout_details->purchase_units[0]->shipping->address->admin_area_1) ? $this->checkout_details->purchase_units[0]->shipping->address->admin_area_1 : '';
            $billing_address['postcode'] = !empty($this->checkout_details->purchase_units[0]->shipping->address->postal_code) ? $this->checkout_details->purchase_units[0]->shipping->address->postal_code : '';
            $billing_address['country'] = !empty($this->checkout_details->purchase_units[0]->shipping->address->country_code) ? $this->checkout_details->purchase_units[0]->shipping->address->country_code : '';
            $billing_address['phone'] = $phone;
            $billing_address['email'] = !empty($this->checkout_details->payer->email_address) ? $this->checkout_details->payer->email_address : '';
        }
        return $billing_address;
    }

    public function get_mapped_shipping_address() {
        if (empty($this->checkout_details->purchase_units[0]) || empty($this->checkout_details->purchase_units[0]->shipping)) {
            return array();
        }
        if (!empty($this->checkout_details->purchase_units[0]->shipping->name->full_name)) {
            $name = explode(' ', $this->checkout_details->purchase_units[0]->shipping->name->full_name);
            $first_name = array_shift($name);
            $last_name = implode(' ', $name);
        } else {
            $first_name = '';
            $last_name = '';
        }
        $result = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'address_1' => !empty($this->checkout_details->purchase_units[0]->shipping->address->address_line_1) ? $this->checkout_details->purchase_units[0]->shipping->address->address_line_1 : '',
            'address_2' => !empty($this->checkout_details->purchase_units[0]->shipping->address->address_line_2) ? $this->checkout_details->purchase_units[0]->shipping->address->address_line_2 : '',
            'city' => !empty($this->checkout_details->purchase_units[0]->shipping->address->admin_area_2) ? $this->checkout_details->purchase_units[0]->shipping->address->admin_area_2 : '',
            'state' => !empty($this->checkout_details->purchase_units[0]->shipping->address->admin_area_1) ? $this->checkout_details->purchase_units[0]->shipping->address->admin_area_1 : '',
            'postcode' => !empty($this->checkout_details->purchase_units[0]->shipping->address->postal_code) ? $this->checkout_details->purchase_units[0]->shipping->address->postal_code : '',
            'country' => !empty($this->checkout_details->purchase_units[0]->shipping->address->country_code) ? $this->checkout_details->purchase_units[0]->shipping->address->country_code : '',
        );
        if (!empty($this->checkout_details->payer->business_name)) {
            $result['company'] = $this->checkout_details->payer->business_name;
        }
        return $result;
    }

    public function account_registration() {
        $checkout = WC()->checkout();
        if (!is_user_logged_in() && $checkout->enable_signup) {
            if ($checkout->enable_guest_checkout) {
                ?>
                    <p class="form-row form-row-wide create-account">
                        <input class="input-checkbox" id="createaccount" <?php checked(( true === $checkout->get_value('createaccount') || ( true === apply_filters('woocommerce_create_account_default_checked', false) )), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e('Create an account?', 'express-checkout'); ?></label>
                    </p>
                <?php
            }
            if (!empty($checkout->checkout_fields['account'])) {
                ?>
                    <div class="create-account">
                        <p><?php _e('Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'express-checkout'); ?></p>
                <?php foreach ($checkout->checkout_fields['account'] as $key => $field) : ?>
                            <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                        <?php endforeach; ?>
                        <div class="clear"></div>
                    </div>
                <?php
            }
        }
    }

    public function maybe_disable_other_gateways($gateways) {
        if ($this->enable_checkout_button === false && $this->advanced_card_payments === false && empty($this->checkout_details)) {
            if (isset($gateways['ppcp_paypal_checkout'])) {
                unset($gateways['ppcp_paypal_checkout']);
            }
        }

        if (!is_admin() && is_main_query() && in_the_loop() && is_page() && is_checkout() && !empty($this->checkout_details)) {
            foreach ($gateways as $id => $gateway) {
                if ('ppcp_paypal_checkout' !== $id) {
                    unset($gateways[$id]);
                }
            }
        }
        if (is_cart() || ( is_checkout() && !is_checkout_pay_page() )) {
            if (isset($gateways['ppcp_paypal_checkout']) && ( 0 >= WC()->cart->total )) {
                unset($gateways['ppcp_paypal_checkout']);
            }
        }
        return $gateways;
    }

    public function filter_billing_fields($billing_fields) {
        if (empty($this->checkout_details) || (isset($_GET['from']) && 'checkout' === $_GET['from'])) {
            return $billing_fields;
        }
        if ($this->enabled === false) {
            return $billing_fields;
        }
        if (array_key_exists('billing_phone', $billing_fields)) {
            $billing_fields['billing_phone']['required'] = false;
        }
        return $billing_fields;
    }

    public function filter_default_address_fields($fields) {
        if (empty($this->checkout_details)) {
            return $fields;
        }
        if ($this->enabled === false) {
            return $fields;
        }
        if (method_exists(WC()->cart, 'needs_shipping') && !WC()->cart->needs_shipping()) {
            $not_required_fields = array('first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'postcode', 'country');
            foreach ($not_required_fields as $not_required_field) {
                if (array_key_exists($not_required_field, $fields)) {
                    $fields[$not_required_field]['required'] = false;
                }
            }
        }
        if (array_key_exists('state', $fields)) {
            $fields['state']['required'] = false;
        }
        return $fields;
    }

    public function copy_checkout_details_to_post() {
        if (empty($this->checkout_details)) {
            $this->checkout_details = ppcp_get_session('ppcp_paypal_transaction_details', false);

            if (empty($this->checkout_details)) {
                if (!empty($_GET['paypal_order_id'])) {
                    $this->checkout_details = $this->request->ppcp_get_checkout_details($_GET['paypal_order_id']);
                }
            }

            if (empty($this->checkout_details)) {
                return;
            }
            ppcp_set_session('ppcp_paypal_transaction_details', $this->checkout_details);
        }
        if (!isset($_POST['payment_method']) || ( 'ppcp_paypal_checkout' !== $_POST['payment_method'] ) || empty($this->checkout_details)) {
            return;
        }
        $shipping_details = $this->get_mapped_shipping_address($this->checkout_details);
        $billing_details = $this->get_mapped_billing_address($this->checkout_details);
        $this->update_customer_addresses_from_paypal($shipping_details, $billing_details);
        /* if (empty($billing_details['address_1'])) {
          $_POST['ship_to_different_address'] = 0;
          $copyable_keys = array('first_name', 'last_name', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country');
          foreach ($copyable_keys as $copyable_key) {
          if (array_key_exists($copyable_key, $shipping_details)) {
          $billing_details[$copyable_key] = $shipping_details[$copyable_key];
          }
          }
          } else {
          $_POST['ship_to_different_address'] = 1;
          }
          foreach ($shipping_details as $key => $value) {
          $_POST['shipping_' . $key] = $value;
          }
          foreach ($billing_details as $key => $value) {
          $_POST['billing_' . $key] = $value;
          } */
    }

    public function maybe_add_shipping_information($packages) {
        if (empty($this->checkout_details) || (isset($_GET['from']) && 'checkout' === $_GET['from'])) {
            return $packages;
        }
        $destination = $this->get_mapped_shipping_address($this->checkout_details);
        if (!empty($destination)) {
            $packages[0]['destination']['country'] = $destination['country'];
            $packages[0]['destination']['state'] = $destination['state'];
            $packages[0]['destination']['postcode'] = $destination['postcode'];
            $packages[0]['destination']['city'] = $destination['city'];
            $packages[0]['destination']['address'] = $destination['address_1'];
            $packages[0]['destination']['address_2'] = $destination['address_2'];
        }
        return $packages;
    }

    public function init() {
        if (version_compare(WC_VERSION, '3.3', '<')) {
            add_filter('wc_checkout_params', array($this, 'filter_wc_checkout_params'), 10, 1);
        } else {
            add_filter('woocommerce_get_script_data', array($this, 'filter_wc_checkout_params'), 10, 2);
        }
    }

    public function filter_wc_checkout_params($params, $handle = '') {
        if ('wc-checkout' !== $handle && !doing_action('wc_checkout_params')) {
            return $params;
        }
        $fields = array('paypal_order_id', 'paypal_payer_id');
        $params['wc_ajax_url'] = remove_query_arg('wc-ajax', $params['wc_ajax_url']);
        foreach ($fields as $field) {
            if (!empty($_GET[$field])) {
                $params['wc_ajax_url'] = add_query_arg($field, $_GET[$field], $params['wc_ajax_url']);
            }
        }
        $params['wc_ajax_url'] = add_query_arg('wc-ajax', '%%endpoint%%', $params['wc_ajax_url']);
        return $params;
    }

    public function ppcp_session_manager() {
        try {
            if (!empty($_GET['paypal_order_id']) && !empty($_GET['paypal_payer_id'])) {
                if (isset($_GET['from']) && 'product' === $_GET['from']) {
                    if (function_exists('wc_clear_notices')) {
                        wc_clear_notices();
                    }
                }
                ppcp_set_session('ppcp_paypal_order_id', wc_clean($_GET['paypal_order_id']));
                if (empty($this->checkout_details)) {
                    $this->checkout_details = ppcp_get_session('ppcp_paypal_transaction_details', false);
                    if ($this->checkout_details === false) {
                        $this->checkout_details = $this->request->ppcp_get_checkout_details($_GET['paypal_order_id']);
                        ppcp_set_session('ppcp_paypal_transaction_details', $this->checkout_details);
                    }
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function ppcp_display_order_page() {
        $this->checkout_details = $this->request->ppcp_get_checkout_details($_GET['paypal_order_id']);
        ppcp_set_session('ppcp_paypal_transaction_details', $this->checkout_details);
        if (empty($this->checkout_details)) {
            return false;
        }
        if (!empty($this->checkout_details)) {
            $shipping_details = $this->get_mapped_shipping_address($this->checkout_details);
            $billing_details = $this->get_mapped_billing_address($this->checkout_details);
            $this->update_customer_addresses_from_paypal($shipping_details, $billing_details);
        }
        $order_id = absint(ppcp_get_session('order_awaiting_payment'));
        if (empty($order_id)) {
            $order_id = ppcp_get_session('ppcp_woo_order_id');
        }
        $order = wc_get_order($order_id);
        $this->checkout_details = $this->checkout_details;
        if ($this->paymentaction === 'capture' && !empty($this->checkout_details->status) && $this->checkout_details->status == 'COMPLETED' && $order !== false) {
            $transaction_id = isset($this->checkout_details->purchase_units[0]->payments->captures[0]->id) ? $this->checkout_details->purchase_units['0']->payments->captures[0]->id : '';
            $seller_protection = isset($this->checkout_details->purchase_units[0]->payments->captures[0]->seller_protection->status) ? $this->checkout_details->purchase_units[0]->payments->captures[0]->seller_protection->status : '';
            $payment_source = isset($this->checkout_details->payment_source) ? $this->checkout_details->payment_source : '';
            if (!empty($payment_source->card)) {
                $card_response_order_note = __('Card Details', 'express-checkout');
                $card_response_order_note .= "\n";
                $card_response_order_note .= 'Last digits : ' . $payment_source->card->last_digits;
                $card_response_order_note .= "\n";
                $card_response_order_note .= 'Brand : ' . ppcp_readable($payment_source->card->brand);
                $card_response_order_note .= "\n";
                $card_response_order_note .= 'Card type : ' . ppcp_readable($payment_source->card->type);
                $order->add_order_note($card_response_order_note);
            }
            $processor_response = isset($this->checkout_details->purchase_units[0]->payments->captures[0]->processor_response) ? $this->checkout_details->purchase_units[0]->payments->captures[0]->processor_response : '';
            if (!empty($processor_response->avs_code)) {
                $avs_response_order_note = __('Address Verification Result', 'express-checkout');
                $avs_response_order_note .= "\n";
                $avs_response_order_note .= $processor_response->avs_code;
                if (isset($this->AVSCodes[$processor_response->avs_code])) {
                    $avs_response_order_note .= ' : ' . $this->AVSCodes[$processor_response->avs_code];
                }
                $order->add_order_note($avs_response_order_note);
            }
            if (!empty($processor_response->cvv_code)) {
                $cvv2_response_code = __('Card Security Code Result', 'express-checkout');
                $cvv2_response_code .= "\n";
                $cvv2_response_code .= $processor_response->cvv_code;
                if (isset($this->CVV2Codes[$processor_response->cvv_code])) {
                    $cvv2_response_code .= ' : ' . $this->CVV2Codes[$processor_response->cvv_code];
                }
                $order->add_order_note($cvv2_response_code);
            }
            $currency_code = isset($this->checkout_details->purchase_units[0]->payments->captures[0]->seller_receivable_breakdown->paypal_fee->currency_code) ? $this->checkout_details->purchase_units[0]->payments->captures[0]->seller_receivable_breakdown->paypal_fee->currency_code : '';
            $value = isset($this->checkout_details->purchase_units[0]->payments->captures[0]->seller_receivable_breakdown->paypal_fee->value) ? $this->checkout_details->purchase_units[0]->payments->captures[0]->seller_receivable_breakdown->paypal_fee->value : '';
            ppcp_update_post_meta($order, '_paypal_fee', $value);
            ppcp_update_post_meta($order, '_paypal_fee_currency_code', $currency_code);
            $payment_status = isset($this->checkout_details->purchase_units[0]->payments->captures[0]->status) ? $this->checkout_details->purchase_units[0]->payments->captures[0]->status : '';
            if ($payment_status == 'COMPLETED') {
                $order->payment_complete($transaction_id);
                $order->add_order_note(sprintf(__('Payment via %s : %s .', 'express-checkout'), $this->title, ucfirst(strtolower($payment_status))));
            } else {
                $payment_status_reason = $payment_status_reason = isset($this->checkout_details->purchase_units[0]->payments->authorizations[0]->status_details->reason) ? $this->checkout_details->purchase_units[0]->payments->authorizations[0]->status_details->reason : '';
                ppcp_update_woo_order_status($order_id, $payment_status, $payment_status_reason);
            }
            $order->add_order_note(sprintf(__('%s Transaction ID: %s', 'express-checkout'), $this->title, $transaction_id));
            $order->add_order_note('Seller Protection Status: ' . ppcp_readable($seller_protection));
        } elseif ($this->paymentaction === 'authorize' && !empty($this->checkout_details->status) && $this->checkout_details->status == 'COMPLETED' && $order !== false) {
            $transaction_id = isset($this->checkout_details->purchase_units[0]->payments->authorizations[0]->id) ? $this->checkout_details->purchase_units['0']->payments->authorizations[0]->id : '';
            $seller_protection = isset($this->checkout_details->purchase_units[0]->payments->authorizations[0]->seller_protection->status) ? $this->checkout_details->purchase_units[0]->payments->authorizations[0]->seller_protection->status : '';
            $payment_status = isset($this->checkout_details->purchase_units[0]->payments->authorizations[0]->status) ? $this->checkout_details->purchase_units[0]->payments->authorizations[0]->status : '';
            $payment_status_reason = isset($this->checkout_details->purchase_units[0]->payments->authorizations[0]->status_details->reason) ? $this->checkout_details->purchase_units[0]->payments->authorizations[0]->status_details->reason : '';
            if (!empty($payment_status_reason)) {
                $order->add_order_note(sprintf(__('Payment via %s Pending. PayPal reason: %s.', 'express-checkout'), $this->title, $payment_status_reason));
            }
            ppcp_update_post_meta($order, '_transaction_id', $transaction_id);
            ppcp_update_post_meta($order, '_payment_status', $payment_status);
            ppcp_update_post_meta($order, '_auth_transaction_id', $transaction_id);
            ppcp_update_post_meta($order, '_payment_action', $this->paymentaction);
            $order->add_order_note(sprintf(__('%s Transaction ID: %s', 'express-checkout'), $this->title, $transaction_id));
            $order->add_order_note('Seller Protection Status: ' . ppcp_readable($seller_protection));
            $order->update_status('on-hold');
            $order->add_order_note(__('Payment authorized. Change payment status to processing or complete to capture funds.', 'express-checkout'));
        }
        WC()->cart->empty_cart();
        unset(WC()->session->ppcp_session);
        wp_safe_redirect($order->get_checkout_order_received_url());
        exit();
    }

    public function update_customer_addresses_from_paypal($shipping_details, $billing_details) {
        try {
            $customer = WC()->customer;
            if (!empty($billing_details['address_1'])) {
                $customer->set_billing_address($billing_details['address_1']);
            }
            if (!empty($billing_details['address_2'])) {
                $customer->set_billing_address_2($billing_details['address_2']);
            }
            if (!empty($billing_details['city'])) {
                $customer->set_billing_city($billing_details['city']);
            }
            if (!empty($billing_details['postcode'])) {
                $customer->set_billing_postcode($billing_details['postcode']);
            }
            if (!empty($billing_details['state'])) {
                $customer->set_billing_state($billing_details['state']);
            }
            if (!empty($billing_details['country'])) {
                $customer->set_billing_country($billing_details['country']);
            }
            if (!empty($shipping_details['address_1'])) {
                $customer->set_shipping_address($shipping_details['address_1']);
            }
            if (!empty($shipping_details['address_2'])) {
                $customer->set_shipping_address_2($shipping_details['address_2']);
            }
            if (!empty($shipping_details['city'])) {
                $customer->set_shipping_city($shipping_details['city']);
            }
            if (!empty($shipping_details['postcode'])) {
                $customer->set_shipping_postcode($shipping_details['postcode']);
            }
            if (!empty($shipping_details['state'])) {
                $customer->set_shipping_state($shipping_details['state']);
            }
            if (!empty($shipping_details['country'])) {
                $customer->set_shipping_country($shipping_details['country']);
            }
        } catch (Exception $ex) {
            
        }
    }

    public function maybe_start_checkout($data, $errors = null) {
        try {
            if (is_null($errors)) {
                $error_messages = wc_get_notices('error');
                wc_clear_notices();
            } else {
                $error_messages = $errors->get_error_messages();
            }
            if (empty($error_messages)) {
                $this->set_customer_data($_POST);
            } else {
                ob_start();
                wp_send_json_error(array('messages' => $error_messages));
                exit;
            }
        } catch (Exception $ex) {
            
        }
    }

    function set_customer_data($data) {
        try {
            $customer = WC()->customer;
            $billing_first_name = empty($data['billing_first_name']) ? '' : wc_clean($data['billing_first_name']);
            $billing_last_name = empty($data['billing_last_name']) ? '' : wc_clean($data['billing_last_name']);
            $billing_country = empty($data['billing_country']) ? '' : wc_clean($data['billing_country']);
            $billing_address_1 = empty($data['billing_address_1']) ? '' : wc_clean($data['billing_address_1']);
            $billing_address_2 = empty($data['billing_address_2']) ? '' : wc_clean($data['billing_address_2']);
            $billing_city = empty($data['billing_city']) ? '' : wc_clean($data['billing_city']);
            $billing_state = empty($data['billing_state']) ? '' : wc_clean($data['billing_state']);
            $billing_postcode = empty($data['billing_postcode']) ? '' : wc_clean($data['billing_postcode']);
            $billing_phone = empty($data['billing_phone']) ? '' : wc_clean($data['billing_phone']);
            $billing_email = empty($data['billing_email']) ? '' : wc_clean($data['billing_email']);
            if (isset($data['ship_to_different_address'])) {
                $shipping_first_name = empty($data['shipping_first_name']) ? '' : wc_clean($data['shipping_first_name']);
                $shipping_last_name = empty($data['shipping_last_name']) ? '' : wc_clean($data['shipping_last_name']);
                $shipping_country = empty($data['shipping_country']) ? '' : wc_clean($data['shipping_country']);
                $shipping_address_1 = empty($data['shipping_address_1']) ? '' : wc_clean($data['shipping_address_1']);
                $shipping_address_2 = empty($data['shipping_address_2']) ? '' : wc_clean($data['shipping_address_2']);
                $shipping_city = empty($data['shipping_city']) ? '' : wc_clean($data['shipping_city']);
                $shipping_state = empty($data['shipping_state']) ? '' : wc_clean($data['shipping_state']);
                $shipping_postcode = empty($data['shipping_postcode']) ? '' : wc_clean($data['shipping_postcode']);
            } else {
                $shipping_first_name = $billing_first_name;
                $shipping_last_name = $billing_last_name;
                $shipping_country = $billing_country;
                $shipping_address_1 = $billing_address_1;
                $shipping_address_2 = $billing_address_2;
                $shipping_city = $billing_city;
                $shipping_state = $billing_state;
                $shipping_postcode = $billing_postcode;
            }
            $customer->set_shipping_country($shipping_country);
            $customer->set_shipping_address($shipping_address_1);
            $customer->set_shipping_address_2($shipping_address_2);
            $customer->set_shipping_city($shipping_city);
            $customer->set_shipping_state($shipping_state);
            $customer->set_shipping_postcode($shipping_postcode);
            if (version_compare(WC_VERSION, '3.0', '<')) {
                $customer->shipping_first_name = $shipping_first_name;
                $customer->shipping_last_name = $shipping_last_name;
                $customer->billing_first_name = $billing_first_name;
                $customer->billing_last_name = $billing_last_name;
                $customer->set_country($billing_country);
                $customer->set_address($billing_address_1);
                $customer->set_address_2($billing_address_2);
                $customer->set_city($billing_city);
                $customer->set_state($billing_state);
                $customer->set_postcode($billing_postcode);
                $customer->billing_phone = $billing_phone;
                $customer->billing_email = $billing_email;
            } else {
                $customer->set_shipping_first_name($shipping_first_name);
                $customer->set_shipping_last_name($shipping_last_name);
                $customer->set_billing_first_name($billing_first_name);
                $customer->set_billing_last_name($billing_last_name);
                $customer->set_billing_country($billing_country);
                $customer->set_billing_address_1($billing_address_1);
                $customer->set_billing_address_2($billing_address_2);
                $customer->set_billing_city($billing_city);
                $customer->set_billing_state($billing_state);
                $customer->set_billing_postcode($billing_postcode);
                $customer->set_billing_phone($billing_phone);
                $customer->set_billing_email($billing_email);
            }
        } catch (Exception $ex) {
            
        }
    }

    public function maybe_clear_session_data() {
        try {
            if (has_active_session()) {
                unset(WC()->session->ppcp_session);
            }
        } catch (Exception $ex) {
            
        }
    }

    public function ppcp_add_class_order_review_page($classes) {
        try {
            if (!class_exists('WooCommerce') || WC()->session == null) {
                return $classes;
            }
            if (has_active_session()) {
                $classes[] = 'ppcp-order-review';
            }
        } catch (Exception $ex) {
            return $classes;
        }
        return $classes;
    }

    public function ppcp_woocommerce_coupons_enabled($bool) {
        if ($bool) {
            return $this->order_review_page_enable_coupons;
        }
        return $bool;
    }

    public function ppcp_order_review_page_description() {
        if ($this->order_review_page_description && has_active_session()) {
            ?>
                <div class="order_review_page_description">
                    <p>
            <?php
            echo wp_kses_post($this->order_review_page_description);
            ?>
                    </p>
                </div>
            <?php
        }
    }

    public function ppcp_capture_payment($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        $old_wc = version_compare(WC_VERSION, '3.0', '<');
        $payment_method = $old_wc ? $order->payment_method : $order->get_payment_method();
        $payment_action = ppcp_get_post_meta($order, '_payment_action');
        $auth_transaction_id = ppcp_get_post_meta($order, '_auth_transaction_id');
        if ('ppcp_paypal_checkout' === $payment_method && $payment_action === 'authorize' && !empty($auth_transaction_id)) {
            $trans_details = $this->request->ppcp_show_details_authorized_payment($auth_transaction_id);
            if ($this->ppcp_is_authorized_only($trans_details)) {
                $this->request->ppcp_capture_authorized_payment($order_id);
            }
        }
    }

    public function ppcp_cancel_authorization($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        $old_wc = version_compare(WC_VERSION, '3.0', '<');
        $payment_method = $old_wc ? $order->payment_method : $order->get_payment_method();
        $transaction_id = $order->get_transaction_id();
        $payment_action = ppcp_get_post_meta($order, '_payment_action');
        if ('ppcp_paypal_checkout' === $payment_method && $transaction_id && $payment_action === 'authorize') {
            // $trans_details = $this->request->ppcp_show_details_authorized_payment($transaction_id);
            //if ($this->ppcp_is_authorized_only($trans_details)) {
            //  $this->request->ppcp_capture_authorized_payment($order_id);
            //}
        }
    }

    public function ppcp_add_capture_charge_order_action($actions) {
        if (!isset($_REQUEST['post'])) {
            return $actions;
        }
        $order = wc_get_order($_REQUEST['post']);
        if (empty($order)) {
            return $actions;
        }
        $old_wc = version_compare(WC_VERSION, '3.0', '<');
        $payment_method = $old_wc ? $order->payment_method : $order->get_payment_method();
        $paypal_status = ppcp_get_post_meta($order, '_payment_status');
        $payment_action = ppcp_get_post_meta($order, '_payment_action');
        if ('ppcp_paypal_checkout' !== $payment_method) {
            return $actions;
        }
        if (!is_array($actions)) {
            $actions = array();
        }
        if ('CREATED' !== $paypal_status && $payment_action === 'authorize') {
            $actions['ppcp_capture_charge'] = esc_html__('Capture Charge', 'express-checkout');
        }
        return $actions;
    }

    public function ppcp_maybe_capture_charge($order) {
        if (!is_object($order)) {
            $order = wc_get_order($order);
        }
        $order_id = version_compare(WC_VERSION, '3.0', '<') ? $order->id : $order->get_id();
        $this->ppcp_capture_payment($order_id);
        return true;
    }

    public function ppcp_is_authorized_only($trans_details = array()) {
        if (!is_wp_error($trans_details) && !empty($trans_details)) {
            $payment_status = '';
            if (isset($trans_details->status) && !empty($trans_details->status)) {
                $payment_status = $trans_details->status;
            }
            if ('CREATED' === $payment_status) {
                return true;
            }
        }
        return false;
    }

    public function ppcp_clean_url($url) {
        if (strpos($url, 'https://www.paypal.com/sdk/js') !== false) {
            $url = "{$url}' data-partner-attribution-id='MBJTechnolabs_SI_SPB";
            if (is_checkout() && $this->advanced_card_payments) {
                $url = "{$url}' data-client-token='{$this->client_token}";
            }
        }
        return $url;
    }

    public function ppcp_set_address_to_checkout_field() {
        
    }

    public function validate_checkout($country, $state, $sec) {
        $state_value = '';
        $valid_states = WC()->countries->get_states(isset($country) ? $country : ( 'billing' === $sec ? WC()->customer->get_country() : WC()->customer->get_shipping_country() ));
        if (!empty($valid_states) && is_array($valid_states)) {
            $valid_state_values = array_flip(array_map('strtolower', $valid_states));
            if (isset($valid_state_values[strtolower($state)])) {
                $state_value = $valid_state_values[strtolower($state)];
                return $state_value;
            }
        } else {
            return $state;
        }
        if (!empty($valid_states) && is_array($valid_states) && sizeof($valid_states) > 0) {
            if (!in_array($state, array_keys($valid_states))) {
                return false;
            } else {
                return $state;
            }
        }

        return $state_value;
    }

    public function ppcp_update_checkout_field_details() {
        if (empty($this->checkout_details)) {
            $this->checkout_details = ppcp_get_session('ppcp_paypal_transaction_details', false);
            if (empty($this->checkout_details)) {
                if (!empty($_GET['paypal_order_id'])) {
                    $this->checkout_details = $this->request->ppcp_get_checkout_details($_GET['paypal_order_id']);
                }
            }
            if (empty($this->checkout_details)) {
                return;
            }
            ppcp_set_session('ppcp_paypal_transaction_details', $this->checkout_details);
        }
        $states_list = WC()->countries->get_states();
        if (!empty($this->checkout_details)) {
            $shipping_address = $this->get_mapped_shipping_address();
            if (!empty($shipping_address)) {
                foreach ($shipping_address as $field => $value) {
                    if (!empty($value)) {
                        if ('state' == $field) {
                            if ($this->validate_checkout($shipping_address['country'], $value, 'shipping')) {
                                $_POST['shipping_' . $field] = $this->validate_checkout($shipping_address['country'], $value, 'shipping');
                            } else {
                                if (isset($shipping_address['country']) && isset($states_list[$shipping_address['country']])) {
                                    $state_key = array_search($value, $states_list[$shipping_address['country']]);
                                    $_POST['shipping_' . $field] = $state_key;
                                } else {
                                    $_POST['shipping_' . $field] = '';
                                }
                            }
                        } else {
                            $_POST['shipping_' . $field] = wc_clean(stripslashes($value));
                        }
                    }
                }
            }
            $billing_address = $this->get_mapped_billing_address($this->checkout_details);
            if (!empty($billing_address)) {
                foreach ($billing_address as $field => $value) {
                    if (!empty($value)) {
                        if ('state' == $field) {
                            if ($this->validate_checkout($shipping_address['country'], $value, 'shipping')) {
                                $_POST['billing_' . $field] = $this->validate_checkout($shipping_address['country'], $value, 'shipping');
                            } else {
                                if (isset($shipping_address['country']) && isset($states_list[$shipping_address['country']])) {
                                    $state_key = array_search($value, $states_list[$shipping_address['country']]);
                                    $_POST['billing_' . $field] = $state_key;
                                } else {
                                    $_POST['billing_' . $field] = '';
                                }
                            }
                        } else {
                            $_POST['billing_' . $field] = wc_clean(stripslashes($value));
                        }
                    }
                }
            }
        }
    }

    public function ppcp_cancel_button() {
        if (has_active_session()) {
            $order_button_text = __('Cancel order', 'express-checkout');
            $cancel_order_url = add_query_arg(array('ppcp_action' => 'cancel_order', 'utm_nooverride' => '1', 'from' => 'checkout'), WC()->api_request_url('PPCP_Paypal_Checkout_For_Woocommerce_Button_Manager'));
            echo apply_filters('ppcp_review_order_cance_button_html', '<a class="button alt ppcp_cancel" name="woocommerce_checkout_cancel_order" href="' . esc_attr($cancel_order_url) . '" >' . $order_button_text . '</a>');
        }
    }

    public function ppcp_create_webhooks() {
        if (false === get_transient('ppcp_is_webhook_process_started')) {
            if (ppcp_is_local_server() === false) {
                $webhook_id = get_option($this->webhook_id, '');
                if (empty($webhook_id)) {
                    $this->request->ppcp_create_webhooks_request();
                }
                set_transient('ppcp_is_webhook_process_started', 'done', 3 * HOUR_IN_SECONDS);
            }
        }
    }

    public function ppcp_handle_webhook_request() {
        $this->request->ppcp_handle_webhook_request_handler();
    }

    public function ppcp_cc_capture() {
        try {
            $ppcp_paypal_order_id = ppcp_get_session('ppcp_paypal_order_id');
            if (!empty($ppcp_paypal_order_id)) {
                include_once EXPRESS_CHECKOUT_DIR . '/ppcp/includes/class-ppcp-paypal-checkout-for-woocommerce-request.php';
                $this->request = new PPCP_Paypal_Checkout_For_Woocommerce_Request();
                $api_response = $this->request->ppcp_get_checkout_details($ppcp_paypal_order_id);
                $order_id = absint(WC()->session->get('order_awaiting_payment'));
                if (empty($order_id)) {
                    $order_id = ppcp_get_session('ppcp_woo_order_id');
                }
                $order = wc_get_order($order_id);
                if ($order === false) {
                    if (!class_exists('PPCP_Paypal_Checkout_For_Woocommerce_Checkout')) {
                        include_once EXPRESS_CHECKOUT_DIR . '/ppcp/includes/class-ppcp-paypal-checkout-for-woocommerce-checkout.php';
                    }
                    $ppcp_checkout = PPCP_Paypal_Checkout_For_Woocommerce_Checkout::instance();
                    $order_id = $ppcp_checkout->ppcp_create_order();
                    $order = wc_get_order($order_id);
                    $this->request->ppcp_update_order($order);
                }
                $liability_shift_result = $this->ppcp_liability_shift($order, $api_response);
                if ($liability_shift_result === 1) {
                    if ($this->paymentaction === 'capture') {
                        $is_success = $this->request->ppcp_order_capture_request($order_id, false);
                    } else {
                        $is_success = $this->request->ppcp_order_auth_request($order_id);
                    }
                    ppcp_update_post_meta($order, '_payment_action', $this->paymentaction);
                    ppcp_update_post_meta($order, 'enviorment', ($this->testmode) ? 'sandbox' : 'live');
                } elseif ($liability_shift_result === 2) {
                    $is_success = false;
                    wc_add_notice(__('We cannot process your order with the payment information that you provided. Please use an alternate payment method.', 'express-checkout'), 'error');
                } elseif ($liability_shift_result === 3) {
                    $is_success = false;
                    wc_add_notice(__('Something went wrong. Please try again.', 'express-checkout'), 'error');
                }
                if ($is_success) {
                    WC()->cart->empty_cart();
                    unset(WC()->session->ppcp_session);
                    if (ob_get_length())
                        ob_end_clean();
                    wp_send_json_success(array(
                        'result' => 'success',
                        'redirect' => apply_filters('woocommerce_get_return_url', $order->get_checkout_order_received_url(), $order),
                    ));
                    exit();
                } else {
                    unset(WC()->session->ppcp_session);
                    if (ob_get_length())
                        ob_end_clean();
                    wp_send_json_success(array(
                        'result' => 'failure',
                        'redirect' => wc_get_checkout_url()
                    ));
                    exit();
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    public function ppcp_liability_shift($order, $response_object) {
        if (!empty($response_object)) {
            $response = json_decode(json_encode($response_object), true);
            if (!empty($response['payment_source']['card']['authentication_result']['liability_shift'])) {
                $LiabilityShift = isset($response['payment_source']['card']['authentication_result']['liability_shift']) ? strtoupper($response['payment_source']['card']['authentication_result']['liability_shift']) : '';
                $EnrollmentStatus = isset($response['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status']) ? strtoupper($response['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status']) : '';
                $AuthenticationResult = isset($response['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status']) ? strtoupper($response['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status']) : '';
                $liability_shift_order_note = __('3D Secure response', 'express-checkout');
                $liability_shift_order_note .= "\n";
                $liability_shift_order_note .= 'Liability Shift : ' . ppcp_readable($LiabilityShift);
                $liability_shift_order_note .= "\n";
                $liability_shift_order_note .= 'Enrollment Status : ' . $EnrollmentStatus;
                $liability_shift_order_note .= "\n";
                $liability_shift_order_note .= 'Authentication Status : ' . $AuthenticationResult;
                if ($order) {
                    $order->add_order_note($liability_shift_order_note);
                }
                if ($LiabilityShift === 'POSSIBLE') {
                    return $this->procceed;
                }
                if ($LiabilityShift === 'UNKNOWN') {
                    return $this->retry;
                }
                if ($LiabilityShift === 'NO') {
                    if ($EnrollmentStatus === 'B' && empty($AuthenticationResult)) {
                        return $this->procceed;
                    }
                    if ($EnrollmentStatus === 'U' && empty($AuthenticationResult)) {
                        return $this->procceed;
                    }
                    if ($EnrollmentStatus === 'N' && empty($AuthenticationResult)) {
                        return $this->procceed;
                    }
                    if ($AuthenticationResult === 'R') {
                        return $this->reject;
                    }
                    if ($AuthenticationResult === 'N') {
                        return $this->reject;
                    }
                    if ($AuthenticationResult === 'U') {
                        return $this->retry;
                    }
                    if (!$AuthenticationResult) {
                        return $this->retry;
                    }
                    return $this->procceed;
                }
                return $this->procceed;
            } else {
                return $this->procceed;
            }
        }
        return $this->retry;
    }

    public function ppcp_add_order_id() {
        global $wp;
        $order_id = absint($wp->query_vars['order-pay']);
        ?>
            <input type="hidden" name="woo_order_id" value="<?php echo $order_id; ?>" />
            <?php
        }

        public function ppcp_paypal_button_style_properties() {
            $this->disable_funding = array();
            $this->style_layout = $this->ppcp_get_settings('cart_button_layout', 'vertical');
            $this->style_color = 'gold';
            $this->style_shape = 'rect';
            $this->style_label = 'paypal';
            $this->style_tagline = 'yes';
            if (is_product()) {
                $this->disable_funding = $this->ppcp_get_settings('product_disallowed_funding_methods', array());
                $this->style_layout = $this->ppcp_get_settings('product_button_layout', 'horizontal');
                $this->style_color = $this->ppcp_get_settings('product_style_color', 'gold');
                $this->style_shape = $this->ppcp_get_settings('product_style_shape', 'rect');
                $this->style_label = $this->ppcp_get_settings('product_button_label', 'paypal');
                $this->style_tagline = $this->ppcp_get_settings('product_button_tagline', 'yes');
            } elseif (is_cart()) {
                $this->disable_funding = $this->ppcp_get_settings('cart_disallowed_funding_methods', array());
                $this->style_layout = $this->ppcp_get_settings('cart_button_layout', 'vertical');
                $this->style_color = $this->ppcp_get_settings('cart_style_color', 'gold');
                $this->style_shape = $this->ppcp_get_settings('cart_style_shape', 'rect');
                $this->style_label = $this->ppcp_get_settings('cart_button_label', 'paypal');
                $this->style_tagline = $this->ppcp_get_settings('cart_button_tagline', 'yes');
            } elseif (is_checkout() || is_checkout_pay_page()) {
                $this->disable_funding = $this->ppcp_get_settings('checkout_disallowed_funding_methods', array());
                $this->style_layout = $this->ppcp_get_settings('checkout_button_layout', 'vertical');
                $this->style_color = $this->ppcp_get_settings('checkout_style_color', 'gold');
                $this->style_shape = $this->ppcp_get_settings('checkout_style_shape', 'rect');
                $this->style_label = $this->ppcp_get_settings('checkout_button_label', 'paypal');
                $this->style_tagline = $this->ppcp_get_settings('checkout_button_tagline', 'yes');
            }
            $this->mini_cart_disable_funding = $this->ppcp_get_settings('mini_cart_disallowed_funding_methods', array());
            $this->mini_cart_style_layout = $this->ppcp_get_settings('mini_cart_button_layout', 'vertical');
            $this->mini_cart_style_color = $this->ppcp_get_settings('mini_cart_style_color', 'gold');
            $this->mini_cart_style_shape = $this->ppcp_get_settings('mini_cart_style_shape', 'rect');
            $this->mini_cart_style_label = $this->ppcp_get_settings('mini_cart_button_label', 'paypal');
            $this->mini_cart_style_tagline = $this->ppcp_get_settings('mini_cart_button_tagline', 'yes');
        }

        public function ppcp_prevent_add_to_cart_woo_action() {
            if (isset($_REQUEST['ppcp-add-to-cart'])) {
                if (isset($_REQUEST['add-to-cart'])) {
                    unset($_REQUEST['add-to-cart']);
                    unset($_POST['add-to-cart']);
                }
            }
        }
        
        public function ppcp_prepare_order_data() {
            if (empty($this->checkout_details)) {
                $this->checkout_details = ppcp_get_session('ppcp_paypal_transaction_details', false);
                if (empty($this->checkout_details)) {
                    $ppcp_paypal_order_id = ppcp_get_session('ppcp_paypal_order_id');
                    if (!empty($ppcp_paypal_order_id)) {
                        $this->checkout_details = $this->request->ppcp_get_checkout_details($ppcp_paypal_order_id);
                    }
                }
                if (empty($this->checkout_details)) {
                    return;
                }
                ppcp_set_session('ppcp_paypal_transaction_details', $this->checkout_details);
            }
            $shipping_address = $this->get_mapped_shipping_address($this->checkout_details);
            $billing_address = $this->get_mapped_billing_address($this->checkout_details, ($this->set_billing_address) ? false : true);
            $order_data['terms'] = 1;
            $order_data['createaccount'] = 0;
            $order_data['payment_method'] = 'wpg_paypal_checkout';
            $order_data['ship_to_different_address'] = false;
            $order_data['order_comments'] = '';
            $order_data['shipping_method'] = '';
            $order_data['billing_first_name'] = isset($billing_address['first_name']) ? $billing_address['first_name'] : '';
            $order_data['billing_last_name'] = isset($billing_address['last_name']) ? $billing_address['last_name'] : '';
            $order_data['billing_email'] = isset($billing_address['email']) ? $billing_address['email'] : '';
            $order_data['billing_company'] = isset($billing_address['company']) ? $billing_address['company'] : '';
            if ($this->set_billing_address) {
                $order_data['billing_address_1'] = isset($billing_address['address_1']) ? $billing_address['address_1'] : '';
                $order_data['billing_address_2'] = isset($billing_address['address_2']) ? $billing_address['address_2'] : '';
                $order_data['billing_city'] = isset($billing_address['city']) ? $billing_address['city'] : '';
                $order_data['billing_state'] = isset($billing_address['state']) ? $billing_address['state'] : '';
                $order_data['billing_postcode'] = isset($billing_address['postcode']) ? $billing_address['postcode'] : '';
                $order_data['billing_phone'] = isset($billing_address['phone']) ? $billing_address['phone'] : '';
            }
            $order_data['shipping_first_name'] = isset($shipping_address['first_name']) ? $shipping_address['first_name'] : '';
            $order_data['shipping_last_name'] = isset($shipping_address['last_name']) ? $shipping_address['last_name'] : '';
            $order_data['shipping_company'] = isset($shipping_address['company']) ? $shipping_address['company'] : '';
            $order_data['shipping_country'] = isset($shipping_address['country']) ? $shipping_address['country'] : '';
            $order_data['shipping_address_1'] = isset($shipping_address['address_1']) ? $shipping_address['address_1'] : '';
            $order_data['shipping_address_2'] = isset($shipping_address['address_2']) ? $shipping_address['address_2'] : '';
            $order_data['shipping_city'] = isset($shipping_address['city']) ? $shipping_address['city'] : '';
            $order_data['shipping_state'] = isset($shipping_address['state']) ? $shipping_address['state'] : '';
            $order_data['shipping_postcode'] = isset($shipping_address['postcode']) ? $shipping_address['postcode'] : '';
            return $order_data;
        }

    }
    