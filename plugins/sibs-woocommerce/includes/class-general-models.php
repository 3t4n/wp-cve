<?php
/**
 * Sibs General Models
 *
 * General Models available on both the front-end and admin.
 * Copyright (c) SIBS
 *
 * @class       Sibs_General_Models
 * @package     Sibs/Classes
 * @located at  /includes/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

/**
 * General Models available on both the front-end and admin.
 */
class Sibs_General_Models {

	/**
	 * Get Registered Payment Detail
	 *
	 * @param string $payment_group payment group.
	 * @param array  $credentials credentials.
	 * @return array
	 */
	public static function sibs_get_db_registered_payment( $payment_group, $credentials ) {

		global $wpdb;

		$registered_payments = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}sibs_payment_information
			WHERE cust_id = %d AND payment_group = %s AND server_mode = %s AND channel_id = %s
			order by id ", get_current_user_id(), $payment_group, $credentials['server_mode'], $credentials['channel_id']
			), ARRAY_A
		); // db call ok; no-cache ok.

		return $registered_payments;
	}

	/**
	 * Get Registered Payment by Registration Id
	 *
	 * @param string $reg_id registration id.
	 * @return array
	 */
	public static function sibs_get_db_registered_payment_by_regid( $reg_id ) {

		global $wpdb;

		$registered_payment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sibs_payment_information WHERE reg_id = %s", $reg_id ), ARRAY_A ); // db call ok; no-cache ok.

		return $registered_payment;

	}

	/**
	 * Check Registered Payment
	 *
	 * @param string $registration_id registration id.
	 * @return bool
	 */
	public static function sibs_is_registered_payment_db( $registration_id ) {
		global $wpdb;

		$registered_payment = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sibs_payment_information WHERE reg_id = %s ", $registration_id ), ARRAY_A ); // db call ok; no-cache ok.

		if ( empty( $registered_payment ) ) {
			$is_registered_payment = false;
		} else {
			$is_registered_payment = true;
		}

		return $is_registered_payment;
	}

	/**
	 * Get Registration Id
	 *
	 * @param int $recurring_id recurring id.
	 * @return string
	 */
	public static function sibs_get_db_registration_id( $recurring_id ) {
		global $wpdb;

		$result = $wpdb->get_row( $wpdb->prepare( "SELECT reg_id FROM {$wpdb->prefix}sibs_payment_information WHERE cust_id = %s AND id = %s ", get_current_user_id(), $recurring_id ) ); // db call ok; no-cache ok.

		return $result->reg_id;
	}

	/**
	 * Get Payment default
	 *
	 * @param string $payment_group payment group.
	 * @param array  $credentials credentials.
	 * @return integer
	 */
	public static function sibs_get_db_payment_default( $payment_group, $credentials ) {
		global $wpdb;

		$payment_default = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}sibs_payment_information
			WHERE cust_id = %d AND payment_group = %s AND server_mode = %s AND channel_id = %s
			AND payment_default = '1' ", get_current_user_id(), $payment_group, $credentials['server_mode'], $credentials['channel_id']
			), ARRAY_A
		); // db call ok; no-cache ok.

		if ( empty( $payment_default ) ) {
			$payment_default = '1';
		} else {
			$payment_default = '0';
		}

		return $payment_default;
	}

	/**
	 * Save Registered Payment
	 *
	 * @param array $registered_payment registered payment.
	 */
	public static function sibs_save_db_registered_payment( $registered_payment ) {
		global $wpdb;

		$wpdb->insert(
			"{$wpdb->prefix}sibs_payment_information",
			array(
				'cust_id'         => get_current_user_id(),
				'payment_group'   => $registered_payment['payment_group'],
				'brand'           => $registered_payment['payment_brand'],
				'holder'          => $registered_payment['holder'],
				'email'           => $registered_payment['email'],
				'last4digits'     => $registered_payment['last_4_digits'],
				'expiry_month'    => $registered_payment['expiry_month'],
				'expiry_year'     => $registered_payment['expiry_year'],
				'server_mode'     => $registered_payment['server_mode'],
				'channel_id'      => $registered_payment['channel_id'],
				'reg_id'          => $registered_payment['registration_id'],
				'payment_default' => $registered_payment['payment_default'],
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d' )
		); // db call ok; no-cache ok.

	}

	/**
	 * Update Registered Payment
	 *
	 * @param int   $recurring_id recurring id.
	 * @param array $registered_payment registered payment.
	 */
	public static function sibs_update_db_registered_payment( $recurring_id, $registered_payment ) {
		global $wpdb;

		$wpdb->update(
			"{$wpdb->prefix}sibs_payment_information",
			array(
				'brand'           => $registered_payment['payment_brand'],
				'holder'          => $registered_payment['holder'],
				'email'           => $registered_payment['email'],
				'last4digits'     => $registered_payment['last_4_digits'],
				'expiry_month'    => $registered_payment['expiry_month'],
				'expiry_year'     => $registered_payment['expiry_year'],
				'server_mode'     => $registered_payment['server_mode'],
				'channel_id'      => $registered_payment['channel_id'],
				'reg_id'          => $registered_payment['registration_id'],
				'payment_default' => $registered_payment['payment_default'],
			),
			array(
				'id' => $recurring_id,
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
			array( '%d' )
		); // db call ok; no-cache ok.
	}

	/**
	 * Delete Registered Payment
	 *
	 * @param int $recurring_id recurring id.
	 */
	public static function sibs_delete_db_registered_payment( $recurring_id ) {

		global $wpdb;
		$wpdb->delete(
			"{$wpdb->prefix}sibs_payment_information", array(
				'id' => $recurring_id,
			), array( '%d' )
		); // db call ok; no-cache ok.

	}

	/**
	 * Delete Registered Payment by Registration Id
	 *
	 * @param string $registration_id registration id.
	 */
	public static function sibs_delete_db_registered_payment_by_regid( $registration_id ) {
		global $wpdb;
		$wpdb->delete(
			"{$wpdb->prefix}sibs_payment_information", array(
				'reg_id' => $registration_id,
			), array( '%s' )
		); // db call ok; no-cache ok.

	}

	/**
	 * Update Default Payment
	 *
	 * @param array  $query query.
	 * @param string $payment_group payment group.
	 * @param array  $credentials credentials.
	 */
	public static function sibs_update_db_default_payment( $query, $payment_group, $credentials ) {
		global $wpdb;

		$wpdb->update(
			"{$wpdb->prefix}sibs_payment_information",
			array(
				'payment_default' => $query['payment_default'],
			),
			array(
				$query['field'] => $query['value'],
				'cust_id'       => get_current_user_id(),
				'payment_group' => $payment_group,
				'server_mode'   => $credentials['server_mode'],
				'channel_id'    => $credentials['channel_id'],
			),
			array( '%d' ),
			array( '%d', '%d', '%s', '%s' )
		); // db call ok; no-cache ok.

	}

	/**
	 * Save Transaction Log
	 *
	 * @param array  $transaction transaction.
	 * @param string $additional_info additional info.
	 */
	public static function sibs_save_db_transaction( $transaction, $additional_info = '' ) {
		global $wpdb;

		$wpdb->insert(
			"{$wpdb->prefix}sibs_transaction",
			array(
				'order_no'               => $transaction['order_id'],
				'payment_type'           => $transaction['payment_type'],
				'reference_id'           => $transaction['reference_id'],
				'payment_brand'          => $transaction['payment_brand'],
				'transaction_id'         => $transaction['transaction_id'],
				'payment_id'             => $transaction['payment_id'],
				'payment_status'         => $transaction['payment_status'],
				'amount'                 => $transaction['amount'],
				'refunded_amount'        => 0,
				'currency'               => $transaction['currency'],
				'customer_id'            => $transaction['customer_id'],
				'date'                   => date( 'Y-m-d H:i:s' ),
				'additional_information' => $additional_info,
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
		); // db call ok; no-cache ok.
	}

    /**
    * Save Sibs transaction to Post Meta
    *
    *  @param array  $transaction transaction.
    *  @param string $additional_info additional info.
    */

    public static function sibs_save_db_postmeta($transaction, $additional_info = '')
    {
        global $wpdb;

        $payment_data_detail = explode("|", $additional_info);

        if ($payment_data_detail != null)
        {
            $pay_entity = $payment_data_detail[0];
            $pay_ref = $payment_data_detail[1];
            $pay_date = $payment_data_detail[2];
            $pay_ammount = $transaction['amount'];
            $hook_usage = intval($payment_data_detail[3]);

            $wpdb->insert(
                "{$wpdb->prefix}postmeta",
                array(
                    'post_id' => $transaction['order_id'],
                    'meta_key' => '_multibanco_sibs_for_woocommerce_ent',
                    'meta_value' => $pay_entity,
                ),
                array('%d', '%s', '%s')
            ); // db call ok; no-cache ok.

            $wpdb->insert(
                "{$wpdb->prefix}postmeta",
                array(
                    'post_id' => $transaction['order_id'],
                    'meta_key' => '_multibanco_sibs_for_woocommerce_ref',
                    'meta_value' => $pay_ref,
                ),
                array('%d', '%s', '%s')
            ); // db call ok; no-cache ok.

            $wpdb->insert(
                "{$wpdb->prefix}postmeta",
                array(
                    'post_id' => $transaction['order_id'],
                    'meta_key' => '_multibanco_sibs_for_woocommerce_val',
                    'meta_value' => $pay_ammount,
                ),
                array('%d', '%s', '%s')
            ); // db call ok; no-cache ok.
        }
    }

	/**
	 * Add Order Notes
	 * add order notes ( database : wp_comment ) if change payment status at backend
	 *
	 * @param array $comments commments.
	 */
	public static function sibs_add_db_order_notes( $comments ) {
		global $wpdb;

		$wpdb->insert(
			"{$wpdb->prefix}comments",
			array(
				'comment_post_ID'      => $comments['order_id'],
				'comment_author'       => $comments['author'],
				'comment_author_email' => $comments['email'],
				'comment_date'         => date( 'Y-m-d H:i:s' ),
				'comment_date_gmt'     => gmdate( 'Y-m-d H:i:s' ),
				'comment_content'      => $comments['content'],
				'comment_approved'     => '1',
				'comment_agent'        => 'WooCommerce',
				'comment_type'         => 'order_note',
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
		); // db call ok; no-cache ok.

	}

	/**
	 * Get Transactions Log from DB
	 *
	 * @param int $order_id order id.
	 * @return string
	 */
	public static function sibs_get_db_transaction_log( $order_id ) {
		global $wpdb;

		$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sibs_transaction WHERE order_no = %s ", $order_id ), ARRAY_A ); // db call ok; no-cache ok.

		return $result;
	}

	/**
	 * Update Payment Status at Transactions Log
	 *
	 * @param int    $order_id order id.
	 * @param string $payment_status payment status.
	 */
	public static function sibs_update_db_transaction_log_status( $order_id, $payment_status ) {
		global $wpdb;
		$wpdb->update(
			"{$wpdb->prefix}sibs_transaction",
			array(
				'payment_status' => $payment_status,
			),
			array(
				'order_no' => $order_id,
			),
			array( '%s' ),
			array( '%d' )
		); // db call ok; no-cache ok.
	}

	
	/**
	 * Update Payment Multibanco Usage at Transactions Log
	 *
	 * @param int    $order_id order id.
	 * @param string $payment_status payment status.
	 */
	public static function sibs_update_db_transaction_multibanco_usage( $order_id, $multibanco_data_usage ) {
		global $wpdb;
		$wpdb->update(
			"{$wpdb->prefix}sibs_transaction",
			array(
				'additional_information' => $multibanco_data_usage,
			),
			array(
				'order_no' => $order_id,
			),
			array( '%s' ),
			array( '%s' )
		); // db call ok; no-cache ok.
	}


	/**
	 * Update Payment Status at database wp_post
	 * Update wp_post post_status if change payment status at backend
	 *
	 * @param int    $order_id order id.
	 * @param string $payment_status payment status.
	 */
	public static function sibs_update_db_posts_status( $order_id, $payment_status ) {
		global $wpdb;
		$wpdb->update(
			"{$wpdb->prefix}posts",
			array(
				'post_status' => $payment_status,
			),
			array(
				'ID' => $order_id,
			),
			array( '%s' ),
			array( '%d' )
		); // db call ok; no-cache ok.

	}

	/**
	 * Get Last Order Id
	 *
	 * @return integer
	 */
	public static function sibs_get_db_last_order_id() {
		global $wpdb;

		$result = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_author = %d AND post_status = 'auto-draft' ORDER BY ID DESC", get_current_user_id() ), ARRAY_A ); // db call ok; no-cache ok.

		return $result['ID'];
	}

	/**
	 * Get order detail
	 *
	 * @param int $order_id order id.
	 * @return array
	 */
	public static function sibs_get_db_order_detail( $order_id ) {
		global $wpdb;

		$order_detail = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id = %d and ( meta_key = '_order_total' or meta_key = '_order_currency' )", $order_id ), ARRAY_A ); // db call ok; no-cache ok.

		return $order_detail;

	}

	/**
	 * Get order detail
	 *
	 * @param int $product_id product id.
	 * @return array
	 */
	public static function sibs_get_db_product_detail( $product_id ) {
		global $wpdb;

		$row = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = %d", $product_id ), ARRAY_A ); // db call ok; no-cache ok.

		$product = array();
		foreach ( $row as $products ) {
			$product_key             = $products['meta_key'];
			$product[ $product_key ] = $products['meta_value'];
		}

		return $product;
	}
}
