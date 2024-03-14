<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
*
* Lusopay - Transferência Simplificada
*
*/

if ( !class_exists('WC_LUSOPAY_PISP') ){
    /*
    
    Class WC_LUSOPAY_PISP
    
    */

    class WC_Lusopay_PISP extends WC_Payment_Gateway {
		
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
		 * User nif
		 *
		 * @var string
		 */
		public $nif = '';

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

		/**
		 * IPN Url for Reference Payment
		 *
		 * @var string
		 */
		public $notify_url = '';

		/**
		 * Lusopay PISP ID
		 *
		 * @var string
		 */
		public $pispId = '';

		/**
		 * Lusopay title
		 *
		 * @var string
		 */
		public $title = '';

		public function __construct()
		{

			$this->integration = new WC_Lusopay_Integration;
			$this->id = 'lusopay_pisp';
			$this->icon = plugins_url( '../imagens/pisp_icon.png', __FILE__ );
			$this->has_fields = true;
			$this->method_title = 'Transferência Simplificada (BY LUSOPAY)';
			$this->secret_key = $this->get_chave_anti_phishing();

			$this->debug = ( 'yes' === $this->get_debug() ? true : false );
			if ( $this->debug ) {
				$this->log = version_compare( WC_VERSION, '3.0', '>=' ) ? wc_get_logger() : new WC_Logger();
			}
			$this->chave = $this->get_chave();

			$this->notify_url = ( '' === get_option( 'permalink_structure' ) ) ? home_url( '/?wc-api=WC_Lusopay_PISP&pispId=«pisId»&amount=«amount»&description=«description»&date=«date»&status=«status»&chave=' . $this->secret_key ) : home_url( '/wc-api/WC_Lusopay_PISP/?pispId=«pispId»&amount=«amount»&description=«description»&date=«date»&status=«status»&chave=' . $this->secret_key );
	
			$this->init_form_fields();
			$this->init_settings();


			$this->set_plugin_title( $this->get_option( 'title' ) );
			$this->set_plugin_description( $this->get_option( 'description' ) );
			$this->nif = $this->get_nif();
			$this->title = $this->get_option('title');
			$this->only_above    = $this->get_option( 'only_above' );
			$this->only_bellow   = $this->get_option( 'only_bellow' );

			if ( get_site_option( 'pisp_db_version' ) !== $this->database_version ) {
				$this->handle_database();
			}			
			//add_action( 'wp_enqueue_scripts', 'enqueue_newtab_script' );
			//function enqueue_newtab_script() {
			//	if(is_checkout()) {
				  wp_enqueue_script( 'my-plugin-script', plugins_url( 'newtab.js', __FILE__ ), array( 'jquery' ), date("h:i:s") );
				  wp_localize_script('my-plugin-script', 'my_plugin_data', array('callback_url' => get_site_url().'/wc-api/WC_Lusopay_PISP/'));
			//	}
			//  }
			/*add_action('wp_ajax_get_redirect_link', array($this,'get_redirect_link'));
			add_action('wp_ajax_nopriv_get_redirect_link',array($this, 'get_redirect_link'));*/
			// Customer Emails.
			add_action( 'woocommerce_email_before_order_table', array(
				$this,
				'email_instructions_lusopay_pisp',
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
			$table_name      = $wpdb->prefix . 'lusopaypisp';
			dbDelta( "CREATE TABLE IF NOT EXISTS `" . $table_name . "` (
				id int NOT NULL AUTO_INCREMENT,
				order_id varchar(10) NOT NULL,
				pispId varchar(50) NOT NULL,
				link varchar(1024),
				PRIMARY KEY(id)) $charset_collate;");		
			update_option( 'pisp_db_version', $this->database_version );
			
		}
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'lusopaygateway'),
					'type'=> 'checkbox',
					'label'   => __( 'Enable Simplified Transfer (By LUSOPAY)', 'lusopaygateway' ),
					'default' => 'no',
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
					'default' => __('Simplified Transfer (by LUSOPAY)', 'lusopaygateway'),
				),
				'description' => array(
					'title' => __('Descrição','lusopaygateway'),
					'type' => 'textarea',
					'description' => __( 'This controls the description that customer sees when doing checkout.', 'lusopaygateway' ),
					'default' => __('Pay with bank transfer without having to input the IBAN of the recipient. By indicating your payment our shop will redirect you to your bank website where you can confirm your transaction data. Extremely fast, easy and with the security of your bank!', 'lusopaygateway'),
					'css' => 'readonly',
				),
			);
		}

		/**
		 * Admin Plugin Configurations Form
		 */
		public function admin_options() {
			include 'views/html-admin-page-pisp.php';
		}

		/**
		 * Just above/bellow certain amounts
		 *
		 * @param array $available_gateways Woocommerce Available Gateways.
		 *
		 * @return mixed
		 */
		function disable_only_above_or_below( $available_gateways ) {
			global $woocommerce;
      		if (isset($available_gateways[$this->id])) {
        		if (@floatval($available_gateways[$this->id]->only_above) > 0) {
					if ($woocommerce->cart) {
						if ($woocommerce->cart->total > 0) {
							if ( $woocommerce->cart->total < @floatval($available_gateways[$this->id]->only_above) ) {
								unset($available_gateways[$this->id]);
				  			}
						} 
					}
        		} 
        		if (@floatval($available_gateways[$this->id]->only_bellow) > 0) {
					if ($woocommerce->cart) {
						if ( $woocommerce->cart->total > @floatval($available_gateways[$this->id]->only_bellow) ) {
							unset($available_gateways[$this->id]);
						  }
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
		function email_instructions_lusopay_pisp( $order ) {
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
		


		/**
		 * Process it
		 */
		public function process_payment($order_id) {
			
		
			global $woocommerce;
			global $wpdb;
		
			$order = new WC_Order_Lusopay($order_id);
			$response = $this->sendPISPRequest($this->chave, $order->lp_order_get_total(), $order->get_id());
			$obj = json_decode($response, true);
			$link = $obj['confirmLink'];
		
			if (strpos($link, 'https') === 0 && strlen($link) > 6) {
				$this->pispId = $obj['id'];
				$order = new WC_Order_Lusopay($order_id);
				$table_name = $wpdb->prefix . 'lusopaypisp';
				$data = array(
					'order_id' => $order->get_id(),
					'pispId' => $this->pispId,
				);
				$wpdb->insert($table_name, $data);
		
				// Mark as pending.
				$order->update_status('pending', __('Waiting for payment Simplified Transfer.', 'lusopaygateway'));
		
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
			
					throw new Exception( __( $response, 'woo' ) );
				
			}
		
		}
	


		function sendPISPRequest($chave, $order_value, $id) {
			$order_value = sprintf( '%01.2f', $order_value );
			$description = $id;
			$currentLang = substr(get_bloginfo('language'),0 , 2);
			$currency = trim( get_woocommerce_currency() );	
			$lusopay_url = '';
			if ($this->nif === '999999999') {
				$lusopay_url = 'http://185.15.20.221:8080/web_dev/run/PISP/'.$chave.'/'.$order_value.'/'.$currency.'/'.$description.'/'.$currentLang;
			} else {
				$lusopay_url = 'https://app.lusopay.com:8443/web/run/PISP/'.$chave.'/'.$order_value.'/'.$currency.'/'.$description.'/'.$currentLang;
			}
			$headers = array(
				'RETURNTRANSFER' => 'true',
				'MAXREDIRS' => 10,
				'FOLLOWLOCATION' => true,
			);
			$args = array(
				'headers' => $headers,
				'timeout' => 30,
			);
			//error_log($lusopay_url);
			$response = wp_remote_post($lusopay_url, $args);

			$statusCode = $response['response']['code'];

			if ($statusCode == 404) {

				if ($this->nif === '999999999') {
					$lusopay_url = 'http://185.15.20.221:8080/web_dev/run/PISP/'.$chave.'/'.$order_value.'/'.$currency.'/'.$description.'/'.$currentLang.'/null';
				} else {
					$lusopay_url = 'https://app.lusopay.com:8443/web/run/PISP/'.$chave.'/'.$order_value.'/'.$currency.'/'.$description.'/'.$currentLang.'/null';
				}
				$headers = array(
					'RETURNTRANSFER' => 'true',
					'MAXREDIRS' => 10,
					'FOLLOWLOCATION' => true,
				);
				$args = array(
					'headers' => $headers,
					'timeout' => 30,
				);
				//error_log($lusopay_url);
				$response = wp_remote_post($lusopay_url, $args);

				$statusCode = $response['response']['code'];

			}
			return $response['body'];
		}

		/*function get_redirect_link(){
			error_log('TEST');
			global $wpdb;
			$table_name = $wpdb->prefix . 'lusopaypisp';
			$response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE pispId = %d", $this->pispId));
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
			$table_name = $wpdb->prefix . 'lusopaypisp';
			$pisp_id = filter_input(INPUT_GET, 'pispId');
			$result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE pispId = %s", $pisp_id ) );
			$order_id = $result->order_id;
			$pisp_id = $result->pispId;
			$chave = trim( filter_input( INPUT_GET, 'chave' ) );
			if(trim( $this->secret_key ) === $chave && !empty($chave)){
				if ($result){
					if(!empty($order_id) && !empty($pisp_id) && ! is_null(filter_input(INPUT_GET, 'pispId')) && ! is_null(filter_input(INPUT_GET, 'status'))){
						if ( $this->debug ) {
							$uri = filter_input( INPUT_SERVER, 'REQUEST_URI' );
							$this->log->add( $this->id, '- Callback (' . $uri . ') with all arguments from ' . $uri );
						}
						$order = new WC_Order_Lusopay($order_id);
						//error_log($pisp_id);
						//error_log(filter_input(INPUT_GET, 'pispId'));
						if ( $pisp_id === filter_input(INPUT_GET, 'pispId')){
							if(strtolower(filter_input(INPUT_GET, 'status')) === 'failed'){
								//Transaction Failed
								echo $order->get_checkout_payment_url();
							}elseif(strtolower(filter_input(INPUT_GET, 'status')) === 'completed'){
								//Completed
								$order -> add_order_note('Pagamento Realizado');
								$order->update_status( $order->has_downloadable_item() ? 'processing' : 'processing', __( 'Payment received by Simplified Transfer ', 'lusopaygateway' ));
								WC()->cart->empty_cart();
								echo $this->get_return_url($order);
							}else{
								//Pending
								$order->add_order_note( 'Pagamento em processamento: ' . filter_input(INPUT_GET, 'status') );
								$order -> update_status('on-hold', __( 'Waiting for payment confirmation Simplified Transfer.', 'lusopaygateway' ) );
								WC()->cart->empty_cart();
								echo $this->get_return_url($order);
							}
						}else{
							echo "Simplified Transfer Id not send in POST!";
						}
					}else{
						echo http_response_code(418);
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