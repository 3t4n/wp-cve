<?php
/*
Description: Simulated Payment Gateway API Functions

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

// Definitions for API Interface Functions
if (!class_exists('StageShowLib_simulator_GatewayClass')) 
{	
	class StageShowLib_simulator_GatewayClass extends StageShowLibGatewayBaseClass // Define class
	{
		var $simSaleStatus = '';
		
		function __construct( $opts )
		{
			parent::__construct($opts);
		}
			        
		static function GetName()
		{
			return 'Simulator';
		}
		
		static function GetType()
		{
			return 'simulator';
		}
		
		static function GetDefaultCurrency()
		{
			return PAYPAL_APILIB_DEFAULT_CURRENCY;
		}
/*		
		function GetCurrencyOptionID()
		{
			return 'SimulatorCurrencyType';
		}
*/		
		function GetCurrencyTable()
		{
			return array( 
				array('Name' => 'Australian Dollars ',  'Currency' => 'AUD', 'Symbol' => '&#36;',        'Char' => 'A$', 'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Brazilian Real ',      'Currency' => 'BRL', 'Symbol' => 'R&#36;',       'Char' => 'R$', 'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Canadian Dollars ',    'Currency' => 'CAD', 'Symbol' => '&#36;',        'Char' => '$',  'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Czech Koruna ',        'Currency' => 'CZK', 'Symbol' => '&#75;&#269;',  'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Danish Krone ',        'Currency' => 'DKK', 'Symbol' => 'kr',           'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Euros ',               'Currency' => 'EUR', 'Symbol' => '&#8364;',      'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Hong Kong Dollar ',    'Currency' => 'HKD', 'Symbol' => '&#36;',        'Char' => '$',  'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Hungarian Forint ',    'Currency' => 'HUF', 'Symbol' => 'Ft',           'Char' => '',   'Position' => 'Left', 'Format' => '%d'),
				array('Name' => 'Israeli Shekel ',      'Currency' => 'ILS', 'Symbol' => '&#x20aa;',     'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Mexican Peso ',        'Currency' => 'MXN', 'Symbol' => '&#36;',        'Char' => '$',  'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'New Zealand Dollar ',  'Currency' => 'NZD', 'Symbol' => '&#36;',        'Char' => '$',  'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Norwegian Krone ',     'Currency' => 'NOK', 'Symbol' => 'kr',           'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Philippine Pesos ',    'Currency' => 'PHP', 'Symbol' => 'P',            'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Polish Zloty ',        'Currency' => 'PLN', 'Symbol' => '&#122;&#322;', 'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Pounds Sterling ',     'Currency' => 'GBP', 'Symbol' => '&#x20a4;',     'Char' => '£',  'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Singapore Dollar ',    'Currency' => 'SGD', 'Symbol' => 'S&#36;',       'Char' => 'S$', 'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Swedish Krona ',       'Currency' => 'SEK', 'Symbol' => 'kr',           'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Swiss Franc ',         'Currency' => 'CHF', 'Symbol' => 'CHF',          'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Taiwan New Dollars ',  'Currency' => 'TWD', 'Symbol' => 'NT&#36;',      'Char' => 'NT$','Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Thai Baht ',           'Currency' => 'THB', 'Symbol' => '&#xe3f;',      'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'U.S. Dollars ',        'Currency' => 'USD', 'Symbol' => '&#36;',        'Char' => '$',  'Position' => 'Left', 'Format' => '%01.2f'),
				array('Name' => 'Yen ',                 'Currency' => 'JYP', 'Symbol' => '&#xa5;',       'Char' => '',   'Position' => 'Left', 'Format' => '%d'),
			);
		}
				
		function IsTestServer()
		{
			return false;
		}
		
		//Returns an array of admin options
		function Gateway_GetOptions() 
		{
			$ourOptions = array(
				'SimulatorCurrency' => PAYPAL_APILIB_DEFAULT_CURRENCY,
			);
			
			$ourOptions = array_merge($ourOptions, parent::Gateway_GetOptions());
			
			return $ourOptions;
		}
		
		function Gateway_SettingsRowsDefinition()
		{
			$currencyOptionID = $this->GetCurrencyOptionID();
			$currSelect = $this->GetCurrencyList();
			
			$rowDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Currency',      StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-simulator', StageShowLibTableClass::TABLEPARAM_ID => $currencyOptionID,       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $currSelect, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Buyer Details', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-simulator', StageShowLibTableClass::TABLEPARAM_ID => 'PreloadDetails',        StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Preload with last sale',  StageShowLibTableClass::TABLEPARAM_DEFAULT => true, ),
			);
			
			return $rowDefs;
		}
