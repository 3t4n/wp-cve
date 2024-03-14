<?php
/*
 Plugin Name: Speed Up - Clean WP
 Plugin URI: http://wordpress.org/plugins/speed-up-clean-wp/
 Description: remove comment-reply.min.js and jquery-migrate.js scripts, disable "embeds" and "emoji" features and clean the head from unnecessary metadata.
 Version: 1.0.8
 Author: Simone Nigro
 Author URI: https://profiles.wordpress.org/nigrosimone
 License: GPLv2 or later
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( !defined('ABSPATH') ) exit;

class SpeedUp_CleanWP {
    
    /**
     * Instance of the object.
     *
     * @since  1.0.0
     * @static
     * @access public
     * @var null|object
     */
    public static $instance = null;
    
    
    /**
     * Access the single instance of this class.
     *
     * @since  1.0.0
     * @return SpeedUp_CleanWP
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     *
     * @since  1.0.0
     * @return SpeedUp_CleanWP
     */
    private function __construct(){
        
        if( !is_admin() ){
            add_action( 'init', array($this, 'init') );
            add_filter( 'wp_default_scripts', array($this, 'dequeue_jquery_migrate') );
        }
    }
    
    /**
     * init.
     * 
     * @since  1.0.0
     * @return void
     */
    public function init(){
        // all actions related to emojis
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        
        // Remove the REST API endpoint.
        remove_action( 'wp_head', 'rest_output_link_wp_head');
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
        
        remove_action('wp_head', 'rsd_link'); // remove really simple discovery link
        remove_action('wp_head', 'wp_generator'); // remove wordpress version
        
        remove_action('wp_head', 'feed_links', 2); // remove rss feed links (make sure you add them in yourself if youre using feedblitz or an rss service)
        remove_action('wp_head', 'feed_links_extra', 3); // removes all extra rss feed links
        
        remove_action('wp_head', 'index_rel_link'); // remove link to index page
        remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml (needed to support windows live writer)
        
        remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
        remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
        remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // remove the next and previous post links
        
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
        
        wp_deregister_script( 'comment-reply' ); // Remove comment-reply.min.js from footer
     }
    
     /**
      * Dequeue jquery migrate.
      * 
      * @since  1.0.0
      * @param object $scripts
      * @return void
      */
     public function dequeue_jquery_migrate( &$scripts ){
         if ( isset($scripts->registered['jquery']) ) {
         	
             $script = $scripts->registered['jquery'];
             
             // Check whether the script has any dependencies
             if ( $script->deps ) { 
                 $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
             }
         }
     }
}

// Init
SpeedUp_CleanWP::get_instance();