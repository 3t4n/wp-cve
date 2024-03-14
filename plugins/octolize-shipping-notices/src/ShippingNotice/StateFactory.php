<?php
/**
 * Class StateFactory
 */

namespace Octolize\Shipping\Notices\ShippingNotice;

use Exception;
use Octolize\Shipping\Notices\Model\Country;
use Octolize\Shipping\Notices\Model\State;
use WC_Countries;

/**
 * State factory.
 */
class StateFactory {

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
	 * @param string $country_code
	 * @param string $state_code
	 *
	 * @return State
	 * @throws Exception
	 */
	public function get_state( string $country_code, string $state_code ): State {
		$country = $this->country_factory->get_country( $country_code );

		$states = $this->countries->get_states( $country->get_code() );

		if ( is_array( $states ) && isset( $states[ $state_code ] ) && is_string( $states[ $state_code ] ) ) {
			return $this->get_single_state( $state_code, $states[ $state_code ], $country );
		}

		throw new Exception();
	}

	/**
	 * @param string  $state_code .
	 * @param string  $state_name .
	 * @param Country $country    .
	 *
	 * @return State
	 * @codeCoverageIgnore
	 */
	protected function get_single_state( string $state_code, string $state_name, Country $country ): State {
		return new State( $state_code, $state_name, $country );
	}
}
