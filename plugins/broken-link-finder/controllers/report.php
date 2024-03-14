<?php
/**
 * This is report file.
 *
 * @package broken-link-finder/controllers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $moblc_db_queries, $moblc_dir_path, $moblc_main_dir;

$moblc_called_service = get_site_option( 'moblc_called_service' );
$diff                 = abs( $moblc_called_service - time() ) / 60 * 60;

$moblc_broken_links  = array();
$is_scanning         = get_site_option( 'moblc_is_scanning' );
$moblc_scanned_pages = $moblc_db_queries->moblc_get_scanned_pages();
update_site_option( 'moblc_scanned_pages', $moblc_scanned_pages );

$moblc_broken_links = $moblc_db_queries->moblc_get_broken_links();
$broken_links_title = $moblc_db_queries->moblc_broken_links_title();
$moblc_broken_pages = $moblc_db_queries->moblc_get_broken_pages();

$size          = count( $moblc_broken_links );
$links_tooltip = array(
	'edit'     => 'It will help you to edit the broken link',
	'unlink'   => 'It will simply remove the destination link. New link will redirect you to the same page on which link is present',
	'unbroken' => 'It will mark the selected link as not broken',
	'remove'   => 'It will remove the selected link from the report',
	'recheck'  => 'It will recheck the status code of the selected link',
);
$posts_tooltip = array(
	'edit' => 'It will help you to edit the post ',
	'view' => 'It redirect you to the selected post or page',
);
$pages_tooltip = array(
	'recheck' => 'The current page will be rescanned for the broken links',
	'ignore'  => 'It will remove the current page from the report',
);
$moblc_loader  = $moblc_main_dir . 'includes/images/moblc_loader.gif';

$total_3xx    = $moblc_db_queries->moblc_get_link_count( 3, false );
$total_4xx    = $moblc_db_queries->moblc_get_link_count( 4, false );
$total_5xx    = $moblc_db_queries->moblc_get_link_count( 5, false );
$total_others = $moblc_db_queries->moblc_get_link_count( 0, true );


require_once $moblc_dir_path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'report.php';
