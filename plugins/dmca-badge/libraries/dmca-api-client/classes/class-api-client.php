<?php
if ( ! class_exists( 'DMCA_API_Client' ) ) {
  /**
   * Class DMCA_API_Client
   *
   * @author Mike Schinkel <mike@newclarity.net>
   * @license GPLv2
   *
   * @requires RESTian v4.0+
   * @see https://github.com/newclarity/restian/
   *
   */
  class DMCA_API_Client extends RESTian_Client {
      /**
       *
       */
    function initialize() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        $error_path = plugin_dir_url(__FILE__) ;
        try { 
        $this->add_filter( 'result_body' );
        $this->add_action( 'prepare_request' );

        $this->api_name = 'DMCA.com API';
        $this->base_url = 'https://www.dmca.com/rest';
        $this->api_version = '1.0.0';
        $this->use_cache = false;

        RESTian::register_auth_provider( 'dmca_auth', 'DMCA_Auth_Provider', dirname( __FILE__ ) . '/class-auth-provider.php' );
        $this->auth_type = 'dmca_auth';

        $this->register_service_defaults( array(
          'content_type'    => 'application/xml',
          'not_vars'        => array(),
        ));

        $this->register_var( 'AccountID',   'type=string' );

        $this->register_resource( 'anonymous_badges',           'path=/GetAnonymousBadges|auth=false|request_settings=' );
        $this->register_resource( 'registered_badges',          'path=/GetRegisteredBadges|AccountID=' );
        $this->register_resource( 'watermarker_tokens',         'path=/GetWaterMarkerTokens' );
        $this->register_resource( 'watermarker_token',          'path=/GetWaterMarkerToken' );
        $this->register_resource( 'watermarker_pro_token',      'path=/GetWaterMarkerProToken' );

        $this->register_settings( 'post_json', 'method=POST|content_type=json|charset=utf-8' );

        $this->register_service_defaults( array(
          'request_settings'=> 'post_json'
        ));

        $this->register_action( 'authenticate',                 'path=/GetAuthenticatedBadges|!has_body' );
        $this->register_action( 'register',                     'path=/RegisterNewAccount|auth=false' );
        $this->register_action( 'create_watermarker_token',     'path=/CreateWaterMarkerToken' );
        $this->register_action( 'update_watermarker_token',     'path=/UpdateWaterMarkerToken' );
        $this->register_action( 'delete_watermarker_token',     'path=/DeleteWaterMarkerToken' );
        $this->register_action( 'create_watermarker_pro_token', 'path=/CreateWaterMarkerProToken' );
        $this->register_action( 'update_watermarker_pro_token', 'path=/UpdateWaterMarkerProToken' );
        $this->register_action( 'delete_watermarker_pro_token', 'path=/DeleteWaterMarkerProToken' );
        $this->register_action( 'reset_watermarker_token',      'path=/ResetWaterMarkerToken' );
      }

      catch (Exception $e) 
          {  
            echo 'Exception Message: ' .$e->getMessage();  
            if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
            } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
            }
            echo "<br> $error_path";
          }  
          catch (ErrorException  $er)
          {  
            echo 'ErrorException Message: ' .$er->getMessage();  
            echo "<br> $error_path";
          }  
          catch ( Throwable $th){
            echo 'ErrorException Message: ' .$th->getMessage();
            echo "<br> $error_path";
          }
        }
        catch (Exception $e) 
        {  
          echo 'Exception Message: ' .$e->getMessage();  
          if ($e->getSeverity() === E_ERROR) {
              echo("E_ERROR triggered.\n");
          } else if ($e->getSeverity() === E_WARNING) {
              echo("E_WARNING triggered.\n");
          }
          echo "<br> $error_path";
        }  
        catch (ErrorException  $er)
        {  
          echo 'ErrorException Message: ' .$er->getMessage();  
          echo "<br> $error_path";
        }  
        catch ( Throwable $th){
          echo 'ErrorException Message: ' .$th->getMessage();
          echo "<br> $error_path";
        }
    }

    function register( $args ) {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        $email = $this->sanitize_email( $args['email'] );
        /**
         * @var RESTian_Http_Agent_Base $http_agent
         */
        if ( ! $this->validate_email( $email ) ) {
          $response = new RESTian_Response( array(
            'authenticated' => false,
          ));
          $response->set_http_error( '400', 'Bad Request' );
          $response->set_error( 'EMAIL_INVALID', "The value entered [{$email}] not a valid email address." );
        } else {
          $fields = explode( '|', 'first_name|last_name|company_name|email' );
          $args = array_merge( array_fill_keys( $fields, false ), $args );
          $response = $this->invoke_action( 'register', array(
            'FirstName'   => $this->sanitize_string( $args['first_name'] ),
            'LastName'    => $this->sanitize_string( $args['last_name'] ),
            'CompanyName' => $this->sanitize_string( $args['company_name'] ),
            'Email'       => $email,
          ));
          if ( preg_match('#>ERROR: Account already exists<#', $response->body ) )
            $response->set_error( 'CREDENTIALS_EXIST', "An account already exists for email {$email}." );
        }
        return $response;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }

    function _result_body( $body, $response ) {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        $body = str_replace( array( '&#xD;', '&#xA;', '&lt;', '&gt;' ), array( '', '', '<', '>' ), $body );
        return $body;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }

    /**
     * @param RESTian_Request $request
     *
     * This should not be needed once we update RESTian to have the ability to "pass" vars in the request body.
     */
    function _prepare_request( $request ) {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        switch ( $request->service->service_name ) {
          case 'authenticate':
            $credentials = $request->get_credentials();
            $request->body = json_encode( array(
              'Email'     => $this->sanitize_email( $credentials['email'] ),
              'Password'  => $credentials['password'],
            ));
            break;
        }
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }

    function get_registered_badges_urls( $args = array() ) {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        $badges = false;
        $data = parent::get_registered_badges( $args );
        return $this->_get_badge_urls( $data );
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }

    /**
     * Returns a list of badge URLs as an array.
     *
     * @return bool|array
     */
    function get_anonymous_badges_urls() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        $badges = false;
        $data = parent::get_anonymous_badges();
        return $this->_get_badge_urls( $data );
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }

    function _get_badge_urls( $data ) {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        $badges = false;
        if ( $data && isset( $data['a'] ) && is_array( $data['a'] ) ) {
          $badges = array();
          foreach( $data['a'] as $badge ) {
            if ( isset( $badge->img->{'@attributes'}['src'] ) ) {
              $url = $badge->img->{'@attributes'}['src'];
              $query = parse_url( $url, PHP_URL_QUERY );
              $badges[] = str_replace( "?$query", "", $url );
            }
          }
        }
        return $badges;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }

    function register_new_account() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        return false;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }
    function create_watermarker_token() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        return false;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }
    function create_watermarker_pro_token() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        return false;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }
    function get_watermarker_token() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        return false;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }
    function get_watermarker_tokens() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        return false;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }
    function delete_watermarker_token() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        return false;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }
    function update_watermarker_token() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        return false;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }
    function update_watermarker_pro_token() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        return false;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }
    function reset_watermarker_token() {
      $error_path = plugin_dir_url(__FILE__) ;
      try {
        
        return false;
      }
      catch (Exception $e) 
      {  
        echo 'Exception Message: ' .$e->getMessage();  
        if ($e->getSeverity() === E_ERROR) {
            echo("E_ERROR triggered.\n");
        } else if ($e->getSeverity() === E_WARNING) {
            echo("E_WARNING triggered.\n");
        }
        echo "<br> $error_path";
      }  
      catch (ErrorException  $er)
      {  
        echo 'ErrorException Message: ' .$er->getMessage();  
        echo "<br> $error_path";
      }  
      catch ( Throwable $th){
        echo 'ErrorException Message: ' .$th->getMessage();
        echo "<br> $error_path";
      }
    }
  }

}
