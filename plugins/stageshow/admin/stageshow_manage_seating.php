<?php
/* 
Description: Code for Managing Seating Plans
 
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

require_once STAGESHOW_INCLUDE_PATH.'stageshowlib_adminlist.php';
require_once STAGESHOW_INCLUDE_PATH.'stageshowlib_admin.php';      

if (!class_exists('StageShowSeatingAdminListClass')) 
{
	// --- Define Class: StageShowSeatingAdminListClass
	class StageShowSeatingAdminListClass extends StageShowLibAdminListClass // Define class
	{	
		const BULKACTION_RELOAD = 'reload';
		
		function __construct($env) //constructor
		{
			$this->hiddenRowsButtonId = 'TBD';		
			
			// Call base constructor
			parent::__construct($env, true);
			
			$this->hiddenRowsButtonId = __('Details', 'stageshow');		
			
			$this->SetRowsPerPage(self::STAGESHOWLIB_EVENTS_UNPAGED);
			
			$this->bulkActions = array(
				self::BULKACTION_RELOAD => __('Reload', 'stageshow'),
				self::BULKACTION_DELETE => __('Delete', 'stageshow'),
				);
					
			$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_BOTH;
			
		}
		
		function GetRecordID($result)
		{
			return $result->seatingID;
		}
		
		function GetCurrentURL() 
		{			
			$currentURL = parent::GetCurrentURL();
			return $currentURL;
		}
		
		function GetDetailsRowsFooter()
		{
			$ourOptions = array(
			);
		
			$ourOptions = self::MergeSettings(parent::GetDetailsRowsFooter(), $ourOptions);
			
			return $ourOptions;
		}
		
		function GetTableID($result)
		{
			return "stageshowgold-seating-list-tab";
		}
		
		function ShowSeatingDetails($result)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$seatingDetails = '';
			$extraDetails = '';
			$seatingResults = $myDBaseObj->GetSeatingLayoutByID($result->seatingID);	
				
			$enableDecodesEdit = ($myDBaseObj->isDbgOptionSet('Dev_ExpertMode') || $myDBaseObj->CanEditSeatingLayout($result->seatingID));
					
			if ((count($seatingResults) > 0) && ($seatingResults[0]->zoneID > 0))
			{
				$seatingDetails = $this->BuildSeatingDetails($seatingResults);

				$zoneDefsParams = 'seatingRef'.$result->seatingID.'='.urlencode($result->seatingRef);
				$zoneDefsParams .= '&seatingDecodeTable'.$result->seatingID.'='.urlencode($result->seatingDecodeTable);

				$ourZones = $myDBaseObj->GetSeatingZones($result->seatingID);
				foreach ($seatingResults as $ourZone)
				{
					$zoneIndex = $ourZone->seatingID.'_'.$ourZone->zoneID;
					$zoneDefsParams .= '&zoneRef'.$zoneIndex.'='.urlencode($ourZone->zoneRef);
					$zoneDefsParams .= '&zoneSpec'.$zoneIndex.'='.urlencode($ourZone->zoneSpec);
					$zoneDefsParams .= '&zoneAllocSeats'.$zoneIndex.'='.urlencode($ourZone->zoneAllocSeats);
				}				
				$previewButton  = '<tr><td colspan="4" align="center">';
				$previewButton .= $myDBaseObj->ActionButtonHTML('Generate Preview', $this->caller, 'stageshow', '', $this->GetRecordID($result), 'viewtemplate', $zoneDefsParams, '_blank');
				$previewButton .= '</td></tr>';
			
				$extraDetails .= $previewButton;
			}

			$settingOption = $this->seatDecodesSelectorDef;
			$fieldID = $settingOption[self::TABLEPARAM_ID];
			$currVal = $result->$fieldID;
			$planId = $this->GetRecordID($result);
			$settingOption[self::TABLEPARAM_ID] .= $planId;
			$seatingDecodeSelector = $this->GetHTMLTag($settingOption, $currVal, $enableDecodesEdit);

			$extraDetails .= "
				<tr>
					<td>".$settingOption[self::TABLEPARAM_LABEL]."</td>
					<td>$seatingDecodeSelector</td>
				</tr>";
		
			if ($seatingResults[0]->seatingImportFile != '')
			{
				$importMsg  = '<strong>'.__('Using Imported Seating Template', 'stageshow').'</strong>';
				$importMsg .= ":&nbsp;&nbsp;".$seatingResults[0]->seatingImportFile;
				$importMsg .= " (".$seatingResults[0]->seatingImportDateTime.")";
				$importMsg .= "&nbsp;&nbsp;";
				$importMsg .= $this->myDBaseObj->ActionButtonHTML('View', $this->caller, 'stageshow', '', $this->GetRecordID($result), 'viewimport', '', '_blank');
				$extraDetails .= "
					<tr>
						<td colspan=4>".$importMsg."</td>
					</tr>";
			}
		
			if ($extraDetails != '')
			{
				$endOfTable = '</tbody></table>';		
				$seatingDetails = StageShowLibMigratePHPClass::Safe_str_replace($endOfTable, $extraDetails.$endOfTable, $seatingDetails);		
			}

			return $seatingDetails;
		}
				
		function GetListDetails($result)
		{
			return $this->myDBaseObj->GetSeatingLayoutByID($result->seatingID);
		}
		
		function BuildSeatingDetails($seatingResults)
		{
			$env = $this->env;

			$seatingPlansList = $this->CreateSeatingAdminDetailsListObject($env, $this->editMode, $seatingResults);	

			// Set Rows per page to disable paging used on main page
			$seatingPlansList->enableFilter = false;
			
			ob_start();	
			$seatingPlansList->OutputList($seatingResults);	
			$zoneDetailsOutput = ob_get_contents();
			ob_end_clean();

			return $zoneDetailsOutput;
		}
		
		function NeedsConfirmation($bulkAction)
		{
			switch ($bulkAction)
			{
				default:
					return parent::NeedsConfirmation($bulkAction);
			}
		}
		
		function ExtendedSettingsDBOpts()
		{
			return parent::ExtendedSettingsDBOpts();
		}
		
		function GetMainRowsDefinition()
		{
			$columnDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Seating Plan Ref',	StageShowLibTableClass::TABLEPARAM_ID => 'seatingRef',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_SEATINGREF_TEXTLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Venue Name',	    StageShowLibTableClass::TABLEPARAM_ID => 'seatingVenue',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_VENUENAME_TEXTLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Zones',            StageShowLibTableClass::TABLEPARAM_ID => 'zonesCount',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Seats',            StageShowLibTableClass::TABLEPARAM_ID => 'seatingSeats', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
			);
			
			return $columnDefs;
		}		

		function GetDetailsRowsDefinition()
		{
			$pluginID = STAGESHOW_FOLDER;
			$templatePath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/html/';
			
			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_FUNCTION, StageShowLibTableClass::TABLEPARAM_FUNC => 'ShowSeatingDetails'),
				array(StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_FUNCTION, StageShowLibTableClass::TABLEPARAM_FUNC => 'AddZoneButtons'),
			);
			
			$seatDecodesSelectorLabel = __('Seat Decode Definition', 'stageshow');
			$this->seatDecodesSelectorDef = 
				array(StageShowLibTableClass::TABLEPARAM_LABEL => $seatDecodesSelectorLabel,  StageShowLibTableClass::TABLEPARAM_ID => 'seatingDecodeTable',     StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_DIR => $templatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'tab', StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true, StageShowLibTableClass::TABLEPARAM_ONCHANGE => 'stageshow_OnChangeZoneDecode');
			
			$rowDefs = self::MergeSettings(parent::GetDetailsRowsDefinition(), $ourOptions);

			return $rowDefs;
		}
		
		function AddZoneButtons($result)
		{
			$exportURL  = $this->myDBaseObj->AddParamAdminReferer('stageshowlib_export.php', StageShowLibUtilsClass::GetCallbackURL(STAGESHOW_EXPORT_TARGET));
			$exportURL .= '&download=true';
			$exportURL .= '&seatingID='.$result->seatingID;
			$exportURL .= '&export_format=html';
			$exportURL .= '&exportSeatingTemplate=Export';
		
			$buttonHTML = '';		
			
			$buttonHTML .=  $this->myDBaseObj->ActionButtonHTML('Add Zone', $this->caller, 'stageshow', '', $this->GetRecordID($result), 'addzone'); 
			$buttonHTML .=  '<a id="stageshow-exporttemplate-'.$result->seatingID.'" class="button-secondary" href="'.$exportURL.'">'.__('Export Template', 'stageshow').'</a>';
			$buttonHTML .=  $this->myDBaseObj->ActionButtonHTML('Import Template', $this->caller, 'stageshow', '', $this->GetRecordID($result), 'importtemplate'); 
				
			return $buttonHTML;
		}
		
		function CreateSeatingAdminDetailsListObject($env, $editMode, $seatingResults)
		{
			return new StageShowSeatingAdminDetailsListClass($env, $editMode, $seatingResults);	
		}
		
	}
}


if (!class_exists('StageShowSeatingAdminDetailsListClass')) 
{
	class StageShowSeatingAdminDetailsListClass extends StageShowLibAdminListClass // Define class
	{		
		function __construct($env, $editMode, $seatingResults) //constructor
		{
			$this->ctrlGroup = $seatingResults[0]->seatingID;
			
			// Call base constructor
			parent::__construct($env, $editMode);
			
			if ($this->myDBaseObj->isDbgOptionSet('Dev_ExpertMode'))
			{
				$this->activeZonesList = array();
			}
			else
			{				
				// Set flag to lock Seating Details once prices have been allocated
				$this->activeZonesList = $this->myDBaseObj->GetActiveZones($seatingResults[0]->seatingID);
			}
			
			$this->SetRowsPerPage(self::STAGESHOWLIB_EVENTS_UNPAGED);
			
			$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_TOP;
		}
		
		function GetTableID($result)
		{
			return "stageshowgold-seating-details-list-tab";
		}
		
		function GetRecordID($result)
		{
			return $result->seatingID;
		}
		
		function GetDetailID($result)
		{
			return '_'.$result->zoneID;
		}
		
		function CanEditZone($result)
		{
			// Seating is readonly when prices have been defined
			return !isset($this->activeZonesList[$result->zoneID]);
		}

		function GetMainRowsDefinition()
		{
			$rtnVal = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Zone Ref',        StageShowLibTableClass::TABLEPARAM_ID => 'zoneRef',        StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,     StageShowLibTableClass::TABLEPARAM_CANEDIT => 'CanEditZone', StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_ZONEREF_TEXTLEN, StageShowLibTableClass::TABLEPARAM_ONCHANGE => 'stageshow_OnChangeZoneRef', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Zone Spec',       StageShowLibTableClass::TABLEPARAM_ID => 'zoneSpec',       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXTBOX,  StageShowLibTableClass::TABLEPARAM_CANEDIT => 'CanEditZone', StageShowLibTableClass::TABLEPARAM_ROWS  => 2, StageShowLibTableClass::TABLEPARAM_COLS => 80, StageShowLibTableClass::TABLEPARAM_ONCHANGE => 'stageshow_OnChangeZoneSpec', ), 
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Allocated Seats', StageShowLibTableClass::TABLEPARAM_ID => 'zoneAllocSeats', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_CANEDIT => 'CanEditZone', StageShowLibTableClass::TABLEPARAM_TEXT => 'Allocated Seats', StageShowLibTableClass::TABLEPARAM_ONCHANGE => 'stageshow_OnChangeZoneAlloc', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Zone Seats',      StageShowLibTableClass::TABLEPARAM_ID => 'zoneSeats',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
			);
			
			return $rtnVal;
		}
		
		function IsRowInView($result, $rowFilter)
		{
			return true;
		}		
				
	}
}
	
if (!class_exists('StageShowSeatingAdminClass')) 
{
	// --- Define Class: BaseSeatingAdminClass
	class StageShowSeatingAdminClass extends StageShowLibAdminClass // Define class
	{		
		var $results;
		var $showOptionsID = 0;
		
		function __construct($env)
		{
			$this->pageTitle = __('Seating Plans', 'stageshow');
			
			parent::__construct($env, true);
		}
		
		function CanEditSeating($seatingID, $seatingRef = '')
		{
			$myDBaseObj = $this->myDBaseObj;
			
			if ($myDBaseObj->isDbgOptionSet('Dev_ExpertMode'))
			{
				return true;
			}
			
			$canEdit = $myDBaseObj->CanEditSeatingLayout($seatingID);
			if (!$canEdit)
			{
				if ($seatingRef == '')
				{
					$results = $myDBaseObj->GetSeatingLayout($seatingID);
					if (count($results) > 0) $seatingRef = $results[0]->seatingRef;
				}
				$errMsg = sprintf(__('Cannot Edit %s Layout - Tickets Already Sold', 'stageshow'), '<strong>'.$seatingRef.'</strong>');
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.$errMsg.'</p></div>');
			}
			
			return $canEdit;
		}
		
		function ProcessActionButtons()
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj = $this->myDBaseObj;				
				
			if (StageShowLibUtilsClass::IsElementSet('post', 'addseatingplanbutton'))
			{
				// Add a new entry
				$this->CheckAdminReferer();		
				
				// Get Unique Plan namespace
				$seatLayoutName = $myDBaseObj->GetUniqueSeatingLayoutRef();
				
				// Add Plan to Database	with one zone
				$seatingPlanId = $myDBaseObj->AddSeatingLayout($seatLayoutName);
				$myDBaseObj->AddSeatingZone($seatingPlanId, '1.1', 'Zone 1', 1);
			}
			else if (StageShowLibUtilsClass::IsElementSet('post', 'savechanges'))
			{

				// Get Current Seating Layouts
				$layoutsChanged = array();
				$zoneDefs = $myDBaseObj->GetSeatingLayout();
				
				/*
					Note: 
						Zones can be changed if there are no prices for that zone
						All other items in seating plan can only be changed if there are no sales
				*/
					
				// Check for any changes
				foreach ($zoneDefs as $zoneDef)
				{
					$seatingID = $zoneDef->seatingID;
					if (!isset($seatingSettings[$seatingID]))
					{
						$seatingSettings[$seatingID] = new stdClass();
						$refOrDecodesChanged = false;
						
						$seatingRef = StageShowLibUtilsClass::GetHTTPTextElem('post', 'seatingRef'.$seatingID);  
						$seatingSettings[$seatingID]->seatingRef = $seatingRef;
						if (StageShowLibMigratePHPClass::Safe_trim($zoneDef->seatingRef) != $seatingRef)
						{
							$refOrDecodesChanged = true;
						}
						
						$seatingVenue = StageShowLibUtilsClass::GetHTTPTextElem('post', 'seatingVenue'.$seatingID);  
						$seatingSettings[$seatingID]->seatingVenue = $seatingVenue;
						if (StageShowLibMigratePHPClass::Safe_trim($zoneDef->seatingVenue) != $seatingVenue)
						{
							$refOrDecodesChanged = true;
						}
						
						$seatingDecodeTable = StageShowLibUtilsClass::GetHTTPTextElem('post', 'seatingDecodeTable'.$seatingID);  
						$seatingSettings[$seatingID]->seatingDecodeTable = $seatingDecodeTable;
						if ($zoneDef->seatingDecodeTable != $seatingDecodeTable)
						{
							$refOrDecodesChanged = true;
						}
						
						if ($refOrDecodesChanged)
						{
							$layoutsChanged[$seatingID] = true;
							if (!$this->CanEditSeating($seatingID, $zoneDef->seatingRef))
							{
								return;
							}
						}
					}
					
					$zoneChanged = false;
					$zoneID = $zoneDef->zoneID;
					$recId = $seatingID.'_'.$zoneDef->zoneID;
					
					$zoneRefId = 'zoneRef'.$recId;
					$zoneRef = StageShowLibUtilsClass::GetHTTPTextElem('post', $zoneRefId);  
					$seatingSettings[$seatingID]->zoneRefs[] = $zoneRef;
					if (StageShowLibMigratePHPClass::Safe_trim($zoneDef->zoneRef) != $zoneRef)
					{
						$zoneChanged = true;
					}
					
					$zoneSpecId = 'zoneSpec'.$recId;
					$zoneSpec = StageShowLibUtilsClass::GetHTTPTextElem('post', $zoneSpecId);  
					$seatingSettings[$seatingID]->zoneSpecs[] = $zoneSpec;
					if ((StageShowLibMigratePHPClass::Safe_trim($zoneDef->zoneSpec) != $zoneSpec) || ($zoneSpec == ''))
					{
						$zoneChanged = true;
					}
					
					$zoneAllocSeatsId = 'zoneAllocSeats'.$recId;					
					$zoneAllocSeats = StageShowLibUtilsClass::GetHTTPInteger('post', $zoneAllocSeatsId, 0);  
					$seatingSettings[$seatingID]->zoneAllocSeats[] = $zoneAllocSeats;
					if ($zoneDef->zoneAllocSeats != $zoneAllocSeats)
					{
						$zoneChanged = true;
					}
					
					if (!$zoneChanged)
						continue;
						
					$layoutsChanged[$seatingID] = true;
					$activeZonesList = $myDBaseObj->GetActiveZones($seatingID);
					if (isset($activeZonesList[$zoneID]) && !$myDBaseObj->isDbgOptionSet('Dev_ExpertMode'))
					{
						$errMsg = sprintf(__('Cannot Edit %s Zone - Prices Already Defined', 'stageshow'), '<strong>'.$seatingRef.'</strong>');
						StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.$errMsg.'</p></div>');
						return;
					}
				}
									
				foreach ($layoutsChanged as $seatingID => $unused)
				{
					$layoutTemplateObj = $myDBaseObj->CreateLayoutTemplate($seatingSettings[$seatingID], $seatingID);
					if ($layoutTemplateObj->errMsg != '')
					{
						$errMsg = sprintf(__('Error Parsing %s Layout', 'stageshow'), '<strong>'.$seatingSettings[$seatingID]->seatingRef.'</strong>').' - '.$layoutTemplateObj->errMsg;;
						StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.$errMsg.'</p></div>');
						$this->showOptionsID = $seatingID;
						return;
					}
										
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>' . $layoutTemplateObj->confirmMsg . '</p></div>');
				}

			}			
			else if (StageShowLibUtilsClass::IsElementSet('get', 'action'))
			{
				$this->CheckAdminReferer();
				$this->DoActions();
			}

		}
		
		function Output_MainPage($updateFailed)
		{
			if (isset($this->pageHTML))	
			{
				StageShowLibEscapingClass::Safe_EchoHTML($this->pageHTML);
				return;
			}
			
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj = $this->myDBaseObj;				
			
			if (!isset($this->results))	
			{
				$this->results = $myDBaseObj->GetSeatingLayoutZoneCounts();
			}
			
			$actionURL = remove_query_arg('action');
			$actionURL = remove_query_arg('id', $actionURL);
			
			// HTML Output - Start 
			$formClass = 'stageshow'.'-admin-form '.'stageshow'.'-seatingplan-editor';
			StageShowLibEscapingClass::Safe_EchoHTML('
				<div class="'.$formClass.'">
				<form method="post" action="'.$actionURL.'">
				');

			if (isset($this->saleId))
				StageShowLibEscapingClass::Safe_EchoHTML("\n".'<input type="hidden" name="saleID" value="'.$this->saleId.'"/>'."\n");
				
			$this->WPNonceField();
				 
			if(count($this->results) == 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<div class='noconfig'>".__('No Seating Plans', 'stageshow')."</div>\n");
			}
			else 
			{
				$this->OutputSeatingPlansList($this->env);
			}

			$this->OutputButton("addseatingplanbutton", __("Add Seating Plan", 'stageshow'));
			if (count($this->results) > 0)
			{
				$this->OutputButton("savechanges", __("Save Changes", 'stageshow'), "button-primary");
			}

?>
	<br></br>
	</form>
	</div>
<?php
		} // End of function Output_MainPage()


		function OutputSeatingPlansList($env)
		{
			$myPluginObj = $this->myPluginObj;
			
			$classId = 'StageShowSeatingAdminListClass';
			$seatingPlansObj = new $classId($env);	// StageShowLibGatewayBaseClassxxxxSeatingAdminListClass etc.
			$seatingPlansObj->showOptionsID = $this->showOptionsID;
			$seatingPlansObj->OutputList($this->results);		
		}
				
		function DoActions()
		{
			$rtnVal = false;
			$myDBaseObj = $this->myDBaseObj;

			switch ($_GET['action'])
			{
				case 'addzone':
					// Add a new zone to the seating plan
					$seatingPlanId = StageShowLibUtilsClass::GetHTTPInteger('get', 'id');  
					
					// Check that the referer is OK
					$this->CheckAdminReferer();		

					// Get Unique Plan namespace
					$zoneName = $myDBaseObj->GetUniqueSeatingZoneRef($seatingPlanId);
					
					// Add Plan to Database	
					$myDBaseObj->AddSeatingZone($seatingPlanId, '', $zoneName, 0);
					
					$this->showOptionsID = $seatingPlanId;
					
					$rtnVal = true;
					break;
					
				case 'exporttemplate':
					break;
					
				case 'importtemplate':
					$seatingPlanId = StageShowLibUtilsClass::GetHTTPInteger('get', 'id');  
					$seatingDefs = StageShowZonesDBaseClass::GetSeatingTemplate($myDBaseObj, $seatingPlanId);
					if (count($seatingDefs) == 0) break;
					
					$this->pageHTML =  "<h3>".__("Import HTML Template for", 'stageshow')." <strong>".$seatingDefs[0]->seatingRef."</strong></h3><br>\n";
					if (StageShowLibUtilsClass::IsElementSet('post', 'importSeatingTemplate'))
					{
						//$this->pageHTML .= 'Import Requested<br>';
						$statusMsg = '';
						$importStatus = $myDBaseObj->ImportSeatingTemplate('importTemplateFile', $statusMsg);
						if (!$importStatus)
						{
							$this->pageHTML .= '<div id="message" class="error"><p>'.$statusMsg."</p></div>";			
						}
						else
						{
							$this->pageHTML .= '<div id="message" class="updated"><p>'.$statusMsg."</p></div>";			
						}
					} 
					else
					{
						$this->pageHTML .= '
							<div class="wrap">	
							<p>'.__('Select the Template file you want to import below', 'stageshow').'.</p>
							
							<style>
							#XX_importTemplateFile 
							{
								padding: 2px 2px 2px 2px;
								margin: 0 0 0 0;
							}
							#XX_importSeatingTemplate
							{
								margin: 2px 2px 2px 2px;
							}
							</style>
							<form method="post" enctype="multipart/form-data">
								<input type="hidden" id="seatingID" name="seatingID" value="'.$seatingPlanId.'"> 
								<input type="file" name="importTemplateFile" id="importTemplateFile" />		
								<input type="submit" name="importSeatingTemplate" id="importSeatingTemplate" class="button-secondary" value="Import Template" />
							</form>
							<br>
							<a class="button-secondary" href="admin.php?page=stageshowgold_seating">Back</a>
							</div>
						';						
					}
					$rtnVal = true;
					break;
					
				case 'viewtemplate':
				case 'viewimport':
					// Preview the seating plan
					$seatingPlanId = StageShowLibUtilsClass::GetHTTPInteger('get', 'id');  
					
					// Check that the referer is OK
					$this->CheckAdminReferer();		

					$seatingSettings = new stdClass();
					$seatingSettings->seatingRef = '';
					$seatingSettings->seatingDecodeTable = '';
					$seatingSettings->zoneRefs = array();
					$seatingSettings->zoneAllocSeats = array();
					
					if ($_GET['action'] == 'viewtemplate')
					{
						foreach (array_keys($_REQUEST) as $paramId)
						{
							$paramVal = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, $paramId);  
							if ($paramId === "seatingRef".$seatingPlanId)
							{
								$seatingSettings->seatingRef = $paramVal;
							}
							else if ($paramId === "seatingDecodeTable".$seatingPlanId)
							{
								$seatingSettings->seatingDecodeTable = $paramVal;
							}
							else 
							{
								$seatingAndZone = explode("_", $paramId);
								if (count($seatingAndZone) > 1)
								{
									$zoneId = $seatingAndZone[1];
									if (StageShowLibMigratePHPClass::Safe_substr($paramId, 0, 7) === "zoneRef")
									{
										$seatingSettings->zoneRefs[$zoneId] = $paramVal;
									}
									else if (StageShowLibMigratePHPClass::Safe_substr($paramId, 0, 8) === "zoneSpec")
									{
										$seatingSettings->zoneSpecs[$zoneId] = $paramVal;
									}
									else if (StageShowLibMigratePHPClass::Safe_substr($paramId, 0, 14) === "zoneAllocSeats")
									{
										$seatingSettings->zoneAllocSeats[$zoneId] = $paramVal;
									}
								}
							}
						}
						$decodes = $myDBaseObj->LoadSeatingDecoder($seatingSettings->seatingDecodeTable);	
						$seatingTemplateObj = $myDBaseObj->GetLayoutTemplate($seatingSettings, $decodes, true);				
						
						$caption = "HTML Template";
					}
					else
					{						
						$layoutDetails = StageShowZonesDBaseClass::GetSeatingTemplate($myDBaseObj, $seatingPlanId);
						$seatingSettings = $layoutDetails[0];
						
						$seatingTemplateObj = new stdClass;
						$seatingTemplateObj->errMsg = '';
						$seatingTemplateObj->template = $seatingSettings->seatingTemplate;						
						$seatingTemplateObj->seatsInLayout = $seatingSettings->seatingSeats;
						$seatingTemplateObj->seatsInZone = Array();
						
						$caption = "Imported HTML Template";
					}					
					
					if ($seatingTemplateObj->errMsg == '')
					{
						$seatingTemplate = $myDBaseObj->AddLayoutWrapper($seatingTemplateObj->template);
					}
					else
					{
						$seatingTemplate = $seatingTemplateObj->errMsg;
					}

					$this->pageHTML  = "<br><h2>$caption for <strong>{$seatingSettings->seatingRef}</strong></h2><br>\n";
					$this->pageHTML .= '<div id="stageshow-boxoffice-layout-seatdef" class="stageshow-readonly" style="width:300px">&nbsp;</div>'."<br><br>\n";
					$this->pageHTML .= '<style> .stageshow-boxoffice-seats {border: 1px black solid;}</style>';
				
					$this->pageHTML .= StageShowLibMigratePHPClass::Safe_str_replace('stageshow_ClickSeat', 'stageshow_TestClickSeat', $seatingTemplate);

					$rtnVal = true;
					break;
				
				default:
					$rtnVal = false;
					break;
					
			}
				
			return $rtnVal;
		}

		function DoBulkPreAction($bulkAction, $recordId)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			// Reset error count etc. on first pass
			if (!isset($this->errorCount)) $this->errorCount = 0;
			if (!isset($this->blockCount)) $this->blockCount = 0;
			
			$results = $myDBaseObj->GetSeatingLayoutByID($recordId);
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:
					// FUNCTIONALITY: Seating Plans - Bulk Action Delete - Block if has performances					
					if (count($results) == 0)
						$this->errorCount++;
					else if (!$myDBaseObj->CanDeleteSeatingPlan($recordId))
						$this->blockCount++;
					return (($this->errorCount > 0) || ($this->blockCount > 0));

				case StageShowSeatingAdminListClass::BULKACTION_RELOAD:	
					// FUNCTIONALITY: Seating Plans - Bulk Action Reload - Block if has a custom import				
					if (count($results) == 0)
						$this->errorCount++;
					else if ($results[0]->seatingImportFile != '')
						$this->blockCount++;
					return (($this->errorCount > 0) || ($this->blockCount > 0));
			}
			
			return false;
		}
		
		function DoBulkAction($bulkAction, $recordId)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$listClassId = $this->myPluginObj->adminClassPrefix.'SeatingAdminListClass';
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:		
					$myDBaseObj->DeleteSeatingLayout($recordId);
					return true;

				case StageShowSeatingAdminListClass::BULKACTION_RELOAD:	
					$zoneDefs = $myDBaseObj->GetSeatingLayoutByID($recordId);
					$seatingSettings = new stdClass();
					$seatingSettings->seatingRef = $zoneDefs[0]->seatingRef;
					$seatingSettings->seatingVenue = $zoneDefs[0]->seatingVenue;
					$seatingSettings->seatingDecodeTable = $zoneDefs[0]->seatingDecodeTable;
					foreach ($zoneDefs as $zoneDef)
					{
						$seatingSettings->zoneRefs[] = $zoneDef->zoneRef;
						$seatingSettings->zoneSpecs[] = $zoneDef->zoneSpec;
						$seatingSettings->zoneAllocSeats[] = $zoneDef->zoneAllocSeats;
					}
					$layoutTemplateObj = $myDBaseObj->CreateLayoutTemplate($seatingSettings, $recordId);
					return true;
			}
				
			return parent::DoBulkAction($bulkAction, $recordId);
		}
		
		function GetBulkActionMsg($bulkAction, $actionCount)
		{
			$actionMsg = '';
			
			$listClassId = $this->myPluginObj->adminClassPrefix.'SeatingAdminListClass';
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:	
					if ($this->errorCount > 0)
						$actionMsg = $this->errorCount . ' ' . _n("Seating Plan does not exist in Database", "Seating Plans do not exist in Database", $this->errorCount, 'stageshow');
					else if ($this->blockCount > 0)
						$actionMsg = $this->blockCount . ' ' . _n("Seating Plan cannot be deleted", "Seating Plans cannot be deleted", $this->blockCount, 'stageshow').' - '.__("In use for one or more Performances!", 'stageshow');
					else if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Seating Plan has been deleted", "Seating Plans have been deleted", $actionCount, 'stageshow');
					else
						$actionMsg =  __("Nothing to Delete", 'stageshow');
					break;
					
				case StageShowSeatingAdminListClass::BULKACTION_RELOAD:	
					if ($this->errorCount > 0)
						$actionMsg = $this->errorCount . ' ' . _n("Seating Plan does not exist in Database", "Seating Plans do not exist in Database", $this->errorCount, 'stageshow');
					else if ($this->blockCount > 0)
						$actionMsg = $this->blockCount . ' ' . _n("Seating Plan cannot be reloaded", "Seating Plans cannot be reloaded", $this->blockCount, 'stageshow').' - '.__("In use for one or more Performances!", 'stageshow');
					else if ($actionCount > 0)
						$actionMsg = $actionCount . ' ' . _n("Seating Plan has been reloaded", "Seating Plans have been reloaded", $actionCount, 'stageshow');
					else
						$actionMsg =  __("Nothing to Reload", 'stageshow');
					break;

				default:
					$actionMsg = parent::GetBulkActionMsg($bulkAction, $actionCount);

			}
			
			return $actionMsg;
		}
		
	}

}



