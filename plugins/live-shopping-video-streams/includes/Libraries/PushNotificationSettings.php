<?php namespace Includes\Libraries;

class PushNotificationSettings extends Resource {


	public function __construct( $client = null ) {
		parent::__construct( null, $client );
	}

	public function get() {
		return $this->request( Client::GET, Client::PATH_PUSH_NOTIFICATION_SETTINGS );
	}

}
