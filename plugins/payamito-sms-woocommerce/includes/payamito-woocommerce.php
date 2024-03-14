<?php

if (!defined('ABSPATH')) {
	die('direct access abort ');
}

/**
 * main class payamito_woocommerce
 *
 * @since    1.0.0
 */
if (!class_exists('payamito_woocommerce')) :

	final class Payamito_Woocommerce
	{

		public $textdomain = "payamito-woocommerce";
		/**
		 * Instance of this loader class.
		 *
		 * @since    1.0.0
		 * @var      object
		 */
		protected static $instance = null;
		/**
		 * Return an instance of this class.
		 *
		 * @return    object    A single instance of this class.
		 * @since     1.0.0
		 */
		/**
		 * Required version of the core.
		 * The minimum version of the core that's required
		 * to properly run this addon. If the minimum version
		 * requirement isn't met an error message is displayed
		 * and the addon isn't registered.
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		protected $version_required = '5.0.0';

		/**
		 * Required version of PHP.
		 * Follow WordPress latest requirements and require
		 * PHP version 5.4 at least.
		 *
		 * @var string
		 */
		protected $php_version_required = '7.4.0';

		/**
		 * Plugin slug.
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public static $slug = 'payamito_wc';
		/**
		 * Possible error message.
		 *
		 * @var null|WP_Error
		 */
		protected $error = null;

		/**
		 * functions container
		 *
		 * @var object
		 */
		public $functions;

		/**
		 * options container
		 *
		 * @var object
		 */
		public $options;

		/**
		 * woocommerce container
		 *
		 * @var object
		 */
		public $woocommerce;

		/**
		 * send container
		 *
		 * @var object
		 */
		public $send;

		/**
		 * plugin name container
		 *
		 * @var object
		 */
		public $plugin_name = ' Payamito Woocommerce ';

		// If the single instance hasn't been set, set it now.
		public static function get_instance()
		{
			if (null == self::$instance) {
				self::$instance = new self;
			}

			self::$instance->init();

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @return void
		 * @since  1.0.0
		 * @access public
		 */
		public function __clone()
		{
			_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'payamito-woocommerce'), '1.0.0');
		}

		/**
		 * Disable unserializing of the class
		 * Attempting to wakeup an FES instance will throw a doing it wrong notice.
		 *
		 * @return void
		 * @since  1.0.0
		 * @access public
		 */
		public function __wakeup()
		{
			_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'payamito-woocommerce'), '1.0.0');
		}

		/**
		 * Initialize the addon.
		 * This method is the one running the checks and
		 * registering the addon to the core.
		 *
		 * @return boolean Whether or not the addon was registered
		 * @since  0.1.0
		 */
		public function init()
		{
			if (!$this->is_php_version_enough()) {
				wp_die(__('Minimum php required version 7.0.0 or higher is required', 'payamito-woocommerce'));
			}

			$this->include_files();
			$this->get_options();
			$this->load_core();
			$this->init_classes();
			add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
		}

		/**
		 * Get options
		 *create a global variable containe options
		 *
		 * @return array
		 * @since  1.0.0
		 */
		public function get_options()
		{
			global $pwoo_otp_options;
			global $pwoo_general_options;

			$otp     = get_option('payamito_woocommerce_otp');
			$general = get_option('payamito_woocommerce_general');
			if ($otp == false) {
				$otp_options['active'] = false;
			}
			if ($general == false) {
				$pwoo_general_options = [];
			}
			$pwoo_otp_options     = $otp;
			$pwoo_general_options = $general;
		}

		public function admin_enqueue_scripts()
		{
			if (isset($_GET['page'])) {
				if ($_GET['page'] === 'payamito' || $_GET['page'] === 'payamito_logs') {
					wp_enqueue_script("payamito-woocommerce-admin-js", PAYAMITO_WC_URL . "/includes/admin/assets/js/admin-app.js", ['jquery'], false, true);
				}
			}
		}

		/**
		 * Check if woocommerce is active.
		 * Checks if the woocommerce is plugin is listed in the active
		 * plugins in the WordPress database.
		 *
		 * @return boolean Whether or not the core is active
		 * @since  1.0.0
		 */
		protected function is_woocommerce_active()
		{
			if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Check if the version of PHP is compatible with this addon.
		 *
		 * @return boolean
		 * @since  1.0.0
		 */
		protected function is_php_version_enough()
		{
			/**
			 * No version set, we assume everything is fine.
			 */
			if (empty($this->php_version_required)) {
				return true;
			}

			if (version_compare(phpversion(), $this->php_version_required, '<')) {
				return false;
			}

			return true;
		}

		/**
		 * Load the addon.
		 * Include all necessary files and instantiate the addon.
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function include_files()
		{
			require_once PAYAMITO_WC_DIR . '/includes/lib/class-tgm-plugin-activation.php';

			require_once PAYAMITO_WC_DIR . '/includes/class-functions.php';

			require_once PAYAMITO_WC_DIR . '/includes/gateway/api/class-send.php';

			require_once PAYAMITO_WC_DIR . '/includes/class-woocommerce.php';

			require_once PAYAMITO_WC_DIR . '/includes/admin/class-settings.php';

			require_once PAYAMITO_WC_DIR . '/includes/class-woocommerce-field.php';

			self::include_modules();
		}

		public function init_classes()
		{
			$this->functions = new Payamito\Woocommerce\Funtions\Functions();

			$this->options = new Payamito\Woocommerce\Settings\Settings();


			$this->woocommerce = Payamito\Woocommerce\P_Woocommerce::get_instance();

			$this->send = Payamito\Woocommerce\Send\Send::get_instance();

			Payamito\Woocommerce\Field\Field::get_instance();

			Payamito\Woocommerce\Modules\Modules::get_instance();

			do_action("payamito_wc_loaded");
		}

		public static function get_object_send()
		{
			return Payamito\Woocommerce\Send\Send::get_instance();
		}

		public function load_core()
		{
			require_once payamito_wc_load_core() . '/payamito.php';
			run_payamito();
		}

		public static function include_modules()
		{
			require_once PAYAMITO_WC_DIR . '/modules/class-modules.php';
		}
	}

endif;
