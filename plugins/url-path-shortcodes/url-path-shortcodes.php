<?php
/*
Plugin Name: URL & Path Shortcodes
Plugin URI: http://dev7studios.com
Description: Allows you to use common WordPress URL's and Paths in the post editor using shortcodes.
Version: 1.0
Author: Dev7studios
Author URI: http://dev7studios.com
Author Email: gilbert@pellegrom.me
License: GPL2
*/

class URLPathShortcodes {

    function __construct() 
    {	
        // URL's
        add_shortcode( 'home_url', array(&$this, 'home_url') );
        add_shortcode( 'site_url', array(&$this, 'site_url') );
        add_shortcode( 'admin_url', array(&$this, 'admin_url') );
        add_shortcode( 'network_home_url', array(&$this, 'network_home_url') );
        add_shortcode( 'network_site_url', array(&$this, 'network_site_url') );
        add_shortcode( 'network_admin_url', array(&$this, 'network_admin_url') );
        add_shortcode( 'content_url', array(&$this, 'content_url') );
        add_shortcode( 'plugins_url', array(&$this, 'plugins_url') );
        add_shortcode( 'wp_upload_dir', array(&$this, 'wp_upload_dir') );
        add_shortcode( 'get_template_directory_uri', array(&$this, 'get_template_directory_uri') );
        add_shortcode( 'get_stylesheet_directory_uri', array(&$this, 'get_stylesheet_directory_uri') );
        add_shortcode( 'get_stylesheet_uri', array(&$this, 'get_stylesheet_uri') );
        add_shortcode( 'get_theme_root_uri', array(&$this, 'get_theme_root_uri') );
        
        // Path's
        add_shortcode( 'get_stylesheet_directory', array(&$this, 'get_stylesheet_directory') );
        add_shortcode( 'get_theme_root', array(&$this, 'get_theme_root') );
        add_shortcode( 'get_theme_roots', array(&$this, 'get_theme_roots') );
    }
    
    function home_url( $atts ) {
    	extract( shortcode_atts( array(
    		'path' => '',
    		'scheme' => null
    	), $atts ) );
    
    	return home_url( $path, $scheme );
    }
    
    function site_url( $atts ) {
    	extract( shortcode_atts( array(
    		'path' => '',
    		'scheme' => null
    	), $atts ) );
    
    	return site_url( $path, $scheme );
    }
    
    function admin_url( $atts ) {
    	extract( shortcode_atts( array(
    		'path' => '',
    		'scheme' => 'admin'
    	), $atts ) );
    
    	return admin_url( $path, $scheme );
    }
    
    function network_home_url( $atts ) {
    	extract( shortcode_atts( array(
    		'path' => '',
    		'scheme' => null
    	), $atts ) );
    
    	return network_home_url( $path, $scheme );
    }
    
    function network_site_url( $atts ) {
    	extract( shortcode_atts( array(
    		'path' => '',
    		'scheme' => null
    	), $atts ) );
    
    	return network_site_url( $path, $scheme );
    }
    
    function network_admin_url( $atts ) {
    	extract( shortcode_atts( array(
    		'path' => '',
    		'scheme' => 'admin'
    	), $atts ) );
    
    	return network_admin_url( $path, $scheme );
    }
    
    function content_url( $atts ) {
    	extract( shortcode_atts( array(
    		'path' => ''
    	), $atts ) );
    
    	return content_url( $path );
    }
    
    function plugins_url( $atts ) {
    	extract( shortcode_atts( array(
    		'path' => '',
    		'plugin' => ''
    	), $atts ) );
    
    	return plugins_url( $path, $plugin );
    }
    
    function wp_upload_dir( $atts ) {
    	extract( shortcode_atts( array(
    		'key' => 'baseurl'
    	), $atts ) );
    
    	$upload_dir = wp_upload_dir();
    	return $upload_dir[$key];
    }
    
    function get_template_directory_uri() {
    	return get_template_directory_uri();
    }
    
    function get_stylesheet_directory_uri() {
        return get_stylesheet_directory_uri();
    }
    
    function get_stylesheet_uri() {
        return get_stylesheet_uri();
    }
    
    function get_theme_root_uri() {
        return get_theme_root_uri();
    }
    
    
    
    function get_stylesheet_directory() {
        return get_stylesheet_directory();
    }
    
    function get_theme_root() {
        return get_theme_root();
    }
    
    function get_theme_roots() {
        return get_theme_roots();
    }

}
new URLPathShortcodes();

?>