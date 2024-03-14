<?php

if (!defined('ABSPATH')) {
    exit;
}

$express_checkout_utility = new Express_Checkout_Utility();
$this->is_us = $express_checkout_utility->ex_show_paypal_credit();

$require_ssl = '';
if (!Express_Checkout_Utility::is_ssl_enable()) {
    $require_ssl = __('This image requires an SSL host. Please upload your image to <a target="_blank" href="http://www.sslpic.com">www.sslpic.com</a> and enter the image URL here.', 'express-checkout');
}

return $form_fields = array(
    'enabled' => array(
        'title' => __('Enable/Disable', 'express-checkout'),
        'label' => __('Enable PayPal Express', 'express-checkout'),
        'type' => 'checkbox',
        'description' => '',
        'default' => 'no'
    ),
    'title' => array(
        'title' => __('Title', 'express-checkout'),
        'type' => 'text',
        'description' => __('This controls the title which the user sees during checkout.', 'express-checkout'),
        'default' => __('PayPal Express', 'express-checkout')
    ),
    'description' => array(
        'title' => __('Description', 'express-checkout'),
        'type' => 'textarea',
        'description' => __('This controls the description which the user sees during checkout.', 'express-checkout'),
        'default' => __("Pay via PayPal; you can pay with your credit card if you don't have a PayPal account", 'express-checkout')
    ),
    'api_credentials' => array(
        'title' => __('API Credentials', 'express-checkout'),
        'type' => 'title',
    ),
    'testmode' => array(
        'title' => __('PayPal Sandbox', 'express-checkout'),
        'type' => 'checkbox',
        'label' => __('Enable PayPal Sandbox', 'express-checkout'),
        'default' => 'yes',
        'description' => __('The sandbox is PayPal\'s test environment and is only for use with sandbox accounts created within your <a href="http://developer.paypal.com" target="_blank">PayPal developer account</a>.', 'express-checkout')
    ),
    'sandbox_api_username' => array(
        'title' => __('API Username', 'express-checkout'),
        'type' => 'text',
        'description' => __('Create sandbox accounts and obtain API credentials from within your <a href="http://developer.paypal.com">PayPal developer account</a>.', 'express-checkout'),
        'default' => ''
    ),
    'sandbox_api_password' => array(
        'title' => __('API Password', 'express-checkout'),
        'type' => 'password',
        'default' => ''
    ),
    'sandbox_api_signature' => array(
        'title' => __('API Signature', 'express-checkout'),
        'type' => 'password',
        'default' => ''
    ),
    'api_username' => array(
        'title' => __('API Username', 'express-checkout'),
        'type' => 'text',
        'description' => __('Get your live account API credentials from your PayPal account profile under the API Access section <br />or by using <a target="_blank" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=_login-api-run">this tool</a>.', 'express-checkout'),
        'default' => ''
    ),
    'api_password' => array(
        'title' => __('API Password', 'express-checkout'),
        'type' => 'password',
        'default' => ''
    ),
    'api_signature' => array(
        'title' => __('API Signature', 'express-checkout'),
        'type' => 'password',
        'default' => ''
    ),
    'display_options' => array(
        'title' => __('Display Options', 'express-checkout'),
        'type' => 'title',
    ),
    'checkout_button_style' => array(
        'title' => __('Checkout button style', 'express-checkout'),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'options' => array(
            'image' => __('PayPal "Check out with PayPal" image', 'express-checkout'),
            'button' => __('WooCommerce-style button', 'express-checkout'),
        ),
        'default' => 'image',
    ),
    'hide_standard_checkout_button' => array(
        'title' => __('Standard checkout button', 'express-checkout'),
        'type' => 'checkbox',
        'label' => __('Hide standard checkout button on cart page', 'express-checkout'),
        'default' => 'no',
    ),
    'show_on_cart_page' => array(
        'title' => __('Cart Page', 'express-checkout'),
        'type' => 'checkbox',
        'label' => __('Show express checkout button on shopping cart page', 'express-checkout'),
        'default' => 'no',
    ),
    'show_on_checkout' => array(
        'title' => __('Standard checkout', 'express-checkout'),
        'type' => 'checkbox',
        'label' => __('Show express checkout button on checkout page', 'express-checkout'),
        'default' => 'no',
    ),
    'show_on_product_page' => array(
        'title' => __('Product Page', 'express-checkout'),
        'type' => 'checkbox',
        'label' => __('Show the Express Checkout button on product detail pages.', 'express-checkout'),
        'default' => 'no',
        'description' => __('Allows customers to checkout using PayPal directly from a product page.', 'express-checkout')
    ),
    'invoice_id_prefix' => array(
        'title' => __('Invoice ID Prefix', 'express-checkout'),
        'type' => 'text',
        'description' => __('Add a prefix to the invoice ID sent to PayPal. This can resolve duplicate invoice problems when working with multiple websites on the same PayPal account.', 'express-checkout'),
    ),
    'paypal_account_optional' => array(
        'title' => __('PayPal Account Optional', 'express-checkout'),
        'type' => 'checkbox',
        'label' => __('Allow customers to checkout without a PayPal account using their credit card.', 'express-checkout'),
        'default' => 'no',
        'description' => __('PayPal Account Optional must be turned on in your PayPal account profile under Website Preferences.', 'express-checkout')
    ),
    'landing_page' => array(
        'title' => __('Landing Page', 'express-checkout'),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'description' => __('Type of PayPal page to display as default. PayPal Account Optional must be checked for this option to be used.', 'express-checkout'),
        'options' => array('login' => __('Login', 'express-checkout'),
            'billing' => __('Billing', 'express-checkout')),
        'default' => 'login',
    ),
    'error_email_notify' => array(
        'title' => __('Error Email Notifications', 'express-checkout'),
        'type' => 'checkbox',
        'label' => __('Enable admin email notifications for errors.', 'express-checkout'),
        'default' => 'yes',
        'description' => __('This will send a detailed error email to the WordPress site administrator if a PayPal API error occurs.', 'express-checkout')
    ),
    'error_display_type' => array(
        'title' => __('Error Display Type', 'express-checkout'),
        'type' => 'select',
        'label' => __('Display detailed or generic errors', 'express-checkout'),
        'class' => 'error_display_type_option, wc-enhanced-select',
        'options' => array(
            'detailed' => __('Detailed', 'express-checkout'),
            'generic' => __('Generic', 'express-checkout')
        ),
        'description' => __('Detailed displays actual errors returned from PayPal.  Generic displays general errors that do not reveal details
									and helps to prevent fraudulant activity on your site.', 'express-checkout')
    ),
    'show_paypal_credit' => array(
        'title' => __('Enable PayPal Credit', 'express-checkout'),
        'type' => 'checkbox',
        'label' => __('Show the PayPal Credit button next to the Express Checkout button.', 'express-checkout'),
        'default' => 'yes',
        'description' => ($this->is_us) ? "" : __('Currently disabled because PayPal Credit is only available for U.S and U.K . merchants.', 'express-checkout'),
    ),
    'use_wp_locale_code' => array(
        'title' => __('Use WordPress Locale Code', 'express-checkout'),
        'type' => 'checkbox',
        'label' => __('Pass the WordPress Locale Code setting to PayPal in order to display localized PayPal pages to buyers.', 'express-checkout'),
        'default' => 'yes'
    ),
    'brand_name' => array(
        'title' => __('Brand Name', 'express-checkout'),
        'type' => 'text',
        'description' => __('This controls what users see as the brand / company name on PayPal review pages.', 'express-checkout'),
        'default' => __(get_bloginfo('name'), 'express-checkout')
    ),
    'checkout_logo' => array(
        'title' => __('PayPal Checkout Logo (190x90px)', 'express-checkout'),
        'type' => 'text',
        'description' => __('This controls what users see as the logo on PayPal review pages. ', 'express-checkout') . $require_ssl,
        'default' => ''
    ),
    'checkout_logo_hdrimg' => array(
        'title' => __('PayPal Checkout Banner (750x90px)', 'express-checkout'),
        'type' => 'text',
        'description' => __('This controls what users see as the header banner on PayPal review pages. ', 'express-checkout') . $require_ssl,
        'default' => ''
    ),
    'customer_service_number' => array(
        'title' => __('Customer Service Number', 'express-checkout'),
        'type' => 'text',
        'description' => __('This controls what users see for your customer service phone number on PayPal review pages.', 'express-checkout'),
        'default' => ''
    ),
    'skip_text' => array(
        'title' => __('Express Checkout Message', 'express-checkout'),
        'type' => 'text',
        'description' => __('This message will be displayed next to the PayPal Express Checkout button at the top of the checkout page.'),
        'default' => __('Skip the forms and pay faster with PayPal!', 'express-checkout')
    ),
    'payment_action' => array(
        'title' => __('Payment Action', 'express-checkout'),
        'label' => __('Whether to process as a Sale or Authorization.', 'express-checkout'),
        'class' => 'wc-enhanced-select',
        'description' => __('Sale will capture the funds immediately when the order is placed.  Authorization will authorize the payment but will not capture the funds.  You would need to capture funds through your PayPal account when you are ready to deliver.'),
        'type' => 'select',
        'options' => array(
            'Sale' => 'Sale',
            'Authorization' => 'Authorization',
            'Order' => 'Order'
        ),
        'default' => 'Sale'
    ),
    'skip_review_order' => array(
        'title' => __('Skip Final Review', 'express-checkout'),
        'label' => __('By default, users will be returned from PayPal and presented with a final review page which includes shipping and tax in the order details. Enable this option to eliminate this page in the checkout process.', 'express-checkout'),
        'type' => 'checkbox',
        'description' => __('', 'express-checkout'),
        'default' => 'no'
    ),
    'enable_notifyurl' => array(
        'title' => __('Enable PayPal IPN', 'express-checkout'),
        'label' => __('Enable Instant Payment Notification.', 'express-checkout'),
        'type' => 'checkbox',
        'description' => __('', 'express-checkout'),
        'default' => 'no',
        'class' => 'enable_notifyurl'
    ),
    'notifyurl' => array(
        'title' => __('PayPal IPN URL', 'express-checkout'),
        'type' => 'text',
        'description' => __('Your URL for receiving Instant Payment Notification (IPN) about transactions.', 'express-checkout'),
        'class' => 'notifyurl'
    ),
    'debug' => array(
        'title' => __('Debug Log', 'express-checkout'),
        'label' => __('Enable logging', 'express-checkout'),
        'type' => 'checkbox',
        'description' => sprintf(__('Log PayPal events, inside <code>%s</code>', 'express-checkout'), wc_get_log_file_path('paypal_express')),
        'default' => 'no'
    ),
);
