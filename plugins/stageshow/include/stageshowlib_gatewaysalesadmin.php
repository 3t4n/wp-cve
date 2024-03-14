<?php
/* 
Description: Code for Sales Admin Page
 
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

require_once 'stageshowlib_salesadmin.php';      
require_once 'stageshowlib_admin.php';      

if (!class_exists('StageShowLibGatewaySalesAdminListClass')) 
{
	class StageShowLibGatewaySalesAdminListClass extends StageShowLibSalesAdminListClass // Define class
	{	
		function __construct($env, $editMode /* = false */) //constructor
		{
			$this->allowHiddenTags = false;
			
			// Call base constructor
			parent::__construct($env, $editMode);
			
			if (!$this->editMode)
			{
				$this->hiddenRowsButtonId = __('Details', 'stageshow');		
			}
			else
			{
				$this->hiddenRowStyle = '';
				$this->hiddenRowsButtonId = '';
				$this->moreText = '';
			}			
			
			if (!$editMode)
			{
				$this->bulkActions = array(
					self::BULKACTION_DELETE => __('Delete', 'stageshow'),
					);
					
				$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_BOTH;
			}
			else
			{
				$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_TOP;
			}
			
			// Define filters ....
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, PAYMENT_API_SALEFILTER_ALL,       __('All Sales', 'stageshow'),       __('All', 'stageshow'));
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, PAYMENT_API_SALEFILTER_CHECKOUT,  __('Checkouts', 'stageshow'), __('Checkouts', 'stageshow'));	
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, PAYMENT_API_SALEFILTER_PENDING,   __('Pending Sales', 'stageshow'),   __('Pending', 'stageshow'));	
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, PAYMENT_API_SALEFILTER_SUSPENDED, __('Suspended Sales', 'stageshow'), __('Suspended', 'stageshow'));						
			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, PAYMENT_API_SALEFILTER_RESERVED,  __('Reserved', 'stageshow'), __('Reserved', 'stageshow'));	
			$this->defaultFilterId = PAYMENT_API_SALEFILTER_ALL;
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
			$results = $this->myDBaseObj->GetFilteredSalesList($sqlFilters);
		}

		function GetFilterSQL($rowFilter)
		{	
			switch ($rowFilter)
			{
				case PAYMENT_API_SALEFILTER_CHECKOUT:
					return "saleStatus = '".PAYMENT_API_SALESTATUS_CHECKOUT."'";
					
				case PAYMENT_API_SALEFILTER_PENDING:
					return "saleStatus = '".PAYMENT_API_SALESTATUS_CHECKOUT."' OR saleStatus = '".PAYMENT_API_SALESTATUS_TIMEOUT."'";
					
				case PAYMENT_API_SALEFILTER_SUSPENDED:
					return "saleStatus = '".PAYMENT_API_SALESTATUS_SUSPENDED."'";
						
				case PAYMENT_API_SALEFILTER_RESERVED:
					return "saleStatus = '".PAYMENT_API_SALESTATUS_RESERVED."'";
						
				default:
					return parent::GetFilterSQL($rowFilter);
			}

			return '';
		}

		function GetDBFilterCounts($sqlSelect)
		{
			// Get list of sales (one row per sale)
			$sqlFilters['sqlSelect'] = $sqlSelect;
			return $this->myDBaseObj->GetFilteredSalesList($sqlFilters);
		}
		
		function GetRecordID($result)
		{
			return $result->saleID;
		}
		
		function GetCurrentURL() 
		{			
			$currentURL = parent::GetCurrentURL();
			$this->myDBaseObj->AddSearchParam($currentURL);
			return $currentURL;
		}
		
		function DecodeSaleName($value, $result)
		{
			return $this->myDBaseObj->GetSaleName($result);
		}
		
		function GetMainRowsDefinition()
		{
			return array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Name',	            StageShowLibTableClass::TABLEPARAM_ID => 'saleLastName', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodeSaleName', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Transaction Date', StageShowLibTableClass::TABLEPARAM_ID => 'saleDateTime', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Status',           StageShowLibTableClass::TABLEPARAM_ID => 'saleStatus',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
			);
		}		
		
		function GetStatusOptions()
		{
			return array(
				PAYMENT_API_SALESTATUS_COMPLETED.'|'.__('Completed', 'stageshow'),
				);
		}	
		
		function FormatSaleNote($saleNote)
		{
			return StageShowLibMigratePHPClass::Safe_str_replace("\n", "<br>", $saleNote);
		}	
		
		function GetDetailsRowsDefinition()
		{
			// FUNCTIONALITY: Sales - Use Payment Gateway consts if defined
			$address = defined('PAYMENT_API_STREET_LABEL')  ? PAYMENT_API_STREET_LABEL  : __('Address', 'stageshow');
			$city    = defined('PAYMENT_API_CITY_LABEL')    ? PAYMENT_API_CITY_LABEL    : __('Town/City', 'stageshow');
			$state   = defined('PAYMENT_API_STATE_LABEL')   ? PAYMENT_API_STATE_LABEL   : __('County', 'stageshow');
			$zip     = defined('PAYMENT_API_ZIP_LABEL')     ? PAYMENT_API_ZIP_LABEL     : __('Postcode', 'stageshow');
			$country = defined('PAYMENT_API_COUNTRY_LABEL') ? PAYMENT_API_COUNTRY_LABEL : __('Country', 'stageshow');
			$phone   = defined('PAYMENT_API_PHONE_LABEL')   ? PAYMENT_API_PHONE_LABEL   : __('Phone', 'stageshow');
			
			$statusOptions = $this->GetStatusOptions();
			
			$gatewayName = $this->myDBaseObj->gatewayObj->GetName();
			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'First Name',	        StageShowLibTableClass::TABLEPARAM_ID => 'saleFirstName',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALENAME_TEXTLEN,      StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALENAME_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Last Name',	        StageShowLibTableClass::TABLEPARAM_ID => 'saleLastName',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALENAME_TEXTLEN,      StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALENAME_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'EMail',	            StageShowLibTableClass::TABLEPARAM_ID => 'saleEMail',       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALEEMAIL_TEXTLEN,     StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALEEMAIL_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => $address,	            StageShowLibTableClass::TABLEPARAM_ID => 'salePPStreet',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALEPPSTREET_TEXTLEN,  StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALEPPSTREET_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => $city,	                StageShowLibTableClass::TABLEPARAM_ID => 'salePPCity',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALEPPCITY_TEXTLEN,    StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALEPPCITY_EDITLEN, ),			
				array(StageShowLibTableClass::TABLEPARAM_LABEL => $state,	                StageShowLibTableClass::TABLEPARAM_ID => 'salePPState',     StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALEPPSTATE_TEXTLEN,   StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALEPPSTATE_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => $zip,                   StageShowLibTableClass::TABLEPARAM_ID => 'salePPZip',       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALEPPZIP_TEXTLEN,     StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALEPPZIP_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => $country,               StageShowLibTableClass::TABLEPARAM_ID => 'salePPCountry',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALEPPCOUNTRY_TEXTLEN, StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALEPPCOUNTRY_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => $phone,                 StageShowLibTableClass::TABLEPARAM_ID => 'salePPPhone',     StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALEPPPHONE_TEXTLEN,   StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALEPPPHONE_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'User Login',	        StageShowLibTableClass::TABLEPARAM_ID => 'user_login',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Total Due',            StageShowLibTableClass::TABLEPARAM_ID => 'soldValue',       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency'),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Payment Method',       StageShowLibTableClass::TABLEPARAM_ID => 'saleMethod',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => $gatewayName.' Fees',   StageShowLibTableClass::TABLEPARAM_ID => 'saleFee',         StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency'),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale Date & Time',     StageShowLibTableClass::TABLEPARAM_ID => 'saleDateTime',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Paid Date & Time',     StageShowLibTableClass::TABLEPARAM_ID => 'salePaidDateTime',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW),		
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale Reference',       StageShowLibTableClass::TABLEPARAM_ID => 'saleTxnId',       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW),						
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Status',               StageShowLibTableClass::TABLEPARAM_ID => 'saleStatus',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $statusOptions),						
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Note',                 StageShowLibTableClass::TABLEPARAM_ID => 'saleNoteToSeller',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatSaleNote'),
			);
			
			$ourOptions = self::MergeSettings(parent::GetDetailsRowsDefinition(), $ourOptions);
			
			if (!$this->editMode) 
			{
				$rowFilter = StageShowLibUtilsClass::GetHTTPAlphaNumericElem('get', 'filter'); 
				switch ($rowFilter)
				{
					//case PAYMENT_API_SALEFILTER_SUSPENDED:
					case PAYMENT_API_SALEFILTER_CHECKOUT:
					case PAYMENT_API_SALEFILTER_PENDING:
					case PAYMENT_API_SALEFILTER_ALL:
						break;
							
					default:
						// FUNCTIONALITY: Sales - Edit Sale
						$actions = array(
							array(StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_FUNCTION, StageShowLibTableClass::TABLEPARAM_FUNC => 'AddSaleListActions'),						
						);

						$ourOptions = self::MergeSettings($ourOptions, $actions);
						break;
				}

			}
						
			return $ourOptions;
		}
		
		function GetDetailsRowsFooter()
		{
			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_ID => 'saleDetails', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_FUNCTION, StageShowLibTableClass::TABLEPARAM_FUNC => 'ShowSaleDetails'),						
			);
			
			$ourOptions = self::MergeSettings(parent::GetDetailsRowsFooter(), $ourOptions);
			
			return $ourOptions;
		}
		
		function GetTableID($result)
		{
			$gatewayName = $this->myDBaseObj->gatewayObj->GetType();
			return $gatewayName."-sales-list-tab";
		}
		
		function CreateSalesAdminDetailsListObject($env, $editMode /* = false */)
		{
			return new StageShowLibGatewaySalesDetailsAdminClass($env, $editMode);	
		}
		
		function CreatePaymentsAdminDetailsListObject($env, $editMode = false)
		{
			return new StageShowLibGatewayPaymentsDetailsAdminClass($env, $editMode);	
		}
		
		function ShowSaleDetails($result, $saleResults)
		{
			if ($this->editMode) 
			{
				return '';
			}
			
			$myDBaseObj = $this->myDBaseObj;
			return $this->BuildSaleDetails($saleResults);
		}
				
		function GetListDetails($result)
		{
			if (isset($this->pricesList)) 
				return $this->pricesList;
			
			return $this->myDBaseObj->GetSaleDetails($result->saleID);
		}
		
		function BuildSaleDetails($saleResults)
		{
			$env = $this->env;
			$env['saleID'] = $saleID = $saleResults[0]->saleID;
			$salesList = $this->CreateSalesAdminDetailsListObject($env, $this->editMode);	
			$paymentsList = $this->CreatePaymentsAdminDetailsListObject($env, $this->editMode);	
			
			$paymentEntries = $this->myDBaseObj->GetPayments($saleID);
			
			// Set Rows per page to disable paging used on main page
			$salesList->enableFilter = false;
			$paymentsList->enableFilter = false;
			
			ob_start();	
			$salesList->OutputList($saleResults);	
			$paymentsList->OutputList($paymentEntries);	
			$saleDetailsOoutput = ob_get_contents();
			ob_end_clean();

			return $saleDetailsOoutput;
		}
		
		function OutputEditSale($editSaleEntry, $pricesList)
		{			
			$this->pricesList = $pricesList;
			$this->OutputList($editSaleEntry);
		}
		
		function AddSaleListActions($result)
		{
			$html  = '<div class="edit-entry-button">';
			$html .= $this->AddSaleListButtons($result);
			$html .= '</div>';
			return $html;
		}
		
		function AddSaleListButtons($result)
		{
			ob_start();
			$this->myDBaseObj->OutputViewTicketButton($result);
			$pluginObj = $this->env['PluginObj'];
			$pluginObj->OnlineStore_EMailSaleButton($result);
			$html .= ob_get_contents();
			ob_end_clean();
						
			return $html;
		}
		
	}
}

