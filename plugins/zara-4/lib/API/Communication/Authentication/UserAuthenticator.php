<?php
if ( ! class_exists( 'Zara4_API_Communication_Authentication_UserAuthenticator' ) ) {


  /**
   * Class Zara4_API_Communication_Authentication_UserAuthenticator
   */
  class Zara4_API_Communication_Authentication_UserAuthenticator extends Zara4_API_Communication_Authentication_Authenticator {

    private $username;
    private $password;


    public function __construct( $client_id, $client_secret, $username, $password ) {
      /** @noinspection PhpUndefinedClassInspection */
      parent::__construct( $client_id, $client_secret );
      $this->username = $username;
      $this->password = $password;
    }


    /**
     * Get an AccessToken for use when communicating with the Zara 4 API service.
     *
     * @return Zara4_API_Communication_AccessToken_AccessToken
     */
    public function acquire_access_token() {
      $grant = new Zara4_API_Communication_Grant_PasswordGrant( $this->client_id, $this->client_secret, $this->username, $this->password, $this->scopes );
      $tokens = $grant->getTokens();

      $accessToken = $tokens->{"access_token"};
      $refreshToken = $tokens->{"refresh_token"};
      $expiresAt = Zara4_API_Communication_Util::calculate_expiry_time( $tokens->{"expires_in"} );

      return new Zara4_API_Communication_AccessToken_RefreshableAccessToken( $this->client_id, $this->client_secret, $accessToken, $expiresAt, $refreshToken );
    }

  }

}