<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;

final class OrderNumber extends WooCommerceOrderBasedPlaceholder {

	public function get_slug(): string {
		return 'order_number';
	}

	public function get_description(): string {
		return esc_html__( 'Display the number of current order. Similar to ID, but useful when you use plugins for sequential order number in WooCoommerce.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return $this->resources->get( \WC_Order::class )->get_order_number();
		}

		return '';
	}
}
