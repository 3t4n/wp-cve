<?php
/**
 * SmartBill API class.
 *
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @copyright  Copyright 2018 © Intelligent IT SRL. All rights reserved.
 */

/**
 * SmartBill API class.
 *
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 */
class SmartBill_Cloud_REST_Client {
	const STOCKS_SYNC_IP                     = '52.214.61.160';
	const STOCKS_SYNC						 = 'https://ws.smartbill.ro/SBORO/api/stocks/ecom/?cif=%s&warehouseName=%s';
	const INVOICE_URL                        = 'https://ws.smartbill.ro/SBORO/api/invoice';
	const INVOICE_URL_WITH_DOCUMENT_ADDRESS  = 'https://ws.smartbill.ro/SBORO/api/invoice/v2';
	const MEASURING_UNITS_URL                = 'https://ws.smartbill.ro/SBORO/api/company/mu?cif=%s';
	const STATUS_INVOICE_URL                 = 'https://ws.smartbill.ro/SBORO/api/invoice/paymentstatus';
	const PROFORMA_URL                       = 'https://ws.smartbill.ro/SBORO/api/estimate';
	const PROFORMA_URL_WITH_DOCUMENT_ADDRESS = 'https://ws.smartbill.ro/SBORO/api/estimate/v2';
	const STATUS_PROFORMA_URL                = 'https://ws.smartbill.ro/SBORO/api/estimate/invoices';
	const PAYMENT_URL                        = 'https://ws.smartbill.ro/SBORO/api/payment';
	const EMAIL_URL                          = 'https://ws.smartbill.ro/SBORO/api/document/send';
	const TAXES_URL                          = 'https://ws.smartbill.ro/SBORO/api/tax?cif=%s';
	const SERIES_URL                         = 'https://ws.smartbill.ro/SBORO/api/series?cif=%s';
	const PRODUCTS_STOCK_URL                 = 'https://ws.smartbill.ro/SBORO/api/stocks?cif=%s&date=%s&warehouseName=%s&productName=%s&productCode=%s';
	const STOCK_URL                          = 'https://ws.smartbill.ro/SBORO/api/company/warehouses?cif=%s';
	const PARAMS_PDF                         = '/pdf?cif=%s&seriesname=%s&number=%s';
	const PARAMS_DELETE                      = '?cif=%s&seriesname=%s&number=%s';
	const PARAMS_DELETE_RECEIPT              = '/chitanta?cif=%s&seriesname=%s&number=%s';
	const PARAMS_CANCEL                      = '/cancel?cif=%s&seriesname=%s&number=%s';
	const PARAMS_RESTORE                     = '/restore?cif=%s&seriesname=%s&number=%s';
	const PARAMS_STATUS                      = '?cif=%s&seriesname=%s&number=%s';
	const PARAMS_FISCAL_RECEIPT              = '/text?cif=%s&id=%s';

	const PAYMENT_TYPE_ORDIN_PLATA   = 'Ordin plata';
	const PAYMENT_TYPE_CHITANTA      = 'Chitanta';
	const PAYMENT_TYPE_CARD          = 'Card';
	const PAYMENT_TYPE_CEC           = 'CEC';
	const PAYMENT_TYPE_BILET_ORDIN   = 'Bilet ordin';
	const PAYMENT_TYPE_MANDAT_POSTAL = 'Mandat postal';
	const PAYMENT_TYPE_OTHER         = 'Alta incasare';
	const PAYMENT_TYPE_BON_FISCAL    = 'Bon';

	const DISCOUNT_TYPE_VALORIC    = 1;
	const DISCOUNT_TYPE_VALUE      = 1; // en.
	const DISCOUNT_TYPE_PROCENTUAL = 2;
	const DISCOUNT_TYPE_PERCENT    = 2; // en.

	const DOCUMENT_TYPE_INVOICE  = 'factura'; // en.
	const DOCUMENT_TYPE_FACTURA  = 'factura';
	const DOCUMENT_TYPE_PROFORMA = 'proforma';
	const DOCUMENT_TYPE_RECEIPT  = 'chitanta'; // en.
	const DOCUMENT_TYPE_CHITANTA = 'chitanta';

