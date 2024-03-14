<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Customer;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\CustomerBasedPlaceholder;

final class CustomerPhone extends CustomerBasedPlaceholder {
	public function get_slug(): string {
		return 'phone';
	}

	public function get_description(): string {
		return esc_html__( 'Display phone number of current Customer.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( Customer::class ) ) {
			return $this->resources->get( Customer::class )->get_phone();
		}

		return '';
	}
}
