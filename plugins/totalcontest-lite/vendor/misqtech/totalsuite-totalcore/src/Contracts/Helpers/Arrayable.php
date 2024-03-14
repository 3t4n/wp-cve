<?php

namespace TotalContestVendors\TotalCore\Contracts\Helpers;

/**
 * Interface Arrayable
 * @package TotalContestVendors\TotalCore\Contracts\Helpers
 */
interface Arrayable {

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray();

}