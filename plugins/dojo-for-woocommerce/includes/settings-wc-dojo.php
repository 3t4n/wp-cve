<?php

/**
 * Settings for Dojo for WooCommerce Gateway.
 *
 * @package    Dojo_For_WooCommerce
 * @subpackage Dojo_For_WooCommerce/includes
 * @author     Dojo
 * @link       http://dojo.tech/
 */

defined('ABSPATH') || exit;

return [
	'enabled'          => [
		'title'   => __('Enable/Disable:', 'woocommerce-dojo'),
		'type'    => 'checkbox',
		'label'   => __('Enable ', 'woocommerce-dojo') . $this->method_title,
		'default' => 'yes',
	],

	'module_options'   => [
		'title'       => __('Module Options', 'woocommerce-dojo'),
		'type'        => 'title',
		'description' => sprintf(
			// Translators: %s - Payment method title.
			__('The following options affect how the %s Module is displayed on the frontend.', 'woocommerce-dojo'),
			$this->method_title
		),
	],

	'title' => [
		'title'       => __('Title:', 'woocommerce-dojo'),
		'type'        => 'text',
		'description' => __('Customise the title your customer sees during checkout.', 'woocommerce-dojo'),
		'default'     => "Pay with credit card / debit card",
		'desc_tip'    => true,
	],

	'description'      => [
		'title'       => __('Description:', 'woocommerce-dojo'),
		'type'        => 'textarea',
		'description' => __('Customise the description your customer sees during checkout.', 'woocommerce-dojo'),
		'default'     => __('Powered by <a target="_blank" href="https://dojo.tech">Dojo</a>.', 'woocommerce-dojo'),
		'desc_tip'    => true,
	],

	'order_prefix'     => [
		'title'       => __('Order Prefix:', 'woocommerce-dojo'),
		'type'        => 'text',
		'description' => __('Customise the order prefix that you will see in the Merchant Portal.', 'woocommerce-dojo'),
		'default'     => 'WC-',
		'desc_tip'    => true,
	],

	'gateway_settings' => [
		'title'       => __('Gateway Settings', 'woocommerce-dojo'),
		'type'        => 'title',
		'description' => __('These are the gateway settings to allow you to connect with the Dojo gateway. <a target="_blank" href="https://docs.dojo.tech/payments/development-resources/portal">Read more</a>', 'woocommerce-dojo'),
	],

	'secret_key'       => [
		'title'       => __('Secret API key:', 'woocommerce-dojo'),
		'type'        => 'text',
		'description' => __('This is the secret API key.', 'woocommerce-dojo'),
		'default'     => '',
		'desc_tip'    => true,
	],

	'custom_checkout_title' => [
		'title'       => __('Checkout Page Title:', 'woocommerce-dojo'),
		'type'        => 'text',
		'description' => __('Customise the title your customer sees when hosted Dojo checkout page is shown. If not specified, company trading name will be shown.', 'woocommerce-dojo'),
		'default'     => "",
		'desc_tip'    => true,
	],

	'webhook_title' => [
		'title'       => __('Webhook Settings', 'woocommerce-dojo'),
		'type'        => 'title',
		'description' => __('Add the following webhook endpoint https://<i>yourdomain</i><b>/?wc-api=wc_dojo</b> to your <a target="_blank" href="https://developer.dojo.tech">Dojo Developer portal</a> (opens in a new tab) (if there isn\'t one already).<br>This will enable you to receive notifications on the payment statuses. <a target="_blank" href="https://docs.dojo.tech/payments/plugins/woocommerce/configure#step-3-add-a-webhook-endpoint">Read more</a>.', 'woocommerce-dojo'),
	],

	'webhook_secret'       => [
		'title'       => __('Webhook secret:', 'woocommerce-dojo'),
		'type'        => 'text',
		'description' => __('This is the webhook secret.', 'woocommerce-dojo'),
		'default'     => '',
		'desc_tip'    => true,
	],

	'wallet_enabled'   => [
		'title'   => __('Enable Wallet (Apple / Google Pay):', 'woocommerce-dojo'),
		'type'    => 'checkbox',
		'label'   => ' ',
		'default' => 'no',
	],

	'logging'   => [
		'title'   => __('Enable Logging (Debug only):', 'woocommerce-dojo'),
		'type'    => 'checkbox',
		'label'   => ' ',
		'default' => 'no',
	],

	'itemlines_enabled'   => [
		'title'   => __('Display each order item on the Dojo Checkout page:', 'woocommerce-dojo'),
		'type'    => 'checkbox',
		'description' => __('If unchecked, only the total amount to pay will be shown to the customer', 'woocommerce-dojo'),
		'label'   => ' ',
		'default' => 'no',
		'desc_tip'    => true,
	],
];
