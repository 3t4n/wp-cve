<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH PayPal Express Checkout for WooCommerce
 * @since  1.0.0
 * @author YITH <plugins@yithemes.com>
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for YITH PayPal EC Gateway.
 */
return apply_filters(
	'yith_paypal_ec_setting_options',
	array(
		'enabled'                 => array(
			'title'   => __( 'Enable/Disable', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'    => 'select',
			'label'   => __( 'Enable YITH Paypal Express Checkout Payment', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'    => 'checkbox',
			'default' => 'no',
		),
		'title'                   => array(
			'title'       => __( 'Title', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'This controls the title that users see during the checkout.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'default'     => __( 'Paypal Express Checkout', 'yith-paypal-express-checkout-for-woocommerce' ),
			'desc_tip'    => true,
		),

		'env'                     => array(
			'title'   => __( 'Environment', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'    => 'select',
			'label'   => __( 'Choose whether to activate the plugin in live or sandbox mode', 'yith-paypal-express-checkout-for-woocommerce' ),
			'options' => array(
				'live'    => __( 'Live', 'yith-paypal-express-checkout-for-woocommerce' ),
				'sandbox' => __( 'Sandbox', 'yith-paypal-express-checkout-for-woocommerce' ),
			),
			'default' => 'live',
		),

		'sandbox_api_credentials' => array(
			'title'       => __( 'Enter your sandbox credentials here and connect your PayPal account', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'title',
			'class'       => 'sandbox',
			'description' => __( 'You have to connect to PayPal. You can connect an existing account or create a new one', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'sandbox_api_username'    => array(
			'title' => __( 'Sandbox API Username', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'text',
		),
		'sandbox_api_password'    => array(
			'title' => __( 'Sandbox API Password', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'password',
		),
		'sandbox_api_signature'   => array(
			'title' => __( 'Sandbox API Signature', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'text',
		),
		'sandbox_api_subject'     => array(
			'title'       => __( 'Sandbox API Subject', 'yith-paypal-express-checkout-for-woocommerce' ),
			'description' => __( 'If you process transactions on behalf of someone else\'s PayPal account, enter their email address or their protected merchant account ID (also known as payment ID) here. Generally, you must possess API authorizations of the other account to process any operation other than sale transactions.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'text',
		),
		'live_api_credentials'    => array(
			'title'       => __( 'Enter your live credentials and connect your PayPal account', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'title',
			'class'       => 'live',
			'description' => __( 'You have to connect to PayPal. You can connect an existing account or create a new one', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'live_api_username'       => array(
			'title' => __( 'Live API Username', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'text',
		),
		'live_api_password'       => array(
			'title' => __( 'Live API Password', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'password',
		),
		'live_api_signature'      => array(
			'title' => __( 'Live API Signature', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'text',
		),
		'live_api_subject'        => array(
			'title'       => __( 'Live API Subject', 'yith-paypal-express-checkout-for-woocommerce' ),
			'description' => __( 'If you process transactions on behalf of someone else\'s PayPal account, enter their email address or their protected merchant account ID (also known as payment ID) here. Generally, you must possess API authorizations of the other account to process any operation other than sale transactions.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'text',
		),
		'button_ec'               => array(
			'title' => __( 'Choose where to show PayPal Express Checkout option', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'title',
		),
		'on_cart_page'            => array(
			'title'       => __( 'Cart Page', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'checkbox',
			'label'       => __( 'Enable PayPal checkout on the cart page.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'description' => __( 'Enable or disable the option to show PayPal Express Checkout button on the cart page', 'yith-paypal-express-checkout-for-woocommerce' ),
			'default'     => 'no',
		),
		'on_single_product_page'  => array(
			'title'       => __( 'Single Product Page', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'checkbox',
			'label'       => __( 'Enable PayPal checkout on single product page.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'description' => __( 'Show Express Checkout button on each single product page.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'default'     => 'no',
		),
		'on_checkout'             => array(
			'title'       => __( 'Regular Checkout', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'checkbox',
			'label'       => __( 'Show PayPal Express Checkout on WooCommerce checkout page.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'description' => __( 'Show PayPal Express Checkout like any other regular WooCommerce gateway on checkout page.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'default'     => 'yes',
		),
		'checkout_button'         => array(
			'title'       => __( 'Show PayPal button on checkout', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'checkbox',
			'label'       => __( 'Show PayPal Express Checkout Button on WooCommerce checkout page to open PayPal in a popup.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'description' => __( 'Show PayPal Express Checkout like any other regular WooCommerce gateway on checkout page.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'default'     => 'no',
		),
		'gateway_description'     => array(
			'title'       => __( 'Description', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'text',
			'default'     => __( 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'description' => __( 'This is the description that the customer will see on checkout page', 'yith-paypal-express-checkout-for-woocommerce' ),
			'desc_tip'    => true,
		),
		'custom_button'           => array(
			'title' => __( 'Custom Button', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'title',
		),
		'button_label'            => array(
			'title'       => __( 'Button Label', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'select',
			'options'     => array(
				'checkout'       => __( 'PayPal Checkout', 'yith-paypal-express-checkout-for-woocommerce' ),
				'pay'            => __( 'Pay with PayPal', 'yith-paypal-express-checkout-for-woocommerce' ),
				'buynow'         => __( 'Buy Now', 'yith-paypal-express-checkout-for-woocommerce' ),
				'buynow-branded' => __( 'Buy Now (with PayPal logo)', 'yith-paypal-express-checkout-for-woocommerce' ),
				'paypal'         => __( 'PayPal', 'yith-paypal-express-checkout-for-woocommerce' ),
			),
			'default'     => 'checkout',
			'description' => __( 'Pick the button label among the following ones provided by PayPal APIs.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'button_cc_icons'         => array(
			'title'       => __( 'Show the funding icons', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'checkbox',
			'default'     => 'no',
			'description' => __( 'Show a list of funding icons', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'button_size'             => array(
			'title'       => __( 'Button Size', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'select',
			'options'     => array(
				'small'      => __( 'Small', 'yith-paypal-express-checkout-for-woocommerce' ),
				'medium'     => __( 'Medium', 'yith-paypal-express-checkout-for-woocommerce' ),
				'large'      => __( 'Large', 'yith-paypal-express-checkout-for-woocommerce' ),
				'responsive' => __( 'Responsive', 'yith-paypal-express-checkout-for-woocommerce' ),
			),
			'default'     => 'medium',
			'description' => __( 'Select the button size.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'button_style'            => array(
			'title'       => __( 'Button Style', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'select',
			'options'     => array(
				'pill' => __( 'Rounded', 'yith-paypal-express-checkout-for-woocommerce' ),
				'rect' => __( 'Squared', 'yith-paypal-express-checkout-for-woocommerce' ),
			),
			'default'     => 'pill',
			'description' => __( 'Select the style of button.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'button_color'            => array(
			'title'       => __( 'Button Color', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'select',
			'options'     => array(
				'gold'   => __( 'Gold', 'yith-paypal-express-checkout-for-woocommerce' ),
				'blue'   => __( 'Blue', 'yith-paypal-express-checkout-for-woocommerce' ),
				'silver' => __( 'Silver', 'yith-paypal-express-checkout-for-woocommerce' ),
				'black'  => __( 'Black', 'yith-paypal-express-checkout-for-woocommerce' ),
			),
			'default'     => 'gold',
			'description' => __( 'Select the color of button.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),

		'custom_checkout'         => array(
			'title' => __( 'Custom Checkout', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'title',
		),
		'brand_name'              => array(
			'title'       => __( 'Brand Name', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter the company/shop/website name as it will be shown on PayPal checkout.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),

		'logo'                    => array(
			'title'       => __( 'Logo Image', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Add a logo image. Size: 190x60. This image requires an SSL Host.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'header'                  => array(
			'title'       => __( 'Header Image', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Add a header image (750x90). This image requires an SSL Host.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'checkout_style'          => array(
			'title'       => __( 'Checkout Style', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'select',
			'options'     => array(
				'login'   => __( 'Login (PayPal account login)', 'yith-paypal-express-checkout-for-woocommerce' ),
				'billing' => __( 'Billing (No PayPal account)', 'yith-paypal-express-checkout-for-woocommerce' ),
			),
			'default'     => 'login',
			'description' => __( 'Type of PayPal page to show.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),

		'other_setting'           => array(
			'title' => __( 'Other Settings', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'title',
		),
		'log_enabled'             => array(
			'title'       => __( 'Debug Log', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'checkbox',
			'label'       => __( 'Enable logging.', 'yith-paypal-express-checkout-for-woocommerce' ),
			// translators: placeholder URL of log file.
			'description' => sprintf( __( 'Log Paypal Express Checkout events inside <code>%s</code>', 'yith-paypal-express-checkout-for-woocommerce' ), wc_get_log_file_path( 'yith-paypal-ec' ) ),
			'default'     => 'no',
		),
		'ipn_notification'        => array(
			'title'       => __( 'IPN Email Notifications', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'checkbox',
			'class'       => 'ipn_notification',
			'label'       => __( 'Enable IPN email notifications', 'yith-paypal-express-checkout-for-woocommerce' ),
			'description' => __( 'Send notifications when an IPN is received from PayPal indicating refunds, chargebacks and cancellations.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'default'     => 'no',
		),
		'ipn_notification_email'  => array(
			'title'       => __( 'IPN Email Notifications Email', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Choose the email addresses.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'default'     => get_option( 'admin_email' ),
		),
		'invoice_prefix'          => array(
			'title'       => __( 'Invoice Prefix', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'text',
			'default'     => 'YITH-',
			'description' => __( 'Enter a prefix that will be attached to the invoice number. Useful if you have connected the same PayPal account on more shops.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'payments'                => array(
			'title' => __( 'Payments', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'  => 'title',
		),
		'payment_action'          => array(
			'title'       => __( 'Payment Action', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'select',
			'class'       => 'payment_action',
			'options'     => array(
				'authorization' => __( 'Authorize', 'yith-paypal-express-checkout-for-woocommerce' ),
				'sale'          => __( 'Sale', 'yith-paypal-express-checkout-for-woocommerce' ),
			),
			'default'     => 'sale',
			'description' => __( 'Choose whether to capture funds immediately or authorize the payment only.', 'yith-paypal-express-checkout-for-woocommerce' ),
		),
		'instant_payments'        => array(
			'title'       => __( 'Instant Payments', 'yith-paypal-express-checkout-for-woocommerce' ),
			'type'        => 'checkbox',
			'label'       => __( 'Require Instant Payment.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'description' => __( 'Instant Payments option does not allow paying with echecks.', 'yith-paypal-express-checkout-for-woocommerce' ),
			'default'     => 'no',
		),
	)
);
