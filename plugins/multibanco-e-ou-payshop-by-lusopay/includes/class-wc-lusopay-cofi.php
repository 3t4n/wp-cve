<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
*
* Lusopay - Transferência Simplificada
*
*/

if ( !class_exists('WC_LUSOPAY_COFI') ){
    /*
    
    Class WC_LUSOPAY_COFI
    
    */

    class WC_Lusopay_COFI extends WC_Payment_Gateway {
		
		/**
		 * Lusopay Database Version
		 *
		 * @var string
		 */
		public $database_version = '1.0';

		/**
		 * User ClientGuid
		 *
		 * @var string
		 */
		 public $chave = '';

		 		/**
		 * Lusopay Debugger State
		 *
		 * @var bool
		 */
		public $debug = false;

		/**
		 * Lusopay Debugger Object
		 *
		 * @var bool|WC_Logger
		 */
		public $log = false;

		/**
		 * Lusopay Secret Key
		 *
		 * @var string
		 */
		public $secret_key = '';

		/**
		 * Only Above a certain order value
		 *
		 * @var float
		 */
		public $only_above = 0;

		/**
		 * Only bellow a certain order value
		 *
		 * @var float
		 */
		public $only_bellow = 0;
		public $cofdispassword = "";
		public $cofidisUsername ="";

		/**
		 * IPN Url for Reference Payment
		 *
		 * @var string
		 */
		public $notify_url = '';

		/**
		 * Lusopay COFI ID
		 *
		 * @var string
		 */
		

		public function __construct()
		{

			$this->integration = new WC_Lusopay_Integration;
			$this->id = 'lusopay_cofi';
			$this->icon = plugins_url( '../imagens/cofidispay_logo.jpg', __FILE__ );
			$this->has_fields = true;
			$this->method_title = 'Cofidis Pay(BY LUSOPAY)';
			$this->secret_key = $this->get_chave_anti_phishing();
		
			$this->debug = ( 'yes' === $this->get_debug() ? true : false );
			if ( $this->debug ) {
				$this->log = version_compare( WC_VERSION, '3.0', '>=' ) ? wc_get_logger() : new WC_Logger();
			}
			$this->chave = $this->get_chave();

			$this->notify_url = ( '' === get_option( 'permalink_structure' ) ) ? home_url( '/?wc-api=WC_Lusopay_COFI&cofiId=«cofiId»&amount=«amount»&description=«description»&date=«date»&status=«status»&chave=' . $this->secret_key ) : home_url( '/wc-api/WC_Lusopay_COFI/?cofiId=«cofiId»&amount=«amount»&description=«description»&date=«date»&status=«status»&chave=' . $this->secret_key );
			
			$this->init_form_fields();
			$this->init_settings();


			$this->set_plugin_title( $this->get_option( 'title' ) );
			$this->set_plugin_description( $this->get_option( 'description' ) );
			$this->nif = $this->get_nif();
			$this->title = $this->get_option('title');
			$this->only_above    = $this->get_option( 'only_above' );
			$this->only_bellow   = $this->get_option( 'only_bellow' );
			$this->cofdispassword   = $this->get_option( 'cofdispassword' );
			$this->cofidisUsername   = $this->get_option( 'cofidisUsername' );

			if ( get_site_option( 'cofi_db_version' ) !== $this->database_version ) {
				$this->handle_database();
			}			
			//add_action( 'wp_enqueue_scripts', 'enqueue_newtab_script' );
			//function enqueue_newtab_script() {
			//	if(is_checkout()) {
				  wp_enqueue_script( 'my-plugin-script', plugins_url( 'newtab.js', __FILE__ ), array( 'jquery' ), date("h:i:s") );
				  wp_localize_script('my-plugin-script', 'my_plugin_data', array('callback_url' => get_site_url().'/wc-api/WC_Lusopay_COFI/'));
			//	}
			//  }
			/*add_action('wp_ajax_get_redirect_link', array($this,'get_redirect_link'));
			add_action('wp_ajax_nopriv_get_redirect_link',array($this, 'get_redirect_link'));*/
			// Customer Emails.
			add_action( 'woocommerce_email_before_order_table', array(
				$this,
				'email_instructions_lusopay_cofi',
			), 10, 2 );
			
	
			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'disable_only_above_or_below' ) );
			add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( &$this, 'callback' ) );
		}

		public function get_chave() {
			return $this->integration->get_clientGuid();
		}

		/**
		 * Get nif
		 */
		public function get_nif() {
			return $this->integration->get_vat_number();
		}
		/**
		 * Get debug option
		 */
		public function get_debug() {
			return $this->integration->get_debug_option();
		}

		/**
		 * Set Plugin Id
		 *
		 * @param string $id ID of the class extending the settings API. Used in option names.
		 */
		public function set_plugin_id( $id ) {
			$this->id = $id;
		}

		/**
		 * Set Plugin Icon
		 *
		 * @param string $icon Icon for the gateway.
		 */
		public function set_plugin_icon( $icon ) {
			$this->icon = $icon;
		}

		/**
		 * Set Plugin has fields
		 *
		 * @param boolean $has_fields if the gateway shows fields on the checkout.
		 */
		public function set_plugin_has_fields( $has_fields ) {
			$this->has_fields = $has_fields;
		}

		/**
		 * Set Plugin Title
		 *
		 * @param string $method_title Gateway title.
		 */
		public function set_plugin_method_title( $method_title ) {
			$this->method_title = $method_title;
		}

		/**
		 * Set Plugin Payment method title for the frontend.
		 *
		 * @param string $title Payment method title for the frontend.
		 */
		public function set_plugin_title( $title ) {
			$this->title = $title;
		}

		/**
		 * Set Plugin Payment method description for the frontend.
		 *
		 * @param string $description Payment method description for the frontend.
		 */
		public function set_plugin_description( $description ) {
			$this->description = $description;
		}

		/**
		 * Icon HTML
		 */
		public function get_icon() {
			$icon_html = '<img src="' . esc_attr( $this->icon ) . '" alt="' . esc_attr( $this->title ) . '" />';

			return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
		}

		/**
		 * Get chave anti-phishing
		 */
		public function get_chave_anti_phishing() {
			return $this->integration->get_chave_anti_phishing();
		}

		function handle_database(){
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$charset_collate = $wpdb->get_charset_collate();
			$table_name      = $wpdb->prefix . 'lusopaycofi';
			dbDelta( "CREATE TABLE IF NOT EXISTS `" . $table_name . "` (
				id int NOT NULL AUTO_INCREMENT,
				order_id varchar(10) NOT NULL,
				value int(10) NOT NULL,
				PAGO varchar(100) NULL,
				reference varchar(255) NULL,
				PRIMARY KEY(id)) $charset_collate;");		
			update_option( 'cofi_db_version', $this->database_version );
			
		}
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'lusopaygateway'),
					'type'=> 'checkbox',
					'label'   => __( 'Enable Cofidi (By LUSOPAY)', 'lusopaygateway' ),
					'default' => 'no',
				),
				'cofdispassword'    => array(
					'title'       => __( 'Username Api (Cofidis Pay)', 'lusopaygateway' ),
					'type'        => 'text',
					'description' => __( 'Providence by cofidis Pay.'),
					'default'     => '',
				),
				'cofidisUsername'    => array(
					'title'       => __( 'Password Api (Cofidis Pay)', 'lusopaygateway' ),
					'type'        => 'password',
					'description' => __( 'Providence by cofidis Pay.'),
					'default'     => '',
				),
				'only_above'    => array(
					'title'       => __( 'Only for orders more than', 'lusopaygateway' ),
					'type'        => 'number',
					'description' => __( 'Activate only for orders above € x (exclusive).'),
					'default'     => '',
				),
				'only_bellow'   => array(
					'title'       => __( 'Only for orders below', 'lusopaygateway' ),
					'type'        => 'number',
					'description' => __( 'Activate only for orders below € x (exclusive).'),
					'default'     => '',
				),
				'title' => array(
					'title' => __('Title', 'lusopaygateway'),
					'type' => 'text',
					'description' => __( 'This controls the title that customer sees when doing checkout.', 'lusopaygateway' ),
					'default' => __('Cofidis Pay(by LUSOPAY)', 'lusopaygateway'),
				),
				'description' => array(
					'title' => __('Descrição','lusopaygateway'),
					'type' => 'textarea',
					'description' => __( 'This controls the description that customer sees when doing checkout.', 'lusopaygateway' ),
					'default' => __('You will be redirected to the cofidis pay	 page where you will make the payment after completing the payment do not leave the page until you are redirected back to our website', 'lusopaygateway'),
					'css' => 'readonly',
				),
			);
		}

		/**
		 * Admin Plugin Configurations Form
		 */
		public function admin_options() {
			include 'views/html-admin-page-cofi.php';
		}

		function disable_only_above_or_below($available_gateways) {
			global $woocommerce;
			if (isset($available_gateways[$this->id])) {
				if (@floatval($available_gateways[$this->id]->only_above) > 0) {
					if ( $woocommerce->cart->total < floatval($available_gateways[$this->id]->only_above) ) {
						unset($available_gateways[$this->id]);
					}
				}
				if ( @floatval($available_gateways[$this->id]->only_bellow) > 0 ) {
					if ( $woocommerce->cart->total > floatval($available_gateways[$this->id]->only_bellow) ) {
						unset($available_gateways[$this->id]);
					}
				}
			}
			return $available_gateways;
		}

		/**
		 * Email instructions
		 *
		 * @param mixed $order Order Object.
		 */
		function email_instructions_lusopay_cofi( $order ) {
			global $wpdb;
			$order_id = version_compare( WC_VERSION, '3.0', '>=' ) ? $order->get_id() : $order->id;
			$order    = new WC_Order_Lusopay( $order_id );

			if ( $order->lp_order_get_payment_method() !== $this->id ) {
				return;
			}
			switch ( $order->lp_order_get_status() ) {
				case 'on-hold':
				case 'pending':
					//echo $this->get_lp_template_email_mb_order_details($order->lp_order_get_id());
					break;
				case 'processing':
					?>
					<p>
						<?php $order->has_downloadable_item() ? esc_html_e( 'Payment received.', 'lusopaygateway' ) : esc_html_e( 'Payment received.', 'lusopaygateway' ). esc_html_e( 'We will process your order now.', 'lusopaygateway' )?> 
					</p>
					<?php
					break;
			}
		}



		function getdata($order_id) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'lusopaycofi';

			$result = $wpdb->get_results( $wpdb->prepare( "SELECT reference, value, PAGO FROM $table_name WHERE order_id = %d", $order_id ) );// db call ok; no-cache ok.
			foreach ( $result as $row ) {
				$PAGO     = $row->PAGO;
				$order_value = $row->value;
				$reference   = $row->reference;
			}

			if ($result != null) {
				return array('PAGO' => $PAGO, 'order_value' => $order_value, 'reference' => $reference);
			} else {
				return array('ref' => null, 'order_value' => 0, 'entidade' => 0);
			}
		}
		function get_lp_template_cofi_order_details($order_id) {
			$data = array();
			$data =	$this->getdata($order_id);

			$status = $data['PAGO'];
			$value = $data['order_value'];
			$reference = $data['reference'];

			$parts = explode("|", $reference);

// Agora $parts conterá um array com as partes divididas
$reference = $parts[0];

$html_lusopay = '';
$html_lusopay .= '<div align="">';
			$html_lusopay .= 'Referencia: '.$reference;
			$html_lusopay .= '<p>Valor: '.$value.'€';
			$html_lusopay .= '<p>Estado: '.$status;
			$html_lusopay .= '</div>';
			if($status==='Pendente Fatura'){
				$html_lusopay .= '<p><a href="https://app.lusopay.com:8443/web/#login"target="_blank">Submeter fatura na área cliente da Lusopay</a></p>';

			}
			if($status==='Pendente Fatura'||$status==='Activo'||$status==='Sucesso'){

				$html_lusopay .= '<p><a href="https://app.lusopay.com:8443/web/#login" target="_blank">Área cliente</a></p>';

			}
		
		
		

			return $html_lusopay;
		}

