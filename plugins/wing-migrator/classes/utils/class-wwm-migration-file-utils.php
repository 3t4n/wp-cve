<?php

class Wwm_File_Utils {
	public static function delete_dir( $target_dir ) {
		if ( strpos( $target_dir, 'bk_' ) === false && strpos( $target_dir, 'rs_' ) === false ) {
			error_log( 'wing-migration:  unauthorized path ' . $target_dir );
			return false;
		}

		if ( file_exists( $target_dir ) === false ) {
			return true;
		}
		foreach ( glob( "{$target_dir}/*", GLOB_MARK ) as $file ) {
			if ( is_dir( $file ) ) {
				$remove_status = self::delete_dir( $file );
			} elseif ( function_exists( 'wp_delete_file' ) ) {
				wp_delete_file( $file );
				$remove_status = true;
			} else {
				@unlink( $file );
				$remove_status = true;
			}
			if ( ! $remove_status ) {
				return false;
			}
		}
		return rmdir( $target_dir );
	}

	public static function list_dir( $base_dir ) {
		$dirs = array();
		foreach ( glob( $base_dir, GLOB_ONLYDIR ) as $dir ) {
			array_push( $dirs, $dir );
		}
		return $dirs;
	}

	public static function file_download( $file_path, $data_url ) {

		$headers = array(
			'Accept' => '*/*',
			'Accept-Encoding' => '*',
			'Accept-Charset' => '*',
			'Accept-Language' => '*',
			'User-Agent' => WWM_MIGRATION_HTTP_USER_AGENT,
		);

		$curlopt_headers = array();
		foreach ( $headers as $key => $value ) {
			$curlopt_headers[] = "{$key}: {$value}";
		}

		$ch = curl_init();
		$file = fopen( $file_path, 'w' );
		curl_setopt( $ch, CURLOPT_URL, $data_url );
		curl_setopt( $ch, CURLOPT_FAILONERROR, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_FILE, $file );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $curlopt_headers );
		curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$response = @curl_exec( $ch );
		$errorno = curl_errno( $ch );
		curl_close( $ch );
		fclose( $file );

		if ( $response === false ) {
			return false;
		}

		if ( $errorno == 18 ) {
			return self::file_download_for_wget( $file_path, $data_url );

		}

		return true;
	}

	public static function chunk_file_download( $file_path, $data_url, $range_start, $range_end, $append_mode ) {

		$headers = array(
			'Accept' => '*/*',
			'Accept-Encoding' => '*',
			'Accept-Charset' => '*',
			'Accept-Language' => '*',
			'User-Agent' => '',
		);

		$headers[ 'Range' ] = 'bytes=' . $range_start . '-' . $range_end;

		$curlopt_headers = array();
		foreach ( $headers as $key => $value ) {
			$curlopt_headers[] = "{$key}: {$value}";
		}

		$ch = curl_init();
		if ( $append_mode ) {
			$file = fopen( $file_path, 'a' );
		} else {
			$file = fopen( $file_path, 'w' );
		}
		curl_setopt( $ch, CURLOPT_URL, $data_url );
		curl_setopt( $ch, CURLOPT_FAILONERROR, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_FILE, $file );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $curlopt_headers );
		curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$response = @curl_exec( $ch );
		$errorno = curl_errno( $ch );
		$http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );
		fclose( $file );

		if ( $response === false ) {
			return false;
		}

		if ( $errorno == 18 ) {
			$wget_res = self::file_download_for_wget( $file_path, $data_url );
			if ( $wget_res === true ) {
				return 200;
			}
			return $wget_res;
		}

		return $http_status;
	}

	public static function get_file_info( $data_url ) {
		$headers = array(
			'Accept' => '*/*',
			'Accept-Encoding' => '*',
			'Accept-Charset' => '*',
			'Accept-Language' => '*',
			'User-Agent' => '',
		);

		$curlopt_headers = array();
		foreach ( $headers as $key => $value ) {
			$curlopt_headers[] = "{$key}: {$value}";
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $data_url );
		curl_setopt( $ch, CURLOPT_FAILONERROR, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $curlopt_headers );
		curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$response = @curl_exec( $ch );
		$errorno = curl_errno( $ch );
		curl_close( $ch );

		$content_length = 0;
		$is_range_support = false;

		if ( $response === false ) {
			return array(
				"file_exists" => false,
				"content_length" => $content_length,
				"errorno" => $errorno,
				"is_range_support" => $is_range_support
			);
		}

		$data = explode( "\n", $response );
		foreach ( $data as $part ) {
			$header_parts = explode( ":", $part, 2 );
			$header_data = strtolower( $header_parts[ 0 ] );
			if ( $header_data === 'content-length' ) {
				$content_length = (int)$header_parts[ 1 ];
			} elseif ( $header_data === 'accept-ranges' && trim( $header_parts[ 1 ] ) === 'bytes' ) {
				$is_range_support = true;
			}
		}

		return array(
			"file_exists" => true,
			"content_length" => $content_length,
			"is_range_support" => $is_range_support
		);
	}

	public static function file_download_for_wget( $file_path, $data_url ) {
		$cmd = "wget -O {$file_path} {$data_url} > /dev/null 2>&1";
		passthru( $cmd, $ret );
		if ( $ret == 1 ) {
			return false;
		}
		return true;
	}

	public static function add_index_php( $target_dir ) {
		try {
			$file = $target_dir . '/index.php';
			$contents = <<< EOF
<?php
header ("HTTP/1.1 404");
EOF;
			@file_put_contents( $file, $contents );
		} catch ( Exception $exception ) {
		}
	}
}