	const DEBUG_ON_ERROR = false; // use this only in development phase; DON'T USE IN PRODUCTION !!!
	const DATA_TYPES     = array(
		'string'  => array( 'address', 'aviz', 'bank', 'bcc', 'bodyText', 'cc', 'city', 'clientName', 'clientCif', 'code', 'company_vat_code', 'contact', 'country', 'county', 'currency', 'delegateAuto', 'delegateIdentityCard', 'delegateName', 'deliveryDate', 'dueDate', 'email', 'iban', 'invoiceNumber', 'invoiceSeries', 'invoicesList', 'issueDate', 'issuerCnp', 'issuerName', 'language', 'measuringUnitName', 'mentions', 'name', 'number', 'observation', 'observations', 'paymentDate', 'paymentType', 'paymentSeries', 'paymentURL', 'phone', 'productDescription', 'regCom', 'series_name', 'subject', 'taxName', 'text', 'to', 'translatedMeasuringUnit', 'translatedName', 'translatedText', 'type', 'vatCode', 'warehouseName', 'warehouseType' ),
		'boolean' => array( 'isTaxPayer', 'saveToDb', 'isDraft', 'useStock', 'useEstimateDetails', 'usePaymentTax', 'isDiscount', 'isTaxIncluded', 'isService', 'isCash', 'useInvoiceDetails', 'returnFiscalPrinterText', 'areInvoicesCreated' ),
		'double'  => array( 'colectedTax', 'discountPercentage', 'discountValue', 'exchangeRate', 'invoiceTotalAmount', 'paidAmount', 'paymentBase', 'paymentTotal', 'paymentValue', 'price', 'quantity', 'receivedBonuriValoareFixa', 'receivedCard', 'receivedCash', 'receivedCec', 'receivedCredit', 'receivedCupon', 'receivedMonedaAlternativa', 'receivedOrdinDePlata', 'receivedPuncteDeFidelitate', 'receivedTicheteCadou', 'receivedTicheteMasa', 'taxPercentage', 'unpaidAmount', 'value' ),
		'integer' => array( 'precision', 'numberOfItems', 'discountType' ),
	);

	/**
	 * This will store the api key for SmartBill auth.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hash    SmartBill api key.
	 */
	private $hash = '';

	/**
	 * This will store loggs about issued documents.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $data_logger    Document loggs.
	 */
	private $data_logger = null;

	/**
	 * This will store the order id that is used for issueing the document.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $woocommerce_order_id    Order ID / Post ID.
	 */
	private $woocommerce_order_id = null;


	/**
	 *  This will be used to contain store settings details for debugging purposes, if debugging on.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $woocommerce_settings_details    Woocommerce->Settings.
	 */
	private $woocommerce_settings_details = null;

	/**
	 *  This will be used to get full order info.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      WC_Order    $woocommerce_full_details    Woocommerce->Order Info.
	 */
	private $woocommerce_full_details = null;

	/**
	 *  Count calls to server.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      int    $counter
	 */
	public static $counter = 0;

	/**
	 * Create hash for latter use
	 *
	 * @param string $user  User Email.
	 * @param string $token SmartBill token.
	 *
	 * @param Array  $wp_info unused.
	 */
	public function __construct( $user, $token, $wp_info = array() ) {
		$this->hash = base64_encode( $user . ':' . $token );
	}
	/**
	 * Getter for data_logger
	 *
	 * @return string
	 */
	public function get_data_logger() {
		return $this->data_logger;
	}

	/**
	 * Setter for data_logger
	 *
	 * @param string $data_logger invoice json.
	 *
	 * @return SmartBill_Cloud_REST_Client
	 */
	public function set_data_logger( $data_logger ) {
		$this->data_logger = $data_logger;

		return $this;
	}
	/**
	 * Getter for woocommerce_order_id
	 *
	 * @return string
	 */
	public function get_woocommerce_order_id() {
		return $this->woocommerce_order_id;
	}

