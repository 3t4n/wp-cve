<?php
/* 
Description: StageShow Plugin Database Access functions
 
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

if (!class_exists('StageShowWPCartDBaseParentClass'))
{
	if (!defined('STAGESHOWLIB_DATABASE_FULL'))
	{
		if (!class_exists('StageShowLibSalesCartDBaseClass')) 
			include STAGESHOW_INCLUDE_PATH.'stageshowlib_sales_trolley_dbase_api.php';
		
		class StageShowWPCartDBaseParentClass extends StageShowLibSalesCartDBaseClass {}
	}
	else
	{
		if (!class_exists('StageShowLibSalesDBaseClass')) 
			include STAGESHOW_INCLUDE_PATH.'stageshowlib_sales_dbase_api.php';
		
		class StageShowWPCartDBaseParentClass extends StageShowLibSalesDBaseClass {}
	}	
}

if (!defined('STAGESHOW_ACTIVATE_RESERVEEMAIL_TEMPLATE_PATH'))
	define('STAGESHOW_ACTIVATE_RESERVEEMAIL_TEMPLATE_PATH', 'stageshow_ReserveEMail.php');
	
if (!defined('STAGESHOW_ACTIVATE_EMAILSUMMARY_TEMPLATE_PATH'))
	define('STAGESHOW_ACTIVATE_EMAILSUMMARY_TEMPLATE_PATH', 'stageshow_SummaryEMail.php');
	
if (!defined('STAGESHOW_ACTIVATE_CLIENTDBEMAIL_TEMPLATE_PATH'))
	define('STAGESHOW_ACTIVATE_CLIENTDBEMAIL_TEMPLATE_PATH', 'stageshow_HTMLEMail_ClientRecords.php');
	
include STAGESHOW_INCLUDE_PATH.'stageshow_dbase_zones.php';

if (!class_exists('StageShowCartDBaseClass')) 
{
	// Set the DB tables names
	global $wpdb;
	
	$dbPrefix = $wpdb->prefix;
	$dbPrefix .= 'sshow_';		
	
	if (defined('STAGESHOW_DATETIME_BOXOFFICE_FORMAT') && !defined('STAGESHOWLIB_DATETIME_BOXOFFICE_FORMAT'))
	{
		define('STAGESHOWLIB_DATETIME_BOXOFFICE_FORMAT',STAGESHOW_DATETIME_BOXOFFICE_FORMAT);
	}

	if (!defined('STAGESHOW_TABLE_PREFIX'))
	{
		define('STAGESHOW_TABLE_PREFIX', $dbPrefix);
		
		define('STAGESHOW_SETTINGS_TABLE', STAGESHOW_TABLE_PREFIX.'settings');
		
		define('STAGESHOW_SHOWS_TABLE', STAGESHOW_TABLE_PREFIX.'shows');
		define('STAGESHOW_PERFORMANCES_TABLE', STAGESHOW_TABLE_PREFIX.'perfs');
		define('STAGESHOW_PRICES_TABLE', STAGESHOW_TABLE_PREFIX.'prices');
		define('STAGESHOW_SALES_TABLE', STAGESHOW_TABLE_PREFIX.'sales');
		define('STAGESHOW_PAYMENTS_TABLE', STAGESHOW_TABLE_PREFIX.'payments');
		define('STAGESHOW_TICKETS_TABLE', STAGESHOW_TABLE_PREFIX.'tickets');
		
		define('STAGESHOW_SESSIONS_TABLE', STAGESHOW_TABLE_PREFIX.'sessions');
	}
	
	if( !defined( 'STAGESHOW_DATETIME_TEXTLEN' ) )
	{
		define('STAGESHOW_DATETIME_TEXTLEN', 19);
		
		if( !defined( 'STAGESHOW_FILENAME_TEXTLEN' ) )
			define('STAGESHOW_FILENAME_TEXTLEN', 80);
		
		if( !defined( 'STAGESHOW_SHOWNAME_TEXTLEN' ) )
			define('STAGESHOW_SHOWNAME_TEXTLEN', 80);
		if( !defined( 'STAGESHOW_PERFREF_TEXTLEN' ) )
			define('STAGESHOW_PERFREF_TEXTLEN', 16);
		if( !defined( 'STAGESHOW_PRICETYPE_TEXTLEN' ) )
			define('STAGESHOW_PRICETYPE_TEXTLEN', 20);
		define('STAGESHOW_PRICEVISIBILITY_TEXTLEN', 10);	
		define('STAGESHOW_TICKETNAME_TEXTLEN', 110);
		define('STAGESHOW_TICKETTYPE_TEXTLEN', 32);
		define('STAGESHOW_TICKETSEAT_TEXTLEN', 10);
			
		define('STAGESHOW_PPLOGIN_USER_TEXTLEN', 127);
		define('STAGESHOW_PPLOGIN_PWD_TEXTLEN', 65);
		define('STAGESHOW_PPLOGIN_SIG_TEXTLEN', 65);
		define('STAGESHOW_PPLOGIN_EMAIL_TEXTLEN', 65);
		
		define('STAGESHOW_PPLOGIN_EDITLEN', 70);
		
		define('STAGESHOW_PPBUTTONID_TEXTLEN',16);
		define('STAGESHOW_ACTIVESTATE_TEXTLEN',10);

		define('STAGESHOW_TICKETNAME_DIVIDER', ' - ');

		define('STAGESHOW_STATE_ACTIVE', 'activate');
		define('STAGESHOW_STATE_INACTIVE', 'deactivate');
		define('STAGESHOW_STATE_DELETED', 'deleted');

		define('STAGESHOW_VISIBILITY_PUBLIC', 'public');
	}
	
	if (!defined('STAGESHOW_PLANS_TABLE'))
	{
		// Set the DB tables names
		define('STAGESHOW_PLANS_TABLE', STAGESHOW_TABLE_PREFIX.'plans');
		define('STAGESHOW_PRESETS_TABLE', STAGESHOW_TABLE_PREFIX.'presets');
		define('STAGESHOW_VERIFYS_TABLE', STAGESHOW_TABLE_PREFIX.'verifys');
	}	
	
	if (!defined('STAGESHOW_CUSTOM_STYLESHEET'))
	{
		define('STAGESHOW_CUSTOM_STYLESHEET', 'stageshow-custom.css');
		define('STAGESHOW_SAMPLE_STYLESHEET', 'stageshow-samples.css');
		define('STAGESHOW_CUSTOM_JAVASCRIPT', 'stageshow-custom.js');
		
		if( !defined( 'STAGESHOW_PLANREF_TEXTLEN' ) )
			define('STAGESHOW_PLANREF_TEXTLEN', 20);
		define('STAGESHOW_LOCATION_TEXTLEN', 32);
			
		if (!defined('STAGESHOW_BARCODE_IDENTIFIER'))
			define('STAGESHOW_BARCODE_IDENTIFIER', 'SaleBarcode.');
	}
	
	define('STAGESHOW_RESERVATIONSMODE_DISABLED', 'Disabled');
	define('STAGESHOW_RESERVATIONSMODE_LOGIN', 'Login');
	define('STAGESHOW_RESERVATIONSMODE_FORM',  'Form');
	define('STAGESHOW_RESERVATIONSMODE_LOGINFORM',  'LoginForm');
/*
	define('STAGESHOWLIB_MIMEENCODING_PHPMAILER', 'PHPMailer');
	define('STAGESHOWLIB_MIMEENCODING_PLUGIN', 'Plugin');
*/	
	define('STAGESHOW_VISIBILITY_ADMIN', 'admin');
	define('STAGESHOW_VISIBILITY_DISABLED', 'none');
	
	define('STAGESHOW_DATETIMEOPTION_ALL', 'DateAndTime');
	//define('STAGESHOW_DATETIMEOPTION_DATE', 'DateOnly');
	define('STAGESHOW_DATETIMEOPTION_NONE', 'NoDate');
	
	define('STAGESHOW_CARTBUTTONS_NORMAL', 'normal');
	define('STAGESHOW_CARTBUTTONS_CHECKOUTONLY', 'checkout');
	define('STAGESHOW_CARTBUTTONS_RESERVEONLY', 'reserve');

	if (!defined( 'STAGESHOW_SEATING_TABLE'))
	{
		// Set the DB tables names
		define('STAGESHOW_DISCODES_TABLE', STAGESHOW_TABLE_PREFIX.'discodes');	
		define('STAGESHOW_DISPRESET_TABLE', STAGESHOW_TABLE_PREFIX.'dispresets');
		define('STAGESHOW_DISPRICES_TABLE', STAGESHOW_TABLE_PREFIX.'disprices');
		define('STAGESHOW_SEATING_TABLE', STAGESHOW_TABLE_PREFIX.'seating');
		define('STAGESHOW_ZONES_TABLE', STAGESHOW_TABLE_PREFIX.'zones');
		define('STAGESHOW_TICKETSMETA_TABLE', STAGESHOW_TABLE_PREFIX.'ticketsmeta');		
	}

	if (!defined( 'STAGESHOW_SPOOLER_TABLE'))
	{
		define('STAGESHOW_SPOOLER_TABLE', STAGESHOW_TABLE_PREFIX.'spooler');
	}
	
	if (!defined( 'STAGESHOW_SEATINGPATH_TEXTLEN' ) )
	{
		if( !defined( 'STAGESHOW_DISCOUNTREF_TEXTLEN' ) )
			define('STAGESHOW_DISCOUNTREF_TEXTLEN', 20);
		if( !defined( 'STAGESHOW_DISCOUNTCODE_TEXTLEN' ) )
			define('STAGESHOW_DISCOUNTCODE_TEXTLEN', 20);
		if( !defined( 'STAGESHOW_SEATINGREF_TEXTLEN' ) )
			define('STAGESHOW_SEATINGREF_TEXTLEN', 20);
		if( !defined( 'STAGESHOW_VENUENAME_TEXTLEN' ) )
			define('STAGESHOW_VENUENAME_TEXTLEN', 30);
		define('STAGESHOW_SEATINGPATH_TEXTLEN', 255);
		if( !defined( 'STAGESHOW_ZONEREF_TEXTLEN' ) )
			define('STAGESHOW_ZONEREF_TEXTLEN', 20);
		define('STAGESHOW_IP_TEXTLEN', 48);	
		
		define('STAGESHOW_ZONESPEC_EDITLEN', 50);	
		
		define('STAGESHOW_ZONESPEC_RANGESEP', '-');
		define('STAGESHOW_ZONESPEC_SEATSEP', '.');		
		define('STAGESHOW_ZONESPEC_ADDHALF', '+');		
	}

	define('STAGESHOW_TYPE_SYSTEM', 'System');
	
	define('STAGESHOW_VISIBILITY_LOCKOUT', 'lockout');
	
	if (!defined('STAGESHOW_CAPABILITY_BOOKLOCKOUT'))
	{
		define('STAGESHOW_CAPABILITY_BOOKLOCKOUT', STAGESHOWLIB_CAPABILITY_SALESUSER);		
	}
	
	class StageShowCartDBaseClass extends StageShowWPCartDBaseParentClass // Define class
  	{
		const STAGESHOW_DATE_FORMAT = 'Y-m-d';
		
		var $perfJoined = false;
		
		var $sshow_update;
		var $MailImages = array();
		
		var $disID = 0;
		var $disCode = '';
		var $disEnabled = false;
		
		function __construct($caller) //constructor	
		{
			if (defined('STAGESHOW_SEATSSELECTEDBUTTON_URL'))
				$this->buttonImageURLs['seatsselected'] = STAGESHOW_SEATSSELECTEDBUTTON_URL;
			if (defined('STAGESHOW_SELECTSEATSBUTTON_URL'))
				$this->buttonImageURLs['selectseats'] = STAGESHOW_SELECTSEATSBUTTON_URL;
			if (defined('STAGESHOW_SHOWAVAILABLEBUTTON_URL'))
				$this->buttonImageURLs['seatsavailable'] = STAGESHOW_SHOWAVAILABLEBUTTON_URL;
			if (defined('STAGESHOW_UPDATEPRICESBUTTON_URL'))
				$this->buttonImageURLs['updateprices'] = STAGESHOW_UPDATEPRICESBUTTON_URL;
				
			if (defined('STAGESHOW_SELECTSHOWBUTTON_URL'))
				$this->buttonImageURLs['selectshow'] = STAGESHOW_SELECTSHOWBUTTON_URL;
			if (defined('STAGESHOW_SELECTPERFBUTTON_URL'))
				$this->buttonImageURLs['selectperf'] = STAGESHOW_SELECTPERFBUTTON_URL;
				
			// TODO - Move PayPal-Express defines to Gateway Code
			if (defined('STAGESHOW_CONFIRMANDPAYBUTTON_URL'))
				$this->buttonImageURLs['confirmandpay'] = STAGESHOW_CONFIRMANDPAYBUTTON_URL;
			if (defined('STAGESHOW_INCONTEXTPAYBUTTON_URL'))
				$this->buttonImageURLs['incontextpay'] = STAGESHOW_INCONTEXTPAYBUTTON_URL;
			if (defined('STAGESHOW_PAYPALEXPRESSBUTTON_URL'))
				$this->buttonImageURLs['checkout2'] = STAGESHOW_PAYPALEXPRESSBUTTON_URL;
									
			if (defined('STAGESHOW_RESERVEBUTTON_URL'))
				$this->buttonImageURLs['reserve'] = STAGESHOW_RESERVEBUTTON_URL;
			
			$stageshowDbgoptionsName = STAGESHOW_DIR_NAME.'dbgsettings';
			
			// Options DB Field - In DEMO Mode make unique for each user, and Plugin type
			$stageshowOptionsName = 'stageshowsettings';
			
			$opts = array (
				'Caller'             => $caller,
				'PluginFolder'       => STAGESHOW_FOLDER,
				'CfgOptionsID'       => $stageshowOptionsName,
				'DbgOptionsID'       => $stageshowDbgoptionsName,
			);			

			if (defined('STAGESHOW_ADDBUTTON_URL'))
				$this->buttonImageURLs['add'] = STAGESHOW_ADDBUTTON_URL;
			if (defined('STAGESHOW_CHECKOUTBUTTON_URL'))
				$this->buttonImageURLs['checkout'] = STAGESHOW_CHECKOUTBUTTON_URL;
			if (defined('STAGESHOW_REMOVEBUTTON_URL'))
				$this->buttonImageURLs['remove'] = STAGESHOW_REMOVEBUTTON_URL;
			if (defined('STAGESHOW_CLOSEBUTTON_URL'))
				$this->buttonImageURLs['closewindow'] = STAGESHOW_CLOSEBUTTON_URL;
				
			// Call base constructor
			parent::__construct($opts);

		}
		
		function GatewayHasEMailName()
		{
			return true;
		}
		
		function getTablePrefix()
		{
			return STAGESHOW_TABLE_PREFIX;
		}
		
		function getTableNames($dbPrefix)
		{
			$DBTables = parent::getTableNames($dbPrefix);
			
			$DBTables->Settings = STAGESHOW_SETTINGS_TABLE;		
			$DBTables->Orders = STAGESHOW_TICKETS_TABLE;
			
			return $DBTables;
		}

		function init()
		{
			// Call init() in base class
			// This function should be called by the 'init' action of the Plugin
			// Action requiring setting of Cookies should be done here
		}

		
		function get_domain()
		{
			// This function returns the domain id (for translations) 
			// The domain is the same for all stageshow derivatives
			return 'stageshow';
		}
		
		function DBField($fieldName)
		{
			switch($fieldName)
			{
				case 'orderDetail':	return 'ticketSeat';
				case 'stockID':	    return 'priceID';
				case 'orderQty':	return 'ticketQty';
				case 'orderPaid':	return 'ticketPaid';
				default:			return $fieldName;
			}
		}
		
		function InTestMode()
		{
			if (!$this->IsSessionElemSet('stageshowlib_debug_test')) return false;
		
			if (!file_exists(STAGESHOW_TEST_PATH.'stageshow_testsettings.php')) return false;
	
			if (!function_exists('wp_get_current_user')) return false;
			
			return current_user_can(STAGESHOWLIB_CAPABILITY_DEVUSER);
		}
		
		function IsStateActive($state)
		{
			switch ($state)
			{
				case STAGESHOW_STATE_INACTIVE:
				case STAGESHOW_STATE_DELETED:
					return false;
					
				case STAGESHOW_STATE_ACTIVE:
				case 'Active':
				case '':
					return true;
			}
			
			return false;
		}

		function GetActiveShowsList()
		{
			if (!current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER))
			{
				$timeNow = current_time('mysql');
			}
			else
			{
				$timeNow = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, current_time('timestamp') - STAGESHOWLIB_ADMIN_EXPIRETIMEOFFSET);
			}							
		
			$selectFields  = STAGESHOW_SHOWS_TABLE.'.*';
			$selectFields .= ', MAX(perfDateTime) AS maxPerfDateTime';

			$sqlFilters['groupBy'] = 'showID';
			$sqlFilters['JoinType'] = 'RIGHT JOIN';
			$sqlFilters['showState'] = STAGESHOW_STATE_ACTIVE;
			$sqlFilters['perfState'] = STAGESHOW_STATE_ACTIVE;
			$sqlFilters['inListing']	= true;
			
			$this->showJoined = true;
			$this->perfJoined = true;

			$sql  = "SELECT $selectFields FROM ".STAGESHOW_PERFORMANCES_TABLE;
			$sql .= " JOIN ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			
			// Add SQL filter(s)
			$sql .= $this->GetWhereSQL($sqlFilters);
			$sql .= ' AND perfDateTime>"'.$timeNow.'" ';
			$sql .= $this->GetOptsSQL($sqlFilters);
			
			$sql .= ' ORDER BY maxPerfDateTime';
			
			$results = $this->get_results($sql, true, $sqlFilters);

			return $results;
		}
				
		function GetShowID($showName)
		{
			if ($showName == '')
			{
				$showID = 0;
			}
			else if (is_numeric($showName))
			{
				$showID = $showName;
			}
			else 
			{				
				$sql  = 'SELECT '.STAGESHOW_SHOWS_TABLE.'.showID, showName FROM '.STAGESHOW_SHOWS_TABLE;
				if (StageShowLibMigratePHPClass::Safe_substr($showName, -1) === '*')
				{
					$timeNow = current_time('mysql');
					$sql .= " RIGHT JOIN ".STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
					$wild = '%';
 					$showName = StageShowLibMigratePHPClass::Safe_substr($showName, 0, -1);
					$sql .= ' WHERE '.STAGESHOW_SHOWS_TABLE.'.showName LIKE %s';
					$showName = $wild . $this->esc_like( $showName ) . $wild;
					$sql .= ' AND perfDateTime>"'.$timeNow.'" ';
					$sql .= ' ORDER BY perfDateTime ASC LIMIT 1 ';
				}
				else
				{
					$sql .= ' WHERE '.STAGESHOW_SHOWS_TABLE.'.showName="%s"';
				}

				$values = array($showName);

				$showsEntries = $this->getresultsWithPrepare($sql, $values);
				$showID = (count($showsEntries) > 0) ? $showsEntries[0]->showID : 0;
			}
			
			return $showID;
		}
		
		function get_results($sql, $debugOutAllowed = true, $sqlFilters = array())
		{
			$this->perfJoined = false;
			
			$results = parent::get_results($sql, $debugOutAllowed, $sqlFilters);
			
			return $results;
		}

		function GetPerformanceSummaryByPerfID($perfID)
		{
			// Get totalQty, perfSeats and perfDateTime
			
			$results = $this->GetPerformancesListByPerfID($perfID);
			if (count($results) == 0) return null;
			
			return $results[0];
		}
				
		function GetPerformancesListByPerfID($perfID)
		{
			$sqlFilters['perfID'] = $perfID;
			return $this->GetPerformancesList($sqlFilters);
		}
				
		function GetPerformanceJoins($sqlFilters = null)
		{
			$sql  = '';
			
			if (isset($sqlFilters['joinZones']))
			{
				$sql .= " LEFT JOIN ".STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.zoneID='.STAGESHOW_PRICES_TABLE.'.priceZoneID';
			}
			
			return $sql;
		}
		
				
		protected function GetPerformancesList($sqlFilters = null)
		{
			$selectFields  = STAGESHOW_PERFORMANCES_TABLE.'.*';
			$selectFields .= ','.STAGESHOW_SHOWS_TABLE.'.*';
			$selectFields .= ', COUNT(priceType) AS pricesCount';
			
			if (!isset($sqlFilters['groupBy']))	
			{			
				$sqlFilters['groupBy'] = 'perfID';
			}
			
			if (isset($sqlFilters['groupBy']))	
			{			
				$totalSalesField = $this->TotalSalesField($sqlFilters);
				if ($totalSalesField != '')
					$selectFields .= ','.$totalSalesField;
			}
			
			$this->perfJoined = true;

			$sql = "SELECT $selectFields FROM ".STAGESHOW_PERFORMANCES_TABLE;
			$sql .= " LEFT JOIN ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= " LEFT JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.perfID='.STAGESHOW_PERFORMANCES_TABLE.'.perfID';
			$sql .= " LEFT JOIN ".STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.priceID='.STAGESHOW_PRICES_TABLE.'.priceID';
			$sql .= " LEFT JOIN ".STAGESHOW_SALES_TABLE.' ON '.STAGESHOW_SALES_TABLE.'.saleID='.STAGESHOW_TICKETS_TABLE.'.saleID';	// MJS: Check SALES Table Fields
			$sql .= $this->GetPerformanceJoins($sqlFilters);

			// Add SQL filter(s)
			$sql .= $this->GetWhereSQL($sqlFilters);
			$sql .= $this->GetOptsSQL($sqlFilters);
			
			$sql .= ' ORDER BY '.STAGESHOW_PERFORMANCES_TABLE.'.showID, '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime DESC';
			
			$perfsListArray = $this->get_results($sql);

			return $perfsListArray;
		}
		
		function GetActivePerformances($showID = '', $adminPageActive = false)
		{
			$selectFields  = STAGESHOW_PERFORMANCES_TABLE.'.*';
			$selectFields .= ', '.STAGESHOW_SHOWS_TABLE.'.*';
			
			$sql = "SELECT $selectFields FROM ".STAGESHOW_PERFORMANCES_TABLE;
			$sql .= " LEFT JOIN ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= " JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.perfID='.STAGESHOW_PERFORMANCES_TABLE.'.perfID';

			// Add SQL filter(s)
			$sqlCond  = '('.STAGESHOW_PERFORMANCES_TABLE.'.perfState="")';
			$sqlCond .= ' OR ';
			$sqlCond .= '('.STAGESHOW_PERFORMANCES_TABLE.'.perfState="'.STAGESHOW_STATE_ACTIVE.'")';
			$sqlWhere  = "($sqlCond)";
			$sqlWhere .= ' AND '.STAGESHOW_SHOWS_TABLE.'.showState="'.STAGESHOW_STATE_ACTIVE.'" ';
										
			$timeNow = current_time('mysql');
			if (current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER))
			{
				$timeNow = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, current_time('timestamp') - STAGESHOWLIB_ADMIN_EXPIRETIMEOFFSET);
			}				
			
			$sqlWhere .= ' AND '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime>"'.$timeNow.'" ';

			if (!$adminPageActive)
			{
				$sqlWhere .= ' AND '.STAGESHOW_PRICES_TABLE.'.priceVisibility="'.STAGESHOW_VISIBILITY_PUBLIC.'" ';
			}
			else
			{
				$sqlWhere .= ' AND '.STAGESHOW_PRICES_TABLE.'.priceVisibility!="'.STAGESHOW_VISIBILITY_DISABLED.'" ';
			}
			
			if ($showID != '')
			{
				$sqlWhere .= ' AND '.STAGESHOW_SHOWS_TABLE.'.showID="'.$showID.'" ';
			}
			
			$sql .= ' WHERE '.$sqlWhere;
			
			$sql .= ' GROUP BY '.STAGESHOW_PERFORMANCES_TABLE.'.perfID';
			$sql .= ' ORDER BY '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime';

			$perfsListArray = $this->get_results($sql);

			return $perfsListArray;
		}
		
		function IsShowEnabled($result)
		{
			//StageShowLibEscapingClass::Safe_EchoHTML("Show:$result->showID $result->showState<br>\n");
			return $this->IsStateActive($result->showState);
		}
		
 		function IsPerfExpired($result)
		{
			// Calculate how long before the booking window closes ...
			if (StageShowLibMigratePHPClass::Safe_strlen($result->perfExpires) == 0)
			{
				$result->perfExpires = $result->perfDateTime;
				$expireLimit = ($this->getOption('PerfExpireLimit') * $this->getOption('PerfExpireUnits'));
			}
			else
			{
				// Expire time overridden by setting for this performance
				$expireLimit = 0;
			}
			$timeToPerf = StageShowLibMigratePHPClass::Safe_strtotime($result->perfExpires) - current_time('timestamp') - $expireLimit;				
			if (current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER))
			{
				$timeToPerf += STAGESHOWLIB_ADMIN_EXPIRETIMEOFFSET;
			}				
			
			if ($timeToPerf < 0) 
			{					
				$timeToPerf *= -1;
				
				StageShowLibEscapingClass::Safe_EchoHTML("<!-- Performance (".$result->perfDateTime.") Expired ".$timeToPerf." seconds ago -->\n");
				// TODO-PRIORITY - Disable Performance Button (using Inventory Control) when it expires
				return true;
			}
			//StageShowLibEscapingClass::Safe_EchoHTML("<!-- Performance Expires in ".$timeToPerf." seconds -->\n");
			
			return false;
		}
		
		function IsPerfEnabled($result)
		{
			if ($this->IsPerfExpired($result))
			{
				return false;
			}
			
			//StageShowLibEscapingClass::Safe_EchoHTML("Show:$result->showID $result->showState Perf:$result->perfID $result->perfState<br>\n");
			return $this->IsStateActive($result->showState) && $this->IsStateActive($result->perfState);
		}
		
		function GetPricesListByShowID($showID, $activeOnly = false)
		{
			$showID = $this->GetShowID($showID);
			$sqlFilters['showID'] = $showID;
			return $this->GetPricesList($sqlFilters, $activeOnly);
		}
				
		function GetPricesListByPerfID($perfID, $activeOnly = false)
		{
			$sqlFilters['perfID'] = $perfID;
			return $this->GetPricesList($sqlFilters, $activeOnly);
		}
				
		function GetPricesListByPerfDateTime($showID, $perfDate)
		{
			if ($showID != 0)
			{
				$sqlFilters['showID'] = $showID;
			}
			if (StageShowLibMigratePHPClass::Safe_strpos($perfDate, ":"))
			{
				$sqlFilters['perfDateTime'] = $perfDate;
			}
			else
			{
				$sqlFilters['perfDate'] = $perfDate;
			}
			return $this->GetPricesList($sqlFilters, true);
		}
				
		function GetPricesListByPriceID($priceID, $activeOnly = false)
		{
			$sqlFilters['priceID'] = $priceID;
			return $this->GetPricesList($sqlFilters, $activeOnly);
		}
				
		function GetPricesSelects($sqlFilters)
		{
			$sql  = '';
			
			if (isset($sqlFilters['joinZones']))
			{
				$sql .= ', '.STAGESHOW_ZONES_TABLE.'.*';
				$sql .= ', seatingRef, seatingVenue';
			}
			if (isset($sqlFilters['joinDiscounts']))
			{
				$sql .= ', discount.*';
			}
			
			return $sql;
		}
		
		
		function GetPricesJoins($sqlFilters)
		{
			$sql  = '';
			
			if (isset($sqlFilters['joinZones']))
			{
				$sql .= " LEFT JOIN ".STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.zoneID='.STAGESHOW_PRICES_TABLE.'.priceZoneID';
				$sql .= " LEFT JOIN ".STAGESHOW_SEATING_TABLE.' ON '.STAGESHOW_SEATING_TABLE.'.seatingID='.STAGESHOW_ZONES_TABLE.'.seatingID';
			}
			
			if (isset($sqlFilters['joinDiscounts']))
			{
				$disID = $this->disID;
				
				$subsql  = 'SELECT priceID, disValue  FROM '.STAGESHOW_DISPRICES_TABLE;
				$subsql .= ' JOIN '.STAGESHOW_DISCODES_TABLE.' ON '.STAGESHOW_DISCODES_TABLE.'.disID='.STAGESHOW_DISPRICES_TABLE.'.disID';
				$subsql .= ' WHERE '.STAGESHOW_DISPRICES_TABLE.'.disID='.$disID;
				$subsql .= ' AND '.STAGESHOW_DISCODES_TABLE.'.disState="'.STAGESHOW_STATE_ACTIVE.'"';
				
				$sql .= ' LEFT JOIN ('.$subsql.') AS discount ON discount.priceID='.STAGESHOW_PRICES_TABLE.'.priceID';
			}
			
			return $sql;
		}
				
		function GetPricesOrder($sqlFilters)
		{
			$sql  = ' , '.STAGESHOW_ZONES_TABLE.'.zoneID';
			$sql .= ' , '.STAGESHOW_BOXOFFICE_SORTFIELD;
			
			return $sql;
		}
							
		function GetPricesList($sqlFilters, $activeOnly = false)
		{
			$sqlFilters['joinZones'] = true;
			$sqlFilters['joinDiscounts'] = true;

			if ($activeOnly)
			{
				if (!current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER)) $sqlFilters['publicPrices'] = true;
			}
			
			$sqlFilters['isPricesList'] = true;
			if ($activeOnly)
			{
				$sqlFilters['activePrices'] = true;
				$sqlFilters['perfState'] = STAGESHOW_STATE_ACTIVE;
			}

			$selectFields  = STAGESHOW_PRICES_TABLE.'.*';
			$selectFields .= ', '.STAGESHOW_PERFORMANCES_TABLE.'.*';
			$selectFields .= ', '.STAGESHOW_SHOWS_TABLE.'.*';
			$selectFields .= $this->GetPricesSelects($sqlFilters);
			
			if (isset($sqlFilters['saleID']))
			{
				// Explicitly add joined fields from "base" tables (otherwise values will be NULL if there is no matching JOIN)
				$selectFields .= ', '.STAGESHOW_SALES_TABLE.'.saleID';

				$joinCmd = ' LEFT JOIN ';
			}
			else
				$joinCmd = ' JOIN ';
						
			// Explicitly add joined fields from "base" tables (otherwise values will be NULL if there is no matching JOIN)
			$selectFields .= ', '.STAGESHOW_PRICES_TABLE.'.priceID';
						
			$sql  = 'SELECT '.$selectFields.' FROM '.STAGESHOW_PRICES_TABLE;
      		$sql .= ' '.$joinCmd.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_PRICES_TABLE.'.perfID';
      		$sql .= ' '.$joinCmd.STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= $this->GetPricesJoins($sqlFilters);
			$sql .= $this->GetWhereSQL($sqlFilters);

			$sql .= ' ORDER BY '.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= ' , '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime';			
			$sql .= $this->GetPricesOrder($sqlFilters);
			
			return $this->get_results($sql);
		}
		
		function GetOnlineStoreItemName($result)
		{
			if ($result->showDateTimeMode == STAGESHOW_DATETIMEOPTION_NONE)
			{
				$showName = $result->showName;
				$priceType = $result->priceType;
							
				$fullName = $showName.'-'.$priceType;
				return $fullName;
			}
			else
			{
				$showName = $result->showName;
				$perfDateTime = $this->FormatDateForDisplay($result->perfDateTime);
				$priceType = $result->priceType;
							
				$fullName = $showName.'-'.$perfDateTime.'-'.$priceType;
			}
			
			if ($result->ticketSeat != '')
			{
				// Add decoded seat name
				$fullName .= '-'.StageShowZonesDBaseClass::DecodeSeatsList($this, $result->ticketSeat, $result->perfSeatingID);
			}

			return $fullName;
		}

