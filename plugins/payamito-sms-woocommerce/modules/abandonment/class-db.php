<?php

namespace Payamito\Woocommerce\Modules\Abandoned;

use Payamito_DB;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'DB' ) ) {
	class DB
	{

		public static $version = 20231202;

		/**
		 *  Create tables
		 *
		 * @return void
		 * @since 1.1.0
		 */
		public static function create()
		{
			if ( ! function_exists( " dbDelta" ) ) {
				include_once ABSPATH . 'wp-admin/includes/upgrade.php';
			}
			if ( ! self::isExist( self::table_name( "cart_abandonment" ) ) ) {
				self::create_cart_abandonment_table();
			} else {
				$executed = get_option( 'payamito_wc_abandoned_db_version', false );
				if ( $executed === false ) {
					self::changeColumnType( self::table_name( "cart_abandonment" ), 'phone', 'varchar(100)' );
					update_option( 'payamito_wc_abandoned_db_version', self::$version );
				}
			}
			if ( ! self::isExist( self::table_name( "history" ) ) ) {
				self::create_history_table();
			}

			if ( self::isExist( Payamito_DB::table_name() ) ) {
				$executed = get_option( 'payamito_wc_core_db_version', false );
				if ( $executed === false ) {
					self::changeColumnType( Payamito_DB::table_name(), 'reciever', 'varchar(100)' );
					update_option( 'payamito_wc_core_db_version', self::$version );
				}

				return;
			}
		}

		/**
		 *  return table name
		 *
		 * @param string $name
		 *
		 * @return string
		 * @since 1.1.0
		 */
		public static function table_name( string $name )
		{
			global $wpdb;
			$tables = self::tables();
			if ( in_array( $name, $tables ) ) {
				$table_name = $wpdb->prefix . 'payamito_wc_' . $name;
			} else {
				$table_name = null;
			}

			return $table_name;
		}

		/**
		 *  all tables of plugin
		 *
		 * @return array
		 * @since 1.1.0
		 */
		private static function tables()
		{
			return array_unique( apply_filters( "payamito_wc_tables", [ 'cart_abandonment', 'history' ] ) );
		}

		/**
		 *  Create  abandonment table
		 *
		 * @return void
		 * @since 1.1.0
		 */
		public static function create_cart_abandonment_table()
		{
			$name = self::table_name( "cart_abandonment" );

			$sql = "CREATE TABLE {$name} (
				id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				checkout_id varchar(100) NOT NULL,
				user_id varchar(100),
				phone varchar(100),
				cart_contents LONGTEXT,
				cart_total DECIMAL(10,2),
				session_id varchar(100) NOT NULL,
				other_fields LONGTEXT,
				order_status ENUM('normal', 'abandoned', 'completed', 'lost') NOT NULL DEFAULT 'normal',
				unsubscribed boolean DEFAULT 0,
				time DATETIME DEFAULT NULL,
				PRIMARY KEY(`id`)
			)";

			dbDelta( $sql );
		}

		/**
		 *  Create history table
		 *
		 * @return void
		 * @since 1.1.0
		 */
		public static function create_history_table()
		{
			$cart_abandonment_history_db = self::table_name( "history" );

			$sql = "CREATE TABLE IF NOT EXISTS {$cart_abandonment_history_db} (
			 `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			 `template_id` varchar(100) NOT NULL,
			 `session_id` varchar(100),
			 `scheduled_time` DATETIME,
             `queue`VARCHAR(500),
             PRIMARY KEY(`id`)
		)";

			dbDelta( $sql );
		}

		public static function select( $table_name )
		{
			return Payamito_DB::select( $table_name, [], null, [ "*" ], null );
		}

		public static function isExist( $table_name ): bool
		{
			global $wpdb;

			return $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name;
		}

		public static function changeColumnType( $table_name, $column, $type )
		{
			global $wpdb;
			$sql = "ALTER TABLE $table_name MODIFY COLUMN $column $type";

			return $wpdb->query( $sql );
		}

	}
}
