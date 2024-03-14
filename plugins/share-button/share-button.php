<?php
/*
Plugin Name: Wordpress Share Buttons
Plugin URI: http://maxbuttons.com/share-button
Description: Wordpress Social Share Buttons lets you easily setup Social Share Buttons on your site
Version: 1.19
Author: Max Foundry
Author URI: http://maxfoundry.com
Text Domain: mbsocial
Domain Path: /languages

Copyright 2022 Max Foundry, LLC (https://maxfoundry.com)
*/

namespace MBSocial;

define("MBSOCIAL_ROOT_FILE", __FILE__);
define('MBSOCIAL_VERSION_NUM', '1.19');
define('MBSOCIAL_RELEASE',"1 December 2022");
define('MBSOCIAL_REQUIRED_MB', '9.4');

// load runtime.
require_once('classes/class-install.php');

add_action('plugins_loaded', function () {

	$status = Install::verifyPlugin(); // check if all requirements are met.
	if (! $status)
		return;

	require_once('classes/class-social.php');
	require_once('classes/class-admin.php');
	require_once('classes/class-social-networks.php');

	require_once('classes/class-styles.php');
	require_once('classes/class-style.php');
	require_once('classes/class-presets.php');
	require_once('classes/class-collections.php');
	require_once('classes/class-collection.php');
	require_once('classes/class-block.php');
	require_once('classes/class-fautils.php');
	require_once('classes/class-utils.php');

	require_once('classes/class-network.php');

	// patching class
	require_once('classes/class-patches.php');

	require_once('libraries/whistle.php');
	require_once('libraries/autoload/ClassLoader.php');
	require_once('libraries/Mobile_Detect.php');

	function MB() {
		return \MaxButtons\maxButtonsPlugin::getInstance();
	}

	$m = new mbSocialPlugin();
	$m->init();
});

if (! function_exists('MBSocial\MBSocial') )
{
	function MBSocial()
	{
		return mbSocialPlugin::getInstance();
	}
}
