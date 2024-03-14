<?php
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpApiManager.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';

	class iwpPluginOptions {
		const USER_SESSION	 				= 'iwp_user_session';
		const WEB_NAME						= 'iwp_web_name';

		const RE_CONFIG_STATUS 				= 'iwp_reConfigStatus';
		const OLD_VALUES 					= 'iwp_oldValues';
		const RETROACTIVE_INFO	            = 'iwp_retroactive_info';
		const USER_PLAN 					= 'iwp_plan_type';
		const IP_INFO 	                    = 'iwp_ip_info';
		const SYSTEM_INFO                   = 'iwp_system_info';
		const ACCOUNT_ID                    = 'iwp_account_id';
		const APP_KEY                       = 'iwp_appkey';
		const APPLICATION_ID                = 'iwp_application_id';
		const APPLICATION_NAME              = 'iwp_application_name';
		const CUSTOM_EVENT_TRACE_ID         = 'iwp_custom_event_aTraceId';
		const DEVELOPER_MODE                = 'iwp_developer_mode';
		const DO_ACTIVATION_REDIRECT        = 'iwp_do_activation_redirect';
		const LONG2FA_TOKEN                 = 'iwp_long2FA_token';
		const SHORT2FA_TOKEN                = 'iwp_short2FA_token';
		const SHOW_MESSAGE_FIRST_STEP       = 'iwp_show_message_first_step';
		const USER_DOMAIN                   = 'iwp_user_domain';
		const USER_LOGIN                    = 'iwp_user_login';
		const USER_PASSWORD                 = 'iwp_user_password';
		const USER_SECRET_KEY               = 'iwp_user_secretKey';
		const USER_TOKEN                    = 'iwp_user_token';

		const WEB_PUSH_STATUS               = 'iwp_activate_plugin';
		const WEB_PUSH_LOCATION_ACCESS      = 'iwp_location_access';

		const TOPICS_STATUS                 = 'iwp_use_topics';
		const TOPICS_COLOR                  = 'iwp_topics_color';

		const WELCOME_PUSH_ID               = 'iwp_welcome_push_id';
		const WELCOME_PUSH_IMAGE_URL        = 'iwp_welcome_push_image_url';

		const WH_STATUS                     = 'iwp_wh_activate';
		const WH_PHONE_COUNTRY              = 'iwp_wh_phone_country';
		const WH_PHONE                      = 'iwp_wh_phone';
		const WH_ICON_OPTION	            = 'iwp_wh_icon_option';
		const WH_ICON_POSITION              = 'iwp_wh_position';
		const WH_ICON_COLOR                 = 'iwp_wh_colorbutton';
		const WH_ICON_IMAGE_ID              = 'iwp_wh_imageid';
		const WH_ICON_IMAGE_NAME            = 'iwp_wh_image_name';
		const WH_ICON_TRANSPARENT_COLOR     = 'iwp_wh_colorbutton_transparent';
		const WH_ICON_SPEECH_BALLOON		= 'iwp_wh_speech_balloon';
		const WH_ICON_SPEECH_BALLOON_TEXT	= 'iwp_wh_speech_balloon_text';
		const WH_ICON_SLEEP                 = 'iwp_wh_logosleep';
		const WH_CHAT_WELCOME_MESSAGE       = 'iwp_wh_messagetext'; // Texto que se pasa a la url de whatsApp web junto al teléfono
		const WH_CHAT_TYPE	                = 'iwp_wh_type';
		const WH_CHAT_QR_HEADER             = 'iwp_wh_qr_header';
		const WH_CHAT_QR_TEXT	            = 'iwp_wh_qr_text';
		const WH_CHAT_QR_COLOR	            = 'iwp_wh_qr_color';
		const WH_CHAT_HEADER                = 'iwp_wh_header';
		const WH_CHAT_BODY                  = 'iwp_wh_chatText';
		const WH_CHAT_COLOR                 = 'iwp_wh_colortheme';
		const WH_CHAT_BUTTON_TEXT			= 'iwp_wh_chat_button_text';
		const WH_CHAT_BUTTON_IMAGE_ID       = 'iwp_wh_logo';
		const WH_CHAT_SLEEP                 = 'iwp_wh_chatsleep';



		/**
		 * Reseteamos todas las opciones del plugin
		 * @return void
		 */
		public static function resetAllOptions() {
			// Antes de eliminar todos los datos, los guardamos en una option por si necesitamos recuperarlos al iniciar sesión
			self::setOldValuesForDisconnection();

			// Config values (2)
			update_option(self::WEB_PUSH_STATUS, "");
			update_option(self::WEB_PUSH_LOCATION_ACCESS, "");

			// User values (8)
			update_option(self::USER_LOGIN, "");
			update_option(self::USER_PASSWORD, "");
			update_option(self::USER_TOKEN, "");
			update_option(self::USER_SECRET_KEY, "");
			update_option(self::SHORT2FA_TOKEN, "");
			update_option(self::LONG2FA_TOKEN, "");
			update_option(self::ACCOUNT_ID, "");
			update_option(self::USER_DOMAIN,"");

			// Project values (3)
			update_option(self::APP_KEY, "");
			update_option(self::APPLICATION_ID, "");
			update_option(self::APPLICATION_NAME, "");
		}

		/**
		 * Eliminamos todas las opciones del plugin, menos aTraceId para que si vuelve a instalarse,
		 *      tengamos control de que es una reinstalación, y no una instalación nueva
		 * @return void
		 */
		public static function deleteAllOptions() {
			// Eliminamos las posibles options usadas
			delete_option(self::OLD_VALUES);
			delete_option(self::RE_CONFIG_STATUS);

			// Config values (3)
			delete_option(self::WEB_PUSH_STATUS);
			delete_option(self::RETROACTIVE_INFO);
			delete_option(self::USER_PLAN);
			delete_option(self::IP_INFO);
			delete_option(self::SYSTEM_INFO);
			delete_option(self::DO_ACTIVATION_REDIRECT);
			delete_option(self::SHOW_MESSAGE_FIRST_STEP);

			// User values (11)
			delete_option(self::DEVELOPER_MODE);
			delete_option(self::USER_LOGIN);
			delete_option(self::USER_PASSWORD);
			delete_option(self::USER_TOKEN);
			delete_option(self::USER_SECRET_KEY);
			delete_option(self::SHORT2FA_TOKEN);
			delete_option(self::LONG2FA_TOKEN);
			delete_option(self::ACCOUNT_ID);
			delete_option(self::APPLICATION_ID);
			delete_option(self::APPLICATION_NAME);
			delete_option(self::USER_DOMAIN);

			// Topics values (1)
			delete_option(self::TOPICS_STATUS);
			delete_option(self::TOPICS_COLOR);

			// WebPush values (4)
			delete_option(self::WEB_PUSH_LOCATION_ACCESS);
			delete_option(self::APP_KEY);
			delete_option(self::WELCOME_PUSH_ID);
			delete_option(self::WELCOME_PUSH_IMAGE_URL);

			// WhatsApp Chat values (13)
			delete_option(self::WH_STATUS);
			delete_option(self::WH_PHONE);
			delete_option(self::WH_PHONE_COUNTRY);
			delete_option(self::WH_ICON_OPTION);
			delete_option(self::WH_ICON_POSITION);
			delete_option(self::WH_CHAT_BUTTON_IMAGE_ID);
			delete_option(self::WH_ICON_COLOR);
			delete_option(self::WH_ICON_TRANSPARENT_COLOR);
			delete_option(self::WH_ICON_SPEECH_BALLOON);
			delete_option(self::WH_ICON_SPEECH_BALLOON_TEXT);
			delete_option(self::WH_ICON_SLEEP);
			delete_option(self::WH_CHAT_COLOR);
			delete_option(self::WH_CHAT_BUTTON_TEXT);
			delete_option(self::WH_CHAT_TYPE);
			delete_option(self::WH_CHAT_QR_HEADER);
			delete_option(self::WH_CHAT_QR_TEXT);
			delete_option(self::WH_CHAT_QR_COLOR);
			delete_option(self::WH_CHAT_HEADER);
			delete_option(self::WH_CHAT_SLEEP);
			delete_option(self::WH_CHAT_BODY);
			delete_option(self::WH_ICON_IMAGE_ID);
			delete_option(self::WH_ICON_IMAGE_NAME);
			delete_option(self::WH_CHAT_WELCOME_MESSAGE);
		}

		/**
		 * Comprueba si el usuario tiene password y/o la secretKey almacenados.
		 * Si tiene password y no tiene secretKey, lo intenta generar.
		 * Si el proceso es correcto, el usuario no se dará cuenta de nada.
		 * Si hay algún problema o por ejemplo tiene 2FA activado, se redireccionará al onBoarding para
		 * que el usuario vuelva a iniciar la sesión correctamente.
		 */
		public static function reconfigureSecretKeyForOldUsers() {
			$user = get_option(self::USER_LOGIN, '');
			$pass = get_option(self::USER_PASSWORD, '');
			$domain = get_option(self::USER_DOMAIN, '');
			$secretKey = get_option(self::USER_SECRET_KEY, '');

			if (empty($pass) && !empty($secretKey)) {
				// No hace falta hacer nada
				return false;
			}
			if (empty($pass) && empty($secretKey)) {
				// No debería pasar, pero por si acaso, si sucede, le redireccionamos al onBoarding y mostramos un aviso en una pop-up
				$reason = 'User pass and secretKey are empty';
				goto gotoOnBoarding;
			}
			// El resto de casos es que tenemos la contraseña pero no la secretKey. Procedemos a crearla
			require_once IWP_PLUGIN_PATH . 'admin/models/iwpOnBoardingModel.php';
			require_once IWP_PLUGIN_PATH . 'admin/responses/iwpLoginResponse.php';

			$onBoardingModel = new iwpOnBoardingModel();
			$onBoardingModel->setUserName($user);
			$onBoardingModel->setPassword($pass);
			$onBoardingModel->setDomain($domain);
			$loginResponse = $onBoardingModel->consoleLogin();

			$code = $loginResponse->getInternalCode();
			// Controlamos solamente si es correcto. Si llega cualquier otro tipo de error como 2FA, redireccionamos al login

			if ($code !== iwpLoginResponse::LOGIN_OK) {
				switch ($code) {
					case iwpLoginResponse::LOGIN_KO:
						// El login no ha sido correcto
						$reason = 'Wrong login';
						break;
					case iwpLoginResponse::LOGIN_2FA:
						// Login requiere autenticación de doble factor 2FA
						$reason = '2FA is enabled';
						break;
					case iwpLoginResponse::LOGIN_NO_PERMISSIONS:
						// El usuario no tiene el rol correcto para poder iniciar sesión
						$reason = 'User has wrong role';
						break;
					case iwpApiManagerResponse::ERROR_TRY_CATCH:
						// Error genérico al enviar la request
						$reason = 'Try-catch error on login: ' . wp_json_encode($loginResponse);
						break;
					default:
						// En el resto de casos, tratamos como login incorrecto
						$reason = 'Wrong login';
				}
				goto gotoOnBoarding;
			}

			// Para el resto de casos, comprobamos que tenemos la secretKey y si es así, eliminamos el password de la base de datos.
			$secretKey = get_option(self::USER_SECRET_KEY, '');
			if (empty($secretKey)) {
				// No debería pasar, pero por si acaso, lo controlamos
				$reason = 'After login, empty secretKey';
				goto gotoOnBoarding;
			}
			// Reconfiguración correcta, eliminamos el password y no hace falta hacer nada más
			$eventData = array(
				'additionalEvent' => 'Secret key re-configured correctly',
				'redirectReason' => 'No redirect'
			);
			iwpCustomEvents::sendCustomEvent(iwpCustomEvents::DEBUG_ERROR_LEVEL_4, $eventData);
			update_option(self::USER_PASSWORD, "");
			return false;

			gotoOnBoarding:
			self::setReConfigStatus();
			$eventData = array(
				'additionalEvent' => 'Reconfigure secretKey jump to onBoarding',
				'redirectReason' => $reason
			);
			iwpCustomEvents::sendCustomEvent(iwpCustomEvents::DEBUG_ERROR_LEVEL_4, $eventData);
			self::resetAllOptions();
			?><script>window.location.reload();</script><?php
			return true;
		}

		/**
		 * Elimina la option que avisa que ha habido una redirección a la home
		 */
		public static function deleteReConfigStatus() {
			delete_option(self::RE_CONFIG_STATUS);
		}

		/**
		 * Crea una option para mostrar aviso al redireccionar al onBoarding
		 */
		private static function setReConfigStatus() {
			update_option(self::RE_CONFIG_STATUS, '1');
		}

		/**
		 * Guardamos en una option la información del usuario antes de desconectarse. De esta manera si vuelve a
		 * 		conectarse con el mismo usuario, mantendrá los mismos valores.
		 * Si al reconectarse el usuario es distinto, esta información se elimina
		 */
		private static function setOldValuesForDisconnection() {
			$userEmail = get_option(self::USER_LOGIN, "");
			if (empty($userEmail)) {
				return;
			}
			$values = array(
				'userEmail' => $userEmail,
				'info' => array(
					self::WEB_PUSH_STATUS => get_option(self::WEB_PUSH_STATUS, ""),
					self::WEB_PUSH_LOCATION_ACCESS => get_option(self::WEB_PUSH_LOCATION_ACCESS, ""),
				)
			);
			$valuesJson = json_encode($values);
			update_option(self::OLD_VALUES, base64_encode($valuesJson));
		}

		/**
		 * Intentamos recuperar la información de la sesión anterior. Si hay información y los userEmail coinciden,
		 * 		recuperamos la información almacenada.
		 * De lo contrario, no se recupera nada
		 * Siempre se elimina la info de la option al inicio
		 */
		public static function getOldValuesForReconnection() {
			$valuesJson = get_option(self::OLD_VALUES, null);
			delete_option(self::OLD_VALUES);

			try {
				if (!is_null($valuesJson)) {
					$values = json_decode(base64_decode($valuesJson),true);
					if ($values['userEmail'] === get_option(self::USER_LOGIN, "")) {
						foreach ($values['info'] as $k => $v) {
							update_option($k, $v);
						}
					}
				}
			} catch (Exception $e) {
				$currentUserEmail = get_option(self::USER_LOGIN);
				$body = array(
					'info' => 'Trying to recover user old info',
					'oldUserEmail' => (!empty($values['userEmail']) ? $values['userEmail'] : 'empty'),
					'currentUserEmail' => (!empty($currentUserEmail) ? $currentUserEmail : 'empty'),
				);
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, 'SET_OLD_VALUES', '', $body, $e);
			}
		}

		/**
		 * Recibimos los datos sobre la ip del cliente. Debería ser un json.
		 * Codificados en base64 y lo almacenamos en una option.
		 * También lo almacenamos en la sesión.
		 *
		 */
		public static function setIpInfo() {
			$data = iwpAdminUtils::getIpInfo();
			$encodedData = base64_encode($data);
			update_option(self::IP_INFO, $encodedData);
		}

		/**
		 * Obtenemos los datos almacenados sobre la ip del cliente.
		 * Descodificamos en base64 y lo devolvemos. Debería ser un json
		 */
		public static function getIpInfo() {
			return base64_decode(get_option(self::IP_INFO, ''));
		}
	}

