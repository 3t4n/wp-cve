<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;


final class OrderBillingFormattedAddress extends WooCommerceOrderBasedPlaceholder {

	public function get_slug(): string {
		return 'billing_formatted_address';
	}

	public function get_description(): string {
		return esc_html__( 'Display the whole billing address of current order in WooCommerce style.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return $this->resources->get( \WC_Order::class )->get_formatted_billing_address();
		}

		return '';
	}
}
