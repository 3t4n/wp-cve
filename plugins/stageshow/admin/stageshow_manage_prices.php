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

include STAGESHOW_INCLUDE_PATH.'stageshowlib_salesadmin.php';

include STAGESHOW_INCLUDE_PATH . 'stageshowlib_admin.php';

if (!class_exists('StageShowPricesAdminListClass'))
{
	class StageShowPricesAdminListClass extends StageShowLibSalesAdminListClass // Define class
	{
		var $updateFailed;
		
		function __construct($env) //constructor
		{
			$this->tableUsesSerializedPost = apply_filters('stageshow_filter_usesserializedpost', STAGESHOWLIB_DISABLE_POSTCONTROLS, 'Prices');
			
			// Call base constructor
			parent::__construct($env, true);
			
			$this->SetRowsPerPage(self::STAGESHOWLIB_EVENTS_UNPAGED);
			
			$this->bulkActions = array(
				StageShowLibAdminListClass::BULKACTION_DELETE => __('Delete', 'stageshow'),
			);
		}
		
		function GetTableID($result)
		{
			return "showtab" . $result->showID;
		}
		
		function GetRecordID($result)
		{
			return $result->priceID;
		}
		
		function DecodePrice($value, $result)
		{
			if ($value == STAGESHOW_PRICE_UNKNOWN)
			{
				return '';
			}
			
			return $value;
		}
		
		function GetMainRowsDefinition()
		{
			// FUNCTIONALITY: Prices - Lists Performance, Type and Price
			$visibilityOptions = array( 
				STAGESHOW_VISIBILITY_PUBLIC.'|'.__('Public', 'stageshow'), 
				STAGESHOW_VISIBILITY_ADMIN.'|'.__('Admin Only', 'stageshow'), 
				STAGESHOW_VISIBILITY_DISABLED.'|'.__('Disabled', 'stageshow'), 
				);
			
			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Visibility',  StageShowLibTableClass::TABLEPARAM_ID => 'priceVisibility', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $visibilityOptions, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'No Of Seats', StageShowLibTableClass::TABLEPARAM_ID => 'priceNoOfSeats',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_FUNC => 'GetCountSelector', ),
			);
			
			
			if ( ($this->myDBaseObj->getOption('ReservationsMode') != STAGESHOW_RESERVATIONSMODE_DISABLED)
			  && ($this->myDBaseObj->isOptionSet('CheckoutModesEnabled')) )			
			{
				$checkoutModes = array(
					STAGESHOW_CARTBUTTONS_NORMAL.'|'.__('Normal', 'stageshow'),
					STAGESHOW_CARTBUTTONS_CHECKOUTONLY.'|'.__('Checkout only', 'stageshow'),
					STAGESHOW_CARTBUTTONS_RESERVEONLY.'|'.__('Reserve only', 'stageshow'),
				);

				$ourOptions = self::MergeSettings($ourOptions, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Mode',  StageShowLibTableClass::TABLEPARAM_ID => 'priceCheckoutMode', StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_ITEMS => $checkoutModes, ),
					)
				);
			}
			
			// FUNCTIONALITY: Prices - Lists Performance, Type and Price
			$columnDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Performance',  StageShowLibTableClass::TABLEPARAM_ID => 'perfDateTime', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW,   StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatDateForAdminDisplay', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Type',  StageShowLibTableClass::TABLEPARAM_ID => 'priceType',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_PRICETYPE_TEXTLEN, ),						
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Price',        StageShowLibTableClass::TABLEPARAM_ID => 'priceValue',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => 9, StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodePrice'),
			);
				
			$ourOptions = self::MergeSettings($ourOptions, $columnDefs);
			

			$ourOptions = $this->MergeSettings(array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Zone',        StageShowLibTableClass::TABLEPARAM_ID => 'zoneRef',         StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_AFTER => 'priceType', StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodeZone', ),
			), $ourOptions);				
			
			$disList = $this->myDBaseObj->GetDiscountsList();
			if (count($disList) > 0)
			{
				// Find the existing price entry ... and replace it
				foreach($ourOptions as $optionIndex => $thisOption)
				{
					if ($thisOption['Id'] == 'priceValue')
					{
						$ourOptions[$optionIndex] = 
							array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Prices',       StageShowLibTableClass::TABLEPARAM_ID => 'priceID',            StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_FUNCTION, StageShowLibTableClass::TABLEPARAM_FUNC => 'PriceHTMLWithDiscounts', StageShowLibTableClass::TABLEPARAM_AFTER => 'priceValue', );

						break;
					}
				}				
			}
			
			return $ourOptions;
		}
		
	
		
		function OutputList($results, $updateFailed = false)
		{
			$this->updateFailed = $updateFailed;
			parent::OutputList($results, $updateFailed);
		}

		function GetCountSelector($result)
		{
			$maxTicketSeats = STAGESHOW_MAX_TICKETSEATS;
			
			for ($i=1; $i<=$maxTicketSeats; $i++)
			{
				$countSelector[] = $i.'|'.$i;
			}

			return $countSelector;
		}
		
		// Commented out Class Def (StageShowPricesAdminListClass)
		function DecodeZone($value, $result)
		{
			if (StageShowLibMigratePHPClass::Safe_strlen($value) == 0)
			{
				return __('N/A', 'stageshow');
			}
			
			return $value;
		}
		
		function PriceHTMLWithDiscounts($result)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$allPricesOutput = '<table>';
			$priceId = 'priceValue'.$result->priceID;
			$htmlRow = $this->GetInputHTML($priceId, 9, $this->DecodePrice($result->priceValue, null));
			$allPricesOutput .= "<tr><td>&nbsp;</td><td>$htmlRow</td></tr>";
			
			$disList = $myDBaseObj->GetDiscountsList();
			foreach ($disList as $disEntry)
			{
				$priceId = 'disPrice'.$result->priceID.'_'.$disEntry->disID;
				$priceRef = $disEntry->disRef;
				
				$priceValue = $myDBaseObj->GetDiscountPrice($result->priceID, $disEntry->disID);
				
				$htmlRow = $this->GetInputHTML($priceId, 9, $this->DecodePrice($priceValue, null));
				$allPricesOutput .= "<tr><td>$priceRef</td><td>$htmlRow</td></tr>";
			}
			$allPricesOutput .= '</table>';
			
			return $allPricesOutput;
		}
		
	}
}

