<?php

class BWFAN_API_Get_Log_Files extends BWFAN_API_Base {
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
		$this->route  = '/settings/log-files';
	}

	public function process_api_call() {

		$file_list[] = array(
			'label' => 'Select Log File',
			'value' => ''
		);
		if ( ! class_exists( 'BWF_Logger' ) ) {
			return $this->success_response( $file_list, 'No log files found' );
		}
		$logger_obj        = BWF_Logger::get_instance();
		$final_logs_result = $logger_obj->get_log_options();

		if ( isset( $final_logs_result['autonami-logs'] ) && ! empty( $final_logs_result['autonami-logs'] ) ) {
			foreach ( $final_logs_result['autonami-logs'] as $file_slug => $file_name ) {
				$option_value = 'autonami-logs/' . $file_slug;
				$file_list[]  = array(
					'label' => $file_name,
					'value' => $option_value
				);
			}
		}

		$this->response_code = 200;

		return $this->success_response( $file_list, 'Log files found' );
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Get_Log_Files' );