<?php
/**
 * File contains 2fa database queries.
 *
 * @package miniorange-login-security/database
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Upgrade.php included.
 */
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
if ( ! class_exists( 'Momls_Db' ) ) {
	/**
	 * Class Momlsdb
	 */
	class Momls_Db {
		/**
		 * User details table variable.
		 *
		 * @var string
		 */
		private $user_details_table;
		/**
		 * User login information table variable.
		 *
		 * @var string
		 */
		private $user_login_info_table;

		/**
		 * Class Momlsdb constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->user_details_table    = $wpdb->prefix . 'mo2f_user_details';
			$this->user_login_info_table = $wpdb->prefix . 'mo2f_user_login_info';
		}

		/**
		 * Updates the database version in the options table.
		 *
		 * @return void
		 */
		public function momls_plugin_activate() {
			global $wpdb;
			if ( ! get_site_option( 'mo2f_dbversion' ) ) {
				update_site_option( 'mo2f_dbversion', Momls_Wpns_Constants::DB_VERSION );
				$this->momls_generate_tables();
			} else {
				$current_db_version = get_site_option( 'mo2f_dbversion' );
				if ( $current_db_version < Momls_Wpns_Constants::DB_VERSION ) {
					update_site_option( 'mo2f_dbversion', Momls_Wpns_Constants::DB_VERSION );
					$this->momls_generate_tables();
				}
				// update the tables based on DB_VERSION.
			}
		}

		/**
		 * Creates the tables and adds columns if not exist in the database.
		 *
		 * @return void
		 */
		public function momls_generate_tables() {
			global $wpdb;

			$table_name = $this->user_details_table;

			if ( $wpdb->get_var( $wpdb->prepare( 'show tables like %s', array( $table_name ) ) ) !== $table_name ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- DB Direct Query is necessary here.
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange -- Schema change is neccessary here.
				$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' ( 
				`user_id` bigint NOT NULL, 
				`mo2f_AuthyAuthenticator_config_status` tinyint, 
				`mo2f_SecurityQuestions_config_status` tinyint, 
				`mo2f_GoogleAuthenticator_config_status` tinyint, 
				`mobile_registration_status` tinyint, 
				`mo2f_2factor_enable_2fa_byusers` tinyint DEFAULT 1,
				`mo2f_configured_2FA_method` mediumtext NOT NULL , 
				`mo2f_user_phone` mediumtext NOT NULL , 
				`mo2f_user_email` mediumtext NOT NULL,  
				`user_registration_with_miniorange` mediumtext NOT NULL, 
				`mo_2factor_user_registration_status` mediumtext NOT NULL,
				UNIQUE KEY user_id (user_id) );';

				dbDelta( $sql );
			}

			$table_name = $this->user_login_info_table;

			if ( $wpdb->get_var( $wpdb->prepare( 'show tables like %s', array( $table_name ) ) ) !== $table_name ) { //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- DB Direct Query is necessary here.
                // phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange -- Schema change is neccessary here.
				$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (
			 `session_id` mediumtext NOT NULL, 
			 `mo2f_login_message` mediumtext NOT NULL , 
			 `mo2f_current_user_id` tinyint NOT NULL , 
			 `mo2f_1stfactor_status` mediumtext NOT NULL , 
			 `mo_2factor_login_status` mediumtext NOT NULL , 
			 `mo2f_transactionId` mediumtext NOT NULL , 
			 `mo_2_factor_kba_questions` longtext NOT NULL , 
			 `mo2f_rba_status` longtext NOT NULL , 
			 `ts_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`session_id`(100)));';

				dbDelta( $sql );
			}

			$check_if_column_exists = $this->check_if_column_exists( 'user_login_info_table', 'mo_2factor_login_status' );

			if ( ! $check_if_column_exists ) {
				$query = "ALTER TABLE `$table_name` ADD COLUMN IF NOT EXISTS `mo_2factor_login_status` mediumtext NOT NULL";
				$this->execute_add_column( $query );

			}

		}

		/**
		 * Adds user id in the user details table in the database.
		 *
		 * @param integer $user_id User ID corresponding which the details get added.
		 * @return void
		 */
		public function momls_insert_user( $user_id ) {
			global $wpdb;

			$wpdb->query( $wpdb->prepare( 'INSERT INTO %1s  (user_id) VALUES(%d) ON DUPLICATE KEY UPDATE user_id=%d;', array( $this->user_details_table, $user_id, $user_id ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- DB Direct Query is necessary here.
		}

		/**
		 * Fetch user details of given user id from database.
		 *
		 * @param string  $column_name Name of the column from which the details get fetched.
		 * @param integer $user_id Id of the users whose details need to be fetched.
		 * @return string
		 */
		public function momls_get_user_detail( $column_name, $user_id ) {
			global $wpdb;

			$user_column_detail = $wpdb->get_results( $wpdb->prepare( 'SELECT %1s FROM %1s WHERE user_id = %d;', array( $column_name, $this->user_details_table, $user_id ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- DB Direct Query is necessary here.
			$value              = empty( $user_column_detail ) ? '' : get_object_vars( $user_column_detail[0] );
			return empty( $value ) ? '' : $value[ $column_name ];
		}

		/**
		 * Checks if the given table exist in the database.
		 *
		 * @return bool
		 */
		public function momls_check_if_table_exists() {
			global $wpdb;

			$does_table_exist = $wpdb->query( $wpdb->prepare( 'SHOW TABLES LIKE %s;', array( $this->user_details_table ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- DB Direct Query is necessary here.
			return $does_table_exist;
		}
		/**
		 * Fetch the user details corresponding to given user id from user details table.
		 *
		 * @param integer $user_id User ID whose details need to be fetched.
		 * @return integer
		 */
		public function momls_check_if_user_column_exists( $user_id ) {
			global $wpdb;

			$value = $wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- DB Direct Query is necessary here.
				$wpdb->prepare( 'SELECT * FROM %1s WHERE user_id = %d;', array( $this->user_details_table, $user_id ) ) // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- We can not have table name in quotes.
			);
			return $value;
		}
		/**
		 * Check if given column exist in the given table.
		 *
		 * @param string $table_type Name of the table where the given column will be checked.
		 * @param string $column_name Name of the column which will be checked in the given table.
		 * @return bool
		 */
		public function check_if_column_exists( $table_type, $column_name ) {

			if ( 'user_login_info_table' === $table_type ) {
				$table = $this->user_login_info_table;
			}

			global $wpdb;
			$value = $wpdb->query( $wpdb->prepare( 'SHOW COLUMNS FROM %1s LIKE %s;', array( $table, $column_name ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- DB Direct Query is necessary here.

			return $value;

		}
		/**
		 * Updates user details for corresponding user id in user details table.
		 *
		 * @param integer $user_id User ID whose details need to be updated.
		 * @param array   $update The details which need to be updated for given user id.
		 * @return void
		 */
		public function update_user_details( $user_id, $update ) {
			global $wpdb;
			$count = count( $update );
			$sql   = 'UPDATE ' . $this->user_details_table . ' SET ';
			$i     = 1;
			foreach ( $update as $key => $value ) {

				$sql .= $key . "='" . $value . "'";
				if ( $i < $count ) {
					$sql .= ' , ';
				}
				$i ++;
			}
			$sql .= $wpdb->prepare( ' WHERE user_id= %s;', array( $user_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- DB Direct Query is necessary here.
			$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- DB Direct Query is necessary here.
		}
		/**
		 * Function to get number of 2fa users..
		 *
		 * @return int
		 */
		public function mo2f_get_no_of_2fa_users() {
			global $wpdb;
			$sql   = $wpdb->prepare( 'SELECT * FROM %s;', array( $this->user_details_table ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- DB Direct Query is necessary here.
			$count = $wpdb->query( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- DB Direct Query is necessary here.
			return $count;
		}
		/**
		 * Function to get all 2fa methods of the users.
		 *
		 * @return array
		 */
		public function mo2f_get_all_user_2fa_methods() {
			global $wpdb;
			$all_methods = array();
			$methods     = $wpdb->get_results( // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- DB Direct Query is necessary here.
				$wpdb->prepare( 'SELECT `mo2f_configured_2FA_method` FROM %s;', array( $this->user_details_table ) ),
				ARRAY_A
			);
			foreach ( $methods as $method ) {
				array_push( $all_methods, $method['mo2f_configured_2FA_method'] );
			}
			return implode( ',', $all_methods );
		}
		/**
		 * Inserts session Id in user login information table and delete the details if created time stamps is less than current added time.
		 *
		 * @param string $session_id The session id which need to be stored.
		 * @return void
		 */
		public function momls_insert_user_login_session( $session_id ) {
			global $wpdb;

			$wpdb->query( $wpdb->prepare( 'INSERT INTO %1s (session_id) VALUES(%d) ON DUPLICATE KEY UPDATE session_id= %s;', array( $this->user_login_info_table, $session_id, $session_id ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- DB Direct Query is necessary here.

			$wpdb->query( $wpdb->prepare( 'DELETE FROM %1s WHERE ts_created < DATE_ADD(NOW(),INTERVAL - 2 MINUTE);', array( $this->user_login_info_table ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- DB Direct Query is necessary here.
		}
		/**
		 * Inserts values in session_id and ts_created columns from user login information table.
		 *
		 * @param string $session_id Session Id corresponding which the details need to be saved.
		 * @param array  $user_values Array of column name and it's values.
		 * @return void
		 */
		public function save_user_login_details( $session_id, $user_values ) {
			global $wpdb;
			$count = count( $user_values );
			$sql   = 'UPDATE ' . $this->user_login_info_table . ' SET ';
			$i     = 1;
			foreach ( $user_values as $key => $value ) {
				if ( 'session_id' === $key || 'ts_created' === $key ) {
					$sql .= $wpdb->prepare( ' %1s=%d', array( $key, $value ) ); // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- We can not have table name in quotes.
				} else {
					$sql .= $wpdb->prepare( ' %1s=%s', array( $key, $value ) ); // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- We can not have table name in quotes.
				}
				if ( $i < $count ) {
					$sql .= ' , ';
				}
				$i ++;
			}

			$wpdb->query( $sql .= $wpdb->prepare( ' WHERE session_id=%s;', array( $session_id ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Ignoring complex placeholder warning and DB Direct Query is necessary here.
		}
		/**
		 * Executes the given query.
		 *
		 * @param string $query The query which needs to be executed.
		 * @return void
		 */
		public function execute_add_column( $query ) {
			global $wpdb;

			$wpdb->query( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Ignoring complex placeholder warning and DB Direct Query is necessary here.
		}
		/**
		 * Fetch details corresponding to given session ID from given column of user login information table.
		 *
		 * @param string $column_name Name of the column from which the details need to be fetched.
		 * @param string $session_id Session Id corresponding which the details need to be fetched.
		 * @return string
		 */
		public function get_user_login_details( $column_name, $session_id ) {
			global $wpdb;

			$user_column_detail = $wpdb->get_results( $wpdb->prepare( 'SELECT %1s FROM %1s WHERE session_id = %s;', array( $column_name, $this->user_login_info_table, $session_id ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- DB Direct Query is necessary here.
			$value              = empty( $user_column_detail ) ? '' : get_object_vars( $user_column_detail[0] );
			return empty( $value ) ? '' : $value[ $column_name ];
		}
		/**
		 * Delete details corresponding to given session id from user login information table.
		 *
		 * @param string $session_id Session id corresponding which the details need to be deleted.
		 * @return void
		 */
		public function delete_user_login_sessions( $session_id ) {
			global $wpdb;

			$wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- DB Direct Query is necessary here.
				$wpdb->prepare( 'DELETE FROM %1s  WHERE session_id=%s;', array( $this->user_login_info_table, $session_id ) ) // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- We can not have table name in quotes.
			);
		}



	}
}