	/**
	 * Setter for woocommerce_order_id
	 *
	 * @param string $woocommerce_order_id woocommerce order id.
	 *
	 * @return SmartBill_Cloud_REST_Client
	 */
	public function set_woocommerce_order_id( $woocommerce_order_id ) {
		$this->woocommerce_order_id = $woocommerce_order_id;

		return $this;
	}

	/**
	 * Getter for woocommerce_settings_details
	 *
	 * @return string
	 */
	public function get_woocommerce_settings_details() {
		return $this->woocommerce_settings_details;
	}

	/**
	 * Setter for woocommerce_settings_details
	 *
	 * @param string $woocommerce_settings_details JSON.
	 *
	 * @return SmartBill_Cloud_REST_Client
	 */
	public function set_woocommerce_settings_details( $woocommerce_settings_details ) {
		$this->woocommerce_settings_details = $woocommerce_settings_details;

		return $this;
	}

	/**
	 * Getter for woocommerce_full_details
	 *
	 * @return string
	 */
	public function get_woocommerce_full_details() {
		return $this->woocommerce_full_details;
	}

	/**
	 * Setter for woocommerce_full_details
	 *
	 * @param string $woocommerce_full_details JSON.
	 *
	 * @return SmartBill_Cloud_REST_Client
	 */
	public function set_woocommerce_full_details( $woocommerce_full_details ) {
		$this->woocommerce_full_details = $woocommerce_full_details;

		return $this;
	}

	/**
	 * Get hash information
	 *
	 * @return string $hash
	 */
	public function get_hash() {
		return $this->hash;
	}

	/**
	 * Setter for hash
	 *
	 * @param string $hash apikey.
	 *
	 * @return void
	 */
	public function set_hash( $hash ) {
		$this->hash = $hash;
	}

