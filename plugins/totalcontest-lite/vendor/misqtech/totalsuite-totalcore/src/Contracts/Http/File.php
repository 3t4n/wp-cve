<?php

namespace TotalContestVendors\TotalCore\Contracts\Http;

use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;

/**
 * Interface File
 * @package TotalContestVendors\TotalCore\Contracts\Http
 */
interface File extends \Countable, Arrayable, \JsonSerializable {
	/**
	 * @return mixed
	 */
	public function getClientExtension();

	/**
	 * @return string
	 */
	public function getExtension();

	/**
	 * @return string
	 */
	public function getMimeType();

	/**
	 * @param $target
	 *
	 * @return bool|File
	 */
	public function move( $target );

	/**
	 * @return null
	 */
	public function getClientFilename();
}