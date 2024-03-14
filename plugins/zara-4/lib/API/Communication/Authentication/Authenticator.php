<?php
if ( ! class_exists( 'Zara4_API_Communication_Authentication_Authenticator' ) ) {


  /**
   * Class Zara4_API_Communication_Authentication_Authenticator
   */
  abstract class Zara4_API_Communication_Authentication_Authenticator {

    protected $client_id;
    protected $client_secret;
    protected $scopes = array();


    public function __construct( $client_id, $client_secret ) {
      $this->client_id = $client_id;
      $this->client_secret = $client_secret;
    }


    /**
     * Get an AccessToken for use when communicating with the Zara 4 API service.
     *
     * @return Zara4_API_Communication_AccessToken_AccessToken
     */
    public abstract function acquire_access_token();



    /**
     * Add image processing to the Authenticator scope.
     *
     * @return $this
     */
    public function with_image_processing() {
      array_push( $this->scopes, "image-processing" );
      return $this;
    }


    /**
     * Add usage to the Authenticator scope.
     *
     * @return $this
     */
    public function with_usage() {
      array_push( $this->scopes, "usage" );
      return $this;
    }


  }

}