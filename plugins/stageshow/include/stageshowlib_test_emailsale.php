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
	
if (!class_exists('StageShowLibTableTestEMailClass')) 
{
	include 'stageshowlib_httpio.php';
	
	if (!defined('STAGESHOWLIB_TESTSALES_LIMIT')) 
		define('STAGESHOWLIB_TESTSALES_LIMIT', 20);
	
	class StageShowLibTableTestEMailClass
	{
		function __construct($caller, $inForm = false) //constructor	
		{	
			$myDBaseObj = $caller->myDBaseObj;
			
			StageShowLibEscapingClass::Safe_EchoHTML('<h3>'.__('Sale EMail Test', 'stageshow').'</h3>');
			
			$this->DivertEMailTo = StageShowLibUtilsClass::GetHTTPEMail('post', 'DivertEMailTo');
			if ($this->DivertEMailTo == '')
			{
				if (defined('SALESMAN_SAMPLE_EMAIL'))
					$this->DivertEMailTo = SALESMAN_SAMPLE_EMAIL;
				else
					$this->DivertEMailTo = get_bloginfo('admin_email');
			}

			if (StageShowLibUtilsClass::IsElementSet('post', 'testbutton_EMailSale')) 
			{
				$caller->CheckAdminReferer();
				
				// Run EMail Test	
				$templatePath = '';
				if (StageShowLibUtilsClass::IsElementSet('post', 'emailTemplate'))
				{
					$templatePath = StageShowLibUtilsClass::GetHTTPFilenameElem('post', 'emailTemplate');
				}
				
				$optionGatewaySuffix = '';
				$saleID = StageShowLibHTTPIO::GetRequestedInt('TestSaleID');
				$this->DivertEMailTo = StageShowLibUtilsClass::GetHTTPEMail('post', 'DivertEMailTo');
				$saleResults = $myDBaseObj->GetSaleDetails($saleID);
				if(count($saleResults) == 0) {
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__(' Sale Record Missing', 'stageshow').'</p></div>');
				}
				else 
				{
					if ($templatePath == '')
					{
						$templatePath = $myDBaseObj->GetEmailTemplatePath('EMailTemplatePath', $saleResults);
					}
					else
					{
						$templatePath = STAGESHOWLIB_UPLOADS_PATH.'/emails/'.$templatePath;						
					}
					
					if (StageShowLibMigratePHPClass::Safe_strlen($this->DivertEMailTo) == 0)
						$this->DivertEMailTo = $saleResults[0]->saleEMail;
						
					if (StageShowLibUtilsClass::IsElementSet('post', 'EMailSale_DebugEnabled'))
						$myDBaseObj->showEMailMsgs = true;
					
					$myDBaseObj->adminOptions['BccEMailsToAdmin'] = StageShowLibUtilsClass::IsElementSet('post', 'EMailSale_BCCToAdmin');
					
					$reqMIMEOption = StageShowLibUtilsClass::GetHTTPTextElem('post', 'EMailSale_MIMEEncoding');
					if ($reqMIMEOption != '')
					{
						// Change the mode "on the fly"
						$origMIMEMode = $myDBaseObj->getOption('MIMEEncoding');
						$myDBaseObj->adminOptions['MIMEEncoding'] = $reqMIMEOption;
					}
					
					if ($myDBaseObj->SendEMailFromTemplate($saleResults, $templatePath, $this->DivertEMailTo))
						StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>'.__('EMail Sent to', 'stageshow').' '.$this->DivertEMailTo.'</p></div>');
						
					if ($reqMIMEOption != '')
					{
						// Change mode back
						$myDBaseObj->adminOptions['MIMEEncoding'] = $origMIMEMode;
					}
				}	
			}
			
			if (!$inForm) 
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<form method="post">'."\n");
			}
?>
	<?php $caller->WPNonceField(); ?>
	<table class="form-table">			
<?php
			$this->ShowCtrls($caller, $inForm);
?>
	</table>
<?php		
			if (!$inForm) StageShowLibEscapingClass::Safe_EchoHTML('</form>'."\n");
		}
		
		function ShowCtrls($caller, $inForm = false)
		{
			$myDBaseObj = $caller->myDBaseObj;
?>
		<tr valign="top">
			<td vertical-align="middle"><?php _e('Selected Sale', 'stageshow'); ?>:</td>
			<td>
				<select name="TestSaleID" id="TestSaleID" class="stageshowlib-tools-ui">
<?php
			$whereSQL  = '(saleStatus="'.PAYMENT_API_SALESTATUS_COMPLETED.'")';
			$whereSQL .= ' OR ';
			$whereSQL .= '(saleStatus="'.PAYMENT_API_SALESTATUS_RESERVED.'")';

			if (StageShowLibUtilsClass::IsElementSet('post', 'testbutton_EMailSale'))
			{
				$debugChecked = StageShowLibUtilsClass::IsElementSet('post', 'EMailSale_DebugEnabled') ? ' checked="yes" ' : '';
				$bccChecked = StageShowLibUtilsClass::IsElementSet('post', 'EMailSale_BCCToAdmin') ? ' checked="yes" ' : '';
			}
			else
			{
				$debugChecked = '';
				$bccChecked = $myDBaseObj->adminOptions['BccEMailsToAdmin'] ? ' checked="yes" ' : '';
			}
			
			$sqlFilters['whereSQL'] = " ($whereSQL) ";
			$sqlFilters['limit'] = STAGESHOWLIB_TESTSALES_LIMIT;
			$results = $myDBaseObj->GetAllSalesList($sqlFilters);		// Get list of sales (one row per sale)
			
			foreach($results as $result) 
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<option value="'.$result->saleID.'">'.$result->saleTxnId.' - '.$result->saleEMail.' - '.$result->saleDateTime.'&nbsp;&nbsp;</option>'."\n");
			}
