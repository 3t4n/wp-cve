<?php

/**
 * Plugin Name: WP Updates Settings
 * Plugin URI: http://wordpress.org/plugins/wp-updates-settings/
 * Description: Configure WordPress updates settings through UI (User Interface).
 * Author: Yslo
 * Text Domain: wp-updates-settings
 * Domain Path: /languages
 * Version: 1.1.4
 * Author URI: http://profiles.wordpress.org/yslo
 * Requires at least: 3.7
 * Tested up to: 4.9
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WP_Updates_Settings
{
	// Define version
	const VERSION = '1.1.4';

	var $wpus_options;
	var $current_user_role;
	var $wpus_admin_page;

	function __construct()
	{
		$this->wpus_options = get_option('yslo_wpus_options');

		// Default install settings
		register_activation_hook(__FILE__, array(&$this, 'wpus_install'));

		// Get plugin options
		$this->current_user_role = array(&$this, 'get_user_role');

		load_plugin_textdomain('wp-updates-settings', false, 'wp-updates-settings/languages');

		add_action('init', array(&$this, 'wpus_init_action'));
		add_action('admin_init', array(&$this, 'wpus_admin_init'));
	}

	function wpus_install()
	{
		// First default install
		if($this->wpus_options === false)
		{
			$wpus_options = array(
				'notification_updates' => 1,
				'menu_updates' => 1,
				'minor_updates' => 1,
				'translation_updates' => 1,
				'auto_core_update_send_email' => 1,
				'version' => self::VERSION
			);
		}

		// Update 1.0.2 and +
		else if(!isset($this->wpus_options['version']) || $this->wpus_options['version'] < self::VERSION)
		{
			if(current_user_can('delete_plugins'))
			{
				$role =& get_role('administrator');
				if(!empty($role))
				{
						$role->add_cap('update_core');
						$role->add_cap('update_themes');
						$role->add_cap('update_plugins');
				}
			}

			$wpus_options = $this->wpus_options;

			// Update 1.0.4 and +
			if( version_compare($wpus_options['version'], '1.1.0', '<') ) {
				$wpus_options['auto_core_update_send_email'] = 1;
			}

			$wpus_options['version'] = self::VERSION;

		}

		update_option('yslo_wpus_options', $wpus_options);

		if(version_compare(get_bloginfo('version'), '3.7', '<'))
		{
			deactivate_plugins(basename(__FILE__));
		}
	}


	function wpus_init_action()
	{
		// Change WordPress updates behaviors using wpus_options
		if (!isset($this->wpus_options['notification_updates']) || $this->wpus_options['notification_updates'] == 0)
		{
			add_action('admin_menu', array(&$this, 'wpus_notification_action'));
		}

		if (!isset($this->wpus_options['menu_updates']) || $this->wpus_options['menu_updates'] == 0)
		{
			add_action('admin_init', array(&$this, 'wpus_menu_updates_action'));
			add_action( 'wp_before_admin_bar_render', array(&$this, 'wpus_remove_admin_bar_updates_links'));
		}

		if (!isset($this->wpus_options['minor_updates']) || $this->wpus_options['minor_updates'] == 0)
		{
			add_filter( 'allow_minor_auto_core_updates', '__return_false' );
		}

		if (isset($this->wpus_options['major_updates']) && $this->wpus_options['major_updates'] == 1)
		{
			add_filter( 'allow_major_auto_core_updates', '__return_true' );
		}

		if (isset($this->wpus_options['plugin_updates']) && $this->wpus_options['plugin_updates'] == 1)
		{
			add_filter( 'auto_update_plugin', '__return_true' );
		}

		if (isset($this->wpus_options['theme_updates']) && $this->wpus_options['theme_updates'] == 1)
		{
			add_filter( 'auto_update_theme', '__return_true' );
		}

		if (!isset($this->wpus_options['translation_updates']) || $this->wpus_options['translation_updates'] == 0)
		{
			add_filter( 'auto_update_translation', '__return_false' );
		}

		if (!isset($this->wpus_options['auto_core_update_send_email']) || $this->wpus_options['auto_core_update_send_email'] == 0)
		{
			add_filter( 'auto_core_update_send_email', '__return_false' );
		}


		// Add admin menu
		add_action('admin_menu', array(&$this, 'register_wpus_menu_page'));

		// Give the plugin a settings link in the plugin overview
		add_filter('plugin_action_links', array(&$this, 'add_action_link'), 10, 2);
	}

	function add_action_link($links, $file)
	{
		static $this_plugin;

		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

		if ($file == $this_plugin){
			$settings_link = '<a href="options-general.php?page=' . $this_plugin . '">' . __('Settings', 'wp-updates-settings') . '</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}

	function wpus_notification_action()
	{
		remove_action('admin_notices', 'update_nag', 3);
	}

	function wpus_menu_updates_action()
	{
		remove_submenu_page('index.php', 'update-core.php');
		wp_enqueue_style('wp-hide-updates-count', plugins_url( 'css/updates-count.css', __FILE__ ), array(), self::VERSION);
	}

	function wpus_remove_admin_bar_updates_links()
	{
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('updates');
	}

	function register_wpus_menu_page()
	{
		$this->wpus_admin_page = add_options_page(__('Updates', 'wp-updates-settings'), __('Updates', 'wp-updates-settings'), 'manage_options', __FILE__, array(&$this, 'wp_updates_manager_menu_page'));
		add_action('load-'.$this->wpus_admin_page, array(&$this, 'wpus_admin_add_help_tab'));
	}

	function wp_updates_manager_menu_page()
	{
		wp_enqueue_style('wp-updates-settings', plugins_url( 'css/style.css', __FILE__ ), array(), self::VERSION);
		?>
		<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e('Updates Settings', 'wp-updates-settings'); ?></h2>
		<form action="options.php" method="post">
		<?php settings_fields('yslo_wpus_options'); ?>
		<?php do_settings_sections('wpus'); ?>
		<?php submit_button(); ?>
		</form>
		<?php
		?>
		</div>
		<?php
	}

	function wpus_admin_add_help_tab()
	{
		$screen = get_current_screen();

		if ($screen->id != $this->wpus_admin_page)
			return;

		$screen->add_help_tab( array(
			'id'	=> 'wpus_help_notification_tab',
			'title'	=> __('WordPress notification & menu updates', 'wp-updates-settings'),
			'content'	=> '<p><ul><li>' . __('<strong>Updates notification</strong> are displayed by default to users. Uncheck this option to hide updates notifications. Check this option to restore default behavior.', 'wp-updates-settings') . '</li>'
				. '<li>' . __('<strong>Administrator menu updates</strong> are displayed by default to Administrator users (see <a href="http://codex.wordpress.org/Roles_and_Capabilities" target="_blank">Roles and Capabilities in Wordpress Codex</a>). Uncheck this option remove Updates capabilities to Administrator users (not avaible for <a href="http://codex.wordpress.org/Glossary#Multisite" target="_blank">Multisite</a>). Check this option to restore default behavior.', 'wp-updates-settings') . '</li></ul>'
				. '</p>',
		));

		$screen->add_help_tab( array(
			'id'	=> 'wpus_help_core_tab',
			'title'	=> __('WordPress core updates', 'wp-updates-settings'),
			'content'	=> '<p>'. __('WordPress 3.7 (and more) use Automatic Background Updates (see <a href="http://codex.wordpress.org/Configuring_Automatic_Background_Updates" target="_blank">Configuring Automatic Background Updates</a>)', 'wp-updates-settings')
				. '<ul><li>' . __('<strong>Minor core updates</strong> are enabled by default. Uncheck this option to disable WordPress Minor core updates. Check this option to restore default behavior.', 'wp-updates-settings') . '</li>'
				. '<li>' . __('<strong>Major core updates</strong> are disabled by default. Check this option to enable WordPress Major core updates. Uncheck this option to restore default behavior.', 'wp-updates-settings') . '</li></ul>'
				. '</p>',
		));

		$screen->add_help_tab( array(
			'id'	=> 'wpus_help_plugin_theme_tab',
			'title'	=> __('Plugin & Theme updates', 'wp-updates-settings'),
			'content'	=> '<p>'. __('WordPress 3.7 (and more) use Automatic Background Updates (see <a href="http://codex.wordpress.org/Configuring_Automatic_Background_Updates" target="_blank">Configuring Automatic Background Updates</a>)', 'wp-updates-settings')
				. '<ul><li>' . __('<strong>Plugin updates</strong> are disabled by default. Check this option to enable WordPress Plugin updates. Uncheck this option to restore default behavior.', 'wp-updates-settings') . '</li>'
				. '<li>' . __('<strong>Theme updates</strong> are disabled by default. Check this option to enable WordPress Theme updates. Uncheck this option to restore default behavior.', 'wp-updates-settings') . '</li></ul>'
				. '</p>',
		));

		$screen->add_help_tab( array(
			'id'	=> 'wpus_help_translation_updates_tab',
			'title'	=> __('Translation updates', 'wp-updates-settings'),
			'content'	=> '<p>'. __('WordPress 3.7 (and more) use Automatic Background Updates (see <a href="http://codex.wordpress.org/Configuring_Automatic_Background_Updates" target="_blank">Configuring Automatic Background Updates</a>)', 'wp-updates-settings')
				. '<ul><li>' . __('<strong>Translation updates</strong> are enabled by default. Uncheck this option to disable WordPress Translation updates. Check this option to restore default behavior.', 'wp-updates-settings') . '</li></ul>'
				. '</p>',
		));

		$screen->add_help_tab( array(
			'id'	=> 'wpus_help_email_updates_tab',
			'title'	=> __('Email updates', 'wp-updates-settings'),
			'content'	=> '<p>'. __('WordPress 3.7 (and more) use Automatic Background Updates (see <a href="http://codex.wordpress.org/Configuring_Automatic_Background_Updates" target="_blank">Configuring Automatic Background Updates</a>)', 'wp-updates-settings')
				. '<ul><li>' . __('<strong>Email updates</strong> are enabled by default. Uncheck this option to disable WordPress Email updates. Check this option to restore default behavior.', 'wp-updates-settings') . '</li></ul>'
				. '</p>',
		));

		$screen->add_help_tab( array(
			'id'	=> 'wpus_help_uninstall_tab',
			'title'	=> __('Uninstall', 'wp-updates-settings'),
			'content'	=> '<p>'. __('Uninstall <strong>WP Updates Settings</strong> will restore default WordPress updates behaviors.', 'wp-updates-settings') . '</p>',
		));

		$screen->set_help_sidebar(
			'<p><strong>'
			. __('For more information:', 'wp-updates-settings')
			. '</strong></p>'
			. '<p>'
			. '<a href="http://wordpress.org/plugins/wp-updates-settings/" target="_blank">' . __('Visit plugin page', 'wp-updates-settings') . '</a>'
			. '</p>');
	}

	function wpus_admin_init()
	{
		register_setting('yslo_wpus_options', 'yslo_wpus_options', array(&$this, 'wpus_validate_options'));

		add_settings_section('wpus_notification', __('WordPress notification & menu updates', 'wp-updates-settings'),	array(&$this, 'wpus_notification_section_text'), 'wpus');
		add_settings_field('wpus_notification_updates', __('Updates notification', 'wp-updates-settings'),				array(&$this, 'wpus_notification_updates_input'), 'wpus', 'wpus_notification');
		add_settings_field('wpus_menu_updates', __('Administrator menu updates (not available for <a href="http://codex.wordpress.org/Glossary#Multisite" target="_blank">Multisite</a>)', 'wp-updates-settings'),				array(&$this, 'wpus_menu_updates_input'), 'wpus', 'wpus_notification');

		add_settings_section('wpus_core', __('WordPress core updates', 'wp-updates-settings'),							array(&$this, 'wpus_core_section_text'), 'wpus');
		add_settings_field('wpus_minor_updates', __('Minor core updates', 'wp-updates-settings'),						array(&$this, 'wpus_minor_updates_input'), 'wpus', 'wpus_core');
		add_settings_field('wpus_major_updates', __('Major core updates', 'wp-updates-settings'),						array(&$this, 'wpus_major_updates_input'), 'wpus', 'wpus_core');

		add_settings_section('wpus_plugin_theme', __('Plugin & Theme updates', 'wp-updates-settings'),					array(&$this, 'wpus_plugin_theme_section_text'), 'wpus');
		add_settings_field('wpus_plugin_updates', __('Plugin updates', 'wp-updates-settings'),							array(&$this, 'wpus_plugin_updates_input'), 'wpus', 'wpus_plugin_theme');
		add_settings_field('wpus_theme_updates', __('Theme updates', 'wp-updates-settings'),								array(&$this, 'wpus_theme_updates_input'), 'wpus', 'wpus_plugin_theme');

		add_settings_section('wpus_translation', __('Translation updates', 'wp-updates-settings'),						array(&$this, 'wpus_translation_section_text'), 'wpus');
		add_settings_field('wpus_translation_updates', __('Translation updates', 'wp-updates-settings'),				array(&$this, 'wpus_translation_updates_input'), 'wpus', 'wpus_translation');

		add_settings_section('wpus_auto_core_email', __('Email updates', 'wp-updates-settings'),						array(&$this, 'wpus_core_update_email_section_text'), 'wpus');
		add_settings_field('wpus_auto_update_email_updates', __('Email updates', 'wp-updates-settings'),				array(&$this, 'wpus_core_update_email_input'), 'wpus', 'wpus_auto_core_email');
	}

	function wpus_notification_section_text()
	{
		_e('By default, notification updates are displayed in Dashboard, Appearance menu and Plugins menu.', 'wp-updates-settings');
	}

	function wpus_core_section_text()
	{
		_e('By default, automatic updates are only enabled for minor core releases.', 'wp-updates-settings');
	}

	function wpus_plugin_theme_section_text()
	{
		_e('Automatic plugin and theme updates are disabled by default.', 'wp-updates-settings');
	}

	function wpus_translation_section_text()
	{
		_e('Automatic translation file updates are already enabled by default.', 'wp-updates-settings');
	}

	function wpus_core_update_email_section_text()
	{
		_e('Automatic emails are sent on updates. This features is enabled by default.', 'wp-updates-settings');
	}

	function wpus_notification_updates_input()
	{
		$options = $this->wpus_options;
		$option_value = isset($options['notification_updates']) ? $options['notification_updates'] : 0;
		echo '<input type="checkbox" name="yslo_wpus_options[notification_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wpus_menu_updates_input()
	{
		$options = $this->wpus_options;
		$option_value = isset($options['menu_updates']) ? $options['menu_updates'] : 0;
		echo '<input type="checkbox" name="yslo_wpus_options[menu_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wpus_minor_updates_input()
	{
		$options = $this->wpus_options;
		$option_value = isset($options['minor_updates']) ? $options['minor_updates'] : 0;
		echo '<input type="checkbox" name="yslo_wpus_options[minor_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wpus_major_updates_input()
	{
		$options = $this->wpus_options;
		$option_value = isset($options['major_updates']) ? $options['major_updates'] : 0;
		echo '<input type="checkbox" name="yslo_wpus_options[major_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wpus_plugin_updates_input()
	{
		$options = $this->wpus_options;
		$option_value = isset($options['plugin_updates']) ? $options['plugin_updates'] : 0;
		echo '<input type="checkbox" name="yslo_wpus_options[plugin_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wpus_theme_updates_input()
	{
		$options = $this->wpus_options;
		$option_value = isset($options['theme_updates']) ? $options['theme_updates'] : 0;
		echo '<input type="checkbox" name="yslo_wpus_options[theme_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wpus_translation_updates_input()
	{
		$options = $this->wpus_options;
		$option_value = isset($options['translation_updates']) ? $options['translation_updates'] : 0;
		echo '<input type="checkbox" name="yslo_wpus_options[translation_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wpus_core_update_email_input()
	{
		$options = $this->wpus_options;
		$option_value = isset($options['auto_core_update_send_email']) ? $options['auto_core_update_send_email'] : 0;
		echo '<input type="checkbox" name="yslo_wpus_options[auto_core_update_send_email]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wpus_validate_options($input)
	{
		$valid = array();

		if(isset($input['notification_updates']) && filter_var($input['notification_updates'], FILTER_VALIDATE_BOOLEAN))
			$valid['notification_updates'] = $input['notification_updates'];

		if(isset($input['menu_updates']) && filter_var($input['menu_updates'], FILTER_VALIDATE_BOOLEAN))
			$valid['menu_updates'] = $input['menu_updates'];

		if(isset($input['minor_updates']) && filter_var($input['minor_updates'], FILTER_VALIDATE_BOOLEAN))
			$valid['minor_updates'] = $input['minor_updates'];

		if(isset($input['major_updates']) && filter_var($input['major_updates'], FILTER_VALIDATE_BOOLEAN))
			$valid['major_updates'] = $input['major_updates'];

		if(isset($input['plugin_updates']) && filter_var($input['plugin_updates'], FILTER_VALIDATE_BOOLEAN))
			$valid['plugin_updates'] = $input['plugin_updates'];

		if(isset($input['theme_updates']) && filter_var($input['theme_updates'], FILTER_VALIDATE_BOOLEAN))
			$valid['theme_updates'] = $input['theme_updates'];

		if(isset($input['translation_updates']) && filter_var($input['translation_updates'], FILTER_VALIDATE_BOOLEAN))
			$valid['translation_updates'] = $input['translation_updates'];

		if(isset($input['auto_core_update_send_email']) && filter_var($input['auto_core_update_send_email'], FILTER_VALIDATE_BOOLEAN))
			$valid['auto_core_update_send_email'] = $input['auto_core_update_send_email'];

		$valid['version'] = self::VERSION;

		return $valid;
	}
}

$wp_updates_settings = new WP_Updates_Settings();
