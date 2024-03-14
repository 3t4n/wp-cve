<?php
/*
Plugin Name: SiteSEO
Plugin URI: http://wordpress.org/plugins/siteseo/
Description: This plugin handles On Page SEO, Content Analysis, Social Previews, Google Preview, Hyperlink Analysis, Image Analysis, Home Page Monitor, Schemas for various type of posts.
Author: Softaculous
Version: 1.0.4
Author URI: https://siteseo.io/
License: GPLv2
Text Domain: siteseo
Domain Path: /languages
*/

// We need the ABSPATH
if (!defined('ABSPATH')) exit;

if(!function_exists('add_action')){
	echo 'You are not allowed to access this page directly.';
	exit;
}

$_tmp_plugins = get_option('active_plugins');

// Is the premium plugin loaded ?
if(in_array('siteseo-pro/siteseo-pro.php', $_tmp_plugins)){
	return;
}

// If GOSMTP_VERSION exists then the plugin is loaded already !
if(defined('SITESEO_VERSION')) {
	return;
}

define('SITESEO_FILE', __FILE__);

include_once(dirname(__FILE__).'/init.php');
