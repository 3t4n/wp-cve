<?php
if ( ! class_exists( 'Zara4_API_Communication_Authentication_ApplicationAuthenticator' ) ) {


  /**
   * Class Zara4_API_Communication_Authentication_ApplicationAuthenticator
   */
  class Zara4_API_Communication_Authentication_ApplicationAuthenticator extends Zara4_API_Communication_Authentication_Authenticator {

    /**
     * Get an AccessToken for use when communicating with the Zara 4 API service.
     *
     * @return Zara4_API_Communication_AccessToken_AccessToken
     */
    public function acquire_access_token() {
      $grant = new Zara4_API_Communication_Grant_ClientCredentialsGrantRequest( $this->client_id, $this->client_secret, $this->scopes );
      $tokens = $grant->getTokens();

      $accessToken = $tokens->{"access_token"};
      $expiresAt = Zara4_API_Communication_Util::calculate_expiry_time( $tokens->{"expires_in"} );

      return new Zara4_API_Communication_AccessToken_ReissuableAccessToken( $this->client_id, $this->client_secret, $accessToken, $expiresAt, $this->scopes );
    }

  }

}