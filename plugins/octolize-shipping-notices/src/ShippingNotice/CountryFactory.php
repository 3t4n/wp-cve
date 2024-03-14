<?php
/**
 * Class CountryFactory
 */

namespace Octolize\Shipping\Notices\ShippingNotice;

use Exception;
use Octolize\Shipping\Notices\Model\Country;
use WC_Countries;

/**
 * Country factory.
 */
class CountryFactory {

	/**
	 * @var WC_Countries
	 */
	private $countries;

	/**
	 * @param WC_Countries $countries .
	 */
	public function __construct( WC_Countries $countries ) {
		$this->countries = $countries;
	}

	/**
	 * @param string $country_code .
	 *
	 * @return Country
	 * @throws Exception
	 */
	public function get_country( string $country_code ): Country {
		if ( $this->countries->country_exists( $country_code ) ) {
			return $this->get_single_country( $country_code, $this->countries->get_countries()[ $country_code ] );
		}

		throw new Exception();
	}

	/**
	 * @param string $country_code .
	 * @param string $country_name .
	 *
	 * @return Country
	 * @codeCoverageIgnore
	 */
	protected function get_single_country( string $country_code, string $country_name ): Country {
		return new Country( $country_code, $country_name );
	}
}
