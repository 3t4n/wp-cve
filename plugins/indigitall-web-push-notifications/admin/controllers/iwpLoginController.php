<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';

	class iwpLoginController {

		/**
		 * Constructor
		 */
		public function __construct() {
			wp_enqueue_style('indigitall-modalLogin-styles', IWP_ADMIN_URL . 'views/login/css/iwp-modalLogin-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-modalLogin-main-scripts', IWP_ADMIN_URL . 'views/login/js/iwp-modalLogin-main-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-modalLogin-utils-scripts', IWP_ADMIN_URL . 'views/login/js/iwp-modalLogin-utils-script.js?v=' . IWP_PLUGIN_VERSION);
		}

		public function returnModalHtml() {
			if (iwpAdminPage::isWebPushPage()) {
				$showReConfigStatus = get_option(iwpPluginOptions::RE_CONFIG_STATUS, false);
				$showModal          = !empty($showReConfigStatus);
			}

			$loginView = $this->loadLoginView();
			$signUpView = $this->loadSignUpView();
			$doubleFaView = $this->load2faView();
			$projectSelectionView = $this->loadProjectSelectionView();
			$recoverPassView = $this->loadRecoverPassView();

			ob_start();
			include_once IWP_ADMIN_PATH . 'views/login/iwpModalLoginView.php';
			return ob_get_clean();
		}

		/***** FUNCIONES SECUNDARIAS *****/

		private function loadLoginView() {
			$showReConfigStatus = false;
			if (iwpAdminPage::isWebPushPage()) {
				$showReConfigStatus = get_option(iwpPluginOptions::RE_CONFIG_STATUS, false);
			}
			$params = array(
				'showReConfigMessage' => !empty($showReConfigStatus),
			);
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/login/partials/iwpLoginView.php', $params);
		}

		private function loadSignUpView() {
			$params = array();
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/login/partials/iwpSignUpView.php', $params);
		}

		private function loadRecoverPassView() {
			$params = array();
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/login/partials/iwpRecoverPasswordView.php', $params);
		}

		private function load2faView() {
			$params = array();
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/login/partials/iwp2faView.php', $params);
		}

		private function loadProjectSelectionView() {
			$params = array();
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/login/partials/iwpProjectSelectionView.php', $params);
		}
	}