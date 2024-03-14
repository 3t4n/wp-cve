<?php

namespace Upress\Booter;

class AjaxHandlers {
	private static $instance;

	/**
	 * @return AjaxHandlers
	 */
	public static function initialize() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_ajax_booter_disable_404_plugins', [ $this, 'disable_404_plugins' ] );
		add_action( 'wp_ajax_booter_download_disavow_list', [ $this, 'download_disavow_list' ] );
		add_action( 'wp_ajax_booter_get_bad_robots_list', [ $this, 'ajax_get_bad_robots_list' ] );
	}

	function disable_404_plugins() {
		check_ajax_referer('booter-notices' );

		$slugs = isset( $_POST['slugs'] ) ? $_POST['slugs'] : [];
		$slugs = array_map( 'sanitize_key', $slugs );
		$slugs = array_filter( $slugs );

		if ( count( $slugs ) <= 0 ) {
			wp_send_json( [ 'success' => false, 'error' => 'nothing to disable' ] );
		}

		deactivate_plugins( $slugs );

		wp_send_json( [ 'success' => true ] );
	}

	function download_disavow_list() {
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'download-disavow' ) ) {
			wp_die( __( 'Sorry, you are not allowed to access this page.' ) );
		}

		set_transient( 'booter_disavow_list_downloaded_at', time() );

		$referers = Utilities::get_bad_referers();
		$referers = array_unique( $referers );
		$referers = array_filter( $referers );
		$referers = array_map( function( $r ) {
			return "domain:" . trim( $r );
		}, $referers );
		$referers = implode( "\r\n", $referers );

		header('Content-Encoding: UTF-8');
		header( 'Content-Type: application/octet-stream; charset=UTF-8' );
		header( 'Content-Transfer-Encoding: Binary' );
		header( 'Content-disposition: attachment; filename="booter-disavow-links-' . time() . '.txt"' );
		echo "\xEF\xBB\xBF"; // UTF-8 BOM
		die( $referers );
	}

	function ajax_get_bad_robots_list() {
		check_ajax_referer( 'booter-options' );

		wp_send_json( Utilities::get_bad_robots() );
	}

}
