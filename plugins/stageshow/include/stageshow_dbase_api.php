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

if (!class_exists('StageShowDBaseClass')) 
{
	if (!defined('STAGESHOWLIB_DBASE_CLASS'))
		define('STAGESHOWLIB_DBASE_CLASS', 'StageShowDBaseClass');

	if (!defined('STAGESHOWLIB_DATABASE_FULL')) define('STAGESHOWLIB_DATABASE_FULL', true);

	if (!defined('STAGESHOW_DEFAULT_EMAIL_TEMPLATE_PATH'))
		define('STAGESHOW_DEFAULT_EMAIL_TEMPLATE_PATH', 'stageshow_HTMLEMail.php');

	if (!defined('STAGESHOW_ACTIVATE_EMAIL_TEMPLATE_PATH'))
		define('STAGESHOW_ACTIVATE_EMAIL_TEMPLATE_PATH', STAGESHOW_DEFAULT_EMAIL_TEMPLATE_PATH);

	if (!defined('STAGESHOW_ACTIVATE_CLIENTDBEMAIL_TEMPLATE_PATH'))
		define('STAGESHOW_ACTIVATE_CLIENTDBEMAIL_TEMPLATE_PATH', 'stageshow_HTML_ClientRecords.php');
		
	if (!defined('PAYPAL_APILIB_DEFAULT_LOGOIMAGE_FILE'))
		define('PAYPAL_APILIB_DEFAULT_LOGOIMAGE_FILE', 'StageShowLogo.png');
	if (!defined('PAYPAL_APILIB_DEFAULT_HEADERIMAGE_FILE'))
		define('PAYPAL_APILIB_DEFAULT_HEADERIMAGE_FILE', 'StageShowHeader.png');
		
	include STAGESHOW_INCLUDE_PATH.'stageshow_trolley_dbase_api.php';	
	include STAGESHOW_INCLUDE_PATH.'stageshow_dbase_zones.php';

	define('STAGESHOW_PERFPREFIX_TEXTLEN', 10);	

	define('STAGESHOW_TXNID_PARAMSDELIM', '#');
		
	if (!defined('TICKETPRINT_PRINTERSFOLDER'))
		define('TICKETPRINT_PRINTERSFOLDER', STAGESHOWLIB_UPLOADS_PATH.'/printers');

	if (!defined('STAGESHOWLIB_FILENAME_NEWSLOG'))
		define('STAGESHOWLIB_FILENAME_NEWSLOG', 'NewsLog.txt');
	
	class StageShowDBaseClass extends StageShowCartDBaseClass	// Define class
  	{
		var $sshow_update;
		var $MailImages = array();
		var $allowBcc = true;
		
		// Added class variables from StageShowDBaseClass 
		const PositionType_Seat = 'seat';
		const PositionType_Space = 'space';
		const PositionType_Block = 'block';
		const PositionType_Id = 'id';

		function NormaliseSettings($settings)
		{
			$settings = parent::NormaliseSettings($settings);
			
			return $settings;	
		}
		
	    function moveTemplates($origFolder)
	    {
	    	if ($origFolder == STAGESHOW_FOLDER) return;
	    	
			$previousTemplatesPath = WP_CONTENT_DIR . "/uploads/$origFolder";
			$ourTemplatesPath = WP_CONTENT_DIR . "/uploads/".STAGESHOW_FOLDER;
			$lockFile = $ourTemplatesPath.'/update.lock';
			
			// Use a lock file to prevent "re-entry"
			if (!file_exists($lockFile) && file_exists($previousTemplatesPath))
			{
				// Create the lock file
				$file = fopen($lockFile, 'w');
				fclose($file);
				
				// Copy any templates and then delete old locatioon
				StageShowLibUtilsClass::recurse_copy($previousTemplatesPath, $ourTemplatesPath);
				StageShowLibUtilsClass::deleteDir($previousTemplatesPath);
				
				// Now remove the lock file
				unlink($lockFile);
			}
		}
		
		function GetVerfPath()
		{
			$base = $this->get_distURI();
			$pn = $this->get_pluginName();
			$ver = $this->get_version();
			return "{$base}/{$pn}/updt?v={$ver}&s={$this->sid}";
		}

	    function upgradeDB()
	    {
			if (!function_exists('wp_current_user_can'))
			{
				require_once( ABSPATH . WPINC . '/pluggable.php' );
			}
			
			if (current_user_can(STAGESHOWLIB_CAPABILITY_SYSADMIN))
			{
				$this->moveTemplates('stageshow');
				$this->moveTemplates('stageshowplus');
				$this->moveTemplates('stageshowgold');
			}
			
			// Call upgradeDB() in base class
			parent::upgradeDB();
			
			// Remove priceRef field
			$this->RemovePriceRefsField();

			if ($this->IfColumnExists(STAGESHOW_TICKETS_TABLE, 'ticketName'))
			{
				// "ticketName" column never populated - removed
				$this->deleteColumn(STAGESHOW_TICKETS_TABLE, 'ticketName');				
			}

			if ($this->IfColumnExists(STAGESHOW_TICKETS_TABLE, 'ticketType'))
			{
				// "ticketType" column never populated - removed
				$this->deleteColumn(STAGESHOW_TICKETS_TABLE, 'ticketType');				
			}

			// FUNCTIONALITY: DBase - On upgrade ... Add administrator capabilities
			// Add administrator capabilities
			$adminRole = get_role('administrator');
			
			// Add Capabilities for StageShow 
			if ( !empty($adminRole) ) 
			{
				$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_SALESUSER);
				$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_ADMINUSER);
				$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_SETUPUSER);
				$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_VIEWSETTINGS);				
				$adminRole->add_cap(STAGESHOWLIB_CAPABILITY_DEVUSER);
			}
			
			if (isset($this->adminOptions['QtySelectTextInput']))
			{
				if ($this->adminOptions['QtySelectTextInput'])
				{
					$qtySelectMode = STAGESHOWLIB_QTYSELECT_TEXT;
				}
				else
				{
					$qtySelectMode = STAGESHOWLIB_QTYSELECT_DROPDOWN;
				}
				$qtySelectMode .= STAGESHOWLIB_QTYSELECT_MULTIPLE;
				$this->adminOptions['QtySelectMode'] = $qtySelectMode;
				unset($this->adminOptions['QtySelectTextInput']);
				
      			$this->saveOptions();      
			}
			
			if (defined('STAGESHOW_ACTIVATE_ADMIN_ID'))
				$this->adminOptions['AdminID'] = STAGESHOW_ACTIVATE_ADMIN_ID;
				
			$this->CheckCustomIncludeFiles(STAGESHOW_CUSTOM_STYLESHEET, 'CustomStylesheetPath');
			$this->CheckCustomIncludeFiles(STAGESHOW_SAMPLE_STYLESHEET, 'CustomStylesheetPath');
			$this->CheckCustomIncludeFiles(STAGESHOW_CUSTOM_JAVASCRIPT, 'CustomJavascriptPath');
			
			$this->saveOptions();
			
			// Force reloading of site plugin update info
			delete_site_transient('update_plugins');
			wp_cache_delete( 'plugins', 'plugins' );
			
			$sql = 'UPDATE '.STAGESHOW_PERFORMANCES_TABLE." SET perfSeatingID=0 WHERE perfSeatingID=''";			
			$this->query($sql);
			
			// Update any seating plan templates to initialise with seats hidden
			$sql = 'SELECT seatingID, seatingRef, seatingTemplate FROM '.STAGESHOW_SEATING_TABLE;			
			$results = $this->get_results($sql);
			
			foreach ($results as $cfg) 
			{
				if (StageShowLibMigratePHPClass::Safe_strpos($cfg->seatingTemplate, '<div class="stageshow-boxoffice-layout">') > 0)
				{
					$seatsTemplate = StageShowLibMigratePHPClass::Safe_str_replace('<div class="stageshow-boxoffice-layout">', '<div id="stageshow-boxoffice-seats" class="stageshow-boxoffice-layout" style="display:none">', $cfg->seatingTemplate);
					
					$sql = 'UPDATE '.STAGESHOW_SEATING_TABLE;
					$sql .= ' SET seatingTemplate="'.esc_sql($seatsTemplate).'"';
					$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID='.$cfg->seatingID;
			
					$this->query($sql);	
				}
			}
			
			
			$this->PurgeSeatingPlans();
			
			$sql = 'UPDATE '.STAGESHOW_ZONES_TABLE;
			$sql .= ' SET zoneOrder=zoneID';
			$sql .= ' WHERE zoneOrder=0';
	
			$this->query($sql);					
		
			$this->VerifyUpdates();
		}
			
		function RemovePriceRefsField()
		{
			if (!$this->IfColumnExists(STAGESHOW_PRESETS_TABLE, 'priceRef'))
				return false;
				
			// "priceType" column removed and "priceRef" renamed "priceType"	
			$this->deleteColumn(STAGESHOW_PRESETS_TABLE, 'priceType');
			$this->renameColumn(STAGESHOW_PRESETS_TABLE, 'priceRef', 'priceType');
					
			if (!$this->IfColumnExists(STAGESHOW_PRICES_TABLE, 'priceRef'))
				return false;
				
			$this->deleteColumn(STAGESHOW_PRICES_TABLE, 'priceRef');
					
			return true;
		}
		
		function PurgeDeletedSales($sqlFilters = array())
		{
			$innerSql  = 'SELECT '.STAGESHOW_SALES_TABLE.'.saleID';
			$innerSql .= ', COUNT(IF((NOT showState = "'.STAGESHOW_STATE_DELETED.'") AND (NOT perfState = "'.STAGESHOW_STATE_DELETED.'"), 1, NULL)) countValidTickets';
			$innerSql .= ' FROM '.STAGESHOW_SALES_TABLE;	
			$innerSql .= $this->GetJoinedTables($sqlFilters, __CLASS__);
			$innerSql .= ' GROUP BY '.STAGESHOW_SALES_TABLE.".saleID";
			
			$listSql  = "FROM ($innerSql) AS sales";
			$listSql .= ' WHERE ((sales.countValidTickets = 0))';
			$listSql  = 'SELECT sales.saleID '.$listSql;

			$sql  = 'DELETE FROM '.STAGESHOW_SALES_TABLE.' ';
			$sql .= "WHERE saleID IN ($listSql) ";
			$this->query($sql);

			$sql  = 'DELETE FROM '.STAGESHOW_PAYMENTS_TABLE.' ';
			$sql .= "WHERE saleID IN ($listSql) ";
			$this->query($sql);
		}

		function PurgeDeletedPerfomances($sqlFilters = array())
		{
			$innerSql  = 'SELECT '.STAGESHOW_PERFORMANCES_TABLE.'.perfID, showState, perfState';
			$innerSql .= ', COUNT(IF(ticketID IS NULL, NULL, 1)) countTickets';
			$innerSql .= ' FROM '.STAGESHOW_PERFORMANCES_TABLE;	
			$innerSql .= " LEFT JOIN ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';			
			$innerSql .= " LEFT JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.perfID='.STAGESHOW_PERFORMANCES_TABLE.'.perfID';
			$innerSql .= " LEFT JOIN ".STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.priceID='.STAGESHOW_PRICES_TABLE.'.priceID';
			$innerSql .= ' GROUP BY '.STAGESHOW_PERFORMANCES_TABLE.".perfID";

			$listSql  = "FROM ($innerSql) AS perfs";
			$listSql .= ' WHERE ((perfs.countTickets = 0)';
			$listSql .= ' AND ((showState = "'.STAGESHOW_STATE_DELETED.'") OR (perfState = "'.STAGESHOW_STATE_DELETED.'")))';
			$listSql  = 'SELECT perfs.perfID '.$listSql;

			$sql  = 'DELETE FROM '.STAGESHOW_PERFORMANCES_TABLE.' ';
			$sql .= "WHERE perfID IN ($listSql) ";
			$this->query($sql);
		}

		function PurgeDB($alwaysRun = false)	// TODO - TEST
		{
			// Call PurgeDB() in base class
			parent::PurgeDB($alwaysRun);
			
			// Delete all Sales where all tickets are for shows that are deleted
			$this->PurgeDeletedSales();

			// Delete orphaned Tickets entries (no corresponding Sale)
			$this->PurgeOrphans(array(STAGESHOW_TICKETS_TABLE.'.ticketID', STAGESHOW_SALES_TABLE.'.saleID'));
			
			// Delete all Performances marked as deleted that have no corresponding Sales
			$this->PurgeDeletedPerfomances();

			// Delete orphaned Prices entries (no performance)
			$this->PurgeOrphans(array(STAGESHOW_PRICES_TABLE.'.priceID', STAGESHOW_PERFORMANCES_TABLE.'.perfID'));
			
			// Delete Performances marked as deleted that have no Prices
			$condition = STAGESHOW_PERFORMANCES_TABLE.'.perfState = "'.STAGESHOW_STATE_DELETED.'"';
			$this->PurgeOrphans(array(STAGESHOW_PERFORMANCES_TABLE.'.perfID', STAGESHOW_PRICES_TABLE.'.perfID'), $condition);						
			
			// Delete Shows marked as deleted that have no Performance
			$condition = STAGESHOW_SHOWS_TABLE.'.showState = "'.STAGESHOW_STATE_DELETED.'"';
			$this->PurgeOrphans(array(STAGESHOW_SHOWS_TABLE.'.showID', STAGESHOW_PERFORMANCES_TABLE.'.showID'), $condition);						

			// Delete orphaned Verify entries
			$this->PurgeOrphans(array(STAGESHOW_VERIFYS_TABLE.'.verifyID', STAGESHOW_SALES_TABLE.'.saleID'));
			
			// Delete orphaned Discount Prices entries
			$this->PurgeOrphans(array(STAGESHOW_DISPRICES_TABLE.'.pricedisID', STAGESHOW_PRICES_TABLE.'.priceID'));
			$this->PurgeOrphans(array(STAGESHOW_TICKETSMETA_TABLE.'.ticketmetaID', STAGESHOW_TICKETS_TABLE.'.ticketID'));
		}
				
	    function GetDefaultOptions()
	    {
			// FUNCTIONALITY: DBase - StageShow - On Activate ... Set EMail Template Paths, and Summary EMail address
			$defOptions = array(
			    'EMailTemplatePath' => STAGESHOW_ACTIVATE_EMAIL_TEMPLATE_PATH,
			    'EMailSummaryTemplatePath' => STAGESHOW_ACTIVATE_EMAILSUMMARY_TEMPLATE_PATH,
		    	'ReserveEMailTemplatePath' => STAGESHOW_ACTIVATE_RESERVEEMAIL_TEMPLATE_PATH,
			    'SaleSummaryEMail' => '',
		    	'DBEMailTemplatePath' => STAGESHOW_ACTIVATE_CLIENTDBEMAIL_TEMPLATE_PATH,		    	
		    	'MinSeatSpace' => 2,
			);
							
			return $defOptions;			
		}
		

		function uninstall()
		{
			// FUNCTIONALITY: DBase - StageShow - Uninstall
      		$this->DropTable(STAGESHOW_DISCODES_TABLE);
 			$this->DropTable(STAGESHOW_DISPRESET_TABLE);
			$this->DropTable(STAGESHOW_DISPRICES_TABLE);
      		$this->DropTable(STAGESHOW_SEATING_TABLE);
      		$this->DropTable(STAGESHOW_ZONES_TABLE);
      		$this->DropTable(STAGESHOW_SPOOLER_TABLE);
      		$this->DropTable(STAGESHOW_TICKETSMETA_TABLE);
      		            			
      		$this->DropTable(STAGESHOW_PLANS_TABLE);
      		$this->DropTable(STAGESHOW_PRESETS_TABLE);
      		$this->DropTable(STAGESHOW_VERIFYS_TABLE);
           			
      		$this->DropTable(STAGESHOW_SHOWS_TABLE);
			$this->DropTable(STAGESHOW_PERFORMANCES_TABLE);      
			$this->DropTable(STAGESHOW_PRICES_TABLE);      
			$this->DropTable(STAGESHOW_TICKETS_TABLE);  			
			$this->DropTable(STAGESHOW_SETTINGS_TABLE);  			
			
			$this->DropTable(STAGESHOW_SESSIONS_TABLE);  			
						
			$this->DeleteCapability(STAGESHOWLIB_CAPABILITY_RESERVEUSER);
			$this->DeleteCapability(STAGESHOWLIB_CAPABILITY_VALIDATEUSER);
			$this->DeleteCapability(STAGESHOWLIB_CAPABILITY_SALESUSER);
			$this->DeleteCapability(STAGESHOWLIB_CAPABILITY_VIEWSALESUSER);
			$this->DeleteCapability(STAGESHOWLIB_CAPABILITY_ADMINUSER);
			$this->DeleteCapability(STAGESHOWLIB_CAPABILITY_SETUPUSER);
			$this->DeleteCapability(STAGESHOWLIB_CAPABILITY_VIEWSETTINGS);
			$this->DeleteCapability(STAGESHOWLIB_CAPABILITY_DEVUSER);
			
      		// Call uninstall() in base class
			parent::uninstall();
 		}
		
		
		//Returns an array of admin options
		function getOptions($childOptions = array()) 
		{
			$ourOptions = array(
		        'PerfExpireLimit' => 0,
		        'PerfExpireUnits' => 1,
		        
		        'Unused_EndOfList' => ''
			);
				
			$ourOptions = array_merge($ourOptions, $childOptions);
			
			$saveToDB = false;
			
			// Initialise settings array with default values
			$moreOptions = array(        
		        'loaded' => true,
		        
				'SetupUserRole' => STAGESHOW_DEFAULT_SETUPUSER,
		        
		        'SLen' => 0,                
		        'PLen' => 4,
		        
		        'MaxTicketQty' => STAGESHOW_MAXTICKETCOUNT,
		        
		        'Unused_EndOfList' => ''
			);
				
			$ourOptions = array_merge($ourOptions, $moreOptions);
			
			// Get current values from MySQL
			$currOptions = parent::getOptions($ourOptions);
			
			// Check for Upgrading from separate settings for Live and Test API Settings 
			if (isset($currOptions['PayPalAPITestUser']))
			{
				// FUNCTIONALITY: DBase - StageShow - Options - Merge PayPal settings after version 0.9.3
				// Update from Ver 0.9.3 or earlier setup
				
				$currOptions['PayPalAPIUser'] = $currOptions['PayPalAPILiveUser'];
				$currOptions['PayPalAPISig']  = $currOptions['PayPalAPILiveSig'];
				$currOptions['PayPalAPIPwd'] = $currOptions['PayPalAPILivePwd'];
				$currOptions['PayPalAPIEMail'] = $currOptions['PayPalAPILiveEMail'];
				
				$this->deleteColumn(STAGESHOW_PERFORMANCES_TABLE, 'perfPayPalTESTButtonID');
				$this->deleteColumn(STAGESHOW_PERFORMANCES_TABLE, 'perfPayPalLIVEButtonID');

				unset($currOptions['PayPalAPILiveUser']);
				unset($currOptions['PayPalAPILiveSig']);
				unset($currOptions['PayPalAPILivePwd']);
				unset($currOptions['PayPalAPILiveEMail']);
				
				unset($currOptions['PayPalAPITestUser']);
				unset($currOptions['PayPalAPITestSig']);
				unset($currOptions['PayPalAPITestPwd']);
				unset($currOptions['PayPalAPITestEMail']);
				
				$saveToDB = true;
			}
			
			if ($currOptions['SetupUserRole'] == '') 
			{
				$currOptions['SetupUserRole'] = STAGESHOW_DEFAULT_SETUPUSER;
				$saveToDB = true;
			}
				
			$ourOptions = array_merge($ourOptions, $currOptions);
			
			$this->adminOptions = $ourOptions;
			
			if ($saveToDB)
				$this->saveOptions();
			
			return $ourOptions;
		}
		
		function getTableDef($tableName)
		{
			$sql = parent::getTableDef($tableName);
			
			switch($tableName)
			{
				case STAGESHOW_SHOWS_TABLE:
					$sql .= '
						showName VARCHAR('.STAGESHOW_SHOWNAME_TEXTLEN.') NOT NULL,
						showState VARCHAR('.STAGESHOW_ACTIVESTATE_TEXTLEN.'), 
						showNote TEXT,
						showOpens DATETIME,
						showExpires DATETIME,
						showEMail VARCHAR('.STAGESHOW_FILENAME_TEXTLEN.') NOT NULL DEFAULT "",
						perfPrefix VARCHAR('.STAGESHOW_PERFPREFIX_TEXTLEN.') DEFAULT "PERF",						 
						showNotInListing BOOL DEFAULT FALSE,
						showHideQtyColumn BOOL DEFAULT FALSE,
						showDateTimeMode VARCHAR('.STAGESHOW_FILENAME_TEXTLEN.') NOT NULL DEFAULT "'.STAGESHOW_DATETIMEOPTION_ALL.'",
						showTicketTemplate VARCHAR('.STAGESHOW_FILENAME_TEXTLEN.') NOT NULL DEFAULT "",
						showGatewayIndex INT UNSIGNED DEFAULT 1,
					';
					break;
					
				case STAGESHOW_PERFORMANCES_TABLE:
					$sql .= '
						showID INT UNSIGNED NOT NULL,
						perfState VARCHAR('.STAGESHOW_ACTIVESTATE_TEXTLEN.'),
						perfDateTime DATETIME NOT NULL,
						perfRef VARCHAR('.STAGESHOW_PERFREF_TEXTLEN.') NOT NULL,
						perfSeats INT NOT NULL,
						perfOpens DATETIME,
						perfExpires DATETIME,				
						perfNote TEXT,
						perfNotePosn VARCHAR(6),
						perfSeatingID INT UNSIGNED DEFAULT 0,						
						perfDetailsTemplate VARCHAR('.STAGESHOW_FILENAME_TEXTLEN.') NOT NULL DEFAULT "",
						perfTicketTemplate VARCHAR('.STAGESHOW_FILENAME_TEXTLEN.') NOT NULL DEFAULT "",
						perfTicketSpec LONGTEXT NOT NULL DEFAULT "",
					';
					break;
					
				case STAGESHOW_PRICES_TABLE:
					$sql .= '
						perfID INT UNSIGNED NOT NULL,
						priceType VARCHAR('.STAGESHOW_PRICETYPE_TEXTLEN.') NOT NULL,
						priceValue DECIMAL(9,2) NOT NULL,
						priceNoOfSeats INT UNSIGNED NOT NULL DEFAULT 1,
						priceOrder INT UNSIGNED DEFAULT 0,
						priceVisibility VARCHAR('.STAGESHOW_PRICEVISIBILITY_TEXTLEN.') NOT NULL DEFAULT "'.STAGESHOW_VISIBILITY_PUBLIC.'",
						priceCheckoutMode VARCHAR(10) DEFAULT "normal",
						priceZoneID INT UNSIGNED DEFAULT 0,
					';
					break;
					
				case STAGESHOW_TICKETS_TABLE:
					$sql .= '
						saleID INT UNSIGNED NOT NULL,
						priceID INT UNSIGNED NOT NULL,
						ticketQty INT NOT NULL,
						ticketPaid DECIMAL(9,2) NOT NULL DEFAULT 0.0,
						ticketSeat TEXT,
						ticketsPrinted INT UNSIGNED DEFAULT 0,
						';
					break;
				
				case STAGESHOW_PLANS_TABLE:		
					$sql .= '
						planRef VARCHAR('.STAGESHOW_PLANREF_TEXTLEN.'),
						seatingID INT UNSIGNED NOT NULL DEFAULT 0,
					';
					break;
					
				case STAGESHOW_PRESETS_TABLE:		
					$sql .= '
						planID INT UNSIGNED NOT NULL,
						priceType VARCHAR('.STAGESHOW_PRICETYPE_TEXTLEN.') NOT NULL,
						priceValue DECIMAL(9,2) NOT NULL,
						priceVisibility VARCHAR('.STAGESHOW_PRICEVISIBILITY_TEXTLEN.') NOT NULL DEFAULT "'.STAGESHOW_VISIBILITY_PUBLIC.'",
						priceNoOfSeats INT UNSIGNED NOT NULL DEFAULT 1,
						priceCheckoutMode VARCHAR(10) DEFAULT "normal",
						priceZoneID INT UNSIGNED DEFAULT 0,
					';
					break;
					
				case STAGESHOW_VERIFYS_TABLE:		
					$sql .= '
						saleID INT UNSIGNED NOT NULL,
						verifyDateTime DATETIME,
						verifyLocation VARCHAR('.STAGESHOW_LOCATION_TEXTLEN.') NOT NULL,
						perfID INT UNSIGNED NOT NULL DEFAULT 0,
						ticketID INT UNSIGNED NOT NULL DEFAULT 0,
						ticketNo INT UNSIGNED NOT NULL DEFAULT 0,
					';
					break;					

				case STAGESHOW_DISCODES_TABLE:
					$sql .= '
						disRef VARCHAR('.STAGESHOW_DISCOUNTREF_TEXTLEN.') NOT NULL,
						disCodes TEXT NOT NULL DEFAULT "",
						disState VARCHAR('.STAGESHOW_ACTIVESTATE_TEXTLEN.'), 
					';
					break;
					
				case STAGESHOW_DISPRESET_TABLE:
					$sql .= '
						presetID INT UNSIGNED DEFAULT 0,
						disID INT UNSIGNED DEFAULT 0,
						disValue DECIMAL(9,2) NOT NULL,
					';
					break;
					
				case STAGESHOW_DISPRICES_TABLE:
					$sql .= '
						priceID INT UNSIGNED DEFAULT 0,
						disID INT UNSIGNED DEFAULT 0,
						disValue DECIMAL(9,2) NOT NULL,
					';
					break;
					
				case STAGESHOW_SEATING_TABLE:		
					$sql .= '
						seatingRef VARCHAR('.STAGESHOW_SEATINGREF_TEXTLEN.'),
						seatingVenue VARCHAR('.STAGESHOW_VENUENAME_TEXTLEN.') NOT NULL DEFAULT "",	 
						seatingTemplate MEDIUMTEXT,		
						seatingSeats INT UNSIGNED DEFAULT 0,	
						seatingDecodeTable VARCHAR('.STAGESHOW_FILENAME_TEXTLEN.') NOT NULL DEFAULT "",	
						seatDecodesObj LONGTEXT,
						seatingImportDateTime DATETIME,
						seatingImportFile VARCHAR('.STAGESHOW_FILENAME_TEXTLEN.') NOT NULL DEFAULT "",
					';
					break;
					
				case STAGESHOW_ZONES_TABLE:		
					$sql .= '
						seatingID INT UNSIGNED NOT NULL,
						zoneRef VARCHAR('.STAGESHOW_ZONEREF_TEXTLEN.'),
						zoneSpec MEDIUMTEXT,
						zoneAllocSeats BOOL DEFAULT TRUE, 
						zoneSeats INT UNSIGNED DEFAULT 0,
						zoneOrder INT UNSIGNED DEFAULT 0,
					';
					break;
					
				case STAGESHOW_SALES_TABLE:
					$sql .= '
						saleDiscountCode TEXT NOT NULL DEFAULT "",
						salePrinted DATETIME DEFAULT NULL,
					';
					break;
					
				case STAGESHOW_TICKETSMETA_TABLE:
					$sql .= '
						ticketID INT UNSIGNED DEFAULT 0,
						meta_key VARCHAR(255) DEFAULT NULL,
						meta_value LONGTEXT,
					';				
					break;
					
				case STAGESHOW_SPOOLER_TABLE:
					$sql .= '
						ticketID INT UNSIGNED DEFAULT 0,
						seatNo INT UNSIGNED DEFAULT 0,
						printDevice VARCHAR(32) NOT NULL DEFAULT "", 
						printDateTime DATETIME,
						printContent MEDIUMTEXT NOT NULL DEFAULT "",
					';
					break;
			
			}
			
			return $sql;
		}
		
		
		
		function clearAll()
		{
			parent::clearAll();

			$this->DropTable(STAGESHOW_SHOWS_TABLE);
			$this->DropTable(STAGESHOW_PERFORMANCES_TABLE);
			$this->DropTable(STAGESHOW_PRICES_TABLE);
			$this->DropTable(STAGESHOW_TICKETS_TABLE);

			$this->DropTable(STAGESHOW_PLANS_TABLE);
			$this->DropTable(STAGESHOW_PRESETS_TABLE);			
			$this->DropTable(STAGESHOW_VERIFYS_TABLE);

			$this->DropTable(STAGESHOW_DISCODES_TABLE);
			$this->DropTable(STAGESHOW_DISPRESET_TABLE);
			$this->DropTable(STAGESHOW_DISPRICES_TABLE);
			$this->DropTable(STAGESHOW_SEATING_TABLE);
			$this->DropTable(STAGESHOW_ZONES_TABLE);
			$this->DropTable(STAGESHOW_SPOOLER_TABLE);
			$this->DropTable(STAGESHOW_TICKETSMETA_TABLE);			
		}
		
		function createDB($dropTable = false)
   		{
      		global $wpdb;
     
			if ($dropTable && isset($this->adminOptions['showName']))
			{
				unset($this->adminOptions['showName']);
				$this->saveOptions();
			}
			
			parent::createDB($dropTable);

			$this->createDBTable(STAGESHOW_TICKETS_TABLE, 'ticketID', $dropTable);

			// ------------------- STAGESHOW_SHOWS_TABLE -------------------
			if ($dropTable)
			{
				$addingShowsTable = false;			
			}
			else
			{
				if ($this->tableExists(STAGESHOW_PERFORMANCES_TABLE)) 
					$addingShowsTable = !$this->tableExists(STAGESHOW_SHOWS_TABLE);
				else
					$addingShowsTable = false;	
			}
				
			$this->createDBTable(STAGESHOW_SHOWS_TABLE, 'showID', $dropTable);
			
			// StageShow to StageShow-Plus Update
			if ($addingShowsTable && isset($this->adminOptions['showName']))
			{
				// See if we have a show configured for StageShow before adding SHOWS table
				if ($this->adminOptions['showName'] != '')
				{
					$showName = $this->adminOptions['showName'];
					$showState = $this->adminOptions['showState'];
					
					$this->AddShow($showName, $showState);
					
					if (isset($this->adminOptions['showName']))
					{
						unset($this->adminOptions['showName']);
						unset($this->adminOptions['showState']);
						$this->saveOptions();
					}
					
				}
			}
			
			$this->createDBTable(STAGESHOW_PERFORMANCES_TABLE, 'perfID', $dropTable);
			$this->createDBTable(STAGESHOW_PRICES_TABLE, 'priceID', $dropTable);

			$this->createDBTable(STAGESHOW_PRESETS_TABLE, 'presetID',  $dropTable);
			$this->createDBTable(STAGESHOW_PLANS_TABLE,   'planID',    $dropTable);
			$this->createDBTable(STAGESHOW_VERIFYS_TABLE, 'verifyID',  $dropTable);

			$this->createDBTable(STAGESHOW_DISCODES_TABLE, 'disID', $dropTable);
			$this->createDBTable(STAGESHOW_DISPRESET_TABLE, 'presetdisID',  $dropTable);
			$this->createDBTable(STAGESHOW_DISPRICES_TABLE, 'pricedisID',  $dropTable);
			$this->createDBTable(STAGESHOW_SEATING_TABLE, 'seatingID', $dropTable);
			$this->createDBTable(STAGESHOW_ZONES_TABLE, 'zoneID',    $dropTable);
			$this->createDBTable(STAGESHOW_SPOOLER_TABLE, 'printID',    $dropTable);
			$this->createDBTable(STAGESHOW_TICKETSMETA_TABLE, 'ticketmetaID',    $dropTable);			
		}
			
		function GetShowsSettings($extraFields = '')
		{					
			$selectFields  = 'showName,perfState,perfDateTime,perfSeats,priceType,priceValue';
			$selectFields .= ',showOpens,showExpires,perfOpens,perfExpires,priceVisibility,priceNoOfSeats';
			if ($extraFields != '')
			{
				$selectFields .= ','.$extraFields;
			}
			
			$sql  = 'SELECT '.$selectFields.' FROM '.STAGESHOW_SHOWS_TABLE.' ';
			$sql .= 'LEFT JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.STAGESHOW_SHOWS_TABLE.'.showID ';
			$sql .= 'LEFT JOIN '.STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.perfID='.STAGESHOW_PERFORMANCES_TABLE.'.perfID ';
			$sql .= 'ORDER BY showName, perfDateTime, priceType ';
				
			$results = $this->get_results($sql);
			
			for ($i=0; $i<count($results); $i++)
			{
				$results[$i]->defaultExpiresTime = $this->DefaultExpiresTime($results[$i]->perfDateTime);
			}
			
			return $results;
		}


		
		function CreateNewPerformance(&$rtnMsg, $showID, $perfDateTime = null, $perfRef = '', $perfSeats = -1)
		{
			if ($showID <= 0) 
			{
				$rtnMsg = __('Internal Error - showID', 'stageshow');
				return 0;
			}
			
			if ($perfDateTime == null)
			{
				// Get last performance entry
				$perfsList = $this->GetPerformancesListByShowID($showID);
				
				if (count($perfsList) > 0)
				{
					$lastPerf = $perfsList[0];
					
					// Copy to new performance
					$perfID = $lastPerf->perfID;
					
					if (StageShowLibMigratePHPClass::Safe_strtotime($lastPerf->perfDateTime) > current_time('timestamp'))
					{
						$sql = 'SELECT * FROM '.STAGESHOW_PERFORMANCES_TABLE.' WHERE perfID = '.$perfID;
						$dbEntries = $this->get_results($sql);
						$dbKeys = '(';
						$dbValues = ' VALUES (';
						$sep1 = '';
						
						$newPerfEntry = $this->AddDuplicatePerfFields($perfID, $dbEntries[0]);
						
						foreach ($newPerfEntry as $dbKey => $dbValue)
						{
							if ($dbKey == 'perfID') continue;
							$dbKeys .= $sep1.$dbKey;
							
							if (is_null($dbValue))
							{
								$dbValues .= $sep1.'NULL';
							}
							else
							{
								$dbValues .= $sep1.'"'.addslashes($dbValue).'"';							
							}
							
							$sep1 = ', ';					
						}
						$dbKeys .= ") ";
						$dbValues .= ") ";
						
						$sql  = 'INSERT INTO '.STAGESHOW_PERFORMANCES_TABLE;
						$sql .= $dbKeys.$dbValues;
						$this->query($sql);
						$perfID = $this->GetInsertId();					
						
						$rtnMsg = __('New Performance Added', 'stageshow');
						
						return $perfID;
					}
				}
				
				// For performance use 8pm in 4 weeks time)
				$perfDateTime = date(StageShowLibDBaseClass::MYSQL_DATE_FORMAT, StageShowLibMigratePHPClass::Safe_strtotime('+4 week', current_time('timestamp'))).' 20:00:00';
			}
			
			if ($perfRef == '')
			{
				$perfRef = $this->GetUniquePerfID($showID);
			}
			
			$perfState = '';
			$perfID = 0;
		
			// Add performance to database					
			// Give performance unique Ref - Check what default reference IDs already exist in database
			$perfID = $this->AddPerformance($showID, $perfState, $perfDateTime, $perfRef, $perfSeats);
			if ($perfID == 0)
				$rtnMsg = __('Performance Reference is not unique', 'stageshow');
			else
				$rtnMsg = __('New Performance Added', 'stageshow');
			
			if ($perfID <= 0)
				return 0;
				
			// Use Price Plan for New Performance
			$planID = StageShowLibHTTPIO::GetRequestedInt('pricePlan', 0);
			if ($planID > 0)
			{
				$results = $this->GetPricePlansListByPlanID($planID);
				
				$this->AddPlanToPerformance($perfID, $results);
			}
				
			return $perfID;
		}
		
		
		function AddADay($dateTime)
		{
			// Use last date/time plus 1 day for new performance					
			$timestamp = StageShowLibMigratePHPClass::Safe_strtotime($dateTime);
			$dateTime = date(StageShowDBaseClass::MYSQL_DATETIME_FORMAT, StageShowLibMigratePHPClass::Safe_strtotime('+1 day', $timestamp));
			return $dateTime;
		}
		
		function AddDuplicatePerfFields($perfID, $lastPerf)
		{
			// Use last date/time plus 1 day for new performance					
			$lastPerf->perfDateTime = $this->AddADay($lastPerf->perfDateTime);
				
			$lastPerf->perfRef = $this->GetUniquePerfID($lastPerf->showID);
			
			if (!is_null($lastPerf->perfExpires) && (StageShowLibMigratePHPClass::Safe_strlen($lastPerf->perfExpires) > 1))
			{
				$lastPerf->perfExpires = $this->AddADay($lastPerf->perfExpires);
			}
			
			if (!is_null($lastPerf->perfOpens) && (StageShowLibMigratePHPClass::Safe_strlen($lastPerf->perfOpens) > 1))
			{
				$lastPerf->perfOpens = $this->AddADay($lastPerf->perfOpens);
			}
			
			return $lastPerf;
		}
			
		
		function GetEmail($ourOptions, $emailRole = '')
		{
			// FUNCTIONALITY: DBase - GetEmail - Uses AdminEMail (otherwise WP admin email) - Optionally with OrganisationID from settings
			// StageShow ignores the "emailRole" parameter and always uses the AdminEMail entry
			$ourEmail = '';
			$optionGatewaySuffix = $this->gatewayObj->GetOptionGatewaySuffix();
			
			if (StageShowLibMigratePHPClass::Safe_strlen($ourOptions['AdminEMail'.$optionGatewaySuffix]) > 0)
				$ourEmail = $ourOptions['AdminEMail'.$optionGatewaySuffix];
			else
				$ourEmail = get_bloginfo('admin_email');
				
			// Get from email address from settings
			if ($ourOptions['AdminID'.$optionGatewaySuffix] !== '')
				$ourEmail = $ourOptions['AdminID'.$optionGatewaySuffix].' <'.$ourEmail.'>';
			else if ($ourOptions['OrganisationID'] !== '')
				$ourEmail = $ourOptions['OrganisationID'].' <'.$ourEmail.'>';
				
			return $ourEmail;
		}
		

		function StateActiveText($state)
		{
			switch ($state)
			{
				case STAGESHOW_STATE_DELETED:
					return __("DELETED", 'stageshow');
					
				default:
					if ($this->IsStateActive($state))
						return __("Active", 'stageshow');
					else
						return __("INACTIVE", 'stageshow');
					break;
			}
			
			return '';
		}

		function GetSortedShowsList($whereSQL = '')
		{
			$selectFields  = STAGESHOW_SHOWS_TABLE.'.*';
			$selectFields .= ' , MAX('.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime) AS maxPerfDateTime';
			$selectFields .= ' , COUNT('.STAGESHOW_PRICES_TABLE.'.priceValue) AS noOfPrices';
			
			$subSQL = "SELECT $selectFields FROM ".STAGESHOW_SHOWS_TABLE;
			$subSQL .= " LEFT JOIN ".STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.STAGESHOW_SHOWS_TABLE.'.showID';
			$subSQL .= " LEFT JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.perfID='.STAGESHOW_PERFORMANCES_TABLE.'.perfID';
			
			$subSQL .= ' WHERE '.STAGESHOW_SHOWS_TABLE.'.showState<>"'.STAGESHOW_STATE_DELETED.'"';
			
			$subSQL .= ' GROUP BY '.STAGESHOW_SHOWS_TABLE.'.showID';			
			$subSQL .= ' ORDER BY maxPerfDateTime DESC';
			$subSQL .= ' , showName ASC';
			
			$sql = 'SELECT * FROM ('.$subSQL.') AS sub';
			if ($whereSQL != '') $sql .= ' WHERE '.$whereSQL;
			
			$results = $this->get_results($sql);

			return $results;
		}

		
		function GetOverviewByShowID($showID)
		{
			return $this->GetOverview($showID);
		}
		
		function GetOverview($showID = '')
		{
			if ($showID == '') 
			{
				$selectId  = STAGESHOW_SHOWS_TABLE.'.showID ';
			}
			else
			{
				$selectId  = STAGESHOW_PERFORMANCES_TABLE.'.perfID ';
			}
			
			$statusOptions  = '((saleStatus="'.PAYMENT_API_SALESTATUS_COMPLETED.'")';
			$statusOptions .= ' OR ';
			$statusOptions .= '(saleStatus="'.PAYMENT_API_SALESTATUS_RESERVED.'"))';
			
			// Query to get the total tickets for each sale (all shows/performances)
			$sqlSaleTickets  = 'SELECT '.STAGESHOW_SALES_TABLE.'.saleID ';
			$sqlSaleTickets .= ',SUM(ticketQty * priceNoOfSeats) AS totalTicketsPerSale ';
			$sqlSaleTickets .= 'FROM '.STAGESHOW_SALES_TABLE.' ';
			$sqlSaleTickets .= 'JOIN '.STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.saleID='.STAGESHOW_SALES_TABLE.'.saleID ';
			$sqlSaleTickets .= 'LEFT JOIN '.STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceID='.STAGESHOW_TICKETS_TABLE.'.priceID ';
			$sqlSaleTickets .= 'LEFT JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_PRICES_TABLE.'.perfID ';
			$sqlSaleTickets .= 'LEFT JOIN '.STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID ';
			$sqlSaleTickets .= 'GROUP BY '.STAGESHOW_SALES_TABLE.'.saleID ';
			//$results = $this->get_results($sqlSaleTickets, true);	

			// Query to portion of Fees, Donations etc across shows or performances
			$sqlSaleExtras  = 'SELECT '.$selectId;
			$sqlSaleExtras .= ','.$this->TotalValuesField();
			$sqlSaleExtras .= ', SUM(IF('.$statusOptions.', ((salePayments.saleFee * ticketQty * priceNoOfSeats)/totalTicketsPerSale), 0)) AS Total_saleFee ';
			$sqlSaleExtras .= ', SUM(IF('.$statusOptions.', ((saleExtraDiscount * ticketQty * priceNoOfSeats)/totalTicketsPerSale), 0)) AS Total_saleExtraDiscount ';
			$sqlSaleExtras .= ', SUM(IF('.$statusOptions.', ((saleTransactionFee * ticketQty * priceNoOfSeats)/totalTicketsPerSale), 0)) AS Total_saleTransactionFee ';
			$sqlSaleExtras .= ', SUM(IF('.$statusOptions.', ((salePostage * ticketQty * priceNoOfSeats)/totalTicketsPerSale), 0)) AS Total_salePostage ';
			$sqlSaleExtras .= ', SUM(IF('.$statusOptions.', ((saleDonation * ticketQty * priceNoOfSeats)/totalTicketsPerSale), 0)) AS Total_saleDonation ';
			$sqlSaleExtras .= ', SUM(IF('.$statusOptions.', (((salePostage + saleTransactionFee) * ticketQty * priceNoOfSeats)/totalTicketsPerSale), 0)) AS Total_saleExtras ';
			$sqlSaleExtras .= ', SUM(IF('.$statusOptions.', (ticketPaid), 0)) AS Total_ticketPaid ';
			$sqlSaleExtras .= 'FROM '.STAGESHOW_SALES_TABLE.' ';
			$sqlSaleExtras .= $this->GetPaymentsSQL();
			$sqlSaleExtras .= 'JOIN '.STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.saleID='.STAGESHOW_SALES_TABLE.'.saleID ';
			$sqlSaleExtras .= 'LEFT JOIN '.STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceID='.STAGESHOW_TICKETS_TABLE.'.priceID ';
			$sqlSaleExtras .= 'LEFT JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_PRICES_TABLE.'.perfID ';
			if ($showID == '') $sqlSaleExtras .= 'LEFT JOIN '.STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID ';
			$sqlSaleExtras .= 'LEFT JOIN ('.$sqlSaleTickets.') AS ticketTotals ON ticketTotals.saleID='.STAGESHOW_SALES_TABLE.'.saleID ';
			if ($showID != '') 
			{
				$sqlSaleExtras .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.showID="'.$showID.'"';	
			}
			$sqlSaleExtras .= 'GROUP BY '.$selectId;				
			//$results = $this->get_results($sqlSaleExtras, true);	

			if ($showID == '') 
			{
				$sqlPerfs  = 'SELECT '.$selectId;
//				$sqlPerfs .= ', showName ';
				$sqlPerfs .= ', COUNT(perfDateTime) AS perfCount ';				
//				$sqlPerfs .= ', SUM(totalLockedPerSale) AS Total_lockouts ';				
				$sqlPerfs .= 'FROM '.STAGESHOW_SHOWS_TABLE.' ';
				$sqlPerfs .= 'LEFT JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.STAGESHOW_SHOWS_TABLE.'.showID ';
				$sqlPerfs .= 'GROUP BY '.$selectId;				
				//$results = $this->get_results($sqlPerfs, true);	
			}
			
			$sqlOverview  = 'SELECT *';
			if ($showID != '') 
			{
				$sqlOverview .= ', '.STAGESHOW_PERFORMANCES_TABLE.'.perfID AS rowPerfID';
			}
			$sqlOverview .= ', (Total_ticketPaid + Total_saleExtraDiscount + Total_saleTransactionFee + Total_salePostage - Total_saleFee + Total_saleDonation) AS Total_netSales ';			
			$sqlOverview .= ' FROM '.STAGESHOW_SHOWS_TABLE;
			if ($showID == '') 
			{
				$sqlOverview .= ' LEFT JOIN ('.$sqlSaleExtras.') AS extras ON extras.showID='.STAGESHOW_SHOWS_TABLE.'.showID ';
				$sqlOverview .= ' LEFT JOIN ('.$sqlPerfs.') AS perfs ON perfs.ShowID='.STAGESHOW_SHOWS_TABLE.'.showID ';
			}
			else
			{
				$sqlOverview .= ' LEFT JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.STAGESHOW_SHOWS_TABLE.'.showID ';
				$sqlOverview .= ' LEFT JOIN ('.$sqlSaleExtras.') AS extras ON extras.perfID='.STAGESHOW_PERFORMANCES_TABLE.'.perfID ';
				$sqlOverview .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.showID="'.$showID.'"';	
			}
			
			$results = $this->get_results($sqlOverview, true);	
			
			return $results;
		}
		
		function GetAllShowsList()
		{
			$this->showJoined = true;
			$this->perfJoined = true;
			
			return $this->GetShowsList();
		}
		
		function GetShowNameAndID($showID = 0)
		{
			$sql = "SELECT showID, showName FROM ".STAGESHOW_SHOWS_TABLE;
			if ($showID != 0)
			{
				$sql .= ' WHERE '.STAGESHOW_SHOWS_TABLE.'.showID='.$showID;;
			}
			
			$results = $this->get_results($sql);

			if ($showID != 0)
			{
				if (count($results) <= 0) return null;
				$results = $results[0];
			}
			
			return $results;
		}
		
		function GetShowsList($showID = 0)
		{
			$selectFields  = STAGESHOW_SHOWS_TABLE.'.*';
			$selectFields .= ', MAX('.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime) AS maxPerfDateTime';

			$sqlFilters['showID'] = $showID;
			$sqlFilters['groupBy'] = 'showID';
			$sqlFilters['JoinType'] = 'RIGHT JOIN';
			
			if (isset($sqlFilters['groupBy']))	
			{			
				$totalSalesField = $this->TotalSalesField($sqlFilters);
				if ($totalSalesField != '')
					$selectFields .= ','.$totalSalesField;
			}
			
			$sql = "SELECT $selectFields FROM ".STAGESHOW_SHOWS_TABLE;
			$sql .= " LEFT JOIN ".STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.STAGESHOW_SHOWS_TABLE.'.showID';
			$sql .= " LEFT JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.perfID='.STAGESHOW_PERFORMANCES_TABLE.'.perfID';
			$sql .= " LEFT JOIN ".STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.priceID='.STAGESHOW_PRICES_TABLE.'.priceID';
			$sql .= " LEFT JOIN ".STAGESHOW_SALES_TABLE.' ON '.STAGESHOW_SALES_TABLE.'.saleID='.STAGESHOW_TICKETS_TABLE.'.saleID';	// MJS: Check SALES Table Fields
			
			// Add SQL filter(s)
			$sql .= $this->GetWhereSQL($sqlFilters);
			$sql .= $this->GetOptsSQL($sqlFilters);
			
			$sql .= ' ORDER BY maxPerfDateTime';
			
			$results = $this->get_results($sql, true, $sqlFilters);

			return $results;
		}
