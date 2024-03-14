<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework {

	if (!defined('ABSPATH'))
	{
		exit; // Exit if accessed directly.
	}

	use FPFramework\API\API;
	use FPFramework\Base\Renderer;
	use FPFramework\Base\HelperMiddleware;
	use FPFramework\Base\Ajax;
	use FPFramework\Libs\Translations;
	use FPFramework\Libs\Media;
	use FPFramework\Libs\GoogleFontsRenderer;

	final class Framework
	{
		/**
		 * Holds the Framework instance.
		 *
		 * @var  Framework  $instance
		 */
		public static $instance = null;
		
		/**
		 * AJAX
		 * 
		 * @var  AJAX  $ajax
		 */
		public $ajax;
		
		/**
		 * API
		 * 
		 * @var  API  $api
		 */
		public $api;
		
		/**
		 * Renders views.
		 * 
		 * @var  Renderer  $renderer
		 */
		public $renderer;

		/**
		 * THe Plugin name attached to the framework.
		 * 
		 * @var  String  $plugin_name
		 */
		public $plugin_name;

		/**
		 * All Translations.
		 * 
		 * @var  Translations  $translations
		 */
		public $translations;

		/**
		 * Registers and enqueuees styles and scripts.
		 * 
		 * @var  Media  $media
		 */
		public $media;

		/**
		 * Helper Classes
		 * 
		 * @var  HelperMiddleware
		 */
		public $helper;

		private function __construct($plugin_name)
		{
			$this->plugin_name = $plugin_name;

			// Translations
			$this->translations = new Translations();

			// run init
			$this->init();
			$this->admin_init();
		}

		/**
		 * Returns the current plugin name
		 * 
		 * @return  string
		 */
		public function getPluginPage()
		{
			return isset($_GET['page']) ? sanitize_text_field($_GET['page']) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		/**
		 * Returns the instance of the Framework given the ID
		 * 
		 * @param   string     $name
		 *
		 * @return  Framework  An instance of the class.
		 */
		public static function getInstance($id)
		{
			if (is_null(self::$instance))
			{
				self::$instance = new self($id);
			}

			return self::$instance;
		}

		/**
		 * Initializes all Common components used by front-end and back-end.
		 * 
		 * @return  void
		 */
		public function initCommons()
		{
			$this->ajax = new Ajax();

			// API
			$this->api = new API();

			// Renderer
			$this->renderer = new Renderer(FPF_LAYOUTS_DIR);

			// Media
			$this->media = new Media();

			// Helper
			$this->helper = new HelperMiddleware();

			new Includes\AllowedCSSTags();

			GoogleFontsRenderer::getInstance()->render();
		}

		/**
		 * Initializes all components used by back-end.
		 * 
		 * @return  void
		 */
		public function initBackendComponents()
		{
			// run actions
			$this->handleAdminActions();
		}

		/**
		 * Runs all Admin Actions
		 * 
		 * @return  void
		 */
		private function handleAdminActions()
		{
			// display admin notice
			$adminNotice = new \FPFramework\Libs\AdminNotice();
			add_action('fpframework/admin/notices', [$adminNotice, 'displayAdminNotice']);

			add_action('admin_enqueue_scripts', [$this, 'registerMedia'], 20);
		}

		public function registerMedia()
		{
			wp_register_style('fpframework_roboto_font', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap', [], null);
			wp_enqueue_style('fpframework_roboto_font');
		}

		/**
		 * Initializes Framework with the required components
		 * 
		 * @return  void
		 */
		public function init()
		{
			$this->setConstants();
			$this->initCommons();
		}

		/**
		 * Sets constants.
		 * 
		 * @return  void
		 */
		public function setConstants()
		{
			/**
			 * Sets the FPF_MEDIA_URL to the Framework Media directory URL.
			 * 
			 * The following will ensure we load the Framework Media URL from the plugin
			 * that has the latest framework version.
			 */
			// Get the plugin the framework is running from (its the plugin that has the latest framework version)
			$latest_framework_plugin = basename(dirname(dirname(dirname(__DIR__))));

			/**
			 * Set from which plugin we will actually be loading the framework media files.
			 * 
			 * - If its fireplugins, we are developing locally, use the plugin name that called the framework, all are symlinked and have the latest version
			 * - If its any other plugin, use it as its the one that has the latest framework version.
			 */
			$plugin_slug = $latest_framework_plugin === 'fireplugins' ? $this->plugin_name : $latest_framework_plugin;

			// Framework Media URL
			if (!defined('FPF_MEDIA_URL'))
			{
				define('FPF_MEDIA_URL', plugins_url(strtolower($plugin_slug)) . '/Inc/Framework/media/');
			}
		}


		/**
		 * Initializes Framework with the required components for admins
		 * 
		 * @return  void
		 */
		public function admin_init()
		{
			if (!is_admin())
			{
				return;
			}

			$this->initBackendComponents();
		}

		/**
		 * Retrieves the translation of $text
		 * 
		 * @param  String  $text
		 * @param  String  $fallback
		 * 
		 * @return  String
		 */
		public function _($text, $fallback = null)
		{
			return $this->translations->_($text, $fallback);
		}
	}
}

namespace {
	/**
	 * The function which returns the one Framework instance.
	 * 
	 * @param   string     $plugin_slug
	 * 
	 * @return  Framework
	 */
	function fpframework($plugin_slug = '')
	{
		return FPFramework\Framework::getInstance($plugin_slug);
	}
}