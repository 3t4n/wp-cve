<?php
/**
 * PeachPay Stripe integration hook functions. Utility functions should not be defined here.
 *
 * @phpcs:disable WordPress.Security.NonceVerification.Recommended
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

require_once PEACHPAY_ABSPATH . 'core/error-reporting.php';

/**
 * Initializes cart tables.
 * Also checks which version they were updated to avoid unnecessary databases touches.
 */
function peachpay_analytics_initialize() {
	$analytics_option = get_option( 'peachpay_analytics' );
	if ( $analytics_option && array_key_exists( 'tables_initialized', $analytics_option ) && $analytics_option['tables_initialized'] ) {
		return;
	}

	PeachPay_Analytics_Database::create_uninitialized_tables();

	if ( $analytics_option && is_array( $analytics_option ) ) {
		$analytics_option['tables_initialized'] = true;

		update_option( 'peachpay_analytics', $analytics_option );
	} else {
		$analytics_option = array(
			'tables_initialized' => true,
		);

		add_option( 'peachpay_analytics', $analytics_option );
	}
}

/**
 * Takes current cart data and updates database accordingly.
 */
function peachpay_analytics_update_cart() {
	global $wpdb;

	$cart_meta_table = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_meta_table' );

	if ( ! PeachPay_Analytics_Database::check_table_existence( $cart_meta_table ) ) {
		PeachPay_Analytics_Database::create_uninitialized_tables();
	}

	$cart_id    = PeachPay_Analytics_Database::session_value( 'cart-id' );
	$cart_email = PeachPay_Analytics_Database::session_value( 'cart-email' );
	$cart_email = ! $cart_email ? null : $cart_email;
	$returning  = PeachPay_Analytics_Database::session_value( 'made-purchase' );

	// check on customer_id (if it's there, attach order to customer)
	$user_info      = wp_get_current_user();
	$customer_id    = $user_info->ID;
	$customer_email = ! $cart_email && $user_info->user_email && strlen( $user_info->user_email ) ? $user_info->user_email : $cart_email;

	// Compute user operating system and browser:
	$user_agent_content = PeachPay_Analytics_Database::explode_user_agent_information();

	try {
		// Confirm cart id existence.
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
		$cart_id = $wpdb->get_var( $wpdb->prepare( "SELECT cart_id from {$cart_meta_table} WHERE cart_id=%d", $cart_id ) );

		if ( ! $cart_id || $returning ) {
			// Insert instead.
			$wpdb->query( "INSERT INTO {$cart_meta_table} (order_id) VALUES(NULL);" );
			$cart_id = $wpdb->get_var( 'SELECT LAST_INSERT_ID();' );
			PeachPay_Analytics_Database::session_value( 'cart-id', $cart_id );
			PeachPay_Analytics_Database::session_value( 'made-purchase', 0 );

			// Update cart tracking analytics
			PeachPay_Analytics_Database::update_analytics(
				array(
					'tab'     => 'abandoned_carts',
					'section' => array(
						'cart_count',
						'cart_interval',
					),
					'title'   => $customer_email ? 'Recoverable' : 'Unrecoverable',
					'value'   => 1,
				)
			);
			// Update browser and operating_system
			PeachPay_Analytics_Database::update_analytics(
				array(
					'tab'     => 'device_breakdown',
					'section' => array(
						'browser_count',
						'browser_interval',
						'operating_system_count',
						'operating_system_interval',
					),
					'title'   => array(
						$user_agent_content['browser'],
						$user_agent_content['browser'],
						$user_agent_content['operating_system'],
						$user_agent_content['operating_system'],
					),
					'value'   => 1,
				)
			);
		}

		// Update in case email has changed for known customer or if browser has changed in general.
		if ( $customer_id ) {
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$cart_meta_table} SET customer_id=%d, browser=%s,
					operating_system=%s, email=%s WHERE cart_id=%d;",
					array(
						$customer_id,
						$user_agent_content['browser'],
						$user_agent_content['operating_system'],
						$customer_email,
						$cart_id,
					)
				)
			);
		} else {
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$cart_meta_table} SET browser=%s,
					operating_system=%s, email=%s WHERE cart_id=%d;",
					array(
						$user_agent_content['browser'],
						$user_agent_content['operating_system'],
						$customer_email,
						$cart_id,
					)
				)
			);
		}

		$cart_table     = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_table' );
		$prev_cart_data = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT cart_total, email FROM {$cart_table} INNER JOIN
				{$cart_meta_table} ON {$cart_meta_table}.cart_id={$cart_table}.cart_id WHERE
				{$cart_meta_table}.cart_id=%d;",
				$cart_id
			)
		);

		$prev_cart_total = $prev_cart_data && is_object( $prev_cart_data ) ? $prev_cart_data->cart_total : 0;
		$prev_cart_email = $prev_cart_data && is_object( $prev_cart_data ) ? $prev_cart_data->email : null;

		$cart_total = 0;
		// used to prevent grouped products from double inserting
		$current_products = WC()->cart->cart_contents;

		// Update cart data.
		$cart_contents_table = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_contents_table' );
		foreach ( $current_products as $product ) {
			$variation_id = array_key_exists( 'variation_id', $product ) ? intval( $product['variation_id'] ) : 0;
			$product_id   = array_key_exists( 'product_id', $product ) ? intval( $product['product_id'] ) : 0;
			// Note: I used floatval for the cast here because quantities can be floats with some plugins
			$qty = array_key_exists( 'quantity', $product ) ? floatval( $product['quantity'] ) : 0;

			$line_total  = $variation_id ? new WC_Product_Variation( $variation_id ) : new WC_Product( $product_id );
			$cart_total += floatval( $line_total->get_price() ) * $qty;

			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$cart_contents_table} (cart_id, item_id, variation_id, qty) VALUES (%d, %d, %d, %d)
					ON DUPLICATE KEY UPDATE qty=%d;",
					array(
						$cart_id,
						$product_id,
						$variation_id,
						$qty,
						$qty,
					)
				)
			);
		}

		$wpdb->query(
			$wpdb->prepare(
				"INSERT INTO {$cart_table} (cart_id, cart_total, date_created, date_updated) VALUES (
					%d, %f, NOW(), NOW()) ON DUPLICATE KEY UPDATE
					cart_total=%f, date_updated=NOW();",
				array(
					$cart_id,
					$cart_total,
					$cart_total,
				)
			)
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery

		// Update cart tracking analytics
		$customer_email = $customer_email && strlen( $customer_email ) ? $customer_email : $prev_cart_email;
		PeachPay_Analytics_Database::update_analytics(
			array(
				'tab'      => 'abandoned_carts',
				'section'  => array(
					'volume_count',
					'volume_interval',
				),
				'title'    => $customer_email ? 'Recoverable' : 'Unrecoverable',
				'currency' => get_woocommerce_currency(),
				'value'    => floor( ( $cart_total - $prev_cart_total ) * 100 ) / 100,
			)
		);

		return 1;
	} catch ( Exception $e ) {
		peachpay_notify_error( $e );
	}
}

