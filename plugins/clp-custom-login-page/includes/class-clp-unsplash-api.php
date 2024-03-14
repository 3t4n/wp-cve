<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

class CLP_Unsplash_Api {

	/**
	 * API constructor.
	 *
	 */
	public function __construct() {
        $this->url = $url;
        $this->url = 'https://api.unsplash.com';
        $this->token = 'vKi1UhM3J-Oetvi-mBmp3Spp0_YVxCmfANyTHKJmrKA';
    }

    public function clp_get_unsplash() {

        // verify nonce
        check_ajax_referer( 'clp-custom-login-page-unsplash', '_wpnonce' );

        // verify user rights
        if( !current_user_can('publish_pages') ) {
            die('Sorry, but this request is invalid');
        }

        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$api = new CLP_Unsplash_Api();
		
		switch ( $post['params']['type'] ) {
			case 'all':
			default:
				$result = $api->all( $post['params'] );
				break;
			case 'search':
				$result = $api->search( $post['params'] );
				break;

		}

        echo json_encode( $result );
        
        wp_die();
    }
    
	/**
	 * Retrieve all the photos based on params
	 * @return array|WP_Error
	 */
	public function all( $params ) {

		$request = $this->send_request(
			'/photos',
			$params
        );
        
		if ( is_wp_error( $request ) ) {
			return $request;
        }
        
		return array( 
            'photos'        => $request['body'],
            'total_pages'   => $request['headers']['total_pages'],
            'total'         => $request['headers']['total'],
        );
	}
	
	/**
	 * Retrieve all the photos based on search params
	 * @return array|WP_Error
	 */
	public function search( $params ) {

		$request = $this->send_request(
			'/search/photos',
			$params
        );
        
		if ( is_wp_error( $request ) ) {
			return $request;
        }
        
		return array( 
            'photos'        => $request['body']['results'],
            'total_pages'   => $request['headers']['total_pages'],
            'total'         => $request['headers']['total'],
        );
	}
    
    
	/**
	 * Send request.
	 *
	 * @param string $path Path of the Unsplash API.
	 * @param array  $args Args passed to the url.
	 *
	 * @return array|WP_Error
	 */
	public function send_request( $path, array $args = [] ) {

        $args['client_id'] = $this->token;
        
		$url = $this->url . $path;

		$url = add_query_arg( $args, $url );

		$response = wp_remote_get( $url );

		// If wp_remote_get returns an error, return an
		if ( is_wp_error( $response ) ) {
			return $response;
		}

        $body = wp_remote_retrieve_body( $response );

        $body = json_decode( $body, true );

		$raw_headers = wp_remote_retrieve_headers( $response );

		if ( isset( $raw_headers['x-total'], $raw_headers['x-per-page'] ) ) {
			$headers['total']       = (int) $raw_headers['x-total'];
			$headers['total_pages'] = (int) ceil( $raw_headers['x-total'] / $raw_headers['x-per-page'] );
		}

		$response = [
			'body'    => $body,
			'headers' => $headers,
		];

		return $response;
    }

    public static function get_unsplash_image( $json ) {
        $unsplash_img = json_decode($json, true);
        return $unsplash_img['urls']['original'] . '&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1920&fit=max';
    }
    
}