<?php
/* 
Description: Code for Overview Page
 
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

include STAGESHOW_INCLUDE_PATH.'stageshowlib_salesadmin.php';

if (file_exists(STAGESHOW_INCLUDE_PATH.'stageshow_contributors.php'))
	include STAGESHOW_INCLUDE_PATH.'stageshow_contributors.php';

include STAGESHOW_INCLUDE_PATH.'stageshowlib_admin.php';      

if (!class_exists('StageShowOverviewAdminListClass')) 
{
	class StageShowOverviewAdminListClass extends StageShowLibSalesAdminListClass // Define class
	{		
		function __construct($env) //constructor
		{
			// Call base constructor
			$editMode = false;
			parent::__construct($env, $editMode);
				
			$this->SetRowsPerPage(self::STAGESHOWLIB_EVENTS_UNPAGED);
			
			$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_TOP;
		}
		
		function GetTableID($result)
		{
			return "overviewtab";
		}
		
		function GetRecordID($result)
		{
			return $result->showID;
		}
		
		function GetMainRowsDefinition()
		{
			// FUNCTIONALITY: Overview - Shows Performances Count, Ticket sales quantity (with link to Show Sales page) and Sales Values
			$columnDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Show',         StageShowLibTableClass::TABLEPARAM_ID => 'showName',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Performances', StageShowLibTableClass::TABLEPARAM_ID => 'perfCount',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Tickets Sold', StageShowLibTableClass::TABLEPARAM_ID => 'soldQty',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE,  StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodeNullToZero', StageShowLibTableClass::TABLEPARAM_LINK =>'admin.php?page='.STAGESHOW_MENUPAGE_SALES.'&action=show&id=', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Sales', StageShowLibTableClass::TABLEPARAM_ID => 'Total_ticketPaid',StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale Extras',  StageShowLibTableClass::TABLEPARAM_ID => 'Total_saleExtras',StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale Fees',    StageShowLibTableClass::TABLEPARAM_ID => 'Total_saleFee',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Net Sales',    StageShowLibTableClass::TABLEPARAM_ID => 'Total_netSales',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
			);

			if ($this->myDBaseObj->HasExtraDiscount())
			{
				$columnDefs = self::MergeSettings($columnDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Discounts',     StageShowLibTableClass::TABLEPARAM_ID => 'Total_saleExtraDiscount',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_BEFORE => 'Total_saleExtras', StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
					)
				);				
			}
			
			if ($this->myDBaseObj->isOptionSet('AllowDonation'))
			{
				$columnDefs = self::MergeSettings($columnDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Donations',     StageShowLibTableClass::TABLEPARAM_ID => 'Total_saleDonation',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_BEFORE => 'Total_saleExtras', StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
					)
				);				
			}
			
			if ($this->myDBaseObj->getOption('ReservationsMode') != STAGESHOW_RESERVATIONSMODE_DISABLED)
			{
				$columnDefs = self::MergeSettings($columnDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Reservations',     StageShowLibTableClass::TABLEPARAM_ID => 'reservedQty',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodeNullToZero', StageShowLibTableClass::TABLEPARAM_BEFORE => 'soldQty', ),
					)
				);				
			}
						
			$columnDefs = self::MergeSettings($columnDefs, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Locked Out',     StageShowLibTableClass::TABLEPARAM_ID => 'lockedQty',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodeNullToZero', StageShowLibTableClass::TABLEPARAM_AFTER => 'perfCount', ),
				)
			);				
			
			return $columnDefs;
		}
		
		

		function GetDetailsRowsDefinition()
		{
			$ourOptions = array(
//				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Name',	                     StageShowLibTableClass::TABLEPARAM_ID => 'showName',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALENAME_TEXTLEN,      StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALENAME_EDITLEN, ),
			);
			
			$ourOptions = array_merge(parent::GetDetailsRowsDefinition(), $ourOptions);
			return $ourOptions;
		}
		
		function GetDetailsRowsFooter()
		{
			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_ID => 'saleDetails', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_FUNCTION, StageShowLibTableClass::TABLEPARAM_FUNC => 'ShowSaleDetails'),						
			);
			
			$ourOptions = array_merge(parent::GetDetailsRowsFooter(), $ourOptions);
			
			return $ourOptions;
		}
		
		function ShowSaleDetails($result, $saleResults)
		{		
			// FUNCTIONALITY: Overview - Output Peformances List
			$env = $this->env;
			$salesList = $this->CreateAdminDetailsListObject($env, $this->editMode);	
			
			// Set Rows per page to disable paging used on main page
			$salesList->enableFilter = false;

			$perfsList = $this->myDBaseObj->GetOverviewByShowID($result->showID);
			
			ob_start();	
			if (count($perfsList) > 0)
				$salesList->OutputList($perfsList);	
			else
				StageShowLibEscapingClass::Safe_EchoHTML(__("No Sales", 'stageshow'));
			$saleDetailsOoutput = ob_get_contents();
			ob_end_clean();

			return $saleDetailsOoutput;
		}
		
		function CreateAdminDetailsListObject($env, $editMode)
		{		
			return new StageShowOverviewAdminDetailsListClass($env, $editMode);	
		}
		
	}
}

if (!class_exists('StageShowOverviewAdminDetailsListClass')) 
{
	class StageShowOverviewAdminDetailsListClass extends StageShowLibSalesAdminListClass // Define class
	{		
		function __construct($env, $editMode = false) //constructor
		{
			// Call base constructor
			parent::__construct($env, $editMode);
			
			$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_TOP;
		}
		
		function GetTableID($result)
		{
			return "showtab".$result->showID;
		}
		
		function GetRecordID($result)
		{
			return $result->rowPerfID;
		}
		
		function GetMainRowsDefinition()
		{
			// FUNCTIONALITY: Overview - Show button lists performances, sales (with link) and value
			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Performance',  StageShowLibTableClass::TABLEPARAM_ID => 'perfDateTime',     StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatDateForAdminDisplay', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Tickets Sold', StageShowLibTableClass::TABLEPARAM_ID => 'soldQty',          StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodeNullToZero', StageShowLibTableClass::TABLEPARAM_LINK =>'admin.php?page='.STAGESHOW_MENUPAGE_SALES.'&action=perf&id=', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Sales', StageShowLibTableClass::TABLEPARAM_ID => 'Total_ticketPaid', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale Extras',  StageShowLibTableClass::TABLEPARAM_ID => 'Total_saleExtras', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale Fees',    StageShowLibTableClass::TABLEPARAM_ID => 'Total_saleFee',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Net Sales',    StageShowLibTableClass::TABLEPARAM_ID => 'Total_netSales',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
			);
			
			$columnDefs = array_merge(parent::GetDetailsRowsDefinition(), $ourOptions);

			if ($this->myDBaseObj->isOptionSet('AllowDonation'))
			{
				$columnDefs = self::MergeSettings($columnDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Donations',     StageShowLibTableClass::TABLEPARAM_ID => 'Total_saleDonation',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_BEFORE => 'Total_saleExtras', StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),
					)
				);				
			}
			
			if ($this->myDBaseObj->getOption('ReservationsMode') != STAGESHOW_RESERVATIONSMODE_DISABLED)
			{
				$columnDefs = self::MergeSettings($columnDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Reservations',     StageShowLibTableClass::TABLEPARAM_ID => 'reservedQty',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodeNullToZero', StageShowLibTableClass::TABLEPARAM_BEFORE => 'soldQty', ),
					)
				);				
			}
						
			$columnDefs = self::MergeSettings($columnDefs, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Locked Out', StageShowLibTableClass::TABLEPARAM_ID => 'lockedQty',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_AFTER => 'perfDateTime', StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodeLockedOut', StageShowLibTableClass::TABLEPARAM_LINK =>'admin.php?page='.STAGESHOW_MENUPAGE_PERFORMANCES.'&action=editlockout&id=', ),
				)
			);				
			
			return $columnDefs;
		}
		
		function DecodeLockedOut($value, $result)
		{
			if ($result->perfSeatingID == 0) return '';
			
			return self::DecodeNullToZero($value, $result);
		}
	}
}

if (!class_exists('StageShowOverviewAdminClass')) 
{
	class StageShowOverviewAdminClass extends StageShowLibAdminClass // Define class
	{
		function __construct($env)
		{
			$this->pageTitle = 'Overview';

			// Call base constructor
			parent::__construct($env);
		}
		
		function ProcessActionButtons()
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj  = $this->myDBaseObj;
			
			// FUNCTIONALITY: Overview - Action "Create Sample" Button
			if(StageShowLibUtilsClass::IsElementSet('post', 'createsample'))
			{
				$myPluginObj->CreateSample();
			}
		}
		
		function Output_MainPage($updateFailed)
		{
			// Stage Show Overview HTML Output - Start 
			$this->Output_Overview();
			$this->Output_UpdateServerHelp();
			$this->Output_Help();
			$this->Output_TrolleyAndShortcodesHelp();
			$this->Output_WebserverHelp();
			$this->Output_UpdateInfo();
			$this->Output_Contributors();
		}
		
		function Output_Overview()
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$isConfigured = $myDBaseObj->CheckIsConfigured();
						
			$showsList = $myDBaseObj->GetOverview();			
						
?>
	<br>
	<h2><?php _e('Shows', 'stageshow'); ?></h2>
<?php	
	
			if(count($showsList) == 0)
			{
				// FUNCTIONALITY: Overview - Show Link to Settings page if Payment Gateway settings required
				if ($isConfigured)
				{
					// FUNCTIONALITY: Overview - Show message and "Create Sample" button if no shows configured
					StageShowLibEscapingClass::Safe_EchoHTML("<div class='noconfig'>".__('No Show Configured', 'stageshow')."</div>\n");
					StageShowLibEscapingClass::Safe_EchoHTML('
					<form method="post" action="admin.php?page='.STAGESHOW_MENUPAGE_ADMINMENU.'">
					<br>
						<input class="button-primary" type="submit" name="createsample" value="'.__('Create Sample', 'stageshow').'"/>
					<br>
					</form>');
				}
			}
			else
			{
				$overviewList = $this->CreateAdminListObj($this->env);
				$overviewList->OutputList($showsList);	
					
				$saleCounts = array();
				$salesGross = 0;
				$salesNet = 0;
				foreach ($showsList as $showEntry)
				{
					$salesGross += $showEntry->Total_ticketPaid;
					$salesNet += $showEntry->Total_netSales;
				}
				
				$salesGross = $myDBaseObj->FormatCurrencyValue($salesGross);
				$salesNet = $myDBaseObj->FormatCurrencyValue($salesNet);
				
				if ($salesGross > 0) 
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<br>'.__('Total Sales', 'stageshow').': ');
					StageShowLibEscapingClass::Safe_EchoHTML($salesGross.' '.__('Gross', 'stageshow').' ');
					StageShowLibEscapingClass::Safe_EchoHTML($salesNet.' '.__('Net', 'stageshow')."<br>\n");
				}
			}
		}
		
		function Output_Help()
		{
			$myDBaseObj = $this->myDBaseObj;
?>
	<br>			
	<h2><?php _e('Help', 'stageshow'); ?></h2>
<?php
			$help_url  = STAGESHOW_URL.'docs/StageShowHelp.pdf';
			
			StageShowLibEscapingClass::Safe_EchoHTML(__('User Guide is Available', 'stageshow').' <a href="'.$help_url.'">'.__('Here', 'stageshow').'</a> (PDF)<br>');
		}
		
		function Output_TrolleyAndShortcodesHelp()
		{
			StageShowLibEscapingClass::Safe_EchoHTML('<br><h2>'.__("Plugin Info & Shortcodes", 'stageshow')."</h2>\n");
			
			$this->myDBaseObj->Output_PluginHelp();
			
			StageShowLibEscapingClass::Safe_EchoHTML('<br>'.__('StageShow generates output to your Wordpress pages for the following shortcodes:', 'stageshow')."<br><br>\n");
	
			$shortcode = $this->env['PluginObj']->shortcode;
			$dbshortcode = $this->env['PluginObj']->dbshortcode;
			$this->Output_ShortcodeHelp($shortcode, $dbshortcode);
		}
		
		function Output_ShortcodeHelpButton()
		{
			$customPHPSampleURL  = StageShowLibUtilsClass::GetCallbackURL(STAGESHOW_SAMPLES_TARGET);
			$customPHPSampleURL .= '&file=stageshow_shortcodes_sample.php';
			$buttonText = __('View Samples', 'stageshow');
			
			StageShowLibEscapingClass::Safe_EchoHTML('<a target="_blank" class="button-secondary" href="'.$customPHPSampleURL.'">'.$buttonText.'</a>');
		}
		
		function Output_ShortcodeHelp($shortcode, $dbshortcode)
		{
			// FUNCTIONALITY: Overview - Show Help for Shortcode(s))
?>
			<div class="stageshow-overview-info">
			<table class="widefat" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('Shortcode', 'stageshow'); ?></th>
						<th><?php _e('Description', 'stageshow'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?>]</td>
						<td><?php _e('Add Box Office with all shows and performances', 'stageshow'); ?></td>
					</tr>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> id="n"]</td>
						<td><?php _e('Add Box Office with all performances for show number "n"', 'stageshow'); ?></td>
					</tr>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> perf="n"]</td>
						<td><?php _e('Add Box Office for performance number "n"', 'stageshow'); ?></td>
					</tr>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> id="Show Name"]</td>
						<td><?php _e('Add Box Office with all performances for show identified by "Show Name"', 'stageshow'); ?></td>
					</tr>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> id="Show Name" perf="Date & Time"]</td>
						<td><?php _e('Add Box Office for show identified by "Show Name" and performance "Date & Time"', 'stageshow'); ?></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><?php _e('(add * at end of "Show Name" to matche an entry that includes the name)', 'stageshow'); ?></td>
					</tr>
<?php
			$this->Output_ShortcodeHelpExtras($shortcode, $dbshortcode);
?>					
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> count=1]</td>
						<td><?php _e('Add Box Office with the next "count" shows (i.e. count=1 is next show)', 'stageshow'); ?></td>
					</tr>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> anchor=trolley]</td>
						<td><?php _e('Aligns page with specified anchor on trolley update', 'stageshow'); ?></td>
					</tr>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($dbshortcode); ?>]</td>
						<td><?php _e('Add Private Data Request form', 'stageshow'); ?></td>
					</tr>
					<tr>
						<td colspan="2" align="center">							
<?php
			$this->Output_ShortcodeHelpButton();
?>					
						</td>
					</tr>
				</tbody>
			</table>
			</div>
			<?php
		}	

		function Output_Contributors()
		{
			StageShowLibEscapingClass::Safe_EchoHTML('<br><h2>'.__("Contributors", 'stageshow')."</h2>\n");
			$contName = __("Name", 'stageshow');
			$contContrib = __("Contribution", 'stageshow');
			
			StageShowLibEscapingClass::Safe_EchoHTML('
<div class="stageshow-overview-info">
<table class="widefat" cellspacing="0">
<thead><tr class="stageshow-overview"><th>'.$contName.'</th><th>'.$contContrib.'</th><th>URL</th></tr></thead>
<tbody>
');
			$contributorsObj = new StageShowContributorsClass();
			$contributorsList = $contributorsObj->GetContributors();
			foreach ($contributorsList as $contributor)
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<tr>');
				StageShowLibEscapingClass::Safe_EchoHTML('<td>'.$contributor->name.'</td>');
				StageShowLibEscapingClass::Safe_EchoHTML('<td>'.$contributor->contribution.'</td>');
				StageShowLibEscapingClass::Safe_EchoHTML('<td>'.$contributor->url.'</td>');
				StageShowLibEscapingClass::Safe_EchoHTML('</tr>');
			}
?>
</tbody>
</table>
</div>
<?php
		}
		
		function Output_WebserverHelp()
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$usingAPC = (extension_loaded('apc') && ini_get('apc.enabled'));
			if ($usingAPC != '')
			{
				// FUNCTIONALITY: Overview - Output APC Warning 
				$msg = "<strong>Server Compatibility Error:</strong> Website Server has APC Enabled in PHP settings<br>\n";
				$onClick = 'onclick=stageshowlib_HideElement("stageshow-webserver-container")';
				$onClick = '';
				StageShowLibEscapingClass::Safe_EchoHTML('<span id="stageshow-webserver-container" '.$onClick.'>');
				StageShowLibEscapingClass::Safe_EchoHTML("<br><h2>".__('Website Server Configuration', 'stageshow')."</h2>\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error inline"><p>'.$msg.'</p></div>');
				StageShowLibEscapingClass::Safe_EchoHTML('</span>');
			}		
				
		}
		
		function Output_UpdateServerHelp()
		{
			$actionMsg = $this->myDBaseObj->GetPluginStatus();
			if ($actionMsg != '')
			{			
				if (StageShowLibUtilsClass::IsElementSet('get', 'section'))
				{
					$id = StageShowLibUtilsClass::GetHTTPTextElem('get', 'section');
					StageShowLibEscapingClass::Safe_EchoScript('
<script>
StageShowLib_addWindowsLoadHandler(stageshow_GotoDCNotice); 

function stageshow_GotoDCNotice()						
{
	var id = "'.$id.'";
	var elmnt = document.getElementById(id);
	elmnt.scrollIntoView(); 
}
</script>');
				}
				
				StageShowLibEscapingClass::Safe_EchoHTML('<a id="discontinued"></a>');
				StageShowLibEscapingClass::Safe_EchoHTML('<span id="stageshow-autoupdate-settingsmsg-container" >');
				StageShowLibEscapingClass::Safe_EchoHTML("<br><h2>".__('Plugin Update', 'stageshow')."</h2>\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error inline"><p>'.$actionMsg.'</p></div>');
				StageShowLibEscapingClass::Safe_EchoHTML('</span>');
			}
		}
		
		function Output_UpdateInfo()
		{
			$myDBaseObj = $this->myDBaseObj;
			$latest = '';

			// Deal with "Not Found" error ....
			if ($latest === '')
				return;
			
			StageShowLibEscapingClass::Safe_EchoHTML('
				<br><h2>'.__('StageShow Updates', 'stageshow').'</h2>
					<table class="widefat" cellspacing="0">
						<thead>
							<tr>
								<th>'.__('Latest Updates', 'stageshow').'</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>'.$latest.'
								</td>
							</tr>
						</tbody>
					</table>
			');
		}

		function Output_ShortcodeHelpExtras($shortcode, $dbshortcode)
		{
			// FUNCTIONALITY: Overview - Show Help for Shortcode(s))
?>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> id=url-(attrib)]</td>
						<td>
						<?php _e('Add Box Office for show specified by attrib in URL', 'stageshow'); ?>
						<br>(Example: Use id=url-sid with {URL....}sid=1 or {URL....}sid="The Wordpress Show")	
						</td>
					</tr>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> perf=url-(attrib)]</td>
						<td>
						<?php _e('Add Box Office for performance specified by attrib in URL', 'stageshow'); ?>
						<br>(Example: Use perf=url-pid with {URL....}pid=1 or {URL....}pid="2015-02-24 20:00:00")	
						</td>
					</tr>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> style=drilldown]</td>
						<td>
						<?php _e('Box-Office opens with "Drill Down" show and performance selector view', 'stageshow'); ?>
						</td>
					</tr>
					<tr>
						<td>[<?php StageShowLibEscapingClass::Safe_EchoHTML($shortcode); ?> style=calendar]</td>
						<td>
						<?php _e('Box-Office opens in Calendar view', 'stageshow'); ?>
						</td>
					</tr>
			<?php
		}	
		
		// Commented out Class Def (StageShowOverviewAdminClass)

  }
}





