<?php

namespace termspul;

class TPUL_DB_API {

	static $primary_key = 'tpul_log_id';

	public static function _log_table() {
		global $wpdb;
		// $tablename = str_replace( '\\', '_', strtolower( get_called_class() ) );
		$tablename = 'tpul_terms_user_log';
		return $wpdb->prefix . $tablename;
	}

	private static function _fetch_sql($value) {
		global $wpdb;
		$sql = sprintf('SELECT * FROM %s WHERE %s = %%s', self::_log_table(), static::$primary_key);
		return $wpdb->prepare($sql, $value);
	}

	static function valid_check($data) {
		global $wpdb;

		$sql_where       = '';
		$sql_where_count = count($data);
		$i               = 1;
		foreach ($data as $key => $row) {
			if ($i < $sql_where_count) {
				$sql_where .= "`$key` = '$row' and ";
			} else {
				$sql_where .= "`$key` = '$row'";
			}
			$i++;
		}
		$sql     = 'SELECT * FROM ' . self::_log_table() . " WHERE $sql_where";
		$results = $wpdb->get_results($sql);
		if (count($results) != 0) {
			return false;
		} else {
			return true;
		}
	}

	static function get($value) {
		global $wpdb;
		return $wpdb->get_row(self::_fetch_sql($value));
	}

	static function insert($data) {
		global $wpdb;
		$wpdb->insert(self::_log_table(), $data);
	}

	static function update($data, $where) {
		global $wpdb;
		$wpdb->update(self::_log_table(), $data, $where);
	}

	static function delete($value) {
		global $wpdb;
		$sql = sprintf('DELETE FROM %s WHERE %s = %%s', self::_log_table(), static::$primary_key);
		return $wpdb->query($wpdb->prepare($sql, $value));
	}

	static function fetch($value) {
		global $wpdb;
		$value = intval($value);
		$sql   = 'SELECT * FROM ' . self::_log_table() . " WHERE `the_user_id` = '$value' order by `created_at` DESC";
		return $wpdb->get_results($sql);
	}


	static function fetch_all() {
		global $wpdb;
		$sql   = 'SELECT * FROM ' . self::_log_table() . " order by `created_at` DESC";
		return $wpdb->get_results($sql);
	}

	static function count_all() {
		global $wpdb;
		$sql   = 'SELECT COUNT(*) FROM ' . self::_log_table() . " order by `created_at` DESC";
		$results = $wpdb->get_results($sql);

		$count = (array) $results[0];
		return $count['COUNT(*)'];
	}

	static function purge_older_than($date) {
		global $wpdb;
		// $sql   = 'SELECT COUNT(*) FROM ' . self::_log_table() . " order by `created_at` DESC";
		// $sql   = 'SELECT * FROM ' . self::_log_table() . " WHERE created_at < ' . $date . '";
		$sql   = 'DELETE FROM ' . self::_log_table() . " WHERE `created_at` < '" . $date . "'";
		// return $sql;
		return $wpdb->query($wpdb->prepare($sql, $date));
	}

	static function does_log_table_exist() {
		global $wpdb;
		$table_name = 'tpul_terms_user_log';
		$query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));

		if (!$wpdb->get_var($query) == $table_name) {
			return true;
		}
		return false;
	}

	static function create_log_table() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$version         = (int) get_site_option('tpul_log_db_version');

		if ($version < 1) {
			$sql = "CREATE TABLE `{$wpdb->base_prefix}tpul_terms_user_log` (
			tpul_log_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			the_user_id varchar(10),
			user_username varchar(255),
			user_displayname varchar(255),
			user_action varchar(255),
			PRIMARY KEY  (tpul_log_id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta($sql);
			$success = empty($wpdb->last_error);

			update_site_option('tpul_log_db_version', 1);
		}

		return $success;
	}
}

class Tpul_DB extends TPUL_DB_API {

	static $primary_key = 'tpul_log_id';
}
