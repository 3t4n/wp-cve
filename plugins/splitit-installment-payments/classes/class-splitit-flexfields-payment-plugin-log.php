<?php
/**
 * @package     Splitit_WooCommerce_Plugin
 *
 * File - class-splitit-flexfields-payment-plugin-log.php
 * Class for loggin user actions and transactions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // @Exit if accessed directly
}

/**
 * Class SplitIt_FlexFields_Payment_Plugin_Log
 */
class SplitIt_FlexFields_Payment_Plugin_Log {
	/**
	 * @var string
	 */
	protected static $file_log = 'splitit.log';

	/**
	 * @var string
	 */
	protected static $db_table_log = 'splitit_log';

	/**
	 * @var string
	 */
	protected static $db_table_refund_info_log = 'splitit_async_refund_log';

	/**
	 * @var string
	 */
	protected static $db_table_transaction_log = 'splitit_transactions_log';

	/**
	 * @var string
	 */
	protected static $db_order_data = 'splitit_order_data_with_ipn';

	/**
	 * Log into DB, file and WC
	 *
	 * @param array  $data Data.
	 * @param string $message Message.
	 * @param string $type Type.
	 * @param null   $file File.
	 */
	public static function save_log_info( $data, $message, $type = '', $file = null ) {
		$data['message'] = $message;
		self::log_to_db( $data );
		self::log_to_file( $message, $file );
		self::wc_log( $type, $message );
	}

	/**
	 * Log to DB method
	 *
	 * @param array $data Data.
	 */
	public static function log_to_db( $data ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_log;

		if ( isset( $data['user_id'] ) && 0 === (int) $data['user_id'] ) {
			$data['user_id'] = null;
		}

		$wpdb->insert(
			"$table_name",
			array(
				'user_id' => $data['user_id'] ?? null,
				'method'  => $data['method'] ?? null,
				'message' => $data['message'] ?? null,
				'date'    => gmdate( 'Y-m-d H:i:s' ),
			)
		);
	}

	/**
	 * Log refund info to DB method
	 *
	 * @param array $data Data.
	 */
	public static function save_refund_info( $data ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_refund_info_log;

		if ( isset( $data['user_id'] ) && 0 === (int) $data['user_id'] ) {
			$data['user_id'] = null;
		}

		$wpdb->insert(
			"$table_name",
			array(
				'user_id' => $data['user_id'] ?? null,
				'order_id'  => $data['order_id'] ?? null,
				'ipn' => $data['ipn'] ?? null,
				'refund_id' => $data['refund_id'] ?? null,
				'refund_amount' => $data['refund_amount'] ?? null,
				'refund_reason' => $data['refund_reason'] ?? null,
				'action_type' => $data['action_type'] ?? null,
				'updated_at'    => gmdate( 'Y-m-d H:i:s' ),
			)
		);
	}

	/**
	 * Log to file method
	 *
	 * @param string $error_message Error message.
	 * @param null   $file File.
	 */
	public static function log_to_file( $error_message, $file = null ) {
		if ( ! isset( $file ) ) {
			$file = self::$file_log;
		}

		$path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
		if ( ! is_dir( $path ) ) {
			mkdir( $path, 0755, true );
		}

		$message = gmdate( 'Y-m-d H:i:s' ) . ' ' . wc_clean( $error_message ) . PHP_EOL;
		$result = file_put_contents( $path . $file, $message, FILE_APPEND | LOCK_EX );

		if ( ! $result ) {
			wp_die( 'Could not open file - ' . $file );
		}
	}

