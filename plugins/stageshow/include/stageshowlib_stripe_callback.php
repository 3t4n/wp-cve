<?php

/*
Description: Stripe Gateway API Functions

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

if (!defined('PAYMENT_API_SALESTATUS_COMPLETED'))
{
	if (StageShowLibUtilsClass::IsElementSet('server', 'HTTP_STRIPE_SIGNATURE'))
	{
		// Attempting to call out of WP - Redirect!
		$stripeSignature = $_SERVER['HTTP_STRIPE_SIGNATURE'];
		$gatewayURL = STAGESHOWLIB_CALLBACKROOT_URL.'stripe';
		$gatewayURL .= "?sig=$stripeSignature";
		StageShowLibEscapingClass::Safe_EchoHTML(file_get_contents($gatewayURL));
		die;
	}
	
}

include 'stageshowlib_gateway_callback.php';
include 'stageshowlib_stripe_base.php';
			      
if (!class_exists('Stripe\Stripe')) 
{
	$pluginPath = dirname(dirname(__FILE__));
	require_once $pluginPath.'/lib/stripe-php/init.php';
}

if (!class_exists('StageShowLib_stripe_CallbackClass')) 
{
		
	class StageShowLib_stripe_CallbackClass extends StageShowLibGatewayCallbackClass // Define class
	{

		function GetCurrencyTable()
		{
			return StageShowLib_StripeBaseClass::GetStripeCurrencyTable();
		}
		
		function ProcessStripeCallback()
		{
			// Set your secret key: remember to change this to your live secret key in production
			// See your keys here: https://dashboard.stripe.com/account/apikeys
			$apiKey = StageShowLib_StripeBaseClass::GetStripeKey($this, STRIPE_PRIVATEKEY);				
			\Stripe\Stripe::setApiKey($apiKey);
			\Stripe\Stripe::setApiVersion(STRIPE_APIVERSION);

			$this->AddToLog('Processing Stripe Callback');
				
			// You can find your endpoint's secret in your webhook settings
			$webhookKey = $this->notifyDBaseObj->getOption('StripeWebhookSecret');		
			$endpoint_secret = $webhookKey;

			$payload = @file_get_contents('php://input');
			$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
			$event = null;

			$rtnVal = new stdClass();
			  
			try {
			  $event = \Stripe\Webhook::constructEvent(
			    $payload, $sig_header, $endpoint_secret
			  );
			} catch(\UnexpectedValueException $e) {
			  // Invalid payload
			  $rtnVal->errorCode = 'UnexpectedValueException';
			  $rtnVal->statusCode = 400;
			  $rtnVal->exception = $e;
			  return $rtnVal;
			} catch(\Stripe\Exception\SignatureVerificationException $e) {
			  // Invalid signature
			  $rtnVal->errorCode = 'SignatureVerificationException';
			  $rtnVal->statusCode = 400;
			  $rtnVal->exception = $e;
			  return $rtnVal;
			}

			$rtnVal->errorCode = '';
			$rtnVal->statusCode = 200;
			$rtnVal->event = $event;
			
			return $rtnVal;
		}
		
		function DoCallback()
		{
			$StripeCBError = '';
			$IPNRxdMsg = ' Gateway Callback Received at ' . date(DATE_RFC822);
			$this->AddToLog($IPNRxdMsg);

			$this->AddToLog('HTTP return from Stripe');
				
			$stripeCurrency = $this->notifyDBaseObj->getOption('StripeCurrency');
			
			$currencyDef = $this->GetCurrencyDef($stripeCurrency);
			$paidMultiplier = $currencyDef['Multiplier'];
			
			$stripeRslt = $this->ProcessStripeCallback();
			
			if ($stripeRslt->errorCode != '')
			{
				http_response_code(400);
				exit;
			}
			
			$event = $stripeRslt->event;
			
			// Handle the checkout.session.completed event
			if ($event->type == 'checkout.session.completed') 
			{
			  	$session = $event->data->object;
			  
			  	// Get the saleID from the session object
			  	$sessionID = $session->id;
			  	$saleID = $session->client_reference_id;
			  	
			  	// Get Customer EMail from Stripe 
			  	$customer = $session->customer_details;
			
			  	// Get Payment Intent object from Stripe 
				$paymentIntentId = $session->payment_intent;
			  	$paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
			  	

				$chargeId = $paymentIntent->latest_charge;
				$chargeObject = \Stripe\Charge::retrieve($chargeId);

				$balanceTransactionId = '';
				if (isset($paymentIntent->charges->data[0]->balance_transaction))		  
				{
					// Get Balance Transaction object from Stripe 
					$balanceTransactionId = $paymentIntent->charges->data[0]->balance_transaction;
				}
				else if (isset($chargeObject->balance_transaction))	
				{
			  		$balanceTransactionId = $chargeObject->balance_transaction;
				}
				
				if (true)
				{
				  	$balanceTransaction = \Stripe\BalanceTransaction::retrieve($balanceTransactionId);

					$results['saleFee'] = $balanceTransaction->fee/$paidMultiplier;
				}
				
				$results['salePaid'] = $paymentIntent->amount_received/$paidMultiplier;

				// TODO Check that $Txn_id has not been previously processed
  				
  				// Get sale details from Event
				$results['saleTxnId'] = $sessionID;

				$results['saleEMail'] = $customer->email;

				$results['saleStatus'] = PAYMENT_API_SALESTATUS_COMPLETED;
  				
				if (isset($paymentIntent->charges->data[0]->billing_details))		  
				{
					$billingDetails = $paymentIntent->charges->data[0]->billing_details;
				}
				else
				{
					$billingDetails = $customer;
				}
					
				if (isset($billingDetails->name))
				{
					$results['salePPName'] = isset($billingDetails->name) ? $billingDetails->name : '';
					$results['salePPPhone'] = isset($billingDetails->phone) ? $billingDetails->phone : '';
			
					$lastSpacePosn = StageShowLibMigratePHPClass::Safe_strrpos($billingDetails->name, ' ');
					if ($lastSpacePosn !== false)
					{						
						$results['saleFirstName'] = StageShowLibMigratePHPClass::Safe_substr($billingDetails->name, 0, $lastSpacePosn);
						$results['saleLastName'] = StageShowLibMigratePHPClass::Safe_substr($billingDetails->name, $lastSpacePosn+1);
					}
					else
					{
						$results['saleFirstName'] = '';
						$results['saleLastName'] = $billingDetails->name;
					}
				}

				if (isset($billingDetails->address))
				{
					$address = $billingDetails->address;
					
					$street  = isset($address->line1) ? $address->line1 : '';
					if (isset($address->line2) && (StageShowLibMigratePHPClass::Safe_strlen($address->line2) > 0))
						$street .= ', '.$address->line2;
					
					$results['salePPStreet'] = $street;
					$results['salePPCity'] = isset($address->city) ? $address->city : '';
					$results['salePPState'] =  isset($address->state) ? $address->state : '';
					$results['salePPZip'] =  isset($address->postal_code) ? $address->postal_code : '';
					$results['salePPCountry'] =  isset($address->country) ? $address->country : '';		
				}

				$results['saleDateTime'] = current_time('mysql');

				$results['saleMethod'] = 'Stripe';

				$results['saleID'] = $saleID;
					
  				// Gateway Notify - Log Sale to DB
				$saleID = $this->notifyDBaseObj->CompleteSale($results);
				$this->AddToLog('Sale Status: '.$results['saleStatus']);

				if ($saleID > 0)
				{
					$this->AddToLog('Sale Logged - SaleID: '.$saleID);
											
  					// FUNCTIONALITY: Gateway Notify - Send Sale EMail to buyer (and admin))
					if ($results['saleStatus'] == PAYMENT_API_SALESTATUS_COMPLETED)
					{
						$emailStatus = $this->notifyDBaseObj->EMailSale($saleID);
						$this->AddToLog('EMail Status: '.$emailStatus);
						$this->emailSent = true;
					}
				}
				else if ($saleID < 0)
				{
					// Send Sale Rejected EMail 
					$saleError = isset($this->notifyDBaseObj->saleError) ? '('.$this->notifyDBaseObj->saleError.')' : '';
					$this->AddToLog("Sale Rejected $saleError - SaleID: $saleID");
					
					$emailStatus = $this->PaymentRejectedEMail($results);

					$this->AddToLog('EMail Status: '.$emailStatus);
					$this->emailSent = true;
				}
				else
				{
					// Error in LogSale()
					$this->AddToLog('Sale Rejected (General Error) - SaleID: '.$saleID);
					$CallbackError = 'DB Error in LogSale';
				}

			}

			http_response_code(200);
		}
				
	}
}

new StageShowLib_stripe_CallbackClass(STAGESHOWLIB_DBASE_CLASS, __FILE__);	



