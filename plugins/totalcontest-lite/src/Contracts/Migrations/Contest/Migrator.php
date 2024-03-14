<?php

namespace TotalContest\Contracts\Migrations\Contest;

use TotalContest\Contracts\Migrations\Contest\Template\Contest;

/**
 * Interface Migrator
 * @package TotalContest\Contracts\Migrations\Contest
 */
interface Migrator extends \JsonSerializable {

	/**
	 * @return int
	 */
	public function getCount();

	/**
	 * @return int
	 */
	public function getMigratedCount();

	/**
	 * @return Contest[]
	 */
	public function migrate();

}
