<?php
/**
 * Plugin Name: NFT Gallery
 * Plugin URI: https://skybee.io
 * Description: The easiest way to add NFTs from OpenSea to your WordPress site! Powered by OpenSea API.
 * Author: Hendra Setiawan
 * Author URI: https://skybee.io/
 * Version: 1.2.0
 * Text Domain: nft-gallery
 * Written by: Hendra Setiawan - https://skybee.io/
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

$mode = 'live'; // dev or live

if($mode == 'dev') { $version = rand(100,999); } else { $version = '1.2.0'; }

define('NFTGALLERY_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('NFTGALLERY_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NFTGALLERY_VERSION', $version);

require_once( NFTGALLERY_PLUGIN_PATH . 'admin/functions.php' );
require_once( NFTGALLERY_PLUGIN_PATH . 'inc/shortcodes.php' );

function nftgallery_assets() {
    wp_register_script( 'lightgallery', '//cdnjs.cloudflare.com/ajax/libs/lightgallery/2.4.0/lightgallery.umd.min.js' , array('jquery'), '', true );
    wp_register_script( 'lightgallerythumbnail', '//cdnjs.cloudflare.com/ajax/libs/lightgallery/2.4.0/plugins/thumbnail/lg-thumbnail.umd.min.js' , array('jquery'), '', true );
    wp_register_script( 'lightgalleryzoom', '//cdnjs.cloudflare.com/ajax/libs/lightgallery/2.4.0/plugins/zoom/lg-zoom.umd.min.js' , array('jquery'), '', true );
    wp_register_script( 'justifiedGallery', '//cdn.jsdelivr.net/npm/justifiedGallery@3.8.1/dist/js/jquery.justifiedGallery.js' , array('jquery'), '', true );
    wp_register_script( 'nftgallery', plugin_dir_url( __FILE__ ).'assets/js/frontend.js' , array('jquery'), NFTGALLERY_VERSION, true );
    wp_register_style( 'flexbox', plugin_dir_url( __FILE__ ) . 'assets/css/flexboxgrid.min.css', false, NFTGALLERY_VERSION );
    wp_register_style( 'nftgallery', plugin_dir_url( __FILE__ ) . 'assets/css/frontend.css', false, NFTGALLERY_VERSION );
    wp_register_style( 'lightgallery', '//cdnjs.cloudflare.com/ajax/libs/lightgallery/2.4.0/css/lightgallery.min.css');
    wp_register_style( 'lightgalleryzoom', '//cdnjs.cloudflare.com/ajax/libs/lightgallery/2.4.0/css/lg-medium-zoom.min.css');
    wp_register_style( 'lightgallerythumbnail', '//cdnjs.cloudflare.com/ajax/libs/lightgallery/2.4.0/css/lg-thumbnail.min.css');
    wp_register_style( 'lightgallerytransition', '//cdnjs.cloudflare.com/ajax/libs/lightgallery/2.4.0/css/lg-transitions.min.css');
    wp_register_style( 'justifiedGallery', '//cdn.jsdelivr.net/npm/justifiedGallery@3.8.1/dist/css/justifiedGallery.css');
}
add_action( 'wp_enqueue_scripts', 'nftgallery_assets' );