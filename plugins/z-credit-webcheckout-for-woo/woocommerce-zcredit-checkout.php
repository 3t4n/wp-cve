<?php
/*
 * Plugin Name: Z-Credit WebCheckout For Woo
 * Description: Z-Credit WebCheckout payment page for Woo-Commerce
 * Version: 3.7.5
 * Author: z-credit.com
 *
 */

class ZCreditCheckout{
    public $plugin_path;

    public function __construct(){

        $this->plugin_path = plugin_dir_path(__FILE__); 

        add_action( 'woocommerce_loaded', array( $this, 'load_payment_gateways') );
        add_filter( 'woocommerce_payment_gateways', array( $this, 'zcredit_gateway' ) );
        add_action( 'init', array( $this, 'init' ), 0 );
    }

    public function init(){
        global $wpdb;

        $type = "zcredit_checkout";
        $table_name = $wpdb->prefix . $type . 'meta';
        $variable_name = $type . 'meta';
        $wpdb->$variable_name = $table_name;

        load_plugin_textdomain('woocommerce_zcredit', false, dirname( plugin_basename( __FILE__ ) ) . "/languages");

        if( is_admin() ) {
            wp_enqueue_style('zcredit-style', plugin_dir_url( __FILE__ ).'css/admin-zcredit.css');
			try
			{
				wp_enqueue_script('zcredit-script', plugin_dir_url( __FILE__ ).'js/admin-zcredit.js',array( 'jquery' ) );
			}
			catch (Exception $e) 
			{
				wp_enqueue_script('zcredit-script', plugin_dir_url( __FILE__ ).'js/admin-zcredit.js');
			}
			//wp_enqueue_script('zcredit-script', plugin_dir_url( _FILE_ ).'js/admin-zcredit.js',array( 'jquery' ) );
        }
		
		/*
        register_post_status( 'wc-paymentauthorized', array(
            'label'                     => _x( 'Payment authorized', 'Order status', 'woocommerce_zcredit' ),
            'public'                    => false,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( __( 'Payment authorized', 'woocommerce_zcredit') . ' <span class="count">(%s)</span>', __( 'Payment authorized', 'woocommerce_zcredit' ) . ' <span class="count">(%s)</span>' )
        ) );
		*/

        //add_filter( 'wc_order_statuses', array($this, 'add_wc_order_statuses') );
        //add_filter( 'wc_order_is_editable', array($this, 'filter_wc_order_is_editable'), 20, 2 );
        add_action( 'woocommerce_order_item_add_action_buttons', array($this, 'action_woocommerce_order_item_add_action_buttons'), 10, 1 );
        add_action( 'wp_ajax_send_authorized_payment', array($this, 'send_authorized_payment') );
    }

    public function load_payment_gateways(){
        //include_once 'class-wc-gateway-zcredit-payment.php';
		include_once 'class-wc-gateway-zcredit-webcheckout.php';
    }

    public function zcredit_gateway($methods){
        $methods[] = 'WC_Gateway_ZCredit_Checkout' ;
        return $methods ;
    }

	/*
    public function add_wc_order_statuses( $order_statuses ) {
        $order_statuses['wc-paymentauthorized'] = __( 'Payment authorized', 'woocommerce_zcredit' );
        return $order_statuses;
    }

    public function filter_wc_order_is_editable( $editable, $order ) {
        $editable_custom_statuses = array( 'paymentauthorized' );
        if( in_array( $order->get_status(), $editable_custom_statuses ) ) {
            $editable = true;
        }
        return $editable;
    }
	*/

    public function action_woocommerce_order_item_add_action_buttons( $order ) {
        $zc_gateway = self::get_gateway_zcredit();
        //if( $order->get_status() == 'paymentauthorized' && $zc_gateway ) {
		if( $order->get_status() == 'on-hold' && $zc_gateway ) {
            $order_id = $order->get_id();
            $zc_response = self::get_zc_response( $order_id );
            if( $zc_gateway->payment_authorized == 'authorize' && $zc_response) { ?>
                <button type="button" class="button button-primary zc-capture-payment"
                        data-id="<?php echo $order->get_id(); ?>" data-sum="<?php echo $zc_response['Total']; ?>"
                        data-ordersum="<?php echo $order->get_total(); ?>"
                        data-message="<?php _e( 'Cannot guarantee a captured amount higher than the amount authorized. Proceed anyway?', 'woocommerce_zcredit' ); ?>">
                    <?php esc_html(_e( 'Capture payment', 'woocommerce_zcredit' )); ?>
                </button>
            <?php } ?>
        <?php }
    }

