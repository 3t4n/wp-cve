<?php

namespace Codemanas\Typesense\Backend;

use Codemanas\Typesense\Helpers\Logger;
use Codemanas\Typesense\Main\TypesenseAPI;

class AdminAjaxHandler {

	public static ?AdminAjaxHandler $instance = null;
	private Logger $logger;

	public static function getInstance(): ?AdminAjaxHandler {
		return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
	}

	public function __construct() {
		$this->logger = new Logger();
		//log callbacks
		add_action( 'wp_ajax_cm_typesense_get_log_files', [ $this, 'getLogFiles' ] );
		add_action( 'wp_ajax_cm_typesense_view_log_file', [ $this, 'viewLogFile' ] );
		add_action( 'wp_ajax_cm_typesense_get_site_info', [ $this, 'getSiteInfo' ] );
		add_action( 'wp_ajax_cm_typesense_delete_all_log_files', [ $this, 'deleteLogFiles' ] );

		//addons
		add_action( 'wp_ajax_cm_typesense_get_addons', [ $this, 'get_addons' ] );
	}

	public function get_addons() {
		wp_send_json( apply_filters( 'cm_typesense_enabled_addons', [] ) );
	}

	/**
	 * @param $posted_data
	 *
	 * @return bool
	 */
	private function validateGetAccess( $posted_data ): bool {

		/*Bail Early*/
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( empty( $posted_data['nonce'] ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $posted_data['nonce'], 'cm_typesense_ValidateNonce' ) ) {
			return false;
		}

		return true;
	}

	public function getLogFiles(): void {
		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );

		if ( ! $this->validateGetAccess( $posted_data ) ) {
			wp_send_json( false );
		}


		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );
		$log_type     = is_array( $posted_data ) ? $posted_data['log_type'] : 'debug';

		$files = $this->logger->readAllErrorLogFiles( $log_type );
		wp_send_json( $files );
	}

	public function viewLogFile(): void {
		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );

		if ( ! $this->validateGetAccess( $posted_data ) ) {
			wp_send_json( false );
		}

		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );
		$filename     = is_array( $posted_data ) ? $posted_data['filename'] : '';
		$log_type     = is_array( $posted_data ) ? $posted_data['log_type'] : '';


		if ( $filename == '' ) {
			wp_send_json( [] );
		}

		$logData = [];

		if ( $log_type == 'error' ) {
			$logData = [ 'logData' => $this->logger->readErrorFile( $filename ) ];
		} elseif ( $log_type == 'debug' ) {
			$logData = [ 'logData' => $this->logger->readDebugFile( $filename ) ];
		}
		wp_send_json( $logData );
	}

	public function getSiteInfo(): void {
		if ( ! class_exists( 'WP_Debug_Data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
		}

		$site_data = \WP_Debug_Data::debug_data();
		$site_data = \WP_Debug_Data::format( $site_data, 'value' );

		$search_config_settings = Admin::get_search_config_settings();
		$enabled_post_types     = $search_config_settings['enabled_post_types'];
		// pre_dump( $search_config_settings ); die;

		$schemas = "### schemas ### \r\n\r\n";
		foreach ( $enabled_post_types as $index_name ) {
			// Change to json to concatinate as string since WP_Debug_Data::format can't format schemas.
			$schema  = json_encode( TypesenseAPI::getInstance()->getSchema( $index_name ) ) . "\r\n\r\n";
			$schemas .= "=======" . $index_name . "====== \r\n" . $schema;
		}

		$server_info = "### Typesense server info ### \r\n\r\n";
		$server_info .= json_encode( TypesenseAPI::getInstance()->getDebugInfo() ) . "\r\n\r\n";
		$server_info .= 'Health: ' . json_encode( TypesenseAPI::getInstance()->getServerHealth() );

		$info = $site_data . $schemas . $server_info;

		wp_send_json( $info );
	}

	public function deleteLogFiles(): void {
		$request_body = file_get_contents( 'php://input' );
		$posted_data  = json_decode( $request_body, true );

		if ( ! $this->validateGetAccess( $posted_data ) ) {
			wp_send_json( false );
		}

		if ( empty( $posted_data['log_type'] ) ) {
			wp_send_json( false );
		}

		$this->logger->deleteAllFiles( $posted_data['log_type'] );
		wp_send_json( true );
	}

}