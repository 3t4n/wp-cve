<?php

namespace WC_BPost_Shipping\Container;

/**
 * Class WC_BPost_Shipping_Container_Postalcode contains and delivers specific regexp according to country code
 * @package WC_BPost_Shipping\Container
 */
class WC_BPost_Shipping_Container_Postalcode {

	/** @var string[] regexps indexed by country iso codes */
	private $postal_code = array(
		'AT' => '#^(\d{4})$#', //9999, Austria
		'BG' => '#^(\d{4})$#', //9999, Bulgaria
		'HR' => '#^(\d{5})$#', //99999, Croatia
		'CZ' => '#^(\d{3} \d{2})$#', //999 99, Czech Republic
		'DK' => '#^(\d{4})$#', //9999, Denmark
		'EE' => '#^(\d{5})$#', //99999, Estonia
		'FI' => '#^(\d{5})$#', //99999, Finland
		'FR' => '#^(\d{5})$#', //99999, France
		'DE' => '#^(\d{5})$#', //99999, Germany
		'GR' => '#^(\d{3} \d{2})$#', //999 99, Greece
		'HU' => '#^(\d{4})$#', //9999, Hungary
		'IT' => '#^(\d{5})$#', //99999, Italy
		'LV' => '#^(\d{4})$#', //9999, Latvia
		'LT' => '#^(\d{5})$#', //99999, Lithuania
		'LU' => '#^(\d{4})$#', //9999, Luxembourg
		'MT' => '#^[A-Z]{3} [0-9]{4}$#', //AAA 9999, Malta
		'NL' => '#^[0-9]{4} [A-Z]{2}$#', //9999 AA, Netherlands
		'PL' => '#^(\d{5})$#', //99999, Poland
		'RO' => '#^(\d{6})$#', //999999, Romania
		'SK' => '#^(\d{3} \d{2})$#', //999 99, Slovakia
		'SI' => '#^(\d{4})$#', //9999, Slovenia
		'ES' => '#^(\d{5})$#', //99999, Spain
		'SE' => '#^(\d{3} \d{2})$#', //999 99 //Sweden

		'BE' => '#^(\d{4})$#', //9999, Belgium
	);

	/**
	 * Provide the regexp to validate postal code againt a specific country
	 *
	 * @param string $country_code ISO 3166-1 alpha-2
	 *
	 * @return null|string valid regexp or null if country not found
	 */
	public function get_regex_for( $country_code ) {
		if ( ! ( $country_code && array_key_exists( $country_code, $this->postal_code ) ) ) {
			return null;
		}

		return $this->postal_code[ $country_code ];
	}
}
