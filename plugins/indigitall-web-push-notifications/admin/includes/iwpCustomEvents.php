<?php

	require_once IWP_PLUGIN_PATH . 'includes/iwpCurlManager.php';
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpApiManager.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';

	class iwpCustomEvents {
		// Eventos SIN autenticación
		const MACRO_PLUGIN_ACTIVAR              = 'macro_plugin_activar';
		const MACRO_PLUGIN_DESACTIVAR           = 'macro_plugin_desactivar';
		const MACRO_PLUGIN_DESINSTALAR          = 'macro_plugin_desinstalar';
		const MACRO_PLUGIN_USER_CREATE          = 'macro_plugin_user_create';
		const INFO_SYSTEM                       = 'info_system';

		// Eventos con autenticación
		const MACRO_PLUGIN_LOGIN                = 'macro_plugin_login';
		const MICRO_PLUGIN_SELECCIONA_SERVICIO  = 'micro_plugin_seleccionaservicio';
		const MICRO_PLUGIN_COMENZAR             = 'micro_plugin_comenzar';
		const MACRO_PLUGIN_DESCONECTAR          = 'macro_plugin_desconectar';
		const MACRO_WA_ACTIVAR                  = 'macro_wa_activar';
		const MACRO_WA_DESACTIVAR               = 'macro_wa_desactivar';
		const MACRO_WP_ACTIVAR                  = 'macro_wp_activar';
		const MACRO_WP_DESACTIVAR               = 'macro_wp_desactivar';
		const MACRO_WP_ACTUALIZAR               = 'macro_wp_actualizar';
		const MACRO_PLUGIN_PROJECT_CREATE       = 'macro_plugin_project_create';
		const MICRO_WP_LOCATION_ACTIVAR         = 'micro_wp_location_activar';
		const MICRO_WP_LOCATION_DESACTIVAR      = 'micro_wp_location_desactivar';
		const MICRO_WP_TOPICS_ACTIVAR           = 'micro_wp_audiencia_activar';
		const MICRO_WP_TOPICS_DESACTIVAR        = 'micro_wp_audiencia_desactivar';
		const MICRO_WP_TOPIC_CREATE             = 'micro_wp_audiencia_crear';
		const MICRO_WP_TOPIC_EDIT               = 'micro_wp_audiencia_editar';
		const MICRO_WP_TOPIC_DELETE             = 'micro_wp_audiencia_eliminar';
		const MICRO_WP_WELCOME_CREATE           = 'micro_wp_welcome_create';
		const MICRO_WP_WELCOME_UPDATE           = 'micro_wp_welcome_update';
		const MICRO_WP_WELCOME_AD_IMAGE         = 'micro_wp_welcome_add_image';
		const MICRO_WP_WELCOME_ACTIVAR          = 'micro_wp_welcome_activar';
		const MICRO_WP_WELCOME_DESACTIVAR       = 'micro_wp_welcome_desactivar';
		const MICRO_WIDGET_PUSH_SEND            = 'micro_widget_push_send';
		const MICRO_IR_CONSOLA                  = 'micro_ir_consola';
		const INFO_RETROACTIVE_CHANNELS         = 'info_retroactive';

		// Eventos para errores e información de acciones. SIN autenticación
		const DEBUG_ERROR_LEVEL_1 =  'log_debug_1'; // Errores try/catch
		const DEBUG_ERROR_LEVEL_2 =  'log_debug_2'; // Errores en las request con un statusCode diferente a 200 o 201
		const DEBUG_ERROR_LEVEL_3 =  'log_debug_3'; // Info de las request con un statusCode igual a 200 o 201
		const DEBUG_ERROR_LEVEL_4 =  'log_debug_4'; // Info debug en general, poco importante


		/**
		 * Usamos para enviar eventos a medida de medición del uso
		 * Devolvemos un booleano para saber si el envío ha ido bien o no, por si se quiere usar en algún punto.
		 * Pero de forma predeterminada, no puede interferir en el funcionamiento normal del plugin.
		 */
		public static function sendCustomEvent($event = '', $eventData = array(), $tryCatch = false) {
			if (empty($event)) {
				return false;
			}

			$uri = "https://wpdata.indigitall.com/events/wp";

			$accountId = (int)esc_attr(get_option(iwpPluginOptions::ACCOUNT_ID, ''));
			$userEmail = esc_attr(get_option(iwpPluginOptions::USER_LOGIN, ''));
			$wpEmail = "";
			$wpDomain = "";
			if (function_exists('wp_get_current_user')) {
				// No debería pasar que la función no exista, pero por si acaso
				$current_user = wp_get_current_user();
				$wpEmail = $current_user ? $current_user->data->user_email : '';
				$wpDomain = get_site_url();
			}

			$aTraceId = esc_attr(get_option(iwpPluginOptions::CUSTOM_EVENT_TRACE_ID, ''));
			if (empty($aTraceId)) {
				// Primera activación. A partir de ahí, siempre debería existir esta variable y con el mismo valor del inicio
				// Genera un ID único de 33 caracteres máximo
				$aTraceId = uniqid(random_int(1, 999999999), true);
				update_option(iwpPluginOptions::CUSTOM_EVENT_TRACE_ID, $aTraceId);
			}

			$cloud = esc_attr(get_option(iwpPluginOptions::USER_DOMAIN, ''));
			if (empty($cloud)) {
				$cloud = 'eu1';
			}

			$userPlan = get_option(iwpPluginOptions::USER_PLAN);
			if (empty($userPlan) && !empty($accountId)) {
				$userPlan = iwpApiManager::getAccount();
			}
			$body = array(
				"aTraceId"  => $aTraceId,
				'plugin'    => 'WordPress',
				'userPlan'  => (empty($userPlan) || ($userPlan === 'NULL') ? null : $userPlan),
				"accountId" => (empty($accountId) ? null : $accountId),
				"userEmail" => (empty($userEmail) ? null : $userEmail),
				"wpEmail"   => $wpEmail,
				"cloud"     => $cloud,
				"wpDomain"  => $wpDomain,
				"timestamp" => date('Y-m-d H:i:s'),
				"eventCode" => $event,
				"eventData" => json_encode($eventData, JSON_UNESCAPED_SLASHES)
			);

			try {
				$response = iwpCurlManager::sendRequest(iwpCurlManager::METHOD_POST, $uri, $body, iwpCurlManager::AUTH_OPENED, false);
				// Si existe código de respuesta y es 200, devolverá true. De lo contrario, devolverá false
				return (isset($response->responseCode) && $response->responseCode === 200);
			} catch (Exception $e) {
				if (!$tryCatch) {
					// Si el envío de un evento da error try/catch, solamente enviaremos el evento del error si no es
					//      en sí mismo el error de try-catch. Así evitaremos una redundancia cíclica.
					$body = ! empty( $response ) ? $response : null;
					self::sendTryCatchErrorEvent(__FUNCTION__, 'POST', $uri, $body, $e);
				}
				return false;
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
				'system_info' => array(
					'PLUGIN VERSION' => IWP_PLUGIN_VERSION,
					'WORDPRESS_VERSION' => IWP_WORDPRESS_VERSION
				),
				'userInfo' => iwpAdminUtils::getUserPlatform(),
				'serverInfo' => iwpAdminUtils::getServerInfo()
			);
			self::sendCustomEvent(self::DEBUG_ERROR_LEVEL_1, $eventData, true);
		}

		/**
		 * Recogemos información del usuario y si es diferente al almacenado, se envía un evento
		 */
		public static function sendSystemInfoEvent() {
			$currentEncodedInfo = get_option(iwpPluginOptions::SYSTEM_INFO, '');
			$newInfo = array(
				// User is enterprise
				'system_info' => array(
					'PLUGIN VERSION' => IWP_PLUGIN_VERSION,
					'WORDPRESS_VERSION' => IWP_WORDPRESS_VERSION,
					'PHP_VERSION' => PHP_VERSION,
				),

				'ip_info' => json_decode(iwpPluginOptions::getIpInfo(), true),
				'userInfo' => iwpAdminUtils::getUserPlatform(),
				'serverInfo' => iwpAdminUtils::getServerInfo()
			);
			$newEncodedInfo = base64_encode(json_encode($newInfo));
			if ($currentEncodedInfo !== $newEncodedInfo) {
				update_option(iwpPluginOptions::SYSTEM_INFO, $newEncodedInfo);
				self::sendCustomEvent(self::INFO_SYSTEM, $newInfo);
			}
		}

		/**
		 * Este código se lanzará siempre al inicio del plugin.
		 * Si el cliente tiene definido el aTraceId y tiene creada la opción 'iwp_retroactive_info', no se mandará nada.
		 * Si el cliente tiene definido el aTraceId, pero NO tiene creada la opción 'iwp_retroactive_info',
		 *      es que es un cliente 'anterior' a la actualización. Se enviará un evento con la info retroactiva
		 *      y se activará la opción.
		 * Si el cliente NO tiene definido aún el traceId, es nuevo cliente y no se enviará la info retroactiva.
		 */
		public static function sendRetroactiveChannelsInfo() {
			if (!get_option(iwpPluginOptions::RETROACTIVE_INFO)) {
				// Se envía la info
				$pluginStatus = get_option(iwpPluginOptions::APPLICATION_ID, '');
				$whatsAppChatStatus = get_option(iwpPluginOptions::WEB_PUSH_STATUS, '');
				$webPushStatus = get_option(iwpPluginOptions::WH_STATUS, '');
				$retroInfo = array(
					'pluginStatus'       => (!empty($pluginStatus)),
					'applicationId'      => $pluginStatus,
					'whatsAppChatStatus' => (!empty($whatsAppChatStatus)),
					'webPushStatus'      => (!empty($webPushStatus)),
				);

				if (self::sendCustomEvent(self::INFO_RETROACTIVE_CHANNELS, $retroInfo)) {
					update_option(iwpPluginOptions::RETROACTIVE_INFO, "1");
				}
			}
		}

//		/**
//		 * Intenta redimensionar una imagen y devolver su uri y su html
//		 * @param $imageRealPath
//		 *
//		 * @return array|null
//		 */
//		public static function cropCenterImage($imageRealPath) {
//			$dstWidth = 512;
//			$dstHeight = 256;
//
////		$info = pathinfo($imageRealPath);
////		$fileExistsRealPath = $info['dirname']."/".$info['filename']."-crop{$dstWidth}x{$dstHeight}".$info['extension'];
//
//			$dstAspectRatio = $dstWidth / $dstHeight;
//
//			$imageEditor = wp_get_image_editor($imageRealPath);
//			$current_size = $imageEditor->get_size();
//			$currentWidth = $current_size['width'];
//			$currentHeight = $current_size['height'];
//			$currentAspectRatio = $currentWidth / $currentHeight;
//
//			if (($dstHeight === $currentHeight) && ($dstWidth === $currentWidth)) {
//				// La imagen tiene las medidas exactas y no la retocaremos
//				return null;
//			}
//
//			if ($currentAspectRatio === $dstAspectRatio) {
//				$imageEditor->resize( $dstWidth, $dstHeight );
//			}
//			if (($dstHeight < $currentHeight) && ($dstWidth < $currentWidth)) {
//				// Al ser las 2 medidas superiores al del destino, directamente hacemos un resize a las medidas de destino.
//				$imageEditor->resize($dstWidth, $dstHeight, true); // Automáticamente hace crop-center
//			} else {
//				// Si alguno de las medidas es más pequeña que las de destino, no podemos hacer resize. Simplemente, haremos
//				// un crop de la imagen para mantener el aspectRatio de destino
//				if ($currentWidth > $currentHeight) {
//					// Con las fórmulas inferiores, conseguiremos redimensionar lo mínimo posible la imagen hasta llegar al aspectRatio del destino
//					if ($currentAspectRatio > $dstAspectRatio) {
//						// El aspectRatio de la imagen recibida al ser superior al del destino, redimensionamos usando como medida la altura
//						$newWidth = $currentHeight * $dstAspectRatio;
//						$imageEditor->resize($newWidth, $currentHeight, true); // Automáticamente hace crop-center
//					} else {
//						// El aspectRatio de la imagen recibida al ser inferior al del destino, redimensionamos usando como medida la anchura
//						// Si la anchura es superior a la de destino pero la altura
//						$newHeight = $currentWidth / $dstAspectRatio;
//						$imageEditor->resize($currentWidth, $newHeight, true); // Automáticamente hace crop-center
//					}
//				} else {
//					// La imagen a conseguir al ser horizontal y la imagen recibida vertical, siempre redimensionaremos la altura
//					$newHeight = $currentWidth / $dstAspectRatio;
//					$imageEditor->resize($currentWidth, $newHeight, true); // Automáticamente hace crop-center
//				}
//
//			}
//
//			$new_size = $imageEditor->get_size();
//			$new_width = $new_size['width'];
//			$new_height = $new_size['height'];
//			$fileSizeText = "crop{$new_width}x{$new_height}";
//
//			$newFilePath = $imageEditor->generate_filename($fileSizeText);
//			$fileExistsData = self::getImageByName(pathinfo($newFilePath, PATHINFO_FILENAME));
//			if (!is_null($fileExistsData)) {
//				return $fileExistsData;
//			}
//
//			$imageEditor->save($newFilePath);
//
//			$attachmentId = uploadFileToMedia($newFilePath);
//			$imageHtml = wp_get_attachment_image($attachmentId, 'full');
//			$imageUri = wp_get_original_image_path($attachmentId);
//			return array(
//				'uri' => $imageUri,
//				'html' => $imageHtml
//			);
//		}
//
	}