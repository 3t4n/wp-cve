<?php
/* 
Description: Code for Managing Price Plans

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

if (!class_exists('StageShowPricePlansAdminListClass'))
{
	class StageShowPricePlansAdminListClass extends StageShowLibSalesAdminListClass // Define class
	{
		function __construct($env) //constructor
		{
			$this->tableUsesSerializedPost = apply_filters('stageshow_filter_usesserializedpost', STAGESHOWLIB_DISABLE_POSTCONTROLS, 'PricePlans');
			
			// Call base constructor
			parent::__construct($env, true);
			
			$this->bulkActions = array(
				StageShowLibAdminListClass::BULKACTION_DELETE => __('Delete', 'stageshow'),
			);
		}
	
		
		function GetTableID($result)
		{
			return "presettab" . $result->planID;
		}
		
		function GetRecordID($result)
		{
			return $result->presetID;
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
			
			for ($i=1; $i<=STAGESHOW_MAX_TICKETSEATS; $i++)
			{
				$countSelector[] = $i.'|'.$i;
			}

			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Type', StageShowLibTableClass::TABLEPARAM_ID => 'priceType',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_PRICETYPE_TEXTLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Price',       StageShowLibTableClass::TABLEPARAM_ID => 'priceValue', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => 9, StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodePrice', ),						
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Visibility',  StageShowLibTableClass::TABLEPARAM_ID => 'priceVisibility', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $visibilityOptions, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'No Of Seats', StageShowLibTableClass::TABLEPARAM_ID => 'priceNoOfSeats',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $countSelector, ),
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
			
			$ourOptions = self::MergeSettings($ourOptions, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Zone',        StageShowLibTableClass::TABLEPARAM_ID => 'priceZoneID',         StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_FUNC => 'GetPlanZones', StageShowLibTableClass::TABLEPARAM_AFTER => 'priceType', ),
				)
			);
			
			$disList = $this->myDBaseObj->GetDiscountsList();
			if (count($disList) > 0)
			{
				// Find the existing price entry ... and replace it
				foreach($ourOptions as $optionIndex => $thisOption)
				{
					if ($thisOption['Id'] == 'priceValue')
					{
						$ourOptions[$optionIndex] = 
							array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Prices',       StageShowLibTableClass::TABLEPARAM_ID => 'presetID',            StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_FUNCTION, StageShowLibTableClass::TABLEPARAM_FUNC => 'PriceHTMLWithDiscounts', StageShowLibTableClass::TABLEPARAM_AFTER => 'priceValue', );

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
		
		// Commented out Class Def (StageShowPricePlansAdminListClass)
		function GetPlanZones($result)
		{
			if ($result->seatingID == 0)
			{
				$countSelector[] = '0|'.__('N/A', 'stageshow');
			}
			else
			{
				//GetZones($result->seatingID);
				$zonesList = $this->myDBaseObj->GetSeatingZones($result->seatingID);
				
				foreach ($zonesList as $zoneDef)
				{
					$countSelector[] = $zoneDef->zoneID.'|'.$zoneDef->zoneRef;
				}				
			}

			return $countSelector;
		}
		
		function PriceHTMLWithDiscounts($result)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$allPricesOutput = '<table>';
			$priceId = 'priceValue'.$result->presetID;
			$htmlRow = $this->GetInputHTML($priceId, 9, $result->priceValue);
			$allPricesOutput .= "<tr><td>&nbsp;</td><td>$htmlRow</td></tr>";
			
			$disList = $myDBaseObj->GetDiscountsList();
			foreach ($disList as $disEntry)
			{
				$priceId = 'disPrice'.$result->presetID.'_'.$disEntry->disID;
				$priceRef = $disEntry->disRef;
				
				$priceValue = $myDBaseObj->GetPresetDiscount($result->presetID, $disEntry->disID);
				
				$htmlRow = $this->GetInputHTML($priceId, 9, $priceValue);
				$allPricesOutput .= "<tr><td>$priceRef</td><td>$htmlRow</td></tr>";
			}
			$allPricesOutput .= '</table>';
			
			return $allPricesOutput;
		}
		
	}
}

if (!class_exists('StageShowPricePlansAdminClass'))
{
	class StageShowPricePlansAdminClass extends StageShowLibAdminClass // Define class
	{
		function __construct($env) //constructor	
		{
			$this->pageTitle = 'Price Plans';
			
			// Call base constructor
			parent::__construct($env);
		}
		
		function SavePricePlans($results)
		{
			$planUpdated = false;
			$seatingPlan = $results[0];
			
			$myDBaseObj  = $this->myDBaseObj;
			
			$disList = $myDBaseObj->GetDiscountsList();
			
			foreach ($results as $result)
			{
				$newZoneID = StageShowLibUtilsClass::GetHTTPInteger('post', 'priceZoneID'.$result->presetID);
				if ($newZoneID != $result->priceZoneID)
				{
					// Update Preset Ref
					$myDBaseObj->UpdatePreset($result->presetID, 'priceZoneID', $newZoneID);
					$planUpdated = true;
				}
				
				foreach ($disList as $disEntry)
				{
					$priceId = 'disPrice'.$result->presetID.'_'.$disEntry->disID;
					$disValue = StageShowLibUtilsClass::GetHTTPNumber('post', $priceId);
					if ($disValue != '')
					{
						$myDBaseObj->SavePresetDiscount($disEntry->disID, $result->presetID, $disValue);
					}
					else
					{
						$myDBaseObj->DeletePresetDiscount($disEntry->disID, $result->presetID);
					}
				}
			}
			
			$pricePlan = $results[0];
			
			$myDBaseObj  = $this->myDBaseObj;
			
			$pricePlanFields = array('priceType', 'priceValue', 'priceVisibility', 'priceNoOfSeats');
			if ( ($myDBaseObj->getOption('ReservationsMode') != STAGESHOW_RESERVATIONSMODE_DISABLED)
			  && ($myDBaseObj->isOptionSet('CheckoutModesEnabled')) )	
			{
				$pricePlanFields[] = 'priceCheckoutMode';
			}		
			
			// FUNCTIONALITY: Price Plans - Update Price Plan Entry
			foreach ($results as $result)
			{
				foreach ($pricePlanFields as $pricePlanField)
				{
					$newPresetValue = StageShowLibUtilsClass::GetHTTPTextElem('post', $pricePlanField . $result->presetID);
					if ($newPresetValue != $result->$pricePlanField)
					{
						// Update Preset Ref
						$myDBaseObj->UpdatePreset($result->presetID, $pricePlanField, $newPresetValue);
						$planUpdated = true;
					}
				}
				
			}
			
			return $planUpdated;
		}
					
		function GetNewPriceReference($result)
		{
			$newPriceZoneRef  = StageShowLibUtilsClass::GetHTTPTextElem('post', 'priceZoneID'.$result->presetID);
			$priceEntry = $newPriceZoneRef.'-';
			
			$priceEntry .= StageShowLibUtilsClass::GetHTTPTextElem('post', 'priceType'.$result->presetID);
			
			return $priceEntry;
		}
			
		function ProcessActionButtons()
		{
			if (StageShowLibUtilsClass::IsElementSet('post', 'addpricebutton'))	
			{
				$this->myDBaseObj->planSeatingID = StageShowLibHTTPIO::GetRequestedInt('seatingID', 0);
			}	
			
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj  = $this->myDBaseObj;
			
			// FUNCTIONALITY: Price Plans - Save Changes
			if (StageShowLibUtilsClass::IsElementSet('post', 'savechanges'))
			{
				$this->CheckAdminReferer();
				
				// Get Plan ID to update
				$planID = StageShowLibUtilsClass::GetHTTPInteger('post', 'planID');   
				$results = $myDBaseObj->GetPricePlansListByPlanID($planID);
				
				$planUpdated = false;
				
				// Verify that Plan Price Refs are unique 
				if (count($results) > 0)
				{
					foreach ($results as $result)
					{
						$priceType = StageShowLibUtilsClass::GetHTTPTextElem('post', 'priceType'.$result->presetID);
						$presetValue = StageShowLibUtilsClass::GetHTTPTextElem('post', 'priceValue'.$result->presetID);
						
						// Generate an entry that consists of the Price Type
						$priceEntry = $this->GetNewPriceReference($result);
						// FUNCTIONALITY: Prices - Reject Duplicate Price Refs
						if (isset($refsList[$priceEntry]))
						{
							// Convert the perfID to a Performance Date & Time to display to the user
							$this->adminMsg = __('Duplicated Price Ref Entry', 'stageshow') . ' (' . $priceType . ')';
							break;
						}
						
						// FUNCTIONALITY: Price Plan - Reject Duplicate or Blank IDs
						// Verify that the Price Ref is not blank
						if (StageShowLibMigratePHPClass::Safe_strlen($priceType) == 0)
						{
							$this->adminMsg = __('Empty Price Ref Entry', 'stageshow');
							break;
						}
						
						$this->adminMsg = $myDBaseObj->IsPriceValid($presetValue, $result);
						if ($this->adminMsg !== '')
						{
							$this->adminMsg .= ' (' /* . $result->perfDateTime . ' - ' */ . $priceType . ')';
							break;				
						}
												
						$refsList[$priceEntry] = true;
					}
					
					if ($this->adminMsg === '')
					{
						$pricePlan = $results[0];
						
						$newPlanRef  = StageShowLibUtilsClass::GetHTTPTextElem('post', 'planRef'.$pricePlan->planID);
						$planRefName = 'planRef' . $planID;
						if ($newPlanRef != $pricePlan->planRef)
						{
							// FUNCTIONALITY: Price Plans - Update Price Plan Name blocking duplicate entries
							// Update Plan Ref - Return status indicates if it was unique
							if (!$myDBaseObj->UpdatePlanRef($planID, $newPlanRef))
								$this->adminMsg = __('Duplicated Price Plan ID', 'stageshow');
							else
								$planUpdated = true;
						}
						
						if ($this->adminMsg === '')
						{
							$planUpdated = $this->SavePricePlans($results);
						}
					}
				}
				
				if ($this->adminMsg !== '')
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>' . __('Settings have NOT been saved', 'stageshow') . '. ' . $this->adminMsg . '</p></div>');
				else if ($planUpdated)
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Settings have been saved', 'stageshow') . '</p></div>');
			}
			else if (StageShowLibUtilsClass::IsElementSet('post', 'addpriceplanbutton'))
			{
				// FUNCTIONALITY: Price Plans - Add new Price Plan 
				$this->CheckAdminReferer();
				
				// Add Group with unique Group Name 
				$planID = $myDBaseObj->AddPlan('');
				
				if ($planID == 0)
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>' . __('Cannot add a price plan', 'stageshow') . '</p></div>');
				else
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Default price plan added - Edit and Save to update it.', 'stageshow') . '</p></div>');
			}
			else if (StageShowLibUtilsClass::IsElementSet('post', 'addpricebutton'))
			{
				// FUNCTIONALITY: Price Plans - Add new price entry 
				$this->CheckAdminReferer();
				
				$planID = StageShowLibUtilsClass::GetHTTPInteger('post', 'planID');   
				
				// Add Group with unique Group Name 
				$presetID = $myDBaseObj->AddPreset($planID);
				
				if ($presetID == 0)
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>' . __('Cannot add a price', 'stageshow') . '</p></div>');
				else
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . __('Default price entry added - Edit and Save to update it.', 'stageshow') . '</p></div>');
			}
		}
		
		
		function Output_PlanName($seatingPlan, $groupIDRef, $planRef)
		{
			$groupIDLen   = STAGESHOW_PLANREF_TEXTLEN;
			$groupIDSize  = STAGESHOW_PLANREF_TEXTLEN + 1;
?>
	<input id="<?php StageShowLibEscapingClass::Safe_EchoAttr($groupIDRef); ?>" type="text" autocomplete="off" value="<?php StageShowLibEscapingClass::Safe_EchoAttr(($planRef)); ?>" size="<?php StageShowLibEscapingClass::Safe_EchoAttr($groupIDSize); ?>" maxlength="<?php StageShowLibEscapingClass::Safe_EchoAttr($groupIDLen); ?>" name="<?php StageShowLibEscapingClass::Safe_EchoAttr($groupIDRef); ?>">
<?php
			StageShowLibEscapingClass::Safe_EchoHTML('
				&nbsp;&nbsp;'.__('Seating Plan', 'stageshow').':&nbsp;
			');
			$seatingRef = __('(None)', 'stageshow');
			$seatingList = $this->myDBaseObj->GetSeatingList();
			foreach ($seatingList as $seatingSpec)
			{
				if ($seatingPlan->seatingID != $seatingSpec->seatingID) continue;
				$seatingRef = $seatingSpec->seatingRef;
				StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" id="seatingID" name="seatingID" value="'.$seatingPlan->seatingID.'"/>'."\n");
				break;
			}
			StageShowLibEscapingClass::Safe_EchoHTML($seatingRef);		
		}
		
		function Output_MainPage($updateFailed)
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj  = $this->myDBaseObj;
			
			// StageShow Price Plan HTML Output - Start 
			
			$pricePlansList = $myDBaseObj->GetAllPlansList();
			if (count($pricePlansList) == 0)
			{
				// FUNCTIONALITY: Price Plans - Show No Price Plans Confgured
				StageShowLibEscapingClass::Safe_EchoHTML("<div class='noconfig'>" . __('No Price Plans Configured', 'stageshow') . "</div>\n");
			}
			// FUNCTIONALITY: Price Plans - Output Settings
			foreach ($pricePlansList as $pricePlan)
			{
				$results      = $myDBaseObj->GetPricePlansListByPlanID($pricePlan->planID);
				$groupIDRef   = 'planRef' . $pricePlan->planID;
				
				$thisUpdateFailed = (($updateFailed) && ($pricePlan->planID == $_POST['planID']));
				
				if ($thisUpdateFailed)
					$planRef = StageShowLibUtilsClass::GetHTTPTextElem('post', $groupIDRef);   
				else
					$planRef = $pricePlan->planRef;
					
?>
	<div class="stageshow-admin-form">
	<form method="post">
	<div class="stageshow-edit-planref">
<?php
				$this->Output_PlanName($pricePlan, $groupIDRef, $planRef);
				
?>
	</div>
	<input type="hidden" name="planID" value="<?php StageShowLibEscapingClass::Safe_EchoAttr($pricePlan->planID); ?>"/>
<?php

				$this->WPNonceField();
				if (count($results) == 0)
				{
					StageShowLibEscapingClass::Safe_EchoHTML("<div class='noconfig'>" . __('Price Plan has No Prices', 'stageshow') . "</div>\n");
				}
				else
				{
					$perfsList = $this->CreateAdminListObj($this->env);
					$perfsList->OutputList($results, $thisUpdateFailed);
				} // End of if (count($results) == 0) ... else ...
				
?>
      <input type="hidden" name="planID" value="<?php StageShowLibEscapingClass::Safe_EchoAttr($pricePlan->planID); ?>"/>
<?php
				$this->OutputPostButton("addpricebutton", __("Add New Price", 'stageshow'));
				
				if (count($results) > 0)
				{
					$this->OutputPostButton("savechanges", __("Save Changes", 'stageshow'), "button-primary");
				}
				
?>
					</form>
					</div>
<?php
			} // End of foreach ($pricePlansList as $pricePlan) ..
?>
				<div class="stageshow-admin-form">
				<form method="post">
<?php
			$this->WPNonceField();
			$this->OutputPostButton("addpriceplanbutton", __("Add New Price Plan", 'stageshow'), "button-secondary");
?>	
				</form>
				</div>
<?php
			// StageShow Price Plan HTML Output - End 
		}
		
		function DoBulkPreAction($bulkAction, $recordId)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			// Reset error count etc. on first pass
			if (!isset($this->errorCount)) $this->errorCount = 0;
			if (!isset($this->blockCount)) $this->blockCount = 0;
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:
					// FUNCTIONALITY: Price Plans - Bulk Action Delete - Check Plan Exists
					if (!$myDBaseObj->PresetExists($recordId))
						$this->errorCount++;
					return ($this->errorCount > 0);
			}
			
			return false;
		}
		
		function DoBulkAction($bulkAction, $recordId)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:
					// FUNCTIONALITY: Price Plans - Bulk Action Delete - Remove Price(s) (and Price Plan on last entry)
					$myDBaseObj->DeletePreset($recordId);
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
					// FUNCTIONALITY: Price Plans - Bulk Action Delete - Output Action Status Message
					if ($this->errorCount > 0)
						$actionMsg = $this->errorCount . ' ' . _n("Price Plan Entry does not exist in Database", "Price Plan Entries do not exist in Database", $this->errorCount, 'stageshow');
					else if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Price Plan Entry has been deleted", "Price Plans Entries have been deleted", $actionCount, 'stageshow');
					else
						$actionMsg = __("Nothing to Delete", 'stageshow');
					break;
			}
			
			return $actionMsg;
		}
		
		// Commented out Class Def (StageShowPricePlansAdminClass)
		function OutputSeatingPlanSelect($label = '')
		{
			$myDBaseObj  = $this->myDBaseObj;

			StageShowLibEscapingClass::Safe_EchoHTML($label);
			
			$seatingPlansList = $myDBaseObj->GetSeatingList();
			
			StageShowLibEscapingClass::Safe_EchoHTML('
			<select name="seatingID">
			<option value="0" selected="selected">'.__('(None)', 'stageshow').'&nbsp;&nbsp;</option>
			');
			foreach ($seatingPlansList as $seatingPlan)
			{
				$seatingPlanRef = $seatingPlan->seatingRef.'&nbsp;&nbsp;';
				$seatingID = $seatingPlan->seatingID;
				StageShowLibEscapingClass::Safe_EchoHTML("<option value=\"$seatingID\">$seatingPlanRef</option>\n");
			}
			StageShowLibEscapingClass::Safe_EchoHTML('
			</select>
			');
		}
		
		function OutputButton($buttonId, $buttonText, $buttonClass = "button-secondary", $clickEvent = '')
		{
			parent::OutputButton($buttonId, $buttonText, $buttonClass, $clickEvent);
			
			switch ($buttonId)
			{
				case "addpriceplanbutton":
					// FUNCTIONALITY: Performances = Add "Price Plan" select to new Performance button
					StageShowLibEscapingClass::Safe_EchoHTML("<!-- Price Plan Select -->\n");
					$this->OutputSeatingPlanSelect('&nbsp; '.__('with Seating Plan', 'stageshow').' &nbsp;');
					break;
			}

		}
		
		
	}
}










