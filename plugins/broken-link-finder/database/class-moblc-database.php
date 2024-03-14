<?php
/**
 * This is database file.
 *
 * @package broken-link-finder/database
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'MOBLC_DATABASE' ) ) {
	/**
	 * This is database class.
	 */
	class MOBLC_DATABASE {
		/**
		 * Link details table name.
		 *
		 * @var string
		 */
		private $link_details_table;
		/**
		 * Scan status table name.
		 *
		 * @var string
		 */
		private $scan_status_table;
		/**
		 * This is constructor function.
		 */
		public function __construct() {
			global $wpdb;
			$this->link_details_table = $wpdb->prefix . 'moblc_link_details_table';
			$this->scan_status_table  = $wpdb->prefix . 'moblc_scan_status_table';
			$this->moblc_generate_tables();
		}
		/**
		 * This function for generating tables in database.
		 *
		 * @return void
		 */
		public function moblc_generate_tables() {
			global $wpdb;
			include_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'upgrade.php';
			$table_name = $this->link_details_table;
			if ( $wpdb->get_var( $wpdb->prepare( 'show tables like %s', array( $table_name ) ) ) !== $table_name ) { //phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery -- no caching is required here.
				$sql = 'CREATE TABLE ' //phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange -- no database schema is voilated
				. $table_name . ' ( 
				`id` bigint NOT NULL AUTO_INCREMENT,
				`link` mediumtext NOT NULL,
				`page_title` mediumtext NOT NULL ,
				`status_code` mediumtext NOT NULL ,
                PRIMARY KEY (id),
                UNIQUE KEY link (link(100),page_title(100)));';
				dbDelta( $sql );
			}

			$table_name = $this->scan_status_table;
			if ( $wpdb->get_var( $wpdb->prepare( 'show tables like %s', array( $table_name ) ) ) !== $table_name ) {//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,  WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here.
				$sql = 'CREATE TABLE ' //phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange -- no database schema is voilated
				. $table_name . ' (
				`option` VARCHAR(30) NOT NULL UNIQUE,
				`value` mediumtext NOT NULL);';
				dbDelta( $sql );
			}

		}
		/**
		 * This function is for fetching scan status from scan_status_table.
		 *
		 * @param mixed $option option.
		 * @return bool
		 */
		public function moblc_get_option( $option ) {
			global $wpdb;
			$result = $wpdb->get_results( $wpdb->prepare( "SELECT `value` FROM %1s WHERE `option` like '" . '%s' . "'", array( $this->scan_status_table, $option ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
			if ( count( $result ) > 0 ) {
				return $result[0]->value;
			} else {
				return false;
			}
		}
		/**
		 * Function for getting column names.
		 *
		 * @param string $table table.
		 * @return array
		 */
		public function moblc_get_column_names( $table ) {
			global $wpdb;
			$table = $wpdb->prefix . $table;

			return $wpdb->get_col( $wpdb->prepare( 'DESC %1s', array( $table ) ), 0 );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder,  WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
		}
		/**
		 * Function for getting table data
		 *
		 * @param string $table tablename.
		 * @return mixed
		 */
		public function moblc_get_table_data( $table ) {
			global $wpdb;
			$table = $wpdb->prefix . $table;

			return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %1s order by `id`', array( $table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- no caching is required here and for unquoted names %1s is required.
		}
		/**
		 * Function for getting post links.
		 *
		 * @return array;
		 */
		public function moblc_get_post_links() {
			global $wpdb;
			$table = $wpdb->prefix . 'posts';

			$result = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM %1s where `post_status`='publish' LIMIT 500", array( $table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- no caching is required here and for unquoted names %1s is required.

			$post_links = array();
			foreach ( $result as $post ) {
				array_push( $post_links, get_permalink( $post->ID ) );
			}

			return $post_links;
		}
		/**
		 * Updating if link is not broken.
		 *
		 * @param mixed $moblc_link_id linkid.
		 * @param mixed $status status.
		 * @return int|bool
		 */
		public function moblc_mark_not_broken( $moblc_link_id, $status ) {
			global $wpdb;
			$table = $this->link_details_table;

			$result = $wpdb->query( $wpdb->prepare( "UPDATE %1s SET status_code = 'Marked as Not Broken (%1s)' WHERE id = %s", array( $table, $status, $moblc_link_id ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- no caching is required here and for unquoted names %1s is required.
			return $result;
		}
		/**
		 * This function is for updating status link details table.
		 *
		 * @param string $moblc_link_id link id.
		 * @param string $status status.
		 * @return int|bool
		 */
		public function moblc_db_update_status( $moblc_link_id, $status ) {
			global $wpdb;
			$table  = $this->link_details_table;
			$table  = str_replace( "'", '`', $table );
			$result = $wpdb->query( //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
				$wpdb->prepare(
					'UPDATE %1s SET status_code = %s WHERE id = %d', //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- For unquoted names %1s is required.
					array(    //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- no caching is required here and For unquoted names %1s is required.
						$table,
						$status,
						$moblc_link_id,
					)
				)
			);

			return $result;
		}
		/**
		 * Function for removing link.
		 *
		 * @param mixed $moblc_link_id linkid.
		 * @return int|bool
		 */
		public function moblc_remove_link( $moblc_link_id ) {
			global $wpdb;
			$table  = $this->link_details_table;
			$result = $wpdb->query( $wpdb->prepare( 'DELETE from %1s where id=%s', array( $table, $moblc_link_id ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- no caching is required here and for unquoted names %1s is required.

			return $result;
		}
		/**
		 * Function for checking status of links in links details table.
		 *
		 * @param mixed $ids ids.
		 * @return array|object|null
		 */
		public function moblc_check_status( $ids ) {
			global $wpdb;
			$table = $this->link_details_table;
			if ( ! empty( $ids ) ) {
				$result = $wpdb->get_results( $wpdb->prepare( 'SELECT id,status_code FROM %1s where id IN (%1s);', array( $table, implode( ',', $ids ) ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- no caching is required here and for unquoted names %1s is required.

				return $result;
			}

		}
		/**
		 * Function for getting broken links from table.
		 *
		 * @return array|object|null
		 */
		public function moblc_get_broken_links() {
			global $wpdb;
			$table = $this->link_details_table;

			$show_300    = get_site_option( 'moblc_show_3xx' );
			$show_400    = get_site_option( 'moblc_show_4xx' );
			$show_500    = get_site_option( 'moblc_show_5xx' );
			$show_others = get_site_option( 'moblc_show_others' );

			$moblc_show_condition = '';

			$show_300 ? $moblc_show_condition    .= " OR `status_code` LIKE '3%'" : '';
			$show_400 ? $moblc_show_condition    .= " OR `status_code` LIKE '4%' AND `status_code` not LIKE '429'" : '';
			$show_500 ? $moblc_show_condition    .= " OR `status_code` LIKE '5%'" : '';
			$show_others ? $moblc_show_condition .= " OR ( `status_code` NOT LIKE '4%' AND `status_code` NOT LIKE '5%' AND `status_code` NOT LIKE '3%' AND `status_code` NOT LIKE '2%' )" : '';

			$result = $wpdb->get_results( $wpdb->prepare( 'SELECT id,link,page_title,status_code FROM %1s where ( false ' . $moblc_show_condition . " ) AND page_title!='BROKEN_PAGE';", array( $table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.PreparedSQL.NotPrepared -- no caching is required here and for unquoted names %1s is required. moblc_show_condition is pure string so no placeholder is required for it.

			return $result;
		}
		/**
		 * Function for getting link counts
		 *
		 * @param mixed $code code.
		 * @param mixed $others others.
		 * @return int
		 */
		public function moblc_get_link_count( $code, $others ) {
			global $wpdb;
			$table = $this->link_details_table;

			if ( $others ) {
				$result = $wpdb->get_results( $wpdb->prepare( "SELECT count( * ) as total from %1s where `status_code` NOT LIKE %s and `status_code` NOT LIKE %s and `status_code` NOT LIKE %s and page_title != 'BROKEN_PAGE';", array( $table, '3%', '4%', '5%' ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
			} else {
				$result = $wpdb->get_results( $wpdb->prepare( "SELECT count( * ) as total from %1s where `status_code` LIKE %s and page_title != 'BROKEN_PAGE';", array( $table, $code . '%' ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
			}

			if ( isset( $result[0]->total ) ) {
				return $result[0]->total;
			} else {
				return 0;
			}
		}
		/**
		 * Function for getting type of broken links.
		 *
		 * @return string
		 */
		public function moblc_broken_links_title() {
			if ( get_site_option( 'moblc_show_3xx' ) ) {
				return 'Redirected Links(3xx)';
			} elseif ( get_site_option( 'moblc_show_4xx' ) ) {
				return 'Broken Links';
			} elseif ( get_site_option( 'moblc_show_5xx' ) ) {
				return 'Server Error Links(5xx)';
			} elseif ( get_site_option( 'moblc_show_others' ) ) {
				return 'Other Broken Links';
			} else {
				return 'Broken Links';
			}
		}
		/**
		 * Function for getting the links where page is broken from database.
		 *
		 * @return array|object|null
		 */
		public function moblc_get_broken_pages() {
			global $wpdb;
			$table = $this->link_details_table;

			$result = $wpdb->get_results( $wpdb->prepare( "SELECT id,link,page_title,status_code FROM %1s where page_title = 'BROKEN_PAGE';", array( $table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder,  WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.

			return $result;
		}
		/**
		 * Function for removing broken pages from links table.
		 *
		 * @param mixed $moblc_page_id pade id.
		 * @return int|bool
		 */
		public function moblc_remove_broken_pages( $moblc_page_id ) {
			global $wpdb;
			$table = $this->link_details_table;

			$result = $wpdb->query( $wpdb->prepare( 'DELETE FROM %1s where id=%1s;', array( $table, $moblc_page_id ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- no caching is required here and for unquoted names %1s is required.

			return $result;
		}
		/**
		 * Function for getting no of scanned tables from database.
		 *
		 * @return mixed
		 */
		public function moblc_get_scanned_pages() {

			global $wpdb;
			$table = $this->link_details_table;

			$result = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(DISTINCT `page_title`) as scanned_pages FROM %1s ', array( $table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.

			$moblc_scanned_pages = isset( $result[0]->scanned_pages ) ? $result[0]->scanned_pages : 0;

			return $moblc_scanned_pages;
		}
		/**
		 * Function for getting data of broken links from links details table
		 *
		 * @return array|object|null;
		 */
		public function moblc_get_bad_responses() {

			global $wpdb;
			$table = $this->link_details_table;

			$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %1s WHERE `status_code` REGEXP '^([^0-9]*)$';", array( $table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- no caching is required here and for unquoted names %1s is required.

			return $result;
		}
		/**
		 * Fetching edit link from link Details table.
		 *
		 * @param string $page_title page title.
		 * @param string $search_link search link.
		 * @return array|object|null
		 */
		public function moblc_get_edit_link( $page_title, $search_link ) {

			global $wpdb;
			$table = $this->link_details_table;

			$result = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM  %1s WHERE  `link` = %s AND `page_title` = %s', array( $table, $search_link, $page_title ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- no caching is required here and for unquoted names %1s is required.

			return $result;
		}
		/**
		 * Function for inserting broken links in linkDetailsTable.
		 *
		 * @param mixed $links_data links data.
		 * @return null|void
		 */
		public function moblc_insert_broken_links( $links_data ) {
			if ( ! $links_data ) {
				return null;
			}
			global $wpdb;
			$table = $this->link_details_table;

			$insert_query = 'INSERT IGNORE INTO ' . $table . '(`link`,`page_title`,`status_code`) VALUES';

			$insert_flag = false;
			foreach ( $links_data as $links_obj ) {
				if ( isset( $links_obj['links'] ) && ! empty( $links_obj['links'] ) && $links_obj['links'] ) {
					$parent_link = $links_obj['links']['parentLink'];
					if ( 'ERROR_WHILE_GETTING_CONTENT' === $parent_link ) {
						$child_link = $links_obj['links']['childLinks'];
						$page_title = 'BROKEN_PAGE';
					} else {
						$child_link = $links_obj['links']['childLinks'];
						$page_title = get_the_title( url_to_postid( $parent_link ) );
					}
					foreach ( $child_link as $link ) {
						MOBLCUtility::moblc_debug_file( print_r( $link, true ) );//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- print_r is required here for enterning data into debug log.
						if ( isset( $link['link'] ) && ! empty( esc_url_raw( $link['link'] ) ) && strlen( $link['link'] ) > 1 ) {
							MOBLCUtility::moblc_debug_file( 'Link is not empty' );
							$insert_flag   = true;
							$insert_query .= $wpdb->prepare(
								' ( %s , %s , %s ),',
								array(
									$link['link'],
									$page_title,
									$link['response'],
								)
							);
						} else {
							MOBLCUtility::moblc_debug_file( 'Link is empty' );
						}
					}
				}
			}

			$insert_query  = substr( $insert_query, 0, - 1 );
			$insert_query .= ';';

			try {
				if ( $insert_flag ) {
					$result = $wpdb->query( $insert_query );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching  -- statement prepared above, Caching is not required here
				}
			} catch ( Exception $e ) {//phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch -- empty catch causes no harm	
			}
		}
		/**
		 * Function to get total pages on WordPress instance.
		 *
		 * @return bool
		 */
		public function moblc_count_of_pages() {
			global $wpdb;
			$pages = $wpdb->get_results( $wpdb->prepare( "select `ID` from %1sposts where `post_status` = 'publish'", array( $wpdb->prefix ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
			$n     = count( $pages );
			$this->moblc_update_option( 'moblc_total_pages', $n );
			if ( $n > 0 ) {
				return true;
			} else {
				return false;
			}
		}
		/**
		 * Function to update information in scanstatus table.
		 *
		 * @param mixed $option option.
		 * @param mixed $value value.
		 * @return void
		 */
		public function moblc_update_option( $option, $value ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO %1s( `option`, `value` ) VALUES( %s, %s ) ON DUPLICATE KEY UPDATE `value` = %s ', array( $this->scan_status_table, $option, $value, $value ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.

		}
		/**
		 * Function to check if column exists or not.
		 *
		 * @param mixed $table_type table type.
		 * @param mixed $column_name column name.
		 * @return int|bool
		 */
		public function moblc_check_if_column_exists( $table_type, $column_name ) {

			if ( 'scan_status_table' === $table_type ) {
				$table = $this->scan_status_table;
			} elseif ( 'link_details_table' === $table_type ) {
				$table = $this->link_details_table;
			}
			global $wpdb;
			$value = $wpdb->query( $wpdb->prepare( 'SHOW COLUMNS FROM %1s LIKE %s', array( $table, $column_name ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
			return $value;

		}
		/**
		 * Function to delete history by deleting linkdetails table data.
		 *
		 * @return void
		 */
		public function moblc_delete_history() {
			global $wpdb;
			$sql = "TRUNCATE $this->link_details_table";
			$wpdb->query( $wpdb->prepare( 'TRUNCATE %1s', array( $this->link_details_table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.

		}
		/**
		 * Function to delete entry from links detail table.
		 *
		 * @param mixed   $search_link search link.
		 * @param mixed   $page_title page title.
		 * @param boolean $is_all bool.
		 * @return void
		 */
		public function moblc_delete_entry( $search_link, $page_title, $is_all = true ) {
			global $wpdb;
			$table = $this->link_details_table;
			if ( $is_all ) {
				$wpdb->delete( $table, array( 'link' => $search_link ), array( '%s' ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here.
			} else {
				$wpdb->delete(//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here.
					$table,
					array(
						'link'       => $search_link,
						'page_title' => $page_title,
					),
					array( '%s', '%s' )
				);
			}
		}
		/**
		 * Function to delete scan status table data.
		 *
		 * @return void
		 */
		public function moblc_clear_status_table() {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'TRUNCATE %1s', array( $this->scan_status_table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.

		}
		/**
		 * Function to count broken links.
		 *
		 * @return string
		 */
		public function moblc_count_broken_links() {
			global $wpdb;
			return $wpdb->get_var( $wpdb->prepare( 'SELECT distinct COUNT( `link` ) from %1s WHERE `status_code` not like %s and `status_code` not like %s', array( $this->link_details_table, '3%', '429' ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
		}
		/**
		 * This function deleting column link_hash and loading_time
		 *
		 * @return void
		 */
		public function moblc_migration() {
			global $wpdb,$moblc_db_queries;
			$response = $moblc_db_queries->moblc_check_if_column_exists( 'link_details_table', 'link' );
			if ( ! $response ) {
				$sql = 'ALTER TABLE ' . $this->link_details_table . ' DROP COLUMN `link_hash`, DROP COLUMN `loading_time`';
				$res = $wpdb->query( $wpdb->prepare( 'ALTER TABLE %1s DROP COLUMN `link_hash`, DROP COLUMN `loading_time`', array( $this->link_details_table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching  -- no caching is required here and for unquoted names %1s is required.
			}
			if ( get_site_option( 'moblc_email' ) === null ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO %1soptions(`option_name`,`option_value`) SELECT `option`,`value` FROM  %1s ON DUPLICATE KEY UPDATE `option_value` = `value`', array( $wpdb->prefix, $this->scan_status_table ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.NoCaching -- no caching is required here and for unquoted names %1s is required.
			}

		}
	}
}
