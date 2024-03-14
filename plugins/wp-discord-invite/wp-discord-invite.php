<?php
/**
 * Plugin Name:       WP Discord Invite
 * Plugin URI:        https://plugins.sarveshmrao.in/wp-discord-invite
 * Description:       Easily add vanity URL in your WP Site
 * Version:           2.5.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Sarvesh M Rao
 * Author URI:        https://www.sarveshmrao.in/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * This file is included with WP Discord Invite WordPress Plugin (https://wordpress.com/plugins/wp-discord-invite), Developed by Sarvesh M Rao (https://sarveshmrao.in/).
 * This file is licensed under Generl Public License v2 (GPLv2)  or later.
 * Using the code on whole or in part against the license can lead to legal prosecution.
 * 
 * Sarvesh M Rao
 * https://sarveshmrao.in/
 */

if (!defined("ABSPATH")) {
  exit();
}

// Configuring Plugin Row Meta
require_once('includes/pluginRowMeta.php');

// Catching URL defined in settings
require_once('includes/urlCatching.php');

// Inserting color picker for admin menu
require_once('includes/colorPicker.php');

// Registering Admin Menus
require_once('includes/registerMenu.php');

// Registering Settings
require_once('includes/settings.php');

// Settings Config Page
require_once('includes/settingsPage.php');


// Click Count Page
require_once('includes/countPage.php');

// Help Page (To be removed in next major release)
// require_once('includes/helpPage.php');

// Important Functions
require_once('includes/utils.php');

?>
