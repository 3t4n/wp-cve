<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for initialize the venues module
 */

class EventM_Venues {
	/**
	 * Constructor
	 */
	public function __construct() {

		if( EventM_Factory_Service::ep_is_request('admin') && EventM_Factory_Service::ep_get_request_param( 'taxonomy' ) == 'em_venue' ) {
			include_once __DIR__ . '/admin/class-ep-venue-admin.php';
		}
	}
}

new EventM_Venues();