	/**
	 * WooCommerce log
	 *
	 * @param string $type Type.
	 * @param string $message Message.
	 * @param array  $context Context.
	 */
	public static function wc_log( $type, $message, $context = array() ) {
		$log = new WC_Logger();

		switch ( $type ) {
			case 'error':
				$log->error( $message, $context );
				break;
			case 'warning':
				break;
			case 'notice':
				$log->notice( $message, $context );
				break;
			case 'info':
				$log->info( $message, $context );
				break;
			case 'critical':
				$log->critical( $message, $context );
				break;
			case 'alert':
				$log->alert( $message, $context );
				break;
			case 'emergency':
				$log->emergency( $message, $context );
				break;
			default:
				$log->info( $message, $context );
				break;
		}
	}

	/**
	 * Method for adding data to transaction log
	 *
	 * @param array $data Data.
	 */
	public static function transaction_log( $data ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_transaction_log;

		if ( isset( $data['user_id'] ) && (int) 0 === $data['user_id'] ) {
			$data['user_id'] = null;
		}

		$wpdb->insert(
			"$table_name",
			array(
				'user_id'                 => $data['user_id'] ?? null,
				'order_id'                => $data['order_id'] ?? null,
				'installment_plan_number' => $data['installment_plan_number'] ?? null,
				'number_of_installments'  => $data['number_of_installments'] ?? null,
				'processing'              => $data['processing'] ?? null,
				'plan_create_succeed'     => $data['plan_create_succeed'] ?? 0,
				'date'                    => gmdate( 'Y-m-d H:i:s' ),
			)
		);
	}

	/**
	 * Method for updating transaction record
	 *
	 * @param array $data Data.
	 */
	public static function update_transaction_log( $data ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_transaction_log;
		$wpdb->update( "$table_name", array( 'plan_create_succeed' => 1 ), array( 'installment_plan_number' => $data['installment_plan_number'] ) );
	}

	/**
	 * Method for updating refund record
	 *
	 * @param int $id ID.
	 * @param array $data Data.
	 */
	public static function update_refund_log( $id, $data ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_refund_info_log;

		$update_data = array(
			'action_type' => $data['action_type'],
		);

		$where = array( 'id' => $id );

		$wpdb->update( $table_name, $update_data, $where );

		if ( $wpdb->last_error ) {
			self::log_to_file( 'Error when update refund_log: ' . $wpdb->last_error );
		}
	}


	/**
	 * Get record from transaction log by order ID
	 *
	 * @param null   $order_id Order ID.
	 * @param string $type Type.
	 *
	 * @return array|object|void|null
	 */
	public static function select_from_transaction_log_by_order_id( $order_id = null, $type = OBJECT ) {
		$return_data = null;
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_transaction_log;

		if ( isset( $order_id ) ) {
			$sql         = $wpdb->prepare(
				'SELECT * FROM ' . $table_name . ' WHERE order_id = %d ORDER BY order_id DESC LIMIT 0,1',
				array(
					$order_id,
				)
			);
			$return_data = $wpdb->get_row(
				$sql,
				$type
			);
		}

		return $return_data;
	}

	/**
	 * Get record from transaction log by ipn
	 *
	 * @param null   $ipn Installment plun number.
	 * @param string $type Type.
	 *
	 * @return array|object|void|null
	 */
	public static function select_from_transaction_log_by_ipn( $ipn = null, $type = OBJECT ) {
		$return_data = null;
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_transaction_log;

		if ( isset( $ipn ) ) {

			$sql = $wpdb->prepare(
				'SELECT * FROM ' . $table_name . ' WHERE installment_plan_number = %s ORDER BY number_of_installments DESC LIMIT 0,1',
				array(
					$ipn,
				)
			);

			$return_data = $wpdb->get_row(
				$sql,
				$type
			);
		}

		return $return_data;
	}

	/**
	 * Get record from transaction log by ipn
	 *
	 * @param string   $ipn Installment plun number.
	 * @param string   $refund_id Refund ID.
	 * @param string $type Type.
	 *
	 * @return array|object|void|null
	 */
	public static function select_from_refund_log_by_ipn_and_refund_id( $ipn, $refund_id, $type = OBJECT ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_refund_info_log;

		$sql = $wpdb->prepare(
			'SELECT * FROM ' . $table_name . ' WHERE ipn = %s AND refund_id = %s  LIMIT 1',
			array(
				$ipn,
				$refund_id,
			)
		);

		$return_data = $wpdb->get_row(
			$sql,
			$type
		);

		return $return_data;
	}

