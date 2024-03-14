<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2023 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/COPYING
 *
 * This file is part of Football pool.
 *
 * Football pool is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * Football pool is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Football pool.
 * If not, see <https://www.gnu.org/licenses/>.
 */

/** @noinspection SqlResolve */

/**
 * Executes functions in the Football Pool plugin.
 */

WP_CLI::add_command( 'football-pool import', 'Football_Pool_CLI_Import_Match_Results' );

class Football_Pool_CLI_Import_Match_Results {
	/**
	 * Imports match results from a CSV file.
	 *
	 * ## OPTIONS
	 *
	 * --file=<filename>
	 * : Filename of the CSV to import. The CSV file must be uploaded to the folder "/wp-content/uploads/football-pool/imports". File should contains a row per match and 3 columns: match id,home score,away score (e.g. 1,2,0).
	 *
	 * [--dry-run]
	 * : Do not change anything in the database. Just show the matches that will be changed by the file.
	 *
	 * ## EXAMPLES
	 *
	 *     # Import the contents from the week1.csv file into the matches table.
	 *     $ wp football-pool import --file=week1.csv
	 *     Importing  100% [===============================================] 0:08 / 0:08
	 *     +----------+-----------+--------------+----------+----------+---------------+
	 *     | match id | home team | away team    | old data | new data | import result |
	 *     +----------+-----------+--------------+----------+----------+---------------+
	 *     | 1        | Russia    | Saudi Arabia | 1 - 0    | 0 - 1    | success       |
	 *     | 2        | Egypt     | Uruguay      | 0 - 2    | 0 - 2    | success       |
	 *     | 3        | Portugal  | Spain        | 1 - 1    | 2 - 2    | success       |
	 *     | 6        | Peru      | Denmark      | 5 - 2    | 5 - 2    | success       |
	 *     +----------+-----------+--------------+----------+----------+---------------+
	 *
	 *     # Do a test run with the file week1.csv.
	 *     $ wp football-pool import --file=week1.csv --dry-run
	 *     +----------+-----------+--------------+----------+----------+---------------+
	 *     | match id | home team | away team    | old data | new data | import result |
	 *     +----------+-----------+--------------+----------+----------+---------------+
	 *     | 1        | Russia    | Saudi Arabia | 1 - 0    | 0 - 1    | test run      |
	 *     | 2        | Egypt     | Uruguay      | 0 - 2    | 0 - 2    | test run      |
	 *     | 3        | Portugal  | Spain        | 1 - 1    | 2 - 2    | test run      |
	 *     | 6        | Peru      | Denmark      | 5 - 2    | 5 - 2    | test run      |
	 *     +----------+-----------+--------------+----------+----------+---------------+
	 */
	public function __invoke( $args, $assoc_args ) {
		$file = ( isset( $assoc_args['file'] ) ? $assoc_args['file'] : false );
		if ( isset( $assoc_args['test'] ) ) {
			$dry_run = ( $assoc_args['test'] === true );
		} else {
			$dry_run = ( isset( $assoc_args['dry-run'] ) && $assoc_args['dry-run'] === true );
		}

		$error = false;

		if ( $file === false ) {
			$error = 'No file specified.';
		} else {
			$file = FOOTBALLPOOL_IMPORT_DIR . $file;

			if ( ! file_exists( $file ) ) {
				$error = "File '{$file}' does not exist!";
			}
		}

		if ( $error !== false ) {
			WP_CLI::error( $error );
		}

		if ( $file !== false && ( $fp = @fopen( $file, 'r' ) ) !== false ) {
			$lines = [];
			$line = 0;
			while ( ( $data = fgetcsv( $fp, 0, FOOTBALLPOOL_CSV_DELIMITER ) ) !== false ) {
				$line++;
				// check the column count in the fetched line
				if ( count( $data ) !== 3 ) {
					WP_CLI::log( sprintf( 'Invalid column count on line %d.', $line ) );
				} else {
					// trim all values and store
					$lines[] = array_map( 'trim', $data );
				}
			}
			if ( isset( $fp ) ) @fclose( $fp );

			// process the lines from the csv
			if ( count( $lines ) > 0 ) {
				global $wpdb, $pool;
				$prefix = FOOTBALLPOOL_DB_PREFIX;

				if ( ! $dry_run ) $progress = \WP_CLI\Utils\make_progress_bar( 'Importing', count( $lines ) );

				$result_table = [];
				foreach ( $lines as $line ) {
					list( $match_id, $home_score, $away_score ) = $line;

					// get the match data
					$match = $pool->matches->get_match_info( (int) $match_id ); // important: get_match_info needs an integer

					if ( ! is_numeric( $home_score ) || ! is_numeric( $away_score ) ) {
						$home_score = $away_score = 'NULL';
						$sql = $wpdb->prepare( "UPDATE {$prefix}matches SET home_score = NULL, away_score = NULL
												WHERE id = %d", $match_id );
					} else {
						$sql = $wpdb->prepare( "UPDATE {$prefix}matches SET home_score = %d, away_score = %d
												WHERE id = %d", $home_score, $away_score, $match_id );
					}

					if ( isset( $match['id'] ) ) {
						if ( ! $dry_run ) {
							$import_result = ( $wpdb->query( $sql ) !== false ) ? 'success' : 'failed';
							$progress->tick();
						} else {
							$import_result = 'test run';
						}

						if ( ! is_numeric( $match['home_score'] ) && ! is_numeric( ['away_score'] ) ) {
							$match['home_score'] = $match['away_score'] = 'NULL';
						}
					} else {
						if ( ! $dry_run ) $progress->tick();
						$import_result = 'match not found';
						$match['home_team'] = $match['home_score'] = $match['away_team'] = $match['away_score'] = ' ';
					}

 					$result_table[] = array(
						'match id' => $match_id,
						'home team' => $match['home_team'],
						'away team' => $match['away_team'],
						'old data' => $match['home_score'] . ' - ' . $match['away_score'],
						'new data' => $home_score . ' - ' . $away_score,
						'import result' => $import_result
					);
//					sleep(2); // for debugging the progress bar
				}

				if ( ! $dry_run ) $progress->finish();

				// display results
				$headers = array(
					'match id',
					'home team',
					'away team',
					'old data',
					'new data',
					'import result'
				);

				WP_CLI\Utils\format_items( 'table', $result_table, $headers );

			} else {
				WP_CLI::error( "Nothing to process in file '{$file}'" );
			}
		} else {
			WP_CLI::error( "Failed to read file '{$file}'." );
		}
	}

}

