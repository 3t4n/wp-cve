<?php
/*!
 * Settings functionality.
 *
 * @since 2.0.0
 *
 * @package    Nav Menu Collapse
 * @subpackage Settings
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the settings functionality.
 *
 * @since 2.0.0
 *
 * @uses Nav_Menu_Collapse_Wrapper
 */
final class Nav_Menu_Collapse_Settings extends Nav_Menu_Collapse_Wrapper
{
	/**
	 * Constructor function.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load_option();
		
		add_action('admin_menu', array($this, 'admin_menu'));
		
		add_filter('plugin_action_links_' . plugin_basename($this->base->plugin), array($this, 'plugin_action_links'));
	}
	
	/**
	 * Get a default value based on the provided name.
	 *
	 * @since 2.0.0
	 *
	 * @access protected
	 * @param  string $name Name of the value to return.
	 * @return mixed        Default value if it exists, otherwise an empty string.
	 */
	protected function _default($name)
	{
		switch ($name)
		{
			/**
			 * Settings page title.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'page_title':
			
				return __('Settings', 'nav-menu-collapse');
				
			/**
			 * True if collapse/expand states should be saved for each user.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case 'store_collapsed_states':
			
			/**
			 * True if plugin settings should be deleted when the plugin is uninstalled.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case Nav_Menu_Collapse_Constants::SETTING_DELETE_SETTINGS:
			
			/**
			 * True if plugin settings should be deleted when the plugin is uninstalled.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case Nav_Menu_Collapse_Constants::SETTING_DELETE_SETTINGS . Nav_Menu_Collapse_Constants::SETTING_UNCONFIRMED:
			
			/**
			 * True if plugin user meta should be deleted when the plugin is uninstalled.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case Nav_Menu_Collapse_Constants::SETTING_DELETE_USER_META:
			
			/**
			 * True if plugin user meta should be deleted when the plugin is uninstalled.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case Nav_Menu_Collapse_Constants::SETTING_DELETE_USER_META . Nav_Menu_Collapse_Constants::SETTING_UNCONFIRMED:
			
				return false;
		}

		return parent::_default($name);
	}

	/**
	 * Load the settings option.
	 *
	 * @since 2.0.2 Added option unslashing.
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array $settings Settings array to load, or null of the settings should be loaded from the database.
	 * @return void
	 */
	public function load_option($settings = null)
	{
		if (empty($settings))
		{
			$settings = wp_unslash(get_option(Nav_Menu_Collapse_Constants::OPTION_SETTINGS));
		}
		
		if (empty($settings))
		{
			$this->_value_collection = $this;
		}
		else
		{
			$this->_set_properties($settings);
		}
	}

	/**
	 * Add the settings menu item.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function admin_menu()
	{
		$settings_page = add_options_page(Nav_Menu_Collapse_Output::page_title($this->page_title), $this->base->cache->plugin_data['Name'], 'manage_options', Nav_Menu_Collapse_Constants::OPTION_SETTINGS, array($this, 'settings_page'));

		if ($settings_page)
		{
			Nav_Menu_Collapse_Output::add_tab('options-general.php', Nav_Menu_Collapse_Constants::OPTION_SETTINGS, $this->page_title);
			Nav_Menu_Collapse_Output::add_tab('nav-menus.php', '', __('Nav Menus', 'nav-menu-collapse'));
			
			add_action('load-' . $settings_page, array($this, 'load_settings_page'));
		}
	}

	/**
	 * Output the settings page.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function settings_page()
	{
		Nav_Menu_Collapse_Output::admin_form_page($this->page_title, Nav_Menu_Collapse_Constants::HOOK_SAVE_SETTINGS, Nav_Menu_Collapse_Constants::OPTION_SETTINGS);
	}

	/**
	 * Load settings page functionality.
	 * 
	 * @since 2.1.2 Removed PHP_INT_MAX reference.
	 * @since 2.1.0 Changed hook priority and added data structure validation.
	 * @since 2.0.3 Changed uninstall setting labels.
	 * @since 2.0.1 Changed screen setup hook name.
	 * @since 2.0.0
	 * 
	 * @access public
	 * @return void
	 */
	public function load_settings_page()
	{
		/**
		 * Setup the screen for Noakes Development Tools.
		 *
		 * @since 2.0.0
		 *
		 * @param  array $suffix Page suffix to use when resetting the screen.
		 * @return void
		 */
		do_action('ndt_screen_setup');
		
		add_action('admin_enqueue_scripts', array('Nav_Menu_Collapse_Global', 'admin_enqueue_scripts'), 9999999);
		
		add_screen_option
		(
			'layout_columns',

			array
			(
				'default' => 2,
				'max' => 2
			)
		);

		Nav_Menu_Collapse_Help::output('settings');
		
		$this->prepare_meta_boxes();

		Nav_Menu_Collapse_Meta_Box::side_meta_boxes();
		Nav_Menu_Collapse_Meta_Box::finalize_meta_boxes();
	}
	
	/**
	 * Prepare the settings form meta boxes.
	 * 
	 * @since 2.1.0
	 * 
	 * @access public
	 * @return void
	 */
	public function prepare_meta_boxes()
	{
		$plugin_name = $this->base->cache->plugin_data['Name'];
		$value_collection = $this->_get_value_collection();
		
		$save_all_settings = array
		(
			'button_label' => __('Save All Settings', 'nav-menu-collapse'),
		);
		
		new Nav_Menu_Collapse_Meta_Box(array
		(
			'context' => 'normal',
			'id' => 'general_settings',
			'option_name' => Nav_Menu_Collapse_Constants::OPTION_SETTINGS,
			'title' => __('General Settings', 'nav-menu-collapse'),
			'value_collection' => $value_collection,
			
			'fields' => array
			(
				new Nav_Menu_Collapse_Field_Checkbox(array
				(
					'field_label' => __('If checked, collapsed states for each menu will be stored on a user-by-user basis.', 'nav-menu-collapse'),
					'label' => __('Store Collapsed States', 'nav-menu-collapse'),
					'name' => 'store_collapsed_states'
				)),
				
				new Nav_Menu_Collapse_Field_Submit($save_all_settings)
			)
		));
		
		$uninstall_settings_box = Nav_Menu_Collapse_Field_Checkbox::add_confirmation
		(
			new Nav_Menu_Collapse_Meta_Box(array
			(
				'context' => 'normal',
				'id' => 'uninstall_settings',
				'option_name' => Nav_Menu_Collapse_Constants::OPTION_SETTINGS,
				'title' => __('Uninstall Settings', 'nav-menu-collapse'),
				'value_collection' => $value_collection
			)),
			
			sprintf
			(
				_x('Delete settings for %1$s when the plugin is uninstalled.', 'Plugin Name', 'nav-menu-collapse'),
				$plugin_name
			),
			
			__('Delete Settings', 'nav-menu-collapse'),
			Nav_Menu_Collapse_Constants::SETTING_DELETE_SETTINGS,
			$this->{Nav_Menu_Collapse_Constants::SETTING_DELETE_SETTINGS}
		);
		
		$uninstall_settings_box = Nav_Menu_Collapse_Field_Checkbox::add_confirmation
		(
			$uninstall_settings_box,
			
			sprintf
			(
				_x('Delete user meta for %1$s when the plugin is uninstalled.', 'Plugin Name', 'nav-menu-collapse'),
				$plugin_name
			),
			
			__('Delete User Meta', 'nav-menu-collapse'),
			Nav_Menu_Collapse_Constants::SETTING_DELETE_USER_META,
			$this->{Nav_Menu_Collapse_Constants::SETTING_DELETE_USER_META}
		);
		
		$uninstall_settings_box->add_fields(new Nav_Menu_Collapse_Field_Submit($save_all_settings));
	}

	/**
	 * Add settings to the plugin action links.
	 *
	 * @since 2.1.0 Added non-breaking space before dashicon.
	 * @since 2.0.2 Added Dashicon to link.
	 * @since 2.0.1 Removed escape from admin URL.
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array $links Existing action links.
	 * @return array        Modified action links.
	 */
	public function plugin_action_links($links)
	{
		array_unshift($links, '<a class="dashicons-before dashicons-admin-tools" href="' . get_admin_url(null, 'options-general.php?page=' . Nav_Menu_Collapse_Constants::OPTION_SETTINGS) . '">&nbsp;' . $this->page_title . '</a>');

		return $links;
	}
}