	/**
	 * Get record from transaction log
	 *
	 * @param string $type Type.
	 *
	 * @return array|object|void|null
	 */
	public static function select_from_refund_log_orders_without_refund_result( $type = OBJECT ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_refund_info_log;

		$sql = $wpdb->prepare(
			'SELECT * FROM ' . $table_name . ' WHERE action_type != %s',
			array(
				'done'
			)
		);

		$return_data = $wpdb->get_results(
			$sql,
			$type
		);

		return $return_data;
	}

	/**
	 * Get record from transaction log by ipn
	 *
	 * @param int   $order_id Order ID.
	 * @param string $type Type.
	 *
	 * @return array|object|void|null
	 */
	public static function select_from_refund_log_by_order_id( $order_id, $type = OBJECT ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_table_refund_info_log;

		$sql = $wpdb->prepare(
			'SELECT * FROM ' . $table_name . ' WHERE order_id = %s LIMIT 1',
			array(
				$order_id,
			)
		);

		$return_data = $wpdb->get_row(
			$sql,
			$type
		);

		return $return_data;
	}

	/**
	 * Get info about transaction by order ID
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return false|mixed
	 */
	public static function get_splitit_info_by_order_id( $order_id ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'splitit_transactions_log';

		$sql = $wpdb->prepare(
			'SELECT installment_plan_number, number_of_installments, plan_create_succeed FROM ' . $table_name . ' WHERE order_id=%d LIMIT 1',
			array(
				$order_id,
			)
		);

		$splitit_transaction_info = $wpdb->get_results( $sql );

		return ! empty( $splitit_transaction_info ) ? $splitit_transaction_info[0] : false;
	}

	/**
	 *  Add data about order
	 *
	 * @param array $data Data.
	 */
	public static function add_order_data( $data ) {
		global $wpdb;
		$table_name = $wpdb->prefix . self::$db_order_data;

		$sql = $wpdb->prepare(
			'SELECT * FROM ' . $table_name . ' WHERE ipn = %s ORDER BY ipn DESC LIMIT 0,1',
			array(
				$data['ipn'],
			)
		);

		$exist = $wpdb->get_row(
			$sql
		);

		if ( ! isset( $exist ) ) {
			$wpdb->insert(
				$table_name,
				array(
					'ipn'                          => $data['ipn'],
					'user_id'                      => $data['user_id'],
					'cart_items'                   => $data['cart_items'],
					'shipping_method_cost'         => $data['shipping_method_cost'],
					'shipping_method_title'        => $data['shipping_method_title'],
					'shipping_method_id'           => $data['shipping_method_id'],
					'coupon_amount'                => $data['coupon_amount'],
					'coupon_code'                  => $data['coupon_code'],
					'tax_amount'                   => $data['tax_amount'],
					'user_data'                    => wp_json_encode( $data['user_data'] ),
					'set_shipping_total'           => $data['set_shipping_total'],
					'set_discount_total'           => $data['set_discount_total'],
					'set_discount_tax'             => $data['set_discount_tax'],
					'set_cart_tax'                 => $data['set_cart_tax'],
					'set_shipping_tax'             => $data['set_shipping_tax'],
					'set_total'                    => $data['set_total'],
					'wc_cart'                      => $data['wc_cart'],
					'get_packages'                 => $data['get_packages'],
					'chosen_shipping_methods_data' => $data['chosen_shipping_methods_data'],
					'updated_at'                   => gmdate( 'Y-m-d H:i:s' ),
					'session_id'                   => WC()->session->get_customer_unique_id(),
				)
			);
		} else {
			$wpdb->update(
				$table_name,
				array(
					'ipn'                          => $data['ipn'],
					'user_id'                      => $data['user_id'],
					'cart_items'                   => $data['cart_items'],
					'shipping_method_cost'         => $data['shipping_method_cost'],
					'shipping_method_title'        => $data['shipping_method_title'],
					'shipping_method_id'           => $data['shipping_method_id'],
					'coupon_amount'                => $data['coupon_amount'],
					'coupon_code'                  => $data['coupon_code'],
					'tax_amount'                   => $data['tax_amount'],
					'user_data'                    => wp_json_encode( $data['user_data'] ),
					'set_shipping_total'           => $data['set_shipping_total'],
					'set_discount_total'           => $data['set_discount_total'],
					'set_discount_tax'             => $data['set_discount_tax'],
					'set_cart_tax'                 => $data['set_cart_tax'],
					'set_shipping_tax'             => $data['set_shipping_tax'],
					'set_total'                    => $data['set_total'],
					'wc_cart'                      => $data['wc_cart'],
					'get_packages'                 => $data['get_packages'],
					'chosen_shipping_methods_data' => $data['chosen_shipping_methods_data'],
					'updated_at'                   => gmdate( 'Y-m-d H:i:s' ),
					'session_id'                   => WC()->session->get_customer_unique_id(),
				),
				array( 'ipn' => $data['ipn'] )
			);
		}
	}

