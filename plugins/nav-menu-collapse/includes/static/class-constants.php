<?php
/*!
 * Plugin constants.
 *
 * @since 2.0.0
 *
 * @package    Nav Menu Collapse
 * @subpackage Constants
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement plugin constants.
 *
 * @since 2.0.0
 */
final class Nav_Menu_Collapse_Constants
{
	/**
	 * Plugin prefix.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const PREFIX = 'nmc_';

	/**
	 * Plugin token.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const TOKEN = 'nav_menu_collapse';

	/**
	 * Plugin versions.
	 *
	 * @since 2.1.1 Added previous version.
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const VERSION = '2.1.3';
	const VERSION_PREVIOUS = '2.1.2';
	
	/**
	 * Plugin hook names.
	 *
	 * @since 2.1.0 Added validate data hook.
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const HOOK_COLLAPSED = self::PREFIX . 'collapsed';
	const HOOK_SAVE_SETTINGS = self::PREFIX . 'save_settings';
	const HOOK_VALIDATE_DATA = self::PREFIX . 'validate_data';

	/**
	 * Plugin option names.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const OPTION_SETTINGS = self::TOKEN . '_settings';
	const OPTION_VERSION = self::TOKEN . '_version';

	/**
	 * Plugin setting names.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const SETTING_DELETE_SETTINGS = 'delete_settings';
	const SETTING_DELETE_USER_META = 'delete_user_meta';
	const SETTING_UNCONFIRMED = '_unconfirmed';

	/**
	 * Plugin URLs.
	 *
	 * @since 2.0.2 Changed donate link.
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const URL_BASE = 'https://noakesplugins.com/';
	const URL_DONATE = 'https://www.paypal.com/donate?hosted_button_id=BE5MGPAKBG8TQ&source=url';
	const URL_KB = self::URL_BASE . 'kb/nav-menu-collapse/';
	const URL_SUPPORT = 'https://wordpress.org/support/plugin/nav-menu-collapse/';
	const URL_REVIEW = self::URL_SUPPORT . 'reviews/?rate=5#new-post';
	const URL_TRANSLATE = 'https://translate.wordpress.org/projects/wp-plugins/nav-menu-collapse';

	/**
	 * Plugin user meta name.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const USER_META_COLLAPSED = self::TOKEN . '_collapsed';
}
