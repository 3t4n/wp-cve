<?php

	class wpsg_itrecht {
		
		var	$SC = false;
		
		var $api_version = '1.0';
		
		public function __construct($SC = false) {
				
			$this->SC = $SC;
						
		} // public function __construct($SC = false)
		
		// Shortcodes
		public function sc_wpsg_itrecht_agb($atts) { return $this->get_option('wpsgitrecht_html_agb'); }
		public function sc_wpsg_itrecht_datenschutz($atts) { return $this->get_option('wpsgitrecht_html_datenschutz'); }
		public function sc_wpsg_itrecht_widerruf($atts) { return $this->get_option('wpsgitrecht_html_widerruf'); }
		public function sc_wpsg_itrecht_impressum($atts) { return $this->get_option('wpsgitrecht_html_impressum'); } 
		
		function dispatch() {
			
			if (isset($_REQUEST['wpsgitrecht_submit'])) {
					
				$this->saveForm();
					
			}
						
			echo $this->render('fullform.phtml');
			
		} // function dispatch()
		
		function admin_menu() {
		
			if (!is_plugin_active('wpshopgermany-free/wpshopgermany.php')) {
					
				add_submenu_page('options-general.php', 'wpShopGermany - IT-Recht Kanzlei München',  'IT-Recht Kanzlei München', 'administrator', 'wpshopgermany-itrecht-Admin', array($this, 'dispatch'));
		
			}
		
		} // function admin_menu()
		
		private function get_plugin_version($file) {

			if (!function_exists('get_plugins')) require_once(ABSPATH.'wp-admin/includes/plugin.php');
			
			$plugin_folder = get_plugins('/'.plugin_basename(dirname(__FILE__).'/../../'));
						
			return  @$plugin_folder[$file]['Version'];
			
		}
		
		function wp_loaded() {

            //include_once(ABSPATH.'wp-admin/includes/plugin.php');

			global $wpdb;

			if (wpsgitrecht_isSizedString($_REQUEST['wpsgitrecht_action'], 'genKey') && is_admin()) {
				
				die($this->getNewApiKey());
				
			}

			if (wpsgitrecht_isSizedString($_REQUEST['wpsgitrecht_action'], 'api')) {
				
				$strMail  = "Script Start: ".date('d.m.Y H:i:s', time())."\r\n";
				
				set_time_limit(300);
				
				$xml = simplexml_load_string(stripslashes(@$_REQUEST['xml']));
				
				$strMail .= "XML geladen: ".date('d.m.Y H:i:s', time())."\r\n";
				
				$returnCode = false; $errorText = false;
				
				if ($xml === false) { $returnCode = '12'; }
				else  {
				
					$request = array(
						'api_version' => strval($xml->api_version),
						'user_auth_token' => strval($xml->user_auth_token),
						'rechtstext_type' => strval($xml->rechtstext_type),
						'rechtstext_text' => strval($xml->rechtstext_text),
						'rechtstext_html' => strval($xml->rechtstext_html),
						'rechtstext_pdf_url' => strval($xml->rechtstext_pdf_url),
						'rechtstext_pdf_md5hash' => strval($xml->rechtstext_pdf_md5hash),
						'rechtstext_language' => strval($xml->rechtstext_language),
                        'rechtstext_pdf_filename_suggestion' => strval($xml->rechtstext_pdf_filename_suggestion),
						'action' => strval($xml->action)								
					);

                    $strMail .= "XML geladen: ".date('d.m.Y H:i:s', time())." API Token: ".$request['user_auth_token']."\r\n";

					$arPageTypes = $this->getPageTypes();
					 
                    // error 1 - Schnittstellen-Version (Versionsnummer api_version) ist unterschiedlich
                    if ($returnCode === false && $request['api_version'] != $this->api_version) { $returnCode = '1'; }
                    if ($returnCode === false && $request['user_auth_token'] != $this->getAPIKey()) { $returnCode = '3'; }
                    if ($returnCode === false && !in_array($request['rechtstext_type'], array_keys($arPageTypes))) { $returnCode = '4'; }
                    if ($returnCode === false && !wpsgitrecht_isSizedString($request['rechtstext_text']) || strlen($request['rechtstext_text']) < 50) { $returnCode = '5'; }
                    if ($returnCode === false && !wpsgitrecht_isSizedString($request['rechtstext_html']) || strlen($request['rechtstext_html']) < 50) { $returnCode = '6'; }
                    if ($returnCode === false && !wpsgitrecht_isSizedString($request['rechtstext_language'])) { $returnCode = '9'; $errorText = __('Kein Sprachcode übermittelt', 'wpsgit'); }
                    if ($returnCode === false && function_exists('icl_object_id')) {

                        $arLanguages = apply_filters('wpml_active_languages', null);

                        if (!array_key_exists($request['rechtstext_language'], $arLanguages)) {

                            $returnCode = '9'; $errorText = sprintf(__('Sprachcode %s ist in WPML nicht konfiguriert.', 'wpsgit'), $request['rechtstext_language']);

                        }

                    }
                    if ($returnCode === false && !function_exists('icl_object_id') && $request['rechtstext_language'] != 'de') { $returnCode = '9'; $errorText = __('WPML ist nicht installiert und ein Sprachcode != de wurde übermittelt.', 'wpsgit'); }
                    if ($returnCode === false && !in_array($request['action'], array('push'))) { $returnCode = '10'; }
                    if ($returnCode === false && $arPageTypes[$request['rechtstext_type']]['needPDF'] === true && trim($request['rechtstext_pdf_url']) === '') { $returnCode = '7'; }
                    //if ($returnCode === false && $arPageTypes[$request['rechtstext_type']]['needPDF'] === true && md5(file_get_contents($request['rechtstext_pdf_url'])) != $request['rechtstext_pdf_md5hash']) { $returnCode = '8'; }

                    if ($returnCode === false) {

                        $arPage = $this->getPageTypes();
                        $arPageConfig = $arPage[$request['rechtstext_type']];

                        // Hier drehe ich die zugeordnete Seite, damit die Übersetzung korrekt zugeordnet wird
                        if (function_exists('icl_object_id') && isset($request['rechtstext_language'])) {

                            $trans_page_id = intval(icl_object_id($arPageConfig['set'], 'page' , false, $request['rechtstext_language']));

                            if ($trans_page_id > 0) {

                                $strMail .= "Sprache: ".$arPageConfig['set'].'->'.$trans_page_id."\r\n";

                                $arPageConfig['set'] = $trans_page_id;

                            } else {

                                $returnCode = '9';
                                $errorText = __('WPML ist installiert aber die angefragte Seite ist nicht in die Zielsprache übersetzt.', 'wpsgit');

                            }

                        }

                        if ($returnCode === false) {

							// PDF aus BASE64
	                        if (strval($xml->rechtstext_pdf) !== '') {

		                        /**
		                         * Anpassung 08.03.2022 @daschmi (daschmi@daschmi.de)
		                         * API liefert das PDF jetzt als BASE64 Codierten String 
		                         */
								
								$wp_upload_dir = \wp_upload_dir();
								$post_id = $arPageConfig['set'];
								$lang = $request['rechtstext_language'];
								
								$path = $wp_upload_dir['basedir'].'/it-recht/'.$post_id.'/'.$lang.'/';
	                            if (!file_exists($path)) mkdir($path, 0766, true);
								
								$file_name = \sanitize_file_name(
                                    ((isset($request['rechtstext_pdf_filename_suggestion'])) ? $request['rechtstext_pdf_filename_suggestion'] : $arPageConfig['label'] . '.pdf')
                                );
								
								file_put_contents($path.$file_name, base64_decode(strval($xml->rechtstext_pdf)));
								
								if (file_exists($path.$file_name) )$this->update_option('wpsgitrecht_file_'.$lang.'_'.$request['rechtstext_type'], $file_name);
								
	                        } else {
								
	                            // PDF speichern
	                            if (strlen($request['rechtstext_pdf_url']) > 0) {
	
	                                require_once(ABSPATH . '/wp-includes/pluggable.php');
	                                require_once(ABSPATH . '/wp-admin/includes/file.php');
	                                require_once(ABSPATH . '/wp-admin/includes/image.php');
	
	                                $rechtstext_pdf_url = $request['rechtstext_pdf_url'];
	                                $rechtstext_pdf_md5hash = $request['rechtstext_pdf_md5hash'];
	
	                                $strMail .= "Download PDF Start: " . date('d.m.Y H:i:s', time()) . "\r\n";
	
	                                $tmp_file = \download_url($rechtstext_pdf_url, 300);
	                                $test_md5_file = md5_file($tmp_file);
	
	                                $strMail .= "Download PDF ENDE: " . date('d.m.Y H:i:s', time()) . "\r\n";
	
	                                if ($test_md5_file === $rechtstext_pdf_md5hash) {
	
	                                    $wp_upload_dir = \wp_upload_dir();
	                                    $post_id = $arPageConfig['set'];
	                                    $lang = $request['rechtstext_language'];
	                                    $rechtstext_type = $request['rechtstext_type'];
	
	                                    if (file_exists($tmp_file)) {
	
	                                        $path = $wp_upload_dir['basedir'].'/it-recht/'.$post_id.'/'.$lang.'/';
	                                        if (!file_exists($path)) mkdir($path, 0766, true);
	
	                                        $file_name = \sanitize_file_name(
	                                            ((isset($request['rechtstext_pdf_filename_suggestion'])) ? $request['rechtstext_pdf_filename_suggestion'] : $arPageConfig['label'] . '.pdf')
	                                        );
	
	                                        @copy($tmp_file, $path.$file_name);
	
	                                        if (file_exists($path.$file_name) )$this->update_option('wpsgitrecht_file_'.$lang.'_'.$request['rechtstext_type'], $file_name);
	
	                                    }
	
	                                } else {
	
	                                    $returnCode = '8';
	
	                                }
	
	                            }

	                        }

                            if ($returnCode === false) {

                                // Inhalt verarbeiten
                                $this->UpdateQuery($wpdb->prefix . "posts", array(
                                    "post_content" => $this->q($request['rechtstext_html'])
                                ), "`ID` = '" . $this->q($arPageConfig['set']) . "'");

                                $this->update_option('wpsgitrecht_html_' . $request['rechtstext_type'], $request['rechtstext_html']);
                                $this->update_option('wpsgitrecht_lastupdate_' . $request['rechtstext_type'], time());

                                $returnCode = "success";

                                $strMail .= "Verarbeitung Ende: " . date('d.m.Y H:i:s', time()) . "\r\n";

                            }

                        }

                    }

				}

				// error 11
				// Wert für user_account_id wird benötigt (Multishop-System), ist aber leer oder nicht gültig oder passt nicht zur Kombination user_username/user_password bzw. zu user_auth_token

				$doc = new DOMDocument('1.0', 'utf-8');

				$node_response = $doc->createElement("response");

				if ($returnCode === "success") {
				
					$node_status = $doc->createElement("status");
					$node_status->appendChild($doc->createTextNode($returnCode));
 
				} else {
					
					$node_status = $doc->createElement("status");
					$node_status->appendChild($doc->createTextNode('error'));
					
					$node_error = $doc->createElement('error');
					$node_error->appendChild($doc->createTextNode($returnCode));
                    $node_response->appendChild($node_error);

                    if ($errorText !== false) {

                        $node_error_message = $doc->createElement('error_message');
                        $node_error_message->appendChild($doc->createCDATASection($errorText));
                        $node_response->appendChild($node_error_message);

                    }
					
				}
				
				// ModulVersion
				$node_module_version = $doc->createElement('meta_modulversion');
				$node_module_version->appendChild($doc->createTextNode($this->get_plugin_version('wpshopgermany-it-recht-kanzlei/wpshopgermany-itrecht.php')));
				$node_response->appendChild($node_module_version);

                $node_phpversion = $doc->createElement("meta_phpversion");
                $node_phpversion->appendChild($doc->createTextNode(phpversion()));
                $node_response->appendChild($node_phpversion);

				if (function_exists('is_plugin_active') && is_plugin_active('wpshopgermany-free/wpshopgermany.php'))
				{

					$node_shop_version = $doc->createElement('meta_shopversion');
					$node_shop_version->appendChild($doc->createTextNode($this->get_plugin_version('wpshopgermany-free/wpshopgermany.php')));
					
					$node_response->appendChild($node_shop_version);
					
				}				
				
				$node_response->appendChild($node_status);
				 
				$doc->appendChild($node_response);

                $strMail .= "Script Ende: " . date('d.m.Y H:i:s', time())."\r\n";
                $strMail .= "header_sent: " . date('d.m.Y H:i:s', time()).": ".((headers_sent() === true)?'true':'false')."\r\n";
                $strMail .= "xml_output: \r\n".$doc->saveXML();

				$strMail .= "request: ".print_r($_REQUEST, true)."\r\n";

				//mail("", "IT Recht Debug", $strMail);

				header('Content-Type: application/xml; charset=utf-8');
				echo $doc->saveXML();
				exit;
				
			}

			/*
            if (is_plugin_active('woocommerce/woocommerce.php')) {

                add_filter('woocommerce_email_attachments', 'wpsg_itrecht_woocommerce_email_attachments', 10, 3);

            }
            */

            add_filter('woocommerce_email_attachments', 'wpsg_itrecht_woocommerce_email_attachments', 10, 3);
            add_filter('wpsg_sendMail', 'wpsg_itrecht_wpsg_sendMail', 10, 1);

            /*
            if (is_plugin_active('wpshopgermany-free/wpshopgermany.php')) {

                add_filter('wpsg_sendMail', 'wpsg_itrecht_wpsg_sendMail', 10, 1);

            }
            */

		} // function wp_loaded()
		
		public function getPageTypes() {
			
			$arPageTypes = array(
				'agb' => array(
					'label' => __('Allgemeine Geschäftsbedingungen', 'wpsgitrecht'),
					'shop_page_option' => 'wpsg_page_agb',
					'needPDF' => true
				),	
				'datenschutz' => array(
					'label' => __('Datenschutzerklärung', 'wpsgitrecht'),
					'shop_page_option' => 'wpsg_page_datenschutz',
					'needPDF' => true
				),
				'widerruf' => array(
					'label' => __('Widerrufsbelehrung', 'wpsgitrecht'),
					'shop_page_option' => 'wpsg_page_widerrufsbelehrung',
					'needPDF' => true
				),
				'impressum' => array(
					'label' => __('Impressum', 'wpsgitrecht'),
					'shop_page_option' => 'wpsg_page_impressum',
					'needPDF' => false
				)
			);
			
			// Werte auslesen
			foreach ($arPageTypes as $page_key => $page) {
				
				if ($this->get_option('wpsgitrecht_lastupdate_'.$page_key) !== false) $arPageTypes[$page_key]['last_update'] = $this->get_option('wpsgitrecht_lastupdate_'.$page_key);
				else $arPageTypes[$page_key]['last_update'] = 0;
				
				$set = false;
				
				if ($this->get_option('wpsgitrecht_page_'.$page_key) !== false) $set = $this->get_option('wpsgitrecht_page_'.$page_key);
				else {

					// Eventuell Seite aus Shop
					if (function_exists('is_plugin_active') && is_plugin_active('wpshopgermany-free/wpshopgermany.php') && $this->get_option($page['shop_page_option']) !== false)
					{

						$set = $this->get_option($page['shop_page_option']);
						$this->update_option('wpsgitrecht_page_'.$page_key, $set);
						
					}
					
				}
				
				$arPageTypes[$page_key]['set'] = $set;
				
				$lang = 'de';
				
				if ($set > 0) {
				
					$arAtt = get_posts(array(
						'post_type' => 'attachment',
						'posts_per_page' => -1,
						'post_parent' => $set
					));
					
					foreach ($arAtt as $a) {
						
						if ($a->post_title === 'it_recht_'.$lang.'_'.$page_key) {
							
							$arPageTypes[$page_key]['pdf_url'] = \wp_get_attachment_url($a->ID);
							
						}
						
					}
					
				}
				
			}
									
			return $arPageTypes;
			
		}
		
		public function getPages()
		{
			
			$pages = get_pages();
			
			$arPages = array();
			
			foreach ($pages as $k => $v)
			{
				$arPages[$v->ID] = $v->post_title.' (ID:'.$v->ID.')';
			}
				
			return $arPages;
			
		} // public function getPages()
		
		public function showForm()
		{
			
			return $this->render('form.phtml');
			
		} // public function showForm()
		
		public function saveForm()
		{
			
			global $wpdb;
			
			$this->update_option('wpsgitrecht_apiToken', $_REQUEST['wpsgitrecht_apiToken']);
			
			foreach ($this->getPageTypes() as $page_key => $page) {
				 
				if (isset($_REQUEST['WpsgOrderMail'][$page_key])) {
					
					if ($_REQUEST['WpsgOrderMail'][$page_key] === '1') $this->update_option('wpsgitrecht_wpsgmail_'.$page_key, '1');
					else $this->update_option('wpsgitrecht_wpsgmail_'.$page_key, '0');
					
				}
				
				if (isset($_REQUEST['WooOrderMail'][$page_key])) {
					
					if ($_REQUEST['WooOrderMail'][$page_key] === '1') $this->update_option('wpsgitrecht_woomail_'.$page_key, '1');
					else $this->update_option('wpsgitrecht_woomail_'.$page_key, '0');
					
				}
				
				if ($_REQUEST['ContentPage'][$page_key] > 0) {

					$this->update_option('wpsgitrecht_page_'.$page_key, $_REQUEST['ContentPage'][$page_key]);
					
				} else if ($_REQUEST['ContentPage'][$page_key] == '-1') {
					
					// Seite anlegen
					global $wpdb;
					  
					$user_id = 0; if (function_exists("get_currentuserinfo")) { $current_user = wp_get_current_user(); $user_id = $current_user->user_ID; }					
					if ($user_id == 0 && function_exists("get_current_user_id")) { $user_id = get_current_user_id(); }
					 
					//wpsg_debug($page);
					
					$data = array(
						"post_author" => $user_id,
						"post_date" => "NOW()",
						"post_title" => $page['label'],
						"post_date_gmt" => "NOW()",
						"post_name" => sanitize_title(strtolower($page['label'])),
						"post_status" => "publish",
						"comment_status" => "closed",
						"ping_status" => "neue-seite",
						"post_type" => "page",
						"post_content" => '',
						"ping_status" => "closed",
						"comment_status" => "closed",
						"post_excerpt" => "",
						"to_ping" => "",
						"pinged" => "",
						"post_content_filtered" => ""
					);
					
					$page_id = $this->ImportQuery($wpdb->prefix."posts", $data);
					
					$this->UpdateQuery($wpdb->prefix."posts", array(
						"post_name" => $this->clear($page['label'], $page_id)
					), "`ID` = '".$this->q($page_id)."'");
					
					$this->update_option('wpsgitrecht_page_'.$page_key, $page_id);
					
				}
				
			}
			
			$this->addBackendMessage(__('Einstellungen erfolgreich gespeichert.', 'wpsgitrecht'));
			
		} // public function saveForm()
		
		public function getNewApiKey() {
			
			$new_code = str_replace('/', '', substr(str_shuffle(base64_encode(rand(1, 500).time().$_SERVER['REQUEST_URI'].rand(1, 500))), 0, 42));
			
			return $new_code;
			
		}
		
		public function getAPIKey() {
		
			$api_token = $this->get_option('wpsgitrecht_apiToken');
			
			if (wpsgitrecht_isSizedString($api_token)) {
				
				return $api_token;
				
			} else {
				
				$new_code = $this->getNewApiKey();
				
				$this->update_option('wpsgitrecht_apiToken', $new_code);
				
				return $new_code;				
				
			}
			
		}
		
		public function getAPIUrl()
		{
			
			$home_url = home_url();
			
			if (strpos($home_url, '?') === false) $home_url .= '?wpsgitrecht_action=api';
			else $home_url .= '&wpsgitrecht_action=api';
			
			return $home_url;
			
		} // public function getAPIUrl()
		
		private function render($file)
		{
			
			ob_start();
			include dirname(__FILE__).'/../views/'.$file;
			$content = ob_get_contents();
			ob_end_clean();
			
			return $content;
			
		} // private function render($file)
		
		public function get_option($key)
		{
				
			return get_option($key);
				
		}
		
		public function update_option($key, $value)
		{
				
			update_option($key, $value);
				
		}
		 
		/**
		 * Fügt eine Hinweismeldung eines Backend Moduls hinzu
		 * Wird mittels writeBackendMessage ausgegeben
		 */
		public function addBackendMessage($message)
		{
		
			if (isset($_REQUEST['wpsg_mod_legaltexts_submitform'])) $GLOBALS['wpsg_sc']->addBackendMessage($message);
				
			if (isset($_SESSION['wpsgitrecht']['backendMessage']) && !in_array($message, (array)$_SESSION['wpsgitrecht']['backendMessage'])) $_SESSION['wpsgitrecht']['backendMessage'][] = $message;
		
		} // public function addBackendMessage($message)
		
		/**
		 * Fügt eine neue Fehlermeldung eines Backend Moduls hinzu
		 */
		public function addBackendError($message)
		{
		
			if (isset($_REQUEST['wpsg_mod_legaltexts_submitform'])) $GLOBALS['wpsg_sc']->addBackendError($message);
				
			if (isset($_SESSION['wpsgitrecht']['backendError']) && !in_array($message, (array)$_SESSION['wpsgitrecht']['backendError'])) $_SESSION['wpsgitrecht']['backendError'][] = $message;
		
		} // public function addBackendError($message)
		
		public function writeBackendMessage()
		{
		
			$strOut  = '';
		
			if (!isset($_SESSION['wpsgitrecht']['backendMessage']) && !isset($_SESSION['wpsgitrecht']['backendError'])) return;
		
			if (is_array($_SESSION['wpsgitrecht']['backendMessage']) && sizeof($_SESSION['wpsgitrecht']['backendMessage']) > 0)
			{
		
				$strOut  .= '<div id="wpsgitrecht_message" class="updated">';
		
				foreach ($_SESSION['wpsgitrecht']['backendMessage'] as $m)
				{
		
					$strOut .= '<p>'.$m.'</p>';
						
				}
		
				$strOut .= '</div>';
		
				unset($_SESSION['wpsgitrecht']['backendMessage']);
		
			}
		
			if (wpsgitrecht_isSizedArray($_SESSION['wpsgitrecht']['backendError']))
			{
		
				$strOut  .= '<div id="wpsgitrecht_message" class="error">';
		
				foreach ($_SESSION['wpsgitrecht']['backendError'] as $m)
				{
		
					$strOut .= '<p>'.$m.'</p>';
		
				}
		
				$strOut .= '</div>';
		
				unset($_SESSION['wpsgitrecht']['backendError']);
		
			}
		
			return $strOut;
		
		} // public function writeBackendMessage()
		
		/**
		 * Importiert die Daten aus $data als neue Zeile in die Tabelle $table
		 * $data muss dabei aus einem Schlüssel/Wert Array bestehen
		 * Der Rückgabewert ist die ID des eingefügten Datensatzes
		 */
		function ImportQuery($table, $data, $checkCols = false)
		{
				
			global $wpdb;
				
			/**
			 * Wenn diese Option aktiv ist, so werden Spalten nur importiert
			 * wenn sie auch in der Zieltabelle existieren.
			 */
			if ($checkCols === true)
			{
		
				$arFields = $this->fetchAssoc("SHOW COLUMNS FROM `".$this->q($table)."` ");
		
				$arCols = array();
				foreach ($arFields as $f) { $arCols[] = $f['Field']; }
				foreach ($data as $k => $v) { if (!in_array($k, $arCols)) { unset($data[$k]); } }
		
			}
				
			if (!wpsgitrecht_isSizedArray($data)) return false;
				
			// Query zusammenbauen
			$strQuery = "INSERT INTO `".$this->q($table)."` SET ";
				
			foreach ($data as $k => $v)
			{
		
				if ($v != "NOW()" && $v != "NULL" && !is_array($v))
					$v = "'".$v."'";
				else if (is_array($v))
					$v = $v[0];
					
				$strQuery .= "`".$k."` = ".$v.", ";
		
			}
				
			$strQuery = substr($strQuery, 0, -2);
				
			$res = $wpdb->query($strQuery);
		
			return $wpdb->insert_id;
			
		} // function ImportQuery($table, $data)
		
		/**
		 * Gibt eine einzelne Zelle aus der Datenbank zurück
		 */
		function fetchOne($strQuery)
		{
				
			global $wpdb;
		
			$result = $wpdb->get_var($strQuery);
		
			return $result;
				
		} // function fetchOne($strQuery)
		
		/**
		 * Aktualisiert Zeilen in der Datenbank anhand des $where Selectse
		 */
		function UpdateQuery($table, $data, $where)
		{
				
			global $wpdb;
				
			// Query aufbauen, da wir den kompletten QueryWHERE String als String übergeben
			$strQuery = "UPDATE `".$this->q($table)."` SET ";
				
			foreach ($data as $k => $v)
			{
		
				if ($v != "NOW()" && $v != "NULL" && !is_array($v))
					$v = "'".$v."'";
				else if (is_array($v))
					$v = $v[0];
					
				$strQuery .= "`".$k."` = ".$v.", ";
		
			}
				
			$strQuery = substr($strQuery, 0, -2)." WHERE ".$where;
				
			$res = $wpdb->query($strQuery);
						
			return $res;
				
		} // function UpdateQuery($table, $data, $where)
		
		function q($value)
		{
			 
			if (is_array($value))
			{
				
				foreach ($value as $k => $v)
				{
					
					$value[$k] = $this->q($v);
					
				}
				
				return $value;
				
			}
			else
			{
							
				return esc_sql($value);
				
			}
			
		} // function q($value)
		
		/**
		 * Bereinigt den URL Key bzw. das Path Segment
		 * Ist der Parameter post_id angegeben, so wird überprüft das kein Post ungleich dieser ID mit diesem Segment existiert
		 */
		public function clear($value, $post_id = false)
		{
				
			global $wpdb;
				
			$arReplace = array(
				'/Ö/' => 'Oe', '/ö/' => 'oe',
				'/Ü/' => 'Ue', '/ü/' => 'ue',
				'/Ä/' => 'Ae', '/ä/' => 'ae',
				'/ß/' => 'ss', '/\040/' => '-',
				'/\€/' => 'EURO',
				'/\//' => '_',
				'/\[/' => '',
				'/\]/' => '',
				'/\|/' => ''
			);
				
			$strReturn = preg_replace(array_keys($arReplace), array_values($arReplace), $value);
			$strReturn = sanitize_title($strReturn);
		
			if (is_numeric($post_id) && $post_id > 0)
			{
		
				$n = 0;
		
				while (true)
				{
						
					$n ++;
						
					$nPostsSame = $this->fetchOne("SELECT COUNT(*) FROM `".$wpdb->prefix."posts` WHERE `post_name` = '".$this->q($strReturn)."' AND `id` != '".$this->q($post_id)."'");
						
					if ($nPostsSame > 0)
					{
		
						$strReturn .= $n;
		
					}
					else
					{
		
						break;
		
					}
						
				}
		
			}
				
			return $strReturn;
				
		} // private function clear($value)
		
	} // class wpsg_itrecht

