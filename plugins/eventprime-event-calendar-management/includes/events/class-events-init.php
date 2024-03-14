<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for initialize the events module
 */

class EventM_Events {

	/**
	 * Constructor
	 */
    public function __construct() {
	    if( EventM_Factory_Service::ep_is_request('admin') /* && EventM_Factory_Service::ep_get_request_param( 'post_type' ) == 'em_event' */ ) {
		    include_once EP_BASE_DIR . 'includes/events/admin/class-ep-event-admin.php';
	    }
    }

}

new EventM_Events();