<?php
/* 
Description: Core Library Database Access functions
 
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

if (!class_exists('StageShowLibSampleDBaseClass')) 
{
	class StageShowLibSampleDBaseClass // Define class
	{
		function __construct($myDBaseObj)
		{
			$this->myDBaseObj = $myDBaseObj;
		}
				
		// Add Sale - Address details are optional
		function AddSampleSale($saleDateTime, $saleFirstName, $saleLastName, $saleEMail, $salePaid, $saleTxnId, $saleStatus, $salePPStreet, $salePPCity, $salePPState, $salePPZip, $salePPCountry, $salePPPhone = '', $saleAddPostage = false, $saleDonation = 0)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$salesVals['salePPName'] = StageShowLibMigratePHPClass::Safe_trim($saleFirstName & ' ' & $saleLastName);
			$salesVals['salePPStreet'] = $salePPStreet;
			$salesVals['salePPCity'] = $salePPCity;
			$salesVals['salePPState'] = $salePPState;
			$salesVals['salePPZip'] = $salePPZip;
			$salesVals['salePPCountry'] = $salePPCountry;				
			$salesVals['salePPPhone'] = $salePPPhone;				
			
			$salesVals['saleFirstName'] = $saleFirstName;
			$salesVals['saleLastName'] = $saleLastName;
			$salesVals['saleEMail'] = $saleEMail;
			$salesVals['saleTxnId'] = $saleTxnId;
			$salesVals['saleStatus'] = $saleStatus;
			
			$sampleCartRow = new stdClass();
			$sampleCartRow->qty = 1;
			$sampleCartRow->price = $salePaid;
			
			$cartContents = new stdClass();
			$cartContents->rows = array();
			$cartContents->rows[] = $sampleCartRow;
			
			$salesVals['saleExtraDiscount'] = $myDBaseObj->GetExtraDiscount($cartContents);
			$salesVals['saleTransactionFee'] = $myDBaseObj->GetTransactionFee($cartContents);
			$salesVals['saleDonation'] = $saleDonation;
			$salesVals['salePostage'] = 0;
			if ($saleAddPostage && ($myDBaseObj->getOption('PostageFee') != ''))
				 $salesVals['salePostage'] += $myDBaseObj->getOption('PostageFee');
			
			$salePaid += $salesVals['saleTransactionFee'];
			$salePaid += $salesVals['saleDonation'];
			$salePaid += $salesVals['salePostage'];
			$salesVals['salePaid'] = $salePaid;
			
			$saleFee = number_format(0.20 + ($salePaid * 3.4/100), 2);			
			$salesVals['saleFee'] = $saleFee;
			
			$salesVals['saleMethod'] = 'PayPal';
			
			return $myDBaseObj->AddSale($saleDateTime, $salesVals);
		}
		
	}
}

?>