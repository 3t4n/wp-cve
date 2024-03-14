<?php

namespace TotalContestVendors\TotalCore\Contracts\Http;

use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;

/**
 * Interface Request
 * @package TotalContestVendors\TotalCore\Contracts\Http
 */
interface Request extends \ArrayAccess, Arrayable, \JsonSerializable {
	/**
	 * @return mixed
	 */
	public function uri();

	/**
	 * @return mixed
	 */
	public function method();

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function header( $name = null, $default = null );

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function server( $name = null, $default = null );

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function query( $name = null, $default = null );

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function post( $name = null, $default = null );

	/**
	 * @param null $name
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function cookie( $name = null, $default = null );

	/**
	 * @param string $name
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function file( $name, $default = null );

	/**
	 * @return mixed
	 */
	public function files();

	/**
	 * @param      $name
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function request( $name, $default = null );

	/**
	 * @return mixed
	 */
	public function ip();

	/**
	 * @return mixed
	 */
	public function userAgent();

}