// ----------------------------------------------------------------------
//
//			Start of CUSTOM SALES functions
//
// ----------------------------------------------------------------------

		function GetSalesQueryFields($sqlFilters = null)
		{		
			if (isset($sqlFilters['addTicketFee']))
			{
				$sql  = STAGESHOW_TICKETS_TABLE.".ticketID, ";
				$sql .= "saleTxnId, saleStatus, saleFirstName, saleLastName, user_login, showName, perfDateTime, priceType, ticketQty, priceNoOfSeats, ticketPaid, ";
				$sql .= "saleFee*(ticketQty * priceNoOfSeats)/saleTotalQty AS ticketFee, ";
				$sql .= "saleTransactionFee*(ticketQty * priceNoOfSeats)/saleTotalQty AS ticketCharge, ";
				$sql .= "saleDateTime, saleEMail, salePPPhone, salePPStreet, salePPCity, salePPState, salePPZip, salePPCountry, perfRef";
			}
			else if (isset($sqlFilters['saleID']))
			{
				$sql  = STAGESHOW_SALES_TABLE.".*";	// MJS: Check SALES Table Fields
				$sql .= ",".STAGESHOW_TICKETS_TABLE.".*";
				$sql .= ",".STAGESHOW_PRICES_TABLE.".*";
				$sql .= ",".STAGESHOW_PERFORMANCES_TABLE.".*";
				$sql .= ",".STAGESHOW_SHOWS_TABLE.".*";
			}
			else
			{
				$sql = parent::GetSalesQueryFields($sqlFilters);
			}

			if (isset($sqlFilters['addTicketFee']))
			{
				$sql = StageShowLibMigratePHPClass::Safe_str_replace(" perfRef", " perfRef, verifyLocation, verifyDateTime", $sql);
				$sql = StageShowLibMigratePHPClass::Safe_str_replace("ticketCharge,", "ticketCharge, saleDonation*(ticketQty * priceNoOfSeats)/saleTotalQty AS ticketDonation,", $sql);
				$sql = StageShowLibMigratePHPClass::Safe_str_replace("ticketCharge,", "ticketCharge, salePostage*(ticketQty * priceNoOfSeats)/saleTotalQty AS ticketPostage,", $sql);
				$sql = StageShowLibMigratePHPClass::Safe_str_replace(" ticketQty,", " ".STAGESHOW_ZONES_TABLE.".seatingID, zoneRef, ticketSeat, ticketQty,", $sql);
			}
			
			if (isset($sqlFilters['saleID']))
			{
				$sql .= ",".STAGESHOW_ZONES_TABLE.".*";
				$sql .= ", seatingRef, seatingVenue";
			}
				
			return $sql;
		}
				
		function GetJoinedTables($sqlFilters = null, $classID = '')
		{
			$sqlJoin = parent::GetJoinedTables($sqlFilters, $classID);
			
			$joinType = isset($sqlFilters['JoinType']) ? $sqlFilters['JoinType'] : 'JOIN';
			
			// JOIN parent tables
			if (!isset($sqlFilters['ordersJoined'])) $sqlJoin .= " $joinType ".STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.saleID='.STAGESHOW_SALES_TABLE.'.saleID';
			$sqlJoin .= " $joinType ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceID='.STAGESHOW_TICKETS_TABLE.'.priceID';
			$sqlJoin .= " $joinType ".STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_PRICES_TABLE.'.perfID';
			
			$this->perfJoined = true;						
			
			$sqlJoin .= " $joinType ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
						
			$this->showJoined = true;
						
			if (isset($sqlFilters['addTicketFee']))
			{
				$sqlJoin .= " LEFT JOIN (";
				$sqlJoin .= "SELECT ".STAGESHOW_SALES_TABLE.".saleID";
				$sqlJoin .= ", SUM(ticketQty) AS saleTotalQty ";
				$sqlJoin .= "FROM ".STAGESHOW_SALES_TABLE." JOIN ".STAGESHOW_TICKETS_TABLE." ON ".STAGESHOW_TICKETS_TABLE.".saleID=".STAGESHOW_SALES_TABLE.".saleID ";
				$sqlJoin .= "JOIN ".STAGESHOW_PRICES_TABLE." ON ".STAGESHOW_PRICES_TABLE.".priceID=".STAGESHOW_TICKETS_TABLE.".priceID ";
				$sqlJoin .= "GROUP BY ".STAGESHOW_SALES_TABLE.".saleID";
				$sqlJoin .= ") AS totals ON ".STAGESHOW_SALES_TABLE.".saleID = totals.saleID ";
			}
			
			if (isset($sqlFilters['addTicketFee']))
			{
				// JOIN parent tables
				$sqlJoin .= ' LEFT JOIN '.STAGESHOW_VERIFYS_TABLE;
				$sqlJoin .= ' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_VERIFYS_TABLE.'.perfID';
				$sqlJoin .= ' AND '.STAGESHOW_SALES_TABLE.'.saleID='.STAGESHOW_VERIFYS_TABLE.'.saleID';
			}
			
			$joinType = isset($sqlFilters['JoinType']) ? $sqlFilters['JoinType'] : 'JOIN';
			
			// JOIN parent tables
			$sqlJoin .= " LEFT JOIN ".STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.zoneID='.STAGESHOW_PRICES_TABLE.'.priceZoneID';
			$sqlJoin .= " LEFT JOIN ".STAGESHOW_SEATING_TABLE.' ON '.STAGESHOW_SEATING_TABLE.'.seatingID='.STAGESHOW_ZONES_TABLE.'.seatingID';
			
			return $sqlJoin;
		}
		
		function GetWhereSQL($sqlFilters)
		{
			$sqlWhere = '';
			if (isset($sqlFilters['deltable']))
			{
				$sqlWhere .= ' WHERE (('.$sqlFilters['deltable'].'.showState = "'.STAGESHOW_STATE_DELETED.'") OR ('.$sqlFilters['deltable'].'.perfState = "'.STAGESHOW_STATE_DELETED.'")) ';			
			}
			
			$sqlWhere .= parent::GetWhereSQL($sqlFilters);
			$sqlCmd = ($sqlWhere === '') ? ' WHERE ' : ' AND ';
			
			if (isset($sqlFilters['perfDate']))
			{
				$sqlWhere .= $sqlCmd.'DATE('.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime)="'.$sqlFilters['perfDate'].'"';
				$sqlCmd = ' AND ';
			}
			
			if (isset($sqlFilters['perfDateTime']))
			{
				$sqlWhere .= $sqlCmd.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime="'.$sqlFilters['perfDateTime'].'"';
				$sqlCmd = ' AND ';
			}
			
			if (isset($sqlFilters['priceID']))
			{
				$sqlWhere .= $sqlCmd.STAGESHOW_PRICES_TABLE.'.priceID="'.$sqlFilters['priceID'].'"';
				$sqlCmd = ' AND ';
			}
			
			if (isset($sqlFilters['perfID']) && ($sqlFilters['perfID'] > 0))
			{
				// Select a specified Performance Record
				$sqlWhere .= $sqlCmd.STAGESHOW_PERFORMANCES_TABLE.'.perfID="'.$sqlFilters['perfID'].'"';
				$sqlCmd = ' AND ';
			}
			else 
			{
				if (($this->perfJoined) && !isset($sqlFilters['allSales']))
				{
					// Select multi performance records
					if (isset($sqlFilters['perfState'])) 
					{
						if ($sqlFilters['perfState'] == STAGESHOW_STATE_ACTIVE)
						{
							$sqlCond  = '('.STAGESHOW_PERFORMANCES_TABLE.'.perfState="")';
							$sqlCond .= ' OR ';
							$sqlCond .= '('.STAGESHOW_PERFORMANCES_TABLE.'.perfState="'.STAGESHOW_STATE_ACTIVE.'")';
							$sqlWhere .= $sqlCmd.'('.$sqlCond.')';							
						}
						else
						{
							$sqlWhere .= $sqlCmd.STAGESHOW_PERFORMANCES_TABLE.'.perfState="'.$sqlFilters['perfState'].'"';
						}
					}
					else
					{
						$sqlCond  = '('.STAGESHOW_PERFORMANCES_TABLE.'.perfState IS NULL)';
						$sqlCond .= ' OR ';
						$sqlCond .= '('.STAGESHOW_PERFORMANCES_TABLE.'.perfState<>"'.STAGESHOW_STATE_DELETED.'")';
						$sqlWhere .= $sqlCmd.'('.$sqlCond.')';
					}
					$sqlCmd = ' AND ';
				}				
			}
						
			if (isset($sqlFilters['priceType']))
			{
				$sqlWhere .= $sqlCmd.STAGESHOW_PRICES_TABLE.'.priceType="'.$sqlFilters['priceType'].'"';
				$sqlCmd = ' AND ';
			}
						
			if (isset($sqlFilters['activePrices']))
			{
				$sqlWhere .= $sqlCmd.STAGESHOW_PRICES_TABLE.'.priceValue>="0"';
				$sqlCmd = ' AND ';
			}			
			
			if (isset($sqlFilters['showID']) && ($sqlFilters['showID'] > 0))
			{
				$sqlWhere .= $sqlCmd.STAGESHOW_SHOWS_TABLE.'.showID="'.$sqlFilters['showID'].'"';
				$sqlCmd = ' AND ';
			}
			else if (!isset($sqlFilters['perfID']) && !isset($sqlFilters['allSales']) && isset($this->showJoined) )
			{
				if (!isset($sqlFilters['showState']))
				{
					$sqlWhere .= $sqlCmd.STAGESHOW_SHOWS_TABLE.'.showState<>"'.STAGESHOW_STATE_DELETED.'"';
					$sqlCmd = ' AND ';
				}
				else
				{
					$sqlWhere .= $sqlCmd.STAGESHOW_SHOWS_TABLE.'.showState="'.$sqlFilters['showState'].'"';
					$sqlCmd = ' AND ';				
				}
			}
			
			$sqlCmd = ($sqlWhere === '') ? ' WHERE ' : ' AND ';
			
			if ( isset($sqlFilters['publicPrices']))
			{
				$sqlWhere .= $sqlCmd.STAGESHOW_PRICES_TABLE.'.priceVisibility="'.STAGESHOW_VISIBILITY_PUBLIC.'"';
				$sqlCmd = ' AND ';
			}			
			else if (isset($sqlFilters['activePrices']))
			{
				$sqlWhere .= $sqlCmd.STAGESHOW_PRICES_TABLE.'.priceVisibility!="'.STAGESHOW_VISIBILITY_DISABLED.'"';
				$sqlCmd = ' AND ';
			}			
			
			if (isset($sqlFilters['zoneID']) && ($sqlFilters['zoneID'] > 0))
			{
				// Select a specified Performance Record
				$sqlWhere .= $sqlCmd.STAGESHOW_ZONES_TABLE.'.zoneID="'.$sqlFilters['zoneID'].'"';
				$sqlCmd = ' AND ';
			}
			
			if ( (!isset($sqlFilters['publicPrices']) && isset($sqlFilters['activePrices']))
			  || ( isset($sqlFilters['isPricesList']) ))
			{
				$sqlCmd = ($sqlWhere === '') ? ' WHERE ' : ' AND ';
				$sqlWhere .= $sqlCmd.STAGESHOW_PRICES_TABLE.'.priceVisibility!="'.STAGESHOW_VISIBILITY_LOCKOUT.'"';
			}
			
			if (isset($sqlFilters['inListing']))
			{
				$sqlCmd = ($sqlWhere === '') ? ' WHERE ' : ' AND ';
				$sqlWhere .= $sqlCmd.' NOT showNotInListing ';
			}
			
			$caldate = StageShowLibUtilsClass::GetHTTPDateTime($_REQUEST, 'caldate'); 
			if ($caldate != '')
			{
				if (preg_match('/([0-9\-]+)/', $caldate, $matches))
				{
					$reqDate = $matches[1];
					
					$sqlCmd = ($sqlWhere === '') ? ' WHERE ' : ' AND ';
					
					$sqlCond  = '('.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime>="'.$reqDate.' 00:00:00")';
					$sqlCond .= ' AND ';
					$sqlCond .= '('.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime<="'.$reqDate.' 23:59:59")';
					$sqlWhere .= $sqlCmd.'('.$sqlCond.')';							
				}
				
			}
					
			return $sqlWhere;
		}
		
		
		
		function GetOptsSQL($sqlFilters, $sqlOpts = '')
		{
			if (isset($sqlFilters['groupBy']))
			{
				switch ($sqlFilters['groupBy'])
				{
					case 'saleID':
						$sqlOpts = $this->AddSQLOpt($sqlOpts, ' GROUP BY ', STAGESHOW_SALES_TABLE.'.saleID');
						break;
						
					case 'showID':
						$sqlOpts = $this->AddSQLOpt($sqlOpts, ' GROUP BY ', STAGESHOW_SHOWS_TABLE.'.showID');
						break;
						
					case 'perfID':
						$sqlOpts = $this->AddSQLOpt($sqlOpts, ' GROUP BY ', STAGESHOW_PERFORMANCES_TABLE.'.perfID');
						break;
						
					case 'priceID':
						$sqlOpts = $this->AddSQLOpt($sqlOpts, ' GROUP BY ', STAGESHOW_PRICES_TABLE.'.priceID');
						break;
						
					default:
						break;
				}
			}
			
			$sqlOpts = parent::GetOptsSQL($sqlFilters, $sqlOpts);
			return $sqlOpts;
		}
		
