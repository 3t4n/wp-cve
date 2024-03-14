<?php
/**
 * Skrill Transactions Log Model
 *
 * Transactions Log Models available on both the front-end and admin.
 * Copyright (c) Skrill
 *
 * @class   Skrill_Transactions_Model
 * @package Skrill/Classes
 * @located at  /includes/models/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skrill Transaction Model
 */
class Skrill_Transactions_Model {


	const TRANSLOG_TABLE = 'skrill_transaction_log';

	/**
	 * Save transaction log
	 *
	 * @param array $transaction - transaction log data.
	 */
	public static function save( $transaction ) {
		global $wpdb;
		$wpdb->insert(
			"{$wpdb->prefix}" . self::TRANSLOG_TABLE,
			array(
				'order_id'               => $transaction['order_id'],
				'transaction_id'         => $transaction['transaction_id'],
				'mb_transaction_id'      => $transaction['mb_transaction_id'],
				'payment_method_id'      => $transaction['payment_method_id'],
				'payment_type'           => $transaction['payment_type'],
				'payment_status'         => $transaction['payment_status'],
				'amount'                 => $transaction['amount'],
				'refunded_amount'        => $transaction['refunded_amount'],
				'currency'               => $transaction['currency'],
				'date'                   => gmdate( 'Y-m-d H:i:s' ),
				'additional_information' => $transaction['additional_information'],
				'payment_response'       => $transaction['payment_response'],
				'customer_id'            => $transaction['customer_id'],
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d' )
		); // db call ok; no-cache ok.
	}

	/**
	 * Update transaction log by order id
	 *
	 * @param string $order_id    - order id.
	 * @param array  $transaction - transaction log data.
	 */
	public static function update( $order_id, $transaction ) {
		global $wpdb;
		$translog_table = $wpdb->prefix . self::TRANSLOG_TABLE;
		$wpdb->update(
			"{$translog_table}",
			array(
				'transaction_id'         => $transaction['transaction_id'],
				'mb_transaction_id'      => $transaction['mb_transaction_id'],
				'payment_method_id'      => $transaction['payment_method_id'],
				'payment_type'           => $transaction['payment_type'],
				'payment_status'         => $transaction['payment_status'],
				'amount'                 => $transaction['amount'],
				'refunded_amount'        => $transaction['refunded_amount'],
				'currency'               => $transaction['currency'],
				'date'                   => gmdate( 'Y-m-d H:i:s' ),
				'additional_information' => $transaction['additional_information'],
				'payment_response'       => $transaction['payment_response'],
				'customer_id'            => $transaction['customer_id'],
			),
			array(
				'order_id' => $order_id,
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d' ),
			array( '%s' )
		); // db call ok; no-cache ok.
	}


	/**
	 * Update transaction log by order id
	 *
	 * @param string $order_id    - order id.
	 * @param array  $transaction - transaction log data.
	 */
	public static function update_by_transaction_id( $order_id, $transaction ) {
		global $wpdb;
		$translog_table = $wpdb->prefix . self::TRANSLOG_TABLE;
		$wpdb->update(
			"{$translog_table}",
			array(
				'order_id'               => $order_id,
				'mb_transaction_id'      => $transaction['mb_transaction_id'],
				'payment_method_id'      => $transaction['payment_method_id'],
				'payment_type'           => $transaction['payment_type'],
				'payment_status'         => $transaction['payment_status'],
				'amount'                 => $transaction['amount'],
				'refunded_amount'        => $transaction['refunded_amount'],
				'currency'               => $transaction['currency'],
				'date'                   => gmdate( 'Y-m-d H:i:s' ),
				'additional_information' => $transaction['additional_information'],
				'payment_response'       => $transaction['payment_response'],
				'customer_id'            => $transaction['customer_id'],
			),
			array(
				'transaction_id' => $transaction['transaction_id'],
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d' ),
			array( '%s' )
		); // db call ok; no-cache ok.
	}

	/**
	 * Save error transaction log
	 *
	 * @param array $transaction - transaction log data.
	 */
	public static function save_error_transaction( $transaction ) {
		global $wpdb;
		$wpdb->insert(
			"{$wpdb->prefix}" . self::TRANSLOG_TABLE,
			array(
				'order_id'          => $transaction['order_id'],
				'payment_method_id' => $transaction['payment_method_id'],
				'payment_status'    => $transaction['payment_status'],
				'amount'            => $transaction['amount'],
				'refunded_amount'   => $transaction['refunded_amount'],
				'currency'          => $transaction['currency'],
				'date'              => gmdate( 'Y-m-d H:i:s' ),
				'customer_id'       => $transaction['customer_id'],
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%d' )
		); // db call ok; no-cache ok.
	}

	/**
	 * Save payment response
	 *
	 * @param array $payment_response - payment response.
	 */
	public static function save_payment_response( $payment_response ) {
		global $wpdb;
		$wpdb->insert(
			"{$wpdb->prefix}" . self::TRANSLOG_TABLE,
			array(
				'transaction_id'   => $payment_response['transaction_id'],
				'date'             => gmdate( 'Y-m-d H:i:s' ),
				'payment_response' => maybe_serialize( $payment_response ),
			),
			array( '%s', '%s', '%s' )
		); // db call ok; no-cache ok.
	}

	/**
	 * Update payment response from status url by order id
	 *
	 * @param string $order_id         - order id.
	 * @param array  $payment_response - payment response.
	 */
	public static function update_payment_response( $order_id, $payment_response ) {
		global $wpdb;
		$translog_table = $wpdb->prefix . self::TRANSLOG_TABLE;
		$wpdb->update(
			"{$translog_table}",
			array(
				'payment_response' => maybe_serialize( $payment_response ),
			),
			array(
				'order_id' => $order_id,
			),
			array( '%s' ),
			array( '%s' )
		); // db call ok; no-cache ok.
	}

	/**
	 * Get transaction by transaction id
	 *
	 * @param  string $transaction_id - transaction id.
	 * @return bool
	 */
	public static function get_transaction_by_transaction_id( $transaction_id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'skrill_transaction_log WHERE transaction_id = %s', $transaction_id ), ARRAY_A ); // db call ok; no-cache ok.
	}


	/**
	 * Get transaction by transaction id and mb transaction id
	 *
	 * @param  string $transaction_id - transaction id.
	 * @param  string $mb_transaction_id - mb transaction id.
	 * @return bool
	 */
	public static function get_transaction_by_transaction_id_and_mb_transaction_id( $transaction_id, $mb_transaction_id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'skrill_transaction_log WHERE transaction_id = %s and mb_transaction_id = %s', $transaction_id, $mb_transaction_id ), ARRAY_A ); // db call ok; no-cache ok.
	}

