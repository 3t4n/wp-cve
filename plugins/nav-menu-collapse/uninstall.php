<?php
/*!
 * Functionality for plugin uninstallation.
 * 
 * @since 2.0.3 Minor MySQL query cleanup.
 * @since 2.0.2 Added option unslashing.
 * @since 2.0.1 Improved condition and changed faux uninstall definition.
 * @since 2.0.0
 * 
 * @package Nav Menu Collapse
 */

if
(
	!defined('WP_UNINSTALL_PLUGIN')
	&&
	!defined('NDT_FAUX_UNINSTALL_PLUGIN')
)
{
	exit;
}

global $wpdb;

require_once(dirname(__FILE__) . '/includes/static/class-constants.php');

$settings = wp_unslash(get_option(Nav_Menu_Collapse_Constants::OPTION_SETTINGS));
$deleted = 0;

if
(
	isset($settings[Nav_Menu_Collapse_Constants::SETTING_DELETE_SETTINGS])
	&&
	$settings[Nav_Menu_Collapse_Constants::SETTING_DELETE_SETTINGS]
)
{
	delete_option(Nav_Menu_Collapse_Constants::OPTION_SETTINGS);
	
	$deleted++;
}

if
(
	isset($settings[Nav_Menu_Collapse_Constants::SETTING_DELETE_USER_META])
	&&
	$settings[Nav_Menu_Collapse_Constants::SETTING_DELETE_USER_META]
)
{
	$wpdb->query($wpdb->prepare
	(
		"DELETE FROM 
			$wpdb->usermeta 
		WHERE 
			meta_key LIKE %s;\n",
			
		'%' . $wpdb->esc_like(Nav_Menu_Collapse_Constants::TOKEN) . '%'
	));
	
	$deleted++;
}

if ($deleted === 2)
{
	delete_option(Nav_Menu_Collapse_Constants::OPTION_VERSION);
}
