<?php
/*
Plugin Name: JoomSport Achievements
Plugin URI: http://joomsport.com
Description: Sport league plugin
Version: 1.5.2
Author: BearDev
Author URI: http://BearDev.com
License: OSLv3
Requires at least: 4.0
Text Domain: joomsport-achievements
Domain Path: /languages/
*/

/* Copyright 2016
BearDev, JB SOFT LLC, BY (sales@beardev.com)
This program is free licensed software; 

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
//error_reporting(E_ALL);
//ini_set("display_errors", 1); 
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
define('JOOMSPORT_ACHIEVEMENTS_PATH', plugin_dir_path( __FILE__ ));
define('JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES', JOOMSPORT_ACHIEVEMENTS_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);
define('JOOMSPORT_ACHIEVEMENTS_PATH_HELPERS', JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES  . 'helpers' . DIRECTORY_SEPARATOR);
require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'joomsport-achievments-admin-install.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'joomsport-achievments-templates.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'joomsport-achievments-post-types.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_HELPERS . 'joomsport-achievments-helper-ef.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_HELPERS . 'joomsport-achievments-helper-selectbox.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_HELPERS . 'joomsport-achievments-helper-object.php';

/*require_once JOOMSPORT_ACHIEVEMENTS_PATH_HELPERS . 'joomsport-helper-selectbox.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_HELPERS . 'joomsport-helper-ef.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_HELPERS . 'joomsport-helper-objects.php';
require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . '3d'. DIRECTORY_SEPARATOR . 'gallery-metabox-master' . DIRECTORY_SEPARATOR . 'gallery.php';
*/
require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . '3d'. DIRECTORY_SEPARATOR . 'gallery-metabox-master' . DIRECTORY_SEPARATOR . 'gallery.php';

register_activation_hook(__FILE__, array('JoomSportAchievmentsAdminInstall', '_installdb') );


