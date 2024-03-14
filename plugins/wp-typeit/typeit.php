<?php
/**
* Plugin Name: WP TypeIt Lite
* Plugin URI: https://typeitjs.com
* Description: Easily create and manage typewriter effects using the JavaScript utility, TypeIt.
* Version: 1.0.3
* Author: Alex MacArthur
* Author URI: https://macarthur.me
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

namespace TypeIt;

if ( !defined( 'WPINC' ) ) {
  die;
}

if(!class_exists('\\TypeIt\\App')) {

  require_once(ABSPATH . 'wp-admin/includes/plugin.php');
  require_once(dirname(__FILE__) . '/vendor/autoload.php');

  class App {
    
    public static function go() {
      $GLOBALS[__CLASS__] = new self;
      return $GLOBALS[__CLASS__];
    }

    /**
     * Instatiate necessary classes, enqueue admin scripts.
     */
    public function __construct() {
      $realpath = realpath(dirname(__FILE__));

      require_once($realpath . '/src/hooks/shortcode.php');
      require_once($realpath . '/src/hooks/plugin-meta.php');
      require_once($realpath . '/src/hooks/enqueue-assets.php');
    }
    
  }

  App::go();
  
}
