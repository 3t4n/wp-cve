<?php
/**
 * ClientSessionException.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Reporting\Exceptions;

use Etracker\Exceptions\PluginException;

/**
 * Exception thrown during connect to etrackers reporting API.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class ClientSessionException extends PluginException {
	/**
	 * Response Headers.
	 *
	 * @var array
	 */
	public $response_headers = array();

	/**
	 * Response Body.
	 *
	 * @var string
	 */
	public $response_body = '';

	/**
	 * Constructor
	 *
	 * @param string    $message          Error message.
	 * @param array     $response_headers Response headers.
	 * @param string    $response_body    Response body.
	 * @param integer   $code             Error code.
	 * @param Throwable $previous         Exception.
	 */
	public function __construct( $message, $response_headers = array(), $response_body = '', $code = 0, Throwable $previous = null ) {
		if ( ! empty( $response_body ) ) {
			$message .= ' Response Body: ' . $response_body;
		}

		if ( ! empty( $response_headers ) && ( is_array( $response_headers ) || is_object( $response_headers ) ) ) {
			$message .= ' Response Headers: ' . json_encode( $response_headers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
		}

		parent::__construct( $message, $code, $previous );
	}
}
