<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname(__FILE__) . './../consts/rapyd-consts.php';

return apply_filters(
	'wc_rapyd_settings',
	array(
		'rapyd_settings_steps' => array(
			'title'       => __( 'Rapyd Payments Plugin Settings', 'rapyd-payments-plugin-for-woocommerce' ),
			'type'        => 'title',
			'description' => '',
		),
		'rapyd_access_key_prod' => array(
			'title' => __('Access key', 'rapyd-payments-plugin-for-woocommerce'),
			'type' => 'text',
			'description' =>  __('Access key of plugin when Client Portal is in production mode.', 'rapyd-payments-plugin-for-woocommerce'),
			'desc_tip'    => true,
		),
		'rapyd_secret_key_prod' => array(
			'title' => __('Secret key', 'rapyd-payments-plugin-for-woocommerce'),
			'type' => 'password',
			'description' =>  __('Secret key of plugin when Client Portal is in production mode.', 'rapyd-payments-plugin-for-woocommerce'),
			'desc_tip'    => true,
		),
		'rapyd_test_mode_enabled' => array(
			'title' => __('Enable test mode', 'rapyd-payments-plugin-for-woocommerce'),
			'type' => 'checkbox',
			'label' => __('Please note: test mode does not support real  transactions.', 'rapyd-payments-plugin-for-woocommerce'),
			'default' => 'no',
			'desc_tip'    => true,
		),
		'rapyd_access_key_test' => array(
			'title' => __('Test access key', 'rapyd-payments-plugin-for-woocommerce'),
			'type' => 'text',
			'description' =>  __('Access key of plugin when Client Portal is in sandbox mode.', 'rapyd-payments-plugin-for-woocommerce'),
			'desc_tip'    => true,
		),
		'rapyd_secret_key_test' => array(
			'title' => __('Test secret key', 'rapyd-payments-plugin-for-woocommerce'),
			'type' => 'password',
			'description' =>  __('Secret key of plugin when Client Portal is in sandbox mode.', 'rapyd-payments-plugin-for-woocommerce'),
			'desc_tip'    => true,
		)

	)
);
