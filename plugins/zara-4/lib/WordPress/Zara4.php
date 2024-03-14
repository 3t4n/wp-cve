<?php
if ( ! class_exists( 'Zara4_WordPress_Zara4' ) ) {


  /**
   * Class Zara4_WordPress_Zara4
   */
  class Zara4_WordPress_Zara4 {

    /**
     * Generate an API access_token from the given api credentials.
     *
     * @param string $client_id
     * @param string $client_secret
     *
     * @throws Zara4_API_Communication_AccessDeniedException
     * @return string
     */
    public static function generate_access_token( $client_id, $client_secret ) {

      $url = Zara4_API_Communication_Util::url( '/oauth/access_token' );

      $fields = array(
        'client_id'     => urlencode( $client_id ),
        'client_secret' => urlencode( $client_secret ),
        'grant_type'    => urlencode( 'client_credentials' ),
        'scope'         => 'image-processing,usage',
      );

      $result = json_decode( Zara4_API_Communication_Util::post( $url, $fields ) );

      if ( isset( $result->{'error'} ) ) {
        throw new Zara4_API_Communication_AccessDeniedException( $result->{'error_description'} );
      }

      return $result->{'access_token'};
    }


    /**
     * Generate an API access_token from the given settings.
     *
     * @param Zara4_WordPress_Settings $settings
     * @return string
     */
    public static function generate_access_token_using_settings( $settings ) {

      $client_id = $settings->api_client_id();
      $client_secret = $settings->api_client_secret();

      if ( ! $client_id || ! $client_secret ) {
        return null;
      }

      return self::generate_access_token( $client_id, $client_secret );
    }

  }

}