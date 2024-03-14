<?php

namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend;

use QuadLayers\IGG\Api\Rest\Endpoints\Base as Endpoints;

/**
 * Base Class
 */
abstract class Base extends Endpoints {

	public function get_rest_permission() {
		if ( ! current_user_can( 'qligg_manage_feeds' ) ) {
			return false;
		}
		return true;
	}

}