/**
 * Updates the email of a cart.
 *
 * @param String $email The email to set.
 */
function peachpay_analytics_update_email( $email ) {
	global $wpdb;

	$cart_meta_table = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_meta_table' );
	$cart_table      = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_table' );
	$cart_id         = PeachPay_Analytics_Database::session_value( 'cart-id' );

	if ( ! $email || ! strlen( $email ) ) {
		return;
	}

	PeachPay_Analytics_Database::session_value( 'cart-email', $email );
	try {
		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
		$cart = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT email, cart_total FROM {$cart_table} INNER JOIN {$cart_meta_table} ON
				{$cart_meta_table}.cart_id={$cart_table}.cart_id WHERE {$cart_meta_table}.cart_id=%s;",
				$cart_id
			)
		);

		if ( ! is_object( $cart ) ) {
			return;
		}

		$cart_total = $cart->cart_total;

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$cart_meta_table} SET email=%s WHERE cart_id=%s;",
				array(
					$email,
					$cart_id,
				)
			)
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery

		// Make sure to not count the same email twice.
		if ( $cart->email ) {
			return;
		}

		// Update analytics.
		PeachPay_Analytics_Database::update_analytics(
			array(
				'tab'      => 'abandoned_carts',
				'section'  => array(
					'cart_count',
					'cart_interval',
					'volume_count',
					'volume_interval',
				),
				'currency' => array(
					null,
					null,
					get_woocommerce_currency(),
					get_woocommerce_currency(),
				),
				'title'    => 'Unrecoverable',
				'value'    => array(
					-1,
					-1,
					-1 * ( floor( $cart_total * 100 ) / 100 ),
					-1 * ( floor( $cart_total * 100 ) / 100 ),
				),
			)
		);
		PeachPay_Analytics_Database::update_analytics(
			array(
				'tab'      => 'abandoned_carts',
				'section'  => array(
					'cart_count',
					'cart_interval',
					'volume_count',
					'volume_interval',
				),
				'currency' => array(
					null,
					null,
					get_woocommerce_currency(),
					get_woocommerce_currency(),
				),
				'title'    => 'Recoverable',
				'value'    => array(
					1,
					1,
					floor( $cart_total * 100 ) / 100,
					floor( $cart_total * 100 ) / 100,
				),
			)
		);
	} catch ( Exception $e ) {
		peachpay_notify_error( $e );
	}
}

