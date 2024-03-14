<?php

namespace CTXFeed\V5\Common;


use CTXFeed\V5\Download\FileDownload;
use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\Utility\CTX_WC_Log_Handler;
use \WP_Error;

/**
 * Class DownloadFiles
 *
 * @package    CTXFeed\V5\Common
 * @subpackage CTXFeed\V5\Common
 */
class DownloadFiles {

	public function __construct() {
		add_action( 'admin_post_wf_download_feed_log', [ $this, 'download_log' ], 10 );
		add_action( 'admin_post_wf_download_feed', [ $this, 'download_feed' ], 10 );
	}

	/**
	 * Download Feed Log.
	 *
	 * @return void
	 *
	 * @throw RuntimeException
	 */
	public function download_log() {
		if (
			isset( $_REQUEST['feed'], $_REQUEST['_wpnonce'] )
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'wpf-log-download' )
		) {
			$feed_name     = sanitize_text_field( wp_unslash( $_REQUEST['feed'] ) );
			$feed_name     = str_replace( 'wf_feed_', '', $feed_name );
			$log_file_path = CTX_WC_Log_Handler::get_log_file_path( $feed_name );

			$file_name = sprintf(
				'%s-%s-%s.log',
				sanitize_title( $feed_name ),
				gmdate( 'Y-m-d', time() ),
				time()
			);

			if ( ! file_exists( $log_file_path ) ) {
				exit( wp_redirect( add_query_arg( 'wpf_notice_code', 'log_file_not_found', admin_url( 'admin.php?page=webappick-manage-feeds' ) ) ) );
			}

			$fileDownload = new FileDownload( fopen( $log_file_path, 'rb' ) );
			$fileDownload->sendDownload( $file_name );
		} else {
			exit( wp_redirect( add_query_arg( 'wpf_notice_code', 'log_file_not_found', admin_url( 'admin.php?page=webappick-manage-feeds' ) ) ) );
		}
	}

	/**
	 * Download feed.
	 *
	 * @return void
	 */
	public function download_feed() {
		if (
			isset( $_REQUEST['feed'], $_REQUEST['_wpnonce'] )
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'wpf-download-feed' )
		) {
			$feed_name = sanitize_text_field( wp_unslash( $_REQUEST['feed'] ) );
			/* your file, somewhere opened with fopen() or tmpfile(), etc.. */
			$config = Factory::get_feed_info( $feed_name );

			if ( ! file_exists( $config->get_feed_path() ) ) {
				exit( wp_redirect( add_query_arg( 'wpf_notice_code', 'feed_download_failed', admin_url( 'admin.php?page=webappick-manage-feeds' ) ) ) );
			}

			$fileData     = fopen( $config->get_feed_path(), 'rb' );
			$fileDownload = new FileDownload( $fileData );
			$fileDownload->sendDownload( $config->get_feed_file_name() );
		} else {
			exit( wp_redirect( add_query_arg( 'wpf_notice_code', 'feed_download_failed', admin_url( 'admin.php?page=webappick-manage-feeds' ) ) ) );
		}
	}

	/**
	 * @param $feed_name
	 *
	 * @return array|WP_Error
	 */
	public static function rest_download_feed( $feed_name ) {
		$feed_name = sanitize_text_field( wp_unslash( $feed_name ) );
		/* your file, somewhere opened with fopen() or tmpfile(), etc.. */
		$config = Factory::get_feed_info( $feed_name );

		if ( ! file_exists( $config->get_feed_path() ) ) {
			return new WP_Error( 'feed_file_not_found', 'Feed file: ' . $feed_name . ' does\'nt exists.' );
		}

		return ['path' => $config->get_feed_path(), 'file_name' => $config->get_feed_file_name() ];

	}

	/**
	 * @param $feed_name
	 *
	 * @return array|WP_Error
	 */
	public static function rest_download_log( $feed_name ) {

		$feed_name     = sanitize_text_field( wp_unslash( $feed_name ) );
		$feed_name     = str_replace( 'wf_feed_', '', $feed_name );
		$log_file_path = CTX_WC_Log_Handler::get_log_file_path( $feed_name );

		$file_name = sprintf(
			'%s-%s-%s.log',
			sanitize_title( $feed_name ),
			gmdate( 'Y-m-d', time() ),
			time()
		);

		if ( ! file_exists( $log_file_path ) ) {
			return new WP_Error( 'log_file_not_found', 'Feed file: ' . $feed_name . ' does\'nt have any log' );
		}



		return ['path' => $log_file_path, 'file_name' => $file_name ];
	}

	/**
	 * Rest Download config.
	 *
	 * @return bool|WP_Error
	 */
	public static function rest_download_config( $feed_name ) {
		$feed   = sanitize_text_field( wp_unslash( $feed_name ) );
		$feed   = str_replace( [ 'wf_feed_', 'wf_config' ], '', $feed );
		$config = Factory::get_feed_info( $feed );

		$file_name = sprintf(
			'%s-%s.wpf',
			sanitize_title( $config->get_feed_file_name() ),
			time()
		);
		$feed      = wp_json_encode( $config->get_feed_rules() );
		$meta      = wp_json_encode( [
			'version'   => WOO_FEED_FREE_VERSION,
			'file_name' => $file_name,
			'hash'      => md5( $feed ),
		] );
		$bin       = pack( 'VA*VA*', strlen( $meta ), $meta, strlen( $feed ), $feed );
		$feed_config      = gzdeflate( $bin, 9 );

		return $feed_config;


	}

}
