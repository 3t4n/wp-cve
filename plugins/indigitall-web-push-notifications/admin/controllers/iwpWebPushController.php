<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_ADMIN_PATH . 'includes/iwpAdminUtils.php';
	require_once IWP_ADMIN_PATH . 'includes/iwpCustomEvents.php';
	require_once IWP_ADMIN_PATH . 'includes/iwpAdminPage.php';

	class iwpWebPushController {

		/** @var string */
		private $currentPage;

		/** @var string */
		private $siteUrl;

		/**
		 * Constructor
		 */
		public function __construct() {
			wp_enqueue_style('indigitall-webPush-styles', IWP_ADMIN_URL . 'views/webPush/css/iwp-webPush-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-webPush-menu-styles', IWP_ADMIN_URL . 'views/webPush/css/iwp-webPush-menu-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-webPush-config-styles', IWP_ADMIN_URL . 'views/webPush/css/iwp-webPush-config-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-webPush-welcome-styles', IWP_ADMIN_URL . 'views/webPush/css/iwp-webPush-welcome-styles.css?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_style('indigitall-webPush-topics-styles', IWP_ADMIN_URL . 'views/webPush/css/iwp-webPush-topics-styles.css?v=' . IWP_PLUGIN_VERSION);

			wp_enqueue_script('indigitall-webPush-scripts', IWP_ADMIN_URL . 'views/webPush/js/iwp-webPush-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-webPush-config-scripts', IWP_ADMIN_URL . 'views/webPush/js/iwp-webPush-config-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-webPush-welcome-scripts', IWP_ADMIN_URL . 'views/webPush/js/iwp-webPush-welcome-scripts.js?v=' . IWP_PLUGIN_VERSION);
			wp_enqueue_script('indigitall-webPush-topics-scripts', IWP_ADMIN_URL . 'views/webPush/js/iwp-webPush-topics-scripts.js?v=' . IWP_PLUGIN_VERSION);

			$this->currentPage = iwpAdminPage::getInnerPage();
			$this->siteUrl = iwpAdminPage::getSiteUrl();
		}

		public function renderHtml() {
			$subPageHtml = '';
			$currentPageFunction = 'load' . ucfirst($this->currentPage);
			if (method_exists(__CLASS__, $currentPageFunction)) {
				$subPageHtml = $this->{$currentPageFunction}();
			}

			$mainMenuHtml = $this->loadWebPushMenu();
			$status = ((get_option(iwpPluginOptions::WEB_PUSH_STATUS, '0') === '1') ? 'enabled' : 'disabled');

			ob_start();
			$topicModal = iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . 'views/webPush/partials/iwpWebPushTopicsModal.php');
			include_once IWP_ADMIN_PATH . 'views/webPush/iwpWebPushView.php';
			return ob_get_clean();
		}

		private function loadWebPushMenu() {
			$menuItems = $this->loadWebPushMenuItems();

			// Cargamos la vista y los estilos del footer
			ob_start();
			include_once IWP_ADMIN_PATH . 'views/webPush/partials/iwpWebPushMenu.php';
			return ob_get_clean();
		}

		private function loadWebPushMenuItems() {
			$webPushConfigCurrentPage = ($this->currentPage === iwpAdminPage::PAGE_WEB_PUSH_CONFIG);
			$webPushConfigLink = "{$this->siteUrl}?page=indigitall-push&inner-page=" . iwpAdminPage::PAGE_WEB_PUSH_CONFIG;

			$webPushWelcomeCurrentPage = ($this->currentPage === iwpAdminPage::PAGE_WEB_PUSH_WELCOME);
			$webPushWelcomeLink = "{$this->siteUrl}?page=indigitall-push&inner-page=" . iwpAdminPage::PAGE_WEB_PUSH_WELCOME;

			$webPushTopicsCurrentPage = ($this->currentPage === iwpAdminPage::PAGE_WEB_PUSH_TOPICS);
			$webPushTopicsLink = "{$this->siteUrl}?page=indigitall-push&inner-page=" . iwpAdminPage::PAGE_WEB_PUSH_TOPICS;

			return array(
				array(
					'name' => __('Settings',  'iwp-text-domain'),
					'currentPage' => ($webPushConfigCurrentPage ? 'active' : ''),
					'link' => $webPushConfigLink,
				),
				array(
					'name' => __('Welcome Push',  'iwp-text-domain'),
					'currentPage' => ($webPushWelcomeCurrentPage ? 'active' : ''),
					'link' => $webPushWelcomeLink,
				),
				array(
					'name' => __('Audience',  'iwp-text-domain'),
					'currentPage' => ($webPushTopicsCurrentPage ? 'active' : ''),
					'link' => $webPushTopicsLink,
				),
			);
		}

		private function loadWebPushConfig() {
			$params = array(
				'notifications' => array(
					'id' => 'iwp-admin-webPush-config-webPush-notifications-container',
					'title' => __('webPushConfigNotificationsTitle',  'iwp-text-domain'),
					'checked' => ((get_option(iwpPluginOptions::WEB_PUSH_STATUS, '0') === '1') ? 'checked' : ''),
					'label' => __('webPushConfigNotificationsLabel',  'iwp-text-domain'),
				),
				'location' => array(
					'id' => 'iwp-admin-webPush-config-webPush-location-container',
					'title' => __('webPushConfigLocationTitle',  'iwp-text-domain'),
					'checked' => ((get_option(iwpPluginOptions::WEB_PUSH_LOCATION_ACCESS, '0') === '1') ? 'checked' : ''),
					'label' => __('webPushConfigLocationLabel',  'iwp-text-domain'),
				),
			);
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . "views/webPush/partials/iwpWebPushConfig.php", $params);
		}

		private function loadWebPushWelcome() {
			require_once IWP_ADMIN_PATH . 'models/iwpWebPushModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpWebPushResponse.php';

			$webPushModel = new iwpWebPushModel();
			$welcomePushId = (int)get_option(iwpPluginOptions::WELCOME_PUSH_ID, 0);
			if (!empty($welcomePushId)) {
				$webPushModel->setCampaignId($welcomePushId);
				$webPushModel->consoleGetWelcomePush();
			}

			$params = array(
				'webPushModel' => $webPushModel,
			);
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . "views/webPush/partials/iwpWebPushWelcome.php", $params);
		}

		private function loadWebPushTopics() {
			require_once IWP_ADMIN_PATH . 'models/iwpTopicsModel.php';
			require_once IWP_ADMIN_PATH . 'models/iwpTopicModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpTopicsResponse.php';

			$topicsModel = new iwpTopicsModel();
			$topicsResponse = $topicsModel->consoleGetTopics();
			$responseOk = ($topicsResponse->getInternalCode() === iwpTopicsResponse::GET_TOPICS_OK);

			$defaultColor = '#8db8ff'; // Definimos un color predeterminado por si acaso
			$params = array(
				'topics' => $responseOk ? $topicsModel->getTopics() : array(),
				'topicsStatus' => get_option(iwpPluginOptions::TOPICS_STATUS, false),
				'topicsColor' => get_option(iwpPluginOptions::TOPICS_COLOR, $defaultColor),
				'showError' => (!$responseOk),
			);
			return iwpAdminUtils::loadViewToVar(IWP_ADMIN_PATH . "views/webPush/partials/iwpWebPushTopics.php", $params);
		}



		/***** FUNCIONES AJAX *****/

		/* AJAX PARA TOPICS */

		public static function updateTopicAjax()  {
			require_once IWP_ADMIN_PATH . 'models/iwpTopicModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpTopicsResponse.php';
			$topicId = (int)iwpAdminUtils::sanitizeText(iwpAdminUtils::getPOSTParam('topicId', 0));
			$topicName = iwpAdminUtils::sanitizeText(stripslashes(trim(iwpAdminUtils::getPOSTParam('name', false))));
			if (empty($topicName) || empty($topicId)) {
				return json_encode(array(
					'status' => 0
				));
			}
			$topicModel = new iwpTopicModel($topicId, $topicName, null);
			$topicResponse = $topicModel->consoleUpdateTopic();
			if ($topicResponse->getInternalCode() === iwpTopicsResponse::UPDATE_TOPIC_OK) {
				iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MICRO_WP_TOPIC_EDIT);
				return json_encode(array(
					'status' => 1
				));
			}
			return json_encode(array(
				'status' => 0
			));
		}

		public static function deleteTopicAjax() {
			require_once IWP_ADMIN_PATH . 'models/iwpTopicModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpTopicsResponse.php';

			$topicId = (int)iwpAdminUtils::getPOSTParam('topicId', 0);

			$topicModel = new iwpTopicModel($topicId);
			$topicResponse = $topicModel->consoleDeleteTopic();
			if ($topicResponse->getInternalCode() === iwpTopicsResponse::DELETE_TOPIC_OK) {
				iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MICRO_WP_TOPIC_DELETE);
				return json_encode(array(
					'status' => 1,
					'message' => ''
				));
			}
			return json_encode(array(
				'status' => 0,
				'message' => __("Error deleting interest group", 'iwp-text-domain')
			));
		}

		public static function createTopicAjax() {
			require_once IWP_ADMIN_PATH . 'models/iwpTopicModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpTopicsResponse.php';

			$topicName = iwpAdminUtils::sanitizeText(stripslashes(trim(iwpAdminUtils::getPOSTParam('name', false))));
			$topicCode = stripslashes(trim(iwpAdminUtils::getPOSTParam('code', false)));

			if (empty($topicName) || empty($topicCode)) {
				return json_encode(array(
					'status' => 0,
					'message' => __("Fill in all the mandatory fields", 'iwp-text-domain')
				));
			}

			$topicModel = new iwpTopicModel(null, $topicName, $topicCode);
			$topicResponse =  $topicModel->consoleCreateTopic();
			switch ($topicResponse->getInternalCode()) {
				case iwpTopicsResponse::CREATE_TOPIC_OK:
					iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MICRO_WP_TOPIC_CREATE);
					return json_encode(array(
						'status' => 1,
						'message' => ''
					));
				case iwpTopicsResponse::CREATE_TOPIC_CODE_EXISTS:
					return json_encode(array(
						'status' => 0,
						'message' => __("A group already exists with the same code. Try another code", 'iwp-text-domain')
					));
				case iwpTopicsResponse::CREATE_TOPIC_KO:
				default:
					return json_encode(array(
						'status' => 0,
						'message' => __("Error creating interest group", 'iwp-text-domain')
					));
			}
		}

		/* AJAX PARA WEB PUSH */

		public static function createWebPushAjax() {
			require_once IWP_ADMIN_PATH . 'models/iwpWebPushModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpWebPushResponse.php';

			$webPushModel = self::prepareWebPushModel();
			if (is_string($webPushModel)) {
				return $webPushModel;
			}
			$webPushResponse = $webPushModel->consoleCreateWebPush();
			$imageId = self::prepareWebPushModelImage($webPushModel);

			// Si tenemos imagen, asignaremos false para asignarle true después de terminar el proceso completo.
			//      Si no tenemos imagen, asignaremos true directamente.
			$webPushModel->setEnabled(!$imageId);

			return self::prepareWebPushResponse($webPushResponse, $webPushModel, true);
		}

		public static function updateWebPushAjax() {
			require_once IWP_ADMIN_PATH . 'models/iwpWebPushModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpWebPushResponse.php';

			$webPushModel = self::prepareWebPushModel();
			if (is_string($webPushModel)) {
				return $webPushModel;
			}
			// No debería suceder porque estamos editando un push y ese valor debería existir
			$welcomePushId = (int)get_option(iwpPluginOptions::WELCOME_PUSH_ID, 0);
			$webPushModel->setCampaignId($welcomePushId);

			$webPushResponse = $webPushModel->consoleUpdateWebPush();

			self::prepareWebPushModelImage($webPushModel);

			return self::prepareWebPushResponse($webPushResponse, $webPushModel, false);
		}

		public static function changeWebPushStatusAjax($status) {
			require_once IWP_ADMIN_PATH . 'models/iwpWebPushModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpWebPushResponse.php';

			$updateEnableDisableButton = $status ? 'iwpAdminWebPushWelcomeDisable' : 'iwpAdminWebPushWelcomeEnable';
			$updateEnableDisableButtonError = !$status ? 'iwpAdminWebPushWelcomeDisable' : 'iwpAdminWebPushWelcomeEnable';
			$updateEnableDisableMessage = $status ? __('Welcome push activated successfully', 'iwp-text-domain') : __('Welcome push disabled successfully', 'iwp-text-domain');
			$mainEvent = ($status) ? iwpCustomEvents::MICRO_WP_WELCOME_ACTIVAR : iwpCustomEvents::MICRO_WP_WELCOME_DESACTIVAR;

			$webPushModel = new iwpWebPushModel();
			$welcomePushId = (int)get_option(iwpPluginOptions::WELCOME_PUSH_ID, 0);
			$webPushModel->setCampaignId($welcomePushId);
			$webPushModel->setEnabled($status);
			$webPushResponse = $webPushModel->consoleChangeWebPushStatus();

			if ($webPushResponse->getInternalCode() === iwpWebPushResponse::WEB_PUSH_OK) {
				iwpCustomEvents::sendCustomEvent($mainEvent);
				return json_encode(array(
					'status' => 1,
					'message' => $updateEnableDisableMessage,
					'buttons' => array(
						$updateEnableDisableButton,
						'iwpAdminWebPushWelcomeUpdate',
					),
				));
			}
			return json_encode(array(
				'status' => 0,
				'message' => __('Welcome push could not be saved successfully', 'iwp-text-domain'),
				'buttons' => array(
					$updateEnableDisableButtonError,
					'iwpAdminWebPushWelcomeUpdate',
				),
			));
		}

		/***** FUNCIONES SECUNDARIAS *****/
		/**
		 * Examina si algún campo obligatorio está vacío
		 */
		private static function checkWebPushMandatoryFields($title, $body, $url) {
			if (!$title || !$body || !$url) {
				// Algún campo está vacío
				return json_encode(array(
					'status' => 0,
					'message' => __("Fill in all the mandatory fields",'iwp-text-domain'),
					'fields' => array(
						'iwpAdminWebPushWelcomeTitle'   => empty($title),
						'iwpAdminWebPushWelcomeBody'    => empty($body),
						'iwpAdminWebPushWelcomeUrl'     => empty($url),
					),
					'buttons' => array(
						'iwpAdminWebPushWelcomeCreate'
					),
				));
			}
			return false;
		}

		/**
		 * Crea el modelo de datos y añade los valores recibidos
		 */
		private static function prepareWebPushModel() {
			require_once IWP_ADMIN_PATH . 'models/iwpWebPushModel.php';

			$isWelcomePush = iwpAdminUtils::getPOSTParam('isWelcomePush', false);
			$isWelcomePush = filter_var($isWelcomePush, FILTER_VALIDATE_BOOLEAN);
			$title = iwpAdminUtils::sanitizeText(trim(iwpAdminUtils::getPOSTParam('title', false)));
			$body = iwpAdminUtils::sanitizeText(trim(iwpAdminUtils::getPOSTParam('body', false)));
			$url = esc_url(trim(iwpAdminUtils::getPOSTParam('url', false)));
			$imageId = (int)trim(iwpAdminUtils::getPOSTParam('imageId', false));

			if ($errorResponse = self::checkWebPushMandatoryFields($title, $body, $url)) {
				return $errorResponse;
			}

			$webPushModel = new iwpWebPushModel();
			$webPushModel->setTitle($title);
			$webPushModel->setBody($body);
			$webPushModel->setUrl($url);
			$webPushModel->setIsWelcomePush($isWelcomePush);

			if ($imageId < 0) {
				// Si recibimos un valor inferior a 0, es que queremos eliminar la del push. Por lo tanto, actualizamos
				//      el valor de la option para que dentro del modelo decida no enviarlo.
				update_option(iwpPluginOptions::WELCOME_PUSH_IMAGE_URL, null);
			}

			return $webPushModel;
		}

		/**
		 * Si tenemos una imageId superior a 0, actualizamos los campos para procesar la imagen posteriormente
		 */
		private static function prepareWebPushModelImage(&$webPushModel) {
			$imageId = (int)trim(iwpAdminUtils::getPOSTParam('imageId', false));
			if ($imageId > 0) {
				$webPushModel->setImageUri(wp_get_original_image_path($imageId));
				$webPushModel->setImageId($imageId);
			}
			return $imageId;
		}

		/**
		 * Preparamos la respuesta a la creación o actualización del push
		 */
		private static function prepareWebPushResponse($webPushResponse, $webPushModel, $isCreate = false) {
			$okMessage = $isCreate ? __("Welcome push saved and activated successfully",'iwp-text-domain') : __("Welcome push updated successfully",'iwp-text-domain');
			$updateEnableDisableButton = $webPushModel->isEnabled() ? 'iwpAdminWebPushWelcomeDisable' : 'iwpAdminWebPushWelcomeEnable';
			$mainEvent = $isCreate ? iwpCustomEvents::MICRO_WP_WELCOME_CREATE : iwpCustomEvents::MICRO_WP_WELCOME_UPDATE;

			if ($webPushResponse->getInternalCode() === iwpWebPushResponse::WEB_PUSH_OK) {
				iwpCustomEvents::sendCustomEvent($mainEvent);

				if (!is_null($webPushModel->getImageId())) {
					// Tiene imagen para actualizar y devolverá directamente el resultado
					return self::changeWebPushImage($webPushModel, $isCreate);
				}

				// Se ha creado el push activado. No tiene imagen a actualizar
				if ($isCreate) {
					iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MICRO_WP_WELCOME_ACTIVAR);
				}
				return json_encode(array(
					'status' => 1,
					'message' => $okMessage,
					'buttons' => array(
						$isCreate ? 'iwpAdminWebPushWelcomeDisable' : $updateEnableDisableButton,
						'iwpAdminWebPushWelcomeUpdate',
					),
				));
			}
			// NO se ha creado el push correctamente
			$ret = array(
				'status' => 0,
				'message' => __("Welcome push could not be saved successfully",'iwp-text-domain'),
				'buttons' => array(
					$isCreate ? 'iwpAdminWebPushWelcomeCreate' : 'iwpAdminWebPushWelcomeUpdate'
				),
			);
			if (!$isCreate) {
				$ret['buttons'][] = $updateEnableDisableButton;
			}
			return json_encode($ret);
		}

		/**
		 * Tenemos una nueva imagen y procedemos a subirla y asignarla al push
		 */
		private static function changeWebPushImage($webPushModel, $isCreate) {
			$updateEnableDisableButton = $webPushModel->isEnabled() ? 'iwpAdminWebPushWelcomeDisable' : 'iwpAdminWebPushWelcomeEnable';
			$errorMessage = $isCreate ? __("welcomePuWelcome push saved, but disabled because the image could not be uploaded successfullyshSavedDeactivatedByImageError",'iwp-text-domain') : __("Welcome push could not be saved successfully",'iwp-text-domain');

			$webPushImageResponse = $webPushModel->consoleUpdateWebPushImage();

			if ($webPushImageResponse->getInternalCode() === iwpWebPushResponse::WEB_PUSH_OK) {
				// La imagen se ha añadido correctamente al push
				iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MICRO_WP_WELCOME_AD_IMAGE);

				if ($isCreate) {
					// Solamente activamos el push si lo estamos creando. Si es edición, no cambiaremos su estado.
					return self::toggleWebPushStatus($webPushModel, true, true);
				}

				return json_encode(array(
					'status' => 1,
					'message' => __("Welcome push updated successfully",'iwp-text-domain'),
					'buttons' => array(
						$updateEnableDisableButton,
						'iwpAdminWebPushWelcomeUpdate',
					),
				));
			}
			// Se ha creado/editado el push, pero NO se ha podido añadir la imagen
			$updateEnableDisableButton = $isCreate ? 'iwpAdminWebPushWelcomeEnable' : $updateEnableDisableButton;
			return json_encode(array(
				'status' => 0,
				'message' => $errorMessage,
				'buttons' => array(
					$updateEnableDisableButton,
					'iwpAdminWebPushWelcomeUpdate',
				),
			));
		}

		/**
		 * Cambiamos el status de un push
		 */
		private static function toggleWebPushStatus($webPushModel, $status, $isCreate) {
			$mainEvent = ($status) ? iwpCustomEvents::MICRO_WP_WELCOME_ACTIVAR : iwpCustomEvents::MICRO_WP_WELCOME_DESACTIVAR;
			// Asignamos el botón habitual que se verá al cambiar de estado
			$updateEnableDisableButton = $status ? 'iwpAdminWebPushWelcomeDisable' : 'iwpAdminWebPushWelcomeEnable';

			// Asignamos los textos habituales de activado/desactivado
			$okMessage = $status ? __("Welcome push activated successfully",'iwp-text-domain') : __("Welcome push disabled successfully",'iwp-text-domain');
			// Si estamos creando el push, el mensaje será de "Creado correctamente" o algo similar
			$okMessage = $isCreate ? __("Welcome push saved and activated successfully",'iwp-text-domain') : $okMessage;
			// El mensaje de error varía si estamos creando una welcome push o editando solamente el estado a un push que ya existe
			$errorMessage = $isCreate ? __("Welcome push saved successfully, but could not be activated",'iwp-text-domain') : __("Welcome push could not be saved successfully",'iwp-text-domain');

			$webPushModel->setEnabled($status);

			$webPushUpdateResponse = $webPushModel->consoleUpdateWebPush();
			if ($webPushUpdateResponse->getInternalCode() === iwpWebPushResponse::WEB_PUSH_OK) {
				// Se ha asignado correctamente el status al push
				iwpCustomEvents::sendCustomEvent($mainEvent);
				return json_encode( array(
					'status'  => 1,
					'message' => $okMessage,
					'buttons' => array(
						$updateEnableDisableButton,
						'iwpAdminWebPushWelcomeUpdate',
					),
				) );
			}
			// El push está creado pero no se ha podido cambiar su status
			$updateEnableDisableButton = $isCreate ? 'iwpAdminWebPushWelcomeEnable' : $updateEnableDisableButton;
			return json_encode(array(
				'status' => 0,
				'message' => $errorMessage,
				'buttons' => array(
					$updateEnableDisableButton,
					'iwpAdminWebPushWelcomeUpdate',
				),
			));
		}
	}