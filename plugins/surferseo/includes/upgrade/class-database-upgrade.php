<?php
/**
 * Abstract class to define SQL upgrade methods
 *
 * When it will be hard to manage this, use: composer require illuminate/database
 *
 * @package SurferSEO
 */

namespace SurferSEO\Upgrade;

/**
 * Abstract class to define DB updates
 */
abstract class Database_Upgrade {

	/**
	 * Set of SQLs to execute.
	 *
	 * @var array
	 */
	protected $sql = array();

	/**
	 * Execute SQLs.
	 *
	 * @return void
	 */
	private function execute_sql() {

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		foreach ( $this->sql as $sql_to_execute ) {
			dbDelta( $sql_to_execute );
		}
	}

	/**
	 * Run version updage.
	 */
	public function execute() {
		$this->execute_sql();
	}
}
