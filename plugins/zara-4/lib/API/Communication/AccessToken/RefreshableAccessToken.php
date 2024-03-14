<?php
if ( ! class_exists( 'Zara4_API_Communication_AccessToken_RefreshableAccessToken' ) ) {


  /**
   * Class Zara4_API_Communication_AccessToken_RefreshableAccessToken
   */
  class Zara4_API_Communication_AccessToken_RefreshableAccessToken extends Zara4_API_Communication_AccessToken_AccessToken {

    protected $refresh_token;


    public function __construct( $client_id, $client_secret, $access_token, $expires_at, $refresh_token ) {
      parent::__construct( $client_id, $client_secret, $access_token, $expires_at );
      $this->refresh_token = $refresh_token;
    }


    /**
     * Refresh this AccessToken
     */
    public function refresh() {
      $grant = new Zara4_API_Communication_Grant_RefreshTokenGrant( $this->client_id, $this->client_secret, $this->refresh_token );
      $tokens = $grant->getTokens();

      $this->access_token = $tokens->{"access_token"};
      $this->expires_at = Zara4_API_Communication_Util::calculate_expiry_time( $tokens->{"expires_in"} );
      $this->refresh_token = $tokens->{"refresh_token"};
    }

  }

}