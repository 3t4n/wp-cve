<?php

class Sidecar_Plugin_Settings extends Sidecar_Settings_Base {

  /**
   * @var string
   */
  var $option_name;

  /**
   * @var string
   */
  var $installed_version;

  /**
   * @var bool
   */
  var $configured = false;

  /**
   * @var bool
   */
  protected $_is_encrypted;

  /**
   * @var object Mirrors $_parent so semantics are easier to understand while debugging.
   */
  var $_plugin;

  /**
   * @param Sidecar_Plugin_Base|Sidecar_Settings_Base $plugin
   * @param string $option_name
   */
  function __construct( $plugin, $option_name ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {
	


      parent::__construct( $plugin );
      $this->_plugin = $plugin;
      $this->option_name = $option_name;
      add_action( 'shutdown', array( $this, 'shutdown' ) );
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
   * Register a form
   * @param string $form_name
   */
  function register_form_settings( $form_name ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      $this->register_setting( $form_name, 'Sidecar_Form_Settings' );
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
   * @param bool|string $setting_class
   */
  function register_setting( $setting_name, $setting_class = false ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      if ( class_exists( $setting_class ) )
        $this->offsetSet( $setting_name, new $setting_class( $this, $setting_name ) );
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
   * @return Sidecar_Settings_Base|Sidecar_Form_Settings
   */
  function get_setting( $setting_name ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	

      if ( ! $this->offsetExists( $setting_name ) ) {
        $setting_value = false;
      } else {
        $setting_value = $this->offsetGet( $setting_name );
      }

      if ( method_exists( $this->_plugin, $method_name = "get_setting_{$setting_name}" ) ) {
        $setting_value = call_user_func( array( $this->_plugin, $method_name ), $setting_value );
      }

      return $setting_value;
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
   * Autosave dirty settings on shutdown
   */
  function shutdown() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      if ( $this->is_dirty() ) {
        $this->save_settings();
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
   * Removes settings from the wp_options table in the WordPress MySQL database.
   */
  function delete_settings() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      delete_option( $plugin->option_name );
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
   * Accepts an array of $form objects and assigns to the internal array.
   *
   * @param array $forms
   */
  function set_values( $forms ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	

      if ( is_array( $forms ) ) {
        $is_dirty = $forms !== $this->getArrayCopy();
        $this->exchangeArray( $forms );
        if ( $is_dirty )
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
   * Accepts an array of name/value pairs and assigns the settings.
   *
   * @param array $forms_values
   */
  function set_values_deep( $forms_values ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	

      if ( is_array( $forms_values ) )
        foreach( $forms_values as $form_name => $form_values ) {
          $this->get_setting( $form_name )->set_values( $form_values );
        }
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
   * Accept an object that might have been serialized and stored in wp_options table in the WordPress MySQL database.
   *
   * @param string|object $data
   * @return array
   */
  function set_data( $data ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	

     if ( $data ) {
       if ( is_string( $data ) )
         $data = unserialize( $data );

       if ( ! empty( $data ) && is_object( $data ) ) {

         $this->configured = isset( $data->configured ) ? $data->configured : false;
         $this->installed_version = isset( $data->installed_version ) ? $data->installed_version : 'unknown';

         if ( ! empty( $data->values ) && is_array( $data->values ) ) {
           $this->set_values_deep( $data->values );
         }

         /**
          * @todo Add logic to compare with current value to ensure dirty is not set unnecessarily.
          */
         $this->set_dirty( true );

       }

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
   * Load settings from the wp_options table in the WordPress MySQL database.
   */
  function load_settings() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      $this->set_data( get_option( $this->option_name ) );
      if ( $this->is_encrypted() )
        $this->decrypt_settings();
      $this->set_dirty( false );
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
   * Format the settings as an object that can be serialized to the wp_options table in the WordPress MySQL database.
   */
  function get_data() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      return (object)array(
        'installed_version' => $this->installed_version,
        'configured'        => $this->configured,
        'values'            => $this->get_values_deep(),
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
   * Save settings to the wp_options table in the WordPress MySQL database.
   */
  function save_settings() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      if ( ! $this->is_encrypted() )
        $this->encrypt_settings();
      update_option( $this->option_name, $this->get_data() );
      $this->set_dirty( false );
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
   * Get a string representing the encryption status
   * @return bool
   */
  function is_encrypted() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      return $this->_is_encrypted;
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
   * @param bool $is_encrypted
   */
  function set_encrypted( $is_encrypted ) {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      $this->_is_encrypted = $is_encrypted;
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
   * Call decryption method if specified in plugin.
   *
   */
  function decrypt_settings() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      if ( method_exists( $this->_plugin, 'decrypt_settings' ) ) {
        call_user_func( array( $this->_plugin, 'decrypt_settings' ), $this );
   	  }
      $this->set_encrypted( false );
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
   * Call decryption method if specified in plugin.
   *
   */
  function encrypt_settings() {
    $error_path = plugin_dir_url(__FILE__) ;
    try {
	
      if ( method_exists( $this->_plugin, 'encrypt_settings' ) ) {
        call_user_func( array( $this->_plugin, 'encrypt_settings' ), $this );
   	  }
      $this->set_encrypted( true );
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
	
      $this->_is_dirty = $is_dirty;
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
