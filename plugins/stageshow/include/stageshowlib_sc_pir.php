<?php

if (!class_exists('StageShowLibPIRShortcodeClass')) 
{
	include 'stageshowlib_httpio.php';
	
	if (!defined('PAYMENT_PIRREQ_EMAIL_TEXTLEN'))
	{
		define('PAYMENT_PIRREQ_EMAIL_TEXTLEN', PAYMENT_API_SALEEMAIL_TEXTLEN);
	}
	
	if (!defined('PAYMENT_PIRREQ_EMAIL_EDITLEN'))
	{
		define('PAYMENT_PIRREQ_EMAIL_EDITLEN', 50);
	}
	
	class StageShowLibPIRShortcodeClass // Define class
	{	
		function __construct($pluginObj)
		{	
			$this->pluginObj = $pluginObj;
			$this->myDBaseObj = $pluginObj->myDBaseObj;
		}
		
		function CreateExportObj()
		{
			die('CreateExportObj() must be defined in '.get_class($this));
		}
		
		function GetTSVData($saleRecords)
		{
			if (!StageShowLibUtilsClass::IsElementSet('post', 'pirDownload')) return '';

			$exportObj = $this->CreateExportObj();
			$tsvData = $exportObj->GetPIRExportFile($saleRecords);

			return $tsvData;
		}
		
		function GetTSVFileName($tsvData)
		{
			return "dbrecords.tsv";
		}
		
		function AddTSVDataToEMail($filePath, $tsvData)
		{
			if ($tsvData == '') return;

			// Add TSV download to email 
			$this->myDBaseObj->emailObj->AddAttachmentFromData($tsvData, $filePath, 'text/csv');
		}
		
		function Output($atts)
		{
	  		// FUNCTIONALITY: Runtime - Output Shop Front
			$myDBaseObj = $this->myDBaseObj;
			
			if ((StageShowLibUtilsClass::IsElementSet('post', 'pirConsentLink')) && (current_user_can(STAGESHOWLIB_CAPABILITY_DEVUSER)))
			{
				$outputContent = '';
				
				$EMailTo = $myDBaseObj->GetSafeString('pirEMail', '');
				
				if (StageShowLibMigratePHPClass::Safe_strlen($EMailTo) > 0)
				{
					$url = $myDBaseObj->GetConsentUpdateURL($EMailTo, StageShowLibSalesDBaseClass::CONSENT_ALLOWED);
					$outputContent .= '<a href="'.$url.'">Grant Consent</a><br>';				
					
					$url = $myDBaseObj->GetConsentUpdateURL($EMailTo, StageShowLibSalesDBaseClass::CONSENT_DENIED);
					$outputContent .= '<a href="'.$url.'">Deny Consent</a><br>';				
					
					return $outputContent;
				}
			}
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'dbReq'))
			{
				$dbReqMsg = __('Any database records matching your email have been sent to you.', 'stageshow');
				$dbReqMsg = apply_filters('stageshow'.'_filter_dbreqmsg', $dbReqMsg);
				$msgClass = 'stageshow'.'-ok ok';
			
				$EMailTo = $myDBaseObj->GetSafeString('pirEMail', '');
				if ($EMailTo != '') 
				{
					$saleRecords = $myDBaseObj->GetPIRRecordsByEMail($EMailTo);
					if (count($saleRecords) > 0)
					{
						$tsvData = $this->GetTSVData($saleRecords);
						// Build template and create email object
						$templatePath = $myDBaseObj->GetEmailTemplatePath('DBEMailTemplatePath');
						$emailRslt = $myDBaseObj->BuildEMailFromTemplate($saleRecords, $templatePath, $EMailTo);
						$rtnstatus = $emailRslt['status'];						
						if ($rtnstatus == 'OK')
						{
							$filePath = $this->GetTSVFileName($saleRecords[0]);		
							$this->AddTSVDataToEMail($filePath, $tsvData);
							$rtnstatus = $myDBaseObj->SendEMailWithDefaults($saleRecords, $emailRslt['subject'], $emailRslt['email'], $EMailTo);
						}
						else
						{							
							$dbReqMsg = $rtnstatus;
							$msgClass = 'stageshow'.'-error error';
						}
					}
				}
				
				$outputContent  = '
					<style>
					'.$msgClass.' 
					{
						padding: 10px;
					}
					</style>
					';
				
				$outputContent .= '<div class="'.$msgClass.'">'.$dbReqMsg.'</div>';
				
				return $outputContent;
			}
			
			$downloadText = __('Tick to attach download with email', 'stageshow');			
			$buttonText = __('Submit Request', 'stageshow');
			
			$button = $this->pluginObj->GetButtonTextAndTypeDef($buttonText, 'dbReq');
			
			$outputContent = '
				<form method="post">
					<table>
						<tr class="pir-row-email">
							<td class="pir-row-label">EMail:</td>
							<td><input type="text" maxlength="'.PAYMENT_PIRREQ_EMAIL_TEXTLEN.'" size="'.PAYMENT_PIRREQ_EMAIL_EDITLEN.'" name=pirEMail id=pirEMail value="" autocomplete="off" /></td>
						</tr>
						<tr class="pir-row-download">
							<td class="pir-row-label">Download:</td>
							<td><input type="checkbox" name=pirDownload id=pirDownload value="1" />'.$downloadText.'</td>
						</tr>
						<tr class="pir-row-submit">
							<td class="pir-row-label" colspan=2>
								<input '.$button.'/>
				';
			
			$outputContent .= '
							</td>							
						</tr>
					</table>
				</form>
			';
			
			$outputContent = apply_filters('stageshow'.'_filter_dbreqform', $outputContent);
			
			return $outputContent;
		}
		
	}
}
		
?>