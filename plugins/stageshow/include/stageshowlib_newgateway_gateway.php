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

// TODO: Change all instances of newgateway to the new gateway name (case sensitive)
// The following instances are included:
//		newgateway
//		NewGateway
//		NEWGATEWAY

// Definitions for API Interface Functions
if (!class_exists('StageShowLib_newgateway_GatewayClass')) 
{
	// TODO: Add any constant definitions for the gateway here
	define('NEWGATEWAY_API_LOGIN_MERCHANTID_TEXTLEN', 65);	
	define('NEWGATEWAY_API_LOGIN_MERCHANTID_EDITLEN', 65);
	
	if (!defined('NEWGATEWAY_API_NOTIFY_URL'))
		define('NEWGATEWAY_API_NOTIFY_URL', STAGESHOWLIB_URL.'include/stageshowlib_newgateway_callback.php');
	
	class StageShowLib_newgateway_GatewayClass extends StageShowLibGatewayBaseClass // Define class
	{
		function __construct( $opts )
		{
			parent::__construct($opts);
		}
			        
		static function GetName()
		{
			return 'NewGateway';
		}
		
		static function ListGateway($pluginID)
		{
			// TODO: Exclude template from list - Change this to include real gateways in list
			return false;
		}
		
		static function GetType()
		{
			return 'newgateway';
		}
		
		static function GetDefaultCurrency()
		{
			// TODO: Set the default Currency
			return 'GBP';
		}
		
		function GetCurrencyOptionID()
		{
			// TODO: Set the variable name of the Currency Type entry in the settings 
			return 'NewGatewayCurrencyType';
		}
		
		function GetCurrencyTable()
		{
			// TODO: Create the currency table - Example below is for GBP
			return array( 
				array('Name' => 'Pounds Sterling ',     'Currency' => 'GBP', 'Symbol' => '&#x20a4;',     'Char' => '£',  'Position' => 'Left', 'Format' => '%01.2f'),
			);
		}
		
		function IsTestServer()
		{
			return false;
		}
		
		//Returns an array of admin options
		function Gateway_GetOptions() 
		{
			// TODO: Create the gateway options defaults
			$ourOptions = array();
			
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
				
				$ourOptions['NewGatewayMerchantID'.$optionGatewaySuffix] = '';
			}
				
			$ourOptions = array_merge($ourOptions, parent::Gateway_GetOptions());

			return $ourOptions;
		}
		
		function Gateway_SettingsRowsDefinition()
		{
			// TODO: Define setting rows for gateway
			
			$currencyOptionID = $this->GetCurrencyOptionID();
			$currSelect = $this->GetCurrencyList();
				
			$rowDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Server',          StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-newgateway', StageShowLibTableClass::TABLEPARAM_ID => 'NewGatewayServer',          StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => array('Live|'.__('Live', $this->myDomain), 'Test|'.__('|Test', $this->myDomain)), ),
			);
			
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
				
				$rowDefs = StageShowLibAdminListClass::MergeSettings($rowDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Merchant ID',     StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-newgateway', StageShowLibTableClass::TABLEPARAM_ID => 'NewGatewayMerchantID'.$optionGatewaySuffix,      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => NEWGATEWAY_API_LOGIN_MERCHANTID_TEXTLEN,    StageShowLibTableClass::TABLEPARAM_SIZE => NEWGATEWAY_API_LOGIN_MERCHANTID_EDITLEN, ),
					));
			}
					
			$rowDefs = StageShowLibAdminListClass::MergeSettings($rowDefs, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Currency',        StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-newgateway', StageShowLibTableClass::TABLEPARAM_ID => $currencyOptionID,        StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $currSelect, ),
				));
					
			return $rowDefs;
		}
		
		function Gateway_SystemFields()
		{
			// TODO: Define system fields - This function can be removed if there are no client fields
			
			// Returns an array of fields that are added for this gateway
			$systemFields = array(
				'salePPExpToken' => md5(uniqid(mt_rand(), true)),
				);
				
			return $systemFields;
		}
		
		function Gateway_ClientFields()
		{
			// TODO: Define client fields - This function can be removed if there are no client fields
			
			// Returns an array defining fields that must 
			// be specified by the purchaser before checkout
			$clientFields = array(
				'saleFirstName' => 'First Name',
				'saleLastName'  => 'Last Name',
				'saleEMail'     => 'EMail',
				);				
			
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
			$this->GatewayNotifyURL = NEWGATEWAY_API_NOTIFY_URL;							
		}
		
		function IsGatewayConfigured($adminOptions)
		{
			// TODO: Check gateway settings to see if it is configured
			if (true)
			{
				return false;
			}
			
			return true;					
		}
		
		function VerifyLogin()
		{
			// TODO: If login can be verified ... do it here
			return '';
		}
						
		function GetGatewayRedirectURL($saleRecord)
		{
			// TODO: Build URL of gateway payment page 
			$newgatewayURL = '';
			
			$saleId = $saleRecord[0]->saleID;			

			return $newgatewayURL;					
		}

	}
}

?>