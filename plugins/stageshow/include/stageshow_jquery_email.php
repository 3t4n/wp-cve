<?php
/* 
Description: Code for TBD
 
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

include STAGESHOW_INCLUDE_PATH.'stageshow_dbase_api.php';      
include STAGESHOW_INCLUDE_PATH.'stageshowlib_httpio.php';      
include STAGESHOW_INCLUDE_PATH.'stageshowlib_nonce.php';      

$DBClass = 'StageShowDBaseClass';
$DBClassObj = new $DBClass(__FILE__);

$targetFile = basename(__FILE__);
$callerNOnce = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, 'nonce');
$ourNOnce = StageShowLibNonce::GetStageShowLibNonce($targetFile);
if ($callerNOnce != $ourNOnce)
{
	StageShowLibEscapingClass::Safe_EchoHTML("Authorisation Failed");
	exit;
}
	
if (!defined('StageShowSendEMailJqueryClass'))
{
	class StageShowSendEMailJqueryClass
	{
		function __construct($DBClassObj) //constructor	
		{
			$this->myDBaseObj = $DBClassObj;
			
			if (!$this->SendEMail($DBClassObj))
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Send EMail - FAILED!");
			}
		}
		
		function SendEMail()
		{
			$myDBaseObj = $this->myDBaseObj;

			if (!StageShowLibUtilsClass::IsElementSet('request', 'saleID'))
				return false;
				
			if (!StageShowLibUtilsClass::IsElementSet('request', 'saleEMail'))
				return false;
			
			if (!StageShowLibUtilsClass::IsElementSet('request', 'saleTxnId'))
				return false;
			
			$saleID = StageShowLibHTTPIO::GetRequestedInt('saleID');
			$saleEMail = StageShowLibHTTPIO::GetRequestedString('saleEMail');
			$saleTxnId = StageShowLibHTTPIO::GetRequestedString('saleTxnId');
			
			$salesList = $myDBaseObj->GetSaleDetails($saleID);
			$saleDetails = $salesList[0];

			if ($saleID != $saleDetails->saleID)
				return false;
			
			if ($saleEMail != $saleDetails->saleEMail)
				return false;
			
			if ($saleTxnId != $saleDetails->saleTxnId)
				return false;
			
			$myDBaseObj->EMailSaleRecord($salesList);			
			StageShowLibEscapingClass::Safe_EchoHTML(__('Confirmation EMail sent to', 'stageshow')." $saleEMail");
			
			return true;
		}
	}
}			

$SaleClass = 'StageShowSendEMailJqueryClass';
new $SaleClass($DBClassObj);



