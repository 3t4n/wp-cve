<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Customer;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\CustomerBasedPlaceholder;


final class CustomerUsername extends CustomerBasedPlaceholder {

	public function get_slug(): string {
		return 'username';
	}

	public function get_description(): string {
		return __( "Displays the customer's username. It will be blank for guests.", 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( ! $this->resources->has( Customer::class ) ) {
			return '';
		}

		$customer = $this->resources->get( Customer::class );
		if ( $customer->is_guest() ) {
			return '';
		}

		return $customer->get_username();
	}
}
