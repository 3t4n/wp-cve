<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.deviodigital.com/
 * @since      1.0
 *
 * @package    DTWC
 * @subpackage DTWC/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	wp_die();
}

/**
 * Define global constants.
 *
 * @since 1.0.0
 */
// Plugin version.
if ( ! defined( 'DTWC_ADMIN_VERSION' ) ) {
	define( 'DTWC_ADMIN_VERSION', '1.2' );
}
if ( ! defined( 'DTWC_ADMIN_NAME' ) ) {
	define( 'DTWC_ADMIN_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}
if ( ! defined( 'DTWC_ADMIN_DIR' ) ) {
	define( 'DTWC_ADMIN_DIR', WP_PLUGIN_DIR . '/' . DTWC_ADMIN_NAME );
}
if ( ! defined( 'DTWC_ADMIN_URL' ) ) {
	define( 'DTWC_ADMIN_URL', WP_PLUGIN_URL . '/' . DTWC_ADMIN_NAME );
}

/**
 * WP-OOP-Settings-API Initializer
 *
 * Initializes the WP-OOP-Settings-API.
 *
 * @since   1.0.0
 */

/**
 * Class `WP_OOP_Settings_API`.
 *
 * @since 1.0.0
 */
require_once DTWC_ADMIN_DIR . '/class-dtwc-admin-settings.php';

/**
 * Actions/Filters
 *
 * Related to all settings API.
 *
 * @since  1.0.0
 */
if ( class_exists( 'DeliveryTimesForWooCommerceAdminSettings' ) ) {

	/**
	 * Load the admin settings on init
	 * 
	 * @return void
	 */
	function dtwc_load_admin_settings() {
		/**
		 * Object Instantiation.
		 *
		 * Object for the class `DeliveryTimesForWooCommerceAdminSettings`.
		 */
		$dtwc_obj = new DeliveryTimesForWooCommerceAdminSettings();

		// Section: Basic Settings.
		$dtwc_obj->add_section(
			array(
				'id'    => 'dtwc_basic',
				'title' => esc_attr__( 'Basic Settings', 'delivery-times-for-woocommerce' ),
			)
		);

		// Section: Business Settings.
		$dtwc_obj->add_section(
			array(
				'id'    => 'dtwc_business',
				'title' => esc_attr__( 'Business Hours', 'delivery-times-for-woocommerce' ),
			)
		);

		// Section: Advanced Settings.
		$dtwc_obj->add_section(
			array(
				'id'    => 'dtwc_advanced',
				'title' => esc_attr__( 'Advanced Settings', 'delivery-times-for-woocommerce' ),
			)
		);

		// Field: Delivery date label.
		$dtwc_obj->add_field(
			'dtwc_basic',
			array(
				'id'      => 'delivery_date_label',
				'type'    => 'text',
				'name'    => esc_attr__( 'Delivery date label', 'delivery-times-for-woocommerce' ),
				'desc'    => esc_attr__( 'The label displayed on checkout page and in order details', 'delivery-times-for-woocommerce' ),
				'default' => esc_attr__( 'Delivery date', 'delivery-times-for-woocommerce' ),
			)
		);

		// Field: Require delivery date.
		$dtwc_obj->add_field(
			'dtwc_basic',
			array(
				'id'   => 'require_delivery_date',
				'type' => 'checkbox',
				'name' => esc_attr__( 'Require delivery date', 'delivery-times-for-woocommerce' ),
				'desc' => esc_attr__( 'Check this box to require customers select a delivery date during checkout', 'delivery-times-for-woocommerce' ),
			)
		);

		// Field: Separator.
		$dtwc_obj->add_field(
			'dtwc_basic',
			array(
				'id'   => 'separator_2',
				'type' => 'separator',
			)
		);

		// Field: Delivery time label.
		$dtwc_obj->add_field(
			'dtwc_basic',
			array(
				'id'      => 'delivery_time_label',
				'type'    => 'text',
				'name'    => esc_attr__( 'Delivery time label', 'delivery-times-for-woocommerce' ),
				'desc'    => esc_attr__( 'The label displayed on checkout page and in order details', 'delivery-times-for-woocommerce' ),
				'default' => esc_attr__( 'Delivery time', 'delivery-times-for-woocommerce' ),
			)
		);

		// Field: Require delivery time.
		$dtwc_obj->add_field(
			'dtwc_basic',
			array(
				'id'   => 'require_delivery_time',
				'type' => 'checkbox',
				'name' => esc_attr__( 'Require delivery time', 'delivery-times-for-woocommerce' ),
				'desc' => esc_attr__( 'Check this box to require customers select a delivery time during checkout', 'delivery-times-for-woocommerce' ),
			)
		);

		// Field: Separator.
		$dtwc_obj->add_field(
			'dtwc_basic',
			array(
				'id'   => 'separator_1',
				'type' => 'separator',
			)
		);

		// Field: Pre-order days.
		$dtwc_obj->add_field(
			'dtwc_basic',
			array(
				'id'                => 'preorder_days',
				'type'              => 'number',
				'name'              => esc_attr__( 'Pre-order days', 'delivery-times-for-woocommerce' ),
				'desc'              => esc_attr__( 'How many days ahead are customers allowed to place an order? (leave blank for no limit)', 'delivery-times-for-woocommerce' ),
				'default'           => '',
				'sanitize_callback' => 'intval',
			)
		);

		// Field: Preparation days.
		$dtwc_obj->add_field(
			'dtwc_basic',
			array(
				'id'                => 'prep_days',
				'type'              => 'number',
				'name'              => esc_attr__( 'Delivery days prep', 'delivery-times-for-woocommerce' ),
				'desc'              => esc_attr__( 'How many days notice do you require for delivery? (leave blank to allow same-day delivery)', 'delivery-times-for-woocommerce' ),
				'default'           => '',
				'sanitize_callback' => 'intval',
			)
		);

		// Field: Delivery time prep.
		$dtwc_obj->add_field(
			'dtwc_basic',
			array(
				'id'                => 'prep_time',
				'type'              => 'number',
				'name'              => esc_attr__( 'Delivery time prep', 'delivery-times-for-woocommerce' ),
				'desc'              => esc_attr__( 'How many hours notice do you require for delivery? (useful for same-day delivery)', 'delivery-times-for-woocommerce' ),
				'default'           => '',
				'sanitize_callback' => 'intval',
			)
		);

		// Array: Delivery days.
		$delivery_days = array(
			'sunday'    => esc_attr__( 'Sunday', 'delivery-times-for-woocommerce' ),
			'monday'    => esc_attr__( 'Monday', 'delivery-times-for-woocommerce' ),
			'tuesday'   => esc_attr__( 'Tuesday', 'delivery-times-for-woocommerce' ),
			'wednesday' => esc_attr__( 'Wednesday', 'delivery-times-for-woocommerce' ),
			'thursday'  => esc_attr__( 'Thursday', 'delivery-times-for-woocommerce' ),
			'friday'    => esc_attr__( 'Friday', 'delivery-times-for-woocommerce' ),
			'saturday'  => esc_attr__( 'Saturday', 'delivery-times-for-woocommerce' ),
		);

		// Filter delivery days.
		$delivery_days = apply_filters( 'dtwc_settings_delivery_days_options', $delivery_days );

		// Field: Multicheck.
		$dtwc_obj->add_field(
			'dtwc_business',
			array(
				'id'      => 'delivery_days',
				'type'    => 'multicheck',
				'name'    => esc_attr__( 'Delivery Days', 'delivery-times-for-woocommerce' ),
				'desc'    => esc_attr__( 'Select the days of the week that you are open for business', 'delivery-times-for-woocommerce' ),
				'options' => $delivery_days,
			)
		);

		// Field: Opening time.
		$dtwc_obj->add_field(
			'dtwc_business',
			array(
				'id'   => 'opening_time',
				'type' => 'time',
				'name' => esc_attr__( 'Opening time', 'delivery-times-for-woocommerce' ),
				'desc' => esc_attr__( 'What time does your business start delivering orders?', 'delivery-times-for-woocommerce' ),
			)
		);

		// Field: Opening time.
		$dtwc_obj->add_field(
			'dtwc_business',
			array(
				'id'   => 'closing_time',
				'type' => 'time',
				'name' => esc_attr__( 'Closing time', 'delivery-times-for-woocommerce' ),
				'desc' => esc_attr__( 'What time does your business stop delivering orders?', 'delivery-times-for-woocommerce' ),
			)
		);

		// Field: Delivery time edit order display.
		$dtwc_obj->add_field(
			'dtwc_advanced',
			array(
				'id'      => 'delivery_time_edit_order_display',
				'type'    => 'select',
				'name'    => esc_attr__( 'Delivery time admin placement', 'delivery-times-for-woocommerce' ),
				'desc'    => esc_attr__( 'Choose where to display the delivery time on the Edit Order screen', 'delivery-times-for-woocommerce' ),
				'options' => array(
					'billing'  => esc_attr__( 'After the billing address', 'delivery-times-for-woocommerce' ),
					'shipping' => esc_attr__( 'After the shipping address', 'delivery-times-for-woocommerce' )
				),
				'default' => 'shipping',
			)
		);
		// Field: Delivery time checkout display.
		$dtwc_obj->add_field(
			'dtwc_advanced',
			array(
				'id'      => 'delivery_time_checkout_display',
				'type'    => 'select',
				'name'    => esc_attr__( 'Delivery time checkout placement', 'delivery-times-for-woocommerce' ),
				'desc'    => esc_attr__( 'Choose where to display the delivery time on the checkout screen', 'delivery-times-for-woocommerce' ),
				'options' => array(
					'after_billing'  => esc_attr__( 'After the billing address', 'delivery-times-for-woocommerce' ),
					'after_shipping' => esc_attr__( 'After the shipping address', 'delivery-times-for-woocommerce' ),
					'after_notes'    => esc_attr__( 'After the order notes', 'delivery-times-for-woocommerce' )
				),
				'default' => 'after_billing',
			)
		);
		// Field: Remove delivery time from emails.
		$dtwc_obj->add_field(
			'dtwc_advanced',
			array(
				'id'   => 'remove_delivery_time_from_emails',
				'type' => 'checkbox',
				'name' => esc_attr__( 'Remove delivery time from customer emails', 'delivery-times-for-woocommerce' ),
				'desc' => esc_attr__( 'Check this box to remove the delivery date and time from emails', 'delivery-times-for-woocommerce' ),
			)
		);
	}
	add_action( 'init', 'dtwc_load_admin_settings' );
}