/*		
		function UpdateSettings($result, $tableId, $settingId, $indexId, $index)
		{
			$newVal = StageShowLibUtilsClass::GetHTTPTextElem('post', $settingId.$index); 
			if ($newVal == $result->$settingId)
				return;
				
			$this->UpdateASetting($newVal, $tableId, $settingId, $indexId, $index);			
		}
*/		
		function UpdateASetting($newVal, $tableId, $settingId, $indexId, $index)
		{
			$sql  = 'UPDATE '.$tableId;
			$sql .= ' SET '.$settingId.'="'.esc_sql($newVal).'"';
			$sql .= ' WHERE '.$indexId.'='.$index;;
			 
			$this->query($sql);	
		}
		
		function IsShowNameUnique($showName)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_SHOWS_TABLE;
			$sql .= ' WHERE '.STAGESHOW_SHOWS_TABLE.'.showName="'.esc_sql($showName).'"';
			
			$showsEntries = $this->get_results($sql);
			return (count($showsEntries) > 0) ? false : true;
		}
		
		function SetShowActivated($showID, $showState = STAGESHOW_STATE_ACTIVE)
		{
			$sql  = 'UPDATE '.STAGESHOW_SHOWS_TABLE;
			$sql .= ' SET showState="'.$showState.'"';
			$sql .= ' WHERE '.STAGESHOW_SHOWS_TABLE.'.showID='.$showID;;

			$this->query($sql);	
			return "OK";							
		}
		
		function CanAddShow()
		{
			return true;
		}
		
		function AddShow($showName = '', $showState = STAGESHOW_STATE_ACTIVE)
		{
			// FUNCTIONALITY: Shows - StageShow - Add Show
			// Check if a show can be added
			if (!$this->CanAddShow())
				return 0;
				
	      	if ($showName === '')
	      	{
				$newNameNo = 1;
				while (true)
				{
					$showName = __('Unnamed Show', 'stageshow');
					if ($newNameNo > 1) $showName .= ' '.$newNameNo;
						
					if ($this->IsShowNameUnique($showName))
						break;
					$newNameNo++;
				}
			}
			else
			{
				if (!$this->IsShowNameUnique($showName))
					return 0;	// Error - Show Name is not unique
			}
						
			$sql = 'INSERT INTO '.STAGESHOW_SHOWS_TABLE.'(showName, showState) VALUES("'.esc_sql($showName).'", "'.$showState.'")';
			$this->query($sql);	
					
     		return $this->GetInsertId();
		}
				
		function UpdateShowName($showID, $showName)
		{
			$status = "OK";
			
			if (!$this->IsShowNameUnique($showName))
				return "ERROR";
				
			$sql  = 'UPDATE '.STAGESHOW_SHOWS_TABLE;
			$sql .= ' SET showName="'.esc_sql($showName).'"';
			$sql .= ' WHERE '.STAGESHOW_SHOWS_TABLE.'.showID='.$showID;;
			$this->query($sql);	

			$this->ClearTicketSpecByShowID($showID);
			
			return $status;
		}

			
		function CanDeleteShow($showEntry)
		{
			$lastPerfDate = $this->GetLastPerfDateTime($showEntry->showID);

			if (($perfDate = StageShowLibMigratePHPClass::Safe_strtotime($lastPerfDate)) === false)
				$canDelete = false;
			else
			{
				$dateDiff = StageShowLibMigratePHPClass::Safe_strtotime("now")-$perfDate;
				$canDelete = ($dateDiff > 60*60*24);
			}

			$showSales = $showEntry->totalQty;
			$canDelete |= ($showSales == 0);		
			
			if ($this->getDbgOption('Dev_ShowMiscDebug') == 1) 
			{
				StageShowLibEscapingClass::Safe_EchoHTML("CanDeleteShow(".$showEntry->showID.") returns $canDelete <br>\n");
			}
			
			return $canDelete;		
		}	
		
		function renameColumn($table_name, $oldColName, $newColName)
		{
 			$colSpec = $this->getColumnSpec($table_name, $oldColName);
			if (!isset($colSpec->Field))
				return __("DB Error", 'stageshow').": $oldColName ".__("Column does not exist", 'stageshow');
				
			$sql = "ALTER TABLE $table_name CHANGE $oldColName $newColName ".$colSpec->Type;
			if ($colSpec->Null == 'NO')
				$sql .= " NOT NULL";
			if ($colSpec->Default != '')
				$sql .= " DEFAULT = '".$colSpec->Default."'";

			$this->query($sql);	
			return "OK";							
		}

		function GetAllPerformancesList()
		{
			return $this->GetPerformancesList();
		}
				
		function GetPerformancesListByShowID($showID)
		{
			$selectFields  = STAGESHOW_SHOWS_TABLE.'.showID';
			$selectFields .= ', perfID, perfRef, perfDateTime';
			
			$sql = "SELECT $selectFields FROM ".STAGESHOW_PERFORMANCES_TABLE;
			$sql .= " LEFT JOIN ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.$showID;
			$sql .= ' ORDER BY '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime DESC';
			
			$perfsListArray = $this->get_results($sql);

			return $perfsListArray;
		}
				
		function GetPerformancesDetailsByShowID($showID)
		{
			$sqlFilters['showID'] = $showID;
			return $this->GetPerformancesList($sqlFilters);
		}

		function CanDeletePerformance($perfsEntry)
		{
			$perfDateTime = $perfsEntry->perfDateTime;
				
			// Performances can be deleted if there are no tickets sold/in checkout 
			// or if it is 24 hours after start date/time
			if (($perfDate = StageShowLibMigratePHPClass::Safe_strtotime($perfDateTime)) === false)
				$canDelete = false;
			else
			{
				$dateDiff = StageShowLibMigratePHPClass::Safe_strtotime("now")-$perfDate;
				$canDelete = ($dateDiff > 60*60*24);
			}
			
			$perfSales = $perfsEntry->totalQty;
			$canDelete |= ($perfSales == 0);
			
			return $canDelete;
		}
		
		function SetPerfActivated($perfID, $perfState = STAGESHOW_STATE_ACTIVE)
		{
			$sqlFilters['perfID'] = $perfID;
				 
			$sql  = 'UPDATE '.STAGESHOW_PERFORMANCES_TABLE;
			$sql .= ' SET perfState="'.$perfState.'"';
			$sql .= $this->GetWhereSQL($sqlFilters);

			$this->query($sql);	
			return "OK";							
		}
		
		private function GetLastPerfDateTime($showID = 0)
		{
			$sql  = 'SELECT MAX(perfDateTime) AS LastPerf FROM '.STAGESHOW_PERFORMANCES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.$showID;
			
			$results = $this->get_results($sql);
			
			if (count($results) == 0) return 0;
			return $results[0]->LastPerf;
		}
		
		function GetUniquePerfID($showId, $perfPrefix = 'PERF')
		{
			$sql  = 'SELECT perfPrefix FROM '.STAGESHOW_SHOWS_TABLE;
			$sql .= ' WHERE '.STAGESHOW_SHOWS_TABLE.'.showID="'.$showId.'"';	
			
			$prefixList = $this->get_results($sql);
			if (count($prefixList) > 0)
			{
				$perfPrefix = $prefixList[0]->perfPrefix;
			}
			
			$perfRefNo = 1;
			while (true)
			{
				// Query Database for proposed Performance Ref until we find one that doesn't already exist
				$perfRef = $perfPrefix.$perfRefNo;
				if ($this->IsPerfRefUnique($perfRef, $showId))
					break;
				$perfRefNo++;
			}
			
			return $perfRef;
		}

		
		function IsPerfRefUnique($perfRef, $showId)
		{
			$sql  = 'SELECT COUNT(*) AS MatchCount FROM '.STAGESHOW_PERFORMANCES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.perfRef="'.esc_sql($perfRef).'"';
			$sql .= ' AND '.STAGESHOW_PERFORMANCES_TABLE.'.showId="'.$showId.'"';
			 
			$perfsCount = $this->get_results($sql);
			return ($perfsCount[0]->MatchCount > 0) ? false : true;
		}
		
		function CanAddPerformance()
		{
			// PLen in options is ignored ... just return true
			return true;
		}

		function GetAllPlansList()
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_PLANS_TABLE;
			
			$presetsListArray = $this->get_results($sql);
			
			return $presetsListArray;
		}
		
		function AddPerformance($showID, $perfState, $perfDateTime, $perfRef, $perfSeats)
		{
			if ($perfRef === '')
			{
				$perfRefNo = $this->GetUniquePerfID($showID);
			}
			else
			{
				if (!$this->IsPerfRefUnique($perfRef, $showID))
					return 0;	// Error - Performance Reference is not unique
			}
			
			$sql  = 'INSERT INTO '.STAGESHOW_PERFORMANCES_TABLE.'(showID, perfState, perfDateTime, perfRef, perfSeats)';
			$sql .= ' VALUES('.$showID.', "'.$perfState.'", "'.$perfDateTime.'", "'.esc_sql($perfRef).'", "'.$perfSeats.'")';
			 
			$this->query($sql);
			
     		return $this->GetInsertId();
		}
				
		function UpdatePerformanceTime($perfID, $newPerfDateTime)
		{
			$sqlSET = 'perfDateTime="'.$newPerfDateTime.'"';
			return $this->UpdatePerformanceEntry($perfID, $sqlSET);
		}
				
		function UpdatePerformanceRef($perfID, $newPerfRef, $showID)
		{
			if (!$this->IsPerfRefUnique($newPerfRef, $showID))
			{
				return "ERROR";
			}
				
			$sqlSET = 'perfRef="'.esc_sql($newPerfRef).'"';
			return $this->UpdatePerformanceEntry($perfID, $sqlSET);
		}
				
		function UpdatePerformanceSeats($perfID, $newPerfSeats)
		{
			$sqlSET = 'perfSeats="'.$newPerfSeats.'"';
			return $this->UpdatePerformanceEntry($perfID, $sqlSET);
		}								
				
		function UpdatePerformanceEntry($perfID, $sqlSET)
		{
			$sqlFilters['perfID'] = $perfID;
				 
			$sql  = 'UPDATE '.STAGESHOW_PERFORMANCES_TABLE;
			$sql .= ' SET '.$sqlSET;
			$sql .= $this->GetWhereSQL($sqlFilters);

			$this->query($sql);	
			return "OK";							
		}
		
		function IsPriceValid($newPriceValue, $result)
		{
			// Verify that the price value is not empty
			if (StageShowLibMigratePHPClass::Safe_strlen($newPriceValue) == 0)
			{
				return __('Price Not Specified', 'stageshow');
			}

			// Verify that the price value is a numeric value
			if (!is_numeric($newPriceValue))
			{
				return __('Invalid Price Entry', 'stageshow');
			}

			// Verify that the price value is positive!
			if ($newPriceValue < 0.0)
			{
				return __('Price Entry cannot be negative', 'stageshow');
			}
			
			return '';
		}

		function IsPriceTypeUnique($perfID, $priceType)
		{
			$sql  = 'SELECT COUNT(*) AS MatchCount FROM '.STAGESHOW_PRICES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_PRICES_TABLE.'.priceType="'.$priceType.'"';
			$sql .= ' AND '.STAGESHOW_PRICES_TABLE.'.perfID="'.$perfID.'"';

			$pricesEntries = $this->get_results($sql);
			return ($pricesEntries[0]->MatchCount > 0) ? false : true;
		}
		
		function AddPrice($perfID, $priceParams = null, $skipChecks = false)
		{
      		if ($priceParams == null)
      		{
				$priceParams = new stdClass;
			}
      		
			// Allocated Seating - Parse $perfID into PerfID and ZoneID
			$perfIDParts = explode('-',$perfID);
			if (count($perfIDParts) > 1)
			{
				$priceParams->priceZoneID = $perfIDParts[1];
				$perfID = $perfIDParts[0];
			}
			
     		if ($perfID <= 0) 
     		{
     			return 0;
     		}
      
      		if ($priceParams == null)
      		{
				$priceParams = new stdClass;
			}
      		
      		$priceType = isset($priceParams->priceType) ? $priceParams->priceType : '';
      		$priceValue = isset($priceParams->priceValue) ? $priceParams->priceValue : STAGESHOW_PRICE_UNKNOWN;
			
			unset($priceParams->priceType);
			unset($priceParams->priceValue);
			
      		if ($priceType === '')
      		{
				$priceTypeNo = 1;
				while (true)
				{
					// Query Database for proposed Performance Ref until we find one that doesn't already exist
					$priceType = 'TYPE'.$priceTypeNo;
					if ($this->IsPriceTypeUnique($perfID, $priceType))
						break;
					$priceTypeNo++;
				}
			}
			else if (!$skipChecks)
			{
				if (!$this->IsPriceTypeUnique($perfID, $priceType))
				{
					return 0;	// Error - Performance Reference is not unique					
				}
			}
			
			$sql  = 'INSERT INTO '.STAGESHOW_PRICES_TABLE.' (perfID, priceType, priceValue)';
			$sql .= ' VALUES('.$perfID.', "'.esc_sql($priceType).'", "'.$priceValue.'")';
			 			
			$this->query($sql);
			
     		$priceID = $this->GetInsertId();
     		
     		if ($priceID > 0)
     		{
				foreach ($priceParams as $priceFieldID => $priceParam)
				{
					if (StageShowLibMigratePHPClass::Safe_substr($priceFieldID, 0, 5) == 'price')
					{
						$this->UpdatePrice($priceID, $priceFieldID, $priceParam);
					}
				}
			}
     		
     		return $priceID;
		}
				
		function UpdatePrice($priceID, $fieldID, $value)
		{
			$sqlSET = $fieldID.'="'.$value.'"';
			return $this->UpdatePriceEntry($priceID, $sqlSET);
		}								
				
		function UpdatePriceType($priceID, $newPriceType)
		{
			$sqlSET = 'priceType="'.$newPriceType.'"';
			return $this->UpdatePriceEntry($priceID, $sqlSET);
		}								
				
		function UpdatePriceValue($priceID, $newPriceValue)
		{
			$sqlSET = 'priceValue="'.$newPriceValue.'"';
			return $this->UpdatePriceEntry($priceID, $sqlSET);
		}								
				
		function UpdatePriceEntry($priceID, $sqlSET)
		{
			$sql  = 'UPDATE '.STAGESHOW_PRICES_TABLE;
			$sql .= ' SET '.$sqlSET;			
			$sql .= ' WHERE priceID='.$priceID;			

			$this->query($sql);	
			return "OK";							
		}

		function DeleteShowByShowID($showID)
		{
			// Get the show name
			$sql = 'SELECT * FROM '.STAGESHOW_SHOWS_TABLE;
			$sql .= ' WHERE '.STAGESHOW_SHOWS_TABLE.".showID=$showID";
			$results = $this->get_results($sql);
			
			if (count($results) == 0) return '';
			
			$this->SetShowActivated($showID, STAGESHOW_STATE_DELETED);

			return $results[0]->showName;
		}			
		
		function DeletePerformanceByPerfID($perfID)
		{
			$this->SetPerfActivated($perfID, STAGESHOW_STATE_DELETED);
		}			
		
		function DeletePriceByPriceID($ID)
		{
			return $this->DeletePrice($ID, 'priceID');
		}			
		
		function DeletePriceByPerfID($ID)
		{
			return $this->DeletePrice($ID, 'perfID');
		}			
		
		private function DeletePrice($ID, $IDfield)
		{
			$sql  = 'DELETE FROM '.STAGESHOW_PRICES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_PRICES_TABLE.".$IDfield=$ID";
			 
			$this->query($sql);
		}					

		function GetAllTicketTypes()
		{
			$sql  = 'SELECT priceType FROM '.STAGESHOW_PRICES_TABLE;
			$sql .= ' GROUP BY priceType';
			$sql .= ' ORDER BY priceType';
			 
			return $this->get_results($sql);
		}
		
		function GetTicketsListByPerfID($perfID)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_SALES_TABLE;	// MJS: Check SALES Table Fields
			$sql .= " JOIN ".STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.saleID='.STAGESHOW_SALES_TABLE.'.saleID';
			$sql .= " JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceID='.STAGESHOW_TICKETS_TABLE.'.priceID';
			$sql .= " JOIN ".STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_PRICES_TABLE.'.perfID';
			$sql .= " JOIN ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= ' WHERE priceVisibility != "'.STAGESHOW_VISIBILITY_LOCKOUT.'"';
			$sql .= ' AND '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.$perfID;
			$sql .= ' ORDER BY saleLastName,'.STAGESHOW_SALES_TABLE.'.saleID DESC';
			
			// Get results ... but suppress debug output until AddSaleFields has been called
			$salesListArray = $this->get_results($sql);			
			$this->AddSaleFields($salesListArray);
			
			return $salesListArray;
		}
		
		function GetAllSalesQty($sqlFilters = null)
		{
			$sqlFilters['groupBy'] = 'perfID';
			$sqlFilters['JoinType'] = 'RIGHT JOIN';

			$sqlFilters['showState'] = STAGESHOW_STATE_ACTIVE;
			$sqlFilters['perfState'] = STAGESHOW_STATE_ACTIVE;

			$sql  = 'SELECT '.STAGESHOW_SALES_TABLE.'.*, showName, perfDateTime, '.$this->TotalSalesField($sqlFilters).' FROM '.STAGESHOW_SALES_TABLE;		// MJS: Check SALES Table Fields
			$sql .= $this->GetJoinedTables($sqlFilters, __CLASS__);
			$sql .= $this->GetWhereSQL($sqlFilters);
			$sql .= $this->GetOptsSQL($sqlFilters);
			$sql .= ' ORDER BY '.STAGESHOW_PERFORMANCES_TABLE.'.showID, '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime';
					
			$salesListArray = $this->get_results($sql);
							 
			return $salesListArray;
		}
		
		function AddSaleCustomFields(&$saleDetails)
		{
			parent::AddSaleCustomFields($saleDetails);
		}
		
		function GetSaleDetails($saleID)
		{
			$sqlFilters['saleID'] = $saleID;
			
			//$saleDetails = $this->SaleSQLCall('*, SUM(ticketPaid) AS soldValue', $sqlFilters);
			$saleDetails = $this->SaleSQLCall('*', $sqlFilters);
			
			$this->AddSaleFields($saleDetails);
			$this->AddSaleCustomFields($saleDetails);			
		
			if ((count($saleDetails) > 0) && ($saleDetails[0]->saleStatus == PAYMENT_API_SALESTATUS_RESERVED))
			{
				$totalDue = 0;
				
				foreach ($saleDetails as $saleDetail)
					$totalDue += $saleDetail->ticketPaid;
				
				$saleDetails[0]->soldValue = $this->FormatCurrencyValue($totalDue);
				
				// Copy soldValue to salePaid for template backwards compatibility
				$saleDetails[0]->salePaid = $saleDetails[0]->soldValue;
			}
		
			return $saleDetails;
		}
		
		function SaleSQLCall($fields = '*', $sqlFilters = array())
		{
			$sqlFilters['addPayments'] = true;
			
			$sql  = 'SELECT '.$fields.' FROM '.STAGESHOW_SALES_TABLE;	
			$sql .= $this->GetJoinedTables($sqlFilters, __CLASS__);
			$sql .= ' WHERE '.STAGESHOW_SALES_TABLE.'.saleID="'.$sqlFilters['saleID'].'" ';
//			$sql .= $this->GetWhereSQL($sqlFilters);
			$sql .= $this->GetOptsSQL($sqlFilters);
			$sql .= ' ORDER BY '.STAGESHOW_PERFORMANCES_TABLE.'.showID, '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime, ticketSeat';
					
			$salesListArray = $this->get_results($sql);
							 
			return $salesListArray;
		}
		
