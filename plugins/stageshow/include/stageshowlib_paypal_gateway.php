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

if (defined('STAGESHOWLIB_TRACK_INCLUDES_FILE'))
{
	include STAGESHOWLIB_TRACK_INCLUDES_FILE;
	trackIncludes(__FILE__);
}
	
include_once('stageshowlib_gatewaybase.php');
	
// Definitions for API Interface Functions
if (!defined('STAGESHOWLIB_PAYPAL_DEFINITIONS')) 
{
	define('STAGESHOWLIB_PAYPAL_DEFINITIONS', true);
	
	if (!defined('PAYPAL_APILIB_IPN_NOTIFY_URL'))
		define('PAYPAL_APILIB_IPN_NOTIFY_URL', STAGESHOWLIB_CALLBACKROOT_URL.'paypal');

	// Copy historical definitions ....
	if (defined('PAYPAL_APILIB_STREET_LABEL')) 
		define ('PAYMENT_API_STREET_LABEL', PAYPAL_APILIB_STREET_LABEL);	
	if (defined('PAYPAL_APILIB_CITY_LABEL')) 
		define ('PAYMENT_API_CITY_LABEL', PAYPAL_APILIB_CITY_LABEL);
	if (defined('PAYPAL_APILIB_STATE_LABEL')) 
		define ('PAYMENT_API_STATE_LABEL', PAYPAL_APILIB_STATE_LABEL);
	if (defined('PAYPAL_APILIB_ZIP_LABEL')) 
		define ('PAYMENT_API_ZIP_LABEL', PAYPAL_APILIB_ZIP_LABEL);
	if (defined('PAYPAL_APILIB_COUNTRY_LABEL')) 
		define ('PAYMENT_API_COUNTRY_LABEL', PAYPAL_APILIB_COUNTRY_LABEL);
				
	if (!defined('PAYMENT_API_LOGIN_MERCHANTID_TEXTLEN'))
	{
		define('PAYMENT_API_LOGIN_MERCHANTID_TEXTLEN', 65);
		define('PAYMENT_API_LOGIN_USER_TEXTLEN', 127);
		define('PAYMENT_API_LOGIN_PWD_TEXTLEN', 65);
		define('PAYMENT_API_LOGIN_SIG_TEXTLEN', 65);
		
		define('PAYMENT_API_LOGIN_EMAIL_TEXTLEN', 65);
		
		define('PAYMENT_API_LOGIN_EDITLEN', 75);
			
		define('PAYMENT_API_BUTTONID_TEXTLEN',16);		
	
		define('PAYPAL_APILIB_REFUNDALL', '-1');	
	}
}

