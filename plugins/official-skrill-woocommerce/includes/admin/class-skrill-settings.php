<?php
/**
 * Skrill Plugin Installation Process
 *
 * This class is used for Skrill Setting Tabs
 * Copyright (c) Skrill
 *
 * @class   Skrill_Settings
 * @package Skrill/Classes
 * @located at  /includes/admin/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skrill_Settings
 */
class Skrill_Settings {


	/**
	 * Skrill tab
	 *
	 * @var string
	 */
	public static $skrill_tab = 'skrill_settings';

	/**
	 * Mandatory field
	 *
	 * @var array
	 */
	public static $mandatory_field = array(
		'skrill_merchant_id',
		'skrill_merchant_account',
		'skrill_api_passwd',
		'skrill_secret_word',
		'skrill_recipient_desc',
	);

	/**
	 * Init
	 *
	 * @return void
	 */
	public static function init() {
		if ( Skrill_Payment::get_request_value( 'page' )
			&& 'wc-settings' === Skrill_Payment::get_request_value( 'page' )
			&& Skrill_Payment::get_request_value( 'tab' )
			&& 'skrill_settings' === Skrill_Payment::get_request_value( 'tab' )
		) {
			self::validate_skrill_setting();
		}

		add_filter( 'woocommerce_settings_tabs_array', array( __CLASS__, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_skrill_settings', array( __CLASS__, 'add_settings_page' ) );
		add_action( 'woocommerce_update_options_skrill_settings', array( __CLASS__, 'update_settings' ) );
	}

	/**
	 * Add Skrill Setting Tab
	 * from hook "woocommerce_settings_tabs_array"
	 *
	 * @param  array $woocommerce_tab - woocommerce tab.
	 * @return array
	 */
	public static function add_settings_tab( $woocommerce_tab ) {
		$woocommerce_tab[ self::$skrill_tab ] = __( 'Skrill Settings', 'wc-skrill' );
		return $woocommerce_tab;
	}

	/**
	 * Add Skrill Setting Page
	 * from hook "woocommerce_settings_tabs_" {tab_name}
	 */
	public static function add_settings_page() {
		woocommerce_admin_fields( self::render_skrill_general_setting() );
	}

	/**
	 * Updates Skrill Settings Page
	 * from hook "woocommerce_update_options_" {tab_name}
	 */
	public static function update_settings() {
		woocommerce_update_options( self::render_skrill_general_setting() );
	}

	/**
	 * Render Skrill General Setting
	 *
	 * @return string
	 */
	public static function render_skrill_general_setting() {
		global $skrill_payments;

		wp_enqueue_script(
			'vrpayecommerce_formpayment_script',
			plugins_url(
				'assets/js/skrill-setting.js',
				realpath( __DIR__ . '/../' )
			),
			array(),
			SKRILL_PLUGIN_VERSION,
			true
		);

		$settings = apply_filters(
			'woocommerce_' . self::$skrill_tab,
			array(
				array(
					'title' => 'Skrill ' . __( 'General Setting', 'wc-skrill' ),
					'id'    => 'skrill_general_settings',
					'desc'  => '',
					'type'  => 'title',
				),
				array(
					'title' => __( 'Merchant ID', 'wc-skrill' ) . ' *',
					'id'    => 'skrill_merchant_id',
					'css'   => 'width:25em;',
					'type'  => 'text',
					'desc'  => '<br />' . __( 'Your Skrill customer ID. It is displayed in the upper-right corner of your Skrill account.', 'wc-skrill' ),
				),
				array(
					'title' => __( 'Merchant Account (email)', 'wc-skrill' ) . ' *',
					'id'    => 'skrill_merchant_account',
					'css'   => 'width:25em;',
					'type'  => 'text',
					'desc'  => '<br />' . __( 'Your Skrill account email address.', 'wc-skrill' ),
				),
				array(
					'title' => __( 'Recipient', 'wc-skrill' ) . ' *',
					'id'    => 'skrill_recipient_desc',
					'css'   => 'width:25em;',
					'type'  => 'text',
					'desc'  => '<br />' . __( 'A description to be shown on Quick Checkout. This can be your company name (max 30 characters).', 'wc-skrill' ),
				),
				array(
					'title' => __( 'Logo Url', 'wc-skrill' ),
					'id'    => 'skrill_logo_url',
					'css'   => 'width:25em;',
					'type'  => 'text',
					'desc'  => '<br />' . __( 'The URL of the logo which you would like to appear at the top right of the Skrill page. The logo must be accessible via HTTPS or it will not be shown. For best results use logos with dimensions up to 200px in width and 50px in height.', 'wc-skrill' ),
				),
				array(
					'title' => __( 'Shop Url', 'wc-skrill' ),
					'id'    => 'skrill_shop_url',
					'css'   => 'width:25em;',
					'type'  => 'text',
				),
				array(
					'title' => __( 'MQI/API Password', 'wc-skrill' ) . ' *',
					'id'    => 'skrill_api_passwd',
					'css'   => 'width:25em;',
					'type'  => 'password',
					'desc'  => '<br />' . __( 'When enabled, this feature allows you to issue refunds and check transaction statuses. To set it up, you need to login to your Skrill account and go to Settings -> then, Developer Settings.', 'wc-skrill' ),
				),
				array(
					'title' => __( 'Secret word', 'wc-skrill' ) . ' *',
					'id'    => 'skrill_secret_word',
					'css'   => 'width:25em;',
					'type'  => 'password',
					'desc'  => '<br />' . __( 'This feature is mandatory and ensures the integrity of the data posted back to your servers. To set it up, you need to login to your Skrill account and go to Settings -> then, Developer Settings.', 'wc-skrill' ),
				),
				array(
					'title'   => __( 'Transaction Status Completed', 'wc-skrill' ),
					'id'      => 'skrill_completed_status',
					'css'     => 'width:25em;',
					'type'    => 'select',
					'options' => array(
						'processing' => __( 'Processing', 'wc-skrill' ),
						'completed'  => __( 'Completed', 'wc-skrill' ),
					),
					'default' => 'processing',
					'desc'    => '<br />' . __( 'How the completed transaction status will be handled by the plugin.', 'wc-skrill' ),
					'css'     => 'padding:3px',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'skrill_vendor_script',
				),
			)
		);
		return apply_filters( 'woocommerce_' . self::$skrill_tab, $settings );
	}

	/**
	 * Validate Skrill Setting
	 * validate mandatory fiels and encrypt password
	 */
	public static function validate_skrill_setting() {
		if ( Skrill_Payment::get_request_value( 'save' ) ) {
			$is_mandatory_field = self::is_mandatory_field();

			if ( Skrill_Payment::get_request_value( 'skrill_api_passwd' ) ) {
				$api_pass = get_option( 'skrill_api_passwd' );
				if ( Skrill_Payment::get_request_value( 'skrill_api_passwd' ) !== $api_pass ) {
					$_POST['skrill_api_passwd'] = md5( Skrill_Payment::get_request_value( 'skrill_api_passwd' ) ); // input var okay.
				}
			}
			if ( Skrill_Payment::get_request_value( 'skrill_secret_word' ) ) {
				$secret_word = get_option( 'skrill_secret_word' );
				if ( Skrill_Payment::get_request_value( 'skrill_secret_word' ) !== $secret_word ) {
					$_POST['skrill_secret_word'] = md5( Skrill_Payment::get_request_value( 'skrill_secret_word' ) ); // input var okay.
				}
			}
			$redirect = get_admin_url() . 'admin.php?page=wc-settings&tab=skrill_settings';
			if ( ! $is_mandatory_field ) {
				$error_message = __( 'Please fill in all the mandatory fields', 'wc-skrill' );
				$redirect      = add_query_arg( 'wc_error', rawurlencode( esc_attr( $error_message ) ), $redirect );
				wp_safe_redirect( $redirect );
				exit();
			} else {
				$message  = __( 'Your settings have been saved.', 'woocommerce' );
				$redirect = add_query_arg( 'wc_message', rawurlencode( esc_attr( $message ) ), $redirect );
				wp_safe_redirect( $redirect );
			}
		}
	}

	/**
	 * Validate mandatory fields from skrill settings
	 *
	 * @return bool
	 */
	public static function is_mandatory_field() {
		foreach ( self::$mandatory_field as $value ) {
			if ( '' === trim( Skrill_Payment::get_request_value( $value ) ) ) {
				return false;
			}
		}

		return true;
	}
}

Skrill_Settings::init();
