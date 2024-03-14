<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;

final class OrderAdminUrl extends WooCommerceOrderBasedPlaceholder {

	public function get_slug(): string {
		return 'admin_url';
	}

	public function get_description(): string {
		return esc_html__( 'Display link to editing current order in admin site.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return $this->resources->get( \WC_Order::class )->get_edit_order_url();
		}

		return '';
	}
}
