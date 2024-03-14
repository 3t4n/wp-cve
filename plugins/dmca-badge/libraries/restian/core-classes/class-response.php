<?php

/**
 * A RESTian_Response represents the return values from an HTTP request
 */
class RESTian_Response {

  /**
   * @var int
   */
  var $status_code = 200;

  /**
   * @var bool
   */
  var $message = false;

  /**
   * @var bool|array False if no error, of code (string) and message that external clients can use to branch
   * appropriatly after a call and/or to provide error messages for their users.
   *
   * Provides a dependable format error code for clients to take action on when using RESTian.
   *
   * 'code' => High level error codes for the caller.
   *
   *  'NO_AUTH' - No username and password passed
   *  'BAD_AUTH' - Username and password combination rejected by Revostock
   *  'API_FAIL' - Problem communicating with the API
   *  'NO_BODY' - No response body returned when one was expected.
   *  'BAD_SYNTAX' - The response body contains malformed XML, JSON, etc.
   *  'UNKNOWN' - Unexpected HTTP response code
   *
   * 'message' => Human readable to explain the $error_code.
   */
  private $_error = false;

  /**
   * @var object|array A structured version of the body, is applicable.
   */
  var $data;
  /**
   * @var bool
   */
  var $body = false;
  /**
   * @var object
   */
  /**
   * @var RESTian_Http_Agent_Base Encapsules the specifics for the HTTP agent: PHP's curl ('php_curl') or WordPress' ('wordpress').
   * Contains raw returned data (data) and error results (error_num and error_msg) when an error occurs.
   */
  var $http_error = false;
  /**
   * @var mixed
   */
  var $result;
  /**
   * @var RESTian_Request
   */
  var $request;
  /**
   * @var bool
   */
  var $authenticated = false;
  /**
   * @var array
   */
  var $grant = array();

  /**
   * @param array $args
   */
  function __construct( $args = array() ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      /**
       * Copy properties in from $args, if they exist.
       */
      foreach( $args as $property => $value )
        if ( property_exists(  $this, $property ) )
          $this->$property = $value;
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
   * @param $number
   * @param $message
   */
  function set_http_error( $number, $message ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $this->http_error = array( $number, $message );
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
   * @return bool|RESTian_Http_Agent_Base
   */
  function is_http_error() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return $this->http_error;
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
   * @return bool|object
   */
  function get_error() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return $this->_error;
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
   * @return bool|object
   */
  function has_error() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return is_object( $this->_error );
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
   * @return bool|object
   */
  function is_error() {
      
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      return is_object( $this->_error );
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
   * @param $code
   * @param bool|string|RESTian_Service $message
   */
  function set_error( $code, $message = false ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      if ( false === $message )
        $message = $code;
      $this->_error = (object)array(
        'code' => $code,
        'message' => ! is_object( $message ) ? $message : $message->get_error_message( $code ),
      );
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