<?php
/**
 * Managing database operations for the plugin.
 *
 * @since 3.0.0
 * @package SWPTLS
 */

namespace SWPTLS;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages plugin database operations.
 *
 * @since 3.0.0
 */
class Database {

	/**
	 * Contains plugins database migrations.
	 *
	 * @var \SWPTLS\Database\Migration
	 */
	public $migration;

	/**
	 * Contains tables related database operations.
	 *
	 * @var \SWPTLS\Database\Table
	 */
	public $table;

	/**
	 * Class constructor.
	 *
	 * @since 3.0.0
	 */
	public function __construct() {
		$this->migration = new \SWPTLS\Database\Migration();
		$this->table     = new \SWPTLS\Database\Table();
	}
}
