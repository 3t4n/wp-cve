<?php
/**
 * WC Order Controller.
 *
 * @package Wc Getnet
 */

declare(strict_types=1);

namespace WcGetnet\Controllers\Admin;

use WC_Order;
use WcGetnet\Entities\WcGetnet_Logs;
use WP_Query;

class OrderController {
	/**
	 * WC Order.
	 *
	 * @var WC_Order
	 */
	protected $wc_order;

	/**
	 * Status.
	 *
	 * @var string
	 */
	protected $status;

	/**
	 * Is webhook
	 *
	 * @var bool
	 */
	protected $webhook;

	/**
	 * Constructor.
	 *
	 * @param mixed $order
	 */
	public function __construct( $order = false ) {
		$this->webhook  = false;
		$this->wc_order = $this->initialize( $order );
	}

	/**
	 * Initialize object.
	 *
	 * @param mixed $order
	 * @return \WC_Order
	 */
	protected function initialize( $order ) {
		if ( ! $order || ! is_object( $order ) ) {
			return new WC_Order( $order );
		}

		return $order;
	}

	/**
	 * Update order status by notifications 1.0.
	 *
	 * @param string $status
	 * @return string
	 */
	public function update_order_by_webhook( $status ) {
		$this->status = $status;

		switch ( $status ) {
			case 'APPROVED':
			case 'PAID':
			case 'CONFIRMED':
				return $this->payment_paid();

			case 'AUTHORIZED':
				return $this->payment_on_hold( __( 'Transação encontra-se autorizada e aguardando confirmação', 'wc_getnet' ) );

			case 'PENDING':
				return $this->payment_on_hold( __( 'Transação encontra-se registrada e aguardando confirmação', 'wc_getnet' ) );

			case 'DENIED':
				return $this->payment_on_hold( __( 'Transação negada', 'wc_getnet' ) );

			case 'ERROR':
				return $this->payment_on_hold( __( 'Erro ao processar a transação', 'wc_getnet' ) );

			case 'CANCELED':
				return $this->payment_canceled( __( 'Transação cancelada e finalizada.', 'wc_getnet' ) );
		}
	}

	/**
	 * Payment Paid.
	 *
	 * @return string
	 */
	public function payment_paid() {
		$current_status = $this->wc_order->get_status();

		if ( ! in_array( $current_status, [ 'completed', 'processing' ] ) ) {
			$this->wc_order->add_order_note( __( 'Getnet: Pagamento confirmado.', 'wc_getnet' ) );
			$this->wc_order->payment_complete();

			return __( 'Webhook processed, order updated', 'wc_getnet' );
		}

		$this->log( $current_status );

		return __( 'Webhook processed', 'wc_getnet' );
	}

	/**
	 * Payment on hold
	 *
	 * @param string $event Event message.
	 * @return string
	 */
	public function payment_on_hold( $event ) {
		$message          = $event ? " - {$event}." : ' .';
		$current_status = $this->wc_order->get_status();

		if ( $current_status !== 'on-hold' ) {
			$this->wc_order->update_status( 'on-hold', wp_sprintf( __( 'Getnet: Aguardando pagamento %s', 'wc_getnet' ), $message ) );

			return __( 'Webhook processed, order updated', 'wc_getnet' );
		}

		$this->log( $current_status );

		return __( 'Webhook processed', 'wc_getnet' );
	}

	/**
	 * Payment canceled
	 *
	 * @param string $event Event message.
	 * @return string
	 */
	public function payment_canceled( $event = '' ) {
		$message          = $event ? " - {$event}." : ' .';
		$current_status = $this->wc_order->get_status();

		if ( ! in_array( $current_status, [ 'cancelled', 'canceled', 'refunded' ] ) ) {
			$this->wc_order->update_status( 'cancelled', wp_sprintf( __( 'Getnet: Pagamento cancelado %s', 'wc_getnet' ), $message ) );

			return __( 'Webhook processed, order updated', 'wc_getnet' );
		}

		$this->log( $current_status );

		return __( 'Webhook processed', 'wc_getnet' );
	}

	/**
	 * WC Log webhook.
	 *
	 * @param string $current_status The current status.
	 * @return void
	 */
	protected function log( $current_status ) {
		if ( ! $this->webhook ) {
			return;
		}

		$content = [
			'previous_status' => $current_status,
			'new_status'      => $this->wc_order->get_status()
		];

		$message =
			'ORDER STATUS UPDATE: #' .
			$this->wc_order->get_id() .
			json_encode( $content, JSON_PRETTY_PRINT );

		WcGetnet_Logs::webhook_log( $this->status, $message );
	}

	/**
	 * Set if is webhook.
	 *
	 * @return  self
	 */
	public function is_webhook() {
		$this->webhook = true;

		return $this;
	}
}
