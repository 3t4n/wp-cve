<?php
/* 
Description: Code for Data Export functionality
 
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

include STAGESHOW_INCLUDE_PATH.'stageshowlib_export.php';

if (!class_exists('StageShowTDTExportAdminClass')) 
{
	if (!defined('STAGESHOWLIB_MIMETYPE_CSV'))
		define('STAGESHOWLIB_MIMETYPE_CSV', 'text/tab-separated-values');
		
	if (!defined('STAGESHOWLIB_MIMETYPE_SAFARI_CSV'))
		define('STAGESHOWLIB_MIMETYPE_SAFARI_CSV', 'csv/tab-separated-values');
		
	class StageShowTDTExportAdminClass extends StageShowLibExportAdminClass  // Define class
	{
		function __construct($myDBaseObj) //constructor	
		{
			parent::__construct($myDBaseObj);
			
	  		// FUNCTIONALITY: Export - Settings, Tickets or Summary
			$perfID = 0;					
			$showID = StageShowLibUtilsClass::GetHTTPFloat('request', 'export_showid', 0);
			if ($showID != 0) 
			{
				$showAndperfID = StageShowLibUtilsClass::GetHTTPFloat('request', 'export_perfid', 0);
				if ($showAndperfID != 0)
				{
					$showAndperfIDParts = explode('.', $showAndperfID);
					$perfID = $showAndperfIDParts[1];
				}
			}
					
			if (StageShowLibUtilsClass::IsElementSet('post', 'downloadexport'))
			{
				if (StageShowLibUtilsClass::IsElementSet('post', 'download'))
				{
					$this->fieldNames = $this->GetFields();

					if (StageShowLibMigratePHPClass::Safe_strpos($_SERVER['HTTP_USER_AGENT'], 'Safari'))
					{
						$mimeType = STAGESHOWLIB_MIMETYPE_SAFARI_CSV;
					}					
					else
					{
						$mimeType = STAGESHOWLIB_MIMETYPE_CSV;
					}									

					switch ($_POST['export_type'])
					{          
						case 'settings':
								if (!current_user_can(STAGESHOWLIB_CAPABILITY_ADMINUSER)) die("Access Denied"); 
								$this->fileName = 'stageshow-settings';
								$this->output_downloadHeader($mimeType);
								$this->output_exportHeader();
								$this->export_shows();
								break;
			          
						case 'tickets':
								$this->fileName = 'stageshow-tickets';
								if (defined('STAGESHOW_EXPORT_TICKETS_FILEEXTN')) $this->fileExtn = STAGESHOW_EXPORT_TICKETS_FILEEXTN;
								$this->output_downloadHeader($mimeType);
								$this->output_exportHeader();
								$this->export_tickets($showID, $perfID);
								break;          

						case 'payments':
								$this->fileName = 'stageshow-payments';
								unset($this->fieldNames);
								if (defined('STAGESHOW_EXPORT_TICKETS_FILEEXTN')) $this->fileExtn = STAGESHOW_EXPORT_TICKETS_FILEEXTN;
								$this->output_downloadHeader($mimeType);
								$this->output_exportHeader();
								$this->export_payments($showID, $perfID);
								break;          

						case 'summary':
								$this->fileName = 'stageshow-summary';
								if (defined('STAGESHOW_EXPORT_SUMMARY_FILEEXTN')) $this->fileExtn = STAGESHOW_EXPORT_SUMMARY_FILEEXTN;
								$this->output_downloadHeader($mimeType);
								$this->output_exportHeader();
								$this->export_summary($showID, $perfID);
								break;								
					}
				}			       
			}
			else if ( StageShowLibUtilsClass::IsElementSet('post', 'downloadvalidator' ) )
			{
				$validatorFields = $this->GetValidatorFields();
				$this->fieldNames = $this->SelectFields($validatorFields);
	
				$this->fileName = 'stageshowValidator';
				$this->fileExtn = 'html';			
				
				$this->output_downloadHeader('text/html');
				$this->output_htmlhead();
				$this->export_validator($showID, $perfID);
				$this->output_endhtmlhead();
				$this->ouput_downloadFooter(true);
			}
			else
				die;
		}

		function GetValidatorFields()
		{	
			$fields = 'saleTxnId,saleStatus,saleFirstName,saleLastName,showName,perfDateTime,priceType,ticketQty,ticketPaid,ticketFee,ticketCharge,saleDateTime,perfRef,verifyLocation,verifyDateTime,priceNoOfSeats';
			
			//$fields .= ',priceNoOfSeats';

			$fields .= ',zoneRef,ticketSeat';
			
			return $fields;
		}



		function GetFields()
		{
			if (StageShowLibUtilsClass::IsElementSet('post', 'export_filter') && ($_POST['export_filter'] != '')) 
			{
				$pluginID = STAGESHOW_FOLDER;	
				$filterFile = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/exports/'.$_POST['export_filter'];
				
				$fieldNames = $this->LoadTSVFile($filterFile);
				return $fieldNames;
			}
					
			$fieldNames = array(
				'seatingID'         => '',
				'saleDiscountCode'   => __('Discount Code', 'stageshow'),
				'ticketSeat'         => __('Ticket Seat', 'stageshow'),
				'zoneRef'            => __('Zone', 'stageshow'),
			);
						
			$fieldNames = array_merge(array(			
				'perfOpens'          => __('Performance Opens', 'stageshow'),
				'perfExpires'        => __('Performance Expires', 'stageshow'),
				'perfNote'           => __('Performance Note', 'stageshow'),
				'perfNotePosn'       => __('Performance Note Position', 'stageshow'),
				'priceNoOfSeats'     => __('No Of Seats/Ticket', 'stageshow'),
				'saleExtraDiscount'  => __('Discount', 'stageshow'),
				'seatsQty'           => __('Seats Qty', 'stageshow'),
			), $fieldNames);
			
			$gatewayName = $this->myDBaseObj->gatewayObj->GetName();
			$fieldNames = array_merge(array(
				'perfDateTime'       => __('Performance Date & Time', 'stageshow'),
				'perfID'             => __('Performance ID', 'stageshow'),
				'perfRef'            => __('Performance Ref', 'stageshow'),
				'perfSeats'          => __('Performance Seats', 'stageshow'),
				'perfState'          => __('Performance State', 'stageshow'),
				'planID'             => __('Plan ID', 'stageshow'),
				'planRef'            => __('Plan Ref', 'stageshow'),
				'presetID'           => __('Preset ID', 'stageshow'),
				'priceID'            => __('Price ID', 'stageshow'),
				'priceType'          => __('Price Type', 'stageshow'),
				'priceValue'         => __('Price', 'stageshow'),
				'saleDateTime'       => __('Date & Time', 'stageshow'),
				'saleEMail'          => __('Sale EMail', 'stageshow'),
				'saleTransactionFee' => __('Booking Fee', 'stageshow'),
				'saleFee'            => $gatewayName.' '.__('Fees', 'stageshow'),
				'saleDonation'       => __('Donation', 'stageshow'),
				'salePostage'        => __('Postage', 'stageshow'),
				'saleID'             => __('Sale ID', 'stageshow'),
				'saleFirstName'      => __('First Name', 'stageshow'),
				'saleLastName'       => __('Last Name', 'stageshow'),
				'salePaid'           => __('Paid', 'stageshow'),
				'salePPCity'         => __('City', 'stageshow'),
				'salePPCountry'      => __('Country', 'stageshow'),
				'salePPName'         => __('Name', 'stageshow'),
				'salePPPhone'        => __('Phone', 'stageshow'),
				'salePPState'        => __('County', 'stageshow'),
				'salePPStreet'       => __('Street', 'stageshow'),
				'salePPZip'          => __('Postcode', 'stageshow'),
				'saleStatus'         => __('Sale Status', 'stageshow'),
				'saleTxnId'          => __('Sale Reference', 'stageshow'),
				'saleMethod'         => __('Sale Method', 'stageshow'),
				'saleNoteToSeller'   => __('Note To Seller', 'stageshow'),
				'showEMail'          => __('Show EMail', 'stageshow'),
				'showExpires'        => __('Show Expires', 'stageshow'),
				'showID'             => __('Show ID', 'stageshow'),
				'showName'           => __('Show Name', 'stageshow'),
				'showNote'           => __('Show Note', 'stageshow'),
				'showOpens'          => __('Show Opens', 'stageshow'),
				'showState'          => __('Show State', 'stageshow'),
				'ticketID'           => __('Ticket ID', 'stageshow'),
				'ticketCharge'       => __('Ticket Charge', 'stageshow'),
				'ticketDonation'     => __('Ticket Donation', 'stageshow'),
				'ticketFee'          => __('Ticket Fee', 'stageshow'),
				'ticketPaid'         => __('Ticket Paid', 'stageshow'),
				'ticketPostage'      => __('Ticket Postage', 'stageshow'),
				'ticketQty'          => __('Ticket Qty', 'stageshow'),
				'user_login'         => __('User Login', 'stageshow'),
				'verifyDateTime'     => __('Verify Date & Time', 'stageshow'),
				'verifyID'           => __('Verify ID', 'stageshow'),
				'verifyLocation'     => __('Verify Location', 'stageshow'),
			), $fieldNames);
						
			$customFieldsList = $this->myDBaseObj->GetSaleMetaFields();
			foreach ($customFieldsList as $customFieldEntry)
			{
				$customFieldId = $customFieldEntry->meta_key;
				$customFieldName = StageShowLibMigratePHPClass::Safe_str_replace("_", " ", $customFieldId);
				$fieldNames[$customFieldId] = $customFieldName;
			}
			
			return $fieldNames;
		}
		
		
			
		function GetValidatorTableFields()
		{			
			$tableFields = array(
				'ticketSeat' => 'seat',
			);
			
			$tableFields = array_merge(array (
				'showName' => 'show',
				'perfDateTime' => 'performance',
				'priceType' => 'type',
				'ticketQty' => 'qty',
			), $tableFields);

			return $tableFields;
		}
			

		function output_htmlhead()
		{
			StageShowLibEscapingClass::Safe_EchoHTML('<html>
<head>
<title>StageShow Validator</title>
<meta http-equiv="Content-Type" content="text/html;">
<style>

.table_verify td, .table_verify th 
{
	padding: 10px;
	text-align: left;
	vertical-align: top;
	
	border-bottom-color: #DFDFDF;
	border-top-color: #FFFFFF;
	
	background-color: #F9F9F9;
	XXXbackground-image: -moz-linear-gradient(center top , #F9F9F9, #ECECEC);
}	

td.col_show
{
	width: 400px;
}

td.col_performance
{
	width: 400px;
}

.table_verify th 
{
	border-top-radius: 20px;
	Xborder-top-left-radius: 3px;
	background-color: #F1F1F1;
	background-image: -moz-linear-gradient(center top , #F9F9F9, #ECECEC);
}

.table_verify td 
{
	border-style: solid;
	border-width: 0px 0px 1px 0px;
}

</style>
<script language="JavaScript">
<!-- Hide script from old browsers
');
		}
		
		function output_endhtmlhead()
		{
			StageShowLibEscapingClass::Safe_EchoScript('		
// End of Hide script from old browsers -->
</script>
</head>
');
		}
		
		function ouput_downloadFooter()
		{
			StageShowLibEscapingClass::Safe_EchoHTML('
<body>
<h2>'.__('Validate Sale', 'stageshow').'</h2>
<table class="stageshow-form-table">
	<tr>
		<th>'.__('Sale Reference', 'stageshow').'</th>
		<td>
			<input type="text" maxlength="20" size="20" name="TxnId" id="TxnId" value="" onkeypress="onKeyPress(event)" autocomplete="off" />
		</td>
	</tr>
</table>
<p>
<p class="submit">
<input class="button-secondary" type="button" name="validatesalebutton" onClick=onclickverify(this) value="'.__('Validate', 'stageshow').'"/>
<br>
<div id="VerifyResult" name="VerifyResult"></div>
</body>
</html>');
		}

		function output_exportHeader()
		{
		}

		function export_shows()
		{
			$this->exportDB($this->myDBaseObj->GetShowsSettings());
		}

		function export_tickets($showID, $perfID)
		{			
			if ($showID !=0)
				$sqlFilters['showID'] = $showID;
					
			if ($perfID !=0)
				$sqlFilters['perfID'] = $perfID;
				
			$sqlFilters['addTicketFee'] = true;
			
			$whereSQL  = '(saleStatus="'.PAYMENT_API_SALESTATUS_COMPLETED.'")';
			$whereSQL .= ' OR (saleStatus="'.PAYMENT_API_SALESTATUS_RESERVED.'")';			
			$sqlFilters['whereSQL'] = " ($whereSQL) ";
			
			$accumList = $this->myDBaseObj->GetSalesListWithDetails($sqlFilters);
			
			unset($this->fieldNames['ticketID']);
			$this->exportDB($accumList);
		}

		function export_payments($showID, $perfID)
		{			
			$sqlFilters = array();
			
			if ($showID !=0)
				$sqlFilters['showID'] = $showID;
					
			if ($perfID !=0)
				$sqlFilters['perfID'] = $perfID;
				
			$accumList = $this->myDBaseObj->GetPaymentsWithDetails($sqlFilters);
			
//			unset($this->fieldNames['ticketID']);
			$this->exportDB($accumList);
		}

		function export_validator($showID=0, $perfID=0)
		{			
			StageShowLibEscapingClass::Safe_EchoScript('var ticketDataList = new Array
(
');

			$whereSQL  = '(saleStatus="'.PAYMENT_API_SALESTATUS_COMPLETED.'")';
			$whereSQL .= ' OR (saleStatus="'.PAYMENT_API_SALESTATUS_RESERVED.'")';			
			$sqlFilters['whereSQL'] = " ($whereSQL) ";

			if ($showID !=0)
				$sqlFilters['showID'] = $showID;
					
			if ($perfID !=0)
				$sqlFilters['perfID'] = $perfID;
				
			$sqlFilters['addTicketFee'] = true;
			$this->exportDB($this->myDBaseObj->GetSalesListWithDetails($sqlFilters), true);
			
			$tableFields = $this->GetValidatorTableFields();
			
			StageShowLibEscapingClass::Safe_EchoScript('				
"");
			
var columnFields = new Array();
var verifysList = new Array();

window.onload = onPageLoad;

function onPageLoad(obj) 
{
	// Set initial focus to TxnId edit box
	var ourTxnIdObj = document.getElementById("TxnId");
	ourTxnIdObj.focus();
}
	
function onKeyPress(obj) 
{
	if (obj.keyCode == 13)
	{
		VerifyTxnId();
	}
}

function onclickverify(obj) 
{
	VerifyTxnId();
}
	
function LogVerified(index) 
{
	var VerifiesList = "";
	
	try
	{
		var timeNow = new Date();		
		if (typeof verifysList[index] === "undefined")	
		{
			verifysList[index] = "";
		}
			
		VerifiesList = verifysList[index];
		verifysList[index] += timeNow.toLocaleString() + "<br> ";	
	}
	catch (err)
	{
	}
	
	return VerifiesList;
		
}
	
function VerifyTxnId() 
{
	var ourTxnIdObj = document.getElementById("TxnId");
	var ourTxnId = ourTxnIdObj.value.StageShowLibMigratePHPClass::Safe_trim();
	var matchedLines = 0;
	var verifyResult = "";
	
	//alert("Verifying - TxnId:" + ourTxnId);
	
	for (var index = 0; index < ticketDataList.length; index++)
	{
		var nextLine = ticketDataList[index];
		ticketDataArray = nextLine.split("\t");
		if (index == 0) 
		{
			// First line ... just index the column field IDs
			for (var fieldNo = 0; fieldNo < ticketDataArray.length; fieldNo++)
			{
				fieldId = ticketDataArray[fieldNo];
				columnFields[fieldId] = fieldNo;
			}
		}
		else
		{
			var thisTxnId = ticketDataArray[columnFields["saleTxnId"]];
			if (thisTxnId != ourTxnId)
				continue;
			matchedLines++;
			
			if (matchedLines == 1)
			{
				verifyHistory = LogVerified(ourTxnId);
				if (verifyHistory !== "")
				{
					verifyResult += "<h3>'.__('History', 'stageshow').':</h3>";		
					verifyResult += verifyHistory;		
					verifyResult += "<br>";		
				}		
			
				verifyResult += "<h3>'.__('Sale Details', 'stageshow').':</h3>";		
				verifyResult += "<table>";
				
				verifyResult += "<tr><td>'.__("TxnId", 'stageshow').':</td><td>" + ourTxnId + "</td></tr>\n"; 
				verifyResult += "<tr><td>'.__("Name", 'stageshow').':</td><td>" + ticketDataArray[columnFields["saleFirstName"]] + " " + ticketDataArray[columnFields["saleLastName"]] + "</td></tr>\n"; 
				verifyResult += "<tr><td>'.__("Date & Time", 'stageshow').':</td><td>" + ticketDataArray[columnFields["saleDateTime"]] + "</td></tr>\n"; 
				
				verifyResult += "</table><br><table class=table_verify>\n"; 
				
				verifyResult += "<tr>"; 
');			
			foreach ($tableFields as $tableField => $tableClass)
			{
				$colClass = 'col_'.$tableClass;
				$colTitle = $this->fieldNames[$tableField];
				StageShowLibEscapingClass::Safe_EchoScript('
				verifyResult += "<th class='.$colClass.'>'.__($colTitle, 'stageshow').'</th>");');
			}
			StageShowLibEscapingClass::Safe_EchoScript('			 				
				verifyResult += "</tr>\n"; 
			}
			
			verifyResult += "<tr>"; 
');	// End of HTML
			foreach ($tableFields as $tableField => $tableClass)
			{
				$colClass = 'ticket_'.$tableClass;
				$colTitle = $this->fieldNames[$tableField];
				StageShowLibEscapingClass::Safe_EchoScript('
			verifyResult += "<td class='.$colClass.'>" + ticketDataArray[columnFields["'.$tableField.'"]] + "</td>"; ');
			}
StageShowLibEscapingClass::Safe_EchoScript('				 				
			verifyResult += "</tr>\n"); 
		}
	}
	
	if (matchedLines > 0)
	{
		ourTxnIdObj.value = "";
		verifyResult += "<table>";
	}
	else
	{
		ourTxnIdObj.select();
		verifyResult += "'.__("No matching record found", 'stageshow').'<br>";
	}
		
	document.getElementById("VerifyResult").innerHTML = verifyResult;
	
	// Set focus to TxnId edit box
	ourTxnIdObj.focus();
}
');		
		}

		function export_summary($showID=0, $perfID=0)
		{
			$accumList = array();
			
			// Get All Sales - Sort by Show Name, then Performance Date/Time, then by Performance ID, then by Buyer Name, then Sale EMail

			// Get list of ticket types for all shows
			$typesList = $this->myDBaseObj->GetAllTicketTypes();
			
			// Add ticket name to array created by GetFields()
			$this->fieldNames = array_merge($this->fieldNames, array('ticketName' => __('Ticket Name', 'stageshow')));
			
			// Add custom ticket type name to array created by GetFields()
			foreach ($typesList as $typeRec)	
			{								
				$typeName = $typeRec->priceType;
				$this->fieldNames = array_merge($this->fieldNames, array($typeName => $typeName));
			}
			
			$showLists = $this->myDBaseObj->GetAllShowsList($showID);
			foreach ($showLists as $showEntry)
			{
				if (($showID !=0) && ($showEntry->showID != $showID))
					continue;					
					
				$perfsLists = $this->myDBaseObj->GetPerformancesDetailsByShowID($showEntry->showID);
				foreach ($perfsLists as $perfsList)
				{
					if (($perfID !=0) && ($perfsList->perfID != $perfID))
						continue;					

					// Get all sales for this performance
					$salesList = $this->myDBaseObj->GetTicketsListByPerfID($perfsList->perfID);
					$lastSaleID = 0;

					foreach ($salesList as $thisSale)
					{
						$ticketType = $thisSale->ticketType;

						if ($lastSaleID == $thisSale->saleID)
						{
							$lastSaleIndex = count($accumList) - 1;					
							$saleRec = $accumList[$lastSaleIndex];
							$saleRec->ticketQty += $thisSale->ticketQty;
							$saleRec->$ticketType += $thisSale->ticketQty;
							
							$accumList[$lastSaleIndex] = $saleRec;
						}
						else
						{
							$saleRec = new stdClass();
							
							foreach ($thisSale as $key => $value)
							{
								$saleRec->$key = $value;
							}

							$saleRec->seatsQty = 0;
							
							foreach ($typesList as $typeRec)
							{								
								$typeName = $typeRec->priceType;
								$saleRec->$typeName = 0;
							}
								
							$saleRec->$ticketType += $thisSale->ticketQty;
							
							$noOfSales = count($accumList);
							$accumList[$noOfSales] = $saleRec;
							
//							unset($saleRec);
						}
						
						if (!isset($thisSale->priceNoOfSeats))
						{
							$thisSale->priceNoOfSeats = 1;
						}
						$saleRec->seatsQty += ($thisSale->priceNoOfSeats * $thisSale->ticketQty);
						
						$lastSaleID = $thisSale->saleID;
					}
					
					unset($salesList);
				}
			}

			$this->exportDB($accumList);
		}
		
		function LoadTSVFile($tsvFilePath)
		{
			$tsvArray = array();
			
			$tsvText = $this->myDBaseObj->ReadTemplateFile($tsvFilePath);
			$tsvLines = preg_split('/\r\n|\r|\n/', $tsvText);
			$rowNo = 1;
			
			foreach ($tsvLines as $lineData)
			{
				// Ignore blank lines
				if (StageShowLibMigratePHPClass::Safe_trim($lineData) == '') continue;
				
				$lineFields = explode("\t", $lineData);
				if (count($lineFields) < 2) continue;
				
				$key = $lineFields[0];
				$tsvArray[$key] = $lineFields[1];
			}

			return $tsvArray;			
		}
		

		function DecodeField($fieldID, $fieldVal, $dbEntry)
		{
			if ($fieldID == 'ticketSeat')
			{
				$seatingID = isset($dbEntry->perfSeatingID) ? $dbEntry->perfSeatingID : $dbEntry->seatingID;
				$fieldVal = StageShowZonesDBaseClass::DecodeSeatsList($this->myDBaseObj,  $fieldVal, $seatingID);
			}
				
			return $fieldVal;
		}
			
	}
		
	class StageShowTSVExportAdminClass extends StageShowTDTExportAdminClass  // Define class
	{
		function __construct($myDBaseObj) //constructor	
		{
			$this->fileExtn = 'tsv';
			parent::__construct($myDBaseObj);
		}
		
		function output_exportHeader()
		{
			$this->exportData .= "sep=\t\n";			
		}

	}
}






