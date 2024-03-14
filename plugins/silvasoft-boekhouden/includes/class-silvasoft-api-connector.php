<?php

/**
 * API Connector class
 *
 * @link       silvasoft.nl
 * @since      1.0.0
 *
 * @package    Silvasoft
 * @subpackage Silvasoft/includes
 */

/**
 * Connect to the Silvasoft API
 */
class Silvasoft_ApiConnector {
	private $version;
	private $silvalogger;
	private $plugin_name;
	protected $apiuser;
	protected $apikey;
	protected $endpoint;
	protected $apiurl;
	protected $invoicenote;
	protected $productDefaultSku;
	protected $address2AsHousNr;
	protected $orderNrAsReference;
	protected $postZeroLines;
	protected $silvasoft_shippingtaxpc;
	protected $ledger_domestic;
	protected $ledger_eu;
	protected $ledger_export;
	protected $useArticleDescription;
	protected $silvasoft_dateuse;
	protected $silvasoft_debugmode;
	protected $silvasoft_exemptvatcode;
	protected $silvasoft_countryLedgerCode;
	protected $silvasoft_countryTxCode;
	protected $distinguisTax;
	protected $relationTypeSetting;
	private $status_sale;
	private $status_refund;
	protected $order_tax_items;

	public function __construct($plugin_name, $version, $silvalogger) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->silvalogger = $silvalogger;
		
		
		$this->productDefaultSku = 'woocommerce-geen-sku';
		$this->order_tax_items = array();
		
		$silvasoftSettings = get_option('silva_settings') ? get_option('silva_settings') : array();	
		$this->apiuser = isset($silvasoftSettings['silva_username']) ? $silvasoftSettings['silva_username'] : null;
		$this->apikey = isset($silvasoftSettings['silva_api_token']) ?  $silvasoftSettings['silva_api_token'] : null;
		$this->endpoint = isset($silvasoftSettings['silvasoft_endpoint']) ? $silvasoftSettings['silvasoft_endpoint'] : null;
		$this->apiurl = isset($silvasoftSettings['silva_api_url']) ? $silvasoftSettings['silva_api_url'] : null;
		
		$this->status_sale = isset($silvasoftSettings['silvasoft_status_sale']) ? $silvasoftSettings['silvasoft_status_sale'] : null;
		$this->status_refund = isset($silvasoftSettings['silvasoft_status_credit']) ? $silvasoftSettings['silvasoft_status_credit'] : null;
		
		$this->relationTypeSetting = isset($silvasoftSettings['silvasoft_relationtype']) ? $silvasoftSettings['silvasoft_relationtype'] : 'auto';
		
		$this->silvasoft_shippingtaxpc = $this->settingHasValue($silvasoftSettings,'silvasoft_shippingtaxpc') ? $silvasoftSettings['silvasoft_shippingtaxpc'] : 21;
		
		$this->silvasoft_exemptvatcode = $this->settingHasValue($silvasoftSettings,'silvasoft_vatexemptvat') ? $silvasoftSettings['silvasoft_vatexemptvat'] : '';
		
		$this->ledger_domestic = $this->settingHasValue($silvasoftSettings,'silvasoft_ledgerdomestic') ? $silvasoftSettings['silvasoft_ledgerdomestic'] : -1;
		$this->ledger_eu = $this->settingHasValue($silvasoftSettings,'silvasoft_ledgereu') ? $silvasoftSettings['silvasoft_ledgereu'] : -1;
		$this->ledger_export = $this->settingHasValue($silvasoftSettings,'silvasoft_ledgerexport') ? $silvasoftSettings['silvasoft_ledgerexport'] : -1;
		
		$this->silvasoft_countryLedgerCode = $this->settingHasValue($silvasoftSettings,'silvasoft_countryledger') ? $silvasoftSettings['silvasoft_countryledger'] : false;
		
		$this->silvasoft_countryTxCode = $this->settingHasValue($silvasoftSettings,'silvasoft_countrytax') ? $silvasoftSettings['silvasoft_countrytax'] : false;
		
		$this->address2AsHousNr = $this->settingHasValue($silvasoftSettings,'silvasoft_address2ashousenumber') ? ($silvasoftSettings['silvasoft_address2ashousenumber'] == 'yes' ? true : false) : false;
		$this->orderNrAsReference = $this->settingHasValue($silvasoftSettings,'silvasoft_ordernrasreferentie') ? ($silvasoftSettings['silvasoft_ordernrasreferentie'] == 'yes' ? true : false) : false;
		
		
		$this->useArticleDescription = $this->settingHasValue($silvasoftSettings,'silvasoft_usearticledescription') ? ($silvasoftSettings['silvasoft_usearticledescription'] == 'yes' ? true : false) : false;
		
		//orderdate or currentdate
		$this->silvasoft_dateuse = $this->settingHasValue($silvasoftSettings,'silvasoft_dateuse') ? $silvasoftSettings['silvasoft_dateuse'] : "orderdate" ; 
		
		$this->postZeroLines = $this->settingHasValue($silvasoftSettings,'silvasoft_postzerolines') ? ($silvasoftSettings['silvasoft_postzerolines'] == 'yes' ? true : false) : false;
		
		$this->distinguisTax = $this->settingHasValue($silvasoftSettings,'silvasoft_shippingtaxdistinguish') ? ($silvasoftSettings['silvasoft_shippingtaxdistinguish'] == 'yes' ? true : false) : false;
		
		
		$defaultnote = 'Boeking aangemaakt vanuit WooCommerce met order nummer: #{ordernr}';
		$this->invoicenote = isset($silvasoftSettings['silvasoft_invoicenote']) ? $silvasoftSettings['silvasoft_invoicenote'] : $defaultnote;
		if($this->invoicenote == '') $this->invoicenote = $defaultnote;
		if($this->invoicenote == '{empty}') $this->invoicenote = false;
		
