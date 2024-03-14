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
	
include_once('stageshowlib_paypal_gateway.php');
	
if (!class_exists('StageShowLibPayPalButtonsAPIClass')) 
{
	class StageShowLibPayPalButtonsAPIClass extends stageshowlib_paypal_GatewayClass // Define class
	{
		const PAYPAL_APILIB_CREATEBUTTON_OK = 0;
		const PAYPAL_APILIB_CREATEBUTTON_ERROR = 1;
		const PAYPAL_APILIB_CREATEBUTTON_NOLOGIN = 2;
		const PAYPAL_APILIB_INFINITE = -1;
		
		// Class variables:
		var   	$APIStatus;			//	API response status value
		var   	$APIResponseText;	//	API response text
		var		$ButtonVarCount;  	//  The number of button variables defined
		var		$OptNo;				//  The number of button options defined
		var		$caller;			//	The path of the calling function
						
		function __construct( $opts )
		{
			parent::__construct( $opts );			
		}
		
		function Reset()
		{
			parent::Reset();
			$this->ButtonVarCount = 0;
			$this->OptNo = 0;
		}
				
		private function AddAPIButtonVar($tagId, $tagValue)
		{
			if (StageShowLibMigratePHPClass::Safe_strlen($tagValue) > 0)
			{
				$tagName = "L_BUTTONVAR$this->ButtonVarCount";
				$this->AddAPIParam($tagName, $tagId.'='.$tagValue);
				$this->ButtonVarCount++;
			}
		}
		
		private function AddAPIButtonParams($methodID, $hostedButtonID)
		{
			$this->InitAPICallParams($methodID);
			$this->AddAPIParam('HOSTEDBUTTONID', $hostedButtonID);
		}
		
		function AddGetBalanceParams()
		{
			$this->InitAPICallParams('BMGetInventory');
		}
		
		function AddCreateButtonParams($description = 'TBD', $reference = '', $amount = '1.00')
		{
			$this->InitAPICallParams('BMCreateButton');
			$this->AddAPIParam('BUTTONTYPE', 'CART');
			$this->AddAPIParam('BUTTONSUBTYPE', 'PRODUCTS');
			
			$this->AddCommonButtonParams($description, $reference);
			
			$this->AddAPIButtonVar('amount', $amount);			
		}
		
		function AddCommonButtonParams($description, $reference)
		{
			$this->AddAPIButtonVar('item_name', $description);
			$this->AddAPIButtonVar('item_number', $reference);
			$this->AddAPIButtonVar('currency_code', $this->PayPalCurrency);	
					
			if ($this->SaleCompleteURL != '')
			{
				$this->AddAPIButtonVar('return', $this->SaleCompleteURL);
			}	
			
			if ($this->SaleCancelURL != '')
			{
				$this->AddAPIButtonVar('cancel_return', $this->SaleCancelURL);
			}	
		}
		
		function AddDeleteButtonParams($hostedButtonID)
		{
			$this->AddAPIButtonParams('BMManageButtonStatus', $hostedButtonID);
			$this->AddAPIParam('BUTTONSTATUS', 'DELETE');
		}
		
		private function AddGetButtonDetailsParams($hostedButtonID)
		{
			$this->AddAPIButtonParams("BMGetButtonDetails", $hostedButtonID);
		}
		
		private function AddSetButtonParams ($hostedButtonID, $description, $reference)
		{
			$this->AddAPIButtonParams('BMUpdateButton', $hostedButtonID);
			$this->AddAPIParam('BUTTONCODE', 'HOSTED');
			$this->AddAPIParam('BUTTONTYPE', 'CART');
			$this->AddAPIParam('BUTTONSUBTYPE', 'PRODUCTS');
			
			$this->AddCommonButtonParams($description, $reference);
			
			$this->AddAPIButtonVar('button_xref', get_site_url());
		}
		
		private function AddButtonOption ($optID, $optPrice)
		{
			$this->AddAPIParam('L_OPTION0SELECT' . $this->OptNo, $optID);
			$this->AddAPIParam('L_OPTION0PRICE' . $this->OptNo, $optPrice);
			$this->OptNo++;
		}
						
		function CreateButton(&$hostedButtonID, $description = 'TBD', $reference = '', $amount = '1.00')
		{
			$hostedButtonID = '';
			// Check that the PayPal login parameters have been set
			if (!$this->IsAPIConfigured($apiStatus))
				return StageShowLibPayPalButtonsAPIClass::PAYPAL_APILIB_CREATEBUTTON_NOLOGIN;	// Cannot Create Button - API Not Configured
			// Create a "Hosted" button on PayPal ... with basic settings
			$this->Reset();
			$this->AddCreateButtonParams($description, $reference, $amount);
			$this->APIStatus = $this->APIAction('Create Button ');
			if ($this->APIStatus !== 'OK')
				return StageShowLibPayPalButtonsAPIClass::PAYPAL_APILIB_CREATEBUTTON_ERROR;
			$hostedButtonID = $this->APIResponses['HOSTEDBUTTONID'];
			return StageShowLibPayPalButtonsAPIClass::PAYPAL_APILIB_CREATEBUTTON_OK;
		}
		
		function DeleteButton($hostedButtonID)
		{
			// Check that the PayPal login parameters have been set
			if (!$this->IsAPIConfigured($apiStatus))	
				return;		// Cannot Delete Button - API Not Configured
			if (StageShowLibMigratePHPClass::Safe_strlen($hostedButtonID) == 0)
				return;		// Cannot Delete Button - Zero Length Button ID
			// Delete a "Hosted" button on PayPal
			$this->Reset();
			$this->AddDeleteButtonParams($hostedButtonID);
			return $this->APIAction('Delete Button ' . $hostedButtonID);
		}
		
		function GetButton($hostedButtonID)
		{
			// Check that the PayPal login parameters have been set
			if (!$this->IsAPIConfigured($apiStatus))
				return 'ERROR';	// Cannot Get Button Details - API Not Configured 
			if (StageShowLibMigratePHPClass::Safe_strlen($hostedButtonID) == 0)
				return 'ERROR';	// Cannot Get Button Details - Zero Length Button ID 
			$this->Reset();
			$this->AddGetButtonDetailsParams($hostedButtonID);
			$APIStatus = $this->APIAction('Button ' . $hostedButtonID);
			return $APIStatus;
		}
		
		function UpdateButton($hostedButtonID, $description, $reference, $optPrices, $optIDs = '')
		{
			// Check that the PayPal login parameters have been set
			if (!$this->IsAPIConfigured($apiStatus))
				return;	// Cannot Update Button - API Not Configured 
			if (StageShowLibMigratePHPClass::Safe_strlen($hostedButtonID) == 0)
				return;	// Cannot Update Button - Zero Length Button ID 
			$this->Reset();
			$this->AddSetButtonParams($hostedButtonID, $description, $reference);
			if (is_array($optPrices))
			{
				if (count($optIDs) != count($optPrices))
				{
					// Error - Unequal Array sizes
					StageShowLibEscapingClass::Safe_EchoHTML("ERROR: optIDs[] and optPrices[] different sizes in UpdateButton() function <br>\n");
					return;
				}
				$this->AddAPIParam('OPTION0NAME', 'TicketTypes');
				for ($index=0; $index<count($optIDs); $index++)
				{
					$this->AddButtonOption($optIDs[$index], $optPrices[$index]);
				}
			}
			else
			{
				$this->AddAPIButtonVar('amount', $optPrices);
			}
			return $this->APIAction('Button ' . $hostedButtonID);
		}
		
		function VerifyPayPalLogin($loginEnv, $username, $password, $signature)
		{
			$this->APIemail = '';      
			$this->SetTestMode($loginEnv == 'sandbox');
			$this->SetLoginParams($username, $password, $signature);
			// Blank PayPal login params disabled this PayPal interface
			if ((StageShowLibMigratePHPClass::Safe_strlen($username) == 0) && (StageShowLibMigratePHPClass::Safe_strlen($password) == 0) && (StageShowLibMigratePHPClass::Safe_strlen($signature) == 0))
				return true;
				
			$ButtonStatus = $this->CreateButton($hostedButtonID);
			if ($ButtonStatus != StageShowLibPayPalButtonsAPIClass::PAYPAL_APILIB_CREATEBUTTON_OK)
			{
				//StageShowLibEscapingClass::Safe_EchoHTML("CreateButton FAILED<br>\n");
				return false;
			}
			// Tidy up - Button was only to check login and get email .... delete it!
			$this->DeleteButton($hostedButtonID);
			
			// VerifyPayPalLogin - Returned $this->APIStatusMsg 
			return ($this->APIStatusMsg === 'OK');
		}
	}
}

?>