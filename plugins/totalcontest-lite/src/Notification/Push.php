<?php

namespace TotalContest\Notification;


/**
 * Push Notification Model
 * @package TotalContest\Notification
 * @since   1.1.0
 */
class Push extends Model {
	public function send() {
		wp_remote_post(
			'https://onesignal.com/api/v1/notifications',
			[
				'user-agent' => $this->getFrom(),
				'blocking'   => false,
				'sslverify'  => false,
				'headers'    => [
					'Content-Type'  => 'application/json; charset=utf-8',
					'Authorization' => 'Basic ' . $this->getArg( 'apiKey' )
				],
				'body'       => json_encode( [
					'app_id'            => $this->getArg( 'appId' ),
					'included_segments' => $this->getTo(),
					'data'              => $this->getArg( 'data', [] ),
					'contents'          => [ 'en' => $this->getBody() ],
					'headings'          => [ 'en' => $this->getSubject() ],
				], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ),
			]
		);
	}
}
