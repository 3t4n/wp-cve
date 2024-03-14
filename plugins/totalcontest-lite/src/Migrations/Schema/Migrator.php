<?php

namespace TotalContest\Migrations\Schema;

use TotalContestVendors\TotalCore\Contracts\Foundation\Environment;
use TotalContestVendors\TotalCore\Helpers\Strings;
use wpdb;

/**
 * Schema Migrator.
 * @package TotalContest\Migrations\Schema
 */
class Migrator {
	/**
	 * @var Environment $env
	 */
	protected $env;
	/**
	 * @var wpdb $db
	 */
	protected $db;

	/**
	 * Migrator constructor.
	 *
	 * @param Environment $env
	 * @param wpdb        $db
	 */
	public function __construct( $env, $db ) {
		$this->env = $env;
		$this->db  = $db;
	}

	/**
	 * Migrate schema.
	 *
	 */
	public function migrate() {
		$this->migrate200();

		update_option( $this->env['db.option-key'], $this->env['db.version'] );
	}

	protected function migrate200() {
		$createLogTable   = file_get_contents( __DIR__ . '/migrations/2018_12_00_12_24_create_log_table.sql' );
		$createVotesTable = file_get_contents( __DIR__ . '/migrations/2018_12_00_12_26_create_votes_table.sql' );

		$createLogTable   = Strings::template( $createLogTable, [ 'db' => $this->env['db'] ] );
		$createVotesTable = Strings::template( $createVotesTable, [ 'db' => $this->env['db'] ] );

		dbDelta( $createLogTable );
		dbDelta( $createVotesTable );
	}
}