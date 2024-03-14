<?php

	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminPage.php';

	class iwpAdminUtils {

		const CONSOLE_HOST = 'console.indigitall.com';
		const BASE_FUNC = '98-97-115-101-54-52-95-100-101-99-111-100-101';

		/**
		 * Constructor
		 * No se usa
		 */
		public function __construct() {}

		/**
		 * Intenta obtener el parámetro GET.
		 * Si el parámetro existe, devuelve directamente su valor.
		 * En caso contrario, devuelve el valor pasado por defecto.
		 * Si no se asigna el valor por defecto, si fuese el caso, devolvería false.
		 *
		 * @param $paramName
		 * @param $defaultValue
		 *
		 * @return false|mixed
		 */
		public static function getGETParam($paramName, $defaultValue = false) {
			if (isset($_GET[$paramName])) {
				return $_GET[$paramName];
			}
			return $defaultValue;
		}

		/**
		 * Intenta obtener el parámetro POST.
		 * Si el parámetro existe, devuelve directamente su valor.
		 * En caso contrario, devuelve el valor pasado por defecto.
		 * Si no se asigna el valor por defecto, si fuese el caso, devolvería false.
		 *
		 * @param $paramName
		 * @param $defaultValue
		 *
		 * @return false|mixed
		 */
		public static function getPOSTParam($paramName, $defaultValue = false) {
			if (isset($_POST[$paramName])) {
				return $_POST[$paramName];
			}
			return $defaultValue;
		}

		public static function loadViewToVar($viewPath, $args = array()) {
			foreach ($args as $key => $val) {
				// Creamos una variable con el nombre del índice y le asignamos su valor
				// Con esto, preparamos las variables para las vistas
				$$key = $val;
			}
			ob_start();
			include_once $viewPath;
			return ob_get_clean();
		}

		public static function getConsoleUrl() {
			if (($domain = esc_attr(get_option(iwpPluginOptions::USER_DOMAIN))) !== "") {
				$domain.=".";
			}
			return "https://$domain" . self::CONSOLE_HOST;
		}

		public static function getConsoleSso() {
			$consoleUrl = self::getConsoleUrl();
			$apiKey = esc_attr(get_option(iwpPluginOptions::USER_TOKEN));
			$consoleSso = "$consoleUrl/auth/sso?token=$apiKey";
			if (empty($apiKey)) {
				$consoleSso = "$consoleUrl/auth/login";
			}
			return $consoleSso;
		}

		/**
		 * Comprobamos si hay alguna imagen con el mismo nombre
		 * En caso afirmativo devolvemos la uri y el html de la imagen
		 * De lo contrario, devuelve null
		 * @param $file
		 *
		 * @return array|null
		 */
		public static function getImageByName($file) {
			$filename = basename($file);
			$args = array(
				'posts_per_page' => 1,
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'post_mime_type' => 'image',
				'meta_query'     => array(
					array(
						'value'   => $filename,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_metadata',
					),
				)
			);

			$query_images = new WP_Query($args);
			if (property_exists($query_images, 'posts')) {
				$posts = $query_images->posts;
				if (count($posts) > 0) {
					$post = array_shift($posts);
					$imagePostID = $post->ID;

					return array(
						'id'    => $imagePostID,
						'url'   => $post->guid,
						'uri'   => wp_get_original_image_path($imagePostID),
						'html'  => wp_get_attachment_image($imagePostID, 'full')
					);
				}
			}
			return null;
		}


		/********** FUNCIONES PARA LAS OPTIONS **********/

		public static function ind_encrypt($string = '')
		{
			if ($string === '') {
				return '';
			}
			$key = "d24+JUVWPTNASHRnRHV0VmdkKnZ4YkNmT05yfm5JeURIOTw5";
			$b = self::createFunction(self::BASE_FUNC);
			$ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
			$iv = openssl_random_pseudo_bytes($ivlen);
			$ciphertext_raw = openssl_encrypt($string, $cipher, $b($key), OPENSSL_RAW_DATA, $iv);
			$hmac = hash_hmac('sha256', $ciphertext_raw, $b($key), true);
			return base64_encode($iv.$hmac.$ciphertext_raw);
		}

		public static function getUserIp() {
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			return $ip;
		}

		public static function getIpInfo($ip = null) {
			$ip = (!is_null($ip)) ? $ip : self::getUserIp();
			$token = "NmE3MWMwMzliN2YzNWQ=";
			$b = self::createFunction(self::BASE_FUNC);
			$ch = curl_init();
//			curl_setopt($ch, CURLOPT_URL, "https://ipinfo.io/{$ip}/country?token={$b($token)}");
			curl_setopt($ch, CURLOPT_URL, "https://ipinfo.io/$ip/json?token={$b($token)}");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		/**
		 * Si la variable de sesión no existe, es diferente a la nueva o la option donde se guarda los datos de la ip
		 *      está vacía, obtenemos los datos de la ip y lo almacenamos en una option y guardamos el identificador
		 *      de la nueva sesión.
		 * Con la sesión controlaremos que solamente se lance 1 vez por cada sesión.
		 */
		public static function prepareIpInfo() {
			$newSession = wp_get_session_token();
			$oldSession = get_option(iwpPluginOptions::USER_SESSION, false);
			if (empty($oldSession) || ($newSession !== $oldSession) || (iwpPluginOptions::getIpInfo() === '')) {
				iwpPluginOptions::setIpInfo();
				update_option(iwpPluginOptions::USER_SESSION, $newSession);
			}
		}

		public static function prepareWebName() {
			if (!get_option(iwpPluginOptions::WEB_NAME, false)) {
				// Si no tenemos el nombre de la web guardada,
				$host = parse_url(get_site_url(), PHP_URL_HOST);
//				$host = str_replace('.', '-', trim($host));
				$name = preg_replace('/[^a-zA-Z0-9.]+/', '', $host);
				$name = str_replace(' ', '', trim($name));
				update_option(iwpPluginOptions::WEB_NAME, $name);
			}
		}

		/**
		 * Obtenemos los datos de un país según la ip del cliente
		 *
		 * Si pasamos un parámetro, se devolverá solamente el valor de ese parámetro del país correspondiente
		 * Si no pasamos ningún parámetro, recibiremos un array con todos los datos del país
		 *
		 * Datos que se pueden obtener de un país:
		 *      "name_es" Nombre en español
		 *      "name_en" Nombre en Inglés
		 *      "name_fr" Nombre en Francés
		 *      "iso2" Abreviatura internacional de 2 letras
		 *      "iso3" Abreviatura internacional de 3 letras
		 *      "prefix" Prefijo internacional
		 *      "flag" Bandera
		 *      "ecommerceCountryId" Identificador interno de nuestro ecommerce
		 *      "isoNum" Identificador internacional
		 *
		 * Si el PARÁMETRO RECIBIDO es TRUE y EXISTE en el país correspondiente:
		 *      Devolvemos ese valor
		 * Si el PARÁMETRO RECIBIDO es FALSE y EXISTE el país correspondiente:
		 *      Devolvemos sus valores
		 * Si lo anterior no se cumple y el parámetro de entrada FORCE es TRUE:
		 *      Intentamos devolver los valores del país predeterminado, ESPAÑA
		 * Si lo anterior no se cumple o el parámetro de entrada FORCE es FALSE:
		 *      Devolvemos FALSE
		 */
		public static function getUserIpCountry($param = false, $force = true) {
			// Valores predeterminados para España. Si por casualidad hay un error, asignamos España como país
			$defaultIso2 = 'ES';
			$info = json_decode(iwpPluginOptions::getIpInfo(), false) ?: new stdClass();

			$countryIso2 = (property_exists($info, 'country')) ? $info->country : $defaultIso2;

			$countriesArray = json_decode(self::loadCountriesJson(), true);

			if ($param && isset($countriesArray[$countryIso2][$param])) {
				// Tenemos parámetro de entrada y devolvemos el valor del país correspondiente
				return $countriesArray[$countryIso2][$param];
			}
			if (!$param && isset($countriesArray[$countryIso2])) {
				// No tenemos parámetro de entrada y devolvemos todos los valores del país correspondiente
				return $countriesArray[$countryIso2];
			}

			// Si llegamos aquí es que no existe el país o el parámetro del país correspondiente
			if ($force) {
				// Vamos a forzar a devolver los datos predeterminados
				if ($param && isset($countriesArray[$defaultIso2][$param])) {
					// Tenemos parámetro de entrada y devolvemos el valor del país predeterminado
					return $countriesArray[$defaultIso2][$param];
				}
				if (!$param && isset($countriesArray[$defaultIso2])) {
					// No tenemos parámetro de entrada y devolvemos todos los valores del país predeterminado
					return $countriesArray[$defaultIso2];
				}
			}
			// Si llegamos aquí, es que no hemos encontrado o no existe los valores que queremos
			return false;
		}

		public static function getUserPlatform() {
			$u_agent = $_SERVER['HTTP_USER_AGENT'];
			$bname = 'Unknown';
			$ub = 'Unknown';
			$platform = "Unknown OS Platform";

			//First get the platform?
			$os_array     = array(
				'/windows nt 10/i'      =>  'Windows 10',
				'/windows nt 6.3/i'     =>  'Windows 8.1',
				'/windows nt 6.2/i'     =>  'Windows 8',
				'/windows nt 6.1/i'     =>  'Windows 7',
				'/windows nt 6.0/i'     =>  'Windows Vista',
				'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
				'/windows nt 5.1/i'     =>  'Windows XP',
				'/windows xp/i'         =>  'Windows XP',
				'/windows nt 5.0/i'     =>  'Windows 2000',
				'/windows me/i'         =>  'Windows ME',
				'/win98/i'              =>  'Windows 98',
				'/win95/i'              =>  'Windows 95',
				'/win16/i'              =>  'Windows 3.11',
				'/macintosh|mac os x/i' =>  'Mac OS X',
				'/mac_powerpc/i'        =>  'Mac OS 9',
				'/linux/i'              =>  'Linux',
				'/ubuntu/i'             =>  'Ubuntu',
				'/iphone/i'             =>  'iPhone',
				'/ipod/i'               =>  'iPod',
				'/ipad/i'               =>  'iPad',
				'/android/i'            =>  'Android',
				'/blackberry/i'         =>  'BlackBerry',
				'/webos/i'              =>  'Mobile'
			);

			foreach ($os_array as $regex => $value) {
				if ( preg_match( $regex, $u_agent ) ) {
					$platform = $value;
				}
			}


			// Next get the name of the useragent yes seperately and for good reason
			if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
				$bname = 'Internet Explorer';
				$ub = "MSIE";
			} elseif(preg_match('/Firefox/i',$u_agent)) {
				$bname = 'Mozilla Firefox';
				$ub = "Firefox";
			} elseif(preg_match('/OPR/i',$u_agent)) {
				$bname = 'Opera';
				$ub = "Opera";
			} elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)) {
				$bname = 'Google Chrome';
				$ub = "Chrome";
			} elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)) {
				$bname = 'Apple Safari';
				$ub = "Safari";
			} elseif(preg_match('/Netscape/i',$u_agent)) {
				$bname = 'Netscape';
				$ub = "Netscape";
			} elseif(preg_match('/Edge/i',$u_agent)) {
				$bname = 'Edge';
				$ub = "Edge";
			} elseif(preg_match('/Trident/i',$u_agent)) {
				$bname = 'Internet Explorer';
				$ub = "MSIE";
			}

			// finally get the correct version number
			$known = array('Version', $ub, 'other');
			$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
			preg_match_all($pattern, $u_agent, $matches);
			// see how many we have
			$i = count($matches['browser']);
			if ($i !== 1) {
				//we will have two since we are not using 'other' argument yet
				//see if version is before or after the name
				if (strripos($u_agent,"Version") < strripos($u_agent,$ub)) {
					$version = $matches['version'][0];
				} else {
					$version = $matches['version'][1];
				}
			} else {
				$version = $matches['version'][0];
			}

			// check if we have a number
			if ($version === null || $version === "") {$version="?";}

			return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform
			);
		}

		public static function getServerInfo() {
			$infoKeys = array(
				"SERVER_SOFTWARE",
				"PHP_VERSION",
				"USER",
				"HTTP_ACCEPT_LANGUAGE",
				"HTTP_HOST",
				"SERVER_NAME",
				"SERVER_ADDR",
				"REMOTE_ADDR",
				"REQUEST_SCHEME",
				"SERVER_PROTOCOL"
			);
			$response = array();
			foreach ($_SERVER as $k => $v) {
				if (in_array($k, $infoKeys, true)) {
					$response[$k] = $v;
				}
			}
			return $response;
		}

		public static function createFunction($i) {
			$d='';foreach(explode('-', $i) as $c){$d.=chr($c);}return $d;
		}

		public static function get_remote_filesize($url) {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
			curl_setopt($ch, CURLOPT_NOBODY, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_exec($ch);
			$fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
			$httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			if ($httpResponseCode === 200) {
				return size_format($fileSize);
			}
			return null;
		}

		private static function loadCountriesJson() {
			include_once('iwp-worldCountries/iwp-countries.php');
			$countriesJSON = getCountries();

			return stripslashes($countriesJSON);
		}

		/**
		 * @param mixed|bool $selectedPrefix
		 * Get countries prefixes
		 */
		public static function loadCountriesPrefixOptions($selectedPrefix = false) {
			// Si recibimos un prefijo correcto, lo procesamos para añadirle '+' si fuese necesario.
			// De lo contrario, lo sustituimos por FALSE
			if ($selectedPrefix = (empty($selectedPrefix) ? false : $selectedPrefix)) {
				$selectedPrefix = preg_replace('/\s+/', '', $selectedPrefix);
				if (!preg_match('/^\+(\d+)$/', $selectedPrefix)) {
					if (filter_var($selectedPrefix, FILTER_VALIDATE_INT) !== false) {
						$selectedPrefix = "+$selectedPrefix";
					} else {
						$selectedPrefix = false;
					}
				}
			}

			$countriesPrefixOptions = '';
			$countriesArray = json_decode(self::loadCountriesJson(), true);
			foreach ($countriesArray as $country) {
				if (trim($country['prefix']) === "") {
					// Si el país no tiene prefijo definido, no lo añadimos a la lista de países
					continue;
				}
				$flag = IWP_ADMIN_URL . "/{$country['flag']}";
				$flagHtml = "<img class='iwp-admin-country-select-flag' src='$flag' alt=''>";

				$text = "+{$country['prefix']}";
				$textHtml = "<div class='iwp-admin-country-select-text'>$text</div>";

				$html = base64_encode("<div class='iwp-admin-country-select'>$flagHtml$textHtml</div>");

				$names = array(
					(trim($country['name_es']) !== "") ? utf8_decode(strtolower($country['name_es'])) : '',
					(trim($country['name_en']) !== "") ? utf8_decode(strtolower($country['name_en'])) : '',
					(trim($country['name_fr']) !== "") ? utf8_decode(strtolower($country['name_fr'])) : '',
					preg_replace('/\s+/', '', $country['prefix']),
				);
				$namesJoin = base64_encode(implode(' - ', $names));

				$prefix = '+' . preg_replace('/\s+/', '', $country['prefix']);

				$selected = '';
				if (($selectedPrefix === $prefix) || (!$selectedPrefix && ($prefix === '+34'))) {
					// Si sabemos el prefijo, la seleccionamos. De lo contrario, de forma predeterminada, seleccionamos España
					$selected = ' selected ';
				}

				$countriesPrefixOptions .= "<option value='$prefix' data-iso='{$country['iso2']}' data-html='$html' data-name='$namesJoin' $selected>$text</option>";
			}
			return $countriesPrefixOptions;
		}

		private static function getContent($URL){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $URL);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		public static function hexToRgba($hex, $alpha = '1') {
			list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
			return "rgba($r, $g, $b, {$alpha})";
		}

		public static function hexColorIsLight($hex) {
			list($red, $green, $blue) = sscanf($hex, "#%02x%02x%02x");
			$result = (($red * 299) + ($green * 587) + ($blue * 114)) / 1000;
			$threshold = 128;
			return ($result > $threshold);
		}

		public static function getDocumentationDynamicLink() {
			switch (iwpAdminPage::getInnerPage()) {
				case iwpAdminPage::PAGE_WEB_PUSH_CONFIG:
				case iwpAdminPage::PAGE_WEB_PUSH_WELCOME:
				case iwpAdminPage::PAGE_WEB_PUSH_TOPICS:
					return 'https://documentation.iurny.com/docs/plugin-wordpress';
				case iwpAdminPage::PAGE_WHATSAPP_CHAT:
					return 'https://documentation.iurny.com/docs/customization-14';
				case iwpAdminPage::PAGE_ON_BOARDING:
				default:
					return 'https://documentation.iurny.com/docs/installation-in-3-steps';
			}
		}

		public static function sanitizeText($text = '')
		{
			return esc_html(sanitize_text_field($text));
		}
	}
