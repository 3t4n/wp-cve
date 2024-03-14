<?php

namespace TotalContest\Notification;


/**
 * Mail Notification Model
 * @package TotalLog\Notification
 * @since   1.1.0
 */
class Mail extends Model {
	public function send() {
		wp_mail( $this->getTo(), $this->getSubject(), $this->getBody(), [ 'Content-Type: text/html; charset=UTF-8' ] );
	}
}