// ----------------------------------------------------------------------
//
//			Start of GENERIC SALES functions
//
// ----------------------------------------------------------------------
    
		function TotalSaleQtyField()
		{
			return 'ticketQty * priceNoOfSeats';
		}
		
		function GetSaleItemName($saleItem)
		{
			return $saleItem->ticketName;
		}
		
		function GetSaleItemPrice($saleItem)
		{
			return $saleItem->ticketPaid/$saleItem->ticketQty;
		}
		
		function GetSaleItemQty($saleItem)
		{
			return $saleItem->ticketQty;
		}
		
		function TotalOrdersField($sqlFilters = null)
		{
			$sql = '';
			
			// totalQty includes Pending sales (i.e. saleStatus=Checkout))
			$sql .= 'SUM('.$this->TotalSaleQtyField().') AS saleQty ';
			$sql .= ', MAX('.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime) AS maxPerfDateTime';
			
			return $sql;
		}
		
		function TotalValuesField($sqlFilters = null)
		{			
			$sql = '';
			
			$statusOptions  = '(saleStatus="'.PAYMENT_API_SALESTATUS_RESERVED.'")';
			$sql .= '  SUM(IF('.$statusOptions.', ticketQty * priceNoOfSeats, 0)) AS reservedQty ';
			$statusOptions .= ' OR ';
			$statusOptions .= '(saleStatus="'.PAYMENT_API_SALESTATUS_COMPLETED.'")';
			$sql .= ', SUM(IF('.$statusOptions.', ticketPaid, 0)) AS soldValue ';
			$sql .= ', SUM(IF('.$statusOptions.', ticketQty * priceNoOfSeats, 0)) AS soldQty ';				
			$statusOptions .= ' OR ';
			$statusOptions .= '(saleStatus="'.PAYMENT_API_SALESTATUS_CHECKOUT.'")';
			$sql .= ', SUM(IF('.$statusOptions.', ticketQty * priceNoOfSeats, 0)) AS totalQty ';
	
			$statusOptions  = '(saleStatus="'.PAYMENT_API_SALESTATUS_LOCKOUT.'")';
			$sql .= ', SUM(IF('.$statusOptions.', ticketQty * priceNoOfSeats, 0)) AS lockedQty ';
	
			return $sql;
		}
		
		function TotalSalesField($sqlFilters = null)
		{			
			return $this->TotalOrdersField($sqlFilters).','.$this->TotalValuesField($sqlFilters);
		}
		
		function AddSaleFields(&$salesListArray)
		{
			if (count($salesListArray) == 0) return;
			
			if (!isset($salesListArray[0]->showName)) return;
			
			foreach ($salesListArray as $i => $saleItem)
			{
				$salesListArray[$i]->ticketName = $this->GetOnlineStoreItemName($saleItem);
				$salesListArray[$i]->ticketType = $salesListArray[$i]->priceType;
			}			
		}
		
		function DeleteOrders($saleID)
		{
			// Delete a show entry
			$sql  = 'DELETE FROM '.STAGESHOW_TICKETS_TABLE;
			$sql .= ' WHERE '.STAGESHOW_TICKETS_TABLE.".saleID=$saleID";
		 
			$this->query($sql);
		}
		
		function GetTransactionFee($cartContents = array())
		{
			$fee = $this->getOption('TransactionFee');			
			if (($fee == '') || ($fee <= 0)) $fee = 0;
			
			$feePerCent = $this->getOption('TransactionFeePerCent');			
			if ( ($feePerCent != '') 
			  && ($feePerCent > 0)
			  && (isset($cartContents->rows)) )
			{
				$runningTotal = 0;		
							
				foreach ($cartContents->rows as $cartEntry)
				{
					$runningTotal += ($cartEntry->price * $cartEntry->qty);
				}
				
				// Add percentage part of fee (rounded)
				$fee += (intval(($runningTotal * $feePerCent) + 0.5)) / 100;
			}
			
			return $fee;
		}