/**
 * Listens for the native checkout billing information update.
 */
function peachpay_analytics_wc_ajax_update_billing() {
	// As seen with the logic below, it does not matter if there is malicious data in the
	// fields as they will be sanatized and put into an SQL prepared statement before DB insertion
	//phpcs:ignore
	$post = $_POST;

	if ( empty( $post ) || ! array_key_exists( 'post_data', $post ) ) {
		return;
	}

	// Grab cart id.
	$cart_id = PeachPay_Analytics_Database::session_value( 'cart-id' );

	if ( ! $cart_id ) { // Something may be wrong.
		return;
	}

	$billing_components = explode( '&', $post['post_data'] );
	foreach ( $billing_components as $billing_component ) {
		$billing_component = explode( '=', $billing_component );

		$billing_key   = stripslashes( sanitize_text_field( wp_unslash( urldecode( $billing_component[0] ) ) ) );
		$billing_value = stripslashes( sanitize_text_field( wp_unslash( urldecode( $billing_component[1] ) ) ) );

		if ( 0 === strcmp( 'billing_email', $billing_key ) ) {
			peachpay_analytics_update_email( $billing_value );
		} elseif ( 0 === strcmp( 'payment_method', $billing_key ) ) {
			peachpay_analytics_update_payment_method( $billing_value );
		} elseif ( array_key_exists( $billing_key, PeachPay_Analytics_Database::$billing_title_convert ) ) {
			PeachPay_Analytics_Database::update_billing( PeachPay_Analytics_Database::$billing_title_convert[ $billing_key ], $billing_value );
		}
	}
}

/**
 * Updates payment method of a specific order and updates analytics for that payment method.
 * This is also used for currency updates.
 *
 * @param string $payment_method - what payment method the user has enabled ( square, stripe, etc. ).
 */
