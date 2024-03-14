<?php

namespace TotalContest\Contracts\Log;

use JsonSerializable;
use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;
use TotalContestVendors\TotalCore\Contracts\Helpers\DateTime;

/**
 * Interface Model
 * @package TotalContest\Contracts\Log
 */
interface Model extends Arrayable, JsonSerializable {
	/**
	 * Get log id.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function getId();

	/**
	 * Get log date.
	 *
	 * @return DateTime
	 * @since 1.0.0
	 */
	public function getDate();

	/**
	 * Get log Ip.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getIp();

	/**
	 * Get user agent.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getUseragent();

	/**
	 * Get user ID.
	 *
	 * @return int|null
	 * @since 1.0.0
	 */
	public function getUserId();

	/**
	 * Get user.
	 *
	 * @return \WP_User
	 * @since 1.0.0
	 */
	public function getUser();

	/**
	 * Get contest ID.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function getContestId();

	/**
	 * Get contest model.
	 *
	 * @return \TotalContest\Contest\Model
	 * @since 1.0.0
	 */
	public function getContest();

	/**
	 * Get submission ID.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function getSubmissionId();

	/**
	 * Get submission model.
	 *
	 * @return \TotalContest\Submission\Model
	 * @since 1.0.0
	 */
	public function getSubmission();

	/**
	 * Get action.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getAction();

	/**
	 * Get status.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getStatus();

	/**
	 * Get details.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getDetails();
}
