<?php namespace Includes\Libraries;

class Message extends Resource {


	public function __construct( $client = null ) {
		parent::__construct( null, $client );
	}

	public function sendMessage( $data ) {
		return $this->request( Client::POST, Client::PATH_MESSAGES . '/send', $data );
	}

	public function getUnreadCount() {
		return $this->request( Client::GET, Client::PATH_MESSAGES . '/unread/count' );
	}
}
