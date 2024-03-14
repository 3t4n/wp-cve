<?php
/**
 * Frontend Account class
 *
 * @package ZiinaPayment\Entities
 */

namespace ZiinaPayment\Entities;

use WC_Order;

defined( 'ABSPATH' ) || exit();

/**
 * Class ZiinaPayment
 *
 * @package ZiinaPayment\Entities
 * @since   1.0.0
 */
class ZiinaPayment {
	const ORDER_META_PAYMENT_ID = '_ziina_payment_id';

	/**
	 * @var WC_Order|null
	 */
	private $order = null;

	/**
	 * @var string|null
	 */
	private $payment_id = null;

	/**
	 * ZiinaPayment constructor.
	 */
	private function __construct() {
	}

	/**
	 * @param mixed $order                 order to set payment_id.
	 *
	 * @return ZiinaPayment
	 */
	public static function by_order( $order ): ZiinaPayment {
		$instance        = new self();
		$instance->order = wc_get_order( $order );

		if ( ! empty( $instance->order ) ) {
			$instance->set_payment_id();
		}

		return $instance;
	}

	/**
	 * @param string $payment_id ziina payment id to find order.
	 *
	 * @return ZiinaPayment
	 */
	public static function by_payment_id( string $payment_id ): ZiinaPayment {
		$instance             = new self();
		$instance->payment_id = $payment_id;
		$instance->set_order_by_payment_id();

		return $instance;
	}

	/**
	 * @return WC_Order|null
	 */
	public function order(): ?WC_Order {
		return $this->order;
	}

	/**
	 * @return string|null
	 */
	public function payment_id(): ?string {
		return $this->payment_id;
	}

	/**
	 *  Set payment_id by order meta
	 *
	 * @param string $payment_id new payment id from API. If empty set api from meta.
	 */
	public function set_payment_id( string $payment_id = '' ) {
		if ( ! empty( $payment_id ) ) {
			$this->order->update_meta_data( self::ORDER_META_PAYMENT_ID, $payment_id );
			$this->order()->save();
			$this->payment_id = $payment_id;
		} else {
			$this->payment_id = $this->order->get_meta( self::ORDER_META_PAYMENT_ID, true, '' ) ?: '';
		}
	}

	/**
	 * Set order by payment_id
	 */
	private function set_order_by_payment_id() {
		$this->order = wc_get_orders(
			array(
				'type'       => 'shop_order',
				'limit'      => 1,
				'meta_key'   => self::ORDER_META_PAYMENT_ID, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value' => $this->payment_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		)[0] ?? null;
	}
}
