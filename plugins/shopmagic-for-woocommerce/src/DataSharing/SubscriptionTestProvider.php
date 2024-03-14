<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\DataSharing;

use WPDesk\ShopMagic\Workflow\Event\DataLayer;

class SubscriptionTestProvider implements DataProvider {

	public function get_provided_data_domains(): array {
		return [ \WC_Subscription::class ];
	}

	public function get_provided_data(): DataLayer {
		if ( \function_exists( 'wcs_get_subscriptions' ) ) {
			$subscriptions = wcs_get_subscriptions(
				[
					'limit'   => 1,
					'orderby' => 'date_created',
					'order'   => 'DESC',
				]
			);

			return new DataLayer( [ \WC_Subscription::class => reset( $subscriptions ) ] );
		}

		return new DataLayer( [] );
	}
}
