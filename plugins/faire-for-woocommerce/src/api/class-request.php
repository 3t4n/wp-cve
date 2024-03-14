<?php

namespace Faire\Wc\Api;

/**
 * A class that represents a REST API request.
 *
 * @since [*next-version*]
 */
class Request {
	/**
	 * Constant for GET requests.
	 *
	 * @since [*next-version*]
	 *
	 * @var int
	 */
	const TYPE_GET = 0;

	/**
	 * Constant for POST requests.
	 *
	 * @since [*next-version*]
	 *
	 * @var int
	 */
	const TYPE_POST = 1;

	/**
	 * Constant for PATCH requests.
	 *
	 * @since [*next-version*]
	 *
	 * @var int
	 */
	const TYPE_PATCH = 2;

	/**
	 * Constant for DELETE requests.
	 *
	 * @since [*next-version*]
	 *
	 * @var int
	 */
	const TYPE_DELETE = 3;

	/**
	 * Constant for PUT requests.
	 *
	 * @since [*next-version*]
	 *
	 * @var int
	 */
	const TYPE_PUT = 4;

	/**
	 * The type of the request.
	 *
	 * @since [*next-version*]
	 *
	 * @see Request::TYPE_GET
	 * @see Request::TYPE_POST
	 * @see Request::TYPE_PATCH
	 * @see Request::TYPE_DELETE
	 *
	 * @var int
	 */
	public int $type;
	/**
	 * The URL where the request will be made.
	 *
	 * @since [*next-version*]
	 *
	 * @var string
	 */
	public string $url;
	/**
	 * The GET params for this request.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public array $params;
	/**
	 * The body of the request.
	 *
	 * @since [*next-version*]
	 *
	 * @var mixed
	 */
	public $body;
	/**
	 * The request headers.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public array $headers;
	/**
	 * The request headers.
	 *
	 * @since [*next-version*]
	 *
	 * @var array
	 */
	public array $cookies;

	/**
	 * Constructor.
	 *
	 * @param int    $type    The request type.
	 * @param string $url     The base URL where the request will be made.
	 * @param array  $params  The GET params for this request.
	 * @param mixed  $body    The body of the request.
	 * @param array  $headers The request headers to send.
	 * @param array  $cookies The request cookies to send.
	 *
	 * @since [*next-version*]
	 */
	public function __construct(
		int $type,
		string $url,
		array $params = array(),
		$body = null,
		array $headers = array(),
		array $cookies = array()
	) {
		$this->type    = $type;
		$this->url     = $url;
		$this->params  = $params;
		$this->body    = $body;
		$this->headers = $headers;
		$this->cookies = $cookies;
	}

}
