<?php
/**
 * Plugin Name: Add .html Extension to Pages
 * Plugin URI: https://wordpress.org/plugins/add-html-extension-to-pages/
 * Description: A simple and easy way to add .html extension to WordPress pages.
 * Version: 1.0.2
 * Author: Subodh Ghulaxe
 * Author URI: http://www.subodhghulaxe.com
 */

// Avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if (!class_exists('AHETP')) {

	/**
	 * Add .html Extension to Pages class
	 *
	 * @package Add .html Extension to Pages
	 * @since 1.0.0
	 */
	class AHETP {
    /**
		 * Instance of AHETP class
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object
		 */
		private static $instance = false;

    /**
		 * Return unique instance of this class
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function get_instance() {
      if ( ! self::$instance ) {
        self::$instance = new self();
      }
      return self::$instance;
    }

    function __construct() {
			add_action('init', array(&$this, 'init'), -1);
      add_filter('user_trailingslashit', array(&$this, 'no_page_slash'), 66, 2);
		}

		/**
		 * Runs after WordPress has finished loading but before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		public function init() {
      global $wp_rewrite;

      if (!strpos($wp_rewrite->get_page_permastruct(), '.html')) {
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
      }

			// Add donate link in plugin listing page.
			add_filter('plugin_row_meta', array( &$this, 'donate_link' ), 10, 2);
		}

    /**
		 * Retrieves a trailing-slashed string if the site is set for adding trailing slashes.
		 *
		 * @since 1.0.0
		 * @param  string $string
		 * @param  string $type
		 */
    public function no_page_slash($string, $type) {
      global $wp_rewrite;
   
      if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes == true && $type == 'page') {
        return untrailingslashit($string);
      }

      return $string;
    }

		/**
		 * Called on register_activation_hook
		 *
		 * @since 1.0.0
		 */
    public static function activate() {
      global $wp_rewrite;
   
      if (!strpos($wp_rewrite->get_page_permastruct(), '.html')) {
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
      }

      $wp_rewrite->flush_rules();
    }

		/**
		 * Called on register_deactivation_hook
		 *
		 * @since 1.0.0
		 */
    public static function deactivate() {
      global $wp_rewrite;
   
      $wp_rewrite->page_structure = str_replace(".html", "", $wp_rewrite->page_structure);
      $wp_rewrite->flush_rules();
    }

		/**
		 * Add donate link to plugin description in /wp-admin/plugins.php
		 * 
		 * @since 1.0.0
		 * @param  array $plugin_meta
		 * @param  string $plugin_file
		 * @return array
		 */
		public function donate_link($plugin_meta, $plugin_file) {
			if (plugin_basename(__FILE__) == $plugin_file)
				$plugin_meta[] = sprintf(
					'&hearts; <a href="%s" target="_blank">%s</a>',
					'https://www.patreon.com/subodhghulaxe',
					'Donate'
			);
			
			return $plugin_meta;
		}
  
  } // end class AHETP
	
	add_action('plugins_loaded', array('AHETP', 'get_instance'));

	register_activation_hook(__FILE__, array('AHETP', 'activate'));
  register_deactivation_hook(__FILE__, array('AHETP', 'deactivate'));

} // end class_exists