<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core
{
	if (!defined('ABSPATH'))
	{
		exit; // Exit if accessed directly.
	}

	use FPFramework\Framework;
	use FPFramework\Admin\Includes\MetaboxManager;
	use FPFramework\Base\Renderer;
	
	use FireBox\Core\Admin\Admin;
	use FireBox\Core\Admin\Includes\Library;
	use FireBox\Core\Admin\Includes\Metaboxes as PluginMetaboxes;
	use FireBox\Core\Admin\Includes\Cpts\Cpts;
	use FireBox\Core\Admin\Menu\PluginMenu;
	use FireBox\Core\FB\Box;
	use FireBox\Core\FB\Boxes;
	use FireBox\Core\FB\Log;
	use FireBox\Core\FB\Track;
	use FireBox\Core\Shortcodes\Shortcodes;
	use FireBox\Core\Libs\Translations;

	/**
	 * This class is responsible for initializing FireBox.
	 * 
	 * It registers all required components needed for the plugin to run.
	 */
	final class Plugin
	{
		/**
		 * Holds the admin plugin.
		 *
		 * @var  Admin
		 */
		public $admin;

		/**
		 * Library
		 *
		 * @var  Library
		 */
		public $library;

		/**
		 * Tables
		 * 
		 * @var  Tables
		 */
		public $tables;

		/**
		 * Custom Post Types
		 * 
		 * @var  CPTS
		 */
		public $cpts;

		/**
		 * Renderer
		 * 
		 * @var  Renderer
		 */
		public $renderer;

		/**
		 * Plugin Metaboxes
		 * 
		 * @var  Metaboxes
		 */
		public $plugin_metaboxes;

		/**
		 * Metaboxes Manager
		 * 
		 * @var  MetaboxManager
		 */
		public $metaboxes_manager;

		/**
		 * Box
		 * 
		 * @var  Box
		 */
		public $box;

		/**
		 * Boxes
		 * 
		 * @var  Boxes
		 */
		public $boxes;

		/**
		 * The WP Admin Menu of the Plugin
		 * 
		 * @var  Menu
		 */
		public $menu;

		/**
		 * Helper Classes
		 * 
		 * @var  HelperMiddleware
		 */
		public $helper;

		/**
		 * Gutenberg blocks
		 * 
		 * @var  Blocks
		 */
		public $blocks;

		/**
		 * Widgets
		 * 
		 * @var  Widgets
		 */
		public $widgets;

		/**
		 * Shortcodes
		 * 
		 * @var  Shortcodes
		 */
		public $shortcodes;

		/**
		 * Track
		 * 
		 * @var  Track
		 */
		public $track;

		/**
		 * log
		 * 
		 * @var  Log
		 */
		public $log;

		/**
		 * PHP Scripts
		 * 
		 * @var  PHPScripts
		 */
		public $phpscripts;
		#PRO-END

		/**
		 * Translations
		 * 
		 * @var  Translations
		 */
		public $translations;

		/**
		 * Tables used by Hook
		 * 
		 * @var  array
		 */
		public $hook_data = [
			'tables' => [ 'firebox_logs', 'firebox_logs_details', 'firebox_submissions', 'firebox_submission_meta' ],
			'activation' => FBOX_PLUGIN_DIR . 'Inc/Core/Admin/sql/firebox.sql',
			'uninstall' => FBOX_PLUGIN_DIR . 'Inc/Core/Admin/sql/uninstall.firebox.sql'
		];

		/**
		 * The plugin name
		 * 
		 * @var  String
		 */
		public $plugin_name = 'FireBox';

		/**
		 * The plugin slug
		 * 
		 * @var  String
		 */
		public $plugin_slug = 'firebox';

		/**
		 * Holds the plugin instance
		 *
		 * @var Plugin $instance
		 */
		public static $instance = null;

		private function __construct()
		{
			$this->preFlight();

			// run init
			add_action('fpf_init', [$this, 'init']);

			// admin init
			add_action('fpf_admin_init', [$this, 'admin_init']);
			
			// admin menu
			add_action('admin_menu', [$this, 'admin_menu']);
		}

		/**
		 * Admin Menu
		 * 
		 * @return  void
		 */
		public function admin_menu()
		{
			$this->menu = new PluginMenu();
			$this->menu->init();
		}

		/**
		 * Admin Init
		 * 
		 * @return  void
		 */
		public function admin_init()
		{
			
			new \FPFramework\Admin\Includes\UpgradeProModal($this->plugin_slug, $this->plugin_name);
			

			// Admin
			$this->admin = new Admin();

			// Library
			$this->library = new Library();

			// Metaboxes
			$this->metaboxes_manager = new MetaboxManager();
			$this->plugin_metaboxes = new PluginMetaboxes();
			$this->metaboxes_manager->init();
		}

		/**
		 * Runs at the start of the Plugin
		 * 
		 * @return  void
		 */
		public function preFlight()
		{
			// set translations
			$this->translations = new Libs\Translations();
			
			// loads text domain
			add_action('plugins_loaded', [$this, 'loadTextdomain'], 10);

			// show rate reminder after user has activated the plugin
			add_action('activated_plugin', [$this, 'set_rate_reminder']);

			// Widgets
			$this->widgets = new Widgets();
		}

		/**
		 * Set rate reminder transient on plugin activation.
		 *
		 * @return  void
		 */
		public function set_rate_reminder()
		{
			if( get_transient( 'fpf_firebox_rate_reminder_deleted' ) || get_transient( 'fpf_firebox_rate_reminder' ) )
			{
				return;
			}

			// make rate reminder appear 2 days after the plugin has been activated.
			$date = new \DateTime();
			$date->add( new \DateInterval( 'P2D' ) );
			set_transient( 'fpf_firebox_rate_reminder', $date->format( 'Y-m-d' ) );
		}

		/**
		 * Ensures only one instance of the plugin class is loaded or can be loaded
		 *
		 * @return  Plugin  An instance of the class.
		 */
		public static function instance()
		{
			if (is_null(self::$instance))
			{
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Loads the textdomain
		 * 
		 * @return  void
		 */
		public function loadTextdomain()
		{
			load_plugin_textdomain('firebox', false, FBOX_PLUGIN_DIR . 'languages/');
		}

		/**
		 * Initializes all Common components used by front-end and back-end.
		 * 
		 * @return  void
		 */
		private function initCommons()
		{
			new AdminBarMenu();

			// Tables
			$this->tables = new Tables();

			// Shortcodes
			$this->shortcodes = new Shortcodes();

			// Log
			$this->log = new Log();

			// REST API
			new RESTAPI();
			
			

			// Custom Post Types
			$this->cpts = new Cpts();
			$this->cpts->init();
			
			// Renderer
			$this->renderer = new Renderer(FBOX_LAYOUTS_DIR);

			// Box
			$this->box = new Box();

			// Boxes
			$this->boxes = new Boxes();

			// Helper
			$this->helper = new HelperMiddleware();

			// Gutenberg Blocks
			$this->blocks = new Blocks();

			// Forms AJAX
			new \FireBox\Core\Form\Ajax();

			// Analytics AJAX
			new \FireBox\Core\Analytics\Ajax();

			// Track
			$this->track = new Track();
		}

		/**
		 * Initializes FireBox Plugin with the required components
		 * 
		 * @return  void
		 */
		public function init()
		{
			// init framework
			\FPFramework\Framework::getInstance($this->plugin_slug);
			
			// common classes (both front/back end)
			$this->initCommons();
		
			// Load front-end
			new Frontend();
		}

		/**
		 * Append our Plugin translation class to the set of translation classes of the Framework
		 * 
		 * @param   array
		 * 
		 * @return  array
		 */
		public function setTranslationsPaths($paths)
		{
			if (isset($paths['firebox']))
			{
				return $paths;
			}
			
			$paths['firebox'] = [
				'prefix' => 'FB_'
			];

			return $paths;
		}

		/**
		 * Retrieves the translation of $text
		 * 
		 * @param  string  $text
		 * @param  string  $fallback
		 * 
		 * @return  string
		 */
		public function _($text, $fallback = null)
		{
			return $this->translations->_($text, $fallback);
		}
	}
}

namespace {
	/**
	 * The function which returns the one and only FireBox instance.
	 * 
	 * @return  Plugin
	 */
	function firebox()
	{
		return FireBox\Core\Plugin::instance();
	}
}