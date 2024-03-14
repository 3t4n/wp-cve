<?php

namespace TotalContest\Contracts\Migrations\Contest;

use TotalContest\Contracts\Migrations\Contest\Template\Contest;
use TotalContest\Contracts\Migrations\Contest\Template\LogEntry;
use TotalContest\Contracts\Migrations\Contest\Template\Options;
use TotalContest\Contracts\Migrations\Contest\Template\Submission;

/**
 * Interface Load
 * @package TotalContest\Contracts\Migrations\Contest
 */
interface Load {

	/**
	 * @param Contest $contest
	 *
	 * @return mixed
	 */
	public function loadContest( Contest $contest );

	/**
	 * @param Options $options
	 *
	 * @return mixed
	 */
	public function loadOptions( Options $options );

	/**
	 * @param Contest  $contest
	 * @param LogEntry $logEntry
	 *
	 * @return mixed
	 */
	public function loadLogEntry( Contest $contest, LogEntry $logEntry );

	/**
	 * @param Contest    $contest
	 * @param Submission $submission
	 *
	 * @return mixed
	 */
	public function loadSubmission( Contest $contest, Submission $submission );
}