	/**
	 * Function that calls an endpoint using wordpress HTTP API
	 *
	 * @param string $url endpoint.
	 * @param Array  $data endpoint parameters.
	 * @param string $request request type get/post/... .
	 * @param string $head_accept header parameters.
	 * 
	 *
	 * @throws \Exception $error_message error message.
	 *
	 * @return Array|false
	 */
	private function call_server( $url, $data = '', $request = '', $head_accept = ['Content-Type' => 'application/json']) {
		self::$counter++;
		// every 5 requests wait a second.
		if ( 0 == self::$counter % 5 ) {
			sleep( 1 );
		}

		$args=[];
		$args['timeout'] = 120;
		$args['headers'] = $head_accept;
		$args['headers']['Authorization'] ='Basic '.$this->hash ;
		$args['method'] = $request;
		if(!empty($data)){
			$args['body'] = wp_json_encode($data);
		}
			
		$ch = wp_remote_request($url, $args);
		if(is_wp_error($ch)){
			throw new \Exception( $ch->get_error_message() );
		}

		$status = wp_remote_retrieve_response_code( $ch );
		if ( isset( $this->data_logger ) && $this->data_logger instanceof SmartBill_Data_Logger ) {
			if ( is_numeric( $this->get_woocommerce_order_id() ) ) {
				$current_timestamp       = time();
				$sent_data               = $data;
				$received_data           = $ch['body'];
				$settings_data           = $this->woocommerce_settings_details;
				$order_data              = $this->woocommerce_full_details;

				if ( $this->data_logger->get_order_id() == $this->get_woocommerce_order_id() ) {
					$existing_sent_data     = json_decode( $this->data_logger->get_data( $this->get_woocommerce_order_id(), 'sent_data' ), $array = true );
					$existing_received_data = json_decode( $this->data_logger->get_data( $this->get_woocommerce_order_id(), 'received_data' ), $array = true );
					$existing_settings_data = json_decode( $this->data_logger->get_data( $this->get_woocommerce_order_id(), 'settings_data' ), $array = true );
					$existing_order_data    = json_decode( $this->data_logger->get_data( $this->get_woocommerce_order_id(), 'order_data' ), $array = true );

					$existing_sent_data[ $current_timestamp ]     = $sent_data;
					$existing_received_data[ $current_timestamp ] = $received_data;
					$existing_settings_data[ $current_timestamp ] = $settings_data;
					$existing_order_data[ $current_timestamp ]    = $order_data;

					$this->data_logger
						->set_data( $this->get_woocommerce_order_id(), 'sent_data', wp_json_encode( $existing_sent_data ) )
						->set_data( $this->get_woocommerce_order_id(), 'received_data', wp_json_encode( $existing_received_data ) )
						->set_data( $this->get_woocommerce_order_id(), 'settings_data', wp_json_encode( $existing_settings_data ) )
						->set_data( $this->get_woocommerce_order_id(), 'order_data', wp_json_encode( $existing_order_data ) )
						->set_data( $this->get_woocommerce_order_id(), 'updated_at', gmdate( 'Y-m-d H:i:s', $current_timestamp ) )
						->save( $this->get_woocommerce_order_id() );
				} else {
					// creare date initiale.
					$this->data_logger->set_data( $this->get_woocommerce_order_id(), 'invoice_id', $this->get_woocommerce_order_id() )
						->set_data( $this->get_woocommerce_order_id(), 'sent_data', wp_json_encode( array( $current_timestamp => $sent_data ) ) )
						->set_data( $this->get_woocommerce_order_id(), 'received_data', wp_json_encode( array( $current_timestamp => $received_data ) ) )
						->set_data( $this->get_woocommerce_order_id(), 'settings_data', wp_json_encode( array( $current_timestamp => $settings_data ) ) )
						->set_data( $this->get_woocommerce_order_id(), 'order_data', wp_json_encode( array( $current_timestamp => $order_data ) ) )
						->set_data( $this->get_woocommerce_order_id(), 'created_at', gmdate( 'Y-m-d H:i:s', $current_timestamp ) )
						->set_data( $this->get_woocommerce_order_id(), 'updated_at', gmdate( 'Y-m-d H:i:s', $current_timestamp ) )
						->save( $this->get_woocommerce_order_id() );
				}
			}
		}

		$return = wp_remote_retrieve_body($ch);
		
		if ( 200 !== $status ) {
			$error_message = json_decode( $return, true );

			if ( false !== strpos( $url, self::EMAIL_URL ) ) {
				$error_message = ! empty( $error_message['status']['code'] ) ? $error_message['status']['message'] : $return;
			} else {
				$error_message = ! empty( $error_message['errorText'] ) ? $error_message['errorText'] : $return;
			}

			throw new \Exception( $error_message );
			// empty response.
			$return = '';
		} elseif ( false == strpos( $url, '/pdf?' ) ) {
			$return                = json_decode( $return, true );
			$return['get_headers'] = json_decode( wp_json_encode( wp_remote_retrieve_headers($ch) ), true );
		}

		return $return;
	}

	/**
	 * Validate request & response
	 *
	 * @param Array $data additional document info.
	 *
	 * @return Array
	 */
	private function prepare_document_data( $data ) {
		if ( ! empty( $data['subject'] ) ) {
			$data['subject'] = base64_encode( $data['subject'] );
		}
		if ( ! empty( $data['bodyText'] ) ) {
			$data['bodyText'] = base64_encode( $data['bodyText'] );
		}
		return $data;
	}

