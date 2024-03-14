<?php
/* 
Description: Code for Managing Show Configuration

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

include STAGESHOW_INCLUDE_PATH . 'stageshowlib_admin.php';

if (!class_exists('StageShowShowsAdminListClass'))
{
	class StageShowShowsAdminListClass extends StageShowLibSalesAdminListClass // Define class
	{
		var $updateFailed;
		
		function __construct($env, $editMode = true) //constructor
		{
			$this->hiddenRowsButtonId = 'TBD';		
				
			// Call base constructor
			parent::__construct($env, true);
			
			$this->SetRowsPerPage(self::STAGESHOWLIB_EVENTS_UNPAGED);
			
			// FUNCTIONALITY: Shows - Bulk Actions - Activate/Deactivate and Delete
			$this->bulkActions = array(
				StageShowLibAdminListClass::BULKACTION_TOGGLE => __('Activate/Deactivate', 'stageshow'),
				StageShowLibAdminListClass::BULKACTION_DELETE => __('Delete', 'stageshow'),
			);
			
			$this->hiddenRowsButtonId = __('Options', 'stageshow');		
				
			// Define filters ....
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_ALL,     __('All', 'stageshow'),     __('All', 'stageshow'));
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_ACTIVE,  __('Active', 'stageshow'),  __('Active', 'stageshow'));						
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_CURRENT, __('Current', 'stageshow'), __('Current', 'stageshow'));						
			$this->defaultFilterId = STAGESHOWLIB_ADMINFILTER_CURRENT;
		}
				
		
		
		function GetTableID($result)
		{
			return "showtab";
		}
		
		function GetRecordID($result)
		{
			return $result->showID;
		}
		
		function GetMainRowsDefinition()
		{
			// FUNCTIONALITY: Shows - Lists Show Names, Tickets Sold (with link to Show Sales page) and Show "State""
			$columnDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Show Name',    StageShowLibTableClass::TABLEPARAM_ID => 'showName',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_SHOWNAME_TEXTLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Tickets Sold', StageShowLibTableClass::TABLEPARAM_ID => 'soldQty',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE,  StageShowLibTableClass::TABLEPARAM_LINK =>'admin.php?page='.STAGESHOW_MENUPAGE_SALES.'&action=show&id=', ),						
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'State',        StageShowLibTableClass::TABLEPARAM_ID => 'showState',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE,  StageShowLibTableClass::TABLEPARAM_DECODE =>'GetShowState', ),						
			);
			
			return $columnDefs;
		}
		
		function GetShowState($showState)
		{
			// FUNCTIONALITY: Shows - Report show state
			return $this->myDBaseObj->StateActiveText($showState);
		}
		
		function OutputList($results, $updateFailed = false)
		{
			// FUNCTIONALITY: Shows - Reset Shows form on update failure
			$this->updateFailed = $updateFailed;
			parent::OutputList($results, $updateFailed);
		}
		
		function GetTableData(&$results, $rowFilter)
		{
			// This function can be overloaded to get the data from the DB
			$sqlFilters['sqlLimit'] = $this->GetLimitSQL();

			if ($rowFilter != '')
			{
				$sqlFilters['whereSQL'] = $this->GetFilterSQL($rowFilter);
			}

			// Get list of sales (one row per sale)
			$results = $this->myDBaseObj->GetFilteredShowsList($sqlFilters);
		}

		function GetDetailsRowsDefinition()
		{
			$myDBaseObj = $this->myDBaseObj;
			
			// FUNCTIONALITY: Shows = Add Note to Show Options
			$dateTimeOptions = array( 
				STAGESHOW_DATETIMEOPTION_ALL.'|'.__('Date & Time', 'stageshow'), 
				/* STAGESHOW_DATETIMEOPTION_DATE.'|'.__('Date Only', 'stageshow'), */
				STAGESHOW_DATETIMEOPTION_NONE.'|'.__('None', 'stageshow'), 
				);
				
			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Date & Time Column', StageShowLibTableClass::TABLEPARAM_ID => 'showDateTimeMode', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_ITEMS => $dateTimeOptions, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Quantity Column',    StageShowLibTableClass::TABLEPARAM_ID => 'showHideQtyColumn',StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Hide Quantity Column' ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Visibility',         StageShowLibTableClass::TABLEPARAM_ID => 'showNotInListing', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Only Shown when selected by shortcode id option' ),
			);
			
			if ($myDBaseObj->IsPrintingActive())
			{
				$templatePath = STAGESHOW_UPLOADS_PATH.'/tickets/';
				$ourOptions = self::MergeSettings($ourOptions, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Template', StageShowLibTableClass::TABLEPARAM_ID => 'showTicketTemplate',   StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_DIR => $templatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'xml', StageShowLibTableClass::TABLEPARAM_HIDEEXTNS => true, StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true, /* StageShowLibTableClass::TABLEPARAM_AFTER => 'PostTicketsEnabled', */),
					)
				);
			}
			
			$gatewayObj = $myDBaseObj->gatewayObj;
			for ($acctNo = 1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$optionGatewaySuffix = $gatewayObj->GetOptionGatewaySuffix($acctNo);
				$acctEMail = $myDBaseObj->getOption('PayPalAPIEMail'.$optionGatewaySuffix);
				$gatewayIds[] = "$acctNo|$acctEMail";
			}
			
			if (count($gatewayIds) > 1)
			{
				$ourOptions = self::MergeSettings($ourOptions, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Gateway Ref', StageShowLibTableClass::TABLEPARAM_ID => 'showGatewayIndex',   StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_ITEMS => $gatewayIds, ),
					)
				);
			}
			
			// FUNCTIONALITY: Shows = Add Note to Show Options
			$ourOptions = self::MergeSettings(array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Note',               StageShowLibTableClass::TABLEPARAM_ID => 'showNote',         StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXTBOX,  StageShowLibTableClass::TABLEPARAM_ROWS => 4, StageShowLibTableClass::TABLEPARAM_COLS => 60, StageShowLibTableClass::TABLEPARAM_ALLOWHTML => true, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Performance Prefix', StageShowLibTableClass::TABLEPARAM_ID => 'perfPrefix',       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,     StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_PERFPREFIX_TEXTLEN, StageShowLibTableClass::TABLEPARAM_SIZE => STAGESHOW_PERFPREFIX_TEXTLEN, ),
			), $ourOptions);
			
			$ourOptions = self::MergeSettings(parent::GetDetailsRowsDefinition(), $ourOptions);

			return $ourOptions;
		}
		
		
		function ExtendedSettingsDBOpts()
		{
			$dbOpts['Table'] = STAGESHOW_SHOWS_TABLE;
			$dbOpts['Index'] = 'showID';
			
			return $dbOpts;
		}
		
		function GetFilterSQL($rowFilter)
		{	
			$filterSQL = '';
			switch ($rowFilter)
			{
				case STAGESHOWLIB_ADMINFILTER_ACTIVE:
					$filterSQL .= "(showState = '".STAGESHOW_STATE_ACTIVE."') AND ";
					// Fall through to next case
					
				case STAGESHOWLIB_ADMINFILTER_CURRENT:
					$currTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT);
					$filterSQL .= "((maxPerfDateTime >= '".$currTime."') || (maxPerfDateTime IS NULL))";
					break;
					
				default:
					$filterSQL = parent::GetFilterSQL($rowFilter);
					break;
			}

			return $filterSQL;
		}

		function GetDBFilterCounts($sqlSelect)
		{
			// Get shows filter counts 
			return $this->myDBaseObj->GetShowsCount($sqlSelect);
		}
		
		// Commented out Class Def (StageShowShowsAdminListClass)
	}
}

