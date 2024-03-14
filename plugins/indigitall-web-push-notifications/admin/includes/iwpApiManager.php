<?php

	require_once IWP_PLUGIN_PATH . 'includes/iwpCurlManager.php';
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpLoginResponse.php';
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpSignUpResponse.php';
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpValidate2FaResponse.php';
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpRefresh2FaResponse.php';
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpApplicationsResponse.php';
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpWebPushResponse.php';
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpTopicsResponse.php';

	class iwpApiManager {

		private static $baseUrl = "https://api.indigitall.com/v1";
		private static $authorizedRolesForLogin = array('account_admin', 'account_owner', 'super_admin');

		/**
		 * Se intenta hacer login usando el usuario y la contraseña recibida.
		 * Si se recibe un dominio concreto, se intentará hacer login a ese dominio en lugar de al predeterminado
		 * Si recibimos 'status code' 200, el login es correcto. Ahora hay que mirar si tenemos el accessToken:
		 *        Si SÍ recibimos el accessToken, el login es correcto. Almacenamos el user y el accountId del usuario.
		 *            Devolvemos 'code' 0 (de que no ha habido ningún error)
		 *        Si NO la recibimos, es que debemos comprobar el 2FA en otro proceso. Guardamos el shortLived2FaToken para ese proceso.
		 *            Devolvemos 'code' 3 (de que hace falta 2FA)
		 * Si recibimos otro 'status code', el login no es correcto y devolvemos 'code' 2
		 * Si ha habido algún tipo de error al hacer request a la api de la consola, devolvemos 'code' 1 y el mensaje de error
		 *
		 * @param $user
		 * @param $password
		 * @param $domain
		 *
		 * @return iwpLoginResponse
		 */
		public static function login($user, $password, $domain = "") {
			$domain = esc_attr($domain);
			$endPoint = "auth";
			$uri = self::getApiUrl($endPoint, $domain);
			$body = array(
				"mail" => $user,
				"password" => $password
			);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, $body, iwpCurlManager::AUTH_OPENED, false);
				update_option(iwpPluginOptions::USER_TOKEN,"");
				update_option(iwpPluginOptions::USER_SECRET_KEY,"");
				update_option(iwpPluginOptions::USER_DOMAIN,"");
				if ($response->statusCode === 200) {
					update_option(iwpPluginOptions::USER_DOMAIN, $domain);
					update_option(iwpPluginOptions::USER_LOGIN, $user);
					if (!empty($response->data->accessToken)) {
						if (empty($response->data->user->roleType)) {
							// No debería pasa que esta propiedad no exista, pero better safe than sorry
							throw new \Exception("User role not received");
						}
						$role = $response->data->user->roleType;
						if (!in_array($role, self::$authorizedRolesForLogin, true)) {
							// El rol del usuario no tiene suficientes permisos para iniciar sesión
							return new iwpLoginResponse(iwpLoginResponse::LOGIN_NO_PERMISSIONS);
						}
						// Login correcto
						update_option(iwpPluginOptions::USER_TOKEN, $response->data->accessToken);
						update_option(iwpPluginOptions::ACCOUNT_ID, $response->data->user->accountId);

						self::getSecretKey(); // Llamamos a api para obtener la serverKey a medida del usuario
						$secretKey = get_option(iwpPluginOptions::USER_SECRET_KEY, "");
						if (empty($secretKey)) {
							// Controlamos que la secretKey se haya creado correctamente. En caso contrario, lanzamos una
							// excepción y no dejaremos iniciar sesión. No debería pasar pero better safe than sorry.
							throw new \Exception("Error getting secretKey");
						}
						return new iwpLoginResponse(iwpLoginResponse::LOGIN_OK);
					}

					if (!empty($response->data->shortLived2FaToken)) {
						// No tenemos accessToken y devolvemos el code para lanzar el 2FA
						update_option(iwpPluginOptions::SHORT2FA_TOKEN, $response->data->shortLived2FaToken);
						return new iwpLoginResponse(iwpLoginResponse::LOGIN_2FA);
					}
					// Petición correcta, pero no tenemos ni el accessToken ni el shortLived2FaToken.
					// No debería pasar pero, better safe than sorry
					throw new \Exception("Correct request but empty accessToken and empty shortLives2FaToken");
				}
				// Error en el login con status 401 o 500. En "data" devolvemos el errorCode
				$errorCode = (!empty($response->errorCode)) ? $response->errorCode : '';
				return new iwpLoginResponse(iwpLoginResponse::LOGIN_KO, $errorCode);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_POST, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpLoginResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		public static function recoverPassword($user, $domain = "") {
			$domain = esc_attr($domain);
			$endPoint = "user/recover";
			$uri = self::getApiUrl($endPoint, $domain);
			$body = array(
				"email" => $user
			);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, $body, iwpCurlManager::AUTH_OPENED, false);

				if ($response->statusCode === 200) {
					return new iwpLoginResponse(iwpLoginResponse::PASS_RECOVER_MAIL_OK);
				}
				// Error en el login con status 401 o 500. En "data" devolvemos el errorCode
				$errorCode = (!empty($response->errorCode)) ? $response->errorCode : '';
				return new iwpLoginResponse(iwpLoginResponse::PASS_RECOVER_MAIL_KO, $errorCode);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_POST, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpLoginResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta validar el 2FA del usuario
		 * Si el statusCode es 200: es correcto, se almacena la información y se genera la secretKey.
		 * Para el resto de casos, se devuelve un código de error.
		 * @param $token
		 * @return iwpValidate2FaResponse
		 */
		public static function validate2FAToken($token) {
			$endPoint = "auth/2fa/validate";
			$uri = self::getApiUrl($endPoint);
			$body = array(
				"totp" => $token,
				"method" => "email"
			);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, $body, iwpCurlManager::AUTH_BEARER_2FA, false);
				update_option(iwpPluginOptions::USER_TOKEN,"");
				update_option(iwpPluginOptions::LONG2FA_TOKEN,"");
				if (($response->statusCode === 200) && !empty($response->data->accessToken)) {
					if (empty($response->data->user->roleType)) {
						// No debería pasa que esta propiedad no exista, pero better safe than sorry
						throw new \Exception("User role not received");
					}
					$role = $response->data->user->roleType;
					if (!in_array($role, self::$authorizedRolesForLogin, true)) {
						// El rol del usuario no tiene suficientes permisos para iniciar sesión
						return new iwpValidate2FaResponse(iwpValidate2FaResponse::VALIDATE_2FA_NO_PERMISSIONS);
					}
					update_option(iwpPluginOptions::SHORT2FA_TOKEN,"");
					update_option(iwpPluginOptions::LONG2FA_TOKEN,$response->data->longLived2FaToken);
					update_option(iwpPluginOptions::USER_TOKEN,$response->data->accessToken);
					update_option(iwpPluginOptions::ACCOUNT_ID,$response->data->user->accountId);

					self::getSecretKey(); // Llamamos a api para obtener la serverKey a medida del usuario
					$secretKey = get_option(iwpPluginOptions::USER_SECRET_KEY, "");
					if (empty($secretKey)) {
						// Controlamos que la secretKey se ha creado correctamente. En caso contrario, lanzamos una
						// excepción y no dejaremos iniciar sesión. No debería pasar pero better safe than sorry.
						throw new \Exception("Error getting secretKey");
					}

					return new iwpValidate2FaResponse(iwpValidate2FaResponse::VALIDATE_2FA_OK);
				}
				return new iwpValidate2FaResponse(iwpValidate2FaResponse::VALIDATE_2FA_KO);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_POST, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpValidate2FaResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta refrescar el token de 2FA
		 * Si el statusCode es 200: es correcto y se almacena la información.
		 * Para el resto de casos, se devuelve un código de error.
		 * @return iwpRefresh2FaResponse
		 */
		public static function refresh2FAToken() {
			$endPoint = "auth/2fa/refresh";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_GET, $uri, null, iwpCurlManager::AUTH_BEARER_2FA, false);
				update_option(iwpPluginOptions::SHORT2FA_TOKEN,"");
				update_option(iwpPluginOptions::USER_TOKEN,"");
				if (($response->statusCode === 200) && !empty($response->data->shortLived2FaToken)) {
					update_option(iwpPluginOptions::SHORT2FA_TOKEN,$response->data->shortLived2FaToken);
					return new iwpRefresh2FaResponse(iwpRefresh2FaResponse::REFRESH_OK);
				}
				return new iwpRefresh2FaResponse(iwpRefresh2FaResponse::REFRESH_KO);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_GET, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpRefresh2FaResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		public static function getAccount() {
			$accountId = get_option(iwpPluginOptions::ACCOUNT_ID, '');
			$endPoint = "account/{$accountId}";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_GET, $uri, null, iwpCurlManager::AUTH_SECRET_KEY, false);
				if (($response->statusCode === 200) && property_exists($response->data, 'accountType')) {
					$accountType = is_null($response->data->accountType) ? 'NULL' : $response->data->accountType;
					update_option(iwpPluginOptions::USER_PLAN, $accountType);
					return $accountType;
				}
				return false;
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_GET, $uri, $body, $e);
				return false;
			}
		}

		/**
		 * Intentamos crear un usuario usando la api del ecommerce
		 * @return iwpSignUpResponse
		 */
		public static function createUser($body) {
			$uri = "aHR0cHM6Ly9lY29tbWVyY2UuaW5kaWdpdGFsbC5jb20vYXBpLnBocA==";

			try {
				$info_encrypted = ['data' => iwpAdminUtils::ind_encrypt(json_encode($body))];
				$ch = curl_init();
				$b = iwpAdminUtils::createFunction(iwpAdminUtils::BASE_FUNC);
				curl_setopt($ch,CURLOPT_URL, $b($uri));
				curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
				curl_setopt($ch,CURLOPT_POSTFIELDS, $info_encrypted);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				$response = curl_exec($ch);

				$eventBody = $body;
				if (is_array($eventBody) && array_key_exists('password', $eventBody)) {
					$eventBody['password'] = substr($eventBody['password'], 0, 5) . '***';
				}
				if (curl_errno($ch)) {
					$eventData = array(
						'error' => curl_error($ch),
						'payload' => array(
							'innerFunction' => __FUNCTION__,
							'uri' => $b($uri),
							'method' => iwpCurlManager::METHOD_POST,
							'data' => $eventBody,
						),
						'response' => $body,
						'system_info' => array(
							'PLUGIN VERSION' => IWP_PLUGIN_VERSION,
							'WORDPRESS_VERSION' => IWP_WORDPRESS_VERSION
						),
						'userInfo' => iwpAdminUtils::getUserPlatform(),
						'serverInfo' => iwpAdminUtils::getServerInfo()
					);
					iwpCustomEvents::sendCustomEvent(iwpCustomEvents::DEBUG_ERROR_LEVEL_2, $eventData);
					return new iwpSignUpResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', curl_error($ch));
				}

				$eventData = array(
					'payload' => array(
						'innerFunction' => __FUNCTION__,
						'uri' => $b($uri),
						'method' => iwpCurlManager::METHOD_POST,
						'data' => $eventBody,
					),
					'response' => json_decode($response, true),
				);
				$resp = json_decode($response, false);
				if ($resp->status === 0) {
					// El registro ha devuelto un error
					iwpCustomEvents::sendCustomEvent(iwpCustomEvents::DEBUG_ERROR_LEVEL_2, $eventData);
				} else  {
					// El registro es correcto
					iwpCustomEvents::sendCustomEvent(iwpCustomEvents::DEBUG_ERROR_LEVEL_3, $eventData);
				}
				curl_close ($ch);

				return new iwpSignUpResponse(null, $response);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_POST, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpSignUpResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Se intenta obtener la lista de aplicaciones creadas por el usuario
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Para el resto de casos, se devuelve un código de error.
		 * @return iwpApplicationsResponse
		 */
		public static function getApplications() {
			$accountId = (int)(get_option(iwpPluginOptions::ACCOUNT_ID, ""));
			$endPoint = "application?accountId=" . $accountId . "&limit=100&page=0";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_GET, $uri, null, iwpCurlManager::AUTH_SECRET_KEY, false);
				if ($response->statusCode === 200) {
					$applicationList = json_encode($response->data);
					return new iwpApplicationsResponse(iwpApplicationsResponse::GET_APPLICATIONS_OK, $applicationList);
				}
				return new iwpApplicationsResponse(iwpApplicationsResponse::GET_APPLICATIONS_KO);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_GET, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpApplicationsResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * @return iwpApplicationsResponse
		 */
		public static function createApplication() {
			$accountId = (int)(get_option(iwpPluginOptions::ACCOUNT_ID, ""));
			$endPoint = "application";
			$uri = self::getApiUrl($endPoint);
			$name = get_option(iwpPluginOptions::WEB_NAME, '');
			$body = array(
				"name" => $name,
				"accountId" => $accountId,
				"androidEnabled" => false,
				"iosEnabled" => false,
				"iosProductionGateway" => true,
				"optionApp" => false,
				"optionWeb" => true,
				"platform" => "web",
				"safariEnabled" => false,
				"webpushEnabled" => true
			);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, $body, iwpCurlManager::AUTH_SECRET_KEY, false);
				if ($response->statusCode === 201) {
					$applicationList = json_encode([$response->data]);
					return new iwpApplicationsResponse(iwpApplicationsResponse::CREATE_APPLICATIONS_OK, $applicationList);
				}
				return new iwpApplicationsResponse(iwpApplicationsResponse::CREATE_APPLICATIONS_KO);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_GET, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpApplicationsResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta recibir los topics paginados de un accountId y una applicationId
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Para el resto de casos, se devuelve un código de error.
		 * @param $page
		 * @param $offset
		 * @return iwpTopicsResponse
		 */
		public static function getTopicsPagination($page, $offset) {
			$accountId = (int)(get_option(iwpPluginOptions::ACCOUNT_ID, ""));
			$applicationId = (int)(get_option(iwpPluginOptions::APPLICATION_ID, ""));
			$endPoint = "application/" . $applicationId . "/topics?accountId=" . $accountId . "&limit=$offset&page=$page";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_GET, $uri, null, iwpCurlManager::AUTH_SECRET_KEY, false);
				if ($response->statusCode === 200) {
					$topicList = json_encode([$response->data]);
					return new iwpTopicsResponse(iwpTopicsResponse::GET_TOPICS_OK, $topicList);
				}
				return new iwpTopicsResponse(iwpTopicsResponse::GET_TOPICS_KO);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_GET, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpTopicsResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta crear un topic en una applicationId
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Si el statusCode es 409: es incorrecto porque el topic ya existe.
		 * Para el resto de casos, se devuelve un código de error.
		 * @param $body
		 * @return iwpTopicsResponse
		 */
		public static function createTopic($body) {
			$applicationId = (int)(get_option(iwpPluginOptions::APPLICATION_ID, ""));
			$endPoint = "application/" . $applicationId . "/topics";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, $body, iwpCurlManager::AUTH_SECRET_KEY, false);
				if ($response->statusCode === 201) {
					$topicList = json_encode([$response->data]);
					return new iwpTopicsResponse(iwpTopicsResponse::CREATE_TOPIC_OK, $topicList);
				}
				if ($response->statusCode === 409) {
					//Topic code exist
					return new iwpTopicsResponse(iwpTopicsResponse::CREATE_TOPIC_CODE_EXISTS);
				}
				return new iwpTopicsResponse(iwpTopicsResponse::CREATE_TOPIC_KO);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_POST, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpTopicsResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta actualizar un topic en una applicationId
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Si el statusCode es 409: es incorrecto porque el topic ya existe. No estoy seguro de que esto pueda
		 * 		pasar, pero por si acaso.
		 * Para el resto de casos, se devuelve un código de error.
		 * @param $body
		 * @param $topicId
		 * @return iwpTopicsResponse
		 */
		public static function updateTopic($body, $topicId) {
			$applicationId = (int)(get_option(iwpPluginOptions::APPLICATION_ID, ""));
			$endPoint = "application/" . $applicationId . "/topics?topicId=$topicId";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_PUT, $uri, $body, iwpCurlManager::AUTH_SECRET_KEY, false);
				if ($response->statusCode === 200) {
					$topicList = json_encode([$response->data]);
					return new iwpTopicsResponse(iwpTopicsResponse::UPDATE_TOPIC_OK, $topicList);
				}
				if ($response->statusCode === 409) {
					//Topic code exist
					return new iwpTopicsResponse(iwpTopicsResponse::UPDATE_TOPIC_CODE_EXISTS);
				}
				return new iwpTopicsResponse(iwpTopicsResponse::UPDATE_TOPIC_KO);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_PUT, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpTopicsResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta eliminar un topic en una applicationId
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Para el resto de casos, se devuelve un código de error.
		 * @param $body
		 * @return iwpTopicsResponse
		 */
		public static function deleteTopic($body) {
			$applicationId = (int)(get_option(iwpPluginOptions::APPLICATION_ID, ""));
			$endPoint = "application/" . $applicationId . "/topics";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_DELETE, $uri, $body, iwpCurlManager::AUTH_SECRET_KEY, false);
				if($response->statusCode === 200){
					return new iwpTopicsResponse(iwpTopicsResponse::DELETE_TOPIC_OK);
				}
				return new iwpTopicsResponse(iwpTopicsResponse::DELETE_TOPIC_KO);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_DELETE, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpTopicsResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta obtener el WelcomePush
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Si el statusCode es 404: es incorrecto porque la campaña no existe.
		 * Para el resto de casos, se devuelve un código de error.
		 * @param $id
		 * @return iwpWebPushResponse
		 */
		public static function getWelcomePushByID($id) {
			$endPoint = "campaign/$id";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_GET, $uri, null, iwpCurlManager::AUTH_SECRET_KEY, false);
				return self::_prepareWebPushResponse($response);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_GET, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpWebPushResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta actualizar una WelcomePush
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Si el statusCode es 404: es incorrecto porque la campaña no existe.
		 * Para el resto de casos, se devuelve un código de error.
		 * @param $body
		 * @param $id
		 * @return iwpWebPushResponse
		 */
		public static function updateWebPush($body, $id) {
			$endPoint = "campaign/$id";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_PUT, $uri, $body, iwpCurlManager::AUTH_SECRET_KEY, false);
				return self::_prepareWebPushResponse($response);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_PUT, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpWebPushResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta crear una WelcomePush
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Si el statusCode es 404: es incorrecto porque la campaña no existe.
		 * Para el resto de casos, se devuelve un código de error.
		 * También se usa para crear campañas cuando se da de alta un nuevo post
		 * @param $body
		 * @return iwpWebPushResponse
		 */
		public static function createWebPush($body) {
			$endPoint = "campaign";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, $body, iwpCurlManager::AUTH_SECRET_KEY, false);
				return self::_prepareWebPushResponse($response);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_POST, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpWebPushResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta actualizar la imagen de una WelcomePush
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Si el statusCode es 404: es incorrecto porque la campaña no existe.
		 * Para el resto de casos, se devuelve un código de error.
		 * El parámetro body debería ser así:
		 *      $body = array('image' => $imageRealPath);
		 * Y el parámetro $imageRealPath debe ser el path real de la imagen dentro del servidor. No la URL pública:
		 * @param $body
		 * @param $id
		 * @return iwpWebPushResponse
		 */
		public static function updateWebPushImage($body, $id) {
			$endPoint = "campaign/$id/image";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, $body, iwpCurlManager::AUTH_SECRET_KEY, true);
				return self::_prepareWebPushResponse($response);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_POST, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpWebPushResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 * Intenta crear una WelcomePush
		 * Si el statusCode es 200: es correcto y se devuelve la información.
		 * Si el statusCode es 404: es incorrecto porque la campaña no existe.
		 * Para el resto de casos, se devuelve un código de error.
		 * También se usa para crear campañas cuando se da de alta un nuevo post
		 * @param $id
		 * @return iwpWebPushResponse
		 */
		public static function sendWebPush($id) {
			$endPoint = "campaign/$id/send/all";
			$uri = self::getApiUrl($endPoint);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, null, iwpCurlManager::AUTH_SECRET_KEY, false);
				return self::_prepareWebPushResponse($response);
			} catch (Exception $e) {
				$body = null;
				iwpCustomEvents::sendTryCatchErrorEvent(__FUNCTION__, iwpCurlManager::METHOD_POST, $uri, $body, $e);
				$errorMessage = "Exception: {$e->getMessage()} \n";
				return new iwpWebPushResponse(iwpApiManagerResponse::ERROR_TRY_CATCH, '', $errorMessage);
			}
		}

		/**
		 *
		 * @param $innerFunction
		 * @param $method
		 * @param $uri
		 * @param $body
		 * @param $error
		 *
		 * @return void
		 */
		public static function sendTryCatchErrorEvent($innerFunction, $method, $uri, $body, $error) {
			$eventData = array(
				'error' => array(
					'code' => $error->getCode(),
					'message' => $error->getMessage(),
					'trace' => $error->getTrace()
				),
				'payload' => array(
					'innerFunction' => $innerFunction,
					'uri' => $uri,
					'method' => $method
				),
				'response' => $body,
				'userInfo' => iwpAdminUtils::getUserPlatform(),
				'serverInfo' => iwpAdminUtils::getServerInfo()
			);
			iwpCustomEvents::sendCustomEvent(iwpCustomEvents::DEBUG_ERROR_LEVEL_1, $eventData);
		}

		/***** FUNCIONES PRIVADAS *****/

		/**
		 * Intentamos obtener la secretKey.
		 * Siempre llamaremos primero al GET, antes de llamar al POST para evitar llamadas innecesarias.
		 *
		 * Si recibimos statusCode 200: la secretKey existe, la obtenemos y la guardamos en su option.
		 * Si recibimos statusCode 404: la secretKey no existe y llamaremos al método POST para que la cree.
		 * Si recibimos cualquier otro statusCode: lanzamos directamente una excepción con el "statusCode" y su mensaje.
		 * @return void
		 * @throws Exception
		 */
		private static function getSecretKey() {
			$accountId = get_option(iwpPluginOptions::ACCOUNT_ID, "");
			$endPoint = "integration/wordpress?id=$accountId";
			$uri = self::getApiUrl($endPoint);

			$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_GET, $uri, null, iwpCurlManager::AUTH_BEARER, false);
			if ($response->statusCode === 200) {
				// Tenemos respuesta correcta
				if (empty($response->data)) {
					// No debería suceder recibir respuesta 200 y sin datos, pero better safe than sorry
					throw new \Exception("Can't request secretKey: " .  json_encode($response) . "\n");
				}
				$data = array_shift($response->data); // data devuelve un array y cogemos el primero.
				update_option(iwpPluginOptions::USER_SECRET_KEY, $data->secretKey);
			} elseif ($response->statusCode === 404) {
				// La secretKey no existe y la intentamos crear llamando al POST
				self::postSecretKey();
			} else {
				// Cualquier otro tipo de error, finalizamos la ejecución y lanzando una excepción
				$statusCode = $response->statusCode;
				$message = $response->message;
				throw new \Exception("Status code: {$statusCode}; {$message}");
			}
		}

		/**
		 * Intentamos crear una nueva secretKey SIEMPRE y cuando aún no exista.
		 * Este proceso se debe lanzar SIEMPRE después de intentar el GET, ya que una de sus respuestas es que la secretKey
		 * 		ya existe y eso implicaría tener que llamar al GET. Para evitar redundancia cíclica, SIEMPRE
		 * 		primero llamaremos al GET y si no tenemos la secretKey, llamaremos a este POST.
		 * De esta manera la mayoría de las veces solamente haremos una llamada a la consola y en contadas excepciones dos.
		 *
		 * Si recibimos statusCode 200: la secretKey se ha creado correctamente y la guardamos en su option.
		 * Si recibimos cualquier otro statusCode: lanzamos directamente una excepción con el "statusCode" y su mensaje.
		 * @return void
		 * @throws Exception
		 */
		private static function postSecretKey() {
			$endPoint = "integration/wordpress";
			$uri = self::getApiUrl($endPoint);

			$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, null, iwpCurlManager::AUTH_BEARER, false);
			if ($response->statusCode === 201) {
				// Tenemos respuesta correcta
				update_option(iwpPluginOptions::USER_SECRET_KEY, $response->data->secretKey);
			} else {
				// Cualquier tipo de error, finalizamos la ejecución y lanzando una excepción
				$statusCode = $response->statusCode;
				$message = $response->message;
				throw new \Exception("Status code: {$statusCode}; {$message}");
			}
		}

		/**
		 * Recibe un endpoint y tal vez un dominio concreto
		 * Devuelve la url completa para llamar a la api de la consola
		 * El dominio es opcional y solamente se usará para usuarios 'alojados' en un servidor 'no habitual'
		 * @param $endPoint
		 * @param $domain
		 * @return string
		 */
		private static function getApiUrl($endPoint = '', $domain = "") {
			if (empty($domain)) {
				// Si no nos viene el $domain, intentamos definirlo con lo guardado. Para auth el $domain debe venir
				$domain = esc_attr(get_option(iwpPluginOptions::USER_DOMAIN, ""));
			}
			return (!empty($domain)) ? "https://$domain.api.indigitall.com/v1/$endPoint" : self::$baseUrl . "/$endPoint";
		}

		/**
		 * Todas las funciones de WebPush procesan igual los resultados. Por eso, modularizamos en una única función
		 * @param $response
		 * @return iwpWebPushResponse
		 */
		private static function _prepareWebPushResponse($response) {
			switch ($response->statusCode) {
				case 200:
				case 201:
					//success
					return new iwpWebPushResponse(iwpWebPushResponse::WEB_PUSH_OK, json_encode($response->data), (string)$response->statusCode);
				case 404:
					//Campaign not found
					return new iwpWebPushResponse(iwpWebPushResponse::WEB_PUSH_NO_CAMPAIGN, '', (string)$response->statusCode);
				default:
					return new iwpWebPushResponse(iwpWebPushResponse::WEB_PUSH_KO, '', (string)$response->statusCode);
			}
		}
	}
