<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminPage.php';

	class iwpAdminController {
		/**
		 * Constructor
		 */
		public function __construct() {
			// Obtenemos la info de la IP (si no la tenemos) y la almacenamos
			iwpAdminUtils::prepareWebName();
			iwpAdminUtils::prepareIpInfo();
			iwpCustomEvents::sendSystemInfoEvent();
			iwpCustomEvents::sendRetroactiveChannelsInfo();

			// CSS genérico
			wp_enqueue_style('indigitall-admin-styles', IWP_ADMIN_URL . 'views/admin/css/iwp-main-admin-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-admin-icons', IWP_ADMIN_URL . 'views/admin/css/iwp-main-admin-icons.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-admin-loader', IWP_ADMIN_URL . 'views/admin/css/iwp-main-admin-loader.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-admin-switch', IWP_ADMIN_URL . 'views/admin/css/iwp-main-admin-switch.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-admin-tooltip', IWP_ADMIN_URL . 'views/admin/css/iwp-main-admin-tooltip.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-custom-select-style', IWP_ADMIN_URL . 'includes/iwp-custom-select/iwp-custom-select.css?v=' . IWP_PLUGIN_VERSION);

			// JS genérico
			wp_register_script('indigitall-admin-scripts', IWP_ADMIN_URL . 'views/admin/js/iwp-main-admin-scripts.js?v=' . IWP_PLUGIN_VERSION);
			// Iniciamos las variables globales de administración para JS
			wp_localize_script('indigitall-admin-scripts', 'ADMIN_PARAMS', self::prepareAdminJsParams());
			wp_enqueue_script('indigitall-admin-scripts');
			wp_enqueue_script('indigitall-custom-select-script', IWP_ADMIN_URL . 'includes/iwp-custom-select/iwp-custom-select.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-custom-switch-script', IWP_ADMIN_URL . 'views/admin/js/iwp-main-admin-switch.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-custom-tooltip-script', IWP_ADMIN_URL . 'views/admin/js/iwp-main-admin-tooltip.js?v=' . IWP_PLUGIN_VERSION);
		}

		public function render() {
			switch (iwpAdminPage::getInnerPage()) {
//				case iwpAdminPage::PAGE_ON_BOARDING:
//					$body = $this->loadOnBoardingHtml();
//					break;
				case iwpAdminPage::PAGE_WEB_PUSH_CONFIG:
				case iwpAdminPage::PAGE_WEB_PUSH_TOPICS:
				case iwpAdminPage::PAGE_WEB_PUSH_WELCOME:
					$body = $this->loadWebPushHtml();
					break;
//				case iwpAdminPage::PAGE_WEB_PUSH_WIDGET:
//					$body = null;
//					break;
				case iwpAdminPage::PAGE_WHATSAPP_CHAT:
					$body = $this->loadWhatsAppChatHtml();
					break;
				default:
					//La página recibida no corresponde con ninguna registrada.
					// Mostramos el nuevo onBoarding
//					if (get_option(iwpPluginOptions::APPLICATION_ID)) {
//						// Tenemos el identificador del "proyecto", por lo tanto, cargamos la página predeterminada
//						$body = $this->loadWhatsAppChatHtml();
//					} else {
//						// No tenemos appKey, por lo tanto, cargamos la página del onBoarding
//						$body = $this->loadOnBoardingHtml();
//					}
					$body = $this->loadWhatsAppChatHtml();
			}
			$loginModal = $this->loadLoginHtml();
			$header = $this->loadHeaderHtml();
			$footer = $this->loadFooterHtml();
			$loader = $this->loadLoaderHtml();
			require_once IWP_ADMIN_PATH . 'views/admin/iwpAdminView.php';
		}

		/***** PRIVATE FUNCTIONS *****/

		/**
		 * Prepara las variables para poder usarlas dentro de javascript
		 * @return array
		 */
		public static function prepareAdminJsParams() {
			$developerMode = get_option(iwpPluginOptions::DEVELOPER_MODE, '0');
			$locale = explode('_', get_locale());
			$appKey = get_option(iwpPluginOptions::APP_KEY, false);
			return array(
				'locale' => array_shift($locale),
				'developerMode' => $developerMode,
				'DEBUG_ERROR_LEVEL_1' => iwpCustomEvents::DEBUG_ERROR_LEVEL_1,
				'DEBUG_ERROR_LEVEL_2' => iwpCustomEvents::DEBUG_ERROR_LEVEL_2,
				'DEBUG_ERROR_LEVEL_3' => iwpCustomEvents::DEBUG_ERROR_LEVEL_3,
				'DEBUG_ERROR_LEVEL_4' => iwpCustomEvents::DEBUG_ERROR_LEVEL_4,

				'MICRO_PLUGIN_SELECCIONA_SERVICIO' => iwpCustomEvents::MICRO_PLUGIN_SELECCIONA_SERVICIO,
				'resetColorLabel' => __('Reset default color', 'iwp-text-domain'),
				'IS_LOGGED' => !empty($appKey) ? '1' : '0',
			);
		}

		/**
		 * Cargamos el Modal del Login de la página y devolvemos su contenido (HTML, CSS, JS)
		 * @return string
		 */
		private function loadLoginHtml() {
			// Cargamos la vista y los estilos del header
			require_once IWP_ADMIN_PATH . 'controllers/iwpLoginController.php';
			return (new iwpLoginController())->returnModalHtml();
		}

		/**
		 * Cargamos el Header de la página y devolvemos su contenido (HTML, CSS, JS)
		 * @return string
		 */
		private function loadHeaderHtml() {
			// Cargamos la vista y los estilos del header
			require_once IWP_ADMIN_PATH . 'controllers/iwpHeaderController.php';
			return (new iwpHeaderController())->returnHtml();
		}

		/**
		 * Cargamos el Footer de la página y devolvemos su contenido (HTML, CSS, JS)
		 * @return string
		 */
		private function loadFooterHtml() {
			// Cargamos la vista y los estilos del footer
			require_once IWP_ADMIN_PATH . 'controllers/iwpFooterController.php';
			return (new iwpFooterController())->returnHtml();
		}

		/**
		 * Cargamos el código HTML del loader
		 * @return string
		 */
		private function loadLoaderHtml() {
			// Cargamos la vista y los estilos del footer
			$loaderImg = IWP_ADMIN_URL . 'images/iwp-loader.gif';
			ob_start();
			include_once IWP_ADMIN_PATH . 'views/admin/partials/iwpLoader.php';
			return ob_get_clean();
		}

		private function loadOnBoardingHtml() {
			// Cargamos la vista y los estilos del onBoarding
			require_once IWP_ADMIN_PATH . 'controllers/iwpOnBoardingController.php';
			return (new iwpOnBoardingController())->renderHtml();
		}

		private function loadWhatsAppChatHtml() {
			// Cargamos la vista y los estilos del onBoarding
			require_once IWP_ADMIN_PATH . 'controllers/iwpWhatsAppChatController.php';
			return (new iwpWhatsAppChatController())->renderHtml();
		}

		private function loadWebPushHtml() {
			// Cargamos la vista y los estilos del onBoarding
			require_once IWP_ADMIN_PATH . 'controllers/iwpWebPushController.php';
			return (new iwpWebPushController())->renderHtml();
		}
	}