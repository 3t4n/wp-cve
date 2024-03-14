<?php

namespace WPPayForm\App\Modules\FormComponents;

if (!defined('ABSPATH')) {
    exit;
}

class DemoGateways extends BaseComponent
{
    public $gateWayName = 'pro_gateway';

    public function __construct()
    {
        parent::__construct('pro_gateway_element', 9);
    }

    public function component()
    {
        return array(
            'type' => 'mollie_gateway_element',
            'editor_title' => 'More Gateways In Pro',
            'editor_icon' => '',
            'disabled' => true,
            'disabled_message' => array(
                'mollie_gateway_element' => array(
                    'type' => 'mollie_gateway_element',
                    'editor_title' => 'Mollie Payment',
                    'postion_group' => 'payment_method',
                ),
                'offline_gateway_element' => array(
                    'type' => 'offline_gateway_element',
                    'editor_title' => 'Offline/Cheque Payment',
                    'postion_group' => 'payment_method',
                ),
                'paypal_gateway_element' => array(
                    'type' => 'paypal_gateway_element',
                    'editor_title' => 'Paypal Payment',
                    'postion_group' => 'payment_method',
                ),
                'payrexx_gateway_element' => array(
                    'type' => 'payrexx_gateway_element',
                    'editor_title' => 'Payrexx Payment',
                    'postion_group' => 'payment_method',
                ),
                'paystack_gateway_element' => array(
                    'type' => 'paystack_gateway_element',
                    'editor_title' => 'Paystack Payment',
                    'postion_group' => 'payment_method',
                ),
                'razorpay_gateway_element' => array(
                    'type' => 'razorpay_gateway_element',
                    'editor_title' => 'Razorpay Payment',
                    'postion_group' => 'payment_method',
                ),
                'square_gateway_element' => array(
                    'type' => 'square_gateway_element',
                    'editor_title' => 'Square Payment',
                    'postion_group' => 'payment_method',
                ),
                'sslcommerz_gateway_element' => array(
                    'type' => 'sslcommerz_gateway_element',
                    'editor_title' => 'SSLCommerz Payment',
                    'postion_group' => 'payment_method',
                ),
                'billplz_gateway_element' => array(
                    'type' => 'billplz_gateway_element',
                    'editor_title' => 'Billplz Payment',
                    'postion_group' => 'payment_method',
                ),
                'xendit_gateway_element' => array(
                    'type' => 'xendit_gateway_element',
                    'editor_title' => 'Xendit Payment',
                    'postion_group' => 'payment_method',
                ),
                'flutterwave_gateway_element' => array(
                    'type' => 'flutterwave_gateway_element',
                    'editor_title' => 'Flutterwave Payment',
                    'postion_group' => 'payment_method',
                )

            ),
            'group' => 'payment_method_element',
            'method_handler' => $this->gateWayName,
            'postion_group' => 'payment_method',
            'single_only' => true,
            'editor_elements' => array(
                'info' => array(
                    'type' => 'info_html',
                    'info' => '<h3 style="color: firebrick; text-align: center;">Mollie Payment Method require Pro version of Paymattic. Please install Pro version to make it work.</h3><br />'
                ),
                'label' => array(
                    'label' => 'Field Label',
                    'type' => 'text'
                ),
                'require_shipping_address' => array(
                    'label' => 'Require Shipping Address',
                    'type' => 'switch'
                ),
                'admin_label' => array(
                    'label' => 'Admin Label',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'wrapper_class' => array(
                    'label' => 'Field Wrapper CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
                'element_class' => array(
                    'label' => 'Input Element CSS Class',
                    'type' => 'text',
                    'group' => 'advanced'
                ),
            ),
            'field_options' => array(
                'label' => __('Mollie Payment Gateway', 'wp-payment-form'),
                'require_shipping_address' => 'no'
            )
        );
    }

    public function render($element, $form, $elements)
    {
        return '';
    }
}
