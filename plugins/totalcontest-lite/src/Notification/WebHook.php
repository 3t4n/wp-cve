<?php

namespace TotalContest\Notification;


/**
 * WebHook Notification Model
 * @package TotalContest\Notification
 * @since   1.1.0
 */
class WebHook extends Model {
	public function send() {
		wp_remote_post( $this->getTo(), [
			'user-agent' => $this->getFrom(),
			'blocking'   => false,
			'sslverify'  => false,
			'body'       => $this->getBody()
		] );
	}
}