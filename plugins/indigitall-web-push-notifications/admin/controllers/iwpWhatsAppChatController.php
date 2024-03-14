<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';

	class iwpWhatsAppChatController {

		/**
		 * Constructor
		 */
		public function __construct() {
			wp_enqueue_style('indigitall-whatsAppChat-styles', IWP_ADMIN_URL . 'views/whatsAppChat/css/iwp-whatsAppChat-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-whatsAppChat-scripts', IWP_ADMIN_URL . 'views/whatsAppChat/js/iwp-whatsAppChat-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-whatsAppChat-step1-scripts', IWP_ADMIN_URL . 'views/whatsAppChat/js/iwp-whatsAppChat-step1-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-whatsAppChat-step2-scripts', IWP_ADMIN_URL . 'views/whatsAppChat/js/iwp-whatsAppChat-step2-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-whatsAppChat-step3-scripts', IWP_ADMIN_URL . 'views/whatsAppChat/js/iwp-whatsAppChat-step3-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-qr-code', IWP_PLUGIN_URL . 'includes/qr-code/qrcode-v2.js?v=' . IWP_PLUGIN_VERSION);
		}

		public function renderHtml() {
			$status = ((get_option(iwpPluginOptions::WH_STATUS, '0') === '1') ? 'enabled' : 'disabled');

			$whStep1View = $this->loadStep1View();
			$whStep2View = $this->loadStep2View();
			$whStep3View = $this->loadStep3View();

			ob_start();
			include_once IWP_ADMIN_PATH . 'views/whatsAppChat/iwpWhatsAppChatView.php';
			return ob_get_clean();
		}

		/***** FUNCIONES SECUNDARIAS *****/

		private function loadStep1View() {
			$phoneCountryPrefix = get_option(iwpPluginOptions::WH_PHONE_COUNTRY, false);
			$phoneCountryPrefix = (!$phoneCountryPrefix) ? '+'.iwpAdminUtils::getUserIpCountry('prefix') : $phoneCountryPrefix;

			$paramsStep1 = array(
				'countriesPrefixOptions' => iwpAdminUtils::loadCountriesPrefixOptions($phoneCountryPrefix),
				'phone'                  => substr(get_option(iwpPluginOptions::WH_PHONE, false), strlen($phoneCountryPrefix)),
				'welcomeMessage'         => htmlentities(self::encodeDecodeText(get_option(iwpPluginOptions::WH_CHAT_WELCOME_MESSAGE, false)), ENT_QUOTES),
			);
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/whatsAppChat/partials/iwpWhatsAppChatStep1.php', $paramsStep1);
		}

		private function loadStep2View() {
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatIconModel.php';
			$paramsStep2 = array(
				'iconOption'        => get_option(iwpPluginOptions::WH_ICON_OPTION, iwpWhatsAppChatIconModel::ICON_OPTION_DEFAULT),
				'iconPosition'      => get_option(iwpPluginOptions::WH_ICON_POSITION, false),
				'iconColor'         => get_option(iwpPluginOptions::WH_ICON_COLOR, false),
				'iconImageId'       => get_option(iwpPluginOptions::WH_ICON_IMAGE_ID, false),
				'iconImage'         => get_option(iwpPluginOptions::WH_ICON_IMAGE_NAME, false),
				'iconTransparent'   => (bool)get_option(iwpPluginOptions::WH_ICON_TRANSPARENT_COLOR, false),
				'iconBalloon'       => get_option(iwpPluginOptions::WH_ICON_SPEECH_BALLOON, false),
				'iconBalloonText'   => htmlentities(self::encodeDecodeText(get_option(iwpPluginOptions::WH_ICON_SPEECH_BALLOON_TEXT, '')), ENT_QUOTES),
				'iconSleep'         => (int)get_option(iwpPluginOptions::WH_ICON_SLEEP, '5'),
			);
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/whatsAppChat/partials/iwpWhatsAppChatStep2.php', $paramsStep2);
		}

		private function loadStep3View() {
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatModel.php';
			$paramsStep3 = array(
				'chatType'            => get_option(iwpPluginOptions::WH_CHAT_TYPE, iwpWhatsAppChatModel::CHAT_TYPE_QR),
				'qrHeader'            => htmlentities(self::encodeDecodeText(get_option(iwpPluginOptions::WH_CHAT_QR_HEADER, '')), ENT_QUOTES),
				'qrText'              => htmlentities(self::encodeDecodeText(get_option(iwpPluginOptions::WH_CHAT_QR_TEXT, '')), ENT_QUOTES),
				'qrColor'             => get_option(iwpPluginOptions::WH_CHAT_QR_COLOR, false),
				'chatHeaderValue'     => htmlentities(self::encodeDecodeText(get_option(iwpPluginOptions::WH_CHAT_HEADER, '')), ENT_QUOTES),
				'chatBodyValue'       => htmlentities(self::encodeDecodeText(get_option(iwpPluginOptions::WH_CHAT_BODY, '')), ENT_QUOTES),
				'themeColor'          => get_option(iwpPluginOptions::WH_CHAT_COLOR, false),
				'chatButtonTextValue' => htmlentities(self::encodeDecodeText(get_option(iwpPluginOptions::WH_CHAT_BUTTON_TEXT, '')), ENT_QUOTES),
				'buttonIcon'          => get_option(iwpPluginOptions::WH_CHAT_BUTTON_IMAGE_ID, false),
				'chatSleep'           => (int)get_option(iwpPluginOptions::WH_CHAT_SLEEP, '20'),
			);
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/whatsAppChat/partials/iwpWhatsAppChatStep3.php', $paramsStep3);
		}

		/***** FUNCIONES AJAX *****/

		public static function saveWhatsAppChatAjax() {
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatModel.php';
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatIconModel.php';
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatWindowModel.php';
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatQrModel.php';
			$status = (get_option(iwpPluginOptions::WH_STATUS, '0') === '1');

			// Default texts
//			$welcomeMessageDefault  = __('Hello, I have a question', 'iwp-text-domain');
//			$iconBalloonTextDefault = __('Hi', 'iwp-text-domain');
//			$headerDefault          = __('iurny.com', 'iwp-text-domain');
//			$welcomeDefault         = __("Welcome to iurny's chat", 'iwp-text-domain');
//			$buttonDefault          = __('Open chat', 'iwp-text-domain');

			$phone               = iwpAdminUtils::getPOSTParam('phone');
			$prefix              = iwpAdminUtils::getPOSTParam('countriesPrefixOptions');
			$welcomeMessage      = trim(iwpAdminUtils::getPOSTParam('welcomeMessage', ''));
			$iconOption          = trim(iwpAdminUtils::getPOSTParam('iconOption', iwpWhatsAppChatIconModel::ICON_OPTION_DEFAULT));
			$iconPosition        = trim(iwpAdminUtils::getPOSTParam('iconPosition', 'r'));
			$iconColor           = trim(iwpAdminUtils::getPOSTParam('iconColor', iwpWhatsAppChatIconModel::DEFAULT_COLOR));
			$iconImageId         = (int)iwpAdminUtils::getPOSTParam('iconImageId');
			$iconImage           = iwpAdminUtils::getPOSTParam('iconImage', 'whatsApp');
			$iconTransparent     = filter_var(iwpAdminUtils::getPOSTParam('iconTransparent'), FILTER_VALIDATE_BOOLEAN);
			$iconBalloon         = trim(iwpAdminUtils::getPOSTParam('iconBalloon'));
			$iconBalloonText     = trim(iwpAdminUtils::getPOSTParam('iconBalloonText', ''));
			$iconSleep           = (int)iwpAdminUtils::getPOSTParam('iconSleep', 5);
			$chatTypeValue       = trim(iwpAdminUtils::getPOSTParam('chatType', iwpWhatsAppChatModel::CHAT_TYPE_QR));
			$chatHeaderValue     = trim(iwpAdminUtils::getPOSTParam('chatHeaderValue', ''));
			$chatBodyValue       = trim(iwpAdminUtils::getPOSTParam('chatBodyValue', ''));
			$themeColor          = trim(iwpAdminUtils::getPOSTParam('themeColor', iwpWhatsAppChatWindowModel::DEFAULT_COLOR));
			$chatButtonTextValue = trim(iwpAdminUtils::getPOSTParam('chatButtonTextValue', ''));
			$buttonIcon          = iwpAdminUtils::getPOSTParam('buttonIcon', 'send');
			$chatSleep           = (int)trim(iwpAdminUtils::getPOSTParam('chatSleep', 20));
			$qrHeader            = trim(iwpAdminUtils::getPOSTParam('qrHeader', ''));
			$qrText              = trim(iwpAdminUtils::getPOSTParam('qrText', ''));
			$qrColor             = trim(iwpAdminUtils::getPOSTParam('qrColor', iwpWhatsAppChatQrModel::DEFAULT_COLOR));

			if ($status && (empty($prefix) || !preg_match('/^\d{7,}$/', $phone))) {
				return json_encode(array(
					'status' => 0,
					'message' => __("The telephone number or the international prefix are not correct", 'iwp-text-domain')
				));
			}

			update_option(iwpPluginOptions::WH_PHONE,                    iwpAdminUtils::sanitizeText($prefix.$phone));
			update_option(iwpPluginOptions::WH_PHONE_COUNTRY,            iwpAdminUtils::sanitizeText($prefix));
			update_option(iwpPluginOptions::WH_CHAT_WELCOME_MESSAGE,     iwpAdminUtils::sanitizeText(self::encodeDecodeText($welcomeMessage)));
			update_option(iwpPluginOptions::WH_ICON_OPTION,              iwpAdminUtils::sanitizeText($iconOption));
			update_option(iwpPluginOptions::WH_ICON_POSITION,            iwpAdminUtils::sanitizeText($iconPosition));
			update_option(iwpPluginOptions::WH_ICON_COLOR,               iwpAdminUtils::sanitizeText($iconColor));
			update_option(iwpPluginOptions::WH_ICON_IMAGE_ID,            (int)iwpAdminUtils::sanitizeText($iconImageId));
			update_option(iwpPluginOptions::WH_ICON_IMAGE_NAME,          esc_url($iconImage));
			update_option(iwpPluginOptions::WH_ICON_TRANSPARENT_COLOR,   $iconTransparent);
			update_option(iwpPluginOptions::WH_ICON_SPEECH_BALLOON,      iwpAdminUtils::sanitizeText($iconBalloon));
			update_option(iwpPluginOptions::WH_ICON_SPEECH_BALLOON_TEXT, iwpAdminUtils::sanitizeText(self::encodeDecodeText($iconBalloonText)));
			update_option(iwpPluginOptions::WH_ICON_SLEEP,               (int)iwpAdminUtils::sanitizeText($iconSleep));
			update_option(iwpPluginOptions::WH_CHAT_TYPE,                iwpAdminUtils::sanitizeText($chatTypeValue));
			update_option(iwpPluginOptions::WH_CHAT_QR_HEADER,           iwpAdminUtils::sanitizeText($qrHeader));
			update_option(iwpPluginOptions::WH_CHAT_QR_TEXT,             iwpAdminUtils::sanitizeText($qrText));
			update_option(iwpPluginOptions::WH_CHAT_QR_COLOR,            iwpAdminUtils::sanitizeText($qrColor));
			update_option(iwpPluginOptions::WH_CHAT_HEADER,              iwpAdminUtils::sanitizeText(self::encodeDecodeText($chatHeaderValue)));
			update_option(iwpPluginOptions::WH_CHAT_BODY,                iwpAdminUtils::sanitizeText(self::encodeDecodeText($chatBodyValue)));
			update_option(iwpPluginOptions::WH_CHAT_COLOR,               iwpAdminUtils::sanitizeText($themeColor));
			update_option(iwpPluginOptions::WH_CHAT_BUTTON_TEXT,         iwpAdminUtils::sanitizeText(self::encodeDecodeText($chatButtonTextValue)));
			update_option(iwpPluginOptions::WH_CHAT_BUTTON_IMAGE_ID,     esc_url($buttonIcon));
			update_option(iwpPluginOptions::WH_CHAT_SLEEP,               (int)iwpAdminUtils::sanitizeText($chatSleep));

			iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_WP_ACTUALIZAR);

			return json_encode(array(
				'status' => 1,
				'message' => __("WhatsApp Chat settings have been saved successfully", 'iwp-text-domain')
			));
		}

		private static function encodeDecodeText($text = '') {
			return self::decodeText(self::encodeText(stripslashes($text)));
		}

		private static function encodeText($text = '') {
			return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
		}

		private static function decodeText($text = '') {
			return htmlspecialchars_decode($text, ENT_QUOTES);
		}
	}