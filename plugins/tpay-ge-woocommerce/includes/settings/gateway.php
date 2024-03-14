<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     TBC Checkout for WooCommerce
 * @author      Plug and Pay Ltd. http://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       1.0.0
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for TBC Checkout Gateway
 */
return array(
	'enabled'           => array(
		'title'   => __( 'Enable/Disable', 'tbc-checkout' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable TBC E-commerce', 'tbc-checkout' ),
		'default' => 'yes',
	),
	'title'             => array(
		'title'       => __( 'Title', 'tbc-checkout' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'tbc-checkout' ),
		'default'     => __( 'TBC E-commerce', 'tbc-checkout' ),
		'desc_tip'    => true,
	),
	'description'       => array(
		'title'       => __( 'Description', 'tbc-checkout' ),
		'type'        => 'text',
		'description' => __( 'This controls the description which the user sees during checkout.', 'tbc-checkout' ),
		'default'     => __( 'Pay with TBC E-Commerce', 'tbc-checkout' ),
		'desc_tip'    => true,
	),
	'order_button_text' => array(
		'title'       => __( 'Order button text', 'tbc-checkout' ),
		'type'        => 'text',
		'description' => __( 'This controls the order button text which the user sees during checkout.', 'tbc-checkout' ),
		'default'     => __( 'Proceed to TBC E-Commerce', 'tbc-checkout' ),
		'desc_tip'    => true,
	),
	'debug'             => array(
		'title'       => __( 'Debug Log', 'tbc-checkout' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Logging', 'tbc-checkout' ),
		'default'     => 'no',
		/* translators: %s: log file path */
		'description' => sprintf( __( 'Log TBC E-commerce events inside: <code>%s</code>', 'tbc-checkout' ), wc_get_log_file_path( 'tpay_gateway' ) ),
	),
	'client_accounts'   => [
		'type' => 'client_accounts',
	],
	'payment_action'    => [
		'title'       => __( 'Payment Action', 'tbc-checkout' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __( 'Choose whether you wish to capture funds immediately (SMS) or authorize payment only (DMS).', 'tbc-checkout' ),
		'default'     => 'capture',
		'desc_tip'    => true,
		'options'     => [
			'capture'   => __( 'Direct Payment', 'tbc-checkout' ),
			'authorize' => __( 'Pre authorisation/Completion', 'tbc-checkout' ),
		],
	],
	'card_payments'     => [
		'title'   => __( 'Card payments', 'tbc-checkout' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-checkout' ),
		'default' => 'yes',
	],
	'qr_payments'       => [
		'title'   => __( 'QR payments', 'tbc-checkout' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-checkout' ),
		'default' => 'yes',
	],
	'ertguli_payments'  => [
		'title'   => __( 'Ertguli payments', 'tbc-checkout' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-checkout' ),
		'default' => 'yes',
	],
	'apple_payments'    => [
		'title'   => __( 'ApplePay payments', 'tbc-checkout' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-checkout' ),
		'default' => 'yes',
	],
	'ib_payments'       => [
		'title'   => __( 'InternetBank payments', 'tbc-checkout' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable', 'tbc-checkout' ),
		'default' => 'yes',
	],
	'skip_info_message' => [
		'title'       => __( 'Skip transaction results page', 'tbc-checkout' ),
		'type'        => 'checkbox',
		'label'       => __( 'Yes', 'tbc-checkout' ),
		'default'     => 'no',
		'description' => __( 'Skips the bank-side transaction results page after payment and redirects the customer directly to your page.', 'tbc-checkout' ),
	],
);
