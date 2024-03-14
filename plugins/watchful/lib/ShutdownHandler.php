<?php
/**
 * Handle unexpected shutdown.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     watchful
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful;

use Watchful\Helpers\ResponseFormatter;

/**
 * Class for handling unexpected shutdowns.
 */
class ShutdownHandler {

	/**
	 * The constructor.
	 */
	public function __construct() {
        register_shutdown_function( array($this, 'shutdown'));
	}

	public function shutdown() {
	    $error = error_get_last();
	    if (empty($error) || $error['type'] !== E_ERROR) {
	        return;
        }
        $response = array(
            'error'   => 1,
            'code'    => $error['type'],
            'message' => $error['message'],
            'details' => $error
        );

       echo ResponseFormatter::add_response_delimiters(wp_json_encode($response));
       die();
    }
}
