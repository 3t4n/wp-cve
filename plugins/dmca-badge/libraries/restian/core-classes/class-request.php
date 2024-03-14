<?php

/**
 * A RESTian_Request represents a specific HTTP request
 */
class RESTian_Request {
  /**
   * @var bool|array
   */
  protected $_credentials = false;
  /**
   * @var bool|array
   */
  protected $_grant = false;
  /**
   * @var array
   */
  protected $_headers = array();
  /**
   * @var RESTian_Service Optional to override the one set in the API, if needed.
   */
  protected $_auth_service = false;
  /**
   * @var RESTian_Client
   */
  var $client;
  /**
   * @var RESTian_Service
   */
  var $service;
  /**
   * @var string
   */
  var $http_method = 'GET';
  /**
   * @var bool Specifies that SSL should not be verified by default.
   * When true it is often too problematic for WordPress plugins.
   */
  var $sslverify = false;
  /**
   * @var RESTian_Response
   */
  var $response;
  /**
   * @var array
   */
  var $vars;
  /**
   * @var string
   */
  var $body;

  /**
   * @TODO These should be moved to RESPONSE, not be in REQUEST.
   */
  var $omit_body = false;
  var $omit_result = false;

  /**
   * @param array $vars
   * @param array $args
   */
  function __construct( $vars = array(), $args = array() ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      if ( is_null( $vars ) )
        $vars = array();

      if ( is_null( $args ) )
        $args = array();
      else if ( is_string( $args ) )
        $args = RESTian::parse_args( $args );

      /**
       * Copy properties in from $args, if they exist.
       */
      foreach( $args as $property => $value )
        if ( property_exists(  $this, $property ) )
          $this->$property = $value;

      /**
       * Do these late $args cannot override them.
       */
      if ( isset( $this->service->client ) ) {
        $this->client = $this->service->client;
      }

      if ( isset( $args['credentials'] ) ) {
        $this->set_credentials( $args['credentials'] );
      }

      if ( isset( $args['grant'] ) ) {
        $this->set_grant( $args['grant'] );
      }

      if ( isset( $args['headers'] ) ) {
        $this->add_headers( $args['headers'] );
      }

      if ( is_array( $vars ) ) {
        $this->vars = $vars;
      } else {
        $this->body = $vars;
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

  /**
   * Check $this->auth to determine if it contains credentials.
   *
   * Does NOT verify if credentials are valid, only that it has them.
   *
   * This class will be extended when we have a proper use-case for extension.
   *
   * @return bool
   */
  function has_credentials() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $auth_provider = $this->client->get_auth_provider();
      return $auth_provider->is_credentials( $this->_credentials );
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
   * @return bool|array
   */
  function get_credentials() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return $this->_credentials;
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
   * @param $credentials
   *
   * @return mixed
   */
  function set_credentials( $credentials ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $this->_credentials = $credentials;
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
   * @return bool|array
   */
  function get_grant() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return $this->_grant;
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
   * @param $grant
   *
   * @return mixed
   */
  function set_grant( $grant ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $this->_grant = $grant;
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
   * @return bool|RESTian_Service
   */
  function get_auth_service() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $this->client->initialize_client();
      if ( ! $this->_auth_service ) {
        $this->_auth_service = $this->client->get_auth_service();
      }
      return $this->_auth_service;
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
   * @param $name
   * @param $value
   */
  function add_header( $name, $value ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $this->_headers[$name] = $value;
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
   * @param array $headers
   */
  function add_headers( $headers = array() ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $this->_headers = array_merge( $this->_headers, $headers );
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
   * @return array
   */
  function get_headers() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return $this->_headers;
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
   *
   */
  function clear_headers() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $this->_headers = array();
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
   * @return bool|string
   */
  function get_body() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $body = false;
      if ( preg_match( '#^(POST|PUT)$#i', $this->http_method ) ) {
        if ( $this->body ) {
          $body = $this->body;
        } else if ( $settings = $this->service->get_request_settings() ) {
          if ( RESTian::expand_content_type( 'form' ) == $settings->content_type ) {
            $body = http_build_query( $this->vars );
          } else if ( count( $this->vars ) && RESTian::expand_content_type( 'json' ) == $settings->content_type ) {
            $body = json_encode( (object)$this->vars );
          }
        } else if ( count( $this->vars ) ) {
          $body = http_build_query( $this->vars );
          $this->vars = null;
        }
      }
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
   * Returns HTTP headers as expected by CURL.
   *
   * Returns numeric indexed array where value contains header name and header value as "{$name}: {$value}"
   *
   * @return array
   */
  function get_curl_headers() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $headers = array();
      foreach( $this->_headers as $name => $value )
        $headers[] = "{$name}: {$value}";
      return $headers;
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
   * @return bool|string
   * @throws Exception
   */
  function get_url() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
        
        $service_url = $this->client->get_service_url( $this->service );
        if ( count( $this->vars ) ) {
          $query_vars = $this->vars;
          foreach( $query_vars as $name => $value ) {
            /**
             * @var array $matches Get all URL path var matches into an array
             */
            preg_match_all( '#([^{]+)\{([^}]+)\}#', $this->service->path, $matches );
            $path_vars = array_flip( $matches[2] );
            if ( isset( $path_vars[$name] ) ) {
              $var = $this->client->get_var( $name );
              $value = $var->apply_transforms( $value );
              $service_url = str_replace( "{{$name}}", $value, $service_url );
              unset( $query_vars[$name] );
            }
          }
          $service_url .= '?' . http_build_query( $query_vars );
        }
        return $service_url;
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
   * @param $vars
   * @param $template
   *
   * @return bool|string
   */
  private function _to_application_form_url_encoded( $vars, $template = '' ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
        $query_vars = $this->vars;
        foreach( $query_vars as $name => $value ) {
          /**
           * @var array $matches Get all URL path var matches into an array
           */
          preg_match_all( '#([^{]+)\{([^}]+)\}#', $this->service->path, $matches );
          $path_vars = array_flip( $matches[2] );
          if ( isset( $path_vars[$name] ) ) {
            $var = $this->client->get_var( $name );
            $value = $var->apply_transforms( $value );
            $template = str_replace( "{{$name}}", $value, $template );
            unset( $query_vars[$name] );
          }
        }
      $result = http_build_query( $query_vars );
      return $result;
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
   * Returns true if RESTian can safely assume that we have authenticated in past with existing credentials.
   *
   * This does NOT mean we ARE authenticated but that we should ASSUME we are and try doing calls without
   * first authenticating. This functionality is defined because the client (often a WordPress plugin) may
   * have captured grant info from a prior page load where this class did authenticate, but this class is not
   * in control of maintaining that grant info so we can only assume it is correct if the client of this code
   * tells us it is by giving us grant info that the auth provider validates. Another use-case where our assumption
   * will fail is if the access_key expires or has since been revoked.
   *
   * @return bool
   */
  function has_grant() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return $this->client->get_auth_provider()->is_grant( $this->_grant );
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
   * Call the API.
   *
   * On success (HTTP status == 200) $this->error_code and $this->error_msg will be false.
   * On failure (HTTP status != 200) $this->error_code and $this->error_msg will contain error information.
   *
   * On success or failure,  $this->response will contain the response captured by HTTP agent
   * except when username and password are not passed as part of auth.
   *
   * @return object|RESTian_Response
   */
  function make_request() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $response = new RESTian_Response( array( 'request' => $this ) );
      $api = $this->client;
      /**
       * Assign request & response to API so they are accessible inside the auth_provider
       */
      $api->request = $this;
      $api->response = $response;
      $auth_provider = $api->get_auth_provider();
      if ( $this->needs_authentication() && ! $this->has_authentication() ) {
        $response->set_error( 'NO_AUTH', $this->service );
      } else {
        $http_agent = RESTian::get_new_http_agent( $this->client->http_agent );
        $this->assign_settings();
        $response = $http_agent->make_request( $this, $response );
        if ( $response->is_http_error() ) {
          /**
           * See if we can provide more than one error type here.
           */
          $msg = 'There was a problem reaching %s when calling the %s. Please try again later or contact the site\'s administrator.';
          $response->set_error( 'API_FAIL', sprintf( $msg, $this->client->api_name, $this->service->service_name ) );
        } else {
          if ( 'authenticate' == $response->request->service->service_name ) {
            $handled = $auth_provider->authenticated( $response );
          } else {
            $handled = $auth_provider->handle_response( $response );
          }
          if ( ! $handled ) {
            // @todo Add more HTTP status code responses as we better understand the use-cases.
            switch ( $response->status_code ) {
              case '200':
                /**
                 * @var RESTian_Parser_Base $parser
                 */
                $parser = RESTian::get_new_parser( $this->service->content_type, $this, $response );
                if ( $parser instanceof RESTian_Parser_Base )
                  $response->data = $parser->parse( $response->body );
                break;
              case '401':
                $response->set_error( 'BAD_AUTH', $this->service );
                break;
              default:
                /**
                 * See if we can provide more than one error type here.
                 */
                $response->set_error( 'UNKNOWN', 'Unexpected API response code: ' . $response->status_code );
                break;
            }
          }
          if ( $this->omit_body )
            $response->body = null;
          if ( $this->omit_result )
            $response->result = null;
        }
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

  /**
   * @return bool
   */
  function needs_authentication() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return $this->service != $this->get_auth_service() && $this->service->needs_authentication;
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
   * @return bool
   */
  function has_authentication() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $auth_provider = $this->client->get_auth_provider();
      return $auth_provider ? $auth_provider->is_grant( $this->get_grant() ) : false;
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
   * @return bool|RESTian_Settings
   */
  function get_settings() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return $this->service->get_request_settings();
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
    * @return bool
    */
  function get_content_type() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $content_type = false;
      if ( $settings = $this->get_settings() ) {
        if ( $settings->content_type )
          $content_type = $settings->content_type;
        if ( $content_type && $settings->charset )
          $content_type .= "; charset={$settings->charset}";
      }
      return $content_type;
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
  
  function assign_settings() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      if ( $settings = $this->service->get_request_settings() ) {

        if ( $settings->http_method )
          $this->http_method = $settings->http_method;

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
}