<?php

namespace TotalContestVendors\TotalCore\Migrations;

use TotalContestVendors\TotalCore\Helpers\Strings;

/**
 * Database migration
 * @package TotalCore
 * @since   1.0.0
 */
class Database {
	private $lastMigrationFilename;
	private $sqlStatements = [];
	private $optionName;

	/**
	 * Database constructor.
	 */
	public function __construct() {
		$this->optionName = \TotalContestVendors\TotalCore\Application::getInstance()->env( 'prefix' ) . 'migration_last_filename';

		// Migrations files
		$migrations = glob( \TotalContestVendors\TotalCore\Application::getInstance()->env( 'db.migrations' ) . '/*_*_*_*_*.sql' );
		// Last file
		$this->lastMigrationFilename = (string) get_option( $this->optionName );
		if ( ! empty( $this->lastMigrationFilename ) ):
			// Because files are ordered by date, we have to unset old files until reaching last migration file
			foreach ( $migrations as $migration => $migrationPath ):
				unset( $migrations[ $migration ] );

				if ( basename( $migrationPath ) === $this->lastMigrationFilename ):
					break;
				endif;
			endforeach;
		endif;
		// Last migration
		$this->lastMigrationFilename = basename( end( $migrations ) ) ?: $this->lastMigrationFilename;
		// Get SQL statements from migration files
		$this->sqlStatements = array_map( 'file_get_contents', $migrations );
	}

	public function upgrade() {
		// Prepare and execute
		foreach ( $this->sqlStatements as $sqlStatement ):
			$sqlStatement = Strings::template( (string) $sqlStatement, [ 'db' => \TotalContestVendors\TotalCore\Application::getInstance()->env( 'db' ) ] );

			if ( ! empty( $sqlStatement ) ):
				\TotalContestVendors\TotalCore\Application::get( 'database' )->query( $sqlStatement );
			endif;
		endforeach;

		// Update
		update_option( $this->optionName, $this->lastMigrationFilename );
	}

}