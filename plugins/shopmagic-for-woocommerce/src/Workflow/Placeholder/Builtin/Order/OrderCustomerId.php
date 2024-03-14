<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;

final class OrderCustomerId extends WooCommerceOrderBasedPlaceholder {
	public function get_slug(): string {
		return 'customer_id';
	}

	public function get_description(): string {
		return esc_html__( 'Display the customer ID of current order.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return (string) $this->resources->get( \WC_Order::class )->get_customer_id();
		}

		return '';
	}
}
