<?php
/**
 * Apocalypse Meow database.
 *
 * This class manages the database extensions.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow;

use blobfolio\wp\meow\vendor\common;

class db {
	const VERSION = '3.0.6';

	// History of sent messages.
	const SCHEMA_LOG = "CREATE TABLE %PREFIX%meow2_log (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  ip varchar(39) NOT NULL DEFAULT '',
  subnet varchar(42) NOT NULL DEFAULT '',
  date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_expires timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  type enum('alert','ban','fail','success') NOT NULL DEFAULT 'fail',
  username varchar(50) NOT NULL DEFAULT '',
  count smallint(5) unsigned NOT NULL DEFAULT '1',
  pardoned tinyint(1) unsigned NOT NULL DEFAULT '0',
  community tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  KEY ip (ip),
  KEY subnet (subnet),
  KEY date_created (date_created),
  KEY date_expires (date_expires),
  KEY type (type)
) %CHARSET%";



	/**
	 * Check if DB upgrade is needed.
	 *
	 * @since 21.0.0
	 *
	 * @return bool True/false.
	 */
	public static function check() {
		// Don't let willynilly traffic trigger this.
		if (
			! \is_admin() &&
			(! \defined('WP_CLI') || ! \WP_CLI)
		) {
			return false;
		}

		$installed = (string) \get_option('meow_db_version', '0');
		if (! \preg_match('/^\d+(\.\d+)*$/', $installed) || ! static::has_tables()) {
			$installed = 0;
		}

		if (\version_compare($installed, static::VERSION) < 0) {
			return static::upgrade($installed);
		}

		return true;
	}

	/**
	 * Has Tables?
	 *
	 * A MySQL error might prevent the necessary tables from being
	 * created.
	 *
	 * @return bool True/false.
	 */
	public static function has_tables() {
		global $wpdb;

		return ! \is_null($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}meow2_log'"));
	}

	/**
	 * Do Upgrade
	 *
	 * @since 21.0.0
	 *
	 * @param string $version Installed version.
	 * @return bool True/false.
	 */
	public static function upgrade($version=null) {
		global $wpdb;
		require_once \trailingslashit(\ABSPATH) . 'wp-admin/includes/upgrade.php';

		// WordPress might get called multiple times while this is
		// running. Let's go ahead and update the version string
		// early to mitigate parallel runs.
		\update_option('meow_db_version', static::VERSION);

		// Try to alter columns in one go so that way if MySQL rebuilds
		// itself, it only does so once. Much faster than dbDelta, and
		// if it fails, dbDelta will catch it anyway.
		if (\is_string($version) && $version && \version_compare($version, '3.0.1') < 0) {
			if (! \is_null($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}meow2_log'"))) {
				$wpdb->query("
					ALTER TABLE `{$wpdb->prefix}meow2_log`
						MODIFY COLUMN `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
						MODIFY COLUMN `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
						MODIFY COLUMN `date_expires` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
						MODIFY COLUMN `count` smallint(5) unsigned NOT NULL DEFAULT '1',
						MODIFY COLUMN `pardoned` tinyint(1) unsigned NOT NULL DEFAULT '0'
				");

				// Older versions of the plugin did not normalize login
				// keys. Now that WP allows logins via email, we should
				// update the records to avoid double-counting.
				$wpdb->query("
					UPDATE
						`{$wpdb->prefix}meow2_log` as m,
						`{$wpdb->prefix}users` as u
					SET
						m.username = LOWER(u.user_login)
					WHERE
						m.username = u.user_email
				");
			}
		}

		$replace = array(
			'%PREFIX%'=>$wpdb->prefix,
			'%CHARSET%'=>$wpdb->get_charset_collate(),
		);

		$table = \str_replace(\array_keys($replace), \array_values($replace), static::SCHEMA_LOG);
		\dbDelta($table);

		return static::migrate();
	}

	/**
	 * Do Migrate
	 *
	 * Older versions of the plugin used a different
	 * data structure. If someone managed to jump
	 * about 500 releases into the future, we want
	 * to do what we can to preserve their history.
	 *
	 * @since 21.0.0
	 *
	 * @return bool True.
	 */
	public static function migrate() {
		global $wpdb;

		// Map old log?
		if (! \is_null($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}meow_log'"))) {
			$wpdb->query("
				INSERT INTO `{$wpdb->prefix}meow2_log` (`ip`, `date_created`, `type`, `username`)
				(
					SELECT
						`ip`,
						FROM_UNIXTIME(`date`) AS `date_created`,
						IF(`success`,'success','fail'),
						`username`
					FROM `{$wpdb->prefix}meow_log`
					ORDER BY `date_created` ASC
				)
			");
			$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}meow_log`");
		}

		// Drop the old bans table.
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}meow_log_banned`");

		// Make sure we have subnets for everybody.
		$dbResult = $wpdb->get_results("
			SELECT DISTINCT `ip`
			FROM `{$wpdb->prefix}meow2_log`
			WHERE NOT(LENGTH(`subnet`))
			ORDER BY `ip` ASC
		", \ARRAY_A);
		if (\is_array($dbResult) && \count($dbResult)) {
			$updates = array();
			foreach ($dbResult as $Row) {
				$updates[\esc_sql($Row['ip'])] = \esc_sql(common\format::ip_to_subnet($Row['ip']));
			}

			// Update en masse, but in chunks.
			$updates = \array_chunk($updates, 100, true);
			foreach ($updates as $u) {
				$query = "UPDATE `{$wpdb->prefix}meow2_log` SET `subnet` = CASE `ip`";
				foreach ($u as $k=>$v) {
					$query .= "\nWHEN '$k' THEN '$v'";
				}
				$query .= "\nEND WHERE `ip` IN ('" . \implode("','", \array_keys($u)) . "')";

				$wpdb->query($query);
			}
		}

		// Remove old CRON triggers.
		if (false !== ($timestamp = \wp_next_scheduled('meow_clean_database'))) {
			\wp_unschedule_event($timestamp, 'meow_clean_database');
		}

		return true;
	}
}
