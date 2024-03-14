<?php

namespace TotalContest\Notification;

use TotalContest\Contracts\Notification\Model as NotificationModel;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Notification Model
 * @package TotalContest\Notification
 * @since   1.1.0
 */
abstract class Model implements NotificationModel {
	/**
	 * @var string $subject
	 */
	protected $subject;
	/**
	 * @var mixed $body
	 */
	protected $body;
	/**
	 * @var string|array $from
	 */
	protected $from;
	/**
	 * @var string|array $to
	 */
	protected $to;
	/**
	 * @var string|array $replyTo
	 */
	protected $replyTo;
	/**
	 * @var mixed $args
	 */
	protected $args;

	/**
	 * Model constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = [] ) {
		if ( ! empty( $args['to'] ) ):
			$this->setTo( $args['to'] );
		endif;

		if ( ! empty( $args['from'] ) ):
			$this->setFrom( $args['from'] );
		endif;

		if ( ! empty( $args['replyTo'] ) ):
			$this->setReplyTo( $args['replyTo'] );
		endif;

		if ( ! empty( $args['subject'] ) ):
			$this->setSubject( $args['subject'] );
		endif;

		if ( ! empty( $args['body'] ) ):
			$this->setBody( $args['body'] );
		endif;

		if ( ! empty( $args['args'] ) ):
			$this->setArgs( $args['args'] );
		endif;
	}

	/**
	 * @param $subject
	 *
	 * @return $this
	 */
	public function setSubject( $subject ) {
		$this->subject = (string) $subject;

		return $this;
	}

	/**
	 * @param $body
	 *
	 * @return $this
	 */
	public function setBody( $body ) {
		$this->body = $body;

		return $this;
	}

	/**
	 * @param $from
	 *
	 * @return $this
	 */
	public function setFrom( $from ) {
		$this->from = $from;

		return $this;
	}

	/**
	 * @param $to
	 *
	 * @return $this
	 */
	public function setTo( $to ) {
		$this->to = $to;

		return $this;
	}

	/**
	 * @param $replyTo
	 *
	 * @return $this
	 */
	public function setReplyTo( $replyTo ) {
		$this->replyTo = $replyTo;

		return $this;
	}

	/**
	 * @param $args
	 *
	 * @return $this
	 */
	public function setArgs( $args ) {
		$this->args = $args;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * @return mixed
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @return mixed
	 */
	public function getReplyTo() {
		return $this->replyTo;
	}

	/**
	 * @return mixed
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * @return mixed
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * @return mixed
	 */
	public function getArgs() {
		return $this->args;
	}

	/**
	 * @param      $argkey
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function getArg( $argkey, $default = null ) {
		return Arrays::getDotNotation( $this->args, $argkey, $default );
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

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return [
			'subject' => $this->subject,
			'body'    => $this->body,
			'from'    => $this->from,
			'to'      => $this->to,
			'replyTo' => $this->replyTo,
			'args'    => $this->args,
		];
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return isset( $this->{$offset} );
	}

	/**
	 * @param mixed $offset
	 *
	 * @return mixed
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return isset( $this->{$offset} ) ? $this->{$offset} : null;
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		$this->{$offset} = $value;
	}

	/**
	 * @param mixed $offset
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		$this->{$offset} = null;
	}
}
