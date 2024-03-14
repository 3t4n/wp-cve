<?php

if ( !defined('CP_CONTACTFORMPP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; }

if( !class_exists( 'DEXBCCF_PayPalEXPC' ) )
{
    	class DEXBCCF_PayPalEXPC {	    
	    
	    public $mode = 'sandbox';
	    public $API_UserName = '';
		public $API_Password = '';
		public $API_Signature = '';
		public $currency = 'EUR';
		public $lang = 'EN';
		
		function GetItemTotalPrice($item){
			return $item['ItemPrice'] * $item['ItemQty']; 
		}
		
		function GetProductsTotalAmount($products){		
			$ProductsTotalAmount=0;
			foreach($products as $p => $item){				
				$ProductsTotalAmount = $ProductsTotalAmount + $this -> GetItemTotalPrice($item);	
			}			
			return $ProductsTotalAmount;
		}
		
		function GetGrandTotal($products, $charges){			
			//Grand total including all tax, insurance, shipping cost and discount			
			$GrandTotal = $this -> GetProductsTotalAmount($products);			
			foreach($charges as $charge){				
				$GrandTotal = $GrandTotal + $charge;
			}			
			return $GrandTotal;
		}
		
		function SetExpressCheckout($products, $charges, $okurl, $errurl, $noshipping='1', $iscredit = false){
			
			//Parameters for SetExpressCheckout, which will be sent to PayPal
			
			$padata  = 	'&METHOD=SetExpressCheckout';
			
			$padata .= 	'&RETURNURL='.urlencode($okurl);
			$padata .=	'&CANCELURL='.urlencode($errurl);
			$padata .=	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
			$padata .=	'&BUTTONSOURCE=NetFactorSL_SI_Custom';
			
			if ($iscredit)
			{
			    $padata .=	'&SOLUTIONTYPE='.urlencode("SOLE");
			    $padata .=	'&USERSELECTEDFUNDINGSOURCE='.urlencode("Finance");
			}
			
			foreach($products as $p => $item){
				
				$padata .=	'&L_PAYMENTREQUEST_0_NAME'.$p.'='.urlencode($item['ItemName']);
				$padata .=	'&L_PAYMENTREQUEST_0_NUMBER'.$p.'='.urlencode($item['ItemNumber']);
				$padata .=	'&L_PAYMENTREQUEST_0_DESC'.$p.'='.urlencode($item['ItemDesc']);
				$padata .=	'&L_PAYMENTREQUEST_0_AMT'.$p.'='.urlencode($item['ItemPrice']);
				$padata .=	'&L_PAYMENTREQUEST_0_QTY'.$p.'='. urlencode($item['ItemQty']);
			}		
						
			$padata .=	'&NOSHIPPING='.$noshipping; //set 1 to hide buyer's shipping address, in-case products that does not require shipping
						
			$padata .=	'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($this -> GetProductsTotalAmount($products));
			
			$padata .=	'&PAYMENTREQUEST_0_TAXAMT='.urlencode($charges['TotalTaxAmount']);
			$padata .=	'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($charges['ShippinCost']);
			$padata .=	'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($charges['HandalingCost']);
			$padata .=	'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($charges['ShippinDiscount']);
			$padata .=	'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($charges['InsuranceCost']);
			$padata .=	'&PAYMENTREQUEST_0_AMT='.urlencode($this->GetGrandTotal($products, $charges));
			$padata .=	'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->currency);
			
			//paypal custom template
			
			$padata .=	'&LOCALECODE='.$this->lang; //PayPal pages to match the language on your website;
			// $padata .=	'&LOGOIMG='.PPL_LOGO_IMG; //site logo
			$padata .=	'&CARTBORDERCOLOR=FFFFFF'; //border color of cart
			$padata .=	'&ALLOWNOTE=1';
						
			############# set session variable we need later for "DoExpressCheckoutPayment" #######			
			$httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $padata);
			
			//Respond according to message we receive from Paypal
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
				$paypalmode = ($this->mode=='sandbox') ? '.sandbox' : '';			
				//Redirect user to PayPal store with Token received.				
				$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';				
				header('Location: '.$paypalurl);
			}
			else{				
				//Show error message			
				
				echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]). '. Probably you an using an Incorrect API information. Please double check the API username, password, signature and if are production or sandbox keys.</div>';				
				echo '<pre>';					
				print_r($httpParsedResponseAr);
				echo '</pre>';
				exit;
			}	
		}		
		
			
		function DoExpressCheckoutPayment($ppl_products, $ppl_charges){
			
			if(!empty($ppl_products)&&!empty($ppl_charges)){
				
				$products=$ppl_products;
				
				$charges=$ppl_charges;
				
				$padata  = 	'&TOKEN='.urlencode($_GET['token']);
				$padata .= 	'&PAYERID='.urlencode($_GET['PayerID']);
				$padata .= 	'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE");
				$padata .=	'&BUTTONSOURCE=NetFactorSL_SI_Custom';								
				
				//set item info here, otherwise we won't see product details later	
				
				foreach($products as $p => $item){
					
					$padata .=	'&L_PAYMENTREQUEST_0_NAME'.$p.'='.urlencode($item['ItemName']);
					$padata .=	'&L_PAYMENTREQUEST_0_NUMBER'.$p.'='.urlencode($item['ItemNumber']);
					$padata .=	'&L_PAYMENTREQUEST_0_DESC'.$p.'='.urlencode($item['ItemDesc']);
					$padata .=	'&L_PAYMENTREQUEST_0_AMT'.$p.'='.urlencode(str_replace(',','',$item['ItemPrice']));
					$padata .=	'&L_PAYMENTREQUEST_0_QTY'.$p.'='. urlencode($item['ItemQty']);
				}
			
				$padata .= 	'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($this -> GetProductsTotalAmount($products));
				$padata .= 	'&PAYMENTREQUEST_0_TAXAMT='.urlencode($charges['TotalTaxAmount']);
				$padata .= 	'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($charges['ShippinCost']);
				$padata .= 	'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($charges['HandalingCost']);
				$padata .= 	'&PAYMENTREQUEST_0_SHIPDISCAMT='.urlencode($charges['ShippinDiscount']);
				$padata .= 	'&PAYMENTREQUEST_0_INSURANCEAMT='.urlencode($charges['InsuranceCost']);
				$padata .= 	'&PAYMENTREQUEST_0_AMT='.urlencode($this->GetGrandTotal($products, $charges));
				$padata .= 	'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($this->currency);
				
				//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.

				$httpParsedResponseAr = $this->PPHttpPost('DoExpressCheckoutPayment', $padata);

				//vdump($httpParsedResponseAr);
				//exit;

				//Check if everything went ok..
				if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
					$this->GetTransactionDetails();
				}
				else{					
				    if ($httpParsedResponseAr["L_ERRORCODE0"] == '10486')
				    {
				        echo '<html><body><script type="text/javascript">window.history.back();</script></body></html>';
				        exit;
				    }
					echo '<div style="color:red"><b>Error : </b>'.esc_html(urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])).'</div>';					
					echo '<pre>';					
					print_r(($httpParsedResponseAr));						
					echo '</pre>';
					exit;
				}
			}
			else{				
				// Request Transaction Details				
				$this->GetTransactionDetails();
			}
		}
				
		function GetTransactionDetails(){
		
			// we can retrive transection details using either GetTransactionDetails or GetExpressCheckoutDetails
			// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
			
			$padata = 	'&TOKEN='.urlencode($_GET['token']);
			
			$httpParsedResponseAr = $this->PPHttpPost('GetExpressCheckoutDetails', $padata);

			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])){
				return $httpParsedResponseAr;					
			} 
			else  {				
				echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.esc_html(urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])).'</div>';				
				echo '<pre>';
				print_r(esc_html($httpParsedResponseAr));					
				echo '</pre>';

			}
		}
		
		function PPHttpPost($methodName_, $nvpStr_) {
				
				// Set up your API credentials, PayPal end point, and API version.
				$API_UserName = ($this->API_UserName);
				$API_Password = ($this->API_Password);
				$API_Signature = ($this->API_Signature);
				
				$paypalmode = ($this->mode=='sandbox') ? '.sandbox' : '';
		
				$API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
				$version = urlencode('109.0');
			
				// Set the parameters.
				$ppparams = array();
                $ppparams['METHOD'] = $methodName_;
                $ppparams['VERSION'] = $version;
                $ppparams['PWD'] = $API_Password;
                $ppparams['USER'] = $API_UserName;
                $ppparams['SIGNATURE'] = $API_Signature;                
                parse_str(substr($nvpStr_,1), $fields);
                foreach ($fields as $item => $value)
                    $fields[$item] = urldecode($value);
                $ppparams = array_merge ($ppparams, $fields);
                
                
                $response = wp_remote_post( 
                                     $API_Endpoint,                                     
                                     array ( 'timeout' => 45, 'body' => $ppparams )
                                 );
			
				if ( !is_array( $response ) || is_wp_error( $response ) ) {
					exit("$methodName_ failed.");
				}
			
				// Extract the response details.
				$httpResponseAr = explode("&", $response['body']);
			
				$httpParsedResponseAr = array();
				foreach ($httpResponseAr as $i => $value) {
					
					$tmpAr = explode("=", $value);
					
					if(sizeof($tmpAr) > 1) {
						
						$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
					}
				}
			
				if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
					
					exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
				}
			
			return $httpParsedResponseAr;
		}
	}

}
?>