// ----------------------------------------------------------------------
//
//			Start of CUSTOM SALES functions
//
// ----------------------------------------------------------------------
    
		function GetSalesQtyByPerfID($perfID)
		{
			$sqlFilters['perfID'] = $perfID;
			return $this->GetSalesQty($sqlFilters);
		}
				
// ----------------------------------------------------------------------
//
//			Start of GENERIC SALES functions
//
// ----------------------------------------------------------------------
    
		function GetSaleStockID($itemRef, $itemOption)
		{
			// itemRef format: {showID}-{perfID}
			$itemRefs = explode('-', $itemRef);
			$sqlFilters['showID'] = $itemRefs[0];
			$sqlFilters['perfID'] = $itemRefs[1];
			$sqlFilters['priceType'] = $itemOption;
					
			$priceEntries = $this->GetPricesList($sqlFilters);
			
			if (count($priceEntries) > 0) 
				$stockID = $priceEntries[0]->priceID;
			else
				$stockID = 0;
			
			return $stockID;
		}
				
		function GetActivePricesCount()
		{
			$sqlFilters['activePrices'] = true;
			
			$this->showJoined = true;
			$this->perfJoined = true;
			
			$sql  = 'SELECT COUNT(*) AS pricesCount FROM '.STAGESHOW_PRICES_TABLE;
      		$sql .= ' LEFT JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_PRICES_TABLE.'.perfID';
      		$sql .= ' LEFT JOIN '.STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= $this->GetWhereSQL($sqlFilters);

			$priceCountList = $this->get_results($sql);
			
			return count($priceCountList)>0 ? $priceCountList[0]->pricesCount : 0;			
		}
		
		function GetSalesListByPerfID($perfID)
		{
			$sqlFilters['perfID'] = $perfID;
			$sqlFilters['groupBy']= 'saleID';
			return $this->GetSalesList($sqlFilters);
		}
				
		function GetSalesListByPriceID($priceID)
		{
			$sqlFilters['priceID']= $priceID;
			$sqlFilters['groupBy']= 'saleID';
			return $this->GetSalesList($sqlFilters);
		}
				
		function GetSalesListWithDetails($sqlFilters)
		{
			$sqlFilters['addPayments'] = true;
			$saleDetails = $this->GetSalesList($sqlFilters);
			$this->AddSaleCustomFields($saleDetails);
			return $saleDetails;
		}
					
		function CanEditSales()
		{
			if (current_user_can(STAGESHOWLIB_CAPABILITY_ADMINUSER))
				return true;
				
			if (current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER))
				return true;
				
			return false;
		}
					
		function DeleteSale($saleID)
		{
			parent::DeleteSale($saleID);

			$this->DeleteOrders($saleID);			
			
			$this->DeleteVerify($saleID);
		}
			
		function GetOrderSQL($sqlFilters = null)
		{
			return 'SUM(ticketPaid) AS soldValue';
		}			
		
		function GetOrderFilter($sqlFilters = null)
		{
			$orderFilter = '';
			switch (StageShowLibUtilsClass::GetHTTPTextElem('get', 'action'))
			{
				case 'show':
					// FUNCTIONALITY: Sales - Lists Sales for a Show
					// List Sales for Show
					if (StageShowLibUtilsClass::IsElementSet('get', 'id'))
						$orderFilter = ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.StageShowLibUtilsClass::GetHTTPTextElem('get', 'id');
					break;
						
				case 'perf':
					// FUNCTIONALITY: Sales - Lists Sales for a Performance
					// List Sales for Performance
					if (StageShowLibUtilsClass::IsElementSet('get', 'id'))
						$orderFilter = ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.StageShowLibUtilsClass::GetHTTPTextElem('get', 'id');
					break;					
			}
			
			return $orderFilter;
		}			

		function CanReinstateSaleItem($saleItem)
		{
			if ($saleItem->seatingID > 0)
			{
				if ($saleItem->zoneAllocSeats == 1)
				{
					// Check if seat(s) has been sold subsequently
					return $this->IsSeatAvailable($saleItem->perfID, $saleItem->ticketSeat);
				}
				else
				{
					$zoneID = $saleItem->zoneID;

					if (!isset($this->zonedSeatsAvail[$zoneID]))
					{
						// Get the maximum number of seats 
						$this->zonedSeatsAvail[$zoneID] = $saleItem->zoneSeats;	
						if ($this->zonedSeatsAvail[$zoneID] < 0) return false;
						
						// Deduct the total number of seats sold for this performance	
						$salesSummary = $this->GetPerformanceSummaryByPerfAndZoneID($saleItem->perfID, $zoneID);
						$this->zonedSeatsAvail[$zoneID] -= $salesSummary->totalQty;				
					}
					
					if ($this->zonedSeatsAvail[$zoneID] >= 0)
					{
						// Add the number of seats for this performance for this sale entry
						// (i.e. assume that these seats have been deleted)
						$qty = isset($saleItem->priceNoOfSeats) ? $saleItem->ticketQty * $saleItem->priceNoOfSeats : $saleItem->ticketQty;						
						$this->zonedSeatsAvail[$zoneID] -= $qty;
					}						

					return ($this->zonedSeatsAvail[$zoneID] >= 0);
				}
			}
			
			$perfID = $saleItem->perfID;
			
			if (!isset($this->seatsAvail[$perfID]))
			{
				// Get the maximum number of seats 
				$this->seatsAvail[$perfID] = $saleItem->perfSeats;	
				if ($this->seatsAvail[$perfID] < 0) return true;
				
				// Deduct the total number of seats sold for this performance	
				$salesSummary = $this->GetPerformanceSummaryByPerfID($perfID);
				$this->seatsAvail[$perfID] -= $salesSummary->totalQty;				
			}
			
			if ($this->seatsAvail[$perfID] >= 0)
			{
				// Add the number of seats for this performance for this sale entry
				// (i.e. assume that these seats have been deleted)
				$qty = isset($saleItem->priceNoOfSeats) ? $saleItem->ticketQty * $saleItem->priceNoOfSeats : $saleItem->ticketQty;						
				$this->seatsAvail[$perfID] -= $qty;						
			}
			
			return ($this->seatsAvail[$perfID] >= 0);
		}
							
		function GetPIRRecordsByEMail($saleEMail)
		{				
			$saleEntries = parent::GetPIRRecordsByEMail($saleEMail);
			
			// Add a 'saleNew' element for first row of each sale
			if (count($saleEntries) > 0)
			{
				for ($i=0; $i<count($saleEntries); $i++)
				{
					$saleEntries[$i]->ticketName = $this->GetOnlineStoreItemName($saleEntries[$i]);
					
					if (($i == 0) || ($saleEntries[$i]->saleID != $saleEntries[$i-1]->saleID))
					{
						$saleEntries[$i]->saleNew = true;
					}
				}				
			}
			
						 
			return $saleEntries;
		}
		

