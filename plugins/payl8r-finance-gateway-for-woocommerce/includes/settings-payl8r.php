<?php
/**
 * @package WC_PayL8r
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return apply_filters( 'wc_payl8r_settings',
	array(
		'enabled' => array(
			'title'       => __( 'Enable/Disable', 'woocommerce-gateway-payl8r' ),
			'label'       => __( 'Enable PayL8r', 'woocommerce-gateway-payl8r' ),
			'type'        => 'checkbox',
			'description' => '',
			'default'     => 'no',
		),
		'title' => array(
			'title'       => __( 'Title', 'woocommerce-gateway-payl8r' ),
			'type'        => 'text',
			'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-gateway-payl8r' ),
			'default'     => __( 'Buy Now Pay Later', 'woocommerce-gateway-payl8r' ),
			'desc_tip'    => true,
		),
		'description' => array(
			'title'       => __( 'Description', 'woocommerce-gateway-payl8r' ),
			'type'        => 'text',
			'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-gateway-payl8r' ),
			'default'     => __( "Spread the cost of your basket over <b>3</b>,<b>6</b>,<b>9</b>,<b>12</b> months with <b>Payl8r</b> <br><br> <b>Who is Payl8r?</b> <br> We're a UK based Buy Now Pay Later provider specialising in offering flexible payment options. We don't just look at your credit history like everybody else, we look at your affordability so we can say yes more. <br><br> <b>How does it work?</b> <br> You'll be taken to a 60 second application form where you'll fill in your details, pick your payment plan and in most cases you will get an instant decision. Once approved, your order will be fulfilled and on its way to you. <br><br> <b>Click 'Continue to Payment' below.</b> <br><br> Subject to affordability assessments - Application fees will apply. <br><br> Representative Example: If you borrow £200 over 12 months at a fixed monthly interest rate of 4% and an annual rate of 48% and representative annual percentage rate of 127.34% You will pay: Monthly payment: £24.66. Total amount you repay: £295.92 Total charge for credit: £95.92", 'woocommerce-gateway-payl8r' ),
            'desc_tip'    => true,
		),
		'testmode' => array(
			'title'       => __( 'Test mode', 'woocommerce-gateway-payl8r' ),
			'label'       => __( 'Enable Test Mode', 'woocommerce-gateway-payl8r' ),
			'type'        => 'checkbox',
			'description' => __( 'Place the payment gateway in test mode.', 'woocommerce-gateway-payl8r' ),
			'default'     => 'yes',
			'desc_tip'    => true,
		),
		'username' => array(
			'title'       => __( 'Username', 'woocommerce-gateway-payl8r' ),
			'type'        => 'text',
			'description' => __( 'Your PayL8r username.', 'woocommerce-gateway-payl8r' ),
			'default'     => '',
			'desc_tip'    => true,
		),
		'public_key' => array(
			'title'       => __( 'Public Key', 'woocommerce-gateway-payl8r' ),
			'type'        => 'textarea',
			'description' => __( 'Your PayL8r public key.', 'woocommerce-gateway-payl8r' ),
			'default'     => '',
			'desc_tip'    => true,
		),
		'logging' => array(
			'title'       => __( 'Logging', 'woocommerce-gateway-payl8r' ),
			'label'       => __( 'Log debug messages', 'woocommerce-gateway-payl8r' ),
			'type'        => 'checkbox',
			'description' => __( 'Save debug messages to the WooCommerce System Status log.', 'woocommerce-gateway-payl8r' ),
			'default'     => 'no',
			'desc_tip'    => true,
        ),
        'custom_payl8r_checkout_label_css' => array(
            'title'       => __( 'Custom checkout CSS', 'woocommerce-gateway-payl8r' ),
			'label'       => __( 'Custom css to be added to the checkout page', 'woocommerce-gateway-payl8r' ),
			'type'        => 'textarea',
			'description' => __( 'Allows customisation of the checkout label.', 'woocommerce-gateway-payl8r' ),
			'default'     => '.payment_method_payl8r img{height:40px;}',
			'desc_tip'    => true,
        )
	)
);
