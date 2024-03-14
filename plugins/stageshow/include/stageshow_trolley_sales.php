<?php
/* 
Description: StageShow Plugin Top Level Code
 
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

if (!defined('STAGESHOWLIB_DATABASE_FULL'))
{
	if (!class_exists('StageShowLibSalesCartPluginBaseClass')) 
		include STAGESHOW_INCLUDE_PATH.'stageshowlib_salesplugin_trolley.php';
	
	class StageShowWPSalesCartPluginParentClass extends StageShowLibSalesCartPluginBaseClass {}
}
else
{
	if (!class_exists('StageShowLibSalesPluginBaseClass')) 
	include STAGESHOW_INCLUDE_PATH.'stageshowlib_salesplugin.php';
	
	class StageShowWPSalesCartPluginParentClass extends StageShowLibSalesPluginBaseClass {}
}

if (!class_exists('StageShowSalesCartPluginClass')) 
{
	class StageShowSalesCartPluginClass extends StageShowWPSalesCartPluginParentClass // Define class
	{
		var $pluginUsesSerializedPost = false;
		
		function __construct()
		{
			$this->pluginUsesSerializedPost = true;
			
			$this->cssBaseID = "stageshow-boxoffice";
		
			parent::__construct();
			
			// colID and cssColID are re-defined here 
			$this->colID['name'] = defined('STAGESHOW_BOXOFFICECOL_NAME') ? STAGESHOW_BOXOFFICECOL_NAME : __('Show', 'stageshow');
			$this->cssColID['name'] = "show";					
			$this->colID['datetime'] = defined('STAGESHOW_BOXOFFICECOL_DATETIME') ? STAGESHOW_BOXOFFICECOL_DATETIME : __('Date & Time', 'stageshow');
			$this->cssColID['datetime'] = "datetime";		
			$this->colID['ref'] = defined('STAGESHOW_BOXOFFICECOL_TICKET') ? STAGESHOW_BOXOFFICECOL_TICKET : __('Ticket Type', 'stageshow');
			$this->cssColID['ref'] = "type";
			$this->colID['price'] = defined('STAGESHOW_BOXOFFICECOL_PRICE') ? STAGESHOW_BOXOFFICECOL_PRICE : __('Price', 'stageshow');
			$this->cssColID['price'] = "price";
			$this->colID['qty'] = defined('STAGESHOW_BOXOFFICECOL_QTY') ? STAGESHOW_BOXOFFICECOL_QTY : __('Quantity', 'stageshow');
			$this->cssColID['qty'] = "qty";
				
			$this->colID['cartqty'] = defined('STAGESHOW_BOXOFFICECOL_CARTQTY') ? STAGESHOW_BOXOFFICECOL_CARTQTY : __('Quantity', 'stageshow');
			$this->cssColID['cartqty'] = "qty";	
		}
	
		function Cart_OutputContent_OnlineStoreMain($atts)
		{
			if (($atts['id'] != '') || ($atts['perf'] != ''))
			{			
				parent::Cart_OutputContent_OnlineStoreMain($atts);
			}			
		    else
			{
				$myDBaseObj = $this->myDBaseObj;
				
				// Get ID of "active" Shows in order of first performance
				$shows = $myDBaseObj->GetActiveShowsList();
	      
		  		// Count can be used to limit the number of Shows displayed
				if ($atts['count'] > 0)
					$count = $atts['count'];
				else
					$count = count($shows);

				$this->Cart_OutputContent_Boxoffice_Preload($atts, $shows);
	
				foreach ( $shows as $show )
				{
					if (!$myDBaseObj->IsShowEnabled($show))
						continue;
					
					$atts['id'] = $show->showID;
					parent::Cart_OutputContent_OnlineStoreMain($atts);
					if (--$count == 0)
						break;

					if ($this->adminPageActive)
					{
						if ($myDBaseObj->IsButtonClicked('editbuyer'))	// editing sale - get buyer details
						{
							break;
						}
					}						
						
				}
				
				$this->Cart_OutputContent_Boxoffice_Preload($atts, $shows, 2);
			}
			
			if (($this->storeRows == 0) && !$this->adminPageActive)
			{
				$msg = $myDBaseObj->isOptionSet('EmptyBoxOfficeMsg') ? $myDBaseObj->getOption('EmptyBoxOfficeMsg') : STAGESHOW_EMPTYBOXOFFICEMSG_DEFAULT;
				$msg = apply_filters('stageshow_filter_nosalesmsg', $msg);
				if ($msg != '') StageShowLibEscapingClass::Safe_EchoHTML($msg."<br>\n");
			}
			
		}
		
		function Cart_OutputContent_Boxoffice_Preload($atts, $shows, $pass = 1)
		{
		}

		function Cart_OnlineStore_GetPurchaserDetailsForm($userFieldsList)
		{
			$html = '
<script>
StageShowLib_addWindowsLoadHandler(stageshow_OnLoadSubmitDetailsForm); 
</script>';
			$html .= parent::Cart_OnlineStore_GetPurchaserDetailsForm($userFieldsList);
			return $html;
		}
				
		function IsOnlineStoreItemEnabled($result)
		{
			$myDBaseObj = $this->myDBaseObj;

			return $myDBaseObj->IsPerfEnabled($result);
		}
				
		function GetOnlineStoreProductDetails($priceID)
		{
			$priceEntryList = $this->myDBaseObj->GetPricesListByPriceID($priceID);
			if ($this->adminPageActive) return $priceEntryList;
			
			if (count($priceEntryList) > 0)
			{
				if (!$this->IsOnlineStoreItemEnabled($priceEntryList[0]))
				{
					return array();
				}
			}
			return $priceEntryList;
		}
		
		function GetOnlineStoreProducts($atts)
		{
			$myDBaseObj = $this->myDBaseObj;

			$showID = StageShowLibMigratePHPClass::Safe_htmlspecialchars_decode($atts['id']);
								
			if ($showID !== '')
			{
				// Get the prices list for a single show
				$results = $myDBaseObj->GetPricesListByShowID($showID, true);
				if (count($results) == 0)
				{
					StageShowLibEscapingClass::Safe_EchoHTML("<!-- StageShow BoxOffice - No Output for ShowID=$showID -->\n");
				}
	      
				return $results;
			}
			else if ($atts['count'] == '')
			{
				return $myDBaseObj->GetPricesList(null, true);
			}
			
			return null;
		}
		
		function GetOnlineStoreGroupID($result)
		{
			return $result->showID;
		}
			
		function GetOnlineStoreStockID($result)
		{
			return $result->perfID;
		}
			
		function GetOnlineStoreItemID($result)
		{
			return $result->priceID;
		}
			
		function GetOnlineStoreItemPrice($result)
		{
			return $result->priceValue;
		}
			
		function GetOnlineStoreDateClass($result)
		{
			return '';
		}
			
		function IsOnlineStoreQtyHidden($result)
		{
			return false;
		}
			
		function GetOnlineStoreQtyClass($result)
		{
			return '';
		}
			
		function GetOnlineStoreItemsAvailable($result)
		{
			static $lastPerfID = 0;
			static $itemsAvailable = 0;
			
			if ($lastPerfID != $result->perfID)
			{
				$salesSummary = $this->myDBaseObj->GetPerformanceSummaryByPerfID($result->perfID);
				if ($result->perfSeats >=0) 
				{
					$itemsAvailable = $result->perfSeats - $salesSummary->totalQty;
					if ($itemsAvailable < 0) $itemsAvailable = 0;
				}
				else
				{
					$itemsAvailable = -1;	// i.e. No limit
				}
				
				$lastPerfID = $result->perfID;
			}
			
			
			return $itemsAvailable;
		}
				
		function OutputContent_ShortcodeStyle()
		{
			return '
<style>
.stageshow-admin-only
{
	display: none;
}					
</style>
';
		}

		function OutputContent_DoShortcode($atts, $isAdminPage=false)
		{
			return parent::OutputContent_DoShortcode($atts);
		}
				
		function OutputContent_OnlineStoreTitle($result)
		{
			$showNameAnchor = '';
			$nameLen = StageShowLibMigratePHPClass::Safe_strlen($result->showName);
			for ($i=0; $i<$nameLen; $i++)
			{
				$nxtChar = $result->showName[$i];
				if ($nxtChar == ' ')
					$nxtChar = '_';
				elseif (!ctype_alnum ($nxtChar))
					continue;
					
				$showNameAnchor .= $nxtChar;				
			}
			StageShowLibEscapingClass::Safe_EchoHTML($this->Cart_OutputContent_Anchor($showNameAnchor));
		
			StageShowLibEscapingClass::Safe_EchoHTML('<h2>'.$result->showName."</h2>\n");					

			if (isset($result->showNote) && ($result->showNote !== ''))
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<div class="stageshow-boxoffice-shownote">'.$result->showNote . "</div><br>\n");
			}
		}
			
		function OutputContent_OnlineStoreHeader($result)
		{
			$dateTimeClass = $this->cssBaseID.'-'.$this->cssColID['datetime'];
			$dateTimeClass .= $this->GetOnlineStoreDateClass($result);

			$qtyClass = $this->cssBaseID.'-'.$this->cssColID['qty'];
			$qtyClass .= $this->GetOnlineStoreQtyClass($result);
			
			StageShowLibEscapingClass::Safe_EchoHTML('
				<table class="'.$this->cssBaseID.'-table" width="100%" border="0">
					<tr>
						<td class="'.$this->cssBaseID.'-header">
							<table width="100%" cellspacing="0">
								<tr>
									<td class="'.$dateTimeClass.'">'.$this->colID['datetime'].'</td>
									<td class="'.$this->cssBaseID.'-'.$this->cssColID['ref'].'">'.$this->colID['ref'].'</td>
									<td class="'.$this->cssBaseID.'-'.$this->cssColID['price'].'">'.$this->colID['price'].'</td>
									<td class="'.$qtyClass.'">'.$this->colID['qty'].'</td>
									<td class="'.$this->cssBaseID.'-add">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
				');
		}
				
		function OutputContent_OnlineStoreRow($result)
		{
			static $lastShowID = 0;
			static $lastPerfDateTime = 0;
			static $perfHasActiveTickets = false;
			
			static $useQtyDropDownInput;
			static $useMultipleAddButton;
			static $qtyInputsList = '';
			
			$myDBaseObj = $this->myDBaseObj;

			$lastPerfRow = isset($result->showAvailable);
			
			$newPerf = ($lastShowID !== $result->showID) || ($lastPerfDateTime !== $result->perfDateTime);
			$firstPerf = ($lastPerfDateTime == 0);

			if ($lastShowID == 0)
			{
				$selectMode = $myDBaseObj->getOption('QtySelectMode');
				
				$useQtyDropDownInput = !is_numeric(StageShowLibMigratePHPClass::Safe_strpos($selectMode, STAGESHOWLIB_QTYSELECT_TEXT));
				$useMultipleAddButton = !is_numeric(StageShowLibMigratePHPClass::Safe_strpos($selectMode, STAGESHOWLIB_QTYSELECT_SINGLE));
			}
			
			$lastShowID = $result->showID;
			$lastPerfDateTime = $result->perfDateTime;
			
			$storeRowHTML = '';
			
			$submitButton = __('Add', 'stageshow');
			$submitId     = $this->GetOnlineStoreElemTagId('AddItemButton', $result);
			$showAllDates = defined('STAGESHOW_BOXOFFICE_ALLDATES');
				
			// Sales Summary from PerfID				
			$seatsAvailable = $this->GetOnlineStoreItemsAvailable($result);
			$soldOut = ($seatsAvailable == 0);
			
			$separator = '';
			if ( $newPerf || $showAllDates)
			{
				$formattedPerfDateTime = $myDBaseObj->FormatDateForDisplay($result->perfDateTime);
				if (!$firstPerf) $separator = "\n".'<tr><td class="stageshow-boxoffice-separator">&nbsp;</td></tr>';
			}
			else
			{
				$formattedPerfDateTime = '&nbsp;';
			}
			
			$dateTimeClass = 'stageshow-boxoffice-datetime';
			$dateTimeClass .= $this->GetOnlineStoreDateClass($result);
			
			$storeRowHTML .= '
				<table width="100%" cellspacing="0">'.$separator.'
				<tr>
				<td class="'.$dateTimeClass.'">'.$formattedPerfDateTime.'</td>';
				
			if (isset($result->perfRowHTML))
				$storeRowHTML .= $result->perfRowHTML;
				
			$storeRowHTML .= '
			<td class="stageshow-boxoffice-type">'.$this->OnlineStore_GetPriceType($result).'</td>
			<td class="stageshow-boxoffice-price">'.$myDBaseObj->FormatCurrency($this->GetOnlineStoreItemPrice($result)).'</td>
			';
															
			if (!$soldOut)
			{
				$itemID = $this->GetOnlineStoreItemID($result);
				$perfHasActiveTickets = true;
				
				$quantityTagId = $this->GetOnlineStoreElemTagId('quantity', $result); 
				$no = ($useMultipleAddButton || $this->IsOnlineStoreQtyHidden($result)) ? 1 : 0;
					
				$maxQty = $myDBaseObj->getOption('MaxTicketQty');
				
				$htmlClass = 'stageshow-trolley-ui';
				$htmlName = 'name="'.$quantityTagId.'"';
				if ($this->pluginUsesSerializedPost)
				{
					$htmlClass .= ' stageshowlib_PostVars';
					if ($this->adminPageActive)
					{
						$htmlName = '';	// Can't include name as it could make form exceed max no of elements
					}
				}
				
				if ($this->IsOnlineStoreQtyHidden($result))		// Remove quantity when hidden
				{
					$qtySelectHTML = "&nbsp;";
				}
				else if ($useQtyDropDownInput)
				{
					if ($qtyInputsList != '') $qtyInputsList .= ',';
					$qtyInputsList .= $itemID;
					$selectedText = $useMultipleAddButton ? '1' : __('(Select...)', 'stageshow');
					
					$onChangeHandler = ' onchange="StageShowLib_OnSelectQuantity(this, event, '.$maxQty.', 0, false);" ';
					$qtySelectHTML = '
						<select '.$onChangeHandler.' class="'.$htmlClass.'" '.$htmlName.' id="'.$quantityTagId.'">
						<option value="'.$no.'" selected="">'.$selectedText.'</option>
						';
					$no++;
					if (($seatsAvailable > 0) && ($seatsAvailable <= $maxQty))
					{
						// TODO - Deduct number of seats in shopping trolley from $seatsAvailable
						$maxQty = $seatsAvailable;
					}
					for (; $no<=$maxQty; $no++)
						$qtySelectHTML .= '<option value="'.$no.'">'.$no.'</option>'."\n";					
					$qtySelectHTML .= '
						</select>';
				}	
				else
				{
					$ticketQtyLen = max(StageShowLibMigratePHPClass::Safe_strlen((string)STAGESHOW_MAXTICKETCOUNT), 2);
					$ticketQtySize = $ticketQtyLen + 1;
					if ($qtyInputsList != '') $qtyInputsList .= ',';
					$qtyInputsList .= $itemID;
					$onKeypressHandler = ' onkeypress="StageShowLib_OnKeypressNumericOnly(this, event, '.$maxQty.', 0, false);" ';
					$onChangeHandler = ' onchange="StageShowLib_OnChangeQuantity(this, event, '.$maxQty.', 0, false);" ';
					$onChangeHandler .= ' oninput="StageShowLib_OnInputQuantity(this);" ';
					$onClickHandler = ' onclick="StageShowLib_OnClickQty(this, event, '.$maxQty.', 0, false);" ';
					$qtySelectHTML = '<input type="number" autocomplete="off" maxlength="'.$ticketQtyLen.'" size="'.$ticketQtySize.'" class="'.$htmlClass.'" '.$htmlName.' id="'.$quantityTagId.'" '.$onKeypressHandler.$onChangeHandler.$onClickHandler.'value="'.$no.'" />';
				}			
					
				$qtyClass = 'stageshow-boxoffice-qty';
				$qtyClass .= $this->GetOnlineStoreQtyClass($result);

				$storeRowHTML .= '<td class="'.$qtyClass.'">'.$qtySelectHTML.'</td>';
				
				$storeRowHTML .= '
					<td class="stageshow-boxoffice-add">';
					
				if (($perfHasActiveTickets && $lastPerfRow) || $useMultipleAddButton || $this->IsOnlineStoreQtyHidden($result))
				{
					if ($this->adminPageActive) $buttonClasses = 'button-secondary';
					else $buttonClasses = 'button-primary';						
					
					$buttonClassdef = $this->GetButtonTextAndTypeDef($submitButton, 'add', $submitId, '', $buttonClasses);
					if ($this->pluginUsesSerializedPost)
					{
						$clickEvent="return stageshowlib_JSONEncodePost(this, 'stageshowlib_PostVars')";
						$buttonClassdef .= ' onclick="'.$clickEvent.'" ';
					}
					
					$storeRowHTML .= "<input $buttonClassdef />";

					if ($qtyInputsList != '')					
					{
						$storeRowHTML .= "\n<script>stageshowlib_qtyInputsList[$itemID] = '$qtyInputsList';</script>\n";
					}
					$qtyInputsList = '';
				}
				else
				{
					$storeRowHTML .= '&nbsp;';
				}
				
				$storeRowHTML .= '</td>
					';
			}
			else
			{
				$storeRowHTML .= '
					<td class="stageshow-boxoffice-soldout" colspan=2>'.__('Sold Out', 'stageshow').'</td>
					';
			}
				
			$storeRowHTML .= '
				</tr>
				';

			if ($lastPerfRow)
			{
				$perfHasActiveTickets = false;
				
				if ($myDBaseObj->getOption('ShowSeatsAvailable'))
				{
					// TODO - SSG Allocated Seating - Check Seats Available Count ....
					if ($seatsAvailable > 0)
					{
						$seatsAvailableText = ($seatsAvailable > 1) ? __('Seats Available', 'stageshow') : __('Seat Available', 'stageshow');
						$storeRowHTML .= '
							<tr>
							<td colspan="4" class="stageshow-boxoffice-available">'.$seatsAvailable.' <span>'.$seatsAvailableText.'</span></td>
							</tr>
							';
					}				
				}				
			}
		
			$storeRowHTML .= '
				</table>
				';

			return $storeRowHTML;
		}
		
		function OutputContent_OnlineStoreSection( $results )
		{
			if (count($results) > 0)
			{
		  		$lastIndex = count($results)-1;
		  		for ($index=0; $index<$lastIndex;$index++)
				{
					if ($results[$index]->perfID != $results[$index+1]->perfID)
					{
						$results[$index]->showAvailable = true;
					}
				}
				$results[$lastIndex]->showAvailable = true;
			}
			
			parent::OutputContent_OnlineStoreSection( $results );
		}
		
		function OutputContent_OnlineTrolleyDetailsHeaders()
		{
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['cartqty'].'">'.$this->colID['cartqty'].'</td>'."\n");
			return 1;
		}
		
		function OutputContent_OnlineTrolleyHeader($result)
		{
			$this->trolleyHeaderCols = 5;	// Count of the number of columns in the header
			
			StageShowLibEscapingClass::Safe_EchoHTML('<tr class="'.$this->cssTrolleyBaseID.'-titles">'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['name'].'">'.$this->colID['name'].'</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['datetime'].'">'.$this->colID['datetime'].'</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['ref'].'">'.$this->colID['ref'].'</td>'."\n");
			$this->trolleyHeaderCols += $this->OutputContent_OnlineTrolleyDetailsHeaders();
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['price'].'">'.$this->colID['price'].'</td>'."\n");
			if (!$this->saleConfirmationMode)
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-remove">&nbsp;</td>'."\n");
			StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
		}
				
		function AdminButtonHasClickHandler($buttonID)
		{
			switch ($buttonID)
			{
				case 'selectshow':
				case 'selectperf':
				case 'selectorback':
					$addClickHandler = true;
					break;
					
				default:
					$addClickHandler = false;
					break;
			}
			
			return $addClickHandler;
		}
			
		function GetButtonTypeDef($buttonID, $buttonName = '', $buttonType = 'submit', $buttonClasses = '')
		{
			$buttonSrc = '';
			
			$buttonImageID = $buttonID;
					
			if (!$this->adminPageActive)
			{
				switch ($buttonImageID)
				{
					case 'checkoutdetails':
						$buttonImageID = 'checkout';
						// Fall into next case ...
						
					case 'add':
					case 'remove':
					case 'checkout':
						if ($this->myDBaseObj->ButtonHasURL($buttonImageID, $buttonURL))
						{
							$buttonType = 'image';
							$buttonSrc = ' src="'.$buttonURL.'"';
						}
						break;
						
					case 'confirm':
						if (defined('STAGESHOW_CONFIRMBUTTON_URL') && (STAGESHOW_CONFIRMBUTTON_URL != ''))
						{
							$buttonType = 'image';
							$buttonSrc = ' src="'.STAGESHOW_CONFIRMBUTTON_URL.'"';							
						}
						break;
					
					default:
						break;					
				}
			}
			
			$buttonTypeDef = parent::GetButtonTypeDef($buttonID, $buttonName, $buttonType, $buttonClasses);				
			$buttonTypeDef .= $buttonSrc;

			return $buttonTypeDef;
		}
				
		function OutputContent_OnlineTrolleyDetailsCols($priceEntry, $cartEntry)
		{
			$qty = $cartEntry->qty;
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['cartqty'].'">'.$qty.'</td>'."\n");
		}
		
		function OutputContent_OnlineTrolleyRow($priceEntry, $cartEntry)
		{
			$showName = $priceEntry->showName;
			$perfDateTime = $this->myDBaseObj->FormatDateForDisplay($priceEntry->perfDateTime);
			$priceType = $this->OnlineStore_GetPriceType($priceEntry);
			$priceValue = $cartEntry->price;
			$qty = ($cartEntry->qty != '') ? $cartEntry->qty : 1;			
			$total = $priceValue * $qty;
			$formattedTotal = $this->myDBaseObj->FormatCurrency($total);
			$shipping = 0.0;
						
			if ($showName != '')
			{
				$dateTimeClass = $this->cssTrolleyBaseID.'-'.$this->cssColID['datetime'];
				$dateTimeClass .= $this->GetOnlineStoreDateClass($priceEntry);
									
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['name'].'">'.$showName.'</td>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$dateTimeClass.'">'.$perfDateTime.'</td>'."\n");
			}
			else
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<td colspan="2">&nbsp;</td>'."\n");
			}
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['ref'].'">'.$priceType.'</td>'."\n");
			$this->OutputContent_OnlineTrolleyDetailsCols($priceEntry, $cartEntry);
			StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['price'].'">'.$formattedTotal.'</td>'."\n");

			return $total;
		}
		
		function OutputContent_OnlineTrolleyFee($cartContents)
		{
			if ($cartContents->saleTransactionFee > 0)
			{				
				$priceEntry = new stdClass;
				$cartEntry = new stdClass;
				
				$priceEntry->showName = '';						
				$priceEntry->perfDateTime = '';						
				$priceEntry->priceType = __('Booking Fee', 'stageshow');
				
				$cartEntry->qty = '';
				$cartEntry->price = $cartContents->saleTransactionFee;
				
				StageShowLibEscapingClass::Safe_EchoHTML('<tr class="'.$this->cssTrolleyBaseID.'-row">'."\n");					
				$this->OutputContent_OnlineTrolleyRow($priceEntry, $cartEntry);
				StageShowLibEscapingClass::Safe_EchoHTML('<td>&nbsp;</td>'."\n");					
				StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
			}
			
			return $cartContents->saleTransactionFee;				
		}
		
		function OutputContent_OnlineTrolleyExtras($cartContents)
		{
			return 0;
		}
					
		function OnlineStore_GetPriceType($result)
		{
			return $result->priceType;
		}
		
		function OnlineStore_GetSortField($result)
		{
			// Includes perfID is case perfDateTime and priceType are the same ....
			$rtnval = $result->perfDateTime.'-'.$result->priceType.'-'.$result->perfID;
			if ($this->myDBaseObj->getOption('MaxTicketQty') == 1)
			{
				$rtnval .= '-'.time(); // Add timestamp
			}
			
			return $rtnval;
		}
		
		function OnlineStore_AddTrolleyExtras(&$cartEntry, $result)
		{
			foreach ($result as $fieldID => $fieldValue)
			{
				if (!isset($cartEntry->$fieldID))
				{
					$cartEntry->$fieldID = $fieldValue;
				}
			}
			$cartEntry->perfID = $result->perfID;
			$cartEntry->perfSeats = $result->perfSeats;
		}
		
		function CheckForSoldOut(&$cartContents, $newCartEntry)
		{
			$perfID = $newCartEntry->perfID;
			if (!isset($cartContents->perfqtys[$perfID]))
				$cartContents->perfqtys[$perfID] = 0;
			
			$seatsAvailable = $this->GetOnlineStoreItemsAvailable($newCartEntry);
			if ($seatsAvailable < 0) return 0;	// No limit!
			
			$seatsAvailable -= ($cartContents->perfqtys[$perfID] + $newCartEntry->qty);
			if ($seatsAvailable < 0)
			{
				$newCartEntry->qty += $seatsAvailable;
				return abs($seatsAvailable);
			}
			
			return 0;
		}
		
		function AddToTrolleyContents(&$cartContents, $newCartEntry)
		{
			if ($newCartEntry->qty <= 0) return;			

			$perfID = $newCartEntry->perfID;
			if (!isset($cartContents->perfqtys[$perfID]))
				$cartContents->perfqtys[$perfID] = 0;
			$cartContents->perfqtys[$perfID] += $newCartEntry->qty;				
				
			parent::AddToTrolleyContents($cartContents, $newCartEntry);
		}
		
		function RemoveFromTrolleyContents(&$cartContents, $itemID)
		{
			if (!isset($cartContents->rows[$itemID])) return;
			
			$cartEntry = $cartContents->rows[$itemID];
			
			$perfID = $cartEntry->perfID;
			$cartContents->perfqtys[$perfID] -= $cartEntry->qty;				
						
			parent::RemoveFromTrolleyContents($cartContents, $itemID);
			
			$cartContents->saleExtraDiscount = $this->myDBaseObj->GetExtraDiscount($cartContents);
			$cartContents->saleTransactionFee = $this->myDBaseObj->GetTransactionFee($cartContents);
		}
		
		function SaveInitialSaleEditTrolley(&$cartContents)
		{
			foreach ($cartContents->perfqtys as $zoneID => $zoneQty)
			{
				$cartContents->perfqtys[$zoneID] = 0;
			}
			
			parent::SaveInitialSaleEditTrolley($cartContents);
		}
		
		function IsOnlineStoreItemValid(&$cartEntry, $saleEntries)
		{
			// Test if this item is valid (i.e. Available))
			static $firstPass = true;
			$myDBaseObj = $this->myDBaseObj;
			
			if ($firstPass)
			{			
				// Just do this on the first call
				$firstPass = false;
				
				foreach ($saleEntries as $saleEntry)
				{
					$perfID = $saleEntry->perfID;
					
					if (!isset($this->seatsAvail[$perfID]))
					{
						// Get the maximum number of seats 
						$this->seatsAvail[$perfID] = $saleEntry->perfSeats;	
						if ($this->seatsAvail[$perfID] < 0) continue;
						
						// Deduct the total number of seats sold for this performance	
						$salesSummary = $myDBaseObj->GetPerformanceSummaryByPerfID($perfID);
						$this->seatsAvail[$perfID] -= $salesSummary->totalQty;				
					}
					
					if ($this->seatsAvail[$perfID] >= 0)
					{
						switch ($saleEntry->saleStatus)
						{
							case PAYMENT_API_SALESTATUS_TIMEOUT:
							case PAYMENT_API_SALESTATUS_SUSPENDED:
								break;
								
							default:
								// Add the number of seats for this performance for this sale entry
								// (i.e. assume that these seats have been deleted)
								$qty = isset($saleEntry->priceNoOfSeats) ? $saleEntry->ticketQty * $saleEntry->priceNoOfSeats : $saleEntry->ticketQty;						
								$this->seatsAvail[$perfID] += $qty;						
								break;
						}
					}
				}
			}

			$qty = isset($cartEntry->priceNoOfSeats) ? $cartEntry->qty * $cartEntry->priceNoOfSeats : $cartEntry->qty;						
			$perfID = $cartEntry->perfID;
			
			if (!isset($this->seatsAvail[$perfID]))
			{
				// This performance has been added to the sale
				$salesSummary = $myDBaseObj->GetPerformanceSummaryByPerfID($perfID);
					
				// Get the maximum number of seats 
				$this->seatsAvail[$perfID] = $salesSummary->perfSeats;	
				if ($this->seatsAvail[$perfID] > 0)
				{
					// Deduct the total number of seats sold for this performance	
					$this->seatsAvail[$perfID] -= $salesSummary->totalQty;				
				}
			}
			
			if ($this->seatsAvail[$perfID] < 0)
				return true;
				
			if ($this->seatsAvail[$perfID] < $qty)
			{
				$this->seatsAvail[$perfID] = 0;
				$salesSummary = $myDBaseObj->GetPerformanceSummaryByPerfID($perfID);
				$perfDateTime = $this->myDBaseObj->FormatDateForDisplay($salesSummary->perfDateTime);
				$this->checkoutMsg = __('Insufficient seats', 'stageshow').' - ('.$salesSummary->showName.' '.$perfDateTime.')';
				return false;
			}
				
			$this->seatsAvail[$perfID] -= $qty;
			
			return true;
		}
					
	}
}

?>