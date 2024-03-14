<?php
/**
 * Class State
 */

namespace Octolize\Shipping\Notices\Model;

/**
 * State Model.
 */
class State extends AbstractRegion {

	/**
	 * @var Country
	 */
	private $country;

	/**
	 * @param string $code .
	 * @param string $name .
	 */
	public function __construct( string $code, string $name, Country $country ) {
		parent::__construct( $code, $name );

		$this->country = $country;
	}

	/**
	 * @return Country
	 */
	public function get_country(): Country {
		return $this->country;
	}
}
