<?php
use Monolog\Logger;

/**
 * Class WC_BPost_Shipping_Logger is a custom logger to handle at the same time:
 *  - Woocommerce logging guidelines
 *  - PSR3 logger
 */
class WC_BPost_Shipping_Logger extends Logger {

	/**
	 * @param $message
	 * @param WC_Order $order
	 */
	public function log_order( $message, WC_Order $order ) {
		$this->info(
			$message,
			array(
				'order_id'  => $order->get_id(),
				'order_key' => $order->order_key,
				'origin_ip' => $order->customer_ip_address,
			)
		);
	}

	/**
	 * @param Exception $exception
	 * @param int $log_level
	 */
	public function log_exception( Exception $exception, $log_level = self::ERROR ) {
		$this->log(
			$log_level,
			$exception->getMessage(),
			array(
				'code'    => $exception->getCode(),
				'message' => $exception->getMessage(),
				'trace'   => $exception->getTrace(),
				'file'    => $exception->getFile() . ':' . $exception->getLine(),
			)
		);
	}
}
