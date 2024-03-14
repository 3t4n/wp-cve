<?php

namespace TotalContest\Contracts\Migrations\Contest\Template;

use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;

/**
 * Interface Template
 * @package TotalContest\Contracts\Migrations\Contest\Template
 */
interface Template extends \JsonSerializable, Arrayable, \ArrayAccess {
	/**
	 * @param $id
	 */
	public function setId( $id );

	/**
	 * @return mixed
	 */
	public function getId();

	/**
	 * @param $newId
	 */
	public function setNewId( $newId );

	/**
	 * @return mixed
	 */
	public function getNewId();
}
