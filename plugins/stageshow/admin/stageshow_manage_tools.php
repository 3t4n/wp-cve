<?php
/* 
Description: Code for Admin Tools
 
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

include STAGESHOW_INCLUDE_PATH.'stageshowlib_admin.php';      
include STAGESHOW_INCLUDE_PATH.'stageshow_sales_table.php';

if ( file_exists(STAGESHOW_INCLUDE_PATH.'stageshowlib_test_emailsale.php') ) 
	include STAGESHOW_INCLUDE_PATH.'stageshowlib_test_emailsale.php'; 
 
include STAGESHOW_INCLUDE_PATH.'stageshow_salevalidate.php'; 
 
if (!class_exists('StageShowToolsAdminClass')) 
{
	class StageShowToolsAdminClass extends StageShowLibAdminClass // Define class
	{
		var	$blockOutput = false;
		
		function __construct($env) //constructor	
		{
			$this->pageTitle = 'Tools';
			$this->adminClassPrefix = $env['PluginObj']->adminClassPrefix;
			
			// Call base constructor
			parent::__construct($env);
		}
		
		function ProcessActionButtons()
		{
			// Hook to add custom action to tools page
			// Will set donePage if other tools are to be hidden
			do_action('stageshow_toolspage_output', $this);
		}
		
		function Output_MainPage($updateFailed)
		{			
?>
<div class="wrap">
	<div class="stageshow-admin-form">
<?php
			if ( current_user_can(STAGESHOWLIB_CAPABILITY_SYSADMIN)
			  && $this->myDBaseObj->IsPrintingActive() )
			{
				$this->Tools_PrintTickets();
			}
?>
	</div>
</div>
<div class="wrap">
	<div class="stageshow-admin-form">
<?php
			$this->html = '';
			if ( current_user_can(STAGESHOWLIB_CAPABILITY_VALIDATEUSER) )
			{
				$this->AddHtml($this->Tools_Validate());
			}
			if ( current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER)
			  || current_user_can(STAGESHOWLIB_CAPABILITY_VIEWSALESUSER)
			  || current_user_can(STAGESHOWLIB_CAPABILITY_VIEWSETTINGS) )
			{
				$this->AddHtml($this->Tools_Export());
			}
			if ( current_user_can(STAGESHOWLIB_CAPABILITY_SYSADMIN) )
			{
				$this->AddHtml($this->Tools_Backup());
			}
			if (class_exists('StageShowLibTableTestEMailClass') && current_user_can(STAGESHOWLIB_CAPABILITY_DEVUSER)) 
			{
				$this->AddHtml($this->Tools_TestEMail());
			}
			if ($this->html == '')
			{
				$this->html = __('You do not have permission for any Tools', 'stageshow');
			}

			StageShowLibEscapingClass::Safe_EchoHTML($this->html);
?>
	</div>
</div>
<?php
		}

		function AddHtml($html)
		{
			static $alreadyBlocked = false;
			
			if (!$this->blockOutput)
			{
				$this->html .= $html;
			}
			else if (!$alreadyBlocked)
			{
				$alreadyBlocked = true;
				$this->html = $html;
			}
			else
			{
				return;
			}
		}
				
		function Tools_PrintTickets()
		{
?>			
<h3><?php _e('Print Tickets', 'stageshow'); ?></h3>
<?php 

			if (!$this->myDBaseObj->IsPrintingConfigured())
			{
				StageShowLibEscapingClass::Safe_EchoHTML(_e('Printer is not configured', 'stageshow')."\n");
				return;
			}
			
			//$this->myDBaseObj->ForceSQLDebug();
			$ticketsList = $this->myDBaseObj->GetUnprintedSales();
			
			$targetFile = 'stageshow_printserver_io.php';
			$printReqURL  = STAGESHOW_URL."include/".$targetFile;
			$TxnId = $this->myDBaseObj->adminOptions['AuthTxnId']; 

			$ourNOnce = StageShowLibNonce::GetStageShowLibNonce($targetFile);

			$js = "<script>

				var printReqURL= '".$printReqURL."';
				var txnId= '".$TxnId."';
				var ourNOnce= '".$ourNOnce."';

				/* Create list of SaleIDs, TicketIDs and .... */
				var tktList_SaleID = [];		
				var tktList_TicketID = [];		
				";

			foreach ($ticketsList as $ticketDef)
			{
	        	$js .= 'tktList_SaleID[tktList_SaleID.length] = "'.$ticketDef->saleID.'";'."\n";
			}
			
			$js .= "</script>\n";
			StageShowLibEscapingClass::Safe_EchoScript($js);

