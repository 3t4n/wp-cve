<?php

/**
 * Class Sidecar_Settings_Base
 */
class Sidecar_Settings_Base extends ArrayObject {

  /**
   * @var Sidecar_Plugin_Base|Sidecar_Settings_Base
   */
  protected $_parent;

  /**
   * @var bool
   */
  protected $_is_dirty = false;

  /**
   * @param Sidecar_Plugin_Base|Sidecar_Settings_Base $parent
   */
  function __construct( $parent ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {
      $this->_parent = $parent;
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
   * Register a setting
   * @param string $setting_name
   */
  function register_setting( $setting_name ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      $this->offsetSet( $setting_name, false );
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
   * @param string $setting_name
   *
   * @return bool
   */
  function has_setting( $setting_name ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return $this->offsetExists( $setting_name );
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
   * @param string $setting_name
   *
   * @return bool|mixed|Sidecar_Settings_Base
   */
  function get_setting( $setting_name ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return $this->offsetGet( $setting_name );
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
   * @param string $setting_name
   * @param mixed $setting_value
   *
   */
  function set_setting( $setting_name, $setting_value ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      $this->offsetSet( $setting_name, $setting_value );
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
  function get_values() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return $this->getArrayCopy();
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
   * @param string $setting_name
   * @return array
   */
  function get_value( $setting_name ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return $this->offsetGet( $setting_name );
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
  function get_values_deep() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {

      $values = array();
      /**
       * @var mixed|Sidecar_Settings_Base $value
       */
      foreach( $this->getArrayCopy() as $name => $value ) {
      $value_type = gettype($value);
        if($value_type != "array"){
          $values[$name] = method_exists( $value, 'get_values' ) ? $value->get_values() : $value;
        }
        else{
          $values[$name] = $value;
        }
      }
      return $values;
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
   * @param $is_dirty
   */
  function set_dirty( $is_dirty ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
      $this->_parent->set_dirty( $is_dirty );
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
   * @param array $settings_values
   */
  function set_values( $settings_values ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {

      if ( ! is_array( $settings_values ) )
        if ( empty( $settings_values ) ) {
          $settings_values = array();
          $this->set_dirty( true );
        } else {
          $settings_values = (array)$settings_values;
        }

      if ( $this->getArrayCopy() !== $settings_values ) {
        $this->exchangeArray( $settings_values );
        $this->set_dirty( true );
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
   * @param string $setting_name
   * @param mixed $setting_value
   * @param bool $set_dirty
   * @return array
   */
  function update_settings_value( $setting_name, $setting_value, $set_dirty = true ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {

      $this->offsetSet( $setting_name, $setting_value );
      if ( $set_dirty )
        $this->set_dirty( true );
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
   */
  function update_settings() {

    $error_path = plugin_dir_url(__FILE__) ;
    try {
      $this->set_dirty( true );
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
  function is_dirty() {

    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return $this->_is_dirty;
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
   * @todo verify this is needed in base class
   * Get a representation of the encryption status
   * @return bool
   */
  function is_encrypted() {

    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return $this->_parent->is_encrypted();
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
   * @todo verify this is needed in base class
   * @param bool $is_encrypted
   */
  function set_encrypted( $is_encrypted ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {
      $this->_parent->set_encrypted( $is_encrypted );
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
   * @param int|string $offset
   *
   * @return Sidecar_Settings_Base|string|null
   */
  function offsetGet( $offset ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return $this->offsetExists( $offset ) ? parent::offsetGet( $offset ) : false;
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

//  /**
//   * @return ArrayIterator
//   */
//  function getIterator() {
//		return new ArrayIterator( $this->_settings_values );
//	}
//
//  /**
//   * @param int|string $offset
//   *
//   * @return bool
//   */
//  function offsetExists( $offset ) {
//		return isset( $this->_settings_values[$offset] );
//	}
//
//  /**
//   * @param int|string $offset
//   * @param Sidecar_Settings_Base|string|null $value
//   */
//  function offsetSet( $offset , $value ) {
//    if ( is_null( $offset ) ) {
//        $this->_settings_values[] = $value;
//    } else {
//        $this->_settings_values[$offset] = $value;
//    }
//  }
//
//  /**
//   * @param int|string $offset
//   */
//  function offsetUnset( $offset ) {
//		unset( $this->_settings_values[$offset] );
//	}
}
