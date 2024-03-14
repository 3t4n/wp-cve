<?php
/**
 * Social security number
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use WC_Order;

/**
 * Social security number
 */
class Social_Security_Number {

	/**
	 * Setup
	 */
	public static function setup(): void {
		// Display field value on the order edit page.
		add_action( 'woocommerce_admin_order_data_after_billing_address', __CLASS__ . '::admin_order_billing_details', 10, 1 );

		// Display field value in the order emails.
		add_action( 'woocommerce_email_customer_details', __CLASS__ . '::order_email_details', 20, 1 );

		// Display field value on the thank you page and order page.
		add_filter( 'woocommerce_order_details_after_customer_details', __CLASS__ . '::after_customer_details', 10, 1 );

		$settings   = get_option( 'woocommerce_dropp_is_settings' );
		$enable_ssn = apply_filters(
			'woocommerce_shipping_dropp_is_option',
			$settings['enable_ssn'] ?? '',
			'enable_ssn'
		);

		if ( 'yes' === $enable_ssn ) {
			// Add fields to Billing address.
			add_filter( 'woocommerce_checkout_fields' , __CLASS__. '::checkout_fields', 10, 1 );

			// Validate SSN number.
			add_action( 'woocommerce_after_checkout_validation', __CLASS__ . '::validate_ssn', 10, 2 );
		}
	}

	/**
	 * Validate ssn
	 *
	 * @param $data
	 * @param $error
	 *
	 * @return void
	 */
	public static function validate_ssn( $data, $error ): void {
		if ( empty( $data['billing_dropp_ssn'] ) ) {
			return;
		}
		$ssn = $data['billing_dropp_ssn'];
		if ( ! preg_match( '/^\d{10}$/', $ssn ) ) {
			$error->add( 'billing', __( 'Social security number must be 10 digits.', 'dropp-for-woocommerce' ) );
			return;
		}
		$nums = str_split( $ssn );
		$sum =
			(3 * $nums[0]) +
			(2 * $nums[1]) +
			(7 * $nums[2]) +
			(6 * $nums[3]) +
			(5 * $nums[4]) +
			(4 * $nums[5]) +
			(3 * $nums[6]) +
			(2 * $nums[7]);
		$checksum = (11 - ($sum % 11)) % 11;

		if ($checksum != $nums[8]) {
			$error->add( 'billing', __( 'Invalid social security number.', 'dropp-for-woocommerce' ) );
		}
	}

	/**
	 * Checkout fields
	 *
	 * @param array $fields Checkout fields.
	 *
	 * @return array         Checkout fields.
	 */
	public static function checkout_fields( array $fields ): array {
		// Get the shipping method.
		$shipping_method = Shipping_Method\Dropp::get_instance();

		// Add the new field.
		$fields['billing']['billing_dropp_ssn'] = [
			'type'        => 'textarea',
			'label'       => __( 'Social Security Number', 'dropp-for-woocommerce' ),
			'placeholder' => '0000000000',
			'required'    => $shipping_method->require_ssn,
			'priority'    => 100,
		];

		return $fields;
	}

	/**
	 * After customer details
	 *
	 * @param WC_Order $order   Order.
	 */
	public static function after_customer_details( WC_Order $order ): void {
		$dropp_ssn = $order->get_meta( '_billing_dropp_ssn', true );
		if ( $dropp_ssn ) {
			require dirname( __DIR__ ) . '/templates/ssn/customer-details.php';
		}
	}

	/**
	 * Admin order billing details
	 *
	 * @param WC_Order $order Order.
	 */
	public static function admin_order_billing_details( WC_Order $order ): void {
		$dropp_ssn = $order->get_meta( '_billing_dropp_ssn', true );
		if ( $dropp_ssn ) {
			require dirname( __DIR__ ) . '/templates/ssn/admin-billing-details.php';
		}
	}

	/**
	 * Customer details in the order confirmation
	 *
	 * @param WC_Order $order Order.
	 */
	public static function order_email_details( WC_Order $order ): void {
		$dropp_ssn = $order->get_meta( '_billing_dropp_ssn', true );
		if ( $dropp_ssn ) {
			require dirname( __DIR__ ) . '/templates/ssn/email-order-details.php';
		}
	}
}