		$this->silvasoft_debugmode = $this->settingHasValue($silvasoftSettings,'silvasoft_debugmode') ? ($silvasoftSettings['silvasoft_debugmode'] == 'yes' ? true : false) : false;
	}
	
	function settingHasValue($silvasoftSettings, $handle) {
		
		if(!isset($silvasoftSettings[$handle])) { return false; }
		
		if(is_null($silvasoftSettings[$handle])) { return false; }
		
		if($silvasoftSettings[$handle] == "") { return false; }
									 
		return true;
									 
	}
	
	/*
	* CRON
	*/
	public function silvasoft_woo_cron() {
		if($this->silvasoft_debugmode) {
			$this->silvalogger->doLog('DEBUG','Start CRON Silvasoft <> WooCommerce sync');
		}
		
		$ordersNormal = wc_get_orders( array(
			'limit'        => 5, // Query all orders
			'orderby'      => 'date',
			'order'        => 'ASC',
			'meta_key'     => 'silva_cron', // The postmeta key field
			'meta_compare' => '=', // The comparison argument
			'meta_value' => 'pending'
		));
		$ordersRefund = wc_get_orders( array(
			'limit'        => 5, // Query all orders
			'orderby'      => 'date',
			'order'        => 'ASC',
			'meta_key'     => 'silva_cron_refund', // The postmeta key field
			'meta_compare' => '=', // The comparison argument
			'meta_value' => 'pending'
		));
		$orders = array_merge($ordersNormal,$ordersRefund);
		
		$processed_ids = array();
		$skipped_ids = array();
		$errored_ids = array();
		
		foreach( $orders as $order ){
    		
			//similar to code in class-woo-hooks
			
			$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
			
			if($this->silvasoft_debugmode) {
				$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Processing from CRON');
			}
			
			$order_status = method_exists( $order, 'get_status' ) ? $order->get_status() : $order->status;
			
			//check if creditorder
			$creditorder = ($order_status ==	$this->status_refund || 'wc-'.$order_status == $this->status_refund) ? 1 : 0;
			
			//check if the order has already been sent to Silvasoft
			$alreadysentNormal = get_post_meta($order_id,'silva_send_sale',true);
		
			//check if the order has already been sent to Silvasoft
			$alreadysentCredit = get_post_meta($order_id,'silva_send_creditnota',true);
			
			//partial creditnota?
			$partialRefund = 0;
			if($order->get_total_refunded() > 0) {
				$creditorder = 1;
				$notRefunded = wc_format_decimal( $order->get_total() - $order->get_total_refunded() );
				if($notRefunded > 0) {
					$partialRefund = 1;
				}
			}
			
			$refundOrderMeta = false;
			if(($order_status == $this->status_sale) || ('wc-'.$order_status == $this->status_sale)) {			
				if($alreadysentNormal) {
					$skipped_ids[] = $post_id;
					continue;
				}
				//sales transaction
				$status = $this->transferOrderToSilvasoft($order_id);
				
			} 
			if(($order_status == $this->status_refund) || ('wc-'.$order_status == $this->status_refund) || $partialRefund === 1) {
				if($alreadysentCredit) {
					$skipped_ids[] = $post_id;
					continue;
				}
				$refundOrderMeta = true;
				//creditnota
				$status = $this->transferOrderToSilvasoft($order_id,true,$partialRefund === 1);
			}	
			
			if($status['status'] == "ok") {
				$processed_ids[] = $post_id;
				
				if($refundOrderMeta === true) {
					update_post_meta($order_id, 'silva_cron_refund', 'completed');
				}
				else {
					update_post_meta($order_id, 'silva_cron', 'completed');
				}
				
			} else {
				$errored_ids[] = $post_id;
			}
		}
		
		if($this->silvasoft_debugmode) {
			$this->silvalogger->doLog('DEBUG','End CRON Silvasoft <> WooCommerce sync');
		}
		
	}
	
	/* 
	 * Function to send a woocommerce order over to Silvasoft
	 */
	public function transferOrderToSilvasoft($order_id,$creditnota = false,$partialrefund = false) {
		
		
		$partialRefund = $partialrefund;
		
		if($this->silvasoft_debugmode) {
			$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Versturen naar Silvasoft start.');
		}
		
		global $woocommerce;
       	//create new order context
		$order = new WC_Order( $order_id );
		
		
		if($partialRefund === true) { $creditnota === true; }
		//check if the order has already been sent to Silvasoft
		$alreadysent = $creditnota === true 
			? get_post_meta($order_id,'silva_send_creditnota',true)
			: get_post_meta($order_id,'silva_send_sale',true);

		$vatexampt = get_post_meta($order_id,'is_vat_exempt',true);
		$is_vat_exempt = ($vatexampt != null && ($vatexampt == "yes" || $vatexampt == "true" || $vatexampt == 1 || $vatexampt === true || $vatexampt == "Yes" || $vatexampt == "True")) ? true : false;
		
		
		$stillRefundToSend = false;
		foreach ( $order->get_refunds() as $refund ) {
			$refund_id = $refund->get_data()['id'];
			$sentRefund = get_post_meta($order_id,'silva_send_refund_'.$refund_id,true);
			if($sentRefund == true || $sentRefund === 1) {
				//ok
			} else {
				$stillRefundToSend = true;
				break;
			}
		}
		if($stillRefundToSend && $creditnota) {
			//continue, still some part to sent to silvasoft
		} else {
			if($alreadysent === true || $alreadysent === 1 || $alreadysent === '1') {
				return array("status"=>"alreadysend");	
			} else if($stillRefundToSend === false && $partialRefund) {
				return array("status"=>"alreadysend");	
			}
		}

		//collect billing address
		$billingfirstname = method_exists( $order, 'get_billing_first_name' ) ? $order->get_billing_first_name() : $order->billing_first_name;
		$billingmail = method_exists( $order, 'get_billing_email' ) ? $order->get_billing_email() : $order->billing_email;
		$billinglastname = method_exists( $order, 'get_billing_last_name' ) ? $order->get_billing_last_name() : $order->billing_last_name;
		$billingphone = method_exists( $order, 'get_billing_phone' ) ? $order->get_billing_phone() : $order->billing_phone;
		$billingcompany = method_exists( $order, 'get_billing_company' ) ? $order->get_billing_company() : $order->billing_company;
		$billingcountrycode = method_exists( $order, 'get_billing_country' ) ? $order->get_billing_country() : $order->billing_country;
		//get address information from order
		$address = method_exists( $order, 'get_billing_address_1' ) ? $order->get_billing_address_1() : $order->billing_address_1;
		//this is not nessecarily housnumber, on most installations this will be uniot number (for example B or suite 61)
		$housnumber = method_exists( $order, 'get_billing_address_2' ) ? $order->get_billing_address_2() : $order->billing_address_2;
		if($this->address2AsHousNr !== true) {
			//add address_2 to full address
			$address .= ' '. $housnumber;
		}
		$city = method_exists( $order, 'get_billing_city' ) ? $order->get_billing_city() : $order->billing_city;
		$postcode = method_exists( $order, 'get_billing_postcode' ) ? $order->get_billing_postcode() : $order->billing_postcode;
		
		//collect shipping address
		$shippingcountrycode = method_exists( $order, 'get_shipping_country' ) ? $order->get_shipping_country() : $order->shipping_country;
		//get address information from order
		$addressshipping = method_exists( $order, 'get_shipping_address_1' ) ? $order->get_shipping_address_1() : $order->shipping_address_1;
		//this is not nessecarily housnumber, on most installations this will be uniot number (for example B or suite 61)
		$housnumbershipping = method_exists( $order, 'get_shipping_address_2' ) ? $order->get_shipping_address_2() : $order->shipping_address_2;
		if($this->address2AsHousNr !== true) {
			//add address_2 to full address
			$addressshipping .= ' '. $housnumber;
		}
		$cityshipping = method_exists( $order, 'get_shipping_city' ) ? $order->get_shipping_city() : $order->shipping_city;
		$postcodeshipping = method_exists( $order, 'get_shipping_postcode' ) ? $order->get_shipping_postcode() : $order->shipping_postcode;
		
		
		//set ledger account number
		$ledgerAccountNumber = -1;
		$isDomestic = false;
		$isEu = false;
		$isExport = false;
		$arrayEuCountries = array("BE","BG","CZ","DK","DE","EE","IE","EL","ES","FR","HR","IT","CY","LV","LT","LU","HU","MT","NL","AT","PL","PT","RO","SI","SK","FI","SE");
		
		if($billingcountrycode != NULL && $billingcountrycode != "" && ($this->ledger_eu != -1 || $this->ledger_domestic != -1 || $this->ledger_export != -1)) {
			$countries_obj   = new WC_Countries();
			$countries   = $countries_obj->__get('countries');
			$baseCountry = $countries_obj->get_base_country();
			if($baseCountry != NULL && $baseCountry != "") {
				//specified ledger in settings
				if($baseCountry == $billingcountrycode) {
					$ledgerAccountNumber = $this->ledger_domestic;
					$isDomestic = true;
					
				} else if(in_array($billingcountrycode,$arrayEuCountries)) {
					$ledgerAccountNumber = $this->ledger_eu;
					$isEu = true;
				} else {
					$ledgerAccountNumber = $this->ledger_export;
					$isExport = true;
				}				
			}
		}
		
		//country specific ledger? > this applies to all orderlines as country is only specified once at invoice level
		if($this->silvasoft_countryLedgerCode !== false) {
			//$this->silvasoft_countryLedgerCode = NL:44000;BE:45000;DE:46000
			$countryLedgers = explode(";",$this->silvasoft_countryLedgerCode);
			$countryLedgers2d = array();
			foreach($countryLedgers as $val) {
				$keyval = explode(':',$val);
				$country = isset($keyval[0]) ? $keyval[0] : '';
				$ledger = isset($keyval[1]) ? $keyval[1] : '';
				$countryLedgers2d[$country] = $ledger;
			}
			
			if(isset($countryLedgers2d[$billingcountrycode]) && $countryLedgers2d[$billingcountrycode] != null) {
				$ledgerAccountNumber = $countryLedgers2d[$billingcountrycode];
			}
			
		}
		
		//country specific taxcode, transform setting to readable array
		//silvasoft_countryTxCode 
		$countryTx2d = array();
		if($this->silvasoft_countryTxCode !== false) {
			//$this->silvasoft_countryTxCode = NL||21||21%-hoogtarief|NEXT|BE||9||9%laag tarief - BE
			$countryTxCodes = explode("|NEXT|",$this->silvasoft_countryTxCode);
			
			foreach($countryTxCodes as $val) {
				$keyval = explode('||',$val);
				$country = isset($keyval[0]) ? $keyval[0] : '';
				$taxpc = isset($keyval[1]) ? $keyval[1] : '';
				$taxcode = isset($keyval[2]) ? $keyval[2] : '';
				
				//$billingcountrycode
				$countryTx2d[$country][$taxpc] = $taxcode;
			}
		}
		
		
		
			
		if($ledgerAccountNumber != -1) {
			$line['LedgerAccountNumber'] = $ledgerAccountNumber;
		}
		
		//create or retreive the Silvasoft customer	
		$customerPostResult = $this->collectCustomer($order);
		
		if($this->silvasoft_debugmode) {
			$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Versturen naar Silvasoft ná collectCustomer.');
		}
		
		$customerName = '';
		$customerMail = '';
		if($customerPostResult['status'] === 'nok') {
			
			if(strpos($customerPostResult['msg'], 'Invalid authentication credentials') !== false) {
				$this->throwRequestErrorLog('Error: uw API gegevens zijn incorrect of uw proefperiode van Silvasoft is verlopen. Controleer uw API key en API username (e-mailadres van uw Silvasoft account) via het menu: Silvasoft > Instellingen.',$order_id,true,$creditnota); 				
			} else if(strpos($customerPostResult['msg'], 'Ratelimit exceeded') !== false) {
				$this->throwRequestErrorLog('Error: u heeft het maximaal aantal requests per uur bereikt. Upgrade uw API plan in Silvasoft, of probeer het later nog eens. Houdt er rekening mee dat het versturen van 1 order minimaal 3 api requests zijn (voor het aanmaken van de relatie, product(en) en de order). Bekijk uw verbruik in Silvasoft via het menu: Beheer > API. ',$order_id,true,$creditnota); 				
			} else {
				$this->throwRequestErrorLog('Error: er kon geen CRM klant aangemaakt worden. Controleer uw API gegevens, uw API plan of neem contact op met onze helpdesk voor ondersteuning. Extra details: ' . $customerPostResult['msg'],$order_id,true,$creditnota); 	
			}
			
			return array("status"=>"nok");
		} else {
			$customerName = $customerPostResult['customer'];
			$customerMail = $customerPostResult['email'];
		}
		
		//collect order data
		$order_status = method_exists( $order, 'get_status' ) ? $order->get_status() : $order->status;
		$orderdatecreated = method_exists( $order, 'get_date_created' ) ? $order->get_date_created() : $order->date_created;
		
		$ordertime = strtotime($orderdatecreated);
		$orderdate = date('d-m-Y', $ordertime);
	
		
		
		$data = array();
		$data['CustomerName'] = $customerName;		
		$data['CustomerEmail'] = $customerMail;
		
		if($this->silvasoft_dateuse == NULL || $this->silvasoft_dateuse == 'orderdate') {
			$data['InvoiceDate'] = $orderdate;
		}
		
		//echo $data['InvoiceDate'];exit;
		
		$orderNrUse = $order_id;	
		
		/* Vervallen met nieuwe versie jetpack booster, filter get_order_number geeft nu deze terug ook 
		if(method_exists('WCJ_Order_Numbers','display_order_number')) {
			
			//booster order nr	
			$orderNrUse = WCJ_Order_Numbers::display_order_number('',$order);			
			if($orderNrUse === NULL || $orderNrUse == '') { $orderNrUse = $order_id; }
		} else */
			
		if( (int)is_callable(array($order, 'get_order_number')) === 1 ) {
			//sequential order nr plugin
			$orderNrUse = $order->get_order_number();		
			if($orderNrUse === NULL || $orderNrUse == '') { $orderNrUse = $order_id; }
		}
		
		
		if($this->invoicenote !== false) {
			$note = $this->invoicenote;
			$note = str_replace('{ordernr}',$orderNrUse,$note);
			$note = str_replace('{orderid}',$order_id,$note);
			$data['OrderNotes'] = $note;
			$data['InvoiceNotes'] = $note;
		}
		
		$lineNameApi = 'Invoice_InvoiceLine';
		if($this->endpoint === 'addorder') {
			$lineNameApi = 'Order_Orderline';	
		}
		$data[$lineNameApi] = array();
		
		$addressNameApi = 'Invoice_Address';
		if($this->endpoint === 'addorder') {
			$addressNameApi = 'Order_Address';	
		}
		$data[$addressNameApi] = array();
		
		//add billing address
		$addressBillingPost = array();
		$addressBillingPost['Address_Street'] = $address;		
		if($this->address2AsHousNr === true) {
			$addressBillingPost['Address_Unit'] = $housnumber;
		}		
		$addressBillingPost['Address_City'] = $city;
		$addressBillingPost['Address_PostalCode'] = $postcode;
		$addressBillingPost['Address_CountryCode'] = $billingcountrycode;
		$addressBillingPost['Address_Type'] = 'BillingAddress';
		array_push($data[$addressNameApi],$addressBillingPost);
		
		//add shipping address
		$addressShippingPost = array();
		$addressShippingPost['Address_Street'] = $addressshipping;		
		if($this->address2AsHousNr === true) {
			$addressShippingPost['Address_Unit'] = $housnumbershipping;
		}		
		$addressShippingPost['Address_City'] = $cityshipping;
		$addressShippingPost['Address_PostalCode'] = $postcodeshipping;
		$addressShippingPost['Address_CountryCode'] = $shippingcountrycode;	
		$addressShippingPost['Address_Type'] = 'ShippingAddress';
		array_push($data[$addressNameApi],$addressShippingPost);
		
	
		if($this->orderNrAsReference === true) {
			$data['InvoiceReference'] = strval($orderNrUse);
			$data['OrderReference'] = strval($orderNrUse);
		}
		
	
		if($this->silvasoft_debugmode) {
			$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Versturen naar Silvasoft voor ophalen orderregels.');
		}
		
		$tax_items = array();
		foreach ( $order->get_items('tax') as $tax_item ) {
			// Set the tax labels by rate ID in an array
			$tax_items[$tax_item->get_rate_id()] = $tax_item->get_rate_percent();
		}
		$this->order_tax_items = $tax_items;
		$order_items = $order->get_items(array('line_item','shipping','fee'));		
		
		$refundsProccessed = array();
		if($partialRefund === true) {		
			//PARTIAL REFUNDS
			foreach ( $order->get_refunds() as $refund ) {
				$refund_data = $refund->get_data();
				$refund_id = $refund_data['id'];
				array_push($refundsProccessed,$refund_id);
				
				$refundAmountRemaining = floatval($refund_data['total']);
				
				//already sent? 
				$sentRefund = get_post_meta($order_id,'silva_send_refund_'.$refund_id,true);
				if($sentRefund === 1 || $sentRefund == true) {
					continue;
				}	
				//refund items are refunds for example refund product or shipping item
				$refundItems = $refund->get_items(array('line_item','shipping','fee'));
								
				//each refund item translates to at least 1 orderline
				if( is_array($refundItems) && (count($refundItems) > 0) ) { //refund items = shipping/product/fee
					foreach($refundItems as $refunded_item) {
						//vars
						$taxData = NULL; //wordt array met percentage en amount (van orderregel)
						$orderLineData = false;	
						$orderLineObj = false;
						$orderLineTotalInclTax = 0;
						$orderLineQuantity = 0;
						$amountTaxRefunded = 0;
						$orderLineType = '';
						$amountEurRefunded = 0;						
						$productId = '';
						$amountStockRefunded=0; //for shipping/fee line
						$refundType = $refunded_item->get_type();
						
						if($refundType == 'line_item') {
							$productId = $refunded_item->get_product_id();
							$amountStockRefunded += $refunded_item->get_quantity();
						}
						$amountEurRefunded += $refunded_item->get_total();
						
						//taxes refund
						$taxes  = $refunded_item->get_taxes();						
						foreach($taxes['total'] as $tid => $tval) {
							$amountTaxRefunded += isset( $taxes['total'][ $tid ] ) ? (float) $taxes['total'][ $tid ] : 0;
						}
						
						//vind matching orderline voor deze refund item en doe daarop de tget_tax-info
						foreach($order_items as $orderline) {
							$item_id = $orderline->get_id();

							if ( absint( $refunded_item->get_meta( '_refunded_item_id' ) ) === $item_id ) {
								//match	
								$orderLineType = $orderline->get_type();
								$orderLineData = $orderline->get_data();
								$orderLineObj = $orderline;
								break;
							}
						}	
						
						
						//totals and create orderline
						$orderLineTotalInclTax = ($amountEurRefunded+$amountTaxRefunded);
						$refundAmountRemaining -= floatval($orderLineTotalInclTax);
						$orderLineQuantity = min($amountStockRefunded,-1);
						$orderLineData["total"] = ($amountEurRefunded);
						$zeroTotal = ($orderLineData['total'] === 0 || $orderLineData['total'] == "0" || floatval($orderLineData['total']) == 0);

						//tax
						$taxData = $this->getTaxInfoByLine($orderLineData,$zeroTotal,$orderLineType);
						$orderLineTaxPC = (is_array($taxData) && isset($taxData['percent'])) ? $taxData['percent'] : -1;

						//create orderline data and product for credit
						$error = false;
						$line = $this->createOrderLineAPI($order,$orderLineObj,$orderLineData, floatval($orderLineTotalInclTax), floatval($orderLineQuantity), $is_vat_exempt, $billingcountrycode, $countryTx2d, $orderLineTaxPC, $ledgerAccountNumber, $orderLineType, $error,false);

						if($line !== false) {
							array_push($data[$lineNameApi],$line);
						} else {
							if($error==true) return;
							continue;
						}
						
						
					} //end foreach refund items (products/shipping/fee)
				}//end if has refund items
				
				//remainder? 
				if(abs($refundAmountRemaining) > 0.01) {
					//remainder that has not been categorized into line items such as products/shipping/fee
					$orderLineObj = false;
					$orderLineData = array();
					$orderLineData['total'] = $refundAmountRemaining;
					$orderLineData['name'] = 'woo-refund';
					$orderLineTotalInclTax = $refundAmountRemaining;
					$orderLineQuantity = 1;
					$amountTaxRefunded = 0;
					$orderLineTaxPC = -1;//no way to know. Maybe maake setting for it in future..
					$orderLineType = 'refund_remainder';
					
					$error = false;						
					$line = $this->createOrderLineAPI($order,$orderLineObj,$orderLineData, floatval($orderLineTotalInclTax), floatval($orderLineQuantity), $is_vat_exempt, $billingcountrycode, $countryTx2d, $orderLineTaxPC, $ledgerAccountNumber, $orderLineType, $error,true);
					
					if($line !== false) {
						array_push($data[$lineNameApi],$line);
					} else {
						if($error==true) return;
						continue;
					}
				}
				
			}
			
		} else {
			//NOT PARTIAL REFUND	
			foreach($order_items as $orderline) {
				//line data
				$orderLineData = $orderline->get_data();
				$orderLineType = $orderline->get_type();
				
				//zero total? only send zero lines if configured
				$zeroTotal = ($orderLineData['total'] === 0 || $orderLineData['total'] == "0" || floatval($orderLineData['total']) == 0);

				//$array[percent,total]
				$taxData = $this->getTaxInfoByLine($orderLineData,$zeroTotal,$orderline['type']);


				//vars
				$taxTotalNotRounded = 0;
				$orderLineQuantity = 1; //for shipping items
				$orderLineTotalInclTax = 0;
				$orderLineTaxPC = 0;


				//calculate total quantity
				$orderLineQuantity = 1; //for shipping items
				if($orderline['type'] === 'line_item') {				
						//line_item (product)
					$orderLineQuantity = $orderLineData['quantity'];
				}

				//calculate total amount including tax
				$orderLineTotalInclTax = $orderLineData['total'] + $orderLineData['total_tax'];	

				//calculate tax PC
				$orderLineTaxPC = $taxData['percent'];
				//full credit order, credit total
				if($creditnota) { 
					$orderLineQuantity = -$orderLineQuantity;
					$orderLineTotalInclTax = -$orderLineTotalInclTax;
				}
				
				$error = false;
				$line = $this->createOrderLineAPI($order,$orderline,$orderLineData, $orderLineTotalInclTax, $orderLineQuantity, $is_vat_exempt, $billingcountrycode, $countryTx2d, $orderLineTaxPC, $ledgerAccountNumber, $orderLineType,$error,false);
				
				if($this->silvasoft_debugmode) {
					$linestring = print_r($line, true);
					$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Orderline toegevoegd met data: ' . $linestring);
				}
				
				//add the order line to the request data
				if($line !== false) {
					array_push($data[$lineNameApi],$line);
				} else {
					if($error==true) return;
					continue;
				}
			}
		}
		
		
		if($this->silvasoft_debugmode) {
			$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Versturen naar Silvasoft ná ophalen orderregels.');
		}

		// Do actual create order API request 
		$result = '';
		try {
			$result = $this->CallAPI('POST',$this->endpoint,$data);			
		} catch (Exception $e) {
			$result = '';
		}

		if($this->silvasoft_debugmode) {
			$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Versturen naar Silvasoft ná versturen naar Silvasoft.');
		}

		//validate
		$validated = $this->validateResult($result,200,true);

		//process validation
		$orderIdString = $orderNrUse;
		if($orderNrUse != $order_id) {
			$orderIdString .= ' (ID: ' . $order_id . ')';	
		}

		if($validated['ok'] === true) {
			$type = $creditnota ? 'Creditnota' : 'Verkoopboeking';

			if($creditnota && $partialRefund === false) {
				//full creditnota? set all refunds to sent
				foreach ( $order->get_refunds() as $refund ) {
					$refund_id = $refund->get_data()['id'];
					array_push($refundsProccessed,$refund_id);					
				}
			}

			$refundsProccessed = array_unique($refundsProccessed);

			if(count($refundsProccessed)===1) {
				$refunds = implode('',$refundsProccessed);
				//order succesfully created log
				$this->silvalogger->doLog($type . ' gemaakt','Terugbetaling: #'.$refunds.' voor order: #' . $orderIdString . ' (met status: ' . $order_status . ') is verstuurd naar uw Silvasoft boekhouding.'
										,$order_id,false,$creditnota);
			} else if(count($refundsProccessed)>1) {
				$refunds = implode(' & #',$refundsProccessed);
				//order succesfully created log
				$this->silvalogger->doLog($type . ' gemaakt','Terugbetalingen: #' . $refunds .' voor order: #' . $orderIdString . ' (met status: ' . $order_status . ') zijn verstuurd naar uw Silvasoft boekhouding.'
										,$order_id,false,$creditnota);
			} else {
				//order succesfully created log
				$this->silvalogger->doLog($type . ' gemaakt','Order: #' . $orderIdString . ' (met status: ' . $order_status . ') is verstuurd naar uw Silvasoft boekhouding.'
										,$order_id,false,$creditnota);
			}
			//add flags to the order whether it's sent to Silvasoft or not
			if($creditnota === true) {
				add_post_meta($order_id, 'silva_send_creditnota', true, true);
			} else {
				add_post_meta($order_id, 'silva_send_sale', true, true);
			}

			foreach($refundsProccessed as $refundId) {
				add_post_meta($order_id, 'silva_send_refund_'.$refundId, true, true);
			}


			return array("status"=>"ok");
		} else{
			if(strpos($validated['msg'], 'Invalid authentication credentials') !== false) {
				$this->throwRequestErrorLog('Error: uw API gegevens zijn incorrect of uw proefperiode van Silvasoft is verlopen. Controleer uw API key en API username (e-mailadres van uw Silvasoft account) via het menu: Silvasoft > Instellingen.',$order_id,true,$creditnota); 				
			} else if(strpos($validated['msg'], 'Ratelimit exceeded') !== false) {
				$this->throwRequestErrorLog('Error: u heeft het maximaal aantal requests per uur bereikt. Upgrade uw API plan in Silvasoft, of probeer het later nog eens. Houdt er rekening mee dat het versturen van 1 order minimaal 3 api requests zijn (voor het aanmaken van de relatie, product(en) en de order). Bekijk uw verbruik in Silvasoft via het menu: Beheer > API. ',$order_id,true,$creditnota); 				
			} else {
				$this->throwRequestErrorLog('Error: ' . $validated['msg'] . ' [orderdate: '.$orderdate. ' ' . $order->get_date_created().']',$order_id,true,$creditnota); 			
			}
			return array("status"=>"nok"); 					
		}

		if($this->silvasoft_debugmode) {
			$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Versturen naar Silvasoft afgerond.');
		}		
	}
	
	
	function createOrderLineAPI($order,$orderline,$orderLineData, $orderLineTotalInclTax, $orderLineQuantity, $is_vat_exempt, $billingcountrycode, $countryTx2d, $orderLineTaxPC, $ledgerAccountNumber, $orderLineType,&$error,$wooRemainderCredit = false) {
				
		$zeroTotal = ($orderLineTotalInclTax === 0 || $orderLineTotalInclTax == "0" || floatval($orderLineTotalInclTax) == 0);
		
		$order_id = $order->get_id();
		
		//post zero lines?
		if($zeroTotal === true && $this->postZeroLines != true) {					
			return false;//skip this one, move on to next	
		}
		
		$orderLineData['total'] = floatval($orderLineData['total']);

		//price per unit
		
		$orderLinePrijsPerStuk = $zeroTotal ? 0 : $orderLineData['total'];
		if($orderLineQuantity != 0) {
			$orderLinePrijsPerStuk = $zeroTotal ? 0 : $orderLineData['total'] / $orderLineQuantity;
		}
		$orderLineProduct = $orderLineData['name'];


		/* format the total price as WooCommerce does it on their orders / payment page etc
		 * Now we know 100% sure we have to correct price as shown in the order total and payment page to a customer 
		 * See wc_price function source code
		 */ 
		$rawPrice = apply_filters( 'raw_woocommerce_price', floatval( $orderLineTotalInclTax ) );
		//force decimal seperator dot and no thousands seperator
		$price = apply_filters( 'formatted_woocommerce_price', number_format( $rawPrice, 2, ".", "" ), $rawPrice, 2, ".", "");

		//build array with line data for post
		$line = array();			
		if($is_vat_exempt && $this->silvasoft_exemptvatcode != '') {
			$line['TaxCode'] = $this->silvasoft_exemptvatcode;
		} else {
			//custom taxcode<>country match (as specified in settings)
			if($billingcountrycode !=  null && $billingcountrycode != '' &&  $orderLineTaxPC != null) {
				//$billingcountrycode
				//$countryTx2d[$country][$taxpc] = $taxcode;
				$taxCodeUse = isset($countryTx2d[$billingcountrycode][$orderLineTaxPC]) ? $countryTx2d[$billingcountrycode][$orderLineTaxPC] : '';
				if($taxCodeUse != null && $taxCodeUse != '') {
					$line['TaxCode'] = $taxCodeUse;
				} else {
					$line['TaxPc'] = $orderLineTaxPC;
				}
			} else {
				$line['TaxPc'] = $orderLineTaxPC;
			}


		}			

		$line['SubTotalInclTax'] = $price;
		if($this->useArticleDescription === false) {
			$line['Description'] = $orderLineProduct;
		}

		if($ledgerAccountNumber != NULL && $ledgerAccountNumber != -1) {
			$line['LedgerAccountNumber'] = $ledgerAccountNumber;
		}

		if($this->endpoint === 'addsalesinvoice' || $this->endpoint === 'addorder') {
			//add salesinvoice endpoint requireds more information
			$line['Quantity'] = $orderLineQuantity;
			$line['UnitPriceExclTax'] = $orderLinePrijsPerStuk;			

			//get or create the product from Silvasoft
			$productPostResult = $this->collectProduct($order,$orderline,$orderLineTaxPC,$orderLineType==='shipping',$orderLineType==='fee',$order_id,$wooRemainderCredit);
			if($productPostResult['status'] === 'nok') {

				if(strpos($productPostResult['msg'], 'Invalid authentication credentials') !== false) {
					$this->throwRequestErrorLog('Error: uw API gegevens zijn incorrect of uw proefperiode van Silvasoft is verlopen. Controleer uw API key en API username (e-mailadres van uw Silvasoft account) via het menu: Silvasoft > Instellingen.',$order_id,true,$creditnota); 				
				} else if(strpos($productPostResult['msg'], 'Ratelimit exceeded') !== false) {
					$this->throwRequestErrorLog('Error: u heeft het maximaal aantal requests per uur bereikt. Upgrade uw API plan in Silvasoft, of probeer het later nog eens. Houdt er rekening mee dat het versturen van 1 order minimaal 3 api requests zijn (voor het aanmaken van de relatie, product(en) en de order). Bekijk uw verbruik in Silvasoft via het menu: Beheer > API. ',$order_id,true,$creditnota); 				
				} else {
					$this->throwRequestErrorLog('Error: er kon geen product voor deze order gevonden of gemaakt worden in uw Silvasoft administratie. Controleer uw API gegevens, uw API plan of neem contact op met onze helpdesk voor ondersteuning.',$order_id,true,$creditnota); 					
				}			
				$error = true;
				return false;
			} else {
				$line['ProductNumber'] = $productPostResult['product'];
			}
		}

		return $line;


	}	

	
	
	/*
	* Get tax info (percentage + total) from orderLineData
	*/
	function getTaxInfoByLine($orderLineData,$zeroTotal = false,$orderLineType) {
		
		$percent = NULL;
		$taxTotalNotRounded = 0;
		$preferOther = true;
		if( isset($orderLineData['taxes'])  && isset($orderLineData['taxes']['total']) ) {

			foreach($orderLineData['taxes']['total'] as $taxId => $taxTotal) {
				//if percent == null always set to this tax ID we found for the line
				if(isset($this->order_tax_items[$taxId]) && $percent == NULL) {
					if($taxTotal != null && $taxTotal != "" && $taxTotal !== 0) { 
						$preferOther = false; 
					}
					$percent = $this->order_tax_items[$taxId];
				} 
				//if we already have a percent, check if there is a better match. Sometimes woocommerce will give 2 percentages, only one containing an actual amount in taxTotal. In that case we prefer to use the one with an amount in taxTotal
				if($percent != NULL) {
					if($preferOther) {
						if($taxTotal != null && $taxTotal != "" && $taxTotal !== 0) {
							$percent = $this->order_tax_items[$taxId];
						}
					}
				}
				
				if(is_numeric($taxTotal)) {
					$taxTotalNotRounded += $taxTotal;
				}
			}
		}
		
		if($taxTotalNotRounded === 0) $taxTotalNotRounded = $orderLineData['total_tax'];
				   
		if($percent == NULL) {
			//calculate tax PC
			$percent = $zeroTotal ? 0 : round(($taxTotalNotRounded / $orderLineData['total']) * 100);				
		}
		
		//voor regels met 0 euro btw op de verzendkosten (gratis verzending bijvoorbeeld) het btw-percentage vanuit de instellingen koppelen, anders het btw bij de regel aanhouden vanuit woocommerce
		if($orderLineType === 'shipping' && $zeroTotal === true) {	
			$percent = $this->silvasoft_shippingtaxpc;				
		}
		if($zeroTotal && $percent === 0 && $this->endpoint != 'addsalestransaction') {
			//-1 will be processed as: use vat of product, or use 0% if no product or product vat is found. Only for addorder and addsalesinvoice endpoints
			$percent = -1; 	
		}
		
		return array("percent"=>$percent,"total"=>$taxTotalNotRounded);
	}
	
	/* 
	 * Get or create the ordered WooCommerce product from Silvasoft administration
	 */
	function collectProduct($order,$orderline,$taxpc,$shippingline,$feeline,$order_id,$wooCreditSku =false) {
		$orderLineSku = '';
		$productPrice = 0;
		$nameUse = '';
		if($orderline == false && $shippingline == false && $feeline == false) {
			//could occur for credit notes where not a product or shipping is credited but a seperate amount unrelated to the orderlines
			$orderLineSku = 'woo-unknown';
			$nameUse = 'woo-unknown';
			if($wooCreditSku !== false) {
				$orderLineSku = 'woo-refund';
				$nameUse = 'woo-refund';
			}
		} else {
			$nameUse = $orderline['name'];
			
			$orderLineData = $orderline->get_data();

			if($shippingline) {
				$shippingTitleUse = 'woo-shipping';			
				try {
					$shippingTitle = $orderline['method_title'];
					if(!is_null($shippingTitle) && $shippingTitle != '' && $shippingTitle != ' ') {
						$shippingTitleUse = strtolower(str_replace(' ','-',$shippingTitle));
					}
				} catch(Exception $e) {
					$shippingTitleUse = 'woo-shipping';
				}

				if($this->distinguisTax && $taxpc != NULL) {
					$shippingTitleUse = $shippingTitleUse . "-" . $taxpc;
				}

				$orderLineSku = $shippingTitleUse;
				$productPrice = $orderLineData['total'];
			} elseif($feeline) {
				$orderLineSku = 'woo-fee';			
				$productPrice = $orderLineData['total'];
				//name of fee, for example 'extra charge iDEAL'. Cut-off the name if too long for productname field
				$nameFee = $orderline['name'];
				$nameFeeUse = 'woo-fee';
				if(!is_null($nameFee) && $nameFee != '' && $nameFee != ' ') {
					$nameFeeUse = 'woo-fee-'.strtolower(str_replace(' ','-',$nameFee));
					if(strlen($nameFeeUse) > 100) {
						$nameFeeUse = substr($nameFeeUse,0,95).'..';
					}				
					$orderLineSku = $nameFeeUse;
				}

			} else {
				//get product by orderline
				$product = $order->get_product_from_item( $orderline );

				//the sku that will be used in the Silvasoft administration
				$orderLineSku = $product->get_sku();		
				if($orderLineSku == NULL || $orderLineSku == '') {
					$orderLineSku = $this->productDefaultSku;	
				}
				$productPrice = $product->get_price();
			}
		}
		
		//check if product exists in Silvasoft administration
		$productRetrieveData['ArticleNumber'] = $orderLineSku;
		$productRetrieveData['IncludeArchived'] = true;
		$result = '';
		try {
			$result = $this->CallAPI('GET','listproducts',$productRetrieveData);			
		} catch (Exception $e) {
			$result = '';
		}
		
		
		//validate
		$validated = $this->validateResult($result,200,true);	
		$msg = isset($validated['msg']) ? $validated['msg'] : '';
		
		if($this->silvasoft_debugmode) {
			$validatedString = print_r($validated,true);
			$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Versturen naar Silvasoft na ophalen product bij orderregel. Validate = ' . $validatedString);
		}
		
		//process validation
		if($validated['ok'] === true) {
			//product exists and can be used for the orderline
			return array("status"=>"ok","product"=>$orderLineSku);
		} else {			
			//product does not exist, create it in Silvasoft administration
			
			//name max length
			$nameUseFull = $nameUse;
			if(!is_null($nameUse) && $nameUse != '' && $nameUse != ' ') {
				$nameUse = strtolower(str_replace(' ','-',$nameUse));
				if(strlen($nameUse) > 175) {
					$nameUse = substr($nameUse,0,170).'..';
				}				
			}
			
			
			$requestData = array();			
			$requestData['ArticleNumber'] = $orderLineSku;
			$requestData['NewName'] = $nameUse;
			$requestData['NewDescription'] = $nameUseFull;
			$requestData['NewSalePrice'] = $productPrice;
			$requestData['NewVATPercentage'] = $taxpc;		
			$requestData['CategoryName'] = 'Woocommerce';
			$requestData['CategoryCreateIfMissing'] = true;	
			
			$inclorexcl = "excl";
			try {
				$pricesIncl = wc_tax_enabled() && 'yes' === get_option( 'woocommerce_prices_include_tax' ); 
				$inclorexcl = $pricesIncl ? "incl" : "excl";
			} catch (Exception $e) {
				$inclorexcl = "excl";
			}
			
			$requestData['SalePriceInclOrExcl'] = $inclorexcl;
			
			$result = '';
			try {
				$result = $this->CallAPI('POST','addproduct',$requestData);			
			} catch (Exception $e) {
				$result = '';
			}		
			//validate
			$validated = $this->validateResult($result,200,true);
			$msg = isset($validated['msg']) ? $validated['msg'] : '';
			
			if($this->silvasoft_debugmode) {
				$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Versturen naar Silvasoft na aanmaken product bij orderregel. Validate = ' . $validated['ok']);
			}
			
			//process validation
			if($validated['ok'] === true) {			
				return array("status"=>"ok","product"=>$orderLineSku);
			} else {
				return array("status"=>"nok","msg"=>$msg);
			}
		}
		return array("status"=>"nok","msg"=>$msg);
	}
	
	
	/* 
	 * Get or create the customer and address details from Silvasoft administration
	 */
	function collectCustomer($order) {
		//get order information
		
		//collect billing address
		$billingfirstname = method_exists( $order, 'get_billing_first_name' ) ? $order->get_billing_first_name() : $order->billing_first_name;
		$billingmail = method_exists( $order, 'get_billing_email' ) ? $order->get_billing_email() : $order->billing_email;
		$billinglastname = method_exists( $order, 'get_billing_last_name' ) ? $order->get_billing_last_name() : $order->billing_last_name;
		$billingphone = method_exists( $order, 'get_billing_phone' ) ? $order->get_billing_phone() : $order->billing_phone;
		$billingcompany = method_exists( $order, 'get_billing_company' ) ? $order->get_billing_company() : $order->billing_company;
		$billingcountrycode = method_exists( $order, 'get_billing_country' ) ? $order->get_billing_country() : $order->billing_country;
		//get address information from order
		$address = method_exists( $order, 'get_billing_address_1' ) ? $order->get_billing_address_1() : $order->billing_address_1;
		//this is not nessecarily housnumber, on most installations this will be uniot number (for example B or suite 61)
		$housnumber = method_exists( $order, 'get_billing_address_2' ) ? $order->get_billing_address_2() : $order->billing_address_2;
		if($this->address2AsHousNr !== true) {
			//add address_2 to full address
			$address .= ' '. $housnumber;
		}
		$city = method_exists( $order, 'get_billing_city' ) ? $order->get_billing_city() : $order->billing_city;
		$postcode = method_exists( $order, 'get_billing_postcode' ) ? $order->get_billing_postcode() : $order->billing_postcode;
		
		
		//build request
		$requestData = array();
		
		//determin endpoint (private or business)		
		$endpoint = 'addprivaterelation';
		
		//set Name param to customer details, overwrite with company details if present
		$requestData['Name'] = '';
		if($billingcompany != null && $billingcompany != '') {
			$endpoint = 'addbusinessrelation';	
			$requestData['Name'] = $billingcompany;
		}
		
		
		if($this->relationTypeSetting != null && $this->relationTypeSetting == 'business' ) {$endpoint = 'addbusinessrelation';	}
		if($this->relationTypeSetting != null && $this->relationTypeSetting == 'private' ) {$endpoint = 'addprivaterelation';	}
				
		//TaxIdentificationNumber	
		$vat_number = "";
		//EU VAT COMPLIANCE
		if(function_exists('WooCommerce_EU_VAT_Compliance')) {
			
			if($vat_number == "" && get_post_meta($order->get_id(),'VAT Number',true)) {
				$vat_number = get_post_meta($order->get_id(), 'VAT Number', true);
				if($vat_number != null && $vat_number != "" && substr($vat_number,0,strlen($billingcountrycode)) === $billingcountrycode) {
					$vat_number = substr($vat_number,strlen($billingcountrycode));
				}
			}			
		}
			
		
		//custom field vat_number
		if($vat_number == "" && get_post_meta($order->get_id(),'vat_number',true)) {
			$vat_number = get_post_meta($order->get_id(), 'vat_number', true);
			if($vat_number != null && $vat_number != "" && substr($vat_number,0,strlen($billingcountrycode)) === $billingcountrycode) {
				$vat_number = substr($vat_number,strlen($billingcountrycode));
			}
		}
		
	
		//custom field _vat_number (also used by Woocommerce EU Vat Number plugin)
		if($vat_number == "" && get_post_meta($order->get_id(),'_vat_number',true)) {
			$vat_number = get_post_meta($order->get_id(), '_vat_number', true);
			if($vat_number != null && $vat_number != "" && substr($vat_number,0,strlen($billingcountrycode)) === $billingcountrycode) {
				$vat_number = substr($vat_number,strlen($billingcountrycode));
			}
		}
		
		//booster plugin EU vat number
		if($vat_number == "") {
			$vat_number = get_post_meta( $order->get_id(), '_billing_eu_vat_number', true );
			if($vat_number != null && $vat_number != "" && substr($vat_number,0,strlen($billingcountrycode)) === $billingcountrycode) {
					$vat_number = substr($vat_number,strlen($billingcountrycode));
			}
		}
		
		//custom field _billing_vat_number (also used by Woocommerce EU Vat Number plugin)
		if($vat_number == "" && get_post_meta($order->get_id(),'_billing_vat_number',true)) {
			$vat_number = get_post_meta($order->get_id(), '_billing_vat_number', true);
			if($vat_number != null && $vat_number != "" && substr($vat_number,0,strlen($billingcountrycode)) === $billingcountrycode) {
				$vat_number = substr($vat_number,strlen($billingcountrycode));
			}
		}		
		
			
		$requestData['IsCustomer'] = true;
		$requestData['TaxIdentificationNumber'] = $vat_number;
		
		if(!is_null($vat_number) && $vat_number != "") {
			$endpoint = 'addbusinessrelation';			
		}
		
		//no company name but business relation? fill name with personal name of customer
		if((!isset($requestData['Name']) || $requestData['Name'] == null || $requestData['Name'] == '') && $endpoint == 'addbusinessrelation') {		
			if($billingfirstname != null) {
				$requestData['Name'] .= $billingfirstname . ' ';
			}
			if($billinglastname != null) {
				$requestData['Name'] .= $billinglastname;
			}		
		}
		
		
		$requestData['OnExistingRelationName'] = 'ABORT';
		$requestData['Relation_Contact'] = array();	
		
		//add address
		$requestData['Address_Street'] = $address;		
		if($this->address2AsHousNr === true) {
			$requestData['Address_Unit'] = $housnumber;
		}		
		$requestData['Address_City'] = $city;
		$requestData['Address_PostalCode'] = $postcode;
		$requestData['Address_CountryCode'] = $billingcountrycode;	
		
		$requestData['Email'] = $billingmail;
		$requestData['Phone'] = $billingphone;			
		$contact = array();
		$contact['Email'] = $billingmail;
		$contact['Phone'] = $billingphone;
		$contact['LastName'] = $billinglastname;
		$contact['FirstName'] = $billingfirstname;		
		array_push($requestData['Relation_Contact'],$contact);
		
		//do the request to create or retreive the relation from Silvasoft
		$result = '';
		try {
			$result = $this->CallAPI('POST',$endpoint,$requestData);			
		} catch (Exception $e) {
			$result = '';
		}
		
		//validate
		$validated = $this->validateResult($result,200,true);
		
		$msg = isset($validated['msg']) ? $validated['msg'] : '';
		//process validation
		if($validated['ok'] === true) {			
			$resultresponse = $result['response'];
			$resultPHP = json_decode($resultresponse);
			$customername = $resultPHP[0]->Name;
			return array("status"=>"ok","customer"=>$customername,"email"=>$billingmail);
		} else{
			return array("status"=>"nok","msg"=>$msg);
		}
	}
	
	/*
	* Generic validation function to validate POST, PUT or GET requests results
	*/
	function validateResult($result, $expectedHttpCode, $mayNotBeEmpty) {
		/* Validation 1 - JSON response error */
		$resultresponse = $result['response'];
		$resultcode = $result['httpcode'];
		$resultPHP = json_decode($resultresponse);
		
		if($this->silvasoft_debugmode) {
			$resultString = print_r($result,true);
			$this->silvalogger->doLog('DEBUG','Aanroep API, valideren resultaat: ' . $resultString);
		}
		
		if(isset($resultPHP->errorCode)) {
			$error = $resultPHP->errorCode . ' - '. $resultPHP->errorMessage; 
			return array('ok'=>false,'msg'=>$error);	
		}
		
		/* Validation 2 - Invalid HTTP response code */		
		if($resultcode !== $expectedHttpCode) {
			$error = 'HTTP code voldoet niet aan de verwachtingen. Teruggekregen code is: ' . $resultcode . '. ';
			
			if(isset($resultPHP->errorCurl)){
				$error .= " Error: " . $resultPHP->errorCurl;
			}
			
			return array('ok'=>false,'msg'=>$error);	
		}
		
		//check curl errors
		if(isset($resultPHP->errorCurl)){
			$error .= "CURL error: " . $resultPHP->errorCurl;
			return array('ok'=>false,'msg'=>$error);	
		}
		
		/* Validation 3 - Empty result */
		if($mayNotBeEmpty) {
			if(empty($resultPHP)) {
				$error = 'Het antwoord van Silvasoft voldoet niet aan de verwachting. Reden: leeg antwoord ontvangen. ' . $resultcode; 
				return array('ok'=>false,'msg'=>$error);
			}
		}
		
		return array('ok'=>true,'msg'=>'');	
		
	}
	

	
	
	/* Create silvasoft log record (error) */
	function throwRequestErrorLog($msg,$order_id,$canresend,$creditnota) {
		$typestring = $creditnota ? 'creditnota' : 'verkoop';
		$this->silvalogger->doLog('FOUT','De '.$typestring.' is niet verstuurd naar Silvasoft. Details: ' . $msg,$order_id,$canresend,$creditnota);
	}
	
	/* Call API using CURL */
	function CallAPI($method, $endpoint, $data = false)
	{
		//initiate and build url
		$curl = curl_init();
		$data_string = json_encode($data);
		
		$url = 	$this->apiurl.$endpoint;
		//setup curl postdata
		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);	
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
					
		}
		//set headers
		if($method == 'POST' || $method == 'PUT') {
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
				'ApiKey: '.$this->apikey,
				'Username: '.$this->apiuser,
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data_string))                                                                       
			);    
		} else {
			//GET
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
				'ApiKey: '.$this->apikey,
				'Username: '.$this->apiuser)                                                                      
			);
		}
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($curl, CURLOPT_FAILONERROR, true);
		
		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		
		if($httpcode === 0 || $httpcode === 404) {
			$response = '{"errorCurl": " '.$httpcode. ' - vermoedelijk is uw API url verkeerd ingesteld onder instellingen. Voorbeeld van een correcte API url is: https://rest-api.silvasoft.nl/rest/"}';
		}
		
		//build and return response
		$result = array('httpcode' => $httpcode, 'response' => $response);	
		curl_close($curl);	
		return $result;
	
	}
	
}
