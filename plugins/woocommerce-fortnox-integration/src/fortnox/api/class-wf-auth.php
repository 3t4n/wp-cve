<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;
use src\fortnox\WF_Plugin;

class WF_Auth
{

    const DEFAULT_CLIENT_ID = 'EY09o6u03lry';
    const DEFAULT_CLIENT_SECRET = "AQV9TbDU1k";
    const REFRESH_URL = 'https://apps.fortnox.se/oauth-v1/token';

    const CLIENT_SECRETS = [
        'I8XC7BdsOfHb' => 'Y9Of3q2R5d',
        'DCgr45nMoivQ' => 'Jayj6qoS3q',
        '3sSe1NG4v7Vy' => 'zWqRG57WB8',
        'XDNN1lVg0cP7' => 'RbwZ6AGqV7',
        'z5cRL3VdVqig' => 'njvW416kDA',
        '2R1zrzTtLIO6' => 'pnS6KS3JUs',
        'Ze142vAjELDu' => '2bk1YiB8E2',
        'Bd5IBIFQX0Kp' => 'EoxA29wVKS',
        '3KFTgHFugr1E' => '5s1t8LiZX1'
    ];
    const CLIENT_IDS = [
        'I8XC7BdsOfHb',
        'DCgr45nMoivQ',
        '3sSe1NG4v7Vy',
        'XDNN1lVg0cP7',
        'z5cRL3VdVqig',
        '2R1zrzTtLIO6',
        'Ze142vAjELDu',
        'Bd5IBIFQX0Kp',
        '3KFTgHFugr1E'
    ];

    public static function get_client_id(){
        $client_id = get_option( 'fortnox_client_id' );
        if( empty( $client_id ) ){
            return self::DEFAULT_CLIENT_ID;
        }

        if( ! in_array( $client_id, self::CLIENT_IDS ) ){
            return self::DEFAULT_CLIENT_ID;
        }

        return $client_id;
    }

    public static function get_client_secret(){
        $client_id = get_option( 'fortnox_client_id' );
        if( empty( $client_id ) ){
            return self::DEFAULT_CLIENT_SECRET;
        }

        if( ! in_array( $client_id, self::CLIENT_IDS ) ){
            return self::DEFAULT_CLIENT_SECRET;
        }

        return self::CLIENT_SECRETS[$client_id];
    }
    /**
     * Get access token
     * @return mixed
     * @throws Exception
     */
	public static function get_access_token()
	{
		$auth_code = get_option( 'fortnox_auth_code'  );
		$client_secret = self::get_client_secret();

		if( empty( $auth_code ) || empty( $client_secret ) ){
            throw new Exception( "Authorisation code is empty." );
        }

        $response = wp_remote_get( "https://api.fortnox.se/3/", array( 'headers' => [
            'Authorization-Code' => $auth_code,
            'Client-Secret' => $client_secret,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ] ) );

        fortnox_write_log( "get_access_token: " );
        fortnox_write_log( $response );

        $response = json_decode( $response['body'] );

		if( ! empty( $response->ErrorInformation ) ){
            throw new Exception( "{$response->ErrorInformation->Message} (Felkod: {$response->ErrorInformation->Code})" );
        }

		if( ! empty( $response->Authorization->AccessToken ) ){
            update_option( 'fortnox_access_token', $response->Authorization->AccessToken );
            update_option( 'fortnox_connected_organization_number', WF_Company_Information::get_organization_number() );
        }

		return $response->Authorization->AccessToken;
	}

    public static function custom_http_request_timeout( $timeout_value ) {
        return 20;
    }

    /**
     * Refreshes token
     */
    public static function refresh_token(){


        add_filter( 'http_request_timeout', [ 'src\fortnox\api\WF_Auth', 'custom_http_request_timeout' ], 10, 1);

        $headers = [
            'Content-type' =>  'application/x-www-form-urlencoded',
            'Authorization' =>  'Basic ' . base64_encode(self::get_client_id() . ':' . self::get_client_secret())
        ];

        $args = [
            'headers' => $headers,
            'body' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => get_option('fortnox_refresh_token')
            ],
            'method' => 'POST',
            'data_format' => 'body'
        ];

        fortnox_write_log( "Refreshing access_token: " );
        fortnox_write_log( $args );

        $response = wp_remote_post( self::REFRESH_URL, $args );
        $data = json_decode( $response['body'] );

        fortnox_write_log( "Response " );
        fortnox_write_log( $response );

        if( self::is_error( $data ) ){
            throw new \Exception( 'Fortnox integrationen har blivit utloggad, logga in igen genom att göra om steg 1 och 2 i : <a href="https://docs.wetail.io/woocommerce/fortnox-integration/fortnox-installationsguide/">Installationsguide</a>', 401 );
        }


        if ( property_exists( $data, 'access_token') ) {
            update_option('fortnox_access_token_oauth2', $data->access_token);
            update_option('fortnox_refresh_token', $data->refresh_token);
            update_option('fortnox_access_token_expiry_time', time() + (60 * 60));
        }

    }

    /**
     * Check if response has en error
     *
     * @param $response
     * @return mixed
     */
    public static function is_error( $data ){
        return isset( $data->error );
    }
}

