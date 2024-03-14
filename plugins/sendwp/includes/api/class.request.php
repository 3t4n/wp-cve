<?php

namespace SendWP\API;

class Request
{
    protected $server_url;
    protected $endpoint;
    protected $client_name;
    protected $client_secret;

    public static function create( $endpoint )
    {
        $server_url = sendwp_get_server_url();
        $client_name = sendwp_get_client_name();
        $client_secret = sendwp_get_client_secret();
        return new self( $server_url, $endpoint, $client_name, $client_secret );
    }

    public function __construct( $server_url, $endpoint, $client_name, $client_secret )
    {
        $this->server_url = $server_url;
        $this->set_endpoint( $endpoint );
        $this->client_name = $client_name;
        $this->client_secret = $client_secret;
    }

    public function set_endpoint( $target )
    {
        $this->endpoint = 'wp-json/sendwp/' . $target;
    }

    public function request_url()
    {
        return $this->server_url . $this->endpoint;
    }

    public function post( $args )
    {
        return $this->request( 'POST', $args );
    }

    public function request( $method, $args )
    {
        $args[ 'method' ] = $method;
        $args['reject_unsafe_urls'] = false; // Whitelist requests to the service.
        $args[ 'headers' ][ 'x-sendwp-client-auth' ] = $this->get_auth_headers();

        if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $args['headers']['x-forwarded-for'] = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $args['headers']['x-forwarded-for'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $args['headers']['x-forwarded-for'] = $_SERVER['REMOTE_ADDR'];
        }

        return wp_remote_request( $this->request_url(), $args );
    }

    protected function get_auth_headers()
    {
        return 'Basic ' . base64_encode( $this->client_name . ':' . $this->client_secret );
    }
}