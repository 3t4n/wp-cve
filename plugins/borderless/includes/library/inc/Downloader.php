<?php

namespace LIBRARY;

class Downloader {
	private $download_directory_path = '';

	public function __construct( $download_directory_path = '' ) {
		$this->set_download_directory_path( $download_directory_path );
	}


	public function download_file( $url, $filename ) {
		$content = $this->get_content_from_url( $url );

		if ( is_wp_error( $content ) ) {
			return $content;
		}

		return Helpers::write_to_file( $content, $this->download_directory_path . $filename );
	}


	private function get_content_from_url( $url ) {
		if ( empty( $url ) ) {
			return new \WP_Error(
				'missing_url',
				__( 'Missing URL for downloading a file!', 'borderless' )
			);
		}

		$response = wp_remote_get(
			$url,
			array( 'timeout' => Helpers::apply_filters( 'library/timeout_for_downloading_import_file', 20 ) )
		);

		if ( is_wp_error( $response ) || 200 !== $response['response']['code'] ) {
			$response_error = $this->get_error_from_response( $response );

			return new \WP_Error(
				'download_error',
				sprintf( 
					__( 'An error occurred while fetching file from: %1$s%2$s%3$s!%4$sReason: %5$s - %6$s.', 'borderless' ),
					'<strong>',
					$url,
					'</strong>',
					'<br>',
					$response_error['error_code'],
					$response_error['error_message']
				) . '<br>' .
				Helpers::apply_filters( 'library/message_after_file_fetching_error', '' )
			);
		}

		return wp_remote_retrieve_body( $response );
	}


	private function get_error_from_response( $response ) {
		$response_error = array();

		if ( is_array( $response ) ) {
			$response_error['error_code']    = $response['response']['code'];
			$response_error['error_message'] = $response['response']['message'];
		}
		else {
			$response_error['error_code']    = $response->get_error_code();
			$response_error['error_message'] = $response->get_error_message();
		}

		return $response_error;
	}


	public function get_download_directory_path() {
		return $this->download_directory_path;
	}


	public function set_download_directory_path( $download_directory_path ) {
		if ( file_exists( $download_directory_path ) ) {
			$this->download_directory_path = $download_directory_path;
		}
		else {
			$upload_dir = wp_upload_dir();
			$this->download_directory_path = Helpers::apply_filters( 'library/upload_file_path', trailingslashit( $upload_dir['path'] ) );
		}
	}
}