if (!class_exists('StageShowLibGatewaySalesDetailsAdminClass')) 
{
	class StageShowLibGatewaySalesDetailsAdminClass extends StageShowLibSalesAdminListClass // Define class
	{		
		function __construct($env, $editMode /* = false */) //constructor
		{
			// Call base constructor
			parent::__construct($env, $editMode);
			
			$this->SetRowsPerPage(self::STAGESHOWLIB_EVENTS_UNPAGED);
			
			$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_TOP;
		}
			
		function GetTableID($result)
		{
			return "paypal-sale-details-tab";
		}
		
		function GetRecordID($result)
		{
			return $result->saleID;
		}
		
		function GetMainRowsDefinition()
		{
			// FUNCTIONALITY: Sales - List Item, Type, Price and Quantity
			return array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Item',     StageShowLibTableClass::TABLEPARAM_ID => 'saleShowName', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Type',     StageShowLibTableClass::TABLEPARAM_ID => 'ticketType',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Price',    StageShowLibTableClass::TABLEPARAM_ID => 'price',        StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),						
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Quantity', StageShowLibTableClass::TABLEPARAM_ID => 'quantity',     StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, ),						
			);
		}		
				
	}
}

if (!class_exists('StageShowLibGatewaySalesAdminClass')) 
{
	class StageShowLibGatewaySalesAdminClass extends StageShowLibAdminClass // Define class
	{		
		var $results;
		var $saleQtyInputID;
		
		function __construct($env) //constructor	
		{
			$this->pageTitle = 'Sales';
			
			// TODO - Check this .....
			if (isset($env['saleQtyInputID'])) 
				$this->saleQtyInputID = $env['saleQtyInputID'];
			else
				$this->saleQtyInputID = 'editSaleQty';

			// Call base constructor
			parent::__construct($env);
		}
		
		function GetSearchParams()
		{
			$searchsalestext = '';
			if (StageShowLibUtilsClass::IsElementSet('post', 'searchsalesbutton') && ($_POST['searchsalesbutton'] != ''))	
			{
				$searchsalestext = StageShowLibUtilsClass::GetHTTPTextElem('post', 'searchsalestext'); 
				$_REQUEST['paged']=$_POST['paged']=$_GET['paged'] = 1;	// Reset the page
			}
			else if (StageShowLibUtilsClass::IsElementSet('get', 'lastsalessearch'))
			{
				$searchsalestext = StageShowLibUtilsClass::GetHTTPTextElem('get', 'lastsalessearch'); 
			}
			else
			{
				return;
			}

			// Search sales records
			$this->CheckAdminReferer();
				
			if ($searchsalestext != '')
			{
				$this->myDBaseObj->GetSearchSQL($searchsalestext, array('saleEMail', 'saleFirstName', 'saleLastName'));			
			}
		}
		
		function ProcessActionButtons()
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj = $this->myDBaseObj;				
				
			$this->salesFor = '';
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'emailsale'))
			{
				$this->CheckAdminReferer();
				
				$this->emailSaleId = StageShowLibHTTPIO::GetRequestedInt('id');
				$myDBaseObj->EMailSale($this->emailSaleId);
			}
			
			if (StageShowLibUtilsClass::IsElementSet('get', 'action'))
			{
				$this->CheckAdminReferer();
				$this->DoActions();
			}

			$this->pageTitle = $this->salesFor . __('Sales', 'stageshow');			
		}
		
		function Output_MainPage($updateFailed)
		{
			if ($this->editingRecord)
			{
				// Sale Editor ... output tickets selector
				$pluginObj = $this->env['PluginObj'];
				StageShowLibEscapingClass::Safe_EchoHTML($pluginObj->OutputContent_DoShortcode(NULL, true));
				return '';
			}
			
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj = $this->myDBaseObj;				
	
			$myDBaseObj->PurgePendingSales();
	
			// HTML Output - Start 
			$formClass = 'stageshow'.'-admin-form '.'stageshow'.'-sales-summary';
			StageShowLibEscapingClass::Safe_EchoHTML('<div class="'.$formClass.'">'."\n");

			if (!$this->editingRecord)
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<h3>'); 
				if ( isset($myDBaseObj->searchText) )
					StageShowLibEscapingClass::Safe_EchoHTML(__('Search Results', 'stageshow').' - '.$myDBaseObj->searchText); 
				else
					_e('Summary', 'stageshow'); 
				StageShowLibEscapingClass::Safe_EchoHTML("</h3>"); 
			}
			else if (!isset($this->saleId))
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<h3>".__('Add Sale', 'stageshow')."</h3>"); 
			}
					
			$actionURL = StageShowLibUtilsClass::GetStrippedPageURL();	
			StageShowLibEscapingClass::Safe_EchoHTML("<form method=\"post\" action=\"$actionURL\" >\n");

			if (!$this->editingRecord)
			{
				$this->OuputSearchButton();
			}
			
			if (isset($this->saleId))
				StageShowLibEscapingClass::Safe_EchoHTML("\n".'<input type="hidden" name="saleID" value="'.$this->saleId.'"/>'."\n");
				
			$this->WPNonceField();
				 
			if ($this->editingRecord)
			{
				// TODO - SSG - Is this section redundant?
				$this->editSaleEntry[0]->totalQty = '';	
				
				$classId = $myPluginObj->adminClassPrefix.'SalesAdminListClass';
				$salesList = new $classId($this->env, StageShowLibAdminListClass::EDITMODE);	// xxxxxxxxSalesAdminListClass etc.
				
				$salesList->OutputEditSale($this->editSaleEntry, $this->pricesList);
			}
			else if($this->OutputSalesList($this->env) == 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<div class='noconfig'>".__('No Sales', 'stageshow')."</div>\n");
			}

			$pricesCount = $myDBaseObj->GetActivePricesCount();
			if ($pricesCount > 0)
			{
				$this->OuputAddSaleButton();
			}			
			else
			{
				StageShowLibEscapingClass::Safe_EchoHTML($this->NoStockMessage());				
			}

