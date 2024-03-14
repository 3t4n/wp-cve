<?php
/* 
Description: Printer Output Code
 
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

if (!StageShowLibUtilsClass::IsElementSet('request', 'action')) exit;

//include '../test/stageshowlib_dumpbytes.php';

class StageShowPrintServer // Define class
{
	function __construct() //constructor	
	{
		switch ($_REQUEST['action'])
		{
			case 'auth':
				$this->ServerAuth();
				break;
				
			case 'list':
				$this->ServerList();
				break;
				
			case 'print':
				$this->ServerPrint();
				break;

			case 'ack':
				$this->ServerAck();
				break;
				
			case 'spool':
				$this->ServerSpool();
				break;
		}
	}
		
	function ServerInitialise()
	{
		include STAGESHOW_INCLUDE_PATH.'stageshow_dbase_api.php';
		include STAGESHOW_INCLUDE_PATH.'stageshowlib_httpio.php';      

		$DBClass = 'StageShowDBaseClass';
		$this->myDBaseObj = new $DBClass(__FILE__);

		$reqIPAddress = StageShowLibDBaseClass::GetIPAddr();
		$expIPAddress = $this->myDBaseObj->adminOptions['PrinterIPAddress'];
		
		// TODO - Check that the request is from the IP Address we expect
		if ($reqIPAddress != $expIPAddress)
		{
			StageShowLibEscapingClass::Safe_EchoHTML("IP Address does not match!<br>\n");
			StageShowLibEscapingClass::Safe_EchoHTML("Requesting IP Address: $reqIPAddress <br>\n");
			StageShowLibEscapingClass::Safe_EchoHTML("Expected IP Address: $expIPAddress <br>\n");
			exit;	
		}
		
		$reqTxnId = StageShowLibHTTPIO::GetRequestedString('TxnId');
		$expIPTxnId = $this->myDBaseObj->adminOptions['AuthTxnId'];
		if ($reqTxnId != $expIPTxnId)
		{
			StageShowLibEscapingClass::Safe_EchoHTML("Connection denied!<br>\n");
			StageShowLibEscapingClass::Safe_EchoHTML("Requesting AuthTxnId: $reqTxnId <br>\n");
			exit;	
		}
	}

	function ServerAuth()
	{
		// Get a list of spooled tickets that are waiting
		$this->ServerInitialise();
		StageShowLibEscapingClass::Safe_EchoHTML("OK");
	}
	
	function ServerList($timeout = 30000)
	{
		// Get a list of spooled tickets that are waiting
		$this->ServerInitialise();
		
		$sleepTime = 500;	// Sleep time in ms
		$usleepVal = $sleepTime * 1000;
		
		$spoolerList = '';
		for (; $timeout>0; $timeout-=$sleepTime)
		{
			$spoolerList = $this->myDBaseObj->GetPrintQueue();
			if ($spoolerList != '') break;
			usleep($usleepVal);
		}
		
		StageShowLibEscapingClass::Safe_EchoHTML($spoolerList);
	}

	function ServerPrint()
	{
		include STAGESHOW_INCLUDE_PATH.'stageshowlib_httpio.php';   
		   
		$this->ServerInitialise();
		
		$printID = StageShowLibHTTPIO::GetRequestedInt('id');
		$nextPrintEntry = $this->myDBaseObj->GetPrintContent($printID);

		if (count($nextPrintEntry) > 0)
		{
			$printContent = $nextPrintEntry[0]->printContent;
			StageShowLibEscapingClass::Safe_EchoHTML($printContent);
		}	
	}

	function ServerAck()
	{
		include STAGESHOW_INCLUDE_PATH.'stageshowlib_httpio.php'; 
		     
		$this->ServerInitialise();
		
		$printID = StageShowLibHTTPIO::GetRequestedInt('id');
		$this->myDBaseObj->PrintComplete($printID);
	}

	function ServerSpool()
	{
		//$this->ServerInitialise();
		include STAGESHOW_INCLUDE_PATH.'stageshowlib_nonce.php';      
		
		$targetFile = basename(__FILE__);
		$callerNOnce = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, 'nonce');
		$ourNOnce = StageShowLibNonce::GetStageShowLibNonce($targetFile);
		if ($callerNOnce != $ourNOnce)
		{
			StageShowLibEscapingClass::Safe_EchoHTML("Authorisation Failed\n");
//			StageShowLibEscapingClass::Safe_EchoHTML("Expected NOnce: $ourNOnce\n");
//			StageShowLibEscapingClass::Safe_EchoHTML("Our NOnce: $callerNOnce\n");
			exit;
		}
	
		include STAGESHOW_INCLUDE_PATH.'stageshow_dbase_api.php';
		include STAGESHOW_INCLUDE_PATH.'stageshowlib_httpio.php';      

		$DBClass = 'StageShowDBaseClass';
		$this->myDBaseObj = new $DBClass(__FILE__);
		
		$saleID = StageShowLibHTTPIO::GetRequestedInt('id', 0);
		if ($saleID == 0)
		{
			StageShowLibEscapingClass::Safe_EchoHTML("Sale Not Specified");
			exit;
		}
		
		$printedCount = $this->myDBaseObj->SpoolTickets($saleID);
		
		StageShowLibEscapingClass::Safe_EchoHTML("Tickets Printed: $printedCount");
	}
}

new StageShowPrintServer();



