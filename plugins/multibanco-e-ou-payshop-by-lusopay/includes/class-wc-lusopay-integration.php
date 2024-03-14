<?php
/**
* LUSOPAY Integration.
*
* @package  WC_LUSOPAY_Integration
* @category Integration
* @author   LUSOPAY
*/
define("HTML_EMAIL_HEADERS", array('Content-Type: text/html; charset=UTF-8'));

if (!class_exists('WC_Lusopay_Integration')):
	class WC_Lusopay_Integration extends WC_Integration
	{

		/**
		 * Init and hook in the integration.
		 */
		public function __construct()
		{
			global $woocommerce;
			global $wpdb;

			$this->id = 'multibanco-e-ou-payshop-by-lusopay';
			$this->method_title = __('LUSOPAY', 'lusopaygateway');
			$this->method_description = __('LUSOPAY services integration.', 'lusopaygateway');

			//IR à base de dados colocar a chave e nif para os clientes antigos

			if (!$this->check_if_option_name_exists("'woocommerce_multibanco-e-ou-payshop-by-lusopay_settings'") && $this->check_if_option_name_exists("'woocommerce_lusopaygateway_settings'")) {
				$this->insert_chave_and_nif_to_old_clients();
			}

			if (trim($this->secret_key = $this->get_option('secret_key')) === '') {
				$this->secret_key = md5(home_url() . time() . rand(0, 999));
			} else {
				$this->secret_key = $this->get_option('secret_key');
			}
			//$this->version = VERSION;

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables.
			$this->chave = $this->get_option('chave');
			$this->nif = $this->get_option('nif');
			$this->debug = ($this->get_option('debug') == 'yes' ? true : false);
			$this->custom_field = ($this->get_option( 'custom_field' ) == 'yes' ? true : false);


			// Actions.
			add_action('woocommerce_update_options_integration_' . $this->id, array($this, 'process_admin_options'));
		}

		/**
		 * Initialize integration settings form fields.
		 */
		public function init_form_fields()
		{

			$debug_log_description = __('Log plugin events, such as callback requests, inside', 'lusopaygateway') . '<code>' . wc_get_log_file_path($this->id) . '</code>';

			//$lang = __('Date of last sent email: ','lusopaygateway');//Data de envio do ultimo email: 
			$custom_field_2_value = get_option('lusopaygateway_custom_field_2_value');
			if ($custom_field_2_value === false) {
				$custom_field_2_value = 'Não enviado';
				//update_option('lusopaygateway_custom_field_2_value', $custom_field_2_value);
			}
		
			if (isset($_POST['submit'])) {
				if (isset($_POST[$this->plugin_id . $this->id . '_custom_field'])) {
					$custom_field_2_value = date('Y-m-d H:i:s');
					update_option('lusopaygateway_custom_field_2_value', $custom_field_2_value);
				}else{
					if($custom_field_2_value == 'Não enviado'){
						$custom_field_2_value = '';
					}
				}
			}

			
		

			$this->form_fields = array(
				'chave' => array(
					'title' => __('ClientGuid', 'lusopaygateway'),
					'type' => 'text',
					'description' => __('ClientGuid given by LUSOPAY after registering at LUSOPAY website and after informing LUSOPAY that you want to activate the Multibanco, Payshop, MB Way services and Simplified Transfer.', 'lusopaygateway'),
					'default' => ''
				),
				'nif' => array(
					'title' => __('VatNumber', 'lusopaygateway'),
					'type' => 'text',
					'description' => __('It is in the email that you receive when you activate the service.', 'lusopaygateway'),
					'default' => ''
				),
				'secret_key' => array(
					'title' => __('Anti-phishing Key', 'lusopaygateway'),
					'type' => 'hidden',
					'description' => '<b id="lusopaygateway_secret_key_label">' . $this->secret_key . '</b><br/> ',
					'default' => $this->secret_key,
				),
				'debug' => array(
					'title' => __('Debug Log', 'woocommerce'),
					'type' => 'checkbox',
					'label' => __('Enable logging', 'woocommerce'),
					'default' => 'no',
					'description' => $debug_log_description
				),
				'custom_field' => array(
					'title' => __('Send Email', 'lusopaygateway'),
					'type' => 'checkbox',
					'description' => __('Send Callbacks to LUSOPAY', 'lusopaygateway'),
					'default' => 'no',
				),
				'custom_field_2' => array(
					'type' => 'hidden',
					'description' => '<b style="font-size:1.2em">Data de envio do ultimo email: '. $custom_field_2_value . '</b>',

				)
			);
			if (isset($_POST['submit']) && isset($_POST[$this->plugin_id . $this->id . '_custom_field'])) {
				update_option('custom_field_2_value', date('Y-m-d H:i:s'));
			}
		}
		public function get_clientGuid()
		{
			return $this->chave;
		}

		public function get_vat_number()
		{
			return $this->nif;
		}

		public function get_debug_option()
		{
			return $this->debug;
		}

		public function get_chave_anti_phishing()
		{
			return $this->secret_key;
		}

		public function get_date()
		{
			return $this->date;
		}

		public function get_custom_field(){
			return $this->custom_field;
		}

		
		private function insert_chave_and_nif_to_old_clients()
		{
			global $wpdb;
			$table_name = $wpdb->prefix . 'options';
			$option_name = "'woocommerce_lusopaygateway_settings'";

			//$query = 'SELECT * FROM '.$table_name.' WHERE option_name ='.$option_name;

			$rows = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE option_name =' . $option_name);

			if ($rows) {
				foreach ($rows as $row) {
					$settings = $row->option_value;
				}

				$wpdb->insert($table_name, array(
					'option_name' => 'woocommerce_multibanco-e-ou-payshop-by-lusopay_settings',
					'option_value' => $settings,
				)); // db call ok; no-cache ok.
			}

		}

		public function check_if_option_name_exists($option_name)
		{
			global $wpdb;
			$exists = false;
			$table_name = $wpdb->prefix . 'options';
			$check_table = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE option_name =' . $option_name);

			if (!empty($check_table)) {
				$exists = true;
			}

			return $exists;
		}

		
		public function process_admin_options() {

			$email_sent = get_option( 'email_sent' );
			
			if (isset($_POST['woocommerce_multibanco-e-ou-payshop-by-lusopay_chave'])) {
				$chave = $_POST['woocommerce_multibanco-e-ou-payshop-by-lusopay_chave'];
				update_option( 'woocommerce_multibanco-e-ou-payshop-by-lusopay_settings', array(
					'chave' => sanitize_text_field($chave)
				  ) );
			}
			if (isset($_POST['woocommerce_multibanco-e-ou-payshop-by-lusopay_nif'])) {
				$nif = $_POST['woocommerce_multibanco-e-ou-payshop-by-lusopay_nif'];
				update_option( 'woocommerce_multibanco-e-ou-payshop-by-lusopay_settings', array(
					'nif' => sanitize_text_field( $_POST['woocommerce_multibanco-e-ou-payshop-by-lusopay_nif'] )
				  ) );
			}
			if (isset($_POST['woocommerce_multibanco-e-ou-payshop-by-lusopay_secret_key'])){
				$secret_key = sanitize_text_field($_POST['woocommerce_multibanco-e-ou-payshop-by-lusopay_secret_key']);
			}
			//$custom_field_value = $this->get_option('custom_field');

			if ($email_sent !== '1') {
				if (function_exists('wp_mail')) {
					$admin_user = get_user_by('id', 1);
					$admin_email = $admin_user->user_email;
					$headers[] = 'FROM: '. $admin_email;
					$headers[] = "Content-Type: text/html;";
					$ip_address = getHostByName(getHostName());
					$message = "ClientGuid: " . $chave . "<br>";
					$message .= "PayShop: " . home_url('/wc-api/WC_Lusopay_PS/?entidade=«entidade»&referencia=«referencia»&valor=«valor»&chave=' . $secret_key) . "<br>";
					$message .= "Multibanco: " .home_url('/wc-api/WC_Lusopaygateway/?entidade=«entidade»&referencia=«referencia»&valor=«valor»&chave=' . $secret_key ) ."<br>";
					$message .= "MBWay: " .home_url('/wc-api/WC_Lusopay_MBWAY/?descricao=«descricao»&statuscode=«statuscode»&data=«data»&valor=«valor»&chave=' .$secret_key) . " - IP: " .$ip_address."<br>";
					$message .= "Transferência Simplificada: ". home_url( '/wc-api/WC_Lusopay_PISP/?pispId=«pisId»&amount=«amount»&description=«description»&date=«date»&status=«status»&chave=' . $secret_key ) ;
					$subject = "Ativar CALLBACK - " . $nif;
					// send the email
					if ($nif === '999999999') {
						wp_mail('dev@lusopay.com', $subject, $message, $headers);
						update_option( 'email_sent', '1' );
					} else {
						wp_mail('geral@lusopay.com', $subject, $message, $headers);
						update_option( 'email_sent', '1' );
					}
				  } else {
					 //Notificação
					 echo '<div class="notice notice-warning is-dismissible">
							 <p>Plugin de envio de emails não detetado. Proceda para a aba dos pagamentos </p>
						  </div>';
				  }
			}


			/*if (isset($_POST['woocommerce_multibanco-e-ou-payshop-by-lusopay_custom_field'])){
			   // Verificar se a checkbox foi usada
			   if ( $custom_field_value === 'yes') {
				  // Verificar se tem um SMTP instalado
				  
			   }
			}*/
			parent::process_admin_options();
			if ($this->settings['custom_field'] === 'yes') {
				$tz = 'Europe/London';
				$timestamp = time();
				$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
				$dt->setTimestamp($timestamp); //adjust the object to correct timestamp
				$dateNow = $dt->format('d-m-Y, H:i:s');
				update_option('lusopaygateway_custom_field_2_value', $dateNow);
			} else {
				delete_option('lusopaygateway_custom_field_2_value');
			}
		 }
		
	}
endif;
