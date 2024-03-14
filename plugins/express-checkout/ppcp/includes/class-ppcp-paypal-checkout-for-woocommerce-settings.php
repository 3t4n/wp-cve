<?php

defined('ABSPATH') || exit;


defined('ABSPATH') || exit;

if (!class_exists('PPCP_Paypal_Checkout_For_Woocommerce_Settings')) {

    class PPCP_Paypal_Checkout_For_Woocommerce_Settings {

        public $gateway_key;
        public $settings = array();
        protected static $_instance = null;

        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct() {
            $this->gateway_key = 'woocommerce_ppcp_paypal_checkout_settings';
        }

        public function get($id, $default = false) {
            if (!$this->has($id)) {
                return $default;
            }
            return $this->settings[$id];
        }

        public function get_load() {
            return get_option($this->gateway_key, array());
        }

        public function has($id) {
            $this->load();
            return array_key_exists($id, $this->settings);
        }

        public function set($id, $value) {
            $this->load();
            $this->settings[$id] = $value;
        }

        public function persist() {
            update_option($this->gateway_key, $this->settings);
        }

        public function load() {
            if ($this->settings) {
                return false;
            }
            $this->settings = get_option($this->gateway_key, array());
            
            $defaults = array(
                'title' => __('PayPal', 'express-checkout'),
                'description' => __(
                        'Accept PayPal, PayPal Credit and alternative payment types.', 'express-checkout'
                )
            );
            foreach ($defaults as $key => $value) {
                if (isset($this->settings[$key])) {
                    continue;
                }
                $this->settings[$key] = $value;
            }
            return true;
        }

        public function ppcp_setting_fields() {
            $default_settings = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal Checkout', 'express-checkout'),
                    'description' => __('Check this box to enable the payment gateway. Leave unchecked to disable it.', 'express-checkout'),
                    'desc_tip' => true,
                    'default' => 'yes',
                ),
                'api_details' => array(
                    'title' => __('Account Settings', 'express-checkout'),
                    'type' => 'title',
                    'description' => '',
                    'class' => 'ppcp_separator_heading',
                ),
                'testmode' => array(
                    'title' => __('PayPal sandbox', 'express-checkout'),
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal sandbox', 'express-checkout'),
                    'default' => 'no',
                    'description' => __('Check this box to enable test mode so that all transactions will hit PayPal’s sandbox server instead of the live server. This should only be used during development as no real transactions will occur when this is enabled.', 'express-checkout'),
                    'desc_tip' => true
                ),
                'api_client_id' => array(
                    'title' => __('PayPal Client ID', 'express-checkout'),
                    'type' => 'password',
                    'description' => __('Enter your PayPal Client ID.', 'express-checkout'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'api_secret' => array(
                    'title' => __('PayPal Secret', 'express-checkout'),
                    'type' => 'password',
                    'description' => __('Enter your PayPal Secret.', 'express-checkout'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'sandbox_client_id' => array(
                    'title' => __('Sandbox Client ID', 'express-checkout'),
                    'type' => 'password',
                    'description' => __('Enter your PayPal Sandbox Client ID.', 'express-checkout'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'sandbox_api_secret' => array(
                    'title' => __('Sandbox Secret', 'express-checkout'),
                    'type' => 'password',
                    'description' => __('Enter your PayPal Sandbox Secret.', 'express-checkout'),
                    'default' => '',
                    'desc_tip' => true
                )
            );

            $button_manager_settings = array(
                'ppcp_button_header' => array(
                    'title' => __('Smart Payment Buttons Settings', 'express-checkout'),
                    'class' => '',
                    'description' => __('', 'express-checkout'),
                    'type' => 'title',
                    'class' => 'ppcp_separator_heading',
                ),
                'product_button_settings' => array(
                    'title' => __('Product Page', 'express-checkout'),
                    'class' => '',
                    'description' => __('Enable the Product specific button settings, and the options set will be applied to the PayPal Smart buttons on your Product pages.', 'express-checkout'),
                    'type' => 'title',
                    'class' => '',
                ),
                'enable_product_button' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'class' => '',
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal Smart Button on the Product Pages.', 'express-checkout'),
                    'default' => 'yes',
                    'desc_tip' => true,
                    'description' => __('', 'express-checkout'),
                ),
                'product_disallowed_funding_methods' => array(
                    'title' => __('Hide Funding Method(s)', 'express-checkout'),
                    'type' => 'multiselect',
                    'class' => 'wc-enhanced-select ppcp_product_button_settings',
                    'description' => __('Funding methods selected here will be hidden from buyers during checkout.', 'express-checkout'),
                    'default' => '',
                    'desc_tip' => true,
                    'options' => array(
                        'card' => __('Credit or Debit Card', 'express-checkout'),
                        'credit' => __('PayPal Credit', 'express-checkout'),
                        'bancontact' => __('Bancontact', 'express-checkout'),
                        'blik' => __('BLIK', 'express-checkout'),
                        'eps' => __('eps', 'express-checkout'),
                        'giropay' => __('giropay', 'express-checkout'),
                        'ideal' => __('iDEAL', 'express-checkout'),
                        'mercadopago' => __('Mercado Pago', 'express-checkout'),
                        'mybank' => __('MyBank', 'express-checkout'),
                        'p24' => __('Przelewy24', 'express-checkout'),
                        'sepa' => __('SEPA-Lastschrift', 'express-checkout'),
                        'sofort' => __('Sofort', 'express-checkout'),
                        'venmo' => __('Venmo', 'express-checkout')
                    ),
                ),
                'product_button_layout' => array(
                    'title' => __('Button Layout', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_product_button_settings',
                    'description' => __('Select Vertical for stacked buttons, and Horizontal for side-by-side buttons.', 'express-checkout'),
                    'default' => 'horizontal',
                    'desc_tip' => true,
                    'options' => array(
                        'horizontal' => __('Horizontal (Recommended)', 'express-checkout'),
                        'vertical' => __('Vertical', 'express-checkout')
                    ),
                ),
                'product_style_color' => array(
                    'title' => __('Button Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_product_button_settings',
                    'description' => __('Set the color you would like to use for the PayPal button.', 'express-checkout'),
                    'desc_tip' => true,
                    'default' => 'gold',
                    'options' => array(
                        'gold' => __('Gold (Recommended)', 'express-checkout'),
                        'blue' => __('Blue', 'express-checkout'),
                        'silver' => __('Silver', 'express-checkout'),
                        'white' => __('White', 'express-checkout'),
                        'black' => __('Black', 'express-checkout')
                    ),
                ),
                'product_style_shape' => array(
                    'title' => __('Button Shape', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_product_button_settings',
                    'description' => __('Set the shape you would like to use for the buttons.', 'express-checkout'),
                    'desc_tip' => true,
                    'default' => 'rect',
                    'options' => array(
                        'rect' => __('Rect (Recommended)', 'express-checkout'),
                        'pill' => __('Pill', 'express-checkout')
                    ),
                ),
                'product_button_label' => array(
                    'title' => __('Button Label', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_product_button_settings',
                    'description' => __('Set the label type you would like to use for the PayPal button.', 'express-checkout'),
                    'default' => 'paypal',
                    'desc_tip' => true,
                    'options' => array(
                        'paypal' => __('PayPal (Recommended)', 'express-checkout'),
                        'checkout' => __('Checkout', 'express-checkout'),
                        'buynow' => __('Buy Now', 'express-checkout'),
                        'pay' => __('Pay', 'express-checkout'),
                    ),
                ),
                'product_button_tagline' => array(
                    'title' => __('Tagline', 'express-checkout'),
                    'type' => 'checkbox',
                    'class' => 'ppcp_product_button_settings',
                    'default' => 'yes',
                    'label' => __('Enable tagline', 'express-checkout'),
                    'desc_tip' => true,
                    'description' => __(
                            'Add the tagline. This line will only show up, if you select a horizontal layout.', 'express-checkout'
                    ),
                ),
                'cart_button_settings' => array(
                    'title' => __('Cart Page', 'express-checkout'),
                    'class' => '',
                    'description' => __('Enable the Cart specific button settings, and the options set will be applied to the PayPal buttons on your Cart page.', 'express-checkout'),
                    'type' => 'title',
                    'class' => '',
                ),
                'enable_cart_button' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'class' => '',
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal Smart Button on the Cart page.', 'express-checkout'),
                    'default' => 'yes'
                ),
                'cart_button_top_position' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'class' => 'ppcp_cart_button_settings',
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal Smart Button on the Top of the Cart page.', 'express-checkout'),
                    'default' => 'no'
                ),
                'cart_disallowed_funding_methods' => array(
                    'title' => __('Hide Funding Method(s)', 'express-checkout'),
                    'type' => 'multiselect',
                    'class' => 'wc-enhanced-select ppcp_cart_button_settings',
                    'description' => __('Funding methods selected here will be hidden from buyers during checkout.', 'express-checkout'),
                    'default' => '',
                    'desc_tip' => true,
                    'options' => array(
                        'card' => __('Credit or Debit Card', 'express-checkout'),
                        'credit' => __('PayPal Credit', 'express-checkout'),
                        'bancontact' => __('Bancontact', 'express-checkout'),
                        'blik' => __('BLIK', 'express-checkout'),
                        'eps' => __('eps', 'express-checkout'),
                        'giropay' => __('giropay', 'express-checkout'),
                        'ideal' => __('iDEAL', 'express-checkout'),
                        'mercadopago' => __('Mercado Pago', 'express-checkout'),
                        'mybank' => __('MyBank', 'express-checkout'),
                        'p24' => __('Przelewy24', 'express-checkout'),
                        'sepa' => __('SEPA-Lastschrift', 'express-checkout'),
                        'sofort' => __('Sofort', 'express-checkout'),
                        'venmo' => __('Venmo', 'express-checkout')
                    ),
                ),
                'cart_button_layout' => array(
                    'title' => __('Button Layout', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_cart_button_settings',
                    'description' => __('Select Vertical for stacked buttons, and Horizontal for side-by-side buttons.', 'express-checkout'),
                    'default' => 'vertical',
                    'desc_tip' => true,
                    'options' => array(
                        'vertical' => __('Vertical (Recommended)', 'express-checkout'),
                        'horizontal' => __('Horizontal', 'express-checkout'),
                    ),
                ),
                'cart_style_color' => array(
                    'title' => __('Button Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_cart_button_settings',
                    'description' => __('Set the color you would like to use for the PayPal button.', 'express-checkout'),
                    'desc_tip' => true,
                    'default' => 'gold',
                    'options' => array(
                        'gold' => __('Gold (Recommended)', 'express-checkout'),
                        'blue' => __('Blue', 'express-checkout'),
                        'silver' => __('Silver', 'express-checkout'),
                        'white' => __('White', 'express-checkout'),
                        'black' => __('Black', 'express-checkout')
                    ),
                ),
                'cart_style_shape' => array(
                    'title' => __('Button Shape', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_cart_button_settings',
                    'description' => __('Set the shape you would like to use for the buttons.', 'express-checkout'),
                    'desc_tip' => true,
                    'default' => 'rect',
                    'options' => array(
                        'rect' => __('Rect (Recommended)', 'express-checkout'),
                        'pill' => __('Pill', 'express-checkout')
                    ),
                ),
                'cart_button_label' => array(
                    'title' => __('Button Label', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_cart_button_settings',
                    'description' => __('Set the label type you would like to use for the PayPal button.', 'express-checkout'),
                    'default' => 'paypal',
                    'desc_tip' => true,
                    'options' => array(
                        'paypal' => __('PayPal (Recommended)', 'express-checkout'),
                        'checkout' => __('Checkout', 'express-checkout'),
                        'buynow' => __('Buy Now', 'express-checkout'),
                        'pay' => __('Pay', 'express-checkout'),
                    ),
                ),
                'cart_button_tagline' => array(
                    'title' => __('Tagline', 'express-checkout'),
                    'type' => 'checkbox',
                    'class' => 'ppcp_cart_button_settings',
                    'default' => 'yes',
                    'label' => __('Enable tagline', 'express-checkout'),
                    'desc_tip' => true,
                    'description' => __(
                            'Add the tagline. This line will only show up, if you select a horizontal layout.', 'express-checkout'
                    ),
                ),
                'checkout_button_settings' => array(
                    'title' => __('Checkout Page', 'express-checkout'),
                    'class' => '',
                    'description' => __('Enable the checkout specific button settings, and the options set will be applied to the PayPal buttons on your checkout page.', 'express-checkout'),
                    'type' => 'title',
                    'class' => '',
                ),
                'enable_checkout_button' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'class' => '',
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal Smart Button on the Checkout page.', 'express-checkout'),
                    'default' => 'yes'
                ),
                'enable_checkout_button_top' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'class' => 'ppcp_checkout_button_settings',
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal Smart Button on the Top of the Checkout page.', 'express-checkout'),
                    'default' => 'no'
                ),
                'checkout_disallowed_funding_methods' => array(
                    'title' => __('Hide Funding Method(s)', 'express-checkout'),
                    'type' => 'multiselect',
                    'class' => 'wc-enhanced-select ppcp_checkout_button_settings',
                    'description' => __('Funding methods selected here will be hidden from buyers during checkout.', 'express-checkout'),
                    'default' => '',
                    'desc_tip' => true,
                    'options' => array(
                        'card' => __('Credit or Debit Card', 'express-checkout'),
                        'credit' => __('PayPal Credit', 'express-checkout'),
                        'bancontact' => __('Bancontact', 'express-checkout'),
                        'blik' => __('BLIK', 'express-checkout'),
                        'eps' => __('eps', 'express-checkout'),
                        'giropay' => __('giropay', 'express-checkout'),
                        'ideal' => __('iDEAL', 'express-checkout'),
                        'mercadopago' => __('Mercado Pago', 'express-checkout'),
                        'mybank' => __('MyBank', 'express-checkout'),
                        'p24' => __('Przelewy24', 'express-checkout'),
                        'sepa' => __('SEPA-Lastschrift', 'express-checkout'),
                        'sofort' => __('Sofort', 'express-checkout'),
                        'venmo' => __('Venmo', 'express-checkout')
                    ),
                ),
                'checkout_button_layout' => array(
                    'title' => __('Button Layout', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_checkout_button_settings',
                    'description' => __('Select Vertical for stacked buttons, and Horizontal for side-by-side buttons.', 'express-checkout'),
                    'default' => 'vertical',
                    'desc_tip' => true,
                    'options' => array(
                        'vertical' => __('Vertical (Recommended)', 'express-checkout'),
                        'horizontal' => __('Horizontal', 'express-checkout'),
                    ),
                ),
                'checkout_style_color' => array(
                    'title' => __('Button Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_checkout_button_settings',
                    'description' => __('Set the color you would like to use for the PayPal button.', 'express-checkout'),
                    'desc_tip' => true,
                    'default' => 'gold',
                    'options' => array(
                        'gold' => __('Gold (Recommended)', 'express-checkout'),
                        'blue' => __('Blue', 'express-checkout'),
                        'silver' => __('Silver', 'express-checkout'),
                        'white' => __('White', 'express-checkout'),
                        'black' => __('Black', 'express-checkout')
                    ),
                ),
                'checkout_style_shape' => array(
                    'title' => __('Button Shape', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_checkout_button_settings',
                    'description' => __('Set the shape you would like to use for the buttons.', 'express-checkout'),
                    'desc_tip' => true,
                    'default' => 'rect',
                    'options' => array(
                        'rect' => __('Rect (Recommended)', 'express-checkout'),
                        'pill' => __('Pill', 'express-checkout')
                    ),
                ),
                'checkout_button_label' => array(
                    'title' => __('Button Label', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_checkout_button_settings',
                    'description' => __('Set the label type you would like to use for the PayPal button.', 'express-checkout'),
                    'default' => 'paypal',
                    'desc_tip' => true,
                    'options' => array(
                        'paypal' => __('PayPal', 'express-checkout'),
                        'checkout' => __('Checkout', 'express-checkout'),
                        'buynow' => __('Buy Now', 'express-checkout'),
                        'pay' => __('Pay', 'express-checkout'),
                    ),
                ),
                'checkout_button_tagline' => array(
                    'title' => __('Tagline', 'express-checkout'),
                    'type' => 'checkbox',
                    'class' => 'ppcp_checkout_button_settings',
                    'default' => 'yes',
                    'label' => __('Enable tagline', 'express-checkout'),
                    'desc_tip' => true,
                    'description' => __(
                            'Add the tagline. This line will only show up, if you select a horizontal layout.', 'express-checkout'
                    ),
                ),
                'mini_cart_button_settings' => array(
                    'title' => __('Mini Cart Page', 'express-checkout'),
                    'class' => '',
                    'description' => __('Enable the Mini Cart specific button settings, and the options set will be applied to the PayPal buttons on your Mini Cart page.', 'express-checkout'),
                    'type' => 'title',
                    'class' => '',
                ),
                'enable_mini_cart_button' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'class' => '',
                    'type' => 'checkbox',
                    'label' => __('Enable PayPal Smart Button on the Mini Cart page.', 'express-checkout'),
                    'default' => 'yes'
                ),
                'mini_cart_disallowed_funding_methods' => array(
                    'title' => __('Hide Funding Method(s)', 'express-checkout'),
                    'type' => 'multiselect',
                    'class' => 'wc-enhanced-select ppcp_mini_cart_button_settings',
                    'description' => __('Funding methods selected here will be hidden from buyers during checkout.', 'express-checkout'),
                    'default' => '',
                    'desc_tip' => true,
                    'options' => array(
                        'card' => __('Credit or Debit Card', 'express-checkout'),
                        'credit' => __('PayPal Credit', 'express-checkout'),
                        'bancontact' => __('Bancontact', 'express-checkout'),
                        'blik' => __('BLIK', 'express-checkout'),
                        'eps' => __('eps', 'express-checkout'),
                        'giropay' => __('giropay', 'express-checkout'),
                        'ideal' => __('iDEAL', 'express-checkout'),
                        'mercadopago' => __('Mercado Pago', 'express-checkout'),
                        'mybank' => __('MyBank', 'express-checkout'),
                        'p24' => __('Przelewy24', 'express-checkout'),
                        'sepa' => __('SEPA-Lastschrift', 'express-checkout'),
                        'sofort' => __('Sofort', 'express-checkout'),
                        'venmo' => __('Venmo', 'express-checkout')
                    ),
                ),
                'mini_cart_button_layout' => array(
                    'title' => __('Button Layout', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_mini_cart_button_settings',
                    'description' => __('Select Vertical for stacked buttons, and Horizontal for side-by-side buttons.', 'express-checkout'),
                    'default' => 'vertical',
                    'desc_tip' => true,
                    'options' => array(
                        'vertical' => __('Vertical (Recommended)', 'express-checkout'),
                        'horizontal' => __('Horizontal', 'express-checkout'),
                    ),
                ),
                'mini_cart_style_color' => array(
                    'title' => __('Button Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_mini_cart_button_settings',
                    'description' => __('Set the color you would like to use for the PayPal button.', 'express-checkout'),
                    'desc_tip' => true,
                    'default' => 'gold',
                    'options' => array(
                        'gold' => __('Gold (Recommended)', 'express-checkout'),
                        'blue' => __('Blue', 'express-checkout'),
                        'silver' => __('Silver', 'express-checkout'),
                        'white' => __('White', 'express-checkout'),
                        'black' => __('Black', 'express-checkout')
                    ),
                ),
                'mini_cart_style_shape' => array(
                    'title' => __('Button Shape', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_mini_cart_button_settings',
                    'description' => __('Set the shape you would like to use for the buttons.', 'express-checkout'),
                    'desc_tip' => true,
                    'default' => 'rect',
                    'options' => array(
                        'rect' => __('Rect (Recommended)', 'express-checkout'),
                        'pill' => __('Pill', 'express-checkout')
                    ),
                ),
                'mini_cart_button_label' => array(
                    'title' => __('Button Label', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select ppcp_mini_cart_button_settings',
                    'description' => __('Set the label type you would like to use for the PayPal button.', 'express-checkout'),
                    'default' => 'mini_cart',
                    'desc_tip' => true,
                    'options' => array(
                        'paypal' => __('PayPal (Recommended)', 'express-checkout'),
                        'checkout' => __('Checkout', 'express-checkout'),
                        'buynow' => __('Buy Now', 'express-checkout'),
                        'pay' => __('Pay', 'express-checkout'),
                    ),
                ),
                'mini_cart_button_tagline' => array(
                    'title' => __('Tagline', 'express-checkout'),
                    'type' => 'checkbox',
                    'class' => 'ppcp_mini_cart_button_settings',
                    'default' => 'yes',
                    'label' => __('Enable tagline', 'express-checkout'),
                    'desc_tip' => true,
                    'description' => __(
                            'Add the tagline. This line will only show up, if you select a horizontal layout.', 'express-checkout'
                    ),
                ),
            );

            $order_review_page_settings = array(
                'order_review_page' => array(
                    'title' => __('Order Review Page options', 'express-checkout'),
                    'type' => 'title',
                    'description' => '',
                    'class' => 'ppcp_separator_heading',
                ),
                'order_review_page_title' => array(
                    'title' => __('Page Title', 'express-checkout'),
                    'type' => 'text',
                    'description' => __('Set the Page Title value you would like used on the PayPal Checkout order review page.', 'express-checkout'),
                    'default' => __('Confirm your PayPal order', 'express-checkout'),
                    'desc_tip' => true,
                ),
                'order_review_page_description' => array(
                    'title' => __('Description', 'express-checkout'),
                    'type' => 'text',
                    'desc_tip' => true,
                    'description' => __('Set the Description you would like used on the PayPal Checkout order review page.', 'express-checkout'),
                    'default' => __("<strong>You're almost done!</strong><br>Review your information before you place your order.", 'express-checkout'),
                ),
                'order_review_page_button_text' => array(
                    'title' => __('Button Text', 'express-checkout'),
                    'type' => 'text',
                    'description' => __('Set the Button Text you would like used on the PayPal Checkout order review page.', 'express-checkout'),
                    'default' => __('Confirm your PayPal order', 'express-checkout'),
                    'desc_tip' => true,
                )
            );

            $pay_later_messaging_settings = array(
                'pay_later_messaging_settings' => array(
                    'title' => __('Pay Later Messaging Settings', 'express-checkout'),
                    'class' => '',
                    'description' => '',
                    'type' => 'title',
                    'class' => 'ppcp_separator_heading',
                ),
                'enabled_pay_later_messaging' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'label' => __('Enable Pay Later Messaging', 'express-checkout'),
                    'type' => 'checkbox',
                    'description' => '<div style="font-size: smaller">Displays Pay Later messaging for available offers. Restrictions apply. <a target="_blank" href="https://developer.paypal.com/docs/business/pay-later/us/commerce-platforms/">See terms and learn more</a></div>',
                    'default' => 'no'
                ),
                'pay_later_messaging_page_type' => array(
                    'title' => __('Page Type', 'express-checkout'),
                    'type' => 'multiselect',
                    'css' => 'width: 100%;',
                    'class' => 'wc-enhanced-select pay_later_messaging_field',
                    'default' => array('home', 'category', 'product', 'cart', 'payment'),
                    'options' => array('home' => __('Home', 'express-checkout'), 'category' => __('Category', 'express-checkout'), 'product' => __('Product', 'express-checkout'), 'cart' => __('Cart', 'express-checkout'), 'payment' => __('Payment', 'express-checkout')),
                    'description' => '<div style="font-size: smaller;">Set the page(s) you want to display messaging on, and then adjust that page\'s display option below.</div>',
                ),
                'pay_later_messaging_home_page_settings' => array(
                    'title' => __('Home Page', 'express-checkout'),
                    'description' => __('Customize the appearance of <a target="_blank" href="https://www.paypal.com/us/business/buy-now-pay-later">Pay Later Messaging</a> on the Home page to promote special financing offers which help increase sales.', 'express-checkout'),
                    'type' => 'title',
                    'class' => 'pay_later_messaging_field pay_later_messaging_home_field',
                ),
                'pay_later_messaging_home_layout_type' => array(
                    'title' => __('Layout Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_home_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'flex',
                    'desc_tip' => true,
                    'options' => array('text' => __('Text Layout', 'express-checkout'), 'flex' => __('Flex Layout', 'express-checkout'))
                ),
                'pay_later_messaging_home_text_layout_logo_type' => array(
                    'title' => __('Logo Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_home_field pay_later_messaging_home_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'primary',
                    'desc_tip' => true,
                    'options' => array('primary' => __('Primary', 'express-checkout'), 'alternative' => __('Alternative', 'express-checkout'), 'inline' => __('Inline', 'express-checkout'), 'none' => __('None', 'express-checkout'))
                ),
                'pay_later_messaging_home_text_layout_logo_position' => array(
                    'title' => __('Logo Position', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_home_field pay_later_messaging_home_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'left',
                    'desc_tip' => true,
                    'options' => array('left' => __('Left', 'express-checkout'), 'right' => __('Right', 'express-checkout'), 'top' => __('Top', 'express-checkout'))
                ),
                'pay_later_messaging_home_text_layout_text_size' => array(
                    'title' => __('Text Size', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_home_field pay_later_messaging_home_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '12',
                    'desc_tip' => true,
                    'options' => array('10' => __('10 px', 'express-checkout'), '11' => __('11 px', 'express-checkout'), '12' => __('12 px', 'express-checkout'), '13' => __('13 px', 'express-checkout'), '14' => __('14 px', 'express-checkout'), '15' => __('15 px', 'express-checkout'), '16' => __('16 px', 'express-checkout'))
                ),
                'pay_later_messaging_home_text_layout_text_color' => array(
                    'title' => __('Text Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_home_field pay_later_messaging_home_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'black',
                    'desc_tip' => true,
                    'options' => array('black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_home_flex_layout_color' => array(
                    'title' => __('Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_home_field pay_later_messaging_home_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'blue',
                    'desc_tip' => true,
                    'options' => array('blue' => __('Blue', 'express-checkout'), 'black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'white-no-border' => __('White (No Border)', 'express-checkout'), 'gray' => __('Gray', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_home_flex_layout_ratio' => array(
                    'title' => __('Ratio', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_home_field pay_later_messaging_home_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '8x1',
                    'desc_tip' => true,
                    'options' => array('1x1' => __('Flexes between 120px and 300px wide', 'express-checkout'), '1x4' => __('160px wide', 'express-checkout'), '8x1' => __('Flexes between 250px and 768px wide', 'express-checkout'), '20x1' => __('Flexes between 250px and 1169px wide', 'express-checkout'))
                ),
                'pay_later_messaging_home_shortcode' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'label' => __('I need a shortcode so that I can place the message in a better spot on Home page.', 'express-checkout'),
                    'type' => 'checkbox',
                    'class' => 'pay_later_messaging_field pay_later_messaging_home_field pay_later_messaging_home_shortcode',
                    'description' => '',
                    'default' => 'no'
                ),
                'pay_later_messaging_home_preview_shortcode' => array(
                    'title' => __('Shortcode', 'express-checkout'),
                    'type' => 'copy_text',
                    'class' => 'pay_later_messaging_field pay_later_messaging_home_field pay_later_messaging_home_preview_shortcode preview_shortcode',
                    'description' => '',
                    'custom_attributes' => array('readonly' => 'readonly'),
                    'button_class' => 'home_copy_text',
                    'default' => '[ppcp_bnpl_message placement="home"]'
                ),
                'pay_later_messaging_category_page_settings' => array(
                    'title' => __('Category Page', 'express-checkout'),
                    'class' => '',
                    'description' => __('Customize the appearance of <a target="_blank" href="https://www.paypal.com/us/business/buy-now-pay-later">Pay Later Messaging</a> on the Category page to promote special financing offers which help increase sales.', 'express-checkout'),
                    'type' => 'title',
                    'class' => 'pay_later_messaging_field pay_later_messaging_category_field',
                ),
                'pay_later_messaging_category_layout_type' => array(
                    'title' => __('Layout Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_category_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'flex',
                    'desc_tip' => true,
                    'options' => array('text' => __('Text Layout', 'express-checkout'), 'flex' => __('Flex Layout', 'express-checkout'))
                ),
                'pay_later_messaging_category_text_layout_logo_type' => array(
                    'title' => __('Logo Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_category_field pay_later_messaging_category_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'primary',
                    'desc_tip' => true,
                    'options' => array('primary' => __('Primary', 'express-checkout'), 'alternative' => __('Alternative', 'express-checkout'), 'inline' => __('Inline', 'express-checkout'), 'none' => __('None', 'express-checkout'))
                ),
                'pay_later_messaging_category_text_layout_logo_position' => array(
                    'title' => __('Logo Position', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_category_field pay_later_messaging_category_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'left',
                    'desc_tip' => true,
                    'options' => array('left' => __('Left', 'express-checkout'), 'right' => __('Right', 'express-checkout'), 'top' => __('Top', 'express-checkout'))
                ),
                'pay_later_messaging_category_text_layout_text_size' => array(
                    'title' => __('Text Size', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_category_field pay_later_messaging_category_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '12',
                    'desc_tip' => true,
                    'options' => array('10' => __('10 px', 'express-checkout'), '11' => __('11 px', 'express-checkout'), '12' => __('12 px', 'express-checkout'), '13' => __('13 px', 'express-checkout'), '14' => __('14 px', 'express-checkout'), '15' => __('15 px', 'express-checkout'), '16' => __('16 px', 'express-checkout'))
                ),
                'pay_later_messaging_category_text_layout_text_color' => array(
                    'title' => __('Text Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_category_field pay_later_messaging_category_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'black',
                    'desc_tip' => true,
                    'options' => array('black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_category_flex_layout_color' => array(
                    'title' => __('Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_category_field pay_later_messaging_category_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'blue',
                    'desc_tip' => true,
                    'options' => array('blue' => __('Blue', 'express-checkout'), 'black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'white-no-border' => __('White (No Border)', 'express-checkout'), 'gray' => __('Gray', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_category_flex_layout_ratio' => array(
                    'title' => __('Ratio', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_category_field pay_later_messaging_category_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '8x1',
                    'desc_tip' => true,
                    'options' => array('1x1' => __('Flexes between 120px and 300px wide', 'express-checkout'), '1x4' => __('160px wide', 'express-checkout'), '8x1' => __('Flexes between 250px and 768px wide', 'express-checkout'), '20x1' => __('Flexes between 250px and 1169px wide', 'express-checkout'))
                ),
                'pay_later_messaging_category_shortcode' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'label' => __('I need a shortcode so that I can place the message in a better spot on category page.', 'express-checkout'),
                    'type' => 'checkbox',
                    'class' => 'pay_later_messaging_field pay_later_messaging_category_field pay_later_messaging_category_shortcode',
                    'description' => '',
                    'default' => 'no'
                ),
                'pay_later_messaging_category_preview_shortcode' => array(
                    'title' => __('Shortcode', 'express-checkout'),
                    'type' => 'copy_text',
                    'class' => 'pay_later_messaging_field pay_later_messaging_category_field pay_later_messaging_category_preview_shortcode preview_shortcode',
                    'description' => '',
                    'button_class' => 'category_copy_text',
                    'custom_attributes' => array('readonly' => 'readonly'),
                    'default' => '[ppcp_bnpl_message placement="category"]'
                ),
                'pay_later_messaging_product_page_settings' => array(
                    'title' => __('Product Page', 'express-checkout'),
                    'description' => __('Customize the appearance of <a target="_blank" href="https://www.paypal.com/us/business/buy-now-pay-later">Pay Later Messaging</a> on the Product page to promote special financing offers which help increase sales.', 'express-checkout'),
                    'type' => 'title',
                    'class' => 'pay_later_messaging_field pay_later_messaging_product_field',
                ),
                'pay_later_messaging_product_layout_type' => array(
                    'title' => __('Layout Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_product_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'text',
                    'desc_tip' => true,
                    'options' => array('text' => __('Text Layout', 'express-checkout'), 'flex' => __('Flex Layout', 'express-checkout'))
                ),
                'pay_later_messaging_product_text_layout_logo_type' => array(
                    'title' => __('Logo Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_product_field pay_later_messaging_product_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'primary',
                    'desc_tip' => true,
                    'options' => array('primary' => __('Primary', 'express-checkout'), 'alternative' => __('Alternative', 'express-checkout'), 'inline' => __('Inline', 'express-checkout'), 'none' => __('None', 'express-checkout'))
                ),
                'pay_later_messaging_product_text_layout_logo_position' => array(
                    'title' => __('Logo Position', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_product_field pay_later_messaging_product_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'left',
                    'desc_tip' => true,
                    'options' => array('left' => __('Left', 'express-checkout'), 'right' => __('Right', 'express-checkout'), 'top' => __('Top', 'express-checkout'))
                ),
                'pay_later_messaging_product_text_layout_text_size' => array(
                    'title' => __('Text Size', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_product_field pay_later_messaging_product_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '12',
                    'desc_tip' => true,
                    'options' => array('10' => __('10 px', 'express-checkout'), '11' => __('11 px', 'express-checkout'), '12' => __('12 px', 'express-checkout'), '13' => __('13 px', 'express-checkout'), '14' => __('14 px', 'express-checkout'), '15' => __('15 px', 'express-checkout'), '16' => __('16 px', 'express-checkout'))
                ),
                'pay_later_messaging_product_text_layout_text_color' => array(
                    'title' => __('Text Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_product_field pay_later_messaging_product_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'black',
                    'desc_tip' => true,
                    'options' => array('black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_product_flex_layout_color' => array(
                    'title' => __('Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_product_field pay_later_messaging_product_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'blue',
                    'desc_tip' => true,
                    'options' => array('blue' => __('Blue', 'express-checkout'), 'black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'white-no-border' => __('White (No Border)', 'express-checkout'), 'gray' => __('Gray', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_product_flex_layout_ratio' => array(
                    'title' => __('Ratio', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_product_field pay_later_messaging_product_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '8x1',
                    'desc_tip' => true,
                    'options' => array('1x1' => __('Flexes between 120px and 300px wide', 'express-checkout'), '1x4' => __('160px wide', 'express-checkout'), '8x1' => __('Flexes between 250px and 768px wide', 'express-checkout'), '20x1' => __('Flexes between 250px and 1169px wide', 'express-checkout'))
                ),
                'pay_later_messaging_product_shortcode' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'label' => __('I need a shortcode so that I can place the message in a better spot on product page.', 'express-checkout'),
                    'type' => 'checkbox',
                    'class' => 'pay_later_messaging_field pay_later_messaging_product_field pay_later_messaging_product_shortcode',
                    'description' => '',
                    'default' => 'no'
                ),
                'pay_later_messaging_product_preview_shortcode' => array(
                    'title' => __('Shortcode', 'express-checkout'),
                    'type' => 'copy_text',
                    'class' => 'pay_later_messaging_field pay_later_messaging_product_field pay_later_messaging_product_preview_shortcode preview_shortcode',
                    'description' => '',
                    'button_class' => 'product_copy_text',
                    'custom_attributes' => array('readonly' => 'readonly'),
                    'default' => '[ppcp_bnpl_message placement="product"]'
                ),
                'pay_later_messaging_cart_page_settings' => array(
                    'title' => __('Cart Page', 'express-checkout'),
                    'description' => __('Customize the appearance of <a target="_blank" href="https://www.paypal.com/us/business/buy-now-pay-later">Pay Later Messaging</a> on the Cart page to promote special financing offers which help increase sales.', 'express-checkout'),
                    'type' => 'title',
                    'class' => 'pay_later_messaging_field pay_later_messaging_cart_field',
                ),
                'pay_later_messaging_cart_layout_type' => array(
                    'title' => __('Layout Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_cart_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'text',
                    'desc_tip' => true,
                    'options' => array('text' => __('Text Layout', 'express-checkout'), 'flex' => __('Flex Layout', 'express-checkout'))
                ),
                'pay_later_messaging_cart_text_layout_logo_type' => array(
                    'title' => __('Logo Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_cart_field pay_later_messaging_cart_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'primary',
                    'desc_tip' => true,
                    'options' => array('primary' => __('Primary', 'express-checkout'), 'alternative' => __('Alternative', 'express-checkout'), 'inline' => __('Inline', 'express-checkout'), 'none' => __('None', 'express-checkout'))
                ),
                'pay_later_messaging_cart_text_layout_logo_position' => array(
                    'title' => __('Logo Position', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_cart_field pay_later_messaging_cart_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'left',
                    'desc_tip' => true,
                    'options' => array('left' => __('Left', 'express-checkout'), 'right' => __('Right', 'express-checkout'), 'top' => __('Top', 'express-checkout'))
                ),
                'pay_later_messaging_cart_text_layout_text_size' => array(
                    'title' => __('Text Size', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_cart_field pay_later_messaging_cart_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '12',
                    'desc_tip' => true,
                    'options' => array('10' => __('10 px', 'express-checkout'), '11' => __('11 px', 'express-checkout'), '12' => __('12 px', 'express-checkout'), '13' => __('13 px', 'express-checkout'), '14' => __('14 px', 'express-checkout'), '15' => __('15 px', 'express-checkout'), '16' => __('16 px', 'express-checkout'))
                ),
                'pay_later_messaging_cart_text_layout_text_color' => array(
                    'title' => __('Text Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_cart_field pay_later_messaging_cart_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'black',
                    'desc_tip' => true,
                    'options' => array('black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_cart_flex_layout_color' => array(
                    'title' => __('Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_cart_field pay_later_messaging_cart_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'blue',
                    'desc_tip' => true,
                    'options' => array('blue' => __('Blue', 'express-checkout'), 'black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'white-no-border' => __('White (No Border)', 'express-checkout'), 'gray' => __('Gray', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_cart_flex_layout_ratio' => array(
                    'title' => __('Ratio', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_cart_field pay_later_messaging_cart_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '8x1',
                    'desc_tip' => true,
                    'options' => array('1x1' => __('Flexes between 120px and 300px wide', 'express-checkout'), '1x4' => __('160px wide', 'express-checkout'), '8x1' => __('Flexes between 250px and 768px wide', 'express-checkout'), '20x1' => __('Flexes between 250px and 1169px wide', 'express-checkout'))
                ),
                'pay_later_messaging_cart_shortcode' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'label' => __('I need a shortcode so that I can place the message in a better spot on cart page.', 'express-checkout'),
                    'type' => 'checkbox',
                    'class' => 'pay_later_messaging_field pay_later_messaging_cart_field pay_later_messaging_cart_shortcode',
                    'description' => '',
                    'default' => 'no'
                ),
                'pay_later_messaging_cart_preview_shortcode' => array(
                    'title' => __('Shortcode', 'express-checkout'),
                    'type' => 'copy_text',
                    'class' => 'pay_later_messaging_field pay_later_messaging_cart_field pay_later_messaging_cart_preview_shortcode preview_shortcode',
                    'description' => '',
                    'button_class' => 'cart_copy_text',
                    'custom_attributes' => array('readonly' => 'readonly'),
                    'default' => '[ppcp_bnpl_message placement="cart"]'
                ),
                'pay_later_messaging_payment_page_settings' => array(
                    'title' => __('Payment Page', 'express-checkout'),
                    'description' => __('Customize the appearance of <a target="_blank" href="https://www.paypal.com/us/business/buy-now-pay-later">Pay Later Messaging</a> on the Payment page to promote special financing offers which help increase sales.', 'express-checkout'),
                    'type' => 'title',
                    'class' => 'pay_later_messaging_field pay_later_messaging_payment_field',
                ),
                'pay_later_messaging_payment_layout_type' => array(
                    'title' => __('Layout Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_payment_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'text',
                    'desc_tip' => true,
                    'options' => array('text' => __('Text Layout', 'express-checkout'), 'flex' => __('Flex Layout', 'express-checkout'))
                ),
                'pay_later_messaging_payment_text_layout_logo_type' => array(
                    'title' => __('Logo Type', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_payment_field pay_later_messaging_payment_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'primary',
                    'desc_tip' => true,
                    'options' => array('primary' => __('Primary', 'express-checkout'), 'alternative' => __('Alternative', 'express-checkout'), 'inline' => __('Inline', 'express-checkout'), 'none' => __('None', 'express-checkout'))
                ),
                'pay_later_messaging_payment_text_layout_logo_position' => array(
                    'title' => __('Logo Position', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_payment_field pay_later_messaging_payment_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'left',
                    'desc_tip' => true,
                    'options' => array('left' => __('Left', 'express-checkout'), 'right' => __('Right', 'express-checkout'), 'top' => __('Top', 'express-checkout'))
                ),
                'pay_later_messaging_payment_text_layout_text_size' => array(
                    'title' => __('Text Size', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_payment_field pay_later_messaging_payment_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '12',
                    'desc_tip' => true,
                    'options' => array('10' => __('10 px', 'express-checkout'), '11' => __('11 px', 'express-checkout'), '12' => __('12 px', 'express-checkout'), '13' => __('13 px', 'express-checkout'), '14' => __('14 px', 'express-checkout'), '15' => __('15 px', 'express-checkout'), '16' => __('16 px', 'express-checkout'))
                ),
                'pay_later_messaging_payment_text_layout_text_color' => array(
                    'title' => __('Text Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_payment_field pay_later_messaging_payment_text_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'black',
                    'desc_tip' => true,
                    'options' => array('black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_payment_flex_layout_color' => array(
                    'title' => __('Color', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_payment_field pay_later_messaging_payment_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => 'blue',
                    'desc_tip' => true,
                    'options' => array('blue' => __('Blue', 'express-checkout'), 'black' => __('Black', 'express-checkout'), 'white' => __('White', 'express-checkout'), 'white-no-border' => __('White (No Border)', 'express-checkout'), 'gray' => __('Gray', 'express-checkout'), 'monochrome' => __('Monochrome', 'express-checkout'), 'grayscale' => __('Grayscale', 'express-checkout'))
                ),
                'pay_later_messaging_payment_flex_layout_ratio' => array(
                    'title' => __('Ratio', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select pay_later_messaging_field pay_later_messaging_payment_field pay_later_messaging_payment_flex_layout_field',
                    'description' => __('', 'express-checkout'),
                    'default' => '8x1',
                    'desc_tip' => true,
                    'options' => array('1x1' => __('Flexes between 120px and 300px wide', 'express-checkout'), '1x4' => __('160px wide', 'express-checkout'), '8x1' => __('Flexes between 250px and 768px wide', 'express-checkout'), '20x1' => __('Flexes between 250px and 1169px wide', 'express-checkout'))
                ),
                'pay_later_messaging_payment_shortcode' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'label' => __('I need a shortcode so that I can place the message in a better spot on payment page.', 'express-checkout'),
                    'type' => 'checkbox',
                    'class' => 'pay_later_messaging_field pay_later_messaging_payment_field pay_later_messaging_payment_shortcode',
                    'description' => '',
                    'default' => 'no'
                ),
                'pay_later_messaging_payment_preview_shortcode' => array(
                    'title' => __('Shortcode', 'express-checkout'),
                    'type' => 'copy_text',
                    'class' => 'pay_later_messaging_field pay_later_messaging_payment_field pay_later_messaging_payment_preview_shortcode preview_shortcode',
                    'description' => '',
                    'button_class' => 'payment_copy_text',
                    'custom_attributes' => array('readonly' => 'readonly'),
                    'default' => '[ppcp_bnpl_message placement="payment"]'
            ));

            $advanced_settings = array(
                'advanced' => array(
                    'title' => __('Advanced Settings', 'express-checkout'),
                    'type' => 'title',
                    'description' => '',
                    'class' => 'ppcp_separator_heading',
                ),
                'brand_name' => array(
                    'title' => __('Brand Name', 'express-checkout'),
                    'type' => 'text',
                    'description' => __('This controls what users see as the brand / company name on PayPal review pages.', 'express-checkout'),
                    'default' => __(get_bloginfo('name'), 'express-checkout'),
                    'desc_tip' => true,
                ),
                'landing_page' => array(
                    'title' => __('Landing Page', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select',
                    'description' => __('The type of landing page to show on the PayPal site for customer checkout. PayPal Account Optional must be checked for this option to be used.', 'express-checkout'),
                    'options' => array('LOGIN' => __('Login', 'express-checkout'),
                        'BILLING' => __('Billing', 'express-checkout'),
                        'NO_PREFERENCE' => __('No Preference', 'express-checkout')),
                    'default' => 'NO_PREFERENCE',
                    'desc_tip' => true,
                ),
                'payee_preferred' => array(
                    'title' => __('Instant Payments ', 'express-checkout'),
                    'type' => 'checkbox',
                    'default' => 'no',
                    'desc_tip' => true,
                    'description' => __(
                            'If you enable this setting, PayPal will be instructed not to allow the buyer to use funding sources that take additional time to complete (for example, eChecks). Instead, the buyer will be required to use an instant funding source, such as an instant transfer, a credit/debit card, or PayPal Credit.', 'express-checkout'
                    ),
                    'label' => __('Require Instant Payment', 'express-checkout'),
                ),
                'enable_advanced_card_payments' => array(
                    'title' => __('Enable/Disable', 'express-checkout'),
                    'type' => 'checkbox',
                    'label' => __('Enable advanced credit and debit card payments', 'express-checkout'),
                    'default' => 'no',
                    'description' => __('Currently PayPal support Unbranded payments in US, AU, UK, FR, IT, DE and ES only. <br> <br>Advanced credit and debit cards requires that your business account be evaluated and approved by PayPal. <br><a target="_blank" href="https://www.sandbox.paypal.com/bizsignup/entry/product/ppcp">Enable for Sandbox Account</a> <span> | </span> <a target="_blank" href="https://www.paypal.com/bizsignup/entry/product/ppcp">Enable for Live Account</a><br>', 'express-checkout'),
                ),
                '3d_secure_contingency' => array(
                    'title' => __('Contingency for 3D Secure', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select',
                    'options' => array(
                        'SCA_WHEN_REQUIRED' => __('3D Secure when required', 'express-checkout'),
                        'SCA_ALWAYS' => __('Always trigger 3D Secure', 'express-checkout'),
                    ),
                    'default' => 'SCA_WHEN_REQUIRED',
                    'desc_tip' => true,
                    'description' => __('3D Secure benefits cardholders and merchants by providing an additional layer of verification using Verified by Visa, MasterCard SecureCode and American Express SafeKey.', 'express-checkout'),
                ),
                'paymentaction' => array(
                    'title' => __('Payment action', 'express-checkout'),
                    'type' => 'select',
                    'class' => 'wc-enhanced-select',
                    'description' => __('Choose whether you wish to capture funds immediately or authorize payment only.', 'express-checkout'),
                    'default' => 'capture',
                    'desc_tip' => true,
                    'options' => array(
                        'capture' => __('Capture', 'express-checkout'),
                        'authorize' => __('Authorize', 'express-checkout'),
                    ),
                ),
                'invoice_prefix' => array(
                    'title' => __('Invoice prefix', 'express-checkout'),
                    'type' => 'text',
                    'description' => __('Please enter a prefix for your invoice numbers. If you use your PayPal account for multiple stores ensure this prefix is unique as PayPal will not allow orders with the same invoice number.', 'express-checkout'),
                    'default' => 'WC-PPCP',
                    'desc_tip' => true,
                ),
                'order_review_page_enable_coupons' => array(
                    'title' => __('Enable/Disable coupons', 'express-checkout'),
                    'type' => 'checkbox',
                    'label' => __('Enable the use of coupon codes', 'express-checkout'),
                    'description' => __('Coupons can be applied from the order review.', 'express-checkout'),
                    'default' => 'no',
                ),
                'soft_descriptor' => array(
                    'title' => __('Credit Card Statement Name', 'express-checkout'),
                    'type' => 'text',
                    'description' => __('The value entered here will be displayed on the buyer\'s credit card statement.', 'express-checkout'),
                    'default' => '',
                    'desc_tip' => true,
                    'custom_attributes' => array( 'maxlength' => '22'),
                ),
                'debug' => array(
                    'title' => __('Debug log', 'express-checkout'),
                    'type' => 'checkbox',
                    'label' => __('Enable logging', 'express-checkout'),
                    'default' => 'no',
                    'description' => sprintf(__('Log PayPal events, such as Webhook, Payment, Refund inside %s Note: this may log personal information. We recommend using this for debugging purposes only and deleting the logs when finished.', 'express-checkout'), '<code>' . WC_Log_Handler_File::get_log_file_path('ppcp_paypal_checkout') . '</code>'),
                )
            );
            if (function_exists('wc_coupons_enabled')) {
                if (!wc_coupons_enabled()) {
                    unset($advanced_settings['order_review_page_enable_coupons']);
                }
            }
            $settings = apply_filters('ppcp_settings', array_merge($default_settings, $button_manager_settings, $pay_later_messaging_settings, $advanced_settings));
            return $settings;
        }

    }

}