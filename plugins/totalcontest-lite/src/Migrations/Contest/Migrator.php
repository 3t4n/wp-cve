<?php

namespace TotalContest\Migrations\Contest;

use Exception;
use TotalContest\Contracts\Migrations\Contest\Extract;
use TotalContest\Contracts\Migrations\Contest\Load;
use TotalContest\Contracts\Migrations\Contest\Migrator as MigratorContract;
use TotalContest\Contracts\Migrations\Contest\Transform;
use TotalContest\Migrations\Contest\Templates\Contest;

/**
 * Contests Migrator.
 * @package TotalContest\Migrations\Contests
 */
abstract class Migrator implements MigratorContract {

	/**
	 * @var array $env
	 */
	protected $env;
	/**
	 * @var Extract $extract
	 */
	protected $extract;
	/**
	 * @var Transform $transform
	 */
	protected $transform;
	/**
	 * @var Load $load
	 */
	protected $load;

	/**
	 * Migrator constructor.
	 *
	 * @param array     $env
	 * @param Extract   $extract
	 * @param Transform $transform
	 * @param Load      $load
	 */
	public function __construct( $env, Extract $extract, Transform $transform, Load $load ) {
		$this->env       = $env;
		$this->extract   = $extract;
		$this->transform = $transform;
		$this->load      = $load;
	}

	/**
	 * Count contests to migrate.
	 * @return int
	 */
	public function getCount() {
		return $this->extract->getCount();
	}

	/**
	 * @return int
	 */
	public function getMigratedCount() {
		return count( $this->extract->getMigratedContestsIds() );
	}

	/**
	 * Migrate contests.
	 *
	 * @param callable $onProgress Progress callback.
	 *
	 * @return Contest[]
	 */
	public function migrate( $onProgress = null ) {
		// Remove WP filters
		remove_filter( 'content_save_pre', 'wp_targeted_link_rel' );

		$total          = $this->getCount();
		$oldContests    = $this->extract->getContests();
		$loadedContests = [];
		foreach ( $oldContests as $contestIndex => $contest ):
			try {
				$transformedContest = $this->transform->transformContest( $contest );

				$loadedContest = $this->load->loadContest( $transformedContest );
				if ( ! is_wp_error( $loadedContest ) ):
					$loadedContests[] = $loadedContest;
				else:
					continue;
				endif;

				$oldLogs = $this->extract->getLogEntries( $transformedContest );
				foreach ( $oldLogs as $logEntry ):
					$transformedLogEntry = $this->transform->transformLog( $logEntry );
					$this->load->loadLogEntry( $transformedContest, $transformedLogEntry );
				endforeach;

				$oldSubmissions = $this->extract->getSubmissions( $transformedContest );
				foreach ( $oldSubmissions as $submission ):
					$transformedSubmission = $this->transform->transformSubmission( $submission );
					$this->load->loadSubmission( $transformedContest, $transformedSubmission );
				endforeach;

				if ( is_callable( $onProgress ) ):
					call_user_func( $onProgress, ( $contestIndex + 1 ), $total );
				endif;
			} catch ( Exception $exception ) {

			}
		endforeach;

		return $loadedContests;
	}
}
