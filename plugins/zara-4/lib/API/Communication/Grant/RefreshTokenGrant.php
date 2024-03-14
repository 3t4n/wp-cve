<?php
if ( ! class_exists( 'Zara4_API_Communication_Grant_RefreshTokenGrant' ) ) {


  /**
   * Class Zara4_API_Communication_Grant_RefreshTokenGrant
   */
  class Zara4_API_Communication_Grant_RefreshTokenGrant extends Zara4_API_Communication_Grant_GrantRequest {

    protected $grantType = "refresh_token";
    protected $refreshToken;


    public function __construct( $client_id, $client_secret, $refreshToken, $scopes = array() ) {
      $this->refreshToken = $refreshToken;
      parent::__construct( $client_id, $client_secret, $scopes );
    }


    protected function data() {
      return array_merge( parent::data(), array(
        "refresh_token" => $this->refreshToken,
      ) );
    }

  }

}