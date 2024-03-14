<?php

/*
Description: NewGateway API Functions

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
			      
if (!class_exists('StageShowLib_newgateway_CallbackClass')) 
{
	if (!defined('STAGESHOWLIB_FILENAME_LASTITNCALL'))
		define('STAGESHOWLIB_FILENAME_LASTITNCALL', 'LastITNCall.txt');
		
	class StageShowLib_newgateway_CallbackClass extends StageShowLibGatewayCallbackClass // Define class
	{
		function DoCallback()
		{
			$NewGatewayCBError = '';
				
			$gatewayResponse = array();

			$IPNRxdMsg = ' Gateway Callback Received at ' . date(DATE_RFC822);
			$this->AddToLog($IPNRxdMsg);

			// Choose NewGateway target environment (test or Live)
			$testingMode = ($this->ourOptions['NewGatewayServer'] == 'Test');
			if ($testingMode)
			{
				$this->AddToLog('NewGateway Environment: TEST' );
			}
			else
			{
				$this->AddToLog('NewGateway Environment: LIVE' );
			}

			// TODO: Verify that the callback is genuine
			$VerifyURL = 'TBD';
			if ($this->skipNewGatewayServer)
			{
				$VerifyURL = '{Skipped}';
				$gatewayResponse['APIStatus'] = 200;
				$gatewayResponse['APIResponseText'] = 'VALID';
			}
			else
			{
				// ITN is enabled and message signature is correct
				$gatewayResponse = $this->VerifyGatewayCallback($VerifyURL, $pfParamString);					
			}
			
			$this->AddToLog("Gateway Verify URL: $VerifyURL");

			// TODO: Get any details from callback
				
			// TODO: Add details to debug log
			$this->AddToLog('---------------------------------------------');
			$this->AddToLog('Name:   ' . $Payer_name);
			$this->AddToLog('EMail:  ' . $Payer_email);

			// TODO: Check that transaction has not been previously completed
			$NewGatewayCBError = $this->CheckTxnStatus($Txn_id, $Payment_status, $txnStatus);
				
			if ($NewGatewayCBError === '')
			{
				if ($txnStatus !== '')
				{
					// TODO: Gateway Notify - Update sale status
					$saleID = $this->notifyDBaseObj->UpdateSaleStatus($Txn_id, $Payment_status);
				}
				else
				{
					// TODO: Gateway Notify - Log Sale to DB
					$saleID = $this->notifyDBaseObj->CompleteSale($results);
					$this->AddToLog('Sale Status: '.$results['saleStatus']);
				}
					
				if ($saleID > 0)
				{
					$this->AddToLog('Sale Logged - SaleID: '.$saleID);
											
					// FUNCTIONALITY: Gateway Notify - Send Sale EMail to buyer (and admin))
					if ($Payment_status == PAYMENT_API_SALESTATUS_COMPLETED)
					{
						$emailStatus = $this->notifyDBaseObj->EMailSale($saleID);
						$this->AddToLog('EMail Status: '.$emailStatus);
						$this->emailSent = true;
					}
					
					if ($Payment_status == PAYMENT_API_SALESTATUS_UNVERIFIED)
					{
						$this->GatewayErrorEMail("Payment Verification Failed", $this->LogMessage);
					}
				}
				else if ($saleID < 0)
				{
					// Send Sale Rejected EMail - No Matching Rows
					$this->AddToLog('Sale Rejected (Checkout Timed Out) - SaleID: '.$saleID);
					
					$emailStatus = $this->PaymentRejectedEMail($results);

					$this->AddToLog('EMail Status: '.$emailStatus);
					$this->emailSent = true;
				}
				else
				{
					// Error in LogSale()
					$NewGatewayCBError = 'DB Error in LogSale';
				}
			}
			
			if ($NewGatewayCBError === '')
			{
				echo "OK<br>\n";
			}
			else
			{
				$this->AddToLog('Gateway Callback Rejected: '.$NewGatewayCBError);
				echo "$NewGatewayCBError<br>\n";
			}
			
			$this->AddToLog("---------------------------------------------------------------------");
		}
				
	}
}

new StageShowLib_newgateway_CallbackClass(STAGESHOWLIB_DBASE_CLASS, __FILE__);	

