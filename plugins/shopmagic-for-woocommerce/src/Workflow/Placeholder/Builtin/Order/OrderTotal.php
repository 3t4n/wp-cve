<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderBasedPlaceholder;

final class OrderTotal extends WooCommerceOrderBasedPlaceholder {

	public function get_slug(): string {
		return 'total';
	}

	public function get_description(): string {
		return esc_html__( 'Display the total value of current order. Including currency format.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( ! $this->resources->has( \WC_Order::class ) ) {
			return '';
		}

		$total = $this->resources->get( \WC_Order::class )->get_total();
		$price = number_format(
			abs( (float) $total ),
			wc_get_price_decimals(),
			wc_get_price_decimal_separator(),
			wc_get_price_thousand_separator()
		);

		return ( $total < 0 ? '-' : '' ) . html_entity_decode( sprintf( get_woocommerce_price_format(), '' . get_woocommerce_currency_symbol() . '', $price ), ENT_COMPAT, 'UTF-8' );
	}
}
