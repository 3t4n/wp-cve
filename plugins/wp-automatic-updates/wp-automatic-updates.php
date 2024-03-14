<?php

/**
 * @package wp-automatic-updates
 * @version 1.1.6
 */
/**
 * Plugin Name: WP Automatic Updates
 * Plugin URI: http://www.omaksolutions.com
 * Description: Themes, plugins and core updates made easier. If you would like to extend or modify this plugin for your custom WP setup, just contact the author. He will be more than happy to hear from you. <br/>E-mail at: <a href="mailto:ak.singla@hotmail.com">ak.singla@hotmail.com</a> | Skype: <a href="skype:ak.singla47?call">ak.singla47</a>
 * Author: ak.singla
 * Version: 1.1.6
 * Author URI: http://www.omaksolutions.com
 * Requires at least: 3.7
 * Tested up to: 4.8.9
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WP_Automatic_Updates
{
	// Define version
	const VERSION = '1.1.6';

	var $wpau_options;
	var $current_user_role;
	var $wpau_admin_page;

	function __construct()
	{
		$this->wpau_options = get_option('omak_wpau_options');

		// Default install settings
		register_activation_hook(__FILE__, array(&$this, 'wpau_install'));
		
		// Get plugin options
		$this->current_user_role = array(&$this, 'get_user_role');
		
		load_plugin_textdomain('wpau-plugin', false, 'wp-automatic-updates/languages');
		
		add_action('init', array(&$this, 'wpau_init_action'));
		add_action('admin_init', array(&$this, 'wpau_admin_init'));
	}

	function wpau_install()
	{	
		// First default install
		if($this->wpau_options === false)
		{
			$wpau_options = array(
				'notification_updates' => 1,
				'menu_updates' => 1,
				
				'minor_updates' => 1,
				'major_updates' => 0,
				
				'plugin_updates' => 1,
				'theme_updates' => 0,
				
				'translation_updates' => 1,
				'auto_core_update_send_email' => 1,
				'version' => self::VERSION
			);
		}
		
		// Update 1.0.2 and +
		else if(!isset($this->wpau_options['version']) || $this->wpau_options['version'] < self::VERSION)
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
			
			$wpau_options = $this->wpau_options;
	
			// Update 1.0.4 and +		
			if( version_compare($wpau_options['version'], '1.0', '<') ) {
				$wpau_options['auto_core_update_send_email'] = 1;
			}

			$wpau_options['version'] = self::VERSION;
			
		}

		update_option('omak_wpau_options', $wpau_options);
		
		if(version_compare(get_bloginfo('version'), '3.7', '<'))
		{
			deactivate_plugins(basename(__FILE__));
		}
	}


	function wpau_init_action()
	{
		// Change WordPress updates behaviors using wpau_options
		if (!isset($this->wpau_options['notification_updates']) || $this->wpau_options['notification_updates'] == 0)
		{
			add_action('admin_menu', array(&$this, 'wpau_notification_action'));
		}
		
		if (!isset($this->wpau_options['menu_updates']) || $this->wpau_options['menu_updates'] == 0)
		{
			add_action('admin_init', array(&$this, 'wpau_menu_updates_action'));
			add_action( 'wp_before_admin_bar_render', array(&$this, 'wpau_remove_admin_bar_updates_links'));
		}
		
		if (!isset($this->wpau_options['minor_updates']) || $this->wpau_options['minor_updates'] == 0)
		{
			add_filter( 'allow_minor_auto_core_updates', '__return_false' );
		}
		
		if (isset($this->wpau_options['major_updates']) && $this->wpau_options['major_updates'] == 1)
		{
			add_filter( 'allow_major_auto_core_updates', '__return_true' );
		}
		
		if (isset($this->wpau_options['plugin_updates']) && $this->wpau_options['plugin_updates'] == 1)
		{
			add_filter( 'auto_update_plugin', '__return_true' );
		}
		
		if (isset($this->wpau_options['theme_updates']) && $this->wpau_options['theme_updates'] == 1)
		{
			add_filter( 'auto_update_theme', '__return_true' );
		}
		
		if (!isset($this->wpau_options['translation_updates']) || $this->wpau_options['translation_updates'] == 0)
		{
			add_filter( 'auto_update_translation', '__return_false' );
		}
		
		if (!isset($this->wpau_options['auto_core_update_send_email']) || $this->wpau_options['auto_core_update_send_email'] == 0)
		{
			add_filter( 'auto_core_update_send_email', '__return_false' );
		}

		
		// Add admin menu
		add_action('admin_menu', array(&$this, 'register_wpau_menu_page'));
		
		// Give the plugin a settings link in the plugin overview
		add_filter('plugin_action_links', array(&$this, 'add_action_link'), 10, 2);
	}

	function add_action_link($links, $file)
	{
		static $this_plugin;
		
		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

		if ($file == $this_plugin){
			$settings_link = '<a href="options-general.php?page=' . $this_plugin . '">' . __('Settings', 'wpau-plugin') . '</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}

	function wpau_notification_action()
	{
		remove_action('admin_notices', 'update_nag', 3);
	}
	
	function wpau_menu_updates_action()
	{
		remove_submenu_page('index.php', 'update-core.php');
		wp_enqueue_style('wp-hide-updates-count', plugins_url( 'css/updates-count.css', __FILE__ ), array(), self::VERSION);
	}
	
	function wpau_remove_admin_bar_updates_links()
	{
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('updates');
	}
	
	function register_wpau_menu_page()
	{
		$this->wpau_admin_page = add_options_page(__('WP Updates', 'wpau-plugin'), __('WP Updates', 'wpau-plugin'), 'manage_options', __FILE__, array(&$this, 'wp_updates_manager_menu_page'));
		add_action('load-'.$this->wpau_admin_page, array(&$this, 'wpau_admin_add_help_tab'));
		add_action( 'admin_print_styles-' .$this->wpau_admin_page, array(&$this, 'wpau_admin_print_styles'));
	}
	
	function wpau_admin_print_styles() {
		wp_enqueue_style('admin-css', plugins_url( 'css/admin-css.css', __FILE__ ), array(), self::VERSION);
	}
	
	function wp_updates_manager_menu_page()
	{
		wp_enqueue_style('wp-automatic-updates', plugins_url( 'css/style.css', __FILE__ ), array(), self::VERSION);
		?>
		<div class="wrap">
		<?php screen_icon(); ?>
		<h2 style="text-align:center;"><?php _e('WP Automatic Updates Settings', 'wpau-plugin'); ?></h2>
		<br/>
		<p style="text-align:center;">Thanks for using the automatic updates plugin. This will make your life easier by automatically installing WordPress core, theme, plugin and translation updates.</p>
		<br/><br/>
		<form action="options.php" method="post">
		<?php settings_fields('omak_wpau_options'); ?>
		<?php do_settings_sections('wpau'); ?>
		<?php submit_button(); ?>
		</form>
		<?php
		?>
		</div>
		<?php
	}
	
	function wpau_admin_add_help_tab()
	{
		$screen = get_current_screen();

		if ($screen->id != $this->wpau_admin_page)
			return;

		$screen->add_help_tab( array(
			'id'	=> 'wpau_help_notification_tab',
			'title'	=> __('WordPress notification & menu updates', 'wpau-plugin'),
			'content'	=> '<p><ul><li>' . __('<strong>Updates notification</strong> are displayed by default to users. Uncheck this option to hide updates notifications. Check this option to restore default behavior.', 'wpau-plugin') . '</li>'
				. '<li>' . __('<strong>Administrator menu updates</strong> are displayed by default to Administrator users (see <a href="http://codex.wordpress.org/Roles_and_Capabilities" target="_blank">Roles and Capabilities in Wordpress Codex</a>). Uncheck this option remove Updates capabilities to Administrator users (not avaible for <a href="http://codex.wordpress.org/Glossary#Multisite" target="_blank">Multisite</a>). Check this option to restore default behavior.', 'wpau-plugin') . '</li></ul>'
				. '</p>',
		));

		$screen->add_help_tab( array(
			'id'	=> 'wpau_help_core_tab',
			'title'	=> __('WordPress core updates', 'wpau-plugin'),
			'content'	=> '<p>'. __('WordPress 3.7 (and more) use Automatic Background Updates (see <a href="http://codex.wordpress.org/Configuring_Automatic_Background_Updates" target="_blank">Configuring Automatic Background Updates</a>)', 'wpau-plugin')
				. '<ul><li>' . __('<strong>Minor core updates</strong> are enabled by default. Uncheck this option to disable WordPress Minor core updates. Check this option to restore default behavior.', 'wpau-plugin') . '</li>'
				. '<li>' . __('<strong>Major core updates</strong> are disabled by default. Check this option to enable WordPress Major core updates. Uncheck this option to restore default behavior.', 'wpau-plugin') . '</li></ul>'
				. '</p>',
		));
		
		$screen->add_help_tab( array(
			'id'	=> 'wpau_help_plugin_theme_tab',
			'title'	=> __('Plugin & Theme updates', 'wpau-plugin'),
			'content'	=> '<p>'. __('WordPress 3.7 (and more) use Automatic Background Updates (see <a href="http://codex.wordpress.org/Configuring_Automatic_Background_Updates" target="_blank">Configuring Automatic Background Updates</a>)', 'wpau-plugin')
				. '<ul><li>' . __('<strong>Plugin updates</strong> are enabled by default. Check this option to disable WordPress Plugin updates. Uncheck this option to restore default behavior.', 'wpau-plugin') . '</li>'
				. '<li>' . __('<strong>Theme updates</strong> are disabled by default. Check this option to enable WordPress Theme updates. Uncheck this option to restore default behavior.', 'wpau-plugin') . '</li></ul>'
				. '</p>',
		));
		
		$screen->add_help_tab( array(
			'id'	=> 'wpau_help_translation_updates_tab',
			'title'	=> __('Translation updates', 'wpau-plugin'),
			'content'	=> '<p>'. __('WordPress 3.7 (and more) use Automatic Background Updates (see <a href="http://codex.wordpress.org/Configuring_Automatic_Background_Updates" target="_blank">Configuring Automatic Background Updates</a>)', 'wpau-plugin')
				. '<ul><li>' . __('<strong>Translation updates</strong> are enabled by default. Uncheck this option to disable WordPress Translation updates. Check this option to restore default behavior.', 'wpau-plugin') . '</li></ul>'
				. '</p>',
		));
		
		$screen->add_help_tab( array(
			'id'	=> 'wpau_help_email_updates_tab',
			'title'	=> __('Email updates', 'wpau-plugin'),
			'content'	=> '<p>'. __('WordPress 3.7 (and more) use Automatic Background Updates (see <a href="http://codex.wordpress.org/Configuring_Automatic_Background_Updates" target="_blank">Configuring Automatic Background Updates</a>)', 'wpau-plugin')
				. '<ul><li>' . __('<strong>Email updates</strong> are enabled by default. Uncheck this option to disable WordPress Email updates. Check this option to restore default behavior.', 'wpau-plugin') . '</li></ul>'
				. '</p>',
		));
		
		$screen->add_help_tab( array(
			'id'	=> 'wpau_help_uninstall_tab',
			'title'	=> __('Uninstall', 'wpau-plugin'),
			'content'	=> '<p>'. __('Uninstall <strong>WP Updates Settings</strong> will restore default WordPress updates behaviors.', 'wpau-plugin') . '</p>',
		));
		
		$screen->set_help_sidebar(
			'<p><strong>'
			. __('For more information:', 'wpau-plugin')
			. '</strong></p>'
			. '<p>'
			. '<a href="http://wordpress.org/plugins/wp-automatic-updates/" target="_blank">' . __('Visit plugin page', 'wpau-plugin') . '</a>'
			. '</p>');
	}
	
	function wpau_admin_init()
	{
		register_setting('omak_wpau_options', 'omak_wpau_options', array(&$this, 'wpau_validate_options'));
	
		add_settings_section('wpau_notification', __('Notification & Menu Updates', 'wpau-plugin'),	array(&$this, 'wpau_notification_section_text'), 'wpau');
		add_settings_field('wpau_notification_updates', __('Enable update notifications', 'wpau-plugin'),				array(&$this, 'wpau_notification_updates_input'), 'wpau', 'wpau_notification');
		add_settings_field('wpau_menu_updates', __('Enable Admin menu updates<br/>(not available for <a href="http://codex.wordpress.org/Glossary#Multisite" target="_blank">Multisite</a>)', 'wpau-plugin'),				array(&$this, 'wpau_menu_updates_input'), 'wpau', 'wpau_notification');
	
		add_settings_section('wpau_core', __('WP Core Updates', 'wpau-plugin'),							array(&$this, 'wpau_core_section_text'), 'wpau');
		add_settings_field('wpau_minor_updates', __('Enable Minor core updates', 'wpau-plugin'),						array(&$this, 'wpau_minor_updates_input'), 'wpau', 'wpau_core');
		add_settings_field('wpau_major_updates', __('Enable Major core updates', 'wpau-plugin'),						array(&$this, 'wpau_major_updates_input'), 'wpau', 'wpau_core');
		
		add_settings_section('wpau_plugin_theme', __('Plugin & Theme Updates', 'wpau-plugin'),					array(&$this, 'wpau_plugin_theme_section_text'), 'wpau');
		add_settings_field('wpau_plugin_updates', __('Enable Plugin updates', 'wpau-plugin'),							array(&$this, 'wpau_plugin_updates_input'), 'wpau', 'wpau_plugin_theme');
		add_settings_field('wpau_theme_updates', __('Enable Theme updates', 'wpau-plugin'),								array(&$this, 'wpau_theme_updates_input'), 'wpau', 'wpau_plugin_theme');
	
		add_settings_section('wpau_translation', __('Translation Updates', 'wpau-plugin'),						array(&$this, 'wpau_translation_section_text'), 'wpau');
		add_settings_field('wpau_translation_updates', __('Enable Translation updates', 'wpau-plugin'),				array(&$this, 'wpau_translation_updates_input'), 'wpau', 'wpau_translation');
		
		add_settings_section('wpau_auto_core_email', __('Email updates', 'wpau-plugin'),						array(&$this, 'wpau_core_update_email_section_text'), 'wpau');
		add_settings_field('wpau_auto_update_email_updates', __('Send Email notifications', 'wpau-plugin'),				array(&$this, 'wpau_core_update_email_input'), 'wpau', 'wpau_auto_core_email');
	}
	
	function wpau_notification_section_text()
	{
		echo '<p class="section-text">';
			_e('By default, notification updates are displayed in Dashboard, Appearance menu and Plugins menu.', 'wpau-plugin');
		echo '</p>';
	}
	
	function wpau_core_section_text()
	{
		echo '<p class="section-text">';
			_e('By default, automatic updates are only enabled for minor core releases.', 'wpau-plugin');
		echo '</p>';
	}
	
	function wpau_plugin_theme_section_text()
	{
		echo '<p class="section-text">';
			_e('Automatic Plugin updates are enabled and Theme updates are disabled by default.', 'wpau-plugin');
		echo '</p>';
	}
	
	function wpau_translation_section_text()
	{
		echo '<p class="section-text">';
			_e('Check to allow automatic updates for translation files. Enabled by default.', 'wpau-plugin');
		echo '</p>';
	}
	
	function wpau_core_update_email_section_text()
	{
		echo '<p class="section-text">';
			_e('Automatic emails are sent only on majore core updates. Enabled by default.', 'wpau-plugin');
		echo '</p>';
	}
	
	function wpau_notification_updates_input()
	{
		$options = $this->wpau_options;
		$option_value = isset($options['notification_updates']) ? $options['notification_updates'] : 0;
		echo '<input type="checkbox" name="omak_wpau_options[notification_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}
	
	function wpau_menu_updates_input()
	{
		$options = $this->wpau_options;
		$option_value = isset($options['menu_updates']) ? $options['menu_updates'] : 0;
		echo '<input type="checkbox" name="omak_wpau_options[menu_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}
	
	function wpau_minor_updates_input()
	{
		$options = $this->wpau_options;
		$option_value = isset($options['minor_updates']) ? $options['minor_updates'] : 0;
		echo '<input type="checkbox" name="omak_wpau_options[minor_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}
	
	function wpau_major_updates_input()
	{
		$options = $this->wpau_options;
		$option_value = isset($options['major_updates']) ? $options['major_updates'] : 0;
		echo '<input type="checkbox" name="omak_wpau_options[major_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}
	
	function wpau_plugin_updates_input()
	{
		$options = $this->wpau_options;
		$option_value = isset($options['plugin_updates']) ? $options['plugin_updates'] : 0;
		echo '<input type="checkbox" name="omak_wpau_options[plugin_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}
	
	function wpau_theme_updates_input()
	{
		$options = $this->wpau_options;
		$option_value = isset($options['theme_updates']) ? $options['theme_updates'] : 0;
		echo '<input type="checkbox" name="omak_wpau_options[theme_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}
	
	function wpau_translation_updates_input()
	{
		$options = $this->wpau_options;
		$option_value = isset($options['translation_updates']) ? $options['translation_updates'] : 0;
		echo '<input type="checkbox" name="omak_wpau_options[translation_updates]" value="1" '.checked( $option_value, 1, false ).' />';
	}
	
	function wpau_core_update_email_input()
	{
		$options = $this->wpau_options;
		$option_value = isset($options['auto_core_update_send_email']) ? $options['auto_core_update_send_email'] : 0;
		echo '<input type="checkbox" name="omak_wpau_options[auto_core_update_send_email]" value="1" '.checked( $option_value, 1, false ).' />';
	}
	
	function wpau_validate_options($input)
	{
		$valid = array();
		
		if( isset( $input['notification_updates'] ) ) {
			if(filter_var($input['notification_updates'], FILTER_VALIDATE_BOOLEAN))
				$valid['notification_updates'] = $input['notification_updates'];
		}

		if( isset( $input['menu_updates'] ) ) {
			if(filter_var($input['menu_updates'], FILTER_VALIDATE_BOOLEAN))
				$valid['menu_updates'] = $input['menu_updates'];
		}
			
		if( isset( $input['minor_updates'] ) ) {
			if(filter_var($input['minor_updates'], FILTER_VALIDATE_BOOLEAN))
				$valid['minor_updates'] = $input['minor_updates'];
		}
			
		if( isset( $input['major_updates'] ) ) {
			if(filter_var($input['major_updates'], FILTER_VALIDATE_BOOLEAN))
				$valid['major_updates'] = $input['major_updates'];
		}
			
		if( isset( $input['plugin_updates'] ) ) {
			if(filter_var($input['plugin_updates'], FILTER_VALIDATE_BOOLEAN))
				$valid['plugin_updates'] = $input['plugin_updates'];
		}
			
		if( isset( $input['theme_updates'] ) ) {
			if(filter_var($input['theme_updates'], FILTER_VALIDATE_BOOLEAN))
				$valid['theme_updates'] = $input['theme_updates'];
		}
			
		if( isset( $input['translation_updates'] ) ) {
			if(filter_var($input['translation_updates'], FILTER_VALIDATE_BOOLEAN))
				$valid['translation_updates'] = $input['translation_updates'];
		}

		if( isset( $input['auto_core_update_send_email'] ) ) {
			if(filter_var($input['auto_core_update_send_email'], FILTER_VALIDATE_BOOLEAN))
				$valid['auto_core_update_send_email'] = $input['auto_core_update_send_email'];
		}
			
		$valid['version'] = self::VERSION;
	
		return $valid;
	}
}

$wp_automatic_updates = new WP_Automatic_Updates();