if (!class_exists('StageShowShowsAdminClass'))
{
	class StageShowShowsAdminClass extends StageShowLibAdminClass // Define class
	{
		function __construct($env) //constructor	
		{
			$this->pageTitle = 'Shows';
			
			// Call base constructor
			parent::__construct($env);			
		}
		
		function ProcessActionButtons()
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj  = $this->myDBaseObj;
			
			// FUNCTIONALITY: Shows - Save Changes
			if (StageShowLibUtilsClass::IsElementSet('post', 'savechanges'))
			{
				// Save Settings Request ....
				$showsList = $myDBaseObj->GetAllShowsList();
				
				// Verify that show names are unique 				
				if (count($showsList) > 0)
				{
					foreach ($showsList as $showEntry)
					{
						$showNamePostId = 'showName' . $showEntry->showID;
						if (!StageShowLibUtilsClass::IsElementSet('post', $showNamePostId)) continue;

						$showName = StageShowLibUtilsClass::GetHTTPTextElem('post', $showNamePostId);
						// FUNCTIONALITY: Shows - Reject Duplicate or Empty Show Name
						if (StageShowLibMigratePHPClass::Safe_strlen($showName) == 0)
						{
							$this->adminMsg = __('Empty Show Name Entry', 'stageshow');
							break;
						}
						
						if (isset($entriesList[$showName]))
						{
							$this->adminMsg = __('Duplicated Show Name', 'stageshow') . ' (' . $showName . ')';
							break;
						}
						$entriesList[$showName] = true;
					}
				}
				
				if ($this->adminMsg !== '')
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>' . __('Settings have NOT been saved', 'stageshow') . '. ' . $this->adminMsg . '</p></div>');
				}
				else
				{
					if (count($showsList) > 0)
					{
						$adminTableObj = $this->CreateAdminListObj($this->env);
						
						// Get the extended settings array
						$settings = $adminTableObj->GetDetailsRowsDefinition();
						$dbOpts   = $adminTableObj->ExtendedSettingsDBOpts();
						
						foreach ($showsList as $showEntry)
						{
							$showNamePostId = 'showName' . $showEntry->showID;
							if (!StageShowLibUtilsClass::IsElementSet('post', $showNamePostId)) continue;
							
							$newShowName = StageShowLibUtilsClass::GetHTTPTextElem('post', $showNamePostId);
							if ($newShowName != $showEntry->showName)
							{
								$myDBaseObj->UpdateShowName($showEntry->showID, $newShowName);
							}
							
							// FUNCTIONALITY: Shows - Save "Options" settings
							// Save option extensions
							$this->UpdateHiddenRowValues($showEntry, $showEntry->showID, $settings, $dbOpts);
						}
					}
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Settings have been saved', 'stageshow') . '</p></div>');
				}
			}
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'addshowbutton'))
			{
				// FUNCTIONALITY: Shows - Add a new show
				// Add Show with unique Show Name 
				$showID = $myDBaseObj->AddShow('');
				
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Default entry added - Edit and Save to update it.', 'stageshow') . '</p></div>');
			}
			
		}
		
		function Output_MainPage($updateFailed)
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj = $this->myDBaseObj;
			
			// FUNCTIONALITY: Shows - Show Link to Settings page if Payment Gateway settings required
			$isConfigured = $myDBaseObj->CheckIsConfigured();
			
