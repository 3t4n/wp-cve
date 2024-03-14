<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;

final class OrderShippingFirstName extends WooCommerceOrderBasedPlaceholder {

	public function get_slug(): string {
		return 'shipping_first_name';
	}

	public function get_description(): string {
		return esc_html__( 'Display the shipping first name of current order.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return $this->resources->get( \WC_Order::class )->get_shipping_first_name();
		}

		return '';
	}
}
