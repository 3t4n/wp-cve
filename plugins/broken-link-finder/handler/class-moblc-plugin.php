<?php
/**
 * This is plugin file.
 *
 * @package broken-link-finder/handler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Moblc_Plugin' ) ) {
	/**
	 * This is plugin class.
	 */
	class Moblc_Plugin {

		/**
		 * This function is to start scan.
		 *
		 * @return void
		 */
		public function moblc_start_scan() {
			MOBLCUtility::moblc_debug_file( 'in plugin start scan' );
			if ( ! wp_next_scheduled( 'moblc_scan_cron_hook' ) ) {
				update_option( 'moblc_cron', 0 );
				update_site_option( 'moblc_width_time', time() );
				wp_schedule_event( time(), 'max_time', 'moblc_scan_cron_hook' );
			}
		}
		/**
		 * This functon for checking continous scan.
		 *
		 * @return void|array
		 */
		public function moblc_continuous_scan() {
			global $moblc_db_queries;
			$response               = $this->moblc_get_scan_data( 'page' );
			$last_link_scanned      = get_site_option( 'moblc_last_link_scanned', 1 );
			$last_page_scanned      = get_site_option( 'moblc_last_page_scanned', 1 );
			$response['moblc_diff'] = ( time() - get_site_option( 'moblc_width_time' ) ) / 60;
			if ( $response['moblc_diff'] > 5 && $response['pscan'] === $last_page_scanned && $response['lscan'] === $last_link_scanned ) {
				update_site_option( 'moblc_last_page_scanned', $response['pscan'] );
				update_site_option( 'moblc_last_link_scanned', $response['lscan'] );
				update_site_option( 'moblc_width_time', time() );
			}
			if ( $response['pscan'] === $moblc_db_queries->moblc_get_option( 'moblc_total_pages' ) && $response['lscan'] >= $moblc_db_queries->moblc_get_option( 'moblc_total_links' ) ) {
				update_site_option( 'moblc_is_scanning', false );
				wp_send_json( 'SCAN_COMPLETED' );
			}
			MOBLCUtility::moblc_debug_file( 'in plugin moblc_continuous_scan' );

			return $response;
		}
		/**
		 * This function is to get scan data.
		 *
		 * @param mixed $scan_type scan type.
		 * @return void
		 */
		public function moblc_get_scan_data( $scan_type ) {
			MOBLCUtility::moblc_debug_file( 'in plugin moblc_get_scan_data' );
			if ( ! $scan_type ) {
				return;
			}
			global $moblc_db_queries;
			$response['ptotal'] = $moblc_db_queries->moblc_get_option( 'moblc_total_pages' );
			$response['pscan']  = $moblc_db_queries->moblc_get_option( 'moblc_page_scanning' );
			$response['ltotal'] = $moblc_db_queries->moblc_get_option( 'moblc_total_links' );
			$response['lscan']  = $moblc_db_queries->moblc_get_option( 'moblc_link_scanning' );

			return $response;
		}
		/**
		 * Function to call another event when scan is complete
		 *
		 * @return void
		 */
		public function moblc_post_scan_complete() {
			MOBLCUtility::moblc_debug_file( 'in plugin moblc_post_scan_complete' );
			$timestamp = wp_next_scheduled( 'moblc_scan_cron_hook' );
			wp_unschedule_event( $timestamp, 'moblc_scan_cron_hook' );
		}
		/**
		 * Function to stop scan.
		 *
		 * @return void
		 */
		public function moblc_stop_scan() {
			MOBLCUtility::moblc_debug_file( 'in plugin moblc_stop_scan' );
			global $moblc_db_queries;
			$timestamp = wp_next_scheduled( 'moblc_scan_cron_hook' );
			wp_unschedule_event( $timestamp, 'moblc_scan_cron_hook' );
			$moblc_db_queries->moblc_clear_status_table();
			wp_send_json( 'SUCCESS' );
		}
	}

}
