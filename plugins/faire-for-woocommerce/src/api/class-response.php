<?php

namespace Faire\Wc\Api;

use stdClass;

/**
 * A class that represents a REST API response.
 *
 * @since [*next-version*]
 */
class Response {
	/**
	 * The request that was sent.
	 *
	 * @since [*next-version*]
	 *
	 * @var Request
	 */
	public Request $request;
	/**
	 * The status code of the response.
	 *
	 * @since [*next-version*]
	 *
	 * @var int
	 */
	public int $status;
	/**
	 * The body of the response, as a raw string or parsed object.
	 *
	 * @since [*next-version*]
	 *
	 * @var string|stdClass
	 */
	public $body;
	/**
	 * The response headers.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public array $headers;
	/**
	 * The response cookies.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public array $cookies;

	/**
	 * Constructor.
	 *
	 * @param Request $request The request that was sent.
	 * @param int     $status  The status code of the response.
	 * @param string  $body    The body of the response.
	 * @param array   $headers The response headers.
	 * @param array   $cookies The response cookies.
	 *
	 * @since [*next-version*]
	 */
	public function __construct(
		Request $request,
		int $status,
		string $body = '',
		array $headers = array(),
		array $cookies = array()
	) {
		$this->request = $request;
		$this->status  = $status;
		$this->body    = $body;
		$this->headers = $headers;
		$this->cookies = $cookies;
	}

}