	/**
	 * Set additional document info
	 *
	 * @param Array   $data additional document info.
	 * @param boolean $debug_mode enable debugging info.
	 *
	 * @return Array
	 */
	private function set_plugin_information( $data, $debug_mode = false ) {
		// plugin info.
		global $woocommerce;
		$data['ecommercePluginInfo']['platformName']    = 'WordPress';
		$data['ecommercePluginInfo']['platformVersion'] = $GLOBALS['wp_version'] . '/' . $woocommerce->version;
		$data['ecommercePluginInfo']['sbPluginVersion'] = SMARTBILL_PLUGIN_VERSION;

		if ( ! $debug_mode ) {
			$data['ecommercePluginInfo']['details'] = new \stdClass();
		} else {
			$data['ecommercePluginInfo']['details'] = new \stdClass();
			if ( is_array( $this->woocommerce_settings_details ) ) {
				foreach ( $this->woocommerce_settings_details as $key => $value ) {
					// ignore array values.
					if ( is_array( $value ) ) {
						continue;
					}
					$new_key = 'settings_' . $key;
					$data['ecommercePluginInfo']['details']->{$new_key} = $value;
				}
			}
			if ( is_array( $this->woocommerce_full_details ) ) {
				foreach ( $this->woocommerce_full_details as $key => $value ) {
					$new_key = 'order_' . $key;
					$data['ecommercePluginInfo']['details']->{$new_key} = $value;
				}
			}
		}
		$data['ecommercePluginInfo']['details']->{'order_id'} = $this->get_woocommerce_order_id();
		return $data;
	}

	/**
	 * Create invoice document with document address
	 *
	 * @param Array   $data document json.
	 * @param boolean $debug_mode enable debugging info.
	 *
	 * @return Array
	 */
	public function create_invoice_with_document_address( $data, $debug_mode = false ) {
		$data = $this->set_plugin_information( $data, $debug_mode );
		return $this->call_server( self::INVOICE_URL_WITH_DOCUMENT_ADDRESS, $data ,'POST');
	}

	/**
	 * Create invoice document
	 *
	 * @param Array $data document json.
	 *
	 * @return Array
	 */
	public function create_invoice( $data ) {
		$data = $this->set_plugin_information( $data );
		return $this->call_server( self::INVOICE_URL, $data ,'POST');
	}

	/**
	 * Create proforma document with document address
	 *
	 * @param Array   $data document json.
	 * @param boolean $debug_mode enable debugging info.
	 *
	 * @return Array
	 */
	public function create_proforma_with_document_address( $data, $debug_mode = false ) {
		$data = $this->set_plugin_information( $data, $debug_mode );
		return $this->call_server( self::PROFORMA_URL_WITH_DOCUMENT_ADDRESS, $data ,'POST');
	}

	/**
	 * Create proforma document
	 *
	 * @param Array $data document json.
	 *
	 * @return Array
	 */
	public function create_proforma( $data ) {
		$data = $this->set_plugin_information( $data );
		return $this->call_server( self::PROFORMA_URL, $data ,'POST');
	}

	/**
	 * Create payment
	 *
	 * @param Array $data document json.
	 *
	 * @return Array
	 */
	public function create_payment( $data ) {
		return $this->call_server( self::PAYMENT_URL, $data ,'POST');
	}

