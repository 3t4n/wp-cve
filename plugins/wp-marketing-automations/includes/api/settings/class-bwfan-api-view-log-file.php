<?php

class BWFAN_API_View_Log_File extends BWFAN_API_Base {
	public static $ins;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function __construct() {
		parent::__construct();
		$this->method = WP_REST_Server::READABLE;
		$this->route  = '/settings/log/view';
	}

	public function process_api_call() {
		$selected_log_file = $this->get_sanitized_arg( 'log_selected', 'text_field' );
		if ( empty( $selected_log_file ) ) {
			$this->response_code = 404;

			return $this->error_response( __( 'Selected log file missing', 'wp-marketing-automations' ) );
		}
		$folder_prefix    = explode( '/', $selected_log_file );
		$folder_file_name = $folder_prefix[1];
		$folder_prefix    = $folder_prefix[0];
		$file_api         = new WooFunnels_File_Api( $folder_prefix );

		// View log submit is clicked, get the content from the selected file
		$content = $file_api->get_contents( $folder_file_name );
		if ( $content !== false ) {
			$this->response_code = 200;

			return $this->success_response( $content, 'Log file data fetched' );
		}
		$this->response_code = 404;

		return $this->error_response( __( 'Selected log file not found', 'wp-marketing-automations' ) );

	}
}

BWFAN_API_Loader::register( 'BWFAN_API_View_Log_File' );