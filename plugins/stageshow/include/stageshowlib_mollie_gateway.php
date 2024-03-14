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

include_once('stageshowlib_gatewaybase.php');
include_once('stageshowlib_table.php');

$pluginPath = dirname(dirname(__FILE__));
require_once $pluginPath.'/lib/Mollie/API/vendor/autoload.php';

// Definitions for API Interface Functions
if (!class_exists('StageShowLib_mollie_GatewayClass')) 
{
	// Constant definitions for the gateway 
	define('MOLLIE_API_LOGIN_APIKEY_TEXTLEN', 40);	
	define('MOLLIE_API_LOGIN_APIKEY_EDITLEN', 40);
	
	if (!defined('MOLLIE_API_CREATE_URL'))
		define('MOLLIE_API_CREATE_URL', 'https://api.mollie.nl/v1/payments');
		
	if (!defined('MOLLIE_API_NOTIFY_URL'))
		define('MOLLIE_API_NOTIFY_URL', STAGESHOWLIB_CALLBACKROOT_URL.'mollie');
	
	define('MOLLIE_API_BUYERDETAILS_NONE', 'None');
	define('MOLLIE_API_BUYERDETAILS_ADDRESS', 'Address');
	define('MOLLIE_API_BUYERDETAILS_NAMEANDEMAIL', 'Name_EMail');
	define('MOLLIE_API_BUYERDETAILS_ALL', 'Name_EMail_Address');
	
	class StageShowLib_mollie_GatewayClass extends StageShowLibGatewayBaseClass // Define class
	{
		function __construct( $opts )
		{
			parent::__construct($opts);
		}
			        
		static function GetName()
		{
			return 'Mollie';
		}
		
		static function GetType()
		{
			return 'mollie';
		}
		
		static function GetDefaultCurrency()
		{
			// Default Currency
			return 'EUR';
		}
		
		function GetCurrencyOptionID()
		{
			// Variable name of the Currency Type entry in the settings 
			return 'MollieCurrencyType';
		}
		
		function GetCurrencyTable()
		{
			// The currency table 
			return array( 
				array('Name' => 'Euros ', 'Currency' => 'EUR', 'Symbol' => '&#8364;', 'Char' => '', 'Position' => 'Left', 'Format' => '%01.2f'),
			);
		}
		
		function IsTestServer()
		{
			return false;
		}
		
		//Returns an array of admin options
		function Gateway_GetOptions() 
		{
			// Create the gateway options defaults
			$ourOptions = array();
			
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
				
				$ourOptions['MollieTestAPIkey'.$optionGatewaySuffix] = '';
				$ourOptions['MollieLiveAPIkey'.$optionGatewaySuffix] = '';
			}
			$ourOptions['MollieServer'] = '';
				
			$ourOptions = array_merge($ourOptions, parent::Gateway_GetOptions());
			$ourOptions['MollieCurrencyType'] = 'EUR';

			return $ourOptions;
		}
		
		function Gateway_SettingsRowsDefinition()
		{
			// Define setting rows for gateway
			
			$currencyOptionID = $this->GetCurrencyOptionID();
			$currSelect = $this->GetCurrencyList();
				
			$buyerSelect = array(
				MOLLIE_API_BUYERDETAILS_NONE."|".__("Do Not Collect", 'stageshow'),
				MOLLIE_API_BUYERDETAILS_ADDRESS."|".__("Collect Address", 'stageshow'),
				MOLLIE_API_BUYERDETAILS_NAMEANDEMAIL."|".__("Collect Name & EMail", 'stageshow'),
				MOLLIE_API_BUYERDETAILS_ALL."|".__("Collect Name, Address & EMail", 'stageshow'),			
			);
			
			$rowDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Server',          StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-mollie', StageShowLibTableClass::TABLEPARAM_ID => 'MollieServer',          StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => array('Live|'.__('Live', 'stageshow'), 'Test|'.__('Test', 'stageshow')), ),
			);
			
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
				
				$rowDefs = StageShowLibAdminListClass::MergeSettings($rowDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Test API key',     StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-mollie', StageShowLibTableClass::TABLEPARAM_ID => 'MollieTestAPIkey'.$optionGatewaySuffix,      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => MOLLIE_API_LOGIN_APIKEY_TEXTLEN,    StageShowLibTableClass::TABLEPARAM_SIZE => MOLLIE_API_LOGIN_APIKEY_EDITLEN, ),
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Live API key',     StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-mollie', StageShowLibTableClass::TABLEPARAM_ID => 'MollieLiveAPIkey'.$optionGatewaySuffix,      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => MOLLIE_API_LOGIN_APIKEY_TEXTLEN,    StageShowLibTableClass::TABLEPARAM_SIZE => MOLLIE_API_LOGIN_APIKEY_EDITLEN, ),
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale Description', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-mollie', StageShowLibTableClass::TABLEPARAM_ID => 'MollieSaleDescription'.$optionGatewaySuffix, StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => MOLLIE_API_LOGIN_APIKEY_TEXTLEN,    StageShowLibTableClass::TABLEPARAM_SIZE => MOLLIE_API_LOGIN_APIKEY_EDITLEN, ),
					));
			}
				
			$rowDefs = StageShowLibAdminListClass::MergeSettings($rowDefs, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Currency',        StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-mollie', StageShowLibTableClass::TABLEPARAM_ID => $currencyOptionID,        StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $currSelect, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Contact Details', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-mollie', StageShowLibTableClass::TABLEPARAM_ID => 'MollieBuyerDetails',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $buyerSelect, ),
				));
					
			return $rowDefs;
		}
		
		function Gateway_SystemFields()
		{
			// TODO: Define system fields - This function can be removed if there are no client fields
			
			// Returns an array of fields that are added for this gateway
			$systemFields = array(
				);
				
			return $systemFields;
		}
		
		function Gateway_ClientFields()
		{
			// Returns an array defining fields that must 
			// be specified by the purchaser before checkout
			$MollieBuyerDetails = $this->myDBaseObj->adminOptions['MollieBuyerDetails'];
			if ($MollieBuyerDetails == MOLLIE_API_BUYERDETAILS_NONE)
				return array();
				
			if ($MollieBuyerDetails != MOLLIE_API_BUYERDETAILS_ADDRESS)
			{
				$clientFields = array(
					'saleFirstName' => 'First Name',
					'saleLastName'  => 'Last Name',
					'saleEMail'     => 'EMail',
					);				
			}
			else
			{
				$clientFields = array();
			}
			
			if ($MollieBuyerDetails != MOLLIE_API_BUYERDETAILS_NAMEANDEMAIL)
			{
				$clientFields = array_merge($clientFields, array(
					'salePPStreet'  => 'Address',
					'salePPCity'    => 'Town/City',
					'salePPState'   => 'County',
					'salePPZip'     => 'Postcode',
					'salePPCountry' => 'Country',
					'salePPPhone'   => 'Phone',
				));				
			}	
			
			return $clientFields;
		}
		
		function Gateway_LoadUserScripts()
		{
		}
		
		function Gateway_LoadAdminStyles()
		{
		}
		
		function LoginGatewayAPI($adminOptions, $dbgOptions)
		{
			$this->GatewayNotifyURL = MOLLIE_API_NOTIFY_URL;							
		}
		
		function IsGatewayConfigured($adminOptions)
		{
			// Check gateway settings to see if it is configured
			$optionGatewaySuffix = $this->GetOptionGatewaySuffix(1);
			if ($adminOptions['MollieServer'] == 'Live')
			{
				$apiKey = $adminOptions['MollieLiveAPIkey'.$optionGatewaySuffix];				
			}
			else if ($adminOptions['MollieServer'] == 'Test')
			{
				$apiKey = $adminOptions['MollieTestAPIkey'.$optionGatewaySuffix];				
			}
			else
			{
				return false;
			}	
				
			return ($apiKey != '');					
		}
		
		function VerifyLogin()
		{
			// TODO: If login can be verified ... do it here
			return '';
		}
						
		function GetGatewayRedirectURL($saleRecord)
		{
			// Build URL of gateway payment page 
			$mollieURL = '';

			$saleId = $saleRecord[0]->saleID;
			$totalPaid = 0;
			
			foreach ($saleRecord as $saleItem)			
			{
				$ticketQty = isset($saleItem->ticketQty) ? $saleItem->ticketQty : $saleItem->orderQty;
				$ticketPaid = isset($saleItem->ticketPaid) ? $saleItem->ticketPaid : $saleItem->orderPaid;
				
				$qty = $ticketQty;
				$itemPaid = $ticketPaid/$qty;
				$totalPaid += ($itemPaid * $qty);
			}
			
			$myDBaseObj = $this->myDBaseObj;
			
			$optionGatewaySuffix = '';
			if (isset($saleRecord[0]->showGatewayIndex)) 
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($saleRecord[0]->saleGatewayIndex);
			}
			
			if ($myDBaseObj->getOption('MollieServer') == 'Live')
			{
				$apiKey = $myDBaseObj->getOption('MollieLiveAPIkey'.$optionGatewaySuffix);				
			}
			else
			{
				$apiKey = $myDBaseObj->getOption('MollieTestAPIkey'.$optionGatewaySuffix);				
			}
			
			$description = $myDBaseObj->getOption('MollieSaleDescription'.$optionGatewaySuffix);
			if ($description == '')	
			{
				$description = $myDBaseObj->getOption('OrganisationID'.$optionGatewaySuffix);
			}
			
			$mollie = new \Mollie\Api\MollieApiClient();
			$mollie->setApiKey($apiKey);
						
			$callbackURL = MOLLIE_API_NOTIFY_URL;
			$returnURL = STAGESHOWLIB_CALLBACKROOT_URL.'mollie'."?gatewaycbid=$saleId";
			
	        $metadata = array();
	        $metadata['saleId'] = $saleId;

			$amount = array();
			$amount['currency'] = "EUR";
			$amount['value'] = sprintf("%01.2f", $totalPaid);

			$paymentAttrs = array();
			$paymentAttrs['amount']      = $amount;
	        $paymentAttrs['description'] = $description;
	        $paymentAttrs['redirectUrl'] = $returnURL;

			if (!defined('STAGESHOWLIB_LOCAL_SERVER'))
			{
		        $paymentAttrs['webhookUrl']  = $callbackURL;
			}

	        $paymentAttrs['metadata']    = $metadata;


			try
			{
			    // Create the Payment object on the Mollie server		        
			    $payment = $mollie->payments->create($paymentAttrs);		
			    
			    
			    // Save the PaymentID to the database ....				        
			    $myDBaseObj->UpdateSaleToken($saleId, $payment->id);
			    $mollieURL = $payment->getCheckoutUrl();
			}
			catch (Mollie_API_Exception $e)
			{
			    $dbgMsg  = "API call failed: " . StageShowLibMigratePHPClass::Safe_htmlspecialchars($e->getMessage());
			    $dbgMsg .= " on field " . StageShowLibMigratePHPClass::Safe_htmlspecialchars($e->getField());
				$this->OutputDebug($dbgMsg, true);
			}

			return $mollieURL;					
		}

	}
}

?>