public function nextid(){

	global $wpdb;

$table_name = $wpdb->prefix . 'lusopaycofi';

$query = "SELECT MAX(id) FROM $table_name";
$highestID = $wpdb->get_var($query);
$highestID = $highestID+1;
$id='woocomerce'.$highestID;
return $id;
}

public function generateUniqueOrderReference($table_name,$orderid,$chave) {
	global $wpdb;
	
	$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	
	do {
		$reference = '';
		for ($i = 0; $i < 8; $i++) {
			$randomIndex = rand(0, strlen($characters) - 1);
			$reference .= $characters[$randomIndex];


$domain = home_url();
$id=$orderid.'-'.$reference.'|'.$domain;
		}

		$existing_reference = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE reference = %s", $id));
	} while ($existing_reference > 0);

	return $reference;
}
		/**
		 * Process it
		 */
		public function process_payment($order_id) {
			
			$this->handle_database();
			global $woocommerce;
			global $wpdb;
		
			$order = new WC_Order_Lusopay($order_id);

			$table_name = $wpdb->prefix . 'lusopaycofi';
			$reference =$this->generateUniqueOrderReference($table_name,$order_id,$this->chave);
			$domain = home_url();
			$id=$order_id.'-'.$reference.'|'.$domain;
				$nextid=$this->nextid();
			
			$value=$order->lp_order_get_total();
			
			$pago=null;
			$pedido='';
			$res = $this->sendCOFIRequest($this->chave, $order->lp_order_get_total(), $id,$this->cofidisUsername,$this->cofdispassword,$reference,$order_id,$pedido);
	
		$link =$res;
			
	
			if (strpos($link, 'https') === 0 && strlen($link) > 6) {
			
				$table_name = $wpdb->prefix . 'lusopaycofi';
				$data = array(
					'order_id' => $order_id,
					'value' => $value,
					'PAGO' => $pago,
					'reference'=>$id
				);	
				
				$wpdb->insert($table_name, $data);
			
				// Mark as pending.
				$order->update_status('pending', __('Waiting for payment Cofidis Pay.', 'lusopaygateway'));
		
				$redirect_url = $this->get_return_url($order);
				$redirect_url = add_query_arg('pisp_payment_redirect', urlencode($link), $redirect_url);
		



				return array(
					'result' => 'success',
					'redirect' => $redirect_url,
				);


			
			} else {
				
				$order = wc_get_order($order_id);
				$order->delete(true);

				// Optional: You can also restore stock for the deleted order's products
				$order_items = $order->get_items();
				foreach ($order_items as $item) {
					$product = $item->get_product();
					if ($product) {
						$product->increase_stock($item->get_quantity());
					}
				}
			
				// Optional: You can also delete the associated order notes
				$order_notes = wc_get_order_notes(array(
					'order_id' => $order_id,
				));
				foreach ($order_notes as $note) {
					$note->delete(true);
				}
			
					throw new Exception( __( $res, 'woo' ) );
				
			}
		
		}


		function sendCOFIRequest($chave, $order_value, $id, $password, $username,$reference,$orderid,$pedido) {
			$order_value = sprintf('%01.2f', $order_value);
			$tete='tete';
			$idurl=$orderid.'-'.$reference;
			$urlteste = home_url() . '/wc-api/WC_Lusopay_COFI/?id=' . $idurl;
			$urlcallback= home_url() . '/wc-api/WC_Lusopay_COFI/?reference=' . $id;
			$linkWithoutHttps = preg_replace("/^https?:\/\//", "", $urlteste);
			
			$postData = 'username='.$username.'&password='.$password.'&orderid='.$id.'&value='.$order_value.'&returnURL='.$linkWithoutHttps.'&paidrequest='.$pedido.'&clientguid='.$chave.'&callbackurl='.$urlcallback;
			$lusopay_url = 'https://services.lusopay.com/Cofidispay/cofidisweb.php?'.$postData;
			//$lusopay_url = 'https://services.lusopay.com/Cofidispay/cofidisweb.php?'.$postData;
			
		
			$response = wp_remote_get($lusopay_url, array('sslverify' => false)); // Desativa a verificação do certificado SSL
			
			
			if (is_wp_error($response)) {
				// Lidar com erros de solicitação, se necessário
				return $response->get_error_message();
			}
			
			// Decodificar a resposta JSON
			 $response_data = json_decode($response['body'], true);
			
			
			if (isset($response_data['resposta'])) {
				// Extrair o URL da chave 'resposta'
				$url = $response_data['resposta'];
				return $url;
			} else {
				
				$url = $response_data['resposta'];
				return $url;
			}

		}



	
		/*function get_redirect_link(){
			error_log('TEST');
			global $wpdb;
			$table_name = $wpdb->prefix . 'lusopaycofi';
			$response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE cofiId = %d", $this->cofiId));
			if($response){
				$redirect_link = $response->link;
				$json_response = array('redirect_link' => $redirect_link);
				wp_send_json($json_response);
				wp_die();
			}else{
				wp_send_json_error('No redirect link found.');
			}
		}*/

		/*
		Callback
		*/


		function callback() {
		


			global $wpdb;
			$table_name = $wpdb->prefix . 'lusopaycofi';
			$estado = filter_input(INPUT_GET, 'estado');
			$referencecall=filter_input(INPUT_GET, 'reference');

			if ($estado !== null && $referencecall !== null) {
				

				$query = "SELECT COUNT(*), reference FROM $table_name WHERE reference = %d ";
				$count_and_reference = $wpdb->get_results($wpdb->prepare($query, $referencecall, '%' ));
				
				if (!empty($count_and_reference)) {
					foreach ($count_and_reference as $result) {
					
						$referenc1 = $result->reference;
						
					}
					
				} else {
					echo "Nenhum registro encontrado.";
				}

				if ($referenc1!=null){
					$query = "UPDATE $table_name SET PAGO = %s WHERE reference = %d ";
					$row = $wpdb->get_var($wpdb->prepare($query,$estado, $referenc1));
					echo 'Encomenda atualizada';}
			else{
					 echo 'Erro ao encontrar encomenda';}

			

			}else{
			$cofi_id = filter_input(INPUT_GET, 'id');
			
			$dados = filter_input(INPUT_GET, 'chave');
			$chave=$this->get_chave();
						

	$cofidis_id=$cofi_id;
	$parts  = explode("-", $cofi_id);
	
	$cofi_id = $parts[0];
	$domain = home_url();
	$reference=$parts[1];





// Get the first part as chave
$orderid = $parts[0];


			
		
			// Get the first part as chave
		
			
		
		
	
		
			$query = "SELECT COUNT(*), reference FROM $table_name WHERE order_id = %d AND reference LIKE %s";
			$count_and_reference = $wpdb->get_results($wpdb->prepare($query, $orderid, '%' . $wpdb->esc_like($reference) . '%'));
			
			if (!empty($count_and_reference)) {
				foreach ($count_and_reference as $result) {
				
					$reference = $result->reference;
					
				}
			} else {
				echo "Nenhum registro encontrado.";
			}
		

				if ($reference!=null){
				
				
						$order = new WC_Order_Lusopay($orderid);
						//error_log($cofi_id);
						//error_log(filter_input(INPUT_GET, 'cofiId'));
						if ( $cofidis_id === filter_input(INPUT_GET, 'id')){
							$pedido='enviar pedido';
$order_value=0.00;

	$resposta =$this->sendCOFIRequest($chave, $order_value, $reference, $this->cofidisUsername, $this->cofdispassword,$reference,$orderid,$pedido);
	



$res=$resposta;



							if( $res === "Cancelado"){
						
								$query = "UPDATE $table_name SET PAGO = %s WHERE order_id = %d AND reference = %d";
								$row = $wpdb->get_var($wpdb->prepare($query,$resposta, $orderid,$reference));
							//Transaction Failed
						
							 $return_url= $order->get_checkout_payment_url();
										wp_redirect($return_url);
									exit;
						
							}elseif($res === 'Pendente Fatura'|| $res === 'Cedida') {
								
								
								$query = "UPDATE $table_name SET PAGO = %s WHERE order_id = %d AND reference = %d";
								$row = $wpdb->get_var($wpdb->prepare($query,$resposta, $orderid,$reference));
								$order -> add_order_note('Pagamento Realizado');
								$order->update_status( $order->has_downloadable_item() ? 'processing' : 'processing', __( 'Payment received by Cofidis Pay ', 'lusopaygateway' ));
								WC()->cart->empty_cart();
								$return_url= $this->get_return_url($order);
										
								wp_redirect($return_url);
										exit;
							}else{
								//Pending
								
								$query = "UPDATE $table_name SET PAGO = %s WHERE order_id = %d AND reference = %d";
								$row = $wpdb->get_var($wpdb->prepare($query,$resposta, $orderid,$reference));
								$order->add_order_note( 'Pagamento em processamento: ' . filter_input(INPUT_GET, 'status') );
								$order -> update_status('on-hold', __( 'Waiting for payment confirmation Cofidis Pay.', 'lusopaygateway' ) );
								WC()->cart->empty_cart();
							$return_url= $this->get_return_url($order);
						
					wp_redirect($return_url);
					exit;
							}

					

							










						}else{
							echo "Cofidi Id not send in POST!";
						}
				
				}else{
					echo "Order not found in table!";
				}
			}
			exit;

					
		}
	}
}
?>