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

if (!class_exists('StageShowCartPluginParentClass')) 
{
	if (!defined('STAGESHOWLIB_DATABASE_FULL'))
	{
		if (!class_exists('StageShowLibSalesCartPluginClass')) 
			include STAGESHOW_INCLUDE_PATH.'stageshow_trolley_sales.php';
		
		class StageShowCartPluginParentClass extends StageShowSalesCartPluginClass {}
	}
	else
	{
		if (!class_exists('StageShowLibSalesPluginClass')) 
			include STAGESHOW_INCLUDE_PATH.'stageshow_sales.php';
		
		class StageShowCartPluginParentClass extends StageShowSalesPluginClass {}
	}
}

if (!class_exists('StageShowCartPluginClass')) 
{
	if (!defined('STAGESHOW_BACKEND_DRILLDOWN'))
		define('STAGESHOW_BACKEND_DRILLDOWN', true);
	
	class StageShowCartPluginClass extends StageShowCartPluginParentClass // Define class 
	{
		var $ourPluginName;
		var $myDBaseObj;
		var	$env;
		
		var	$adminClassFilePrefix;
		var $adminClassPrefix;
		
		const PAGEMODE_SELECTSEATS = 'selectseats';
		const PAGEMODE_SHOWSEATS = 'showseats';
		const PAGEMODE_VIEWSALES = 'viewsales';
		const PAGEMODE_QUICKSALE = 'quicksale';
		const PAGEMODE_LOCKOUTSEATS = 'lockoutseats';
		
		const PAGEMODE_RESERVATIONS_FORM = 'reservationsform';
		
		var $usingDrillDown = false;
		
		function __construct($caller)		 
		{
			if (defined('STAGESHOW_ERROR_REPORTING')) 
			{
				error_reporting(STAGESHOW_ERROR_REPORTING);
			}
			
			$myDBaseObj = $this->CreateDBClass($caller);
			
			$this->myDBaseObj = $myDBaseObj;
					
			parent::__construct();
			
			$this->myDBaseObj->pluginSlug = 'stageshow';
			$this->adminClassFilePrefix = 'stageshow';
			$this->adminClassPrefix = 'StageShow';
			
			$this->env = array(
			    'Caller' => $caller,
			    'PluginObj' => $this,
			    'DBaseObj' => $this->myDBaseObj,
			);

			$this->getStageshowOptions();

			$this->colID['cartqty'] = '<span>'.(defined('STAGESHOW_BOXOFFICECOL_CARTQTY') ? STAGESHOW_BOXOFFICECOL_CARTQTY : __('Quantity', 'stageshow').'</span>/<span>'.__('Seat', 'stageshow')).'</span>';
			$this->cssColID['cartqty'] = "seat";	
		}
		
		static function CreateDBClass($caller)
		{			
			if (!class_exists('StageShowCartDBaseClass')) 
				include STAGESHOW_INCLUDE_PATH.'stageshow_trolley_dbase_api.php';
				
			return new StageShowCartDBaseClass($caller);		
		}
		
		//Returns an array of admin options
		function getStageshowOptions() 
		{
			$myDBaseObj = $this->myDBaseObj;
			return $myDBaseObj->adminOptions;
		}
		// Saves the admin options to the options data table
		
		// ----------------------------------------------------------------------
		// Activation / Deactivation Functions
		// ----------------------------------------------------------------------
	
		function OnWPInit()
		{
			// This function should be called by the 'init' action of the Plugin
			// Action requiring setting of Cookies should be done here
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'TerminalLocation')) 
			{
				$this->CheckAdminReferer(); 
						
				// Cookies must be set here (if required))					
				$TerminalLocation = StageShowLibUtilsClass::GetHTTPTextElem('post', 'TerminalLocation');
				setcookie('TerminalLocation', $TerminalLocation, current_time('timestamp')+(86400*365)); // , COOKIEPATH, COOKIE_DOMAIN, false);
				
				// Update the $_COOKIE global so the location appears on this pass of the code
				$_COOKIE['TerminalLocation'] = $TerminalLocation;
			}	
					
			$myDBaseObj = $this->myDBaseObj;
			$myDBaseObj->init($this->env['Caller']);
			
			// Get plugin version number
			wp_update_plugins();
		}

		function Cart_OutputContent_OnlineStoreMain($atts)
		{			
			switch ($this->pageMode)
			{
				case self::PAGEMODE_SELECTSEATS: 
				case self::PAGEMODE_SHOWSEATS: 
					$this->suppressUI = true;
					return;
					
				case self::PAGEMODE_LOCKOUTSEATS: 
				default:
					break;
			}

			if (isset($atts['style']) && ($atts['style'] == 'calendar'))
			{
				$caldate = StageShowLibUtilsClass::GetHTTPDateTime($_REQUEST, 'caldate');
				if ($caldate == '')
				{
					$months = ($atts['months'] != '') ? $atts['months'] : -1;
					$cols = ($atts['cols'] != '') ? $atts['cols'] : 1;

					ob_end_clean();	// Stop buffering here (discarded anyway) ... so dbase debug works when enabled
								
					include 'include/stageshow_calendar.php'; 
					$calObj = new StageShowCalendarClass($this->myDBaseObj); 
					$results = $this->myDBaseObj->GetActivePerformances();

					$calObj->separateMonths = ($atts['separate'] != '');
					
					foreach ($results as $index => $result)
					{
						if ($result->perfSeatingID != 0)
						{
							$results[$index]->perfSeats = $this->myDBaseObj->GetTotalZoneSeats($result->perfSeatingID);
						}
						
						$results[$index]->seatsAvailable = $this->GetOnlineStoreItemsAvailable($result);
					}

					ob_start();
					if ($months == 'active')
					{
						$calObj->activeMonths = true;
						$months = -1;
					}
					$calObj->monthsInRow = $cols;
					$this->boxofficeOverride = $calObj->OutputCalender($results, $months); 
				}
				else
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" name="caldate" id="caldate" value="'.$caldate.'"/>'."\n");						
				}
			}
			
			if ($this->adminPageActive)
			{
				$this->usingDrillDown = STAGESHOW_BACKEND_DRILLDOWN;	// Force Drilldown mode for back-end
			}
			else
			{
				$this->usingDrillDown = (isset($atts['style']) && ($atts['style'] == 'drilldown'));
			}

			switch ($this->pageMode)
			{
				case self::PAGEMODE_RESERVATIONS_FORM: 
					$this->suppressUI = true;
					return;
				default:
					break;
			}
			
			parent::Cart_OutputContent_OnlineStoreMain($atts);				
		}
		
		function OutputContent_TrolleyJQueryPostvars()
		{
			$jqCode = parent::OutputContent_TrolleyJQueryPostvars();
			
			if ($this->myDBaseObj->isOptionSet('UseNoteToSeller'))
			{
				$jqCode .= '
				var saleNoteToSellerElem = document.getElementById("saleNoteToSeller");
				if (saleNoteToSellerElem)
				{
					postvars.saleNoteToSeller = saleNoteToSellerElem.value;
				}';
			}
				
			return $jqCode;
		}

		function OutputContent_OnlinePurchaserDetails($cartContents, $extraHTML = '')
		{
			$formHTML = $extraHTML;
			
			if ($extraHTML == '') 
			{
				$formHTML .= '
				<input type="hidden" id="saleStatus" name="saleStatus" value="'.PAYMENT_API_SALESTATUS_COMPLETED.'"/>
				';
			}
			
			if ($this->myDBaseObj->getOption('UseNoteToSeller') && isset($cartContents->saleNoteToSeller))
			{
				$rowsDef = '';
				$noteToSeller = $cartContents->saleNoteToSeller;
				
				$formHTML .=  '
				<tr class="stageshow-boxoffice-formRow">
				<td class="stageshow-boxoffice-formFieldID">'.__('Message To Seller', 'stageshow').'</td>
				<td class="stageshow-boxoffice-formFieldValue" colspan="2">
				<textarea name="saleNoteToSeller" id="saleNoteToSeller" '.$rowsDef.'>'.$noteToSeller.'</textarea>
				</td>
				</tr>
				';
			}
			
			$formHTML = parent::OutputContent_OnlinePurchaserDetails($cartContents, $formHTML);
			
			return $formHTML;
		}
		
		function GetOnlineStoreProducts($atts)
		{
			$myDBaseObj = $this->myDBaseObj;

			$showID = StageShowLibMigratePHPClass::Safe_htmlspecialchars_decode($atts['id']);
			$perfID = StageShowLibMigratePHPClass::Safe_htmlspecialchars_decode($atts['perf']);

			if (($showID == '') && ($perfID == ''))
			{
				return parent::GetOnlineStoreProducts($atts);
			}
			
			if (is_numeric($perfID))
			{
				$results = $myDBaseObj->GetPricesListByPerfID($perfID, true);
			}
			else
			{
				$showID = $myDBaseObj->GetShowID($showID);
				if ($perfID != '')
				{
					$results = $myDBaseObj->GetPricesListByPerfDateTime($showID, $perfID);
				}
				else
				{
					$results = $myDBaseObj->GetPricesListByShowID($showID, true);
				}
			}
			if (count($results) == 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<!-- StageShow BoxOffice - No Output for PerfID=$perfID -->\n");
			}
			
			return $results;
		}
		
		function OutputContent_OnlineTrolleyDiscount($cartContents)
		{
			if ($cartContents->saleExtraDiscount > 0)
			{				
				$priceEntry = new stdClass;
				$cartEntry = new stdClass;
				
				$priceEntry->showName = '';						
				$priceEntry->perfDateTime = '';						
				$priceEntry->priceType = __('Discount', 'stageshow');
				
				$cartEntry->qty = ''; // 1;
				$cartEntry->price = $cartContents->saleExtraDiscount;
				
				StageShowLibEscapingClass::Safe_EchoHTML('<tr class="'.$this->cssTrolleyBaseID.'-row">'."\n");					
				$this->OutputContent_OnlineTrolleyRow($priceEntry, $cartEntry);
				StageShowLibEscapingClass::Safe_EchoHTML('<td>&nbsp;</td>'."\n");					
				StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
			}
			
			return $cartContents->saleExtraDiscount;				
		}
		
		function OutputContent_OnlineTrolleyExtras($cartContents)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$extrasTotal = 0;
			
			if ($myDBaseObj->isOptionSet('AllowDonation'))
			{
				$onKeyUpHandler = ' onkeyup="StageShowLib_OnChangeTrolleyTotal(this)"'; 
				$onKeypressHandler = ' onkeypress="StageShowLib_OnKeypressNumericOnly(this, event, '."'U'".', 0, true);" ';
				$onChangeHandler = ' onchange="StageShowLib_OnChangeNumericOnly(this, event, '."'U'".', 0, true);" ';
				
				StageShowLibEscapingClass::Safe_EchoHTML('<tr class="'.$this->cssTrolleyBaseID.'-donationrow">'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<td colspan="'.($this->trolleyHeaderCols-4).'">&nbsp;</td>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-donationrow-text">'.__('Donation', 'stageshow').'</td>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<td>&nbsp;</td>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-donation">');
				
				if ($this->saleConfirmationMode)
				{
					StageShowLibEscapingClass::Safe_EchoHTML($cartContents->saleDonation);
				}
				else
				{
					StageShowLibEscapingClass::Safe_EchoHTML('<input type="text" class="stageshow-trolley-ui" id="saleDonation" name="saleDonation" '.$onKeypressHandler.$onKeyUpHandler.$onChangeHandler.' value="'.$cartContents->saleDonation.'" maxlength=6 size=6 autocomplete="off"/>');
				}	
						
				StageShowLibEscapingClass::Safe_EchoHTML('</td>'."\n");
				if (!$this->saleConfirmationMode)
					StageShowLibEscapingClass::Safe_EchoHTML('<td>&nbsp;</td>'."\n");
				StageShowLibEscapingClass::Safe_EchoHTML("</tr>\n");
				
				if (isset($cartContents->saleDonation) && is_numeric($cartContents->saleDonation)) $extrasTotal += $cartContents->saleDonation;				
			}
			
			if ($myDBaseObj->isOptionSet('PostTicketsEnabled'))
			{				
				$salePostTickets = isset($cartContents->salePostTickets) && $cartContents->salePostTickets;

				$salePostage = $myDBaseObj->getOption('PostageFee');
				$salePostageText = $myDBaseObj->FormatCurrency($salePostage);
				$postageRowStyle = $salePostTickets ? '' : 'style="display: none;"';
				StageShowLibEscapingClass::Safe_EchoHTML('
					<tr class="'.$this->cssTrolleyBaseID.'-postage" id="'.$this->cssTrolleyBaseID.'-postagerow" '.$postageRowStyle.'>
					<td colspan="'.($this->trolleyHeaderCols-4).'">&nbsp;</td>
					<td>'.__('Postage', 'stageshow').'</td>
					<td>&nbsp;</td>
					<td><input type="hidden" name="salePostage" id="salePostage" value="'.$salePostageText.'" />'.$salePostageText.'</td>
					</tr>
					');
					
				if ($salePostTickets) $extrasTotal += $salePostage;
			}
			
			return $extrasTotal;	
		}
				
		function OutputContent_OnlineTrolleyFooterRows($cartContents)
		{
			if (!$this->myDBaseObj->isOptionSet('PostTicketsEnabled'))
				return;
			
			if (!$this->saleConfirmationMode)
			{
				$checked = $cartContents->salePostTickets ? 'checked="yes" ' : '';
				StageShowLibEscapingClass::Safe_EchoHTML('
					<tr class="stageshow-trolley-posttickets">
					<td id="stageshow-trolley-posttickets-checkbox" colspan="'.($this->trolleyHeaderCols).'">
					<span>'.__('Send tickets by post', 'stageshow').'</span>&nbsp;
					<input type="checkbox" class="stageshow-trolley-ui" name="salePostTickets" id="salePostTickets" '.$checked.' onchange="StageShowLib_OnChangeTrolleyTotal(this)" />
					</td>
					</tr>
					');				
			}
		}
		
		function GetOnlineStoreItemNote($result, $posn)
		{
			if (!is_null($result->perfNote) && (StageShowLibMigratePHPClass::Safe_strlen($result->perfNote) > 0) && ($result->perfNotePosn === $posn))
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<tr><td class="stageshow-boxoffice-perfnote stageshow-boxoffice-row-perf'.$result->perfID.'">'.$result->perfNote."<td><tr>\n"); 
			}					
		}
			
		function OnlineStore_AddTrolleyExtras(&$cartEntry, $result)
		{
			$cartEntry->ckoutDetailsTemplate = $result->perfDetailsTemplate;

			$cartEntry->zoneID = $result->zoneID;
			$cartEntry->zoneSpec = $result->zoneSpec;
			$cartEntry->zoneAllocSeats = $result->zoneAllocSeats;		
			$cartEntry->zoneSeats = $result->zoneSeats;		
			$cartEntry->seatingID = $result->seatingID;
			$cartEntry->seatLocns = isset($result->ticketSeat) ? $result->ticketSeat : '';
			
			$seatNames = StageShowZonesDBaseClass::DecodeSeatsList($this->myDBaseObj,  $cartEntry->seatLocns, $cartEntry->seatingID);
			if (isset($result->saleStatus) && ($result->saleStatus == PAYMENT_API_SALESTATUS_SUSPENDED))
			{
				// Break down seats list ... and check what is available
				$seatLocnsList = explode(',', $cartEntry->seatLocns);
				$seatNamesList = explode(',', $seatNames);
				$seatNames = '';
				$delim = '';
				foreach ($seatLocnsList as $index => $seatLocn)
				{
					if (!$this->myDBaseObj->IsSeatAvailable($result->perfID, $seatLocn))
					{
						$seatNames .= "$delim(".$seatNamesList[$index].")";
					}
					else
					{
						$seatNames .= $delim.$seatNamesList[$index];
					}
					$delim = ',';
				}
			}
			$cartEntry->seatNames = $seatNames;
			
			if (isset($result->ticketID))
			{
				$ticketMetaList = $this->myDBaseObj->GetSaleItemMeta($result->ticketID);
				foreach($ticketMetaList as $ticketMeta)
				{
					$fieldID = $ticketMeta->meta_key;
					$fieldValue = $ticketMeta->meta_value;
					$cartEntry->customFields[$fieldID] = $fieldValue;
				}
			}
			
			$cartEntry->priceNoOfSeats = $result->priceNoOfSeats;
			
			parent::OnlineStore_AddTrolleyExtras($cartEntry, $result);
		}
			
		function GetButtonTypeDef($buttonID, $buttonName = '', $buttonType = 'submit', $buttonClasses = '')
		{
			$buttonSrc = '';
			$buttonURL;
			
			if (!$this->adminPageActive)
			{
				switch ($buttonID)
				{					
					case 'selectshow':
					case 'selectperf':
					case 'selectseats':
					case 'seatsselected':
					case 'seatsavailable':
					case 'updateprices':
						if ($this->myDBaseObj->ButtonHasURL($buttonID, $buttonURL))
						{
							$buttonType = 'image';
							$buttonSrc = ' src="'.$buttonURL.'"';
						}
						break;
						
					case 'reserve':
					case 'reserveuserform':
						if ($this->myDBaseObj->ButtonHasURL('reserve', $buttonURL))
						{
							$buttonType = 'image';
							$buttonSrc = ' src="'.$buttonURL.'"';
						}
						break;
				}				
			}
			
			$buttonTypeDef = parent::GetButtonTypeDef($buttonID, $buttonName, $buttonType, $buttonClasses);
			$buttonTypeDef .= $buttonSrc;

			return $buttonTypeDef;
		}
				
		function OutputContent_HasCheckoutMode($cartContents, $checkoutMode)
		{
			// Check for a "Reserve only" trolley entry
			if (!isset($cartContents->rows)) return false;
			
			foreach ($cartContents->rows as $cartIndex => $cartEntry)
			{
				if ( (isset($cartEntry->priceCheckoutMode))
				  && ($cartEntry->priceCheckoutMode == $checkoutMode))
				{
					return true;
				}
			}				

			return false;
		}
				
		function OutputContent_HideCheckoutButton($cartContents)
		{
			$hideButton = false;
			
			if ( ($this->myDBaseObj->getOption('ReservationsMode') != STAGESHOW_RESERVATIONSMODE_DISABLED)
			  && ($this->myDBaseObj->isOptionSet('CheckoutModesEnabled')) )	
			{
				$hideButton = $this->OutputContent_HasCheckoutMode($cartContents, STAGESHOW_CARTBUTTONS_RESERVEONLY);
			}  
					
			return apply_filters('stageshow_filter_hidecheckout', $hideButton, $cartContents);
		}
		
		function OutputContent_HideReserveButton($cartContents)
		{
			$hideButton = false;
			
			if ( ($this->myDBaseObj->getOption('ReservationsMode') != STAGESHOW_RESERVATIONSMODE_DISABLED)
			  && ($this->myDBaseObj->isOptionSet('CheckoutModesEnabled')) )	
			{
				$hideButton = $this->OutputContent_HasCheckoutMode($cartContents, STAGESHOW_CARTBUTTONS_CHECKOUTONLY);
			}  
					
			return apply_filters('stageshow_filter_hidereserve', $hideButton, $cartContents);
		}
		
		function OutputContent_OnlineCheckoutButton($cartContents)
		{
			if (!$this->saleConfirmationMode && isset($cartContents->rows))
			{
				foreach ($cartContents->rows as $cartEntry)
				{
					// Check seat entry to test if it has all the seats defined
					if ($this->AreSeatsDefined($cartEntry))
					{
						continue;
					}
					
					if ($this->adminPageActive) return;	// Exit without showing the "Next" button
					
					$buttonText = __('Select Seats', 'stageshow');
					$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'selectseats');
					StageShowLibEscapingClass::Safe_EchoHTML("<input $buttonType />\n");
					return '';
				}				
			}
			
			if ($this->saleConfirmationMode)
			{
				$buttonText = __('Confirm and Pay', 'stageshow');
				$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'confirmpandpay');
				StageShowLibEscapingClass::Safe_EchoHTML("<input $buttonType />\n");
				return '';
			}
			
			$checkoutButtonsHTML = '';
			if (!$this->adminPageActive)
			{
				$buttonText = __('Reserve', 'stageshow');
				$reservationMode = $this->myDBaseObj->getOption('ReservationsMode');
				$canDoReservation = true;
				
				// Check for a "Checkout only" trolley entry
				if ($this->OutputContent_HideReserveButton($cartContents))
				{
					$canDoReservation = false;
				}

				if ($canDoReservation)
				{
					switch ($reservationMode)
					{
						case STAGESHOW_RESERVATIONSMODE_LOGIN:
						case STAGESHOW_RESERVATIONSMODE_LOGINFORM:
							$canDoReservation = current_user_can(STAGESHOWLIB_CAPABILITY_RESERVEUSER); 
							break;
					}
				}				
				
				if ($canDoReservation)
				{
					$buttonType = '';
					switch ($reservationMode)
					{
						case STAGESHOW_RESERVATIONSMODE_LOGIN:
							if ($this->myDBaseObj->HasCheckoutForm($cartContents))
							{
								$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'reserveform');
								break;
							}
							$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'reserve');
							break;
												
						case STAGESHOW_RESERVATIONSMODE_LOGINFORM: 
						case STAGESHOW_RESERVATIONSMODE_FORM: 
							$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'reserveuserform');
							break;
							
						case STAGESHOW_RESERVATIONSMODE_DISABLED: 
						default:
							break;
					}		
					
					if ($buttonType != '')
					{
						$checkoutButtonsHTML  = '<input '.$buttonType.'/>';
						$checkoutButtonsHTML .=  '&nbsp;&nbsp;&nbsp;&nbsp;';
					}		
						
				}
			}
			
			$checkoutButtonsHTML .= parent::OutputContent_OnlineCheckoutButton($cartContents);
			return $checkoutButtonsHTML;
		}
		
		function OnlineStore_HandleTrolleyButtons($cartContents)
		{
			$discountCode = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleDiscountCode');
			$this->myDBaseObj->GetDiscountCode($discountCode);
			
			if ($cartContents->disCode != $discountCode)
			{
				$cartContents->disCode = $discountCode;
				$this->OnlineStore_UpdateTrolleyPrices($cartContents);
			}
			
			$perfID = 0;
			if ($this->adminPageActive)
			{
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
						case 'editseats': 
							// Get the product ID from posted data
							$_REQUEST['selectseats'] = true;
							$perfID = $cartContents->rows[$reqId]->perfID;
							break;
					}					
				}				
			}

			if ($this->myDBaseObj->IsButtonClicked('selectseats') && isset($cartContents->rows))
			{
				$this->PassPostValueBack('saleDonation');
				$this->PassPostValueBack('saleNoteToSeller');
				$this->PassPostValueBack('salePostTickets');
				$this->PassPostValueBack('saleDiscountCode');
				
				// Find first entry that needs a seat selection
				// and get the number of seats for that performance and zone
				$seatingID = 0;
				
				if ($perfID == 0)
				{
					foreach ($cartContents->rows as $cartEntry)
					{
						if ($this->AreSeatsDefined($cartEntry))
						{
							continue;
						}

						if ($perfID == 0) 
						{
							$perfID = $cartEntry->perfID;
						}
						else if ($perfID != $cartEntry->perfID)
						{
							continue;
						}
					}					
				}
				
				if ($perfID != 0)
				{
					// Show The Seating Template
					$this->OutputContent_SelectSeats($perfID);
					return;					
				}
			}

			$availSeatsPerfID = StageShowLibUtilsClass::GetHTTPInteger($_REQUEST, 'showSeatsPerfID');
			if ($availSeatsPerfID)
			{
				$this->OutputContent_SelectSeats($availSeatsPerfID, self::PAGEMODE_SHOWSEATS);
				return;					
			}

			if ($this->myDBaseObj->IsButtonClicked('seatsselected') && isset($cartContents->rows))
			{
				// Allocated Seating - Box Office - Seats have been selected
				$this->OnlineStore_AddSeatDetails($cartContents);
			}
			
			if ( $this->myDBaseObj->IsButtonClicked('reserveuserform') 
			  && isset($cartContents->rows)
			  )
			{
				$doReservationForm = false;
				if ($this->myDBaseObj->getOption('ReservationsMode') == STAGESHOW_RESERVATIONSMODE_FORM)
				{
					$doReservationForm = true;
				}
				
				if ( ($this->myDBaseObj->getOption('ReservationsMode') == STAGESHOW_RESERVATIONSMODE_LOGINFORM)
				  && current_user_can(STAGESHOWLIB_CAPABILITY_RESERVEUSER) )
				{
					$doReservationForm = true;
				}

				if ($doReservationForm)
				{
					$this->pageMode = self::PAGEMODE_RESERVATIONS_FORM;
					
					$clientFields = array(
						'saleFirstName' => __('First Name', 'stageshow'),
						'saleLastName'  => __('Last Name', 'stageshow'),
						'saleEMail'     => __('EMail', 'stageshow'),
						'salePPStreet'  => __('Address', 'stageshow'),
						'salePPCity'    => __('Town/City', 'stageshow'),
						'salePPState'   => __('County', 'stageshow'),
						'salePPZip'     => __('Postcode', 'stageshow'),
						'salePPCountry' => __('Country', 'stageshow'),
						'salePPPhone'   => __('Phone', 'stageshow'),
						);
					
					$html = $this->Cart_OnlineStore_GetPurchaserDetailsForm($clientFields);
					StageShowLibEscapingClass::Safe_EchoHTML($html);

					$buttonText = __('Reserve', 'stageshow');
					if ($this->myDBaseObj->HasCheckoutForm($cartContents))
						$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'reserveform', '', '', 'stageshow-trolley-ui button-primary');
					else
					{
						$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'reserve', '', '', 'stageshow-trolley-ui button-primary');
						$buttonType = StageShowLibMigratePHPClass::Safe_str_replace('_OnClickReserve', '_OnClickReserveform', $buttonType);					
					}
					StageShowLibEscapingClass::Safe_EchoHTML("<input $buttonType />\n");
					
					return false;
				}
			}
			
			return parent::OnlineStore_HandleTrolleyButtons($cartContents);
		}
		
		function OutputContent_ShowSeatsList($perfId, $pageMode)
		{
			$myDBaseObj = $this->myDBaseObj;

			// TODO - Allocated Seating - Build Available Seats List
			$perfDetails = $myDBaseObj->GetPerformancesListByPerfID($perfId);
			$seatingID = $perfDetails[0]->perfSeatingID;
			
			if ($seatingID <= 0) 
			{
				return false;
			}
			
			$jsCode = '';
			
			StageShowLibEscapingClass::Safe_EchoHTML("<h2>");
			StageShowLibEscapingClass::Safe_EchoHTML($perfDetails[0]->showName);
			if ($perfDetails[0]->showDateTimeMode != STAGESHOW_DATETIMEOPTION_NONE)
			{
				StageShowLibEscapingClass::Safe_EchoHTML(' - '.$this->myDBaseObj->FormatDateForDisplay($perfDetails[0]->perfDateTime));				
			}
			StageShowLibEscapingClass::Safe_EchoHTML("</h2>");
			
			$seatInfoSeatIDs   = "\n";
			$seatInfoSaleIDs = "\n";	
			$seatInfoTicketMeta = "\n";	
			
			switch($pageMode)
			{
				case self::PAGEMODE_LOCKOUTSEATS:
					$saleID = $myDBaseObj->GetSaleLockoutID($perfId);
					break;
					
				case self::PAGEMODE_VIEWSALES:
				case self::PAGEMODE_QUICKSALE:				
					$saleID = 0;
					break;
					
				default:
					$cartContents = $this->GetTrolleyContents();
					$saleID = isset($cartContents->saleID) ? $cartContents->saleID : 0;
					break;
			}
			$soldSeats = $myDBaseObj->GetSoldSeats($perfId, $saleID);
			
			$showSeatMeta = current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER) && ($pageMode != StageShowCartPluginClass::PAGEMODE_SELECTSEATS);
			$showSeatMeta = apply_filters('stageshow_filter_showseatmeta', $showSeatMeta, $pageMode);

			if ($showSeatMeta)
			{
				foreach ($soldSeats as $soldSeat)
				{
					// Find any custom fields
					$ticketMeta = '';
					$ticketMetaDelim = '|';
					foreach ($soldSeat as $fieldName => $fieldValue)
					{
						switch($fieldName)
						{
							case 'saleID':
							case 'saleStatus':
							case 'ticketID':
							case 'ticketSeat':
								break;
								
							default:
								$showMetaField = apply_filters('stageshow_filter_showmetafield', $fieldName, $pageMode);
								if (!$showMetaField) break;
								if ($fieldValue != '')
								{
									$ticketMeta .= "$fieldName: $fieldValue $ticketMetaDelim";
								}
								break;
						}
					}

/*					
					$saleURL = "http://scut.com";
					$ticketMeta .= "<a href=$saleURL>SaleID: $saleID</a> $ticketMetaDelim";
					$ticketMeta .= "SaleID: $saleID $ticketMetaDelim";
*/
					
					$seats = explode(',', StageShowLibMigratePHPClass::Safe_trim($soldSeat->ticketSeat));
					foreach ($seats as $seatDef)
					{
						$seatInfoSeatIDs .= '"'.$seatDef.'",'."\n";			
						$seatInfoSaleIDs .= '"'.$soldSeat->saleID.'",'."\n";
						$seatInfoTicketMeta .= '"'.$ticketMeta.'",'."\n";
					}
				}
				
				$jsCode .= '
					var seatInfoSeatIDs = new Array('.$seatInfoSeatIDs.' "");
					var seatInfoSaleIDs = new Array('.$seatInfoSaleIDs.' "");					
					var seatInfoTicketMeta = new Array('.$seatInfoTicketMeta.' "");
				';
			}
			
			$seatsStatus = array();
			$zonesCount = array();

			// Add all entries that are already booked
			foreach ($soldSeats as $soldSeat)
			{
				$seats = explode(',', StageShowLibMigratePHPClass::Safe_trim($soldSeat->ticketSeat));
				foreach ($seats as $seatDef)
				{
					switch ($soldSeat->saleStatus)
					{
						case PAYMENT_API_SALESTATUS_RESERVED:
							$seatsStatus[$seatDef] = 'reserved';
							break;	
						
						case PAYMENT_API_SALESTATUS_LOCKOUT:
							$seatsStatus[$seatDef] = 'locked';
							break;						

						default:
							$seatsStatus[$seatDef] = 'booked';
							break;
					}
				}	
			}
		
			switch ($pageMode)
			{
				case self::PAGEMODE_SELECTSEATS:
					$buttonText = __('Continue', 'stageshow');
					$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'seatsselected');				
					
					if (!isset($cartContents->rows)) break;

					// Add entries from "Trolley" to array
					foreach ($cartContents->rows as $index => $cartRow)
					{
						if ($cartRow->perfID != $perfId)
						{
							continue;
						}
						
						if ($cartRow->zoneAllocSeats)
						{
							$zonesCount[$cartRow->zoneID] = isset($zonesCount[$cartRow->zoneID]) ? $zonesCount[$cartRow->zoneID]+$cartRow->priceNoOfSeats : $cartRow->priceNoOfSeats;
						}
						
						if ($cartRow->seatLocns != '')
						{
							$seats = explode(',', StageShowLibMigratePHPClass::Safe_trim($cartRow->seatLocns));
							foreach ($seats as $seatDef)
							{
								if (isset($seatsStatus[$seatDef]))
								{
									//$seatsStatus[$seatDef] = 'seaterror';					
									continue;
								}
					
								$seatsStatus[$seatDef] = 'selected';					
							}
						}
					}	
					break;			

				case self::PAGEMODE_LOCKOUTSEATS:
					$buttonText = __('Save', 'stageshow');
					$buttonSave = $this->GetButtonTextAndTypeDef($buttonText, 'closewindow');				
					
					$buttonText = __('Cancel', 'stageshow');
					$buttonCancel = $this->GetButtonTextAndTypeDef($buttonText, 'cancel', '', '', 'button-secondary');				

					$buttonType = "$buttonSave />\n<input $buttonCancel";
					StageShowLibEscapingClass::Safe_EchoHTML("<input $buttonType />\n");
					
					$zonesList = $myDBaseObj->GetZoneList($perfId);
					foreach ($zonesList as $zone)
					{
						$zonesCount[$zone->zoneID] = ($zone->zoneAllocSeats) ? 1 : 0;
					}
					
					// Add entries from Locked Out Sale to array
					if ($saleID > 0)
					{
						$saleDetails = $myDBaseObj->GetSale($saleID);
						foreach ($saleDetails as $saleEntry)
						{
							$seats = preg_split('/\s+/', StageShowLibMigratePHPClass::Safe_trim($saleEntry->ticketSeat));
							foreach ($seats as $seatDef)
							{
								$seatsStatus[$seatDef] = 'selected';					
							}
						}							
					}
					break;			
					
				case self::PAGEMODE_VIEWSALES:				
				case self::PAGEMODE_SHOWSEATS:
					$buttonText = __('Close Window', 'stageshow');
					$buttonId = 'closewindow';
					$buttonType = $this->GetButtonTextAndTypeDef($buttonText, $buttonId);	
					StageShowLibEscapingClass::Safe_EchoHTML("<input $buttonType />\n");
					$buttonType = StageShowLibMigratePHPClass::Safe_str_replace($buttonId, "$buttonId-bottom", $buttonType);
					// ... Drop into next case
							
				case self::PAGEMODE_QUICKSALE:				
					$zonesList = $myDBaseObj->GetZoneList($perfId);
					foreach ($zonesList as $zone)
					{
						$zonesCount[$zone->zoneID] = 0;
						$zonesNames[$zone->zoneID] = $zone->zoneRef;
					}
					break;			
			}

			// If STAGESHOW_IDENTIFY_RESERVED is defined then booked and reserved seats are listed separately
			// with reserved seats marked as "allocated" seats. Otherwise both booked and reserved seats are
			// listed as "reserved" seats
			$selectedSeatsEntries = "\n";
			$reservedSeatsEntries = "\n";	
			$allocatedSeatsEntries = "\n";
			$bookedSeatsEntries   = "\n";
			$lockedSeatsEntries   = "\n";

			foreach ($seatsStatus as $seatID => $seatStatus)
			{
				$varDef = '"'.$seatID.'",'."\n";

				switch ($seatStatus)
				{
					case 'selected': 
						$selectedSeatsEntries .= $varDef; 
						break;			
								
					case 'booked':   
						if (defined('STAGESHOW_IDENTIFY_RESERVED'))
						{
							$bookedSeatsEntries .= $varDef; 
							break;
						}
						$reservedSeatsEntries .= $varDef; 
						break;
						
					case 'locked':   
						if (defined('STAGESHOW_IDENTIFY_LOCKED'))
						{
							$lockedSeatsEntries .= $varDef; 
							break;
						}
						$reservedSeatsEntries .= $varDef; 
						break;
						
					case 'reserved': 
						if (defined('STAGESHOW_IDENTIFY_RESERVED'))
						{
							$allocatedSeatsEntries .= $varDef; 
							break;
						}
						$reservedSeatsEntries .= $varDef; 
						break;
				}
			}
			
			// Load Seating Template
			$layoutDetails = StageShowZonesDBaseClass::GetSeatingTemplate($myDBaseObj, $seatingID);
			
			$minSeatSpace = $myDBaseObj->getOption('MinSeatSpace');
			if (!is_numeric($minSeatSpace)) $minSeatSpace = 0;
			if (($minSeatSpace>0) && current_user_can(STAGESHOWLIB_CAPABILITY_SALESUSER))
			{
				if (!StageShowLibMigratePHPClass::Safe_strpos($layoutDetails[0]->seatingTemplate, $myDBaseObj->adminOptions['BOClass']."-leftend"))
				{
					$minSeatSpace = 0;
					$headerMsg  = __("Old Seating Template Version", 'stageshow');
				}
				else if (!$myDBaseObj->isDbgOptionSet('Dev_SeatSpacingChecked'))
				{
					$minSeatSpace = 0;
					$headerMsg  = __("User Logged In", 'stageshow');
				}
				
				if ($minSeatSpace == 0)
				{
					$minSeatSpaceEnabled = 'false';
					if ($pageMode == self::PAGEMODE_SELECTSEATS)
					{
						$headerMsg = __("Minimum Seat Spacing Blocking Disabled", 'stageshow').' - '.$headerMsg;
						StageShowLibEscapingClass::Safe_EchoHTML("<div id=SeatSpacingBlocking class=stageshow-error>$headerMsg</div>\n");
					}
				}
			}
			
			$CantReserveSeatMessage = __("Invalid Seat Selection", 'stageshow');				
			$canBookLocked = $myDBaseObj->UserCanBookLockout() ? 'true' : 'false';
			
			// Inject JS into output 
			$jsCode .= '
				var SeatAllowLocked = '.$canBookLocked.';
				var minSeatSpace = '.$minSeatSpace.';
				var selectedSeats = new Array('.$selectedSeatsEntries.' "");
				var reservedSeats = new Array('.$reservedSeatsEntries.' "");
				var allocatedSeats = new Array('.$allocatedSeatsEntries.' "");
				var bookedSeats = new Array('.$bookedSeatsEntries.' "");
				var lockedSeats = new Array('.$lockedSeatsEntries.' "");
				var CantReserveSeatMessage = "'.$CantReserveSeatMessage.'";

				var SeatAltInvalid = "'.__("Invalid", 'stageshow').'";
				var SeatAltAvailable = "'.__("Available", 'stageshow').'";
				var SeatAltRequested = "'.__("Requested", 'stageshow').'";
				var SeatAltReserved = "'.__("Reserved", 'stageshow').'";
				var SeatAltAllocated = "'.__("Allocated", 'stageshow').'";
				var SeatAltBooked = "'.__("Booked", 'stageshow').'";
				var SeatAltLocked = "'.__("Locked", 'stageshow').'";
				var SeatAltDisabled = "'.__("Disabled", 'stageshow').'";
				
				var pageMode = "'.$pageMode.'";
				';
			
			$zonesDef = "var zones = {";
			foreach ($zonesCount as $zoneNo => $zoneCount)
			{
				$zonesDef .= "$zoneNo:$zoneCount, ";			
			}
			$zonesDef .= "0:0}; \n\n";			
			$jsCode .= $zonesDef;
				
			if (isset($zonesNames))
			{
				$zoneNameDefs = "var zonenames = {";
				foreach ($zonesNames as $zoneNo => $zoneRef)
				{
					$zoneNameDefs .= "$zoneNo:'$zoneRef', ";			
				}
				$zoneNameDefs .= "0:''}; \n\n";			
				$jsCode .= $zoneNameDefs;
			}			
			
			// For Seat Selector .... stageshow_OnSeatsLoad is called explicitly
			if ( ($pageMode == self::PAGEMODE_SHOWSEATS) 
			  || ($pageMode == self::PAGEMODE_LOCKOUTSEATS) 
			  || ($this->adminPageActive)
			  || !StageShowLibUtilsClass::IsElementSet('post', 'jquery') )
			{
				$jsCode .= "StageShowLib_addWindowsLoadHandler(stageshow_OnSeatsLoad); \n";
			}
				
				
			$this->InjectJSCode("<script>\n$jsCode\n</script>\n");
			
			StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" name="PerfId" id="PerfId" value="'.$perfId.'"/>'."\n");
			
			$showSelectedSeatsList = false;
			// $showSelectedSeatsList = $myDBaseObj->InTestMode();
			$hiddenTagType = $showSelectedSeatsList ? ' type="text" size=40 ' : ' type="hidden" ';
			$hiddenTagLine = $showSelectedSeatsList ? "<br>\n" : "\n";
			
			StageShowLibEscapingClass::Safe_EchoHTML('<input '.$hiddenTagType.' name="stageshow-seatselected-seats" id="stageshow-seatselected-seats" value=""/>'.$hiddenTagLine);
			StageShowLibEscapingClass::Safe_EchoHTML('<input '.$hiddenTagType.' name="stageshow-seatselected-zones" id="stageshow-seatselected-zones" value=""/>'.$hiddenTagLine);
			
			$seatLayoutName = $layoutDetails[0]->seatingRef;
			$seatTemplate = $myDBaseObj->AddLayoutWrapper($layoutDetails[0]->seatingTemplate);
			
			StageShowLibEscapingClass::Safe_EchoHTML($seatTemplate);

			if (isset($buttonType)) StageShowLibEscapingClass::Safe_EchoHTML('<input '.$buttonType.'/>');
