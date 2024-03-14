<?php

namespace TotalContestVendors\TotalCore\Contracts\Modules;

/**
 * Interface Manager
 * @package TotalContestVendors\TotalCore\Contracts\Modules
 */
interface Manager {
	/**
	 * Fetch modules.
	 *
	 * @return mixed
	 */
	public function fetch();

	/**
	 * Update module.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function update( $moduleId );

	/**
	 * Install from store.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function installFromStore( $moduleId );

	/**
	 * install from file (.zip)
	 *
	 * @param $moduleZip
	 *
	 * @return mixed
	 */
	public function install( $moduleZip );

	/**
	 * Uninstall.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function uninstall( $moduleId );

	/**
	 * Active module.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function activate( $moduleId );

	/**
	 * Deactivate module.
	 *
	 * @param $moduleId
	 *
	 * @return mixed
	 */
	public function deactivate( $moduleId );
}