function peachpay_analytics_update_payment_method( $payment_method ) {
	global $wpdb;

	if ( ! class_exists( 'PeachPay_Analytics_Database' ) ) {
		return;
	}

	$cart_meta_table = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_meta_table' );

	$cart_id = PeachPay_Analytics_Database::session_value( 'cart-id' );
	if ( ! $cart_id ) {
		return;
	}

	try {
		$payment_gateways     = WC()->payment_gateways()->payment_gateways();
		$payment_method_title = $payment_method;
		if ( isset( $payment_gateways[ $payment_method_title ] ) && method_exists( $payment_gateways[ $payment_method_title ], 'get_method_title' ) ) {
			$payment_method_title = $payment_gateways[ $payment_method_title ]->get_method_title();
		}

		$cart_table = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_table' );

		// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
		$current_billing = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT email, currency, cart_total FROM {$cart_meta_table} INNER JOIN
				{$cart_table} ON {$cart_meta_table}.cart_id={$cart_table}.cart_id WHERE {$cart_meta_table}.cart_id=%d;",
				$cart_id
			)
		);

		$new_currency_code     = get_woocommerce_currency();
		$current_currency_code = 'object' === gettype( $current_billing ) && $current_billing->currency ? $current_billing->currency : $new_currency_code;

		if ( 'object' === gettype( $current_billing ) && 0 !== strcmp( $current_currency_code, $new_currency_code ) ) {
			$current_products      = WC()->cart->cart_contents;
			$current_cart_subtotal = $current_billing->cart_total;
			$update_cart_subtotal  = 0;

			$current_currency = null;
			$new_currency     = null;

			$all_currencies = peachpay_get_settings_option( 'peachpay_currency_options', 'selected_currencies', array() );
			foreach ( $all_currencies as $currency => $data ) {
				if ( 0 === strcmp( $current_currency_code, $data['name'] ) ) {
					$current_currency = $data;
				}

				if ( 0 === strcmp( $new_currency_code, $data['name'] ) ) {
					$new_currency = $data;
				}

				if ( $current_currency && $new_currency ) {
					break;
				}
			}

			if ( $current_currency && 'up' === $current_currency['round'] ) {
				$current_currency['round'] = PHP_ROUND_HALF_UP;
			} elseif ( $current_currency && 'down' === $current_currency['round'] ) {
				$current_currency['round'] = PHP_ROUND_HALF_DOWN;
			} elseif ( $current_currency ) {
				$current_currency['round'] = 0;
			}

			if ( 'up' === $new_currency['round'] ) {
				$new_currency['round'] = PHP_ROUND_HALF_UP;
			} elseif ( 'down' === $new_currency['round'] ) {
				$new_currency['round'] = PHP_ROUND_HALF_DOWN;
			} else {
				$new_currency['round'] = 0;
			}

			// Convert to base currency and then convert to correct currency.
			foreach ( $current_products as $product ) {
				$variation_id = array_key_exists( 'variation_id', $product ) ? $product['variation_id'] : 0;
				$product_id   = array_key_exists( 'product_id', $product ) ? $product['product_id'] : 0;
				$qty          = array_key_exists( 'quantity', $product ) ? $product['quantity'] : 0;

				$line_total = $variation_id ? new WC_Product_Variation( $variation_id ) : new WC_Product( $product_id );
				if ( $product_id ) {
					wc_delete_product_transients( $product_id );
				}

				$update_cart_subtotal += $line_total->get_price() * $qty;
			}

			// Update cart tracking analytics.
			$customer_email = $current_billing->email && strlen( $current_billing->email ) ? $current_billing->email : null;
			PeachPay_Analytics_Database::update_analytics(
				array(
					'tab'      => 'abandoned_carts',
					'section'  => array(
						'volume_count',
						'volume_interval',
						'volume_count',
						'volume_interval',
					),
					'title'    => $customer_email ? 'Recoverable' : 'Unrecoverable',
					'currency' => array(
						$current_currency_code,
						$current_currency_code,
						$new_currency_code,
						$new_currency_code,
					),
					'value'    => array(
						-1 * $current_cart_subtotal,
						-1 * $current_cart_subtotal,
						floor( $update_cart_subtotal * 100 ) / 100,
						floor( $update_cart_subtotal * 100 ) / 100,
					),
				)
			);

			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$cart_table} SET cart_total=%f WHERE cart_id=%d;",
					array(
						$update_cart_subtotal,
						$cart_id,
					)
				)
			);
		}

		return $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$cart_meta_table} SET payment_method=%s, currency=%s WHERE cart_id=%d;",
				array(
					$payment_method_title,
					$new_currency_code,
					$cart_id,
				)
			)
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
	} catch ( Exception $e ) {
		peachpay_notify_error( $e );
	}
}

/**
 * Updates the order status.
 *
 * @param number $order_id - ID of order to update.
 * @param String $old_status If NULL, just updates to new status.
 * @param String $new_status .
 * @param bool   $to_pending - If the status is going to pending, remove taxes from the analytics.
 */
