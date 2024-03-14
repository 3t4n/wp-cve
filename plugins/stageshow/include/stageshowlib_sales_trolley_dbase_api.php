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

include_once('stageshowlib_gatewaybase.php');

if (!defined('STAGESHOWLIB_DBASE_CLASS'))
	define('STAGESHOWLIB_DBASE_CLASS', 'StageShowLibSalesCartDBaseClass');
	
if (!class_exists('StageShowLibDBaseClass')) 
	include STAGESHOWLIB_INCLUDE_PATH.'stageshowlib_dbase_api.php';
	
if (!class_exists('StageShowLibSalesCartDBaseClass')) 
{
	/*
	---------------------------------------------------------------------------------
		StageShowLibSalesCartDBaseClass
	---------------------------------------------------------------------------------
	
	This class provides database functionality to capture sales data and support
	Payment Notification
	*/
	
	if (!defined('PAYPAL_APILIB_DEFAULT_HEADERIMAGE_FILE'))
		define('PAYPAL_APILIB_DEFAULT_HEADERIMAGE_FILE', '');
		
	if( !defined( 'PAYMENT_API_SALESTATUS_RESERVED' ) )
	{
		define('PAYMENT_API_SALESTATUS_RESERVED', 'Reserved');	
	}
		
	if( !defined( 'PAYMENT_API_SALESTATUS_LOCKOUT' ) )
	{
		define('PAYMENT_API_SALESTATUS_LOCKOUT', 'Lockout');		
	}

	if( !defined( 'STAGESHOWLIB_QTYSELECT_TEXT' ) )
	{
		define('STAGESHOWLIB_QTYSELECT_TEXT', 'text-');		
		define('STAGESHOWLIB_QTYSELECT_DROPDOWN', 'dd-');		
		define('STAGESHOWLIB_QTYSELECT_SINGLE', 'single');		
		define('STAGESHOWLIB_QTYSELECT_MULTIPLE', 'multiple');		
	}
	
  	class StageShowLibSalesCartDBaseClass extends StageShowLibDBaseClass // Define class
  	{	
		const STAGESHOWLIB_LOGSALEMODE_CHECKOUT = 'Checkout';
		const STAGESHOWLIB_LOGSALEMODE_RESERVE = 'Reserve';
		
		const STAGESHOWLIB_FROMTROLLEY = 't';		// 'Trolley';
		const STAGESHOWLIB_FROMCALLBACK = 'c';	// 'Callback';
		const STAGESHOWLIB_FROMSAVEEDIT = 'e';	// 'Edit';
		
		var 	$GatewayID = '';
		var		$GatewayName = '';
		
		function __construct($opts)		//constructor		
		{
			parent::__construct($opts);
						
			$optionsId = $opts['CfgOptionsID'];
			$currOptions = $this->ReadSettings($optionsId);
			$gatewayUpdated = true;
			$opts['DBaseObj'] = $this;
			if (isset($currOptions['GatewaySelected']))
			{
				$gatewayID = $currOptions['GatewaySelected'];	
				if ($this->AddGateway($opts, $gatewayID))
				{
					$gatewayUpdated = false;
				}
				else
				{
					$gatewayID = $currOptions['GatewaySelected'];					
				}
			}
			
			if ($gatewayUpdated)
			{
				$gatewayID = $this->GetDefaultGateway();
				if ($this->AddGateway($opts, $gatewayID))
				{
					$currOptions['GatewaySelected'] = $gatewayID;
				}
				else
				{
					$currOptions['GatewaySelected'] = '';
				}

				$this->WriteSettings($opts['CfgOptionsID'], $currOptions);
			}

			if (!isset($this->emailObjClass))
			{
				$this->emailObjClass = 'StageShowLibEMailAPIClass';
				$this->emailClassFilePath = STAGESHOWLIB_INCLUDE_PATH.'stageshowlib_email_api.php';   			
			}

		}

		function GatewayHasEMailName()
		{
			return true;
		}
		
		function AddGateway($opts, $gatewayID)
		{
			$this->GatewayID = $gatewayID;
			
			$gatewayFile = 'stageshowlib_'.$this->GatewayID.'_gateway.php'; 
			if (!file_exists(dirname(__FILE__).'/'.$gatewayFile)) return false;

			$gatewayClass = 'StageShowLib_'.$this->GatewayID.'_GatewayClass'; 
			
			include $gatewayFile;      						// i.e. stageshowlib_paypal_gateway.php
			$this->gatewayObj = new $gatewayClass($opts); 	// i.e. StageShowLib_paypal_GatewayClass
			
			$this->GatewayName = $this->gatewayObj->GetName();
			
			// Add default values for any undefined gateway settings
			$this->adminOptions = array_merge($this->gatewayObj->Gateway_GetOptions(), $this->adminOptions);

			$this->gatewayObj->LoginGatewayAPI($this->adminOptions, $this->dbgOptions);				

			return true;
		}
		
		function getImagesURL($useSSL = false)
		{
			if (defined('STAGESHOWLIB_IMAGESURL'))
				return STAGESHOWLIB_IMAGESURL;
			
			if (defined('STAGESHOWLIB_UPLOADS_URL'))	
			{
				$imagesurl = STAGESHOWLIB_UPLOADS_URL.'images/';
			}
			else
			{
				$imagesurl = dirname(dirname(StageShowLibUtilsClass::GetPageURL()));
				$imagesurl = StageShowLibMigratePHPClass::Safe_str_replace('plugins', 'uploads');
				$imagesurl .= 'images/';
			}
			if ($useSSL)
			{
				$imagesurl = StageShowLibMigratePHPClass::Safe_str_replace('http//', 'https//', $imagesurl);
			}
			return $imagesurl;
		}
		
		function GetGatewayHeaderURL()
		{
			if (isset($this->adminOptions['PayPalHeaderImageMode']))
			{
				$PayPalHeaderImageMode = $this->adminOptions['PayPalHeaderImageMode'];
			}
			else
			{
				$PayPalHeaderImageMode = PAYMENT_API_IMAGES_LOCAL_HTTP;
			}
			
			if ($PayPalHeaderImageMode == PAYMENT_API_IMAGES_SPECIFY_URL)
			{
				return $this->adminOptions['PayPalHeaderURL'];
			}
					
			$useSSL = ($PayPalHeaderImageMode == PAYMENT_API_IMAGES_LOCAL_HTTPS);
			return $this->getImagesURL('PayPalHeaderImageFile', $useSSL);
		}
		
		function CartEntryHasCheckoutForm($cartEntry)
		{
			return false;
		}
		
		function HasCheckoutForm($cartContents)
		{
			foreach ($cartContents->rows as $cartEntry)
			{
				if ($this->CartEntryHasCheckoutForm($cartEntry)) return true;
			}

			return false;
		}
		
		function getImageURL($optionId)
		{			
			$imageURL = isset($this->adminOptions[$optionId]) ? $this->getImagesURL().$this->adminOptions[$optionId] : '';
			return $imageURL;
		}
		
		static function FormatDateForDisplay($dateInDB, $dateFormat='')
		{
			// Convert time string to UNIX timestamp
			$timestamp = StageShowLibMigratePHPClass::Safe_strtotime( $dateInDB );
			return self::FormatTimestampForDisplay($timestamp, $dateFormat);
		}
		
		static function FormatTimestampForDisplay($timestamp, $dateFormat='')
		{
			if ($dateFormat == '')
			{
				$dateFormat = self::GetDateTimeFormat();				
			}
				
			// Get Time & Date formatted for display to user
			$dateAndTime = date_i18n($dateFormat, $timestamp);
			if (StageShowLibMigratePHPClass::Safe_strlen($dateAndTime) < 2)
			{
				$dateAndTime = '[Invalid WP Date/Time Format]';
			}
			
			return $dateAndTime;
		}
		
		function SettingsConfigured()
		{
			return $this->GatewayConfigured();
		}
		
		function GatewayConfigured()
		{
			return $this->gatewayObj->IsGatewayConfigured($this->adminOptions);
		}
		
		function saveOptions()
		{
			$newOptions = $this->adminOptions;

			if (isset($this->gatewayObj) && isset($newOptions['GatewaySelected']))
			{
				$currentGateway = $this->gatewayObj->GetID();
				$newGateway = $newOptions['GatewaySelected'];
				if ($newGateway != $currentGateway)		
				{
					// Load new gateway ...
					$this->AddGateway($this->gatewayObj->opts, $newGateway);
				}
				
				$currencyOptionID = $this->gatewayObj->GetCurrencyOptionID();	
				if (isset($newOptions[$currencyOptionID]))
				{
					$currency = $newOptions[$currencyOptionID];			
					$currencyDef = $this->gatewayObj->GetCurrencyDef($currency);
					
					if (isset($currencyDef['Symbol']))
					{
						$newOptions['CurrencySymbol'] = $currencyDef['Symbol'];
						$newOptions['CurrencyText']   = ($currencyDef['Char'] != '') ? $currencyDef['Char'] : $currency.'';
						$newOptions['CurrencyFormat'] = $currencyDef['Format'];
					}
					else
					{
						$newOptions['CurrencySymbol'] = $currency.'';
						$newOptions['CurrencyText']   = $currency.'';
						$newOptions['CurrencyFormat'] = '%01.2f';
					}							
				}				
			}
			
			$this->adminOptions = $newOptions;
			parent::saveOptions();
		}
		
		function getTableNames($dbPrefix)
		{
			$DBTables = parent::getTableNames($dbPrefix);
			
			$DBTables->Sales = $dbPrefix.'sales';
			$DBTables->Payments = $dbPrefix.'payments';
			$DBTables->Orders = $dbPrefix.'orders';
			$DBTables->Settings = $dbPrefix.'settings';
			
			return $DBTables;
		}
				
		function GetSalesQueryFields($sqlFilters = null)
		{
			if (isset($sqlFilters['groupBy'])) return $this->DBTables->Sales.'.*';	// MJS: Check SALES Table Fields
			
			return '*';
		}
		
		function GetPaymentsSQL()
		{
			$paymentsSelectSQL  = "SELECT saleID AS paymentSaleID, ";
			$paymentsSelectSQL .= "MAX(paymentDateTime) AS salePaidDateTime, ";
			$paymentsSelectSQL .= "GROUP_CONCAT(paymentMethod SEPARATOR ',') AS saleMethod, ";
			$paymentsSelectSQL .= "SUM(paymentPaid) AS salePaid, ";
			$paymentsSelectSQL .= "SUM(paymentFee) AS saleFee FROM ".$this->DBTables->Payments." ";
			$paymentsSelectSQL .= "GROUP BY saleID";		

			$sql = " LEFT JOIN($paymentsSelectSQL) AS salePayments ON salePayments.paymentSaleID = ".$this->DBTables->Sales.'.saleID ';
			return $sql;
		}
		
		function GetJoinedTables($sqlFilters = null, $classID = '')
		{
			$sql = '';
			if (isset($sqlFilters['addPayments']))
			{
				$sql .= $this->GetPaymentsSQL();
			}
			return $sql;
		}
		
		function GetWhereSQL($sqlFilters)
		{
			$sqlWhere = '';
			$sqlCmd = ' WHERE ';
			
			if (isset($sqlFilters['saleID']) && ($sqlFilters['saleID'] > 0))
			{
				$sqlWhere .= $sqlCmd.$this->DBTables->Sales.'.saleID="'.$sqlFilters['saleID'].'"';
				$sqlCmd = ' AND ';
			}
			
			if (isset($sqlFilters['saleTxnId']) && (StageShowLibMigratePHPClass::Safe_strlen($sqlFilters['saleTxnId']) > 0))
			{
				$sqlWhere .= $sqlCmd.$this->DBTables->Sales.'.saleTxnId="'.$sqlFilters['saleTxnId'].'"';
				$sqlCmd = ' AND ';
			}
			
			if (isset($sqlFilters['saleEMail']) && (StageShowLibMigratePHPClass::Safe_strlen($sqlFilters['saleEMail']) > 0))
			{
				$sqlWhere .= $sqlCmd.$this->DBTables->Sales.'.saleEMail="'.$sqlFilters['saleEMail'].'"';
				$sqlCmd = ' AND ';
			}
			
			if (isset($sqlFilters['incSearch']) && isset($this->searchSQL))
			{
				$sqlWhere .= $sqlCmd.$this->searchSQL;
				$sqlCmd = ' AND ';
			}
			
			if (isset($sqlFilters['whereSQL']))
			{
				$sqlWhere .= $sqlCmd.$sqlFilters['whereSQL'];
				$sqlCmd = ' AND ';
			}
			
			return $sqlWhere;
		}
		
		function AddSQLOpt($sql, $optName, $optValue)
		{
			if (StageShowLibMigratePHPClass::Safe_strstr($sql, $optName))
			{
				$sql = StageShowLibMigratePHPClass::Safe_str_replace($optName, $optName.$optValue.',', $sql);
			}
			else
			{
				$sql .= $optName.$optValue;
			}
			
			return $sql;
		}
		
		function GetOptsSQL($sqlFilters, $sqlOpts = '')
		{
			if (isset($sqlFilters['orderBy']))
			{
				$sqlOpts = $this->AddSQLOpt($sqlOpts, ' ORDER BY ', $sqlFilters['orderBy']);
			}
			
			if (isset($sqlFilters['limit']))
			{
				$sqlOpts = $this->AddSQLOpt($sqlOpts, ' LIMIT ', $sqlFilters['limit']);
			}
			
			return $sqlOpts;
		}

		// Edit Sale
		function GetSalesFields()
		{
			return array
			(
				'saleFirstName'  => 'saleFirstName', 
				'saleLastName'  => 'saleLastName', 
				'saleDateTime'  => 'saleDateTime', 
				'saleTxnId' => 'saleTxnId',
				'salePPExpToken' => 'salePPExpToken',
				'saleStatus'  => 'saleStatus', 
				'salePPName'  => 'salePPName', 

				'saleEMail'  => 'saleEMail', 
				'saleDonation'  => 'saleDonation', 
				'salePostage'  => 'salePostage', 
				'saleDiscountCode'  => 'saleDiscountCode', 
				'saleExtraDiscount'  => 'saleExtraDiscount', 
				'saleTransactionFee'  => 'saleTransactionFee', 
				'salePPStreet'  => 'salePPStreet', 
				'salePPCity'  => 'salePPCity', 
				'salePPState'  => 'salePPState', 
				'salePPZip'  => 'salePPZip', 
				'salePPCountry'  => 'salePPCountry', 
				'salePPPhone'  => 'salePPPhone', 

				'saleNoteToSeller'  => 'saleNoteToSeller', 

				'saleCheckoutURL' => 'saleCheckoutURL',
				
				'user_login'  => 'user_login', 
			);
		}			
		
		function GetPaymentsFields()
		{
			return array
			(
				'saleID' => 'saleID', 
				'saleDateTime' => 'paymentDateTime', 
				'saleMethod' => 'paymentMethod', 
				'salePaid' => 'paymentPaid', 
				'saleFee' => 'paymentFee',
			);
		}			
		
		function BuildInsertSQL($dbTable, $dbVals, $dbfields)
		{
			$sqlFields = 'INSERT INTO '.$dbTable.'(';
			$sqlValues = ' VALUES(';
			
			$comma = '';
			foreach ($dbVals as $fieldName => $fieldValue)
			{
				if (!isset($dbfields[$fieldName]))
					continue;
					
				$sqlFields .= $comma.$dbfields[$fieldName];
				$sqlValues .= $comma.'"'.self::_real_escape($fieldValue).'"';
				
				$comma = ', ';
			}
			$sqlFields .= ')';
			$sqlValues .= ')';
			
			return $sqlFields.$sqlValues;	
		}			
		
		function AddSale($saleDateTime = '', $salesVals = array())
		{			
			$fieldsList = $this->GetSalesFields();
			
			if (is_array($salesVals))
				$salesVals['saleDateTime'] = $saleDateTime;
			else
				$salesVals->saleDateTime = $saleDateTime;
			
			$sql = $this->BuildInsertSQL($this->DBTables->Sales, $salesVals, $fieldsList);
			$this->query($sql);
			$saleID = $this->GetInsertId();	
			
			// "Translation" of sales table fields to payment fields
			if (is_array($salesVals))
			{
				$salesVals['saleID'] = $saleID;
				$salePaid = $salesVals['salePaid'];
				$saleStatus = $salesVals['saleStatus'];
			}
			else
			{
				$salesVals->saleID = $saleID;
				$salePaid = $salesVals->salePaid;
				$saleStatus = $salesVals->saleStatus;
			}
			
			if (($salePaid != 0) && ($saleStatus != PAYMENT_API_SALESTATUS_RESERVED))
			{
				$fieldsList = $this->GetPaymentsFields();
		
				$sql = $this->BuildInsertSQL($this->DBTables->Payments, $salesVals, $fieldsList);
				$this->query($sql);
			}
	
			return $saleID;
		}			
		
		function SendSaleReport()
		{
		}
		
		function CompleteSale(&$results, $saleUpdateMode = self::STAGESHOWLIB_FROMCALLBACK, $CanClearURL = true)
		{
			$saleIsValid = true;
			
			if ($saleUpdateMode != self::STAGESHOWLIB_FROMCALLBACK)
			{
				$saleID = $results->saleID;
			}
			else
			{
				$saleID = $results['saleID'];
				$saleStatus = $this->GetSaleStatus($saleID);
				switch ($saleStatus)
				{
					case '':
					case PAYMENT_API_SALESTATUS_SUSPENDED:
						$this->saleError = 'Checkout Timed Out';
						return 0-$saleID;	// Error
						
					case PAYMENT_API_SALESTATUS_COMPLETED:
						$this->saleError = 'Sale Already Completed';
						return 0-$saleID;	// No valid sale
						
					default:
					case PAYMENT_API_SALESTATUS_CHECKOUT:
						break;
						
					case PAYMENT_API_SALESTATUS_TIMEOUT:
						// Lock the DB (prevents sale disappearing before it is checked)
						$this->LockSalesTable();
						
						// Get Sale Record
						$saleList = $this->GetSale($saleID);
						if (count($saleList) == 0) 
						{
							$this->UnLockTables();
							return 0;
						}
						
						// Check if Timed out sale items are still available
						foreach ($saleList as $saleItem)
						{
							$saleIsValid &= $this->CanReinstateSaleItem($saleItem);
						}
						
						if (!$saleIsValid)
						{
							// Sale is not valid - Update details but leave status unchanged
							if ($saleUpdateMode != self::STAGESHOWLIB_FROMCALLBACK)
							{
								$results->saleStatus = PAYMENT_API_SALESTATUS_SUSPENDED;
							}
							else
							{
								$results['saleStatus'] = PAYMENT_API_SALESTATUS_SUSPENDED;
							}							
						}
						break;
					}
			}
			
			if ($saleUpdateMode != self::STAGESHOWLIB_FROMCALLBACK)
			{
				if (isset($results->saleStatus) && ($results->saleStatus == PAYMENT_API_SALESTATUS_COMPLETED))
				{
					if ($CanClearURL) $results->saleCheckoutURL ="";
					$results->salePaidDateTime=current_time('mysql');
					// Add saleMethod --> paymentMethod
				}
			}
			else
			{
				if (isset($results['saleStatus']) && ($results['saleStatus'] == PAYMENT_API_SALESTATUS_COMPLETED))
				{
					if ($CanClearURL) $results['saleCheckoutURL'] ="";
					$results['salePaidDateTime'] = current_time('mysql');
				}
			}
			
			$fieldsList = $this->GetSalesFields();
			
			$fieldSep = 'UPDATE '.$this->DBTables->Sales.' SET ';	// MJS: Check SALES Table Fields
			
			$sql = '';
			foreach ($fieldsList as $fieldName)
			{
				if ($saleUpdateMode != self::STAGESHOWLIB_FROMCALLBACK)
				{
					if (!isset($results->$fieldName))
						continue;
					$fieldValue = self::_real_escape($results->$fieldName);
				}
				else
				{
					if (!isset($results[$fieldName]))
						continue;
					$fieldValue = self::_real_escape($results[$fieldName]);
				}
					
				$sql .= $fieldSep.$fieldName.'="'.$fieldValue.'"';
				$fieldSep = ' , ';
			}
			
			$sql .= ' WHERE '.$this->DBTables->Sales.'.saleID='.$saleID;;
			 
			$rtnVal = $this->query($sql);
			$queryResult = $this->queryResult;	
			if ($this->getDbgOption('Dev_ShowSQL'))
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<br>UpdateSale - query() Returned: $rtnVal<br>\n");
			}
			
			if ($saleUpdateMode == self::STAGESHOWLIB_FROMSAVEEDIT)
			{
				// Get Amount Paid so far
				$totalPaid = $this->GetTotalPayments($saleID);

				// Add Payment for difference
				$saleDateTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT);
				if (is_array($results))
				{
					$results['saleDateTime'] = $saleDateTime;
					$results['salePaid'] -= $totalPaid;
					$addPayment = ($results['salePaid'] != 0);
				}
				else
				{
					$results->saleDateTime = $saleDateTime;
					$results->salePaid -= $totalPaid;
					$addPayment = ($results->salePaid != 0);
				}					
			}
			else
			{
				$addPayment	= true;
			}
			
			if ($addPayment)
			{
				$fieldsList = $this->GetPaymentsFields();
		
				$sql = $this->BuildInsertSQL($this->DBTables->Payments, $results, $fieldsList);
				$this->query($sql);
			}
			
			if ($saleUpdateMode != self::STAGESHOWLIB_FROMTROLLEY)
			{
				$this->UnLockTables();
			}
			
			if (!$rtnVal)
				return 0;
			
			if ($queryResult == 0)
				return 0-$saleID;
				
			return $saleID;
		}
		
		function AddPayment($saleID, $paymentDateTime, $paymentPaid, $paymentMethod)
		{
			$results['saleID'] = $saleID;
			$results['saleDateTime'] = $paymentDateTime;
			$results['salePaid'] = $paymentPaid;
			$results['saleMethod'] = $paymentMethod;
			
			$fieldsList = $this->GetPaymentsFields();
	
			$sql = $this->BuildInsertSQL($this->DBTables->Payments, $results, $fieldsList);
			$this->query($sql);
		}

		function AddSaleItem($saleID, $stockID, $qty, $paid, $saleExtras = array())
		{
			$paid *= $qty;
			
			$sqlFields  = 'INSERT INTO '.$this->DBTables->Orders.'(saleID, '.$this->DBField('stockID').', '.$this->DBField('orderQty').', '.$this->DBField('orderPaid');
			$sqlValues  = ' VALUES('.$saleID.', '.$stockID.', "'.$qty.'", "'.$paid.'"';
			
			foreach ($saleExtras as $field => $value)
			{
				$sqlFields .= ','.$field;
				$sqlValues .= ', "'.$value.'"';
			}
			
			$sqlFields .= ')';
			$sqlValues .= ')';
			
			$sql = $sqlFields.$sqlValues;
			
			$this->query($sql);
			$orderID = $this->GetInsertId();
				
			return $orderID;
		}			

		function AddSaleFields(&$salesListArray)
		{
		}
		
		function AddSaleItemMeta($ticketID, $metaID, $metaValue)
		{
		}
		
		function GetSaleStatus($saleID)
		{
			$sql  = 'SELECT saleStatus FROM '.$this->DBTables->Sales;
			$sql .= ' WHERE '.$this->DBTables->Sales.".saleID=$saleID";
			$salesListArray = $this->get_results($sql);			
			if (count($salesListArray) < 1)
			{
				return '';
			}
			
			return $salesListArray[0]->saleStatus;
		}
		
		function GetSale($saleID, $sqlFilters = array())
		{
			$sqlFilters['saleID'] = $saleID;
			$sqlFilters['addPayments'] = true;
			return $this->GetSalesList($sqlFilters);
		}
				
		function TotalSaleQtyField()
		{
			return 'orderQty';
		}
		
		function GetSaleItemName($saleItem)
		{
			return $saleItem->stockName;
		}
		
		function GetSaleItemPrice($saleItem)
		{
			return $saleItem->orderPaid;
		}
		
		function GetSaleItemQty($saleItem)
		{
			return $saleItem->orderQty;
		}
		
		function PurgePendingSales($timeout = '')
		{
			if ($timeout == '')
				$timeout = 60*$this->adminOptions['CheckoutTimeout'];	// 1 hour default
				
			$limitDateTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, current_time( 'timestamp' ) - $timeout);
			$limitStatus = PAYMENT_API_SALESTATUS_CHECKOUT;

			$sql  = 'UPDATE '.$this->DBTables->Sales;
			$sql .= ' SET saleStatus="'.PAYMENT_API_SALESTATUS_TIMEOUT.'"';
			$sql .= ' WHERE '.$this->DBTables->Sales.'.saleStatus="'.$limitStatus.'"';
			$sql .= ' AND   '.$this->DBTables->Sales.'.saleDateTime < "'.$limitDateTime.'"';
			
			$this->query($sql);
			
			if (defined('STAGESHOWLIB_GATEWAYAPI_ABANDON_TIMEOUT')) $timeout = 60*STAGESHOWLIB_GATEWAYAPI_ABANDON_TIMEOUT;
			else $timeout = STAGESHOWLIB_GATEWAYAPI_ABANDON_TIMEOUT_DEFAULT;
						
			$limitDateTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, current_time( 'timestamp' ) - $timeout);
			$limitStatus = PAYMENT_API_SALESTATUS_TIMEOUT;
			
			$sql  = 'DELETE FROM '.$this->DBTables->Sales;
			$sql .= ' WHERE '.$this->DBTables->Sales.'.saleStatus="'.$limitStatus.'"';
			$sql .= ' AND   '.$this->DBTables->Sales.'.saleDateTime < "'.$limitDateTime.'"';
			
			$this->query($sql);
			
			$this->PurgeOrdersAndPayments();
		}
		
		function PurgeOrdersAndPayments()
		{
			$sql  = 'DELETE o FROM '.$this->DBTables->Orders.' o ';
			$sql .= 'LEFT OUTER JOIN '.$this->DBTables->Sales.' s ON o.saleID = s.saleID ';
			$sql .= 'WHERE s.saleStatus IS NULL';
			 
			$this->query($sql);
			 
			$sql  = 'DELETE p FROM '.$this->DBTables->Payments.' p ';
			$sql .= 'LEFT OUTER JOIN '.$this->DBTables->Sales.' s ON p.saleID = s.saleID ';
			$sql .= 'WHERE s.saleStatus IS NULL';
			 
			$this->query($sql);
		}
		
		function TotalOrdersField($sqlFilters = null)
		{
			$sql = '';
			
			// totalQty includes Pending sales (i.e. saleStatus=Checkout))
			$sql .= ' COALESCE(SUM('.$this->TotalSaleQtyField().')) AS saleQty ';
			
			return $sql;
		}

		function TotalSalesField($sqlFilters = null)
		{
			return '';
		}

		function GetSalesList($sqlFilters)
		{
			$selectFields  = $this->GetSalesQueryFields($sqlFilters);
			
			if (isset($sqlFilters['saleID']) || isset($sqlFilters['priceID']))
			{
				// Explicitly add joined fields from "base" tables (otherwise values will be NULL if there is no matching JOIN)
				$selectFields .= ', '.$this->DBTables->Sales.'.saleID';

				$joinCmd = ' LEFT JOIN ';
			}
			else
				$joinCmd = ' JOIN ';
				
			if (isset($sqlFilters['groupBy']))	
			{			
				$totalSalesField = $this->TotalSalesField($sqlFilters);
				if ($totalSalesField != '')
					$selectFields .= ','.$totalSalesField;
			}
						
			if (isset($sqlFilters['addPayments']))	
			{			
				$selectFields .= ', salePayments.*';
			}
			
			$sql  = 'SELECT '.$selectFields.' FROM '.$this->DBTables->Sales;	// MJS: Check SALES Table Fields

			$sql .= $this->GetJoinedTables($sqlFilters, __CLASS__);
			
			$sql .= $this->GetWhereSQL($sqlFilters);
			$sql .= $this->GetOptsSQL($sqlFilters);
			
			// Get results ... but suppress debug output until AddSaleFields has been called
			$salesListArray = $this->get_results($sql, false, $sqlFilters);			
			if (!isset($sqlFilters['addTicketFee']))
			{
				$this->AddSaleFields($salesListArray);				
			}
			
			$this->show_results($salesListArray);
					
			return $salesListArray;
		}			

		function DeleteOrders($saleID)
		{
			// Delete a show entry
			$sql  = 'DELETE FROM '.$this->DBTables->Orders;
			$sql .= ' WHERE '.$this->DBTables->Orders.".saleID=$saleID";
		 
			$this->query($sql);
		}
		
		function GetExtraDiscount($cartContents)
		{
			return 0;
		}
		
		function GetTransactionFee($cartContents)
		{
			return 0;
		}
		
		function AddSaleFromTrolley($saleID, $cartEntry, $saleExtras = array())
		{
			return $this->AddSaleItem($saleID, $cartEntry->itemID, $cartEntry->qty, $cartEntry->price, $saleExtras);
		}

		function OutputViewTicketButton($saleDetails = null)
		{
			$text = __('View EMail', 'stageshow');
			StageShowLibEscapingClass::Safe_EchoHTML($this->GetViewTicketLink($text, 'button-secondary stageshowlib-tools-ui', $saleDetails));
		}
		
		function GetViewTicketLink($text='', $class = '', $saleDetails = null)
		{
			$output = '';
			
			if (defined('STAGESHOWLIB_VIEWEMAIL_TARGET'))
			{
				$showEMailTarget = STAGESHOWLIB_VIEWEMAIL_TARGET;
				$showEMailURL = StageShowLibUtilsClass::GetCallbackURL($showEMailTarget);					
				$output .= $this->WPNonceField($showEMailTarget, 'ShowEMailNOnce', false);
												
				if ($saleDetails != null) 
				{
					$eventId = "\"stageshowlib_OpenTicketView('".$saleDetails->saleID."', '".$showEMailURL."', 'ShowEMailNOnce')\"";
				}
				else
				{
					$eventId = "\"stageshowlib_OnTicketButtonClick('".$showEMailURL."')\"";
				}
				
				$output .= "<a id=\"showemailbutton\" class=\"".$class."\" onclick=".$eventId.">".$text."</a>";
			}

			return $output;
		}
				
		function DBField($fieldName)
		{
			return $fieldName;
		}
    
		function GetOnlineStoreItemName($result)
		{
			return $result->stockName;
		}
			
	}
}

?>