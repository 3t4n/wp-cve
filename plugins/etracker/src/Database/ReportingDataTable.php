<?php
/**
 * The Reporting Data Table to store reporting data.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Database;

/**
 * ReportingDataTable to store reporting results.
 *
 * This table will be used to query data from while listing
 * pages and posts in backend.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class ReportingDataTable {
	/**
	 * Counter for # of stored figures.
	 *
	 * @var integer
	 */
	private $store_count = 0;

	/**
	 * Name of etracker reporting data table without wpdb->prefix.
	 *
	 * @var string
	 */
	private $table_name = 'etracker_reporting_data';

	/**
	 * Returns table name with $wpdb->prefix for reporting data.
	 *
	 * @return string
	 */
	public function get_table_name(): string {
		global $wpdb;

		$table_name = $wpdb->prefix . $this->table_name;

		return $table_name;
	}

	/**
	 * Activator method called by activation hook.
	 *
	 * It is used to create the table itself.
	 *
	 * @return void
	 */
	public function activate() {
		// require global wpdb object.
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $this->get_table_name();

		$sql = "CREATE TABLE {$table_name} (
			ID bigint(20) unsigned NOT NULL,
			time timestamp DEFAULT CURRENT_TIMESTAMP,
			start_date date NOT NULL,
			end_date date NOT NULL,
			unique_visits bigint(20) unsigned DEFAULT 0 NOT NULL,
			UNIQUE KEY ID (ID)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.dbDelta_dbdelta

		$this->flush_cache();
	}

	/**
	 * Drop table during uninstall process of plugin.
	 *
	 * @return void
	 */
	public function uninstall() {
		global $wpdb;

		$table_name = $this->get_table_name();

		//phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		//phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		//phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		//phpcs:disable WordPress.DB.DirectDatabaseQuery.SchemaChange
		$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
		//phpcs:enable
	}

	/**
	 * Converts any date time string into mysql compatible date.
	 *
	 * @see DateTimeImmutable::__construct
	 *
	 * @param string $date Date string to be converted.
	 *
	 * @return string Formatted date matching MySQL date syntax.
	 */
	private function convert_datetime_to_mysql_date( string $date ): string {
		$datetime = new \DateTime( $date );
		return $datetime->format( 'Y-m-d' );
	}

	/**
	 * Store UniqueVisits $count for Page|Post with $id.
	 *
	 * @param integer $id                WP_Page|WP_Post ID field value.
	 * @param integer $count             UniqueVisits.
	 * @param string  $report_start_date Start date of report.
	 * @param string  $report_end_date   End date of report.
	 *
	 * @return void
	 */
	public function store_unique_visits( int $id, int $count, string $report_start_date, string $report_end_date ) {
		global $wpdb;

		$table_name = $this->get_table_name();

		$start_date = $this->convert_datetime_to_mysql_date( $report_start_date );
		$end_date   = $this->convert_datetime_to_mysql_date( $report_end_date );

		//phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		//phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->get_var(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
				'REPLACE INTO `%1s` (ID, start_date, end_date, unique_visits) VALUES (%d, %s, %s, %d)',
				array(
					$table_name,
					$id,
					$start_date,
					$end_date,
					$count,
				)
			)
		);
		//phpcs:enable

		// New data arrived, delete cache key to display new data.
		$cache_key = 'etracker-unique_visits_' . $id;
		\wp_cache_delete( $cache_key );

		// Increment store count.
		$this->store_count++;
	}

	/**
	 * Query number of stored figures by this instance.
	 *
	 * @return integer Number of stored figures.
	 */
	public function get_store_count(): int {
		return $this->store_count;
	}

	/**
	 * Get cache_key to query if table is ready.
	 *
	 * @return string Cache key.
	 */
	private function get_cache_key(): string {
		return 'etracker-table-ready-' . $this->get_table_name();
	}

	/**
	 * Flush wp_cache entries for this object.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function flush_cache() {
		$cache_key = $this->get_cache_key();
		return wp_cache_delete( $cache_key );
	}

	/**
	 * Is table ready to be queried?
	 *
	 * @return boolean True if table exist or false if not.
	 */
	public function is_ready(): bool {
		global $wpdb;

		$cache_key      = $this->get_cache_key();
		$table_is_ready = wp_cache_get( $cache_key ); // false if error while retrieving key.

		if ( false !== $table_is_ready && true === boolval( $table_is_ready ) ) {
			// We got a cached result to allow joins.
			$table_is_ready = true;
		} else {
			// Verify table has been created before adding join.
			$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $this->get_table_name() ) );

			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
			if ( $wpdb->get_var( $query ) === $this->get_table_name() ) {
				// Table $table_name has been created, allow join and cache state.
				wp_cache_set( $cache_key, 'true', '', 60 );
				$table_is_ready = true;
			} else {
				// Table $table_name has NOT been created, skip join and cache state.
				wp_cache_set( $cache_key, 0, '', 60 );
				$table_is_ready = false;
			}
		}

		return $table_is_ready;
	}
}
