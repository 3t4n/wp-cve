<?php

class Sidecar_Form_Settings extends Sidecar_Settings_Base {

  /**
   * @var array List of names for required fields.
   */
  private $_required_fields = array();

  /**
   * Register a setting
   * @param string $setting_name
   * @param bool|mixed $value
   */
  function register_setting( $setting_name, $value = false ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      if ( ! $this->offsetExists( $setting_name ) )
        $this->offsetSet( $setting_name, $value );
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
  function get_empty_field_values() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
    
      return array_fill_keys( array_keys( (array)$this ), false );
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
   * Set the list of required settings names for this form.
   * @param bool|array $required_fields
   */
  function set_required_fields( $required_fields = false ) {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $this->_required_fields = $required_fields;
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
  function has_required_fields() {
    $error_path = plugin_dir_url(__FILE__) ;
	  try {
      
      $has_required_fields = true;
      /** @var Sidecar_Form $form */
      foreach( $this->_required_fields as $setting_name ) {
        if ( ! $this[$setting_name] ) {
          $has_required_fields = false;
          break;
        }
      }
      return $has_required_fields;
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