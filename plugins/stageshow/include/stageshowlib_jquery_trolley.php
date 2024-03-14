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

if (!class_exists('StageShowLibJQueryTrolley')) 
{
	include 'stageshowlib_nonce.php';
	include 'stageshowlib_dbase_base.php';
	
	if (!defined('STAGESHOWLIB_FILENAME_JQUERYCALLLOG'))
		define('STAGESHOWLIB_FILENAME_JQUERYCALLLOG', 'JQueryCallLog.txt');
		
	class StageShowLibJQueryTrolley
	{
		function __construct($cartObjClassName)
		{
			$atts = array();
			
			$cartObj = new $cartObjClassName(__FILE__);
			
			$myDBaseObj = $cartObj->myDBaseObj;
			
			if ($myDBaseObj->IsPageCached())
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="'.'stageshow'.'-error error">AJAX Call Error: Box Office Page is Cached<br>
				Update Cache Settings or Disable Caching
				</div>');
				exit;				
			}
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'sessionID'))
			{
				$sessionID = StageShowLibUtilsClass::GetHTTPTextElem('post', 'sessionID');
				$myDBaseObj->SetSessionID($sessionID);
			}
				
			if (!$myDBaseObj->SessionVarsAvailable())
			{
				$this->ShowSessionError($myDBaseObj->lastSessionErr);
			}
			
				
			$logCallToFile = $myDBaseObj->getDbgOption('Dev_LogJQueryCalls');
			$logCall = $logCallToFile || $myDBaseObj->IsSessionElemSet('stageshowlib_debug_trolley');
			
			$stringToHash = '';
        	$ourNOnce = StageShowLibNonce::GetStageShowLibNonceEx(STAGESHOWLIB_UPDATETROLLEY_TARGET, $stringToHash);
        	
			
			if (!StageShowLibUtilsClass::IsElementSet('post', '_wpnonce') || ($_POST['_wpnonce'] != $ourNOnce))
			{
				if ($logCall)
				{
					if ($logCallToFile)
					{
						$logFileObj->StampedLogToFile(STAGESHOWLIB_FILENAME_JQUERYCALLLOG, $logEntry, StageShowLibDBaseClass::ForAppending);
					}
					else
					{
						StageShowLibEscapingClass::Safe_EchoHTML($logEntry);
					}
				}
				die;
			}		
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'count'))			
			{
				$cartObj->shortcodeCount = StageShowLibUtilsClass::GetHTTPTextElem('post', 'count');
			}			

			$atts = array();
			$scattMarker = 'scatt_';
			$scattMarkerLen = StageShowLibMigratePHPClass::Safe_strlen($scattMarker);
			foreach (array_keys($_POST) as $key)
			{
				if (StageShowLibMigratePHPClass::Safe_substr($key, 0, $scattMarkerLen) == $scattMarker)
				{
					$attkey = StageShowLibMigratePHPClass::Safe_substr($key, $scattMarkerLen);
					$atts[$attkey] = StageShowLibUtilsClass::GetHTTPTextElem('post', $key);
				}
			}
			 		
			ob_start();
			$divId = $cartObj->cssTrolleyBaseID.'-trolley-jquery';			
			$hiddenDivStyle  = 'style="display: none;"';
			$trolleyDiv = "<div id=$divId name=$divId $hiddenDivStyle >\n";	
			$endDiv = '</div>'."\n";
				
			$hasActiveTrolley = $cartObj->Cart_OnlineStore_HandleTrolley();
			$trolleyContent = ob_get_contents();
			ob_end_clean();

			ob_start();
			$cartObj->Cart_OutputContent_OnlineStoreMain($atts);
			$uiOut = $cartObj->OutputContent_OnlineTrolleyUserInterface();
			
			if ($cartObj->boxofficeContent == '')
			{
				$cartObj->boxofficeContent = ob_get_contents();
			}
			ob_end_clean();		
			
			if ($hasActiveTrolley) $trolleyContent .= $uiOut;
			else if ($cartObj->storeRows > 0) $cartObj->boxofficeContent .= $uiOut;
			
			$trolleyContent = $trolleyDiv.$trolleyContent.$endDiv;
			
			$trolleyContent = apply_filters('stageshow'.'_filter_trolley', $trolleyContent);
			$cartObj->boxofficeContent = apply_filters('stageshow'.'_filter_boxoffice', $cartObj->boxofficeContent);
			
			if ($myDBaseObj->getOption('ProductsAfterTrolley'))
			{
				$outputContent = $trolleyContent.$cartObj->boxofficeContent;
			}
			else
			{
				$outputContent = $cartObj->boxofficeContent.$trolleyContent;
			}
			
			if ($logCall)
			{
				if ($logCallToFile)
				{
					$logEntry .= $outputContent;
					$logFileObj->StampedLogToFile(STAGESHOWLIB_FILENAME_JQUERYCALLLOG, $logEntry, StageShowLibDBaseClass::ForAppending);
				}
				else
				{
					StageShowLibEscapingClass::Safe_EchoHTML($logEntry);
				}
			}
			
			StageShowLibEscapingClass::Safe_EchoHTML($outputContent);
		}
		
		function ShowSessionError($lastSessionErr)
		{
			$msg = 'AJAX Call Error: Session Variables not available';
			
			switch($lastSessionErr)
			{
				case STAGESHOWLIB_SESSIONERR_FALSE: 
					$msg .= "<br>\n".__("Call to session_id returned false", 'stageshow');
					break;
				case STAGESHOWLIB_SESSIONERR_INACTIVE: 
					$msg .= "<br>\n".__("No Active Session", 'stageshow');
					break;
				case STAGESHOWLIB_SESSIONERR_NOTABLE: 
					$msg .= "<br>\n".__("Session table did not exist", 'stageshow');
					break;
					
				// Ignore No Matching Session Error - Create new session and reset trolley
				case STAGESHOWLIB_SESSIONERR_NOMATCH: 
					$msg = __("Trolley Reset (Session Timeout)", 'stageshow');
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="'.'stageshow'.'-warning warning">'.$msg.'</div>');
					return;
					
				default:
					return;
			}
			
			StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="'.'stageshow'.'-error error">'.$msg.'</div>');
			exit;
		}
	}
}