// ----------------------------------------------------------------------
//
//			End of SALES functions
//
// ----------------------------------------------------------------------
		
		function query($sql)
		{
			$this->perfJoined = false;
			return parent::query($sql);
		}
		
		function ShowSQL($sql, $values = null)
		{
			parent::ShowSQL($sql, $values);
			
			unset($this->showJoined);
		}			
		
		function SettingsConfigured()
		{
			if ($this->getOption('ReservationsMode') != STAGESHOW_RESERVATIONSMODE_DISABLED)
			{
				return true;
			}
			
			return parent::SettingsConfigured();
		}

		function GetExtraDiscount($cartContents = array())
		{
			return apply_filters('stageshow_filter_discount', 0, $cartContents);
		}

		// Commented out Class Def (StageShowCartDBaseClass)
		function CartEntryHasCheckoutForm($cartEntry)
		{
			return ($cartEntry->ckoutDetailsTemplate != '');
		}
		
		function UserCanBookLockout()
		{
			return ((STAGESHOW_CAPABILITY_BOOKLOCKOUT != '') && current_user_can(STAGESHOW_CAPABILITY_BOOKLOCKOUT));
		}
		
		function GetPerformanceSummaryByPerfAndZoneID($perfID, $zoneID)
		{
			$sqlFilters['perfID'] = $perfID;
			$sqlFilters['zoneID'] = $zoneID;
			$sqlFilters['joinZones'] = true;
			$results =  $this->GetPerformancesList($sqlFilters);
			if (count($results) == 0) return null;
			
			return $results[0];
		}
		
		function GetZoneList($perfID)
		{
			$sql  = 'SELECT showID,perfID,zoneID,zoneRef,zoneAllocSeats FROM '.STAGESHOW_PERFORMANCES_TABLE;
			$sql .= " JOIN ".STAGESHOW_SEATING_TABLE.' ON '.STAGESHOW_SEATING_TABLE.'.seatingID='.STAGESHOW_PERFORMANCES_TABLE.'.perfSeatingID';
			$sql .= " RIGHT JOIN ".STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.seatingID='.STAGESHOW_SEATING_TABLE.'.seatingID';
			$sql .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.perfID = "'.$perfID.'"';
			
			$zonesList = $this->get_results($sql);
			
			return ($zonesList);
		}
		
		function GetSalesQtyByPerfAndZone($perfID, $zoneID)
		{
			$sqlFilters['perfID'] = $perfID;
			$sqlFilters['zoneID'] = $zoneID;
			return $this->GetSalesQty($sqlFilters);
		}
				
		function DiscountsAreActive()
		{
			$sql  = "SELECT * FROM ".STAGESHOW_DISCODES_TABLE;
			$sql .= ' WHERE disState = "'.STAGESHOW_STATE_ACTIVE.'"';	
			
			$results = $this->get_results($sql);
			return (count($results)>0);
		}
		
		function GetDiscountsEntry($discountCode, $activeOnly = false)
		{
			$sql  = "SELECT * FROM ".STAGESHOW_DISCODES_TABLE;
			$sql .= ' WHERE disCodes LIKE "%,'.$discountCode.',%"';				
			if ($activeOnly)			
			{
				$sql .= ' AND disState = "'.STAGESHOW_STATE_ACTIVE.'"';	
			}
			
			$results = $this->get_results($sql);
			return $results;
		}
		
		function GetDiscountCode($discountCode)
		{			
			$this->disEnabled = $this->DiscountsAreActive();
			$this->disID = 0;
			
			if ($discountCode == '')
			{
				return '';
			}
	
			if (!$this->disEnabled)	
			{
				return '';
			}
			
			$disRef = '';
			$results = $this->GetDiscountsEntry($discountCode, true);
			if (count($results) > 0)
			{
				$this->disID = $results[0]->disID;
				$disRef = $results[0]->disRef;			
			}
			
			return "$disRef ($discountCode)";
		}

		function GetSoldSeats($perfID, $saleID)
		{
			$selectFields  = STAGESHOW_TICKETS_TABLE.'.saleID, saleStatus, ticketID, ticketSeat';
			
			$sqlWhere = ' WHERE '.STAGESHOW_TICKETS_TABLE.'.perfID = "'.$perfID.'"';
			
			$sql  = "SELECT $selectFields FROM ".STAGESHOW_TICKETS_TABLE;
			$sql .= " LEFT JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceID='.STAGESHOW_TICKETS_TABLE.'.priceID';
			$sql .= " LEFT JOIN ".STAGESHOW_SALES_TABLE.' ON '.STAGESHOW_SALES_TABLE.'.saleID='.STAGESHOW_TICKETS_TABLE.'.saleID';
			$sql .= ' WHERE '.STAGESHOW_PRICES_TABLE.'.perfID = "'.$perfID.'"';
			$sql .= ' AND '.STAGESHOW_SALES_TABLE.'.saleStatus != "'.PAYMENT_API_SALESTATUS_TIMEOUT.'"';
			$sql .= ' AND '.STAGESHOW_SALES_TABLE.'.saleStatus != "'.PAYMENT_API_SALESTATUS_SUSPENDED.'"';

			if ($saleID > 0)
			{
				$sql .= ' AND '.STAGESHOW_TICKETS_TABLE.'.saleID != "'.$saleID.'"';			 
			}
			$sql .= ' ORDER BY '.STAGESHOW_TICKETS_TABLE.'.ticketSeat';

			$seatsList = $this->get_results($sql);

			$this->AddSaleCustomFields($seatsList);

			return $seatsList;
		}
		
		function AddSaleCustomFields(&$saleDetails)
		{
			if (count($saleDetails) == 0)
				return;
			
			$metaKeysList = $this->GetSaleMetaFields();
					
			foreach ($saleDetails as $saleKey => $saleItem)
			{
				$customFields = $this->GetSaleItemMeta($saleItem->ticketID);
				
				foreach ($customFields as $customField)
				{
					$metaKey = $customField->meta_key;
					$saleDetails[$saleKey]->$metaKey = $customField->meta_value;
				}
				
				foreach ($metaKeysList as $metaKeyEntry)
				{
					$metaKey = $metaKeyEntry->meta_key;
					if (isset($saleDetails[$saleKey]->$metaKey))
						continue;
						
					$saleDetails[$saleKey]->$metaKey = "";
				}		
		
			}
		}
		
		function GetSaleMetaFields($saleID=0)
		{
			$sql  = 'SELECT meta_key FROM '.STAGESHOW_SALES_TABLE;
			$sql .= ' JOIN '.STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_SALES_TABLE.'.saleID='.STAGESHOW_TICKETS_TABLE.'.saleID';
			$sql .= ' JOIN '.STAGESHOW_TICKETSMETA_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.ticketID='.STAGESHOW_TICKETSMETA_TABLE.'.ticketID';
			if ($saleID != 0) $sql .= ' WHERE '.STAGESHOW_SALES_TABLE.'.saleID='.$saleID;
			$sql .= ' GROUP BY meta_key';
			$sql .= ' ORDER BY ticketmetaID';
			
			$extraFields = $this->get_results($sql);			
			
			return $extraFields;
		}
		
		function GetSaleItemMeta($ticketID)
		{
			$sql  = 'SELECT '.STAGESHOW_TICKETSMETA_TABLE.'.* FROM '.STAGESHOW_TICKETSMETA_TABLE;
			$sql .= ' WHERE '.STAGESHOW_TICKETSMETA_TABLE.'.ticketID='.$ticketID;
			
			$extraFields = $this->get_results($sql);			
			
			return $extraFields;
		}
		
		function IsSeatAvailable($perfID, $seatIDs)
		{
			$canBookLockouts = $this->UserCanBookLockout();
			return $this->CheckSeatAvailable($perfID, $seatIDs, $canBookLockouts);
		}
		
		function CheckSeatAvailable($perfID, $seatIDs, $canBookLockouts)
		{
			$rtnStatus = true;
			
			$seatsList = explode(',', $seatIDs);
			foreach ($seatsList as $seatID)
			{
				// Query to find booking entry for this seat ....
				$sql  = 'SELECT '.STAGESHOW_SALES_TABLE.'.saleID, perfID, ticketSeat, saleStatus, saleFirstName, saleLastName';
				$sql .= ' FROM '.STAGESHOW_TICKETS_TABLE;
				$sql .= ' JOIN '.STAGESHOW_SALES_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.saleID='.STAGESHOW_SALES_TABLE.'.saleID ';
				$sql .= " LEFT JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceID='.STAGESHOW_TICKETS_TABLE.'.priceID';
				$sql .= ' WHERE '.STAGESHOW_PRICES_TABLE.'.perfID="'.$perfID.'"';	
				$sql .= ' AND '.STAGESHOW_SALES_TABLE.'.saleStatus != "'.PAYMENT_API_SALESTATUS_TIMEOUT.'"';
				$sql .= ' AND '.STAGESHOW_SALES_TABLE.'.saleStatus != "'.PAYMENT_API_SALESTATUS_SUSPENDED.'"';

				if ($canBookLockouts)
				{
					$sql .= ' AND '.STAGESHOW_SALES_TABLE.'.saleStatus != "'.PAYMENT_API_SALESTATUS_LOCKOUT.'"';
				}
				
				if ($this->GetMySQLRegexVersion() === MYSQL_REGEX_ICU)
				{
					$regex = '\\\\b'.$seatID.'\\\\b';
					$sql .= ' AND '.STAGESHOW_TICKETS_TABLE.'.ticketSeat REGEXP "'.$regex.'"';
				}
				else
				{
					// Deprecated POSIX style word boundaries for old versions of MYSQL (pre v8.0.4)
					$sql .= ' AND '.STAGESHOW_TICKETS_TABLE.'.ticketSeat REGEXP "[[:<:]]'.$seatID.'[[:>:]]" = 1';	// \b(?=\w)'.$seatID.'\b(?<=\w) 
				}
			
				$seatsList = $this->get_results($sql);
			
				if (count($seatsList) > 0) $rtnStatus = false;			
			}
			
			return $rtnStatus;
		}
		
		function DeleteSeatLockout($stockID, $ticketSeat)
		{
			if ($ticketSeat == '') return;
			
			// Get perfID from stockID
			$sql  = 'SELECT * FROM '.STAGESHOW_PRICES_TABLE;
			$sql .= " WHERE priceID=$stockID";								 
			$priceEntry = $this->get_results($sql);
			if (count($priceEntry) == 0) return;
			
			// Get Locked Seats saleID from perfID
			$saleID = $this->GetSaleLockoutID($priceEntry[0]->perfID);
			if ($saleID == 0) return;
			
			// Delete seat from locked seats			
			$sql  = 'DELETE FROM '.STAGESHOW_TICKETS_TABLE;
			$sql .= ' WHERE ticketSeat="'.$ticketSeat.'"';					
			$sql .= ' AND saleID="'.$saleID.'"';					
			 
			$this->query($sql);
		}
		
		function AddSaleItem($saleID, $stockID, $qty, $paid, $saleExtras = array())
		{
			if ($this->UserCanBookLockout() && isset($saleExtras['ticketSeat']))
			{
				$seatsList = explode(',', $saleExtras['ticketSeat']);
				foreach($seatsList as $ticketSeat)
				{
					$this->DeleteSeatLockout($stockID, $ticketSeat);
				}
			}
			
			return parent::AddSaleItem($saleID, $stockID, $qty, $paid, $saleExtras);
		}
		
		function AddSaleFromTrolley($saleID, $cartEntry, $saleExtras = array())
		{
			$saleExtras['ticketSeat'] = $cartEntry->seatLocns;
			$saleItemID = parent::AddSaleFromTrolley($saleID, $cartEntry, $saleExtras);
			if (isset($cartEntry->customFields))
			{
				foreach ($cartEntry->customFields as $customFieldID => $customFieldValue)
				{
					$this->AddSaleItemMeta($saleItemID, $customFieldID, $customFieldValue);
				}
			}
		}
		
		function AddLayoutWrapper($template) 
		{
if (!defined('STAGESHOW_BOXOFFICE_ENABLESCROLL')) return $template;

			$scriptsURL = STAGESHOW_URL.'js/jquery.transit.js';
			$wrapperHead = '
<style>
.stageshow-boxoffice-wrapper
{
	overflow: hidden;
	XX_border: 1px solid black;
}
.stageshow-boxoffice-layout
{
	overflow: auto;
	display:table;
	border: 1px solid red;
}
</style>

<script src="'.$scriptsURL.'"></script>
<script>

var scale = 1.0;
var xposn = 0.0;
var yposn = 0.0;
var target = "stageshow-boxoffice-seats"; // "innerdiv";

function click_In(obj)
{
	scale = scale * 1.5;
	zoomToScale(target, scale);
	return false;
}

function click_Out(obj)
{
	scale = scale / 1.5;
	if (scale < 1) scale = 1;
	zoomToScale(target, scale);
	return false;
}

function click_Reset(obj)
{
	scale = 1;
	xposn = 0;
	yposn = 0;
	zoomToScale(target, scale);
	moveToPosn(target, xposn, yposn);
	return false;
}

function click_Left(obj)
{
	xposn = xposn - 40;
	//if (xposn < 0) xposn = 0;
	moveToPosn(target, xposn, yposn);
	return false;
}

function click_Right(obj)
{
	xposn = xposn + 40;
	//if (xposn < 0) xposn = 0;
	moveToPosn(target, xposn, yposn);
	return false;
}

function click_Up(obj)
{
	yposn = yposn - 40;
	//if (xposn < 0) xposn = 0;
	moveToPosn(target, xposn, yposn);
	return false;
}

function click_Down(obj)
{
	yposn = yposn + 40;
	//if (xposn < 0) xposn = 0;
	moveToPosn(target, xposn, yposn);
	return false;
}

function zoomToScale(elemId, elemScale)
{
	var req = {};
	req.scale = elemScale;
	jQuery("#"+elemId).transition(req);
}

function moveToPosn(elemId, elemXPosn, elemYPosn)
{
	var req = {};
	req.x = elemXPosn;
	req.y = elemYPosn;
	jQuery("#"+elemId).transition(req);
}

function click_MouseDown(obj)
{
	return false;
}

function click_MouseUp(obj)
{
	return false;
}

</script>

<button type="button" id="btnIn" xx_name="btnIn" onclick="click_In(this)">In</button>
<button type="button" id="btnOut" xx_name="btnOut" onclick="click_Out(this)">Out</button>
&nbsp;

<button type="button" id="btnLeft" xx_name="btnLeft" onclick="click_Left(this)">Left</button>
<button type="button" id="btnRight" xx_name="btnRight" onclick="click_Right(this)">Right</button>
<button type="button" id="btnUp" xx_name="btnUp" onclick="click_Up(this)">Up</button>
<button type="button" id="btnDown" xx_name="btnDown" onclick="click_Down(this)">Down</button>
&nbsp;

<button type="button" id="btn_Reset" xx_name="btn_Reset" onclick="click_Reset(this)">Reset</button>
<br>
';
			$wrapperHead .= '<div id="stageshow-boxoffice-wrapper" onmousedown="click_MouseDown(this)" onmouseup="click_MouseUp(this)" class="stageshow-boxoffice-wrapper">'."\n";	
			$wrapperFoot = '</div>';

			$headPosn = StageShowLibMigratePHPClass::Safe_strpos($template, '<div id="stageshow-boxoffice-seats"');
			if ($headPosn <= 0) return $template;			
			$divCount = 1;
			$scanPosn = $headPosn;
			for (;;)
			{
				$scanPosn++;
				
				$nextDivEnd = StageShowLibMigratePHPClass::Safe_strpos($template, '</div', $scanPosn);
				if ($nextDivEnd <= 0) break;	
						
				$nextDivStart = StageShowLibMigratePHPClass::Safe_strpos($template, '<div', $scanPosn);
				if ($nextDivStart > 0)
				{
					if ($nextDivStart < $nextDivEnd)
					{
						$divCount++;
						$scanPosn = $nextDivStart;
						continue;
					}
				}
				
				$divCount--;
				if ($divCount == 0) 
				{
					$nextDivEnd = StageShowLibMigratePHPClass::Safe_strpos($template, '>', $nextDivEnd) + 1;
					return StageShowLibMigratePHPClass::Safe_substr($template, 0, $headPosn).$wrapperHead.StageShowLibMigratePHPClass::Safe_substr($template, $headPosn, $nextDivEnd-$headPosn).$wrapperFoot.StageShowLibMigratePHPClass::Safe_substr($template, $nextDivEnd);
				}
				$scanPosn = $nextDivEnd;					
			}
			
			return $wrapperHead.$template.$wrapperFoot;
		}		
		
	
	}			// class StageShowCartDBaseClass
}				// if (!class_exists('StageShowCartDBaseClass'))

