function peachpay_analytics_update_order_status( $order_id, $old_status, $new_status, $to_pending = 0 ) {
	$order = wc_get_order( $order_id );

	// Check if this order is being tracked.
	if ( ! $order->get_meta( 'peachpay_tracked_order', 1 ) ) {
		return;
	}

	$payment_gateways  = WC()->payment_gateways()->payment_gateways();
	$payment_end_title = $order->get_payment_method();
	if ( isset( $payment_gateways[ $payment_end_title ] ) && method_exists( $payment_gateways[ $payment_end_title ], 'get_method_title' ) ) {
		$payment_end_title = $payment_gateways[ $payment_end_title ]->get_method_title();
	}

	$total = $order->get_total();
	if ( 'Refunded' === $new_status ) {
		$total = 0;
		$order->add_meta_data( 'peachpay_tracked_order_pre_refund_status', $old_status, 1 );
		$order->save();
	}

	$tax_total = 0;
	$fee_total = 0;
	if ( $to_pending ) {
		$tax_total = $order->get_total_tax();
		foreach ( $order->get_items( 'fee' ) as $item_id => $item_fee ) {
			$fee_total += $item_fee->get_total();
		}
	}

	$currency = $order->get_currency();

	PeachPay_Analytics_Database::update_analytics(
		array(
			'tab'      => 'abandoned_carts',
			'section'  => $old_status ? array(
				'volume_count',
				'volume_interval',
				'volume_count',
				'volume_interval',
			) : array(
				'volume_count',
				'volume_interval',
			),
			'title'    => $old_status ? array(
				$old_status,
				$new_status,
			) : $new_status,
			'currency' => $currency,
			'value'    => $old_status ? array(
				-1 * ( floor( ( $total - $tax_total - $fee_total ) * 100 ) / 100 ),
				-1 * ( floor( ( $total - $tax_total - $fee_total ) * 100 ) / 100 ),
				floor( $total * 100 ) / 100,
				floor( $total * 100 ) / 100,
			) : $total,
		)
	);
	PeachPay_Analytics_Database::update_analytics(
		array(
			'tab'     => 'abandoned_carts',
			'section' => $old_status ? array(
				'cart_count',
				'cart_interval',
				'cart_count',
				'cart_interval',
			) : array(
				'cart_count',
				'cart_interval',
			),
			'title'   => $old_status ? array(
				$old_status,
				$new_status,
			) : $new_status,
			'value'   => $old_status ? array(
				-1,
				-1,
				1,
				1,
			) : 1,
		)
	);
}

/**
 * Takes a given order and sets the status to failed - may be useful information to have in the future.
 * All this updates is the analytics DB as everything else is handled by WC.
 *
 * @param number $order_id - ID of order to cancel.
 * @param number $refund_id - ID of refund.
 *
 * @return number if the order has been fully refunded.
 */
function peachpay_analytics_order_refunded( $order_id, $refund_id ) {
	$order = wc_get_order( $order_id );
	// Check if this order is being tracked.
	if ( ! $order->get_meta( 'peachpay_tracked_order', 1 ) ) {
		return;
	}

	$status = $order->get_meta( 'peachpay_tracked_order_pre_refund_status', 1 );

	$currency = $order->get_currency();

	$refund = wc_get_order( $refund_id );
	$refund = $refund->get_amount();

	try {
		PeachPay_Analytics_Database::update_analytics(
			array(
				'tab'      => 'abandoned_carts',
				'section'  => array(
					'volume_count',
					'volume_interval',
					'volume_count',
					'volume_interval',
				),
				'title'    => array(
					$status,
					'Refunded',
				),
				'currency' => $currency,
				'value'    => array(
					-1 * $refund,
					-1 * $refund,
					$refund,
					$refund,
				),
			)
		);
	} catch ( Exception $e ) {
		peachpay_notify_error( $e );
	}

	return 0;
}

/**
 * Set order to pending and plug in order id here.
 *
 * @param number $order_id ID of given order.
 */
