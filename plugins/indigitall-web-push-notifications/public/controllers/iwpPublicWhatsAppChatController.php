<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';

	class iwpPublicWhatsAppChatController {
		/**
		 * Constructor
		 * No se usa
		 */
		public function __construct() {
			wp_register_style('indigitall-public-whatsAppChat-styles',
				IWP_PUBLIC_URL . 'views/whatsAppChat/css/iwp-public-whatsAppChat-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_register_script('indigitall-public-whatsAppChat-scripts',
				IWP_PUBLIC_URL . 'views/whatsAppChat/js/iwp-public-whatsAppChat-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_register_script('indigitall-qr-code', IWP_PLUGIN_URL . 'includes/qr-code/qrcode-v2.js?v=' . IWP_PLUGIN_VERSION);

		}

		public function renderHtml() {
			$whatsAppChatStatus = filter_var(get_option(iwpPluginOptions::WH_STATUS, false), FILTER_VALIDATE_BOOLEAN);
			$whatsAppChatPhone = get_option(iwpPluginOptions::WH_PHONE, false);
			if ($whatsAppChatStatus && !empty($whatsAppChatPhone)) {
				wp_enqueue_style('indigitall-public-whatsAppChat-styles');
				wp_enqueue_script('indigitall-public-whatsAppChat-scripts');
				wp_enqueue_script('indigitall-qr-code');
				$whatsAppChatModel = $this->loadWhatsAppChatValues();

				$params = array(
					'whatsAppChatModel' => $whatsAppChatModel
				);
				$view = iwpAdminUtils::loadViewToVar(IWP_PUBLIC_PATH . 'views/whatsAppChat/iwpPublicWhatsAppChatView.php', $params);
				echo($view);
			}
			return false;
		}

		private function loadWhatsAppChatValues() {
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatModel.php';
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatIconModel.php';
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatWindowModel.php';
			require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatQrModel.php';

			$whStatus           = filter_var(get_option(iwpPluginOptions::WH_STATUS,                            false), FILTER_VALIDATE_BOOLEAN);
			$whPhone            = self::sanitizeText(get_option(iwpPluginOptions::WH_PHONE,                     false));
			$whWelcome          = self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_WELCOME_MESSAGE,      ''));
			$whIconPosition     = self::sanitizeText(get_option(iwpPluginOptions::WH_ICON_POSITION,             ''));
			$whIconColor        = self::sanitizeText(get_option(iwpPluginOptions::WH_ICON_COLOR,                iwpWhatsAppChatIconModel::DEFAULT_COLOR));
			$whIconImageId      = (int)self::sanitizeText(get_option(iwpPluginOptions::WH_ICON_IMAGE_ID,        false));
			$whIconImageName    = esc_url(get_option(iwpPluginOptions::WH_ICON_IMAGE_NAME,                      ''));
			$whIconTransparent  = filter_var(get_option(iwpPluginOptions::WH_ICON_TRANSPARENT_COLOR,            false), FILTER_VALIDATE_BOOLEAN);
			$whIconBubble       = self::sanitizeText(get_option(iwpPluginOptions::WH_ICON_SPEECH_BALLOON,       false));
			$whIconBubbleText   = self::sanitizeText(get_option(iwpPluginOptions::WH_ICON_SPEECH_BALLOON_TEXT,  ''));
			$whIconSleep        = (int)self::sanitizeText(get_option(iwpPluginOptions::WH_ICON_SLEEP,           false));
			$whChatType         = self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_TYPE,                 iwpWhatsAppChatModel::CHAT_TYPE_QR));
			$qrHeader           = self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_QR_HEADER,            ''));
			$qrText             = self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_QR_TEXT,              ''));
			$qrColor            = self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_QR_COLOR,             iwpWhatsAppChatQrModel::DEFAULT_COLOR));
			$whChatHeader       = self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_HEADER,               ''));
			$whChatBody         = self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_BODY,                 ''));
			$whChatColor        = self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_COLOR,                iwpWhatsAppChatWindowModel::DEFAULT_COLOR));
			$whChatButtonText   = self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_BUTTON_TEXT,          ''));
			$whChatButtonImage  = esc_url(get_option(iwpPluginOptions::WH_CHAT_BUTTON_IMAGE_ID,                 ''));
			$whChatSleep        = (int)self::sanitizeText(get_option(iwpPluginOptions::WH_CHAT_SLEEP,           false));

			// Si el tipo de chat es QR y estamos en un dispositivo móvil, cambiar a "desactivado" para que se abra con el clic
			$whChatType = (($whChatType === iwpWhatsAppChatModel::CHAT_TYPE_QR) && IWP_IS_MOBILE) ? iwpWhatsAppChatModel::CHAT_TYPE_DISABLE : $whChatType;

			$icon = new iwpWhatsAppChatIconModel($whIconPosition, $whIconColor, $whIconImageId, $whIconImageName,
				$whIconTransparent, $whIconBubble, self::encodeDecodeText($whIconBubbleText), $whIconSleep);

			$window = new iwpWhatsAppChatWindowModel(self::encodeDecodeText($whChatHeader),
				self::encodeDecodeText($whChatBody), $whChatColor, self::encodeDecodeText($whChatButtonText),
				$whChatButtonImage, $whChatSleep);

			$qr = new iwpWhatsAppChatQrModel(self::encodeDecodeText($qrHeader), self::encodeDecodeText($qrText), $qrColor);

			$model = new iwpWhatsAppChatModel($whStatus, $whPhone, self::encodeDecodeText($whWelcome), $whChatType);
			$model->setIcon($icon);
			$model->setWindow($window);
			$model->setQr($qr);

			return $model;
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

		private static function sanitizeText($text = '')
		{
			return esc_html(sanitize_text_field($text));
		}
	}