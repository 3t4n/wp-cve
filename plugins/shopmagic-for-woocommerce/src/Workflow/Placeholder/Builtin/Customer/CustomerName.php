<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Customer;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\CustomerBasedPlaceholder;

final class CustomerName extends CustomerBasedPlaceholder {

	public function get_slug(): string {
		return 'name';
	}

	public function get_description(): string {
		return esc_html__( "Display concatenation of first and last name for current Customer. If no name is provided, display Customer's username.", 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WC_Order::class ) ) {
			$order    = $this->resources->get( \WC_Order::class );
			$fallback = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
		} elseif ( $this->resources->has( \WP_Comment::class ) ) {
			$fallback = $this->resources->get( \WP_Comment::class )->comment_author ?: '';
		} else {
			$fallback = '';
		}

		if ( $this->resources->has( Customer::class ) ) {
			return $this->resources->get( Customer::class )->get_full_name() ?: $fallback;
		}

		return $fallback;
	}
}
