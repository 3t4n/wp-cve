<?php
/*
 * Plugin Name: VBOUT Wordpress Plugin
 * Plugin URI: https://developers.vbout.com/thirdparty/plugins
 * Description: VBOUT Dashboard integration with wordpress
 * Author: VBOUT Inc.
 * Version: 1.2.6.7
 * Author URI: https://developers.vbout.com/
 * License: GPLv2
 */

register_activation_hook( __FILE__, array( 'VboutWP', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'VboutWP', 'on_deactivation' ) );
//register_uninstall_hook( __FILE__, array( 'VboutWP', 'on_uninstall' ) );

//require_once ABSPATH . "wp-admin/includes/plugin.php";
//require_once ABSPATH . "wp-includes/pluggable.php";

define("VBOUT_URL", plugins_url('', __FILE__));
define("VBOUT_DIR", WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "marketing-automation");

require_once VBOUT_DIR . "/vbout-src/services/ApplicationWS.php";
require_once VBOUT_DIR . "/vbout-src/services/SocialMediaWS.php";
require_once VBOUT_DIR . "/vbout-src/services/EmailMarketingWS.php";
require_once VBOUT_DIR . "/vbout-src/services/WebsiteTrackWS.php";
require_once VBOUT_DIR . "/includes/Vbout.php";

add_action('init','VboutWP_init');

function VboutWP_init(){

	VboutWP::process();

	if (current_user_can("publish_posts")) {
		if (basename($_SERVER['SCRIPT_FILENAME']) == "options.php" && $_POST['action'] == "update" && $_POST['option_page'] == "vbout-connect") {
			///	VERIFY KEYS AND POPULATE API VARIABLES
			VboutWP::checkApiStatus();
		} elseif (basename($_SERVER['SCRIPT_FILENAME']) == "options.php" && $_POST['action'] == "update" && $_POST['option_page'] == "vbout-settings") {
			VboutWP::updateExtraOptions();
		} elseif (basename($_SERVER['SCRIPT_FILENAME']) == "options.php" && $_POST['action'] == "update" && $_POST['option_page'] == "vbout-schedule") {
			VboutWP::sendToVbout();
		} elseif (basename($_SERVER['SCRIPT_FILENAME']) == "options.php" && $_POST['action'] == "refresh" && $_POST['option_page'] == "vbout-settings") {
			VboutWP::refreshSettings();
		}

		VboutWP::adminInit();	
	}
	VboutWP::fillDropDownData();
}
