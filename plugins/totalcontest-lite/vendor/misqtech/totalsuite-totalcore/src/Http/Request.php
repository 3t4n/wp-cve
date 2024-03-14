<?php

namespace TotalContestVendors\TotalCore\Http;

use TotalContestVendors\TotalCore\Contracts\Http\Request as RequestContract;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Class Request
 * @package TotalContestVendors\TotalCore\Http
 */
class Request implements RequestContract {
	/**
	 * @var string $uri
	 */
	protected $uri = '';
	/**
	 * @var string $method
	 */
	protected $method;
	/**
	 * @var array $query
	 */
	protected $query = [];
	/**
	 * @var array $post
	 */
	protected $post = [];
	/**
	 * @var array $files
	 */
	protected $files = [];
	/**
	 * @var array|Headers $headers
	 */
	protected $headers = [];
	/**
	 * @var array $cookies
	 */
	protected $cookies = [];
	/**
	 * @var array $server
	 */
	protected $server = [];
	/**
	 * @var array $old
	 */
	protected $old = [];
	/**
	 * @var mixed|string $ip
	 */
	protected $ip = '';
	/**
	 * @var mixed|string $userAgent
	 */
	protected $userAgent = '';

	/**
	 * Request constructor.
	 *
	 * @param string $uri
	 * @param string $method
	 * @param array  $query
	 * @param array  $post
	 * @param array  $files
	 * @param array  $cookies
	 * @param array  $server
	 * @param array  $headers
	 */
	public function __construct(
		$uri = null,
		$method = null,
		$query = null,
		$post = null,
		$files = null,
		$cookies = null,
		$server = null,
		$headers = null
	) {

		$method = strtoupper( (string) $method );

		if ( in_array( $method, [ 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' ] ) ):
			$this->method = $method;
		else:
			$this->method = filter_input( INPUT_SERVER, 'REQUEST_METHOD' ) ?: filter_var( $_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_STRING );
		endif;

		$this->uri     = $uri === null ? $_SERVER['REQUEST_URI'] : (string) $uri;
		$this->query   = $query === null ? $_GET : (array) $query;
		$this->post    = $post === null ? $_POST : (array) $post;
		$this->files   = $files === null ? $_FILES : (array) $files;
		$this->cookies = $cookies === null ? $_COOKIE : (array) $cookies;
		$this->server  = $server === null ? $_SERVER : (array) $server;
		$this->headers = new Headers( (array) $headers );

		foreach ( $this->server as $name => $value ) {
			if ( strstr( $name, 'HTTP_' ) ) {
				$this->headers[ str_replace( 'HTTP_', '', $name ) ] = $value;
			}
		}

		if ( isset( $this->server['HTTP_CLIENT_IP'] ) ):
			$this->ip = $this->server['HTTP_CLIENT_IP'];
		elseif ( isset( $this->server['HTTP_X_FORWARDED_FOR'] ) ):
			$this->ip = current( explode( ', ', $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		elseif ( isset( $this->server['HTTP_X_FORWARDED'] ) ):
			$this->ip = $this->server['HTTP_X_FORWARDED'];
		elseif ( isset( $this->server['HTTP_FORWARDED_FOR'] ) ):
			$this->ip = $this->server['HTTP_FORWARDED_FOR'];
		elseif ( isset( $this->server['HTTP_FORWARDED'] ) ):
			$this->ip = $this->server['HTTP_FORWARDED'];
		elseif ( isset( $this->server['REMOTE_ADDR'] ) ):
			$this->ip = $this->server['REMOTE_ADDR'];
		endif;

		$this->userAgent = isset( $this->server['HTTP_USER_AGENT'] ) ? $this->server['HTTP_USER_AGENT'] : 'Unknown';

	}

	/**
	 * @return string
	 */
	public function uri() {
		return $this->uri;
	}

	/**
	 * @return mixed|string
	 */
	public function method() {
		return $this->method;
	}

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return array|null|string|Headers
	 */
	public function header( $name = null, $default = null ) {

		if ( $name === null ):
			return $this->headers;
		endif;

		return isset( $this->headers[ $name ] ) ? stripslashes( $this->headers[ $name ] ) : $default;
	}

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return array|null|string
	 */
	public function server( $name = null, $default = null ) {
		if ( $name === null ):
			return $this->server;
		endif;

		return isset( $this->server[ $name ] ) ? stripslashes( $this->server[ $name ] ) : $default;
	}

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return array|mixed|null
	 */
	public function cookie( $name = null, $default = null ) {
		if ( $name === null ):
			return $this->cookies;
		endif;

		return isset( $this->cookies[ $name ] ) ? $this->cookies[ $name ] : $default;
	}

	/**
	 * @param string $name
	 * @param null   $default
	 *
	 * @return File|null
	 */
	public function file( $name, $default = null ) {
		if ( empty( $name ) ):
			return $default;
		endif;

		$file = isset( $this->files[ $name ] ) ? $this->files[ $name ] : false;

		if ( ! $file && strpos( $name, '.' ) !== false ):
			$needle = explode( '.', $name );
			$master = array_shift( $needle );

			if ( isset( $this->files[ $master ] ) ):
				$file = [
					'name'     => Arrays::getDeep( $this->files[ $master ]['name'], $needle, $default ),
					'type'     => Arrays::getDeep( $this->files[ $master ]['type'], $needle, $default ),
					'tmp_name' => Arrays::getDeep( $this->files[ $master ]['tmp_name'], $needle, $default ),
					'error'    => Arrays::getDeep( $this->files[ $master ]['error'], $needle, $default ),
					'size'     => Arrays::getDeep( $this->files[ $master ]['size'], $needle, $default ),
				];
			endif;

		endif;

		if ( $file && isset( $file['error'] ) && ! is_array( $file['error'] ) && $file['error'] === UPLOAD_ERR_OK ):
			return new File( $file['tmp_name'], $file['name'] );
		endif;

		return $default;
	}

	/**
	 * @return array
	 */
	public function files() {
		return $this->files;
	}

	/**
	 * @return mixed|string
	 */
	public function ip() {
		return $this->ip;
	}

	/**
	 * @return mixed|string
	 */
	public function userAgent() {
		return $this->userAgent;
	}

	/**
	 * @param $offset
	 *
	 * @return bool
	 */
    #[\ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return isset( $this->query[ $offset ] ) || isset( $this->post[ $offset ] );
	}

	/**
	 * @param $offset
	 *
	 * @return array|mixed|null
	 */
    #[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return $this->request( $offset );
	}

	/**
	 * @param      $name
	 * @param null $default
	 *
	 * @return array|mixed|null
	 */
	public function request( $name, $default = null ) {
		return $this->query( $name, $this->post( $name, $default ) );
	}

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return array|mixed|null
	 */
	public function query( $name = null, $default = null ) {
		if ( $name === null ):
			return $this->query;
		endif;

		if ( strpos( $name, '.' ) !== false ):
			return Arrays::getDotNotation( $this->query, $name, $default );
		endif;

		return isset( $this->query[ $name ] ) ? $this->query[ $name ] : $default;
	}

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return array|mixed|null
	 */
	public function post( $name = null, $default = null ) {
		if ( $name === null ):
			return $this->post;
		endif;

		if ( strpos( $name, '.' ) !== false ):
			return Arrays::getDotNotation( $this->post, $name, $default );
		endif;

		return isset( $this->post[ $name ] ) ? $this->post[ $name ] : $default;
	}

	/**
	 * @param $offset
	 * @param $value
	 */
    #[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		if ( $this->method === 'GET' ):
			$this->query[ $offset ] = $value;
		else:
			$this->post[ $offset ] = $value;
		endif;
	}

	/**
	 * @param $offset
	 */
    #[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		unset( $this->query[ $offset ], $this->post[ $offset ] );
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
    #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->toArray();
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array_merge( $this->post, $this->query, $this->files );
	}
}
