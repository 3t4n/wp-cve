<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Event\Builtin\OrderCommonEvent;
use function __;
use function add_action;

final class OrderCancelled extends OrderCommonEvent {
	public function get_id(): string {
		return 'shopmagic_order_cancelled_event';
	}

	public function get_name(): string {
		return __( 'Order Cancelled', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when order status is set to cancelled.', 'shopmagic-for-woocommerce' );
	}

	public function initialize(): void {
		add_action(
			'woocommerce_order_status_cancelled',
			function ( $order_id, $order ) {
				$this->process_event( $order_id, $order );
			},
			10,
			2
		);
	}

}
