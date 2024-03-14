<?php
/*!
 * Base plugin functionality.
 *
 * @since 2.0.0
 *
 * @package    Nav Menu Collapse
 * @subpackage Base
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the base plugin functionality.
 *
 * @since 2.0.0
 *
 * @uses Nav_Menu_Collapse_Wrapper
 */
final class Nav_Menu_Collapse extends Nav_Menu_Collapse_Wrapper
{
	/**
	 * Main instance of Nav_Menu_Collapse.
	 *
	 * @since 2.0.0
	 *
	 * @access private static
	 * @var    Nav_Menu_Collapse
	 */
	private static $_instance = null;

	/**
	 * Returns the main instance of Nav_Menu_Collapse.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string          $file Main plugin file.
	 * @return Nav_Menu_Collapse       Main Nav_Menu_Collapse instance. 
	 */
	public static function _get_instance($file)
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new self($file);
		}

		return self::$_instance;
	}

	/**
	 * Base name for the plugin.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    string
	 */
	public $plugin;

	/**
	 * Global cache object.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    Nav_Menu_Collapse_Cache
	 */
	public $cache;

	/**
	 * Global settings object.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    Nav_Menu_Collapse_Settings
	 */
	public $settings;

	/**
	 * Global nav menus object.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    Nav_Menu_Collapse_Nav_Menus
	 */
	public $nav_menus;

	/**
	 * Global AJAX object.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    Nav_Menu_Collapse_AJAX
	 */
	public $ajax;

	/**
	 * Constructor function.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  string $file Main plugin file.
	 * @return void
	 */
	public function __construct($file)
	{
		if
		(
			!empty($file)
			&&
			file_exists($file)
		)
		{
			$this->plugin = $file;

			add_action('plugins_loaded', array($this, 'plugins_loaded'));
		}
	}

	/**
	 * Load the plugin functionality.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function plugins_loaded()
	{
		if (Nav_Menu_Collapse_Plugins::check_version('noakes-menu-manager/noakes-menu-manager.php', '3.0.0', '<'))
		{
			add_filter('plugin_action_links_' . plugin_basename($this->plugin), array($this, 'plugin_action_links'), 11);
		}
		else
		{
			$this->cache = new Nav_Menu_Collapse_Cache();
			$this->settings = new Nav_Menu_Collapse_Settings();
			$this->nav_menus = new Nav_Menu_Collapse_Nav_Menus();
			$this->ajax = new Nav_Menu_Collapse_AJAX();

			add_action('admin_init', array('Nav_Menu_Collapse_Setup', 'check_version'), 0);
			add_action('init', array($this, 'init'));
		}
		
		add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);
	}

	/**
	 * Add action links to the plugin list.
	 * 
	 * @since 2.0.1 Removed escape from admin URL.
	 * @since 2.0.0
	 * 
	 * @access public
	 * @param  array $links Existing action links.
	 * @return array        Modified action links.
	 */
	public function plugin_action_links($links)
	{
		array_unshift($links, '<a class="thickbox open-plugin-details-modal" data-title="' . esc_attr__('Nav Menu Manager', 'iem-clients-plus') . '" href="' . admin_url('plugin-install.php?tab=plugin-information&plugin=noakes-menu-manager&TB_iframe=true&width=772&height=871') . '">' . __('Update Nav Menu Manager', 'nav-menu-collapse') . '</a>');

		return $links;
	}

	/**
	 * Initialize the plugin.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function init()
	{
		load_plugin_textdomain('nav-menu-collapse', false, dirname(plugin_basename($this->plugin)) . '/languages/');
	}

	/**
	 * Add links to the plugin page.
	 *
	 * @since 2.1.0 Removed 'noreferrer' from links and added non-breaking space before dashicons.
	 * @since 2.0.2 Added Dashicons to links.
	 * @since 2.0.1 Improved condition.
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array  $links Default links for the plugin.
	 * @param  string $file  Main plugin file name.
	 * @return array         Modified links for the plugin.
	 */
	public function plugin_row_meta($links, $file)
	{
		return ($file === plugin_basename($this->plugin))
		? array_merge
		(
			$links,

			array
			(
				'<a class="dashicons-before dashicons-sos" href="' . Nav_Menu_Collapse_Constants::URL_SUPPORT . '" rel="noopener" target="_blank">&nbsp;' . __('Support', 'nav-menu-collapse') . '</a>',
				'<a class="dashicons-before dashicons-star-filled" href="' . Nav_Menu_Collapse_Constants::URL_REVIEW . '" rel="noopener" target="_blank">&nbsp;' . __('Review', 'nav-menu-collapse') . '</a>',
				'<a class="dashicons-before dashicons-translation" href="' . Nav_Menu_Collapse_Constants::URL_TRANSLATE . '" rel="noopener" target="_blank">&nbsp;' . __('Translate', 'nav-menu-collapse') . '</a>',
				'<a class="dashicons-before dashicons-coffee" href="' . Nav_Menu_Collapse_Constants::URL_DONATE . '" rel="noopener" target="_blank">&nbsp;' . __('Donate', 'nav-menu-collapse') . '</a>'
			)
		)
		: $links;
	}
}
