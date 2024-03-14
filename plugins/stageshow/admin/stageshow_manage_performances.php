<?php
/* 
Description: Code for Managing Performances Configuration

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

if (!class_exists('StageShowPerformancesAdminListClass'))
{
	class StageShowPerformancesAdminListClass extends StageShowLibSalesAdminListClass // Define class
	{
		const CHAR_INFINITY = '&#8734;';
		
		var $updateFailed;
		
		function __construct($env) //constructor
		{
			$this->hiddenRowsButtonId = 'TBD';
			
			$this->tableUsesSerializedPost = apply_filters('stageshow_filter_usesserializedpost', STAGESHOWLIB_DISABLE_POSTCONTROLS, 'Performances');
			
			// Call base constructor
			parent::__construct($env, true);
			
			$this->dateTimeMode = 'datetime';	// Don't display seconds
			
			$this->SetRowsPerPage(self::STAGESHOWLIB_EVENTS_UNPAGED);
			
			// FUNCTIONALITY: Performances - Bulk Actions - Activate/Deactivate and Delete
			$this->bulkActions = array(
				StageShowLibAdminListClass::BULKACTION_TOGGLE => __('Activate/Deactivate', 'stageshow'),
				StageShowLibAdminListClass::BULKACTION_DELETE => __('Delete', 'stageshow')
			);
			
			$updateFailed = false;
			
			$this->hiddenRowsButtonId = __('Options', 'stageshow');
		}
				
		
		
		function GetTableID($result)
		{
			return "showtab" . $result->showID;
		}
		
		function GetRecordID($result)
		{
			return $result->perfID;
		}
		
		function GetMainRowsDefinition()
		{
			// FUNCTIONALITY: Performances - Lists Performance Date & Time, Reference, Max Seats, Tickets Sold Count and Activation State
			$columnDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Date & Time',  StageShowLibTableClass::TABLEPARAM_ID => 'perfDateTime', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibAdminListClass::TABLEENTRY_DATETIME,  StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatDateForAdminDisplay', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Reference',    StageShowLibTableClass::TABLEPARAM_ID => 'perfRef',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,  StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_PERFREF_TEXTLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Max Seats',    StageShowLibTableClass::TABLEPARAM_ID => 'perfSeats',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_INTEGER,  StageShowLibTableClass::TABLEPARAM_DECODE =>'GetPerfMaxSeats',  StageShowLibTableClass::TABLEPARAM_LEN => 4, ),						
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Tickets Sold', StageShowLibTableClass::TABLEPARAM_ID => 'soldQty',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_LINK =>'admin.php?page='.STAGESHOW_MENUPAGE_SALES.'&action=perf&id=', ),						
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'State',        StageShowLibTableClass::TABLEPARAM_ID => 'perfState',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE, StageShowLibTableClass::TABLEPARAM_DECODE =>'GetPerfState'),						
			);

			$ourOptions = $columnDefs;
			
			$seatingList = $this->myDBaseObj->GetSeatingList();
			$seatingSelect[] = '0|(none)';				
			foreach ($seatingList as $seatingSpec)
			{
				$seatingSelect[] = $seatingSpec->seatingID.'|'.$seatingSpec->seatingRef;				
			}
			
			$paramType = StageShowLibTableClass::TABLEENTRY_SELECT;
			
			$ourOptions = self::MergeSettings($ourOptions, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Seating',    StageShowLibTableClass::TABLEPARAM_ID => 'perfSeatingID',    StageShowLibTableClass::TABLEPARAM_TYPE => $paramType,   StageShowLibTableClass::TABLEPARAM_ITEMS => $seatingSelect, StageShowLibTableClass::TABLEPARAM_CANEDIT => 'CanEditSeatingID', StageShowLibTableClass::TABLEPARAM_ONCHANGE => 'stageshow_OnClickSeatingID', StageShowLibTableClass::TABLEPARAM_AFTER => 'perfRef', ),						
			));				
			
			return $ourOptions;
		}
		
		
		function GetPerfMaxSeats($perfSeats)
		{
			// FUNCTIONALITY: Performances - Negative Max Seats shown as infinity
			if ($perfSeats < 0)
				$perfSeats = self::CHAR_INFINITY;
			return $perfSeats;
		}
		
		function GetPerfState($perfState)
		{
			// FUNCTIONALITY: Performances - Activation State shown as "Active" or "INACTIVE"
			switch ($perfState)
			{
				case STAGESHOW_STATE_ACTIVE:
				default:
					$perfStateText =__("Active", 'stageshow');
					break;
					
				case STAGESHOW_STATE_INACTIVE:
					$perfStateText =__("INACTIVE", 'stageshow');
					break;
					
				case STAGESHOW_STATE_DELETED:
					$perfStateText ='('.__("Deleted", 'stageshow').')';
					break;
					
			}
			
			return $perfStateText;
		}
		
		function OutputList($results, $updateFailed = false)
		{
			// FUNCTIONALITY: Performances - Reset Shows form on update failure
			$this->updateFailed = $updateFailed;
			parent::OutputList($results, $updateFailed);
		}
		
		function ShowExpiresTime($value, $result)
		{
			$myDBaseObj = $this->myDBaseObj;
			if (is_null($value) || ($value === '') || ($value === "0000-00-00 00:00:00"))
			{
				$dateTime = $myDBaseObj->DefaultExpiresTime($result->perfDateTime);
			}
			else
			{
				$dateTime =  $myDBaseObj->FormatDateForAdminDisplay($value);
			}
			
			return $dateTime;
		}
		
		function GetDetailsRowsDefinition()
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$aboveEntryText = 'above|'.__('Above Entry', 'stageshow');
			$belowEntryText = 'below|'.__('Below Entry', 'stageshow');
			
			// FUNCTIONALITY: Performances = Adds Expires Time, Note Position and Text
			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Expires',         StageShowLibTableClass::TABLEPARAM_ID => 'perfExpires',  StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_DATETIME, StageShowLibTableClass::TABLEPARAM_DECODE =>'ShowExpiresTime', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Note Position',   StageShowLibTableClass::TABLEPARAM_ID => 'perfNotePosn', StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_ITEMS => array($aboveEntryText, $belowEntryText), ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Note',            StageShowLibTableClass::TABLEPARAM_ID => 'perfNote',     StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_TEXTBOX,  StageShowLibTableClass::TABLEPARAM_ROWS  => 4, StageShowLibTableClass::TABLEPARAM_COLS => 60, StageShowLibTableClass::TABLEPARAM_ALLOWHTML => true, ),
			);
			
			$ourOptions = self::MergeSettings(parent::GetDetailsRowsDefinition(), $ourOptions);
			
			$ourOptions = self::MergeSettings(array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Seats Lockout', StageShowLibTableClass::TABLEPARAM_ID => 'perfSeatingID',   StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_FUNCTION, StageShowLibTableClass::TABLEPARAM_FUNC => 'EditSeatLockoutHTML', StageShowLibTableClass::TABLEPARAM_DECODE => 'ValidSeatingID', StageShowLibTableClass::TABLEPARAM_BLOCKBLANK => true, StageShowLibTableClass::TABLEPARAM_BEFORE => 'priceValue', ),
				), $ourOptions
			);

			// FUNCTIONALITY: Performances Options 
			if ($myDBaseObj->IsPrintingActive())
			{
				$templatePath = STAGESHOW_UPLOADS_PATH.'/tickets/';
				$ourOptions = self::MergeSettings($ourOptions, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Template', StageShowLibTableClass::TABLEPARAM_ID => 'perfTicketTemplate',   StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_DIR => $templatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'xml', StageShowLibTableClass::TABLEPARAM_HIDEEXTNS => true, StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true, /* StageShowLibTableClass::TABLEPARAM_AFTER => 'PostTicketsEnabled', */),
					)
				);
			}
			
			// FUNCTIONALITY: Performances Options 
			$templatePath = STAGESHOW_UPLOADS_PATH.'/forms/';
			$ourOptions = self::MergeSettings($ourOptions, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Details Template', StageShowLibTableClass::TABLEPARAM_ID => 'perfDetailsTemplate',   StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_DIR => $templatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'php', StageShowLibTableClass::TABLEPARAM_HIDEEXTNS => true, StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true, ),
				)
			);
			
			return $ourOptions;
		}
				
		
		function ExtendedSettingsDBOpts()
		{
			$dbOpts['Table'] = STAGESHOW_PERFORMANCES_TABLE;
			$dbOpts['Index'] = 'perfID';
			
			return $dbOpts;
		}
		
		// Commented out Class Def (StageShowPerformancesAdminListClass)
		function CanEditSeatingID($result)
		{
			// Seating is readonly when prices have been defined
			$canEdit  = ($result->pricesCount == 0);
			$canEdit |= $this->myDBaseObj->isDbgOptionSet('Dev_ExpertMode');			
			return $canEdit;
		}

		function ValidSeatingID($perfSeatingID)
		{
			if ($perfSeatingID == 0) return '';
			
			return $perfSeatingID;
		}
		
		function EditSeatLockoutHTML($result)
		{
			if ($result->perfSeatingID == 0) return '';
			
			$myDBaseObj = $this->myDBaseObj;
			
			$lockoutCount = $myDBaseObj->GetSaleLockoutCount($this->GetRecordID($result));
			$lockoutButtonHTML = '';
			if ($result->pricesCount > 0)
			{
				$buttonText = __('Edit', 'stageshow');
				$lockoutButtonHTML =  ' - '.$myDBaseObj->ActionButtonHTML($buttonText, $this->caller, 'stageshow', '', $this->GetRecordID($result), 'editlockout'); 
			}
			$buttonText = __('View Sales', 'stageshow');
			$lockoutButtonHTML .=  '&nbsp;&nbsp;'.$myDBaseObj->ActionButtonHTML($buttonText, $this->caller, 'stageshow', '', $this->GetRecordID($result), 'viewsales'); 

			$seatLockoutLabel = __('Seats Locked Out', 'stageshow');
			$seatLockoutHTML = "$lockoutCount $seatLockoutLabel$lockoutButtonHTML";
			return $seatLockoutHTML;
		}
	}
}

