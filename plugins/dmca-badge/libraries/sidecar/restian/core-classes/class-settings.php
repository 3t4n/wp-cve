<?php

class RESTian_Settings {

  /**
   * @var string
   */
  var $settings_name;

  /**
   * @var string HTTP Method
   */
  var $http_method;

  /**
   * @var string Content Type used on the Body of a POST or PUT
   */
  var $content_type;

  /**
   * @var string character set used
   */
  var $charset;

  function __construct( $settings_name, $args = array() ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {


      $this->modifier_name = $settings_name;

      if ( ! is_object( $args ) && ! is_array( $args ) ) {
        throw new Exception( 'Must pass a string, array or object for $args when creating a new ' . __CLASS__ . '.' );
      }

      $args = RESTian::expand_shortnames( $args, array(
        'method'  => 'http_method',
        'type'    => 'content_type',
      ));

      if ( isset( $args['content_type'] ) )
        $args['content_type'] = RESTian::expand_content_type( $args['content_type'] );

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
}