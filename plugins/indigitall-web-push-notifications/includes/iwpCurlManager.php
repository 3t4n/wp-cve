<?php

	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';

	class iwpCurlManager {

		const METHOD_GET = 'get';
		const METHOD_POST = 'post';
		const METHOD_PUT = 'put';
		const METHOD_DELETE = 'delete';

		const AUTH_OPENED = 0;
		const AUTH_BEARER = 1;
		const AUTH_BEARER_2FA = 2;
		const AUTH_SECRET_KEY = 3;
		const AUTH_OTHER = 3;

		const CONTENT_TYPE_NO_FILE = 'application/json';
		const CONTENT_TYPE_WITH_FILE = 'multipart/form-data';

		const IMAGE_TYPE_DEFAULT = 'application/octet-stream';

		/**
		 * Lanza peticiones al endpoint de Indigitall
		 *
		 * @param $method
		 * @param $url
		 * @param $data
		 * @param $authLevel
		 * @param bool $hasFiles // Determina si en el data viene algún archivo para enviarlo como 'multipart/form-data'
		 *
		 * @return mixed
		 */
		public static function sendRequest($method, $url, $data, $authLevel, $hasFiles = false) {
			$contentType = ($hasFiles) ? self::CONTENT_TYPE_WITH_FILE : self::CONTENT_TYPE_NO_FILE;

			if (empty($data)) {
				$newData = "{}";
			} else {
				// Si nos avisan de que hay una imagen, lo preparamos. De lo contrario codificamos los datos a json
				$newData = ($hasFiles) ? self::prepareImageToCurl($data) : json_encode($data);
			}

			$authorization = self::prepareAuthorization($authLevel);
			$header = array(
				"Authorization: $authorization ",
				"accept: application/json",
				"Content-Type: $contentType",
			);

			// Preparamos la llamada CURL
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

			if ($method !== self::METHOD_GET) {
				// Añadimos los datos solamente si el método es de tipo POST, PUT o DELETE. GET no envían cuerpo
				curl_setopt($ch,CURLOPT_POSTFIELDS, $newData);
			} else {
				// Asignamos null para que en el envío de evento se sepa que no se ha enviado dato alguno
				$newData = null;
			}
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

			// Llamada CURL
			$result = curl_exec($ch);

			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close ($ch);

			self::sendResponseEvent($method, $url, $newData, $header, $httpCode, $result);

			return json_decode( $result, false );
		}

		/**
		 * Recorremos todos los valores del array de entrada y buscamos si hay una imagen a tratar. Si encontramos
		 *      un índice llamado 'image', lo procesamos. El resto de valores, se devuelven tal y como han llegado.
		 * Esta función se llamará solamente si en la request hemos marcado que hay una imagen en los datos. Y
		 *      diferenciamos el índice 'image' del resto porque podría recibir una imagen y más datos adicionales.
		 * @param $data
		 *
		 * @return array
		 */
		private static function prepareImageToCurl($data) {
			if (!is_array($data)) {
				return array();
			}
			$ret = array();
			foreach ($data as $index => $value) {
				// Solamente preparamos una imagen si su índice en el array es 'image'. El resto de valores,
				//      se devuelven sin procesar.
				if ($index !== 'image') {
					$ret[$index] = $value;
					continue;
				}

				$filename = basename( $value );

				$mimeType = self::IMAGE_TYPE_DEFAULT;
				if ( $imageType = exif_imagetype( $value ) ) {
					$mimeType = image_type_to_mime_type( $imageType );
				}

				if ( function_exists( 'curl_file_create' ) ) {
					$ret[ $index ] = curl_file_create( $value, $mimeType, $filename );
				} else {
					$ret[ $index ] = "@{$value};type={$mimeType}";
				}
			}
			return $ret;
		}

		/**
		 * Enviamos un evento por cada petición a la consola
		 * Si el 'statusCode' no es 200 0 201, también añadimos información del usuario
		 *
		 * @param $method
		 * @param $url
		 * @param $body
		 * @param $headers
		 * @param $statusCode
		 * @param $response
		 *
		 * @return false|void
		 */
		private static function sendResponseEvent($method, $url, $body, $headers, $statusCode, $response) {
			if (self::avoidSendEvent()) {
				// Si estamos en redundancia cíclica, evitamos volver a enviar los eventos
				return false;
			}
			global $wp_version;

			// Ocultamos parte del Bearer o ServerKey del header
			if (is_array($headers)) {
				$newHeader = array();
				foreach ($headers as $h) {
					$nh = $h;
					if (strpos($h, 'Bearer') !== false) {
						$nh = substr($h, 0, 27) . '***';
					} else if (strpos($h, 'ServerKey') !== false) {
						$nh = substr($h, 0, 30) . '***';
					}
					$newHeader[] = $nh;
				}
			} else {
				$newHeader = $headers;
			}

			// Ocultamos parte del password
			$body = is_string($body) ? json_decode($body, true) : $body;
			if (is_array($body) && array_key_exists('password', $body)) {
				$body['password'] = substr($body['password'], 0, 5) . '***';
			}

			$eventData = array(
				'statusCode' => $statusCode,
				'payload' => array(
					'uri' => $url,
					'method' => strtoupper($method),
					'header' => $newHeader,
					'body' => $body
				),
				'response' => json_decode($response, true),
				'PHP_VERSION' => PHP_VERSION,
				'wordPress_VERSION' => $wp_version
			);

			switch ($statusCode) {
				case 200:
				case 201:
					$event = iwpCustomEvents::DEBUG_ERROR_LEVEL_3;
					break;
				default:
					$event = iwpCustomEvents::DEBUG_ERROR_LEVEL_2;
					$eventData['userInfo'] = iwpAdminUtils::getUserPlatform();
					$eventData['serverInfo'] = iwpAdminUtils::getServerInfo();
					$eventData['system_info'] = array(
						'PLUGIN VERSION' => IWP_PLUGIN_VERSION,
						'WORDPRESS_VERSION' => IWP_WORDPRESS_VERSION
					);
			}

			iwpCustomEvents::sendCustomEvent($event, $eventData);
		}

		private static function prepareAuthorization($authLevel) {
			switch ($authLevel) {
				case 0:
					return "";
				case 1:
					$token = get_option(iwpPluginOptions::USER_TOKEN, "");
					return "Bearer $token";
				case 2:
					$shortToken = get_option(iwpPluginOptions::SHORT2FA_TOKEN, "");
					return "Bearer $shortToken";
				case 3:
					$userSecretKey = get_option(iwpPluginOptions::USER_SECRET_KEY, "");
					return "ServerKey $userSecretKey";
				default:
					return '';
			}
		}

		/**
		 * Hay una redundancia cíclica entre las clases 'iwpCurlManager' y 'iwpCustomEvents'. Con esto miramos hacia atrás
		 * a ver si la clase que ha llamado a esta clase, es la de los eventos. En caso afirmativo, devolvemos TRUE
		 * para no llamar de nuevo a la clase de los eventos. De lo contrario devolvemos FALSE.
		 *
		 * @return bool
		 */
		private static function avoidSendEvent() {
			$currentClass = 'iwpCurlManager';
			$customEventsClass = 'iwpCustomEvents';
			foreach (debug_backtrace() as $history) {
				if (array_key_exists('class', $history)) {
					if ($history['class'] === $currentClass) {
						continue;
					}
					if ($history['class'] === $customEventsClass) {
						return true;
					}
				}
			}
			return false;
		}
	}
