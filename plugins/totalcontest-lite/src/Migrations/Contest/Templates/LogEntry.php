<?php

namespace TotalContest\Migrations\Contest\Templates;

use TotalContest\Contracts\Migrations\Contest\Template\LogEntry as LogEntryContract;

/**
 * Log Migration Template.
 *
 * @package TotalContest\Migrations\Contest\Templates
 */
class LogEntry extends Template implements LogEntryContract {
	/**
	 * Contest log entry data.
	 *
	 * @var array $data
	 */
	protected $data = [
		'ip'        => '',
		'useragent' => '',
		'user_id'   => '',
		'contest_id'   => '',
		'choices'   => [],
		'action'    => '',
		'status'    => '',
		'details'   => [],
		'date'      => '',
	];

	/**
	 * @param $ip
	 */
	public function setIp( $ip ) {
		$this->data['ip'] = $ip;
	}

	/**
	 * @param $useragent
	 */
	public function setUseragent( $useragent ) {
		$this->data['useragent'] = $useragent;
	}

	/**
	 * @param $userId
	 */
	public function setUserId( $userId ) {
		$this->data['user_id'] = $userId;
	}

	/**
	 * @param $contestId
	 */
	public function setContestId( $contestId ) {
		$this->data['contest_id'] = $contestId;
	}

	/**
	 * @param $action
	 */
	public function setAction( $action ) {
		$this->data['action'] = $action;
	}

	/**
	 * @param $date
	 */
	public function setDate( $date ) {
		$this->data['date'] = $date;
	}

	/**
	 * @param $status
	 */
	public function setStatus( $status ) {
		$this->data['status'] = $status;
	}

	/**
	 * @param $choiceUid
	 */
	public function addChoice( $choiceUid ) {
		$this->data['choices'][] = $choiceUid;
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function addDetail( $key, $value ) {
		$this->data['details'][ $key ] = $value;
	}
}