if (!class_exists('StageShowPerformancesAdminClass'))
{
	class StageShowPerformancesAdminClass extends StageShowLibAdminClass // Define class
	{
		var $filterRowDefs = array();
		var $defaultFilterId = '';
		var $filterSQL = '';
		
		function __construct($env) //constructor	
		{
			$this->pageTitle = 'Performances';
			
			// Call base constructor
			parent::__construct($env);
		}
		
		function ValidateEditPerformances($result)
		{
			// FUNCTIONALITY: Performances - Check that Seating Plan can be changed
			$perfSeatingID = StageShowLibUtilsClass::GetHTTPInteger('post', 'perfSeatingID'.$result->perfID);
			if ( ($perfSeatingID != $result->perfSeatingID) && ($result->pricesCount > 0) 
			  && !$this->myDBaseObj->isDbgOptionSet('Dev_ExpertMode') )
			{
				return __("Cannot Change Seating Plan - Prices already defined", 'stageshow') . ' (' . "TBD" . ')';
			}						
			
			// FUNCTIONALITY: Performances - Validate Performance Expiry Date/Time
			$perfExpires = StageShowLibUtilsClass::GetHTTPTextElem('post', 'perfExpires'.$result->perfID);
			
			// Verify that the date value is not empty
			if (StageShowLibMigratePHPClass::Safe_strlen($perfExpires) == 0)
			{
				return __('Empty Expiry Date Entry', 'stageshow');
			}
			// Verify that the date value is valid
			if (StageShowLibMigratePHPClass::Safe_strtotime($perfExpires) == FALSE)
			{
				return __('Invalid Expiry Date Entry', 'stageshow') . ' (' . $perfExpires . ')';
			}
			
			// Keep default Expiry Date/Time if Perf Date/Time changes
			$currperfDateTime = StageShowLibUtilsClass::GetHTTPDateTime('post', 'currperfDateTime'.$result->perfID);
			$perfDateTime = StageShowLibUtilsClass::GetHTTPDateTime('post', 'perfDateTime'.$result->perfID);			
			if ($currperfDateTime != $perfDateTime) 
			{
				// Performance date has been edited ....
				$currperfExpires = StageShowLibUtilsClass::GetHTTPDateTime('post', 'currperfExpires'.$result->perfID);
				$perfExpires = StageShowLibUtilsClass::GetHTTPDateTime('post', 'perfExpires'.$result->perfID);			
				if ($currperfExpires == $perfExpires)
				{
					// Expiry Date/Time unchanged
					$defaultExpiryDateTime = $this->myDBaseObj->DefaultExpiresTime($currperfDateTime);
					if ($currperfExpires == $defaultExpiryDateTime)
					{
						// Expiry Date/Time was default
						$defaultExpiryDateTime = $this->myDBaseObj->DefaultExpiresTime($perfDateTime);
						$_POST['perfExpires' . $result->perfID] = $defaultExpiryDateTime;
					}
				}
			}
			
			// FUNCTIONALITY: Performances - Verify that the date value is valid
			if (StageShowLibMigratePHPClass::Safe_strlen($perfDateTime) == 0)
			{
				return __('Blank Date Entry', 'stageshow') . ' (' . $perfDateTime . ')';
			}
			if (StageShowLibMigratePHPClass::Safe_strtotime($perfDateTime) == FALSE)
			{
				return __('Invalid Date Entry', 'stageshow') . ' (' . $perfDateTime . ')';
			}
						
			// FUNCTIONALITY: Performances - Reject Duplicate Performance Date & Time
			if (isset($this->datesList[$perfDateTime]))
			{
				return __('Duplicated Performance Date', 'stageshow') . ' (' . $perfDateTime . ')';
			}
			$this->datesList[$perfDateTime] = true;
						
			$perfRef = StageShowLibUtilsClass::GetHTTPTextElem('post', 'perfRef'.$result->perfID);
			if ( ($perfRef != $result->perfRef) && !$this->myDBaseObj->IsPerfRefUnique($perfRef, $result->showID) )
			{
				return __('Duplicated Performance Reference', 'stageshow') . ' (' . $perfRef . ')';
			}
						
			// FUNCTIONALITY: Performances - Validate Performance Date/Time
			// Verify that the date value is not empty
			if (StageShowLibMigratePHPClass::Safe_strlen($perfDateTime) == 0)
			{
				return __('Empty Date Entry', 'stageshow');
			}
						
			return '';
		}
		
		function SavePerformance(&$result)
		{
			$perfUpdated = false;
			$myDBaseObj = $this->myDBaseObj;
			
			$newPerfDateTime = StageShowLibUtilsClass::GetHTTPDateTime('post', 'perfDateTime'.$result->perfID);

			// Reformat date & time to correct for for MySQL
			$reformattedPerfDateTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, StageShowLibMigratePHPClass::Safe_strtotime($newPerfDateTime));
			//if ($newPerfDateTime !== $reformattedPerfDateTime)
			//	StageShowLibEscapingClass::Safe_EchoHTML("Reformatted $newPerfDateTime to $reformattedPerfDateTime <br>\n");
			$newPerfDateTime         = $reformattedPerfDateTime;

			// FUNCTIONALITY: Performances - Save Performance Date/Time, Ref and Max Seats
			if ($newPerfDateTime != $result->perfDateTime)
			{
				$myDBaseObj->UpdatePerformanceTime($result->perfID, $newPerfDateTime);
				$result->perfDateTime = $newPerfDateTime;
				$perfUpdated = true;
			}

			$newPerfRef = StageShowLibUtilsClass::GetHTTPTextElem('post', 'perfRef'.$result->perfID);
			if ($newPerfRef != $result->perfRef)
			{
				$myDBaseObj->UpdatePerformanceRef($result->perfID, $newPerfRef, $result->showID);
				$result->perfRef = $newPerfRef;
				$perfUpdated = true;
			}

			$newPerfSeats = StageShowLibUtilsClass::GetHTTPTextElem('post', 'perfSeats'.$result->perfID);
			if (!is_numeric($newPerfSeats) || ($newPerfSeats < 0))
				$newPerfSeats = -1;
			if ($newPerfSeats != $result->perfSeats)
			{
				$myDBaseObj->UpdatePerformanceSeats($result->perfID, $newPerfSeats);
				$result->perfSeats = $newPerfSeats;
				$perfUpdated = true;
			}
			
			$newPerfSeatingID = StageShowLibUtilsClass::GetHTTPInteger('post', 'perfSeatingID'.$result->perfID);
			if ($newPerfSeatingID != $result->perfSeatingID)
			{
				$myDBaseObj->UpdateSeatingLayout($result->perfID, $newPerfSeatingID);
				$result->perfSeatingID = $newPerfSeatingID;
				$perfUpdated = true;
			}

			if ($perfUpdated)
			{
				// Clear Ticket Print Spec Object (recreated "on the fly" next time ticket is printed)
				$myDBaseObj->ClearTicketSpecByPerfID($result->perfID);
			}
			
			return $perfUpdated;
		}	
		
		function ProcessActionButtons()
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj  = $this->myDBaseObj;
			
			$delperfId = 0;
			
			$perfsMsg = '';
			
			// FUNCTIONALITY: Performances - Save Changes
			if (StageShowLibUtilsClass::IsElementSet('post', 'savechanges'))
			{
				$this->CheckAdminReferer();
				
				// Save Settings Request ....
				$showID = StageShowLibUtilsClass::GetHTTPInteger('post', 'showID');  
				$results = $myDBaseObj->GetPerformancesDetailsByShowID($showID);
				
				// Verify that performance Refs are unique 
				
				if (count($results) > 0)
				{
					foreach ($results as $result)
					{
						$perfsMsg = $this->ValidateEditPerformances($result);
						if ($perfsMsg != '')
							break;												
					}
				}
				
				if ($perfsMsg !== '')
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>' . __('Settings have NOT been saved', 'stageshow') . '. ' . $perfsMsg . '</p></div>');
				}
				else
				{
					if (count($results) > 0)
					{
						$adminTableObj = $this->CreateAdminListObj($this->env);
						
						// Get the extended settings array
						$settings = $adminTableObj->GetDetailsRowsDefinition();
						$dbOpts   = $adminTableObj->ExtendedSettingsDBOpts();
						
						foreach ($results as $result)
						{
							$perfUpdated = $this->SavePerformance($result);
							
							// Save option extensions
							$this->UpdateHiddenRowValues($result, $result->perfID, $settings, $dbOpts);
						} // End of foreach($results as $result)
					}
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Settings have been saved', 'stageshow') . '</p></div>');
				}
			}
			else if (StageShowLibUtilsClass::IsElementSet('post', 'addperfbutton') && StageShowLibUtilsClass::IsElementSet('post', 'showID'))
			{
				// FUNCTIONALITY: Performances - Add Performance 
				$this->CheckAdminReferer();
				
				$showID = StageShowLibUtilsClass::GetHTTPInteger('post', 'showID');  
				
				$statusMsg = '';
				$newPerfID = $myDBaseObj->CreateNewPerformance($statusMsg, $showID);
				
				$statusMsgClass = ($newPerfID > 0) ? 'updated' : 'error';
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="' . $statusMsgClass . '"><p>' . $statusMsg . '</p></div>');
			}
		}
		
		function OutputButton($buttonId, $buttonText, $buttonClass = "button-secondary", $clickEvent = '')
		{
			parent::OutputButton($buttonId, $buttonText, $buttonClass, $clickEvent);
			
			switch ($buttonId)
			{
				case "addperfbutton":
					// FUNCTIONALITY: Performances = Add "Price Plan" select to new Performance button
					StageShowLibEscapingClass::Safe_EchoHTML("<!-- Price Plan Select -->\n");
					$this->OutputPricePlanSelect('&nbsp; '.__('initialised as', 'stageshow').' &nbsp;');
					break;
			}
		}
		
		function Output_MainPage($updateFailed)
		{
			$myDBaseObj = $this->myDBaseObj;

			if (StageShowLibUtilsClass::IsElementSet('get', 'action')) 
			{
				switch ($_GET['action'])
				{
					case 'editlockout':
						$perfID = StageShowLibUtilsClass::GetHTTPInteger('get', 'id');  
						if (StageShowLibUtilsClass::IsElementSet('post', 'stageshow-seatselected-seats'))
						{
							$lockedOutSeats = StageShowLibUtilsClass::GetHTTPTextElem('post', 'stageshow-seatselected-seats');  
							$lockedOutZones = StageShowLibUtilsClass::GetHTTPTextElem('post', 'stageshow-seatselected-zones');  
							$myDBaseObj->SetSeatLockoutByPerfID($perfID, $lockedOutSeats, $lockedOutZones);
							break;
						}

						$pluginObj = $this->env['PluginObj'];
						StageShowLibEscapingClass::Safe_EchoHTML('<form id=trolley method="post">');
						$pluginObj->OutputContent_SelectSeats($perfID, StageShowCartPluginClass::PAGEMODE_LOCKOUTSEATS);
						StageShowLibEscapingClass::Safe_EchoHTML('</form>');
						return;
						
					case 'viewsales':
						$perfID = StageShowLibUtilsClass::GetHTTPInteger('get', 'id');  
						$pluginObj = $this->env['PluginObj'];
						StageShowLibEscapingClass::Safe_EchoHTML('<form method="post" action="admin.php?page='.STAGESHOW_MENUPAGE_PERFORMANCES.'">');
						$pluginObj->OutputContent_SelectSeats($perfID, StageShowCartPluginClass::PAGEMODE_VIEWSALES);
						StageShowLibEscapingClass::Safe_EchoHTML('</form>');
						return;
				}
			}
					
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_ALL,     __('All', 'stageshow'),     __('All', 'stageshow'));
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_ACTIVE,  __('Active', 'stageshow'),  __('Active', 'stageshow'));						
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_CURRENT, __('Current', 'stageshow'), __('Current', 'stageshow'));						
			$this->defaultFilterId = STAGESHOWLIB_ADMINFILTER_CURRENT;
			
			$this->GetFilterCounts($this->filterRowDefs);
			
			// Calculate and output filter links - Returns the row count for the selected filter
			StageShowLibEscapingClass::Safe_EchoHTML("<div>\n");
			$filterObj = StageShowLibAdminListClass::OutputFilterLinks($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_CURRENT);
			StageShowLibEscapingClass::Safe_EchoHTML("</div>\n");
				
			$this->filterSQL = $this->GetShowsFilterSQL($filterObj->rowFilter);
				
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj  = $this->myDBaseObj;

			// Stage Show Performances HTML Output - Start 
			$showsList = $myDBaseObj->GetSortedShowsList($this->filterSQL);

			$isConfigured = $myDBaseObj->CheckIsConfigured();
			
			if (count($showsList) == 0)
			{
				// FUNCTIONALITY: Performances - Show Link to Settings page if Payment Gateway settings required
				if ($isConfigured)
				{
					$showsPageURL = get_option('siteurl') . '/wp-admin/admin.php?page=' . STAGESHOW_MENUPAGE_SHOWS;
					StageShowLibEscapingClass::Safe_EchoHTML("<div class='error'><p>" . __('No Show Configured', 'stageshow') . ' - <a href=' . $showsPageURL . '>' . __('Add one Here', 'stageshow') . '</a>' . "</p></div>\n");
				}
			}
			
			// HTML special chars are converted by wp_kses .... hard code script here ...
