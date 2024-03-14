<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';

	class iwpLoginUtils {

		/* PUBLIC STATIC FUNCTIONS */

		/**
		 * Ajax sign up
		 */
		public static function iwpSignUp() {
			require_once IWP_ADMIN_PATH . 'models/iwpSignUpModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpSignUpResponse.php';

			$email = trim(iwpAdminUtils::getPOSTParam('userNewEmail', ''));
			$password = iwpAdminUtils::getPOSTParam('userNewPassword', '');
			$rePassword = iwpAdminUtils::getPOSTParam('userNewPasswordConfirm', '');
			$countryInnerId = (int)iwpAdminUtils::getUserIpCountry('ecommerceCountryId'); // Asignamos el país según su IP

			$confirmTermsCheckbox = iwpAdminUtils::getPOSTParam('confirmTermsCheckbox');
			$confirmTermsCheckbox = filter_var($confirmTermsCheckbox, FILTER_VALIDATE_BOOLEAN);

			$confirmNewslettersCheckbox = iwpAdminUtils::getPOSTParam('confirmNewsletters');
			$confirmNewslettersCheckbox = filter_var($confirmNewslettersCheckbox, FILTER_VALIDATE_BOOLEAN);

			// Comprobación de requisitos + validación antes de seguir adelante
			$validationData = array(
				'userNewEmail'           => $email,
				'userNewPassword'        => $password,
				'userNewPasswordConfirm' => $rePassword,
				'confirmTermsCheckbox'   => $confirmTermsCheckbox,
			);
			$validation = self::validateSignUp($validationData);
			if ($validation['status'] !== 1) {
				return json_encode($validation);
			}

			$signUpModel = new iwpSignUpModel($email, $password, $countryInnerId, $confirmNewslettersCheckbox);
			$signUpResponse = $signUpModel->consoleSignUpUser();
			switch ($signUpResponse->getInternalCode()) {
				case iwpSignUpResponse::SIGNUP_OK:
					require_once IWP_ADMIN_PATH . 'models/iwpOnBoardingModel.php';

					iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_PLUGIN_USER_CREATE);

					$onBoardingModel = new iwpOnBoardingModel();
					$onBoardingModel->setUserName($email);
					$onBoardingModel->setPassword($password);

					$ret = json_decode(self::iwpLogin($onBoardingModel), false);
					if ($ret->status === 1) {
						// Login correcto
						return json_encode($ret);
					}

					// Ha habido un error al hacer login y mostramos otro mensaje diferente para todos los errores
					$ret = array(
						'status' => 0,
						'message' => __("The user has been created successfully, but there was an error logging in automatically. Please try again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>",'iwp-text-domain')
					);
					return json_encode($ret);
				case iwpSignUpResponse::SIGNUP_PASSWORD_VALIDATE_ERROR:
					$ret = array(
						'status' => 2,
						'fields' => array('userNewPassword' => true),
						'message' => __('The password must be 8 characters or more in length (max: 32 characters) and contain at least one letter and one number', 'iwp-text-domain')
					);
					break;
				case iwpSignUpResponse::SIGNUP_MANDATORY_FIELDS_ERROR:
					$ret = array(
						'status' => 2,
						'fields' => array(
							'userNewEmail' => true,
							'userNewPassword' => true,
							'userNewPasswordConfirm' => true,
							'confirmTermsCheckbox' => true,
						),
						'message' => __('Fill in all the mandatory fields', 'iwp-text-domain')
					);
					break;
				case iwpSignUpResponse::SIGNUP_ACCOUNT_EXISTS:
					$ret = array(
						'status' => 0,
						'fields' => array('userNewEmail' => true),
						'message' => __("Account already exists. Please try to log in again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>", 'iwp-text-domain')
					);
					break;
				case iwpSignUpResponse::SIGNUP_EMAIL_ERROR:
					$ret = array(
						'status' => 2,
						'fields' => array('userNewEmail' => true),
						'message' => __("Invalid email. Type the email correctly. Please try again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>", 'iwp-text-domain')
					);
					break;
				case iwpSignUpResponse::SIGNUP_UNKNOWN:
				case iwpApiManagerResponse::ERROR_TRY_CATCH:
				default:
					$ret = array(
						'status' => 2,
						'message' => __("Unknown error. Please try again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>", 'iwp-text-domain')
					);
			}
			return json_encode($ret);
		}

		/**
		 * Ajax login
		 */
		public static function iwpLogin($onBoardingModel = null) {
			require_once IWP_ADMIN_PATH . 'models/iwpOnBoardingModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpLoginResponse.php';

			if (is_null($onBoardingModel)) {
				$onBoardingModel = new iwpOnBoardingModel();
				$onBoardingModel->setUserName(trim(iwpAdminUtils::getPOSTParam('userEmail', '')));
				$onBoardingModel->setPassword(iwpAdminUtils::getPOSTParam('userPassword', ''));
				$customDomainCheck = iwpAdminUtils::getPOSTParam('userDomainCheckbox');
				if (filter_var($customDomainCheck, FILTER_VALIDATE_BOOLEAN)) {
					$onBoardingModel->setDomain(trim(esc_attr(iwpAdminUtils::getPOSTParam('userDomain', null))));
				}
			}

			$loginResponse = $onBoardingModel->consoleLogin();
			$ret = $loginResponse->getLoginResponseResult($onBoardingModel);
			return json_encode($ret);
		}

		/**
		 * Ajax recover password
		 */
		public static function iwpRecoverPass() {
			$onBoardingModel = new iwpOnBoardingModel();
			$onBoardingModel->setUserName(trim(iwpAdminUtils::getPOSTParam('recoverPassUserEmail', '')));
			$customDomainCheck = iwpAdminUtils::getPOSTParam('recoverPassUserDomainCheckbox');
			if (filter_var($customDomainCheck, FILTER_VALIDATE_BOOLEAN)) {
				$onBoardingModel->setDomain(trim(esc_attr(iwpAdminUtils::getPOSTParam('recoverPassUserDomain', null))));
			}
			$emailRecoverResponse = $onBoardingModel->recoverPasswordEmail();

			if ($emailRecoverResponse->getInternalCode() !== iwpLoginResponse::PASS_RECOVER_MAIL_OK) {
				$message = __("Tried to email to reset your password, but there was some problem.", 'iwp-text-domain');
				$message .= " " . __("<a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>Contact us</a> to solve the problem", 'iwp-text-domain');
				return json_encode(array(
					'status' => 0,
					'message' => $message
				));
			}

			return json_encode(array(
				'status' => 1,
				'message' => __("An email has been sent to you to reset your password",'iwp-text-domain')
			));
		}

		/**
		 * Ajax verificate 2FA
		 */
		public static function iwpSubmit2Fa() {
			require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
			require_once IWP_ADMIN_PATH . 'models/iwpOnBoardingModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpLoginResponse.php';

			$onBoardingModel = new iwpOnBoardingModel();
			$onBoardingModel->setToken(trim(iwpAdminUtils::getPOSTParam('2Fa_token', '')));

			$validate2FaResponse = $onBoardingModel->validate2Fa();
			switch ($validate2FaResponse->getInternalCode()) {
				case iwpValidate2FaResponse::VALIDATE_2FA_OK:
					// Login correcto
					iwpPluginOptions::deleteReConfigStatus();
					iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_PLUGIN_LOGIN);
					$ret = array(
						'status' => 1,
						'message' => ''
					);
					break;
				case iwpValidate2FaResponse::VALIDATE_2FA_KO:
					// Mensaje de "credenciales incorrectas"
					$ret = array(
						'status' => 0,
						'message' => __("Invalid credentials",'iwp-text-domain')
					);
					break;
				case iwpValidate2FaResponse::VALIDATE_2FA_NO_PERMISSIONS:
					// Mensaje de "no tiene permisos"
					$ret = array(
						'status' => 0,
						'message' => __("You don't have enough permissions to log in. Talk to your administrator",'iwp-text-domain')
					);
					break;
				case iwpApiManagerResponse::ERROR_TRY_CATCH:
				default:
					// El error try/catch como cualquier otra devolución, los trataremos igual
					$ret = array(
						'status' => 0,
						'message' => __("Unknown error. Please try again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>",'iwp-text-domain')
					);
			}
			return json_encode($ret);
		}

		/**
		 * Ajax refresh 2FA
		 */
		public static function iwpRefresh2Fa() {
			require_once IWP_ADMIN_PATH . 'models/iwpOnBoardingModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpRefresh2FaResponse.php';

			$iwpOnBoardingModel = new iwpOnBoardingModel();
			$refresh2FaResponse = $iwpOnBoardingModel->refresh2Fa();
			switch ($refresh2FaResponse->getInternalCode()) {
				case iwpRefresh2FaResponse::REFRESH_OK:
					// Refresh correcto
					$ret = array(
						'status' => 1,
						'message' => __("A new email with a new verification code has been sent to you", 'iwp-text-domain')
					);
					break;
				case iwpRefresh2FaResponse::REFRESH_KO:
					// Error en el Refresh
				default:
					// El error try/catch como cualquier otra devolución, los trataremos igual
					$ret = array(
						'status' => 0,
						'message' => __("Unknown error. Please close the window and try again, and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us< /a>", 'iwp-text-domain')
					);
			}
			return json_encode($ret);
		}

		/**
		 * Ajax get applications
		 */
		public static function iwpGetApplications() {
			require_once IWP_ADMIN_PATH . 'models/iwpApplicationsModel.php';
			require_once IWP_ADMIN_PATH . 'models/iwpApplicationModel.php';
			require_once IWP_ADMIN_PATH . 'responses/iwpApplicationsResponse.php';

			$applicationsModel = new iwpApplicationsModel();
			$applicationsResponse = $applicationsModel->consoleGetApplications();
			if ($applicationsResponse->getInternalCode() === iwpApplicationsResponse::GET_APPLICATIONS_EMPTY) {
				// Al no tener app alguna, creamos una y sobreescribimos la respuesta anterior
				$applicationsResponse = $applicationsModel->consoleCreateApplication();
			}

			return self::processApplicationsModelResponse($applicationsModel, $applicationsResponse);
		}

		/**
		 * Ajax finish login
		 */
		public static function iwpFinishLogin() {
			$applicationId = trim(iwpAdminUtils::getPOSTParam('applicationId', ''));
			$applicationPkey = trim(iwpAdminUtils::getPOSTParam('applicationPkey', ''));
			$applicationName = trim(iwpAdminUtils::getPOSTParam('applicationName', ''));
			$activateWebPush = trim(iwpAdminUtils::getPOSTParam('activateWebPush', '0'));

			if (empty($applicationId) || empty($applicationPkey) || empty($applicationName)) {
				// Estos campos se deberían rellenar automáticamente. Si alguno está vacío, es un proceso anormal
				$ret = array(
					'status' => 0,
					'message' => __("The selected project is not correct. Please reload the page and try again. If the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>", 'iwp-text-domain')
				);
				return json_encode($ret);
			}

			iwpPluginOptions::getOldValuesForReconnection(); // Intentamos recuperar los datos de la sesión anterior

			update_option(iwpPluginOptions::APPLICATION_ID, $applicationId);
			update_option(iwpPluginOptions::APP_KEY, $applicationPkey);
			update_option(iwpPluginOptions::APPLICATION_NAME, $applicationName);
			iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MICRO_PLUGIN_COMENZAR);

			if (!empty($activateWebPush)) {
				update_option(iwpPluginOptions::WEB_PUSH_STATUS, 1);
				iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_WP_ACTIVAR);
			}

			$ret = array(
				'status' => 1,
				'message' => ''
			);
			return json_encode($ret);
		}

		/* PRIVATE STATIC FUNCTIONS */

		/**
		 * Se validan los campos de registro y devuelve un array con información
		 * Si algo no es correcto, se devolverá 'status' a 0, el mensaje del error y los campos que se
		 *      deben marcar en rojo en el formulario.
		 * Si es correcto, se devuelve 'status' a 1
		 */
		private static function validateSignUp($data) {
			if (empty($data['userNewEmail']) || empty($data['userNewPassword'])
			    || empty($data['userNewPasswordConfirm']) || empty($data['confirmTermsCheckbox']))
			{
				// Alguno de los campos obligatorios está vacío
				$fields = array(
					'userNewEmail'           => empty($data['userNewEmail']),
					'userNewPassword'        => empty($data['userNewPassword']),
					'userNewPasswordConfirm' => empty($data['userNewPasswordConfirm']),
					'confirmTermsCheckbox'   => empty($data['confirmTermsCheckbox']),
				);
				return array(
					'status' => 2,
					'fields' => $fields,
					'message' => __('Fill in all the mandatory fields', 'iwp-text-domain')
				);
			}

			if (!filter_var($data['userNewEmail'], FILTER_VALIDATE_EMAIL)) {
				// El email no está escrito correctamente
				return array(
					'status' => 2,
					'fields' => array('userNewEmail' => true),
					'message' => __("Invalid email. Type the email correctly. Please try again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>", 'iwp-text-domain')
				);
			}

			$passLength = strlen($data['userNewPassword']);
			$containsALetter  = preg_match('/[a-zA-Z]/',    $data['userNewPassword']); // Contiene una letra
			$containsADigit   = preg_match('/\d/',          $data['userNewPassword']); // Contiene un número
			$containsGoodLength = ((4 <= $passLength) && ($passLength <= 32)); // Largura entre 4 y 32 caracteres
			if (!($containsALetter && $containsADigit && $containsGoodLength)) {
				// Alguna de las restricciones para la contraseña, no se cumple
				$fields = array(
					'userNewPassword'        => true,
					'userNewPasswordConfirm' => true,
				);
				return array(
					'status' => 2,
					'fields' => $fields,
					'message' => __('The password must be 8 characters or more in length (max: 32 characters) and contain at least one letter and one number', 'iwp-text-domain')
				);
			}

			if ($data['userNewPassword'] !== $data['userNewPasswordConfirm']) {
				// La contraseña y su confirmación no son iguales
				return array(
					'status' => 2,
					'fields' => array('userNewPasswordConfirm' => true),
					'message' => __('The password and its confirmation are not the same', 'iwp-text-domain')
				);
			}
			// Validaciones correctas
			return array('status' => 1);
		}

		/**
		 * Partiendo del modelo de las aplicaciones, obtenemos la lista y creamos los elementos HTML necesarios
		 */
		private static function iwpCreateOptionList($model) {
			$applicationList = $model->getApplications();
			$options = '';
			$selected = false;
			foreach ($applicationList as $app) {
				$selectedAttr = '';
				if (!$selected) {
					$selectedAttr = ' selected '; // Preseleccionamos la primera opción
					$selected = true;
				}
				$options .= "<option value='{$app->getId()}' data-pkey='{$app->getPublicKey()}' data-pkname='{$app->getName()}' $selectedAttr>{$app->getName()}</option>";
			}
			return $options;
		}

		/**
		 * Procesamos la respuesta al obtener las apps o crear una nueva
		 * Si la respuesta es OK, creamos el HTML de las opciones
		 */
		private static function processApplicationsModelResponse($model, $response) {
			switch ($response->getInternalCode()) {
				case iwpApplicationsResponse::CREATE_APPLICATIONS_OK:
					// Si hemos creado un nuevo proyecto, enviamos el evento y continuamos porque se envía la misma
					//      respuesta que cuando se obtienen los proyectos
					iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_PLUGIN_PROJECT_CREATE);
				case iwpApplicationsResponse::GET_APPLICATIONS_OK:
					$ret = array(
						'status' => 1,
						'message' => "",
						'totalOptions' => count($model->getApplications()),
						'options' => self::iwpCreateOptionList($model),
					);
					break;
				case iwpApplicationsResponse::GET_APPLICATIONS_KO:
				case iwpApplicationsResponse::CREATE_APPLICATIONS_KO:
					$ret = array(
						'status' => 0,
						'message' => __("Error getting your projects. Please try again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>",'iwp-text-domain'),
						'options' => '',
					);
					break;
				default:
					$ret = array(
						'status' => 0,
						'message' => __("Unknown error. Please try again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>",'iwp-text-domain'),
						'options' => '',
					);
			}
			return json_encode($ret);
		}
	}