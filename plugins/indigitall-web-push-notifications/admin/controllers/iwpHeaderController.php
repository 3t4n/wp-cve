<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminPage.php';

	class iwpHeaderController {

		/** @var string */
		private $currentPage;

		/** @var string */
		private $siteUrl;

		/**
		 * Constructor
		 */
		public function __construct() {
			wp_enqueue_style('indigitall-header-styles', IWP_ADMIN_URL . 'views/header/css/iwp-admin-header-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-header-modal-styles', IWP_ADMIN_URL . 'views/header/css/iwp-admin-header-modal-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-header-main-menu-styles', IWP_ADMIN_URL . 'views/header/css/iwp-admin-header-main-menu-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-header-scripts', IWP_ADMIN_URL . 'views/header/js/iwp-admin-header-scripts.js?v=' . IWP_PLUGIN_VERSION);

			$this->currentPage = iwpAdminPage::getInnerPage();
			$this->siteUrl = iwpAdminPage::getSiteUrl();
		}

		public function returnHtml() {
			// Definición de parámetros que necesita la vista
			$registered = (bool)get_option(iwpPluginOptions::APPLICATION_ID);
			$logoSrc = IWP_ADMIN_URL . 'views/header/images/logo.svg';
			$consoleSso = iwpAdminUtils::getConsoleSso();

			$logoutModal = iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/header/partials/iwpLogoutModal.php');

			$subHeaderParams = array(
				'appKey' => get_option(iwpPluginOptions::APP_KEY, ""),
				'appName' => get_option(iwpPluginOptions::APPLICATION_NAME, ""),
			);
			$subHeaderHtml = iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/header/partials/iwpSubHeader.php', $subHeaderParams);

			$mainMenuParams = array(
				'channels' => $this->getMainMenu()
			);
			$mainMenuHtml = iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/header/partials/iwpMainMenu.php', $mainMenuParams);

			if ($registered) {
				// Solamente si hemos iniciado sesión, comprobaremos la existencia de la secretKey
				iwpPluginOptions::reconfigureSecretKeyForOldUsers();
			}

			$dynamicDocumentationLink = iwpAdminUtils::getDocumentationDynamicLink();

			ob_start();
			include_once IWP_ADMIN_PATH . 'views/header/iwpHeaderView.php';
			return ob_get_clean();
		}

		public static function iwpDisconnect() {
			iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_PLUGIN_DESCONECTAR);
			iwpPluginOptions::resetAllOptions();
			$ret = array(
				'status' => 1,
				'message' => ''
			);
			return json_encode($ret);
		}

		private function getMainMenu() {
			$whatsAppChatCurrentPage = ($this->currentPage === iwpAdminPage::PAGE_WHATSAPP_CHAT);
			$whatsAppChatActivated = (get_option(iwpPluginOptions::WH_STATUS, '0') === '1');
			$whatsAppChatLink = "{$this->siteUrl}?page=indigitall-push&inner-page=" . iwpAdminPage::PAGE_WHATSAPP_CHAT;

			$webPushCurrentPage = iwpAdminPage::isWebPushPage();
			$webPushActivated = (get_option(iwpPluginOptions::WEB_PUSH_STATUS, '0') === '1');
			$webPushLink = "{$this->siteUrl}?page=indigitall-push&inner-page=" . iwpAdminPage::PAGE_WEB_PUSH_CONFIG;
			$showReConfigStatus = get_option(iwpPluginOptions::RE_CONFIG_STATUS, false);

			$warningImage = IWP_ADMIN_URL . 'images/warning.svg';
			$warningTitle = __('Login required', 'iwp-text-domain');
			$webPushWarning = !empty($showReConfigStatus) ? "<img class='iwp-admin-warning-image' src='$warningImage' alt='' title='$warningTitle'>" : '';

			return array(
				array(
					'id' => 'iwp-admin-main-menu-channel-whatsAppChat',
					'name' => 'WHATSAPP CHAT',
					'currentPage' => ($whatsAppChatCurrentPage ? 'active' : ''),
					'class' => ($whatsAppChatActivated ? 'activated' : 'deactivated'),
					'link' => $whatsAppChatLink,
					'warning' => '',
				),
				array(
					'id' => 'iwp-admin-main-menu-channel-webPush',
					'name' => 'WEB PUSH',
					'currentPage' => ($webPushCurrentPage ? 'active' : ''),
					'class' => ($webPushActivated ? 'activated' : 'deactivated'),
					'link' => $webPushLink,
					'warning' => $webPushWarning,
				),
			);
		}
	}