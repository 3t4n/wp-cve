<?php

namespace FSPoster\App\Providers;

class Curl
{
	public static function getContents ( $url, $method = 'GET', $data = [], $headers = [], $proxy = '', $postDataHBQ = FALSE, $sendUserAgent = TRUE )
	{
		$method = strtoupper( $method );

		$c = curl_init();

		$user_agents = [
			"Mozilla/5.0 (Linux; Android 5.0.2; Andromax C46B2G Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile Safari/537.36 [FB_IAB/FB4A;FBAV/60.0.0.16.76;]"
		];

		$useragent = $user_agents[ array_rand( $user_agents ) ];

		if ( $method === 'GET' && ! empty( $data ) && is_array( $data ) )
		{
			$url .= ( strpos( $url, '?' ) !== FALSE ? '&' : '?' ) . http_build_query( $data );
		}

		$opts = [
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_MAXREDIRS      => 2
		];

		if ( $sendUserAgent )
		{
			$opts[ CURLOPT_USERAGENT ] = $useragent;
		}

		if ( ! empty( $proxy ) )
		{
			$opts[ CURLOPT_PROXY ] = $proxy;
		}

		if ( $method === 'POST' )
		{
			$opts[ CURLOPT_POST ]       = TRUE;
			$opts[ CURLOPT_POSTFIELDS ] = $postDataHBQ ? http_build_query( $data ) : $data;
		}
		else
		{
			if ( $method === 'DELETE' )
			{
				$opts[ CURLOPT_CUSTOMREQUEST ] = 'DELETE';
				$opts[ CURLOPT_POST ]          = TRUE;
				$opts[ CURLOPT_POSTFIELDS ]    = http_build_query( $data );
			}
		}

		if ( is_array( $headers ) && ! empty( $headers ) )
		{
			$headers_arr = [];
			foreach ( $headers as $k => $v )
			{
				$headers_arr[] = $k . ': ' . $v;
			}

			$opts[ CURLOPT_HTTPHEADER ] = $headers_arr;
		}

		curl_setopt_array( $c, $opts );

		$result = curl_exec( $c );

		$cError = curl_error( $c );

		if ( $cError )
		{
			return json_encode( [
				'error' => [
					'message' => htmlspecialchars( $cError )
				]
			] );
		}

		curl_close( $c );

		return $result;
	}
}