	/**
	 * Get transaction by mb transaction id
	 *
	 * @param  string $mb_transaction_id - mb transaction id.
	 * @return bool
	 */
	public static function get_transaction_by_mb_transaction_id( $mb_transaction_id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'skrill_transaction_log WHERE mb_transaction_id = %s', $mb_transaction_id ), ARRAY_A ); // db call ok; no-cache ok.
	}

	/**
	 * Get transaction by order id
	 *
	 * @param  int $order_id - order id.
	 * @return array
	 */
	public static function get_transaction_by_order_id( $order_id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'skrill_transaction_log WHERE order_id = %s', $order_id ), ARRAY_A ); // db call ok; no-cache ok.
	}


	/**
	 * Get parent transaction log by order id
	 *
	 * @param  int $order_id - order id.
	 * @return array
	 */
	public static function get_parent_transaction_by_order_id( $order_id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'skrill_transaction_log WHERE order_id = %s order by id limit 1', $order_id ), ARRAY_A ); // db call ok; no-cache ok.
	}

	/**
	 * Get transaction by order id
	 *
	 * @param  int $order_id - order id.
	 * @return array
	 */
	public static function get_transaction_by_parent_order_id_and_customer_id( $order_id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'skrill_transaction_log WHERE order_id = %s and customer_id = 0', $order_id ), ARRAY_A ); // db call ok; no-cache ok.
	}

	/**
	 * Update transaction status by order id
	 *
	 * @param int    $order_id       - order id.
	 * @param string $payment_status - payment status.
	 */
	public static function update_payment_status( $order_id, $payment_status ) {
		global $wpdb;
		$wpdb->update(
			"{$wpdb->prefix}" . self::TRANSLOG_TABLE,
			array(
				'payment_status' => $payment_status,
			),
			array(
				'order_id' => $order_id,
			),
			array( '%s' ),
			array( '%d' )
		); // db call ok; no-cache ok.
	}

	/**
	 * Update refunded status by order id
	 *
	 * @param int    $order_id        - order id.
	 * @param string $refunded_status - refunded status.
	 * @param float  $refunded_amount - refunded amount.
	 */
	public static function update_refunded_status( $order_id, $refunded_status, $refunded_amount = '0' ) {
		global $wpdb;
		$wpdb->update(
			"{$wpdb->prefix}" . self::TRANSLOG_TABLE,
			array(
				'payment_status'  => $refunded_status,
				'refunded_amount' => $refunded_amount,
			),
			array(
				'order_id' => $order_id,
			),
			array( '%s', '%s' ),
			array( '%d' )
		); // db call ok; no-cache ok.
	}

	/**
	 * Save transaction log
	 *
	 * @param array $transaction - transaction log data.
	 */
	public static function save_rec_payment( $transaction ) {
		global $wpdb;
		$wpdb->insert(
			"{$wpdb->prefix}" . self::TRANSLOG_TABLE,
			array(
				'order_id'               => $transaction['order_id'],
				'payment_status'         => $transaction['stat'],
				'transaction_id'         => $transaction['transaction_id'],
				'mb_transaction_id'      => $transaction['mb_transaction_id'],
				'payment_method_id'      => $transaction['payment_method_id'],
				'payment_type'           => $transaction['payment_type'],
				'payment_status'         => $transaction['payment_status'],
				'amount'                 => $transaction['amount'],
				'refunded_amount'        => $transaction['refunded_amount'],
				'currency'               => $transaction['currency'],
				'date'                   => gmdate( 'Y-m-d H:i:s' ),
				'additional_information' => $transaction['additional_information'],
				'payment_response'       => $transaction['payment_response'],
				'customer_id'            => $transaction['customer_id'],
			),
			array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d' )
		); // db call ok; no-cache ok.
	}
}
