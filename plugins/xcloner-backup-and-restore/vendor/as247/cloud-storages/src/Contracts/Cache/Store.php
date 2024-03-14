<?php


namespace As247\CloudStorages\Contracts\Cache;


interface Store
{
	/**
	 * Put value to cache, update if existing
	 * @param $path
	 * @param $data
	 * @param int $seconds
	 * @return mixed
	 */
	public function put($path, $data, $seconds=3600);

	/**
	 * Never expire cache
	 * @param $path
	 * @param $value
	 * @return mixed
	 */
	public function forever($path, $value);

	/**
	 * Get or return default
	 * @param $path
	 * @return mixed
	 */
	public function get($path);

	/**
	 * For get a path
	 * @param $path
	 * @return mixed
	 */
	public function forget($path);

	/**
	 * Flush cache
	 * @return mixed
	 */
	public function flush();


}
