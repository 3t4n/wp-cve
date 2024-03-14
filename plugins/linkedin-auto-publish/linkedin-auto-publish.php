<?php
/*
 Plugin Name: WP to LinkedIn Auto Publish
Plugin URI: https://xyzscripts.com/wordpress-plugins/linkedin-auto-publish/
Description:   Publish posts automatically from your blog to LinkedIn social media. You can publish your posts to LinkedIn as simple text message or as text message with attached image. The plugin supports filtering posts by custom post-types and categories.
Version: 1.9.2
Author: xyzscripts.com
Author URI: https://xyzscripts.com/
License: GPLv2 or later
Text Domain: linkedin-auto-publish
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
	_e('Hi there!  I'.'m just a plugin, not much I can do when called directly.','linkedin-auto-publish');
	exit;
}
if(isset($_POST) && isset($_POST['lnauth'] ) || (isset($_GET['page']) && ($_GET['page']=='linkedin-auto-publish-settings' || $_GET['page']=='linkedin-auto-publish-suggest-features')) )
{
	ob_start();
}

function plugin_load_lnaptextdomain() {
    load_plugin_textdomain( 'linkedin-auto-publish', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'plugin_load_lnaptextdomain' );

//error_reporting(E_ALL);
define('XYZ_LNAP_PLUGIN_FILE',__FILE__);
if (!defined('XYZ_SMAP_SOLUTION_AUTH_URL'))
	define('XYZ_SMAP_SOLUTION_AUTH_URL','https://authorize.smapsolutions.com/');
if (!defined('XYZ_SMAP_SOLUTION_LN_PUBLISH_URL'))
	define('XYZ_SMAP_SOLUTION_LN_PUBLISH_URL','https://li-publish.smapsolutions.com/');
global $wpdb;
include_once(ABSPATH.'wp-includes/version.php');
global $wp_version;
define('XYZ_WP_LNAP_WP_VERSION',$wp_version);
// $wpdb->query('SET SQL_MODE=""');

require_once( dirname( __FILE__ ) . '/admin/install.php' );
require_once( dirname( __FILE__ ) . '/xyz-functions.php' );
require_once( dirname( __FILE__ ) . '/admin/menu.php' );
require_once( dirname( __FILE__ ) . '/admin/destruction.php' );

require_once( dirname( __FILE__ ) . '/api/linkedin.php' );

require_once( dirname( __FILE__ ) . '/admin/ajax-backlink.php' );
require_once( dirname( __FILE__ ) . '/admin/metabox.php' );
require_once( dirname( __FILE__ ) . '/admin/publish.php' );
require_once( dirname( __FILE__ ) . '/admin/admin-notices.php' );
if(get_option('xyz_credit_link')=="lnap"){

	add_action('wp_footer', 'xyz_lnap_credit');

}
function xyz_lnap_credit() {
	$content = '<div style="clear:both;width:100%;text-align:center; font-size:11px; "><a target="_blank" title="WP to LinkedIn Auto Publish" href="https://xyzscripts.com/wordpress-plugins/linkedin-auto-publish/compare" >WP to LinkedIn Auto Publish</a> Powered By : <a target="_blank" title="PHP Scripts & Programs" href="http://www.xyzscripts.com" >XYZScripts.com</a></div>';
	echo $content;
}
if(!function_exists('get_post_thumbnail_id'))
	add_theme_support( 'post-thumbnails' );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'xyz_lnap_add_action_links' );
function xyz_lnap_add_action_links( $links ) {
	$xyz_lnap_links = array(
			'<a href="' . admin_url( 'admin.php?page=linkedin-auto-publish-settings' ) . '">Settings</a>',
	);
	return array_merge( $links, $xyz_lnap_links);
}
