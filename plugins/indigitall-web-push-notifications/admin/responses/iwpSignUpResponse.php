<?php
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpApiManagerResponse.php';

	class iwpSignUpResponse extends iwpApiManagerResponse {

		const SIGNUP_OK = 0;
		const SIGNUP_PASSWORD_VALIDATE_ERROR = 1;
		const SIGNUP_MANDATORY_FIELDS_ERROR = 2;
		const SIGNUP_ACCOUNT_EXISTS = 3;
		const SIGNUP_EMAIL_ERROR = 4;
		const SIGNUP_UNKNOWN = 5;

		public function __construct($internalCode = null, $data = '', $message = '') {
			if (is_null($internalCode)) {
				$dataObj = json_decode($data, false);
				if ($dataObj->status === 0) {
					$internalCode = self::processErrorMessages($dataObj->message);
				} else {
					// Usuario creado correctamente
					$internalCode = self::SIGNUP_OK;
				}
			}
			parent::__construct($internalCode, '', $message);
		}

		/**
		 * Según el mensaje de error recibido, las agrupamos para dar respuestas coherentes
		 */
		private static function processErrorMessages($type) {
			if (is_array($type)) {
				$firstError = array_shift($type);
			} elseif (is_string($type)) {
				$firstError = $type;
			} else {
				return self::SIGNUP_UNKNOWN;
			}

			switch ($firstError) {
				case 'pass':
					return self::SIGNUP_PASSWORD_VALIDATE_ERROR;
				case 'mandatory-fields':
					return self::SIGNUP_MANDATORY_FIELDS_ERROR;
				case 'email':
					return self::SIGNUP_EMAIL_ERROR;
				case 'wrongPassword':
				case 'userInactivo':
				case 'tienePlanIgual':
				case 'planUsuarioEsInferior':
				case 'planUsuarioEsSuperior':
					return self::SIGNUP_ACCOUNT_EXISTS;
				case 'idPais':
				case 'unknown':
				default:
					return self::SIGNUP_UNKNOWN;
			}
		}
	}