    public function send_authorized_payment() {
        //self::commit_full_transaction( $_POST['order_id'] );
        self::commit_full_transaction(sanitize_text_field($_POST['order_id']));
        exit();
    }

    private static function commit_full_transaction( $order_id ) {
		
	
        $order = new WC_Order( $order_id );
        $zc_gateway = self::get_gateway_zcredit();
        //if( $order && $order->get_status() == 'paymentauthorized' && $zc_gateway ) {
		if( $order && $order->get_status() == 'on-hold' && $zc_gateway ) {
            $order_sum = $order->get_total();
            $zc_response = self::get_zc_response( $order_id );
            $zc_terminal = $zc_gateway->terminal_number;
            $zc_password = $zc_gateway->password;
			
						
			if( $zc_terminal == "" || $zc_password == "" ) {
                $result = array( 'success' => false, 'error_code' => -1, 'message' => __('Terminal Number or password values are missing in settings.', 'woocommerce_zcredit') );
				echo json_encode($result);
				return;
            }
			
			$noOfPayments = $zc_response['Installments'];
			$firstPayment = number_format($zc_response['FirstInstallentAmount'], 2, '.', '');
			$OtherPayments = number_format($zc_response['OtherInstallmentsAmount'], 2, '.', '');
			$creditType = $zc_response['CreditType'];
			
			if ($creditType == 8 && $noOfPayments > 1)
			{
				$OtherPayments = number_format($order_sum/$noOfPayments, 2, '.', '');
				$firstPayment = number_format($order_sum-($OtherPayments*($noOfPayments-1)), 2, '.', '');
			}
			
			
			//$zc_response['ReferenceNumber'] = 12651257;
            $order_currency = $order->get_order_currency();
            $list_currency = array( 'USD' => 2, 'ILS' => 1 );
            $currency = $list_currency[$order_currency] ? $list_currency[$order_currency] : 1;
            $data = array(
                'TerminalNumber'                 => $zc_terminal,
                'Password'                       => $zc_password,
                'CurrencyType'                   => $currency,
                'CardNumber'                     => $zc_response['Token'],
                'ObeligoAction'                  => 1,
                'OriginalZCreditReferenceNumber' => $zc_response['ReferenceNumber'],
                'TransactionSum'                 => $order_sum,
				'ApplicationType'				 => 5,
				'NumberOfPayments'				 => $noOfPayments,
				'FirstPaymentSum'				 => $firstPayment,
				'OtherPaymentsSum'				 => $OtherPayments,
				'CreditType'				 	 => $creditType
            );
            if( $zc_response['CustomerName'] ) $data['CustomerName'] = $zc_response['CustomerName'];
            if( $zc_response['CustomerPhone'] ) $data['PhoneNumber'] = $zc_response['CustomerPhone'];
            if( $zc_response['CustomerEmail'] ) $data['CustomerEmail'] = $zc_response['CustomerEmail'];
			
            
			//Create invoice
			if ($zc_gateway->create_invoice == 'true')
			{
				//Create items array
				$qty = 0;
				$order_items = $order->get_items();
				$products = array();
				$items_Total = 0;
				foreach( $order_items as $order_item ) {
					
					if ($zc_gateway->Taxes_Enabled == 'true')
					{
						$IsTaxFree = ($order_item['line_tax'] > 0)? 'false' : 'true';
					}
					else
					{
						$IsTaxFree = 'false';
					}
					
					$qty += $order_item['qty'];

					$line_total = ($order_item['line_tax'] > 0)?number_format( ($order_item['line_total'] + $order_item['line_tax'])/$order_item['qty'], 2, '.', ''): number_format($order_item['line_total']/$order_item['qty'], 2, '.', '');
					$items_Total += ($line_total*$order_item['qty']);
			
					$products[] = array(
						"ItemDescription" => $order_item['name'],
						"ItemQuantity" => $order_item['qty'],
						"ItemPrice" => str_replace(',','',$line_total) ,
						"IsTaxFree" => $IsTaxFree
					);
				}

				$shipping = $order->get_shipping_method();
				if( $shipping) {
					if ($order->get_shipping_total() > 0)
					{
						$shippingTotal = number_format($order->get_shipping_total() + $order->get_shipping_tax(), 2, '.', '');
						$products[] = array(
							"ItemDescription"       => $shipping,
							"ItemQuantity"     => 1,
							"ItemPrice"  => str_replace(',','',$shippingTotal) ,
							"IsTaxFree" => 'false' 
						);
						
						$items_Total += $shippingTotal;
					}
				}
				
				$order_sum = number_format($order_sum, 2, '.', '');
				$items_Total = number_format($items_Total, 2, '.', '');
				
				if (false){
					//$result = array( 'success' => false, 'error_code' => '', 'message' => 'response: ' . $zc_response );
					$result = array( 'success' => false, 'error_code' => '', 'message' => json_encode($data)  . '\n' . json_encode($products));
					echo json_encode($result);
					return;
				}
				
				if ($items_Total != $order_sum){
					$result = array( 'success' => false, 'error_code' => -1, 'message' =>  __('Total amount does not match items amount: ', 'woocommerce_zcredit') . $items_Total . " VS " . $order_sum );
					echo json_encode($result);
					return;
				}
				//End - Create Items Array
				
				//Create main array
				$invoiceData = array(
					'Address'                 		 => '',
					'City'                       	 => '',
					'EmailDocumentToReceipient'      => ($zc_response['CustomerEmail'] ? 'true' : 'false'),
					'FaxNum'                     	 => '',
					'PhoneNum'                  	 => $zc_response['CustomerPhone'],
					'ReceipientEmail' 				 => $zc_response['CustomerEmail'],
					'RecepientCompanyID'             => $zc_response['HolderId'],
					'RecepientName'             	 => $zc_response['CustomerName'],
					'ReturnDocumentInResponse'       => 'false',
					'TaxRate'             			 => '-1',
					'ZipCode'             			 => '',
					'Type'             				 => '0',
					'Items' 						 => $products
				);
				
				$data['ZCreditInvoiceReceipt'] = $invoiceData;
			}

		
			if (false){
				//$result = array( 'success' => false, 'error_code' => '', 'message' => 'response: ' . $zc_response );
                $result = array( 'success' => false, 'error_code' => '', 'message' => json_encode($data) );
				echo json_encode($result);
				return;
			}
			
		
			/*******************************
			//WP HTTP API CALL
			********************************/
			$args = array(
				'headers' => array(
					'Content-Type' => 'application/json; charset=utf-8'
				),
				'body' => json_encode($data)
			);
			
			$full_response = wp_remote_post("https://pci.zcredit.co.il/ZCreditWS/api/Transaction/CommitFullTransaction", $args );		
			$response = wp_remote_retrieve_body($full_response);	
			
            $response = json_decode($response, true);
            if( $response['HasError'] ) {
                $result = array( 'success' => false, 'error_code' => $response['ReturnCode'], 'message' => $response['ReturnMessage'] );
            }
            else {
                $result = array( 'success' => true );
                //$order->update_status('processing', __( 'Z-Credit payment complete.', 'woocommerce_zcredit' ) );
                $order->add_order_note( __( 'Z-Credit Payment Complete.', 'woocommerce_zcredit' ) );
                $order->payment_complete(); 
				update_post_meta( $order_id, 'zc_payment_token', $response['Token'] );
            }
            //echo json_encode($result);
        }
		else
		{
			$result = array( 'success' => false, 
							 'error_code' => -1, 
							 'message' =>  __( 'This order status does not allow capture', 'woocommerce_zcredit' ) );
			//echo json_encode($result);
		}
		
		echo json_encode($result);
		return;
    }
	
    private static function get_zc_response( $order_id ) {
        $json = get_post_meta( $order_id, 'zc_response', true );
        $json = $json ? unserialize(base64_decode($json)) : "";
		
		// Converts it into a PHP object
		$zc_response = json_decode($json, true);
		//$data = $zc_response['Data'];
		
        return $zc_response;
    }

    private static function get_gateway_zcredit() {
        if( class_exists('WC_Gateway_ZCredit_Checkout') ) {
            return new WC_Gateway_ZCredit_Checkout();
        }
        return false;
    }

}

$GLOBALS['zcredit_checkout'] = new ZCreditCheckout();