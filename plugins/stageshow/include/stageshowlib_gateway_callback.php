<?php

/*
Description: Gateway Callback Functions

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

// Include wp settings and plugins ...
include 'stageshowlib_gatewayroot.php';
include 'stageshowlib_logfile.php';
			      
if (!class_exists('StageShowLibGatewayCallbackClass')) 
{
	if (!defined('STAGESHOWLIB_FILENAME_LASTGATEWAYCALL'))
		define('STAGESHOWLIB_FILENAME_LASTGATEWAYCALL', 'LastGatewayCall.txt');
		
	if (!defined('STAGESHOWLIB_FILENAME_GATEWAYNOTIFY'))
		define('STAGESHOWLIB_FILENAME_GATEWAYNOTIFY', 'GatewayNotify.txt');
		
	if (!defined('STAGESHOWLIB_GATEWAYCALLBACK_VERIFYRETRIES'))
		define('STAGESHOWLIB_GATEWAYCALLBACK_VERIFYRETRIES', 4);
		
	class StageShowLibGatewayCallbackClass extends StageShowLibGatewayRootClass // Define class
	{
	    // Class variables:
	    var		$notifyDBaseObj;			//  Database access Object
    	var		$charset = 'windows-1252';
		var		$LogMessage = '';
		var		$gatewayStatus = '';
		var		$logFileMode = StageShowLibDBaseClass::ForAppending;
		
		var 	$displayIPNs = false;
		
		function __construct($targetDBaseClass, $callerPath)
		{
			$this->notifyDBaseObj = new $targetDBaseClass($callerPath);
			$this->myDBaseObj = $this->notifyDBaseObj;


			$this->emailSent = false;			
			$this->HTTPError = false;			
			$this->ourOptions = $this->notifyDBaseObj->adminOptions;

			$this->displayIPNs   = $this->notifyDBaseObj->isDbgOptionSet('Dev_IPNDisplay');
			$this->skipIPNServer = $this->notifyDBaseObj->isDbgOptionSet('Dev_IPNSkipServer');
			$this->logIPNs       = $this->notifyDBaseObj->isDbgOptionSet('Dev_IPNLogRequests');

			$this->charset = $this->QueryParam('charset', 'windows-1252');

			$this->AddToLog('Creating Callback Object - Class:'.get_class($this));
			
			$rtnStatus = $this->DoCallback();
			
			if (!$this->emailSent || $this->HTTPError)
			{
				$this->GatewayDiagnosticEMail("Payment Verification HTTP Error", $this->LogMessage);
			}	
			
			if ($this->gatewayStatus != '') die ($this->gatewayStatus);
		}
	
		function DoCallback()
		{
			// Default Callback Function ... only run if not defined is parent class
			$LogMessage = '';
			foreach (array_keys($_REQUEST) as $reqKey)
			{
				$reqValue = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, $reqKey);
				$LogMessage .= "$reqKey: $reqValue \n";
			}
			
			$LogsFolder = $this->ourOptions['LogsFolderPath'];				
			$fileName = get_class($this).STAGESHOWLIB_FILENAME_GATEWAYNOTIFY;
			$logFileObj = new StageShowLibLogFileClass($LogsFolder);
			$logFileObj->LogToFile($fileName, $LogMessage, $this->logFileMode);
			
			StageShowLibEscapingClass::Safe_EchoHTML(StageShowLibMigratePHPClass::Safe_str_replace("\n", "<br>\n", $LogMessage));
		}

		function VerifyGatewayCallback($VerifyURL, $pfParamString)
		{
			$retries = 1;
			$maxRetries = STAGESHOWLIB_GATEWAYCALLBACK_VERIFYRETRIES;
			do
			{
				$gatewayResponse = $this->notifyDBaseObj->HTTPPost($VerifyURL, $pfParamString);					
				$HTTPStatusMsg = "Gateway Response ($retries/$maxRetries): Status=".$gatewayResponse['APIStatus']." (".$gatewayResponse['APIStatusMsg'].")";
				$this->AddToLog($HTTPStatusMsg);
				
				if ($gatewayResponse['APIStatus'] == 200 )
				{
					break;
				}
				
				$this->HTTPError = true;
				
				$retries++;
			}
			while ($retries <= $maxRetries);
			
			return $gatewayResponse;
		}
		
		function CheckTxnStatus($Txn_id, $Payment_status, &$txnStatus)
		{
			$txnStatus = $this->notifyDBaseObj->GetTxnStatus($Txn_id);
			if (($txnStatus === $Payment_status) 
			 || ($txnStatus === PAYMENT_API_SALESTATUS_COMPLETED)
			 || ($txnStatus === PAYMENT_API_SALESTATUS_TIMEOUT)
			 || ($txnStatus === PAYMENT_API_SALESTATUS_SUSPENDED) )
			{
				return 'Txn_ID Already Processed';		// Entry with matching Txn_Id found
			}
					
			return '';				
		}
		
		function PaymentRejectedEMail($results)
		{
			$templatePath = $this->notifyDBaseObj->GetEmailTemplatePath('TimeoutEMailTemplatePath');
			$emailTo = $this->notifyDBaseObj->GetEmail($this->notifyDBaseObj->adminOptions);
			
			$emailData = array();
			$emailData[0] = new stdClass();

			foreach ($results as $key => $result)
			{
				if (is_numeric(StageShowLibMigratePHPClass::Safe_substr($key, -1, 1)))
					continue;
					
				$emailData[0]->$key = $result;
			}
						
			$postDump = '';
			foreach (array_keys($_POST) as $key)
			{
				$value = StageShowLibUtilsClass::GetHTTPTextElem('post', $key);
				$postDump .= "$key: $value \n";
			}			
			$emailData[0]->postDump = $postDump;
			
			$emailStatus = $this->notifyDBaseObj->SendEMailFromTemplate($emailData, $templatePath, $emailTo);
			
			return $emailStatus;
		}
		
		function GatewayErrorEMail($subject, $LogMessage)
		{
			$optionGatewaySuffix = $this->notifyDBaseObj->gatewayObj->GetOptionGatewaySuffix();
			$to = $this->notifyDBaseObj->getOption('AdminEMail'.$optionGatewaySuffix);	
			$this->GatewayEMail($subject, $LogMessage, $to);		
		}
		
		function GatewayDiagnosticEMail($subject, $LogMessage)
		{
			$to = $this->notifyDBaseObj->getDbgOption('Dev_GatewayEMailAlerts');	
			$this->GatewayEMail($subject, $LogMessage, $to);					
		}
		
		function GatewayEMail($subject, $LogMessage, $to = '')
		{
			$from = $to;
			if ($to != '')
			{
				$headers  = "From: $from";	
				$headers .= "\r\nReply-To: $from";	
										
				$orgId = $this->ourOptions['OrganisationID'];
				$subject .= " ($orgId)";
				
				//send the email
				wp_mail($to, $subject, $LogMessage, $headers);
			}		
		}
		
		function ClearLogFile()
		{
			
		}
		
		function LogDebugToFile($LogNotifyFile, $DebugMessage)
		{
			$LogsFolder = $this->ourOptions['LogsFolderPath'];				
			$logFileObj = new StageShowLibLogFileClass($LogsFolder);
			$logFileObj->LogToFile($LogNotifyFile, $DebugMessage, $this->logFileMode);
		}
			
		function AddToLog($LogLine)
		{
			if ($this->displayIPNs)
			{
	  			// FUNCTIONALITY: IPN Notify - Log IPN Messages to Screen if Dev_IPNDisplay set
				StageShowLibEscapingClass::Safe_EchoHTML("$LogLine<br>\n");
			}
		  
			if ($this->logIPNs)
			{
	  			// FUNCTIONALITY: IPN Notify - Log IPN Messages to file if Dev_IPNLogRequests set
				$LogLine .= "\n";
				
				$this->LogMessage .= $LogLine;
				$this->LogDebugToFile(STAGESHOWLIB_FILENAME_GATEWAYNOTIFY, $LogLine);
			}
		}

		function QueryParam($paramId, $default = '')
		{
			if (StageShowLibUtilsClass::IsElementSet('get', $paramId))
				$HTTPParam = StageShowLibUtilsClass::GetHTTPTextElem('get', $paramId);
			elseif (StageShowLibUtilsClass::IsElementSet('post', $paramId))
				$HTTPParam = StageShowLibUtilsClass::GetHTTPTextElem('post', $paramId);
			else
				return $default;
				
			return $HTTPParam;
		}

		function HTTPParam($paramId)
		{
			$HTTPParam = $this->QueryParam($paramId);
			if (StageShowLibMigratePHPClass::Safe_strlen($HTTPParam) > 0)
				$HTTPParam = urldecode($HTTPParam);
			
			// Convert from IPN Charset to UTF-8
			if (function_exists('iconv'))
			{
				// If iconv exists then use it ... 
				$HTTPParam = iconv($this->charset, "UTF-8", $HTTPParam);
			}
			
			return $HTTPParam;
		}

	}
}



