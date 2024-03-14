<?php
    /*
     * Plugin Name:       Latest Posts Block Lite
     * Plugin URI:        https://afthemes.com/plugins/
     * Description:       A beautiful collection of latest posts Gutenberg blocks for WordPress, which helps you to design posts grid, posts list, full posts layout, advanced express posts design and tile layouts of your posts.
     * Version:           1.0.7
     * Author:            AF themes
     * Author URI:        https://afthemes.com
     * Text Domain:       latest-posts-block-lite
     * License:           GPL-2.0+
     * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
     */
    
    defined('ABSPATH') or die('No script kiddies please!');  // prevent direct access
    
    if (!class_exists('LatestPostsBlockLite')) :
        
        class LatestPostsBlockLite
        {
            
            
            /**
             * Plugin version.
             *
             * @var string
             */
            const VERSION = '1.0.7';
            
            /**
             * Instance of this class.
             *
             * @var object
             */
            protected static $instance = null;
            
            
            /**
             * Initialize the plugin.
             */
            public function __construct()
            {
                
                /**
                 * Define global constants
                 **/
                defined('LATEST_POSTS_BOX_LITE_BASE_FILE') or define('LATEST_POSTS_BOX_LITE_BASE_FILE', __FILE__);
                defined('LATEST_POSTS_BOX_LITE_BASE_DIR') or define('LATEST_POSTS_BOX_LITE_BASE_DIR', dirname(LATEST_POSTS_BOX_LITE_BASE_FILE));
                defined('LATEST_POSTS_BOX_LITE_PLUGIN_URL') or define('LATEST_POSTS_BOX_LITE_PLUGIN_URL', plugin_dir_url(__FILE__));
                defined('LATEST_POSTS_BOX_LITE_PLUGIN_DIR') or define('LATEST_POSTS_BOX_LITE_PLUGIN_DIR', plugin_dir_path(__FILE__));
                
                defined( 'LATEST_POSTS_BOX_LITE_SHOW_PRO_NOTICES' ) || define( 'LATEST_POSTS_BOX_LITE_SHOW_PRO_NOTICES', true );
                defined( 'LATEST_POSTS_BOX_LITE_VERSION' ) || define( 'LATEST_POSTS_BOX_LITE_VERSION', '1.0.6' );
                
                
                
                include_once 'src/init.php';
                include_once  'src/fonts.php';
                
                
            } // end of constructor
            
            /**
             * Return an instance of this class.
             *
             * @return object A single instance of this class.
             */
            public static function get_instance()
            {
                
                // If the single instance hasn't been set, set it now.
                if (null == self::$instance) {
                    self::$instance = new self;
                }
                return self::$instance;
            }
            
            
        }// end of the class
        
        add_action('plugins_loaded', array('LatestPostsBlockLite', 'get_instance'), 0);
    
    endif;
