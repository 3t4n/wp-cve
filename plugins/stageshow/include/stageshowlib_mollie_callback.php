<?php

/*
Description: Mollie API Functions

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

include 'stageshowlib_gateway_callback.php';
			      
require_once STAGESHOWLIB_FILE_PATH.'lib/Mollie/API/vendor/autoload.php';
require_once STAGESHOWLIB_FILE_PATH.'lib/Mollie/API/src/Types/PaymentStatus.php';

if (!class_exists('StageShowLib_mollie_CallbackClass')) 
{
		
	class StageShowLib_mollie_CallbackClass extends StageShowLibGatewayCallbackClass // Define class
	{
		function GetPaymentObj($mollieTxnId)
		{
			$this->AddToLog('GetPaymentObj-mollieTxnId:  '.$mollieTxnId);
			
			$MollieObjError = '';
			
			try
			{
				if (!empty($_GET['gatewayId']) && is_numeric($_GET['gatewayId']))
				{
					$acctNo = $_GET['gatewayId'];
				}
				else
				{
					$acctNo = 1;
				}
				
				$optionGatewaySuffix = ($acctNo > 1) ? '_'.$acctNo : '';
				if ($this->notifyDBaseObj->getOption('MollieServer') == 'Live')
				{
					$apiKey = $this->notifyDBaseObj->getOption('MollieLiveAPIkey'.$optionGatewaySuffix);				
					$this->AddToLog('Mollie Environment: LIVE' );
				}
				else
				{
					$apiKey = $this->notifyDBaseObj->getOption('MollieTestAPIkey'.$optionGatewaySuffix);				
					$this->AddToLog('Mollie Environment: TEST' );
				}

				$mollie = new \Mollie\Api\MollieApiClient();
				$mollie->setApiKey($apiKey);
			
				// Retrieve the payment's current state.				 
				$this->payment  = $mollie->payments->get($mollieTxnId);
				
			}
			catch (Mollie_API_Exception $e)
			{
				$MollieObjError = "API call failed: " . StageShowLibMigratePHPClass::Safe_htmlspecialchars($e->getMessage());
				$this->AddToLog($MollieObjError);
			}
			
			return $MollieObjError;
		}
		
		function DoCallback()
		{
			$MollieCBError = '';
			$IPNRxdMsg = ' Gateway Callback Received at ' . date(DATE_RFC822);
			$this->AddToLog($IPNRxdMsg);

			// Check if this is a test request by Mollie
			if (!empty($_GET['testByMollie']))
			{
				$this->AddToLog('Test request by Mollie');
				$this->gatewayStatus = 'OK';
				return;
			}
			 
			if (!empty($_GET['gatewaycbid']) && is_numeric($_GET['gatewaycbid']))
			{
				$this->AddToLog('HTTP return from Mollie');
				
				$saleId = $_GET['gatewaycbid'];
				$results = $this->notifyDBaseObj->GetSale($saleId);
				if (count($results) == 0) return '';
				
				// Get Mollie transaction id from saleId
				$paymentId = $results[0]->salePPExpToken;
				$url = $results[0]->saleCheckoutURL;

				$session_gatewaystatus = '';
				
				if ($results[0]->saleStatus != PAYMENT_API_SALESTATUS_COMPLETED)
				{
					$this->AddToLog("Checkout Cancelled");
					if ($this->notifyDBaseObj->isOptionSet('CheckoutCancelledURL') != false)
						$url = $this->notifyDBaseObj->getOption('CheckoutCancelledURL');
					else
						$session_gatewaystatus = 'Cancelled';						
				}
				else
				{
					$this->AddToLog("Checkout Complete");
					if ($this->notifyDBaseObj->isOptionSet('CheckoutCompleteURL') != false)
						$url = $this->notifyDBaseObj->getOption('CheckoutCompleteURL');						
					else
						$session_gatewaystatus = 'Complete';						
				}
					
				$this->AddToLog("return url:  $url");
				
				if ($session_gatewaystatus != '')
				{
					$this->AddToLog("gatewaystatus:  $session_gatewaystatus");
					$this->notifyDBaseObj->SetSessionElem('gatewaystatus', $session_gatewaystatus);
					$this->notifyDBaseObj->SetSessionElem('gatewaycbid', $saleId);
					$this->notifyDBaseObj->SetSessionElem('gatewaytxnid', $paymentId);
				}
				
				$this->notifyDBaseObj->UpdateCheckoutURL($saleId);
				
				header( 'Location: '.$url );
				exit;
			}
			
			$MollieCBError = $this->GetPaymentObj($_POST["id"]);

			if ($MollieCBError === '')
			{				
				$saleId = $this->payment->metadata->saleId;
				
				// Update the order in the database.
				if (!$this->payment->isPaid())
				{
					if ( $this->payment->isExpired() || !$this->payment->isOpen() )
					{
						// The payment isn't paid and isn't open anymore. We can assume it was aborted.
						$this->notifyDBaseObj->UpdateSaleIDStatus($saleId, PAYMENT_API_SALESTATUS_TIMEOUT);
						return;
					}
				}

				
				// TODO: Check that transaction has not been previously completed
				$Txn_id = StageShowLibMigratePHPClass::Safe_substr($this->payment->id, 3);	// saleTxnId is payment ID with the 'tr_' prefixx removed
				$txnStatus = '';
				$MollieCBError = $this->CheckTxnStatus($Txn_id, PAYMENT_API_SALESTATUS_COMPLETED, $txnStatus);
			}
			
			if ($MollieCBError === '')
			{
				$this->AddToLog('TxnStatus:  '.$txnStatus);
				
				if ($txnStatus !== '')
				{
					// TODO: Gateway Notify - Update sale status
					$saleId = $this->notifyDBaseObj->UpdateSaleStatus($Txn_id, PAYMENT_API_SALESTATUS_COMPLETED, false);
				}
				else
				{
					// TODO: Gateway Notify - Log Sale to DB
					//$results['saleDateTime'] = current_time('mysql');
					$results['saleID'] = $saleId;
					$results['saleTxnId'] = $Txn_id;
					$results['saleStatus'] = PAYMENT_API_SALESTATUS_COMPLETED;
					$results['salePaid'] = $this->payment->amount->value;
					$results['salePaidDateTime'] = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, StageShowLibMigratePHPClass::Safe_strtotime($this->payment->paidAt));					
					
					$results['saleMethod'] = 'Mollie';
					
					if (isset($this->payment->details->consumerName))
					{
						$results['saleLastName'] = $this->payment->details->consumerName;
						if (isset($this->payment->method) && ($this->payment->method == 'paypal'))
						{
							$results['saleEMail'] = $this->payment->details->consumerAccount;
							$results['saleTransactionFee'] = $this->payment->details->paypalFee->value;
						}
					}
					else
					{
						$results['saleLastName'] = $this->payment->details->cardHolder;
					}
					
					$saleId = $this->notifyDBaseObj->CompleteSale($results, StageShowLibSalesCartDBaseClass::STAGESHOWLIB_FROMCALLBACK, false);
					$this->AddToLog('Sale Status: '.$results['saleStatus']);
				}
					
				if ($saleId > 0)
				{
					$this->AddToLog('Sale Logged - SaleID: '.$saleId);
											
					// FUNCTIONALITY: Gateway Notify - Send Sale EMail to buyer (and admin))
/*
					if ($Payment_status == PAYMENT_API_SALESTATUS_COMPLETED)
*/
					{
						$emailStatus = $this->notifyDBaseObj->EMailSale($saleId);
						$this->AddToLog('EMail Status: '.$emailStatus);
						$this->emailSent = true;
					}
