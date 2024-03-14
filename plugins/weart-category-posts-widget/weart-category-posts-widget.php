<?php
/*
Plugin Name: Weart Category Posts Widget
Plugin URI: https://github.com/weartstudio/weart-category-posts-widget
Description: Display the latest posts with the most beautiful way from the picked category.
Author: weartstudio
Author URI: http://weartstudio.eu
Version: 1.0.1
Text Domain: weart-category-posts-widget
Domain Path: /languages
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/* Security (even if not hacker) */
defined('ABSPATH') or die('Hey what are you doing here? You silly human!');

/* define the plugin's folder as default filefolder */
define ('WEART_WIDGET_URL', trailingslashit(plugin_dir_url(__FILE__)));
define ('WEART_WIDGET_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define ('WEART_WIDGET_VER', '1.0.1');

/* Initialize Widget */
if(!function_exists('weart_social_widget_init')):
	function weart_social_widget_init() {
		require_once(WEART_WIDGET_DIR.'inc/widget.php');
		register_widget('Weart_Featured_Widget');
	}
endif;
add_action('widgets_init','weart_social_widget_init');

/* Load Textdomain */
if(!function_exists('weart_load_featured_post_widget_text_domain')):
  function weart_load_featured_post_widget_text_domain() {
  	load_plugin_textdomain( 'weart-category-posts-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  }
endif;
add_action( 'plugins_loaded', 'weart_load_featured_post_widget_text_domain' );