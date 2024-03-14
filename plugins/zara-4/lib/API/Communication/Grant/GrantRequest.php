<?php
if ( ! class_exists( 'Zara4_API_Communication_Grant_GrantRequest' ) ) {


  /**
   * Class Zara4_API_Communication_Grant_GrantRequest
   */
  abstract class Zara4_API_Communication_Grant_GrantRequest {

    protected $grantType;
    protected $scopes;
    protected $client_id;
    protected $client_secret;


    public function __construct( $client_id, $client_secret, $scopes = array() ) {
      $this->client_id = $client_id;
      $this->client_secret = $client_secret;
      $this->scopes = $scopes;
    }


    /**
     * @return array
     */
    public function getTokens() {
      return Zara4_API_Communication_Util::post(
        Zara4_API_Communication_Util::url( "/oauth/access_token" ),
        array( "body" => $this->data() )
      );
    }



    protected function data() {
      return array(
        "grant_type"    => $this->grantType,
        "client_id"     => $this->client_id,
        "client_secret" => $this->client_secret,
        "scope"         => implode( ",", array_unique( $this->scopes ) ),
      );
    }



    /**
     * Add image processing to the request scope.
     *
     * @return $this
     */
    public function withImageProcessing() {
      array_push( $this->scopes, "image-processing" );
      return $this;
    }


    /**
     * Add usage to the request scope.
     *
     * @return $this
     */
    public function withUsage() {
      array_push( $this->scopes, "usage" );
      return $this;
    }

  }

}