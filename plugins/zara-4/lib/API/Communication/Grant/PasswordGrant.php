<?php
if ( ! class_exists( 'Zara4_API_Communication_Grant_PasswordGrant' ) ) {


  /**
   * Class Zara4_API_Communication_Grant_PasswordGrant
   */
  class Zara4_API_Communication_Grant_PasswordGrant extends Zara4_API_Communication_Grant_GrantRequest {

    protected $grantType = "password";
    protected $username;
    protected $password;


    public function __construct( $client_id, $client_secret, $username, $password, $scopes = array() ) {
      $this->username = $username;
      $this->password = $password;
      parent::__construct( $client_id, $client_secret, $scopes );
    }


    protected function data() {
      return array_merge( parent::data(), array(
        "username" => $this->username,
        "password" => $this->password,
      ) );
    }


  }

}