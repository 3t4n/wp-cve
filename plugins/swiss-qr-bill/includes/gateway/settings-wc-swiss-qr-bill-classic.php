<?php
if ( !defined('ABSPATH') ) {
    exit();
}
/**
 * Settings for WC_Swiss_Qr_Bill_Classic Gateway.
 *
 * @since      1.1.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/includes/gateway
 * /
 **/


$setting_fields = array(
    'enabled' => array(
        'title' => __('Enable/Disable', 'swiss-qr-bill'),
        'label' => __('Enable Swiss QR bill payments', 'swiss-qr-bill'),
        'type' => 'checkbox',
        'description' => '',
        'default' => 'no',
    ),
    'title' => array(
        'title' => __('Title', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('Payment method title that the customer will see in checkout.', 'swiss-qr-bill'),
        'default' => __('Swiss QR Bill', 'swiss-qr-bill'),
        'desc_tip' => true,
    ),
    'description' => array(
        'title' => __('Description', 'swiss-qr-bill'),
        'type' => 'textarea',
        'description' => __('Payment method description that the customer will see in checkout.', 'swiss-qr-bill'),
        'default' => __('Pay conveniently with a Swiss QR bill.', 'swiss-qr-bill'),
        'desc_tip' => true,
    ),
    'instructions' => array(
        'title' => __('Instructions', 'swiss-qr-bill'),
        'type' => 'textarea',
        'description' => __('Please enter the payment instructions that will be added to the thank you page and to the order confirmation email.', 'swiss-qr-bill'),
        'default' => __('The QR bill is attached to your order confirmation email, please use it to issue your payment with your banking application.', 'swiss-qr-bill'),
        'desc_tip' => true,
    ),
    'classic_iban' => array(
        'title' => __('IBAN*', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('Please enter your IBAN number provided by your bank. This field is required.', 'swiss-qr-bill'),
        'desc_tip' => true,
    ),

    'shop_logo' => array(
        'title' => __('Shop Header/Logo', 'swiss-qr-bill'),
        'type' => 'hidden',
        'description' => __('Please upload the shop logo to be shown on your QR bills.', 'swiss-qr-bill'),
        'desc_tip' => true,
        'default' => get_theme_mod('custom_logo'),
        'class' => 'swiss_qr_bill_shop_logo'
    ),
    'shop_name' => array(
        'title' => __('Shop Name*', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('Please enter the shop name to be shown on your QR bills. This field is required.', 'swiss-qr-bill'),
        'desc_tip' => true,
        'default' => get_bloginfo('name')
    ),
    'shop_street_address_1' => array(
        'title' => __('Shop street & number*', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('Please enter your shop address with street and number to be shown on your QR bills. This field is required.', 'swiss-qr-bill'),
        'desc_tip' => true,
        'default' => get_option('woocommerce_store_address', '')
    ),
    'shop_address_2' => array(
        'title' => __('Shop address line 2', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('You may enter a second address line here to be shown on your QR bills.', 'swiss-qr-bill'),
        'desc_tip' => true,
        'default' => get_option('woocommerce_store_address_2', '')
    ),
    'shop_zipcode' => array(
        'title' => __('Shop zipcode*', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('Please enter the shop zipcode to be shown on your QR bills. This field is required.', 'swiss-qr-bill'),
        'desc_tip' => true,
        'default' => get_option('woocommerce_store_postcode', '')
    ),
    'shop_city' => array(
        'title' => __('Shop city*', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('Please enter the shop city to be shown on your QR bills. This field is required.', 'swiss-qr-bill'),
        'desc_tip' => true,
        'default' => get_option('woocommerce_store_city', '')
    ),
    'shop_telephone' => array(
        'title' => __('Shop telephone', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('Please enter the shop telephone number to be shown on your QR bills.', 'swiss-qr-bill'),
        'desc_tip' => true,
    ),
    'shop_email' => array(
        'title' => __('Shop email', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('Please enter the shop email to be shown on your QR bills.', 'swiss-qr-bill'),
        'desc_tip' => true,
    ),
    'shop_vat_number' => array(
        'title' => __('Shop VAT number', 'swiss-qr-bill'),
        'type' => 'text',
        'description' => __('Please enter the shop VAT number to be shown on your QR bills.', 'swiss-qr-bill'),
        'desc_tip' => true,
    ),
    'login_restriction' => array(
        'title' => __('Login Restriction', 'swiss-qr-bill'),
        'label' => __('Enable Login Restriction', 'swiss-qr-bill'),
        'type' => 'checkbox',
        'description' => 'Please check this box to restrict the Swiss QR bill payment method to registered users only who are logged in. Guest users will not see the QR payment option in checkout.',
        'desc_tip' => true,
        'default' => 'no',
    ),
    'order_restriction' => array(
        'title' => __('Order Restriction', 'swiss-qr-bill'),
        'label' => __('Enable Order Restriction', 'swiss-qr-bill'),
        'type' => 'checkbox',
        'description' => 'Please check this box to restrict the Swiss QR bill payment method to registered users who have completed at least one previous order. All other users will not see the QR payment option in checkout.',
        'desc_tip' => true,
        'default' => 'no',
    ),
);

return apply_filters('wc_swiss_qr_bill_classic_gateway_setting_fields', $setting_fields);
