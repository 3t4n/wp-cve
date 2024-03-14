<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;

final class OrderBillingFirstName extends WooCommerceOrderBasedPlaceholder {

	public function get_slug(): string {
		return 'billing_first_name';
	}

	public function get_description(): string {
		return esc_html__( 'Display the billing first name of current order.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return $this->resources->get( \WC_Order::class )->get_billing_first_name();
		}

		return '';
	}
}
