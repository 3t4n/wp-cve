<?php
/* 
Description: Database class for ticket validation 
 
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

include STAGESHOW_INCLUDE_PATH.'stageshowlib_dbase_base.php';
include STAGESHOW_INCLUDE_PATH.'stageshowlib_gatewaybase.php';

include STAGESHOW_INCLUDE_PATH.'stageshow_dbase_zones.php';

	//define ('STAGESHOWLIB_DBASE_CLASS', 'StageShowValidateDBaseClass');
	
if (!class_exists('StageShowValidateDBaseClass')) 
{
	class StageShowValidateDBaseClass extends StageShowLibGenericDBaseClass
	{
		// Added class variables from StageShowValidateDBaseClass 
				
		function __construct()
		{
			parent::__construct();
			
			$this->adminOptions = $this->ReadSettings('stageshowsettings');
			$this->dbgOptions = $this->ReadSettings(STAGESHOW_DIR_NAME.'dbgsettings');
		}
		
		function ReadSettings($optionName)
		{
			$sql = "SELECT * FROM ".STAGESHOW_SETTINGS_TABLE." WHERE option_name='$optionName'";
			$rslt = $this->get_results($sql);
			if (count($rslt) == 0) 
			{
				return array();
			}

			$settings = $rslt[0]->option_value;
			$settings = unserialize($settings);				

			return $settings;
		}
		
		function get_domain()
		{
			// This function returns a default profile (for translations)
			return 'stageshow';
		}
		
		function GetLocation()
		{
			return '';
		}
		
		function CheckAdminReferer($referer = '')
		{
			return true;
		}
	
		function GetActivePerformancesFilter()
		{
			/*
			$timeNow = current_time('mysql');
			$sqlStarted = '('.STAGESHOW_PERFORMANCES_TABLE.'.perfExpires IS NULL AND '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime>"'.$timeNow.'")';
			$sqlExpired = '('.STAGESHOW_PERFORMANCES_TABLE.'.perfExpires>"'.$timeNow.'")';
			$sqlWhere = " WHERE ($sqlExpired OR $sqlStarted)";
			*/
			$timeNow = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, current_time('timestamp') - STAGESHOWLIB_ADMIN_EXPIRETIMEOFFSET);
			$sqlStarted = '('.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime>"'.$timeNow.'")';
			$sqlWhere = " WHERE ($sqlStarted)";
			return $sqlWhere;
		}	

		function GetActivePerformancesList()
		{
			$selectFields  = '*';
			$selectFields .= ','.STAGESHOW_PERFORMANCES_TABLE.'.perfID';
			
			$sql = "SELECT $selectFields FROM ".STAGESHOW_PERFORMANCES_TABLE;
			$sql .= " LEFT JOIN ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			
			// Add SQL filter(s)
			$sql .= $this->GetActivePerformancesFilter();
			
			$sql .= ' ORDER BY '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime';
			
			$perfsListArray = $this->get_results($sql);

			return $perfsListArray;
		}
				
		function GetPerformancesListByPerfID($perfID)
		{
			$sql  = 'SELECT showName, perfDateTime FROM '.STAGESHOW_PERFORMANCES_TABLE.' ';
			$sql .= 'JOIN '.STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID ';
			$sql .= 'WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.perfID="'.$perfID.'"';
				
			// Get results
			$perfEntry = $this->get_results($sql);	
					
			return $perfEntry;
		}

		function GetAllSalesListBySaleTxnId($ticketDetails)
		{			
			$sql  = 'SELECT * FROM '.STAGESHOW_SALES_TABLE.' ';
			$sql .= 'JOIN '.STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.saleID='.STAGESHOW_SALES_TABLE.'.saleID ';
			$sql .= 'JOIN '.STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceID='.STAGESHOW_TICKETS_TABLE.'.priceID ';
			$sql .= 'JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_PRICES_TABLE.'.perfID ';
			$sql .= 'JOIN '.STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID ';

			$sql .= 'LEFT JOIN '.STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.zoneID='.STAGESHOW_PRICES_TABLE.'.priceZoneID ';
			$sql .= 'WHERE '.STAGESHOW_SALES_TABLE.'.saleTxnId="'.$ticketDetails->txnId.'" ';
			$sql .= 'AND '.STAGESHOW_SALES_TABLE.'.saleStatus != "'.PAYMENT_API_SALESTATUS_SUSPENDED.'" ';
			$sql .= 'AND (('.STAGESHOW_PERFORMANCES_TABLE.'.perfState IS NULL) OR ('.STAGESHOW_PERFORMANCES_TABLE.'.perfState<>"deleted")) ';
			$sql .= 'AND '.STAGESHOW_SHOWS_TABLE.'.showState<>"deleted"';		
				
			// Get results
			$salesListArray = $this->get_results($sql);

			if (($ticketDetails->ticketNo > 0) && (count($salesListArray) > 0))
			{
				if ($ticketDetails->ticketNo > $salesListArray[0]->ticketQty)
				{
					// Ticket no is not valid - Fake a 'No matching record' error
					$salesListArray = array();
				}
			}
			
			return $salesListArray;
		}
		
		function LogVerify($ticketDetails)
		{
			$verifyDateTime = current_time('mysql');
			$verifyLocation = $this->GetLocation();

			$sql  = 'INSERT INTO '.STAGESHOW_VERIFYS_TABLE.'(saleID, verifyDateTime, verifyLocation, perfID, ticketID, ticketNo)';
			$sql .= ' VALUES('.$ticketDetails->saleID.', "'.$verifyDateTime.'", "'.esc_sql($verifyLocation).'", "'.$ticketDetails->perfID.'", "'.$ticketDetails->ticketID.'", "'.$ticketDetails->ticketNo.'")';
			$this->query($sql);	
					
			return $this->GetInsertId();
		}
		
		function GetVerifysList($ticketDetails)
		{
			$sql = $this->GetVerifysListSQL($ticketDetails);
			$salesListArray = $this->get_results($sql);
			
			return $salesListArray;
		}
		
		function GetVerifysListSQL($ticketDetails)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_VERIFYS_TABLE;	
			$sql .= ' LEFT JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_VERIFYS_TABLE.'.perfID';
			$sql .= ' JOIN '.STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= ' WHERE '.STAGESHOW_VERIFYS_TABLE.".saleID=$ticketDetails->saleID";	
			if ($ticketDetails->perfID > 0)
			{
				$sql .= ' AND ('.STAGESHOW_VERIFYS_TABLE.".perfID=$ticketDetails->perfID";
				$sql .= ' OR   '.STAGESHOW_VERIFYS_TABLE.".perfID=0)";
			}			
								
			if ($ticketDetails->ticketID > 0)
			{
				$sql .= ' AND ('.STAGESHOW_VERIFYS_TABLE.".ticketID=$ticketDetails->ticketID";
				$sql .= ' OR   '.STAGESHOW_VERIFYS_TABLE.".ticketID=0)";
			}
								
			if ($ticketDetails->ticketNo > 0)
			{
				$sql .= ' AND ('.STAGESHOW_VERIFYS_TABLE.".ticketNo=$ticketDetails->ticketNo";
				$sql .= ' OR   '.STAGESHOW_VERIFYS_TABLE.".ticketNo=0)";
			}
					
			$salesListArray = $this->get_results($sql);
			
			return $sql;
		}
		
	}
}

