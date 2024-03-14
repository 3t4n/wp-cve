<?php

/**
 * A RESTian_Http_Agent represents the internal type of agent that calls HTTP:
 *
 *   'wordpress' for WordPress wp_remote_get()
 *   'php_curl' for PHP's CURL
 *
 * Having more than one agent allows RESTian to be used outside WordPress.
 *
 */
abstract class RESTian_Http_Agent_Base {
  var $agent_type;
  var $error_num = false;
  var $error_msg = false;

  /**
   * @param $agent_type
   */
  function __construct( $agent_type ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {

      $this->agent_type = $agent_type;
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
   * Provides a generic HTTP GET method that can call WordPress' wp_remove_get() if available, or CURL if not.
   *
   * @param RESTian_Request $request
   * @param RESTian_Response $response
   * @return RESTian_Response
   * @throws Exception
   */
  function make_request( $request, $response ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {

      if (1) // Only here so PhpStorm won't flag as an error
        throw new Exception( 'Class ' . get_class($this) . ' [subclass of ' . __CLASS__ . '] must define $this->make_request().' );
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
}