//			StageShowLibEscapingClass::Safe_EchoHTML("</form>\n");
			
			return true;
		}
			
		function OutputContent_SelectSeats($perfId, $pageMode = self::PAGEMODE_SELECTSEATS)
		{
			$this->pageMode = $pageMode;

			// Remove any incomplete Checkouts
			$this->myDBaseObj->PurgePendingSales();
			
			if (!$this->OutputContent_ShowSeatsList($perfId, $pageMode))
			{		
				$perfDetails = $this->myDBaseObj->GetPricesListByPerfID($perfId, true);
				foreach ($perfDetails as $key => $perfDetail)
				{
					$perfDetails[$key]->showAddTickets = true;
				}
				$this->OutputContent_OnlineStoreSection($perfDetails);
			}
		}
			
		function OnlineStore_GetSortField($result)
		{
			$sortBy = parent::OnlineStore_GetSortField($result).'-';
			$sortBy .= (isset($result->ticketSeat)) ? $result->ticketSeat : ''; // 'noseat';
			$sortBy .= (isset($result->zoneID)) ? '-'.$result->zoneID : '';
				
			return $sortBy;
		}
		
		function OutputContent_OnlineTrolleyUserInterface()
		{
			if (isset($this->suppressUI)) return '';
			 
			static $firstTime = true;
			
			$uiOut = '';
			if ($this->myDBaseObj->disEnabled && $firstTime)
			{
				$discountsHeading = __('Discount Codes', 'stageshow');
				$prompt = __('Enter Discount Code', 'stageshow');
				$currCode = StageShowLibUtilsClass::GetHTTPTextElem('post', 'saleDiscountCode');

				$onDiscountChangeHandler = 'stageshow_DiscountChange';
				$onDiscountChangeCall = ''; //  onchange="return '.$onDiscountChangeHandler.'(this, '.$this->shortcodeCount.')"';				
				
				$buttonText = __('Update Prices', 'stageshow');
				$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'updateprices');
				$uiOut .= '
					<div class="stageshow-discountcode">
					<table class="stageshow-discountcode-table">
					<tr class="stageshow-discountcode-row">
					<td id="stageshow-trolley-discountcode-gap">&nbsp;</td>
					<td id="stageshow-trolley-discountcode-text">
					<span>'.$prompt.'</span></td>
					<td id="stageshow-trolley-discountcode-input">
					<input type="text" value="'.$currCode.'" '.$onDiscountChangeCall.' class="stageshow-trolley-ui" name="saleDiscountCode" id="saleDiscountCode" />
					</td>
					<td id="stageshow-trolley-discountcode-update">
					<input '.$buttonType.'/>					
					</td>
					</tr>
					</table>
					</div>
					';				
			}
			
			$uiOut .= parent::OutputContent_OnlineTrolleyUserInterface();

			$firstTime = false;		
			
			return $uiOut;	
		}
		
		function CompareTrolleyEntries($cartEntry1, $cartEntry2)
		{
			if ($this->HasAllocatedSeat($cartEntry1)) return false;
			
			return parent::CompareTrolleyEntries($cartEntry1, $cartEntry2);
		}
		
		function CheckForSoldOut(&$cartContents, $newCartEntry)
		{
			if (!isset($newCartEntry->seatingID) || ($newCartEntry->seatingID == 0))
			{
				return parent::CheckForSoldOut($cartContents, $newCartEntry);				
			}
			
			$zoneID = $newCartEntry->zoneID;
			if (!isset($cartContents->zoneqtys[$zoneID]))
				$cartContents->zoneqtys[$zoneID] = 0;
			
			$seatsAvailable = $this->GetOnlineStoreItemsAvailable($newCartEntry);
			if ($seatsAvailable < 0) return 0;	// No limit!
			
			$seatsAvailable -= ($cartContents->zoneqtys[$zoneID] + $newCartEntry->qty);
			if ($seatsAvailable < 0)
			{
				$newCartEntry->qty += $seatsAvailable;
				return abs($seatsAvailable);
			}
			
			return 0;
		}
		
		function AddDeletedCartEntryDetails(&$newCartEntry, $delCartEntry)
		{
			if ( ($newCartEntry->zoneID == $delCartEntry->zoneID) && isset($delCartEntry->seatLocns) )
			{
				$newCartEntry->seatLocns = $delCartEntry->seatLocns;
				$newCartEntry->seatNames = $delCartEntry->seatNames;
			}

			parent::AddDeletedCartEntryDetails($newCartEntry, $delCartEntry);
		}
		
		function AddToTrolleyContents(&$cartContents, $newCartEntry)
		{
			if ($newCartEntry->qty <= 0) return;
			
			if (isset($cartContents->rows))
			{
				foreach ($cartContents->rows as $cartindex => $cartEntry)
				{
					if ($cartEntry->showGatewayIndex != $newCartEntry->showGatewayIndex)
					{
						// All entries must have the same Payment Gateway
						return;
					}
				}				
			}
			
			if (isset($newCartEntry->seatingID) && ($newCartEntry->seatingID > 0))
			{
				$zoneID = $newCartEntry->zoneID;
				if (!isset($cartContents->zoneqtys[$zoneID]))
					$cartContents->zoneqtys[$zoneID] = $newCartEntry->qty;	
				else
					$cartContents->zoneqtys[$zoneID] += $newCartEntry->qty;	
			}
			
			if ($this->HasAllocatedSeat($newCartEntry)) 
			{
				// Trolley Entries with allocated seats must be one seat per row ...
				$entries = $newCartEntry->qty;
				$newCartEntry->qty = 1;

				while ($entries > 1)
				{
					parent::AddToTrolleyContents($cartContents, clone $newCartEntry);
					$entries--;
				}
			}
			
			parent::AddToTrolleyContents($cartContents, clone $newCartEntry);			
		}
		
		function RemoveFromTrolleyContents(&$cartContents, $itemID)
		{
			if (!isset($cartContents->rows[$itemID])) return;
			
			$cartEntry = $cartContents->rows[$itemID];
			if (isset($cartEntry->seatingID) && ($cartEntry->seatingID > 0))
			{
				$zoneID = $cartEntry->zoneID;
				$cartContents->zoneqtys[$zoneID] -= $cartEntry->qty;
			}
					
			parent::RemoveFromTrolleyContents($cartContents, $itemID);
		}
		
		function SaveInitialSaleEditTrolley(&$cartContents)
		{
			// Start the count of tickets in the zone at 0
			if (isset($cartContents->zoneqtys))
			{
				foreach ($cartContents->zoneqtys as $zoneID => $zoneQty)
				{
					$cartContents->zoneqtys[$zoneID] = 0;
				}				
			}
			
			parent::SaveInitialSaleEditTrolley($cartContents);
		}
		
		function HasSeatingZone($cartEntry)
		{			
			if ($cartEntry->zoneID <= 0)
			{
				return false;
			}
			
			return true;
		}
		
		function HasAllocatedSeat($cartEntry)
		{			
			if (!$this->HasSeatingZone($cartEntry))
			{
				return false;
			}
			
			if (!$cartEntry->zoneAllocSeats)
			{
				return false;
			}
			
			return true;
		}
		
		function AreSeatsDefined($cartEntry)
		{
			if (!$this->HasAllocatedSeat($cartEntry))
			{
				return true;
			}
					
			$seats = StageShowLibMigratePHPClass::Safe_trim($cartEntry->seatLocns);
			if ($seats != '')
			{
				$seats = explode(',', $seats);
				if (count($seats) == $cartEntry->priceNoOfSeats)
				{
					return true;
				}
			}
			
			return false;
		}
			
		function OutputContent_OnlineRemoveButton($cartIndex, $cartEntry)
		{
			$buttonCode = '';
			
			if ($this->adminPageActive && ($cartEntry->seatingID > 0))
			{
				$buttonName = 'editseats_'.$cartIndex;
				if ($cartEntry->seatLocns == '')
					$buttonText = __('Add Seat', 'stageshow');
				else
					$buttonText = __('Edit Seat', 'stageshow');
				$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'Edit', $buttonName, '', 'button-secondary');
				$buttonCode .= "<input $buttonType />\n";
			}
			
			$buttonCode .= parent::OutputContent_OnlineRemoveButton($cartIndex, $cartEntry);
			return $buttonCode;
		}
		
		function PassPostValueBack($itemId)
		{
			if (StageShowLibUtilsClass::IsElementSet('post', $itemId))
			{
				$postVal = StageShowLibUtilsClass::GetHTTPTextElem('post', $itemId);
				StageShowLibEscapingClass::Safe_EchoHTML('<input type="hidden" name="'.$itemId.'" id="'.$itemId.'" value="'.$postVal.'"/>'."\n");
			}
		}
		
		function OnlineStore_AddSeatDetails(&$cartContents)
		{
				$perfId = StageShowLibHTTPIO::GetRequestedInt('PerfId');
				
				// Loop round all Trolley Entries and clear seats for this performance
				foreach ($cartContents->rows as $cartindex => $cartEntry)
				{
					// Find Entries for this performance
					if ($perfId != $cartEntry->perfID)
						continue;

					$cartContents->rows[$cartindex]->seatLocns = '';
					$cartContents->rows[$cartindex]->seatNames = '';
				}
										
				$seats = StageShowLibUtilsClass::GetHTTPTextElem('post', 'stageshow-seatselected-seats');
				$zones = StageShowLibUtilsClass::GetHTTPTextElem('post', 'stageshow-seatselected-zones');

				while (StageShowLibMigratePHPClass::Safe_strstr($seats, '  '))
					$seats = StageShowLibMigratePHPClass::Safe_str_replace('  ', ' ', $seats);	
				while (StageShowLibMigratePHPClass::Safe_strstr($zones, '  '))
					$zones = StageShowLibMigratePHPClass::Safe_str_replace('  ', ' ', $zones);	
				$seats = StageShowLibMigratePHPClass::Safe_trim($seats);				
				$zones = StageShowLibMigratePHPClass::Safe_trim($zones);				
								
				$seatsList = explode(' ', $seats);
				$zonesList = explode(' ', $zones);
				
				$seatsLeftCount = count($seatsList);

				// Loop round all Trolley Entries
				foreach ($cartContents->rows as $cartindex => $cartEntry)
				{
					// Find Entries for this performance
					if ($perfId != $cartEntry->perfID)
					{
						$rows[$cartindex] = $cartEntry;
						continue;
					}

					// Find Matching Zones in Seats for Zones in Trolley
					$matchedSeatsInRow = 0;
					$seats = '';
					foreach ($zonesList as $seatsIndex => $zoneNo)
					{
						if ($zoneNo != $cartEntry->zoneID)
						{
							continue;
						}
						
						$matchedSeatsInRow++;
						if ($matchedSeatsInRow > 1)
						{
							$seats .= ',';
						}
						$seats .= $seatsList[$seatsIndex];
						$seatsLeftCount--;
						
						$cartContents->rows[$cartindex]->seatLocns = $seats;
						$cartContents->rows[$cartindex]->seatNames = StageShowZonesDBaseClass::DecodeSeatsList($this->myDBaseObj,  $seats, $cartEntry->seatingID);
						
						// Remove the matched item as it is matched
						$zonesList[$seatsIndex] = 0;
						$seatsList[$seatsIndex] = '';

						if ($matchedSeatsInRow == $cartEntry->priceNoOfSeats)
						{
							break;							
						}
					}
					
					if ($matchedSeatsInRow == 0)
					{
						$cartContents->rows[$cartindex]->seatLocns = '';
					}
				}

				if ($seatsLeftCount > 0)
				{
					$this->checkoutMsg = __('Seat/Zone Mismatch', 'stageshow');
				}

				$this->SaveTrolleyContents($cartContents, __LINE__);
				
/*				
				{
					// TODO - Allocated Seating - Deal with incorrect total seats
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Incorrect Number of Seats Selected', 'stageshow').'</p></div>');
					$this->pageMode = self::PAGEMODE_SELECTTICKETS;
					
					return;
				}
*/				
		}
		
		function GetTrolleyDefaults()
		{
			$cartDefaults = parent::GetTrolleyDefaults();
			$cartDefaults->disCode = '';
			
			return $cartDefaults;
		}
		
		function TrolleyToSale($cartIndex, $cartEntry, $lineNo)
		{
			$saleEntry = $this->myDBaseObj->GetPricesListByPriceID($cartEntry->itemID);
			if (count($saleEntry) == 0) return null;
				
			$saleEntry[0]->ticketSeat = $cartEntry->seatLocns;				
			$saleEntry[0]->cartIndex = $cartIndex;				
			$saleEntry[0]->lineNo = $lineNo;				

			return $saleEntry[0];
		}
		
		function OnlineStore_GetPriceType($result)
		{
			$priceType = parent::OnlineStore_GetPriceType($result);
			
			if (isset($result->zoneRef))
			{
				$priceType .= '<span class=stageshow_zoneref> ('.$result->zoneRef.')</span>';
			}
			return $priceType;
		}
		
		function Cart_OutputContent_Boxoffice_Preload($atts, $shows, $pass = 1)
		{
			if (!$this->usingDrillDown) return;

			if ($this->adminPageActive && $this->myDBaseObj->IsButtonClicked('editbuyer')) return;

			$dateFormat = defined('STAGESHOWLIB_DATETIME_DRILLDOWN_FORMAT') ? STAGESHOWLIB_DATETIME_DRILLDOWN_FORMAT : '';

			$myDBaseObj = $this->myDBaseObj;
			$selbuttonId = 'stageshow-selbutton-';
			$boxOfficeSelector = '';
			
			$buttonBaseClass = $this->adminPageActive ? 'button-primary ' : '';
			
			switch ($pass)
			{
				case 1:
					$firstLine = true;
					$boxOfficeSelector .= '<span id="stageshow-selector-header"></span>'."\n";
					$boxOfficeSelector .= '<table id="stageshow-selector-table" >'."\n";	
					foreach ( $shows as $show )
					{
						$showButtonId = $selbuttonId."show-".$show->showID;
						$buttonClass = $buttonBaseClass."stageshow-selector-showbutton";
						$buttonText = __('Select', 'stageshow');				
						$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'selectshow', $showButtonId, 'button', $buttonClass);				
						
						if (!$firstLine)
						{
							$boxOfficeSelector .= '<tr>'."\n";
							$boxOfficeSelector .= '<td colspan=2 class="stageshow-selector-separator">&nbsp;</td>'."\n";
							$boxOfficeSelector .= '</tr>'."\n";					
						}
						
						$boxOfficeSelector .= '<tr class="stageshow-selector-showrow">'."\n";
						$boxOfficeSelector .= '<td class="stageshow-selector-showname"><h2>'.$show->showName.'</h2></td>'."\n";
						$boxOfficeSelector .= '<td><input '.$buttonType.'></td>'."\n";
						$boxOfficeSelector .= '</tr>'."\n";
						
						$perfs = $myDBaseObj->GetActivePerformances($show->showID, $this->adminPageActive);
						foreach ( $perfs as $perf )
						{
							$buttonId = $selbuttonId."perf-".$perf->showID."-".$perf->perfID;
							$buttonClass = $buttonBaseClass."stageshow-selector-perfbutton";
							$rowClass = "stageshow-selector-perfrow stageshow-selector-perfrow-".$perf->showID;
							$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'selectperf', $buttonId, 'button', $buttonClass);				
						
							$boxOfficeSelector .= '<tr class="'.$rowClass.'" style="display: none;" >'."\n";
							$boxOfficeSelector .= '<td class="stageshow-selector-datetime">'.$myDBaseObj->FormatDateForDisplay($perf->perfDateTime, $dateFormat).'</td>'."\n";
							$boxOfficeSelector .= '<td><input '.$buttonType.'></td>'."\n";
							$boxOfficeSelector .= '</tr>'."\n";
						}	
						
						$firstLine = false;
					}
					$boxOfficeSelector .= '</table>'."\n";
					$boxOfficeSelector .= '<span id="stageshow-selector-footer"></span>'."\n";
					StageShowLibEscapingClass::Safe_EchoScript('
						<script>
							jQuery(document).ready(
								function()
								{
									stageshow_DrilldownLoaded();
								}
							);
						</script>');
					
					break;
					
				case 2:
					$divId = $selbuttonId."back-div";
					$buttonId = $selbuttonId."back";
					$buttonClass = $buttonBaseClass."stageshow-selector-backbutton";
					$buttonText = __('Back', 'stageshow');	
					$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'selectorback', $buttonId, 'button', $buttonClass);				
					$buttonStyle = ' style="display: none" ';			
					$boxOfficeSelector .= '<div id='.$divId.$buttonStyle.'>'."\n";
					$boxOfficeSelector .= "<input $buttonType >\n";
					$boxOfficeSelector .= "</div>\n";
					break;
					
				default:
					break;
			}
			
			
			StageShowLibEscapingClass::Safe_EchoHTML($boxOfficeSelector);				
		}

		function OutputContent_OnlineStoreHeader($result)
		{			
			parent::OutputContent_OnlineStoreHeader($result);
		}
		
		function OnlineStore_AddExtraFields(&$cartContents, $detailsTemplate, $cartIndex = 0)
		{
				$UnusedSubject = '';
				
				// All empty fields for entries determined by template
				$noOfFields = preg_match_all('/id=(stageshow_customcoItem_\w+)/', $detailsTemplate, $matches);
				if ($noOfFields > 0)
				{
					$cartContents->extraFields = array();
					$keyPrefixLen = StageShowLibMigratePHPClass::Safe_strlen("stageshow_customcoItem_");
					foreach ($matches[1] as $index => $fieldId)
					{
						$cartContents->extraFields[$index] = $fieldId;
						if ($cartIndex > 0)
						{
							$cartEntry = $cartContents->rows[$cartIndex];
							
							$dbfieldID = StageShowLibMigratePHPClass::Safe_substr($fieldId, $keyPrefixLen);
							$fieldValue = '';
							if (isset($cartEntry->customFields) && isset($cartEntry->customFields[$dbfieldID]))
							{
								$fieldValue = $cartEntry->customFields[$dbfieldID];
							}

							if (!isset($cartContents->scriptCode)) $cartContents->scriptCode = "var stageshow_customco_vals = [];\n";
							$cartContents->scriptCode .= "stageshow_customco_vals.$fieldId$cartIndex = '$fieldValue';\n";
						}
					}
		
					$this->SaveTrolleyContents($cartContents, __LINE__);
				}
		}		
		function Cart_OnlineStore_GetCheckoutDetails()
		{
			$myDBaseObj = $this->myDBaseObj;

			$buttonClicked = '';
			
			if ( $myDBaseObj->IsButtonClicked('checkoutloadform') )
			{
				$buttonClicked = 'checkoutloadform';
			}
			else if ( $myDBaseObj->IsButtonClicked('reserveform') )
			{
				$buttonClicked = 'reserveform';
			}
			else if ( $myDBaseObj->IsButtonClicked('editsaleform') )
			{
				$buttonClicked = 'editsaleform';
			}
			
			if ($buttonClicked == '')	// Get checkout details from user
			{
				return parent::Cart_OnlineStore_GetCheckoutDetails();
			}

			$cartContents = $this->GetTrolleyContents();
			if (!isset($cartContents->rows))
			{
				return parent::Cart_OnlineStore_GetCheckoutDetails();
			}
			
			$html  = "
<script>
StageShowLib_addWindowsLoadHandler(stageshow_OnLoadCheckoutDetails); 
</script>
<input type=hidden id=checkoutform-submit name=checkoutform-submit value=true />
				";	
				
			foreach (array_keys($_POST) as $postKey)
			{
				$postValue = StageShowLibUtilsClass::GetHTTPTextElem('post', $postKey);
				switch ($postKey)
				{
					case '_wpnonce':
					case 'checkoutform':
					case 'reserveform':
					case 'editsaleform':
					case 'checkoutform-submit':
					case 'reserveform-submit':
						break;
						
					default:
						$html .= "<input type=hidden id=$postKey name=$postKey value=\"$postValue\" />\n";					
						break;
				}
			}

			$detailsForm = '';
			$customValsList = '';
			
			$ckoutDetailsTemplate = '';
			$lineNo = 0;
			foreach ($cartContents->rows as $cartIndex => $cartEntry)
			{
				if ($cartEntry->ckoutDetailsTemplate == '') continue;
				
				$lineNo++;
				
				if ($cartEntry->ckoutDetailsTemplate != $ckoutDetailsTemplate)
				{
					$templatePath = STAGESHOWLIB_UPLOADS_PATH.'/forms/'.$cartEntry->ckoutDetailsTemplate;
					$detailsTemplate = $myDBaseObj->ReadTemplateFile($templatePath);
				}
												
				$JSCodeIndex = ($buttonClicked == 'editsaleform') ? $cartIndex : 0;
				$this->OnlineStore_AddExtraFields($cartContents, $detailsTemplate, $JSCodeIndex);

				$salesList = array();			
				$salesList[] = $this->TrolleyToSale($cartIndex, $cartEntry, $lineNo);
	
				$rtnStatus = $myDBaseObj->AddFieldsToTemplate($salesList, $detailsTemplate, $UnusedSubject, $detailsForm);	
				
				$html .= $detailsForm;
			}
				
			if (isset($cartContents->scriptCode))
			{
				$this->InjectJSCode("<script>\n ".$cartContents->scriptCode." \n</script>\n");
			}			
			
			// Add status message box (hidden)
			$html .= "<div class=CheckoutFormStatus id=CheckoutFormStatus name=CheckoutFormStatus>Status Goes Here</div>\n";					
		
			switch ($buttonClicked)
			{
				case 'reserveform':
					$buttonText = __('Reserve', 'stageshow');
					$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'reserve');
					$buttonType = StageShowLibMigratePHPClass::Safe_str_replace('_OnClickReserve', '_OnClickCheckoutForm', $buttonType);
					$html .= "<input $buttonType />\n";			
					break;
					
				case 'checkoutloadform':
				case 'checkoutform':
					$buttonText = __('Checkout', 'stageshow');
					$buttonType = $myDBaseObj->gatewayObj->GetTrolleyButtonsHTML($this, $cartContents);
					$buttonType = StageShowLibMigratePHPClass::Safe_str_replace('_OnClickCheckout', '_OnClickCheckoutForm', $buttonType);
					$html .= $buttonType;			
					break;
					
				case 'editsaleform':
					$buttonText = __('Next', 'stageshow');
					$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'editbuyer');
					$buttonType = StageShowLibMigratePHPClass::Safe_str_replace('_OnClickCheckout', '_OnClickCheckoutForm', $buttonType);
					$html .= "<input $buttonType />\n";			
					break;
			}			
			
			StageShowLibEscapingClass::Safe_EchoHTML($html);
			return $html;		
		}
		
		function GetBoxOfficeRowClass($result)
		{
			$rowClass = parent::GetBoxOfficeRowClass($result);
			if ($this->usingDrillDown)	
			{
				$rowClass .= ' stageshow-boxoffice-row-perf'.$result->perfID;
				//$rowClass .= ' stageshow-boxoffice-row-hidden';
			}
			
			$priceType = $result->priceType;
			$priceType = StageShowLibMigratePHPClass::Safe_str_replace(' ', '_', $priceType);
			$priceType = StageShowLibMigratePHPClass::Safe_str_replace('+', '_', $priceType);
			
			$rowClass .= ' stageshow-boxoffice-row-'.$priceType;
			return $rowClass;
		}
		
		function OutputContent_OnlineStoreSection( $results )
		{
			if (count($results) > 0)
			{
		  		$lastIndex = count($results)-1;
		  		$showButton = $this->myDBaseObj->isOptionSet('AvailableSeatsButton') && !$this->adminPageActive;
		  		for ($index=0; $index<=$lastIndex; $index++)
				{
					if ($results[$index]->seatingID == 0)
						continue;						
					
					if ($showButton)					
					{
						if (($index == 0) || ($results[$index]->perfID != $results[$index-1]->perfID))
						{							
							$perfID = $results[$index]->perfID;
							$buttonText = __('Show Available Seats', 'stageshow');
							$buttonName = 'seatsavailable_'.$perfID;				
							$buttonType = $this->GetButtonTextAndTypeDef($buttonText, 'seatsavailable', $buttonName, '', 'stageshow-trolley-seatsavailable-button');				
							$buttonHTML = "<input $buttonType >";						

							$results[$index]->perfRowHTML = "<td colspan=4 class=stageshow-boxoffice-seatslink>$buttonHTML</td></tr><tr><td class=stageshow-boxoffice-datetime>&nbsp;</td>";
						}						
					}
					
					if ($index>=$lastIndex)
					{
						$results[$index]->showAvailable = true;
						break;
					}
						
					if ($results[$index]->perfID != $results[$index+1]->perfID)
						continue;
						
					if ($results[$index]->zoneID == $results[$index+1]->zoneID)
						continue;
					
					$results[$index]->showAvailable = true;
				}
			}
			
			if ($this->usingDrillDown)	
			{
				$divClass = $this->cssBaseID.'-body';
				$divId = $divClass.'-'.$results[0]->showID;
				$divStyle = 'style="display: none;" ';
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="'.$divId.'" name="'.$divId.'" class="'.$divClass.'" '.$divStyle.'>'."\n");
			}
			
			parent::OutputContent_OnlineStoreSection( $results );
			
			if ($this->usingDrillDown)	
			{
				StageShowLibEscapingClass::Safe_EchoHTML("</div>\n");
			}
		}
		
		function GetOnlineStoreItemPrice($result)
		{
			if (isset($result->disValue) && is_numeric($result->disValue))
			{
				return $result->disValue;
			}
			
			return $result->priceValue;
		}
			
		function GetOnlineStoreInventoryID($result)
		{
			$invID = $result->perfID;
			
			if ($result->seatingID > 0)
			{
				$invID .= '-'.$result->zoneID;
			}
			
			return $invID;
		}
			
		function GetOnlineStoreDateClass($result)
		{
			$dateTimeClass = '';
			if ($result->showDateTimeMode == STAGESHOW_DATETIMEOPTION_NONE)
				$dateTimeClass .= ' stageshow-boxoffice-hidden';				
			return $dateTimeClass;
		}
			
		function IsOnlineStoreQtyHidden($result)
		{
			return $result->showHideQtyColumn;
		}
			
		function GetOnlineStoreQtyClass($result)
		{
			$qtyClass = '';
			if ($result->showHideQtyColumn)
				$qtyClass .= ' stageshow-boxoffice-hidden';				
			return $qtyClass;
		}
			
		function GetOnlineStoreItemsAvailable($result)
		{
			if (!isset($result->seatingID) || ($result->seatingID == 0))
			{
				return parent::GetOnlineStoreItemsAvailable($result);
			}
			
			static $lastPerfID = 0;
			static $lastZoneID = 0;
			static $itemsAvailable = 0;
			
			if (($lastPerfID != $result->perfID) || ($lastZoneID != $result->zoneID))
			{
				$salesSummary = $this->myDBaseObj->GetPerformanceSummaryByPerfAndZoneID($result->perfID, $result->zoneID);
				$itemsAvailable = $result->zoneSeats - $salesSummary->totalQty - $salesSummary->lockedQty;
				
				$lastPerfID = $result->perfID;
				$lastZoneID = $result->zoneID;
			}
						
			return $itemsAvailable;
		}
					
		function IsOnlineStoreItemValid(&$cartEntry, $saleEntries)
		{
			if (!$this->HasSeatingZone($cartEntry))
			{
				return parent::IsOnlineStoreItemValid($cartEntry, $saleEntries);
			}

			if (!$this->HasAllocatedSeat($cartEntry))
			{
				// Check for Availability of Seats in Unallocated Seating Zones
				// Test if this item is valid (i.e. Available))
				static $firstPass = true;
				$myDBaseObj = $this->myDBaseObj;
				
				if ($firstPass)
				{		
					// Just do this on the first call
					$firstPass = false;
					
					foreach ($saleEntries as $saleEntry)
					{
						$perfID = $cartEntry->perfID;
						$zoneID = $saleEntry->zoneID;
						
						if (!isset($this->zonedSeatsAvail[$zoneID]))
						{
							// Get the maximum number of seats 
							$this->zonedSeatsAvail[$zoneID] = $saleEntry->zoneSeats;	
							if ($this->zonedSeatsAvail[$zoneID] < 0) continue;
							
							// Deduct the total number of seats sold for this performance	
							$salesSummary = $myDBaseObj->GetPerformanceSummaryByPerfAndZoneID($perfID, $zoneID);
							$this->zonedSeatsAvail[$zoneID] -= $salesSummary->totalQty;				
						}
						
						if ($this->zonedSeatsAvail[$zoneID] >= 0)
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
									$this->zonedSeatsAvail[$zoneID] += $qty;
									break;
							}
						}						
					}
				}

				$qty = isset($cartEntry->priceNoOfSeats) ? $cartEntry->qty * $cartEntry->priceNoOfSeats : $cartEntry->qty;						
				$perfID = $cartEntry->perfID;
				$zoneID = $cartEntry->zoneID;
				
				if (!isset($this->zonedSeatsAvail[$zoneID]))
				{
					// This performance has been added to the sale
					$salesSummary = $myDBaseObj->GetPerformanceSummaryByPerfAndZoneID($perfID, $zoneID);
						
					// Get the maximum number of seats 
					$this->zonedSeatsAvail[$zoneID] = $cartEntry->zoneSeats;	
					if ($this->zonedSeatsAvail[$zoneID] > 0)
					{
						// Deduct the total number of seats sold for this performance	
						$this->zonedSeatsAvail[$zoneID] -= $salesSummary->totalQty;				
					}
				}
			
				if ($this->zonedSeatsAvail[$zoneID] < 0)
					return true;
					
				if ($this->zonedSeatsAvail[$zoneID] < $qty)
				{
					$this->zonedSeatsAvail[$zoneID] = 0;
					$salesSummary = $myDBaseObj->GetPerformanceSummaryByPerfAndZoneID($perfID, $zoneID);
					$perfDateTime = $this->myDBaseObj->FormatDateForDisplay($salesSummary->perfDateTime);
					$this->checkoutMsg = __('Insufficient seats', 'stageshow').' - ('.$salesSummary->showName.' '.$perfDateTime.')';
					return false;
				}
					
				$this->zonedSeatsAvail[$zoneID] -= $qty;
				
				return true;
			}
			
			$itemsValid = true;
			$seatsInCart = explode(',', $cartEntry->seatLocns);
			
			foreach ($seatsInCart as $seatInCart)
			{
				$seatOK = false;
				
				// Loop though current tickets list for this sale
				foreach ($saleEntries as $priceEntry)
				{
					if ($priceEntry->zoneID != $cartEntry->zoneID)
						continue;
					
					$saleSeats = explode(',', $priceEntry->ticketSeat);					
					foreach ($saleSeats as $saleSeat)
					{
						if ($saleSeat == $seatInCart)
						{
							// Trolley entry is already in Sale ... ignore it
							$seatOK = true;
//StageShowLibEscapingClass::Safe_EchoHTML("Trolley entry is already in Sale - ".$priceEntry->showName.'-'.$priceEntry->perfDateTime.' ('.$priceEntry->ticketSeat.")<br>\n");
							break;
						}
						
					}
					
					if ($seatOK) break;
				}
				
				if (!$seatOK)
				{
					// This seat has been added - Check that it is available
					if (!$this->myDBaseObj->IsSeatAvailable($cartEntry->perfID, $seatInCart))
					{
	//StageShowLibEscapingClass::Safe_EchoHTML("SeatNOT Available - PerfID:".$cartEntry->perfID.' Seat:'.$priceEntry->ticketSeat."<br>\n");
						$this->checkoutMsg = __('One or more seats selected is not available', 'stageshow');
						$itemsValid = false;
						
						$cartEntry->seatLocns = '';
						$cartEntry->seatNames = '';
					}
	//StageShowLibEscapingClass::Safe_EchoHTML("Entry added to Sale - PerfID:".$cartEntry->perfID.' Seat:'.$priceEntry->ticketSeat."<br>\n");
				}
			}			
			
			return $itemsValid;
		}
			
		function OutputContent_OnlineTrolleyDetailsCols($priceEntry, $cartEntry)
		{
			if (isset($cartEntry->zoneID) && $this->HasAllocatedSeat($cartEntry))
			{
				StageShowLibEscapingClass::Safe_EchoHTML('<td class="'.$this->cssTrolleyBaseID.'-'.$this->cssColID['cartqty'].'">'.StageShowZonesDBaseClass::SeatsToHTML($cartEntry->seatNames).'</td>'."\n");
				return;
			}

			parent::OutputContent_OnlineTrolleyDetailsCols($priceEntry, $cartEntry);
		}
		
	}
}




