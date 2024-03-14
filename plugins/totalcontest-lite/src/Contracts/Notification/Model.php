<?php

namespace TotalContest\Contracts\Notification;

use JsonSerializable;
use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;

/**
 * Interface Model
 * @package TotalContest\Contracts\Notification
 */
interface Model extends Arrayable, JsonSerializable {
	/**
	 * Set subject.
	 *
	 * @param $subject
	 *
	 * @return mixed
	 */
	public function setSubject( $subject );

	/**
	 * Set body.
	 *
	 * @param $body
	 *
	 * @return mixed
	 */
	public function setBody( $body );

	/**
	 * Set from.
	 *
	 * @param $from
	 *
	 * @return mixed
	 */
	public function setFrom( $from );

	/**
	 * Set to.
	 *
	 * @param $to
	 *
	 * @return mixed
	 */
	public function setTo( $to );

	/**
	 * Set reply to.
	 *
	 * @param $replyTo
	 *
	 * @return mixed
	 */
	public function setReplyTo( $replyTo );

	/**
	 * Get subject.
	 *
	 * @return mixed
	 */
	public function getSubject();

	/**
	 * Set body.
	 *
	 * @return mixed
	 */
	public function getBody();

	/**
	 * Get from.
	 *
	 * @return mixed
	 */
	public function getFrom();

	/**
	 * Get to.
	 *
	 * @return mixed
	 */
	public function getTo();

	/**
	 * Get reply to.
	 *
	 * @return mixed
	 */
	public function getReplyTo();

}