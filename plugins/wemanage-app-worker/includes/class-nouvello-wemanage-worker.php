<?php

/**
 * Nouvello WeManage Worker Main Class
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Core
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


if (!class_exists('Nouvello_WeManage_Worker')) :

	/**
	 * Main Plugin Class.
	 *
	 * @since 1.0
	 */
	final class Nouvello_WeManage_Worker
	{

		/**
		 * The singleton instance of the Nouvello_WeManage_Worker object.
		 *
		 * @static
		 * @access public
		 * @var null|object
		 */
		public static $instance = null;

		/**
		 * The plugin folder name.
		 *
		 * @static
		 * @access public
		 * @var string
		 */
		public static $plugin_folder_name = '';

		/**
		 * Declare all dynamic properties here for enticipated PHP 9 support.
		 */
		public $init;
		public $api;
		public $webhooks;
		public $visitor_counter;
		public $chat;
		public $leads;
		public $utm;
		public $oauth;
		public $wc_api;
		public $wc_api_functions;

		/**
		 * Main Instance - Singleton.
		 *
		 * Insures that only one instance of Nouvello_WeManage_Worker plugin exists in memory at any one time.
		 *
		 * @since 1.0
		 * @static array $instance
		 * @see ps_splent()
		 * @return object|Nouvello_WeManage_Worker
		 */
		public static function instance()
		{
			if (!isset(self::$instance) && !(self::$instance instanceof Nouvello_WeManage_Worker)) {
				self::$instance = new Nouvello_WeManage_Worker();
				self::$instance->setup_constants(); // setup constants.
				self::$instance->includes(); // load required files.
				self::$instance->init             = new Nouvello_WeManage_Worker_Init();
				self::$instance->api              = new Nouvello_WeManage_Worker_Api();
				self::$instance->webhooks         = new Nouvello_WeManage_Worker_Webhooks();
				self::$instance->visitor_counter  = new Nouvello_WeManage_Worker_Visitor_Counter();
				self::$instance->chat             = new Nouvello_WeManage_Worker_Chat();
				self::$instance->leads            = new Nouvello_WeManage_Worker_Leads();
				self::$instance->utm              = new Nouvello_WeManage_Worker_Utm();
			}
			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function includes()
		{
			require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-init.php';
			require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-api.php';
			require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-webhooks.php';
			require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-visitor-counter.php';
			require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-chat.php';
			require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-leads.php';
			require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker-utm.php';
		}

		/**
		 * Setup plugin constants.
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function setup_constants()
		{
			if (!defined('WEMANAGE_SERVER_URL')) {
				define('WEMANAGE_SERVER_URL', 'https://run.wemanage.app');
			}
			if (!defined('WEMANAGE_PLUGIN_FOLDER')) {
				$url = NSWMW_ROOT_PATH;
				$tokens = explode('/', $url);
				define('WEMANAGE_PLUGIN_FOLDER', $tokens[count($tokens) - 2]);
			}
		}
	} // end of class

endif; // end if class exist.

/**
 * Return the main instance of the class
 *
 * @return [object] [main instance of the class]
 */
function nouvello_wemanage_worker()
{
	return Nouvello_WeManage_Worker::instance();
}

// Get worker running.
Nouvello_WeManage_Worker();
