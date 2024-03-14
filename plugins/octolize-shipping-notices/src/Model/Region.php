<?php
/**
 * Interface Region
 */

namespace Octolize\Shipping\Notices\Model;

/**
 * Region.
 */
interface Region {

	/**
	 * @return string
	 */
	public function get_code(): string;

	/**
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * @return string
	 */
	public function __toString(): string;
}