/*					
					if ($Payment_status == PAYMENT_API_SALESTATUS_UNVERIFIED)
					{
						$this->GatewayErrorEMail("Payment Verification Failed", $this->LogMessage);
					}
*/
				}
				else if ($saleId < 0)
				{
					// Send Sale Rejected EMail - No Matching Rows
					$saleError = isset($this->notifyDBaseObj->saleError) ? '('.$this->notifyDBaseObj->saleError.')' : '';
					$this->AddToLog("Sale Rejected $saleError - SaleID: $saleID");
					
					$emailStatus = $this->PaymentRejectedEMail($results);

					$this->AddToLog('EMail Status: '.$emailStatus);
					$this->emailSent = true;
				}
				else
				{
					// Error in LogSale()
					$MollieCBError = 'DB Error in LogSale';
				}
			}
			
			if ($MollieCBError === '')
			{
				StageShowLibEscapingClass::Safe_EchoHTML("OK<br>\n");
			}
			else
			{
				$this->AddToLog('Gateway Callback Rejected: '.$MollieCBError);
				StageShowLibEscapingClass::Safe_EchoHTML("$MollieCBError<br>\n");
			}
			
			$this->AddToLog("---------------------------------------------------------------------");
		}
				
	}
}

new StageShowLib_mollie_CallbackClass(STAGESHOWLIB_DBASE_CLASS, __FILE__);	
