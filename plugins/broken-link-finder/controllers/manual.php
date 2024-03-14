<?php
/**
 * This file store scan data in variables;
 *
 * @package broken-link-finder/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $moblc_dir_path, $scanned_link_count, $moblc_db_queries, $moblc_main_dir;
$interval              = ( ini_get( 'max_execution_time' ) - 15 ) * 1000;
$moblc_total_pages     = get_site_option( 'moblc_total_pages' );
$moblc_scanned_pages   = get_site_option( 'moblc_scanned_pages' );
$is_scanning           = get_site_option( 'moblc_is_scanning' );
$count_of_broken_links = $moblc_db_queries->moblc_count_broken_links();
$is_service_started    = get_site_option( 'moblc_service_scan_started' );
$moblc_loader          = $moblc_main_dir . 'includes/images/moblc_loader.gif';
$moblc_scan_count      = get_site_option( 'moblc_scan_count', 0 );
require_once $moblc_dir_path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'manual.php';
