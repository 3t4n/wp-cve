<?php
/*
Plugin Name: Remove Footer Links
Plugin URI: https://wordpress.org/plugins/remove-footer-links/
Description: Simple way to remove footer credit
Version: 1.0.1
Author: plugindeveloper
Author URI: https://profiles.wordpress.org/plugindeveloper/
License: GPLv3 or later
Text Domain: remove-footer-links
*/
if( !class_exists( 'Remove_Footer_Links' ) ){

    class Remove_Footer_Links{

        public function __construct(){

            if ( ! defined( 'ABSPATH' ) ) {
                die( 'Invalid request.' );
            }
            $this->define();
            $this->dependencies();
            $this->loader();
        }

        public function define(){

            if(!defined('REMOVE_FOOTER_LINKS_VERSION')){
                define( 'REMOVE_FOOTER_LINKS_VERSION', '1.0.1' );
            }

            if(!defined('DIRECTORY_SEPARATOR')){
                define( 'DIRECTORY_SEPARATOR', '/' );
            }

            if(!defined('REMOVE_FOOTER_LINKS_FILE')){
                define( 'REMOVE_FOOTER_LINKS_FILE', __FILE__ );
            }

            if(!defined('REMOVE_FOOTER_LINKS_BASENAME')){
                define( 'REMOVE_FOOTER_LINKS_BASENAME', plugin_basename(__FILE__) );
            }

            if(!defined('REMOVE_FOOTER_LINKS_DEV_MODE')){
                define( 'REMOVE_FOOTER_LINKS_DEV_MODE', false );
            }

            if(!defined('REMOVE_FOOTER_LINKS_PATH')){
                define( 'REMOVE_FOOTER_LINKS_PATH', dirname( __FILE__ ) );
            }

            if(!defined('REMOVE_FOOTER_LINKS_URL')){
                define( 'REMOVE_FOOTER_LINKS_URL', plugins_url( '/', __FILE__ ) );
            }

        }

        public function dependencies(){

            require_once wp_normalize_path(REMOVE_FOOTER_LINKS_PATH.'/inc/core/functions.php');
            require_once wp_normalize_path(REMOVE_FOOTER_LINKS_PATH.'/inc/core/autoloader.php');

        }

        public function loader(){

            new \Remove_Footer_Links\Inc\Initialize();

        }

    }

}

new \Remove_Footer_Links();