?>
	<br></br>
	</form>
	</div>
<?php
		} // End of function Output_MainPage()
		
		function NoStockMessage()
		{
			return 'No Stock';
		}
		
		function OuputAddSaleButton()
		{
			$myDBaseObj = $this->myDBaseObj;
			StageShowLibEscapingClass::Safe_EchoHTML($myDBaseObj->ActionButtonHTML('Add Sale', '', 'stageshow', 'edit-entry-button', 0, 'editsale'));    
		}
		
		function DoActions()
		{
			$rtnVal = false;

			switch ($_GET['action'])
			{
				default:
					break;					
			}
				
			return $rtnVal;
		}
		
		function DoBulkPreAction($bulkAction, $recordId)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			// Reset error count etc. on first pass
			if (!isset($this->errorCount)) $this->errorCount = 0;
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:
					// FUNCTIONALITY: Price Plans - Bulk Action Delete - Check Plan Exists
					$saleResults = $this->myDBaseObj->GetSale($recordId);
					if (count($saleResults) == 0)
						$this->errorCount++;
					return ($this->errorCount > 0);
					
				default:
					break;
			}
			
			return false;
		}
		
		function DoBulkAction($bulkAction, $recordId)
		{
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:		
					$this->myDBaseObj->DeleteSale($recordId);
					return true;
					
				default:
					break;
			}
				
			return false;
		}
		
		function GetBulkActionMsg($bulkAction, $actionCount)
		{
			$actionMsg = '';
			
			switch ($bulkAction)
			{
				case StageShowLibAdminListClass::BULKACTION_DELETE:		
					if ($this->errorCount > 0)
						$actionMsg = $this->errorCount . ' ' . _n("Sale Entry does not exist in Database", "Sale Entries do not exist in Database", $this->errorCount, 'stageshow');
					else if ($actionCount > 0)		
						$actionMsg = $actionCount . ' ' . _n("Sale has been deleted", "Sales have been deleted", $actionCount, 'stageshow');
					else
						$actionMsg =  __("Nothing to Delete", 'stageshow');
					break;
					
				default:
					break;
			}
			
			return $actionMsg;
		}
		
		function OutputSalesList($env)
		{
			$myPluginObj = $this->myPluginObj;
						
			$classId = $myPluginObj->adminClassPrefix.'SalesAdminListClass';
			$salesList = new $classId($env);	// StageShowLibGatewayBaseClassxxxxSalesAdminListClass etc.
			$extraTableClass = 'stageshow'.'-sales-list-table';
			$salesList->tableTags = StageShowLibMigratePHPClass::Safe_str_replace(' widefat', ' '.$extraTableClass.' widefat', $salesList->tableTags);
			
			return $salesList->OutputList($this->results);		
		}
				
	}
} 
		 
