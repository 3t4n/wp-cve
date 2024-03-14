<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Components;

use WPDesk\ShopMagic\DataSharing\DataProvider;
use WPDesk\ShopMagic\DataSharing\DataReceiver;

/**
 * @template T of object
 * @template-extends AbstractCollection<T>
 */
abstract class MatchableCollection extends AbstractCollection {

	/**
	 * @param DataProvider $provider
	 *
	 * @return static<T>
	 */
	public function match_receivers( DataProvider $provider ): self {
		$provided_data_domains = $provider->get_provided_data_domains();

		return $this->filter(
			static function ( DataReceiver $item ) use ( $provided_data_domains ): bool {
				foreach ( $item->get_required_data_domains() as $required_domain ) {
					$provided_data_found = array_reduce(
						$provided_data_domains,
						static function ( $carry, $provided_domain ) use ( $required_domain ) {
							if ( is_a( $provided_domain, $required_domain, true ) ) {
								return true;
							}

							return $carry;
						},
						false
					);

					if ( ! $provided_data_found ) {
						return false;
					}
				}

				return true;
			}
		);
	}
}
