<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';

	class iwpFooterController {
		/**
		 * Constructor
		 */
		public function __construct() {
			wp_enqueue_style('indigitall-footer-styles', IWP_ADMIN_URL . 'views/footer/css/iwp-admin-footer-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-footer-scripts', IWP_ADMIN_URL . 'views/footer/js/iwp-footer-scripts.js?v=' . IWP_PLUGIN_VERSION);
		}

		public function returnHtml() {
			$developerMode = filter_var(get_option(iwpPluginOptions::DEVELOPER_MODE, false), FILTER_VALIDATE_BOOLEAN);
			$dynamicDocumentationLink = iwpAdminUtils::getDocumentationDynamicLink();
			$developerDocumentationLink = 'https://documentation.iurny.com/reference/wordpress-plugin';

			ob_start();
			include_once IWP_ADMIN_PATH . 'views/footer/iwpFooterView.php';
			return ob_get_clean();
		}

		public static function toggleDeveloperMode() {
			$iwpDeveloperMode = filter_var(iwpAdminUtils::getPOSTParam('developerMode', false), FILTER_VALIDATE_BOOLEAN);
			update_option(iwpPluginOptions::DEVELOPER_MODE, $iwpDeveloperMode);
			return json_encode(array('status' => 1));
		}
	}