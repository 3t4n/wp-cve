<?php
/*
 * Plugin Name: Sidecar for WordPress
 * Plugin URI: https://github.com/newclarity/sidecar
 * Description:
 * Version: 0.5.1
 * Author: NewClarity, MikeSchinkel
 * Author URI: https://newclarity.net
 * Text Domain: sidecar
 * License: GPLv2
 *
 *  Copyright 2012
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License, version 2, as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
define( 'SIDECAR_FILE', __FILE__ );
define( 'SIDECAR_DIR', dirname( __FILE__ ) );
define( 'SIDECAR_PATH', plugin_dir_path( __FILE__ ) );

define( 'SIDECAR_VER', '0.5.1' );
define( 'SIDECAR_MIN_PHP', '5.2.4' );
define( 'SIDECAR_MIN_WP', '3.2' );

/**
 *
 */
final class Sidecar {
  /**
   * @var string
   */
  private static $_installed_dir = false;

  /**
   * @var string
   */
  private static $_this_url = false;

  /**
   * @var string
   */
  private static $_this_domain = false;

  /**
   *
   */
  static function on_load() {

    $error_path = plugin_dir_url(__FILE__) ;
        try {

          spl_autoload_register( array( __CLASS__, 'autoloader' ) );
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
   */
static function autoloader( $class_name ) {
    $error_path = plugin_dir_url(__FILE__) ;
        try {
    
    $suffix = preg_replace( '#^Sidecar_(.*)$#', '$1', $class_name );
    $suffix = str_replace( '_', '-', strtolower( $suffix ) );
    $filename = dirname( __FILE__ ) . "/classes/class-{$suffix}.php";
    if ( is_file( $filename ) ) {
      require( $filename );
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
   * @param string $message
   * @param array $args
   */
  static function show_error( $message, $args ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {   

      $args = func_get_args();
      echo '<div class="error"><p><strong>ERROR</strong>[Sidecar]: ' . call_user_func_array( 'sprintf', $args ) . '</p></div>';

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
   * Tests an array element for value, first checking for isset().
   *
   * @param array $array
   * @param string $element
   * @param mixed $value
   * @param bool $exactly
   * @return bool
   */
  static function element_is( $array, $element, $value = true, $exactly = false ) {

    $error_path = plugin_dir_url(__FILE__) ;
    try {
      return isset( $array[$element] ) && ( $exactly ? $value === $array[$element] : $value == $array[$element] );
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
 	 * Returns the domain for this site.
   *
 	 * @return string
 	 */
 	static function this_domain() {

    $error_path = plugin_dir_url(__FILE__) ;
    try {
 	    if ( ! self::$_this_domain ) {
 	      $parts = explode( '/', site_url() );
        self::$_this_domain = $parts[2];
       }
      return self::$_this_domain;
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
 	 * Returns the directory in which WordPress is installed, or '/' if root.
   *
   * Preceded with a '/' but no trailing '/'
 	 *
 	 * @return string
 	 */
 	static function installed_dir() {

    $error_path = plugin_dir_url(__FILE__) ;
    try {

 	    if ( ! self::$_installed_dir ) {
 	      $site_url = site_url();
        if ( 2 == substr_count( $site_url, '/' ) ) {
          self::$_installed_dir = '/';
        } else {
          $regex = '^https?://' . preg_quote( self::this_domain() ) . '(/.*)/?$';
          self::$_installed_dir = preg_replace( "#{$regex}#", '$1', site_url() );
        }
      }
      return self::$_installed_dir;
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
   * Returns the current URL.
   *
   * @return bool
   */
  static function this_url() {

    $error_path = plugin_dir_url(__FILE__) ;
    try {    

 	    if ( ! self::$_this_url ) {
         $installed_dir = self::installed_dir();
         $requested_path = substr( $_SERVER['REQUEST_URI'], strlen( $installed_dir ) );
         self::$_this_url = site_url( $requested_path );
      }
      return self::$_this_url;    
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
Sidecar::on_load();