?>			
<form method="POST">
<?php 
			$this->WPNonceField('stageshowlib_export.php');
			$salesEntries = count($ticketsList);
			$noOfSales = ($salesEntries > 0) ? $salesEntries : 'no';
			$msg = "There are $noOfSales sales to be printed<br>\n";

			$printerDefPath = $this->adminOptions['PrinterDefPath'];
			$printerDefs = StageShowDBaseClass::ParsePrinterDef($printerDefPath);
			switch (count($printerDefs))
			{
				case 0:	$printerMode = __('Undefined', 'stageshow'); break;
				case 1:	$printerMode = $printerDefs[0]->Mode; break;
				default:
					$printerMode  = '<select name="printer_mode" id="printer_mode" class="stageshowlib-tools-ui">'."\n";
					foreach ($printerDefs as $printerDef)
					{
						$printerMode  .= '<option value="'.$printerDef->Mode.'">'.$printerDef->Mode."</option>\n";
					}
					$printerMode .= "</select>\n";
					break;

			}
?>
<p>
<div id=stageshowgold-print-status name=stageshowgold-print-status class="stageshow-readonly" style="width:300px"><?php StageShowLibEscapingClass::Safe_EchoHTML($msg); ?></div>
<table class="stageshow-form-table stageshow-print-table">
<tr id="stageshow-printer_mode-row">
<th><?php _e('Printer Mode', 'stageshow'); ?></th>
<td><?php StageShowLibEscapingClass::Safe_EchoHTML($printerMode); ?>
</td>
</tr>

</table>
<p class="submit">
<input type="submit" onclick="return stageshow_OnClickPrintTickets()" name="printtickets" class="button stageshowlib-tools-ui" value="<?php esc_attr_e('Print Tickets', 'stageshow'); ?>" />
</p>
</form>
<?php
//$this->myDBaseObj->ForceSQLDebug(false);
		}
		
		function Tools_Export()
		{
			$myDBaseObj = $this->myDBaseObj;
			
			ob_start();
			//$ourNOnce = StageShowLibNonce::GetStageShowLibNonce(STAGESHOW_EXPORT_TARGET);
			$actionURL = StageShowLibUtilsClass::GetCallbackURL(STAGESHOW_EXPORT_TARGET);
			
			$showsList = $myDBaseObj->GetAllShowsList();
			$perfsList = $myDBaseObj->GetAllPerformancesList();
?>				
<h3><?php _e('Export', 'stageshow'); ?></h3>
<p><?php _e('Export to a "TAB Separated Values" format file on your computer.', 'stageshow'); ?></p>
<p><?php _e('This format can be imported to many applications including spreadsheets and databases.', 'stageshow'); ?></p>
<form action="<?php StageShowLibEscapingClass::Safe_EchoHTML($actionURL); ?>" method="POST">
<?php $this->WPNonceField('stageshowlib_export.php'); ?>
<table class="stageshow-form-table stageshow-export-table">
<tr id="stageshow-export_show-row">
<th><?php _e('Show', 'stageshow'); ?></th>
<td>
<select name="export_showid" id="export_showid" class="stageshowlib-tools-ui" onchange=stageshow_onSelectShow(this)>
<?php
			StageShowLibEscapingClass::Safe_EchoHTML('<option value="0" selected="selected">'.__('All', 'stageshow')."</option>\n");
			foreach ($showsList as $showEntry)
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<option value="'.$showEntry->showID.'">'.$showEntry->showName."</option>\n");
			}	
