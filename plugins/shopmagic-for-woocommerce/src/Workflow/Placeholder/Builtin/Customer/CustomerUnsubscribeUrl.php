<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Customer;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Marketing\Subscribers\PreferencesRoute;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\CustomerBasedPlaceholder;

final class CustomerUnsubscribeUrl extends CustomerBasedPlaceholder {

	/** @var PreferencesRoute */
	private $preferences_route;

	public function __construct( PreferencesRoute $preferences_route ) {
		$this->preferences_route = $preferences_route;
	}

	public function get_slug(): string {
		return 'unsubscribe_url';
	}

	public function get_description(): string {
		return esc_html__( 'Display link for customer communication preferences page.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( ! $this->resources->has( Customer::class ) ) {
			$this->logger->error( sprintf( 'No Customer provided for `%s`', $this->get_slug() ) );

			return '';
		}

		return $this->preferences_route->create_preferences_url( $this->resources->get( Customer::class ) );
	}
}
