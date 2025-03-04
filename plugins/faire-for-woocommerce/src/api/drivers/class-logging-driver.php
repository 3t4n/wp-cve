<?php

namespace Faire\Wc\Api\Drivers;

use Faire\Wc\Api\Interfaces\Api_Driver_Interface;
use Faire\Wc\Api\Request;
use Faire\Wc\Api\Response;
use WC_Logger_Interface;

/**
 * A REST API driver decorator that automatically logs requests and responses.
 *
 * This is a REST API driver DECORATOR class, which means that it is not a standalone driver but instead decorates
 * another driver. It does so to log the parameters and return values of the "inner" driver.
 *
 * @since [*next-version*]
 *
 * @see Api_Driver_Interface
 */
class Logging_Driver implements Api_Driver_Interface {
	/**
	 * The logger instance.
	 *
	 * @since [*next-version*]
	 *
	 * @var WC_Logger_Interface
	 */
	protected $logger;

	/**
	 * The driver to decorate.
	 *
	 * @since [*next-version*]
	 *
	 * @var Api_Driver_Interface
	 */
	protected $driver;

	/**
	 * Constructor.
	 *
	 * @since [*next-version*]
	 *
	 * @param WC_Logger_Interface  $logger The logger instance to use for logging.
	 * @param Api_Driver_Interface $driver The driver instance to decorate.
	 */
	public function __construct( WC_Logger_Interface $logger, Api_Driver_Interface $driver ) {
		$this->logger = $logger;
		$this->driver = $driver;
	}

	/**
	 * {@inheritdoc}
	 *
	 * Before invoking the inner driver's {@link send()} method, it logs the request data.
	 * The response returned from the inner driver is also logged, after which it is returned.
	 *
	 * @since [*next-version*]
	 */
	public function send( Request $request ) {
		// Log the request.
		$this->log_request( 'Request:', $request );

		// Send the request using the inner driver.
		$response = $this->driver->send( $request );

		// Log the response.
		$this->log_response( 'Response:', $response );

		// Return the response from the inner driver.
		return $response;
	}

	/**
	 * Logs a request.
	 *
	 * @since [*next-version*]
	 *
	 * @param string  $message Prefix message to include in the log.
	 * @param Request $request The request to log.
	 */
	protected function log_request( string $message, Request $request = null ) {
		$request_info = array(
			'type'    => $this->get_request_type_name( $request->type ),
			'url'     => $request->url,
			'params'  => $request->params,
			'headers' => $request->headers,
			'body'    => $request->body,
			'cookies' => $request->cookies,
		);

		$this->log(
			'info',
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			sprintf( '%s %s', $message, print_r( $request_info, true ) )
		);
	}

	/**
	 * Logs a response.
	 *
	 * @since [*next-version*]
	 *
	 * @param string   $message Prefix message to include in the log.
	 * @param Response $response The response to log.
	 */
	protected function log_response( $message, Response $response ) {
		$body = ( isset( $response->headers['Content-Type'] ) && ( 'application/pdf' === $response->headers['Content-Type'] ) )
			? '[PDF data]'
			: $response->body;

		$response_info = array(
			'status'  => $response->status,
			'headers' => $response->headers,
			'body'    => $body,
			'cookies' => $response->cookies,
		);

		if ( 500 <= $response->status ) {
			$level = 'critical';
		}
		if ( 400 <= $response->status ) {
			$level = 'error';
		}
		$level = 'info';

		$this->log(
			$level,
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			sprintf( '%s %s', $message, print_r( $response_info, true ) ),
		);
	}

	/**
	 * Retrieves the name for a request type.
	 *
	 * @since [*next-version*]
	 *
	 * @param int $type The request type. See the constants in the {@link Request} class.
	 *
	 * @return string|int The name of the request type, or the parameter if the request type is unknown.
	 */
	protected function get_request_type_name( $type ) {
		if ( Request::TYPE_GET === $type ) {
			return 'GET';
		}

		if ( Request::TYPE_POST === $type ) {
			return 'POST';
		}

		if ( Request::TYPE_PATCH === $type ) {
			return 'PATCH';
		}

		if ( Request::TYPE_DELETE === $type ) {
			return 'DELETE';
		}

		return $type;
	}

	/**
	 * Adds a log entry.
	 *
	 * @inheritDoc
	 */
	private function log( $level, $message ) {
		$this->logger->log( $level, $message, array( 'source' => 'faire' ) );
	}

}
