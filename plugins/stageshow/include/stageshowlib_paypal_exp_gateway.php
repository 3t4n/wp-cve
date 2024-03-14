<?php
/*
Description: Payment Gateway API Functions

Copyright 2020 Malcolm Shergold

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Definitions for API Interface Functions
if (!class_exists('StageShowLib_paypal_exp_GatewayClass')) 
{
	include_once('stageshowlib_paypal_gateway.php');
			
	if (!defined('PAYMENT_API_SALESTATUS_PENDINGPPEXP'))
		define('PAYMENT_API_SALESTATUS_PENDINGPPEXP', 'PendingPPExp');
		
	if (!defined('STAGESHOW_PAYPALEXPRESSBUTTON_URL'))
	{
		define('STAGESHOW_PAYPALEXPRESSBUTTON_URL', 'https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif');		
	}

	class StageShowLib_paypal_exp_GatewayClass extends StageShowLib_paypal_GatewayClass // Define class
	{
		const STAGESHOWLIB_CHECKOUTSTYLE_EXPRESS = 2;
		const STAGESHOWLIB_CHECKOUTSTYLE_STANDARD_AND_EXPRESS = 3;

		function __construct( $opts )
		{
			parent::__construct($opts);
		}
		
		static function GetParent()
		{
			return 'paypal';
		}
		
		static function GetID()
		{
			return 'paypal_exp';
		}
		
		function IsCallback($pluginObj)
		{
			if (!StageShowLibUtilsClass::IsElementSet('request', 'ppexp') || !StageShowLibUtilsClass::IsElementSet('request', 'token') || !StageShowLibUtilsClass::IsElementSet('request', 'PayerID'))
				return false;			
			
			$myDBaseObj = $this->myDBaseObj;
			
			// Get PayPal Express token and PayerID from POST data
			$token = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, 'token');
			$payerID = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, 'PayerID');
				
			switch ($_REQUEST['ppexp'])
			{
				case 'ppexp_ok':
					$pprslt = $this->GetCheckoutDetailsFromToken($token);
					if ($pprslt->status != 'OK')
					{
						$pluginObj->checkoutMsg = __('Cannot Checkout', 'stageshow').' - ';
						$pluginObj->checkoutMsg .= __('Error getting purchaser details from PayPal', 'stageshow');
						return true;						
					}
					
					$cartContents = new stdClass();
					$saleID = $myDBaseObj->GetCartFromToken($token, $cartContents);
					if ($saleID == 0)
					{
						return 0;
					}
					
					$pprslt->saleDetails['saleID'] = $saleID;						
					$saleID = $myDBaseObj->CompleteSale($pprslt->saleDetails);

					$pluginObj->OnlineStore_OutputTrolley($cartContents);									
					
					StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" name="saleID" value="'.$saleID.'"/>'."\n");						
					StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" name="ppexp" value="ppexp_complete"/>'."\n");	
					StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" name="token" value="'.$token.'"/>'."\n");	
					StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" name="PayerID" value="'.$payerID.'"/>'."\n");			
					
					return true;
					
				case 'ppexp_cancel':
					// User Cancelled on PayPal Payment page
					$saleID = $myDBaseObj->GetSaleIDFromToken($token);
					$myDBaseObj->DeleteSale($saleID);
					
					$pluginObj->checkoutMsg = __('Sale Cancelled', 'stageshow');					
					$pluginObj->checkoutMsgClass = $pluginObj->cssDomain.'-error';
					break;
				
				case 'ppexp_complete':
					// Lock Tables
					$this->myDBaseObj->LockSalesTable();
										
					// Check that Sale still exists in DB
					$saleID = $myDBaseObj->GetSaleIDFromToken($token);
					if ($saleID > 0)
					{
						// Update sale status to pending confirmation
						// This is so we can release locks while processing DoExpressCheckoutPayment 
						$myDBaseObj->UpdateSaleIDStatus($saleID, PAYMENT_API_SALESTATUS_PENDINGPPEXP);
					}
					
					// Release Tables
					$this->myDBaseObj->UnLockTables();
					
					if ($saleID == 0)
					{
						// Output Sale Cancelled Message
						$pluginObj->checkoutMsg = __('Sale Cancelled', 'stageshow').' - '.__('Timeout', 'stageshow');					
						return true;
					}
					
					$tickets = $myDBaseObj->GetPendingSale($saleID);
					
					// Call DoExpressCheckoutPayment
					$pprslt = $this->DoCheckoutPaymentByToken($token, $payerID, $tickets);
					if ($pprslt->status != 'OK')
					{
						// Output Sale Not Completed Message
						$myDBaseObj->UpdateSaleIDStatus($saleID, PAYMENT_API_SALESTATUS_ERROR);	// same as PAYMENT_API_SALESTATUS_SUSPENDED
						$pluginObj->checkoutMsg = __('Cannot Checkout', 'stageshow').' - DoExpressCheckoutPayment returned Error<br>';
						if (isset($pprslt->errMsg))
							$pluginObj->checkoutMsg .= $pprslt->errMsg;
						else
							$pluginObj->checkoutMsg .= __('Could not get sale details from PayPal', 'stageshow');
					
						// Send EMail to Admin
						$myDBaseObj->EMailSale($saleID);
						
						return true;						
					}

					// Update sale status and fee
					$results['saleID'] = $saleID;
					$results['saleDateTime'] = current_time('mysql');
					$results['saleStatus'] = PAYMENT_API_SALESTATUS_COMPLETED;
					$results['saleTxnId'] = $pprslt->saleTxnId;
					$results['salePaid'] = $pprslt->salePaid;
					$results['saleFee'] = $pprslt->saleFee;
						
  					// FUNCTIONALITY: IPN Notify - Log Sale to DB
					$saleID = $myDBaseObj->CompleteSale($results);
					
					$emailStatus = $myDBaseObj->EMailSale($saleID);
					
					if ($myDBaseObj->isDbgOptionSet('Dev_AutoRefund'))
					{
						$this->RefundTransaction($pprslt->saleTxnId);
					}
					
					$pluginObj->checkoutMsg = __('Sale Complete', 'stageshow').'<br>'.__('Confirmation EMail sent to', 'stageshow').' '.$tickets[0]->saleEMail;					
					$pluginObj->checkoutMsgClass = $pluginObj->cssDomain.'-ok';
		
					break;
				
				default:
					return false;
			}

			return true;
		}
		
		function Gateway_LoadUserScripts()
		{
			parent::Gateway_LoadUserScripts();
		
			// Add our own Javascript
			if (defined('STAGESHOWLIB_EXPRESSCHECKOUT_JSURL'))
			{
				wp_enqueue_script( 'paypal-express', STAGESHOWLIB_EXPRESSCHECKOUT_JSURL);
				$this->myDBaseObj->enqueue_script( 'stageshowlib_ppexp', plugins_url( 'js/stageshowlib_ppexp.js', __FILE__ ));				
			}
		}
		
		function Gateway_LoadAdminStyles()
		{
			parent::Gateway_LoadAdminStyles();
		}
		
		function Gateway_SettingsRowsDefinition()
		{
			$checkoutSelector = array(
				StageShowLibGatewayBaseClass::STAGESHOWLIB_CHECKOUTSTYLE_STANDARD.'|PayPal Standard Checkout', 
				StageShowLib_paypal_exp_GatewayClass::STAGESHOWLIB_CHECKOUTSTYLE_EXPRESS.'|PayPal Express Checkout', 
				StageShowLib_paypal_exp_GatewayClass::STAGESHOWLIB_CHECKOUTSTYLE_STANDARD_AND_EXPRESS.'|PayPal Standard and Express Checkouts'
				);
				
			$rowDefs = array();
		
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
		
				$rowDefs = array_merge($rowDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'API User',                        StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-paypal', 	StageShowLibTableClass::TABLEPARAM_ID => 'PayPalAPIUser'.$optionGatewaySuffix, StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_LOGIN_USER_TEXTLEN,        StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_LOGIN_EDITLEN, StageShowLibTableClass::TABLEPARAM_AFTER => 'PayPalMerchantID'.$optionGatewaySuffix, ),
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'API Password',                    StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-paypal', 	StageShowLibTableClass::TABLEPARAM_ID => 'PayPalAPIPwd'.$optionGatewaySuffix,  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_LOGIN_PWD_TEXTLEN,         StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_LOGIN_EDITLEN, StageShowLibTableClass::TABLEPARAM_AFTER => 'PayPalAPIUser'.$optionGatewaySuffix, ),
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'API Signature',                   StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-paypal', 	StageShowLibTableClass::TABLEPARAM_ID => 'PayPalAPISig'.$optionGatewaySuffix,  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_LOGIN_SIG_TEXTLEN,         StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_LOGIN_EDITLEN, StageShowLibTableClass::TABLEPARAM_AFTER => 'PayPalAPIPwd'.$optionGatewaySuffix,  ),
				));								
			}

			$rowDefs = array_merge($rowDefs, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Type',		           StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-paypal', StageShowLibTableClass::TABLEPARAM_ID => 'PayPalCheckoutType',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $checkoutSelector, StageShowLibTableClass::TABLEPARAM_AFTER => 'PayPalAPISig',),
			));								
			
			$rowDefs = array_merge(parent::Gateway_SettingsRowsDefinition(), $rowDefs);

			return $rowDefs;
		}
		
		function GetButtonImage($buttonID)
		{
			$buttonImage = '';
			
			switch ($buttonID)
			{					
				case 'checkout2':
					$buttonImage = STAGESHOW_PAYPALEXPRESSBUTTON_URL;
					break;
					
				case 'incontext':
					if (defined('STAGESHOW_INCONTEXTPAYBUTTON_URL'))
					{
						$buttonImage = STAGESHOW_INCONTEXTPAYBUTTON_URL;
					}
					break;
					
				case 'confirmpandpay':
					if (defined('STAGESHOW_CONFIRMANDPAYBUTTON_URL'))
					{
						$buttonImage = STAGESHOW_CONFIRMANDPAYBUTTON_URL;
					}
					break;	
					
				default:
					$buttonImage = parent::GetButtonImage($buttonID);
					break;				
			}
			
			return $buttonImage;		
		}
		
		function IsCheckout($pluginObj)
		{
			$PPExpButtonID = 'checkout2';
			$IncontextButtonID = 'incontext';
			
			if ($this->myDBaseObj->IsButtonClicked($PPExpButtonID)) 
				$this->checkoutSubmit = 'checkout2';
			elseif ($this->myDBaseObj->IsButtonClicked($IncontextButtonID)) 
			{
				$this->checkoutSubmit = 'checkout2';
				$this->InContextCheckout = true;
			}
			else
				$this->checkoutSubmit = parent::IsCheckout($pluginObj);
				
			return $this->checkoutSubmit;
		}
		
		function GetTrolleyButtonsHTML($pluginObj, $cartContents)
		{
			$checkoutButtonsHTML = '';
			$checkoutSelector = $this->myDBaseObj->gatewayObj->GetCheckoutType();
			
			if ($checkoutSelector != StageShowLib_paypal_exp_GatewayClass::STAGESHOWLIB_CHECKOUTSTYLE_EXPRESS)
			{
				$checkoutButtonsHTML = parent::GetTrolleyButtonsHTML($pluginObj, $cartContents);
			}
						
			if ( ($checkoutSelector == StageShowLib_paypal_exp_GatewayClass::STAGESHOWLIB_CHECKOUTSTYLE_EXPRESS)
			  || ($checkoutSelector == StageShowLib_paypal_exp_GatewayClass::STAGESHOWLIB_CHECKOUTSTYLE_STANDARD_AND_EXPRESS) )
			{
				$buttonText = __('PayPal Express', 'stageshow');
				$buttonType = $pluginObj->GetButtonTextAndTypeDef($buttonText, 'checkout2');
				$checkoutButtonsHTML .= "<input $buttonType/>\n";
				if (defined('STAGESHOWLIB_EXPRESSCHECKOUT_JSURL'))
				{
					$buttonText = __('In Context', 'stageshow');
					$buttonType = $pluginObj->GetButtonTextAndTypeDef($buttonText, 'incontext');
					$checkoutButtonsHTML .= "<input $buttonType/>\n";
				}
			}				
			
			return $checkoutButtonsHTML;
		}
		
		function GetPayPalExpressItems($saleDetails)
		{
			$ppexpParams = array();
			
			foreach ($saleDetails as $saleItem)
			{
				$ppexpSale = new stdClass();
				$ppexpSale->name = $saleItem->ticketName;
				$ppexpSale->qty = $saleItem->ticketQty;
				$ppexpSale->amt = $saleItem->ticketPaid/$saleItem->ticketQty;
				if (isset($this->InContextCheckout))
					$saleItem->category = 'Digital';
				
				$ppexpParams[] = $ppexpSale;
			}
			
			return $ppexpParams;
		}
		
		function GetPayPalExpressToken($saleDetails)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$expCORslt = new stdClass();

			$ppexpParams = $this->GetPayPalExpressItems($saleDetails);
					
			$logoURL = $myDBaseObj->getImageURL('PayPalLogoImageFile');				
			$expCORslt->status = $this->SetExpressCheckout($ppexpParams, $logoURL);
			$expCORslt->errMsg = '';
			if ($expCORslt->status == 'OK')
			{
				$expCORslt->token = $this->APIResponses['TOKEN'];
			}
			else
			{
				if (isset($this->APIResponses['L_LONGMESSAGE0']))
					$expCORslt->errMsg = $this->APIResponses['L_LONGMESSAGE0'];
				else
					$expCORslt->errMsg = $expCORslt->status;
			}
			
			return $expCORslt;
		}	
		
		function GetGatewayRedirectURL($saleRecord)
		{
			$myDBaseObj = $this->myDBaseObj;

			if ($this->checkoutSubmit == 'checkout2')
			{	
				$saleId = $saleRecord[0]->saleID;
				
				$pprslt = $this->GetPayPalExpressToken($saleRecord);
				if ($pprslt->status != 'OK')
				{
					$this->checkoutMsg = __('Cannot Checkout', 'stageshow').' - SetExpressCheckout returned Error<br>';
					if (isset($pprslt->errMsg))
						$this->checkoutMsg .= $pprslt->errMsg;
					else
						$this->checkoutMsg .= __('Could not get token from PayPal', 'stageshow');
					return '';						
				}
			
				// Log PayPal Express token with sale 
				$myDBaseObj->UpdateSaleToken($saleId, $pprslt->token);
									
				if (isset($this->InContextCheckout))
				{
					$paypalURL = 'https://www.paypal.com/incontext';
					$paypalURL = add_query_arg('token', $pprslt->token, $paypalURL);
				}
				else
				{
					$paypalURL = $this->GetGatewayURL();
					$paypalURL = add_query_arg('cmd', '_express-checkout', $paypalURL);
					$paypalURL = add_query_arg('token', $pprslt->token, $paypalURL);
					$paypalURL = add_query_arg('custom', $saleId, $paypalURL);
				}
				
				return $paypalURL;					
			}
				
			return parent::GetGatewayRedirectURL($saleRecord);
		}
		
		private function SetExpressCheckout($salesDetails, $logoURL, $headerURL = '')
		{
			$boxofficeURL = StageShowLibUtilsClass::GetPageURL();
			$ppexpCallbackURL = $boxofficeURL;

			$ppexpCancelURL   = add_query_arg('ppexp', 'ppexp_cancel', $ppexpCallbackURL);
			$ppexpCallbackURL = add_query_arg('ppexp', 'ppexp_ok', $ppexpCallbackURL);

			// Check that the PayPal login parameters have been set
			$apiStatus = '';
			if (!$this->IsAPIConfigured($apiStatus))
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Error using PayPal Express <br> $apiStatus");
				die;
				return $apiStatus;	// Cannot Execute - API Not Configured
			}
				
			$this->InitAPICallParams('SetExpressCheckout');
			$this->AddAPIParam('RETURNURL', $ppexpCallbackURL);
			$this->AddAPIParam('CANCELURL', $ppexpCancelURL);
			
			$saleTotal = 0;
			$itemNo = 0;
			foreach ($salesDetails as $sale)
			{
				$this->AddAPIParam('L_PAYMENTREQUEST_0_ITEMCATEGORY'.$itemNo, isset($sale->category) ? $sale->category : 'Physical');
				$this->AddAPIParam('L_PAYMENTREQUEST_0_NAME'.$itemNo, $sale->name);
				$this->AddAPIParam('L_PAYMENTREQUEST_0_NUMBER'.$itemNo, $itemNo);
				$this->AddAPIParam('L_PAYMENTREQUEST_0_QTY'.$itemNo, $sale->qty);
				$this->AddAPIParam('L_PAYMENTREQUEST_0_TAXAMT'.$itemNo, 0);
				$this->AddAPIParam('L_PAYMENTREQUEST_0_AMT'.$itemNo, $sale->amt);
				$this->AddAPIParam('L_PAYMENTREQUEST_0_DESC'.$itemNo, 'Download');
				$saleTotal += ($sale->qty * $sale->amt);
				$itemNo++;							
			}

			$this->AddAPIParam('PAYMENTREQUEST_0_CURRENCYCODE', $this->PayPalCurrency);
			$this->AddAPIParam('PAYMENTREQUEST_0_AMT', $saleTotal);
			$this->AddAPIParam('PAYMENTREQUEST_0_ITEMAMT', $saleTotal);
			$this->AddAPIParam('PAYMENTREQUEST_0_TAXAMT', 0);
			$this->AddAPIParam('PAYMENTREQUEST_0_DESC', 'Tickets');
			$this->AddAPIParam('PAYMENTREQUEST_0_PAYMENTACTION', 'Sale');
			
			if ($logoURL != '')
			{
				$this->AddAPIParam('LOGOIMG', $logoURL);
			}
			
			if ($headerURL != '')
			{
				$this->AddAPIParam('HDRIMG', $headerURL);
			}
			
			return $this->APIAction('SetExpressCheckout ');
		}

		function GetCheckoutDetailsFromToken($token)
		{
			$expCODetails = new stdClass();
			
			$expCODetails->status = $this->myDBaseObj->gatewayObj->GetExpressCheckoutDetails($token);
			if ($expCODetails->status == 'OK')
			{
				$APIResponses = $this->APIResponses;
					
				$expCODetails->saleDetails['saleEMail'] = $APIResponses['EMAIL'];
				$expCODetails->saleDetails['salePPPhone'] = isset($APIResponses['PHONENUM']) ? $APIResponses['PHONENUM'] : '';
				
				$expCODetails->saleDetails['saleFirstName'] = $APIResponses['FIRSTNAME'];
				$expCODetails->saleDetails['saleLastName'] = $APIResponses['LASTNAME'];
				
				$expCODetails->saleDetails['salePPName'] = $APIResponses['FIRSTNAME'].' '.$APIResponses['LASTNAME'];
				
				$expCODetails->saleDetails['salePPStreet'] = StageShowLibMigratePHPClass::Safe_trim($APIResponses['SHIPTOSTREET']);
				if (isset($APIResponses['SHIPTOSTREET2']))
				{
					$expCODetails->saleDetails['salePPStreet'] .= StageShowLibMigratePHPClass::Safe_trim($APIResponses['SHIPTOSTREET2']);
				}
				$expCODetails->saleDetails['salePPCity'] = $APIResponses['SHIPTOCITY'];
				$expCODetails->saleDetails['salePPState'] = $APIResponses['SHIPTOSTATE'];
				$expCODetails->saleDetails['salePPZip'] = $APIResponses['SHIPTOZIP'];
				$expCODetails->saleDetails['salePPCountry'] = $APIResponses['SHIPTOCOUNTRYNAME'];	
			}
			
			return $expCODetails;
		}
		
		function GetExpressCheckoutDetails($token)
		{
			// Check that the PayPal login parameters have been set
			if (!$this->IsAPIConfigured($apiStatus))
				return $apiStatus;	// Cannot Execute - API Not Configured
				
			$this->InitAPICallParams('GetExpressCheckoutDetails');
			$this->AddAPIParam('TOKEN', $token);
			
			return $this->APIAction('GetExpressCheckoutDetails ');
		}

		function DoCheckoutPaymentByToken($token, $payerID, $tickets)
		{
			$expCODetails = new stdClass();
			
			$items = $this->GetPayPalExpressItems($tickets);
			
			$expCODetails->status = $this->myDBaseObj->gatewayObj->DoExpressCheckoutPayment($token, $payerID, $items);
			if ($expCODetails->status == 'OK')
			{
				
				$expCODetails->saleTxnId = $this->myDBaseObj->gatewayObj->APIResponses['PAYMENTINFO_0_TRANSACTIONID'];
				$expCODetails->salePaid = $this->myDBaseObj->gatewayObj->APIResponses['PAYMENTINFO_0_AMT'];
				$expCODetails->saleFee = $this->myDBaseObj->gatewayObj->APIResponses['PAYMENTINFO_0_FEEAMT'];
			}
			
			return $expCODetails;
		}

		function DoExpressCheckoutPayment($token, $payerID, $items)
		{
			// Check that the PayPal login parameters have been set
			if (!$this->IsAPIConfigured($apiStatus))
				return $apiStatus;	// Cannot Execute - API Not Configured
				
			$this->InitAPICallParams('DoExpressCheckoutPayment');
			$this->AddAPIParam('TOKEN', $token);
			$this->AddAPIParam('PAYERID', $payerID);			
			$this->AddAPIParam('PAYMENTREQUEST_0_NOTIFYURL', PAYPAL_APILIB_IPN_NOTIFY_URL);
			$this->AddAPIParam('PAYMENTREQUEST_0_CURRENCYCODE', $this->PayPalCurrency);
			$this->AddAPIParam('PAYMENTREQUEST_0_PAYMENTACTION', 'Sale');
			
			$saleTotal = 0;
			$itemNo = 0;
			foreach($items as $item)
			{
				$this->AddAPIParam('L_PAYMENTREQUEST_0_NAME'.$itemNo, $item->name);
				$this->AddAPIParam('L_PAYMENTREQUEST_0_QTY'.$itemNo, $item->qty);
				$this->AddAPIParam('L_PAYMENTREQUEST_0_AMT'.$itemNo, $item->amt);
				$this->AddAPIParam('L_PAYMENTREQUEST_0_ITEMCATEGORY'.$itemNo, 'Digital');
				$saleTotal += ($item->qty * $item->amt);
				$itemNo++;
			}
			$this->AddAPIParam('PAYMENTREQUEST_0_AMT', $saleTotal);
			
			return $this->APIAction('DoExpressCheckoutPayment ');
		} 

	}
}

