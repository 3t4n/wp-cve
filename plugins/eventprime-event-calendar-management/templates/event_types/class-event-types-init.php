<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for initialize the event types module
 */

class EventM_Event_Types {
	/**
	 * Constructor
	 */
	public function __construct() {
		if( EventM_Factory_Service::ep_is_request('admin') && EventM_Factory_Service::ep_get_request_param( 'taxonomy' ) == 'em_event_type' ) {
			include_once __DIR__ . '/admin/class-ep-event-type-admin.php';
		}
	}
}

new EventM_Event_Types();