?>	
</select>
</td>
</tr>

<tr id="stageshow-export_performance-row" style="display: none;">
<th><?php _e('Performance', 'stageshow'); ?></th>
<td>
<select name="export_perfid" id="export_perfid" class="stageshowlib-tools-ui">
<?php
			StageShowLibEscapingClass::Safe_EchoHTML('<option value="0" selected="selected">'.__('All', 'stageshow')."</option>\n");
			foreach ($perfsList as $perfEntry)
			{
				// showID is included in the value because stageshow_onSelectShow() uses it
				StageShowLibEscapingClass::Safe_EchoHTML('<option value="'.$perfEntry->showID.'.'.$perfEntry->perfID.'">'.$perfEntry->perfDateTime."</option>\n");
			}	
?>	
</select>
</td>
</tr>

<tr>
<th><?php _e('Format', 'stageshow'); ?></th>
<td>
<select name="export_format" id="export_format" class="stageshowlib-tools-ui" onchange=stageshow_onSelectExportType(this)>
	<option value="tdt" selected="selected"><?php _e('Tab Delimited Text', 'stageshow'); ?> </option>
	<option value="tsv" selected="selected"><?php _e('Tab Delimited for Excel', 'stageshow'); ?> </option>
	<option value="ofx"><?php _e('OFX', 'stageshow'); ?>&nbsp;&nbsp;</option>
</select>
</td>
</tr>
<tr id="stageshow-export_type-row">
<th><?php _e('Type', 'stageshow'); ?></th>
<td>
<select name="export_type" id="export_type" class="stageshowlib-tools-ui" onchange=stageshow_onSelectDownload(this)>
	<?php if (current_user_can(STAGESHOWLIB_CAPABILITY_SETUPUSER)) { ?>
	<option value="settings"><?php _e('Settings', 'stageshow'); ?> </option>
	<?php } ?>	
	<option value="tickets"><?php _e('Tickets', 'stageshow'); ?> </option>
	<option value="payments"><?php _e('Payments', 'stageshow'); ?> </option>
	<option value="summary" selected="selected"><?php _e('Sales Summary', 'stageshow'); ?>&nbsp;&nbsp;</option>
</select>
</td>
</tr>

<?php

			$js = '				
<script type="text/javascript">
var perfselect_id = [];
var perfselect_text = [];
perfselect_id[0] = "0";
				';
				
        	$js .= 'perfselect_text[0] = "'.__('All', 'stageshow').'";'."\n";
			foreach ($perfsList as $perfEntry)
			{
	        	$js .= 'perfselect_id[perfselect_id.length] = "'.$perfEntry->showID.'.'.$perfEntry->perfID.'";'."\n";
	        	$js .= 'perfselect_text[perfselect_text.length] = "'.$perfEntry->perfDateTime.'";'."\n";
			}	

			$js .=	"StageShowLib_addWindowsLoadHandler(stageshow_updateExportOptions); \n";
			$js .= "</script> \n";
			
			StageShowLibEscapingClass::Safe_EchoScript($js);