/*		
		function Gateway_SystemFields()
		{
		}
*/	
		function Gateway_ClientFields()
		{
			// Returns an array defining fields that must 
			// be specified by the purchaser before checkout
			$clientFields = array(
				'saleFirstName' => 'First Name',
				'saleLastName'  => 'Last Name',
				'saleEMail'     => 'EMail',
				'salePPStreet'  => 'Address',
				'salePPCity'    => 'Town/City',
				'salePPState'   => 'County',
				'salePPZip'     => 'Postcode',
				'salePPCountry' => 'Country',
				'salePPPhone'   => 'Phone',
			);				
			
			if ($this->myDBaseObj->isOptionSet('PreloadDetails'))
			{
				$sqlFilters['limit'] = 1;
				$sqlFilters['orderBy'] = 'saleDateTime DESC';
				$salesList = $this->myDBaseObj->GetSalesList($sqlFilters);
				if (count($salesList)>0)
				{
					$this->clientFieldvalues = $salesList[0];
				}
			}
			
			return $clientFields;
		}

		function Gateway_ClientFieldValue($userField)
		{
			// Returns a value of a user specified field value
			if (!isset($this->clientFieldvalues->$userField))
				return '';

			return $this->clientFieldvalues->$userField;
		}
		
/*		
		function Gateway_LoadUserScripts()
		{
		}
		
		function Gateway_LoadAdminStyles()
		{
		}
		
		function LoginGatewayAPI($adminOptions, $dbgOptions)
		{
		}
*/		
		function IsGatewayConfigured($adminOptions)
		{
			return true;					
		}
/*		
		function VerifyLogin()
		{
				return '';
		}
*/						
		function GetGatewayRedirectURL($saleRecord)
		{
			// Don't redirect ... but mark sale as completed
	
			$saleHeader = $saleRecord[0];
			$saleId = $saleHeader->saleID;
						
			$totalPaid = 0;			
			foreach ($saleRecord as $saleItem)			
			{
				$ticketQty = isset($saleItem->ticketQty) ? $saleItem->ticketQty : $saleItem->orderQty;
				$ticketPaid = isset($saleItem->ticketPaid) ? $saleItem->ticketPaid : $saleItem->orderPaid;
				
				$qty = $ticketQty;
				$itemPaid = $ticketPaid/$qty;
				$totalPaid += ($itemPaid * $qty);
			}
			
			$totalPaid += $saleHeader->saleDonation;
						
			$this->updatedDetails = new stdClass;
			$this->updatedDetails->saleID = $saleId;
			$this->updatedDetails->saleStatus = PAYMENT_API_SALESTATUS_COMPLETED;
			$this->updatedDetails->salePaid = $totalPaid;
			$this->updatedDetails->saleTxnId = 'MAN-'.time();				

			$this->myDBaseObj->CompleteSale($this->updatedDetails, StageShowLibSalesCartDBaseClass::STAGESHOWLIB_FROMTROLLEY);
			$this->myDBaseObj->SendSaleReport();			
			$this->myDBaseObj->UnLockTables();
			
			//$this->AddToLog('Sale Logged - SaleID: '.$saleID);
									
			// FUNCTIONALITY: Gateway Notify - Send Sale EMail to buyer (and admin))
			$emailStatus = $this->myDBaseObj->EMailSale($saleId);
			//$this->AddToLog('EMail Status: '.$emailStatus);
			$this->emailSent = true;
			
			$this->simSaleStatus  = '<br>'.__('EMail Sent to', 'stageshow').' '.$saleHeader->saleEMail;
						
			return '';
		}

		function RedirectToGateway($urlObject)
		{		
			return $this->simSaleStatus;
		}
		
		function IsComplete()
		{
			// This function is called before any output so it can do a URL redirect if needed
			if (!isset($this->updatedDetails)) return null;
			
			return $this->updatedDetails;
		}
		
	}
}