if (!class_exists('StageShowPricesAdminClass'))
{
	class StageShowPricesAdminClass extends StageShowLibAdminClass // Define class
	{
		var $filterRowDefs = array();
		var $defaultFilterId = '';
		var $filterSQL = '';
		
		function __construct($env) //constructor	
		{
			$this->pageTitle = 'Prices';
			
			// Call base constructor
			parent::__construct($env);
		}
		
		function SavePriceEntry($result)
		{
			$myDBaseObj  = $this->myDBaseObj;
			$pricesUpdated = false;
							
			// FUNCTIONALITY: Prices - Save Price Ref and Price
			$newPriceType = StageShowLibUtilsClass::GetHTTPTextElem('post', 'priceType'.$result->priceID);
			if ($newPriceType != $result->priceType)
			{
				$myDBaseObj->UpdatePriceType($result->priceID, $newPriceType);
				$result->priceType = $newPriceType;
				$pricesUpdated     = true;
			}
							
			$newPriceValue = StageShowLibUtilsClass::GetHTTPTextElem('post', 'priceValue'.$result->priceID);
			if ($newPriceValue != $result->priceValue)
			{
				$myDBaseObj->UpdatePriceValue($result->priceID, $newPriceValue);
				$result->priceValue = $newPriceValue;
				$pricesUpdated      = true;
			}
							
			$priceFields = array('priceVisibility', 'priceNoOfSeats');
			if ( ($myDBaseObj->getOption('ReservationsMode') != STAGESHOW_RESERVATIONSMODE_DISABLED)
			  && ($myDBaseObj->isOptionSet('CheckoutModesEnabled')) )	
			{
				$priceFields[] = 'priceCheckoutMode';
			}		

			foreach ($priceFields as $priceField)
			{
				$newPriceValue = StageShowLibUtilsClass::GetHTTPTextElem('post', $priceField . $result->priceID);
				if ($newPriceValue != $result->$priceField)
				{
					// Update Preset Ref
					$myDBaseObj->UpdatePrice($result->priceID, $priceField, $newPriceValue);
					$planUpdated = true;
				}
			}
			
			$disList = $myDBaseObj->GetDiscountsList();
			
			foreach ($disList as $disEntry)
			{
				$priceId = 'disPrice'.$result->priceID.'_'.$disEntry->disID;
				$disValue = StageShowLibUtilsClass::IsElementSet('post', $priceId) ? StageShowLibMigratePHPClass::Safe_trim(StageShowLibUtilsClass::GetHTTPNumber('post', $priceId)) : '';
				if (($disValue != '') && ($disValue != 0))
				{
					$myDBaseObj->SaveDiscountPrice($disEntry->disID, $result->priceID, $disValue);
				}
				else
				{
					$myDBaseObj->DeleteDiscountPrice($disEntry->disID, $result->priceID);
				}
			}

			return $pricesUpdated;
		}
		
		
		function GetNewPriceReference($result)
		{
			$newPerfID     = $result->perfID;
			$newPriceType  = StageShowLibUtilsClass::GetHTTPTextElem('post', 'priceType'.$result->priceID);
			
			$priceEntry = $newPerfID . '-' . $newPriceType;
			
			if ($result->zoneID != 0)
			{
				$newPriceZoneRef  = StageShowLibUtilsClass::GetHTTPTextElem('post', 'zoneRef'.$result->priceID);
				$priceEntry .= '-'.$newPriceZoneRef;
			}
			
			return $priceEntry;
		}
	
		
		function ProcessActionButtons()
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj  = $this->myDBaseObj;
			
			$showID = 0;
			
			// FUNCTIONALITY: Prices - Save Changes
			if (StageShowLibUtilsClass::IsElementSet('post', 'savechanges'))
			{
				$this->CheckAdminReferer();
				
				$showID = StageShowLibUtilsClass::GetHTTPInteger('post', 'showID');  
				$results = $myDBaseObj->GetPricesListByShowID($showID);
				
				// Verify that Price Types are unique for each performance								
				if (count($results) > 0)
				{
					foreach ($results as $result)
					{
						$newPriceType  = StageShowLibUtilsClass::GetHTTPTextElem('post', 'priceType'.$result->priceID);
						$newPriceValue = StageShowLibUtilsClass::GetHTTPTextElem('post', 'priceValue'.$result->priceID);
						
						// Generate an entry that consists of the PerformanceID and the Price Type
						$priceEntry = $this->GetNewPriceReference($result);
						// FUNCTIONALITY: Prices - Reject Duplicate Price Refs
						if (isset($entriesList[$priceEntry]))
						{
							// Convert the perfID to a Performance Date & Time to display to the user
							$this->adminMsg = __('Duplicated Price Type', 'stageshow') . ' (' . $result->perfDateTime . ' - ' . $newPriceType . ')';
							break;
						}
						
						$this->adminMsg = $myDBaseObj->IsPriceValid($newPriceValue, $result);
						if ($this->adminMsg !== '')
						{
							$this->adminMsg .= ' (' . $result->perfDateTime . ' - ' . $newPriceType . ')';
							break;				
						}
						
						$entriesList[$priceEntry] = true;
					}
				}
				
				if ($this->adminMsg !== '')
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Settings have NOT been saved', 'stageshow').'. '.$this->adminMsg.'</p></div>');
				}
				else
				{
					if (count($results) > 0)
					{
						foreach ($results as $result)
						{
							$pricesUpdated = $this->SavePriceEntry($result);							
						} // End foreach
					}
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Settings have been saved', 'stageshow') . '</p></div>');
				}
			}
			else if (StageShowLibUtilsClass::IsElementSet('post', 'addpricebutton'))
			{
				$this->CheckAdminReferer();
				
				$showID = StageShowLibUtilsClass::GetHTTPInteger('post', 'showID');  
				
				// Performance ID of first performance is passed with call - Type ID is null ... AddPrice() will add (unique) value
				$perfID = StageShowLibUtilsClass::GetHTTPTextElem('post', 'perfID');  
				$myDBaseObj->AddPrice($perfID);
				
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Settings have been saved', 'stageshow') . '</p></div>');
			}
		}
		
		function Output_MainPage($updateFailed)
		{
			$this->filterRowDefs = array();
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_ALL,     __('All', 'stageshow'),     __('All', 'stageshow'));
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_ACTIVE,  __('Active', 'stageshow'),  __('Active', 'stageshow'));						
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_CURRENT, __('Current', 'stageshow'), __('Current', 'stageshow'));						
			
			$this->GetFilterCounts($this->filterRowDefs);
			
			// Calculate and output filter links - Returns the row count for the selected filter
			StageShowLibEscapingClass::Safe_EchoHTML("<div>\n");
			$filterObj = StageShowLibAdminListClass::OutputFilterLinks($this->filterRowDefs, STAGESHOWLIB_ADMINFILTER_CURRENT);
			StageShowLibEscapingClass::Safe_EchoHTML("</div>\n");
				
			$this->filterSQL = $this->GetShowsFilterSQL($filterObj->rowFilter);
				
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj  = $this->myDBaseObj;
			
			$isConfigured = $myDBaseObj->CheckIsConfigured();
			
			// Stage Show Prices HTML Output - Start 
			$showsList = $myDBaseObj->GetSortedShowsList($this->filterSQL);
			if (count($showsList) == 0)
			{
				// FUNCTIONALITY: Prices - Show Link to Settings page if Payment Gateway settings required
				if ($isConfigured)
				{
					$showsPageURL = get_option('siteurl') . '/wp-admin/admin.php?page=' . STAGESHOW_MENUPAGE_SHOWS;
					StageShowLibEscapingClass::Safe_EchoHTML("<div class='error'><p>" . __('No Show Configured', 'stageshow') . ' - <a href=' . $showsPageURL . '>' . __('Add one Here', 'stageshow') . '</a>' . "</p></div>\n");
				}
			}
			foreach ($showsList as $showEntry)
			{
?>
	<div class="stageshow-admin-form">
	<form method="post">
<?php
				$this->WPNonceField();
				if ($showEntry->maxPerfDateTime == null)
				{
					$showsPageURL = get_option('siteurl') . '/wp-admin/admin.php?page=' . STAGESHOW_MENUPAGE_PERFORMANCES;
					$showsPageMsg = $showEntry->showName . ' ' . __('has No Performances', 'stageshow') . ' - <a href=' . $showsPageURL . '>' . __('Add one Here', 'stageshow') . '</a>';
?> 
	<div class='error'><p><?php StageShowLibEscapingClass::Safe_EchoHTML($showsPageMsg); ?></p></div>
<?php
				}
				else
				{
?>
		<h3><?php StageShowLibEscapingClass::Safe_EchoHTML($showEntry->showName); ?></h3>
<?php
					$results = $myDBaseObj->GetPricesListByShowID($showEntry->showID);
					if (count($results) == 0)
					{
						StageShowLibEscapingClass::Safe_EchoHTML("<div class='noconfig'>" . __('Show has No Prices', 'stageshow') . "</div>\n");
					}
					else
					{
						$showsList = $this->CreateAdminListObj($this->env);
						$showsList->OutputList($results, $updateFailed);
					} 

      				StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" name="showID" value="'.$showEntry->showID.'" />'."\n");

					// FUNCTIONALITY: Prices - Output "Add New Price" Button (if valid)
					$this->showID = $showEntry->showID;
					$this->OutputPostButton("addpricebutton", __("Add New Price", 'stageshow'), "button-secondary");

					// Output Performance Select
					$this->OutputPerformanceSelect('&nbsp; '.__('for performance', 'stageshow').' &nbsp;');
				
					// FUNCTIONALITY: Prices - Output "Save Changes" Button (if there are entries)
					if (count($results) > 0)
						$this->OutputPostButton("savechanges", __("Save Changes", 'stageshow'), "button-primary");
				}
?>
		</form>
		</div>
<?php
			}
			// Stage Show Prices HTML Output - End 
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
				// FUNCTIONALITY: Prices - Bulk Action Delete - Block if tickets sold
				case StageShowLibAdminListClass::BULKACTION_DELETE:
					// Don't delete if any tickets have been sold for this performance
					$priceEntry = $myDBaseObj->GetPricesListByPriceID($recordId);
					$results = $myDBaseObj->GetSalesListByPriceID($recordId);
					if (count($priceEntry) == 0)
						$this->errorCount++;
					else if (count($results) > 0)
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
					// FUNCTIONALITY: Prices - Bulk Action Delete 
					// Now delete the entry in the PRICES table
					$delShowName = $myDBaseObj->DeletePriceByPriceID($recordId);
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
					// FUNCTIONALITY: Prices - Bulk Action Delete - Output Action Status Message
					if ($this->errorCount > 0)
						$actionMsg = $this->errorCount . ' ' . _n("Price does not exist in Database", "Prices do not exist in Database", $this->errorCount, 'stageshow');
					else if ($this->blockCount > 0)
						$actionMsg = $this->blockCount . ' ' . _n("Price cannot be deleted", "Prices cannot be deleted", $this->blockCount, 'stageshow').' - '.__("Tickets already sold!", 'stageshow');
					else if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Price has been deleted", "Prices have been deleted", $actionCount, 'stageshow');
					else
						$actionMsg = __("Nothing to Delete", 'stageshow');
					break;
			}
			
			return $actionMsg;
		}
		
		function OutputPerformanceSelect($label = '')
		{
			// Output a performance drop-down box
			$myDBaseObj  = $this->myDBaseObj;

			StageShowLibEscapingClass::Safe_EchoHTML($label);
			
			// Get performances list for this show
			$perfsList = $myDBaseObj->GetPerformanceAndZonesListByShowID($this->showID);
			
			StageShowLibEscapingClass::Safe_EchoHTML('<select name="perfID">'."\n");
			foreach ($perfsList as $perfRecord)
			{
				$perfPrompt = StageShowDBaseClass::FormatDateForAdminDisplay($perfRecord->perfDateTime);
				$perfID = $perfRecord->perfID;
				if (StageShowLibMigratePHPClass::Safe_strlen($perfRecord->zoneRef) > 0) 
				{
					$perfPrompt .= ' - '.$perfRecord->zoneRef;
					$perfID .= '-'.$perfRecord->zoneID;
				}
				$perfPrompt .= '&nbsp;&nbsp;';
				
				StageShowLibEscapingClass::Safe_EchoHTML("<option value=\"$perfID\">$perfPrompt</option>\n");
			}
			StageShowLibEscapingClass::Safe_EchoHTML('</select>'."\n");
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
					$filterSQL  = "((maxPerfDateTime >= '".$currTime."') || (maxPerfDateTime IS NULL))";
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
				$sqlSelect .= "COUNT(IF($filterSQL, 1, NULL)) count$filterId";
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

		// Commented out Class Def (StageShowPricesAdminClass)
		
	}
}











