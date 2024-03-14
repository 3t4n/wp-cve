<?php
/**
 * Class AbstractRegion
 */

namespace Octolize\Shipping\Notices\Model;

/**
 * AbstractRegion.
 */
abstract class AbstractRegion implements Region {

	/**
	 * @var string
	 */
	private $code;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param string $code .
	 * @param string $name .
	 */
	public function __construct( string $code, string $name ) {
		$this->code = $code;
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function get_code(): string {
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return $this->name;
	}
}
