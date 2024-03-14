<?php
/* 
Description: Code for Managing Prices Configuration
 
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

include STAGESHOW_INCLUDE_PATH.'stageshow_validate_api.php';
include STAGESHOW_INCLUDE_PATH.'stageshow_sales_table.php';
	
if (!class_exists('StageShowSaleValidateClass')) 
{
	define('STAGESHOW_TICKETID_TEXTLEN', PAYMENT_API_SALETXNID_TEXTLEN+10);
	
	if (!defined('STAGESHOWLIB_TESTSALES_LIMIT')) 
		define('STAGESHOWLIB_TESTSALES_LIMIT', 20);
	
	if (!defined('STAGESHOW_VERIFYLOG_DUPLICATEACTION')) 
		define('STAGESHOW_VERIFYLOG_DUPLICATEACTION', '');

	
	if (!defined('STAGESHOW_VALIDATERESULT_TIMEOUT')) 
		define('STAGESHOW_VALIDATERESULT_TIMEOUT', 1000);

	include STAGESHOW_INCLUDE_PATH.'stageshowlib_admin.php';
	
	if (!defined('STAGESHOW_TXNID_PARAMSDELIM'))
		define('STAGESHOW_TXNID_PARAMSDELIM', '#');
		
	class StageShowSaleValidateClass extends StageShowLibAdminClass
	{
		function __construct($env, $inForm = false) //constructor	
		{	
			$this->pageTitle = '';	// Supress warning message
			
			$valDBClass = 'StageShowValidateDBaseClass';
			$this->validateDBObj = new $valDBClass();

			parent::__construct($env);
		}
		
		function ProcessActionButtons()
		{
		}
		
		function Output_MainPage($updateFailed)
		{		
			$this->Tools_Validate();			
		}
			
		function GetValidatePerformanceSelect($perfID = 0)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			if ($perfID > 0)
			{
				$perfsList = $myDBaseObj->GetPerformancesListByPerfID($perfID);
				$perfRecord = $perfsList[0];
				$perfDateTime = StageShowLibGenericDBaseClass::FormatDateForAdminDisplay($perfRecord->perfDateTime).'&nbsp;&nbsp;';
				$perfName = $perfRecord->showName.' - '.$perfDateTime;
				$hiddenTags  = '<input type="hidden" name="perfID" id="perfID" value="'.$perfID.'"/>'."\n";
				$html = $perfName.$hiddenTags."\n";
			}
			else
			{
				// Get performances list for all shows
				$perfsList = $this->validateDBObj->GetActivePerformancesList();
			
				$selected = ' selected="" ';
				
				$html = '<select name="perfID" id="perfID" class="stageshowlib-tools-ui">'."\n";
				
				foreach ($perfsList as $perfRecord)
				{
					$perfDateTime = StageShowLibGenericDBaseClass::FormatDateForAdminDisplay($perfRecord->perfDateTime).'&nbsp;&nbsp;';
					$perfName = $perfRecord->showName.' - '.$perfDateTime;
					//$selected = ($perfID == $perfRecord->perfID) ? ' selected=""' : '';
					$html .= '<option value="'.$perfRecord->perfID.'"'.$selected.' >'.$perfName.'</option>'."\n";
					$selected = '';
				}
				
				$perfName = __("All Performances", 'stageshow' );
				//$selected = ($perfID == 0) ? ' selected=""' : '';
				$html .= '<option value="0"'.$selected.' >'.$perfName.'</option>'."\n";
				
				$html .= '</select>'."\n";
			}
						
			return $html;			
		}
		
		function Tools_Validate()
		{												
	  		// FUNCTIONALITY: Tools - Online Sale Validator
			$myDBaseObj = $this->myDBaseObj;

			StageShowLibEscapingClass::Safe_EchoHTML("<h3>".__('Validate Sale', 'stageshow')."</h3>");
			
			$this->ValidateSaleForm();
		}
				
		function ValidateSaleForm()
		{
include STAGESHOW_INCLUDE_PATH.'stageshowlib_nonce.php';      
			$myDBaseObj = $this->myDBaseObj;
			
			$TxnId = '';
			$perfID = StageShowLibUtilsClass::GetHTTPInteger($_REQUEST, 'perfID', 0);
			
			$actionURL = StageShowLibUtilsClass::GetPageURL();
			
?>
<form method="post" target="_self" action="<?php StageShowLibEscapingClass::Safe_EchoAttr($actionURL); ?>">
<div id="stageshow-validate-table">
<?php 
			$this->WPNonceField(); 
			
			StageShowLibEscapingClass::Safe_EchoHTML('
<table class="stageshow-form-table">
');		
			$TerminalLocation = StageShowLibUtilsClass::GetHTTPTextElem('post', 'location', $myDBaseObj->GetLocation()); 
			if ($TerminalLocation !== '')
			{
				StageShowLibEscapingClass::Safe_EchoHTML('
					<tr>
						<td class="stageshow_tl8" id="label_Location">'.__("Location / Computer ID", 'stageshow').'&nbsp;</td>
						<td id="value_Location">'.$TerminalLocation.'</td>
					</tr>
					');				
			}
			
			StageShowLibEscapingClass::Safe_EchoHTML('
				<tr>
					<td class="stageshow_tl8" id="label_Performance">'.__("Performance", 'stageshow').'</td>
					<td id="value_Performance">'.$this->GetValidatePerformanceSelect($perfID).'</td>
				</tr>
				');				
?>
			<tr>
				<td class="stageshow_tl8" id="label_Transaction_ID"><?php _e('Sale Reference', 'stageshow'); ?></td>
				<td id="value_Transaction_ID">
					<input class="stageshowlib-tools-ui" type="text" maxlength="<?php StageShowLibEscapingClass::Safe_EchoHTML(STAGESHOW_TICKETID_TEXTLEN); ?>" size="<?php StageShowLibEscapingClass::Safe_EchoHTML(STAGESHOW_TICKETID_TEXTLEN+2); ?>" name="TxnId" id="TxnId" value="<?php StageShowLibEscapingClass::Safe_EchoHTML($TxnId); ?>" autocomplete="off" />
					&nbsp;
					<input class="stageshowlib-tools-ui button-primary" onclick="return stageshow_OnClickValidate()" type="submit" name="jqueryvalidatebutton" id="jqueryvalidatebutton" value="Validate"/>
				</td>
			</tr>
			<?php
			$jQueryURL = admin_url( 'admin-ajax.php' );
			
			$ourNOnce = StageShowLibNonce::GetStageShowLibNonce(STAGESHOW_SALEVALIDATE_TARGET);

			StageShowLibEscapingClass::Safe_EchoScript('<script>
				jQuery(document).ready(
					function()
					{
					   jQuery("#TxnId").on("change textInput input", function () 
					   {
					        var txnid = this.value;
					    	if (txnid.length > 0)
							{
								var lastChar = txnid.slice(-1);
								if (lastChar == " ")
								{
						      		stageshow_OnClickValidate();
								}
							}
					    });

					    jQuery("#TxnId").keypress(function(e)
					    {
					    	if (e.keyCode == 13)
					    	{
					      		stageshow_OnClickValidate();
							}
					    });
					    
						jQuery("#jqueryvalidatebutton").prop("disabled", false);
						stageshow_set_txnid_focus();
					}
				);

				
				function stageshow_set_txnid_focus()
				{
					jQuery("#TxnId").focus();
				}	

				function stageshow_JQuery_OnClickValidate()
				{');
				
			if (defined('STAGESHOWLIB_JQUERY_DISABLE') || $this->myDBaseObj->isDbgOptionSet('Dev_DisableJS')) 
			{
				StageShowLibEscapingClass::Safe_EchoScript('
					return true;');	
			}
			else
			{
				StageShowLibEscapingClass::Safe_EchoScript('			
					/* Get input values from form */
					var TxnId = jQuery("#TxnId").val();
					var perfID = jQuery("#perfID").val();
					var location = jQuery("#value_Location").html();
					
					if (TxnId.length <= 0) return;
		
					/* Disable the button and input box .... this will be replaced when the page refreshes */					
					/* Set Cursor to Busy and Disable All UI Buttons */
					StageShowLib_SetBusy(true, "stageshowlib-tools-ui");

					var postvars = {
						jquery: "true"
					};
					');
			
			$postParams = '';
					
			StageShowLibEscapingClass::Safe_EchoScript('					
					postvars.nonce = "'.$ourNOnce.'";
					postvars.TxnId = TxnId;
					postvars.perfID = perfID;
					postvars.location = location;
					postvars.validatesalebutton = true;
					
					postvars.request  = "stageshowlib_jquery_callback";
					postvars.target = "'.STAGESHOW_SALEVALIDATE_TARGET.'";
					postvars.sessionID = "'.$myDBaseObj->sessionCookieID.'";
					postvars.action = "stageshowlib_ajax_request";		// Determines the AJAX handler

										
					/* Call Server to validate sale */
					var url = "'.$jQueryURL.'";
				    jQuery.post(url, postvars,
					    function(data,status)
					    {
							divElem = jQuery("#stageshow-validate-table");
							divElem.html(data);
							
							/* Move .updated and .error alert boxes. Do not move boxes designed to be inline. */
							/* Code copied from wp-admin\js\common.js */
							/*
							jQuery("div.wrap h2:first").nextAll("div.updated, div.error").addClass("below-h2");
							jQuery("div.updated, div.error").not(".below-h2, .inline").insertAfter( $("div.wrap h2:first") );
							*/
							
							/* Apply translations to any message */
							messageElem = jQuery(".stageshow-validate-message");
							messageHtml = messageElem.html();
							messageElem.html(messageHtml);

							/* Set Cursor to Normal and Enable All UI Buttons */
							StageShowLib_SetBusy(false, "stageshowlib-tools-ui");
					    	jQuery("#jqueryvalidatebutton").focus();
					    	setTimeout(stageshow_set_txnid_focus, '.STAGESHOW_VALIDATERESULT_TIMEOUT.');
					    }
				    );
				    
				    return false;
				    
				}
			');
			}
			StageShowLibEscapingClass::Safe_EchoScript('
				</script>');	
			
			$env = $this->env;
			if(StageShowLibUtilsClass::IsElementSet('request', 'jqueryvalidatebutton'))
			{
				$_REQUEST['validatesalebutton'] = true;
				$DBClass = 'StageShowValidateDBaseClass';
				$env['DBaseObj'] = new $DBClass();
				
			}
			
			if(StageShowLibUtilsClass::IsElementSet('request', 'validatesalebutton'))
			{
				$this->ValidateSale($env, $perfID);
			}
			else
			{
				StageShowLibEscapingClass::Safe_EchoHTML($this->SaleSummaryTable($this->env, array()));
				StageShowLibEscapingClass::Safe_EchoHTML($this->ShowValidation($this->env));	
			}
			StageShowLibEscapingClass::Safe_EchoHTML('
			</table>
			</div>
			</form>
			');
		}

		function ShowValidation($env, $ticketDetails = null)
		{
			$myDBaseObj = $env['DBaseObj'];
			
			if ($ticketDetails != null)
			{
				$verifyList = $myDBaseObj->GetVerifysList($ticketDetails);
				if (count($verifyList) == 0)
					return '';				
			}
			else
			{
				$verifyList = array();
			}
			
			ob_start();
			$salesList = new StageShowSalesAdminVerifyListClass($env);							
			$salesList->blankTableClass = "stageshow-hidden-table";	
			StageShowLibEscapingClass::Safe_EchoHTML('<tr><td colspan="2">'."\n");
			$salesList->OutputList($verifyList);	
			StageShowLibEscapingClass::Safe_EchoHTML("</td></tr>\n");
			$verifyDetails = ob_get_contents();
			ob_end_clean();
			
			return $verifyDetails;
		}
			
		function LogValidation($ticketDetails)
		{
			$this->validateDBObj->LogVerify($ticketDetails);
		}

		function GetValidateReqTxnid($TxnId)
		{	
			if (preg_match('/^([A-Z0-9_\-]*)$/i', $TxnId) != 1)
				return null;
		
			$ticketDetails = new stdClass();
			$ticketDetails->saleID = 0;
			$ticketDetails->txnId = $TxnId;
			
			$txnIdParts = explode(STAGESHOW_TXNID_PARAMSDELIM, $TxnId);
			$ticketDetails->ticketID = (count($txnIdParts) >= 3) ? $txnIdParts[1] : 0;
			$ticketDetails->ticketNo = (count($txnIdParts) >= 3) ? $txnIdParts[2] : 0;
			
			return $ticketDetails;
		}
		
		function ValidateSale($env, $perfID)
		{
			$myDBaseObj = $env['DBaseObj'];

			$myDBaseObj->CheckAdminReferer();
			$TxnId = StageShowLibMigratePHPClass::Safe_trim(StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, 'TxnId'));
			$ticketDetails = $this->GetValidateReqTxnid($TxnId);
			if ($ticketDetails == null)
				return 0;

			$verifyMessageHTML = '';
			$saleDetailsHTML = '';
			$ticketsListTableHTML = '';
			$validatedMessageHTML = '';
			
			$validateMsg = __('Sale Validation', 'stageshow').' ('.__('Sale Reference', 'stageshow').': '.$TxnId.') - ';
			$msgClass = '';
			$showDetails = true;
			
			if (StageShowLibMigratePHPClass::Safe_strlen($ticketDetails->txnId) == 0) return 0;
			
			$ticketsList = $results = $myDBaseObj->GetAllSalesListBySaleTxnId($ticketDetails);
			
			$entryCount = count($results);
			if ($entryCount == 0)
			{
				$validateMsg .= __('No matching record', 'stageshow');
				$msgClass = 'stageshow-validate-notfound';							}
			else
			{
				$ticketDetails->saleID = $results[0]->saleID;
			 
				// Check that it is for selected performance
				if ($perfID != 0)
				{
					$matchingSales = 0;
					for ($index = 0; $index<$entryCount; $index++)	
					{
						if ($results[$index]->perfID != $perfID)
						{
							unset($results[$index]);
						}
						else
						{
							$matchingSales++;
							if ($matchingSales == 1)
							{
								$salerecord = $results[$index];							
							}								
						}
					}						
				}
				else
				{
					$matchingSales = $entryCount;								
					$salerecord = $results[0];
				}
				
				if ($matchingSales == 0)
				{
					$validateMsg .= __('Wrong Performance', 'stageshow');
					$msgClass = 'stageshow-validate-wrongperf error alert';
					
					$results = $ticketsList;
					$ticketDetails->saleID = 0;
					$salerecord = $results[0];
				}	
				else
				{
					$ticketDetails->perfID = $salerecord->perfID;
					$validatedMessageHTML = $this->ShowValidation($env, $ticketDetails);
					if (($validatedMessageHTML != '') && (STAGESHOW_VERIFYLOG_DUPLICATEACTION != 'ignore'))
					{
						$validateMsg .= __('Already Verified', 'stageshow');
						$msgClass = "error stageshow-validate-duplicated";

						if (STAGESHOW_VERIFYLOG_DUPLICATEACTION == 'hide')
							$showDetails = false;					
					}
					else
					{
						$this->LogValidation($ticketDetails);
						
						$validateMsg .= __('Matching record found', 'stageshow');
						switch($salerecord->saleStatus)
						{
							case PAYMENT_API_SALESTATUS_COMPLETED:
								$msgClass = 'stageshow-validate-ok updated ok';
								break;
									
							case PAYMENT_API_SALESTATUS_RESERVED:
								$msgClass = 'stageshow-validate-reserved error alert';
								$validateMsg .= ' - '.__('Sale Status', 'stageshow').' '.__($salerecord->saleStatus, 'stageshow');
								break;
									
							default:
								$msgClass = 'stageshow-validate-unknown error';
								$validateMsg .= ' - '.__('Sale Status', 'stageshow').' '.__($salerecord->saleStatus, 'stageshow');
								break;
									
						}
					}
				}	
			}
			
					
			$verifyMessageHTML = '<tr><td colspan="2"><div id="message" class="inline stageshow-validate-message '.$msgClass.'"><p>'.$validateMsg.'</p></div></td></tr>'."\n";
			
			$ticketsListTableHTML = $this->SaleSummaryTable($env, $results);
				
			if ($ticketsListTableHTML == '')
			{
				$validatedMessageHTML = $this->ShowValidation($env);	
			}
			
			StageShowLibEscapingClass::Safe_EchoHTML("<table class='stageshow-validate-results'>\n");
			StageShowLibEscapingClass::Safe_EchoHTML($verifyMessageHTML);
			StageShowLibEscapingClass::Safe_EchoHTML($saleDetailsHTML);
			StageShowLibEscapingClass::Safe_EchoHTML($ticketsListTableHTML);
			StageShowLibEscapingClass::Safe_EchoHTML($validatedMessageHTML);
			StageShowLibEscapingClass::Safe_EchoHTML("</table>\n");
						 
			return $ticketDetails->saleID;
		}
		
		function SaleSummaryTable($env, $results)
		{
			if (count($results)>0)
			{
				$salerecord = reset($results);
				$ticketsListTableHTML = '<tr><td class="stageshow_tl8" id="label_Name">'.__('Name', 'stageshow').':</td><td id="value_Name">'.$salerecord->saleFirstName.' '.$salerecord->saleLastName.'</td></tr>'."\n";
				$ticketsListTableHTML .= '<tr><td class="stageshow_tl8" id="label_Sale_Status">'.__('Sale Status', 'stageshow').':</td><td id="value_Sale_Status">'.__($salerecord->saleStatus, 'stageshow').'</td></tr>'."\n";
			}
			else
			{
				$ticketsListTableHTML = '<tr class="stageshow-hidden-table"><td class="stageshow_tl8" id="label_Name">'.__('Name', 'stageshow').':</td><td></td></tr>'."\n";
				$ticketsListTableHTML .= '<tr class="stageshow-hidden-table"><td class="stageshow_tl8" id="label_Sale_Status">'.__('Sale Status', 'stageshow').':</td><td></td></tr>'."\n";
			}
			
			if ((count($results)>0) && ($salerecord->saleStatus == PAYMENT_API_SALESTATUS_RESERVED))
			{
				$saleDue = 0;
				foreach ($results as $result)
				{
					$saleDue += $result->ticketPaid;
				}
				$saleDue -= $results[0]->saleExtraDiscount;
				
				$ticketsListTableHTML .= '<tr><td class="stageshow_tl8" id="label_Total_Due">'.__('Total Due', 'stageshow').':</td><td id="value_Total_Due">'.$this->myDBaseObj->FormatCurrencyValue($saleDue).'</td></tr>'."\n";
			}
			else
			{
				$ticketsListTableHTML .= '<tr class="stageshow-hidden-table"><td class="stageshow_tl8" id="label_Total_Due">'.__('Total Due', 'stageshow').':</td><td></td></tr>'."\n";
			}

			ob_start();
			$classId = 'StageShowSalesAdminDetailsListClass';
			$salesList = new $classId($env);
			$salesList->blankTableClass = "stageshow-hidden-table";	
						
			StageShowLibEscapingClass::Safe_EchoHTML('<tr><td colspan="2">'."\n");
			$salesList->OutputList($results);	
			StageShowLibEscapingClass::Safe_EchoHTML("</td></tr>\n");				
			$ticketsListTableHTML .= ob_get_contents();
			ob_end_clean();
			
			return $ticketsListTableHTML;
		}
		
	}
}


