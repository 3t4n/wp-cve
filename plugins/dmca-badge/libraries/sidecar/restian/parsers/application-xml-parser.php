<?php

class RESTian_Application_Xml_Parser extends RESTian_Parser_Base {
  /**
   * Returns an object or array of stdClass objects from an XML string.
   *
   * @note Leading and trailing space are trim()ed.
   *
   * @see https://www.bookofzeus.com/articles/convert-simplexml-object-into-php-array/
   * @see https://php.net/manual/en/function.simplexml-load-string.php
   * @see https://hakre.wordpress.com/2013/02/12/simplexml-type-cheatsheet/
   *
   * @param string|SimpleXMLElement $body
   * @return array|object A(n array of) stdClass object(s) with structure dictated by the passed XML string.
   */
  function parse( $body ) {
   
    try{

    
    $error_path = plugin_dir_url(__FILE__) ;
   


    if ( empty( $body ) )
      return array();

    $is_array = false;
    $xml = is_string( $body ) ? new SimpleXMLElement( $body ) : $body;

    $data = (array)$xml->attributes();
    if ( 0 == count( $data ) )
      $data['@attributes'] = array();

    /**
     * @var SimpleXMLElement $element
     */
    foreach ($xml as $element) {
      $tag = $element->getName();
      $e = get_object_vars( $element );
      if ( ! empty( $e ) ) {
        $subset = $element instanceof SimpleXMLElement ? $this->parse( $element ) : $e;
      } else {
        $subset = trim( $element );
      }
      if ( ! isset( $data[$tag] ) ) {
        $data[$tag] = $subset;
      } else {
        if ( is_array( $data[$tag] ) ) {
          $data[$tag][] = $subset;
        } else {
          /**
           * Convert to an an array because we are seeing duplicate tags.
           */
          $data[$tag] = array( $data[$tag], $subset );
          $is_array = true;
        }
      }
    
    return $is_array ? $data : (object)$data;
  
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