	/**
	 * Get pdf document
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name invoice series.
	 * @param string $number invoice number.
	 *
	 * @return Array
	 */
	public function pdf_invoice( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::INVOICE_URL . self::PARAMS_PDF, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'GET', ['Accept' => 'application/octet-stream'] );
	}

	/**
	 * Get pdf document
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function pdf_proforma( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::PROFORMA_URL . self::PARAMS_PDF, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'POST', ['Accept' => 'application/octet-stream'] );
	}

	/**
	 * Delete smartbill document
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function delete_invoice( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::INVOICE_URL . self::PARAMS_DELETE, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'DELETE' );
	}

	/**
	 * Delete smartbill document
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function delete_proforma( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::PROFORMA_URL . self::PARAMS_DELETE, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'DELETE' );
	}

	/**
	 * Delete smartbill document
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function delete_receipt( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::PAYMENT_URL . self::PARAMS_DELETE_RECEIPT, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'DELETE' );
	}

	/**
	 * Delete smartbill payment
	 *
	 * @param string $payment .
	 *
	 * @return Array
	 */
	public function delete_payment( $payment ) {
		return $this->call_server( self::PAYMENT_URL, $payment, 'DELETE' );
	}

	/**
	 * Cancel smartbill invoice
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function cancel_invoice( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::INVOICE_URL . self::PARAMS_CANCEL, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'PUT' );
	}

	/**
	 * Cancel smartbill document
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function cancel_proforma( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::PROFORMA_URL . self::PARAMS_CANCEL, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'PUT' );
	}

	/**
	 * Cancel smartbill payment
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function cancel_payment( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::PAYMENT_URL . self::PARAMS_CANCEL, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'PUT' );
	}

	/**
	 * Restore smartbill invoice
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function restore_invoice( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::INVOICE_URL . self::PARAMS_RESTORE, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'PUT' );
	}

	/**
	 * Restore smartbill invoice
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function restore_proforma( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::PROFORMA_URL . self::PARAMS_RESTORE, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'PUT' );
	}

	/**
	 * Get warehouse stock
	 *
	 * @param string $company_vat_code vat code.
	 * @param string $warehouse smartbill werehouse.
	 *
	 * @return Array
	 */
	public function get_ware_stock( $company_vat_code, $warehouse ) {
		$url = sprintf( self::STOCKS_SYNC, urlencode($company_vat_code), urlencode($warehouse) );
		return $this->call_server( $url, '', 'GET' );
	}


	/**
	 * Restore smartbill document
	 *
	 * @param array $data document body.
	 *
	 * @return Array
	 */
	public function send_document( $data ) {
		return $this->call_server( self::EMAIL_URL, $data, 'POST');
	}

	/**
	 * Get smartbill measuring units
	 *
	 * @param string $company_vat_code company vat code/cif.
	 *
	 * @return Array
	 */
	public function get_measuring_units( $company_vat_code ) {
		$url = sprintf( self::MEASURING_UNITS_URL, $company_vat_code );
		return $this->call_server( $url , '' , 'GET');
	}

	/**
	 * Get smartbill vats
	 *
	 * @param string $company_vat_code company vat code/cif.
	 *
	 * @return Array
	 */
	public function get_taxes( $company_vat_code ) {
		$url = sprintf( self::TAXES_URL, $company_vat_code);
		return $this->call_server( $url , '' , 'GET');
	}

	/**
	 * Get smartbill series
	 *
	 * @param string  $company_vat_code company vat code/cif.
	 * @param boolean $document_type invoice/proforma.
	 *
	 * @return Array
	 */
	public function get_document_series( $company_vat_code, $document_type = '' ) {
		$document_type = ! empty( $document_type ) ? substr( $document_type, 0, 1 ) : $document_type; // take the 1st character.
		$url           = sprintf( self::SERIES_URL, urlencode($company_vat_code) );
		return $this->call_server( $url, '' , 'GET' );
	}

	/**
	 * Get smartbill document payment status
	 *
	 * @param string $company_vat_code company vat code/cif.
	 * @param string $series_name document series.
	 * @param string $number document number.
	 *
	 * @return Array
	 */
	public function status_invoice_payments( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::STATUS_INVOICE_URL . self::PARAMS_STATUS, urlencode($company_vat_code), urlencode($series_name), urlencode($number) );
		return $this->call_server( $url,'','GET' );
	}

	/**
	 * Get smartbill warehouses
	 *
	 * @param string $company_vat_code company vat code/cif.
	 *
	 * @return Array
	 */
	public function get_stock( $company_vat_code ) {
		$url = sprintf( self::STOCK_URL, $company_vat_code);
		return $this->call_server( $url, '', 'GET' );
	}

	/**
	 * Get document status
	 *
	 * @param string $company_vat_code company vat code/cif.
	 * @param string $series_name company document series.
	 * @param string $number company document number.
	 *
	 * @return Array
	 */
	public function status_proforma( $company_vat_code, $series_name, $number ) {
		$url = sprintf( self::STATUS_PROFORMA_URL . self::PARAMS_STATUS, $company_vat_code, $series_name, $number );
		return $this->call_server( $url, '', 'GET' );
	}

	/**
	 * Get receipt details
	 *
	 * @param string $company_vat_code company vat code/cif.
	 * @param int    $id document id.
	 *
	 * @throws \Exception $ex invalid message.
	 *
	 * @return Array
	 */
	public function details_fiscal_receipt( $company_vat_code, $id ) {
		$url  = sprintf( self::PAYMENT_URL . self::PARAMS_FISCAL_RECEIPT, $company_vat_code, $id );
		$text = $this->call_server( $url );
		try {
			$text = base64_decode( $text['message'] );
		} catch ( \Exception $ex ) {
			throw new \Exception( 'invalid / empty response' );
		}

		return $text;
	}

	/**
	 * Get products stock
	 *
	 * @param Array $data products.
	 *
	 * @throws \Exception $ex invalid message.
	 *
	 * @return Array $list stocks
	 */
	public function products_stock( $data ) {
		$data = self::validate_products_stock( $data );
		$url  = self::url_products_stock( $data );
		$list = $this->call_server( $url,'','GET' );
		try {
			$list = $list['list'];
		} catch ( \Exception $ex ) {
			throw new \Exception( 'invalid / empty response' );
		}

		return $list;
	}

	/**
	 * Validate array of products
	 *
	 * @param Array $data products.
	 *
	 * @throws \Exception $ex invalid message.
	 *
	 * @return Array
	 */
	private static function validate_products_stock( $data ) {
		// append required keys in case they are missing.
		if ( null == $data ) {
			$data = array();
		}
		$data += array(
			'cif'           => '',
			'date'          => gmdate( 'Y-m-d' ),
			'warehouseName' => '',
			'productName'   => '',
			'productCode'   => '',
		);
		// urlencode values.
		foreach ( $data as $key => $value ) {
			$value        = rawurlencode( $value );
			$data[ $key ] = $value;
		}
		return $data;
	}


	/**
	 * Get product url
	 *
	 * @param Array $data product.
	 *
	 * @return string
	 */
	private static function url_products_stock( $data ) {
		return sprintf( self::PRODUCTS_STOCK_URL, $data['cif'], $data['date'], $data['warehouseName'], $data['productName'], $data['productCode'] );
	}


	/**
	 * Convert data to strict data types
	 *
	 * @param Array $data products.
	 *
	 * @return Array|null
	 */
	public static function convert_data_to_strict_data_types( $data ) {
		$data_types    = self::DATA_TYPES;
		$string_types  = $data_types['string'];
		$boolean_types = $data_types['boolean'];
		$double_types  = $data_types['double'];
		$integer_types = $data_types['integer'];
		if ( is_array( $data ) ) {
			foreach ( $data as $key => $item ) {
				if ( is_array( $data[ $key ] ) ) {
					$data[ $key ] = self::convert_data_to_strict_data_types( $data[ $key ] );
				} else {
					if ( in_array( $key, $string_types ) ) {
						$data[ $key ] = strval( $item );
					}
					if ( in_array( $key, $boolean_types ) ) {
						$data[ $key ] = boolval( $item );
					}
					if ( in_array( $key, $double_types ) ) {
						$data[ $key ] = doubleval( $item );
					}
					if ( in_array( $key, $integer_types ) ) {
						$data[ $key ] = intval( $item );
					}
				}
			}
			return $data;
		}
		return null;
	}

	/**
	 * Get list of headers for request
	 *
	 * @param string $resp_headers products.
	 *
	 * @return Array
	 */
	private function get_headers( $resp_headers ) {
		$headers = array();

		$header_text = substr( $resp_headers, 0, strpos( $resp_headers, "\r\n\r\n" ) );

		foreach ( explode( "\r\n", $header_text ) as $i => $line ) {
			if ( 0 == $i ) {
				$headers['http_code'] = $line;
			} else {
				list ($key, $value) = explode( ': ', $line );

				$headers[ $key ] = $value;
			}
		}

		return $headers;
	}

}
// phpcs: ignore.
