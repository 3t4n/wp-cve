<?php
if ( ! class_exists( 'Zara4_API_Communication_AccessToken_AccessToken' ) ) {


  /**
   * Class Zara4_API_Communication_AccessToken_AccessToken
   */
  abstract class Zara4_API_Communication_AccessToken_AccessToken {

    protected $client_id;
    protected $client_secret;
    protected $access_token;
    protected $expires_at;


    public function __construct( $client_id, $client_secret, $access_token, $expires_at ) {
      $this->client_id = $client_id;
      $this->client_secret = $client_secret;
      $this->access_token = $access_token;
      $this->expires_at = $expires_at;
    }


    /**
     * Get the token.
     *
     * @return String
     */
    public function token() {
      if ( $this->has_expired() ) {
        $this->refresh();
      }
      return $this->access_token;
    }


    /**
     * Represent this AccessToken as a String.
     *
     * @return String
     */
    public function __toString() {
      return $this->token();
    }


    /**
     * Refresh this AccessToken.
     *
     * @return void
     */
    public abstract function refresh();


    /**
     * Has this AccessToken expired?
     *
     * @return bool
     */
    public function has_expired() {
      return time() > $this->expires_at;
    }


  }

}