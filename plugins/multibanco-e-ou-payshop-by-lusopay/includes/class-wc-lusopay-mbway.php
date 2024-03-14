<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
* Lusopay - MB Way
*
* @since 2.0.0
*/

if ( ! class_exists( 'WC_Lusopay_MBWAY' ) ) {

	/**
	 * Class WC_Lusopay_MBWAY
	 */
	class WC_Lusopay_MBWAY extends WC_Payment_Gateway {

		/**
		 * Lusopay Database Version
		 *
		 * @var string
		 */
		public $database_version = '1.0';

		/**
		 * Lusopay Secret Key
		 *
		 * @var string
		 */
		public $secret_key = '';

		/**
		 * IPN Url for Reference Payment
		 *
		 * @var string
		 */
		public $notify_url = '';

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
		 * Variable to store if user has WPML module installed
		 *
		 * @var bool
		 */
		public $has_wpml = false;

		/**
		 * User ClientGuid
		 *
		 * @var string
		 */
		public $chave = '';

		/**
		 * User vatNumber
		 *
		 * @var string
		 */
		public $nif = '';

		/**
		 * Only for portuguese Clients?
		 *
		 * @var string
		 */
		public $only_portugal = 'no';

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
		 * When to change the products stocks
		 *
		 * @var string
		 */
		public $stock_when = '';

		/**
		 * Send throw email generate reference
		 * 
		 * @var bool
		 */
		public $send_email = false;
		/**
		 * IP Address of store
		 * 
		 * @var string
		 */
		public $clientIPAddress = '';

		/**
		 * WC_Lusopay_MBWAY constructor.
		 */
		public function __construct() {

			$this->integration = new WC_Lusopay_Integration;

			$this->set_plugin_id( 'lusopay_mbway' );
			$this->set_plugin_icon( plugins_url( '../imagens/MB_Way_logo_150x64px.jpg', __FILE__ ) );
			$this->set_plugin_has_fields( false );
			$this->set_plugin_method_title( __( 'MB Way (LUSOPAY)', 'lusopaygateway' ) );
			$this->secret_key = $this->get_chave_anti_phishing();

			$this->debug = ( 'yes' === $this->get_debug() ? true : false );
			if ( $this->debug ) {
				$this->log = version_compare( WC_VERSION, '3.0', '>=' ) ? wc_get_logger() : new WC_Logger();
			}

			$this->upgrade();

			$this->notify_url = ( '' === get_option( 'permalink_structure' ) ) ? home_url( '/?wc-api=WC_Lusopay_MBWAY&descricao=«descricao»&statuscode=«statuscode»&data=«data»&valor=«valor»&chave=' . $this->secret_key ) : home_url( '/wc-api/WC_Lusopay_MBWAY/?descricao=«descricao»&statuscode=«statuscode»&data=«data»&valor=«valor»&chave=' . $this->secret_key );
			$this->clientIPAddress = getHostByName(getHostName());

			// WPML?
			$this->has_wpml = function_exists( 'icl_object_id' ) && function_exists( 'icl_register_string' );

			// Plugin options and settings.
			$this->init_form_fields();
			$this->init_settings();

			// User settings.
			$this->set_plugin_title( $this->get_option( 'title' ) );
			$this->set_plugin_description( $this->get_option( 'description' ) );
			$this->chave         = $this->get_chave();
			$this->nif           = $this->get_nif();
			$this->only_portugal = $this->get_option( 'only_portugal' );
			$this->only_above    = $this->get_option( 'only_above' );
			$this->only_bellow   = $this->get_option( 'only_bellow' );
			$this->stock_when    = $this->get_option( 'stock_when' );
			$this->send_email    = $this->get_option( 'send_email' );

			if ( get_site_option( 'mbway_db_version' ) !== $this->database_version ) {
				$this->handle_database();
			}

			// Actions and filters.
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
				$this,
				'process_admin_options',
			) );

			if ( $this->has_wpml ) {
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
					$this,
					'register_wpml_strings',
				) );
			}

			add_action( 'woocommerce_thankyou_lusopay_mbway', array( &$this, 'thankyou' ) );
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'disable_unless_portugal' ) );
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'disable_only_above_or_below' ) );
      
      
      //ADD in 2020-01-08
      add_action('woocommerce_order_details_after_order_table', array( $this, 'order_details_after_order_table' ), 20 );
      
      
      
      // APG SMS Notifications Integration.
			add_filter( 'apg_sms_message', array( $this, 'sms_instructions_apg' ), 10, 2 );

			// Customer Emails.
			add_action( 'woocommerce_email_before_order_table', array(
				$this,
				'email_instructions_lusopaygateway',
			), 10, 2 );

			// Payment listener/API hook.
			add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( &$this, 'callback' ) );

		}

		/**
		 * Get clientGUID
		 */
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
		 * Get chave anti-phishing
		 */
		public function get_chave_anti_phishing() {
			return $this->integration->get_chave_anti_phishing();
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
		 * Get Customer Billing Country
		 *
		 * @return string
		 */
		function get_customer_billing_country() {
			if ( version_compare( WC_VERSION, '3.0', '>=' ) ) {
				return trim( WC()->customer->get_billing_country() );
			} else {
				return trim( WC()->customer->get_country() );
			}
		}

		/**
		 * Upgrades (if needed)
		 */
		function upgrade() {
    }
    
    /**
    *
    * View Order detail payment reference.
    */
    function order_details_after_order_table( $order ) {
      if ( is_wc_endpoint_url( 'view-order' ) ) {
        $this->thankyou( $order->get_id() );
      }
    }

		/**
		 * Create or Update Needed Tables
		 */
		function handle_database() {
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$charset_collate = $wpdb->get_charset_collate();
			$table_name      = $wpdb->prefix . 'lusopaymbway';

      dbDelta( "CREATE TABLE IF NOT EXISTS `" . $table_name . "` (
        id int NOT NULL AUTO_INCREMENT,
        order_id varchar(10) NOT NULL,
        phoneNumber varchar(50) NOT NULL,
        value varchar(10),
        merchantOperationId varchar(255),
        statusCode varchar(10),
        statusMessage varchar(255),
        dataPedido datetime,
        token varchar(255),
        dataPagamento datetime,
        cancelado tinyint(1) DEFAULT 0,
        PRIMARY KEY(id)) $charset_collate;");
			
			update_option( 'mbway_db_version', $this->database_version );
		}


		/**
		 * WPML compatibility
		 */
		function register_wpml_strings() {
			$to_register = array(
				'title',
				'description',
			);
			foreach ( $to_register as $string ) {
				icl_register_string( $this->id, $this->id . '_' . $string, $this->settings[ $string ] );
			}
		}

		/**
		 * Initialise Gateway Settings Form Fields
		 * 'setting-name' => array(
		 *        'title' => __( 'Title for setting', 'woothemes' ),
		 *        'type' => 'checkbox|text|textarea',
		 *        'label' => __( 'Label for checkbox setting', 'woothemes' ),
		 *        'description' => __( 'Description for setting' ),
		 *        'default' => 'default value'
		 *    ),
		 */
		function init_form_fields() {

			$this->form_fields = array(
				'enabled'       => array(
					'title'   => __( 'Enable/Disable', 'lusopaygateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable MB Way (By LUSOPAY)', 'lusopaygateway' ),
					'default' => 'no',
				),
				'only_portugal' => array(
					'title'   => __( 'Only for Portugal clients?', 'lusopaygateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Activate only for customers that have a Portuguese address?', 'lusopaygateway' ),
					'default' => 'no',
				),
				'only_above'    => array(
					'title'       => __( 'Only for orders more than', 'lusopaygateway' ),
					'type'        => 'number',
					'description' => __( 'Activate only for orders above € x (exclusive). To use MB Way payment the minimum order is of € 0.15.',
							'lusopaygateway' ) . ' <br/> ' . __( 'MB Way only accepts payments below € 99.999,99; (inclusive). You can use this option to limit more than the standard values.', 'lusopaygateway' ),
					'default'     => '',
				),
				'only_bellow'   => array(
					'title'       => __( 'Only for orders below', 'lusopaygateway' ),
					'type'        => 'number',
					'description' => __( 'Activate only for orders below € x (exclusive). Leave blank (or zero) to activate for any order value.', 'lusopaygateway' ) . ' <br/> ' . __( 'MB Way only accepts payments below € 99.999,99 (inclusive). You can use thes option to limit more than the standard values.', 'lusopaygateway' ),
					'default'     => '',
				),
				'stock_when'    => array(
					'title'       => __( 'Change stock', 'lusopaygateway' ),
					'type'        => 'select',
					'description' => __( 'Choose when to change stock.', 'lusopaygateway' ),
					'default'     => '',
					'options'     => array(
						''      => __( 'when the order is paid (demands callback feature to be active)', 'lusopaygateway' ),
						'order' => __( 'when the order is done (before payment)', 'lusopaygateway' ),
					),
				),
				'title'         => array(
					'title'       => __( 'Title', 'lusopaygateway' ),
					'type'        => 'text',
					'description' => __( 'This controls the title that customer sees when doing checkout.', 'lusopaygateway' ),
					'default'     => __( 'MB Way (By LUSOPAY)', 'lusopaygateway' ),
				),
				'description'   => array(
					'title'       => __( 'Description', 'lusopaygateway' ),
					'type'        => 'textarea',
					'description' => __( 'This controls the description that customer sees when doing checkout.', 'lusopaygateway' ),
					'default'     => __( 'Pagamento por MB Way App (apenas disponível para clientes com conta bancária Portuguesa).', 'lusopaygateway' ),
				),
				'send_email'          => array(
					'title'       => __( 'Send email of the notifications', 'lusopaygateway' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable send email', 'lusopaygateway' ),
					'description' => __( 'If you want receive the email of the payment/canceled notifications, check, please.', 'lusopaygateway' ),
					'default'     => 'no',
				),
			);

		}

		/**
		 * Admin Plugin Configurations Form
		 */
		public function admin_options() {
			include 'views/html-admin-page-mbway.php';
		}

		/**
		 * Thank you page
		 *
		 * @param int $order_id Order Id.
		 */
		function thankyou( $order_id ) {
			echo $this->get_template_frontEnd_order_details($order_id);
		}

		/**
		 * Email instructions
		 *
		 * @param mixed $order Order Object.
		 */
		function email_instructions_lusopaygateway( $order ) {
			global $wpdb;
			$order_id = version_compare( WC_VERSION, '3.0', '>=' ) ? $order->get_id() : $order->id;
			$order    = new WC_Order_Lusopay( $order_id );

			if ( $order->lp_order_get_payment_method() !== $this->id ) {
				return;
			}
			switch ( $order->lp_order_get_status() ) {
				case 'on-hold':
        case 'pending':
					echo $this->get_template_email_mbway_order_details( $order->lp_order_get_id() );
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

		function payment_fields() {
			if ( $description = $this->get_description() ) {
			  echo wpautop( wptexturize( $description ) );
			}
	  
			$this->lp_mbway_form();
	  
		}
	  
		function lp_mbway_form() {
			$user = wp_get_current_user();
			if ( $user->ID ) {
			  $user_phone = get_user_meta( $user->ID, 'billing_phone', true );
			  // $user_phone = $user_phone ? $user_phone : $user->user_phone;
			}
			?>
			<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-mbway-form" class="wc-mbway-form wc-payment-form" style="background:transparent;">
			  <p class="form-row form-row-wide">
				<label for="mbway_phone"><?php esc_html_e( 'Phone number registered on MB WAY', 'lusopaygateway' ); ?></label>
				<input type="tel" id="mbway_phone" autocorrect="off" spellcheck="false" name="mbway_phone" class="input-text" aria-label="<?php _e('Phone number registered on MB WAY', 'lusopaygateway' ); ?>" placeholder="<?php _e( 'If different of billing phone', 'lusopaygateway' ); ?>" aria-placeholder="" aria-invalid="false" value="<?php echo $user_phone; ?>" />
				<span class="help-text"><small><?php _e( 'Fill in, if different from the billing phone.', 'lusopaygateway' ); ?></small></span>
			  </p>
			  <div class="clear"></div>
			</fieldset>
			<?php
		  }

		  /**
		   * Get Remote IP Address
		   * 
		   * @return string
		   */
		  function get_IP_Address_remote(){
			$clientIP = '';

			$clientIP = $_SERVER['REMOTE_ADDR'];
			/*
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$clientIP = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$clientIP = $_SERVER['REMOTE_ADDR'];
			}
			*/
			return $clientIP;
		  }


		/**
		 * Process it
		 *
		 * @param int $order_id Order Id.
		 *
		 * @return array
		 */
		function process_payment( $order_id ) {
			$sendEmail = 'false';
			$order = new WC_Order_Lusopay( $order_id );
			//$currency = trim( get_woocommerce_currency() );
			$billing_phone = $order->lp_order_get_billing_phone();
			$phoneNumber = isset( $_POST['mbway_phone'] ) && !empty( $_POST['mbway_phone'] ) ? $_POST['mbway_phone'] : $billing_phone;
			
			//Caso insira o número com espaços
			$phoneNumber = str_replace(' ', '', trim($phoneNumber));
			//Caso insira com o indicativo
			if (strlen($phoneNumber) > 9 && substr($phoneNumber, 0, 4) == '+351') {
				$phoneNumber = substr($phoneNumber, 4, strlen($phoneNumber));
			}
			//Caso o primeiro digito não seja 9
			if (substr($phoneNumber, 0, 1) != '9') {
				return array('result' => 'fail');
			}
			//Caso o tamanho seja diferente de 9
			if (strlen($phoneNumber) != 9) {
				return array('result' => 'fail');
			}
			

			if ($this->send_email === 'yes') {
				$sendEmail = 'true';
			}

			$response = $this->sendMBWayRequest( $this->chave, $this->nif, $order->lp_order_get_id(), $order->lp_order_get_total(), $phoneNumber, $sendEmail );
			$mess=$response[3];
			$status=$response[2];
			if($status=='000') {
			// Mark as on-hold.
			$order->update_status( 'on-hold', __( 'Waiting for payment by MB Way.', 'lusopaygateway' ) );

			// Reduce stock levels.
			if ( 'order' === $this->stock_when ) {
				$order->lp_order_reduce_order_stock();
			}
			// Remove cart.
			WC()->cart->empty_cart();

			// Return thankyou redirect.
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
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
			throw new Exception( __( $mess, 'woo' ) );
			
		}

	}

		/**
		 * Just for Portugal
		 *
		 * @param array $available_gateways Woocommerce Available Gateways.
		 *
		 * @return mixed
		 */
		function disable_unless_portugal( $available_gateways ) {
			if ( isset( $available_gateways[ $this->id ] ) ) {
				if ( $available_gateways[ $this->id ]->only_portugal == 'yes' && WC()->customer && $this->get_customer_billing_country() !== 'PT' ) {
					unset( $available_gateways[ $this->id ] );
				}
			}

			return $available_gateways;
		}

		/**
		 * Just above/below certain amounts
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
		 * Check if id exist in table
		 *
		 * @param mixed $order_id Order Id.
		 * @param mixed $valor Value.
		 *
		 * @return int
		 */
		function checkIfExistOrder( $order_id, $valor ) {
			global $wpdb;
			$id = 0;
			$table_name = $wpdb->prefix . 'lusopaymbway';

			$result   = $wpdb->get_results("SELECT * FROM ". $table_name . " WHERE order_id= " . $order_id . " AND value=" . $valor . " AND dataPagamento is null and cancelado = 0");// db call ok; no-cache ok.
			foreach ( $result as $row ) {
				$id = $row->id;
			}
			
			return $id;
    }

    /**
     * Check If Payment was canceled
     * 
     * @param mixed $id 
     */
    function checkIfPaymentCanceled($id) {

      global $wpdb;

      $table_name = $wpdb->prefix . "lusopaymbway";

      $result = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE id=" . $id);

      foreach ($result as $row) {
        $cancelado = $row->cancelado;
      }

      return $cancelado;
    }

    /**
     * Update Order Lusopay Status as paid
     * 
     * @param mixed $id lusopaymbway Table Id
     * @param mixed $date Payment date
     */
    function updateStatus($id, $data) {

      global $wpdb;

      $set = $data;

      $table_name = $wpdb->prefix . "lusopaymbway";


      $wpdb->update($table_name, array('dataPagamento' => $set), array('id' => $id));
    }

    /**
     * Update Order LUSOPAY Status as canceled or rejected
     * 
     * @param mixed $id lusopaymbway Table Id
     * @param mixed $newStatusCode código do estado
     */
    function updateStatusCancelReject($id, $newStatusCode) {

      global $wpdb;

      $set = 1;
      $set2 = $newStatusCode;

      $table_name = $wpdb->prefix . "lusopaymbway";


      $wpdb->update($table_name, array('cancelado' => $set, 'statusCode' => $set2), array('id' => $id));
    }


		/**
		 * Format Number
		 *
		 * @param mixed $number Value.
		 *
		 * @return string
		 */
		function format_number( $number ) {
			$verify_sep_decimal = number_format( 99, 2 );

			$valor_tmp = $number;

			$sep_decimal = substr( $verify_sep_decimal, 2, 1 );

			$has_sep_decimal = true;

			$val = ( strlen( $valor_tmp ) - 1 );

			for ( $i = $val; 0 !== $i; $i -- ) {
				if ( substr( $valor_tmp, $i, 1 ) === '.' || substr( $valor_tmp, $i, 1 ) === ',' ) {
					$has_sep_decimal = true;
					$valor_tmp       = trim( substr( $valor_tmp, 0, $i ) ) . '@' . trim( substr( $valor_tmp, 1 + $i ) );
					break;
				}
			}

			if ( true !== $has_sep_decimal ) {
				$valor_tmp = number_format( $valor_tmp, 2 );

				$val = ( strlen( $valor_tmp ) - 1 );

				for ( $i = $val; 0 !== $i; $i -- ) {
					if ( substr( $valor_tmp, $i, 1 ) === '.' || substr( $valor_tmp, $i, 1 ) === ',' ) {
						$valor_tmp = trim( substr( $valor_tmp, 0, $i ) ) . '@' . trim( substr( $valor_tmp, 1 + $i ) );
						break;
					}
				}
			}

			$tam = strlen( $valor_tmp ) - 1;
			for ( $i = 1; $i !== $tam; $i ++ ) {
				if ( '.' === substr( $valor_tmp, $i, 1 ) || ',' === substr( $valor_tmp, $i, 1 ) || ' ' === substr( $valor_tmp, $i, 1 ) ) {
					$valor_tmp = trim( substr( $valor_tmp, 0, $i ) ) . trim( substr( $valor_tmp, 1 + $i ) );
					break;
				}
			}

			if ( strlen( strstr( $valor_tmp, '@' ) ) > 0 ) {
				$valor_tmp = trim( substr( $valor_tmp, 0, strpos( $valor_tmp, '@' ) ) ) . trim( $sep_decimal ) . trim( substr( $valor_tmp, strpos( $valor_tmp, '@' ) + 1 ) );
			}

			return $valor_tmp;
		}

		/**
		 * Lusopay Call to generate reference
		 *
		 * @param string $ent_chave Lusopay GUID.
		 * @param string $ent_nif User Vat Number.
		 * @param int    $order_id Order ID.
		 * @param float  $order_value Order Value.
		 *
		 * @return bool
		 */
		function sendMBWayRequest($ent_chave, $ent_nif, $order_id, $order_value, $phoneNumber, $send_email) {

			//$order_id ="0000".$order_id;

			//$order_id ="0000"."123456";

			$order_value = sprintf("%01.2f", $order_value);

			$order_value = $this->format_number($order_value);
			

			//Apenas sao considerados os 4 caracteres mais a direita do order_id
			//$order_id = substr($order_id, (strlen($order_id) - 4), strlen($order_id));

			if (!is_null($ent_chave) && !is_null($ent_nif) && !is_null($order_id) && !is_null($order_value) && !is_null($phoneNumber) && !is_null($send_email)) {

				$soapUrl = '';
				if ($ent_nif === '999999999') {
				  $soapUrl = 'https://services.lusopay.com/PaymentServices_test/PaymentServices.svc?wsdl';
				} else {
				  $soapUrl = 'https://services.lusopay.com/PaymentServices/PaymentServices.svc?wsdl';
				}


				$xml_post_string = '<?xml version="1.0" encoding="utf-8"
?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/" xmlns:pay="http://schemas.datacontract.org/2004/07/PaymentServices">
<soapenv:Body>
	<tem:sendMBWayRequest>
		<tem:clientGuid>' . $ent_chave . '</tem:clientGuid>
		<tem:vatNumber>' . $ent_nif . '</tem:vatNumber>
		<tem:cellPhoneNumber>' . $phoneNumber . '</tem:cellPhoneNumber>
		<tem:amount>' . $order_value . '</tem:amount>
		<tem:externalReference>' . $order_id . '</tem:externalReference>
		<tem:sendEmail>'. $send_email .'</tem:sendEmail>
	</tem:sendMBWayRequest>
</soapenv:Body>

</soapenv:Envelope>';

				$headers = array(
					'Host'           => 'services.lusopay.com',
					'Content-type'   => 'text/xml;charset="utf-8"',
					'Accept'         => 'text/xml',
					'Cache-Control'  => 'no-cache',
					'Pragma'         => 'no-cache',
					'SOAPAction'     => 'http://tempuri.org/IPaymentServices/sendMBWayRequest',
					'Content-length' => strlen( $xml_post_string ),
				);

				$args     = array(
				  'headers' => $headers,
				  'body'    => $xml_post_string,
				  'timeout' => 30,
				);
				$response = wp_remote_post( $soapUrl, $args );
			

				/*
				if (!function_exists('write_log')) {

					function write_log($log) {
						if (true === WP_DEBUG) {
							if (is_array($log) || is_object($log)) {
								error_log(print_r($log, true));
							} else {
								error_log($log);
							}
						}
					}
				
				}
				
				write_log('THIS IS THE START OF MY CUSTOM DEBUG MB WAY');
				//i can log data like objects
				write_log($response);
				*/
				
				if ( ! is_wp_error( $response )) {

					$response = $response['body'];




					$modified_html = preg_replace('/<[a-z]+\s*(.*?)>/si', '<$1>', $response);


					  $merchantOperationID = "/<a:merchantOperationID>(.*?)<\/a:merchantOperationID>/s";
					$statusCode = "/<a:statusCode>(.*?)<\/a:statusCode>/s";
					$statusMessage = "/<a:statusMessage>(.*?)<\/a:statusMessage>/s";
					$timeStamp = "/<a:timeStamp>(.*?)<\/a:timeStamp>/s";
					$token = "/<a:token>(.*?)<\/a:token>/s";
				

					preg_match($statusCode, $response, $statusCode_value);
					preg_match($statusMessage, $response, $statusMessage_value);
					$erro[2]=$statusCode_value[1];
					$erro[3]=$statusMessage_value[1];

					if ($statusCode_value[1] === '000') {
						
						if (preg_match($merchantOperationID, $response, $merchantOperationID_value) &&
						preg_match($token, $response, $token_value) && 
						preg_match($statusCode, $response, $statusCode_value) &&
						preg_match($statusMessage, $response, $statusMessage_value) &&
						preg_match($timeStamp, $response, $timeStamp_value)
						) {
						
						$resp[1] = $merchantOperationID_value[1];
						$resp[2] = $statusCode_value[1];
						$resp[3] = $statusMessage_value[1];
						$resp[4] = $timeStamp_value[1];
						$resp[5] = $token_value[1];
						global $wpdb;

						$table_name = $wpdb->prefix . "lusopaymbway";

						$wpdb->insert($table_name, array(
							'order_id' => $order_id, 
							'value' => $order_value,
							'phoneNumber' => $phoneNumber,
							'merchantOperationId' => $resp[1],
							'statusCode' => $resp[2],
							'statusMessage' => $resp[3],
							'dataPedido' => $resp[4],
							'token' => $resp[5]));

						} else {
							
							$message = "/<a:message>(.*?)<\/a:message>/s";
							if (preg_match($message, $response, $message_value) && preg_match($statusMessage, $response, $statusMessage_value)) {
								echo $message_value[1] . "/n" . $statusMessage_value[1];
							
							}
						} 

					} else {
						$teste='1';
						if (preg_match($statusCode, $response, $statusCode_value) &&
						preg_match($statusMessage, $response, $statusMessage_value) &&
						preg_match($timeStamp, $response, $timeStamp_value)
						) {
							
						$resp[2] = $statusCode_value[1];
						$resp[3] = $statusMessage_value[1];
						$resp[4] = $timeStamp_value[1];
						

						global $wpdb;

						$table_name = $wpdb->prefix . "lusopaymbway";

						$wpdb->insert($table_name, array(
							'order_id' => $order_id, 
							'value' => $order_value,
							'phoneNumber' => $phoneNumber,
							'statusCode' => $resp[2],
							'statusMessage' => $resp[3],
							'dataPedido' => $resp[4]));

						} else {
							$teste='5';
							$message = "/<a:message>(.*?)<\/a:message>/s";
							if (preg_match($message, $response, $message_value)) {
								
								$erro[1]=$message_value[1];
							}
							
						} 

					}
				} else{
					return 'Erro ao gerar pagamento ';
					
				}
				
		
				return $erro;
			
			} else return 'Erro ao gerar pagamento';
			
}
		
		/**
		 * Get MB Way Details
		 * 
		 * @param int $order_id Order Id.
		 * 
		 * @return array
		 * 
		 */
		function get_mbway_request($order_id) {
			global $wpdb;
			$phoneNumber = '';
			$order_value = 0;
			$statusCode = '';

			$table_name = $wpdb->prefix . "lusopaymbway";

            $result = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE order_id = '" . $order_id . "'");

            foreach ($result as $row) {
                $phoneNumber = $row->phoneNumber;
				$order_value = $row->value;
				$statusCode = $row->statusCode;
			}
			if ($result != null) {
				return array('phoneNumber' => $phoneNumber, 'order_value' => $order_value, 'statusCode' => $statusCode);
			} else {
				return null;
			}
			
		}

		
		/**
		 * Get Template MB Way Request
		 *
		 * @param int $order_id Order Id.
		 *
		 * @return string
		 */
    function get_template_frontEnd_order_details($order_id) {
		$currency = trim( get_woocommerce_currency_symbol() );			
		$res = array();
		$res = $this->get_mbway_request($order_id);
		$tabela = '';
		if ($res != null) {

			$phoneNumber = $res['phoneNumber'];
			$order_value = $res['order_value'];
			$statusCode = $res['statusCode'];
			
		
		switch($statusCode){
			case '000':
			case '020':
			case '048':
			case '100':
				$tabela = '<div align="center">

        <table cellpadding="3" width="400px" cellspacing="0" style="margin-top: 10px;border: 1px solid #dcdcdc;width: 50%; background-color: white; color: black;" align="center">
        	<tr>
        		<td style="font-size: small; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #dcdcdc; background-color: #dcdcdc; color: black" colspan="3"><center>Pagamento por MB Way (by LUSOPAY)</center></td>
        	</tr>
        	<tr>
				<td rowspan="2"><div align="center"><img src="https://www.lusopay.com/App_Files/cms/documents/images/logo_lusopaymbway.png" alt=""/></div></td>
				<td style="font-size: small; font-weight:bold; text-align:left">Telemóvel:</td>
                <td style="font-size: small; text-align:left">'. $phoneNumber .'</td>
            </tr>
            <tr>
            	<td style="font-size: small; font-weight:bold; text-align:left">Valor:</td>
                <td style="font-size: small; text-align:left">' . number_format( $order_value, 2, ',', ' ' ). ' ' . $currency . '</td>
            </tr>
            <tr>
                <td style="font-size: x-small;border-top: 1px solid #dcdcdc; border-left: 0px; border-right: 0px; border-bottom: 0px; background-color: #dcdcdc; color: black" colspan="3"><center>Caso não efetuar o pagamento dentro de 5 minutos o seu pedido será cancelado. Caso não receba a notificação, verifique na aplicação MB Way se o pagamento está pendente.</center></td>
            </tr>
        </table></div>';
			break;
			case '113':
				$tabela = '<div align="center"><table cellpadding="3" width="400px" cellspacing="0" style="margin-top: 10px;border: 1px solid #dcdcdc;width: 50%; background-color: white; color: black;" align="center">
				<tr>
					<td style="font-size: small; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #dcdcdc; background-color: red; color: white" colspan="3"><center><b>ERRO - Pagamento por MB Way (by LUSOPAY)</b></center></td>
				</tr>
				<tr>
					<td rowspan="2"><div align="center"><img src="https://www.lusopay.com/App_Files/cms/documents/images/logo_lusopaymbway.png" alt=""/></div></td>
					<td style="font-size: small; font-weight:bold; text-align:left">Telemóvel:</td>
					<td style="font-size: small; text-align:left">'. $phoneNumber .'</td>
				</tr>
				<tr>
					<td style="font-size: small; font-weight:bold; text-align:left">Valor:</td>
					<td style="font-size: small; text-align:left">' . number_format( $order_value, 2, ',', ' ' ). ' ' . $currency . '</td>
				</tr>
				<tr>
					<td style="font-size: small;border-top: 1px solid #dcdcdc; border-left: 0px; border-right: 0px; border-bottom: 0px; background-color: #dcdcdc; color: black" colspan="3"><center>Este número de telemóvel não está associado ao MB Way!</center></td>
				</tr>
			</table></div>';
			break;
			default:
			$tabela = '<div align="center"><table cellpadding="3" width="400px" cellspacing="0" style="margin-top: 10px;border: 1px solid #dcdcdc;width: 50%; background-color: white; color: black;" align="center">
				<tr>
					<td style="font-size: small; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #dcdcdc; background-color: red; color: white" colspan="3"><center><b>ERRO - Pagamento por MB Way (by LUSOPAY)</b></center></td>
				</tr>
				<tr>
					<td style="font-size: small;border-top: 1px solid #dcdcdc; border-left: 0px; border-right: 0px; border-bottom: 0px; background-color: #dcdcdc; color: black" colspan="3"><center>De momento não é possível processar o seu pedido!</center></td>
				</tr>
			</table></div>';
			break;
		}

		}          
        return $tabela;
	}
	
	/**
	 * Get Template MB Way Request Admin
	 * 
	 * @param int $order_id Order Id.
	 * 
	 * @return string
	 * 
	 */
	function get_lp_template_mbway_order_details($order_id) {
		$currency = trim( get_woocommerce_currency_symbol() );			
		$res = array();
		$res = $this->get_mbway_request($order_id);
		$tabela = '';
		if ($res != null) {
			$phoneNumber = $res['phoneNumber'];
		$order_value = $res['order_value'];
		$statusCode = $res['statusCode'];
		
                    

        switch($statusCode){
			case '000':
			case '020':
			case '048':
			case '100':
				$tabela = '<div align="center">

        <table cellpadding="3" width="250px" cellspacing="0" style="margin-top: 10px;border: 1px solid #dcdcdc;width: 50%; background-color: white; color: black;" align="center">
        	<tr>
        		<td style="font-size: small; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #dcdcdc; background-color: #dcdcdc; color: black" colspan="3"><center>Pagamento por MB Way (by LUSOPAY)</center></td>
        	</tr>
        	<tr>
				<td rowspan="2"><div align="center"><img src="https://www.lusopay.com/App_Files/cms/documents/images/logo_lusopaymbway.png" alt=""/></div></td>
				<td style="font-size: small; font-weight:bold; text-align:left">Telemóvel:</td>
                <td style="font-size: small; text-align:left">'. $phoneNumber .'</td>
            </tr>
            <tr>
            	<td style="font-size: small; font-weight:bold; text-align:left">Valor:</td>
                <td style="font-size: small; text-align:left">' . number_format( $order_value, 2, ',', ' ' ). ' ' . $currency . '</td>
            </tr>
            <tr>
                <td style="font-size: x-small;border-top: 1px solid #dcdcdc; border-left: 0px; border-right: 0px; border-bottom: 0px; background-color: #dcdcdc; color: black" colspan="3"><center>Caso não efetuar o pagamento dentro de 5 minutos o seu pedido será cancelado. Caso não receba a notificação, verifique na aplicação MB Way se o pagamento está pendente.</center></td>
            </tr>
        </table></div>';
			break;
			case '113':
				$tabela = '<div align="center"><table cellpadding="3" width="250px" cellspacing="0" style="margin-top: 10px;border: 1px solid #dcdcdc;width: 50%; background-color: white; color: black;" align="center">
				<tr>
					<td style="font-size: small; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #dcdcdc; background-color: red; color: white" colspan="3"><center><b>ERRO - Pagamento por MB Way (by LUSOPAY)</b></center></td>
				</tr>
				<tr>
					<td rowspan="2"><div align="center"><img src="https://www.lusopay.com/App_Files/cms/documents/images/logo_lusopaymbway.png" alt=""/></div></td>
					<td style="font-size: small; font-weight:bold; text-align:left">Telemóvel:</td>
					<td style="font-size: small; text-align:left">'. $phoneNumber .'</td>
				</tr>
				<tr>
					<td style="font-size: small; font-weight:bold; text-align:left">Valor:</td>
					<td style="font-size: small; text-align:left">' . number_format( $order_value, 2, ',', ' ' ). ' ' . $currency . '</td>
				</tr>
				<tr>
					<td style="font-size: x-small;border-top: 1px solid #dcdcdc; border-left: 0px; border-right: 0px; border-bottom: 0px; background-color: #dcdcdc; color: black" colspan="3"><center>Este número de telemóvel não está associado ao MB Way!</center></td>
				</tr>
			</table></div>';
			break;
			default:
			$tabela = '<div align="center"><table cellpadding="3" width="250px" cellspacing="0" style="margin-top: 10px;border: 1px solid #dcdcdc;width: 50%; background-color: white; color: black;" align="center">
				<tr>
					<td style="font-size: small; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #dcdcdc; background-color: red; color: white" colspan="3"><center><b>ERRO - Pagamento por MB Way (by LUSOPAY)</b></center></td>
				</tr>
				<tr>
					<td style="font-size: x-small;border-top: 1px solid #dcdcdc; border-left: 0px; border-right: 0px; border-bottom: 0px; background-color: #dcdcdc; color: black" colspan="3"><center>De momento não é possível processar o seu pedido!</center></td>
				</tr>
			</table></div>';
			break;
		}

		}

		
                    
        return $tabela;
	}

	/**
		 * Get Template MB Way to email 
		 *
		 * @param int $order_id Order Id.
		 *
		 * @return string
		 */
		function get_template_email_mbway_order_details($order_id) {
			$currency = trim( get_woocommerce_currency_symbol() );			
			$res = array();
			$res = $this->get_mbway_request($order_id);
			$tabela = '';
			if ($res != null) {
				$phoneNumber = $res['phoneNumber'];
			$order_value = $res['order_value'];
			$statusCode = $res['statusCode'];
			
						
	
			switch($statusCode){
				case '000':
					$tabela = '<div align="center">
	
			<table cellpadding="3" width="100%" cellspacing="0" style="margin-top: 10px;border: 1px solid #dcdcdc;width: 50%; background-color: white; color: black;" align="center">
				<tr>
					<td style="font-size: small; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #dcdcdc; background-color: #dcdcdc; color: black" colspan="3"><center>Pagamento por MB Way (by LUSOPAY)</center></td>
				</tr>
				<tr>
					<td rowspan="2"><div align="center"><img src="https://www.lusopay.com/App_Files/cms/documents/images/logo_lusopaymbway.png" alt=""/></div></td>
					<td style="font-size: small; font-weight:bold; text-align:left">Telemóvel:</td>
					<td style="font-size: small; text-align:left">'. $phoneNumber .'</td>
				</tr>
				<tr>
					<td style="font-size: small; font-weight:bold; text-align:left">Valor:</td>
					<td style="font-size: small; text-align:left">' . number_format( $order_value, 2, ',', ' ' ). ' ' . $currency . '</td>
				</tr>
				<tr>
					<td style="font-size: x-small;border-top: 1px solid #dcdcdc; border-left: 0px; border-right: 0px; border-bottom: 0px; background-color: #dcdcdc; color: black" colspan="3"><center>Caso não efetuar o pagamento dentro de 5 minutos o seu pedido será cancelado. Caso não receba a notificação, verifique na aplicação MB Way se o pagamento está pendente.</center></td>
				</tr>
			</table></div>';
				break;
				case '113':
					$tabela = '<div align="center"><table cellpadding="3" width="100%" cellspacing="0" style="margin-top: 10px;border: 1px solid #dcdcdc;width: 50%; background-color: white; color: black;" align="center">
					<tr>
						<td style="font-size: small; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #dcdcdc; background-color: red; color: white" colspan="3"><center><b>ERRO - Pagamento por MB Way (by LUSOPAY)</b></center></td>
					</tr>
					<tr>
						<td rowspan="2"><div align="center"><img src="https://www.lusopay.com/App_Files/cms/documents/images/logo_lusopaymbway.png" alt=""/></div></td>
						<td style="font-size: small; font-weight:bold; text-align:left">Telemóvel:</td>
						<td style="font-size: small; text-align:left">'. $phoneNumber .'</td>
					</tr>
					<tr>
						<td style="font-size: small; font-weight:bold; text-align:left">Valor:</td>
						<td style="font-size: small; text-align:left">' . number_format( $order_value, 2, ',', ' ' ). ' ' . $currency . '</td>
					</tr>
					<tr>
						<td style="font-size: x-small;border-top: 1px solid #dcdcdc; border-left: 0px; border-right: 0px; border-bottom: 0px; background-color: #dcdcdc; color: black" colspan="3"><center>Este número de telemóvel não está associado ao MB Way!</center></td>
					</tr>
				</table></div>';
				break;
				default:
				$tabela = '<div align="center"><table cellpadding="3" width="100%" cellspacing="0" style="margin-top: 10px;border: 1px solid #dcdcdc;width: 50%; background-color: white; color: black;" align="center">
					<tr>
						<td style="font-size: small; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #dcdcdc; background-color: red; color: white" colspan="3"><center><b>ERRO - Pagamento por MB Way (by LUSOPAY)</b></center></td>
					</tr>
					<tr>
						<td style="font-size: x-small;border-top: 1px solid #dcdcdc; border-left: 0px; border-right: 0px; border-bottom: 0px; background-color: #dcdcdc; color: black" colspan="3"><center>De momento não é possível processar o seu pedido!</center></td>
					</tr>
				</table></div>';
				break;
			}

			}
			
						
			return $tabela;
		}

		
		/**
		 * Callback
		 */
		function callback() {
			// We must 1st check the situation and then process it and send email to the store owner in case of error.
			if ( ! is_null( filter_input( INPUT_GET, 'descricao' ) ) && ! is_null( filter_input( INPUT_GET, 'statuscode' ) ) && ( ( ! is_null( filter_input( INPUT_GET, 'data' ) ) && ! is_null( filter_input( INPUT_GET, 'valor' ) ) ) ) ) {
				if ( $this->debug ) {
					$uri = filter_input( INPUT_SERVER, 'REQUEST_URI' );
					$this->log->add( $this->id, '- Callback (' . $uri . ') with all arguments from ' . $uri );
				}
				$statuscode = trim ( filter_input( INPUT_GET, 'statuscode') );
                $data = trim ( filter_input( INPUT_GET, 'data') );
                $data_decode = iconv('ISO-8859-1', 'UTF-8', $data); //utf8_decode($data);
                $data_final = date("Y-m-d H:i:s", strtotime($data_decode));
                $orderMBWay = iconv('ISO-8859-1', 'UTF-8', trim( filter_input(INPUT_GET, 'descricao')));
				$valor       = filter_input( INPUT_GET, 'valor' );
				$val         = str_replace( ',', '.', $valor );
				$valor_final = number_format( $val, 2, '.', '' );
				$chave       = trim( filter_input( INPUT_GET, 'chave' ) );

				if ( trim( $this->secret_key ) === $chave ) {

					$id = $this->checkIfExistOrder($orderMBWay, $valor_final);

					if ($val >= 0.15) {
						$order = new WC_Order_Lusopay( $orderMBWay );
						if ($statuscode === '000') {

							if ( 0 !== $id ) {

								
		
								include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
								if ( ! is_plugin_active( 'order-status-emails-for-woocommerce/order-status-emails-for-woocommerce.php' ) ) {
									// Only if this plugin is not active.
									if ( 'pending' !== $order->lp_order_get_status() ) {
										$order->update_status( 'pending', __( 'Temporary status. Used to force an email on the next order status change.', 'lusopaygateway' ) );
									}
								}
								
								$order->update_status( $order->has_downloadable_item() ? 'processing' : 'processing', __( 'Payment received by MB Way.', 'lusopaygateway' ) );
							
								$this->updateStatus( $id, $data_final );
		
								echo 'true';
							} else {
								echo 'false';
							}

						} else {
							if ( 0 !== $id) {
								$cancelado = $this->checkIfPaymentCanceled($id);
                                //wp_die(var_dump($cancelado));
                                if ($cancelado == '0') {
                                    //wp_die($cancelado);
                                    $order->update_status('cancelled', __('Payment MB WAY canceled or rejected!', 'lusopaygateway'));
									$this->updateStatusCancelReject($id, $statuscode);
									echo 'true';
								} else {
									echo 'true';
								}
							} else {
								echo 'false';
							}
						}
					} else {
						echo 'false';
					}
				} else {
					echo 'false';
				}
			} else {
				echo 'false';
			}
			exit;
		}
	}
}
