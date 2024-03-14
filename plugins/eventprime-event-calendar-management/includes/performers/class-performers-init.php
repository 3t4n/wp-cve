<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for initialize the performers module
 */

class EventM_Performers {

	/**
	 * Constructor
	 */
    public function __construct() {
	    if( EventM_Factory_Service::ep_is_request('admin') /* && EventM_Factory_Service::ep_get_request_param( 'post_type' ) == 'em_performer' */) {
		    include_once EP_BASE_DIR . 'includes/performers/admin/class-ep-performer-admin.php';
	    }
    }

}

new EventM_Performers();