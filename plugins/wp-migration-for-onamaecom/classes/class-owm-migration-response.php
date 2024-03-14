<?php

class Owm_Migration_Response {
	public static function create_response( $response, $status_code = 200 ) {
		self::owm_send_json( $response, $status_code );
	}

	public static function create_error_response( $reason, $status_code = 400 ) {
		$response = array(
			array(
				'error' => array(
					'reason' => $reason
				)
			)
		);
		self::owm_send_json( $response, $status_code );
	}

	private static function owm_send_json( $response, $status_code ) {
		@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		if ( null !== $status_code ) {
			self::owm_status_header( $status_code );
		}
		echo json_encode( $response );
		die;
	}

	private static function owm_get_server_protocol() {
		$protocol = $_SERVER[ 'SERVER_PROTOCOL' ];
		if ( ! in_array( $protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0' ) ) ) {
			$protocol = 'HTTP/1.0';
		}
		return $protocol;
	}

	private static function owm_status_header( $code ) {
		$description = get_status_header_desc( $code );
		if ( empty( $description ) ) {
			return;
		}
		$protocol = self::owm_get_server_protocol();
		$status_header = "$protocol $code $description";
		@header( $status_header, true, $code );
	}
}