<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\DataSharing;

use WPDesk\ShopMagic\Workflow\Event\DataLayer;

class TestProvider implements DataProvider {

	/** @var DataProvider[] */
	private $providers;

	public function add_provider( DataProvider $provider ): void {
		$this->providers[] = $provider;
	}

	public function get_provided_data_domains(): array {
		$provided_domains = array_reduce(
			$this->providers,
			static function ( array $provided_domains, DataProvider $provider ): array {
				return array_merge( $provided_domains, $provider->get_provided_data_domains() );
			},
			[]
		);

		return apply_filters( 'shopmagic/core/test_data_provider/domains', $provided_domains );
	}

	public function get_provided_data(): DataLayer {
		$provided_data = array_reduce(
			$this->providers,
			function ( DataLayer $layer, DataProvider $provider ) {
				$inner_layer = $provider->get_provided_data();
				foreach ( $inner_layer->get_known_entries() as $known_entry ) {
					$layer->set( $known_entry, $inner_layer->get( $known_entry ) );
				}

				return $layer;
			},
			new DataLayer( [] )
		);

		return apply_filters( 'shopmagic/core/test_data_provider/data', $provided_data );
	}
}
