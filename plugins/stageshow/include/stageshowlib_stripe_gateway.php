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

include_once('stageshowlib_gatewaybase.php');
include_once('stageshowlib_table.php');
include 'stageshowlib_stripe_base.php';

if (!class_exists('Stripe\Stripe')) 
{
	$pluginPath = dirname(dirname(__FILE__));
	require_once $pluginPath.'/lib/stripe-php/init.php';
}

// Definitions for API Interface Functions
if (!class_exists('StageShowLib_stripe_GatewayClass')) 
{
	// Constant definitions for the gateway 
	define('STRIPE_APIKEY_TEXTLEN', 260);
	define('STRIPE_APIKEY_EDITLEN', 100);
		
	define('STRIPE_SALEDESCRIPTION_TEXTLEN', 40);	
	define('STRIPE_SALEDESCRIPTION_EDITLEN', 40);
		
	if (!defined('STAGESHOWLIB_CALLBACKROOT_URL'))
		define('STAGESHOWLIB_CALLBACKROOT_URL',  STAGESHOWLIB_URL);
		
	if (!defined('STRIPE_API_NOTIFY_URL'))
		define('STRIPE_API_NOTIFY_URL', STAGESHOWLIB_CALLBACKROOT_URL.'stripe');
//	define('STRIPE_API_NOTIFY_URL', STAGESHOWLIB_URL.'include/stageshowlib_stripe_callback.php');

	define('STRIPE_API_BUYERDETAILS_NONE', 'None');
	define('STRIPE_API_BUYERDETAILS_ADDRESS', 'Address');

	class StageShowLib_stripe_GatewayClass extends StageShowLibGatewayBaseClass // Define class
	{
		function __construct( $opts )
		{
			parent::__construct($opts);
		}
			        
		static function GetName()
		{
			return 'Stripe';
		}
		
		static function GetType()
		{
			return 'stripe';
		}

		static function GetDefaultCurrency()
		{
			// Default Currency
			return 'GBP';
		}
		
		function RedirectToGateway($url)
		{		
			$redirectMsg = __("Redirecting to Stripe", 'stageshow').' - '.__("Please Wait!", 'stageshow');
			$redirectMsg .= "<form name=checkoutForm method=post action='".$url."' >\n";	
			
			$redirectMsg .= "
				<style>.stageshow-stripe-submit { display: none; } </style>";
			$buttonAtts = '';
			//$buttonAtts = 'style="display: none;"';
			$buttonAtts = 'class=stageshow-stripe-submit';
			$redirectMsg .= "
				<input $buttonAtts type=submit name=submitRedirect id=submitRedirect value=Submit />
				</form>";

			$redirectMsg .= $this->StripeCallback($this->sessionId);
			
			return $redirectMsg;	
		}
		
		function GetCurrencyTable()
		{
			return StageShowLib_StripeBaseClass::GetStripeCurrencyTable();
		}
		
		function IsTestServer()
		{
			return false;
		}
		
		//Returns an array of admin options
		function Gateway_GetOptions() 
		{
			$ourOptions = array(
				'StripeCurrency' => STRIPE_APILIB_DEFAULT_CURRENCY,				        
				'StripeAPIKey' => '',
			);
			
			$ourOptions = array_merge($ourOptions, parent::Gateway_GetOptions());
			
			return $ourOptions;
		}
				
		function Gateway_SettingsRowsDefinition()
		{
			$myDBaseObj = $this->myDBaseObj;

			$optionGatewaySuffix = $this->GetOptionGatewaySuffix(1);
			$pluginID = basename(dirname(dirname(__FILE__)));	// Library files should be in 'include' folder						
			$currSelect = $this->GetCurrencyList();
			
			$buyerSelect = array(
				STRIPE_API_BUYERDETAILS_NONE."|".__("Do Not Collect", 'stageshow'),
				STRIPE_API_BUYERDETAILS_ADDRESS."|".__("Collect Address", 'stageshow'),
			);
			
			// Old Webhook setting: StripeWebhookKey
/*
			$uploadImagesPath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/images';
*/			
			$rowDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Server *',               StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeServer',        StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => array(STRIPE_SERVERMODE_LIVE.'|'.__('Live', 'stageshow'), STRIPE_SERVERMODE_TEST.'|'.__('Test', 'stageshow')), ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Live Publishable Key *', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeLivePublicKey', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STRIPE_APIKEY_TEXTLEN,  StageShowLibTableClass::TABLEPARAM_SIZE => STRIPE_APIKEY_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Live Secret Key *',      StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeLiveSecretKey', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STRIPE_APIKEY_TEXTLEN,  StageShowLibTableClass::TABLEPARAM_SIZE => STRIPE_APIKEY_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Test Publishable Key',   StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeTestPublicKey', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STRIPE_APIKEY_TEXTLEN,  StageShowLibTableClass::TABLEPARAM_SIZE => STRIPE_APIKEY_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Test Secret Key',        StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeTestSecretKey', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STRIPE_APIKEY_TEXTLEN,  StageShowLibTableClass::TABLEPARAM_SIZE => STRIPE_APIKEY_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Webhook Signing Secret', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeWebhookSecret',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STRIPE_APIKEY_TEXTLEN,  StageShowLibTableClass::TABLEPARAM_SIZE => STRIPE_APIKEY_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Currency',               StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeCurrency',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $currSelect, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Contact Details',        StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeGetBuyerAddress', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $buyerSelect, ),
/*
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Popup Logo',           StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripePopupLogo',     StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true, StageShowLibTableClass::TABLEPARAM_DIR => $uploadImagesPath, StageShowLibTableClass::TABLEPARAM_EXTN => 'gif,jpeg,jpg,png', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Popup Name',           StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripePopupName',     StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_STRIPE_POPUPNAME_TEXTLEN,      StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_STRIPE_POPUPNAME_EDITLEN, ),
*/
			);
	
			$webhookKey = $myDBaseObj->getOption('StripeWebhookSecret');	
			if ($webhookKey == '')
			{
				$webhookURL = STRIPE_API_NOTIFY_URL;
				$webhookEvents = 'checkout.session.completed';
			
				$myDBaseObj->adminOptions['StripeWebhookURL'] = $webhookURL;
				$myDBaseObj->adminOptions['StripeWebhookEvents'] = $webhookEvents;

				$extraDefs = array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Webhook URL', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeWebhookURL', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_VALUE => $webhookURL, StageShowLibTableClass::TABLEPARAM_AFTER => 'StripeTestSecretKey', ),
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Webhook Events', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$optionGatewaySuffix.'-settings-tab-stripe', StageShowLibTableClass::TABLEPARAM_ID => 'StripeWebhookEvents', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_VALUE => $webhookEvents, StageShowLibTableClass::TABLEPARAM_AFTER => 'StripeWebhookURL', ),
				);
				$rowDefs = StageShowLibAdminListClass::MergeSettings($rowDefs, $extraDefs);
			}
			
			return $rowDefs;
		}
		
		function IsCallback($pluginObj)
		{
			if (!StageShowLibUtilsClass::IsElementSet('request', 'rslt'))
				return false;		
				
			switch ($_REQUEST['rslt'])
			{
				case STAGESHOWLIB_CHECKOUTSTATUS_COMPLETE:
					$pluginObj->checkoutMsg = __('Checkout Complete', 'stageshow').'<br>'.__('Please check your EMail for confirmation', 'stageshow');					
					$pluginObj->checkoutMsgClass = $pluginObj->cssDomain.'-error';
					break;

				case STAGESHOWLIB_CHECKOUTSTATUS_CANCELLED:
					$pluginObj->checkoutMsg = __('Checkout Cancelled', 'stageshow');					
					$pluginObj->checkoutMsgClass = $pluginObj->cssDomain.'-ok';
					break;
					
				default:
					return false;
			}
				
			return true;	
		}
		
		function Gateway_LoadUserScripts()
		{
		}
		
		function Gateway_LoadAdminStyles()
		{
		}
		
		function LoginGatewayAPI($adminOptions, $dbgOptions)
		{
			$this->GatewayNotifyURL = STRIPE_API_NOTIFY_URL;							
		}
	
		function IsGatewayConfigured($adminOptions)
		{
			// Check gateway settings to see if it is configured
			return StageShowLib_StripeBaseClass::IsConfigured($this);
		}
		
		function SaveSettings($myDBaseObj)
		{
			if ($myDBaseObj->getOption('StripeWebhookSecret') != '')
			{
				if (isset($myDBaseObj->adminOption['StripeWebhookKey']))
					unset($myDBaseObj->adminOption['StripeWebhookKey']);
			}	
			parent::SaveSettings($myDBaseObj);
		}
		
		function VerifyLogin()
		{
			// TODO: If login can be verified ... do it here
			return '';
		}
						
		function GetGatewayRedirectURL($saleRecord)
		{
			// Build URL of gateway payment page 
			$stripeURL = '';

			$session = $this->CreateStripeSession($saleRecord);

			$this->sessionId = $session['id'];
			return $stripeURL;					
		}
						
		function CreateStripeSession($saleRecord)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$saleId = $saleRecord[0]->saleID;
			$totalPaid = 0;
			
			$stripeCurrency = $myDBaseObj->getOption('StripeCurrency');
			
			$currencyDef = $this->GetCurrencyDef($stripeCurrency);
			$paidMultiplier = $currencyDef['Multiplier'];
			
			$line_items = array();
			foreach ($saleRecord as $saleItem)			
			{
				$itemName = $myDBaseObj->GetSaleItemName($saleItem);
				$itemPaid = $myDBaseObj->GetSaleItemPrice($saleItem);
				$qty = $myDBaseObj->GetSaleItemQty($saleItem);

				$ticketPaid = isset($saleItem->ticketPaid) ? $saleItem->ticketPaid : $saleItem->orderPaid;

				$itemPaid = $ticketPaid/$qty;
				$itemPaid *= $paidMultiplier;
				$totalPaid += ($itemPaid * $qty);

				$line_item = array
				(
					'quantity' => $qty, 
					'price_data' => array
					(
						'unit_amount' => $itemPaid,
						'product_data' => array
						(
							'name' => $itemName
						),
						'currency' => $stripeCurrency
					), 
				);
				
				$line_items[] = $line_item;				
			}
			
			$optionGatewaySuffix = '';
			if (isset($saleRecord[0]->showGatewayIndex)) 
			{
				$optionGatewaySuffix = $this->GetOptionGatewaySuffix($saleRecord[0]->saleGatewayIndex);
			}
			
			$apiKey = StageShowLib_StripeBaseClass::GetStripeKey($this, STRIPE_PRIVATEKEY);				
			
			$description = $myDBaseObj->getOption('StripeSaleDescription'.$optionGatewaySuffix);
			if ($description == '')	
			{
				$description = $myDBaseObj->getOption('OrganisationID'.$optionGatewaySuffix);
			}
			
			\Stripe\Stripe::setApiKey($apiKey);
			\Stripe\Stripe::setApiVersion(STRIPE_APIVERSION);

			$sessionAtts = array(
			  'payment_method_types' => ['card'],
			  'client_reference_id' => $saleId,
			);
			
			// Add line items to checkout			
			$sessionAtts['line_items'] = $line_items;
			$sessionAtts['mode'] = 'payment';
						
			// TODO - Get success and cancel URLs (... add paameters to default URLs)
			$rtnURL = StageShowLibUtilsClass::GetPageURL();
			
			if ($myDBaseObj->isOptionSet('CheckoutCompleteURL') != false)
				$success_url = $myDBaseObj->getOption('CheckoutCompleteURL');
			else
			{
				$success_url = add_query_arg('rslt', STAGESHOWLIB_CHECKOUTSTATUS_COMPLETE, $rtnURL);				
			}
			$sessionAtts['success_url'] = $success_url;
		
			if ($myDBaseObj->isOptionSet('CheckoutCancelledURL') != false)
				$cancel_url = $myDBaseObj->getOption('CheckoutCancelledURL');
			else
			{
				$cancel_url = StageShowLibUtilsClass::GetPageURL();				
				$cancel_url = add_query_arg('rslt', STAGESHOWLIB_CHECKOUTSTATUS_CANCELLED, $rtnURL);				
			}
			$sessionAtts['cancel_url'] = $cancel_url;
			
			// Get address required option 
			if ($myDBaseObj->getOption('StripeGetBuyerAddress') == STRIPE_API_BUYERDETAILS_ADDRESS)
				$sessionAtts['billing_address_collection'] = 'required';
			
			$session = \Stripe\Checkout\Session::create($sessionAtts);

			return $session;
		}

		function StripeCallback($sessionId)
		{					
			$myDBaseObj = $this->myDBaseObj;
			$apiKey = StageShowLib_StripeBaseClass::GetStripeKey($this, STRIPE_PUBLICKEY);

			$stripeCallback = '
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
var stripe = Stripe("'.$apiKey.'");
var sessionId = "'.$sessionId.'";

stripe.redirectToCheckout({
  sessionId: sessionId
}).then(function (result) {
  // If `redirectToCheckout` fails due to a browser or network
  // error, display the localized error message to your customer
  // using `result.error.message`.
});
</script>
';

			return $stripeCallback;
		}
		
	}
}

