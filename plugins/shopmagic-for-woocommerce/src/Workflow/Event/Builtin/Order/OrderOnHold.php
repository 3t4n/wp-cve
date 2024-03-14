<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Event\Builtin\OrderCommonEvent;


final class OrderOnHold extends OrderCommonEvent {
	public function get_id(): string {
		return 'shopmagic_order_on_hold_event';
	}

	public function get_name(): string {
		return __( 'Order On-Hold', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when an order status is set to On-Hold', 'shopmagic-for-woocommerce' );
	}

	public function initialize(): void {
		add_action(
			'woocommerce_order_status_on-hold',
			function ( $order_id, $order ) {
				$this->process_event( $order_id, $order );
			},
			10,
			2
		);
	}

}
