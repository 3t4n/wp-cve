<?php
/*
 Plugin Name: Social Media Auto Publish
Plugin URI: https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/
Description:   Publish posts automatically from your blog to social media networks like Facebook, Twitter,  Instagram, LinkedIn and Tumblr. The plugin supports filtering posts by post-types and categories.
Version: 3.2
Requires PHP: 7.4
Author: xyzscripts.com
Author URI: https://xyzscripts.com/
License: GPLv2 or later
Text Domain:social-media-auto-publish
Domain Path: /languages/
*/

/*
 This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
if( !defined('ABSPATH') ){ exit();}
if ( !function_exists( 'add_action' ) ) {
    _e('Hi there!  I'.'m just a plugin, not much I can do when called directly.','social-media-auto-publish');
	exit;
}
function plugin_load_smaptextdomain() {
    load_plugin_textdomain( 'social-media-auto-publish', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'plugin_load_smaptextdomain' );
error_reporting(0);
define('XYZ_SMAP_PLUGIN_FILE',__FILE__);

if (!defined('XYZ_SMAP_FB_API_VERSION'))
	define('XYZ_SMAP_FB_API_VERSION','v13.0');
if (!defined('XYZ_SMAP_IG_API_VERSION'))
    define('XYZ_SMAP_IG_API_VERSION','v13.0');
	    
if (!defined('XYZ_SMAP_SOLUTION_AUTH_URL'))
define('XYZ_SMAP_SOLUTION_AUTH_URL','https://authorize.smapsolutions.com/');
if (!defined('XYZ_SMAP_SOLUTION_PUBLISH_URL'))
define('XYZ_SMAP_SOLUTION_PUBLISH_URL','https://free-publish.smapsolutions.com/');
if (!defined('XYZ_SMAP_SOLUTION_LN_PUBLISH_URL'))
	define('XYZ_SMAP_SOLUTION_LN_PUBLISH_URL','https://li-publish.smapsolutions.com/');
if (!defined('XYZ_SMAP_SOLUTION_TW_PUBLISH_URL'))
    define('XYZ_SMAP_SOLUTION_TW_PUBLISH_URL','https://tw-publish.smapsolutions.com/');
if (!defined('XYZ_SMAP_SOLUTION_IG_PUBLISH_URL'))
    define('XYZ_SMAP_SOLUTION_IG_PUBLISH_URL','https://ig-publish.smapsolutions.com/');
        
global $wpdb;
if(isset($_POST) && isset($_POST['fb_auth'] ) ||isset($_GET['page']) && ($_GET['page']=='social-media-auto-publish-suggest-features')|| (isset($_GET['page']) && ($_GET['page']=='social-media-auto-publish-settings')) || isset($_GET['page']) && ($_GET['page']=='social-media-auto-publish-manage-authorizations'))
{
	ob_start();
}
// $wpdb->query('SET SQL_MODE=""');
include_once(ABSPATH.'wp-includes/version.php');
global $wp_version;
define('XYZ_SMAP_WP_VERSION',$wp_version);
require_once( dirname( __FILE__ ) . '/admin/install.php' );
require_once( dirname( __FILE__ ) . '/xyz-functions.php' );
require_once( dirname( __FILE__ ) . '/admin/menu.php' );
require_once( dirname( __FILE__ ) . '/admin/destruction.php' );

if (version_compare(PHP_VERSION, '5.4.0', '>'))
{ 
require_once( dirname( __FILE__ ) . '/vendor/autoload.php');
require_once( dirname( __FILE__ ) . '/admin/publish.php' );
}
	

if(!class_exists('SMAPOAuth2'))
require_once( dirname( __FILE__ ) . '/api/linkedin.php' );
require_once( dirname( __FILE__ ) . '/admin/ajax-backlink.php' );
require_once( dirname( __FILE__ ) . '/admin/metabox.php' );
require_once( dirname( __FILE__ ) . '/admin/admin-notices.php' );

if(get_option('xyz_credit_link')=="smap"){

	add_action('wp_footer', 'xyz_smap_credit');

}
function xyz_smap_credit() {
	$content = '<div style="clear:both;width:100%;text-align:center; font-size:11px; "><a target="_blank" title="Social Media Auto Publish" href="https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/compare" >Social Media Auto Publish</a> Powered By : <a target="_blank" title="PHP Scripts & Programs" href="http://www.xyzscripts.com" >XYZScripts.com</a></div>';
	echo $content;
}
if(!function_exists('get_post_thumbnail_id'))
	add_theme_support( 'post-thumbnails' );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'xyz_smap_add_action_links' );
function xyz_smap_add_action_links( $links ) {
	$xyz_smap_links = array(
			'<a href="' . admin_url( 'admin.php?page=social-media-auto-publish-settings' ) . '">Settings</a>',
	);
	return array_merge( $links, $xyz_smap_links);
}
?>
