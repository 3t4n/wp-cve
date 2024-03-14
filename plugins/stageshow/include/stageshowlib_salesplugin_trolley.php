<?php
/*
Description: Core Library Generic Base Class for Sales Plugins

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

if (!class_exists('StageShowLibSalesCartPluginBaseClass')) 
{
	include 'stageshowlib_plugin.php';
	include 'stageshowlib_httpio.php';
	
	if (!defined('STAGESHOWLIB_STATE_DOWNLOAD'))
	{
		define('STAGESHOWLIB_STATE_DOWNLOAD',  'Download');
		define('STAGESHOWLIB_STATE_POST',      'Post');
		define('STAGESHOWLIB_STATE_DELETED',   'deleted');
		define('STAGESHOWLIB_STATE_DISCARDED', 'discarded');
	}
			
	if (!defined('STAGESHOWLIB_MAXSALECOUNT'))
	{
		define('STAGESHOWLIB_MAXSALECOUNT', 4);
	}
	
	if (!defined('STAGESHOWLIB_NOTETOSELLER_ROWS'))
	{
		define('STAGESHOWLIB_NOTETOSELLER_ROWS', 2);
	}
	
	if (!defined('STAGESHOWLIB_TROLLEYTIMEOUT'))
	{
		define('STAGESHOWLIB_TROLLEYTIMEOUT', 30*60);
	}
	
	if (!defined('STAGESHOWLIB_PAYMENT_METHODS'))
	{
		define('STAGESHOWLIB_PAYMENT_METHODS', __('/Cash/Cheque/Credit Card/Debit Card/Voucher'));
	}
	
	if (!defined('STAGESHOWLIB_SENDEMAIL_TARGET'))
	{
		define('STAGESHOWLIB_SENDEMAIL_TARGET', 'stageshowlib_jquery_email.php');
	}
	
	if (!defined('STAGESHOWLIB_RESERVATION_EMAIL_MINLEN'))
	{
		define('STAGESHOWLIB_RESERVATION_EMAIL_MINLEN', 3);				
	}
	
	if (!defined('STAGESHOWLIB_RESERVATION_PHONE_MINLEN'))
	{
		define('STAGESHOWLIB_RESERVATION_PHONE_MINLEN', 8);				
	}
	
	if (!defined('STAGESHOWLIB_RESERVATION_COUNTRY_MINLEN'))
	{
		define('STAGESHOWLIB_RESERVATION_COUNTRY_MINLEN', 2);				
	}
	
	if (!defined('STAGESHOWLIB_RESERVATION_STATE_MINLEN'))
	{
		define('STAGESHOWLIB_RESERVATION_STATE_MINLEN', 2);				
	}
	
	if (!defined('STAGESHOWLIB_RESERVATION_DEFAULT_MINLEN'))
	{
		define('STAGESHOWLIB_RESERVATION_DEFAULT_MINLEN', 3);	
	}
	
	class StageShowLibSalesCartPluginBaseClass extends StageShowLibPluginClass
	{
		const PAGEMODE_NORMAL = 'normal';
		const ANCHOR_PREFIX = 'Anchor_';
		
		var $lastItemID = '';
		var $pageMode = self::PAGEMODE_NORMAL;
		var $adminPageActive = false;
		

		var $cssDomain;
		var $cssBaseID;
		var $colID;
		var $cssColID;
		var $cssTrolleyBaseID;
		var $trolleyid;
		var $shortcode;
		var $hasAddButtonPerPrice = true;
		var $trolleyHeaderCols;
		
		var $checkoutMsg;
		var $checkoutMsgClass;
		
		var $editpage;
		var $cart_ReadOnly;
		var $saleConfirmationMode = false;
		
		var $boxofficeContent = '';
		var $storeRows = 0;
		
		var $doneSalesTrolley = false;
			
		function __construct()
		{
			$myDBaseObj = $this->myDBaseObj;
		
			if (!isset($this->cssBaseID)) $this->cssBaseID = 'stageshow'.'-shop';
			if (!isset($this->cssTrolleyBaseID)) $this->cssTrolleyBaseID = 'stageshow'.'-trolley';
			
			if (!isset($this->colID)) 
			{
				$this->colID['name'] = __('Name', 'stageshow');
				$this->cssColID['name'] = "name";			
				$this->colID['datetime'] = __('Date & Time', 'stageshow');
				$this->cssColID['datetime'] = "datetime";			
				$this->colID['ref'] = __('Ref', 'stageshow');
				$this->cssColID['ref'] = "ref";

				$this->colID['price'] = __('Price', 'stageshow');
				$this->cssColID['price'] = "price";
				$this->colID['qty'] = __('Quantity', 'stageshow');
				$this->cssColID['qty'] = "qty";
			}
				
			if (!isset($this->trolleyid)) 
			{
				if (defined('STAGESHOWLIB_TROLLEYID'))
					$this->trolleyid = STAGESHOWLIB_TROLLEYID.'_cart_obj';
				else
					$this->trolleyid = 'stageshow'.'_cart_obj';
					
			}
			$this->liveTrolleyid = $this->trolleyid;
			
			if (!isset($this->shortcode)) $this->shortcode = 'stageshow'.'-store';
		}
		
		function Reset()
		{
			$this->doneSalesTrolley = false;
		}
		
		function CheckAdminReferer($referer = '')
		{
		}
		
		function InjectJSCode($jsCode, $echoRslt=true)
		{
			if (!$this->myDBaseObj->isDbgOptionSet('Dev_AllowMultilineJS'))
			{
				// Split into lines ...
				$jsLines = explode("\n", $jsCode);
				$jsCode = '';
				foreach ($jsLines as $jsLine)
				{
					// Output the line without whitespace or the CR
					$jsCode .= StageShowLibMigratePHPClass::Safe_trim($jsLine);
				}
				
				$jsCode .= "\n";
			}
			
			if ($echoRslt) StageShowLibEscapingClass::Safe_EchoScript($jsCode);
			return $jsCode;
		}
		
		function Cart_OutputContent_OnlineStoreMain($atts)
		{
			static $doneJS = false;
			
			// Deal with sale editor pages
			if ($this->adminPageActive)
			{
				if ($this->myDBaseObj->IsButtonClicked('editbuyer'))	// editing sale - get buyer details
				{
					if (!$this->myDBaseObj->CanEditSales())
						return;
					
					// Output Buyer Details Form
					$cartContents = $this->GetTrolleyContents();
			
					$this->OnlineStore_AddCustomFieldValues($cartContents);
					
					// Get $cartContents->salePostTickets = **** etc.
					$this->OnlineStore_AddTrolleySuplementaryInputs($cartContents);

					$saleId = StageShowLibHTTPIO::GetRequestedInt('id', 0);
					StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" name="id" value="'.$saleId.'"/>'."\n");
/*					
					$saleCustomValues = StageShowLibHTTPIO::GetRequestedString('saleCustomValues');
					StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" id="saleCustomValues" name="saleCustomValues" value="'.$saleCustomValues.'"/>'."\n");
*/					
					$this->OutputContent_OnlinePurchaserDetails($cartContents);
					return;
				}
				
				if ($this->myDBaseObj->IsButtonClicked('savesaleedit'))
					return;
			}
			
			if (!$doneJS)
			{
				$scriptCode  = "<script>\n";
				if ( current_user_can(STAGESHOWLIB_CAPABILITY_SETUPUSER)
				 && !defined('STAGESHOWLIB_CACHEDETECT_DISABLE') )
				{
					$scriptCode .= 'pageGeneratedServerTime = '.time().";\n";
				}
				$scriptCode .= '
var StageShowLib_CachedPageTimeDelta  = '.STAGESHOWLIB_CACHEDPAGE_TIMEDELTA.';
StageShowLib_addWindowsLoadHandler(StageShowLib_OnLoadOnlineStore); 
</script>
				';
				
				$this->InjectJSCode($scriptCode);			
				$doneJS = true;	
			}
      		
      		// Get all database entries for this item ... ordered by date/time then ticket type
	      	$results = $this->GetOnlineStoreProducts($atts);
			$this->OutputContent_OnlineStoreSection($results);
		}

		function OutputContent_PurchaseMethodSelector($selectId = 'saleMethod', $reqdClass = '', $selectedMethod = '')
		{
			$methodsSeparator = StageShowLibMigratePHPClass::Safe_substr(STAGESHOWLIB_PAYMENT_METHODS, 0, 1);
			$methodsList = explode($methodsSeparator, STAGESHOWLIB_PAYMENT_METHODS);
			/* First Entry will be blank - Overwrite with the Payment Gateway name */
			$methodsList[0] = $this->myDBaseObj->gatewayObj->GetName();;
			$methodsList[] = '';
			
			$selectHTML =  '
				<select class="'.$reqdClass.'" id="'.$selectId.'" name="'.$selectId.'">';
				
			foreach ($methodsList as $methodId)
			{
				$isSelected = ($selectedMethod == $methodId) ? 'selected=true ' : '';
				$selectHTML .=  '
					<option value="'.$methodId.'" '.$isSelected.'>'.$methodId.'&nbsp;</option>';
			}
					
			$selectHTML .=  '
				</select>
				';
				
			return $selectHTML;
					
		}

		function OutputContent_OnlinePurchaserDetails($cartContents, $extraHTML = '')
		{
			$paramIDs = array(
				'saleEMail'        => __('EMail', 'stageshow').' *',
				'saleFirstName'    => __('First Name', 'stageshow').' *',
				'saleLastName'     => __('Last Name', 'stageshow').' *',
				'salePPStreet'     => __('Street', 'stageshow'),
				'salePPCity'       => __('City', 'stageshow'),
				'salePPState'      => __('County', 'stageshow'),
				'salePPZip'        => __('Postcode', 'stageshow'),
				'salePPCountry'    => __('Country', 'stageshow'),
				'salePPPhone'      => __('Phone', 'stageshow'),
				'saleNoteToSeller' => __('Note To Seller', 'stageshow'),
				);
			
			$formHTML  = ''; 
		
			$formHTML .= '<div style="padding: 0px 0px 20px 0px;" class="'.$this->cssBaseID.'-purchaserdetails">'."\n";			
			$formHTML .= "<h2>".__("Purchaser Details", 'stageshow').":</h2>\n"; 
			if ($cartContents != NULL) $formHTML .= '<form method="post">'."\n";						
			$formHTML .= $this->GetParamAsHiddenTag('id');
			$formHTML .= "<table>\n";			

			$reqdClass = $this->cssBaseID.'_customco';

			// Output all Payment Gateway tags as edit boxes
			foreach ($paramIDs as $paramID => $paramLabel)
			{
				$paramValue = isset($cartContents->$paramID) ? $cartContents->$paramID : '';
				$classNames = (StageShowLibMigratePHPClass::Safe_strpos($paramLabel,'*') !== false) ? $reqdClass : '';
				if ($paramID == 'saleNoteToSeller')
					$inputTag = '<textarea class="'.$classNames.'" name="'.$paramID.'" id="'.$paramID.'" rows="4" cols="60" >'.$paramValue.'</textarea>';
				else
					$inputTag = '<input class="'.$classNames.'" name="'.$paramID.'" id="'.$paramID.'" type="text" maxlength="50" size="50" value="'.$paramValue.'" />';
					
				$formHTML .=  '
				<tr class="'.$this->cssBaseID.'-formRow">
					<td class="'.$this->cssBaseID.'-formFieldID">'.$paramLabel.'</td>
					<td class="'.$this->cssBaseID.'-formFieldValue" colspan="2">
					'.$inputTag.'
					</td>
				</tr>
			';
			}
			
			$formHTML .=  '
				<tr class="'.$this->cssBaseID.'-formRow">
					<td class="'.$this->cssBaseID.'-formFieldID">'.__('Payment Method', 'stageshow').' *</td>
					<td class="'.$this->cssBaseID.'-formFieldValue" colspan="2">
				';

			$selectedMethod = isset($cartContents->saleMethod) ? $cartContents->saleMethod : '';
		
			$formHTML .= $this->OutputContent_PurchaseMethodSelector('saleMethod', $reqdClass, $selectedMethod);
			
			$formHTML .=  '
					</td>
				</tr>
				';
				
			if ($extraHTML == '')
			{
				$formHTML .= '
				<input class="'.$reqdClass.'" type="hidden" id="saleStatus" name="saleStatus" value="'.PAYMENT_API_SALESTATUS_COMPLETED.'"/>
				';
			}
			else
			{
				$formHTML .= $extraHTML;				
			}
			
			$saveCaption = __('Save', 'stageshow');
			$buttonID = $this->myDBaseObj->GetButtonID('savesaleedit');
			
			$buttonClassdef = ($this->adminPageActive) ? 'class="button-primary " ' : 'class="xx" ';
			
			$formHTML .=  '
				<tr class="'.$this->cssBaseID.'-formRow">
					<td colspan="2" class="'.$this->cssBaseID.'-savesale">
						<input name="'.$buttonID.'" '.$buttonClassdef.'id="'.$buttonID.'" type="submit" value="'.$saveCaption.'" />
					</td>
				</tr>
			';
			
			$formHTML .= "</table>\n";			
			if ($cartContents != NULL) $formHTML .= "</form>\n";			
			$formHTML .= "<div>\n";			
			
			StageShowLibEscapingClass::Safe_EchoHTML($formHTML);
			return $formHTML;
		}
					
		function OnlineStoreSaveEdit()
		{
			if (isset($this->checkoutMsg) && ($this->checkoutMsg != '')) return 0;
			
			$myDBaseObj = $this->myDBaseObj;
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'id') && is_numeric($_POST['id']) && ($_POST['id']>0))
			{
				// Get Current DB Entry
				$saleID = StageShowLibHTTPIO::GetRequestedInt('id');
				$saleEntries = $myDBaseObj->GetSale($saleID);	
				if (count($saleEntries) > 0) 
				{
					$lastSaleStatus = $saleEntries[0]->saleStatus;
					switch ($lastSaleStatus)
					{
						case PAYMENT_API_SALESTATUS_TIMEOUT:
						case PAYMENT_API_SALESTATUS_SUSPENDED:
							// Ignore recorded sale entries for Suspended Sale
							$saleEntries = array();
							break;
							
						default:
							break;
					}
				}			
			}
			else
			{
				$saleID = 0;
				$saleEntries = array();
			}
