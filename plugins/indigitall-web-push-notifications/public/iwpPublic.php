<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';

	/*
	 * Indigitall Public Class
	 */
	class iwpPublic {
		/**
		 * Constructor
		 * No se usa
		 */
		public function __construct() {}

		public static function init() {
			define('IWP_PUBLIC_URL', plugin_dir_url(__FILE__));
			define('IWP_PUBLIC_PATH', plugin_dir_path(__FILE__));
			define('IWP_ADMIN_URL', IWP_PLUGIN_URL . "admin/");
			define('IWP_ADMIN_PATH', IWP_PLUGIN_PATH . "admin/");
			$isMobileDevice = wp_is_mobile() ? '1' : '0';
			define('IWP_IS_MOBILE', $isMobileDevice);

			wp_enqueue_style('indigitall-admin-styles', IWP_ADMIN_URL . 'views/admin/css/iwp-main-admin-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_register_script('indigitall-public-scripts', IWP_PUBLIC_URL . 'views/public/js/iwp-main-public-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_localize_script('indigitall-public-scripts', 'PUBLIC_PARAMS', self::preparePublicJsParams());
			wp_enqueue_script('indigitall-public-scripts');

			add_action('wp_footer', array(__CLASS__, 'loadWhatsAppChat'), 10);
			if (get_option(iwpPluginOptions::APP_KEY, false)) {
				// Solamente iniciaremos si tenemos el plugin activo. Es decir, que tengamos al appKey definida
				add_action('wp_footer', array(__CLASS__, 'loadWebPush'), 10);
			}
		}

		public static function loadWebPush() {
			require_once IWP_PUBLIC_PATH . 'controllers/iwpPublicWebPushController.php';
			$controller = new iwpPublicWebPushController();
			$controller->renderHtml();

		}
		public static function loadWhatsAppChat() {
			require_once IWP_PUBLIC_PATH . 'controllers/iwpPublicWhatsAppChatController.php';
			$controller = new iwpPublicWhatsAppChatController();
			$controller->renderHtml();
		}

		/***** PRIVATE FUNCTIONS *****/

		/**
		 * Prepara las variables para poder usarlas dentro de javascript
		 * @return array
		 */
		public static function preparePublicJsParams() {
			return array(
				'isMobileDevice' => IWP_IS_MOBILE,
			);
		}
	}