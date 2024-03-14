<?php
/**
 * Class Advanced_Form_Integration_OAuth2
 *
 * This class provides an OAuth 2.0 client for Advanced Form Integration WordPress plugin.
 */
class Advanced_Form_Integration_OAuth2 {

    /**
     * The client ID for the OAuth 2.0 client.
     *
     * @var string
     */
    protected $client_id;

    /**
     * The client secret for the OAuth 2.0 client.
     *
     * @var string
     */
    protected $client_secret;

    /**
     * The access token for the OAuth 2.0 client.
     *
     * @var string
     */
    protected $access_token;

    /**
     * The refresh token for the OAuth 2.0 client.
     *
     * @var string
     */
    protected $refresh_token;

    /**
     * The authorization endpoint for the OAuth 2.0 server.
     *
     * @var string
     */
    protected $authorization_endpoint;

    /**
     * The token endpoint for the OAuth 2.0 server.
     *
     * @var string
     */
    protected $token_endpoint;

    /**
     * The refresh token endpoint for the OAuth 2.0 server.
     *
     * @var string
     */
    protected $refresh_token_endpoint = '';

    public function get_title() {

        return '';
    }

    /**
     * Checks if the OAuth 2.0 client is active.
     *
     * @return bool
     */
    public function is_active() {

        return !empty( $this->refresh_token );
    }

    /**
     * Saves the OAuth 2.0 client data to the database.
     *
     * @return void
     */
    protected function save_data() {
    }

    /**
     * Resets the OAuth 2.0 client data.
     *
     * @return void
     */
    protected function reset_data() {
    }

    /**
     * Gets the redirect URI for the OAuth 2.0 client.
     *
     * @return string
     */
    protected function get_redirect_uri() {
        return admin_url();
    }

    /**
     * Authorizes the user to access the OAuth 2.0 server.
     *
     * @param string $scope The scope of the OAuth 2.0 authorization request.
     * @return void
     */
    protected function authorize( string $scope = '' ) {

        $data = array(
            'response_type' => 'code',
            'client_id'     => $this->client_id,
            'redirect_uri'  => urlencode( $this->get_redirect_uri() )
        );

        if( $scope ) {
            $data["scope"] = $scope;
        }

        $endpoint = add_query_arg( $data, $this->authorization_endpoint );

        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit();
        }
    }

    protected function get_http_authorization_header( $scheme = 'basic' ) {

        $scheme = strtolower( trim( $scheme ) );

        switch ( $scheme ) {
            case 'bearer':
                return sprintf( 'Bearer %s', $this->access_token );
            case 'basic':
            default:
                return sprintf( 'Basic %s',
                    base64_encode( $this->client_id . ':' . $this->client_secret )
                );
        }
    }

    protected function request_token( $authorization_code ) {

        $endpoint = add_query_arg(
            array(
                'code'         => $authorization_code,
                'redirect_uri' => urlencode( $this->get_redirect_uri() ),
                'grant_type'   => 'authorization_code',
            ),
            $this->token_endpoint
        );

        $request = [
            'headers' => [
                'Authorization' => $this->get_http_authorization_header( 'basic' ),
            ],
        ];

        $response      = wp_remote_post( esc_url_raw( $endpoint ), $request );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );

        if ( 401 == $response_code ) { // Unauthorized
            $this->access_token  = null;
            $this->refresh_token = null;
        } else {
            if ( isset( $response_body['access_token'] ) ) {
                $this->access_token = $response_body['access_token'];
            } else {
                $this->access_token = null;
            }

            if ( isset( $response_body['refresh_token'] ) ) {
                $this->refresh_token = $response_body['refresh_token'];
            } else {
                $this->refresh_token = null;
            }
        }

        $this->save_data();

        return $response;
    }

    protected function refresh_token() {

        $endpoint = add_query_arg(
            array(
                'refresh_token' => $this->refresh_token,
                'grant_type'    => 'refresh_token',
            ),
            $this->refresh_token_endpoint
        );

        $request = [
            'headers' => array(
                'Authorization' => $this->get_http_authorization_header( 'basic' ),
            ),
        ];

        $response      = wp_remote_post( esc_url_raw( $endpoint ), $request );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );

        if ( 401 == $response_code ) { // Unauthorized
            $this->access_token  = null;
            $this->refresh_token = null;
        } else {
            if ( isset( $response_body['access_token'] ) ) {
                $this->access_token = $response_body['access_token'];
            } else {
                $this->access_token = null;
            }

            if ( isset( $response_body['refresh_token'] ) ) {
                $this->refresh_token = $response_body['refresh_token'];
            }
        }

        $this->save_data();

        return $response;
    }

    protected function remote_request( $url, $request = array() ) {

        static $refreshed = false;

        $request = wp_parse_args( $request, array( 'timeout' => 30 ) );

        $request['headers'] = array_merge(
            $request['headers'],
            array( 'Authorization' => $this->get_http_authorization_header( 'bearer' ), )
            
        );

        $response = wp_remote_request( esc_url_raw( $url ), $request );

        if ( 401 === wp_remote_retrieve_response_code( $response )
            and !$refreshed
        ) {
            $this->refresh_token();
            $refreshed = true;

            $response = $this->remote_request( $url, $request );
        }

        return $response;
    }
}