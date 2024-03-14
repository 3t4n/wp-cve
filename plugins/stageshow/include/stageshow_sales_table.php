<?php
/* 
Description: Code for Sales Page
 
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

include 'stageshowlib_table.php';
include 'stageshowlib_gatewaysalesadmin.php';      

if (!class_exists('StageShowSalesAdminListClass')) 
{
	define('PAYMENT_API_SALEFILTER_CURRENT', 'Current');		
		
	class StageShowSalesAdminListClass extends StageShowLibGatewaySalesAdminListClass // Define class
	{		
		var	$salesList;
		
		const BULKACTION_COMPLETED = 'completed';
		
		function __construct($env, $editMode = false) //constructor
		{
			if ($editMode)
			{
			}
			
			// Call base constructor
			parent::__construct($env, $editMode);
			
			if (!current_user_can(STAGESHOWLIB_CAPABILITY_VALIDATEUSER)
			  && !current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER) )
			{
			  	$this->filterRowDefs = array();
			}

			StageShowLibAdminListClass::AddFilterRow($this->filterRowDefs, PAYMENT_API_SALEFILTER_CURRENT, __('Current Sales', 'stageshow'), __('Current', 'stageshow'));			
			$this->defaultFilterId = PAYMENT_API_SALEFILTER_CURRENT;
			
			if (!$editMode)
			{
				if (!current_user_can(STAGESHOWLIB_CAPABILITY_VALIDATEUSER)
				  && !current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER) )
				{
					unset($this->bulkActions);
				}
				else
				{
					$this->bulkActions = array_merge($this->bulkActions, array(
						self::BULKACTION_COMPLETED => __('Set Completed', 'stageshow'),
						));
				}
			}
		}
		
		function GetMainRowsDefinition()
		{
			if ($this->editMode) return array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale',       StageShowLibTableClass::TABLEPARAM_ID => 'edit', ),						
			);
							
			$columnDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Total Due', StageShowLibTableClass::TABLEPARAM_ID => 'soldValue', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', ),		
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Seats', StageShowLibTableClass::TABLEPARAM_ID => 'saleQty', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),		
			);
			
			return self::MergeSettings(parent::GetMainRowsDefinition(), $columnDefs);
		}		
			
		function GetDetailsRowsDefinition()
		{
			$ourOptions = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Extra Discount', StageShowLibTableClass::TABLEPARAM_ID => 'saleExtraDiscount',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', StageShowLibTableClass::TABLEPARAM_BEFORE => 'saleFee', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Discount Code', StageShowLibTableClass::TABLEPARAM_ID => 'saleDiscountCode', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_AFTER => 'saleExtraDiscount', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Booking Fee',    StageShowLibTableClass::TABLEPARAM_ID => 'saleTransactionFee', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', StageShowLibTableClass::TABLEPARAM_BEFORE => 'saleFee', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Donation',       StageShowLibTableClass::TABLEPARAM_ID => 'saleDonation',       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', StageShowLibTableClass::TABLEPARAM_BEFORE => 'saleFee', ),
			);

			$ourOptions = self::MergeSettings(parent::GetDetailsRowsDefinition(), $ourOptions);
			
			if ($this->myDBaseObj->isOptionSet('PostTicketsEnabled'))
			{
				$ourOptions = self::MergeSettings($ourOptions, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Postage', StageShowLibTableClass::TABLEPARAM_ID => 'salePostage', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatCurrency', StageShowLibTableClass::TABLEPARAM_AFTER => 'saleTransactionFee', ),
					)
				);				
			}
			
			return $ourOptions;
		}
		
		

		function GetFilterSQL($rowFilter)
		{	
			switch ($rowFilter)
			{
				case PAYMENT_API_SALEFILTER_CURRENT:
					$currTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT);
					$filterSQL = "NOT saleStatus = '".PAYMENT_API_SALESTATUS_CHECKOUT."' AND NOT saleStatus = '".PAYMENT_API_SALESTATUS_TIMEOUT."' AND maxPerfDateTime >= '".$currTime."'";
					break;
						
				default:
					$filterSQL = parent::GetFilterSQL($rowFilter);
			}

			if ($filterSQL == '') $filterSQL .= 'WHERE';
			else $filterSQL .= ' AND';
			$filterSQL .= " NOT saleStatus = '".PAYMENT_API_SALESTATUS_LOCKOUT."'";
			
			return $filterSQL;
		}
		
		function ShowSaleDetails($result, $salesList)
		{
			if (!$this->editMode)
			//if ((!$this->editMode) || ($result->saleID != NULL))
			{
				return parent::ShowSaleDetails($result, $salesList);
			}
			
			return $this->BuildSaleDetails($salesList);
		}
		
		function CreateSalesAdminDetailsListObject($env, $editMode = false)
		{
			return new StageShowSalesAdminDetailsListClass($env, $editMode);	
		}
		
		function NeedsConfirmation($bulkAction)
		{
			switch ($bulkAction)
			{
				case self::BULKACTION_COMPLETED:
					return true;
					
				default:
					return parent::NeedsConfirmation($bulkAction);
			}
		}
		
		function ExtendedSettingsDBOpts()
		{
			return parent::ExtendedSettingsDBOpts();
		}
		
		function GetStatusOptions()
		{
			$statusOptions = array(
				PAYMENT_API_SALESTATUS_RESERVED.'|'.__('Reserved', 'stageshow'),
				);
				
			return array_merge(parent::GetStatusOptions(), $statusOptions);
		}		
		
		function AddSaleListButtons($result)
		{
			$html  = parent::AddSaleListButtons($result);			
			if (current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER))
			{
				$html .= $this->myDBaseObj->ActionButtonHTML(__('Add Payment', 'stageshow'), $this->caller, 'stageshow', '', $result->saleID, 'addpayment');    				
				$html .= $this->myDBaseObj->ActionButtonHTML(__('Edit Sale', 'stageshow'), $this->caller, 'stageshow', '', $result->saleID, 'editsale');    				
			}

			if ($this->myDBaseObj->IsPrintingEnabled())
			{
				$printMsg = ($result->salePrinted == null) ? __('Print Sale', 'stageshow') : __('REPRINT Sale', 'stageshow');
				$html .= "&nbsp;&nbsp;".$this->myDBaseObj->ActionButtonHTML($printMsg, $this->caller, 'stageshow', '', $result->saleID, 'printsale');    				
			}

			return $html;
		}
/*		
		function CanShowBulkActions() 
		{	
			if (!current_user_can(STAGESHOWLIB_CAPABILITY_VALIDATEUSER)
			  && !current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER) )
			{
				return false;
			}
			
			return true;
		}
		
		function ShowBulkActions( $which = 'top' ) 
		{	
			if (!$this->CanShowBulkActions())
				return '';
				
			return parent::ShowBulkActions($which);
		}
*/	
		
		// Commented out Class Def (StageShowSalesAdminListClass)
	}
}

