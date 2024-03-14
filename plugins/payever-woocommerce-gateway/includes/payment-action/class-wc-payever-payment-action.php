<?php

use Payever\Sdk\Payments\Action\ActionDeciderInterface;

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Payment_Action' ) ) {
	return;
}

/**
 * WC_Payever_Payment_Action class.
 */
class WC_Payever_Payment_Action {

	use WC_Payever_Wpdb_Trait;

	const SOURCE_EXTERNAL = 'external';
	const SOURCE_INTERNAL = 'internal';
	const SOURCE_PSP      = 'psp';

	/**
	 * @param string $order_id
	 * @param string $identifier
	 * @param string $type
	 * @param float|null $amount
	 */
	public function add_action( $order_id, $identifier, $type, $amount = null ) {
		$this->add_item(
			array(
				'unique_identifier' => $identifier,
				'order_id'          => $order_id,
				'action_type'       => $type,
				'action_source'     => self::SOURCE_EXTERNAL,
				'amount'            => $amount,
				'created_at'        => gmdate( 'Y-m-d H:i:s' ),
			)
		);
	}

	/**
	 * @param string $order_id
	 * @param float $amount
	 *
	 * @return string
	 */
	public function add_shipping_action( $order_id, $amount = null ) {
		$identifier = $this->generate_identifier();

		$this->add_action( $order_id, $identifier, ActionDeciderInterface::ACTION_SHIPPING_GOODS, $amount );

		return $identifier;
	}

	/**
	 * @param string $order_id
	 * @param float $amount
	 *
	 * @return string
	 */
	public function add_refund_action( $order_id, $amount = null ) {
		$identifier = $this->generate_identifier();

		$this->add_action( $order_id, $identifier, ActionDeciderInterface::ACTION_REFUND, $amount );

		return $identifier;
	}

	/**
	 * @param string $order_id
	 * @param float $amount
	 *
	 * @return string
	 */
	public function add_cancel_action( $order_id, $amount = null ) {
		$identifier = $this->generate_identifier();

		$this->add_action( $order_id, $identifier, ActionDeciderInterface::ACTION_CANCEL, $amount );

		return $identifier;
	}

	/**
	 * Adds new item to the payment action
	 *
	 * @param $data
	 */
	public function add_item( $data ) {
		$this->get_wpdb()->insert( $this->get_wpdb()->prefix . 'woocommerce_payever_payment_action', $data );
	}

	/**
	 * @param string $identifier
	 * @param string $order_id
	 */
	public function get_item( $order_id, $identifier, $source ) {
		return $this->get_wpdb()->get_row(
			$this->get_wpdb()->prepare(
				'SELECT * FROM ' . esc_sql( $this->get_wpdb()->prefix ) . 'woocommerce_payever_payment_action WHERE `identifier` = %s and `source` = %s and `order_id` = %d LIMIT 1;',
				$identifier,
				$source,
				$order_id
			)
		);
	}

	/**
	 * @return string
	 */
	private function generate_identifier() {
		return uniqid();
	}
}
