<?php
/**
 * Handle Watchful exceptions.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     watchful
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful;

/**
 * Class for handling Watchful exceptions.
 */
class ExceptionHandler {

	/**
	 * The constructor.
	 */
	public function __construct() {
		set_exception_handler( array( $this, 'exception' ) );
	}

    /**
     * Handle the exception.
     *
     * @param \Exception|\Throwable|\Error $exception The exception to handle.
     */
	public function exception( $exception ) {

	    $response = array(
	        'error' => 1
        );

	    if ($exception instanceof \Exception || $exception instanceof \Watchful\Exception || $exception instanceof \Error) {
            $response = array(
                'error'   => 1,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'details' => json_encode([
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine()
                ])
            );
        }

		// Check for instance of `\Watchful\Exception`.
		if ( $exception instanceof \Watchful\Exception && ! is_null( $exception->getData() ) ) {
			$response['data'] = $exception->getData();
		}

		if ( function_exists( 'http_response_code' ) &&  $exception instanceof \Exception) {
			http_response_code( $exception->getCode() );
		}
		echo wp_json_encode( $response );
		die();

	}
}
