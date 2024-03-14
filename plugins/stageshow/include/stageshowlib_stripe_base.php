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

// Definitions for API Interface Functions
if (!class_exists('StageShowLib_StripeBaseClass')) 
{
	// Constant definitions for the gateway 
	if (!defined('STRIPE_APILIB_DEFAULT_CURRENCY'))
	{
		define('STRIPE_APILIB_DEFAULT_CURRENCY', 'GBP');
		define('STRIPE_PUBLICKEY', 'Public');
		define('STRIPE_PRIVATEKEY', 'Secret');
			
		define('STRIPE_APIVERSION', '2022-11-15');
			
		define('STRIPE_SERVERMODE_TEST', 'Test');
		define('STRIPE_SERVERMODE_LIVE', 'Live');
	}
		
	class StageShowLib_StripeBaseClass // Define class
	{
		static function GetStripeKey($gatewayObj, $keyType = STRIPE_PUBLICKEY, $stripeMode = '')
		{
			$myDBaseObj = $gatewayObj->myDBaseObj;
			$optionGatewaySuffix = $gatewayObj->GetOptionGatewaySuffix(1);
			if ($stripeMode == '')
				$stripeMode = $myDBaseObj->getOption('StripeServer');
				
			switch ($keyType)
			{
				case STRIPE_PUBLICKEY:
				case STRIPE_PRIVATEKEY:
					break;
					
				default:
					return '';
			}
			
			switch ($stripeMode)
			{
				case STRIPE_SERVERMODE_LIVE:
				case STRIPE_SERVERMODE_TEST:
					break;
				
				default:
					return '';
			}
			
			$optionId = 'Stripe'.$stripeMode.$keyType.'Key'.$optionGatewaySuffix;
			$apiKey = $myDBaseObj->getOption($optionId);
			
			return $apiKey;
		}
	
		static function IsConfigured($gatewayObj)
		{
			$myDBaseObj = $gatewayObj->myDBaseObj;
			
			// Check gateway settings to see if it is configured
			$publicKey = self::GetStripeKey($gatewayObj, STRIPE_PUBLICKEY);				
			$privateKey = self::GetStripeKey($gatewayObj, STRIPE_PRIVATEKEY);	
						
			$webhookKey = $myDBaseObj->getOption('StripeWebhookSecret');	
			if (($webhookKey == '') && ($myDBaseObj->getOption('StripeWebhookKey') != ''))
			{
				$settingsPageId = STAGESHOWLIB_DOMAIN."_settings";
				
				$settingsPageURL = get_option('siteurl').'/wp-admin/admin.php?page='.$settingsPageId;
				$stripeWebhooksPageURL = 'https://dashboard.stripe.com/webhooks';
				
				$msg  = __('Stripe Webhook Endpoint has changed', STAGESHOWLIB_DOMAIN);
				$msg .= ' - <a href=' . $stripeWebhooksPageURL . '>' . __('Create a new one here', STAGESHOWLIB_DOMAIN) . '</a>';
				$msg .= ' '.__('then', STAGESHOWLIB_DOMAIN);
				$msg .= ' <a href=' . $settingsPageURL . '>' . __('Add it here', STAGESHOWLIB_DOMAIN) . '</a>';
				StageShowLibEscapingClass::Safe_EchoHTML("<div class='error'><p>{$msg}</p></div>\n");
			}
			
			return (($publicKey != '') && ($privateKey != '') && ($webhookKey != ''));					
		}
		
		static function GetStripeCurrencyTable()
		{
			return array( 		
				array('Name' => 'Australian Dollars ',  'Currency' => 'AUD', 'Symbol' => '&#36;',        'Char' => 'A$', 'Position' => 'Left', 'Format' => '%01.2f', 'Multiplier' => 100),
				array('Name' => 'Canadian Dollars ',    'Currency' => 'CAD', 'Symbol' => '&#36;',        'Char' => '$',  'Position' => 'Left', 'Format' => '%01.2f', 'Multiplier' => 100),
				array('Name' => 'Danish Krone ',        'Currency' => 'DKK', 'Symbol' => 'kr',           'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f', 'Multiplier' => 100),
				array('Name' => 'Euros ',               'Currency' => 'EUR', 'Symbol' => '&#8364;',      'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f', 'Multiplier' => 100),
				array('Name' => 'Norwegian Krone ',     'Currency' => 'NOK', 'Symbol' => 'kr',           'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f', 'Multiplier' => 100),
				array('Name' => 'Swedish Krona ',       'Currency' => 'SEK', 'Symbol' => 'kr',           'Char' => '',   'Position' => 'Left', 'Format' => '%01.2f', 'Multiplier' => 100),
				array('Name' => 'Pounds Sterling ',     'Currency' => 'GBP', 'Symbol' => '&#x20a4;',     'Char' => '£',  'Position' => 'Left', 'Format' => '%01.2f', 'Multiplier' => 100),
				array('Name' => 'U.S. Dollars ',        'Currency' => 'USD', 'Symbol' => '&#36;',        'Char' => '$',  'Position' => 'Left', 'Format' => '%01.2f', 'Multiplier' => 100),
			);
		}
		
	}
}

?>