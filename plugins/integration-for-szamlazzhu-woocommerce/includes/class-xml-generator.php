<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Szamlazz_Xml_Generator', false ) ) :

	class WC_Szamlazz_Xml_Generator {

		private $agent_body = array();
		private $agent_header = array();

		//Init notices
		public function generate($xml, $orderId, $field) {

			//Log request in debug mode
			WC_Szamlazz()->log_debug_messages($xml, $field.'-'.$orderId);

			//Temporarily save XML
			$UploadDir = wp_upload_dir();
			$UploadURL = $UploadDir['basedir'];
			$location = realpath($UploadURL . "/wc_szamlazz/");
			$xmlfile = $location.'/'.$orderId.'.xml';
			$test = file_put_contents($xmlfile, $xml);

			$cookie_meta_name = '_wc_szamlazz_cookie_name';
			$xml_file = simplexml_load_string($xml);
			if($xml_file->beallitasok && $xml_file->beallitasok->felhasznalo) {
				$username = sanitize_title($xml_file->beallitasok->felhasznalo);
				$cookie_meta_name = '_wc_szamlazz_cookie_name_'.$username;
			}

			if($xml_file->beallitasok && $xml_file->beallitasok->szamlaagentkulcs) {
				$agentkey = sanitize_title($xml_file->beallitasok->szamlaagentkulcs);
				$cookie_meta_name = '_wc_szamlazz_cookie_name_'.substr($agentkey, 0, 5);
			}

			//Generate cookie
			if(get_option($cookie_meta_name)) {
				$cookie_file_random_name = get_option($cookie_meta_name);
			} else {
				$cookie_file_random_name = substr(md5(rand()),5);
				update_option($cookie_meta_name,$cookie_file_random_name);
			}
			$cookie_file = $location.'/szamlazz_cookie_'.$cookie_file_random_name.'.txt';

			//Agent URL
			$agent_url = 'https://www.szamlazz.hu/szamla/';

			//Generate Cookie if not already exists
			if (!file_exists($cookie_file)) {
				file_put_contents($cookie_file, '');
			}

			// a CURL inicializálása
			$ch = curl_init($agent_url);

			// A curl hívás esetén tanúsítványhibát kaphatunk az SSL tanúsítvány valódiságától
			// függetlenül, ez az alábbi CURL paraméter állítással kiküszöbölhető,
			// ilyenkor nincs külön SSL ellenőrzés:
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			// POST-ban küldjük az adatokat
			curl_setopt($ch, CURLOPT_POST, true);

			// Kérjük a HTTP headert a válaszba, fontos információk vannak benne
			curl_setopt($ch, CURLOPT_HEADER, true);

			// változóban tároljuk a válasz tartalmát, nem írjuk a kimenetbe
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Beállítjuk, hol van az XML, amiből számlát szeretnénk csinálni (= file upload)
			// az xmlfile-t itt fullpath-al kell megadni
			if (!class_exists('CurlFile')) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, array($field=>'@' . $xmlfile));
			} else {
				curl_setopt($ch, CURLOPT_POSTFIELDS, array($field=>new CurlFile($xmlfile)));
			}

			// 30 másodpercig tartjuk fenn a kapcsolatot (ha valami bökkenő volna)
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);

			// Itt állítjuk be, hogy az érkező cookie a $cookie_file-ba kerüljön mentésre
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);

			// Ha van már cookie file-unk, és van is benne valami, elküldjük a Számlázz.hu-nak
			if (file_exists($cookie_file) && filesize($cookie_file) > 0) {
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
			}

			// elküldjük a kérést a Számlázz.hu felé, és eltároljuk a választ
			$agent_response = curl_exec($ch);

			// kiolvassuk a curl-ból volt-e hiba
			$http_error = curl_error($ch);

			// ezekben a változókban tároljuk a szétbontott választ
			$agent_header = '';
			$agent_body = '';
			$agent_http_code = '';

			// lekérjük a válasz HTTP_CODE-ját, ami ha 200, akkor a http kommunikáció rendben volt
			// ettől még egyáltalán nem biztos, hogy a számla elkészült
			$agent_http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);

			// a válasz egy byte kupac, ebből az első "header_size" darab byte lesz a header
			$header_size = curl_getinfo($ch,CURLINFO_HEADER_SIZE);

			// a header tárolása, ebben lesznek majd a számlaszám, bruttó nettó összegek, errorcode, stb.
			$agent_header = substr($agent_response, 0, $header_size);

			// a body tárolása, ez lesz a pdf, vagy szöveges üzenet
			$agent_body = substr( $agent_response, $header_size );

			// a curl már nem kell, lezárjuk
			curl_close($ch);

			// a header soronként tartalmazza az információkat, egy tömbbe teszük a külön sorokat
			$header_array = explode("\n", $agent_header);

			// Save data locally
			$this->agent_header = $header_array;
			$this->agent_body = $agent_body;

			// ezt majd true-ra állítjuk ha volt hiba
			$volt_hiba = false;

			// ebben lesznek a hiba információk, plusz a bodyban
			$agent_error = '';
			$agent_error_code = '';

			// menjünk végig a header sorokon, ami "szlahu"-val kezdődik az érdekes nekünk és írjuk ki
			foreach ($header_array as $val) {
				if (substr($val, 0, strlen('szlahu')) === 'szlahu') {
					// megvizsgáljuk, hogy volt-e hiba
					if (substr($val, 0, strlen('szlahu_error:')) === 'szlahu_error:') {
						// sajnos volt
						$volt_hiba = true;
						$agent_error = substr($val, strlen('szlahu_error:'));
					}
					if (substr($val, 0, strlen('szlahu_error_code:')) === 'szlahu_error_code:') {
						// sajnos volt
						$volt_hiba = true;
						$agent_error_code = substr($val, strlen('szlahu_error_code:'));
					}
				}
			}

			// ha volt http hiba dobunk egy kivételt
			$response = array();
			$response['header_array'] = $header_array;
			$response['error'] = false;
			$response['messages'] = array();
			$response['agent_body'] = $agent_body;
			if ( $http_error != "" ) {
				$response['error'] = true;
				$response['http_error'] = $http_error;
				$response['messages'][] = sprintf(__('Http error: %s', 'wc-szamlazz'), $http_error);

				//Log error messages
				WC_Szamlazz()->log_error_messages($response, $field.'-'.$orderId);

				return $response;
			}

			//Delete the XML
			unlink($xmlfile);

			if ($volt_hiba) {
				$response['error'] = true;
				$response['agent_error'] = $agent_error;
				$response['agent_error_code'] = $agent_error_code;

				// ha a számla nem készült el kiírjuk amit lehet
				$response['messages'][] = sprintf(__('Agent error code: %s', 'wc-szamlazz'), $agent_error_code);
				$response['messages'][] = sprintf(__('Agent error message: %s', 'wc-szamlazz'), urldecode($agent_error));
				//$response['messages'][] = 'Agent válasz: '.urldecode($agent_body);

				//Log error messages
				WC_Szamlazz()->log_error_messages($response, $field.'-'.$orderId);

				// dobunk egy kivételt
				return $response;
			}

			return $response;
		}

		public function save_pdf_file($type, $orderId, $content = false, $invoice_name = false) {
			$pdf_file_path = WC_Szamlazz()->get_pdf_file_path($type, $orderId, $invoice_name);

			//If folder doesn't exists, create it with an empty index.html file
			$file = array(
				'base' 		=> $pdf_file_path['file_dir'],
				'file' 		=> 'index.html',
				'content' 	=> ''
			);

			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}

			//Save the actual PDF file
			if($content) {
				file_put_contents($pdf_file_path['path'], $content);
			} else {
				file_put_contents($pdf_file_path['path'], $this->agent_body);
			}

			return $pdf_file_path['name'];
		}

		public function get_invoice_id($headers) {
			$szlahu_szamlaszam = '';
			foreach ($headers as $val) {
				if (substr($val, 0, strlen('szlahu_szamlaszam')) === 'szlahu_szamlaszam') {
					$szlahu_szamlaszam = substr($val, strlen('szlahu_szamlaszam:'));
					break;
				}
			}
			return preg_replace("/\s+/", "", $szlahu_szamlaszam); //remove whitespace characters, sometimes there is one for some reason
		}

	}

endif;

if ( ! class_exists( 'WCSzamlazzSimpleXMLElement', false ) ) :
	class WCSzamlazzSimpleXMLElement extends SimpleXMLElement {
		public function appendXML($append) {
			if ($append) {
				// Create new DOMElements from the two SimpleXMLElements
				$domdict = dom_import_simplexml($this);
				$domcat = dom_import_simplexml($append);

				// Import the <cat> into the dictionary document
				$domcat = $domdict->ownerDocument->importNode($domcat, TRUE);

				// Append the <cat> to <c> in the dictionary
				$domdict->appendChild($domcat);
			}
		}
	}
endif;
