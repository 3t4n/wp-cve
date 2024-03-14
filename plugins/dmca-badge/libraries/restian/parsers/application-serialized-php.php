<?php

/**
 * TODO: Need to test this one first.
 */
class RESTian_Application_Serialized_Php_Parser extends RESTian_Parser_Base {
  /**
   * Returns an object or array of stdClass objects from a string containing valid Serialized PHP
   *
   * @param string $body
   * @return array|object|void A(n array of) stdClass object(s) with structure dictated by the passed Serialized PHP string.
   */
  function parse( $body ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {

      return unserialize( $body );
    
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
