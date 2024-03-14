<?php
if ( ! class_exists( 'Zara4_API_Communication_AccessToken_ReissuableAccessToken' ) ) {


  /**
   * Class Zara4_API_Communication_AccessToken_ReissuableAccessToken
   */
  class Zara4_API_Communication_AccessToken_ReissuableAccessToken extends Zara4_API_Communication_AccessToken_AccessToken {

    private $scopes = array();


    public function __construct( $client_id, $client_secret, $access_token, $expires_at, array $scopes = array() ) {
      parent::__construct( $client_id, $client_secret, $access_token, $expires_at );
      $this->scopes = $scopes;
    }


    /**
     * Refresh this AccessToken
     */
    public function refresh() {
      $grant = new Zara4_API_Communication_Grant_ClientCredentialsGrantRequest( $this->client_id, $this->client_secret, $this->scopes );
      $tokens = $grant->getTokens();

      $this->access_token = $tokens->{"access_token"};
      $this->expires_at = Zara4_API_Communication_Util::calculate_expiry_time( $tokens->{"expires_in"} );
    }

  }

}