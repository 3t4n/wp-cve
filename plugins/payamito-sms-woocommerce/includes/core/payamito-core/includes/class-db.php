<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Payamito_DB::class ) ) {
	class Payamito_DB
	{
		public static $version = 2024122;

		public static function insert_sms( $receiver, $method, $slug, $status, $message )
		{
			global $wpdb;
			$format     = [ '%s', '%d' ];
			$table_name = self::table_name();

			$data = [
				'reciever' => $receiver,
				'method'   => $method,
				'slug'     => $slug,
				'status'   => $status,
				'message'  => $message,
				'date'     => current_time( "mysql" ),
			];
			$wpdb->insert( $table_name, $data, $format );
		}

		public static function table_name()
		{
			global $wpdb;

			return $wpdb->prefix . 'payamito' . '_sms';
		}

		public static function create_tabls()
		{
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$table_name = self::table_name();

			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name ) {
				return;
			}

			$sql = "CREATE TABLE IF NOT EXISTS  `{$table_name}` (
              `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              `reciever` varchar(100)  NOT NULL ,
              `method`   tinyint(1)  NOT NULL ,
              `slug`     varchar(255) NOT NULL DEFAULT 0,
              `status`   tinyint(1) NOT NULL DEFAULT 0,
              `message`  varchar(500) NOT NULL DEFAULT 0,
              `date` timestamp ,
              PRIMARY KEY (`id`)
            ) DEFAULT CHARSET=utf8";
			dbDelta( $sql );
		}

		public static function select( $table_name, $wheres = [], $limit = null, $columns = [ "*" ], $order = null )
		{
			global $wpdb;
			$sql = "";

			foreach ( $columns as $column ) {
				$sql .= sprintf( "%s %s ", $column, "," );
			}
			$len = ( strlen( $sql ) );

			if ( $sql[ ( $len ) - 2 ] == "," ) {
				$sql = substr_replace( $sql, "", ( $len - 2 ) );
			}

			$sql = "SELECT " . $sql;
			$sql .= "FROM `{$table_name}` ";

			if ( count( $wheres ) == 0 ) {
				$sql;
			} else {
				$flg  = false;
				$last = end( $wheres );
				foreach ( $wheres as $index => $where ) {
					if ( is_null( $where ) ) {
						continue;
					}
					if ( $flg === false ) {
						$sql .= "where ";
					}
					$flg   = true;
					$index = esc_sql( $index );
					$where = esc_sql( $where );
					$sql   .= $wpdb->prepare( "$index=%s ", $where );
					if ( count( $wheres ) > 1 && $last !== $where && $where != null ) {
						$sql .= "AND ";
					}
				}
			}
			$len = ( strlen( $sql ) );

			if ( $sql[ ( $len ) - 2 ] == "D" ) {
				$sql = substr_replace( $sql, "", ( $len - 4 ) );
			}
			if ( ! is_null( $limit ) ) {
				$sql .= " LIMIT $limit";
			}

			if ( is_array( $order ) ) {
				$sql .= " ORDER BY " . $order['column'] . " " . $order['order'];
			}
			$sql = $wpdb->prepare( $sql );

			return $wpdb->get_results( $sql, ARRAY_A );
		}

		public static function delete( $table_name, $wheres = [] )
		{
			global $wpdb;
			$sql = "";
			if ( count( $wheres ) == 0 ) {
				$sql .= "DELETE FROM {$table_name}";
			} else {
				$sql  .= "DELETE FROM {$table_name}";
				$flg  = false;
				$last = end( $wheres );
				foreach ( $wheres as $where ) {
					$key = key( $where );

					if ( is_null( $where ) ) {
						continue;
					}
					if ( $flg === false ) {
						$sql .= " where ";
					}
					$flg   = true;
					$key   = esc_sql( $key );
					$where = esc_sql( $where );
					$sql   .= $wpdb->prepare( "`$key`=%s ", $where );
					if ( count( $wheres ) > 1 && $last !== $where && $where != null ) {
						$sql .= " OR ";
					}
					$len = strlen( $sql );
					if ( $len > 1000 ) {
						self::run_delete( $sql );
						$flg = false;
						$sql = "DELETE FROM {$table_name}";
					}
				}

				self::run_delete( $sql );
			}
		}

		private static function run_delete( $sql )
		{
			global $wpdb;
			$len = ( strlen( $sql ) );

			if ( $sql[ ( $len - 2 ) ] == "R" ) {
				$sql = substr_replace( $sql, "", ( $len - 3 ) );
			}
			$sql;
			if ( false === $wpdb->query( $wpdb->prepare( $sql ) ) ) {
				return false;
			}
		}

		public static function updateTable2024122(): void
		{
			global $wpdb;

			$table_name = self::table_name();

			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name ) {
				$column_info = $wpdb->get_row( "SHOW COLUMNS FROM $table_name LIKE 'reciever'" );
				if ( $column_info && $column_info->Type !== 'varchar(100)' ) {
					$wpdb->query( "ALTER TABLE $table_name MODIFY reciever VARCHAR(100)" );
				}
			}
		}
	}
}
