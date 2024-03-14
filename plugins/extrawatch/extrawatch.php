<?php
/*
Plugin Name: ExtraWatch (See your visitors in real time, on a map)
Plugin URI: http://www.extrawatch.com
Description: <strong>See visits and clicks on your website in real-time</strong>! Features: <strong>Visitor Real time Stats</strong>, <strong>Visitor paths</strong>, <strong>Location on a map</strong>, <strong>Most popular pages</strong>, <strong>Top referring pages</strong>.
Version: 4.0.53
Author: CodeGravity.com
Author URI: http://www.extrawatch.com
*/

/**
 * @file
 * ExtraWatch - Real-time visitor dashboard and stats
 * @package ExtraWatch
 * @version 4.0
 * @revision 53
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2021 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

const EXTRAWATCH_PLUGIN_ACTIVATE = 'ExtraWatch-activate-status';
const EXTRAWATCH_PLUGIN_ACTIVATE_VALUE = 'activated';

require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "extrawatch-config.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "cms" . DIRECTORY_SEPARATOR . "ExtraWatchWordpressSpecific.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "ExtraWatchURLHelper.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "ExtraWatchController.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "ExtraWatchRequestHelper.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "ExtraWatchRenderer.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "ExtraWatchLogin.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "ExtraWatchAPI.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "ExtraWatchPrerequisites.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "ExtraWatchProject.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "ExtraWatchSettings.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "auth" . DIRECTORY_SEPARATOR . "ExtraWatchOAuth2Request.php");
require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. "ew-plg-common" . DIRECTORY_SEPARATOR . "auth" . DIRECTORY_SEPARATOR . "ExtraWatchAuth.php");



register_activation_hook( __FILE__, 'extrawatch_activate' );
register_deactivation_hook( __FILE__, 'extrawatch_deactivate' );
register_uninstall_hook( __FILE__, 'extrawatch_uninstall' );

add_action('plugins_loaded', 'extrawatch_menu');


function extrawatch_activate() {
}

function extrawatch_deactivate() {
    $extraWatchWordpressSpecific = new ExtraWatchWordpressSpecific();
    $extraWatchWordpressSpecific->deleteOptionTempPassword();
    $extraWatchWordpressSpecific->deletePluginOptionProjectId();
}

function extrawatch_uninstall() {
}


function getExtraWatchURL() {
    $extraWatchWordpress = new ExtraWatchWordpressSpecific();
    return $extraWatchWordpress->getPluginsURL();
}

function extrawatch_menu() {
    $extraWatchURL = getExtraWatchURL();

    if(!@function_exists("add_menu_page")) {
        $wpBase = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..");
        require_once($wpBase.DIRECTORY_SEPARATOR."wp-admin".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."plugin.php");
    }


    $EC_userLevel = 'level_7';
    if (function_exists("add_menu_page")) {
        add_menu_page('ExtraWatch', 'ExtraWatch', $EC_userLevel, 'extrawatch', 'extrawatch_page', $extraWatchURL.'/assets/extrawatch-logo-16x16.png');
    }

}

function getExtraWatchWordpressSiteURL() {
    $extraWatchWordpress = new ExtraWatchWordpressSpecific();
    $url = urlencode($extraWatchWordpress->getCMSURL());
    return $url;
}

function extrawatch_page() {
    $extraWatchController = new ExtraWatchController(new ExtraWatchWordpressSpecific());

    echo $extraWatchController->controlLivePage();

}

function extrawatch_track_code() {
    $extraWatchController = new ExtraWatchController(new ExtraWatchWordpressSpecific());
    echo $extraWatchController->controlTrackingCode();
}


function extrawatch_load_plugin() {
    if(is_admin())  {
        if (get_option(EXTRAWATCH_PLUGIN_ACTIVATE)== EXTRAWATCH_PLUGIN_ACTIVATE_VALUE) {
            delete_option(EXTRAWATCH_PLUGIN_ACTIVATE);
        }
    }
}

function extrawatch_register_options_page() {
    $folder=basename(dirname(preg_replace('/\\\\/','/',__FILE__)));
    add_submenu_page($folder,__("Settings","extrawatch-settings"),__("Settings","extrawatch-settings"),'manage_options','extrawatch-settings','extrawatch_options');
}

add_action('admin_menu', 'extrawatch_register_options_page');
add_action('wp_head', 'extrawatch_track_code');
add_action('admin_init','extrawatch_load_plugin');

function extrawatch_options() {
    $extraWatchSettings = new ExtraWatchSettings(new ExtraWatchWordpressSpecific());

    if($extraWatchSettings->isSettingsSaveTriggered()) {
        $extraWatchSettings->saveSettings();
    }
    $extraWatchSettings->renderExtraWatchSettings();

}




include("extrawatch-social.php");

