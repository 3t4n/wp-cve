<?php
/*
Plugin Name:  Disable Registration Page
Plugin URI:   https://www.rizonesoft.com/wordpress/disable-wordpress-registration-page/
Description:  Disable the default WordPress registration page without disabling user registration.
Version:      1.1.0
Author:       Rizonesoft.com
Author URI:   https://www.rizonesoft.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  disable-regpage
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
* remove the register link from the wp-login.php script
*/
add_filter('option_users_can_register', function($value) {
    $script = basename(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH));
 
    if ($script == 'wp-login.php') {
        $value = false;
    }
 
    return $value;
});

add_filter( 'plugin_action_links', 'r_disable_regpage_add_action_links', 10, 5 );
add_filter( 'plugin_row_meta', 'r_disable_regpage_row_meta', 10, 2 );

function r_disable_regpage_add_action_links( $actions, $plugin_file ) {
 
 $action_links = array(
 
   'documentation' => array(
      'label' => __('Documentation', 'r_disable_regpage'),
      'url' => 'https://www.rizonesoft.com/wordpress/disable-wordpress-registration-page/'
       )
   );
 
  return r_disable_regpage_plugin_action_links( $actions, $plugin_file, $action_links, 'before');
}

function r_disable_regpage_row_meta( $actions, $plugin_file ) {
 
 $action_links = array(
 
   'donate' => array(
      'label' => __('Donate', 'r_disable_regpage'),
      'url'   => 'https://www.paypal.me/rizonesoft'
    ));
 
  return r_disable_regpage_plugin_action_links( $actions, $plugin_file, $action_links, 'after');
}
 
/**
 * plugin_action_links
 */
 
function  r_disable_regpage_plugin_action_links ( $actions, $plugin_file,  $action_links = array(), $position = 'after' ) { 
 
  static $plugin;
 
  if( !isset($plugin) ) {
      $plugin = plugin_basename( __FILE__ );
  }
 
  if( $plugin == $plugin_file && !empty( $action_links ) ) {
 
     foreach( $action_links as $key => $value ) {
 
        $link = array( $key => '<a href="' . $value['url'] . '">' . $value['label'] . '</a>' );
 
         if( $position == 'after' ) {
 
            $actions = array_merge( $actions, $link );    
 
         } else {
 
            $actions = array_merge( $link, $actions );
         }
 
 
      }//foreach
 
  }// if
 
  return $actions;
 
}


?>