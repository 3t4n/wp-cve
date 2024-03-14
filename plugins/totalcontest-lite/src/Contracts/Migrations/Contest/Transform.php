<?php

namespace TotalContest\Contracts\Migrations\Contest;

/**
 * Interface Transform
 * @package TotalContest\Contracts\Migrations\Contest
 */
interface Transform {
	/**
	 * Transform contest.
	 *
	 * @param $raw
	 *
	 * @return mixed
	 */
	public function transformContest( $raw );

	/**
	 * Transform options.
	 *
	 * @param $raw
	 *
	 * @return mixed
	 */
	public function transformOptions( $raw );

	/**
	 * Transform log.
	 *
	 * @param $raw
	 *
	 * @return mixed
	 */
	public function transformLog( $raw );

	/**
	 * Transform submission.
	 *
	 * @param $raw
	 *
	 * @return mixed
	 */
	public function transformSubmission( $raw );
}
