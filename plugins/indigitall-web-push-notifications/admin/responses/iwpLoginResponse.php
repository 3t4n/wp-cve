<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpApiManagerResponse.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'admin/models/iwpOnBoardingModel.php';

	class iwpLoginResponse extends iwpApiManagerResponse {

		const LOGIN_OK = 0;
		const LOGIN_KO = 1;
		const LOGIN_2FA = 2;
		const LOGIN_NO_PERMISSIONS = 3;

		const LOGIN_KO_AS_LOCKED_INACTIVITY = 'AS_LOCKED_INACTIVITY';
		const LOGIN_KO_AS_LOCKED_EXPIRED_PASSWORD = 'AS_LOCKED_EXPIRED_PASSWORD';
		const LOGIN_KO_AS_LOCKED_TOO_MANY_LOGIN_ATTEMPTS = 'AS_LOCKED_TOO_MANY_LOGIN_ATTEMPTS';
		const LOGIN_KO_AS_INVALID_CREDENTIALS = 'AS_INVALID_CREDENTIALS';
		const LOGIN_KO_AS_USER_DISABLED = 'AS_USER_DISABLED';
		const LOGIN_KO_AS_IP_WHITELIST = 'AS_IP_WHITELIST';
		const LOGIN_KO_AS_FORBIDDEN = 'AS_FORBIDDEN';

		const PASS_RECOVER_MAIL_OK = 100;
		const PASS_RECOVER_MAIL_KO = 101;

		public function __construct($internalCode, $data = '', $message = '') {
			parent::__construct($internalCode, $data, $message);
		}

		/**
		 * @param iwpOnBoardingModel $onBoardingModel
		 *
		 * @return array
		 */
		final public function getLoginResponseResult($onBoardingModel) {
			$status = 0;
			$message = '';
			switch ($this->getInternalCode()) {
				case self::LOGIN_OK:
					// Login correcto
					iwpPluginOptions::deleteReConfigStatus();
					iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_PLUGIN_LOGIN);
					$status = 1;
					break;
				case self::LOGIN_KO:
					// Mensaje de error según el código de error recibido en la respuesta
					$message = $this->getKoMessage();
					if ($this->getData() === self::LOGIN_KO_AS_LOCKED_EXPIRED_PASSWORD) {
						// Si la contraseña está caducada, se envía un email para su recuperación
						$emailRecoverResponse = $onBoardingModel->recoverPasswordEmail();
						if ($emailRecoverResponse->getInternalCode() !== self::PASS_RECOVER_MAIL_OK) {
							// En el caso de que el email no se haya enviado bien, se mostrará un mensaje diferente.
							$message = __("Password expired. Tried to email to reset it, but there was some problem.", 'iwp-text-domain');
							$message .= " " . __("<a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>Contact us</a> to solve the problem", 'iwp-text-domain');
						}
					}
					break;
				case self::LOGIN_2FA:
					// Cargamos la modal para el 2FA
					$status = 2;
					break;
				case self::LOGIN_NO_PERMISSIONS:
					// Mensaje de "no tiene permisos"
					$message = __("You don't have enough permissions to log in. Talk to your administrator",'iwp-text-domain');
					break;
				case iwpApiManagerResponse::ERROR_TRY_CATCH:
				default:
					// El error try/catch como cualquier otra devolución, los trataremos igual
					$message = __("Unknown error. Please try again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>",'iwp-text-domain');
			}

			return array(
				'status' => $status,
				'message' => $message
			);
		}

		/**
		 * @return string
		 */
		final public function getKoMessage() {
			$contactLink = __("<a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>Contact us</a> to solve the problem", 'iwp-text-domain');
			switch ($this->getData()) {
				case self::LOGIN_KO_AS_LOCKED_INACTIVITY:
					$message = __("User blocked due to inactivity.", 'iwp-text-domain');
					$message .= " $contactLink";
					break;
				case self::LOGIN_KO_AS_LOCKED_EXPIRED_PASSWORD:
					$message = __("Password expired. An email has been sent to you to reset it.", 'iwp-text-domain');
					break;
				case self::LOGIN_KO_AS_LOCKED_TOO_MANY_LOGIN_ATTEMPTS:
					$message = __("User blocked due to too many login attempts.", 'iwp-text-domain');
					$message .= " $contactLink";
					break;
				case self::LOGIN_KO_AS_INVALID_CREDENTIALS:
					$message = __("Invalid credentials",'iwp-text-domain');
					break;
				case self::LOGIN_KO_AS_USER_DISABLED:
					$message = __("User is disabled.", 'iwp-text-domain');
					$message .= " $contactLink";
					break;
				case self::LOGIN_KO_AS_IP_WHITELIST:
					$message = __("User IP blocked.", 'iwp-text-domain');
					$message .= " $contactLink";
					break;
				case self::LOGIN_KO_AS_FORBIDDEN:
					$message = __("Forbidden access.", 'iwp-text-domain');
					$message .= " $contactLink";
					break;
				default:
					$message = __("Unknown error. Please try again and if the error persists, <a href='https://iurny.com/en/contact-us/#contact-form' target='_blank'>contact us</a>",'iwp-text-domain');
			}
			return $message;
		}
	}