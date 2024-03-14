<?php

namespace TotalContest\Log;

use TotalContest\Contracts\Log\Model as ModelContract;
use TotalContestVendors\TotalCore\Contracts\Helpers\DateTime;

/**
 * Log Model
 * @package TotalLog\Log
 * @since   1.0.0
 */
class Model implements ModelContract {
	/**
	 * Log ID.
	 *
	 * @var int|null
	 * @since 1.0.0
	 */
	protected $id = null;

	/**
	 * Log date.
	 *
	 * @var DateTime
	 * @since 1.0.0
	 */
	protected $date = null;

	/**
	 * Log IP.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $ip = null;

	/**
	 * Log user agent.
	 * @var string
	 * @since 1.0.0
	 */
	protected $useragent = null;

	/**
	 * Log user ID.
	 * @var int|null
	 * @since 1.0.0
	 */
	protected $userId = null;

	/**
	 * Log user.
	 * @var \WP_User
	 * @since 1.0.0
	 */
	protected $user = null;

	/**
	 * Log contest ID.
	 * @var int|null
	 * @since 1.0.0
	 */
	protected $contestId = null;

	/**
	 * Log contest.
	 * @var \TotalContest\Contest\Model
	 * @since 1.0.0
	 */
	protected $contest = null;

	/**
	 * Log submission ID.
	 *
	 * @var int|null
	 * @since 1.0.0
	 */
	protected $submissionId = null;

	/**
	 * Log submission.
	 *
	 * @var \TotalContest\Submission\Model
	 * @since 1.0.0
	 */
	protected $submission = null;

	/**
	 * Log action.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $action = null;

	/**
	 * Log status.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected $status = null;

	/**
	 * Log details.
	 * @var array
	 * @since 1.0.0
	 */
	protected $details = [];

	/**
	 * Model constructor.
	 *
	 * @param $log array Log array.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $log ) {
		// Id
		if ( is_array( $log ) ):
			$this->id           = (int) $log['id'];
			$this->ip           = (string) $log['ip'];
			$this->useragent    = trim( $log['useragent'] );
			$this->userId       = (int) $log['user_id'];
			$this->contestId    = (int) $log['contest_id'];
			$this->submissionId = (int) $log['submission_id'];
			$this->action       = (string) $log['action'];
			$this->status       = (string) $log['status'];
			$this->details      = (array) json_decode( $log['details'], true );
			$this->date         = TotalContest( 'datetime', [ $log['date'], new \DateTimeZone('UTC') ] );
			$this->user         = new \WP_User( $this->userId );
			$this->contest      = TotalContest( 'contests.repository' )->getById( $this->contestId );
			$this->submission   = TotalContest( 'submissions.repository' )->getById( $this->submissionId );
		endif;
	}

	/**
	 * Get log id.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function getId() {
		return (int) $this->id;
	}

	/**
	 * Get log date.
	 *
	 * @return DateTime
	 * @since 1.0.0
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Get log Ip.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getIp() {
		return $this->ip;
	}

	/**
	 * Get user agent.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getUseragent() {
		return $this->useragent;
	}

	/**
	 * Get user ID.
	 *
	 * @return int|null
	 * @since 1.0.0
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * Get user.
	 *
	 * @return \WP_User
	 * @since 1.0.0
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Get contest ID.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function getContestId() {
		return $this->contestId;
	}

	/**
	 * Get contest model.
	 *
	 * @return \TotalContest\Contest\Model
	 * @since 1.0.0
	 */
	public function getContest() {
		return $this->contest;
	}

	/**
	 * Get submission ID.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function getSubmissionId() {
		return $this->submissionId;
	}

	/**
	 * Get submission model.
	 *
	 * @return \TotalContest\Submission\Model
	 * @since 1.0.0
	 */
	public function getSubmission() {
		return $this->submission;
	}

	/**
	 * Get action.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Get status.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Get details.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getDetails() {
		return $this->details;
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return [
			'id'         => $this->id,
			'ip'         => $this->ip,
			'useragent'  => $this->useragent,
			'user'       => $this->userId ? [
				'id'    => $this->userId,
				'login' => $this->user->user_login,
				'name'  => $this->user->display_name,
				'email' => $this->user->user_email,
			] : [ 'id' => $this->userId ],
			'contest'    => $this->getContest(),
			'submission' => $this->getSubmission(),
			'action'     => ucfirst( $this->action ),
			'status'     => ucfirst( $this->status ),
			'details'    => $this->details,
			'date'       => $this->date->format( DATE_ATOM ),
		];
	}

	/**
	 * Get Serializable JSON of this instance
	 *
	 * @return array
	 */
	#[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->toArray();
	}

}
