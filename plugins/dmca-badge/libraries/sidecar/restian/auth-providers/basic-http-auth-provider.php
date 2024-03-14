<?php

class RESTian_Basic_Http_Auth_Provider extends RESTian_Auth_Provider_Base {

  /**
   * @return array
   */
  function get_new_credentials() {

      return array(
        'username' => '',
        'password' => '',
      );
  }

  /**
   * @return array
   */
  function get_new_grant() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return array(
        'authenticated' => false,
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

  /**
   * @param array $credentials
   * @return bool
   */
  function is_credentials( $credentials ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return ! empty( $credentials['username'] ) && ( ! empty( $credentials['password'] ) );
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
   * @param array $grant
   * @return bool
   */
  function is_grant( $grant ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return ! empty( $grant['authenticated'] );
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
   */
  function prepare_request( $request ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      $credentials = $request->get_credentials();
      $auth = base64_encode( "{$credentials['username']}:{$credentials['password']}" );
      $request->add_header( 'Authorization', "Basic {$auth}" );
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
