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
	
if (!defined('StageShowPrintReqJqueryClass'))
{
	class StageShowPrintReqJqueryClass
	{
		function __construct($DBClassObj) //constructor	
		{
			$this->myDBaseObj = $DBClassObj;
			
			$status = $this->PrintSaleReq($DBClassObj);
			
			if ($status) StageShowLibEscapingClass::Safe_EchoHTML("OK\n");
			else StageShowLibEscapingClass::Safe_EchoHTML("Error\n");
		}
		
		function PrintSaleReq()
		{
			$myDBaseObj = $this->myDBaseObj;
				 
			if (!StageShowLibUtilsClass::IsElementSet('request', 'saleID'))
				return false;
				
			$saleID = StageShowLibHTTPIO::GetRequestedInt('saleID');
			$ticketCount = $this->myDBaseObj->PrintSale($saleId);
			if ($ticketCount == 0)
			{
				return false;
			}

			StageShowLibEscapingClass::Safe_EchoHTML(__('Printed', 'stageshow')." - ID:$saleId ");
			StageShowLibEscapingClass::Safe_EchoHTML(__('Tickets', 'stageshow').":$ticketCount");
			
			return true;
		}
	}
}			

$SaleClass = 'StageShowPrintReqJqueryClass';
$valObj = new $SaleClass($DBClassObj);