?>
<script>
StageShowLib_addWindowsLoadHandler(stageshow_OnLoadPerformances); 

</script>
<?php
						
			foreach ($showsList as $showEntry)
			{
				$results = $myDBaseObj->GetPerformancesDetailsByShowID($showEntry->showID);
?>
	<div class="stageshow-admin-form">
	<form method="post">
	<h3><?php StageShowLibEscapingClass::Safe_EchoHTML(($showEntry->showName)); ?></h3>
<?php
				foreach ($results as $result)
				{
					StageShowLibEscapingClass::Safe_EchoScript('
<script>
PerfIDList.push('.$result->perfID.');
</script>
						');
				}
				$this->WPNonceField();
				if (count($results) == 0)
				{
					StageShowLibEscapingClass::Safe_EchoHTML(__('Show has No Performances', 'stageshow') . "<br>\n");
				}
				else
				{
					$thisUpdateFailed = (($updateFailed) && ($showEntry->showID == $_POST['showID']));
					$adminTableObj    = $this->CreateAdminListObj($this->env);
					$adminTableObj->OutputList($results, $thisUpdateFailed);
				} // End of if (count($results) == 0) ... else ...
				
?>
      <input type="hidden" name="showID" value="<?php StageShowLibEscapingClass::Safe_EchoHTML($showEntry->showID); ?>"/>
<?php
				if ($myDBaseObj->CanAddPerformance())
				{
					// FUNCTIONALITY: Performances - Output "Add New Show" Button (if valid)
					$this->OutputPostButton("addperfbutton", __("Add New Performance", 'stageshow'));
				}
				
				if (count($results) > 0)
				{
					// FUNCTIONALITY: Performances - Output "Save Changes" Button (if there are entries)
					$this->OutputPostButton("savechanges", __("Save Changes", 'stageshow'), "button-primary");					
				}
?>
</form>
</div>		
<?php
			} // End of foreach ($showsList as $showEntry) ..

			// Stage Show Performances HTML Output - End 
		}
		
		
		
		function DoBulkPreAction($bulkAction, $recordId)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			if (!isset($this->errorCount))
				$this->errorCount = 0;
			if (!isset($this->blockCount))
				$this->blockCount = 0;
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:
					// FUNCTIONALITY: Performances - Bulk Action Delete - Block if tickets sold
					// Don't delete if any tickets have been sold for this performance
					$delPerfEntry = $myDBaseObj->GetPerformancesListByPerfID($recordId);
					if (count($delPerfEntry) == 0)
						$this->errorCount++;
					else if (!$myDBaseObj->CanDeletePerformance($delPerfEntry[0]))
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
					// FUNCTIONALITY: Performances - Bulk Action Delete - Remove Prices, Hosted Buttons and Performance
					// Note: Prices are deleted by Database Cleanup - $myDBaseObj->DeletePriceByPerfID($recordId);
					
					// Delete a performance entry
					$myDBaseObj->DeletePerformanceByPerfID($recordId);
					return true;
				
				case StageShowLibAdminListClass::BULKACTION_TOGGLE:
					// FUNCTIONALITY: Performances - Bulk Action Activate/Deactivate
					$perfEntry = $myDBaseObj->GetPerformancesListByPerfID($recordId);
					if ($myDBaseObj->IsStateActive($perfEntry[0]->perfState))
						$myDBaseObj->SetPerfActivated($recordId, STAGESHOW_STATE_INACTIVE);
					else
						$myDBaseObj->SetPerfActivated($recordId, STAGESHOW_STATE_ACTIVE);
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
					// FUNCTIONALITY: Performances - Bulk Action Delete - Output Action Status Message
					if ($this->errorCount > 0)
						$actionMsg = $this->errorCount . ' ' . _n("Performance does not exist in Database", "Performances do not exist in Database", $this->errorCount, 'stageshow');
					else if ($this->blockCount > 0)
						$actionMsg = $this->blockCount . ' ' . _n("Performance cannot be deleted", "Performances cannot be deleted", $this->blockCount, 'stageshow').' - '.__("Tickets already sold!", 'stageshow');
					else if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Performance has been deleted", "Performances have been deleted", $actionCount, 'stageshow');
					else
						$actionMsg = __("Nothing to Delete", 'stageshow');
					break;
				
				case StageShowLibAdminListClass::BULKACTION_TOGGLE:
					// FUNCTIONALITY: Performances - Bulk Action Activate/Deactivate - Output Action Status Message
					if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Performance has been Activated/Deactivated", "Performances have been Activated/Deactivated", $actionCount, 'stageshow');
					else
						$actionMsg = __("Nothing to Activate/Deactivate", 'stageshow');
					break;
			}
			
			return $actionMsg;
		}
		
		function GetShowsFilterSQL($rowFilter)
		{	
			$filterSQL = '';
			switch ($rowFilter)
			{
				case STAGESHOWLIB_ADMINFILTER_ACTIVE:
					$filterSQL .= "(showState = '".STAGESHOW_STATE_ACTIVE."') AND ";
					// Fall through to next case
					
				case STAGESHOWLIB_ADMINFILTER_CURRENT:
					$currTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT);
					$filterSQL  = "((maxPerfDateTime >= '".$currTime."') || (maxPerfDateTime IS NULL) || (noOfPrices = 0))";
					break;
					
				default:
					$filterSQL = 'TRUE';
					break;
			}

			return $filterSQL;
		}

		function GetFilterCounts(&$filterRowDefs)
		{
			// Loop through all entries to get SQL for row counts
			$sqlSelect = '';
			foreach ($filterRowDefs as $filterId => $filterRowDef)
			{
				$filterSQL = $this->GetShowsFilterSQL($filterId);
				if ($sqlSelect != '') $sqlSelect .= ', ';
				$sqlSelect .= "COUNT(IF($filterSQL, 1, NULL)) AS count$filterId";
			}

			if ($sqlSelect == '') return null;

			$filtersCounts = $this->myDBaseObj->GetShowsCount($sqlSelect);
							
			// Loop through all entries to get row counts for each filter
			foreach ($filterRowDefs as $filterId => $filterRowDef)
			{			
				$filterIndex = "count$filterId";				
				$filterRowDefs[$filterId]->count = $filtersCounts[0]->$filterIndex;
			}			
		}

		function OutputPricePlanSelect($label = '')
		{
			$myDBaseObj  = $this->myDBaseObj;

			StageShowLibEscapingClass::Safe_EchoHTML($label);
			
			$pricePlansList = $myDBaseObj->GetAllPlansList();
			
			$selectedPlan = StageShowLibUtilsClass::GetHTTPInteger('post', 'pricePlan', 0);  
			$selected = ($selectedPlan == 0) ? 'selected="selected"': '';
			
			StageShowLibEscapingClass::Safe_EchoHTML('
			<select name="pricePlan">
			<option value="0" '.$selected.'>'.__('No Price Plan', 'stageshow').'&nbsp;&nbsp;</option>
			');
			foreach ($pricePlansList as $pricePlan)
			{
				$selected = ($selectedPlan == $pricePlan->planID) ? 'selected="selected"': '';
				$planPlanRef = $pricePlan->planRef.'&nbsp;&nbsp;';
				$planID = $pricePlan->planID;
				StageShowLibEscapingClass::Safe_EchoHTML("<option value=\"$planID\" $selected>$planPlanRef</option>\n");
			}
			StageShowLibEscapingClass::Safe_EchoHTML('
			</select>
			');
		}
		
		// Commented out Class Def (StageShowPerformancesAdminClass)

	}
}











