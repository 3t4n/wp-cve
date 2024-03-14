<?php
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';

	class iwpAdminPage {

		// Definimos el slug de las páginas definidas en el plugin
		const PAGE_ON_BOARDING = 'onBoarding';
		const PAGE_WEB_PUSH_CONFIG = 'webPushConfig';
		const PAGE_WEB_PUSH_TOPICS = 'webPushTopics';
		const PAGE_WEB_PUSH_WELCOME = 'webPushWelcome';
		const PAGE_WEB_PUSH_WIDGET = 'webPushWidget';
		const PAGE_WHATSAPP_CHAT = 'whatsAppChat';

		/**
		 * Constructor
		 * No se usa
		 */
		public function __construct() {}

		/**
		 * Calculamos la url del admin
		 * @return string
		 */
		public static function getSiteUrl()  {
			return get_site_url(null, '/wp-admin/admin.php');
		}

		/**
		 * Calculamos la página que se debe mostrar
		 * @return string
		 */
		public static function getInnerPage() {
//			if (!get_option(iwpPluginOptions::APP_KEY)) {
//				// No tenemos appKey definida. Nos vamos al onBoarding
//				return self::PAGE_ON_BOARDING;
//			}

			// Tenemos el identificador del "proyecto", por lo tanto, cargamos la página correspondiente predeterminada
			if (filter_var(get_option(iwpPluginOptions::WH_STATUS, false), FILTER_VALIDATE_BOOLEAN)) {
				// Si tenemos WhatsApp Chat activado, cargamos su vista
				$innerPage = self::PAGE_WHATSAPP_CHAT;
			} elseif (filter_var(get_option(iwpPluginOptions::WEB_PUSH_STATUS, false), FILTER_VALIDATE_BOOLEAN)) {
				// Si tenemos Web Push activado (se presupone que WhatsApp Chat no está activado), cargamos su vista
				$innerPage = self::PAGE_WEB_PUSH_CONFIG;
			} else {
				// No debería pasar, pero si hay cualquier error como que no haya canal alguno activado,
				//      pero tenemos el identificador del proyecto, mostramos WhatsApp Chat.
				$innerPage = self::PAGE_WHATSAPP_CHAT;
			}

			// Tenemos appKey y vamos a la página correspondiente. Si no hay página definida, iremos al de WhatsAppChat
			return sanitize_text_field(iwpAdminUtils::getGETParam('inner-page', $innerPage));
		}

		public static function isWebPushPage() {
			switch (self::getInnerPage()) {
				case self::PAGE_WEB_PUSH_CONFIG:
				case self::PAGE_WEB_PUSH_WELCOME:
				case self::PAGE_WEB_PUSH_TOPICS:
					return true;
				default:
					return false;
			}
		}

	}