if (!class_exists('StageShowLibGatewayPaymentsDetailsAdminClass')) 
{
	class StageShowLibGatewayPaymentsDetailsAdminClass extends StageShowLibSalesAdminListClass // Define class
	{		
		function __construct($env, $editMode = false) //constructor
		{
			// Call base constructor
			parent::__construct($env, $editMode);
			
			$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_TOP;
			$this->allowHiddenTags = false;
		}
		
		function GetTableID($result)
		{
			return "stageshowlib_payments_list";
		}
		
		function GetRecordID($result)
		{
			return $result->paymentID;
		}
			
		function FormatPaymentType($unused, $result)
		{
			return $this->myDBaseObj->FormatPaymentType($result->paymentType);
		}
			
		function FormatPayment($priceInDB, $result)
		{
			return $this->FormatCurrency($priceInDB);
		}
		
		function GetMainRowsDefinition()
		{
			return array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Payment Date', StageShowLibTableClass::TABLEPARAM_ID => 'paymentDateTime',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatDateForAdminDisplay', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Method',       StageShowLibTableClass::TABLEPARAM_ID => 'paymentMethod',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Paid',         StageShowLibTableClass::TABLEPARAM_ID => 'paymentPaid',      StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatPayment', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Fee',          StageShowLibTableClass::TABLEPARAM_ID => 'paymentFee',       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatPayment', ),
			);
		}
		
		function IsRowInView($result, $rowFilter)
		{
			if (!$this->editMode)
			{
				if ($result->ticketQty == 0)
				{
					// Only show rows that have non-zero quantity
					return false;
				}
			}
			
			return true;
		}		
/*				
		function GetListDetails($result)
		{
			return $this->myDBaseObj->GetPayments($result->saleID);
		}
*/		
	}
}





