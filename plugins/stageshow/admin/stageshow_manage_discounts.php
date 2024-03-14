<?php
/* 
Description: Code for Managing Discount Codes Configuration

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

if (!class_exists('StageShowDiscountsAdminListClass'))
{
	class StageShowDiscountsAdminListClass extends StageShowLibSalesAdminListClass // Define class
	{
		var $updateFailed;
		
		function __construct($env) //constructor
		{
			// Call base constructor
			parent::__construct($env, true);
			
			$this->SetRowsPerPage(self::STAGESHOWLIB_EVENTS_UNPAGED);
			
			// FUNCTIONALITY: Discounts - Bulk Actions - Activate/Deactivate and Delete
			$this->bulkActions = array(
				StageShowLibAdminListClass::BULKACTION_TOGGLE => __('Activate/Deactivate', 'stageshow'),
				StageShowLibAdminListClass::BULKACTION_DELETE => __('Delete', 'stageshow'),
			);
		}
		
		function GetTableID($result)
		{
			return "discountstab";
		}
		
		function GetRecordID($result)
		{
			return $result->disID;
		}
		
		function GetMainRowsDefinition()
		{
			// FUNCTIONALITY: Discounts - Lists Discount Codes
			return array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Discount Ref',   StageShowLibTableClass::TABLEPARAM_ID => 'disRef',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,    StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_DISCOUNTREF_TEXTLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Discount Codes', StageShowLibTableClass::TABLEPARAM_ID => 'disCodes', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXTBOX, StageShowLibTableClass::TABLEPARAM_DECODE =>'GetDiscountCodesText', StageShowLibTableClass::TABLEPARAM_ROWS => 4, StageShowLibTableClass::TABLEPARAM_COLS => 60, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'State',          StageShowLibTableClass::TABLEPARAM_ID => 'disState', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VALUE,   StageShowLibTableClass::TABLEPARAM_DECODE =>'GetDiscountState', ),						
			);
		}
		
		function GetDiscountState($disState)
		{
			// FUNCTIONALITY: Discounts - Report show state
			return $this->myDBaseObj->StateActiveText($disState);
		}
		
		function GetDiscountCodesText($disCodes)
		{
			// FUNCTIONALITY: Discounts - Split into lines
			$disCodesList = explode(",", $disCodes);
			$disCodes = '';
			$cr = '';
			foreach($disCodesList as $discountCodeEntry)
			{
				$discountCodeEntry = StageShowLibMigratePHPClass::Safe_trim($discountCodeEntry);
				if (StageShowLibMigratePHPClass::Safe_strlen($discountCodeEntry) == 0)
					continue;
					
				$disCodes .= $cr.$discountCodeEntry;
				$cr = "\n";
			}
			return $disCodes;
		}
		
		function OutputList($results, $updateFailed = false)
		{
			// FUNCTIONALITY: Discounts - Reset form on update failure
			$this->updateFailed = $updateFailed;
			parent::OutputList($results, $updateFailed);
		}
		
	}
}

if (!class_exists('StageShowDiscountsAdminClass'))
{
	class StageShowDiscountsAdminClass extends StageShowLibAdminClass // Define class
	{
		function __construct($env) //constructor	
		{
			$this->pageTitle = 'Discounts';
			
			// Call base constructor
			parent::__construct($env);			
		}
		
		function ProcessActionButtons()
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj  = $this->myDBaseObj;
		
			// FUNCTIONALITY: Discounts - Save Changes
			if (StageShowLibUtilsClass::IsElementSet('post', 'savechanges'))
			{
				// Save Settings Request ....
				$results = $myDBaseObj->GetDiscountsList();
				
				$entriesList = array();
				$allDisCodeList = array();
				$disCodesList = array();
				$discountsUpdated = false;
					
				// Verify that names are unique 				
				if (count($results) > 0)
				{
					foreach ($results as $result)
					{
						$disID = $result->disID;						
						$disRef = StageShowLibUtilsClass::GetHTTPTextElem('post', 'disRef'.$disID);
						
						// FUNCTIONALITY: Discounts - Reject Duplicate or Empty Name
						if (StageShowLibMigratePHPClass::Safe_strlen($disRef) == 0)
						{
							$this->adminMsg = __('Empty Discount Ref Entry', 'stageshow');
							break;
						}
						
						if (isset($entriesList[$disRef]))
						{
							$this->adminMsg = __('Duplicated Discount Ref', 'stageshow') . ' (' . $disRef . ')';
							break;
						}
						$entriesList[$disRef] = true;
						
						$newDiscountCodes = StageShowLibUtilsClass::GetHTTPTextareaElem('post', 'disCodes'.$disID);
						$disCodesArray = explode("\n", $newDiscountCodes);
						$disCodesList[$disID] = ',';
						foreach ($disCodesArray as $discountCode)
						{
							$discountCode = StageShowLibMigratePHPClass::Safe_trim($discountCode);
							if (StageShowLibMigratePHPClass::Safe_strlen($discountCode) == 0) 
								continue;
							
							if (!preg_match('/^[A-Za-z0-9]+$/', $discountCode))
							{
								$this->adminMsg = __('Invalid Discount Code', 'stageshow')." ($discountCode)";
								break 2;
							}
							
							if (StageShowLibMigratePHPClass::Safe_strlen($discountCode) > STAGESHOW_DISCOUNTCODE_TEXTLEN)
							{
								$this->adminMsg = __('Discount Code Too Long', 'stageshow')." ($discountCode)";
								break 2;
							}
							
							if (isset($allDisCodeList[$discountCode]))
							{
								$this->adminMsg = __('Duplicated Discount Code', 'stageshow') . ' (' . $discountCode . ')';
								break;
							}
							$allDisCodeList[$discountCode] = true;
							
							$disCodesList[$disID] .= $discountCode.',';
						}						

						if ($disCodesList[$disID] == ',')
						{
							$this->adminMsg = __('Empty Discount Codes Entry', 'stageshow') . ' (' . $disRef . ')';
							break;
						}
					}

					if ($this->adminMsg == '')
					{
						foreach ($results as $result)
						{
							$disID = $result->disID;						
							$disRef = StageShowLibUtilsClass::GetHTTPTextElem('post', 'disRef'.$disID);
							if ($disRef != $result->disRef)
							{
								$myDBaseObj->UpdateDiscountRef($result->disID, $disRef);
								$discountsUpdated = true;
							}
						
							if ($disCodesList[$disID] != $result->disCodes)
							{
								$myDBaseObj->UpdateDiscountCodes($disID, $disCodesList[$disID]);
								$discountsUpdated = true;
							}					
						}
						
						if (!$discountsUpdated)
						{
							$this->adminMsg = __('No Changes', 'stageshow');
						}
					}
				}
					
				if ($this->adminMsg != '')
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>' . __('Settings have NOT been saved', 'stageshow') . '. ' . $this->adminMsg . '</p></div>');
				}
				else
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Settings have been saved', 'stageshow') . '</p></div>');
				}
			}
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'adddiscountbutton'))
			{
				// FUNCTIONALITY: Discounts - Add a new entry
				// Add Discount with unique Ref 
				$disID = $myDBaseObj->AddDiscount('');
				
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Default entry added - Edit and Save to update it.', 'stageshow') . '</p></div>');
			}		
		}
		
		function Output_MainPage($updateFailed)
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj = $this->myDBaseObj;
			
?>
	<div class="stageshow-admin-form">
	<form method="post">
<?php

			$this->WPNonceField();
			
			$results = $myDBaseObj->GetDiscountsList();
			if (count($results) == 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<div class='noconfig'>" . __('No Discounts Configured', 'stageshow') . "</div>\n");
			}
			else
			{
				$adminTableObj = $this->CreateAdminListObj($this->env);
				$adminTableObj->OutputList($results, $updateFailed);
			}
			
			// FUNCTIONALITY: Discounts - Output "Add New Discount" Button (if valid)
			$this->OutputButton("adddiscountbutton", __("Add New Discount", 'stageshow'));
			
			if (count($results) > 0)
			{
				// FUNCTIONALITY: Discounts - Output "Save Changes" Button (if there are entries)
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
					// FUNCTIONALITY: Discounts - Bulk Action Delete - Block if tickets sold
/*					
					// Don't delete if any tickets have been sold for this performance
					$delShowEntry = $myDBaseObj->GetDiscountsList($recordId);
					if (count($delShowEntry) == 0)
						$this->errorCount++;
					else if (!$myDBaseObj->CanDeleteShow($delShowEntry[0]))
						$this->blockCount++;
*/						
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
					// FUNCTIONALITY: Discounts - Bulk Action Delete - Remove Discount and Corresponding Prices
					// Delete the entry in the DISCOUNTS table
					$myDBaseObj->DeleteDiscount($recordId);

