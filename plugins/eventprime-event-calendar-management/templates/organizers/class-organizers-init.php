<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for initialize the organizers module
 */

class EventM_Organizers {
	/**
	 * Constructor
	 */
	public function __construct() {

		if( EventM_Factory_Service::ep_is_request('admin') && EventM_Factory_Service::ep_get_request_param( 'taxonomy' ) == 'em_event_organizer' ) {
			include_once __DIR__ . '/admin/class-ep-organizer-admin.php';
		}
	}
}

new EventM_Organizers();