?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<td vertical-align="middle"><?php _e('Divert EMail To', 'stageshow'); ?>:</td>
			<td>
				<input name="DivertEMailTo" id="DivertEMailTo" class="stageshowlib-tools-ui" type="text" maxlength="110" size="50" value="<?php StageShowLibEscapingClass::Safe_EchoHTML($this->DivertEMailTo); ?>" />
			</td>
		</tr>
<?php
			if (!$inForm) 
			{
?>		
		<tr valign="top">
			<td vertical-align="middle"><?php _e('MIME Encoding', 'stageshow'); ?>:</td>
			<td>
<?php
				$mimeEncodingOptions = array(
					STAGESHOWLIB_MIMEENCODING_PHPMAILER =>__('Uses PHPMailer Methods', STAGESHOWLIB_DOMAIN), 
					STAGESHOWLIB_MIMEENCODING_PLUGIN => sprintf(__('Uses %s Plugin Code', STAGESHOWLIB_DOMAIN), $myDBaseObj->get_pluginName()), 
				);

				$lastMimeOption = StageShowLibUtilsClass::GetHTTPTextElem('post', 'EMailSale_MIMEEncoding');
				if ($lastMimeOption == '') 	$lastMimeOption = $myDBaseObj->getOption('MIMEEncoding');

				StageShowLibEscapingClass::Safe_EchoHTML('
				<select name="EMailSale_MIMEEncoding">
				');
				foreach ($mimeEncodingOptions as $mimeEncodingID => $mimeEncodingValue)
				{
					$selected = ($lastMimeOption == $mimeEncodingID) ? ' selected="selected "' : '';
					StageShowLibEscapingClass::Safe_EchoHTML("<option $selected value=\"$mimeEncodingID\">$mimeEncodingValue</option>\n");
				}
				StageShowLibEscapingClass::Safe_EchoHTML('
				</select>
				');
?>		
			</td>
		</tr>
<?php
?>		
		<tr valign="top">
			<td vertical-align="middle"><?php _e('Bcc to Admin', 'stageshow'); ?>:</td>
			<td>
				<input name="EMailSale_BCCToAdmin" class="stageshowlib-tools-ui" type="checkbox" <?php StageShowLibEscapingClass::Safe_EchoHTML($bccChecked); ?> value="1"  />&nbsp;<?php _e('Enable', 'stageshow'); ?>
			</td>
		</tr>
<?php
			}
?>		
		<tr valign="top">
			<td vertical-align="middle"><?php _e('Add Diagnostics', 'stageshow'); ?>:</td>
			<td>
				<input name="EMailSale_DebugEnabled" class="stageshowlib-tools-ui" type="checkbox" value="1" <?php StageShowLibEscapingClass::Safe_EchoHTML($debugChecked); ?> />&nbsp;<?php _e('Enable', 'stageshow'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td>
				<?php $myDBaseObj->OutputViewTicketButton(); ?>
			</td>
			<td>
				<input class="button-primary stageshowlib-tools-ui" type="submit" name="testbutton_EMailSale" value="<?php _e('EMail Sale', 'stageshow'); ?>"/>
			</td>
		</tr>
	<?php		
		}
	}
}