//StageShowLibEscapingClass::Safe_EchoHTML("<br> -- saleID=$saleID --<br><br>");
			
			// Scan Trolley Contents
			$cartContents = $this->GetTrolleyContents();
			
			// Lock Sales Database 
			$myDBaseObj->LockSalesTable();
			
			$itemsOK = true;
			foreach ($cartContents->rows as $cartIndex => $cartEntry)
			{
				$itemValid = $this->IsOnlineStoreItemValid($cartContents->rows[$cartIndex], $saleEntries);
				$itemsOK &= $itemValid;
//StageShowLibEscapingClass::Safe_EchoHTML("<br>itemsOK=$itemsOK<br><br>");
			}
			
			if (!$itemsOK)
			{
				$this->SaveTrolleyContents($cartContents, __LINE__);
			}

			if ($itemsOK)
			{
				$runningTotal = 0;		
							
				foreach ($cartContents->rows as $cartEntry)
				{
					$runningTotal += ($cartEntry->price * $cartEntry->qty);
				}
					
				if (isset($cartContents->salePostTickets) && $cartContents->salePostTickets)
				{
					$cartContents->salePostage = $myDBaseObj->getOption('PostageFee');
					$runningTotal += $cartContents->salePostage;
				}
				else
					$cartContents->salePostage = 0;
				
				if (isset($cartContents->saleDonation))
				{
					$runningTotal += $cartContents->saleDonation;
				}
				
				if (isset($cartContents->saleExtraDiscount))
				{
					$runningTotal -= $cartContents->saleExtraDiscount;
				}
				
				if (isset($cartContents->saleTransactionFee))
				{
					$runningTotal += $cartContents->saleTransactionFee;
				}
				
				$cartContents->salePaid = $runningTotal;				
					
				if ($saleID == 0)
				{
					// Add a new Sale
					$saleDateTime = current_time('mysql'); 
									
					$cartContents->saleTxnId = 'MAN-'.time();				
					$cartContents->salePPName = StageShowLibMigratePHPClass::Safe_trim($cartContents->saleFirstName.' '.$cartContents->saleLastName);
					$cartContents->saleFee = 0.0;
					
					global $current_user;
					if (is_user_logged_in())
					{
						wp_get_current_user();
						$cartContents->user_login = $current_user->user_login;
					}		
					
					$saleID = $myDBaseObj->AddSale($saleDateTime, $cartContents);
				}
				else
				{
					// Calculate new sale total
					// Update Sale
					if (isset($lastSaleStatus) && ($lastSaleStatus == $cartContents->saleStatus))
					{
						unset($cartContents->saleStatus);
					}
					$saleID = $myDBaseObj->CompleteSale($cartContents, StageShowLibSalesCartDBaseClass::STAGESHOWLIB_FROMSAVEEDIT);
					$saleID = abs($saleID);		// Returned value will be negative if nothing is changed so make it positive
				}
				$this->ClearTrolleyContents();
				
				// Delete Existing Tickets and Add New Ones
				$myDBaseObj->DeleteOrders($saleID);
				
				foreach ($cartContents->rows as $cartEntry)
				{
					$myDBaseObj->AddSaleFromTrolley($saleID, $cartEntry);					
				}
				//DELETE_AND_REPLACE_TICKETS = UNDEFINED_AS_YET;
			}
			else if (isset($this->checkoutMsg))
			{
/*
				if (!isset($this->checkoutMsgClass))
				{
					$this->checkoutMsgClass = 'stageshow'.'-error error';
				}
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="'.$this->checkoutMsgClass.'">'.$this->checkoutMsg.'</div>');
*/
				$saleID = 0;
			}
				
			// Release Tables
			$this->myDBaseObj->UnLockTables();

			return $saleID;
		}
		
		function IsOnlineStoreItemEnabled($result)
		{
			return true;
		}
		
		function IsOnlineStoreItemValid(&$cartEntry, $saleEntries)
		{
			return true;
		}
		
		function GetOnlineStoreProductDetails($reqRecordId)
		{
			return $this->myDBaseObj->GetStockItem($reqRecordId);
		}
		
		function GetOnlineStoreProducts($atts)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$reqRecordId = StageShowLibMigratePHPClass::Safe_htmlspecialchars_decode($atts['id']);
			$storeProducts = ($reqRecordId == '') ? $myDBaseObj->GetPricesList(null) :  $this->GetOnlineStoreProductDetails($reqRecordId);
			
			$activeProducts = array();
			
			foreach ($storeProducts as $storeProduct)
			{
				if ($storeProduct->stockState == STAGESHOWLIB_STATE_INACTIVE)
					continue;
				
				$activeProducts[] = $storeProduct;
			}
			
			return $activeProducts;
		}
		
		function GetOnlineStoreGroupID($result)
		{
			return $this->GetOnlineStoreItemID($result);
		}
			
		function GetOnlineStoreStockID($result)
		{
			return $this->GetOnlineStoreItemID($result);
		}
			
		function GetOnlineStoreInventoryID($result)
		{
			return $this->GetOnlineStoreStockID($result);
		}
			
		function GetOnlineStoreItemID($result)
		{
			if (!isset($result->stockID)) return 0;
			
			return $result->stockID;
		}
			
		function GetOnlineStoreItemPrice($result)
		{
			return $result->stockPrice + $result->stockPostage;
		}
			
		function GetOnlineStoreItemsAvailable($result)
		{
			return -1;
		}
			
		function GetOnlineStoreItemNote($result, $posn)
		{
			return '';
		}

		function GetOnlineStoreElemTagId($id, $result)
		{
			$itemID = $this->GetOnlineStoreItemID($result);	
			$id .= '_' . $itemID;
			return $id;
		}

		function OutputContent_OnlineStoreTitle($result)
		{
		}
			
		function OutputContent_OnlineStoreHeader($result)
		{
			StageShowLibEscapingClass::Safe_EchoHTML('
				<table class="'.$this->cssBaseID.'-table" width="100%" border="0">
					<tr>
						<td class="'.$this->cssBaseID.'-header">
							<table width="100%" cellspacing="0">
								<tr>
									<td class="'.$this->cssBaseID.'-'.$this->cssColID['name'].'">'.$this->colID['name'].'</td>
									<td class="'.$this->cssBaseID.'-'.$this->cssColID['ref'].'">'.$this->colID['ref'].'</td>
									<td class="'.$this->cssBaseID.'-'.$this->cssColID['price'].'">'.$this->colID['price'].'</td>
									<td class="'.$this->cssBaseID.'-'.$this->cssColID['qty'].'">'.$this->colID['qty'].'</td>
									<td class="'.$this->cssBaseID.'-add">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
				');
		}		
				
		function OutputContent_OnlineStoreRow($result)
		{
			$storeRowHTML = '';
			$myDBaseObj = $this->myDBaseObj;
			
			$buttonURL = $myDBaseObj->getImageURL('AddCartButtonURL');
	
			$itemPrice = $myDBaseObj->FormatCurrency($this->GetOnlineStoreItemPrice($result));
			
			$stockDetails = isset($result->stockDetails) ? $result->stockDetails : '';
			$addColSpan = ($stockDetails != '') ? ' rowspan="2" ' : '';
			
			$storeRowHTML .= '
				<table width="100%" cellspacing="0">
					<tr>
						<td class="'.$this->cssBaseID.'-'.$this->cssColID['name'].'">'.$result->stockName.'</td>
						<td class="'.$this->cssBaseID.'-'.$this->cssColID['ref'].'">'.$result->stockRef.'</td>
						<td class="'.$this->cssBaseID.'-price">'.$itemPrice.'</td>
						<td class="'.$this->cssBaseID.'-qty">
				';
				
				switch ($result->stockType)
				{
					case STAGESHOWLIB_STATE_POST:
						$quantityTagId = $this->GetOnlineStoreElemTagId('quantity', $result); 
						$storeRowHTML .= '
								<select class="'.$this->cssTrolleyBaseID.'-ui" name="'.$quantityTagId.'" id="'.$quantityTagId.'">
									<option value="1" selected="">1</option>
						';
						for ($no=2; $no<=STAGESHOWLIB_MAXSALECOUNT; $no++)
							$storeRowHTML .= '<option value="'.$no.'">'.$no.'</option>'."\n";
						$storeRowHTML .= '
								</select>
							</td>
						';
						break;
						
					case STAGESHOWLIB_STATE_DOWNLOAD:
					default:
						$quantityTagId = $this->GetOnlineStoreElemTagId('quantity', $result); 
						$storeRowHTML .= '<input type="hidden" class="'.$this->cssTrolleyBaseID.'-ui" name="'.$quantityTagId.'" id="'.$quantityTagId.'" value="1"/>1'."\n";
						break;
				}
				
			$buttonTag = ''; // ($buttonURL != '') ? ' src="'.$buttonURL.'"' : '';
			
			$submitButton = __('Add', 'stageshow');
			$submitId = $this->GetOnlineStoreElemTagId('AddItemButton', $result);

			$buttonClasses = '';						
			if ($this->adminPageActive) $buttonClasses .= ' button-secondary';
				
			$buttonClassdef = $this->GetButtonTextAndTypeDef($submitButton, 'add', $submitId, '', $buttonClasses);
			
			$storeRowHTML .= '
				<td '.$addColSpan.'class="'.$this->cssBaseID.'-add">
					<input '.$buttonClassdef.' '.$buttonTag.'/>
				</td>
				</tr>				
				';
				
			if ($stockDetails != '') $storeRowHTML .= '
					<tr>
						<td colspan="4" class="'.$this->cssBaseID.'-details">'.$result->stockDetails.'</td>
					</tr>				
				';
				
			$storeRowHTML .= "</table>\n";			
			return $storeRowHTML;
		}
		
		function Cart_OutputContent_GetAtts( $atts )
		{
			$atts = shortcode_atts(array(
				'id'    => '',
				'count' => '',
				'anchor' => '',
				'style' => 'normal' 
			), $atts );
        
        	return $atts;
		}
		
		function Cart_OutputContent_Anchor( $anchor )
		{
			$anchor = self::ANCHOR_PREFIX.$anchor;
			return '<a name="'.$anchor.'" id="'.$anchor.'"></a>';	
		}
		
		function GetBoxOfficeRowClass($result)
		{
			static $oddPage = true;
			
			$rowClass = $this->cssBaseID . '-row ' . $this->cssBaseID . ($oddPage ? "-oddrow" : "-evenrow");
			$oddPage = !$oddPage;
			
			return $rowClass;
		}
		
		function OutputContent_OnlineStoreSection( $results )
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$rowCount = 0;
			
			if (!isset($this->storeRows)) $this->storeRows = 0;
			if (count($results) == 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<!-- OnlineStore - No Output -->\n");
				return;
			}
      
			$lastGroupID = 0;	
			for ($recordIndex = 0; $recordIndex<count($results); $recordIndex++)
			{		
				$result = $results[$recordIndex];
				
				if (!$this->IsOnlineStoreItemEnabled($result))
					continue;
					
				$storeRowHTML = $this->OutputContent_OnlineStoreRow($result);
				if ($storeRowHTML == '')
					continue;
				
				$this->storeRows++;
				$rowCount++;
				
				$groupID = $this->GetOnlineStoreGroupID($result);
				if ($lastGroupID != $groupID)
				{
					if ($rowCount > 1)
					{
						StageShowLibEscapingClass::Safe_EchoHTML('</table></div>');						
					}
					
					StageShowLibEscapingClass::Safe_EchoHTML('<div class="'.$this->cssBaseID.'">'."\n");
					$this->OutputContent_OnlineStoreTitle($result);			
					$this->OutputContent_OnlineStoreHeader($result);				
				}
				$lastGroupID = $groupID;
					
				$stockID = $this->GetOnlineStoreStockID($result);
				if ($this->lastItemID !== $stockID)
				{
					$this->GetOnlineStoreItemNote($result, 'above');
				}
											
				$addSaleItemParams = '';
				
				StageShowLibEscapingClass::Safe_EchoHTML('
					<tr class="'.$this->GetBoxOfficeRowClass($result).'">
					<td class="'.$this->cssBaseID.'-data">
					');

				StageShowLibEscapingClass::Safe_EchoHTML($storeRowHTML);
				
				StageShowLibEscapingClass::Safe_EchoHTML('
					</td>
					</tr>
				');
				$this->lastItemID = $stockID;
								
				$nextItemID = $recordIndex+1<count($results) ? $this->GetOnlineStoreStockID($results[$recordIndex+1]) : -1;
				if ($nextItemID !== $stockID)
				{
					$this->GetOnlineStoreItemNote($result, 'below');
				}

			}

			if ($rowCount >= 1)
			{
				StageShowLibEscapingClass::Safe_EchoHTML('</table></div>');						
			}

			// OnlineStore BoxOffice HTML Output - End 
		}
						
		function OutputContent_OnlineTrolleyHeader($result)
		{
			StageShowLibEscapingClass::Safe_EchoHTML('<tr class="'.$this->cssTrolleyBaseID.'-titles">'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['name'].'">'.$this->colID['name'].'</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['ref'].'">'.$this->colID['ref'].'</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['price'].'">'.$this->colID['price'].'</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['qty'].'">'.$this->colID['qty'].'</td>'."\n");
			if (!$this->saleConfirmationMode)
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-remove">&nbsp;</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
			
			$this->trolleyHeaderCols = 5;	// Count of the number of columns in the header
		}
				
		function OutputContent_OnlineTrolleyRow($priceEntry, $cartEntry)
		{
			$qty = ($cartEntry->qty != '') ? $cartEntry->qty : 1;			
			$priceValue = $cartEntry->price;
			$total = $priceValue * $qty;
								
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['name'].'">'.$priceEntry->stockName.'</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['ref'].'">'.$priceEntry->stockRef.'</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['price'].'">'.$this->myDBaseObj->FormatCurrency($priceValue).'</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['qty'].'">'.$cartEntry->qty.'</td>'."\n");
			
			return $total;
		}
				
		function OutputContent_OnlineTrolleyDiscount($cartContents)
		{
			return 0;
		}
		
		function OutputContent_OnlineTrolleyFee($cartContents)
		{
			return 0;
		}
		
		function OutputContent_OnlineTrolleyUserInterface()
		{
			return '';
		}
				
		function OutputContent_OnlineTrolleyExtras($cartContents)
		{
			return 0;
		}
				
		function OutputContent_OnlineTrolleyFooterRows($cartContents)
		{
			return;
		}
			
		function OutputContent_OnlineRemoveButton($cartIndex, $cartEntry)
		{
			$buttonName = 'RemoveItemButton_'.$cartIndex;
			$buttonText = __('Remove', 'stageshow');
			$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'remove', $buttonName, '', 'button-secondary');
			$buttonCode = "<input $buttonType />\n";
			
			return $buttonCode;
		}
		
		function OutputContent_HideCheckoutButton($cartContents)
		{
			return false;
		}
		
		function OutputContent_OnlineCheckoutButton($cartContents)
		{
			if ($this->adminPageActive)
			{
				$buttonText = __('Next', 'stageshow');
				$onClick = '';
				if ($this->myDBaseObj->HasCheckoutForm($cartContents))
				{
					$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'editsaleform');
				}
				else
				{
					$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'editbuyer');
				}
				
				if (!isset($cartContents->saleID))
				{
					$onClick = ' onclick="stageshow_OnClickCheckout(this)" ';
				}
				StageShowLibEscapingClass::Safe_EchoHTML("<input $buttonType $onClick/>\n");
						
				return '';
			}
			
			if (!$this->myDBaseObj->GatewayConfigured())
			{
				return '';
			}
		
			// Check for a "Reserve only" trolley entry
			if ($this->OutputContent_HideCheckoutButton($cartContents)) return '';

			$html = $this->myDBaseObj->gatewayObj->GetPreCheckoutButtonHTML($this, $cartContents);
			if ($html != '')
			{
				return $html;
			}
			
			return $this->myDBaseObj->gatewayObj->GetTrolleyButtonsHTML($this, $cartContents);
		}
		
		function GetTrolleyDefaults()
		{
			$cartDefaults = new stdClass;
			$cartDefaults->nextIndex = 1;
			$cartDefaults->saleDonation = 0;
			$cartDefaults->saleNoteToSeller = '';
			$cartDefaults->salePostTickets = false;
			$cartDefaults->timestamp = 0;
			
			return $cartDefaults;
		}
		
		function GetTrolleyContents()
		{
			$clearTrolley = true;
			$timestampNow = time();
			if ($this->myDBaseObj->IsSessionElemSet($this->trolleyid))
			{
				$cartValSer = $this->myDBaseObj->GetSessionElem($this->trolleyid);
				$cartContents = unserialize($cartValSer);
				if ($timestampNow - $cartContents->timestamp <= STAGESHOWLIB_TROLLEYTIMEOUT)
				{
					$clearTrolley = false;
				}
				else
				{
					$trolleyState = 'Timeout';
				}
			}
			else
			{
				$trolleyState = 'Missing';
			}
			
			if ($clearTrolley)
			{
				$cartContents = $this->GetTrolleyDefaults();
			}
			
			$cartContents->timestamp = $timestampNow;

			
			return $cartContents;
		}
		
		function CompareTrolleyEntries($cartEntry1, $cartEntry2)
		{
			return ($cartEntry1->sortBy == $cartEntry2->sortBy);
		}
		
		function CheckForSoldOut(&$cartContents, $newCartEntry)
		{
			return 0;
		}
		
		function GetMatchingDeletedCartEntry(&$cartContents, $newCartEntry)
		{
			if (!isset($cartContents->deletedrows))
			{
				return 0;
			}
			
			if (!$this->myDBaseObj->CartEntryHasCheckoutForm($newCartEntry))
			{
				return 0;
			}
			
			foreach ($cartContents->deletedrows as $delindex => $delcartEntry)
			{
				if ( isset($delcartEntry->customFields)
				  && ($delcartEntry->ckoutDetailsTemplate == $newCartEntry->ckoutDetailsTemplate) )
				{
					return $delindex;
				}
			}

			return 0;
		}
		
		function AddDeletedCartEntryDetails(&$newCartEntry, $delCartEntry)
		{
			$newCartEntry->customFields = $delCartEntry->customFields;
		}
		
		function AddToTrolleyContents(&$cartContents, $newCartEntry)
		{
			if ($newCartEntry->qty <= 0) return;			

			$delindex = $this->GetMatchingDeletedCartEntry($cartContents, $newCartEntry);
			if ($delindex > 0)
			{
				$delCartEntry = $cartContents->deletedrows[$delindex];
				$this->AddDeletedCartEntryDetails($newCartEntry, $delCartEntry);
				unset($cartContents->deletedrows[$delindex]);
			}
			
			if (isset($cartContents->rows))
			{
				foreach ($cartContents->rows as $index => $cartEntry)
				{
					if ($this->CompareTrolleyEntries($newCartEntry, $cartEntry))
					{
						$cartContents->rows[$index]->qty += $newCartEntry->qty;
						$cartContents->saleExtraDiscount = $this->myDBaseObj->GetExtraDiscount($cartContents);
						$cartContents->saleTransactionFee = $this->myDBaseObj->GetTransactionFee($cartContents);
						return;
					}
					
					if ($newCartEntry->sortBy > $cartEntry->sortBy)
						continue;
						
					$tmpCartEntry = $cartEntry;
					$cartContents->rows[$index] = $newCartEntry;
					
					// The last entry becomes the one to add next
					$newCartEntry = $tmpCartEntry;
				}				
			}
			
			$index = $cartContents->nextIndex;
			$cartContents->nextIndex++;
			
			$cartContents->rows[$index] = $newCartEntry;
			
			$cartContents->saleExtraDiscount = $this->myDBaseObj->GetExtraDiscount($cartContents);
			$cartContents->saleTransactionFee = $this->myDBaseObj->GetTransactionFee($cartContents);
			
			$cartContents->timestamp = time();
		}
		
		function RemoveFromTrolleyContents(&$cartContents, $itemID)
		{
			if (!isset($cartContents->rows[$itemID])) return;

			// Move deleted row to deleted-rows array
			if (!isset($cartContents->deletedrows))
			{
				$cartContents->deletedrows = array();
			}
			$cartContents->deletedrows[$itemID] = $cartContents->rows[$itemID];
			
			// Delete the row from $cartContents
			unset($cartContents->rows[$itemID]);
			
			if (count($cartContents->rows) == 0)
			{
				$cartContents->saleExtraDiscount = $this->myDBaseObj->FormatCurrency(0);
				$cartContents->saleTransactionFee = $this->myDBaseObj->FormatCurrency(0);
				$cartContents->saleDonation = 0;
			}
				
			$this->OnlineStore_AddTrolleySuplementaryInputs($cartContents);
		}
		
		function SaveInitialSaleEditTrolley(&$cartContents)
		{
			$this->SaveTrolleyContents($cartContents, __LINE__);
		}
		
		function SaveTrolleyContents($cartContents, $lineNo = '')
		{
			$cartContents->timestamp = time();
			
			$this->myDBaseObj->SetSessionElem($this->trolleyid, serialize($cartContents));
		}
		
		function SetTrolleyID($id = '')
		{
			if (defined('STAGESHOWLIB_TROLLEYID'))
				$this->trolleyid = STAGESHOWLIB_TROLLEYID.'_saleedit_';
			else
				$this->trolleyid = 'stageshow'.'_saleedit_';
					
			$this->trolleyid .= ($id != '') ? $id : 'new';
		}
		
		function ClearTrolleyContents()
		{
			if ($this->myDBaseObj->dev_ShowTrolley())
			{
				StageShowLibEscapingClass::Safe_EchoHTML('CLEAR cartContents ('.$this->trolleyid.") <br>\n");
			}

			$this->myDBaseObj->UnsetSessionElem($this->trolleyid);
		}
		
		function Cart_OnlineStore_GetCheckoutDetails()
		{
			$myDBaseObj = $this->myDBaseObj;
			
			if (!$myDBaseObj->IsButtonClicked('checkoutdetails'))	// Get checkout details from user
				return '';
			
			// Get the list of fields for user to add
			$userFieldsList = $myDBaseObj->gatewayObj->Gateway_ClientFields();				
			if (count($userFieldsList) == 0)
				return '';
				
			if (StageShowLibUtilsClass::IsElementSet('post', 'checkout-submit'))
			{
				return '';
			}
			
			$html = $this->Cart_OnlineStore_GetPurchaserDetailsForm($userFieldsList);
			
			$buttonText = __('Checkout', 'stageshow');
			$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'checkout');
			$buttonType = StageShowLibMigratePHPClass::Safe_str_replace('_OnClickCheckout', '_OnClickSubmitDetails', $buttonType);
			$html .= "<input $buttonType />\n";
			
			StageShowLibEscapingClass::Safe_EchoHTML($html);
			return $html;	
		}
		
		function Cart_OnlineStore_PostToHiddenElem($postId)
		{
			$postVal = StageShowLibUtilsClass::GetHTTPTextElem('post', $postId); 
			if ($postVal == '') return '';
				
			$html = '
					<input type="hidden" id="'.$postId.'" name="'.$postId.'" value="'.$postVal.'" />';				
				
			return $html;				
		}
		
		function Cart_OnlineStore_GetPurchaserDetailsForm($userFieldsList)
		{
			$html = '';
			$detailsCSSBase = 'stageshow'.'-checkoutdetails';
			
			$html .= $this->Cart_OutputContent_Anchor("trolley");
			$html .= $this->Cart_OutputContent_Anchor("checkoutdetails");
					
			// Pass cart contents so we can detect if the trolley is changed in another window
			$cartContents = $this->GetTrolleyContents();
			$paramCount = 0;
			if (isset($cartContents->rows))
			{
				foreach ($cartContents->rows as $cartIndex => $cartEntry)
				{				
					$paramCount++;
					$itemID = $cartEntry->itemID;
					$qty = $cartEntry->qty;
					
					$html .= '
						<input type="hidden" id="id'.$cartIndex.'" name="id'.$cartIndex.'" value="'.$itemID.'" />
						<input type="hidden" id="qty'.$cartIndex.'" name="qty'.$cartIndex.'" value="'.$qty.'" />';
				}				
			}
					
			$html .= $this->Cart_OnlineStore_PostToHiddenElem('saleDiscountCode');
			$html .= $this->Cart_OnlineStore_PostToHiddenElem('saleNoteToSeller');
						
			$html .= '<div class="'.$this->cssTrolleyBaseID.'-header"><h2>'.__('Your Contact Details', 'stageshow')."</h2></div>\n";

			$missingMessage = __('must be entered', 'stageshow');
			
			$html .= '
				<div id="'.$detailsCSSBase.'" class="'.$detailsCSSBase.'">
				<table class="'.$detailsCSSBase.'-table">';
			
			$inputClass = 'stageshow'."_customco ".'stageshow'."-trolley-ui";
			
			foreach ($userFieldsList as $userField => $userLabel)
			{
				$elemId = 'checkoutdetails-'.$userField;
				$elemValue = $this->myDBaseObj->gatewayObj->Gateway_ClientFieldValue($userField);
			
				switch ($userField)
				{
					case 'saleEMail':
						$inputParams = ' input-format="email" minchars='.STAGESHOWLIB_RESERVATION_EMAIL_MINLEN.' class="'.$inputClass.'" '; 
						break;
						
					case 'salePPPhone':
						$inputParams = ' input-format="numeric" minchars='.STAGESHOWLIB_RESERVATION_PHONE_MINLEN.' class="'.$inputClass.'" '; 
						break;
					
					case 'salePPCountry':
						$inputParams = ' input-format="text" minchars='.STAGESHOWLIB_RESERVATION_COUNTRY_MINLEN.' class="'.$inputClass.'" '; 
						break;
						
					case 'salePPState':
						$inputParams = ' input-format="text" minchars='.STAGESHOWLIB_RESERVATION_STATE_MINLEN.' class="'.$inputClass.'" '; 
						break;
						
					default:
						$inputParams = ' input-format="text" minchars='.STAGESHOWLIB_RESERVATION_DEFAULT_MINLEN.' class="'.$inputClass.'" '; 
						break;
				}
				
				$html .= '
					<tr class="'.$detailsCSSBase.'-row '.$detailsCSSBase.'-row-'.$userField.'">
					<td class="'.$detailsCSSBase.'-label" id="'.$elemId.'-label">'.$userLabel.'</td>
					<td class="'.$detailsCSSBase.'-value"><input type=text '.$inputParams.' id="'.$elemId.'" name="'.$elemId.'" alt="'.$userLabel.'" value="'.$elemValue.'" /></td>
					</tr>';							
				
			}
			$html .= '
				</table>
				</div>';
			
			$html .= "<div class=CheckoutFormStatus id=CheckoutFormStatus name=CheckoutFormStatus>Status Goes Here</div>\n";					
			
			$html .= "
<script>
StageShowLib_addWindowsLoadHandler(".'stageshow'."_OnLoadPurchaserDetailsForm); 
</script>
<input type=hidden id=checkoutform-submit name=checkoutform-submit value=true />
				";	
			
			return $html;
		}
				
		function Cart_OnlineStore_HandleTrolley()
		{
			// Only Allow One Shopping Trolley (If there are multiple shortcodes on one page)
			if ($this->doneSalesTrolley) return false;
			$this->doneSalesTrolley = true;
				
			$myDBaseObj = $this->myDBaseObj;

			$cartContents = $this->GetTrolleyContents();
			
			return $this->OnlineStore_HandleTrolleyButtons($cartContents);
		}
		
		function OnlineStore_GetSortField($result)
		{
			return $result->stockName.'_'.$result->stockID;
		}
		
		function OnlineStore_AddTrolleyExtras(&$cartEntry, $result)
		{
		}
		
		function OnlineStore_EMailSaleButton($saleDetails)
		{
			$targetFile = STAGESHOWLIB_SENDEMAIL_TARGET;
			$ourNOnce = StageShowLibNonce::GetStageShowLibNonce($targetFile);

			$sendEMailURL = StageShowLibUtilsClass::GetCallbackURL($targetFile);
			$saleID = $saleDetails->saleID; 
			$saleEMail = $saleDetails->saleEMail; 
			$saleTxnId = $saleDetails->saleTxnId; 
			$cssDomain = 'stageshow';
			$emailSaleButtonClick = "StageShowLib_Send_EMail($saleID, '$sendEMailURL', '$saleEMail', '$saleTxnId', '$ourNOnce', '$cssDomain')";
			
			$disabled = (defined('STAGESHOWLIB_JQUERY_DISABLE') || $this->myDBaseObj->isDbgOptionSet('Dev_DisableJS')) ? 'disabled ' : '';
			StageShowLibEscapingClass::Safe_EchoHTML('
				&nbsp;&nbsp;<input '.$disabled.'type="button" class="'.'stageshow'.'-trolley-ui button-secondary" name="EMailSale" id="EMailSale" value="'.__('EMail Sale', 'stageshow').'" onclick="'.$emailSaleButtonClick.'" />
				&nbsp;&nbsp;<span class="'.'stageshow'.'-sendemail-status" name="'.'stageshow'.'-sendemail-status" id="'.'stageshow'.'-sendemail-status"></span>
				');
		}
		
		function OnlineStore_AddTrolleyInputs(&$cartContents)
		{
			$cartContents->saleEMail     = StageShowLibUtilsClass::GetHTTPEMail('post', 'saleEMail');
			$cartContents->saleFirstName = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleFirstName');
			$cartContents->saleLastName  = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleLastName');
			$cartContents->salePPStreet  = StageShowLibUtilsClass::GetHTTPTextElem('post', 'salePPStreet');
			$cartContents->salePPCity    = StageShowLibUtilsClass::GetHTTPTextElem('post', 'salePPCity');
			$cartContents->salePPState   = StageShowLibUtilsClass::GetHTTPTextElem('post', 'salePPState');
			$cartContents->salePPZip     = StageShowLibUtilsClass::GetHTTPTextElem('post', 'salePPZip');
			$cartContents->salePPCountry = StageShowLibUtilsClass::GetHTTPTextElem('post', 'salePPCountry');
			$cartContents->salePPPhone   = StageShowLibUtilsClass::GetHTTPTextElem('post', 'salePPPhone');	
			$cartContents->saleStatus    = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleStatus');	
			$cartContents->saleMethod    = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleMethod');	
													
			$cartContents->saleNoteToSeller = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleNoteToSeller');							
		}
		
		function OnlineStore_AddTrolleySuplementaryInputs(&$cartContents)
		{
			if (StageShowLibUtilsClass::IsElementSet('post', 'saleDonation'))
			{
				$cartContents->saleDonation = StageShowLibHTTPIO::GetRequestedCurrency('saleDonation', 0);
			}	

			if (StageShowLibUtilsClass::IsElementSet('post', 'saleNoteToSeller'))
			{
				$cartContents->saleNoteToSeller = StageShowLibDBaseClass::GetSafeString('saleNoteToSeller', '');
			}
			
			$cartContents->salePostTickets = StageShowLibUtilsClass::IsElementSet('post', 'salePostTickets');
			$this->SaveTrolleyContents($cartContents, __LINE__);
		}
		
		function OnlineStore_AddTicketToCart($itemID, $reqQty, &$cartContents)
		{
			if ($reqQty <= 0) return 0;

			// Interogate the database to confirm that the item exists
			$priceEntries = $this->GetOnlineStoreProductDetails($itemID);
			if (count($priceEntries) <= 0) return 0;

			if (!$this->hasAddButtonPerPrice)
			{
				if ($cartContents->inventoryID != $this->GetOnlineStoreInventoryID($priceEntries[0]))
					return 0;
			}

			// Add the item to the shopping trolley
			$cartEntry = new stdClass;
			$cartEntry->itemID = $itemID;
			$cartEntry->qty = $reqQty;
			$cartEntry->price = $this->GetOnlineStoreItemPrice($priceEntries[0]);
			
			$this->OnlineStore_AddTrolleyExtras($cartEntry, $priceEntries[0]);
			$cartEntry->sortBy = $this->OnlineStore_GetSortField($priceEntries[0]);
			
			$soldOutItems = $this->CheckForSoldOut($cartContents, $cartEntry);
			$this->AddToTrolleyContents($cartContents, $cartEntry);
			
			return $soldOutItems;
		}
		
		function OnlineStore_HandleTrolleyButtons($cartContents)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			if (StageShowLibUtilsClass::IsElementSet('get', 'action') && StageShowLibUtilsClass::IsElementSet('request', 'editpage'))
			{
				if ($_GET['action'] == 'editsale')
				{
					if ($myDBaseObj->IsButtonClicked('editbuyer')) 
					{
						$this->cart_ReadOnly = true;
						$this->OnlineStore_AddTrolleySuplementaryInputs($cartContents);	
					}
					
					if ($myDBaseObj->IsButtonClicked('savesaleedit')) 
					{
						$cartContents = $this->GetTrolleyContents();
						
						$this->OnlineStore_AddTrolleyInputs($cartContents);
						
						$saleEMail = $cartContents->saleEMail;
																
						$this->SaveTrolleyContents($cartContents, __LINE__);
						
						$saleID = $this->OnlineStoreSaveEdit();
						
						if ($saleID > 0)
						{
							StageShowLibEscapingClass::Safe_EchoHTML('
							<div id="message" class="updated">
							<p>'.__('Sale Details have been saved', 'stageshow'));
							$salesList = $myDBaseObj->GetSale($saleID);
							if (count($salesList) > 0)
							{
								$saleDetails = $salesList[0];							
								$myDBaseObj->OutputViewTicketButton($saleDetails);
								$this->OnlineStore_EMailSaleButton($saleDetails);
							}
							StageShowLibEscapingClass::Safe_EchoHTML('
							</div>');								
							
							$this->suppressUI = true;
							
							return true;	// Supress output of shopping trolley
						}
						
						// $cartContents is updated when OnlineStoreSaveEdit() fails - Reload it!
						$cartContents = $this->GetTrolleyContents();
						unset($_POST['savesaleedit']);
					}				
				}
			}
			
			$addItemID = 0;
			$qtys = array();
			$this->hasAddButtonPerPrice = !is_numeric(StageShowLibMigratePHPClass::Safe_strpos($myDBaseObj->getOption('QtySelectMode'), STAGESHOWLIB_QTYSELECT_SINGLE));

			foreach ($_POST as $postId => $postVal)
			{
				$postIdElems = explode("_", $postId);
				if (count($postIdElems) < 2) 
					continue;
					
				$reqId = $postIdElems[1];
				if (!is_numeric($reqId)) 
					continue;
					
				switch ($postIdElems[0])
				{
					case 'AddItemButton': 
						// Clear Posted Data so it can't be used again'
						unset($_POST[$postId]);	
										
						// Get the product ID from posted data
						$addItemID = $reqId;
						if (!$this->hasAddButtonPerPrice) break;
						$qtys[$addItemID] = StageShowLibHTTPIO::GetRequestedInt('quantity_'.$addItemID);
						if ($qtys[$addItemID] == '') $qtys[$addItemID] = 1;
						break;
						
					case 'RemoveItemButton': 
						// Clear Posted Data so it can't be used again'
						unset($_POST[$postId]);					
						$_GET['remove'] = $reqId;
						break;
						
					case 'quantity':
						// Clear Posted Data so it can't be used again'
						unset($_POST[$postId]);					
						if ($this->hasAddButtonPerPrice) break;
						if (!is_numeric($postVal)) break;
						$qtys[$reqId] = $postVal;
						break;
				}
				
			}

			// For Box-Office pages that do not have a "quantity" input .... force qty to 1
			if ( ($addItemID > 0) && (count($qtys) == 0) )
			{
				$qtys[$addItemID] = 1;
			}
			
			$soldOutItems = 0;
			if ($addItemID > 0) 
			{
				if (!$this->hasAddButtonPerPrice)
				{
					// Get the item details - Used to determine valid quantities 
					$cartContents->inventoryID = 0;
					$priceEntries = $this->GetOnlineStoreProductDetails($addItemID);
					if (count($priceEntries) > 0) 
						$cartContents->inventoryID = $this->GetOnlineStoreInventoryID($priceEntries[0]);
					else
						$addItemID = 0;
				}
			}
				
			if ($addItemID > 0) 
			{
				foreach ($qtys as $itemID => $reqQty)
				{
					$soldOutItems += $this->OnlineStore_AddTicketToCart($itemID, $reqQty, $cartContents);
				}

				$this->OnlineStore_AddTrolleySuplementaryInputs($cartContents);
				$this->SaveTrolleyContents($cartContents, __LINE__);
			}
					
			if ($soldOutItems > 0)
			{
				$msg  = _n("Item was not added", "Items were not added", $soldOutItems, 'stageshow');
				$msg .= ' = '.__('Insufficient Seats Available', 'stageshow');
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="'.'stageshow'.'-error error">'."$soldOutItems $msg </div>");									
			}
			
			if (!isset($cartContents->rows))
				return false;
				
			if (count($cartContents->rows) == 0)
				return false;
				
			if (StageShowLibUtilsClass::IsElementSet('post', 'saleDonation'))
			{
				$cartContents->saleDonation = StageShowLibHTTPIO::GetRequestedCurrency('saleDonation', 0);
				$this->SaveTrolleyContents($cartContents, __LINE__);
			}
			
			$removeItemID = StageShowLibUtilsClass::GetHTTPInteger('get', 'remove', 0); 
			if ($removeItemID != 0)
			{
				$this->RemoveFromTrolleyContents($cartContents, $removeItemID);
				unset($_GET['remove']);
			}
			
			$doneHeader = $this->OnlineStore_OutputTrolley($cartContents);
			return $doneHeader;			
		}
		
		function OnlineStore_UpdateTrolleyPrices($cartContents)
		{
			if (!isset($cartContents->rows))
				return false;
					
			foreach ($cartContents->rows as $cartIndex => $cartEntry)
			{				
				$itemID = $cartEntry->itemID;
				
				$priceEntries = $this->GetOnlineStoreProductDetails($itemID);				
				if (count($priceEntries) == 0) continue;
				
				$cartContents->rows[$cartIndex]->price = $this->GetOnlineStoreItemPrice($priceEntries[0]);
			}
		}
		
		function OnlineStore_OutputTrolleyHTML($html)
		{
			$colCount = $this->trolleyHeaderCols;
			$addTags = (StageShowLibMigratePHPClass::Safe_stripos($html, "<tr") === false);
			
			if ($addTags) StageShowLibEscapingClass::Safe_EchoHTML("<tr><td colspan=$colCount>");			
			StageShowLibEscapingClass::Safe_EchoHTML($html);
			if ($addTags) StageShowLibEscapingClass::Safe_EchoHTML("</td></tr>");			
		}
		
		function OnlineStore_OutputTrolley($cartContents)
		{
			$myDBaseObj = $this->myDBaseObj;
	
			$doneHeader = false;
			$runningTotal = 0;		
			$hiddenTags  = "\n";

			if (isset($cartContents->confirmSaleMode))					
				$this->saleConfirmationMode = true;				
			
			if (isset($this->editpage) || $this->saleConfirmationMode)
			{
				$checkoutNote = '';
				$checkoutNotePosn = '';
			}			
			else
			{
				$checkoutNote = $myDBaseObj->getOption('CheckoutNote');
				$checkoutNotePosn = $myDBaseObj->getOption('CheckoutNotePosn');
			}
				
			foreach ($cartContents->rows as $cartIndex => $cartEntry)
			{				
				$itemID = $cartEntry->itemID;
				$qty = $cartEntry->qty;
				
				$priceEntries = $this->GetOnlineStoreProductDetails($itemID);				
				if (count($priceEntries) == 0)
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="'.'stageshow'.'-error error">'.__("Shopping Trolley Cleared", 'stageshow').'</div>');					
					$this->ClearTrolleyContents();
					return false;
				}

				$priceEntry = $priceEntries[0];
				if (!$doneHeader)
				{
					if ($this->saleConfirmationMode)
						$trolleyHeading = __('Your Order Details', 'stageshow');
					else if ($this->adminPageActive)
						$trolleyHeading = __('Sale Items', 'stageshow');
					else
						$trolleyHeading = __('Your Shopping Trolley', 'stageshow');
						
					StageShowLibEscapingClass::Safe_EchoHTML($this->Cart_OutputContent_Anchor("trolley"));
					
					StageShowLibEscapingClass::Safe_EchoHTML('<div class="'.$this->cssTrolleyBaseID.'-header"><h2>'."$trolleyHeading</h2></div>\n");
					if ( ($checkoutNotePosn == 'header') && ($checkoutNote != '') )
					{
						StageShowLibEscapingClass::Safe_EchoHTML($checkoutNote);
					}
					
					StageShowLibEscapingClass::Safe_EchoHTML('<div class="'.$this->cssTrolleyBaseID.'">'."\n");
					StageShowLibEscapingClass::Safe_EchoHTML('<table class="'.$this->cssTrolleyBaseID.'-table">'."\n");
					if ( ($checkoutNotePosn == 'titles') && ($checkoutNote != '') )
					{
						StageShowLibEscapingClass::Safe_EchoHTML($checkoutNote);
					}
					$this->OutputContent_OnlineTrolleyHeader($priceEntry);
						
					$doneHeader = true;
				}
					
				StageShowLibEscapingClass::Safe_EchoHTML('<tr class="'.$this->cssTrolleyBaseID.'-row">'."\n");
					
				$runningTotal += $this->OutputContent_OnlineTrolleyRow($priceEntry, $cartEntry);
					
				if (!$this->saleConfirmationMode)
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-remove">'."\n");
					if (!isset($this->cart_ReadOnly))
					{
						$removeLinkContent = $this->OutputContent_OnlineRemoveButton($cartIndex, $cartEntry);
					}
					else
					{
						$removeLinkContent = '&nbsp;';
					}	
					StageShowLibEscapingClass::Safe_EchoHTML($removeLinkContent.'</td>'."\n");
					
					StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");					
				}
					
				$hiddenTags .= '<input type="hidden" name="id'.$cartIndex.'"  id="id'.$cartIndex.'" value="'.$itemID.'"/>'."\n";
				$hiddenTags .= '<input type="hidden" name="qty'.$cartIndex.'" id="qty'.$cartIndex.'" value="'.$qty.'"/>'."\n";
			}
			
			if ($doneHeader)
			{	
				$runningTotal -= $this->OutputContent_OnlineTrolleyDiscount($cartContents);
				$runningTotal += $this->OutputContent_OnlineTrolleyFee($cartContents);
				$trolleyTotal = $runningTotal + $this->OutputContent_OnlineTrolleyExtras($cartContents);

				// Add totals row and checkout button
				$runningTotal = $myDBaseObj->FormatCurrency($runningTotal);				
				$trolleyTotal = $myDBaseObj->FormatCurrency($trolleyTotal);

				$cartContents->trolleyTotal = $trolleyTotal;
				
				if (defined('STAGESHOWLIB_TROLLEYHTML_ABOVETOTAL')) $this->OnlineStore_OutputTrolleyHTML(STAGESHOWLIB_TROLLEYHTML_ABOVETOTAL);
				
				StageShowLibEscapingClass::Safe_EchoHTML('<tr class="'.$this->cssTrolleyBaseID.'-totalrow">'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['name'].'">&nbsp;</td>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['datetime'].'">&nbsp;</td>'."\n");
				$colSpan = $this->trolleyHeaderCols-6;
				while ($colSpan > 0)
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<td>&nbsp;</td>'."\n");
					$colSpan--;
				}
				StageShowLibEscapingClass::Safe_EchoHTML('<td>'.__('Total', 'stageshow')."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" id="saleTrolleyTotal" name="saleTrolleyTotal" value="'.$runningTotal.'"/>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('</td>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<td>&nbsp;</td>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-total" id="'.$this->cssTrolleyBaseID.'-totalval" name="'.$this->cssTrolleyBaseID.'-totalval">'.$trolleyTotal.'</td>'."\n");
				if (!$this->saleConfirmationMode)
					StageShowLibEscapingClass::Safe_EchoHTML('<td>&nbsp;</td>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
				
				if (defined('STAGESHOWLIB_TROLLEYHTML_ABOVEBUTTONS')) $this->OnlineStore_OutputTrolleyHTML(STAGESHOWLIB_TROLLEYHTML_ABOVEBUTTONS);
				
				if ( ($checkoutNotePosn == 'above') && ($checkoutNote != '') )
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<tr><td class="'.$this->cssTrolleyBaseID.'-checkoutnote" colspan="'.$this->trolleyHeaderCols.'">'.$checkoutNote."</td></tr>\n");
				}
					
				if (!isset($this->cart_ReadOnly))
				{
					if (!isset($this->editpage) && $myDBaseObj->isOptionSet('UseNoteToSeller'))
					{
						if (StageShowLibUtilsClass::IsElementSet('post', 'saleNoteToSeller'))
							$noteToSeller = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleNoteToSeller');
						else if (isset($cartContents->saleNoteToSeller))
							$noteToSeller = $cartContents->saleNoteToSeller;
						else
							$noteToSeller = '';

						$noteCols = $this->trolleyHeaderCols-1;
						$rowsDef = defined('STAGESHOWLIB_NOTETOSELLER_ROWS') ? "rows=".STAGESHOWLIB_NOTETOSELLER_ROWS." " : "";
						
						StageShowLibEscapingClass::Safe_EchoHTML('
							<tr class="'.'stageshow'.'-trolley-notetoseller">
							<td>'.__('Message To Seller', 'stageshow').'</td>
							<td colspan="'.$noteCols.'">
							<textarea class="'.'stageshow'.'-trolley-ui" name="saleNoteToSeller" id="saleNoteToSeller" '.$rowsDef.'>'.$noteToSeller.'</textarea>
							</td>
							</tr>
							');
					}
					
					StageShowLibEscapingClass::Safe_EchoHTML(apply_filters('stageshow'.'_filter_trolley_extrarows', ''));
										
					StageShowLibEscapingClass::Safe_EchoHTML('<tr class="'.$this->cssTrolleyBaseID.'-buttons">'."\n");
					StageShowLibEscapingClass::Safe_EchoHTML('<td align="center" colspan="'.$this->trolleyHeaderCols.'" class="'.$this->cssTrolleyBaseID.'-checkout">'."\n");
					
					StageShowLibEscapingClass::Safe_EchoHTML($this->OutputContent_OnlineCheckoutButton($cartContents));
					
					StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" id="saleCustomValues" name="saleCustomValues" value=""/>'."\n");
					
					StageShowLibEscapingClass::Safe_EchoHTML('</td>'."\n");
					StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
				
					$this->OutputContent_OnlineTrolleyFooterRows($cartContents);
				}
				
				if (defined('STAGESHOWLIB_TROLLEYHTML_BELOWBUTTONS')) $this->OnlineStore_OutputTrolleyHTML(STAGESHOWLIB_TROLLEYHTML_BELOWBUTTONS);
				
				if ( ($checkoutNotePosn == 'below') && ($checkoutNote != '') )
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<tr><td class="'.$this->cssTrolleyBaseID.'-checkoutnote" colspan="'.$this->trolleyHeaderCols.'">'.$checkoutNote."</td></tr>\n");
				}
					
				StageShowLibEscapingClass::Safe_EchoHTML("</table>\n");
				StageShowLibEscapingClass::Safe_EchoHTML($hiddenTags);						
				StageShowLibEscapingClass::Safe_EchoHTML('</div>'."\n");
				
				if ( ($checkoutNotePosn == 'bottom') && ($checkoutNote != '') )
				{
					StageShowLibEscapingClass::Safe_EchoHTML($checkoutNote);
				}
				
			}		
			
			return $doneHeader;			
		}

		function GetParamAsHiddenTag($paramId)
		{
			if (StageShowLibUtilsClass::IsElementSet('get', $paramId))	
			{
				$paramValue = StageShowLibUtilsClass::GetHTTPTextElem('get', $paramId); 
			}
			else if (StageShowLibUtilsClass::IsElementSet('post', $paramId))	
			{
				$paramValue = StageShowLibUtilsClass::GetHTTPTextElem('post', $paramId); 
			}
			else
			{
				return "<!-- GetParamAsHiddenTag($paramId) returned NULL -->\n";
			}
			
			return '<input type="hidden" name="'.$paramId.'" id="'.$paramId.'" value="'.$paramValue.'"/>'."\n";
		}
	}
}


