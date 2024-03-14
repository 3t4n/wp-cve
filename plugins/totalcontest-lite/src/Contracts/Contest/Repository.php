<?php

namespace TotalContest\Contracts\Contest;

use TotalContest\Contracts\Contest\Model as ModelContract;

/**
 * Contest repository
 * @package TotalContest\Contest
 * @since   1.0.0
 */
interface Repository {
	/**
	 * Get contests.
	 *
	 * @param $query
	 *
	 * @return ModelContract[]
	 */
	public function get( $query );

	/**
	 * Get a contest by id.
	 *
	 * @param $contest
	 *
	 * @return ModelContract|null
	 * @since 1.0.0
	 */
	public function getById( $contest );
}