/*					
					// TODO - Delete or Hide Prices ?
*/					
					
					//$myDBaseObj->PurgeDB();
					return true;
				
				case StageShowLibAdminListClass::BULKACTION_TOGGLE:
					// FUNCTIONALITY: Discounts - Bulk Action Activate/Deactivate Discount Code		
					$actionCount = 0;
					$discountEntry   = $myDBaseObj->GetDiscountsList($recordId);
					if ($myDBaseObj->IsStateActive($discountEntry[0]->disState))
						$myDBaseObj->SetDiscountActivated($recordId, STAGESHOW_STATE_INACTIVE);
					else
						$myDBaseObj->SetDiscountActivated($recordId, STAGESHOW_STATE_ACTIVE);
					
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
					// FUNCTIONALITY: Discounts - Bulk Action Delete - Output Action Status Message
					if ($this->errorCount > 0)
						$actionMsg = $this->errorCount . ' ' . _n("Discount does not exist in Database", "Discounts do not exist in Database", $this->errorCount, 'stageshow');
					else if ($this->blockCount > 0)
						$actionMsg = $this->blockCount . ' ' . _n("Discount cannot be deleted", "Discounts cannot be deleted", $this->blockCount, 'stageshow').' - '.__("Tickets already sold!", 'stageshow');
					else if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Discount has been deleted", "Discounts have been deleted", $actionCount, 'stageshow');
					else
						$actionMsg = __("Nothing to Delete", 'stageshow');
					break;
					
				case StageShowLibAdminListClass::BULKACTION_TOGGLE:
					// FUNCTIONALITY: Discounts - Bulk Action Delete - Output Action Status Message
					if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Discount has been Activated/Deactivated", "Discounts have been Activated/Deactivated", $actionCount, 'stageshow');
					else
						$actionMsg = __("Nothing to Activate/Deactivate", 'stageshow');
					break;
			}
			
			return $actionMsg;
		}
		
	}
}
