<?php

namespace Wicked_Folders\REST_API\v1;

use \WP_REST_Controller;

// Disable direct load
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

class REST_API extends WP_REST_Controller {

    protected $version = 1;

    protected $base = 'wicked-folders/v1';
}