if (!class_exists('StageShowLib_paypal_GatewayClass')) 
{
	class StageShowLib_paypal_GatewayClass extends StageShowLibGatewayBaseClass // Define class
	{
		var		$APIEndPoint;		//	PayPal API access URL
		var   	$APIResponses;		//	API response data parsed into an array
		var		$APIStatusMsg;
		
		var		$APIusername;		//	PayPal login name
		var		$APIpassword;		//	PayPal login password
		var		$APIsignature;		//	PayPal login signature
		var		$APIemail;			//	PayPal primary email
		var		$PayPalCurrency;	//  PayPal Currency Code
		
		var		$SaleCompleteURL = '';
		var		$SaleCancelURL = '';
			
		var		$checkout = '';
		
		const	PAYPAL_SHIPPING_NOTREQUIRED = '1';
		const	PAYPAL_SHIPPING_REQUIRED = '2';
		
		function __construct( $opts )
		{
			$this->opts = $opts;
			parent::__construct($opts);

			// Initialise PayPal API Variables
			$this->APIusername = '';
			$this->APIpassword = '';
			$this->APIsignature = '';
		}
		
		function NoOfAccounts()
		{
			return STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS;
		}

		static function GetName()
		{
			return 'PayPal';
		}
		
		static function GetType()
		{
			return 'paypal';
		}
/*		
		static function GetDefaultCurrency()
		{
			return PAYPAL_APILIB_DEFAULT_CURRENCY;
		}
*/		
		function GetCheckoutType()
		{
			return $this->myDBaseObj->getOption('PayPalCheckoutType');
		}
/*		
		function GetCurrencyOptionID()
		{
			return $this->GetName().'Currency';
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
				'PayPalCurrency' => PAYPAL_APILIB_DEFAULT_CURRENCY,
			);
			
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
				
				$ourOptions['PayPalMerchantID'.$optionGatewaySuffix] = '';
				$ourOptions['PayPalAPIUser'.$optionGatewaySuffix] = '';
				$ourOptions['PayPalAPIPwd'.$optionGatewaySuffix] = '';
				$ourOptions['PayPalAPISig'.$optionGatewaySuffix] = '';
				$ourOptions['PayPalAPIEMail'.$optionGatewaySuffix] = '';				
			}
				
			$ourOptions = array_merge($ourOptions, parent::Gateway_GetOptions());
			
			return $ourOptions;
		}
		
		function Gateway_SettingsRowsDefinition()
		{
			$currencyOptionID = $this->GetCurrencyOptionID();
			$currSelect = $this->GetCurrencyList();
			
			$rowDefs = array();
			
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
				
				$rowDefs = array_merge($rowDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Account EMail', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-paypal', StageShowLibTableClass::TABLEPARAM_ID => 'PayPalAPIEMail'.$optionGatewaySuffix,   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_LOGIN_EMAIL_TEXTLEN,       StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_LOGIN_EDITLEN, ),
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Merchant ID',   StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-paypal', StageShowLibTableClass::TABLEPARAM_ID => 'PayPalMerchantID'.$optionGatewaySuffix, StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_LOGIN_MERCHANTID_TEXTLEN,  StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_LOGIN_EDITLEN, StageShowLibTableClass::TABLEPARAM_BLOCKBLANK => true, ),
					));
					
				if ($acctNo > 1) continue;
				
				$rowDefs = array_merge($rowDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Currency',      StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-paypal', 					StageShowLibTableClass::TABLEPARAM_ID => $currencyOptionID,                   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $currSelect, ),

					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Purchaser Address',               StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',       StageShowLibTableClass::TABLEPARAM_ID => 'GetPurchaserAddress',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Always Required',  StageShowLibTableClass::TABLEPARAM_DEFAULT => false, StageShowLibTableClass::TABLEPARAM_BEFORE => 'CheckoutTimeout',  ),
					));
			
				$dbClass = STAGESHOWLIB_DBASE_CLASS;
				if ($dbClass::HasCheckoutImage())
				{
					$pluginID = basename(dirname(dirname(__FILE__)));	// Library files should be in 'include' folder						
					$uploadImagesPath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/images';
					$imageModeSelector = array(
						PAYMENT_API_IMAGES_LOCAL_HTTP.'|File (http)', 
						PAYMENT_API_IMAGES_LOCAL_HTTPS.'|File (https)', 
						PAYMENT_API_IMAGES_SPECIFY_URL.'|URL'
						);
						
					$rowDefs = array_merge($rowDefs, array(
						array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Header Image', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-paypal', StageShowLibTableClass::TABLEPARAM_ID => 'PayPalHeaderImageMode',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $imageModeSelector, StageShowLibTableClass::TABLEPARAM_SIZE => 7, StageShowLibTableClass::TABLEPARAM_NEXTINLINE => true, ),
						array(StageShowLibTableClass::TABLEPARAM_LABEL => '',                      StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-paypal', StageShowLibTableClass::TABLEPARAM_ID => 'PayPalHeaderImageFile',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true, StageShowLibTableClass::TABLEPARAM_DIR => $uploadImagesPath, StageShowLibTableClass::TABLEPARAM_EXTN => 'gif,jpeg,jpg,png', StageShowLibTableClass::TABLEPARAM_NEXTINLINE => true),
						array(StageShowLibTableClass::TABLEPARAM_LABEL => '',                      StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab-paypal', StageShowLibTableClass::TABLEPARAM_ID => 'PayPalHeaderURL', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_URL_TEXTLEN, StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_URL_EDITLEN, ),
						));				
				}
			}
			
			return $rowDefs;
		}
			
		function Gateway_LoadUserScripts()
		{
		}
		
		function Gateway_LoadAdminStyles()
		{
		}
		
		function LoginGatewayAPI($adminOptions, $dbgOptions)
		{
			$this->SetLoginParams(
				$adminOptions['PayPalAPIUser'], 
				$adminOptions['PayPalAPIPwd'], 
				$adminOptions['PayPalAPISig'], 
				$adminOptions['PayPalCurrency']);
			$this->SetTestMode(false);

			$this->GatewayNotifyURL = PAYPAL_APILIB_IPN_NOTIFY_URL;							
			$this->PayPalURL = $this->GetGatewayURL();

			// URL for Plugin code to verify PayPal IPNs
/*
			if (isset($dbgOptions['Dev_IPNLocalServer']) && ($dbgOptions['Dev_IPNLocalServer']))
			{
				$pluginName = basename(dirname(dirname(__FILE__)));
				$verifyURL = STAGESHOWLIB_URL.'test/paypal_VerifyIPNTest.php';	
				$this->PayPalVerifyURL = $verifyURL;	
			}
			else
*/			
			{
				$this->PayPalVerifyURL = $this->PayPalURL;
			}				
		}
		
		function IsLoginChanged ($adminOptions)
		{
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
				
				if (StageShowLibAdminClass::IsOptionChanged($adminOptions, 'PayPalAPIUser'.$optionGatewaySuffix))
					return true;
				if (StageShowLibAdminClass::IsOptionChanged($adminOptions, 'PayPalAPIPwd'.$optionGatewaySuffix))
					return true;
				if (StageShowLibAdminClass::IsOptionChanged($adminOptions, 'PayPalAPISig'.$optionGatewaySuffix))
					return true;	
			}
					
			return false;
		}
				
		function IsGatewayConfigured($adminOptions)
		{
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
				
				// Must have EITHER PayPalMerchantID or PayPalAPIEMail
				if (!self::isOptionSet($adminOptions, 'PayPalMerchantID'.$optionGatewaySuffix) && !self::isOptionSet($adminOptions, 'PayPalAPIEMail'.$optionGatewaySuffix))
					return false;
				
				// Either All of PayPalAPIUser, PayPalAPIPwd and PayPalAPISig must be defined or none of them
				$ApiOptsCount = 0;
				if (self::isOptionSet($adminOptions, 'PayPalAPIUser'.$optionGatewaySuffix)) $ApiOptsCount++;
				if (self::isOptionSet($adminOptions, 'PayPalAPIPwd'.$optionGatewaySuffix)) $ApiOptsCount++;
				if (self::isOptionSet($adminOptions, 'PayPalAPISig'.$optionGatewaySuffix)) $ApiOptsCount++;
				if (($ApiOptsCount != 0) && ($ApiOptsCount != 3))
					return false;
			}
				
			return true;					
		}
		
		function VerifyLogin()
		{
			include_once('stageshowlib_paypal_buttons.php');
			$payPalButtonsAPIObj = new StageShowLibPayPalButtonsAPIClass($this->opts);
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($acctNo);
				
				if ($payPalButtonsAPIObj->VerifyPayPalLogin(
					'live', 
					StageShowLibUtilsClass::GetHTTPTextElem('post', 'PayPalAPIUser'.$optionGatewaySuffix),
					StageShowLibUtilsClass::GetHTTPTextElem('post', 'PayPalAPIPwd'.$optionGatewaySuffix), 
					StageShowLibUtilsClass::GetHTTPTextElem('post', 'PayPalAPISig'.$optionGatewaySuffix)))
				{
					// New PayPal API Settings are valid			
				}
				else
				{
					// FUNCTIONALITY: Settings - Reject PayPal settings if cannot create hosted button 
					$APIStatus = $payPalButtonsAPIObj->APIStatus;
					return __('PayPal Login FAILED', 'stageshow')." - $APIStatus";
				}
			}
						
			return '';
		}
		
		function GetGatewayRedirectURL($saleRecord)
		{
			$saleId = $saleRecord[0]->saleID;
			
			$myDBaseObj = $this->myDBaseObj;
			
			if (isset($saleRecord[0]->showGatewayIndex)) 
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($saleRecord[0]->saleGatewayIndex);
			}
			else
			{
				$optionGatewaySuffix = '';
			}
			
			$paramCount=0;	
			foreach ($saleRecord as $saleItem)			
			{
				$paramCount++;
				$reqParams['item_name_'.$paramCount] = $myDBaseObj->GetSaleItemName($saleItem);
				$reqParams['amount_'.$paramCount] = $myDBaseObj->GetSaleItemPrice($saleItem);
				$reqParams['quantity_'.$paramCount] = $myDBaseObj->GetSaleItemQty($saleItem);
				$reqParams['shipping_'.$paramCount] = 0.0;
			}
			
			$headerURL = $myDBaseObj->GetGatewayHeaderURL();

			if ($headerURL != '') $reqParams['image_url'] = $headerURL;
			
			if ( ($saleRecord[0]->salePostage > 0)
			  || $myDBaseObj->isOptionSet('GetPurchaserAddress') )
			{
				$reqParams['no_shipping'] = self::PAYPAL_SHIPPING_REQUIRED;
			}
			else
			{
				$reqParams['no_shipping'] = self::PAYPAL_SHIPPING_NOTREQUIRED;
			}

			// Use Merchant ID if it is defined
			if ($myDBaseObj->isOptionSet('PayPalMerchantID'.$optionGatewaySuffix))
			{
				$reqParams['business'] = $myDBaseObj->adminOptions['PayPalMerchantID'.$optionGatewaySuffix];	// Can use adminOptions['PayPalAPIEMail']
			}
			else
			{
				$reqParams['business'] = $myDBaseObj->adminOptions['PayPalAPIEMail'.$optionGatewaySuffix];	// Can use adminOptions['PayPalAPIEMail']
			}
			$reqParams['currency_code'] = $myDBaseObj->adminOptions['PayPalCurrency'];
			$reqParams['cmd'] = '_cart';
			$reqParams['upload'] = '1';
			
			if ($myDBaseObj->adminOptions['CheckoutCompleteURL'] != '')
			{
				$reqParams['rm'] = '2';
				$reqParams['return'] = $myDBaseObj->adminOptions['CheckoutCompleteURL'];
			}
			
			if ($myDBaseObj->adminOptions['CheckoutCancelledURL'] != '')
			{
				$reqParams['cancel_return'] = $myDBaseObj->adminOptions['CheckoutCancelledURL'];
			}
				
			$reqParams['notify_url'] = $this->GatewayNotifyURL;
		
			$gatewayURL = $this->GetGatewayURL();
			foreach ($reqParams as $paypalArg => $paypalParam)
				$gatewayURL = add_query_arg($paypalArg, urlencode($paypalParam), $gatewayURL);

			$gatewayURL = add_query_arg('custom', $saleId, $gatewayURL);				
					
			return $gatewayURL;					
		}
		
		protected function IsAPIConfigured(&$apiStatus)
		{
			$apiStatus = '';
			if ((StageShowLibMigratePHPClass::Safe_strlen( $this->APIusername ) == 0) || ( StageShowLibMigratePHPClass::Safe_strlen( $this->APIpassword ) == 0 ) || ( StageShowLibMigratePHPClass::Safe_strlen( $this->APIsignature ) == 0 ))
			{
				$apiStatus = "PayPal API UserName, Password or Signature Undefined";
			}
			else if (StageShowLibMigratePHPClass::Safe_strlen( $this->APIEndPoint ) == 0)
			{
				$apiStatus = "PayPal APIEndPoint Undefined";
			}
			else
			{
				return true;
			}

			if (($this->DebugEnabled) && ($apiStatus != ''))
			{
				$this->OutputDebug("--------------------------------------\n");
				$this->OutputDebug("API Access Error: $apiStatus\n");
				$this->OutputDebug("\n", true);
			}
			
			return false;
		}
		
		function SetLoginParams($username, $password, $signature, $currency = PAYPAL_APILIB_DEFAULT_CURRENCY, $email = '')		
		{
			if (( StageShowLibMigratePHPClass::Safe_strlen( $username ) == 0 ) || ( StageShowLibMigratePHPClass::Safe_strlen( $password ) == 0 ) || ( StageShowLibMigratePHPClass::Safe_strlen( $signature ) == 0 ))
			{
				$this->APIusername = '';
				$this->APIpassword = '';
				$this->APIsignature = '';
				$this->APIStatusMsg = __('Missing PayPal Login Param');
				return;
			}
			$this->APIusername = $username;
			$this->APIpassword = $password;
			$this->APIsignature = $signature;
			$this->APIemail = $email;
			$this->PayPalCurrency = $currency;
			$this->APIStatusMsg = '';
		}
		
		function SetTestMode()
		{
			if ($this->IsTestServer())
			{
				$this->APIEndPoint = 'https://api-3t.sandbox.paypal.com/nvp';
			}
			else 
			{
				$this->APIEndPoint = 'https://api-3t.paypal.com/nvp';
			}
		}
		
		function GetGatewayURL()
		{
			if ($this->IsTestServer())
			{
				return 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			}
			else 
			{
				return 'https://www.paypal.com/cgi-bin/webscr';
			}
		}
		
		protected function APIAction()
		{
			$myDBaseObj = $this->myDBaseObj;
			
			static	$firstCall = true;
			
			if ($firstCall)
			{
				$firstCall = false;
				if ($myDBaseObj != null)
				{
					$this->DebugDisplay = $myDBaseObj->getDbgOption('Dev_ShowGatewayAPI');
					$this->DebugLogging = $myDBaseObj->getDbgOption('Dev_LogGatewayAPI');					
					$this->DebugEnabled |= ($this->DebugDisplay || $this->DebugLogging);
				}
			}
			
			$this->APIResponses = null;
			$response = $myDBaseObj->HTTPAction($this->APIEndPoint, $this->URLParamsArray);
			if ($response['APIStatusMsg'] === 'ERROR')
			{
			}
			else
			{
				parse_str($response['APIResponseText'], $response['APIResponses']);

				if (isset($response['APIResponses']['ACK']))
				{
					$this->APIResponses = $response['APIResponses'];				
					if ($response['APIResponses']['ACK'] == 'Success')
					{
						$this->APIStatusMsg = 'OK';
					}
					else
					{
						$this->APIStatusMsg = 'API Error ';
						if (isset($response['APIResponses']['L_ERRORCODE0']))
							$this->APIStatusMsg .= $response['APIResponses']['L_ERRORCODE0'];
						$this->APIStatusMsg .= ' - ';
						if (isset($response['APIResponses']['L_SHORTMESSAGE0']))
							$this->APIStatusMsg .= $response['APIResponses']['L_SHORTMESSAGE0'];
					}
				}
				else
					$this->APIStatusMsg = 'API Error - No Response';
			}
			if ($this->DebugEnabled)
			{
				$this->OutputDebug("APIStatusMsg:".$this->APIStatusMsg."\n", true);
			}
			return $this->APIStatusMsg;
		}
				
		protected function AddAPIParam($tagName, $tagValue)
		{
			$this->URLParamsArray[$tagName]=$tagValue;
			if ($this->DebugEnabled)
			{
				$this->OutputDebug ("$tagName=$tagValue\n");
			}			
		}
		
		protected function InitAPICallParams($methodID)
		{
			if ($this->DebugEnabled)
			{
				$this->OutputDebug("--------------------------------------\n");
				$this->OutputDebug("$methodID API Call\n");
				$this->OutputDebug("--------------------------------------\n", true);
			}
			if (StageShowLibMigratePHPClass::Safe_strlen($this->APIusername) > 0)
			{
				$this->AddAPIParam('USER', $this->APIusername);
			}
			else
			{
				StageShowLibEscapingClass::Safe_EchoHTML("ERROR: API Username not specified<br>\n");
			}
			if (StageShowLibMigratePHPClass::Safe_strlen($this->APIusername) > 0)
			{
				$this->AddAPIParam('PWD', $this->APIpassword);
			}
			else
			{
				StageShowLibEscapingClass::Safe_EchoHTML("ERROR: API Password not specified<br>\n");
			}
			if (StageShowLibMigratePHPClass::Safe_strlen($this->APIusername) > 0)
			{
				$this->AddAPIParam('SIGNATURE', $this->APIsignature);
			}
			else
			{
				StageShowLibEscapingClass::Safe_EchoHTML('ERROR: API Signature not specified<br>\n');
			}
			$this->AddAPIParam('VERSION', '65.1');
			$this->AddAPIParam('METHOD', $methodID);
			$this->ButtonVarCount = 0;
		}
		
		function GetTransactions($fromDate, $toDate = '')
		{
			// Check that the PayPal login parameters have been set
			if (!$this->IsAPIConfigured($apiStatus))
				return $apiStatus;	// Cannot Execute - API Not Configured
				
			$this->InitAPICallParams('TransactionSearch');
			$this->AddAPIParam('STARTDATE', $fromDate);
			if ($toDate != '')
			{
				$this->AddAPIParam('ENDDATE', $toDate);
			}
			return $this->APIAction('Get Transactions ');
		}
		
		function GetTransaction($txnId)
		{
			// Check that the PayPal login parameters have been set
			if (!$this->IsAPIConfigured($apiStatus))
				return $apiStatus;	// Cannot Execute - API Not Configured
				
			// Search for Transaction on PayPal
			$this->Reset();
			$this->InitAPICallParams('GetTransactionDetails');
			$this->AddAPIParam('TRANSACTIONID', $txnId);
			return $this->APIAction('Get Transaction ');
		}
		
		function RefundTransaction($txnId, $amt = PAYPAL_APILIB_REFUNDALL)
		{
			// Check that the PayPal login parameters have been set
			if (!$this->IsAPIConfigured($apiStatus))
				return $apiStatus;	// Cannot Execute - API Not Configured
				
			// Search for Transaction on PayPal
			$this->Reset();
			$this->InitAPICallParams('RefundTransaction');
			$this->AddAPIParam('TRANSACTIONID', $txnId);
			//$this->AddAPIParam('INVOICEID', $tbd);
			//$this->AddAPIParam('NOTE', $tbd);
			if ($amt == PAYPAL_APILIB_REFUNDALL)
			{
				$this->AddAPIParam('REFUNDTYPE', 'Full');
			}
			else
			{
				$this->AddAPIParam('REFUNDTYPE', 'Partial');
				$this->AddAPIParam('AMT', $amt);
				$this->AddAPIParam('CURRENCYCODE', $this->PayPalCurrency);
			}
			return $this->APIAction('Refund Transaction ');
		}
		
	}
}
		

