<?php /*
    Plugin Name: WP Flash Messages
    Plugin URI: http://webpresencepartners.com
    Description: Easily Show Flash Messages in WP Admin
    Version: 1
    Author: Daniel Grundel, Web Presence Partners
    Author URI: http://webpresencepartners.com
*/

if ( ! class_exists('WPFlashMessages') ) {
  class WPFlashMessages {

      public function __construct() {
          add_action('admin_notices', array(&$this, 'show_flash_messages'));
      }

      //Flash Messages
      public static function queue_flash_message($message, $class = '') {

          $default_allowed_classes = array('error', 'updated');
          $allowed_classes = apply_filters('flash_messages_allowed_classes', $default_allowed_classes);
          $default_class = apply_filters('flash_messages_default_class', 'updated');

          if(!in_array($class, $allowed_classes)) $class = $default_class;

          $flash_messages = maybe_unserialize(get_option('wp_flash_messages', array()));
          $flash_messages[$class][] = $message;

          update_option('wp_flash_messages', $flash_messages);
      }
      public static function show_flash_messages() {
          $flash_messages = maybe_unserialize(get_option('wp_flash_messages', ''));

          if(is_array($flash_messages)) {
              foreach($flash_messages as $class => $messages) {
                  foreach($messages as $message) {
                      ?><div class="<?php echo $class; ?>"><p><?php echo $message; ?></p></div><?php
                  }
              }
          }

          //clear flash messages
          delete_option('wp_flash_messages');
      }
  }
  new WPFlashMessages();

  //convenience function
  if( class_exists('WPFlashMessages') && !function_exists('queue_flash_message') ) {
      function queue_flash_message($message, $class = null) {
          WPFlashMessages::queue_flash_message($message, $class);
      }
  }
}
