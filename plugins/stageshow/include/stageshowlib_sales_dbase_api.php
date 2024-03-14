<?php
/* 
Description: Core Library Database Access functions
 
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

if (!defined('STAGESHOWLIB_DBASE_CLASS'))
	define('STAGESHOWLIB_DBASE_CLASS', 'StageShowLibSalesDBaseClass');
	
if (!defined('STAGESHOWLIB_DATABASE_FULL')) define('STAGESHOWLIB_DATABASE_FULL', true);

if (!class_exists('StageShowLibSalesCartDBaseClass')) 
	include STAGESHOWLIB_INCLUDE_PATH.'stageshowlib_sales_trolley_dbase_api.php';

if (!class_exists('StageShowLibSalesDBaseClass')) 
{
	/*
	---------------------------------------------------------------------------------
		StageShowLibSalesDBaseClass
	---------------------------------------------------------------------------------
	
	This class provides database functionality to capture sales data and support
	Payment Notification
	*/
	
	if (!defined('PAYPAL_APILIB_DEFAULT_LOGOIMAGE_FILE'))
		define('PAYPAL_APILIB_DEFAULT_LOGOIMAGE_FILE', '');
	if (!defined('PAYPAL_APILIB_DEFAULT_HEADERIMAGE_FILE'))
		define('PAYPAL_APILIB_DEFAULT_HEADERIMAGE_FILE', '');
		
	if (!defined('STAGESHOWLIB_SALE_TIMEOUT_EMAIL_TEMPLATE_PATH'))
		define('STAGESHOWLIB_SALE_TIMEOUT_EMAIL_TEMPLATE_PATH', 'stageshowlib_SaleTimeoutEMail.sys');

	if (!defined('STAGESHOWLIB_SALE_REJECTED_EMAIL_TEMPLATE_PATH'))
		define('STAGESHOWLIB_SALE_REJECTED_EMAIL_TEMPLATE_PATH', 'stageshowlib_SaleRejectedEMail.sys');
		
	if (!defined('STAGESHOWLIB_FILENAME_HTTPIOLOG'))
		define('STAGESHOWLIB_FILENAME_HTTPIOLOG', 'HTTPLog.txt');
						
	define('STAGESHOWLIB_GATEWAYAPI_ABANDON_TIMEOUT_DEFAULT', 259200);	// 3 days in seconds
		
	class StageShowLibSalesDBaseClass extends StageShowLibSalesCartDBaseClass // Define class 
  	{	
		const CONSENT_ALLOWED = 'Allowed';
		const CONSENT_DENIED = 'Denied';
		
		function __construct($opts)		//constructor		
		{
			parent::__construct($opts);
		}

    	function GetDefaultGateway()
    	{    		
    		return 'paypal';
		}
		
		function GetJSONEncodedPostVars()
		{
			if (!StageShowLibUtilsClass::IsElementSet('post', 'stageshowlib_PostVars')) return;
			
			$Postvars = array();
			
			// Unserialise serialised POST data
			StageShowLibUtilsClass::GetHTTPJSONEncodedElem('post', 'stageshowlib_PostVars');
			
			
			unset($_POST['stageshowlib_PostVars']);
		}
		
		function SplitSaleNameField()
		{
			if (!$this->IfColumnExists($this->DBTables->Sales, 'saleName'))
				return false;
				
			// Split saleName field into two parts 			
			$sql  = 'UPDATE '.$this->DBTables->Sales.' SET ';
			$sql .= 'saleName = CONCAT(" ", REPLACE(saleName, ".", " "))';
			$this->query($sql);	

			$sql  = 'UPDATE '.$this->DBTables->Sales.' SET ';
			$sql .= 'saleFirstName = TRIM(SUBSTR(saleName, 1, LENGTH(saleName) - LOCATE(" ", REVERSE(saleName))))';
			$this->query($sql);	

			$sql  = 'UPDATE '.$this->DBTables->Sales.' SET ';
			$sql .= 'saleLastName = TRIM(SUBSTR(saleName, 1 + (LENGTH(saleName) - LOCATE(" ", REVERSE(saleName)))))';
			$this->query($sql);	

			$this->deleteColumn($this->DBTables->Sales, 'saleName');
					
			return true;
		}

		function NormaliseSettings($settings)
		{
			$settings = parent::NormaliseSettings($settings);
			
			return $settings;	
		}
		
	    function upgradeDB()
	    {
			$pluginID = basename(dirname(dirname(__FILE__)));	// Library files should be in 'include' folder			
			$salesDefaultTemplatesPath = WP_CONTENT_DIR . '/plugins/' . $pluginID . '/templates';
			$salesTemplatesPath = WP_CONTENT_DIR . '/uploads/'.$pluginID;
			
			// FUNCTIONALITY: DBase - On upgrade ... Copy sales templates to working folder
			// Copy release templates to plugin persistent templates and images folders
			if (!StageShowLibUtilsClass::recurse_copy($salesDefaultTemplatesPath, $salesTemplatesPath))
			{
				StageShowLibEscapingClass::Safe_EchoHTML(__("Error copying default templates", 'stageshow')."<br>\n");
				StageShowLibEscapingClass::Safe_EchoHTML("Src:  $salesDefaultTemplatesPath <br>\n");
				StageShowLibEscapingClass::Safe_EchoHTML("Dest: $salesTemplatesPath <br>\n");
			}
			
			if ($this->GatewayID != '')
			{
				if (!isset($this->adminOptions['CheckoutTimeout']) || ($this->adminOptions['CheckoutTimeout'] == ''))
				{
					$this->adminOptions['CheckoutTimeout'] = PAYMENT_API_CHECKOUT_TIMEOUT_DEFAULT;
				}
				
				if (!isset($this->adminOptions['PayPalCheckoutType']))
				{
					$this->adminOptions['PayPalCheckoutType'] = StageShowLibGatewayBaseClass::STAGESHOWLIB_CHECKOUTSTYLE_STANDARD;
				}				
			}
			
      		$this->saveOptions();      
			
			// FUNCTIONALITY: DBase - On upgrade ... Add any database fields
			// Add DB Tables
			$this->createDB();
			
			if ($this->GatewayID != '')
			{
				// Remove the saleName field - Move data first
				$this->SplitSaleNameField();
			}
		}
		
		function PurgeDB($alwaysRun = false)
		{
		}
		
		function uninstall()
		{
			$this->DropTable($this->DBTables->Sales);
			$this->DropTable($this->DBTables->Payments);
			
			$pluginID = basename(dirname(dirname(__FILE__)));	// Library files should be in 'include' folder			
			$salesTemplatesPath = WP_CONTENT_DIR . '/uploads/'.$pluginID;
			
			// Remove templates and images folders in Uploads folder
			if (is_dir($salesTemplatesPath))
				StageShowLibUtilsClass::deleteDir($salesTemplatesPath);
			
			parent::uninstall();
		}
				
		function CheckIsConfigured()
		{
			$isConfigured = $this->SettingsConfigured();
				
			if (!$isConfigured)
			{
				$gatewayName = $this->gatewayObj->GetName();

				$settingsPageId = 'stageshow'."_settings";
				
				$settingsPageURL = get_option('siteurl').'/wp-admin/admin.php?page='.$settingsPageId;
				$settingsPageURL .= '&tab=gateway-settings';
				$actionMsg = __('Set', 'stageshow').' '.$gatewayName.' '.__('Payment Gateway Settings are not set', 'stageshow').' - <a href='.$settingsPageURL.'>'.__('Do it Here', 'stageshow').'</a>';
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.$actionMsg.'</p></div>');				
			}
			
			if ( !get_option('permalink_structure') )
			{
				$isConfigured = false;

				$permalinkAdminPageURL = get_option('siteurl').'/wp-admin/options-permalink.php';
				$actionMsg = __('Plain Permalinks not permitted', 'stageshow').' - <a href='.$permalinkAdminPageURL.'>'.__('Set them Here', 'stageshow').'</a>';
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.$actionMsg.'</p></div>');								
			}
			
			return $isConfigured;
		}
				
		
		
		//Returns an array of admin options
		function getOptions($childOptions = array()) 
		{
			$ourOptions = array(
				'CheckoutCompleteURL' => '',        
				'CheckoutCancelledURL' => '',
				          
				'PayPalLogoImageFile' => PAYPAL_APILIB_DEFAULT_LOGOIMAGE_FILE,
				'PayPalHeaderImageFile' => PAYPAL_APILIB_DEFAULT_HEADERIMAGE_FILE,
				'PayPalHeaderImageMode' => PAYMENT_API_IMAGES_LOCAL_HTTP,
				        
				'CurrencySymbol' => '',

				'SalesID' => '',        
				'SalesEMail' => '',
				  
				'CheckoutTimeout' => PAYMENT_API_CHECKOUT_TIMEOUT_DEFAULT,
				
				'TimeoutEMailTemplatePath' => STAGESHOWLIB_SALE_TIMEOUT_EMAIL_TEMPLATE_PATH,
				'RejectedEMailTemplatePath' => STAGESHOWLIB_SALE_REJECTED_EMAIL_TEMPLATE_PATH,
								
				'Unused_EndOfList' => ''
			);
			
			if ($this->GatewayID != '')
				$ourOptions = array_merge($this->gatewayObj->Gateway_GetOptions(), $ourOptions);
			
			$ourOptions = array_merge($ourOptions, $childOptions);
			
			$currOptions = parent::getOptions($ourOptions);
			
			$saveToDB = false;
			
			// PayPalLogoImageURL option has been changed to PayPalLogoImageFile
			if (isset($currOptions['PayPalLogoImageURL']))
			{
				$currOptions['PayPalLogoImageFile'] = basename($currOptions['PayPalLogoImageURL']);
				unset($currOptions['PayPalLogoImageURL']);
				$saveToDB = true;
			}
				
			// PayPalHeaderImageURL option has been changed to PayPalHeaderImageFile
			if (isset($currOptions['PayPalHeaderImageURL']))
			{
				$currOptions['PayPalHeaderImageFile'] = basename($currOptions['PayPalHeaderImageURL']);
				unset($currOptions['PayPalHeaderImageURL']);
				$saveToDB = true;
			}
			
			// Added PayPalImagesMode option 
			if (!isset($currOptions['PayPalHeaderImageMode']))
			{
				$currOptions['PayPalHeaderImageMode'] = PAYMENT_API_IMAGES_LOCAL_HTTP;					
				$saveToDB = true;
			}
			
			// PayPalImagesUseSSL option removed
			if (isset($currOptions['PayPalImagesUseSSL']))
			{
				if (($currOptions['PayPalImagesUseSSL'])
				  && isset($currOptions['PayPalHeaderImageMode'])
				  && ($currOptions['PayPalHeaderImageMode'] == PAYMENT_API_IMAGES_LOCAL_HTTP))
				{
					$currOptions['PayPalHeaderImageMode'] = PAYMENT_API_IMAGES_LOCAL_HTTPS;					
				}
				
				unset($currOptions['PayPalImagesUseSSL']);
				$saveToDB = true;
			}
			
			$this->adminOptions = $currOptions;

			if ($saveToDB)
				$this->saveOptions();
				
			return $currOptions;
		}
		
		function Output_PluginHelp($exHelpHTML = '')
		{
			$exHelpHTML = '<strong>'.__('Gateway', 'stageshow').':</strong> '.$this->GatewayName."<br>\n";
			
			parent::Output_PluginHelp($exHelpHTML);			
		}
		
		function SetTestSettings($testSettings)
		{
			if (!isset($this->adminOptions))
			{
				$this->getOptions();
			}
			
			foreach($testSettings as $settingID => $settingValue)
			{
				if (StageShowLibMigratePHPClass::Safe_substr($settingID, 0, 4) === 'Dev_')
					$this->dbgOptions[$settingID] = $settingValue;
				else
					$this->adminOptions[$settingID] = $settingValue;
			}
			
			$this->saveOptions();			
		}
		
		// Saves the admin options to the options data table
		
		
		function CheckVersionNumber($stockRec)
		{
		}
		
		function getTableDef($tableName)
		{
			$sql = parent::getTableDef($tableName);
			
			switch($tableName)
			{
				case $this->DBTables->Sales:
					$sql .= '
						saleDateTime DATETIME NOT NULL,
						saleFirstName VARCHAR('.PAYMENT_API_SALENAME_TEXTLEN.') NOT NULL,
						saleLastName VARCHAR('.PAYMENT_API_SALENAME_TEXTLEN.') NOT NULL,
						saleEMail VARCHAR('.PAYMENT_API_SALEEMAIL_TEXTLEN.') NOT NULL,
						salePPName VARCHAR('.PAYMENT_API_SALEPPNAME_TEXTLEN.'),
						salePPStreet VARCHAR('.PAYMENT_API_SALEPPSTREET_TEXTLEN.'),
						salePPCity VARCHAR('.PAYMENT_API_SALEPPCITY_TEXTLEN.'),
						salePPState VARCHAR('.PAYMENT_API_SALEPPSTATE_TEXTLEN.'),
						salePPZip VARCHAR('.PAYMENT_API_SALEPPZIP_TEXTLEN.'),
						salePPCountry VARCHAR('.PAYMENT_API_SALEPPCOUNTRY_TEXTLEN.'),
						salePPPhone VARCHAR('.PAYMENT_API_SALEPPPHONE_TEXTLEN.'),
						saleDonation DECIMAL(9,2) NOT NULL DEFAULT 0,
						salePostage DECIMAL(9,2) NOT NULL DEFAULT 0,
						saleExtraDiscount DECIMAL(9,2) NOT NULL DEFAULT 0,
						saleTransactionFee DECIMAL(9,2) NOT NULL DEFAULT 0,						
						saleStatus VARCHAR('.PAYMENT_API_SALESTATUS_TEXTLEN.'),	
						saleTxnId VARCHAR('.PAYMENT_API_SALETXNID_TEXTLEN.') NOT NULL,
						saleNoteToSeller TEXT,
						salePPExpToken VARCHAR('.PAYMENT_API_EXPTOKEN_TEXTLEN.') NOT NULL DEFAULT "",
						saleCheckoutURL VARCHAR('.PAYMENT_API_URL_TEXTLEN.') NOT NULL DEFAULT "",
						saleGatewayIndex INT UNSIGNED DEFAULT 1,
						user_login VARCHAR(60) NOT NULL DEFAULT "",
					';
					break;
					
				case $this->DBTables->Payments:
					$sql .= '
						saleID INT UNSIGNED NOT NULL,
						paymentDateTime DATETIME DEFAULT NULL,
						paymentMethod VARCHAR('.PAYMENT_API_SALEMETHOD_TEXTLEN.'),
						paymentPaid DECIMAL(9,2) NOT NULL,
						paymentFee DECIMAL(9,2) NOT NULL,
					';
					break;
			}
							
			return $sql;
		}
		
		function clearAll()
		{
			parent::clearAll();

			$this->DropTable($this->DBTables->Sales);
			$this->DropTable($this->DBTables->Payments);
		}
		
		function createDB($dropTable = false)
		{
			parent::createDB($dropTable);

			$this->createDBTable($this->DBTables->Sessions, 'sessionID');	
								
			if ($this->GatewayID != '')
			{
				$copyPaymentsTable = ($this->tableExists($this->DBTables->Sales) && !$this->tableExists($this->DBTables->Payments));
				
				$this->createDBTable($this->DBTables->Payments, 'paymentID', $dropTable);

				if ($copyPaymentsTable)
				{
					$dateTimeColName = 'saleDateTime';
					if ($this->IfColumnExists($this->DBTables->Sales, 'salePaidDateTime'))
						$dateTimeColName = 'GREATEST(salePaidDateTime, saleDateTime)';
					
					$select = " SELECT saleID, $dateTimeColName, saleMethod, salePaid, saleFee FROM ".$this->DBTables->Sales;
					
					$sql  = 'INSERT INTO '.$this->DBTables->Payments;
					$sql .= ' (saleID, paymentDateTime, paymentMethod, paymentPaid, paymentFee)';
					$sql .= $select;
					$this->query($sql);
					
					$this->DeleteColumnIfExists($this->DBTables->Sales, 'salePaidDateTime');
					$this->DeleteColumnIfExists($this->DBTables->Sales, 'saleMethod');
					$this->DeleteColumnIfExists($this->DBTables->Sales, 'salePaid');
				}			

				$this->DeleteColumnIfExists($this->DBTables->Sales, 'saleFee');
				$this->DeleteColumnIfExists($this->DBTables->Sales, 'saleCheckoutTime');
				
				$this->createDBTable($this->DBTables->Sales, 'saleID', $dropTable);
			}
		}
		
		function GetSaleStockID($itemRef, $itemOption)
		{
			return 0;
		}
		
		function GetSaleName($result)
		{
			if (is_array($result))
			{
				return StageShowLibMigratePHPClass::Safe_trim($result['saleFirstName'].' '.$result['saleLastName']);
			}
			else
			{
				return StageShowLibMigratePHPClass::Safe_trim($result->saleFirstName.' '.$result->saleLastName);
			}
		}
		
		// Edit Sale	
			
		function AddSampleSaleItem($saleID, $stockID, $qty, $paid, $saleExtras = array())
		{
			return $this->AddSaleItem($saleID, $stockID, $qty, $paid, $saleExtras);
		}	
		
		function GetSalesQty($sqlFilters)
		{
			$sql  = 'SELECT '.$this->TotalSalesField($sqlFilters).' FROM '.$this->DBTables->Sales;	
			$sql .= $this->GetJoinedTables($sqlFilters, __CLASS__);
			$sql .= $this->GetWhereSQL($sqlFilters);
			$sql .= ' AND '.$this->DBTables->Sales.'.saleStatus != "'.PAYMENT_API_SALESTATUS_TIMEOUT.'"';
			$sql .= ' AND '.$this->DBTables->Sales.'.saleStatus != "'.PAYMENT_API_SALESTATUS_SUSPENDED.'"';
					
			$salesListArray = $this->get_results($sql);
			if (count($salesListArray) == 0)
					return 0;
							 
			return $salesListArray[0]->totalQty;
		}
		
		function CanEditSales()
		{
			return true;
		}
					
		function DeleteSale($saleID)
		{
			// Delete a show entry
			$sql  = 'DELETE FROM '.$this->DBTables->Sales;
			if (is_array($saleID))
			{
				$salesList = '';
				foreach ($saleID as $saleItemID)
				{
					if ($salesList != '') $salesList .= ',';
					$salesList .= $saleItemID->saleID;
				}
				$sql .= ' WHERE '.$this->DBTables->Sales.".saleID IN ($salesList)";
			}
			else
				$sql .= ' WHERE '.$this->DBTables->Sales.".saleID=$saleID";
				
			$this->query($sql);
			
			$this->PurgeOrdersAndPayments();
		}			

		function GetOrderSQL($sqlFilters = null)
		{
			return 'SUM(orderPaid) AS soldValue';
		}			
		
		function GetOrderFilter($sqlFilters = null)
		{
			return '';
		}			

		function GetFilteredSalesList($sqlFilters = null)
		{	
			$sqlFilters['ordersJoined'] = true;
			
			$ordersSql  = 'SELECT '.$this->DBTables->Orders.'.saleID, '.$this->TotalOrdersField($sqlFilters).' ';
			$ordersSql .= ', '.$this->GetOrderSQL($sqlFilters).' ';
			$ordersSql .= 'FROM '.$this->DBTables->Orders.' ';
			$ordersSql .= $this->GetJoinedTables($sqlFilters, __CLASS__).' ';
			$ordersSql .= $this->GetOrderFilter($sqlFilters).' ';
			$ordersSql .= 'GROUP BY '.$this->DBTables->Orders.'.saleID ';

			$selectFields = isset($sqlFilters['sqlSelect']) ? $sqlFilters['sqlSelect'] : '*';
			
			$sqlFilters['allSales'] = true;
			$sqlFilters['incSearch'] = true;

			$sql  = 'SELECT '.$selectFields.' FROM '.$this->DBTables->Sales.' ';
			$sql .= $this->GetPaymentsSQL();
			$sql .= 'JOIN ('.$ordersSql.') AS ticket ';
			$sql .= 'ON '.$this->DBTables->Sales.'.saleID=ticket.saleID ';
			$sql .= $this->GetWhereSQL($sqlFilters).' ';
			$sql .= 'ORDER BY '.$this->DBTables->Sales.'.saleDateTime DESC ';
			
			if (isset($sqlFilters['sqlLimit']))
			{
				$sql .= $sqlFilters['sqlLimit'].' ';
			}
			
			$salesListArray = $this->get_results($sql);			
					
			return $salesListArray;
		}			
		
		function GetAllSalesList($sqlFilters = null)
		{
			$sqlFilters['addPayments'] = true;
			$sqlFilters['groupBy'] = 'saleID';
			$sqlFilters['orderBy'] = $this->DBTables->Sales.'.saleDateTime DESC';
			return $this->GetSalesList($sqlFilters);
		}

		function GetSaleTotals($saleID, $sqlFilters = array())
		{
			$sqlFilters['saleID'] = $saleID;
			$sqlFilters['groupBy'] = 'saleID';
			$sqlFilters['addPayments'] = true;
			return $this->GetSalesList($sqlFilters);
		}
		
		function AddPendingSaleItem(&$saleDetails, $itemName, $itemAmt, $itemQty = 1)
		{
			if ($itemAmt == 0) return 0;
			
			$saleItem = new stdClass();
			$saleItem->ticketName = $itemName;
			$saleItem->ticketQty = 1;
			$saleItem->ticketPaid = $itemAmt * $itemQty;
			$saleItem->priceValue = $itemAmt;
			$saleItem->ticketType = '';
			
			$saleDetails[] = $saleItem;
		}						
		
		function GetPendingSale($saleID)
		{
			$saleDetails = $this->GetSale($saleID);

			if (count($saleDetails) > 0)
			{
				$this->AddPendingSaleItem($saleDetails, __('Discount', $this->get_Domain()), $saleDetails[0]->saleExtraDiscount);
				$this->AddPendingSaleItem($saleDetails, __('Booking Fee', $this->get_Domain()), $saleDetails[0]->saleTransactionFee);
				$this->AddPendingSaleItem($saleDetails, __('Donation', $this->get_Domain()), $saleDetails[0]->saleDonation);
				$this->AddPendingSaleItem($saleDetails, __('Postage', $this->get_Domain()), $saleDetails[0]->salePostage);
			}
		
			return $saleDetails;
		}						
		
		function HasExtraDiscount()
		{
			return false;
		}						

		function GetSalesEMail()
		{
			return $this->adminOptions['SalesEMail'];
		}
		
		function GetLocation()
		{
			return '';
		}
	
		function AddGenericDBFields(&$saleDetails)
		{
			parent::AddGenericDBFields($saleDetails);
			$saleDetails->salesEMail = $this->GetSalesEMail();
		}
		
		function IsDateTimeField($tag)
		{	
			// Return true for DATETIME DB Fields
			switch ($tag)
			{
				case '[saleDateTime]':
				case '[salePaidDateTime]':
					return true;
			}
			
			return false;					
		}
		
		function IsCurrencyField($tag)
		{
			switch ($tag)
			{
				case '[saleFee]':
				case '[saleExtraDiscount]':
				case '[saleTransactionFee]':
				case '[salePaid]':
				case '[saleDonation]':
				case '[salePostage]':
					return true;
			}
			
			return false;					
		}
		
		function RetrieveEventElement($tag, $field, &$saleDetails)
		{
			if ($tag =='[saleName]')
			{
				return $this->GetSaleName($saleDetails);
			}
			
			if ($tag =='[saleNoteToSeller]')
			{
				$saleNoteToSeller = $saleDetails->saleNoteToSeller;
				if ($saleNoteToSeller != '')
				{
					$saleNoteToSeller = StageShowLibMigratePHPClass::Safe_str_replace("\n", "<br>", $saleNoteToSeller);
					$saleNoteToSeller = StageShowLibMigratePHPClass::Safe_str_replace("<br><br>", "<br>", $saleNoteToSeller);
				}
				return $saleNoteToSeller;
			}
			
			if (!property_exists($saleDetails, $field))
			{
				return "**** $field ".__("Undefined", 'stageshow')." ****";
			}
			
			if ($this->IsCurrencyField($tag))
			{
				$saleFieldValue = $this->FormatCurrency($saleDetails->$field, false);
			}
			else if ($this->IsDateTimeField($tag))
			{
				$saleFieldValue = $this->FormatDateForDisplay($saleDetails->$field);
			}
			else 
			{
				$saleFieldValue = $saleDetails->$field;
			}
			
			return $saleFieldValue;
		}
		
		static function HasCheckoutImage()
		{
			return false;
		}
		
		function AddEventToTemplate($EMailTemplate, $saleDetails)
		{
			$this->AddGenericDBFields($saleDetails);
			
			$changeCount = $this->DoTemplateConditionals($EMailTemplate, $saleDetails);
			
			// FUNCTIONALITY: DBase - Sales - Add DB fields to EMail			
			$EMailTemplate = $this->AddGenericFields($EMailTemplate);
			
			
			if (isset($saleDetails->saleID))
			{
				// Add any email fields that are not in the sale record
				$saleDetails->saleName = '';
			}
			
			$EMailTemplate = parent::AddEventToTemplate($EMailTemplate, $saleDetails);
			
			return $EMailTemplate;
		}			

		function GetAdminEMail()
		{
			return $this->GetEmail($this->adminOptions);
		}
				
		function GetServerEmail()
		{
			return $this->GetEmail($this->adminOptions, 'Sales');
		}			

		function GetEmail($ourOptions, $emailRole = '')
		{
			if ($emailRole === '')
				$emailRole = 'Admin';
				
			$ourEmail = '';
			$IDIndex = $emailRole.'ID';
			$EMailIndex = $emailRole.'EMail';

			// Get from email address from settings
			if (StageShowLibMigratePHPClass::Safe_strlen($ourOptions[$EMailIndex]) > 0)
			{
				$ourEmail .= $ourOptions[$EMailIndex];
				if (StageShowLibMigratePHPClass::Safe_strlen($ourOptions[$IDIndex]) > 0)
					$ourEmail = $ourOptions[$IDIndex] . ' <'.$ourEmail.'>';
			}
					
			return $ourEmail;
		}			

		function GetTemplateIDFromName($templateName)
		{
			return $templateName;
		}
		
		function CheckEmailTemplatePath($templateID, $defaultTemplate = '', $baseClassTemplate = '')
		{
			$templatePath = StageShowLibMigratePHPClass::Safe_str_replace("\\", "/", $this->adminOptions[$templateID]);
			$templatePath = basename($templatePath);

			// Fix for update downgrading template error
			if ( ($baseClassTemplate != '')
			  && ($defaultTemplate != $baseClassTemplate)
			  && ($templatePath == $baseClassTemplate))
			{
			  	$templatePath = $defaultTemplate;
			}
			
			if ($templatePath == '')
			{
				$templatePath = $defaultTemplate;
			}
			$this->adminOptions[$templateID] = $templatePath;
		}

		function GetEmailTemplatePath($templateID, $salesList = array())
		{
			if (($templateID === 'EMailTemplatePath') && isset($salesList[0]->saleGatewayIndex))
			{
				$acctNo = $salesList[0]->saleGatewayIndex;
				$optionGatewaySuffix = $this->gatewayObj->GetOptionGatewaySuffix($acctNo);
				$templateID .= $optionGatewaySuffix;
			}
			
			return $this->GetTemplatePath($templateID, 'emails');
		}

		function GetTemplateRoot($folder)
		{
			// EMail Template defaults to templates folder
			$pluginID = basename(dirname(dirname(__FILE__)));	// Library files should be in 'include' folder			
			$templateRoot = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/'.$folder.'/';

			return $templateRoot;
		}

		function GetTemplatePath($templateID, $folder)
		{
			// EMail Template defaults to templates folder
			$pluginID = basename(dirname(dirname(__FILE__)));	// Library files should be in 'include' folder			
			$templatePath = $this->GetTemplateRoot($folder).$this->adminOptions[$templateID];

			return $templatePath;
		}

		function EMailSale($saleID, $EMailTo = '')
		{
			$salesList = $this->GetSaleDetails($saleID);
			if (count($salesList) < 1) 
				return 'salesList Empty';
			
			switch ($salesList[0]->saleStatus)
			{
				case PAYMENT_API_SALESTATUS_SUSPENDED:
					$templateID = 'RejectedEMailTemplatePath';
					if ($EMailTo == '') $EMailTo = $this->GetEmail($this->adminOptions);
					break;

				default:
					$templateID = 'EMailTemplatePath';
					break;
			}
			
			return $this->EMailSaleRecord($salesList, $EMailTo, $templateID);
		}

		function EMailSaleRecord($salesList, $EMailTo = '', $templateID = 'EMailTemplatePath')
		{
			$templatePath = $this->GetEmailTemplatePath($templateID, $salesList);
	
			return $this->SendEMailFromTemplate($salesList, $templatePath, $EMailTo);
		}
		
		function GetBccEMail()
		{
			$BccEMail = '';
			if ($this->getOption('BccEMailsToAdmin'))
			{
				$BccEMail = $this->GetAdminEMail();
			}
			return $BccEMail;
		}
		
		function SendEMailWithDefaults($eventRecord, $EMailSubject, $EMailContent, $EMailTo = '', $headers = '')
		{
			if (StageShowLibMigratePHPClass::Safe_strlen($EMailTo) == 0) $EMailTo = $eventRecord[0]->saleEMail;

			// FUNCTIONALITY: General - EMail copy of any outgoing email to AdminEMail
			$headers = '';
			$bccEMail = $this->GetBccEMail();
			if (StageShowLibMigratePHPClass::Safe_strlen($bccEMail) > 0)
			{	
				// Bcc emails to Admin Email	
				$headers .= "bcc: $bccEMail";
			}

			return parent::SendEMailWithDefaults($eventRecord, $EMailSubject, $EMailContent, $EMailTo, $headers);
		}

		function GetTxnStatus($Txnid)
		{
			$sql = 'SELECT saleStatus FROM '.$this->DBTables->Sales.' WHERE saleTxnId="'.$Txnid.'"';
			 
			$txnEntries = $this->get_results($sql);
			
			if (count($txnEntries) == 0) 
				return '';
			
			return $txnEntries[0]->saleStatus;
		}
		
		function GetTxnGatewayID($saleId)
		{
			$sql = 'SELECT saleGatewayIndex FROM '.$this->DBTables->Sales.' WHERE saleId="'.$saleId.'"';
			 
			$txnEntries = $this->get_results($sql);
			
			if (count($txnEntries) == 0) 
				return 1;
			
			return $txnEntries[0]->saleGatewayIndex;
		}
		
		function GetCheckoutURL($saleId)
		{
			$sql = 'SELECT saleCheckoutURL FROM '.$this->DBTables->Sales.' WHERE saleId="'.$saleId.'"';
			 
			$chkoutEntries = $this->get_results($sql);
			
			if (count($chkoutEntries) == 0) 
				return '';
			
			return $chkoutEntries[0]->saleCheckoutURL;
		}
		
		function UpdateSaleIDStatus($saleId, $Payment_status)
		{
			$sql  = 'UPDATE '.$this->DBTables->Sales;
			$sql .= ' SET saleStatus="'.$Payment_status.'"';		
			$sql .= ' WHERE saleId="'.$saleId.'"';							
			 
			$this->query($sql);			
		}
		
		function UpdateSaleTxnId($saleId, $saleTxnId)
		{
			$sql  = 'UPDATE '.$this->DBTables->Sales;
			$sql .= ' SET saleTxnId="'.$saleTxnId.'"';		
			$sql .= ' WHERE saleId="'.$saleId.'"';							
			 
			$this->query($sql);			
		}
		
		function UpdateSaleToken($saleId, $saleToken)
		{
			$sql  = 'UPDATE '.$this->DBTables->Sales;
			$sql .= ' SET salePPExpToken="'.$saleToken.'"';		
			$sql .= ' WHERE saleId="'.$saleId.'"';							
			 
			$this->query($sql);			
		}
		
		function UpdateCheckoutURL($saleId, $checkoutURL='')
		{
			$sql  = 'UPDATE '.$this->DBTables->Sales;
			$sql .= ' SET saleCheckoutURL="'.$checkoutURL.'"';		
			$sql .= ' WHERE saleId="'.$saleId.'"';							
			 
			$this->query($sql);			
		}
		
		function UpdateSaleStatus($Txn_id, $Payment_status, $CanClearURL = true)
		{
			$sql  = 'UPDATE '.$this->DBTables->Sales;

			if ( ($Payment_status == PAYMENT_API_SALESTATUS_COMPLETED) && ($CanClearURL) )
			{
				$sql .= ' SET saleStatus="'.$Payment_status.'", saleCheckoutURL=""';
			}
			else		
			{
				$sql .= ' SET saleStatus="'.$Payment_status.'"';
			}		
			$sql .= ' WHERE saleTxnId="'.$Txn_id.'"';							
			 
			$this->query($sql);	
			
			return $this->GetSaleFromTxnId($Txn_id);
		}
		
		function GetSaleFromTxnId($Txn_id, $saleEMail = '')
		{					
			// Get the SaleId and return it ....
			$sql  = 'SELECT saleId, saleEMail FROM '.$this->DBTables->Sales;
			$sql .= ' WHERE saleTxnId="'.$Txn_id.'"';							
			 
			$saleEntry = $this->get_results($sql);
			if (count($saleEntry) == 0)
				return 0;
			
			if (($saleEMail != '') && ($saleEMail != $saleEntry[0]->saleEMail))
				return 0;
						 
			return $saleEntry[0]->saleId;
		}
		
		function GetPIRRecordsByEMail($saleEMail)
		{					
			$sqlFilters['JoinType'] = 'LEFT JOIN';
			
			// Get the SaleId and return it ....
			$sql  = 'SELECT * FROM '.$this->DBTables->Sales.' ';
			$sql .= $this->GetPaymentsSQL();
			$sql .= $this->GetJoinedTables($sqlFilters, __CLASS__).' ';
			$sql .= ' WHERE saleEMail="'.$saleEMail.'"';							
			 
			$saleEntries = $this->get_results($sql);
						 
			return $saleEntries;
		}
		
		function GetSaleExtras($itemNo, $results)
		{
			return array();
		}
		
		function GetSaleItemMeta($itemNo)
		{
			
		}
		
		function GetPayments($saleID)
		{
			// Get the SaleId and return it ....
			$sql  = 'SELECT * FROM '.$this->DBTables->Payments.' ';
			$sql .= ' WHERE saleID="'.$saleID.'"';							
			 
			$paymentEntries = $this->get_results($sql);
						 
			return $paymentEntries;
		}
		
		function GetTotalPayments($saleID)
		{
			// Get the SaleId and return it ....
			$sql  = 'SELECT SUM(paymentPaid) AS totalPaid FROM '.$this->DBTables->Payments.' ';
			$sql .= ' WHERE saleID="'.$saleID.'"';							
			 
			$paymentEntries = $this->get_results($sql);
					
			if (count($paymentEntries) == 0) return 0;
			
			return $paymentEntries[0]->totalPaid;
		}
		
		
		
		function LogSale($checkoutRslt, $saleMode)
		{
			$results = $checkoutRslt->saleDetails;
			
			switch ($saleMode)
			{
				case self::STAGESHOWLIB_LOGSALEMODE_CHECKOUT:
					$saleDateTime = current_time('mysql'); 
					
					$saleVals['saleStatus'] = PAYMENT_API_SALESTATUS_CHECKOUT;
					
					if (isset($results['saleMethod']))    $saleVals['saleMethod'] = $results['saleMethod'];
				
					// Add empty values for fields that do not have a default value
					$saleVals['saleFirstName'] = isset($results['saleFirstName']) ? $results['saleFirstName'] : '';
					$saleVals['saleLastName']  = isset($results['saleLastName']) ? $results['saleLastName'] : '';
					$saleVals['saleEMail']     = isset($results['saleEMail']) ? $results['saleEMail'] : '';
					
					// Add values for fields that are entered by user
					if (isset($results['salePPStreet']))    $saleVals['salePPStreet'] = $results['salePPStreet'];
					if (isset($results['salePPCity']))      $saleVals['salePPCity'] = $results['salePPCity'];
					if (isset($results['salePPState']))     $saleVals['salePPState'] = $results['salePPState'];
					if (isset($results['salePPZip']))       $saleVals['salePPZip'] = $results['salePPZip'];
					if (isset($results['salePPCountry']))   $saleVals['salePPCountry'] = $results['salePPCountry'];
					if (isset($results['salePPPhone']))     $saleVals['salePPPhone'] = $results['salePPPhone'];
					
					$saleVals['saleTxnId'] = '';

					$saleVals['salePaid'] = '0.0';
					$saleVals['saleFee'] = '0.0';
					if (isset($results['saleExtraDiscount']))   $saleVals['saleExtraDiscount'] = $results['saleExtraDiscount'];
					if (isset($results['saleTransactionFee']))  $saleVals['saleTransactionFee'] = $results['saleTransactionFee'];
					if (isset($results['saleDonation']))        $saleVals['saleDonation'] = $results['saleDonation'];
					if (isset($results['salePostage']))         $saleVals['salePostage'] = $results['salePostage'];
					if (isset($results['saleNoteToSeller']))	$saleVals['saleNoteToSeller'] = $results['saleNoteToSeller'];
					if (isset($results['salePPExpToken']))      $saleVals['salePPExpToken'] = $results['salePPExpToken'];
					if (isset($results['saleDiscountCode']))    $saleVals['saleDiscountCode'] = $results['saleDiscountCode'];
					if (isset($results['saleGatewayIndex']))    $saleVals['saleGatewayIndex'] = $results['saleGatewayIndex'];

					global $current_user;
					if (is_user_logged_in())
					{
						wp_get_current_user();
						$saleVals['user_login'] = $current_user->user_login;
					}		
									
					$saleVals['saleCheckoutURL'] = StageShowLibUtilsClass::GetPageURL();
							
					$saleID = $this->AddSale($saleDateTime, $saleVals);

					break;
				
				case self::STAGESHOWLIB_LOGSALEMODE_RESERVE:
					$saleDateTime  = $results['saleDateTime'];
					
					foreach ($results as $fieldID => $fieldVal)
					{
						// Don't pass ticket details to AddSale() ... these are passed in AddSaleItem()
						if (is_numeric(StageShowLibMigratePHPClass::Safe_substr($fieldID, -1, 1)))
							continue;
							
						$saleVals[$fieldID] = $fieldVal;
					}
					
					// Log sale to Database
					$saleID = $this->AddSale($saleDateTime, $saleVals);
					break;
				
				default:
					StageShowLibEscapingClass::Safe_EchoHTML("<br><br>Invalid saleMode in LogSale() call<br><br>");
					return 0;
				
			}
		  		
			$itemNo = 1;
			While (true)
			{
				if (!isset($results['qty' . $itemNo]))
					break;

				if (isset($results['itemRef' . $itemNo]))
				{
					$itemRef  = $results['itemRef' . $itemNo];
					$itemOption  = $results['itemOption' . $itemNo];
										
					// Find stockID from Database	    
					$stockID = $this->GetSaleStockID($itemRef, $itemOption);
				}
				else
				{
					$stockID = $results['itemID' . $itemNo];
				}
				
				$qty  = $results['qty' . $itemNo];
				$itemPaid  = $results['itemPaid' . $itemNo];

				if ($qty > 0)
				{
					// Log sale item to Database
					$saleExtras = $this->GetSaleExtras($itemNo, $results);
					$saleItemID = $this->AddSaleItem($saleID, $stockID, $qty, $itemPaid, $saleExtras);

					if (isset($checkoutRslt->customFields))
					{
						if (isset($checkoutRslt->customFields[$itemNo]))
						{
							foreach ($checkoutRslt->customFields[$itemNo] as $customFieldID => $customFieldValue)
							{
								$this->AddSaleItemMeta($saleItemID, $customFieldID, $customFieldValue);
							}
						}
					}
				}
				$itemNo++;
			}
		  
			return $saleID;
		}

		function CanReinstateSaleItem($saleItem)
		{
			return true;
		}
		
		function AddTableLocks($sql)
		{
			$sql .= $this->DBTables->Sessions.' WRITE, ';
			$sql .= $this->DBTables->Sales.' WRITE, ';
			$sql .= $this->DBTables->Payments.' WRITE, ';
			$sql .= $this->DBTables->Orders.' WRITE';
			
			return $sql;
		}
		
		function LockSalesTable()
		{
			$sql = $this->AddTableLocks('LOCK TABLES ');
			$this->query($sql);
		}
		
		function UnLockTables()
		{
			$sql  = 'UNLOCK TABLES';
			$this->query($sql);
		}
		
	    function HTTPGet($url)
	    {	
			return $this->HTTPRequest($url, '', 'GET');
		}
		
	    function HTTPPost($url, $urlParams = '')
	    {	
			return $this->HTTPRequest($url, $urlParams, 'POST');
		}
		
	    function HTTPRequest($url, $urlParams = '', $method = '', $redirect = true)
	    {	
			if ($method == '')
			{
				$method = ($urlParams == '') ? 'GET' : 'POST';			
			}
			
			$HTTPResponse = $this->HTTPAction($url, $urlParams, $method, $redirect);
			return $HTTPResponse; 
	    }
    
		function HTTPAction($url, $urlParams = '', $method = 'POST', $redirect = true)
		{
			if( !class_exists( 'WP_Http' ) )
				include_once( ABSPATH . WPINC. '/class-http.php' );

			$args = array(
			'method' => $method,
			'body' => $urlParams,
			'sslverify' => false
			);
			
			if (!$redirect)
				$args['redirection'] = 0;
			
			$request = new WP_Http;
			$HTTPResult = $request->request( $url, $args );
			if ( is_wp_error($HTTPResult) )
			{
				$response['APIResponseText'] = '';
				$response['APIStatus'] = 'ERROR';
				$response['APIStatusMsg'] = $HTTPResult->get_error_message();
				$response['APIHeaders'] = '';
				$response['APICookies'] = array();
			}
			else
			{
				$response['APIResponseText'] = $HTTPResult['body'];
				$response['APIStatus'] = $HTTPResult['response']['code'];
				$response['APIStatusMsg'] = $HTTPResult['response']['message'];
				$response['APIHeaders'] = $HTTPResult['headers'];
				$response['APICookies'] = $HTTPResult['cookies'];
			}
/*			
			{
				StageShowLibEscapingClass::Safe_EchoHTML("HTTPRequest Called<br>");
				StageShowLibEscapingClass::Safe_EchoHTML("URL: $url<br>");
				StageShowLibEscapingClass::Safe_EchoHTML("METHOD: $method<br>");
				StageShowLibEscapingClass::Safe_EchoHTML("URL Params: <br>");
				print_r($urlParams);
				print_r($response, 'HTTPResponse:');
			}
*/
			return $response;			
		}

		function GetDatabaseSQL($tablePrefix)
		{
			$tables = $this->GetPluginDBTablesList($tablePrefix);
			$backupData = $this->ExportDatabaseTables($tables);						
			return $backupData;
		}
		
		function GetPluginDBTablesList($tablePrefix)
		{
			$this->query("SET NAMES 'utf8'");

			$allTables = $this->get_results('SHOW TABLES');
			$ssTables = array();

			foreach ($allTables as $tableRow) 
			{
				foreach ($tableRow as $table)
				{
					break;
				}

				$thisPrefix = StageShowLibMigratePHPClass::Safe_substr($table, 0, StageShowLibMigratePHPClass::Safe_strlen($tablePrefix));
				if ($thisPrefix == $tablePrefix)
				{
					$ssTables[$table] = $table;
				}
			}				
			
			return $ssTables;
		}
		
		function ExportDatabaseTables($tables = false)
		{
			global $wpdb;
			
			$destDBRemoveEMail = (StageShowLibUtilsClass::GetHTTPAlphaNumericElem('post', 'dest_DB_removeEMail', false) != false); 
			
			$dbEmailRedirect = StageShowLibUtilsClass::GetHTTPAlphaNumericElem('post', 'dbemailredirect'); 
			
			$destDBPrefix = StageShowLibUtilsClass::GetHTTPAlphaNumericElem('post', 'dest_DB_prefix'); 

			$content = '';
			
			$this->query("SET NAMES 'utf8'");

			$allTables = $this->get_results('SHOW TABLES');

			foreach ($allTables as $tableRow) 				
			{
				// get first table row
				foreach ($tableRow as $table)
				{
					break;
				}

				if ($tables !== false)
				{
					if (!in_array($table, $tables)) 
					{
						continue;
					}
				}
				
				if ($destDBPrefix != '')
				{
					$destTable = StageShowLibMigratePHPClass::Safe_str_replace($wpdb->prefix, $destDBPrefix, $table);
				}
				else
				{
					$destTable = $table;
				}
				
				$content .= "\nDROP TABLE IF EXISTS $destTable;\n";
				
				$dbval = $this->GetDBValues($table);

				$res           = $this->get_results('SHOW CREATE TABLE ' . $table);
				$TableMLine    = $res[0];
				$i = 0;
				foreach ($TableMLine as $key => $value)
				{
					if (($key == 0) && ($destDBPrefix != '')) $value = StageShowLibMigratePHPClass::Safe_str_replace($table, $destTable, $value);
					$TableMDef = $value;
				}
				$content .= "\n\n" . $TableMDef . ";\n\n";
				
				for ($i = 0, $st_counter = 0; $i < $dbval->noOfRows; $i++)
				{
					$tablerow = $dbval->results[$i];
					
					if ($st_counter % 100 == 0 || $st_counter == 0)
					{
						$content .= "\nINSERT INTO " . $destTable . " VALUES";
					}
					$content .= "\n(";
					
					$row = array();
					foreach ($tablerow as $fieldName => $fieldVal) 
					{						
						if (StageShowLibMigratePHPClass::Safe_strpos(StageShowLibMigratePHPClass::Safe_strtolower($fieldName), 'email') !== false)
						{
							if ($destDBRemoveEMail)
							{
								$fieldVal = StageShowLibMigratePHPClass::Safe_str_replace('@', '_', $fieldVal);
								if ($dbEmailRedirect != '')
								{
									$fieldVal .= "<$dbEmailRedirect>";
								}
							}
						}
	
						$row[] = $fieldVal;
					}
					
					for ($j = 0; $j < $dbval->noOfFields; $j++)
					{
						if (isset($row[$j]))
						{
							$row[$j] = addslashes($row[$j]);
							
							// Add Escape codes for CR and LF
							$row[$j] = StageShowLibMigratePHPClass::Safe_str_replace("\n", "\\n", $row[$j]);
							$row[$j] = StageShowLibMigratePHPClass::Safe_str_replace("\r", "\\r", $row[$j]);
						
							$content .= '"' . $row[$j] . '"';
						}
						else if (is_null($row[$j]))
						{
							$content .= 'NULL';
						}
						else
						{
							$content .= '""';
						}
						
						if ($j < ($dbval->noOfFields - 1))
						{
							$content .= ',';
						}
					}
					$content .= ")";
					
					//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
					if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $dbval->noOfRows)
					{
						$content .= ";";
					}
					else
					{
						$content .= ",";
					}
					$st_counter = $st_counter + 1;
				}
				$content .= "\n\n\n";
			}

			return $content;
		}
		
		function GetDBValues($table, $fields = '*', $where = '')
		{
			$results = $this->get_results("SELECT $fields FROM $table $where");
			$noOfFields = 0;			
			$noOfRows = count($results);
			if ($noOfRows > 0)
			{
				$firstRow = $results[0];
				foreach ($firstRow as $key => $value)
				{
					$noOfFields++;
				}					
			}
			
			$rtnVal = new stdClass();
			$rtnVal->results = $results;
			$rtnVal->noOfFields = $noOfFields;			
			$rtnVal->noOfRows = $noOfRows;
			
			return $rtnVal;
		}
		
		function DoSalesUpdateActions()
		{
			// Hook to do something when the Sales Database is updated
			do_action('stageshow'.'_sales_updated', $this);
		}
		
	}
}