?>
	<div class="stageshow-admin-form">
	<form method="post">
<?php

			$this->WPNonceField();
			
			$showsList = $myDBaseObj->GetAllShowsList();
			if (count($showsList) == 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<div class='noconfig'>" . __('No Show Configured', 'stageshow') . "</div>\n");
			}
			else
			{
				$adminTableObj = $this->CreateAdminListObj($this->env);
				$adminTableObj->OutputList($showsList, $updateFailed);
			}
			
			if ($myDBaseObj->CanAddShow())
			{
				// FUNCTIONALITY: Shows - Output "Add New Show" Button (if valid)
				$this->OutputButton("addshowbutton", __("Add New Show", 'stageshow'));
			}
			
			if (count($showsList) > 0)
			{
				// FUNCTIONALITY: Shows - Output "Save Changes" Button (if there are entries)
				$this->OutputButton("savechanges", __("Save Changes", 'stageshow'), "button-primary");
			}
?>
	</form>
	</div>
<?php
		} // End of function Output_MainPage()
		
		function DoBulkPreAction($bulkAction, $recordId)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			// Reset error count etc. on first pass
			if (!isset($this->errorCount)) $this->errorCount = 0;
			if (!isset($this->blockCount)) $this->blockCount = 0;
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:
					// FUNCTIONALITY: Shows - Bulk Action Delete - Block if tickets sold
					// Don't delete if any tickets have been sold for this performance
					$delShowEntry = $myDBaseObj->GetShowsList($recordId);
					if (count($delShowEntry) == 0)
						$this->errorCount++;
					else if (!$myDBaseObj->CanDeleteShow($delShowEntry[0]))
						$this->blockCount++;
					return (($this->errorCount > 0) || ($this->blockCount > 0));
			}
			
			return false;
		}
		
		function DoBulkAction($bulkAction, $recordId)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:
					// FUNCTIONALITY: Shows - Bulk Action Delete - Remove Prices, Hosted Buttons, Performances and Show		
					// Get a list of performances
					$results = $myDBaseObj->GetPerformancesDetailsByShowID($recordId);
					
					foreach ($results as $result)
					{
						// Get ID of performance to delete
						$delperfId = $result->perfID;
						
						// Note: Prices are deleted by Database Cleanup - $myDBaseObj->DeletePriceByPerfID($delperfId);
						
						// Delete a performances entry (Marks entry as deleted)
						$myDBaseObj->DeletePerformanceByPerfID($delperfId);
					}
					
					// Now delete the entry in the SHOWS table
					$delShowName = $myDBaseObj->DeleteShowByShowID($recordId);
					return true;
				
				case StageShowLibAdminListClass::BULKACTION_TOGGLE:
					// FUNCTIONALITY: Shows - Bulk Action Activate/Deactivate Show		
					$actionCount = 0;
					$showEntry   = $myDBaseObj->GetShowsList($recordId);
					if ($myDBaseObj->IsStateActive($showEntry[0]->showState))
						$myDBaseObj->SetShowActivated($recordId, STAGESHOW_STATE_INACTIVE);
					else
						$myDBaseObj->SetShowActivated($recordId, STAGESHOW_STATE_ACTIVE);
					
					return true;
					
			}
			
			return false;
		}
		
		function GetBulkActionMsg($bulkAction, $actionCount)
		{
			$actionMsg = '';
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:
					// FUNCTIONALITY: Shows - Bulk Action Delete - Output Action Status Message
					if ($this->errorCount > 0)
						$actionMsg = $this->errorCount . ' ' . _n("Show does not exist in Database", "Shows do not exist in Database", $this->errorCount, 'stageshow');
					else if ($this->blockCount > 0)
						$actionMsg = $this->blockCount . ' ' . _n("Show cannot be deleted", "Shows cannot be deleted", $this->blockCount, 'stageshow').' - '.__("Tickets already sold!", 'stageshow');
					else if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Show has been deleted", "Shows have been deleted", $actionCount, 'stageshow');
					else
						$actionMsg = __("Nothing to Delete", 'stageshow');
					break;
					
				case StageShowLibAdminListClass::BULKACTION_TOGGLE:
					// FUNCTIONALITY: Shows - Bulk Action Delete - Output Action Status Message
					if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Show has been Activated/Deactivated", "Shows have been Activated/Deactivated", $actionCount, 'stageshow');
					else
						$actionMsg = __("Nothing to Activate/Deactivate", 'stageshow');
					break;
			}
			
			return $actionMsg;
		}
		
	}
}




