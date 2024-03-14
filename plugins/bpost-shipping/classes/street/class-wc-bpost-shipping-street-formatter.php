<?php
namespace WC_BPost_Shipping\Street;

/**
 * Class WC_BPost_Shipping_Street_Formatter formats... an address
 * @package WC_BPost_Shipping\Street
 */
class WC_BPost_Shipping_Street_Formatter {

	/** @var \string[] */
	private $address_to_format;

	/**
	 * @param string[] $address_to_format
	 */
	public function __construct( array $address_to_format ) {
		$this->address_to_format = $address_to_format;
	}

	/**
	 * Return formatted address according to google maps api standard
	 * @return string
	 */
	public function get_gmaps_address() {
		return sprintf(
			'%s %s, %s %s, %s, %s',
			$this->address_to_format['address_1'],
			$this->address_to_format['address_2'],
			$this->address_to_format['postcode'],
			$this->address_to_format['city'],
			$this->address_to_format['state'],
			$this->address_to_format['country']
		);
	}
}
