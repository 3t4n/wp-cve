<?php

class DMCA_Auth_Provider extends RESTian_Auth_Provider_Base {

  /**
   * @param RESTian_Response $response
   *
   * @return bool
   */
  function authenticated( $response ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      

      $account_id = 0;
      $xml = null;
      $error = null;

      if ( 500 === $response->status_code ) {
        $error = json_decode($response->body);
        $response->set_error('500', $error->Message);
      } else {
        try {
          $xml = new SimpleXMLElement( $response->body );
        } catch (Exception $e) {
          $response->set_error('parsing', $e->getMessage());
        }

        if ( !$response->has_error() ) {
          if ( isset( $xml->a ) ) {
            $a = $xml->a[0];
            $attrs = $a->attributes();
            $href = (string) $attrs->href;
            $params = array();
            $query = parse_url( $href, PHP_URL_QUERY );
            parse_str( $query, $params );
            if ( isset( $params[ 'ID' ] ) ) {
              $account_id = $params[ 'ID' ];
            }
          }
        }
      }

      return $account_id;
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

  function capture_grant( $response ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $new_grant = $this->get_new_grant();
      $new_grant[ 'AccountID' ] = $response->authenticated;
      $response->grant = $new_grant;
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
  function get_new_credentials() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      return array(
        'email' => '',
        'password' => '',
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
   * @return array
   */
  function get_new_grant() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      // TODO: change authenticated to AccountID
      return array(
        'AccountID' => '',
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
      
      return ! empty( $credentials['email'] ) && ( ! empty( $credentials['password'] ) );
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
      
      return isset( $grant[ 'AccountID' ] ) && !empty( $grant[ 'AccountID' ] );
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