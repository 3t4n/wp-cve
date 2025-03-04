<?php

define( 'RESTIAN_VER', '0.4.1' );
define( 'RESTIAN_DIR', dirname( __FILE__ ) );

require(RESTIAN_DIR . '/core-classes/class-base.php');
require(RESTIAN_DIR . '/core-classes/class-client.php');
require(RESTIAN_DIR . '/core-classes/class-request.php');
require(RESTIAN_DIR . '/core-classes/class-response.php');
require(RESTIAN_DIR . '/core-classes/class-var.php');
require(RESTIAN_DIR . '/core-classes/class-service.php');
require(RESTIAN_DIR . '/core-classes/class-settings.php');

require(RESTIAN_DIR . '/base-classes/class-http-agent.php');
require(RESTIAN_DIR . '/base-classes/class-auth-provider.php');
require(RESTIAN_DIR . '/base-classes/class-parser.php');

/**
 *
 */
class RESTian {
  protected static $_clients = array();
  protected static $_auth_providers = array();
  protected static $_parsers = array();
  protected static $_http_agents = array();
  protected static $_filters = array();

  /**
   * @param $client_name
   * @param string|array $client If string then the class name of the RESTian client. If array, $args to find it.
   *
   * @notes $client_name can be any of the following format:
   *
   *   'local_short_code' - If all code it local
   *   'github_user_name/github_repo_name' - If code is on GitHub, master branch latest commit (not smart)
   *   'github_user_name/github_repo_name/tag_name' - If code is on GitHub, tagged commit
   *   'repo_host/user_name/repo_name' - If code is on GitHub or BitBucket, master branch latest commit (not smart)
   *   'repo_host/user_name/repo_name/tag_name' - If code is on GitHub or BitBucket, tagged commit
   *
   *  'repo_host' => 'github.com' or 'bitbucket.org'
   *
   */
  static function register_client( $client_name, $client ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      self::$_clients[$client_name] = $client;
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
   * @param $client_name
   */
  static function get_new_client( $client_name ) {
  }

  /**
   * Registers a result parser class based on the mime type.
   *
   * @see: https://www.iana.org/assignments/media-types/index.html
   *
   * @param string $content_type valid mime type of RESTian shortcut (xml,json,html,plain,csv)
   * @param bool|string $class_name Name of class that defines this Parser
   * @param bool|string $filepath Full local file path for the file containing the class.
   */
  static function register_parser( $content_type, $class_name = false, $filepath = false ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $content_type = self::expand_content_type( $content_type );
      /**
       * Hardcode the predefined parser types this way because it appears this is most performant approach
       * and most efficient use of memory vs. pre-registering them.
       * Predefined types ignore class_name and filepath.
       */
      $internal = true;
      switch ( $content_type ) {
        case 'application/xml':
          $parser = array(
            'class_name'=> 'RESTian_Application_Xml_Parser',
            'filepath'   => RESTIAN_DIR . '/parsers/application-xml-parser.php',
          );
          break;
        case 'application/json':
          $parser = array(
            'class_name'=> 'RESTian_Application_Json_Parser',
            'filepath'   => RESTIAN_DIR . '/parsers/application-json-parser.php',
          );
          break;
        case 'text/plain':
          $parser = array(
            'class_name'=> 'RESTian_Text_Plain_Parser',
            'filepath'   => RESTIAN_DIR . '/parsers/text-plain-parser.php',
          );
          break;
        case 'text/html':
          $parser = array(
            'class_name'=> 'RESTian_Text_Html_Parser',
            'filepath'   => RESTIAN_DIR . '/parsers/text-html-parser.php',
          );
          break;
        case 'text/csv':
          $parser = array(
            'class_name'=> 'RESTian_Text_Csv_Parser',
            'filepath'   => RESTIAN_DIR . '/parsers/text-csv-parser.php',
          );
          break;
        case 'application/vnd.php.serialized':
          $parser = array(
            'class_name'=> 'RESTian_Application_Serialized_Php_Parser',
            'filepath'   => RESTIAN_DIR . '/parsers/application-serialized-php.php',
          );
          break;

        default:
          $internal = false;
          /**
           * Or if an externally defined auth parser, do this:
           */
          $parser = array(
            'class_name'=> $class_name,
            'filepath'   => $filepath,
          );
          break;
      }
      if ( $internal )
        if ( $class_name ) {
          $parser['class_name'] = $class_name;

        if ( $filepath )
          $parser['filepath'] = $filepath;
      }
      $parser['content_type'] = $content_type;
      self::$_parsers[$content_type] = $parser;
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
   * Constructs a new Parser instance
   *
   * @param string $content_type
   * @param RESTian_Request $request
   * @param RESTian_Response $response
   * @param array $args
   * @return RESTian_Parser
   */
  static function get_new_parser( $content_type, $request, $response, $args = array() ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      if ( ! isset( self::$_parsers[$content_type] ) ) {
        self::register_parser( $content_type );
      }
      $parser = self::$_parsers[$content_type];
      if ( isset( $parser['class_name'] ) && isset( $parser['filepath'] ) && file_exists( $parser['filepath'] ) ) {
        require_once( $parser['filepath'] );
        $class_name = $parser['class_name'];
      }
      if ( isset( $class_name ) && class_exists( $class_name ) ) {
        $parser = new $class_name( $request, $response );
      } else {
        $response->set_error( 'NO_PARSER', sprintf( 'There is no parser registered for content type %s.', $content_type ) );
        $parser = false;
      }
      return $parser;
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
   * Registers an Auth Provider type
   *
   * @param string $provider_type RESTian-specific type of Auth Provider
   * @param bool|string $class_name Name of class that defines this Auth Provider
   * @param bool|string $filepath Full local file path for the file containing the class.
   */
  static function register_auth_provider( $provider_type, $class_name = false, $filepath = false ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      /**
       * Hardcode the predefined provider types this way because it appears this is most performant approach
       * and most efficient use of memory vs. pre-registering them.
       * Predefined types ignore class_name and filepath.
       */
      $internal = true;
      switch ( $provider_type ) {
        case 'n/a':
          $provider = array(
            'class_name'=> 'RESTian_Not_Applicable_Provider',
            'filepath'   => RESTIAN_DIR . '/auth-providers/not-applicable-auth-provider.php',
          );
          break;
        case 'basic_http':
          $provider = array(
            'class_name'=> 'RESTian_Basic_Http_Auth_Provider',
            'filepath'   => RESTIAN_DIR . '/auth-providers/basic-http-auth-provider.php',
          );
          break;
        default:
          $internal = false;
          /**
           * Or if an externally defined auth provider, do this:
           */
          $provider = array(
            'class_name'=> $class_name,
            'filepath'   => $filepath,
          );
          break;
      }
      if ( $internal ) {
        if ( $class_name )
          $provider['class_name'] = $class_name;

        if ( $filepath )
          $provider['filepath'] = $filepath;
      }
      $provider['provider_type'] = $provider_type;
      self::$_auth_providers[$provider_type] = $provider;
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
   * Constructs a new Auth Provider instance
   *
   * @param string $auth_type RESTian-specific type of auth providers
   * @param bool|RESTian_Client $api - The API that's dping the calling
   * @return RESTian_Auth_Provider_Base
   */
  static function get_new_auth_provider( $auth_type, $api = false ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $provider = false;
      if ( ! isset( self::$_auth_providers[$auth_type] ) ) {
        /**
         * Try to register a provider for this auth type.
         */
        self::register_auth_provider( $auth_type );
        $provider = self::$_auth_providers[$auth_type];
        require_once( $provider['filepath'] );
        $class_name = $provider['class_name'];
      } else if ( ! isset( self::$_auth_providers[$auth_type]['instance'] ) ) {
        $provider = self::$_auth_providers[$auth_type];
        $class_name = $provider['class_name'];
        if ( ! class_exists( $class_name ) )
          require_once( $provider['filepath'] );
      } else {
        $provider = self::$_auth_providers[$auth_type]['instance'];
        /**
         * Resetting $api in case it is different instance than the one that was used before.
         */
        $provider->api = $api;
      }
      if ( isset( $class_name ) ) {
        $provider = new $class_name( $api );
        $provider->auth_type = $auth_type;
        self::$_auth_providers[$auth_type]['instance'] = $provider;
      }
      return $provider;
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
   * Registers an HTTP Agent type
   *
   * @param string $agent_type RESTian-specific type of HTTP agent
   * @param bool|string $class_name Name of class that defines this HTTP agent
   * @param bool|string $filepath Full local file path for the file containing the class.
   */
  static function register_http_agent( $agent_type, $class_name = false, $filepath = false ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      /**
       * Hardcode the predefined agent types this way because it appears this is most performant approach
       * and most efficient use of memory vs. pre-registering them.
       * Predefined types ignore class_name and filepath.
       */
      $internal = true;
      switch ( $agent_type ) {
        case 'wordpress':
          $agent = array(
            'class_name'=> 'RESTian_WordPress_Http_Agent',
            'filepath'   => RESTIAN_DIR . '/http-agents/wordpress-http-agent.php',
          );
          break;
        case 'php_curl':
          $agent = array(
            'class_name'=> 'RESTian_Php_Curl_Http_Agent',
            'filepath'   => RESTIAN_DIR . '/http-agents/php-curl-http-agent.php',
          );
          break;
        default:
          $internal = false;
          /**
           * Or if an externally defined http agent, do this:
           */
          $agent = array(
            'class_name'=> $class_name,
            'filepath'   => $filepath,
          );
          break;
      }
      if ( $internal ) {
        if ( $class_name )
          $agent['class_name'] = $class_name;

        if ( $filepath )
          $agent['filepath'] = $filepath;
      }
      $agent['agent_type'] = $agent_type;
      self::$_http_agents[$agent_type] = $agent;
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
   * Constructs a new HTTP Agent instance
   *
   * @param string $agent_type RESTian-specific type of HTTP agent
   * @return RESTian_Http_Agent_Base
   */
  static function get_new_http_agent( $agent_type ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      if ( isset( self::$_http_agents[$agent_type] ) ) {
        $class_name = self::$_http_agents[$agent_type]['class_name'];
      } else {
        self::register_http_agent( $agent_type );
        $agent = self::$_http_agents[$agent_type];
        require_once( $agent['filepath'] );
        $class_name = $agent['class_name'];
      }
      return new $class_name( $agent_type );
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
   * Expands the following shortcut content types to their valid mime type:
   *
   * @see: https://www.iana.org/assignments/media-types/index.html
   *
   *   xml    => application/xml
   *   json   => application/json
   *   form   => application/x-www-form-urlencoded
   *   html   => text/html
   *   plain  => text/plain
   *   csv    => text/csv
   *
   * @param string $content_type
   * @return string
   */
  static function expand_content_type( $content_type ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      if ( false !== strpos( 'jx', $content_type[0] ) )  {
        $content_type = preg_replace( '#^(json|xml)$#', 'application/$1', $content_type );
      } else if ( false !== strpos( 'htc', $content_type[0] ) ) {
        $content_type = preg_replace( '#^(html|text|csv)$#', 'text/$1', $content_type );
      } else if ( 'form' == $content_type ) {
        $content_type = 'application/x-www-form-urlencoded';
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

  /**
   * Parses a string of arguments using a data format optimized for the use-case.
   *
   * The data format is similar to URL query string format but it uses vertical bars ('|') as seperators
   * instead of ampersands ('&') because ampersands are frequently used in URLs. Names without no equals
   * sign ('=') following are set to boolean true. Names prefixed with exclamation point ('!') will negate
   * the value they would otherwise have, so a name with an exclamation point and no equals sign will
   * be set to boolean false which is the primary use case for the ('!') syntax.
   *
   * @example 'foo|!bar|baz=zoom' parses to
   *
   *     array(
   *       'foo' => true,
   *       'bar' => false,
   *       'baz' => 'zoom',
   *    )
   *
   * @param $args
   * @return array
   */
  static function parse_args( $args ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      if ( is_string( $args ) ) {
        $args = explode( '|', $args );
        $new_args = array();
        foreach( $args as $arg ) {
          list( $name, $value ) = array_map( 'trim', explode( '=', "{$arg}=" ) );
          if ( 0 == strlen( $value ) ) {
            $value = true;
          } else if ( preg_match( '#^(true|false)$#', $value ) ) {
            $value = 't' == $value[0];
          }
          if ( '!' ==$name[0] ) {
            /**
             * If $name begins with '!' then we want to NOT it's value.
             * If no values was passed (i.e. "!omit_body" was the value) then
             * then that will set 'omit_body' => true which was the goal of
             * adding this syntax sugar.
             */
            $name = substr( $name, 1 );
            $value = ! $value;
          }
          $new_args[$name] = $value;
        }
        $args = $new_args;
      }
      return $args;
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
   * Takes a like this 'color,type=all,size' and translates into an array that looks like this:
   *
   *     array(
   *       'color' => true,
   *       'type'  => 'all'
   *       'size'  => true
   *     );
   *
   * @param string $string
   * @param string $separator
   * @return array
   */
  static function parse_string( $string, $separator = ',' ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $array = array_fill_keys( explode( $separator, $string ), true );
      foreach( $array as $name => $value ) {
        unset( $array[$name] );
        if ( preg_match( '#=#', $name ) ) {
          list( $name, $value ) = explode( '=', $name );
        }
        $array[$name] = $value;
      }
      return $array;
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
     * Parses comma seperated transforms like this:
     *
     *     fill[/],replace[a][b],trim
     *
     * To this:
     *
     *    array(
     *      'fill' => '/'
     *       'replace' => array( 'a','b' ),
     *       'trim' => true,
     *     )
     *
     * @param string $string
     * @param string $separator
     * @return array
     */
  static function parse_transforms( $string, $separator = ',' ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $transforms = RESTian::parse_string( $string, $separator );
      $new_transforms = array();
      foreach( array_keys( $transforms ) as $name ) {
        if ( ! preg_match( '#\[#', $name ) ) {
          $new_transforms[$name] = true;
        } else {
          $data = explode( '[', $name );
          $name = array_shift( $data );
          foreach( $data as $index => $value ) {
            $data[$index] = trim( $value, '][' );
          }
          $new_transforms[$name] = 1 == count( $data ) ? $data[0] : $data;
        }
      }
      return $new_transforms;
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
   * @param array $args
   * @param array $shortnames
   * @return array
   */
  static function expand_shortnames( $args, $shortnames = array() ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      foreach( $args as $property_name => $value ) {
        if ( isset( $shortnames[$property_name] ) ) {
          $args[$shortnames[$property_name]] = $value;
          unset( $args[$property_name] );
        }
      }
      return $args;
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
   * Adds a filter hook for an object
   *
   * 	RESTian::add_filter( 'filter_data', array( $this, 'filter_data' ) );
   * 	RESTian::add_filter( 'filter_data', array( $this, 'filter_data' ), 11 );
   * 	RESTian::add_filter( 'filter_data', 'special_func' );
   * 	RESTian::add_filter( 'filter_data', 'special_func', 11 );
   * 	RESTian::add_filter( 'filter_data', array( __CLASS__, 'filter_data' ) );
   * 	RESTian::add_filter( 'filter_data', array( __CLASS__, 'filter_data' ), 11 );
   * 	RESTian::add_filter( 'filter_data', array( new SpecialClass(), 'filter_data' ) );
   * 	RESTian::add_filter( 'filter_data', array( new SpecialClass(), 'filter_data' ), 11 );
   *
   * @param string $filter_name
   * @param array|string $callable
   * @param int $priority
   *
   * @return mixed
   */
  static function add_filter( $filter_name, $callable, $priority = 10 ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      if ( is_string( $callable ) ) {
        $function = "{$callable}()";
      } else if ( is_array( $callable ) ) {
        if ( is_string( $callable[0] ) ) {
          $function = "{$callable[0]}::{$callable[1]}()";
        } else if ( is_object( $callable[0] ) ) {
          $object_hash = spl_object_hash( $callable[0] );
          $function = "{$object_hash}->{$callable[1]}()";
        }
      }
      self::$_filters[$filter_name][$function][$priority][] = $callable;
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
   * Adds a filter hook for an object
   *
   * 	RESTian::add_action( 'process_action', array( $this, 'process_action' ) );
   * 	RESTian::add_action( 'process_action', array( $this, 'process_action' ), 11 );
   * 	RESTian::add_action( 'process_action', 'special_func' );
   * 	RESTian::add_action( 'process_action', 'special_func', 11 );
   * 	RESTian::add_action( 'process_action', array( __CLASS__, 'process_action' ) );
   * 	RESTian::add_action( 'process_action', array( __CLASS__, 'process_action' ), 11 );
   * 	RESTian::add_action( 'process_action', array( new SpecialClass(), 'process_action' ) );
   * 	RESTian::add_action( 'process_action', array( new SpecialClass(), 'process_action' ), 11 );
   *
   * @param string $action_name
   * @param array|string $callable
   * @param int $priority
   *
   * @return mixed
   */
  static function add_action( $action_name, $callable, $priority = 10 ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      self::add_filter( $action_name, $callable, $priority );
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
   * Return a list of filters
   *
   * @param string $filter_name
   *
   * @return mixed
   */
  static function get_filters( $filter_name ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return isset( self::$_filters[$filter_name] ) ? self::$_filters[$filter_name] : false;
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
