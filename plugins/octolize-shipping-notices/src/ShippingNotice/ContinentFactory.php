<?php
/**
 * Class ContinentFactory
 */

namespace Octolize\Shipping\Notices\ShippingNotice;

use Exception;
use Octolize\Shipping\Notices\Model\Continent;
use Octolize\Shipping\Notices\Model\Country;
use WC_Countries;

/**
 * Continent factory.
 */
class ContinentFactory {

	/**
	 * @var WC_Countries
	 */
	private $countries;

	/**
	 * @var CountryFactory
	 */
	private $country_factory;

	/**
	 * @param WC_Countries $countries .
	 */
	public function __construct( WC_Countries $countries, CountryFactory $country_factory ) {
		$this->countries       = $countries;
		$this->country_factory = $country_factory;
	}

	/**
	 * @param string $continent_code .
	 *
	 * @return Continent
	 * @throws Exception
	 */
	public function get_continent( string $continent_code ): Continent {
		$continents = $this->countries->get_continents();

		if ( isset( $continents[ $continent_code ] ) ) {
			$countries = [];

			foreach ( $continents[ $continent_code ]['countries'] as $country_code ) {
				$countries[] = $this->country_factory->get_country( $country_code );
			}

			return $this->get_single_continent( $continent_code, $continents[ $continent_code ]['name'], $countries );
		}

		throw new Exception();
	}

	/**
	 * @param string    $continent_code .
	 * @param string    $continent_name .
	 * @param Country[] $countries      .
	 *
	 * @return Continent
	 * @codeCoverageIgnore
	 */
	protected function get_single_continent( string $continent_code, string $continent_name, array $countries ): Continent {
		return new Continent( $continent_code, $continent_name, $countries );
	}
}
