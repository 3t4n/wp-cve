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

// Definitions for API Interface Functions
if (!class_exists('StageShowLibGatewayRootClass')) 
{	
	class StageShowLibGatewayRootClass // Define class
	{
		function __construct( $opts )
		{
		}

		function NoOfAccounts()
		{
			return 1;
		}

		function GetOptionGatewaySuffix($acctNo = 0)
		{
			if ($acctNo <= 0) $acctNo = 1;
			
			if ($acctNo > STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS) $acctNo = 1;
			
			$optionGatewaySuffix = ($acctNo > 1) ? '_'.$acctNo : '';
			return $optionGatewaySuffix;
		}

/* ------------------------------------------------------------------

	Public Functions

------------------------------------------------------------------ */
			
		function GetCurrencyDef($currency)
		{
			$currencyTable = $this->GetCurrencyTable();
			
			foreach ($currencyTable as $currencyDef)
			{
				if ($currencyDef['Currency'] == $currency)
				{
					return $currencyDef;
				}
			}
			
			return null;
		}
		
	}
}

?>