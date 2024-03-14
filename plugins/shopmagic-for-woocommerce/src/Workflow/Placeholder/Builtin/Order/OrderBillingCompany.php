<?php

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;

final class OrderBillingCompany extends WooCommerceOrderBasedPlaceholder {

	public function get_slug(): string {
		return 'billing_company';
	}

	public function get_description(): string {
		return esc_html__( 'Display the billing company of current order.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return $this->resources->get( \WC_Order::class )->get_billing_company();
		}

		return '';
	}
}
