<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MontonioShippingSDK {
    /**
     * @var string
     */
    protected $access_key;
    /**
     * @var string
     */
    protected $secret_key;
    /**
     * @var string
     */
    protected $sandbox_mode;

    const MONTONIO_SHIPPING_SANDBOX_API_URL = 'https://api.shipping.montonio.com';
    const MONTONIO_SHIPPING_API_URL = 'https://api.shipping.montonio.com';

    /**
     * @param $accessKey
     * @param $secret_key
     * @param $environment
     */
    public function __construct( $access_key, $secret_key, $sandbox_mode ) {
        $this->access_key   = $access_key;
        $this->secret_key   = $secret_key;
        $this->sandbox_mode = $sandbox_mode;
    }

    /**
     * @return array
     */
    public function get_pickup_points() {
        $route = '/pickup-points';

        $options = array(
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->get_bearer_token(),
            ),
            'method' => 'GET'
        );

        return $this->api_request ($route, $options );
    }

    /**
     * @param $data
     * @param bool $async
     * @return array
     */
    public function post_shipment( $data, $async = false ) {
        $route = $async ? '/shipments/create-async' : '/shipments';
        $options = array(
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->get_bearer_token(),
            ),
            'method' => 'POST',
            'body' => json_encode( $data )
        );

        return $this->api_request( $route, $options );
    }

    public function create_labels( $data ) {
        $route = '/shipments/label-from-store';
        $options = array(
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->get_bearer_token(),
            ),
            'method' => 'POST',
            'body' => json_encode( $data )
        );

        return $this->api_request( $route, $options );
    }

    /**
     * Decode the Webhook Token
     * This is used to validate the integrity of a webhook sent from Montonio shipping API
     *
     * @param string $token - The Payment Token
     * @param string Your Secret Key for the environment
     * @return object The decoded Payment token
     */
    public static function decode_webhook_token( $token, $secret_key ) {
        MontonioFirebaseV2\JWT\JWT::$leeway = 60 * 5; // 5 minutes
        return MontonioFirebaseV2\JWT\JWT::decode( $token, $secret_key, array('HS256') );
    }

    /**
     * Function for making API calls
     * @param Array Context Options
     * @return Array Array containing status and json_decode response
     */
    protected function api_request( $route, $options ) {
        $url = $this->get_api_url() . $route;
        $options = wp_parse_args( $options, array( 'timeout' => 30 ) );
        
        $response      = wp_remote_request( $url, $options );
        $response_code = wp_remote_retrieve_response_code ( $response );

        if ( $response_code !== 200 && $response_code !== 201 ) {
            throw new Exception( wp_remote_retrieve_body( $response ) );
        }
       
        return json_decode( wp_remote_retrieve_body( $response ) );
    }

    /**
     * @return string
     */
    protected function get_api_url() {
        $url = self::MONTONIO_SHIPPING_API_URL;

        if ( $this->sandbox_mode === 'yes') {
            $url = self::MONTONIO_SHIPPING_SANDBOX_API_URL;
        }

        return $url;
    }

    /**
     * @return string
     */
    protected function get_bearer_token() {
        $data = array(
            'access_key' => $this->access_key,
            'iat'        => time(),
            'exp'        => time() + (60 * 60)
        );

        return MontonioFirebaseV2\JWT\JWT::encode( $data, $this->secret_key );
    }
}