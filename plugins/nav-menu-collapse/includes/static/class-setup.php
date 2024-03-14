<?php
/*!
 * Plugin setup functionality.
 *
 * @since 2.0.0
 *
 * @package    Nav Menu Collapse
 * @subpackage Setup
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the setup functionality.
 *
 * @since 2.0.0
 */
final class Nav_Menu_Collapse_Setup
{
	/**
	 * Check and update the plugin version.
	 *
	 * @since 2.1.1 Added previous version constant.
	 * @since 2.0.2 Improved version sanitization.
	 * @since 2.0.1 Improved condition and changed force previous version definition.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @return void
	 */
	public static function check_version()
	{
		$current_version =
		(
			!defined('NDT_FORCE_PREVIOUS_VERSION')
			||
			!NDT_FORCE_PREVIOUS_VERSION
		)
		? wp_unslash(get_option(Nav_Menu_Collapse_Constants::OPTION_VERSION))
		: Nav_Menu_Collapse_Constants::VERSION_PREVIOUS;

		if (empty($current_version))
		{
			add_option(Nav_Menu_Collapse_Constants::OPTION_VERSION, sanitize_text_field(Nav_Menu_Collapse_Constants::VERSION));
		}
		else if ($current_version !== Nav_Menu_Collapse_Constants::VERSION)
		{
			update_option(Nav_Menu_Collapse_Constants::OPTION_VERSION, sanitize_text_field(Nav_Menu_Collapse_Constants::VERSION));

			if (version_compare($current_version, '2.0.0', '<'))
			{
				self::_pre_two_zero_zero($current_version);
			}
		}
	}

	/**
	 * Clean up plugin settings for plugin versions earlier than 2.0.0.
	 * 
 	 * @since 2.0.3 Minor MySQL query cleanup.
	 * @since 2.0.2 Added option unslashing.
	 * @since 2.0.0
	 * 
	 * @access private static
	 * @param  string $current_version Current version of the plugin.
	 * @return void
	 */
	private static function _pre_two_zero_zero($current_version)
	{
		global $wpdb;
		
		$wpdb->query($wpdb->prepare
		(
			"UPDATE 
				$wpdb->usermeta 
			SET 
				meta_key = %s 
			WHERE 
				meta_key = 'nmc_collapsed';\n",
				
			Nav_Menu_Collapse_Constants::USER_META_COLLAPSED
		));
		
		$plugin_settings = wp_unslash(get_option(Nav_Menu_Collapse_Constants::OPTION_SETTINGS));
		
		if (is_array($plugin_settings))
		{
			if (version_compare($current_version, '1.1.0', '<'))
			{
				$plugin_settings = self::_pre_one_one_zero($plugin_settings);
			}

			unset($plugin_settings['disable_help_buttons']);
			unset($plugin_settings['disable_help_tabs']);

			update_option(Nav_Menu_Collapse_Constants::OPTION_SETTINGS, $plugin_settings);

			Nav_Menu_Collapse()->settings->load_option($plugin_settings);
		}
	}

	/**
	 * Clean up plugin settings for plugin versions earlier than 1.1.0.
	 * 
	 * @since 2.0.0
	 * 
	 * @access private static
	 * @param  array $plugin_settings Loaded plugin settings.
	 * @return array                  Modified plugin settings.
	 */
	private static function _pre_one_one_zero($plugin_settings)
	{
		$plugin_settings['store_collapsed_states'] = '1';
		
		return $plugin_settings;
	}
}
