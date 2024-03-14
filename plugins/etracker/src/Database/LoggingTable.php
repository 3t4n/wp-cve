<?php
/**
 * The Logging Table to store plugin logging data.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Database;

/**
 * Logging Table to store plugin logging data.
 *
 * This table will be used to store and query last x log messages
 * from this plugin.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class LoggingTable {
	/**
	 * Name of etracker logging table without wpdb->prefix.
	 *
	 * @var string
	 */
	private $table_name = 'etracker_logging';

	/**
	 * Returns table name with $wpdb->prefix.
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

		$sql = "CREATE TABLE $table_name (
			ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			time timestamp DEFAULT CURRENT_TIMESTAMP,
			priority tinyint unsigned NOT NULL,
			message text,
			UNIQUE KEY ID (ID),
			INDEX priority (priority)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.dbDelta_dbdelta
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
	 * Store logging message.
	 *
	 * @param integer $priority Syslog compatible priority of the message.
	 * @param string  $message  Message to store.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function store_message( int $priority, string $message ) {
		global $wpdb;

		$table_name = $this->get_table_name();

		//phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		//phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		return $wpdb->query(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
				'INSERT INTO `%1s` (priority, message) VALUES (%d, %s)',
				array(
					$table_name,
					$priority,
					$message,
				)
			)
		);
		//phpcs:enable
	}

	/**
	 * Delete all stored log message older than x days.
	 *
	 * @param integer $maxage_in_days Maxage of messages in days.
	 *
	 * @return int|bool Number of rows affected. Boolean false on error.
	 */
	public function delete_messages_older_x_days( int $maxage_in_days = 10 ) {
		global $wpdb;

		$table_name = $this->get_table_name();

		//phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
		//phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
		return $wpdb->query(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
				'DELETE FROM `%1s` WHERE time < DATE_SUB(NOW(), INTERVAL %1d DAY)',
				array(
					$table_name,
					$maxage_in_days,
				)
			)
		);
		//phpcs:enable
	}
}
