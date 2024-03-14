<?php


namespace As247\CloudStorages\Contracts\Cache;


interface PathStore extends Store
{
	/**
	 * Forget path and its parent
	 * @param $path
	 * @return mixed
	 */
	public function forgetBranch($path);
	/**
	 * Set false value for $path
	 * @param $path
	 * @return mixed
	 */
	public function delete($path);

	/**
	 * Delete path and all its parents
	 * @param $path
	 * @return mixed
	 */
	public function deleteBranch($path);

	/**
	 * Forget a path and all its children
	 * eg if forget /a then /a/b /a/b/c also removed
	 * @param $path
	 * @return mixed
	 */
	public function forgetDir($path);

	/**
	 * Set false value for $path and all existing children
	 * Eg: If deleteDir('/a') is called and '/a/b','/a/c/e.txt' exists in cache
	 * 			Then all of them  set to false
	 * @param $path
	 * @return mixed
	 */
	public function deleteDir($path);

	/**
	 * Simulate rename function, we need to move all value from $source tree to $destination
	 *
	 * @param $source
	 * @param $destination
	 * @return mixed
	 */
	public function move($source, $destination);

	/**
	 * Query for matching path
	 * @param $path
	 * @param string|int $match * content in current directory ** include subdirectory
	 * @return mixed
	 */
	public function query($path, $match = '*');

	/**
	 * Mark the path is completed that mean nothing under this path is outside cache, used for listing
	 * @param $path
	 * @param bool $isCompleted
	 * @return mixed
	 */
	public function complete($path, $isCompleted = true);

	/**
	 * Check if current path is completed
	 * @param $path
	 * @return mixed
	 */
	public function isCompleted($path);

	public function getCompleted($path);
}
