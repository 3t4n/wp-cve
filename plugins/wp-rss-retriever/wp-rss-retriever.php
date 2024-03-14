<?php
/**
 * Plugin Name: RSS Feed Retriever
 * Plugin URI: https://thememason.com/plugins/rss-retriever/
 * Description: A lightweight RSS feed plugin which uses shortcode to fetch and display an RSS feed including thumbnails and an excerpt.
 * Version: 1.6.10
 * Author: Theme Mason
 * Author URI: https://thememason.com/
 * Text Domain: wp-rss-retriever
 * Domain Path: /languages
 * License: GPL2
 */

// Global variables
define('WP_RSS_RETRIEVER_VER', '1.6.10');
define('WP_RSS_RETRIEVER_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('WP_RSS_RETRIEVER_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

include( WP_RSS_RETRIEVER_PLUGIN_PATH . 'inc/welcome-screen.php');
include( WP_RSS_RETRIEVER_PLUGIN_PATH . 'inc/classes/RSS_Retriever_Feed.php');
include( WP_RSS_RETRIEVER_PLUGIN_PATH . 'inc/classes/RSS_Retriever_Feed_Item.php');
include( WP_RSS_RETRIEVER_PLUGIN_PATH . 'inc/ajax/rss-retriever-ajax-request.php');
require_once ( ABSPATH . WPINC . '/class-simplepie.php' );


// set the default cache to 12 hours
add_option( 'wp_rss_cache', 43200 );

function wp_rss_retriever_css_js() {
    wp_enqueue_style('rss-retriever', WP_RSS_RETRIEVER_PLUGIN_URL . 'inc/css/rss-retriever.css', $deps = false, $ver = WP_RSS_RETRIEVER_VER);
    wp_enqueue_script('jquery');

}
add_action( 'wp_enqueue_scripts', 'wp_rss_retriever_css_js');

function wp_rss_retriever_load_textdomain() {
    load_plugin_textdomain( 'wp-rss-retriever', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'wp_rss_retriever_load_textdomain' );

function wp_rss_retriever_activate() {
    set_transient( '_wp_rss_retriever_activation_redirect', true, 30 );
}
register_activation_hook( __FILE__, 'wp_rss_retriever_activate' );

// add action link under plugins list
function wp_rss_retriever_add_action_links ($links) {
    $mylinks = array(
        '<a href="' . admin_url( 'index.php?page=wp-rss-retriever-welcome' ) . '">Get Started</a>',
    );
    return array_merge( $links, $mylinks );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wp_rss_retriever_add_action_links' );

// include the thumbnail in the rss feed for self-hosted sources
function wp_rss_retriever_add_thumbnail_to_rss($content) {
    global $post;
    if ( has_post_thumbnail( $post->ID ) ){
        $content = '' . get_the_post_thumbnail( $post->ID, 'large', array( 'style' => 'float:left; margin:0 15px 15px 0;' ) ) . '' . $content;
    }
    return $content;
}
add_filter('the_excerpt_rss', 'wp_rss_retriever_add_thumbnail_to_rss');
add_filter('the_content_feed', 'wp_rss_retriever_add_thumbnail_to_rss');


add_shortcode( 'wp_rss_retriever', 'wp_rss_retriever_func' );

function wp_rss_retriever_func( $atts ){
    $parsed_attributes = shortcode_atts( array(
        'url' => '#',
        'items' => '10',
        'orderby' => 'default',
        'title' => 'true',
        'excerpt' => '20',
        'read_more' => 'true',
        'new_window' => 'true',
        'thumbnail' => 'true',
        'source' => 'true',
        'date' => 'true',
        'cache' => '43200',
        'dofollow' => 'false',
        'credits' => 'false',
        'ajax' => 'true',
    ), $atts );

    try {
        $feed = new RSS_Retriever_Feed($parsed_attributes);
        return $feed->display_feed();
    } catch (Exception $e) {
        return $e->getMessage() . "\n";
    }
}

function wp_rss_retriever_error( $message ){
    // only display errors for editors or administrators
    if (current_user_can('editor') || current_user_can('administrator')) {
        throw new Exception("RSS ERROR - " . $message);
    } else {
        throw new Exception("");
    }
}

function wp_rss_retriever_debug( $data ){
    echo '<pre>';
        var_dump($data);
    echo '</pre>';
}