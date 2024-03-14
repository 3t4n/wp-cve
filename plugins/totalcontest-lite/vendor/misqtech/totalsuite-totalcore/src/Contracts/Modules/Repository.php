<?php

namespace TotalContestVendors\TotalCore\Contracts\Modules;

/**
 * Interface RepositoryService
 * @package TotalContestVendors\TotalCore\Contracts\Modules
 */
interface Repository {
	/**
	 * Get all modules.
	 *
	 * @return mixed
	 */
	public function getAll();

	/**
	 * Get all installed modules.
	 *
	 * @return mixed
	 */
	public function getAllInstalled();

	/**
	 * Get all modules from store.
	 *
	 * @return mixed
	 */
	public function getAllStore();

	/**
	 * Get installed module by id.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function getInstalled( $moduleId );

	/**
	 * Get module from store by id.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function getFromStore( $moduleId );

	/**
	 * Get module by id.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function get( $moduleId );

	/**
	 * Get modules where conditions are met.
	 *
	 * @param $criteria
	 *
	 * @return mixed
	 */
	public function getWhere( $criteria );

	/**
	 * Get active modules.
	 *
	 * @return mixed
	 */
	public function getActive();

	/**
	 * Get active modules where conditions are met.
	 *
	 * @param $criteria
	 *
	 * @return mixed
	 */
	public function getActiveWhere( $criteria );

	/**
	 * Set module as active.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function setActive( $moduleId );

	/**
	 * Set module as inactive.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function setInactive( $moduleId );
}