<?php
/*
Plugin Name: TPG Get Posts
Plugin URI: http://www.tpginc.net/wordpress-plugins/
Description: Adds a shortcode tag to display posts on static page.
Version: 3.6.5
Author: Criss Swaim
Author URI: http://www.tpginc.net/
Text Domain: tpg-get-posts
Domain Path: /languages/
License: This software is licensed under <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html">GNU GPL</a> version 2.0 or later.

Description:  TPG Get Postsadds a shortcode tag 'tpg-get-posts' to display posts within a static page or another post.
*/


/*
 * Main controller for tpg-get-posts
 *
 * @package WordPress
 * @subpackage tpg-get-posts
 * @since 2.8
 *
 * determine if the plugin is being invoked in the frontend or backend and
 * load only the functions needed for that process
 *
 * the tpg-get-post class sets up the base class that is extended for
 * either the frontend or backend processing.
 *
 */

//error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);

// get base class
if (!class_exists("tpg_get_posts")) {
    require_once plugin_dir_path(__FILE__)."inc/class-tpg-get-posts.php";
}

//get plugin options & set paths
$gp = new tpg_get_posts(plugin_dir_url(__FILE__),plugin_dir_path(__FILE__),plugin_basename(__FILE__));

//get class factory
if (!class_exists("tpg_gp_factory")) {
    require_once($gp->gp_paths["dir"]."inc/class-tpg-gp-factory.php");
}
// load appropriate class based on admin or front-end
if(is_admin()){
    // load backend class function
    $tpg_gp_admin = tpg_gp_factory::create_admin($gp->gp_opts,$gp->gp_paths);
    if ($tpg_gp_admin->gp_opts['valid-lic'] && $tpg_gp_admin->gp_opts['active-in-backend']) {
		$tpg_gp_process = tpg_gp_factory::create_process($gp->gp_opts,$gp->gp_paths);
    }

}else{

    // load front-end class functions
    $tpg_gp_process = tpg_gp_factory::create_process($gp->gp_opts,$gp->gp_paths);

    //load custom functions
    if (file_exists($gp->gp_paths['theme']."user-get-posts-custom-functions.php")) {
        include($gp->gp_paths['theme']."user-get-posts-custom-functions.php");
    }
}

/*
 *	add reference to language file
 * @package WordPress
 * @subpackage tpg_get_posts
 * @since 3.3
 *
 * load the textdomain for tpg-get-posts
 *
 */

add_action('init', 'tpg_get_posts_init');
function tpg_get_posts_init()
{
// Localization
load_plugin_textdomain('tpg-get-posts', false, dirname(plugin_basename(__FILE__)).'/languages/');
}

/*
 *	display a message to update the extension
 * @package WordPress
 * @subpackage tpg_get_posts
 * @since 3.3
 *
 * check for the valid lic and missing file or old version and display a message to update plugin extension
 *
 */
add_action('admin_notices','tpg_update_notice');
function tpg_update_notice() {
	if (!class_exists("tpg_get_posts")) {
		require_once plugin_dir_path(__FILE__)."inc/class-tpg-get-posts.php";
	}

	//get plugin options & set paths
	$gp = new tpg_get_posts(plugin_dir_url(__FILE__),plugin_dir_path(__FILE__),plugin_basename(__FILE__));

	//get class factory
	if (!class_exists("tpg_gp_factory")) {
		require_once($gp->gp_paths["dir"]."inc/class-tpg-gp-factory.php");
	}
	$tpg_gp_admin = tpg_gp_factory::create_admin($gp->gp_opts,$gp->gp_paths,true);
//	$updt_msg = $tpg_gp_admin->check_for_update();
//	if ($updt_msg) {
//		echo '<div id="message" class="updated"><p><strong>' . sprintf(__('An update to ver %s for the tpg-get-posts extension is available. Go to Settings tab to update.','tpg-get-posts'),$tpg_gp_admin->v_store) . '</strong></p></div>';
//	}
}

?>
