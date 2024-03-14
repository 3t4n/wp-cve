<?php
/**
 * Class RegionFactory
 */

namespace Octolize\Shipping\Notices\ShippingNotice;

use Exception;
use Octolize\Shipping\Notices\Model\World;
use Octolize\Shipping\Notices\Model\Region;

/**
 * Region factory.
 */
class RegionFactory {

	/**
	 * @var World
	 */
	private $all_regions;

	/**
	 * @var ContinentFactory
	 */
	private $continent_factory;

	/**
	 * @var StateFactory
	 */
	private $state_factory;

	/**
	 * @var CountryFactory
	 */
	private $country_factory;

	/**
	 * @param World            $all_regions       .
	 * @param ContinentFactory $continent_factory .
	 * @param CountryFactory   $country_factory   .
	 * @param StateFactory     $state_factory     .
	 */
	public function __construct(
		World $all_regions,
		ContinentFactory $continent_factory,
		CountryFactory $country_factory,
		StateFactory $state_factory
	) {
		$this->all_regions       = $all_regions;
		$this->country_factory   = $country_factory;
		$this->continent_factory = $continent_factory;
		$this->state_factory     = $state_factory;
	}

	/**
	 * @param string[] $regions
	 *
	 * @return Region[]
	 */
	public function get_regions( array $regions ): array {
		$regions_list = [];

		try {
			foreach ( $regions as $region ) {
				[ $type, $id ] = array_pad( explode( ':', $region, 2 ), 2, null );

				if ( $type === $this->all_regions->get_code() ) {
					$regions_list[] = $this->all_regions;
				} elseif ( $type === 'continent' ) {
					$regions_list[] = $this->continent_factory->get_continent( $id );
				} elseif ( $type === 'country' ) {
					$regions_list[] = $this->country_factory->get_country( $id );
				} elseif ( $type === 'state' ) {
					[ $country_code, $state_code ] = explode( ':', $id );
					$regions_list[] = $this->state_factory->get_state( $country_code, $state_code );
				}
			}
		} catch ( Exception $e ) { //phpcs:ignore
			// Do nothing.
		}

		return $regions_list;
	}
}
