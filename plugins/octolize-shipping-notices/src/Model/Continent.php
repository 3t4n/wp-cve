<?php
/**
 * Class Continent
 */

namespace Octolize\Shipping\Notices\Model;

/**
 * Continent Model.
 */
class Continent extends AbstractRegion {

	/**
	 * @var Region[]
	 */
	private $countries;

	/**
	 * @param string   $code      .
	 * @param string   $name      .
	 * @param Region[] $countries .
	 */
	public function __construct( string $code, string $name, array $countries ) {
		parent::__construct( $code, $name );

		$this->countries = $countries;
	}

	/**
	 * @return Region[]
	 */
	public function get_countries(): array {
		return $this->countries;
	}
}
