<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';

	class iwpPublicWebPushController {
		/**
		 * Constructor
		 * No se usa
		 */
		public function __construct() {
			define('IWP_PUBLIC_WEB_PUSH_VIEW_URL', IWP_PLUGIN_URL . "public/views/webPush/");
			define('IWP_PUBLIC_WEB_PUSH_VIEW_PATH', IWP_PLUGIN_PATH . "public/views/webPush/");
			wp_register_style('indigitall-public-webPush-styles',
				IWP_PUBLIC_URL . 'views/webPush/css/iwp-public-webPush-styles.css?v=' . IWP_PLUGIN_VERSION);
		}

		public function renderHtml() {
			self::initPublicLang();
			$wpStatus = filter_var(get_option(iwpPluginOptions::WEB_PUSH_STATUS, false), FILTER_VALIDATE_BOOLEAN);
			if ($wpStatus) {
				$appKey = get_option(iwpPluginOptions::APP_KEY, false);
				// Comprobación adicional. Better safe than sorry
				if ($appKey) {
					define('IWP_PUBLIC_WORKER_URL', IWP_PUBLIC_WEB_PUSH_VIEW_URL . 'js/worker.min.js?v=' . IWP_PLUGIN_VERSION);
					define('IWP_PUBLIC_SDK_URL', IWP_PUBLIC_WEB_PUSH_VIEW_URL . 'js/sdk.min.js?v=' . IWP_PLUGIN_VERSION);

					// JS genérico
					wp_register_script('indigitall-public-scripts', IWP_PUBLIC_WEB_PUSH_VIEW_URL . 'js/iwp-main-public-scripts.js?v=' . IWP_PLUGIN_VERSION);
					// Iniciamos las variables globales de web push para JS
					wp_localize_script('indigitall-public-scripts', 'PUBLIC_PARAMS', self::preparePublicJsParams());
					wp_enqueue_script('indigitall-public-scripts');

					wp_enqueue_style('indigitall-public-webPush-styles');
					self::loadSdk();
					add_filter('script_loader_tag', array(__CLASS__, 'loadWorker'), 10, 3);
					$wpTopicsStatus = filter_var(get_option(iwpPluginOptions::TOPICS_STATUS, false), FILTER_VALIDATE_BOOLEAN);
					if ($wpTopicsStatus) {
						$defaultColor = "#0F3B7A";
						$themeColor = get_option(iwpPluginOptions::TOPICS_COLOR, $defaultColor);
						$params = array(
							"color" => $themeColor
						);
						$view = iwpAdminUtils::loadViewToVar(IWP_PUBLIC_WEB_PUSH_VIEW_PATH . 'iwpPublicWebPushView.php', $params);
						echo($view);
					}
				}
			}
		}

		/**
		 * Se busca en los scripts cargados, el tag que carga el sdk con los topics o sin ellos. Si se encuentra, se
		 *      modifican algunos parámetros para su correcto funcionamiento y se devuelve el tag modificado.
		 */
		public static function loadWorker($tag, $handle, $src) {
			$path_to_worker = IWP_PUBLIC_WORKER_URL;
			$appKey = get_option(iwpPluginOptions::APP_KEY, false);
			$requestLocation = filter_var(get_option(iwpPluginOptions::WEB_PUSH_LOCATION_ACCESS, false), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
			$domain = get_option(iwpPluginOptions::USER_DOMAIN, '');
			$domain = (trim($domain) === "") ? "" : $domain . ".";

			$scripts_to_load = array (
				(0) => Array(
					('name') => 'indigitall-script-topics',
					('type') => '<script src="',
					('async') => 'async',
					('onload') => "window.indigitall.init({ appKey: '" . $appKey . "', requestLocation: " . $requestLocation . ", workerPath: '" . $path_to_worker . "', urlDeviceApi: 'https://" . $domain ."device-api.indigitall.com/v1', urlInappApi: 'https://" . $domain . "inapp-api.indigitall.com/v1', urlInboxApi: 'https://" . $domain . "inbox-api.indigitall.com/v1', onNewUserRegistered: iwp_loadTopics });",
					('close') => '></script>'
				),
				(1) => Array(
					('name') => 'indigitall-script-no-topics',
					('type') => '<script src="',
					('async') => 'async',
					('onload') => "window.indigitall.init({ appKey: '" . $appKey . "', requestLocation: " . $requestLocation . ", workerPath: '" . $path_to_worker . "', urlDeviceApi: 'https://" . $domain ."device-api.indigitall.com/v1', urlInappApi: 'https://" . $domain . "inapp-api.indigitall.com/v1', urlInboxApi: 'https://" . $domain . "inbox-api.indigitall.com/v1' });",
					('close') => '></script>'
				)
			);
			$key = array_search($handle, array_column($scripts_to_load, 'name'), true);
			if ($key !== false) {
				$tag = $scripts_to_load[$key]['type'] . esc_url($src) . '" onload="' . $scripts_to_load[$key]['onload'] . '" ' . $scripts_to_load[$key]['async'] . $scripts_to_load[$key]['close'] . "\n";
			}
			return $tag;
		}

		/**
		 * Se presupone que webPush está activo.
		 * Si los topics están activos, se carga el código js necesario para su funcionamiento.
		 * En los 2 casos se carga el sdk, pero con diferente nombre del handle según se cargan los topics o no.
		 */
		public static function loadSdk() {
			$path_to_sdk = IWP_PUBLIC_SDK_URL;
			$wpTopicsStatus = filter_var(get_option(iwpPluginOptions::TOPICS_STATUS, false), FILTER_VALIDATE_BOOLEAN);
			if ($wpTopicsStatus) {
				$topicsScripts = IWP_PUBLIC_WEB_PUSH_VIEW_URL . 'js/iwp-public-topics-scripts.js?v=' . IWP_PLUGIN_VERSION;
				wp_register_script( 'indigitall-topics-js', $topicsScripts, null, null, true );
				wp_enqueue_script('indigitall-topics-js');

				wp_register_script( 'indigitall-script-topics', $path_to_sdk, null, null, true );
				wp_enqueue_script('indigitall-script-topics');
			}else{
				wp_register_script( 'indigitall-script-no-topics', $path_to_sdk, null, null, true );
				wp_enqueue_script('indigitall-script-no-topics');
			}
		}

		/**
		 * Revisa si el archivo de traducciones se ha cargado. En caso afirmativo, no se hace nada. De lo contrario,
		 * 		se recoge el idioma de la web, se comprueba si el archivo mo existe y si existe, se carga.
		 *		Si no existe el archivo mo del idioma, se carga la versión inglesa.
		 */
		private static function initPublicLang() {
			// El archivo de traducciones no se ha cargado
			$langPath = IWP_PLUGIN_PATH . 'languages';
			$domain = 'iwp-text-domain';
			$moExtension = '.mo';

			$locale = get_locale();
			$moFile = "{$langPath}/{$domain}-{$locale}{$moExtension}";
			if (file_exists($moFile)) {
				// El archivo con el idioma completo, sí existe: en_US, en_GB, es_ES, es_MX...
				goto loadFile;
			}

			$miniLocale = substr($locale, 0, 2);
			$moFile = "{$langPath}/{$domain}-{$miniLocale}{$moExtension}";
			if (file_exists($moFile)) {
				// El archivo con el idioma resumido, sí existe: es, en...
				goto loadFile;
			}

			// Cargamos el archivo predeterminado del idioma, la versión en inglés
			$defaultLocale = 'en';
			$moFile = "{$langPath}/{$domain}-{$defaultLocale}{$moExtension}";

			loadFile:
			load_textdomain( 'iwp-text-domain', $moFile);
		}

		/**
		 * Prepara las variables para poder usarlas dentro de javascript
		 * @return array
		 */
		private static function preparePublicJsParams() {
			return array(
				'workerUrl' => IWP_PUBLIC_WORKER_URL,
			);
		}
	}