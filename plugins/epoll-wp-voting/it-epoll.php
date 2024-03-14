<?php
/*
Plugin Name: WP Poll Survey & Voting Plugin - ePoll Lite
Plugin Uri: https://infotheme.net/product/epoll-pro/
Description: The WP Poll Maker & Voting Plugin is a unique advanced and stylish voting poll system & online contest system designed to integrate voting / poll / survey / election quiz systems into your post, pages and everywhere in website by just a shortcode. Add poll system to your post by placing shortcode or add voting system into your website.
Author: Poll Maker & Voting Plugin Team (InfoTheme)
Author URI: https://www.infotheme.net
Version: 3.4
Tags: poll, contest, poll plugin, voting plugin, election plugin, survey plugin, polling, voting, vote, survey, election, contest system, poll system, wp voting, wp poll, user poll, user voting, wp poll, poll, voting system, wp voting
Text Domain: it_epoll
Requires PHP: 5.6
Domain Path: /languages
Licence: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*###############################################################
    EPOLL 3.1 Lite (A Complete Event/Contest/Voting System)
##############################################################*/
/*********Plugin Initialization*/
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );


/**ACTIVATOR*/
register_activation_hook(__FILE__, 'it_epoll_activate');

//E Poll Activation
if(!function_exists('it_epoll_activate')){
	function it_epoll_activate(){}
}else{
	$plugin = dirname(__FILE__) . '/it-epoll.php';
	deactivate_plugins($plugin);
	wp_die(esc_html('<div class="plugins">
				<h2>Epoll 3.4 Plugin Activation Error!</h2>
				<p style="background: #ffef80;padding: 10px 15px;border: 1px solid #ffc680;">We Found that you are using Our Plugin\'s Another Version, Please Deactivate That Version & than try to re-activate it. 
				Don\'t worry free plugins data will be automatically migrate into this version. 
				Thanks!</p>
			</div>','it_epoll'),'Plugin Activation Error',array('response'=>200,'back_link'=>TRUE));
}

/**ACTIVATOR*/
register_activation_hook(__FILE__, 'it_epoll_deactivate');

//E Poll Deactivation
if(!function_exists('it_epoll_deactivate')){
	function it_epoll_deactivate(){}
}


/********Constants *********/
define( 'IT_EPOLL_DIR_PATH', plugin_dir_path( __FILE__ ) ); // Root Plugin Directory Define
define( 'IT_EPOLL_DIR_URL', plugin_dir_url( __FILE__ ) ); // Root Plugin URI Define
define( 'IT_EPOLL_VERSION', '3.3'); // Root Plugin Version
define( 'IT_EPOLL_EXTENSION_STORE_URL', esc_url('https://store.infotheme.net/epoll/plugins/','it_epoll') ); // Root Plugin Directory Define
define( 'IT_EPOLL_THEME_STORE_URL', esc_url('https://store.infotheme.net/epoll/themes/','it_epoll') ); // Root Plugin Directory Define
define( 'IT_EPOLL_DOC_STORE_URL', esc_url('https://store.infotheme.net/epoll/doc/','it_epoll') ); // Root Plugin Directory Define
define( 'IT_EPOLL_THUMBNAIL_CDN_URL', esc_url('https://store.infotheme.net/epoll/thumbnail/','it_epoll') ); // Root Plugin Directory Define
define( 'IT_EPOLL_DOWNLOAD_URL', esc_url('https://store.infotheme.net/epoll/download/','it_epoll') ); // Root Plugin Directory Define

include_once('core/initial_setup.php');	
include_once('core/extras.php');		
include_once('backend/metaboxes.php');
include_once('core/enque_scripts.php');	
include_once('core/addon_loader.php');
include_once('core/template_loader.php');
include_once('core/shortcode_loader.php');
include_once('core/admin/admin_ajax.php');
?>