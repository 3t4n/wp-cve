<?php

namespace TotalContestVendors\TotalCore\Contracts\Limitations;

/**
 * Interface Limitation
 * @package TotalContestVendors\TotalCore\Contracts\Limitations
 */
interface Limitation {
	/**
	 * Limitation logic.
	 *
	 * @return bool
	 */
	public function check();
}