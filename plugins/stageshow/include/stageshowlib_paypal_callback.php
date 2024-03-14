<?php

/*
Description: PayPal API Functions

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

if (!defined('PAYPAL_APILIB_NVPTARGET_URL'))
	define ( 'PAYPAL_APILIB_NVPTARGET_URL', 'https://www.paypal.com/cgi-bin/webscr' );
	
include 'stageshowlib_gateway_callback.php';

	      
if (!class_exists('StageShowLib_paypal_CallbackClass')) 
{
	class StageShowLib_paypal_CallbackClass extends StageShowLibGatewayCallbackClass // Define class
	{
		function DoCallback()
		{
		
			$IPNError = '';
				
			$gatewayResponse = array();

			// read post from PayPal server and add 'cmd'
			$URLParamsArray = stripslashes_deep($_POST);

			$IPNRxdMsg = ' Gateway Callback Received at ' . date(DATE_RFC822);

			{
				// Add 'cmd' parameter to URL params array
				$URLParamsArray['cmd'] = '_notify-validate';
			
				$VerifyURL = $this->notifyDBaseObj->gatewayObj->PayPalVerifyURL;
				$gatewayResponse = $this->VerifyGatewayCallback($VerifyURL, $URLParamsArray);
			}

			// assign posted variables to local variables
			$Payment_status = StageShowLibUtilsClass::GetHTTPTextElem('post', 'payment_status');
			$Payment_amount = StageShowLibUtilsClass::GetHTTPNumber('post', 'mc_gross');
			$Payment_fee = StageShowLibUtilsClass::GetHTTPNumber('post', 'mc_fee');
			$Payment_currency = StageShowLibUtilsClass::GetHTTPTextElem('post', 'mc_currency');
			$Txn_id = StageShowLibUtilsClass::GetHTTPTextElem('post', 'txn_id');
			$Receiver_email = StageShowLibUtilsClass::GetHTTPEMail('post', 'receiver_email');
			$Payer_email = StageShowLibUtilsClass::GetHTTPEMail('post', 'payer_email');
			$Payer_name = StageShowLibUtilsClass::GetHTTPTextElem('post', 'first_name') . ' ' . StageShowLibUtilsClass::GetHTTPTextElem('post', 'last_name');


			// Check notification validation
			if ( ($gatewayResponse['APIStatus'] != 200 ) || ($gatewayResponse['APIResponseText'] === 'VERIFIED') )
			{
				if ($gatewayResponse['APIStatus'] != 200 )
				{

					if ($Payment_status == PAYMENT_API_SALESTATUS_COMPLETED)
						$Payment_status = PAYMENT_API_SALESTATUS_UNVERIFIED;					
				}

				if ($IPNError === '')
				{
					// Check $Payment_status and deal with "Pending" payment status
					if ( ($Payment_status !== PAYMENT_API_SALESTATUS_COMPLETED) 
					  && ($Payment_status !== PAYMENT_API_SALESTATUS_UNVERIFIED) 
					  && ($Payment_status !== 'Pending') )
					{
						$IPNError = 'Payment_status not completed';
					}
				}
				
				// Check that saleID is defined (passed as 'custom' parameter)
				$saleID = StageShowLibUtilsClass::GetHTTPInteger('post', 'custom');
				if (($IPNError === '') && !is_numeric($saleID))
				{
					$IPNError = 'saleID not defined';
				}
						
				if ($IPNError === '')
				{
					// Check that $Receiver_email is the EMail we expected
					$acctNo = $this->notifyDBaseObj->GetTxnGatewayID($saleID);
					$optionGatewaySuffix = $this->notifyDBaseObj->gatewayObj->GetOptionGatewaySuffix($acctNo);					
					$PayPalNotifyEMail = StageShowLibMigratePHPClass::Safe_trim($this->ourOptions['PayPalAPIEMail'.$optionGatewaySuffix]);
					if ((StageShowLibMigratePHPClass::Safe_strlen($PayPalNotifyEMail)>0) && (StageShowLibMigratePHPClass::Safe_strcasecmp(StageShowLibMigratePHPClass::Safe_trim($Receiver_email), $PayPalNotifyEMail) != 0))
					{
						$this->GatewayErrorEMail("Unknown PayPal Account", "Payment was from an unexpected PaylPal account ($Receiver_email)");
						// $IPNError = 'Unexpected PalPal Account';
					}
				}
				
				$txnStatus = '';
				if ($IPNError === '')
				{
					// Check that $Txn_id has not been previously processed
					$IPNError = $this->CheckTxnStatus($Txn_id, $Payment_status, $txnStatus);
				}
				
				if ($IPNError === '')
				{
					if ($txnStatus !== '')
					{
						$saleID = $this->notifyDBaseObj->UpdateSaleStatus($Txn_id, $Payment_status);
					}
					else
					{
						$results['saleTxnId'] = $Txn_id;
						$results['saleFirstName'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'first_name');
						$results['saleLastName'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'last_name');
						$results['saleEMail'] = $Payer_email;
						$results['saleStatus'] = $Payment_status;
						$results['salePaid'] = $Payment_amount;
						$results['saleFee'] = $Payment_fee;
						$results['salePPName'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'address_name');
						$results['salePPStreet'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'address_street');
						$results['salePPCity'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'address_city');
						$results['salePPState'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'address_state');
						$results['salePPZip'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'address_zip');
						$results['salePPCountry'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'address_country');
						$results['salePPPhone'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'contact_phone');
						$itemNo = 0;
						$lineNo = 1;
						while (true)
						{
							$itemNo++;
							if (!StageShowLibUtilsClass::IsElementSet('post', 'item_number' . $itemNo))
								break;
							$qty = StageShowLibUtilsClass::GetHTTPInteger('post', 'quantity' . $itemNo);
							if ($qty == 0)
								continue;
							$results['itemID' . $lineNo] = StageShowLibUtilsClass::GetHTTPInteger('post', 'item_number' . $itemNo);
							$results['itemName' . $lineNo] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'item_name' . $itemNo);
							$results['itemRef' . $lineNo] = StageShowLibUtilsClass::GetHTTPInteger('post', 'item_number' . $itemNo);
							$results['itemOption' . $lineNo] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'option_selection1_' . $itemNo);
							$results['qty' . $lineNo] = StageShowLibUtilsClass::GetHTTPInteger('post', 'quantity' . $itemNo);
							$results['itemPaid' . $lineNo] = StageShowLibUtilsClass::GetHTTPNumber('post', 'mc_gross_' . $itemNo);
							$lineNo++;
						}
						$results['saleDateTime'] = current_time('mysql');
						$results['saleMethod'] = $this->notifyDBaseObj->gatewayObj->GetPaymentMethod();
					
						$results['saleID'] = $saleID;
						
	  					// FUNCTIONALITY: Gateway Notify - Log Sale to DB
						$saleID = $this->notifyDBaseObj->CompleteSale($results);
					}
						
					if ($saleID > 0)
					{
	  					// FUNCTIONALITY: Gateway Notify - Send Sale EMail to buyer (and admin))
						if ($Payment_status == PAYMENT_API_SALESTATUS_COMPLETED)
						{
							$emailStatus = $this->notifyDBaseObj->EMailSale($saleID);
							$this->emailSent = true;
						}
						
						if ($Payment_status == PAYMENT_API_SALESTATUS_UNVERIFIED)
						{
							$this->GatewayErrorEMail("Payment Verification Failed", $this->LogMessage);
						}
						
						if ($this->notifyDBaseObj->isDbgOptionSet('Dev_AutoRefund'))
						{
							$this->notifyDBaseObj->gatewayObj->RefundTransaction($Txn_id);
						}
					}
					else if ($saleID < 0)
					{
						// Send Sale Rejected EMail - No Matching Rows
						$emailStatus = $this->PaymentRejectedEMail($results);
						$this->emailSent = true;
					}
					else
					{
						// Error in LogSale()
						$IPNError = 'DB Error in LogSale';
					}
				}
				
				if ($IPNError === '')
				{
					StageShowLibEscapingClass::Safe_EchoHTML("OK<br>\n");
				}
				else
				{
					StageShowLibEscapingClass::Safe_EchoHTML("$IPNError<br>\n");
				}
			}
			else if ($gatewayResponse['APIResponseText'] == 'INVALID')
			{
				StageShowLibEscapingClass::Safe_EchoHTML("INVALID<br>\n");
			}			

		}
	}
}


new StageShowLib_paypal_CallbackClass(STAGESHOWLIB_DBASE_CLASS, __FILE__);	

