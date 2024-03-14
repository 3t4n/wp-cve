<?php

namespace TotalContestVendors\TotalCore\Contracts\Foundation;

use JsonSerializable;
use Serializable;
use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;

/**
 * Interface Environment
 * @package TotalContestVendors\TotalCore\Contracts\Foundation
 */
interface Environment extends \ArrayAccess, JsonSerializable, Arrayable {
	/**
	 * Get item.
	 *
	 * @param      $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null );

	/**
	 * Set item.
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public function set( $key, $value );
}
