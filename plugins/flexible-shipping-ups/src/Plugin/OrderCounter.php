<?php
/**
 * Order counter.
 *
 * @package WPDesk\FlexibleShippingUps
 */

namespace WPDesk\FlexibleShippingUps;

use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsShippingService;

/**
 * Can count orders.
 */
class OrderCounter implements Hookable {

	const ORDER_STATUS_COMPLETED = 'completed';
	const FS_UPS_COUNTER         = 'fs_ups_counter';

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'woocommerce_order_status_changed', array( $this, 'maybe_count_order' ), 10, 4 );
	}

	/**
	 * Count order.
	 *
	 * @param \WC_Order $order .
	 */
	private function count_order( $order ): void {
		update_option( self::FS_UPS_COUNTER, intval( get_option( self::FS_UPS_COUNTER, '0' ) ) + 1 ); //@phpstan-ignore-line
		$order->update_meta_data( self::FS_UPS_COUNTER, (string) 1 );
		$order->save();
	}

	/**
	 * Maybe count order.
	 *
	 * @param int       $order_id .
	 * @param string    $status_from .
	 * @param string    $status_to .
	 * @param \WC_Order $order .
	 */
	public function maybe_count_order( $order_id, $status_from, $status_to, $order ): void {
		if ( self::ORDER_STATUS_COMPLETED === $status_to ) {
			$shipping_methods = $order->get_shipping_methods();
			foreach ( $shipping_methods as $shipping_method ) {
				if ( UpsShippingService::UNIQUE_ID === $shipping_method->get_method_id() ) {
					if ( '' === $order->get_meta( self::FS_UPS_COUNTER ) ) {
						$this->count_order( $order );
					}
				}
			}
		}
	}

}