	/**
	 * Get information about order by ipn
	 *
	 * @param int $ipn Installment plan number.
	 *
	 * @return false|mixed
	 */
	public static function get_order_info_by_ipn( $ipn ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'splitit_order_data_with_ipn';

		$sql = $wpdb->prepare( 'SELECT * FROM ' . $table_name . ' WHERE ipn=%s LIMIT 1', array( $ipn ) );

		$order_info = $wpdb->get_results( $sql );

		return ! empty( $order_info ) ? $order_info[0] : false;
	}

	/**
	 * Check if order exists by ipn
	 *
	 * @param int $ipn Installment plan number.
	 *
	 * @return bool
	 */
	public static function check_exist_order_by_ipn( $ipn ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'splitit_transactions_log';

		$sql = $wpdb->prepare(
			'SELECT order_id FROM ' . $table_name . ' WHERE installment_plan_number=%s LIMIT 1',
			array(
				$ipn,
			)
		);

		$order_id = $wpdb->get_results( $sql );

		return isset( $order_id[0] ) && ! empty( $order_id[0] )
			&& isset( $order_id[0]->order_id ) && ! empty( $order_id[0]->order_id );
	}

	/**
	 * Check if order exists by ipn
	 *
	 * @param string $ipn Installment plan number.
	 * @param string $refund_id Refund ID.
	 *
	 * @return bool
	 */
	public static function check_exist_order_by_ipn_and_refund_id( $ipn, $refund_id ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'splitit_async_refund_log';

		$sql = $wpdb->prepare(
			'SELECT order_id FROM ' . $table_name . ' WHERE ipn=%s AND refund_id=%s LIMIT 1',
			array(
				$ipn,
				$refund_id
			)
		);

		$order_id = $wpdb->get_results( $sql );

		return isset( $order_id[0] ) && ! empty( $order_id[0] )
			&& isset( $order_id[0]->order_id ) && ! empty( $order_id[0]->order_id );
	}

	/**
	 * Check if order exists by ipn
	 *
	 * @param int $ipn Installment plan number.
	 *
	 * @return mixed|null
	 */
	public static function get_order_id_by_ipn( $ipn ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'splitit_transactions_log';

		$sql = $wpdb->prepare(
			'SELECT order_id FROM ' . $table_name . ' WHERE installment_plan_number=%s LIMIT 1',
			array(
				$ipn,
			)
		);

		$order_id = $wpdb->get_results( $sql );

		return $order_id[0] ?? null;
	}
}
