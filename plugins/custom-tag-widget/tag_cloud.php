<?php
/*
Plugin Name: Tag Widget
Plugin URI: http://incbrite.com/
Description: A tag cloud plugin for WordPress to give you more flexibility with the styling of your tag cloud.
Author: Clayton McIlrath
Version: 1.0.4
Author URI: http://thinkclay.com/

	Copyright (c) 2012 Clayton McIlrath (http://thinkclay.com)
	Tag Widget is released under the GNU General Public License (GPL)
	http://www.gnu.org/licenses/gpl.txt
*/

/* Load Helper Functions */
define('MOD_ROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

require_once MOD_ROOT.'base.php';

/* Load Template Tag Config Page */
require_once MOD_ROOT.'admin_page.php';

/* Load WP Sidebar Widget */
if (class_exists('WP_Widget')) 
{
	include MOD_ROOT.'widget_28.php';
} 
else {
	include MOD_ROOT.'widget.php';
}

register_activation_hook(__FILE__, 'install_defs');
register_deactivation_hook(__FILE__, 'uninstall_defs');
?>