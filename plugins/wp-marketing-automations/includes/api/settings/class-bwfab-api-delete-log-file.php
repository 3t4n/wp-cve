<?php

class BWFAN_API_Delete_Log_File extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::DELETABLE;
		$this->route  = '/settings/log/delete';
	}

	public function process_api_call() {
		$selected_log_file = $this->get_sanitized_arg( 'log_selected', 'text_field' );
		$all               = $this->get_sanitized_arg( 'all', 'bool' );

		if ( true === $all ) {
			$file_api = new WooFunnels_File_Api( 'autonami-logs' );

			$delete = $file_api->delete_all( 'autonami-logs', true );
			if ( $delete ) {
				$this->response_code = 200;

				return $this->success_response( [], 'Log files deleted' );
			}

			$this->response_code = 404;

			return $this->error_response( __( 'Log files not found', 'wp-marketing-automations' ) );
		}

		if ( empty( $selected_log_file ) ) {
			$this->response_code = 404;

			return $this->error_response( __( 'Selected log file missing', 'wp-marketing-automations' ) );
		}
		$folder_prefix    = explode( '/', $selected_log_file );
		$folder_file_name = $folder_prefix[1];
		$folder_prefix    = $folder_prefix[0];
		$file_api         = new WooFunnels_File_Api( $folder_prefix );

		// View log submit is clicked, get the content from the selected file
		$delete = $file_api->delete_file( $folder_file_name );
		if ( $delete ) {
			$this->response_code = 200;

			return $this->success_response( [], 'Log file deleted' );
		}
		$this->response_code = 404;

		return $this->error_response( __( 'Selected log file not found', 'wp-marketing-automations' ) );

	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Delete_Log_File' );