?>	
<tr id="stageshow-export_filter-row" style="display: none;">
<th><?php _e('Filter', 'stageshow'); ?></th>
<td>
<select name="export_filter" id="export_filter" class="stageshowlib-tools-ui">
<?php
			StageShowLibEscapingClass::Safe_EchoHTML('<option value="" selected="selected">'.__('None', 'stageshow')."</option>\n");
			
			$pluginID = STAGESHOW_FOLDER;
			$dir = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/exports/*.tab';
			$filesList = glob($dir);
		
			$optionIndex = 1;
			foreach ($filesList as $index => $filePath)
			{
				$fileName = basename($filePath);
				
				$startPosn = StageShowLibMigratePHPClass::Safe_strpos($fileName, "_");
				if ($startPosn === false) continue;
				$startPosn = StageShowLibMigratePHPClass::Safe_strpos($fileName, "_", $startPosn+1);
				if ($startPosn === false) continue;		
						
				$endPosn = StageShowLibMigratePHPClass::Safe_strlen($fileName) - 4;
				$exportName = StageShowLibMigratePHPClass::Safe_substr($fileName, $startPosn, $endPosn-$startPosn);
				$exportName = StageShowLibMigratePHPClass::Safe_str_replace("_", " ", $exportName);
				
				$filterID = 'filterSelect'.$optionIndex; 
				StageShowLibEscapingClass::Safe_EchoHTML('<option id='.$filterID.' id='.$filterID.' value="'.$fileName.'">'.$exportName."</option>\n");
				
				$optionIndex++;
			}	

?>	
</select>
</td>
</tr>
	
</table>
<p>
<p class="submit">
<input type="submit" name="downloadexport" class="button stageshowlib-tools-ui" value="<?php esc_attr_e('Download Export File', 'stageshow'); ?>" />
<input type="submit" name="downloadvalidator" id="downloadvalidator" class="button-secondary stageshowlib-tools-ui" value="<?php _e('Download Offline Validator', 'stageshow'); ?>" />
<input type="hidden" name="page" value="stageshow_tools" />
<input type="hidden" name="download" value="true" />
</p>
</form>
<?php
			$toolOutput = ob_get_contents();
			ob_end_clean();
			
			return $toolOutput;
		}

		function Tools_Validate()
		{
			ob_start();
			
			$classId = 'StageShowSaleValidateClass';
			new $classId($this->env);
			$toolOutput = ob_get_contents();
			ob_end_clean();
			
			return $toolOutput;
		}

		function Tools_Backup()
		{
			$toolOutput = '';

			ob_start();
			//$ourNOnce = StageShowLibNonce::GetStageShowLibNonce(STAGESHOW_DBEXPORT_TARGET);
			$actionURL = StageShowLibUtilsClass::GetCallbackURL(STAGESHOW_DBEXPORT_TARGET);
?>			
<h3><?php _e('Backup', 'stageshow'); ?></h3>
<form action="<?php StageShowLibEscapingClass::Safe_EchoHTML($actionURL); ?>" method="POST">
<?php $this->WPNonceField('stageshowlib_export.php'); ?>
<?php
			if ($this->myDBaseObj->IsSessionElemSet('stageshowlib_debug_test'))
			{
?>
<table class="form-table">
	<tr>
		<td width=150px ><?php _e('EMail Addresses', 'stageshow');?></td>
		<td><input type="checkbox" id="dest_DB_removeEMail" name="dest_DB_removeEMail" class="stageshowlib-tools-ui" /> <?php _e('Exclude Sale EMail Addresses', 'stageshow');?></td>
	</tr>
	<tr>
		<td width=150px ><?php _e('DB Table Prefix', 'stageshow');?></td>
		<td><input type="text" id="dest_DB_prefix" name="dest_DB_prefix" class="stageshowlib-tools-ui" maxlength="10" size="11" /> (<?php _e('Leave Blank to use default', 'stageshow');?>)</td>
	</tr>
	
</table>
<?php
			}
?>
<p class="submit">
<input type="submit" name="downloadexport" class="button stageshowlib-tools-ui" value="<?php esc_attr_e('Export StageShow Database', 'stageshow'); ?>" />
<input type="hidden" name="download" value="true" />
</p>
</form>
<?php
			
			$toolOutput = ob_get_contents();
			ob_end_clean();
			
			return $toolOutput;
		}
		
		function Tools_TestEMail()
		{
			ob_start();
			new StageShowLibTableTestEMailClass($this);
			$toolOutput = ob_get_contents();
			ob_end_clean();
			
			return $toolOutput;
		}				

	}
}

