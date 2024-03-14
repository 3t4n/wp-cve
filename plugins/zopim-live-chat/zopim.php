<?php

/*
Plugin Name: Zendesk Chat Widget
Plugin URI: http://www.zendesk.com/chat?iref=wp_plugin
Description: Zendesk Chat is an award winning chat solution that helps website owners to engage their visitors and convert customers into fans!
Author: Zendesk
Version: 1.4.18
Author URI: http://www.zendesk.com/chat?iref=wp_plugin
Text Domain: zopim
Domain path: /language
*/

define( 'VERSION_NUMBER', "1.4.18" );
define( 'ZOPIM_BASE_URL', "https://www.zopim.com/" );
define( 'ZOPIM_ACCOUNT_URL', "https://account.zopim.com/" );
define( 'ZOPIM_SIGNUP_REDIRECT_URL', ZOPIM_ACCOUNT_URL . "?aref=MjUxMjY4:1TeORR:9SP1e-iPTuAVXROJA6UU5seC8x4&visit_id=6ffe00ec3cfc11e2b5ab22000a1db8fa&utm_source=account%2Bsetup%2Bpage&utm_medium=link&utm_campaign=wp%2Bsignup2#signup" );
define( 'ZOPIM_GETACCOUNTDETAILS_URL', ZOPIM_BASE_URL . "plugins/getAccountDetails" );
define( 'ZOPIM_LOGIN_URL', ZOPIM_BASE_URL . "plugins/login" );
define( 'ZOPIM_DASHBOARD_LINK', "https://dashboard.zopim.com/?utm_source=wp&utm_medium=link&utm_campaign=wp%2Bdashboard" );
define( 'ZOPIM_SMALL_LOGO', "https://dashboard.zopim.com/assets/branding/zopim.com/chatman/online.png" );
define( 'ZOPIM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ZOPIM_USER_AGENT', 'Zendesk Chat Wordpress Plugin' );

require_once( 'includes/compatibility.php' );

class Zopim
{
  private static $_instance = NULL;

  private function __construct()
  {
    spl_autoload_register( array( &$this, 'autoload' ) );

    if ( is_admin() ) {
      Zopim_Admin::get_instance();
    }

    add_action( 'wp_footer', array( 'Zopim_Widget', 'zopimme' ) );
    add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ) );
  }

  /**
   * [get_instance description]
   * @return [type] [description]
   */
  public static function get_instance()
  {
    if ( self::$_instance === NULL )
      self::$_instance = new self();

    return ( self::$_instance );
  }

  /*
 * autoloading callback function
 * @param string $class name of class to autoload
 * @return TRUE to continue; otherwise FALSE
 */
  public function autoload( $class )
  {
    // setup the class name
    $classname = str_replace( 'Zopim_', '', $class );
    $classname = strtolower( str_replace( '_', '-', $classname ) );

    $classfile = ZOPIM_PLUGIN_DIR . 'includes/class-' . $classname . '.php';

    if ( file_exists( $classfile ) ) {
      require_once( $classfile );
    }
  }

  /*
   * return reference to asset, relative to the base plugin's /assets/ directory
   * @param string $ref asset name to reference
   * @return string href to fully qualified location of referenced asset
   */
  public static function get_asset( $ref )
  {
    $ret = plugin_dir_url( __FILE__ ) . 'assets/' . $ref;

    return ( $ret );
  }

  public function load_textdomain()
  {
    // Load plugin text domain
    load_plugin_textdomain( 'zopim-live-chat', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
  }

}

Zopim::get_instance();
