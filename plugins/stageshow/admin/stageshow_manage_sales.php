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

include STAGESHOW_INCLUDE_PATH.'stageshow_sales_table.php';

if (!class_exists('StageShowSalesAdminClass')) 
{
	class StageShowSalesAdminClass extends StageShowLibGatewaySalesAdminClass // Define class
	{		
		function __construct($env) //constructor
		{
			$env['saleQtyInputID'] = 'ticketQty';
			parent::__construct($env);
		}
		
		function NoStockMessage()
		{
			$perfsPageURL = get_option('siteurl').'/wp-admin/admin.php?page='.STAGESHOW_MENUPAGE_PRICES;
			$perfsPageMsg = __('No Prices Defined', 'stageshow').' - <a href='.$perfsPageURL.'>'.__('Add one Here', 'stageshow').'</a>';
			$perfsPageMsg = "<div class='error'><p>$perfsPageMsg</p></div>";
			return $perfsPageMsg;
		}
		
		function OuputAddSaleButton()
		{
			if ($this->myDBaseObj->CanEditSales())
			{
				parent::OuputAddSaleButton();
			}
		}
		
		function Output_MainPage($updateFailed)
		{
			return parent::Output_MainPage($updateFailed);
		}
		
		function ProcessActionButtons()
		{
			if ( StageShowLibUtilsClass::IsElementSet('get', 'action')
			  && !StageShowLibUtilsClass::IsElementSet('get', '_wpnonce')
			  && !StageShowLibUtilsClass::IsElementSet('request', 'editpage')
			  && ($_GET['action'] == 'editsale') )
			{
				// For "Add Sale" button - Fake _wpnonce attribute
				$baseName = plugin_basename($this->caller);
				$nonceVal = wp_create_nonce($baseName);
				$_GET['_wpnonce']=$_REQUEST['_wpnonce']=$nonceVal;
			}
			
			parent::ProcessActionButtons();
		}
		
		function DoActions()
		{
			$rtnVal = false;

			switch ($_GET['action'])
			{
				case 'printsale':
					$pluginObj = $this->env['PluginObj'];
					$saleId = StageShowLibUtilsClass::GetHTTPInteger('get', 'id'); 
					$this->myDBaseObj->PrintSale($saleId);
					break;
															
				case 'editsale':
					$pluginObj = $this->env['PluginObj'];
					$this->saleId = StageShowLibUtilsClass::GetHTTPInteger('get', 'id');   
					$pluginObj->SetTrolleyID($this->saleId);
					$pluginObj->editpage = StageShowLibUtilsClass::GetHTTPTextElem($_REQUEST, 'editpage', 'start');   

					$this->editingRecord = true;	// Set this flag to show that we are editing a Sale entry
					
					switch($pluginObj->editpage)
					{
						case 'start':
							// Initialise values to start editing a sale							
							$pluginObj->editpage = 'tickets';
							$pluginObj->ClearTrolleyContents();
							if ($this->saleId == '') break;
							$cartContents = $pluginObj->GetTrolleyContents();					

							$saleEntries = $this->myDBaseObj->GetSale($this->saleId);
							$cartContents->saleID = $this->saleId;
							
							if (count($saleEntries) > 0)
							{
								$cartContents->saleEMail          = $saleEntries[0]->saleEMail;
								$cartContents->saleFirstName      = $saleEntries[0]->saleFirstName;
								$cartContents->saleLastName       = $saleEntries[0]->saleLastName;
								$cartContents->salePPStreet       = $saleEntries[0]->salePPStreet;
								$cartContents->salePPCity         = $saleEntries[0]->salePPCity;								
								$cartContents->salePPState        = $saleEntries[0]->salePPState;
								$cartContents->salePPZip          = $saleEntries[0]->salePPZip;
								$cartContents->salePPCountry      = $saleEntries[0]->salePPCountry;
								$cartContents->salePPPhone        = $saleEntries[0]->salePPPhone;								
								$cartContents->saleStatus         = $saleEntries[0]->saleStatus;								
								$cartContents->saleNoteToSeller   = $saleEntries[0]->saleNoteToSeller;								
								$cartContents->saleDonation       = $saleEntries[0]->saleDonation;								
								$cartContents->salePostage        = $saleEntries[0]->salePostage;								
								$cartContents->saleExtraDiscount  = $saleEntries[0]->saleExtraDiscount;
								$cartContents->saleTransactionFee = $saleEntries[0]->saleTransactionFee;
								
								$cartContents->saleMethod         = $saleEntries[0]->saleMethod;			
								$cartContents->salePostTickets    = ($cartContents->salePostage > 0);
							}
							else
							{
								$cartContents->saleMethod         = '';			
								$cartContents->saleStatus    = PAYMENT_API_SALESTATUS_COMPLETED;								
							}
												
							foreach ($saleEntries as $priceEntry)
							{
								$cartEntry = new stdClass;
								$cartEntry->itemID = $priceEntry->priceID;
								$cartEntry->qty = $priceEntry->ticketQty;
								$cartEntry->price = $pluginObj->GetOnlineStoreItemPrice($priceEntry);
								
								$pluginObj->OnlineStore_AddTrolleyExtras($cartEntry, $priceEntry);
								$cartEntry->sortBy = $pluginObj->OnlineStore_GetSortField($priceEntry);
								
								$pluginObj->AddToTrolleyContents($cartContents, $cartEntry);
							}

							$pluginObj->SaveInitialSaleEditTrolley($cartContents);
							$rtnVal = true;
							break;
							
						case 'tickets':
							//$rtnVal = $this->StageShowSalesAdminClass_DoActions();
							break;
					}
					break;
										
				case 'addpayment':
					$rtnVal = $this->AddPaymentHandler();
					$this->donePage = true;
					break;
				
				case 'show':
					// FUNCTIONALITY: Sales - Lists Sales for a Show
					// List Sales for Show
					$showID = StageShowLibUtilsClass::GetHTTPInteger('get', 'id');   
					$showEntry = $this->myDBaseObj->GetShowsList($showID);
					if (count($showEntry) == 0)
					{
						// Invalid showID ... bail out!
						break;
					}
					$this->salesFor = $showEntry[0]->showName.' - ';
					$rtnVal = true;
					break;
						
				case 'perf':
					// FUNCTIONALITY: Sales - Lists Sales for a Performance
					// List Sales for Performance
					$perfID = StageShowLibUtilsClass::GetHTTPInteger('get', 'id');   
					$perfEntry = $this->myDBaseObj->GetPerformancesListByPerfID($perfID);
					if (count($perfEntry) == 0)
					{
						// Invalid perfID ... bail out!
						break;
					}
					$this->salesFor = $perfEntry[0]->showName.' ('.$this->myDBaseObj->FormatDateForDisplay($perfEntry[0]->perfDateTime).') - ';
					$rtnVal = true;
					break;
					
				default:
					$rtnVal = parent::DoActions();
					break;
					
			}
				
			return $rtnVal;
		}
		
		function CanChangeState($prevSaleStatus, $newSaleStatus)
		{
			if ( ($prevSaleStatus == PAYMENT_API_SALESTATUS_RESERVED) 
			  && ($newSaleStatus  == PAYMENT_API_SALESTATUS_COMPLETED) )
			  	return true;
			  	
			if ($newSaleStatus  == PAYMENT_API_SALESTATUS_COMPLETED)
			{
				if ($prevSaleStatus == PAYMENT_API_SALESTATUS_CHECKOUT) 
			  		return true;
				if ($prevSaleStatus == PAYMENT_API_SALESTATUS_UNVERIFIED) 
			  		return true;
			  	return false;				
			}
			  	
			return false;
		}

		function DoBulkAction($bulkAction, $recordId)
		{
			switch ($bulkAction)
			{
				case StageShowSalesAdminListClass::BULKACTION_COMPLETED:	
					$saleResults = $this->myDBaseObj->GetSale($recordId);
					$saleEntry = $saleResults[0];
					
					// Check record can be changed to COMPLETED
					$prevSaleStatus = $saleEntry->saleStatus;
					
					// Get the sale entry
					if (!$this->CanChangeState($prevSaleStatus, PAYMENT_API_SALESTATUS_COMPLETED))
						return false;
						
					// Change saleStatus to Completed	
					$this->myDBaseObj->UpdateSaleIDStatus($recordId, PAYMENT_API_SALESTATUS_COMPLETED);
					if ($prevSaleStatus == PAYMENT_API_SALESTATUS_UNVERIFIED)
					{
						// Previously Unverified Sale is confirmed - Send EMail to Purchaser
						$this->myDBaseObj->EMailSale($saleEntry->saleID);
					}
					return true;
			}
				
			return parent::DoBulkAction($bulkAction, $recordId);
		}
		
		function AddPaymentHandler()
		{
			if (!StageShowLibUtilsClass::IsElementSet('get', 'id')) return;
			
			$myDBaseObj = $this->myDBaseObj;
			$html = '';
			
			$this->saleId = StageShowLibUtilsClass::GetHTTPInteger('get', 'id');   
			
			$saleTotalsList = $myDBaseObj->GetSaleTotals($this->saleId);
			$saleTotals = $saleTotalsList[0];
			$saleBalance = $saleTotals->soldValue - $saleTotals->salePaid;
				
			if (StageShowLibUtilsClass::IsElementSet('post', 'savepayment'))
			{
				$paymentDateTime = StageShowLibHTTPIO::GetRequestedString('paymentDateTime');
				$paymentPaid = StageShowLibHTTPIO::GetRequestedCurrency('paymentPaid');
				$paymentMethod = StageShowLibHTTPIO::GetRequestedString('paymentMethod');
			
				$saleBalance -= $paymentPaid;
				if ($saleBalance < 0)
				{
					// Overpaid! .... Reject Payment 
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Overpayment! Payment has not been added.', 'stageshow').'</p></div>');
				}
				else
				{
					$myDBaseObj->AddPayment($this->saleId, $paymentDateTime, $paymentPaid, $paymentMethod);
					
					if ($saleBalance == 0)
					{
						$myDBaseObj->UpdateSaleIDStatus($this->saleId, PAYMENT_API_SALESTATUS_COMPLETED);
					}
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="update"><p>'.__('Payment added.', 'stageshow').'</p></div>');
					$html .= $myDBaseObj->ActionButtonHTML(__('Back to Sales', 'stageshow'), $this->caller, 'stageshow', '', 0, '');
				}
			}
			else
			{
				$paymentDateTime = current_time('mysql'); 
				$buttonClassdef = $this->myPluginObj->GetButtonTextAndTypeDef(__('Add Payment', 'stageshow'), 'savepayment', '', '', 'button-secondary');
				$paymentMethodSelect = $this->myPluginObj->OutputContent_PurchaseMethodSelector('paymentMethod');
				$html .= '
<form method="post">
<table>
	<tr><td>'.__('Date', 'stageshow').'</td><td><input id=paymentDateTime name=paymentDateTime value="'.$paymentDateTime.'"></td></tr>
	<tr><td>'.__('Payment', 'stageshow').'</td><td><input id=paymentPaid name=paymentPaid value='.$saleBalance.'></td></tr>
	<tr><td>'.__('Method', 'stageshow').'</td><td>'.$paymentMethodSelect.'</td></tr>
	<tr><td colspan=2>'."<input $buttonClassdef />".'</td></tr>
</table>
</form>
				';
			}
			
			StageShowLibEscapingClass::Safe_EchoHTML($html);
			
			return true;
		}
		
		function GetBulkActionMsg($bulkAction, $actionCount)
		{
			$actionMsg = '';
			
			switch ($bulkAction)
			{
				case StageShowSalesAdminListClass::BULKACTION_COMPLETED:		
					if ($actionCount > 0)		
						$actionMsg = $actionCount . ' ' . _n("Sale has been changed to Completed", "Sales have been changed to Completed", $actionCount, 'stageshow');
					else
						$actionMsg =  __("Nothing to Update", 'stageshow');
					break;
					
				default:
					$actionMsg = parent::GetBulkActionMsg($bulkAction, $actionCount);

			}
			
			return $actionMsg;
		}
		
		// Commented out Class Def (StageShowSalesAdminClass)

	}
}