function peachpay_analytics_order_pending( $order_id ) {
	global $wpdb;

	$cart_meta_table = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_meta_table' );
	// Grab cart id.
	$cart_id = PeachPay_Analytics_Database::session_value( 'cart-id' );
	$order   = wc_get_order( $order_id );

	try {
		if ( ! $cart_id ) {
			// Stripe Afterpay / Affirm / redirecting payment edge case: does not have cookies passed along
			// Attempt to match this order with an order in the analytics database.
			if ( ! is_object( $order ) ) {
				return;
			}

			$email = $order->get_billing_email();

			// Attempt to pull row from database with same email. More than one? Whichever comes back first.
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
			$cart_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT cart_id FROM {$cart_meta_table} WHERE email=%s",
					$email
				)
			);

			if ( ! $cart_id ) {
				return;
			}
		}

		$payment_gateways  = WC()->payment_gateways()->payment_gateways();
		$payment_end_title = $order->get_payment_method();
		if ( isset( $payment_gateways[ $payment_end_title ] ) && method_exists( $payment_gateways[ $payment_end_title ], 'get_method_title' ) ) {
			$payment_end_title = $payment_gateways[ $payment_end_title ]->get_method_title();
		}

		$currency = $order->get_currency();

		$email = null;
		if ( $cart_id ) {
			$cart_info = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT email, order_id FROM {$cart_meta_table} WHERE cart_id=%d",
					$cart_id
				)
			);
			if ( ! is_object( $cart_info ) || $cart_info->order_id ) {
				return;
			}

			$email = $cart_info->email;
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$cart_meta_table} SET order_id=%d, payment_method=%s, currency=%s WHERE cart_id=%d",
					array(
						$order_id,
						$payment_end_title,
						$currency,
						$cart_id,
					)
				)
			);

			$order->add_meta_data( 'peachpay_tracked_order', 1, true );
			$order->save();

			// Update order and volume metrics.
			$total = $order->get_total();
			PeachPay_Analytics_Database::update_analytics(
				array(
					'tab'      => 'payment_methods',
					'section'  => array(
						'order_count',
						'order_interval',
						'volume_count',
						'volume_interval',
					),
					'title'    => $payment_end_title,
					'currency' => $currency,
					'value'    => array(
						1,
						1,
						floor( $total * 100 ) / 100,
						floor( $total * 100 ) / 100,
					),
				)
			);
			PeachPay_Analytics_Database::update_analytics(
				array(
					'tab'     => 'payment_methods',
					'section' => 'currency_count',
					'title'   => $currency,
					'value'   => 1,
				)
			);

			// check on customer_id (if it's there, attach order to customer)
			if ( ! $email && ! strlen( $email ) ) {
				$user_info = wp_get_current_user();
				$email     = $user_info ? $user_info->user_email : '';
			}

			$old_status = 'Unrecoverable';
			if ( $email && strlen( $email ) ) {
				$old_status = 'Recoverable';
				PeachPay_Analytics_Database::session_value( 'cart-email', $email );
			}

			peachpay_analytics_update_order_status( $order_id, $old_status, wc_get_order_status_name( 'pending' ), 1 );
			PeachPay_Analytics_Database::session_value( 'made-purchase', 1 );
		}

		// Remove billing information
		$cart_table_contents = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_contents_table' );
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$cart_table_contents} WHERE cart_id=%d;",
				$cart_id
			)
		);

		$cart_table = $wpdb->prefix . PeachPay_Analytics_Database::get_table_name( 'create_cart_table' );
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$cart_table} WHERE cart_id=%d;",
				$cart_id
			)
		);

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$cart_meta_table} WHERE cart_id=%d;",
				$cart_id
			)
		);
		// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
	} catch ( Exception $e ) {
		peachpay_notify_error( $e );
	}
}

/**
 * Updates the order value for any status changes.
 *
 * @param number $order_id .
 * @param String $old_status .
 * @param String $new_status .
 */
function peachpay_analytics_order_changed( $order_id, $old_status, $new_status ) {
	if ( 0 === strcmp( 'pending', $old_status ) ) {
		peachpay_analytics_order_pending( $order_id );
	}

	peachpay_analytics_update_order_status( $order_id, wc_get_order_status_name( $old_status ), wc_get_order_status_name( $new_status ) );
}

/**
 * Listener for ajax queries from the frontend. Attaches into @query_analytics and sends json response from there.
 *
 * Expects the input `query` which should be a JSON representation of the input to @query_analytics
 */
function wp_ajax_query_analytics() {
	// Ignoring POST warnings as query is sanitized and then solely used in prepared statements in the query_analytics method
	//phpcs:ignore
	$post = $_POST;

	if ( empty( $post ) || ! array_key_exists( 'query', $post ) ) {
		return wp_send_json(
			array(
				'error' => array(
					'title'   => 'Invalid inputs',
					'message' => 'There was something wrong with the given inputs. Please try again.',
				),
			)
		);
	}

	$query = json_decode( stripslashes( sanitize_text_field( wp_unslash( $post['query'] ) ) ), true );
	wp_send_json(
		PeachPay_Analytics_Database::query_analytics( $query )
	);
}
