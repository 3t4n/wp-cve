<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpAdminController.php';

	class iwpWidgetController {

		const MAX_TITLE_LENGTH = 60;
		const MAX_BODY_LENGTH = 400;

		/**
		 * Constructor
		 */
		public function __construct() {
			// CSS genérico
			wp_enqueue_style('indigitall-admin-styles', IWP_ADMIN_URL . 'views/admin/css/iwp-main-admin-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-widget-styles', IWP_ADMIN_URL . 'views/widget/css/iwp-widget-styles.css?v=' . IWP_PLUGIN_VERSION);

			// JS genérico
			wp_register_script('indigitall-admin-scripts', IWP_ADMIN_URL . 'views/admin/js/iwp-main-admin-scripts.js?v=' . IWP_PLUGIN_VERSION);
			// Iniciamos las variables globales de administración para JS
			wp_localize_script('indigitall-admin-scripts', 'ADMIN_PARAMS', iwpAdminController::prepareAdminJsParams());
			wp_enqueue_script('indigitall-admin-scripts');
			wp_enqueue_script('indigitall-widget-script', IWP_ADMIN_URL . 'views/widget/js/iwp-widget-scripts.js?v=' . IWP_PLUGIN_VERSION);
		}

		private static function getPostData($post) {
			return array(
				/*title*/   self::encodeAndClearPostTitle($post->post_title),
				/*body*/    self::decodeAndClearText(self::encodeAndClearPostBody(apply_filters('the_content', $post->post_content))),
				/*URL*/     get_permalink($post->ID),
				/*ImageId*/ get_post_thumbnail_id($post),
			);
		}

		public function renderHtml($post) {
			require_once IWP_ADMIN_PATH . 'models/iwpTopicsModel.php';
			require_once IWP_ADMIN_PATH . 'models/iwpTopicModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpTopicsResponse.php';

			// Parámetros para la vista
			list($titleValue, $bodyValue, $urlValue, $imageId) = self::getPostData($post);
			$topicList  = get_option(iwpPluginOptions::TOPICS_STATUS, false) ? self::getTopicList() : array();

			require_once IWP_ADMIN_PATH . 'views/widget/iwpWidgetView.php';
		}

		public static function sendPush($post_id, $post, $updated) {
			require_once IWP_ADMIN_PATH . 'models/iwpWebPushModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpWebPushResponse.php';
			if ($post->post_status !== 'publish') {
				// page is not published
				// Not send push notification
				return;
			}

			// Envío de push activado o desactivado
			$iwpWidgetSend = filter_var(iwpAdminUtils::getPOSTParam('iwpWidgetSend', false), FILTER_VALIDATE_BOOLEAN);
			$ret = array(
				'webPushStatus' => ($iwpWidgetSend) ? 'Enabled' : 'Disabled',
				'createWebPushStatus' => 'NO FIRED',
				'updateWebPushImage' => 'NO FIRED',
				'sendWebPush' => 'NO FIRED'
			);
			if (!$iwpWidgetSend) {
				return;
			}

			list($postTitle, $postBody, $postUrl, $postImageId) = self::getPostData($post);

			// Título del push. Si viene vacío, cogemos del post
			$iwpWidgetTitle = self::encodeAndClearPostTitle(iwpAdminUtils::getPOSTParam('iwpWidgetTitle', false));
			$iwpWidgetTitle = !empty($iwpWidgetTitle) ? self::decodeAndClearText($iwpWidgetTitle) : self::decodeAndClearText($postTitle);

			// Cuerpo del push. Si viene vacío, cogemos del post
			$iwpWidgetBody = self::encodeAndClearPostBody(iwpAdminUtils::getPOSTParam('iwpWidgetBody', false));
			$iwpWidgetBody = !empty($iwpWidgetBody) ? self::decodeAndClearText($iwpWidgetBody) : self::convertCurlyQuotes($postBody);

			// URL del push. Si viene vacío, cogemos del post
			$iwpWidgetUrl = esc_url_raw(iwpAdminUtils::getPOSTParam('iwpWidgetUrl', false));
			$iwpWidgetUrl = !empty($iwpWidgetUrl) ? $iwpWidgetUrl : $postUrl;

			// id de la imagen del push. Si viene vacío, cogemos del post
			$iwpWidgetImageId  = (int)iwpAdminUtils::getPOSTParam('iwpWidgetImageId', false);
			$iwpWidgetImageId  = !empty($iwpWidgetImageId) ? $iwpWidgetImageId : $postImageId;
			$iwpWidgetImageUri = wp_get_original_image_path($iwpWidgetImageId);

			// Se envía a todos los usuarios o se debe filtrar por topics
			$iwpWidgetTopics = trim(iwpAdminUtils::getPOSTParam('iwpWidgetTopics', false));
			$iwpWidgetTopics = filter_var($iwpWidgetTopics, FILTER_VALIDATE_BOOLEAN);

			// Lista de códigos de topics que se deben añadir a la campaña para filtrar los envíos
			// Si tenemos activo el filtro de topics, cargamos la lista de códigos. De lo contrario, creamos un array vacío
			$iwpWidgetTopicElements = $iwpWidgetTopics ? iwpAdminUtils::getPOSTParam('iwpWidgetTopicElements', array()) : array();

			$iwpWidgetName = "WordPress_".self::clearPushTitle($iwpWidgetTitle);

			$webPushModel = new iwpWebPushModel();
			$webPushModel->setName($iwpWidgetName);
			$webPushModel->setTitle(stripslashes($iwpWidgetTitle));
			$webPushModel->setBody(stripslashes($iwpWidgetBody));
			$webPushModel->setUrl($iwpWidgetUrl);
			$webPushModel->setEnabled(true);
			$webPushModel->setIsWelcomePush(false);
			$webPushModel->setTopicsCode($iwpWidgetTopicElements);
			$hasImage = false;
			if (!empty($iwpWidgetImageId) && !empty($iwpWidgetImageUri)) {
				$webPushModel->setImageId($iwpWidgetImageId);
				$webPushModel->setImageUri($iwpWidgetImageUri);
				$hasImage = true;
			}

			$webPushResponse = $webPushModel->consoleCreateWebPush();
			$ret['createWebPushStatus'] = $webPushResponse->getMessage();
			if ($webPushResponse->getInternalCode() === iwpWebPushResponse::WEB_PUSH_OK) {
				if ($hasImage) {
					$webPushImageResponse      = $webPushModel->consoleUpdateWebPushImage();
					$ret['updateWebPushImage'] = $webPushImageResponse->getMessage();
				}

				$webPushSendResponse = $webPushModel->consoleSendWebPush();
				$ret['sendWebPush'] = $webPushSendResponse->getMessage();
				iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MICRO_WIDGET_PUSH_SEND);
			}
			// Pasamos los resultados a un string y lo pintamos para tener información si tenemos activado el developerMode
			$developerMode = filter_var(get_option(iwpPluginOptions::DEVELOPER_MODE, false), FILTER_VALIDATE_BOOLEAN);
			if ($developerMode) {
				$ret = print_r($ret, true);
				echo($ret);
				die;
			}
		}

		private static function getTopicList() {
			$topicsModel = new iwpTopicsModel();
			$topicsResponse = $topicsModel->consoleGetTopics();
			$responseOk = ($topicsResponse->getInternalCode() === iwpTopicsResponse::GET_TOPICS_OK);
			return $responseOk ? $topicsModel->getTopics() : array();
		}

		private static function encodeAndClearPostTitle($postTitle = '') {
			$a0 = str_replace(array(PHP_EOL, '\n', '\r', '\n\r', '\r\n', '\t', '  '), array(' ', ' ', '', '', '', '', ' '), trim($postTitle));
			$a01 = str_replace('  ', ' ', $a0);
			$a1 = strip_tags($a01);
			$a2 = mb_substr($a1, 0, self::MAX_TITLE_LENGTH, 'UTF-8');
			return htmlspecialchars($a2, ENT_QUOTES, 'UTF-8');
		}

		private static function encodeAndClearPostBody($postContent = '') {
			$a0 = str_replace(array(PHP_EOL, '\n', '\r', '\n\r', '\r\n', '\t', '  '), array(' ', ' ', '', '', '', '', ' '), trim($postContent));
			$a01 = str_replace('  ', ' ', $a0);
			$a1 = strip_tags($a01);
			$a2 = mb_substr($a1, 0, self::MAX_BODY_LENGTH, 'UTF-8');
			return htmlspecialchars($a2, ENT_QUOTES, 'UTF-8');
		}

		private static function decodeAndClearText($postContent = '') {
			return htmlspecialchars_decode($postContent, ENT_QUOTES);
		}

		private static function clearPushTitle($string) {
			$string = remove_accents($string);
			$string = mb_substr($string,0, 12, 'UTF-8');
			$string = str_replace(' ', '-', trim($string));
			return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		}

		private static function convertCurlyQuotes($text)
		{
			$quoteMapping = [
				// U+0082⇒U+201A single low-9 quotation mark
				"\xC2\x82"     => "'",

				// U+0084⇒U+201E double low-9 quotation mark
				"\xC2\x84"     => '"',

				// U+008B⇒U+2039 single left-pointing angle quotation mark
				"\xC2\x8B"     => "'",

				// U+0091⇒U+2018 left single quotation mark
				"\xC2\x91"     => "'",

				// U+0092⇒U+2019 right single quotation mark
				"\xC2\x92"     => "'",

				// U+0093⇒U+201C left double quotation mark
				"\xC2\x93"     => '"',

				// U+0094⇒U+201D right double quotation mark
				"\xC2\x94"     => '"',

				// U+009B⇒U+203A single right-pointing angle quotation mark
				"\xC2\x9B"     => "'",

				// U+00AB left-pointing double angle quotation mark
				"\xC2\xAB"     => '"',

				// U+00BB right-pointing double angle quotation mark
				"\xC2\xBB"     => '"',

				// U+2018 left single quotation mark
				"\xE2\x80\x98" => "'",

				// U+2019 right single quotation mark
				"\xE2\x80\x99" => "'",

				// U+201A single low-9 quotation mark
				"\xE2\x80\x9A" => "'",

				// U+201B single high-reversed-9 quotation mark
				"\xE2\x80\x9B" => "'",

				// U+201C left double quotation mark
				"\xE2\x80\x9C" => '"',

				// U+201D right double quotation mark
				"\xE2\x80\x9D" => '"',

				// U+201E double low-9 quotation mark
				"\xE2\x80\x9E" => '"',

				// U+201F double high-reversed-9 quotation mark
				"\xE2\x80\x9F" => '"',

				// U+2039 single left-pointing angle quotation mark
				"\xE2\x80\xB9" => "'",

				// U+203A single right-pointing angle quotation mark
				"\xE2\x80\xBA" => "'",

				// HTML left double quote
				"&ldquo;"      => '"',

				// HTML right double quote
				"&rdquo;"      => '"',

				// HTML left sinqle quote
				"&lsquo;"      => "'",

				// HTML right single quote
				"&rsquo;"      => "'",
			];

			return strtr(html_entity_decode($text, ENT_QUOTES, "UTF-8"), $quoteMapping);
		}
	}