<?php
/**
 * This is cron file.
 *
 * @package broken-link-finder/handler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'MOBLC_Cron' ) ) {
	/**
	 * This is cron class.
	 */
	class MOBLC_Cron {
		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'moblc_scan_cron_hook', array( $this, 'moblc_scan_cron_hook_exec' ) );
			add_filter( 'cron_schedules', array( $this, 'moblc_scan_cron_hook_intervals' ) );//phpcs:ignore WordPress.WP.CronInterval.CronSchedulesInterval -- Interval of 100 seconds is required here.
		}
		/**
		 * Function for cron based scanning.
		 *
		 * @return void
		 */
		public function moblc_scan_cron_hook_exec() {
			global $moblc_dir_path;
			require_once $moblc_dir_path . DIRECTORY_SEPARATOR . 'handler' . DIRECTORY_SEPARATOR . 'class-moblcutility.php';
			MOBLCUtility::moblc_debug_file( 'moblc_scan_cron_hook_exec' );
			global $moblc_db_queries;
			$scan_type = $moblc_db_queries->moblc_get_option( 'moblc_scan_message' );
			if ( ! $scan_type ) {
				$timestamp = wp_next_scheduled( 'moblc_scan_cron_hook' );
				wp_unschedule_event( $timestamp, 'moblc_scan_cron_hook' );
			}
			if ( 'page' === $scan_type ) {
				$pscan = $moblc_db_queries->moblc_get_option( 'moblc_page_scanning' ) - 1;
				$lscan = $moblc_db_queries->moblc_get_option( 'moblc_link_scanning' ) - 1;
				$this->moblc_check_links_from_pages( $pscan, $lscan );
			}
		}
		/**
		 * Function for storing links on pages.
		 *
		 * @param mixed $page_index page index.
		 * @param mixed $link_index link index.
		 * @return void
		 */
		public function moblc_check_links_from_pages( $page_index, $link_index ) {
			global $moblc_dir_path;
			require_once $moblc_dir_path . DIRECTORY_SEPARATOR . 'handler' . DIRECTORY_SEPARATOR . 'class-moblcutility.php';
			MOBLCUtility::moblc_debug_file( 'Checking links..' );
			global $moblc_db_queries, $wpdb;
			$link_details_table = $wpdb->prefix . 'moblc_link_details_table';
			$query              = $wpdb->prepare( 'INSERT INTO %1s (`link`,`page_title`,`status_code`) VALUES', array( $link_details_table ) );//phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- Complex place holder is required for unquoted string.
			$s_time             = time();
			$pages              = $wpdb->get_results( $wpdb->prepare( "select `ID` from %1sposts where `post_status`='publish'", array( $wpdb->prefix ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Caching is not required here and For unquoted string complex placeholder is required.
			$psize              = count( $pages );
			update_site_option( 'moblc_total_pages', $psize );
			$flag = false;
			for ( $page_index; $page_index < $psize; $page_index ++ ) {
				$moblc_db_queries->moblc_update_option( 'moblc_page_scanning', $page_index + 1 );
				$page_id = $pages[ $page_index ]->ID;
				$base    = get_permalink( $page_id );
				$content = $wpdb->get_results( $wpdb->prepare( 'select `post_content`,`post_title` from %1sposts where `id`=%1s', array( $wpdb->prefix, $page_id ) ) );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder, WordPress.DB.DirectDatabaseQuery.DirectQuery -- Caching is not required here and For unquoted string complex placeholder is required.
				$title   = $content[0]->post_title;
				$content = $content[0]->post_content;
				if ( strlen( $content ) > get_site_option( 'moblc_max_length' ) ) {
					update_site_option( 'moblc_max_length', strlen( $content ) );
				}
				$links      = preg_split( '/<a/', $content );
				$lsize      = count( $links );
				$hash_links = array();
				MOBLCUtility::moblc_debug_file( ' Page: [ name: "' . $title . '" (ID: ' . $page_id . ') ]' );
				$links = preg_split( '/<a | <link/', $content );
				MOBLCUtility::moblc_get_links( 'href', $links, $hash_links );

				$links = preg_split( '/<img | <iframe/', $content );
				MOBLCUtility::moblc_get_links( 'src', $links, $hash_links );
				MOBLCUtility::moblc_debug_file( 'size of hash links' );
				MOBLCUtility::moblc_debug_file( strval( count( $hash_links ) ) );
				if ( count( $hash_links ) ) {
					MOBLCUtility::moblc_debug_file( '   Hashing link : ' . print_r( $hash_links, true ) );//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- For entering data in debug log file.
					$hashs = array_keys( $hash_links );
					$lsize = count( $hash_links );
					$moblc_db_queries->moblc_update_option( 'moblc_total_links', $lsize );
					for ( $link_index; $link_index < $lsize; $link_index ++ ) {
						$moblc_db_queries->moblc_update_option( 'moblc_link_scanning', $link_index + 1 );
						if ( $this->moblc_check_time( $s_time ) ) {
							$moblc_db_queries->moblc_update_option( 'moblc_page_scanning', $page_index + 1 );
							$query  = substr( $query, 0, - 1 );
							$query .= ' ON DUPLICATE KEY UPDATE `status_code`= VALUES(status_code);';
							if ( $flag ) {
								$wpdb->query( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- query is prepared above and no caching is required here.
							}
							exit();
						}

						$link_hash = $hashs[ $link_index ];
						$link      = $hash_links[ $link_hash ];
						MOBLCUtility::moblc_debug_file( '   Hash table created:' );
						if ( ! empty( $link ) && filter_var( $link, FILTER_VALIDATE_URL ) ) {
							$link  = trim( moblc_relative_to_absolute( $link, $base ) );
							$stime = time();

							if ( strpos( $link, '://youtube' ) === true || strpos( $link, '://www.youtube' ) === true ) {
								$body = wp_remote_retrieve_body( wp_remote_post( $link ) );
								MOBLCUtility::moblc_debug_file( '  scanning link  [tag:youtube]' );
								if ( strpos( $body, 'Video unavailable' ) === false ) {
									$response = wp_remote_retrieve_response_code( wp_remote_head( $link ) );
								} else {
									$status = 404;
								}
							} else {
								$response = wp_remote_retrieve_response_code( wp_remote_head( $link ) );
								$status   = isset( $response ) ? $response : 'invalid link';

							}

							$ltime = time();
							$time  = ( $ltime - $stime ) . 's';
							if ( $status > 300 ) {
								$flag   = true;
								$query .= $wpdb->prepare( ' ( %s , %s , %s ),', array( $link, $title, $status ) );
							}
						} elseif ( ( $lsize - 1 ) === $link_index && $page_index === $psize ) {
							$moblc_db_queries->moblc_update_option( 'moblc_link_scanning', $link_index + 2 );
						}
					}
				}
				$link_index = 0;
			}
			MOBLCUtility::moblc_debug_file( 'Page Scanned ! ' );
			$query  = substr( $query, 0, - 1 );
			$query .= ' ON DUPLICATE KEY UPDATE `status_code`= VALUES(status_code);';
			if ( $flag ) {
				$wpdb->query( $query );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- No caching is required here, query is prepared above.
				MOBLCUtility::moblc_debug_file( 'Scan table updated!' );
			}
			$timestamp = wp_next_scheduled( 'moblc_scan_cron_hook' );
			wp_unschedule_event( $timestamp, 'moblc_scan_cron_hook' );
		}
		/**
		 * Function for keeping track of execution time.
		 *
		 * @param mixed $s_time scanning time.
		 * @return bool
		 */
		public function moblc_check_time( $s_time ) {
			$max_time = ini_get( 'max_execution_time' );
			if ( ( $max_time - ( time() - $s_time ) ) <= 10 ) {
				return true;
			}

			return false;
		}
		/**
		 * Function for returning scheduled intervals.
		 *
		 * @param mixed $schedules schedule.
		 * @return array
		 */
		public function moblc_scan_cron_hook_intervals( $schedules ) {
			$schedules['max_time'] = array(
				'interval' => 100,
				'display'  => __( 'Once 100 Seconds' ),
			);
			$schedules['weekly']   = array(
				'interval' => 604800,
				'display'  => __( 'Once Weekly' ),
			);
			$schedules['monthly']  = array(
				'interval' => 2635200,
				'display'  => __( 'Once a month' ),
			);

			return $schedules;
		}
	}
	new MOBLC_Cron();
}

