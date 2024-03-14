<?php

namespace TotalContest\Contracts\Migrations\Contest;

use TotalContest\Contracts\Migrations\Contest\Template\Contest;

/**
 * Interface Extract
 * @package TotalContest\Contracts\Migrations\Contest
 */
interface Extract {

	/**
	 * Count contests.
	 *
	 * @return int
	 */
	public function getCount();

	/**
	 * Get contests.
	 *
	 * @return array
	 */
	public function getContests();

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function getOptions();

	/**
	 * Get log entries.
	 *
	 * @param Contest $contest
	 *
	 * @return array
	 */
	public function getLogEntries( Contest $contest );

	/**
	 * Get submissions.
	 *
	 * @param Contest $contest
	 *
	 * @return array
	 */
	public function getSubmissions( Contest $contest );

	/**
	 * Get migrated contests ids.
	 *
	 * @return array
	 */
	public function getMigratedContestsIds();

}