if (!class_exists('StageShowSalesAdminDetailsListClass')) 
{
	class StageShowSalesAdminDetailsListClass extends StageShowLibGatewaySalesDetailsAdminClass // Define class
	{		
		function __construct($env, $editMode = false) //constructor
		{
			$this->tabHeadClass = "stageshow_tl8";
			$this->allowHiddenTags = false;
			
			// Call base constructor
			parent::__construct($env, $editMode);
		}
		
		function GetTableID($result)
		{
			return "stageshow_saledetails_list";
		}
		
		function GetRecordID($result)
		{
			return $result->priceID;
		}
			
		function FormatTicketType($ticketTypeInDB, $result)
		{
			if ($result->priceZoneID > 0)
			{
				$myDBaseObj = $this->env['DBaseObj'];
				$zoneSpec = StageShowZonesDBaseClass::GetSeatingZone($myDBaseObj, $result->priceZoneID);
				
				return $ticketTypeInDB.' ('.$zoneSpec->zoneRef.')';
			}
			
			//return $ticketTypeInDB.' ('.$result->zoneRef.')';
			return $ticketTypeInDB;
		}
				
		function FormatTicketPrice($ticketTypeInDB, $result)
		{
			$price = $result->ticketPaid/$result->ticketQty;
			return $this->myDBaseObj->FormatCurrencyValue($price);
		}
		
		function GetMainRowsDefinition()
		{
			$rowDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Show',        StageShowLibTableClass::TABLEPARAM_ID => 'showName',     StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Performance', StageShowLibTableClass::TABLEPARAM_ID => 'perfDateTime', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatDateForAdminDisplay', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Type', StageShowLibTableClass::TABLEPARAM_ID => 'priceType',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatTicketType', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Price',       StageShowLibTableClass::TABLEPARAM_ID => 'ticketPaid',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'FormatTicketPrice', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Quantity',    StageShowLibTableClass::TABLEPARAM_ID => 'ticketQty',    StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT, StageShowLibTableClass::TABLEPARAM_LEN => 4, ),						
			);

			$seatsDef = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Seat', StageShowLibTableClass::TABLEPARAM_ID => 'ticketSeat', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, StageShowLibTableClass::TABLEPARAM_DECODE => 'DecodeSeats', StageShowLibTableClass::TABLEPARAM_AFTER => 'priceType', ),
			);
			$rowDefs = self::MergeSettings($rowDefs, $seatsDef);

			// Get Extra Fields
			if (isset($this->env['saleID']))
			{
				$myDBaseObj = $this->env['DBaseObj'];	
				$customFieldsList = $myDBaseObj->GetSaleMetaFields($this->env['saleID']);	
				foreach ($customFieldsList as $customFields)
				{
					$fieldID = $customFields->meta_key;
					$fieldName = StageShowLibMigratePHPClass::Safe_str_replace("_", " ", $fieldID);
					
					$fieldDef = array(StageShowLibTableClass::TABLEPARAM_LABEL => $fieldName, StageShowLibTableClass::TABLEPARAM_ID => $fieldID, StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW);
					$rowDefs[] = $fieldDef; 
				}				
			}

			return $rowDefs;
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
				
		// Commented out Class Def (StageShowSalesAdminDetailsListClass)
		function DecodeSeats($value, $result)
		{		
			if ($result->perfSeatingID != 0)
			{
				$myDBaseObj = $this->env['DBaseObj'];				
				$value = StageShowZonesDBaseClass::DecodeSeatsList($myDBaseObj, $value, $result->perfSeatingID);
				$value = StageShowZonesDBaseClass::SeatsToHTML($value);
			}
		
			return $value;
		}
		
	}
}

if (!class_exists('StageShowSalesAdminVerifyListClass')) 
{
	class StageShowSalesAdminVerifyListClass extends StageShowLibAdminListClass // Define class
	{		
		function __construct($env, $editMode = false) //constructor
		{
			$this->tabHeadClass = "stageshow_tl8";
			
			// Call base constructor
			parent::__construct($env, $editMode);
			
			$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_TOP;
		}
		
		function GetTableID($result)
		{
			return "stageshow_saleverify_list";
		}
		
		function GetRecordID($result)
		{
			return $result->verifyID;
		}
		
		function GetMainRowsDefinition()
		{
			return array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Location',               StageShowLibTableClass::TABLEPARAM_ID => 'verifyLocation',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Verified Date and Time', StageShowLibTableClass::TABLEPARAM_ID => 'verifyDateTime',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_VIEW, ),
			);
		}		
				
	}
}











