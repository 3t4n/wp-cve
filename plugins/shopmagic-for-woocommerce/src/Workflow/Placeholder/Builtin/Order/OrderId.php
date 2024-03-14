<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;


final class OrderId extends WooCommerceOrderBasedPlaceholder {

	public function get_slug(): string {
		return 'id';
	}

	public function get_description(): string {
		return esc_html__( 'Display the ID of current order.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return (string) $this->resources->get( \WC_Order::class )->get_id();
		}

		return '';
	}
}
