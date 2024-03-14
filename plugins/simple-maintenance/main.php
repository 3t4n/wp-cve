<?php
/*
Plugin Name: Simple Maintenance
Version: 1.0.4
Plugin URI: http://wphowto.net/simple-maintenance-plugin-wordpress-595 
Author: naa986
Author URI: http://wphowto.net/
Description: A simple maintenance mode plugin for WordPress
Text Domain: simple-maintenance
Domain Path: /languages
*/

if(!defined('ABSPATH')) exit;
if(!class_exists('SIMPLE_MAINTENANCE'))
{
    class SIMPLE_MAINTENANCE
    {
        var $plugin_version = '1.0.4';
        var $plugin_url;
        var $plugin_path;
        function __construct()
        {
            define('SIMPLE_MAINTENANCE_VERSION', $this->plugin_version);
            define('SIMPLE_MAINTENANCE_SITE_URL',site_url());
            define('SIMPLE_MAINTENANCE_URL', $this->plugin_url());
            define('SIMPLE_MAINTENANCE_PATH', $this->plugin_path());
            $this->plugin_includes();
        }
        function plugin_includes()
        {
            add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
            add_action('template_redirect', array($this, 'sm_template_redirect'));
        }
        function plugins_loaded_handler()
        {
            load_plugin_textdomain('simple-maintenance', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 
        }
        function plugin_url()
        {
            if($this->plugin_url) return $this->plugin_url;
            return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
        }
        function plugin_path(){ 	
            if ( $this->plugin_path ) return $this->plugin_path;		
            return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        }
        function is_valid_page() {
            return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
        }
        function sm_template_redirect()
        {
            if(is_user_logged_in()){
                //do not display maintenance page
            }
            else
            {
                if( !is_admin() && !$this->is_valid_page()){  //show maintenance page
                    $this->load_sm_page();
                }
            }
        }
        function load_sm_page()
        {
            //header('HTTP/1.0 503 Service Unavailable');
            status_header(503);
            include_once("sm-template.php");
            exit();
        }
    }
    $GLOBALS['simple_maintenance'] = new SIMPLE_MAINTENANCE();
}
