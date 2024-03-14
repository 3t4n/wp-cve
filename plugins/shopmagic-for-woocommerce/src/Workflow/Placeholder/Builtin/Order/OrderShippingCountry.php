<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Helper\WooCommerceFormatHelper;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;

final class OrderShippingCountry extends WooCommerceOrderBasedPlaceholder {

	public function get_slug(): string {
		return 'shipping_country';
	}

	public function get_description(): string {
		return esc_html__( 'Display the shipping country of current order.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			return WooCommerceFormatHelper::country_full_name(
				$this->resources->get( \WC_Order::class )->get_shipping_country()
			);
		}

		return '';
	}
}