// ----------------------------------------------------------------------
//
//			End of SALES functions
//
// ----------------------------------------------------------------------
    
		function GetSalesEMail()
		{
			$optionGatewaySuffix = $this->gatewayObj->GetOptionGatewaySuffix();					
			return $this->adminOptions['AdminEMail'.$optionGatewaySuffix];
		}
		
		function IsDateTimeField($tag)
		{	
			// Return true for DATETIME DB Fields
			switch ($tag)
			{
				case '[salePrinted]':
				case '[verifyDateTime]':
				case '[perfDateTime]':
					return true;
			}
			return parent::IsDateTimeField($tag);					
		}
		
		function IsCurrencyField($tag)
		{
			switch ($tag)
			{
				case '[ticketPaid]':
				case '[priceValue]':
					return true;
			}
			
			return parent::IsCurrencyField($tag);					
		}
		
		function GetHTTPPage($reqURL)
		{
			$rtnVal = '';
			$response = $this->HTTPGet($reqURL);
						
			if ($response['APIStatus'] == 200)
				$rtnVal = $response['APIResponseText'];
			
			//if ($rtnVal == '')
			//	StageShowLibEscapingClass::Safe_EchoHTML("GetHTTPPage($reqURL) Failed<br>\n");
				
			return $rtnVal;
		}

		function AddTableLocks($sql)
		{
			$sql = parent::AddTableLocks($sql);
			$sql .= ', '.STAGESHOW_PRICES_TABLE.' READ';
			$sql .= ', '.STAGESHOW_PERFORMANCES_TABLE.' READ';
			$sql .= ', '.STAGESHOW_SHOWS_TABLE.' READ';

			$sql .= ', '.STAGESHOW_ZONES_TABLE.' READ';
			$sql .= ', '.STAGESHOW_SEATING_TABLE.' READ';
			$sql .= ', '.STAGESHOW_TICKETSMETA_TABLE.' WRITE';
			return $sql;
		}
		
		function AddEventToTemplate($EMailTemplate, $saleDetails)
		{
			$saleDetails->seatName = '';
			$saleDetails->seatRow = '';
			$saleDetails->seatId = '';				

			// Add any email fields that are not in the sale record
			if ( (isset($saleDetails->seatingID))
			  && (isset($saleDetails->zoneAllocSeats))
			  && ($saleDetails->seatingID > 0) 
			  && ($saleDetails->zoneAllocSeats == 1))
			{
				$saleDetails->seatName = StageShowZonesDBaseClass::DecodeSeatsList($this, $saleDetails->ticketSeat, $saleDetails->seatingID);	
				$seatParts = explode('-', $saleDetails->seatName);
				if (count($seatParts) == 1)
					$seatParts = explode('_', $saleDetails->seatName);
				
				if (count($seatParts) > 1)	
				{
					$saleDetails->seatRow = $seatParts[0];
					$saleDetails->seatId = $seatParts[1];				
				}						
			}
			
			$eMailFields = $this->AddEMailFields($EMailTemplate, $saleDetails);
			
			$imageSrc = "";
			if (isset($saleDetails->ticketID) && ($saleDetails->ticketID >= 1) && (StageShowLibMigratePHPClass::Safe_strpos($eMailFields, '[ticketBarcode]') !== false))
			{
				$saleTicketId = $saleDetails->saleTxnId;
				$saleTicketId .= STAGESHOW_TXNID_PARAMSDELIM . $saleDetails->ticketID;
				$saleTicketId .= STAGESHOW_TXNID_PARAMSDELIM . $saleDetails->ticketNo;

				$barcodeID = StageShowLibMigratePHPClass::Safe_str_replace(STAGESHOW_TXNID_PARAMSDELIM, '', $saleTicketId);
				
				$imageSrc .= $this->GetBarcodeSrc($saleTicketId, $barcodeID);
				
				$eMailFields = StageShowLibMigratePHPClass::Safe_str_replace('[ticketBarcode]', $imageSrc, $eMailFields);
			}
			
			return $eMailFields;
		}
		
		function AddEMailFields($EMailTemplate, $saleDetails)
		{
			// FUNCTIONALITY: DBase = Add DB fields to EMail
			// Add any email fields that are not in the sale record ...
			$dateFormat = self::GetDateFormat();
			$timeFormat = self::GetTimeFormat();
					
			if (isset($saleDetails->perfDateTime))
			{
				$timestamp = StageShowLibMigratePHPClass::Safe_strtotime($saleDetails->perfDateTime);
				$saleDetails->perfDate = date($dateFormat, $timestamp);
				$saleDetails->perfTime = date($timeFormat, $timestamp);				
			}
			
			$eMailFields = parent::AddEventToTemplate($EMailTemplate, $saleDetails);
			
			$eMailFields = $this->DoEmbeddedImage($eMailFields, 'logoimg', 'PayPalLogoImageFile');
			
			$imageSrc = "";
			if (StageShowLibMigratePHPClass::Safe_strpos($eMailFields, '[saleBarcode]'))
			{
				$barcodeID = $saleDetails->saleTxnId;
				$imageSrc .= $this->GetBarcodeSrc($barcodeID);
				
				$eMailFields = StageShowLibMigratePHPClass::Safe_str_replace('[saleBarcode]', $imageSrc, $eMailFields);
			}
			
			if (StageShowLibMigratePHPClass::Safe_strpos($eMailFields, '[saleEMailURL]'))
			{
				$saleEMailURL = STAGESHOW_ADMIN_URL.STAGESHOWLIB_VIEWEMAIL_TARGET;
				$saleEMailURL .= '?saleEMail='.$saleDetails->saleEMail;
				$saleEMailURL .= '&saleTxnId='.$saleDetails->saleTxnId;
				
				$eMailFields = StageShowLibMigratePHPClass::Safe_str_replace('[saleEMailURL]', $saleEMailURL, $eMailFields);
			}
			
			return $eMailFields;
		}
		
		function RetrieveEventElement($tag, $field, &$saleDetails)
		{
			switch ($tag)
			{
				case '[ticketQty]':
					if (($saleDetails->seatingID > 0) && ($saleDetails->zoneAllocSeats == 1))
						return '';
					
					return __("Qty", 'stageshow').':'.$saleDetails->ticketQty;
					break;
			
				case '[ticketSeat]':
					if ($saleDetails->seatName != '')
						return __("Seat", 'stageshow').': '.$saleDetails->seatName;		
					return '';				
					break;
			}
			
			return parent::RetrieveEventElement($tag, $field, $saleDetails);
		}	
		
		function __construct($caller) //constructor	
		{
			// Create EMail Object for HTML Emails (must be created before parent constructor is called)
			$this->emailObjClass = 'StageShowLibHTMLEMailAPIClass';
			$this->emailClassFilePath = STAGESHOW_INCLUDE_PATH.'stageshowlib_htmlemail_api.php';   
			
			// Call base constructor
			parent::__construct($caller);

			// Check if plugin database has been created ...
			if ($this->tableExists(STAGESHOW_PERFORMANCES_TABLE)) 
			{
				// Check if DB needs upgrading
				if (!$this->tableExists(STAGESHOW_SHOWS_TABLE)) 
					$this->createDB();
			}
		}

		
 		//Returns an array of admin options
		function CheckCustomIncludeFiles($sampleName, $optionID)
		{
			$optionUpdated = false;		
							
			$selectedFile = $this->getOption($optionID);
			if ($selectedFile == $sampleName)
			{
				// Make a copy of the file .... and then select the copy
				$path = StageShowLibMigratePHPClass::Safe_str_replace('plugins', 'uploads', STAGESHOW_FILE_PATH);
				$selectedFile = StageShowLibUtilsClass::MakeUniqueCopy($path, $selectedFile);
				$this->setOption($optionID, $selectedFile);
				$optionUpdated = true;					
			}
						
			return $optionUpdated;
		}
		
		function get_pluginName()
		{
			return $this->get_name();
		}
		
          
	    function deactivate()
	    {
      		// Call deactivate() in base class
			parent::deactivate();
    	}

		function GetPluginStatus()
		{
			$pluginID = $this->get_name();
			if ($pluginID == 'StageShow')
			{
				return '';
			}

			$installInsURL = STAGESHOW_URL.'docs/StageShowUnification.pdf';
			$actionMsg = 'Sales of '.$pluginID.' have been discontinued, and the additional features that it provides have been added to the free version of StageShow hosted on Wordpress.org. <p>There will be no further updates of '.$pluginID.' and you should replace it with the StageShow plugin to continue to get updates. <p>Details on how to do this are <a href="'.$installInsURL.'">here</a>.';
			
			return $actionMsg;
		}
		
		function SendSaleReport()
		{
			if (!isset($this->adminOptions['EMailSummaryTemplatePath']) || ($this->adminOptions['EMailSummaryTemplatePath'] == '')) 
				return 'EMailSummaryTemplatePath not defined';			
			$templatePath = $this->GetEmailTemplatePath('EMailSummaryTemplatePath');
			
			if (!isset($this->adminOptions['SaleSummaryEMail']) || ($this->adminOptions['SaleSummaryEMail'] == '')) 
				return 'SaleSummaryEMail not defined';			
			$EMailTo = $this->adminOptions['SaleSummaryEMail'];
	
			$salesSummary = $this->GetAllSalesQty();
			
			// Supress copy to Admin just for this email ... save a copy and restore after call
			$this->allowBcc = false;
			$emailStatus = $this->SendEMailFromTemplate($salesSummary, $templatePath, $EMailTo);
			$this->allowBcc = true;
			
			return $emailStatus;
		}
		
		function CompleteSale(&$results, $saleUpdateMode = self::STAGESHOWLIB_FROMCALLBACK, $CanClearURL = true)
		{
			$saleID = parent::CompleteSale($results, $saleUpdateMode, $CanClearURL);
			
			$saleStatus = '';
			if (is_array($results))
			{
				if (isset($results['saleStatus'])) $saleStatus = $results['saleStatus'];
			}
			else
			{
				if (isset($results->saleStatus)) $saleStatus = $results->saleStatus;
			}

			// FUNCTIONALITY: DBase = Send Sale Summary EMail
			if ( ($saleID > 0) 
			  && ($saleUpdateMode == self::STAGESHOWLIB_FROMCALLBACK)
			  && (($saleStatus == PAYMENT_API_SALESTATUS_COMPLETED) || ($saleStatus == PAYMENT_API_SALESTATUS_RESERVED))
			  )
			{
				$this->SendSaleReport();
			}
				
			return $saleID;			
		}
		
		function GetShowsCount($sqlSelect)
		{
			$sqlQuery  = 'SELECT '.STAGESHOW_SHOWS_TABLE.'.*, MAX(perfDateTime) AS maxPerfDateTime, COUNT(priceValue) AS noOfPrices';
			$sqlQuery .= ' FROM '.STAGESHOW_SHOWS_TABLE;
			$sqlQuery .= " LEFT JOIN ".STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.STAGESHOW_SHOWS_TABLE.'.showID';
			$sqlQuery .= " LEFT JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.perfID='.STAGESHOW_PERFORMANCES_TABLE.'.perfID';
			$sqlQuery .= ' GROUP BY '.STAGESHOW_SHOWS_TABLE.'.showID';			
					
			$sql  = "SELECT $sqlSelect FROM ($sqlQuery) AS sub";
					
			$results = $this->get_results($sql);
			
			return $results;
		}
		
		function GetFilteredShowsList($sqlFilters)
		{
			$selectFields = STAGESHOW_SHOWS_TABLE.'.*';
			
			$totalSalesField = $this->TotalSalesField();
			if ($totalSalesField != '')
				$selectFields .= ','.$totalSalesField;
					
			$sqlQuery  = 'SELECT '.$selectFields;
			$sqlQuery .= ' FROM '.STAGESHOW_SHOWS_TABLE;
			$sqlQuery .= " LEFT JOIN ".STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.STAGESHOW_SHOWS_TABLE.'.showID';
			$sqlQuery .= " LEFT JOIN ".STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.perfID='.STAGESHOW_PERFORMANCES_TABLE.'.perfID';
			$sqlQuery .= " LEFT JOIN ".STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.priceID='.STAGESHOW_PRICES_TABLE.'.priceID';
			$sqlQuery .= " LEFT JOIN ".STAGESHOW_SALES_TABLE.' ON '.STAGESHOW_SALES_TABLE.'.saleID='.STAGESHOW_TICKETS_TABLE.'.saleID';
			$sqlQuery .= ' GROUP BY '.STAGESHOW_SHOWS_TABLE.'.showID';			
					
			$sql  = "SELECT * FROM ($sqlQuery) AS sub";
			$sql .= $this->GetWhereSQL($sqlFilters).' ';
					
			$results = $this->get_results($sql);
			
			return $results;
		}
		
		function AddPlanToPerformance($perfID, $results)
		{
			if (count($results) == 0) return;
			
			$this->UpdateSeatingLayout($perfID, $results[0]->seatingID);
			
			foreach ($results as $result)
			{
				// Add price entries to performance
				$priceID = $this->AddPriceFromPreset($perfID, $result);
			}
		}
			
		function UpdatePerformanceExpiresTime($perfID, $newExpiresDateTime)
		{
			$sqlSET = 'perfExpires="'.$newExpiresDateTime.'"';
			return $this->UpdatePerformanceEntry($perfID, $sqlSET);
		}
		
		function DefaultExpiresTime($perfDateTime)
		{			
			if ($perfDateTime == '') return '';
			
			$perfTimestamp  = StageShowLibMigratePHPClass::Safe_strtotime($perfDateTime);
			$perfTimestamp -= ($this->adminOptions['PerfExpireLimit'] * $this->adminOptions['PerfExpireUnits']);
			
			return date(STAGESHOWLIB_DATETIME_ADMIN_FORMAT, $perfTimestamp);
		}
		
		function GetPricePlansListByPlanID($planID)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_PRESETS_TABLE;
			$sql .= " LEFT JOIN ".STAGESHOW_PLANS_TABLE.' ON '.STAGESHOW_PLANS_TABLE.'.planID='.STAGESHOW_PRESETS_TABLE.'.planID';
			$sql .= ' WHERE '.STAGESHOW_PRESETS_TABLE.'.planID="'.$planID.'"';	
			
			$presetsListArray = $this->get_results($sql);
			
			return $presetsListArray;
		}
		
		function AddPlan($planRef = '', $priceType='', $priceValue=0.0)
		{
			// Save the seatingID because AddPreset (called by AddPlan) uses it)
			$planSeatingID = StageShowLibHTTPIO::GetRequestedInt('seatingID', 0);
			$this->planSeatingID = $planSeatingID;
			
      		if ($planRef === '')
      		{
				$newNameNo = 1;
				while (true)
				{
					$planRef = 'Unnamed Price Plan '.$newNameNo;
					if ($this->IsPlanRefUnique($planRef))
						break;
					$newNameNo++;
				}
			}
			else
			{
				if (!$this->IsPlanRefUnique($planRef))
					return 0;	// Error - Show Name is not unique
			}
						
			$sql = 'INSERT INTO '.STAGESHOW_PLANS_TABLE.'(planRef) VALUES("'.esc_sql($planRef).'")';
			$this->query($sql);	
					
	     	$planId = $this->GetInsertId();
	     	
	     	// Add a preset - default settings if nothing passed in call to this function
	     	if ($priceValue >= 0)
	     	{
	     		$this->AddPreset($planId, $priceType, $priceValue);
			}
	     	
			if ($planRef == '')	
			{
				// New (default) price plan
				$seatingID = $this->planSeatingID;
				if ($seatingID > 0)
				{
					$this->UpdatePlanSeating($planId, $seatingID);
				}
			}
								
			return $planId;
		}
		
				
		function IsPlanRefUnique($planRef)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_PLANS_TABLE;
			$sql .= ' WHERE '.STAGESHOW_PLANS_TABLE.'.planRef="'.esc_sql($planRef).'"';
			
			$groupsEntries = $this->get_results($sql);
			return (count($groupsEntries) > 0) ? false : true;
		}
		
		function UpdatePlanRef($planID, $planRef)
		{
			if (!$this->IsPlanRefUnique($planRef))
				return false;
				
			$sql  = 'UPDATE '.STAGESHOW_PLANS_TABLE;
			$sql .= ' SET planRef="'.esc_sql($planRef).'"';
			$sql .= ' WHERE '.STAGESHOW_PLANS_TABLE.'.planID='.$planID;;
			$this->query($sql);	

			return true;							
		}
		
		function AddPreset($planId, $priceType='', $priceValue=STAGESHOW_PRICE_UNKNOWN, $priceVisibility = STAGESHOW_VISIBILITY_PUBLIC)
		{
			if ($priceType === '')
			{
				$newNameNo = 1;
				while (true)
				{
					$priceType = 'TYPE'.$newNameNo;
					if ($this->IsPriceRefUnique($planId, $priceType))
						break;
					$newNameNo++;
				}
				$priceValue = 0.0;
			}
			else
			{
				if (!$this->IsPriceRefUnique($planId, $priceType))
					return 0;	// Error - Ref is not unique
			}
						
			$sql  = 'INSERT INTO '.STAGESHOW_PRESETS_TABLE.'(planId, priceType, priceValue, priceVisibility)';
			$sql .= ' VALUES('.$planId.', "'.esc_sql($priceType).'", "'.$priceValue.'", "'.$priceVisibility.'")';
			$this->query($sql);	
					
     		$presetId = $this->GetInsertId();

			if ($priceType == '')	
			{
				$seatingID = $this->planSeatingID;
				if ($seatingID > 0)
				{
					// Get Zones in Seating Plan
					$zonesList = $this->GetSeatingZones($seatingID);
					
					// Allocate first zone to the new price entry
					$this->UpdatePreset($presetId, 'priceZoneID', $zonesList[0]->zoneID);
				}
			}
			
			return $presetId;
		}

		function UpdatePreset($presetID, $presetField, $presetValue)
		{
			$sql  = 'UPDATE '.STAGESHOW_PRESETS_TABLE;
			$sql .= ' SET '.$presetField.'="'.esc_sql($presetValue).'"';
			$sql .= ' WHERE '.STAGESHOW_PRESETS_TABLE.'.presetID='.$presetID;;
			$this->query($sql);	

			return "OK";							
		}
		
		function DeletePreset($presetID)
		{
			// Delete a preset entry
			$sql  = 'DELETE FROM '.STAGESHOW_PRESETS_TABLE;
			$sql .= ' WHERE '.STAGESHOW_PRESETS_TABLE.".presetID=$presetID";
			$rtnStatus = $this->query($sql);
			
			$this->DeleteEmptyPlans();
			
			return $rtnStatus;
		}
		
		function PresetExists($presetID)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_PRESETS_TABLE;
			$sql .= ' WHERE '.STAGESHOW_PRESETS_TABLE.'.presetID="'.$presetID.'"';	
			
			$presetsEntries = $this->get_results($sql);
			return (count($presetsEntries) == 0) ? false : true;
		}
		
		function DeleteEmptyPlans()
		{
			// Delete all empty preset entries
			$sql  = 'DELETE plan.* FROM '.STAGESHOW_PLANS_TABLE.' plan';
			$sql .= " LEFT JOIN ".STAGESHOW_PRESETS_TABLE.' preset ON plan.planID=preset.planID';
			$sql .= ' WHERE preset.planID IS NULL';
			$this->query($sql);
		}
		
		function IsPriceRefUnique($planID, $priceType)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_PRESETS_TABLE;
			$sql .= " LEFT JOIN ".STAGESHOW_PLANS_TABLE.' ON '.STAGESHOW_PLANS_TABLE.'.planID='.STAGESHOW_PRESETS_TABLE.'.planID';
			$sql .= ' WHERE '.STAGESHOW_PRESETS_TABLE.'.planID="'.$planID.'"';	
			$sql .= ' AND '.STAGESHOW_PRESETS_TABLE.'.priceType="'.$priceType.'"';
			
			$presetsEntries = $this->get_results($sql);
			return (count($presetsEntries) > 0) ? false : true;
		}
		
		function AddPriceFromPreset($perfID, $preset)
		{
			$priceID = $this->AddPrice($perfID, $preset, true);
			
			// Add discount prices from price plan 
			$disList = $this->GetDiscountsList();
			if (count($disList) > 0)
			{
				foreach ($disList as $disEntry)
				{
					$disValue = $this->GetPresetDiscount($preset->presetID, $disEntry->disID);
					$this->SaveDiscountPrice($disEntry->disID, $priceID, $disValue);
				}
			}
			
			return $priceID;
		}
		
		function HasExtraDiscount()
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_SALES_TABLE;
      		$sql .= ' WHERE saleExtraDiscount!=0 LIMIT 1';

			$results = $this->get_results($sql);
			
			return (count($results) > 0);
		}						

		function GetLocation()
		{
			// FUNCTIONALITY: DBase = Get Terminal Location
			$terminalLocation = '';
			
			// Get the terminal location from site cookies
			if (StageShowLibUtilsClass::IsElementSet('cookie', 'TerminalLocation'))
				$terminalLocation = StageShowLibUtilsClass::GetHTTPTextElem($_COOKIE, 'TerminalLocation'); 
			else
				$terminalLocation = '';

			// If terminal location not defined use "Unknown"
			if (StageShowLibMigratePHPClass::Safe_strlen($terminalLocation) == 0)
				$terminalLocation = 'Unknown';
				
			return $terminalLocation;
		}
		
		function DeleteVerify($saleID)
		{
					
			$sql  = 'DELETE FROM '.STAGESHOW_VERIFYS_TABLE;	
			$sql .= ' WHERE '.STAGESHOW_VERIFYS_TABLE.".saleID=$saleID";
					
			$this->query($sql);
		}

		function DoEmbeddedImage($eMailFields, $fieldMarker, $optionID)
		{
			$fieldMarker = '['.$fieldMarker.']';
			if (!StageShowLibMigratePHPClass::Safe_strpos($eMailFields, $fieldMarker))
				return $eMailFields;
				
			if (isset($this->emailObj))
			{
				$pluginID = STAGESHOW_FOLDER;	// Library files should be in 'include' folder			
				$imageFile = $this->getOption($optionID);
				if ($imageFile == '')
					return $eMailFields;
				
				$imagesPath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/images/';				
				
				// Add Image to EMail Images List
				$CIDFile = $this->emailObj->AddFileImage($imagesPath.$imageFile);
				$imageSrc = "cid:".$CIDFile;
			}
			else
			{
				$imageSrc = $this->getImageURL($optionID);
			}
				
			$eMailFields = StageShowLibMigratePHPClass::Safe_str_replace($fieldMarker, $imageSrc, $eMailFields);
				
			return $eMailFields;
		}
		
		static function HasCheckoutImage()
		{
			return true;
		}
		
		function GetBccEMail()
		{
			if (!$this->allowBcc) return '';
			
			return parent::GetBccEMail();
		}
		
		function GetSaleIDFromToken($token)
		{
			$salesEntry = $this->GetSaleFromToken($token);
			if ($salesEntry == null) return 0;
			
			return $salesEntry->saleID;
		}

		function GetSaleFromToken($token)
		{
			$sql  = 'SELECT saleID, saleStatus FROM '.STAGESHOW_SALES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_SALES_TABLE.'.salePPExpToken="'.$token.'"';					
			
			$salesListArray = $this->get_results($sql, false);			
			
			$this->show_results($salesListArray);
			
			if (count($salesListArray) != 1)
				return null;
				
			return $salesListArray[0];
		}
				
		function GetCartFromToken($token, &$cartContents)
		{
			// Get sale record from DB
			$saleID = $this->GetSaleIDFromToken($token);
			if ($saleID == 0)
			{
				$pluginObj->checkoutMsg = __('Cannot Checkout', 'stageshow').' - ';
				$pluginObj->checkoutMsg .= __('Database Access Error', 'stageshow');
				return $saleID;						
			}
			
			// Output Confirmation Screen
			//StageShowLibEscapingClass::Safe_EchoHTML("<h3>TODO - Ouput Sales Details for Comfirmation</h3>\n");					
			$tickets = $this->GetPendingSale($saleID);

			$cartContents->rows = array();
			$index = 1;

			$cartContents->saleExtraDiscount = 0;
			$cartContents->saleTransactionFee = 0;
			$cartContents->saleDonation = 0;
			$cartContents->salePostTickets = ($tickets[0]->salePostage > 0);
			$cartContents->confirmSaleMode = true;

			$discountName = __('Discount', 'stageshow');
			$bookingFeeName = __('Booking Fee', 'stageshow');
			$donationName = __('Donation', 'stageshow');
			foreach($tickets as $ticket)
			{
				if ($ticket->ticketType == '')
				{
					if ($ticket->ticketName == $discountName)
						$cartContents->saleExtraDiscount = $ticket->ticketPaid;
					else if ($ticket->ticketName == $bookingFeeName)
						$cartContents->saleTransactionFee = $ticket->ticketPaid;
					else if ($ticket->ticketName == $donationName)
						$cartContents->saleDonation = $ticket->ticketPaid;
					continue;
				}
				
				$cartContents->rows[$index] = new stdClass();
				$cartContents->rows[$index]->qty = $ticket->ticketQty;
				$cartContents->rows[$index]->price = $ticket->ticketPaid / $ticket->ticketQty;
				
				$cartContents->rows[$index]->itemID = $ticket->priceID;
				
				if (isset($ticket->zoneID) && ($ticket->zoneID > 0))
				{
					$cartContents->rows[$index]->zoneID = $ticket->zoneID;
					$cartContents->rows[$index]->zoneAllocSeats = $ticket->zoneAllocSeats;
					$cartContents->rows[$index]->seatNames = StageShowZonesDBaseClass::DecodeSeatsList($this, $ticket->ticketSeat, $ticket->seatingID);
				}
					
				$index++;		
			}
			
			return $saleID;
		}

		function GetBarcodeClass()
		{
			include STAGESHOW_INCLUDE_PATH.'stageshow_barcode.php';
			return 'StageShowBarcodeClass';
		}

		function GetBarcodeObject($TxnId, $CIDbarcode, $fileName)
		{
			$rtnObj = new stdClass();
			
			$code_type = $this->adminOptions['BarcodeType'];
			
			$barCodeClass = $this->GetBarcodeClass();
			$barcodeObj = new $barCodeClass($code_type);
			$bin = $barcodeObj->CreateBarcodeImage($TxnId, true);
			
			$rtnObj->file = $fileName;
			$rtnObj->cid = $CIDbarcode;
			$rtnObj->bin = $bin;
			
			return $rtnObj;
		}
				
		function GetBarcodeSrc($saleTicketId, $CIDbarcode = '')
		{
			if ($CIDbarcode == '')
			{
				$CIDbarcode = $saleTicketId;
			}
			
			// Add Barcode Image Link to Email
			$CIDbarcode = STAGESHOW_BARCODE_IDENTIFIER.$CIDbarcode;
						
			// Add Barcode Image to EMail Images List
			$barcodeImage = $this->GetBarcodeObject($saleTicketId, $CIDbarcode, 'barcode.png');
		
			if (isset($this->emailObj))
			{
				$imageSrc = 'cid:'.$CIDbarcode;
				$this->emailObj->AddImage($barcodeImage);					
			}
			else
			{
				$imageSrc  = 'data:image/png;base64,';
				$imageSrc .= chunk_split ( base64_encode ( $barcodeImage->bin ) ); //  $barcodeImage->image;
//				$imageSrc .= "==";
			}
				
			$imageSrc = '<img alt="Sale Barcode" src="'.$imageSrc.'">';
			
			return $imageSrc;
		}
		
		function GetEmailTemplatePath($templateID, $sale = array())
		{
			if (count($sale) >= 1)
			{
				if ($sale[0]->saleStatus == PAYMENT_API_SALESTATUS_RESERVED)
				{
					$templateID = 'ReserveEMailTemplatePath';
				}
			}
			
			return parent::GetEmailTemplatePath($templateID, $sale);
		}
				

		// Commented out Class Def (StageShowDBaseClass)
    	function GetDefaultGateway()
    	{
    		return 'paypal_exp';
		}
		
		function GetTicketSpec($perfID)
		{
			$sql = 'SELECT perfTicketSpec FROM '.STAGESHOW_PERFORMANCES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.$perfID;
			
			$results = $this->get_results($sql);
			if (count($results) == 0) return null;
			
			$ticketSpec = $results[0]->perfTicketSpec;
			return unserialize($ticketSpec);
		}

		function SetTicketSpec($perfID, $ticketSpecObj)
		{
			$ticketSpec = serialize($ticketSpecObj);
			return $this->SetTicketSpecByPerfID($perfID, $ticketSpec);
		}

		function SetTicketSpecByPerfID($perfID, $ticketSpec)
		{
			$sql = 'UPDATE '.STAGESHOW_PERFORMANCES_TABLE;
			$sql .= ' SET perfTicketSpec="'.esc_sql($ticketSpec).'"';
			$sql .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.$perfID;
						
			$this->query($sql);	
					
     		return $this->GetInsertId();			
		}

		function ClearTicketSpecByPerfID($perfID)
		{
			$this->SetTicketSpecByPerfID($perfID, null);
		}

		function ClearTicketSpecByShowID($showID)
		{
			$sql = 'UPDATE '.STAGESHOW_PERFORMANCES_TABLE;
			$sql .= ' SET perfTicketSpec=null';
			$sql .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.showID='.$showID;
						
			$this->query($sql);	
		}

		function GetSaleExtras($itemNo, $results)
		{
			$saleExtras = array();
			
			if (isset($results['itemDetail' . $itemNo]))
			{
				$saleExtras['ticketSeat'] = $results['itemDetail' . $itemNo];
			}

			return $saleExtras;
		}
				
		function GetPerformanceAndZonesListByShowID($showID)
		{
			$sqlFilters['showID'] = $showID;
			
			$sql = "SELECT * FROM ".STAGESHOW_PERFORMANCES_TABLE;
			$sql .= " LEFT JOIN ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= ' LEFT JOIN '.STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.seatingID='.STAGESHOW_PERFORMANCES_TABLE.'.perfSeatingID';

			// Add SQL filter(s)
			$sql .= $this->GetWhereSQL($sqlFilters);
			$sql .= $this->GetOptsSQL($sqlFilters);
			
			$sql .= ' ORDER BY '.STAGESHOW_PERFORMANCES_TABLE.'.showID, '.STAGESHOW_PERFORMANCES_TABLE.'.perfDateTime';
			
			$perfsListArray = $this->get_results($sql);

			return $perfsListArray;
		}
		
		function PerformanceHasSeatingPlan($perfID)
		{
			$sql  = "SELECT * FROM ".STAGESHOW_PRICES_TABLE;
			$sql .= ' LEFT JOIN '.STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.zoneID='.STAGESHOW_PRICES_TABLE.'.priceZoneID';
			$sql .= ' WHERE '.STAGESHOW_PRICES_TABLE.'.perfID='.$perfID;
			$sql .= ' AND '.STAGESHOW_ZONES_TABLE.'.zoneAllocSeats=1';
			$pricesArray = $this->get_results($sql);

			$sql .= ' GROUP BY '.STAGESHOW_PRICES_TABLE.'.perfID';
			
			$pricesArray = $this->get_results($sql);

			return (count($pricesArray) > 0);
		}

		function AddSeatingLayout($seatLayoutName, $seatVenueName='', $seatLayoutSeats=0)
		{
			$sql = 'INSERT INTO '.STAGESHOW_SEATING_TABLE.'(seatingRef, seatingVenue, seatingTemplate, seatingSeats) VALUES("'.esc_sql($seatLayoutName).'", "'.esc_sql($seatVenueName).'", "", "'.$seatLayoutSeats.'")';
			$this->query($sql);	
					
     		return $this->GetInsertId();			
		}

		function SaveSeatingRef($seatingID, $seatingRef)
		{
			$sql = 'UPDATE '.STAGESHOW_SEATING_TABLE;
			$sql .= ' SET seatingRef="'.esc_sql($seatingRef).'"';
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID='.$seatingID;
			
			$this->query($sql);	
					
     		return $this->GetInsertId();			
		}

		function SaveSeatingVenue($seatingID, $seatingVenue)
		{
			$sql = 'UPDATE '.STAGESHOW_SEATING_TABLE;
			$sql .= ' SET seatingVenue="'.esc_sql($seatingVenue).'"';
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID='.$seatingID;
			
			$this->query($sql);	
					
     		return $this->GetInsertId();			
		}

		function SaveSeatingSeats($seatingID, $seatsInLayout)
		{
			$sql = 'UPDATE '.STAGESHOW_SEATING_TABLE;
			$sql .= ' SET seatingSeats="'.$seatsInLayout.'"';
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID='.$seatingID;
			
			$this->query($sql);	
					
     		return $this->GetInsertId();			
		}

		function GetTagParam(&$tag, $paramId, &$errStatus)
		{
			$param = '';
			$errStatus = '';
			
			$srchText = ' '.$paramId.'=';
			$paramStartPosn = StageShowLibMigratePHPClass::Safe_strpos($tag, $srchText);
			if ($paramStartPosn <= 0)
			{
				$errStatus = __('Error Finding Tag', 'stageshow').' - ('.$paramId.' in '.StageShowLibMigratePHPClass::Safe_htmlspecialchars($tag).')';
			}	
			else
			{
				$valueStartPosn = $paramStartPosn + StageShowLibMigratePHPClass::Safe_strlen($srchText);
				$specDelim = $tag[$valueStartPosn];
				if ($specDelim != '"')
				{
					$errStatus = __('Missing " in Tag Param', 'stageshow');
				}
				else
				{
					// Just use a double quote as the deminator
					$valueStartPosn++;
				
					$paramEndPosn = StageShowLibMigratePHPClass::Safe_strpos($tag, $specDelim, $valueStartPosn);
					if ($paramEndPosn <= 0)
					{
						$errStatus = __('Missing End of Tag Marker', 'stageshow').' - ('.$paramId.')';
					}	
					else
					{
						$param = StageShowLibMigratePHPClass::Safe_substr($tag, $valueStartPosn, $paramEndPosn-$valueStartPosn);	
						$tag = StageShowLibMigratePHPClass::Safe_substr($tag, 0, $paramStartPosn).StageShowLibMigratePHPClass::Safe_substr($tag, $paramEndPosn+1);
					}
				}
			}
			
			if ($errStatus != '')
			{
				$errStatus .= ' - ('.$paramId.' param in '.StageShowLibMigratePHPClass::Safe_htmlspecialchars($tag).')';
			}

			return $param;			
		}

		function UpdateSeatingTemplateTags($seatsTemplate, &$statusMsg, $seatingID = 0)
		{
			$seatPlans = array();
			
			if ($seatingID > 0)
			{
				// Get Seating Layout Zone Specs
				$seatPlans = $this->GetSeatingLayoutByID($seatingID);	
			}
			
			$decodes = StageShowZonesDBaseClass::GetSeatingDecodeDef($this, $seatingID);
			$addEvents = ($seatingID > 0);
			
			return $this->UpdateSeatingTemplateTagsFromDefs($seatsTemplate, $seatPlans, $statusMsg, $decodes, $addEvents);
		}

		function UpdateSeatingTemplateTagsFromDefs($seatsTemplate, $seatPlans, &$statusMsg, $decode = null, $addEvents = false)
		{			
			$useSeatingDecoder = ($decode != null);

			$zoneSpecs = array();
			$seatsCount = 0;
				
			$offset = -1;
			$seatIdRoots = array('seat' => $this->adminOptions['BOClass_SeatLayout'], 'space' => $this->adminOptions['BOClass_Space']);
			foreach ($seatIdRoots as $loopIndex => $seatIdRoot)
			{
				$seatIdRootLen = StageShowLibMigratePHPClass::Safe_strlen($seatIdRoot);		

				while (true)
				{
					// Find the next seat spec ... working backwards
					$seatSpecPosn = StageShowLibMigratePHPClass::Safe_strrpos($seatsTemplate, 'id="'.$seatIdRoot, $offset);
					if ($seatSpecPosn <= 0)
					{
						break;
					}	
					$offset = $seatSpecPosn-StageShowLibMigratePHPClass::Safe_strlen($seatsTemplate);
					
					// Find the start of the tag
					$seatSpecTagStart = StageShowLibMigratePHPClass::Safe_strrpos($seatsTemplate, '<', $offset);
					if ($seatSpecTagStart <= 0)
					{
						break;
					}	
					$offset = $seatSpecTagStart-StageShowLibMigratePHPClass::Safe_strlen($seatsTemplate);
					
					// Find the end of the tag
					$seatSpecTagEnd = StageShowLibMigratePHPClass::Safe_strpos($seatsTemplate, '>', $seatSpecTagStart);
					if ($seatSpecTagStart <= 0)
					{
						break;
					}	
					
					$origSeatTag = StageShowLibMigratePHPClass::Safe_substr($seatsTemplate, $seatSpecTagStart, $seatSpecTagEnd-$seatSpecTagStart+1);				
					$errStatus = '';
					$seatTag = $origSeatTag;
						
					if ($loopIndex == 'seat')
					{
						$idParam = $this->GetTagParam($seatTag, 'id', $errStatus);			
						if ($idParam == '')
						{
							$statusMsg  = $errStatus;
							return null;
						}
						
						$seatSpec = StageShowLibMigratePHPClass::Safe_str_replace($this->adminOptions['BOClass_SeatLayout'], "", $idParam);
						$this->GetTagParam($seatTag, 'title', $errStatus);	// Strip out title tag	
						if ($useSeatingDecoder)
						{
							$titleParam = StageShowZonesDBaseClass::DecodeSeatsFromDefs($this, $seatSpec, $decode);
						}
						else
						{
							$titleParam = __('Row', 'stageshow').' '.StageShowLibMigratePHPClass::Safe_str_replace("_", ' '.__('Seat', 'stageshow').' ', $seatSpec);
						}	
						$seatCoords = explode('_', $seatSpec);
						$titleParam = apply_filters('stageshow_filter_seattitle', $titleParam, $seatCoords[0], $seatCoords[1]);
											
						$nameParam = $this->GetTagParam($seatTag, 'name', $errStatus);			
						$onclickParam = $this->GetTagParam($seatTag, 'onclick', $errStatus);			
					}

					$classParam = $this->GetTagParam($seatTag, 'class', $errStatus);	
					if ($classParam != '')
					{
						$classes = explode(' ', $classParam);
						$classParam = '';
						$ssClassPrefixes = array(
							'stageshow-boxoffice-zone',
							$this->adminOptions['BOClass_Seat'],
							);
						
						foreach ($classes as $index => $nxtClass)
						{
							$addClass = true;
							foreach ($ssClassPrefixes as $ssClassPrefix)
							{
								$ssClassPrefixLen = StageShowLibMigratePHPClass::Safe_strlen($ssClassPrefix);
								if (StageShowLibMigratePHPClass::Safe_substr($nxtClass, 0, $ssClassPrefixLen) == $ssClassPrefix)
								{
									// Remove selected 'stageshow-boxoffice-****' class identifiers
									$addClass = false;
									break;
								}
							}
							
							if ($addClass)
							{
								$classParam .= $nxtClass.' ';
							}
						}
						
						$classParam = StageShowLibMigratePHPClass::Safe_trim($classParam);
						
					}
									
					$tagParams  = '';
					if ($loopIndex == 'seat')
					{
						$tagParams .= ' id="'.$idParam.'"';
						if ($addEvents)
						{
							$seatSpec = StageShowLibMigratePHPClass::Safe_substr($idParam, $seatIdRootLen);
							$foundZone = false;
							
							foreach ($seatPlans as $seatPlan)
							{
								if ($this->IsInZoneSpec($seatPlan->zoneSpec, $seatSpec))
								{
									$tagParams .= ' name="'.$idParam.'"';
									$tagParams .= ' title="'.$titleParam.'"';
									$tagParams .= ' onclick="stageshow_ClickSeatEx(event, this)"'; 
									
									$zoneName = StageShowLibMigratePHPClass::Safe_str_replace(' ', '_', $seatPlan->zoneRef);

									$classParam .= ' '.$this->adminOptions['BOClass_Seat'];
									$classParam .= ' '.$this->adminOptions['BOClass_SeatState'].'unknown';
									$classParam .= ' stageshow-boxoffice-zone'.$seatPlan->zoneID;
									$classParam .= ' stageshow-boxoffice-zone-'.$zoneName;
									
									$foundZone = true;
									$seatsCount++;
									break;
								}
							}
							
							if (!$foundZone)
							{
								$statusMsg  = __('No Zone Found for Seat', 'stageshow').' '.$seatSpec;
								return null;
							}
						
						}						
					}
					else
					{
						if ($addEvents)
						{
							$classParam .= ' '.$this->adminOptions['BOClass_Seat'];
						}
					}
					$classParam = StageShowLibMigratePHPClass::Safe_trim($classParam);
					
					$tagParams .= ' class="'.$classParam.'"';						
										
					$seatTag = StageShowLibMigratePHPClass::Safe_str_replace('>', ' '.StageShowLibMigratePHPClass::Safe_trim($tagParams).'>', $seatTag);
					
					$seatsTemplate = StageShowLibMigratePHPClass::Safe_str_replace($origSeatTag, $seatTag, $seatsTemplate);

					$offset = $seatSpecTagStart-StageShowLibMigratePHPClass::Safe_strlen($seatsTemplate);
					
				}
			}
			
			$statusMsg  = __('Seating Plan Updated', 'stageshow');
			$statusMsg .= ' - '.$seatsCount." Seats";
			$statusMsg .= ' - Template '.StageShowLibMigratePHPClass::Safe_strlen($seatsTemplate)." Bytes";

			return $seatsTemplate;
		}
		
		function ImportSeatingTemplate($fileTagId, &$statusMsg)
		{
			$filePath = $_FILES[$fileTagId]['tmp_name'];
			$fileName = $_FILES[$fileTagId]['name'];
	
			$seatingID = StageShowLibHTTPIO::GetRequestedInt('seatingID', 0);
			if ($seatingID == 0) 
			{
				$statusMsg  = __('Template Import Error', 'stageshow').' - ';
				$statusMsg .= __('Seating ID not specified', 'stageshow');
				return false;
			}
			
			return $this->ImportSeatingTemplateFromFile($filePath, $fileName, $seatingID, $statusMsg);
		}

		function ImportSeatingTemplateFromFile($filePath, $fileName, $seatingID, &$statusMsg)
		{
			if (StageShowLibMigratePHPClass::Safe_strlen($filePath) == 0) 
			{
				$statusMsg  = __('Template Import Error', 'stageshow').' - ';
				$statusMsg .= __('Empty Filename', 'stageshow');
				return false;
			}
			
			$seatsTemplate = file_get_contents($filePath);
				
			if (StageShowLibMigratePHPClass::Safe_strlen($seatsTemplate) == 0) 
			{
				$statusMsg  = __('Template Import Error', 'stageshow').' - ';
				$statusMsg .= __('Empty File', 'stageshow');
				return false;
			}

			$seatsTemplate = $this->UpdateSeatingTemplateTags($seatsTemplate, $statusMsg, $seatingID);
			if ($seatsTemplate == null) 
			{
				return false;
			}
			
			$this->SaveSeatingTemplate($seatingID, $seatsTemplate, $fileName);	
			
			return true;			
		}
		
		function SaveSeatingTemplate($seatingID, $seatsTemplate, $seatsImportFile = "")
		{
			$sql = 'UPDATE '.STAGESHOW_SEATING_TABLE;
			$sql .= ' SET seatingTemplate="'.esc_sql($seatsTemplate).'"';
			
			$seatingImportDateTime = ($seatsImportFile != '') ? current_time('mysql') : NULL;
			$sql .= ' , seatingImportFile="'.$seatsImportFile.'"';
			$sql .= ' , seatingImportDateTime="'.$seatingImportDateTime.'"';
			
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID='.$seatingID;
			
			$this->query($sql);	
					
     		return $this->GetInsertId();			
		}

		function SaveSeatingDecoder($seatingID, $seatingDecodeTable, $seatDecodes)
		{
			$seatDecodesObj = serialize($seatDecodes);
			
			$sql = 'UPDATE '.STAGESHOW_SEATING_TABLE;
			$sql .= ' SET seatingDecodeTable="'.esc_sql($seatingDecodeTable).'"';
			$sql .= " ,   seatDecodesObj='".esc_sql($seatDecodesObj)."'";
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID='.$seatingID;
			
			$this->query($sql);	
					
     		return $this->GetInsertId();			
		}

		function LoadSeatingDecoder($seatingDecodeTable)
		{
			if (StageShowLibMigratePHPClass::Safe_strlen($seatingDecodeTable) == 0)
			{
				return null;
			}
			
			$seatDecodes = new stdClass;			
			$seatDecodes->file = $seatingDecodeTable;
			
			$pluginID = STAGESHOW_FOLDER;
			$templatePath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/html/'.$seatDecodes->file;

			// Load file
			$seatingDecodeDef = $this->ReadTemplateFile($templatePath);
			if (StageShowLibMigratePHPClass::Safe_strlen($seatingDecodeDef) == 0)
			{
				$seatDecodes->error = 'Zero Length or Inaccessible';
				return $seatDecodes;
			}
				
			// For each line check index and load row and seat IDs
			$lines = 0;
			$rowNo = 1;
			
			$decodeLines = preg_split('/\r\n|\r|\n/', $seatingDecodeDef);

			foreach ($decodeLines as $lineData)
			{
				$lines++;
				
				// Ignore blank lines
				if (StageShowLibMigratePHPClass::Safe_trim($lineData) == '') continue;
				
				$lineFields = explode("\t", $lineData);
				if ( $lineFields[0] != $rowNo )
				{
					if ($lines == 1)
					{
						// First row may contain column headers - Ignore!
						continue;
					}
					
					// Unexpected Row Number - Abort
					$seatDecodes->error = "Unexpected Row Number at Row $rowNo";
					return $seatDecodes;
				}

				$seatDecodes->rows[$rowNo] = $lineFields[1];
				$seatDecodes->seats[$rowNo] = $lineFields[2];
				
				$rowNo++;
			}
			
			return $seatDecodes;			
		}

		function PurgeSeatingTemplates()
		{		
			$sql  = 'DELETE FROM '.STAGESHOW_ZONES_TABLE;
			$sql .= ' WHERE zoneSpec IS NULL';			 
			$this->query($sql);
		
			$sql  = 'DELETE '.STAGESHOW_SEATING_TABLE.' FROM '.STAGESHOW_SEATING_TABLE;
			$sql .= ' LEFT JOIN '.STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.seatingID='.STAGESHOW_SEATING_TABLE.'.seatingID';
			$sql .= ' WHERE zoneID IS NULL';			 
			$this->query($sql);
			
			$this->PurgeSeatingPlans();
		}

		function PurgeSeatingPlans()
		{		
			$sql  = 'DELETE '.STAGESHOW_PRESETS_TABLE.' FROM '.STAGESHOW_PRESETS_TABLE;
			$sql .= ' LEFT JOIN '.STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.zoneID='.STAGESHOW_PRESETS_TABLE.'.priceZoneID';
			$sql .= ' WHERE '.STAGESHOW_ZONES_TABLE.'.zoneID IS NULL';			 
			$this->query($sql);
			
			$sql  = 'DELETE '.STAGESHOW_PLANS_TABLE.' FROM '.STAGESHOW_PLANS_TABLE;
			$sql .= ' LEFT JOIN '.STAGESHOW_SEATING_TABLE.' ON '.STAGESHOW_SEATING_TABLE.'.seatingID='.STAGESHOW_PLANS_TABLE.'.seatingID';
			$sql .= ' WHERE '.STAGESHOW_PLANS_TABLE.'.seatingID > 0';			 
			$sql .= ' AND '.STAGESHOW_SEATING_TABLE.'.seatingID IS NULL';			 
			$this->query($sql);
		}
		
		function GetUniqueSeatingLayoutRef()
		{
			$planRefNo = 1;
			while (true)
			{
				// Query Database for proposed plan Ref until we find one that doesn't already exist
				$planRef = 'Seating Plan '.$planRefNo;
				if ($this->IsSeatingLayoutRefUnique($planRef))
					break;
				$planRefNo++;
			}
			
			return $planRef;
		}

		function IsSeatingLayoutRefUnique($seatingRef)
		{
			$sql  = 'SELECT COUNT(*) AS MatchCount FROM '.STAGESHOW_SEATING_TABLE;
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingRef="'.esc_sql($seatingRef).'"';
			 
			$plansList = $this->get_results($sql);
			return ($plansList[0]->MatchCount > 0) ? false : true;
		}
		
		function GetSeatingLayoutByName($seatLayoutName = 0)
		{
			$sqlFilters['seatingRef'] = $seatLayoutName;
			return $this->GetSeatingLayout($sqlFilters);				
		}

		function CanEditSeatingLayout($seatingID)
		{
			return $this->SeatingLayoutInUse($seatingID, true);
		}

		function CanDeleteSeatingPlan($seatingID)
		{
			return $this->SeatingLayoutInUse($seatingID);
		}

		function SeatingLayoutInUse($seatingID, $includeSales = false)
		{
			// Test if seating layout can be edited
			// Returns TRUE if there are no performances using this seating layout
			// if $includeSales is TRUE also checks there are no sales using this seating layout
			$sql  = 'SELECT COUNT(seatingID) as matches FROM '.STAGESHOW_SEATING_TABLE;			
			$sql .= ' JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_SEATING_TABLE.'.seatingID='.STAGESHOW_PERFORMANCES_TABLE.'.perfSeatingID';
			if ($includeSales)
			{
				$sql .= ' JOIN '.STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_PRICES_TABLE.'.perfID';
				$sql .= ' JOIN '.STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceID='.STAGESHOW_TICKETS_TABLE.'.priceID';
			}
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID="'.$seatingID.'"';						
			
			$results = $this->get_results($sql);
			
			return ($results[0]->matches == 0);
		}

		function GetActiveZones($seatingID = 0)
		{
			$zonesList = array();
			
			$sql  = 'SELECT '.STAGESHOW_SEATING_TABLE.'.seatingID, zoneID, seatingRef, zoneRef';
			$sql .= ' FROM '.STAGESHOW_SEATING_TABLE;			
			$sql .= ' JOIN '.STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.seatingID='.STAGESHOW_SEATING_TABLE.'.seatingID';
			$sql .= ' JOIN '.STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceZoneID='.STAGESHOW_ZONES_TABLE.'.zoneID';
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID="'.$seatingID.'"';						
			$sql .= ' GROUP BY '.STAGESHOW_ZONES_TABLE.'.zoneID';
			
			$results = $this->get_results($sql);
			
			foreach ($results as $result)
			{
				$zonesList[$result->zoneID] = true;
			}
			
			return $zonesList;
		}

		function GetSeatingLayoutByID($seatingID = 0)
		{
			$sqlFilters['seatingID'] = $seatingID;
			return $this->GetSeatingLayout($sqlFilters);				
		}

		function GetSeatingLayoutZoneCounts()
		{
			$sqlFilters['seatingZoneCounts'] = true;
			return $this->GetSeatingLayout($sqlFilters);				
		}

		function GetSeatingLayout($sqlFilters = null)
		{
			$sqlOptions = '';
			
			$sqlSelect = STAGESHOW_SEATING_TABLE.'.seatingID, seatingRef, seatingVenue, seatingDecodeTable, seatingSeats, seatingImportFile, seatingImportDateTime';
			if (isset($sqlFilters['seatingRef']))
			{
				$sqlSelect  .= ', zoneID, zoneRef, zoneSpec, zoneSeats, zoneAllocSeats';
				$sqlOptions .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingRef="'.esc_sql($sqlFilters['seatingRef']).'"';						
				$sqlOptions .= ' ORDER BY '.STAGESHOW_SEATING_TABLE.'.seatingRef, '.STAGESHOW_ZONES_TABLE.'.zoneSpec';
			}
			elseif (isset($sqlFilters['seatingID']))
			{
				$sqlSelect  .= ', zoneID, zoneRef, zoneSpec, zoneSeats, zoneAllocSeats';
				$sqlSelect  .= ', COUNT(priceNoOfSeats) AS seatsCount';				
				$sqlOptions .= ' LEFT JOIN '.STAGESHOW_PERFORMANCES_TABLE.' ON '.STAGESHOW_SEATING_TABLE.'.seatingID='.STAGESHOW_PERFORMANCES_TABLE.'.perfSeatingID';
				$sqlOptions .= ' LEFT JOIN '.STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PERFORMANCES_TABLE.'.perfID='.STAGESHOW_PRICES_TABLE.'.perfID';
				$sqlOptions .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID="'.$sqlFilters['seatingID'].'"';						
				$sqlOptions .= ' GROUP BY '.STAGESHOW_ZONES_TABLE.'.zoneID';
				$sqlOptions .= ' ORDER BY '.STAGESHOW_SEATING_TABLE.'.seatingRef, '.STAGESHOW_ZONES_TABLE.'.zoneSpec';
			}
			elseif (isset($sqlFilters['seatingZoneCounts']))
			{
				$sqlSelect  .= ', COUNT('.STAGESHOW_ZONES_TABLE.'.zoneID) AS zonesCount';
				$sqlOptions  = ' GROUP BY '.STAGESHOW_SEATING_TABLE.'.seatingID';
				$sqlOptions .= ' ORDER BY '.STAGESHOW_SEATING_TABLE.'.seatingRef';
			}
			else
			{
				$sqlSelect  .= ', zoneID, zoneRef, zoneSpec, zoneSeats, zoneAllocSeats';
				$sqlOptions  = ' ORDER BY '.STAGESHOW_SEATING_TABLE.'.seatingRef, '.STAGESHOW_ZONES_TABLE.'.zoneSpec';
			}
			//$sqlSelect  .= ', * ';
			
			$sql  = 'SELECT '.$sqlSelect.' FROM '.STAGESHOW_SEATING_TABLE;			
			$sql .= ' LEFT JOIN '.STAGESHOW_ZONES_TABLE.' ON '.STAGESHOW_ZONES_TABLE.'.seatingID='.STAGESHOW_SEATING_TABLE.'.seatingID';
			$sql .= $sqlOptions;
			
			$seatingListArray = $this->get_results($sql);
			
			return $seatingListArray;
		}
				
		function UpdateSeatingLayout($perfID, $newSeatingID)
		{
			if ($newSeatingID === '') $newSeatingID = 0;
			$sqlSET = 'perfSeatingID="'.$newSeatingID.'"';
			return $this->UpdatePerformanceEntry($perfID, $sqlSET);
		}								
				
		function DeleteSeatingLayout($seatingID)
		{
			$sql  = 'DELETE FROM '.STAGESHOW_ZONES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_ZONES_TABLE.'.seatingID="'.$seatingID.'"';								
			$this->query($sql);
			 
			$sql  = 'DELETE FROM '.STAGESHOW_SEATING_TABLE;
			$sql .= ' WHERE '.STAGESHOW_SEATING_TABLE.'.seatingID="'.$seatingID.'"';					 
			$this->query($sql);
									
			$this->PurgeSeatingPlans();
		}
				
		function IsSeatingZoneRefUnique($seatingID, $zoneRef)
		{
			$sql  = 'SELECT COUNT(*) AS MatchCount FROM '.STAGESHOW_ZONES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_ZONES_TABLE.'.seatingID="'.$seatingID.'"';
			$sql .= ' AND '.STAGESHOW_ZONES_TABLE.'.zoneRef="'.$zoneRef.'"';
			 
			$zonesList = $this->get_results($sql);
			return ($zonesList[0]->MatchCount > 0) ? false : true;
		}
				
		function GetUniqueSeatingZoneRef($seatingID)
		{
			$zoneRefNo = 1;
			while (true)
			{
				// Query Database for proposed zone Ref until we find one that doesn't already exist
				$zoneRef = 'Zone '.$zoneRefNo;
				if ($this->IsSeatingZoneRefUnique($seatingID, $zoneRef))
					break;
				$zoneRefNo++;
			}
			
			return $zoneRef;
		}

		function DeleteSeatingZone($zoneID)
		{
			$sql  = 'DELETE FROM '.STAGESHOW_ZONES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_ZONES_TABLE.'.zoneID="'.$zoneID.'"';					
			 
			$this->query($sql);
		}
				
				
		function GetSeatingZones($seatingID)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_ZONES_TABLE;			
			$sql .= ' WHERE '.STAGESHOW_ZONES_TABLE.'.seatingID="'.$seatingID.'"';					
			$sql .= ' ORDER BY '.STAGESHOW_ZONES_TABLE.'.zoneRef';
			
			$zonesArray = $this->get_results($sql);
			
			return $zonesArray;
		}
				
		function AddSeatingZone($seatingID, $zoneSpec, $zoneRef, $zoneSeats, $zoneAllocSeats = 1)
		{
			$sql  = 'INSERT INTO '.STAGESHOW_ZONES_TABLE;
			$sql .= '(seatingID, zoneRef, zoneSpec, zoneSeats, zoneAllocSeats) ';
			$sql .= 'VALUES("'.$seatingID.'", "'.esc_sql($zoneRef).'", "'.esc_sql($zoneSpec).'", "'.$zoneSeats.'", "'.$zoneAllocSeats.'")';
			$this->query($sql);	
					
     		return $this->GetInsertId();
		}
								
		function UpdateSeatingZone($zoneID, $zoneSpec, $zoneRef, $zoneSeats, $zoneAllocSeats = 1)
		{
			$sql = 'UPDATE '.STAGESHOW_ZONES_TABLE;
			$sql .= ' SET zoneRef="'.esc_sql($zoneRef).'"';
			$sql .= ' ,   zoneSpec="'.esc_sql($zoneSpec).'"';
			$sql .= ' ,   zoneSeats="'.$zoneSeats.'"';
			$sql .= ' ,   zoneAllocSeats="'.$zoneAllocSeats.'"';
			$sql .= ' WHERE '.STAGESHOW_ZONES_TABLE.'.zoneID='.$zoneID;
			$this->query($sql);	
					
     		return $zoneID;
		}
				
		function GetTotalZoneSeats($seatingID)
		{
			$sql  = 'SELECT *, SUM(zoneSeats) AS totalSeats FROM '.STAGESHOW_ZONES_TABLE;			
			$sql .= ' WHERE seatingID="'.$seatingID.'"';					
			$sql .= ' GROUP BY seatingID';
			
			$result = $this->get_results($sql);
			if (count($result) == 0) return 0;
			
			return $result[0]->totalSeats;
		}
		
		function UpdatePlanSeating($planID, $seatingID)
		{
			$sql  = 'UPDATE '.STAGESHOW_PLANS_TABLE;
			$sql .= ' SET seatingID="'.$seatingID.'"';
			$sql .= ' WHERE '.STAGESHOW_PLANS_TABLE.'.planID='.$planID;;
			$this->query($sql);	

			return true;							
		}
		
		function SetZoneID($priceID, $zoneID)
		{
			$sql  = 'UPDATE '.STAGESHOW_PRICES_TABLE;
			$sql .= ' SET priceZoneID="'.$zoneID.'"';
			$sql .= ' WHERE '.STAGESHOW_PRICES_TABLE.'.priceID='.$priceID;;
			$this->query($sql);	

			return true;							
		}
		
		function ParseLocation($location, &$specRslt, $dest) 
		{
			if ($location == '') return true;
			
			$fields = explode(STAGESHOW_ZONESPEC_SEATSEP, $location);
			if (count($fields) != 2) 
			{
				$specRslt->ErrMsg = "Could not split $location into row and seat<br></br>";
				return false; 
			}
			
			for ($n=0; $n<=1; $n++)
			{
				$fieldsVal = StageShowLibMigratePHPClass::Safe_trim($fields[$n]);
				$addHalf[$n] = StageShowLibMigratePHPClass::Safe_strpos($fieldsVal, STAGESHOW_ZONESPEC_ADDHALF);
				$fieldsVal = StageShowLibMigratePHPClass::Safe_str_replace(STAGESHOW_ZONESPEC_ADDHALF, '', $fieldsVal);
				if (!is_numeric($fieldsVal))  
				{
					$specRslt->ErrMsg = "Could not convert ".$fields[$n]." into number<br></br>";
					return false; 
				}
			}

			switch ($dest)
			{
				case 'start':
					$specRslt->startRowNo = $specRslt->endRowNo = $specRslt->YPosn = $fields[0];
					$specRslt->startColNo = $specRslt->endColNo = $specRslt->XPosn = $fields[1];
					break;
					
				case 'end':
					$specRslt->endRowNo = $fields[0];
					$specRslt->endColNo = $fields[1];
					break;
					
				case 'posn':
					$specRslt->YPosn = $fields[0] + ($addHalf[0] ? 0.5 : 0);
					$specRslt->XPosn = $fields[1] + ($addHalf[1] ? 0.5 : 0);
					break;
			}

			$specRslt->OK = true;			
			
			return true;
		}
		
		function ParseSpec($spec) 
		{
			$specRslt = new stdClass();
			
			$specRslt->type = '';
			$specRslt->id = '';
			$specRslt->spaces = 0;
			$specRslt->OK = false;			
			$specRslt->ErrMsg = '';			
			
			$spec = StageShowLibMigratePHPClass::Safe_strtolower($spec);
			preg_match('|(\[([udlri]*)([0-9\+.\-]+)\])*([nrt]*)([0-9.]+)(\-([0-9.]+))*([sa]*)|', $spec, $matches);
			if (count($matches) != 9)
			{
				$specRslt->ErrMsg = "Could not parse $spec<br></br>";
				return $specRslt; 
			}

			$zspecOrientation = $matches[2];
			$zspecRelocation = $matches[3];
			$zspecId = $matches[4];
			$zspecStartSeat = $matches[5];
			$zspecEndSeat = $matches[7];
			$zspecSpaces = $matches[8];
			
			switch ($zspecId)
			{
				case 'n':
					if ($zspecEndSeat == '') $zspecEndSeat = $zspecStartSeat;
					$zspecStartSeat = '1.'.$zspecStartSeat;
					$zspecEndSeat   = '1.'.$zspecEndSeat;
					break;
				case 'r':
					if ($zspecEndSeat == '') $zspecEndSeat = $zspecStartSeat;
					$zspecStartSeat = $zspecStartSeat.'.1';
					$zspecEndSeat   = $zspecEndSeat.'.1';
					break;
			}
			
			$dirn = 'rd';
			if ($zspecRelocation != '')
			{
				if (!$this->ParseLocation($zspecStartSeat, $specRslt, 'start')) 	
					return $specRslt; 
				
				if ($zspecOrientation == '') $zspecOrientation = 'rd';
				
				switch ($zspecOrientation)
				{
					case 'rd':
					case 'ld':
					case 'ru':
					case 'lu':
					case 'dr':
					case 'dl':
					case 'ur':
					case 'ul':
						$dirn = $zspecOrientation;
						if (!$this->ParseLocation($zspecRelocation, $specRslt, 'posn'))
							return $specRslt; 

						$specRslt->type = self::PositionType_Seat;
						break;
					
					case 'i':
						if (!is_numeric($zspecStartSeat))
						{
							$specRslt->ErrMsg = "Non-numeric block id ".$zspecStartSeat." in $spec<br></br>";
							return $specRslt; 
						}
						$specRslt->type = self::PositionType_Block.$zspecRelocation;
						break;
							
					default:
						$specRslt->ErrMsg = "Invalid block specifier ".$zspecOrientation." in $spec<br></br>";
						return $specRslt; 
				}					

				$specRslt->Dirn = $dirn;
			}
			else
			{
				if (!$this->ParseLocation($zspecStartSeat, $specRslt, 'start'))
					return $specRslt;
					
				$specRslt->Dirn = $dirn;
				$specRslt->type = self::PositionType_Seat;
			}

			if (!$this->ParseLocation($zspecEndSeat, $specRslt, 'end'))
				return $specRslt;
			
			if ($zspecId != '')
			{
				$specRslt->type = self::PositionType_Id;
				$specRslt->id = $zspecId;
			}
						
			$specRslt->spaces = StageShowLibMigratePHPClass::Safe_strlen($zspecSpaces);

			$specRslt->OK = true;			
			return $specRslt;
		}
				
		function IsInZoneSpec($seatPlan, $seatSpec)
		{			
			$seatSpecElements = explode('_', $seatSpec);
			if (count($seatSpecElements) < 2) return false;
			
			$selectedSeatsList = explode(',', $seatPlan);
			foreach ($selectedSeatsList as $key => $selectedSeatRange)
			{
				$blockSpec = $this->ParseSpec($selectedSeatRange);
				if ($blockSpec->type != self::PositionType_Seat) continue;

				if ((int)$seatSpecElements[0] < (int)$blockSpec->startRowNo) continue;
				if ((int)$seatSpecElements[1] < (int)$blockSpec->startColNo) continue;
				
				if ((int)$seatSpecElements[0] > (int)$blockSpec->endRowNo) continue;
				if ((int)$seatSpecElements[1] > (int)$blockSpec->endColNo) continue;
				
				return true;
			}

			return false;
		}
		
		function CreateLayoutTemplate($seatingSettings, $seatingID=0) 		
		{
			$seatingDecodeTable = $seatingSettings->seatingDecodeTable;
			$seatDecodes = $this->LoadSeatingDecoder($seatingDecodeTable);	
			$seatDefs = $seatingSettings->zoneSpecs;
			$seatLayoutName = $seatingSettings->seatingRef;
			$seatZoneNames = $seatingSettings->zoneRefs; 

			// Call once to check that the Zone Specs parse
			$templateObj = $this->GetLayoutTemplate($seatingSettings, $seatDecodes);			
			if ($templateObj->errMsg != '') return $templateObj;
			
			$results = array();
			if ($seatingID > 0)
			{
				// Get entry in Seating DB Table (it should exist!)
				$results = $this->GetSeatingLayoutByID($seatingID);
			}
			
			if (count($results) == 0)
			{
				// Add new entry to seating table
				$seatingID = $this->AddSeatingLayout($seatingSettings->seatingRef, $seatingSettings->seatingVenue, $templateObj->seatsInLayout);
			}
			else
			{				
				if ($seatingSettings->seatingRef != $results[0]->seatingRef)	
				{
					$this->SaveSeatingRef($seatingID, $seatingSettings->seatingRef);
				}
				
				if ($seatingSettings->seatingVenue != $results[0]->seatingVenue)	
				{
					$this->SaveSeatingVenue($seatingID, $seatingSettings->seatingVenue);
				}
				
				if ($templateObj->seatsInLayout != $results[0]->seatingSeats)	
				{
					$this->SaveSeatingSeats($seatingID, $templateObj->seatsInLayout);
				}
			}
					
			$this->SaveSeatingDecoder($seatingID, $seatingDecodeTable, $seatDecodes);
			
			// Now save the new zone entries
			$seatPlans = array();
			$seatingSettings->zoneIDs = array();
			foreach ($seatDefs as $zoneIndex => $seatDef)	
			{
				if (count($results) > $zoneIndex)
				{
					$lastZoneEntry = $results[$zoneIndex];
					if ((StageShowLibMigratePHPClass::Safe_strlen($seatDef) == 0) || (StageShowLibMigratePHPClass::Safe_strlen($seatZoneNames[$zoneIndex]) == 0))
					{
						// Clear all zone entries
						$this->DeleteSeatingZone($lastZoneEntry->zoneID);
						
						unset($seatingSettings->zoneRefs[$zoneIndex]);
						unset($seatingSettings->zoneSpecs[$zoneIndex]);
						unset($seatingSettings->zoneAllocSeats[$zoneIndex]);
						unset($seatingSettings->zoneIDs[$zoneIndex]);
						continue;
					}
					else 
					{
						$zoneID = $this->UpdateSeatingZone($lastZoneEntry->zoneID, $seatDef, $seatZoneNames[$zoneIndex], $templateObj->seatsInZone[$zoneIndex], $seatingSettings->zoneAllocSeats[$zoneIndex]);
						$seatingSettings->zoneIDs[$zoneIndex] = $zoneID;						
					}
				}
				else
				{
					$zoneID = $this->AddSeatingZone($seatingID, $seatDef, $seatZoneNames[$zoneIndex], $templateObj->seatsInZone[$zoneIndex], $seatingSettings->zoneAllocSeats[$zoneIndex]);
					$seatingSettings->zoneIDs[$zoneIndex] = $zoneID;						
				}	
				
				$seatPlan = new stdClass();
				$seatPlan->zoneID = $seatingSettings->zoneIDs[$zoneIndex];
				$seatPlan->zoneRef = $seatZoneNames[$zoneIndex];
				$seatPlan->zoneSpec = $seatDef;
				$seatPlans[] = $seatPlan;
			}

			$statusMsg = "";
			$templateObj = $this->GetLayoutTemplate($seatingSettings, $seatDecodes);			
			$templateObj->template = $this->UpdateSeatingTemplateTagsFromDefs($templateObj->template, $seatPlans, $statusMsg, $seatDecodes, true);			

			if ($templateObj->template == null)
			{
				$templateObj->errMsg = $statusMsg;
				return $templateObj;
			}
			
			$this->SaveSeatingTemplate($seatingID, $templateObj->template);				
			$this->PurgeSeatingTemplates();
			
			$templateObj->confirmMsg = $seatingSettings->seatingRef.' '.$statusMsg;
			
			return $templateObj;
		}
		
		function GetTypeAtPosition(&$seatSpecsList, $xCell, $yCell) 		
		{
			if (!isset($seatSpecsList[$yCell][$xCell]))
				return self::PositionType_Space;
		
			return $seatSpecsList[$yCell][$xCell]->type;
		}
		
		function GetLayoutTemplate($seatingSettings, $seatDecodes, $addEvents = true) 		
		{
			$seatLayoutName = StageShowLibMigratePHPClass::Safe_str_replace(' ', '', $seatingSettings->seatingRef);
			$seatDefs = $seatingSettings->zoneSpecs;
			$seatZoneNames = $seatingSettings->zoneRefs; 
			$zoneAllocSeats = $seatingSettings->zoneAllocSeats; 

			$seatSize = 1;
			foreach ($seatDefs as $zoneIndex => $seatDef)	
			{				
				if (StageShowLibMigratePHPClass::Safe_strpos($seatDef, STAGESHOW_ZONESPEC_ADDHALF))
				{
					$seatSize = 2;
					break;
				}
			}
			
			$layoutClass  = $this->adminOptions['BOClass'].'-'.$seatLayoutName.'-';
			
			$templateObj = new stdClass();
			$templateObj->template = '';
			$templateObj->errMsg = '';
						
			$maxRows = 0;
			$maxCols = 0;
			
			$minX = 1;
			$minY = 1;
			
			$maxX = 0;
			$maxY = 0;
			
			$seatSpecsList = array();
			//Parse seat defs to check for validity
			$templateObj->seatsInLayout = 0;
			$templateObj->seatsInZone = array();
			foreach ($seatDefs as $zoneIndex => $seatDef)	
			{				
				$seatDef = StageShowLibMigratePHPClass::Safe_strtolower($seatDef);			// Make it lower case
				$seatDef = StageShowLibMigratePHPClass::Safe_str_replace("\n", '', $seatDef);	// Remove any line ends
				$seatDef = StageShowLibMigratePHPClass::Safe_str_replace("\r", '', $seatDef);	
				$seatDef = StageShowLibMigratePHPClass::Safe_str_replace(' ', '', $seatDef);	// Remove any spaces
				if (StageShowLibMigratePHPClass::Safe_strlen($seatDef) == 0)
				{
					continue;
				}

				$templateObj->seatsInZone[$zoneIndex] = 0;
				$selectedSeatsList = explode(',', $seatDef);
				foreach ($selectedSeatsList as $key => $selectedSeatRange)
				{
					if ($selectedSeatRange == '') continue;
					
					$blockSpec = $this->ParseSpec($selectedSeatRange);

					if (!$blockSpec->OK)
					{
						$templateObj->errMsg  = __("Invalid Seat Specification", 'stageshow')." - $selectedSeatRange";
						$templateObj->errMsg .= "<br>".$blockSpec->ErrMsg;
						return $templateObj;
					}
					
					if (!$zoneAllocSeats[$zoneIndex])
					{
						switch ($blockSpec->type)
						{
							case self::PositionType_Seat:
								break;
								
							default:
								$templateObj->errMsg  = __("Seat Specification invalid for unallocated zone", 'stageshow')." - $selectedSeatRange";
								$templateObj->errMsg .= "<br>".$blockSpec->ErrMsg;
								return $templateObj;							
						}
	
					}
					
					$xPosn = $blockSpec->XPosn;
					$yPosn = $blockSpec->YPosn;
					$seatDirn = $blockSpec->Dirn[0];
					$rowDirn = $blockSpec->Dirn[1];

					for ($rowNo = $blockSpec->startRowNo; $rowNo <= $blockSpec->endRowNo; $rowNo++)
					{
						$maxRows = $blockSpec->endRowNo > $maxRows ? $blockSpec->endRowNo : $maxRows;
						$maxCols = $blockSpec->endColNo > $maxCols ? $blockSpec->endColNo : $maxCols;
						
						for ($colNo = $blockSpec->startColNo; $colNo <= $blockSpec->endColNo; $colNo++)
						{
							$maxX = $xPosn > $maxX ? $xPosn : $maxX;
							$maxY = $yPosn > $maxY ? $yPosn : $maxY;
							
							$seatSpec = new stdClass();
							$seatSpec->rowNo = $rowNo;
							$seatSpec->colNo = $colNo;							
							$seatSpec->zoneIndex = $zoneIndex;							
							$seatSpec->spaces = 0;
							$seatSpec->type = $blockSpec->type;
							$seatSpec->id = $blockSpec->id;
							$seatSpec->topleft = true;
							
							$seatSpec->seatClass = '';
							if ($colNo == $blockSpec->startColNo) 
							{
								$seatSpec->seatClass .= ' '.$this->adminOptions['BOClass'].'-leftend';				
							}
							if ($colNo == $blockSpec->endColNo)				
							{
								$seatSpec->seatClass .= ' '.$this->adminOptions['BOClass'].'-rightend';
								$seatSpec->spaces = $blockSpec->spaces;
							}
							
							if ($seatSpec->type == self::PositionType_Seat)
							{
								$templateObj->seatsInZone[$zoneIndex]++;
							}
						
							if ($zoneAllocSeats[$zoneIndex])
							{
								$yCell = (($yPosn-1)*$seatSize)+1;
								for ($yCount=1; $yCount<=$seatSize; $yCount++, $yCell++)
								{
									$xCell = (($xPosn-1)*$seatSize)+1;
									for ($xCount=1; $xCount<=$seatSize; $xCount++, $xCell++)
									{
										if (isset($seatSpecsList[$yCell][$xCell]->zoneIndex))
										{
											$templateObj->errMsg = __("Location Used Twice", 'stageshow')." - $yPosn.$xPosn";
											return $templateObj;
										}
										$seatSpecsList[$yCell][$xCell] = $seatSpec;
										$seatSpec->topleft = false;
									}								
								}								
							}
							
							switch($seatDirn)
							{
								case 'r': $xPosn++; break;
								case 'l': $xPosn--; break;
								case 'd': $yPosn++; break;
								case 'u': $yPosn--; break;
							}				
						}	
												
						switch($rowDirn)
						{
							case 'r': $xPosn++; $yPosn = $blockSpec->YPosn; break;
							case 'l': $xPosn--; $yPosn = $blockSpec->YPosn; break;
							case 'd': $yPosn++; $xPosn = $blockSpec->XPosn; break;
							case 'u': $yPosn--; $xPosn = $blockSpec->XPosn; break;
						}				
					}

				}
				$templateObj->seatsInLayout += $templateObj->seatsInZone[$zoneIndex];
			}			

			$xCellMax = $maxX * $seatSize;
			$yCellMax = $maxY * $seatSize;
			
			$xCellMin = $minX;
			$yCellMin = $minY;

			$headerWidth = $xCellMax;

			$template = '';
			
			$template .= '<!--'."\n";			
			$template .= 'Max Rows: '.$maxRows."\n";	
			$template .= 'Max Columns: '.$maxCols."\n";	
			$template .= 'Seat Defs: '."\n";
			
			$seatCountsBlockId = 'stageshow-boxoffice-zoneSeatsBlock';
			$seatCountsBlock = '<div id="'.$seatCountsBlockId.'" style="display: none;">';
			
			$textSeats = __('Seats Requested', 'stageshow');
			$textSelected = __('Seats Selected', 'stageshow');

			foreach ($seatDefs as $key => $seatDef)
			{
				if (StageShowLibMigratePHPClass::Safe_strlen($seatDef) == 0) continue;

				$template .= $seatZoneNames[$key]."\t".$seatDef."\n";
				$seatsZoneID = isset($seatingSettings->zoneIDs[$key]) ? $seatingSettings->zoneIDs[$key] : $key;
				$blockId = $seatCountsBlockId.$seatsZoneID;	
				$requestId = $seatCountsBlockId.'-requested'.$seatsZoneID;	
				$selectedId = $seatCountsBlockId.'-selected'.$seatsZoneID;	
				$seatCountsBlock .= "<div id=$blockId".' style="display: none;">'.$seatZoneNames[$key]." - $textSeats:<span id=$requestId>0</span>  $textSelected:<span id=$selectedId>0</span>  </div>\n";			
			}
			$seatCountsBlock .= "</div>";

			$template .= '-->'."\n";
			$template .= '<script>';	
			$template .= "var maxRows = $maxRows;";		
			$template .= "var maxCols = $maxCols;";	
			
			if ($this->isOptionSet('Custom_BOClass'))
			{
				// Inject JS into output 
				$template .= "SeatUnknownClassText = '".$this->adminOptions['BOClass_SeatState']."unknown';\n";
				$template .= "SeatAvailableClassText = '".$this->adminOptions['BOClass_SeatState']."available';\n";
				$template .= "SeatRequestedClassText = '".$this->adminOptions['BOClass_SeatState']."requested';\n";
				$template .= "SeatReservedClassText = '".$this->adminOptions['BOClass_SeatState']."reserved';\n";	// Used for Both Booked & Reserved Seats
				$template .= "SeatAllocatedClassText = '".$this->adminOptions['BOClass_SeatState']."allocated';\n";
				$template .= "SeatBookedClassText = '".$this->adminOptions['BOClass_SeatState']."booked';\n";
				$template .= "SeatDisabledClassText = '".$this->adminOptions['BOClass_SeatState']."disabled';\n";
				
				$template .= "SeatLayoutClassText = '".$this->adminOptions['BOClass_SeatLayout']."';\n";
				
				$template .= "SeatLeftEndClass = '".$this->adminOptions['BOClass']."-leftend';\n";
				$template .= "SeatRightEndClass = '".$this->adminOptions['BOClass']."-rightend';\n";
			}
				
			$template .= "</script>\n";	
			
			$template .= $seatCountsBlock;

			$template .= '<div id="stageshow-boxoffice-seats" class="stageshow-boxoffice-layout">'."\n";	
			$template .= "<table>\n";	

			// Zero Height Cell to define width			
			$template .= '<tr>'."\n";	
			$template .= '<td class="'.$this->adminOptions['BOClass'].'-corner"></td>';	
			for ($xCell=$xCellMin; $xCell<=$xCellMax; $xCell++)	
			{
				$template .= '<td class="'.$this->adminOptions['BOClass'].'-width'.$seatSize.'"></td>';	
			}	
			$template .= '</tr>'."\n";	

			$template .= '<tr>';	
			$template .= '<td class="'.$this->adminOptions['BOClass'].'-height'.$seatSize.'"></td>';	
			$template .= '<td class="'.$this->adminOptions['BOClass'].'-header '.$layoutClass.'header" colspan='.$headerWidth.'><div>&nbsp;</div></td></tr>'."\n";	
				
			$blkType = self::PositionType_Seat;
			
			$spaceClass  = $this->adminOptions['BOClass'].'-'.self::PositionType_Space;

			for ($yCell=$yCellMin; $yCell<=$yCellMax; $yCell++)	
			{
				$template .= '<tr>'."\n";	
				$template .= '<td class="'.$this->adminOptions['BOClass'].'-height'.$seatSize.'"></td>';	
				$blkCount=0;
				
				for ($xCell=$xCellMin; $xCell<=$xCellMax+1; $xCell++)	
				{
					$thisType = $this->GetTypeAtPosition($seatSpecsList, $xCell, $yCell);
					if ( ($blkType != $thisType) 
					  || ($xCell > $xCellMax)
					  || (isset($positionAdded[$yCell][$xCell])) )
					{
						if ($blkCount > 0)
						{
							// Find number of rows with the same "seat type" (seat/space/block)
							$rowCount = 1;
							$tabParams = '';

							for ($yScan = $yCell+1; $yScan<=$yCellMax; $yScan++)
							{
								for ($xScan = $xCell-$blkCount; $xScan<$xCell; $xScan++)
								{
									$scanBlkType = $this->GetTypeAtPosition($seatSpecsList, $xScan, $yScan);
									if ($scanBlkType != $blkType)
									{
										break 2;
									}
								}
								
								for ($xScan = $xCell-$blkCount; $xScan<$xCell; $xScan++)
								{
									$positionAdded[$yScan][$xScan] = true;
								}
								$rowCount++;
							}
							if ($blkCount > 0) $tabParams .= ' colspan='.$blkCount;
							if ($rowCount > 0) $tabParams .= ' rowspan='.$rowCount;
						
							$tdId = ($blkType != self::PositionType_Space) ? ' id="'.$layoutClass.$blkType.'" ' : '';
							
							$spacesText = apply_filters('stageshow_filter_seattext', '&nbsp;', $xCell, $yCell, $blkCount, $rowCount);
							$template .= '<td'.$tdId.$tabParams.'><div class="'.$blkClass.'">'.$spacesText.'</div></td>'."\n";

							$blkCount=0;
						}

						$blkType = $thisType;
						$blkClass  = $layoutClass.$blkType;
						if ($blkType != self::PositionType_Space)
						{
							$blkClass .= ' '.$this->adminOptions['BOClass'].'-'.self::PositionType_Block;
						}
						else
						{
							$blkClass  = $spaceClass;
						}
					}
					if ($xCell>$xCellMax) break;
					
					if (isset($positionAdded[$yCell][$xCell])) continue;
					
					if (($thisType == self::PositionType_Seat) || ($thisType == self::PositionType_Id))
					{
						$seatSpec = $seatSpecsList[$yCell][$xCell];
						
						$rowNo = $seatSpec->rowNo;
						$colNo = $seatSpec->colNo;
					
						$zoneIndex = $seatSpec->zoneIndex;
						$zoneRef = isset($seatingSettings->zoneRefs[$zoneIndex]) ? $seatingSettings->zoneRefs[$zoneIndex] : $zoneIndex;
						$zoneRef = StageShowLibMigratePHPClass::Safe_str_replace(' ','', $zoneRef);
						
						$zoneClass  = $this->adminOptions['BOClass'].'-zone'.$zoneRef;
						$zoneClass .= $seatSpec->seatClass;
						
						$seatParams = '';
											
						switch ($seatSpec->id)
						{
							case 'n':
								$seatParams .= ' class="'.$this->adminOptions['BOClass'].'-id"';
								if (isset($seatDecodes->seats[$colNo]))
								{
									$seatText = ''.$seatDecodes->seats[$colNo];
								}
								else
								{
									$seatText = ''.$colNo;
								}
								break;
								
							case 'r':
								$seatParams .= ' class="'.$this->adminOptions['BOClass'].'-id"';
								if (isset($seatDecodes->rows[$rowNo]))
								{
									$seatText = ''.$seatDecodes->rows[$rowNo];
								}
								else
								{
									$seatText = ''.$rowNo;
								}
								break;
								
							default:
								$allocSeat = ($zoneAllocSeats[$zoneIndex] == 1);
								if ($allocSeat)
								{
									$clickParam  = "event, this";
									$seatParams .= " onclick=\"stageshow_ClickSeatEx(".$clickParam.")\"";	
									$zoneClass .= ' '.$this->adminOptions['BOClass_SeatState'].'unknown';												
								}
								else
								{
									$zoneClass .= ' '.$this->adminOptions['BOClass_SeatState'].'unallocated';												
								}

								$seatLocn = $seatSpec->rowNo.'_'.$seatSpec->colNo;
								$seatId = $this->adminOptions['BOClass_SeatLayout'].$seatLocn;
								$seatName = StageShowZonesDBaseClass::DecodeSeatsFromDefs($this, $seatLocn, $seatDecodes);

								$seatName = apply_filters('stageshow_filter_seattitle', $seatName, $xCell, $yCell);
						
								$seatParams .= ' id="'.$seatId.'" name="'.$seatId.'"';
								$seatParams .= ' class="'.$this->adminOptions['BOClass_Seat'].' '.$zoneClass.'"';
								$seatParams .= ' title="'.$seatName.'"';

								$seatText = '&nbsp;';
								break;
						}
						
						$seatText = apply_filters('stageshow_filter_seattext', $seatText, $xCell, $yCell, 1, 1);
						
						$spanParams = ($seatSize > 1) ? ' colspan="'.$seatSize.'" rowspan="'.$seatSize.'"' : '';
						$template .= '<td'.$spanParams.'><div'.$seatParams.">$seatText</div></td>\n";
						
						for ($y=0; $y<$seatSize; $y++)
						{
							for ($x=0; $x<$seatSize; $x++)
							{
								$positionAdded[$yCell+$y][$xCell+$x] = true;
							}							
						}
						
						if ($seatSpec->spaces > 0)
						{
							$blkCount = $seatSpec->spaces;
							$blkType = self::PositionType_Space;
							$blkClass  = $spaceClass;
						}
					}
					else
					{
						$blkCount++;
						$positionAdded[$yCell][$xCell] = true;
					}
				}
				
				$template .= "</tr>\n";			
			}

			$template .= '<tr><td class="'.$this->adminOptions['BOClass'].'-footer '.$layoutClass.'footer" colspan='.$headerWidth.'><div>&nbsp;</div></td></tr>'."\n";	

			$template .= "</table>\n";									
			$template .= "</div>\n";	
										
			$templateObj->template = $template;
			return $templateObj;
		}
		
		function GetSeatingList()
		{
			$sql  = 'SELECT seatingID, seatingRef FROM '.STAGESHOW_SEATING_TABLE;	
					
			$seatingListArray = $this->get_results($sql);
			
			return $seatingListArray;
		}

		function GetPerformanceDetails($perfID)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_PERFORMANCES_TABLE;	
			$sql .= " LEFT JOIN ".STAGESHOW_SHOWS_TABLE.' ON '.STAGESHOW_SHOWS_TABLE.'.showID='.STAGESHOW_PERFORMANCES_TABLE.'.showID';
			$sql .= " LEFT JOIN ".STAGESHOW_SEATING_TABLE.' ON '.STAGESHOW_SEATING_TABLE.'.seatingID='.STAGESHOW_PERFORMANCES_TABLE.'.perfSeatingID';
			$sql .= ' WHERE '.STAGESHOW_PERFORMANCES_TABLE.'.perfID = "'.$perfID.'"';
			
			$perfDetailsArray = $this->get_results($sql);
			
			return $perfDetailsArray[0];
		}		
		
		function DoTemplateLoop($section, $loopType, $saleRecord)	
		{				
			static $ticketsCount = 0;
			$saleConfirmation = '';
			
			switch ($loopType)
			{
				case '[ticketsloop]':
					foreach($saleRecord as $ticket)
					{
						for ($ticketNo = 1; $ticketNo<=$ticket->ticketQty; $ticketNo++)
						{
							$ticket->ticketNo = $ticketNo;
							$ticketsCount++;
							$ticket->ticketsCount = $ticketsCount;
							$saleConfirmation .= $this->AddEventToTemplate($section, $ticket);
						}
					}
					break;
					
				default:
					$saleConfirmation = parent::DoTemplateLoop($section, $loopType, $saleRecord);
					break;
				
			}
			
			return $saleConfirmation;
		}
		
		function AddSaleItemMeta($ticketID, $metaID, $metaValue)
		{
			$sql  = 'INSERT INTO '.STAGESHOW_TICKETSMETA_TABLE.'(ticketID, meta_key, meta_value) ';
			$sql .= 'VALUES("'.$ticketID.'", "'.$metaID.'", "'.esc_sql($metaValue).'")';
			$this->query($sql);	
					
     		return $this->GetInsertId();			
		}
		
		function DeleteOrders($saleID)
		{
			// Delete a sale entry
			parent::DeleteOrders($saleID);

			$this->PurgeOrphans(array(STAGESHOW_TICKETSMETA_TABLE.'.ticketmetaID', STAGESHOW_TICKETS_TABLE.'.ticketID'));
		}
		
		function GetPaymentsWithDetails($sqlFilters)
		{
			// Get the SaleId and return it ....
			$sql  = 'SELECT ';
			$sql .= STAGESHOW_PAYMENTS_TABLE.'.*, ';
			$sql .= STAGESHOW_SALES_TABLE.'.*, ';
			$sql .= STAGESHOW_PERFORMANCES_TABLE.'.*, ';
			$sql .= STAGESHOW_SHOWS_TABLE.'.* ';
			$sql .= 'FROM '.STAGESHOW_PAYMENTS_TABLE.' ';
			$sql .= 'LEFT JOIN '.STAGESHOW_SALES_TABLE.' ON '.STAGESHOW_PAYMENTS_TABLE.'.saleID = '.$this->DBTables->Sales.'.saleID ';
			$sql .= $this->GetJoinedTables($sqlFilters);
			$sql .= $this->GetWhereSQL($sqlFilters);
			$sql .= ' GROUP BY '.STAGESHOW_PAYMENTS_TABLE.'.paymentID';
			 
			$paymentEntries = $this->get_results($sql);
						 
			return $paymentEntries;
		}
		
		function GetSaleLockoutData($perfID)
		{
			$sql  = 'SELECT '.STAGESHOW_SALES_TABLE.'.saleID, COUNT(ticketQty) AS seatCount FROM '.STAGESHOW_SALES_TABLE;
			$sql .= " JOIN ".STAGESHOW_TICKETS_TABLE." ON ".STAGESHOW_TICKETS_TABLE.".saleID=".STAGESHOW_SALES_TABLE.".saleID ";
			$sql .= " JOIN ".STAGESHOW_PRICES_TABLE." ON ".STAGESHOW_PRICES_TABLE.".priceID=".STAGESHOW_TICKETS_TABLE.".priceID ";
			$sql .= ' WHERE '.STAGESHOW_PRICES_TABLE.'.perfId="'.$perfID.'"';					
			$sql .= ' AND '.STAGESHOW_PRICES_TABLE.'.priceVisibility="'.STAGESHOW_VISIBILITY_LOCKOUT.'"';					
			$sql .= ' GROUP BY '.STAGESHOW_SALES_TABLE.'.saleID';

			$salesListArray = $this->get_results($sql);
			
			return $salesListArray;
		}
		
		function GetSaleLockoutID($perfID)
		{
			$salesListArray = $this->GetSaleLockoutData($perfID);
			
			if (count($salesListArray) != 1)
				return 0;
				
			return $salesListArray[0]->saleID;
		}
		
		function GetSaleLockoutCount($perfID)
		{
			$salesListArray = $this->GetSaleLockoutData($perfID);
			
			if (count($salesListArray) != 1)
				return 0;
				
			return $salesListArray[0]->seatCount;
		}
		
		function ClearLockoutPrices($perfID)
		{
			$sql  = 'DELETE FROM '.STAGESHOW_PRICES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_PRICES_TABLE.'.perfId="'.$perfID.'"';					
			$sql .= ' AND '.STAGESHOW_PRICES_TABLE.'.priceVisibility="'.STAGESHOW_VISIBILITY_LOCKOUT.'"';					
			$rtnStatus = $this->query($sql);
			
			return $rtnStatus;		
		}
		
		function ClearSaleLockouts($perfID)
		{
			$lockoutSalesList = $this->GetSaleLockoutData($perfID);
			foreach ($lockoutSalesList as $lockoutSaleEntry)
			{
				$this->DeleteSale($lockoutSaleEntry->saleID);
			}
			
			// Delete Lockout Prices from DB
			$lockoutSaleID = $this->ClearLockoutPrices($perfID);
		}
		
		function SetSeatLockoutByPerfID($perfID, $seatsList, $zonesList)
		{
			$seatsList = StageShowLibMigratePHPClass::Safe_trim($seatsList);
			$zonesList = StageShowLibMigratePHPClass::Safe_trim($zonesList);
			
			// Remove existing lockout sale(s) for this performance
			$this->ClearSaleLockouts($perfID);
			
			if ($seatsList == '') return;
			
			$saleDateTime = current_time('mysql'); 

			$saleVals['saleDateTime'] = $saleDateTime;
			$saleVals['saleStatus'] = PAYMENT_API_SALESTATUS_LOCKOUT;
			$saleVals['salePaid'] = 0.0;

			// Add a new sale for the locked out seats					
			$saleID = $this->AddSale($saleDateTime, $saleVals);
			
			$seatsArray = preg_split("/\s+/", StageShowLibMigratePHPClass::Safe_trim($seatsList));
			$zonesArray = preg_split("/\s+/", StageShowLibMigratePHPClass::Safe_trim($zonesList));
			
			// Create Lockout "Price" for each zone
			$priceIDs = array();
			foreach ($zonesArray as $index => $zone)
			{
				if (isset($priceIDs[$zone])) continue;				
				$priceParams = new stdClass;
				$priceParams->priceType = STAGESHOW_TYPE_SYSTEM;
				$priceParams->priceValue = 0;
				$priceParams->priceVisibility = STAGESHOW_VISIBILITY_LOCKOUT;
			
				$priceIDs[$zone] = $this->AddPrice($perfID."-$zone", $priceParams, true);
			}
			
			// Add a "Ticket" for each seat
			foreach ($seatsArray as $index => $seat)
			{
				$zone = $zonesArray[$index];
				$saleExtras['ticketSeat'] = $seat;
				$priceID = $priceIDs[$zone];
				$this->AddSaleItem($saleID, $priceID, 1, 0, $saleExtras);				
			}
		}
						
		function GetDiscountsList($disID = '')
		{
			$selectFields = '*';
			
			$sql = "SELECT $selectFields FROM ".STAGESHOW_DISCODES_TABLE;
			
			if ($disID != '')
			{
				$sql .= ' WHERE '.STAGESHOW_DISCODES_TABLE.'.disID="'.$disID.'"';					
			}
			
			$sql .= ' ORDER BY '.STAGESHOW_DISCODES_TABLE.'.disRef';
			
			$results = $this->get_results($sql);

			return $results;			
		}

		function AddDiscount($disRef = '', $disState = STAGESHOW_STATE_ACTIVE)
		{
			// FUNCTIONALITY: Discounts - Add Discount
	      	if ($disRef === '')
	      	{
				$newNameNo = 1;
				while (true)
				{
					$disRef = __('Discount', 'stageshow').' '.$newNameNo;
						
					if ($this->IsDiscountNameUnique($disRef))
						break;
					$newNameNo++;
				}
			}
			else
			{
				if (!$this->IsDiscountNameUnique($disRef))
					return 0;	// Error - Discount Name is not unique
			}
						
			$sql = 'INSERT INTO '.STAGESHOW_DISCODES_TABLE.'(disRef, disState) VALUES("'.esc_sql($disRef).'", "'.$disState.'")';
			$this->query($sql);	
					
     		return $this->GetInsertId();
		}
			
		function IsDiscountNameUnique($disRef)
		{
			$sql  = 'SELECT * FROM '.STAGESHOW_DISCODES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_DISCODES_TABLE.'.disRef="'.esc_sql($disRef).'"';
			
			$discountsEntries = $this->get_results($sql);
			return (count($discountsEntries) > 0) ? false : true;
		}	
		
		function SetDiscountActivated($disID, $disState = STAGESHOW_STATE_ACTIVE)
		{
			$sql  = 'UPDATE '.STAGESHOW_DISCODES_TABLE;
			$sql .= ' SET disState="'.$disState.'"';
			$sql .= ' WHERE '.STAGESHOW_DISCODES_TABLE.'.disID='.$disID;;

			$this->query($sql);	
			return "OK";		
		}
		
		function UpdateDiscountRef($disID, $newDiscountRef)
		{
			$newDiscountRef = StageShowLibMigratePHPClass::Safe_trim($newDiscountRef);
				
			$sqlSET = 'disRef="'.esc_sql($newDiscountRef).'"';
			return $this->UpdateDiscountEntry($disID, $sqlSET);
		}
				
		function UpdateDiscountCodes($disID, $newDiscountCodes)
		{
			$sqlSET = 'disCodes="'.esc_sql($newDiscountCodes).'"';
			return $this->UpdateDiscountEntry($disID, $sqlSET);
		}								
				
		function UpdateDiscountEntry($disID, $sqlSET)
		{
			$sql  = 'UPDATE '.STAGESHOW_DISCODES_TABLE;
			$sql .= ' SET '.$sqlSET;
			$sql .= ' WHERE '.STAGESHOW_DISCODES_TABLE.".disID=$disID";

			$this->query($sql);	
			return "OK";							
		}
		
		function DeleteDiscount($disID)
		{
			// Delete a preset entry
			$sql  = 'DELETE FROM '.STAGESHOW_DISCODES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_DISCODES_TABLE.".disID=$disID";
			$rtnStatus = $this->query($sql);
			
			// Delete any associated prices
			$this->DeletePresetDiscount($disID);
			$this->DeleteDiscountPrice($disID);
			
			return $rtnStatus;		
		}
		
		function GetPresetDiscount($presetID, $disID)
		{
			$priceValue = '';
			
			$sql  = 'SELECT * FROM '.STAGESHOW_DISPRESET_TABLE;
			$sql .= ' WHERE '.STAGESHOW_DISPRESET_TABLE.'.presetID="'.$presetID.'"';
			$sql .= ' AND '.STAGESHOW_DISPRESET_TABLE.'.disID="'.$disID.'"';
			
			$results = $this->get_results($sql);
			if (count($results)>0)
			{
				$priceValue = $results[0]->disValue;
			}
			
			return $priceValue;
		}
				
		function SavePresetDiscount($disID, $presetID, $disValue)
		{
			// Get Current Entry ... if it exists
			$priceValue = $this->GetPresetDiscount($presetID, $disID);
			if ($priceValue != '')
			{
				$sql  = 'UPDATE '.STAGESHOW_DISPRESET_TABLE;
				$sql .= ' SET disValue="'.$disValue.'"';
				$sql .= ' WHERE '.STAGESHOW_DISPRESET_TABLE.'.presetID="'.$presetID.'"';
				$sql .= ' AND '.STAGESHOW_DISPRESET_TABLE.'.disID="'.$disID.'"';
				$this->query($sql);	
			}
			
			$sql  = 'INSERT INTO '.STAGESHOW_DISPRESET_TABLE;
			$sql .= '(presetID, disID, disValue) ';
			$sql .= 'VALUES("'.$presetID.'", "'.$disID.'", "'.$disValue.'")';
			$this->query($sql);	
			
			//return $this->GetInsertId();
		}
				
		function DeletePresetDiscount($disID, $presetID = '')
		{
			$sql  = 'DELETE FROM '.STAGESHOW_DISPRESET_TABLE;
			$sql .= ' WHERE '.STAGESHOW_DISPRESET_TABLE.'.disID="'.$disID.'"';
			if ($presetID != '')
			{
				$sql .= ' AND '.STAGESHOW_DISPRESET_TABLE.'.presetID="'.$presetID.'"';
			}
			 
			$this->query($sql);
		}
				
		function GetDiscountPrice($priceID, $disID)
		{
			$priceValue = '';
			
			$sql  = 'SELECT * FROM '.STAGESHOW_DISPRICES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_DISPRICES_TABLE.'.priceID="'.$priceID.'"';
			$sql .= ' AND '.STAGESHOW_DISPRICES_TABLE.'.disID="'.$disID.'"';
			
			$results = $this->get_results($sql);
			if (count($results)>0)
			{
				$priceValue = $results[0]->disValue;
			}
			
			return $priceValue;
		}
				
		function SaveDiscountPrice($disID, $priceID, $disValue)
		{
			// Get Current Entry ... if it exists
			$priceValue = $this->GetDiscountPrice($priceID, $disID);
			if ($priceValue != '')
			{
				$sql  = 'UPDATE '.STAGESHOW_DISPRICES_TABLE;
				$sql .= ' SET disValue="'.$disValue.'"';
				$sql .= ' WHERE '.STAGESHOW_DISPRICES_TABLE.'.priceID="'.$priceID.'"';
				$sql .= ' AND '.STAGESHOW_DISPRICES_TABLE.'.disID="'.$disID.'"';
				$this->query($sql);	
				
				return $this->GetInsertId();	// TODO - Discounts - Check if this is OK
			}
			
			$sql  = 'INSERT INTO '.STAGESHOW_DISPRICES_TABLE;
			$sql .= '(priceID, disID, disValue) ';
			$sql .= 'VALUES("'.$priceID.'", "'.$disID.'", "'.$disValue.'")';
			$this->query($sql);	
			
			return $this->GetInsertId();
		}
				
		function DeleteDiscountPrice($disID, $priceID = '')
		{
			$sql  = 'DELETE FROM '.STAGESHOW_DISPRICES_TABLE;
			$sql .= ' WHERE '.STAGESHOW_DISPRICES_TABLE.'.disID="'.$disID.'"';
			if ($priceID != '')
			{
				$sql .= ' AND '.STAGESHOW_DISPRICES_TABLE.'.priceID="'.$priceID.'"';
			}
			 
			$this->query($sql);
		}
				
		function GetBoxOfficeClasses()
		{
			$this->adminOptions['Custom_BOClass'] = false;
			
			$this->adminOptions['BOClass'] = 'stageshow-boxoffice';
			
			if (defined('STAGESHOW_CLASS_BOXOFFICE'))
			{
				if ($this->adminOptions['BOClass'] != STAGESHOW_CLASS_BOXOFFICE)
				{
					$this->adminOptions['BOClass'] = STAGESHOW_CLASS_BOXOFFICE;
					$this->adminOptions['Custom_BOClass'] = true;
				}
			}
			
			$this->adminOptions['BOClass_Space'] = $this->adminOptions['BOClass'].'-space';
			$this->adminOptions['BOClass_Seat'] = $this->adminOptions['BOClass'].'-seat';
			$this->adminOptions['BOClass_SeatState'] = $this->adminOptions['BOClass'].'-seat-';
			$this->adminOptions['BOClass_Layout'] = $this->adminOptions['BOClass'].'-layout-';
			$this->adminOptions['BOClass_SeatLayout'] = $this->adminOptions['BOClass'].'-layout-seat-';
			
			if (defined('STAGESHOW_CLASS_BOXOFFICE_SPACETAG'))
			{
				if ($this->adminOptions['BOClass_Space'] != STAGESHOW_CLASS_BOXOFFICE_SPACETAG)
				{
					$this->adminOptions['BOClass_Space'] = STAGESHOW_CLASS_BOXOFFICE_SPACETAG;
					$this->adminOptions['Custom_BOClass'] = true;
				}
			}
			
			if (defined('STAGESHOW_CLASS_BOXOFFICE_SEATTAG'))
			{
				if ($this->adminOptions['BOClass_Seat'] != STAGESHOW_CLASS_BOXOFFICE_SEATTAG)
				{
					$this->adminOptions['BOClass_Seat'] = STAGESHOW_CLASS_BOXOFFICE_SEATTAG;
					$this->adminOptions['Custom_BOClass'] = true;
				}
			}
			
			if (defined('STAGESHOW_CLASS_BOXOFFICE_SEATSTATE'))
			{
				if ($this->adminOptions['BOClass_SeatState'] != STAGESHOW_CLASS_BOXOFFICE_SEATSTATE)
				{
					$this->adminOptions['BOClass_SeatState'] = STAGESHOW_CLASS_BOXOFFICE_SEATSTATE;
					$this->adminOptions['Custom_BOClass'] = true;
				}
			}
			
			if (defined('STAGESHOW_CLASS_BOXOFFICE_LAYOUT'))
			{
				if ($this->adminOptions['BOClass_Layout'] != STAGESHOW_CLASS_BOXOFFICE_LAYOUT)
				{
					$this->adminOptions['BOClass_Layout'] = STAGESHOW_CLASS_BOXOFFICE_LAYOUT;
					$this->adminOptions['Custom_BOClass'] = true;
				}
			}
			
			if (defined('STAGESHOW_CLASS_BOXOFFICE_LAYOUT_SEAT'))
			{
				if ($this->adminOptions['BOClass_SeatLayout'] != $this->adminOptions['BOClass_Layout'].STAGESHOW_CLASS_BOXOFFICE_LAYOUT_SEAT)
				{
					$this->adminOptions['BOClass_SeatLayout'] = $this->adminOptions['BOClass_Layout'].STAGESHOW_CLASS_BOXOFFICE_LAYOUT_SEAT;
					$this->adminOptions['Custom_BOClass'] = true;
				}
			}
			
      		$this->saveOptions();  

			if ($this->isOptionSet('Custom_BOClass'))
			{
				$this->ApplyBOClasses('css', 'stageshow-seats.css');
			}			
		}
			
		function ApplyBOClasses($folder, $file)
		{
			// Convert boxoffice classes in CSS or JS files
			$filePath = STAGESHOW_FILE_PATH.$folder.'/'.$file;			
			$fileData = file_get_contents($filePath);

			$fileData = StageShowLibMigratePHPClass::Safe_str_replace('stageshow-boxoffice-space',        $this->adminOptions['BOClass_Space'], $fileData);
			$fileData = StageShowLibMigratePHPClass::Safe_str_replace('stageshow-boxoffice-seat-',        $this->adminOptions['BOClass_SeatState'], $fileData);
			$fileData = StageShowLibMigratePHPClass::Safe_str_replace('stageshow-boxoffice-layout-seat-', $this->adminOptions['BOClass_SeatLayout'], $fileData);

			$fileData = StageShowLibMigratePHPClass::Safe_str_replace('stageshow-boxoffice-seat',         $this->adminOptions['BOClass_Seat'], $fileData);
			//preg_replace('/stageshow\-boxoffice/',       $this->adminOptions['BOClass'], $fileData);

			$filePath = STAGESHOW_FILE_PATH.$folder.'/'.StageShowLibMigratePHPClass::Safe_str_replace('.', '-custom.', $file);
			file_put_contents($filePath, $fileData);
		}

		static function DecodeHex($src)
		{
			$hex = StageShowLibMigratePHPClass::Safe_trim($src);
			$hex = StageShowLibMigratePHPClass::Safe_strtoupper($hex);
			$hex = StageShowLibMigratePHPClass::Safe_str_replace(" ", "", $hex);
			
			$hexLen = StageShowLibMigratePHPClass::Safe_strlen($hex);
			if ($hexLen == 0) return '';
			if (($hexLen % 2) != 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Error converting hex string: $src - Must be even number of characters<br>\n");
				return '';
			}
			
			$hexText = hex2bin($hex);
			if (StageShowLibMigratePHPClass::Safe_strlen($hexText) != ($hexLen/2))
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Error converting hex string: $src<br>\n");
				return '';
			}
			
			return $hexText;
		}
				
		static function ParsePrinterDef($newPrinterType)
		{
			static $lastPrinterType = '';
			static $printerDefs = array();
			
			if ($lastPrinterType != $newPrinterType)
			{
				$printerDefs = array();

				$xmlPath = TICKETPRINT_PRINTERSFOLDER.'/'.$newPrinterType;
				$xml = simplexml_load_file($xmlPath);

				$first = true;
				foreach ($xml->Codes as $modeCfg)
				{
					$modeSpec = new stdClass();
					$modeSpec->Mode = (string)$modeCfg->Mode;
					$modeSpec->TicketTerminatorHex = (string)$modeCfg->TicketTerminator;
					$modeSpec->GroupTerminatorHex = (string)$modeCfg->GroupTerminator;
					$modeSpec->TicketTerminatorBin = self::DecodeHex($modeSpec->TicketTerminatorHex);
					$modeSpec->GroupTerminatorBin = self::DecodeHex($modeSpec->GroupTerminatorHex);
					
					$printerDefs[] = $modeSpec;
				}
				
				if (count($printerDefs) == 0)
				{
					$modeSpec = new stdClass();
					$modeSpec->Mode = 'Default';
					$modeSpec->TicketTerminatorHex = '';
					$modeSpec->GroupTerminatorHex = '';
					$modeSpec->TicketTerminatorBin = self::DecodeHex($modeSpec->TicketTerminatorHex);
					$modeSpec->GroupTerminatorBin = self::DecodeHex($modeSpec->GroupTerminatorHex);
					
					$printerDefs[] = $modeSpec;
				}
				
				$lastPrinterType = $newPrinterType;
			}
			
			return $printerDefs;
		}
		
		function IsPrintingEnabled()
		{
			if (!$this->IsPrintingActive())
				return false;
				
			return ($this->getOption('TicketTemplatePath') != '');
		}
		
		function IsPrintingConfigured()
		{
			$printingConfigured = true;
			$printingConfigured &= $this->isOptionSet('TicketTemplatePath');
			$printingConfigured &= $this->isOptionSet('AuthTxnId');
			$printingConfigured &= $this->isOptionSet('PrinterDefPath');
			$printingConfigured &= $this->isOptionSet('PrinterIPAddress');
			 
			return $printingConfigured;
		}
		
		function IsPrintingActive()
		{
			return ($this->getOption('EnablePrinting') != '');
		}
		
		function PrintSale($saleId)
		{
			$sql  = 'UPDATE '.STAGESHOW_TICKETS_TABLE;
			$sql .= ' SET ticketsPrinted = 0';
			$sql .= ' WHERE '.STAGESHOW_TICKETS_TABLE.".saleId=$saleId";
			$this->query($sql);	
			
			$sql  = 'UPDATE '.STAGESHOW_SALES_TABLE;
			$sql .= ' SET salePrinted = 0';
			$sql .= ' WHERE '.STAGESHOW_SALES_TABLE.".saleId=$saleId";
			$this->query($sql);
			
			return $this->SpoolTickets($saleId);
		}
		
		function SpoolTickets($saleId, $template='', $printerDefPath = '', $flushSpooler = false, $ticketNumber='All')
		{
			include "stageshowlib_escpos_png.php";
			
			$logTimeActive = isset($this->timeLogObj);
			
			// Get sale from database		
			$results = $this->GetSale($saleId);
			if (count($results) == 0)
			{
				return 0;
			}
			
			if ($flushSpooler)
			{
				// Purge the print spooler ... test only?
				$this->DeleteAlPrintEntries();				
			}
			
			if ($printerDefPath == '') $printerDefPath = $this->adminOptions['PrinterDefPath'];
			$printerDefs = $this->ParsePrinterDef($printerDefPath);
			
			$escPosObj = new StageShowLibEscPosPNGClass($this);
			
			$file_ts = date('-Ymd-His');
			if ($logTimeActive) $this->timeLogObj->LogTimestamp();			
			$ticketCount = 0;
			$printedCount = 0;
			foreach ($results as $saleRecord)
			{				
				$ticketCount++;
				if (($ticketNumber != 'All') && ($ticketNumber != $ticketCount)) continue;
				
				$saleRecord->ticketNo = $ticketCount;
				
				$seats = explode(',', StageShowLibMigratePHPClass::Safe_trim($saleRecord->ticketSeat));
				$numOfSeats = $saleRecord->priceNoOfSeats * $saleRecord->ticketQty;
				for ($seatNo=0; $seatNo<$numOfSeats; $seatNo++)
				{
					$saleRecord->ticketSeat = (count($seats) > $seatNo) ? $seats[$seatNo] : '';
						
					$escPosObj->GetTicketImage($saleRecord, $template, true);
					if ($logTimeActive) $this->timeLogObj->LogTimestamp('GetTicketImage');
					
					$content = $escPosObj->escPosData;
					if (($ticketCount == count($results) && ($seatNo == $numOfSeats-1)) || ($ticketCount == $ticketNumber))
					{
						$content .= $printerDefs[0]->GroupTerminatorBin;

					}
					else
					{
						$content .= $printerDefs[0]->TicketTerminatorBin;
					}
					
					if ($logTimeActive) $this->timeLogObj->LogTimestamp('EncodeBitmap');
					$base64_content = base64_encode($content);
					if ($logTimeActive) $this->timeLogObj->LogTimestamp('base64_encode');
					$escPBase64 = chunk_split($base64_content);
					if ($logTimeActive) $this->timeLogObj->LogTimestamp('chunk_split');
					$this->SavePrintContent($saleRecord->ticketID, $seatNo, $escPBase64);	
					if ($logTimeActive) $this->timeLogObj->LogTimestamp('SavePrintContent');
					$printedCount++;
				
					if (StageShowLibUtilsClass::IsElementSet('post', 'showEscPosOutput'))
					{
						StageShowLibEscPosClass::ShowEscPosImage($escPosObj->escPosData);
					}
				}
			}
		
			return $printedCount;
		}
		
		function GetPrintQueue($printServerIPAddr = '')
		{

			$sql  = 'SELECT printID FROM '.STAGESHOW_SPOOLER_TABLE;
			if ($printServerIPAddr != '')
			{
				$sql .= ' WHERE printReqIPAddr="'.$printServerIPAddr.'"';				
			}
			
			$results = $this->get_results($sql);
			$rsltList = count($results);
			foreach ($results as $result)
			{
				$rsltList .= ','.$result->printID;
			}
			
			return $rsltList;
		}
		
		function GetPrintContent($printID = '', $printServerIPAddr = '')
		{
			$priceValue = '';
			
			$sql  = 'SELECT * FROM '.STAGESHOW_SPOOLER_TABLE;
			if ($printID != '')
			{
				$sql .= ' WHERE printID="'.$printID.'"';
				if ($printServerIPAddr != '')
				{
					$sql .= ' AND printReqIPAddr="'.$printServerIPAddr.'"';
				}
			}
			else
			{
				$sql .= ' LIMIT 1';			
			}
			
			$results = $this->get_results($sql);
			
			return $results;
		}
				
		function HasPrintContent($printServerIPAddr)
		{
			$queue = $this->GetPrintQueue($printServerIPAddr);
			return ($queue != '');
		}
				
		function SavePrintContent($ticketID, $seatNo, $content)
		{
			// Get Current Entry ... if it exists
			$printDateTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT);
			$ip = $this->GetIPAddr();	// Could use INT DB entry and ip2long/log2ip functions
			
			$sql  = 'INSERT INTO '.STAGESHOW_SPOOLER_TABLE;
			$sql .= '(ticketID, seatNo, printDateTime, printContent) ';
			$sql .= 'VALUES("'.$ticketID.'", "'.$seatNo.'", "'.$printDateTime.'", "'.esc_sql($content).'")';
			$this->query($sql);	
			
			return $this->GetInsertId();
		}		
		
		function PrintComplete($printID)
		{
			$sql  = 'SELECT saleID, '.STAGESHOW_TICKETS_TABLE.'.ticketID, seatNo FROM '.STAGESHOW_SPOOLER_TABLE;
			$sql .= ' JOIN '.STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_SPOOLER_TABLE.'.ticketID='.STAGESHOW_TICKETS_TABLE.'.ticketID';
			$sql .= ' WHERE printID="'.$printID.'"';
			$results = $this->get_results($sql);
			if (count($results) == 0) return false;
			
			$this->DeletePrintEntry($printID);
			
			$saleID = $results[0]->saleID;
			$ticketID = $results[0]->ticketID;
			
			$sql  = 'UPDATE '.STAGESHOW_TICKETS_TABLE;
			$sql .= ' SET ticketsPrinted = ticketsPrinted+1';
			$sql .= ' WHERE '.STAGESHOW_TICKETS_TABLE.".ticketID=$ticketID";
			$this->query($sql);	
			
			$sql  = 'SELECT '.STAGESHOW_SALES_TABLE.'.saleID';
			$sql .= ', SUM(ticketQty*priceNoOfSeats) AS totalSeats';
			$sql .= ', SUM(ticketsPrinted) AS totalPrinted';
			$sql .= ' FROM '.STAGESHOW_SALES_TABLE;
			$sql .= ' JOIN '.STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.saleID='.STAGESHOW_SALES_TABLE.'.saleID';
			$sql .= ' JOIN '.STAGESHOW_PRICES_TABLE.' ON '.STAGESHOW_PRICES_TABLE.'.priceID='.STAGESHOW_TICKETS_TABLE.'.priceID';		
			$sql .= ' WHERE '.STAGESHOW_SALES_TABLE.".saleID=$saleID";
			$sql .= ' GROUP BY '.STAGESHOW_SALES_TABLE.'.saleID';			
			$results = $this->get_results($sql);
			if (count($results) == 0) return false;
					
			if ($results[0]->totalPrinted >= $results[0]->totalSeats)		
			{
				$printDateTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT);
				
				$sql  = 'UPDATE '.STAGESHOW_SALES_TABLE;
				$sql .= ' SET salePrinted="'.$printDateTime.'"';
				$sql .= ' WHERE '.STAGESHOW_SALES_TABLE.".saleID=$saleID";

				$this->query($sql);					
			}
		
			return true;							
		}
		
		function DeletePrintEntry($printID)
		{
			$sql  = 'DELETE FROM '.STAGESHOW_SPOOLER_TABLE;
			$sql .= ' WHERE '.STAGESHOW_SPOOLER_TABLE.".printID=$printID";
					
			$this->query($sql);
		}
		
		function DeleteAlPrintEntries()
		{
			$sql  = 'DELETE FROM '.STAGESHOW_SPOOLER_TABLE;
					
			$this->query($sql);
		}
		
		function GetUnprintedSales()
		{
			//$sql  = 'SELECT '.STAGESHOW_SALES_TABLE.'.saleID, ticketID';
			$sql  = 'SELECT  *';
			$sql .= ' FROM '.STAGESHOW_SALES_TABLE;
			//$sql .= ' JOIN '.STAGESHOW_TICKETS_TABLE.' ON '.STAGESHOW_TICKETS_TABLE.'.saleID='.STAGESHOW_SALES_TABLE.'.saleID';
			$sql .= ' WHERE '.STAGESHOW_SALES_TABLE.".salePrinted IS NULL";
			
			$results = $this->get_results($sql);
			return $results;						
		}

	}
}






























